<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

namespace local_bftranslate;

use curl;
use local_bftranslate\local\exception\translateerror;

/**
 * Handles the translations through the DeepL API.
 *
 * @package    local_bftranslate
 * @author     Karen Holland <karen@brickfieldlabs.ie>
 * @copyright  2025 onward Brickfield Education Labs Ltd, https://www.brickfield.ie
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class deepl_translator {
    /** @var string DeepL API key. */
    private $apikey;

    /** @var string DeepL API URL. */
    private $apiurl = 'https://api.deepl.com/v2/translate';

    /** @var string DeepL Free API URL. */
    private $freeapiurl = 'https://api-free.deepl.com/v2/translate';

    /**
     * Constructor.
     *
     * @param string $apikey
     */
    public function __construct(string $apikey) {
        $this->apikey = $apikey;
    }

    /**
     * Handles the batch translation.
     *
     * @param array $strings
     * @param string $targetlang
     * @return array [$translations, $errors]
     */
    public function translate_batch(array $strings, string $targetlang): array {
        $translations = [];
        $errors = [];

        foreach ($strings as $key => $text) {
            // Skip empty strings, but still add to array for display in the table.
            if ($text == '') {
                $translations[$key] = '';
                continue;
            }
            try {
                $translatedtext = $this->translate($text, $targetlang);
                if ($translatedtext) {
                    $translations[$key] = $translatedtext;
                }
            } catch (translateerror $e) {
                $errors[$key] = $e->getMessage();
            }
        }
        return [$translations, $errors];
    }

    /**
     * Wrap placeholders with DeepL's notranslate tag.
     *
     * @param string $text
     * @return string|null
     */
    private function protect_placeholders_for_deepl(string $text): ?string {
        return preg_replace('/(\{\$a->[^}]+\})/', '<x>$1</x>', $text);
    }

    /**
     * Remove DeepL's notranslate tags from placeholders.
     *
     * @param string $translatedtext
     * @return string|null
     */
    private function remove_notranslate_tags(string $translatedtext): ?string {
        return preg_replace('/<x>(.*?)<\/x>/', '$1', $translatedtext);
    }

    /**
     * Translate the requested strings using the API.
     *
     * @param string $text
     * @param string $targetlang
     * @return string|null
     * @throws \local_bftranslate\local\exception\translateerror On an API error response.
     */
    private function translate(string $text, string $targetlang): ?string {
        $text = $this->protect_placeholders_for_deepl($text);
        $postdata = [
            'text' => [$text],
            'target_lang' => strtoupper($targetlang),
            'tag_handling' => 'xml',
            'ignore_tags' => ['x'],
        ];
        $string = json_encode($postdata);

        $curl = new curl();
        $curl->setopt(['CURLOPT_RETURNTRANSFER' => true]);
        $curl->setopt(['CURLOPT_POST' => true]);
        $curl->setopt(['CURLOPT_TIMEOUT' => 30]);
        $authstring = 'Authorization: DeepL-Auth-Key ' . $this->apikey;
        $curl->setopt(['CURLOPT_HTTPHEADER' => [$authstring, 'Content-Type: application/json']]);

        // Determine which api url to use, pro or free.
        $apiurl = $this->apikey == get_config('local_bftranslate', 'deepl_api_key') ? $this->apiurl : $this->freeapiurl;
        // Extra sanity check in case free DeepL key is saved in the non-free DeepL key setting.
        if (str_ends_with($this->apikey, ':fx')) {
            $apiurl = $this->freeapiurl;
        }

        $response = $curl->post($apiurl, $string);

        if ($response) {
            $decoded = json_decode($response, true);
            if (isset($decoded['translations'][0]['text'])) {
                $decoded['translations'][0]['text'] = $this->remove_notranslate_tags($decoded['translations'][0]['text']);
            } else {
                throw new translateerror($decoded['message'] ?? '');
            }
            return $decoded['translations'][0]['text'] ?? null;
        }
        return null;
    }
}

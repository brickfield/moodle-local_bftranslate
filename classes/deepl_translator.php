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
     * @return array
     */
    public function translate_batch(array $strings, string $targetlang): array {
        $translations = [];

        foreach ($strings as $key => $text) {
            // Skip empty strings, but still add to array for display in the table.
            if ($text == '') {
                $translations[$key] = '';
                continue;
            }
            $translatedtext = $this->translate($text, $targetlang);
            if ($translatedtext) {
                $translations[$key] = $translatedtext;
            }
        }
        return $translations;
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
     */
    private function translate(string $text, string $targetlang): ?string {
        $text = static::protect_placeholders_for_deepl($text);
        $postdata = [
            'text' => [$text],
            'target_lang' => strtoupper($targetlang),
            'tag_handling' => 'xml',
            'ignore_tags' => ['x'],
        ];
        $string = json_encode($postdata);

        $ch = curl_init($this->apiurl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $string);
        $authstring = 'Authorization: DeepL-Auth-Key ' . $this->apikey;
        curl_setopt($ch, CURLOPT_HTTPHEADER, [$authstring, 'Content-Type: application/json']);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response) {
            $decoded = json_decode($response, true);
            if (isset($decoded['translations'][0]['text'])) {
                $decoded['translations'][0]['text'] = static::remove_notranslate_tags($decoded['translations'][0]['text']);
            }
            return $decoded['translations'][0]['text'] ?? null;
        }
        return null;
    }
}

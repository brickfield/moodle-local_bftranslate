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
 * Handles the translations through the Azure API.
 *
 * @package    local_bftranslate
 * @author     Karen Holland <karen@brickfieldlabs.ie>
 * @copyright  2025 onward Brickfield Education Labs Ltd, https://www.brickfield.ie
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class azure_translator {
    /** @var string Azure API key. */
    private $apikey;

    /** @var string Azure API URL. */
    private $apiurl = 'https://api.cognitive.microsofttranslator.com/translate?api-version=3.0';

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
     * Wrap placeholders with Azure's notranslate tag.
     *
     * @param string $text
     * @return string|null
     */
    private function protect_placeholders_for_azure(string $text): ?string {
        return preg_replace('/(\{\$a->[^}]+\})/', '<span class="notranslate">$1</span>', $text);
    }

    /**
     * Remove Azure's notranslate tags from placeholders.
     *
     * @param string $translatedtext
     * @return string|null
     */
    private function remove_notranslate_tags(string $translatedtext): ?string {
        return preg_replace('/<span class="notranslate">(.*?)<\/span>/', '$1', $translatedtext);
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
        $text = $this->protect_placeholders_for_azure($text);
        // Lang needs to be added to local variable to avoid changing class variable.
        $url = $this->apiurl . "&to=" . $targetlang;
        $url .= "&textType=html";
        $string = json_encode([["Text" => $text]]);

        $curl = new curl();
        $curl->setopt(['CURLOPT_RETURNTRANSFER' => true]);
        $curl->setopt(['CURLOPT_POST' => true]);
        $curl->setopt(['CURLOPT_TIMEOUT' => 30]);
        $authstring = 'Ocp-Apim-Subscription-Key: ' . $this->apikey;
        // Azure keys are region-scoped, so the region must match the subscription.
        $regionvalue = get_config('local_bftranslate', 'azure_region');
        if (empty($regionvalue)) {
            $regionvalue = 'westeurope';
        }
        $region = 'Ocp-Apim-Subscription-Region: ' . $regionvalue;
        $curl->setopt(['CURLOPT_HTTPHEADER' => [$authstring, $region, 'Content-Type: application/json']]);

        $response = $curl->post($url, $string);

        if ($response) {
            $decoded = json_decode($response, true);
            if (isset($decoded[0]['translations'][0]['text'])) {
                $decoded[0]['translations'][0]['text'] = $this->remove_notranslate_tags($decoded[0]['translations'][0]['text']);
            } else {
                $errorcode = $decoded['error']['code'] ?? '';
                if ($errorcode == 401001) {
                    throw new translateerror(get_string('apikey:invalid', 'local_bftranslate', $errorcode));
                } else {
                    throw new translateerror($errorcode);
                }
            }
            return $decoded[0]['translations'][0]['text'] ?? null;
        }
        return null;
    }
}

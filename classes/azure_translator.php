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
    public function __construct($apikey) {
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
     * Generate a random UUID.
     *
     * @return string
     */
    public function com_create_guid(): string {
          return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
              mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
              mt_rand( 0, 0xffff ),
              mt_rand( 0, 0x0fff ) | 0x4000,
              mt_rand( 0, 0x3fff ) | 0x8000,
              mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
          );
    }

    /**
     * Translate the requested strings using the API.
     *
     * @param string $text
     * @param string $targetlang
     * @return string|null
     */
    private function translate(string $text, string $targetlang): ?string {
        $text = static::protect_placeholders_for_azure($text);
        $string = "[{'Text': \"$text\"}]";
        // Lang needs to be added to local variable to avoid changing class variable.
        $url = $this->apiurl . "&to=" . $targetlang;
        $url .= "&textType=html";
        $string = json_encode([["Text" => $text]]);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $string);
        $authstring = 'Ocp-Apim-Subscription-Key: ' . $this->apikey;
        $region = 'Ocp-Apim-Subscription-Region: westeurope';
        curl_setopt($ch, CURLOPT_HTTPHEADER, [$authstring, $region, 'Content-Type: application/json']);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response) {
            $decoded = json_decode($response, true);
            if (isset($decoded[0]['translations'][0]['text'])) {
                $decoded[0]['translations'][0]['text'] = static::remove_notranslate_tags($decoded[0]['translations'][0]['text']);
            } else {
                return 'bftranslateerror:' . $decoded['error']['code'];
            }
            return $decoded[0]['translations'][0]['text'] ?? null;
        }
        return null;
    }
}

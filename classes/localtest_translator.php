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
 * A simple Rot13 translator for testing without API calls.
 *
 * @package    local_bftranslate
 * @author     James McQuillan <james@brickfieldlabs.ie>
 * @copyright  2025 onward Brickfield Education Labs Ltd, https://www.brickfield.ie
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class localtest_translator {
    /**
     * Constructor.
     */
    public function __construct() {
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
            $translatedtext = $this->translate_string($text);
            if ($translatedtext) {
                $translations[$key] = $translatedtext;
            }
        }
        return [$translations, []];
    }

    /**
     * Translate a single string.
     *
     * @param string $string A string to translate.
     * @return string The translated string.
     */
    public function translate_string(string $string): string {
        // Protect placeholders.
        $string = preg_replace('/\{\$a(?:->[^}]+)?\}/', '<x>$0</x>', $string);
        // Split string by HTML tags or entire "notranslate" tags to avoid transforming them.
        $parts = preg_split(
            '/(<x\b[^>]*>.*?<\/x>|<[^>]+>)/is',
            $string,
            -1,
            PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY
        );

        // Selective transform.
        $out = '';
        foreach ($parts as $p) {
            $out .= ($p[0] === '<') ? $p : $this->dotransform($p);
        }

        // Remove notranslate tags.
        $out = preg_replace('/<x>(.*?)<\/x>/', '$1', $out);
        return $out;
    }

    /**
     * Transform a string.
     *
     * @param string $txt The text to transform.
     * @return string The transformed text.
     */
    private function dotransform(string $txt): string {
        return str_rot13($txt);
    }
}

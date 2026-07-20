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
 * Tests for the local test (rot13) translator.
 *
 * @package    local_bftranslate
 * @group      local_bftranslate
 * @category   test
 * @copyright  2025 onward: Brickfield Education Labs, www.brickfield.ie
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class localtest_translator_test extends \advanced_testcase {
    /**
     * Plain text is rot13'd while Moodle placeholders are left untouched.
     *
     * @covers \local_bftranslate\localtest_translator::translate_string
     * @return void
     */
    public function test_translate_string_preserves_placeholders(): void {
        $translator = new localtest_translator();
        $result = $translator->translate_string('Hello {$a->name}');

        // The placeholder must survive verbatim.
        $this->assertStringContainsString('{$a->name}', $result);
        // The word Hello rot13s to Uryyb.
        $this->assertStringContainsString('Uryyb', $result);
    }

    /**
     * HTML tags are preserved while their text content is transformed.
     *
     * @covers \local_bftranslate\localtest_translator::translate_string
     * @return void
     */
    public function test_translate_string_preserves_html(): void {
        $translator = new localtest_translator();
        $result = $translator->translate_string('<strong>Hello</strong>');

        $this->assertStringContainsString('<strong>', $result);
        $this->assertStringContainsString('</strong>', $result);
        $this->assertStringContainsString('Uryyb', $result);
    }

    /**
     * translate_batch keeps empty strings empty and reports no errors.
     *
     * @covers \local_bftranslate\localtest_translator::translate_batch
     * @return void
     */
    public function test_translate_batch_handles_empty_and_errors(): void {
        $translator = new localtest_translator();
        [$translations, $errors] = $translator->translate_batch(['blank' => '', 'greeting' => 'Hello'], 'en');

        $this->assertSame('', $translations['blank']);
        $this->assertSame('Uryyb', $translations['greeting']);
        $this->assertSame([], $errors);
    }
}

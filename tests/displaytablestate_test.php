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
 * Tests for the displaytablestate transport object.
 *
 * @package    local_bftranslate
 * @group      local_bftranslate
 * @category   test
 * @copyright  2025 onward: Brickfield Education Labs, www.brickfield.ie
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class displaytablestate_test extends \advanced_testcase {
    /**
     * The encode/decode round-trip preserves the state fields.
     *
     * @covers \local_bftranslate\displaytablestate::encode
     * @covers \local_bftranslate\displaytablestate::instance_from_encoded
     * @return void
     */
    public function test_encode_decode_round_trip(): void {
        $state = new displaytablestate(
            ['local_bftranslate', 'mod_forum'],
            1,
            'fr',
            ['greeting' => 'Hello'],
            ['greeting' => 'Bonjour'],
            [],
            [],
            'deepl',
            10,
            true
        );

        $decoded = displaytablestate::instance_from_encoded($state->encode());

        $this->assertSame(['local_bftranslate', 'mod_forum'], $decoded->requestedplugins);
        $this->assertSame(1, $decoded->currentpluginindex);
        $this->assertSame('fr', $decoded->targetlang);
        $this->assertSame('deepl', $decoded->selectapi);
        $this->assertSame(10, $decoded->batchlimit);
        $this->assertTrue($decoded->showexisting);
        $this->assertSame(['greeting' => 'Hello'], $decoded->source);
        $this->assertSame(['greeting' => 'Bonjour'], $decoded->results);
    }

    /**
     * Malformed encoded state raises a clean exception rather than PHP warnings.
     *
     * @covers \local_bftranslate\displaytablestate::instance_from_encoded
     * @return void
     */
    public function test_instance_from_encoded_rejects_malformed_input(): void {
        $this->expectException(\moodle_exception::class);
        // Valid base64 of "hello", which is not valid gzip/JSON.
        displaytablestate::instance_from_encoded('aGVsbG8=');
    }

    /**
     * Decoding sanitises the language code and plugin names to block path traversal.
     *
     * @covers \local_bftranslate\displaytablestate::instance_from_encoded
     * @return void
     */
    public function test_instance_from_encoded_sanitises_targetlang_and_plugins(): void {
        $state = new displaytablestate(
            ['../../evil', 'local_bftranslate'],
            0,
            '../../../etc',
            [],
            [],
            [],
            [],
            'deepl',
            5,
            false
        );

        $decoded = displaytablestate::instance_from_encoded($state->encode());

        // The traversal plugin is dropped by PARAM_COMPONENT; the valid one remains.
        $this->assertSame(['local_bftranslate'], $decoded->requestedplugins);
        // PARAM_SAFEDIR strips the slashes and dots from the language code.
        $this->assertSame('etc', $decoded->targetlang);
        $this->assertStringNotContainsString('..', $decoded->targetlang);
    }

    /**
     * current_plugin() falls back to the first plugin when the index is out of range.
     *
     * @covers \local_bftranslate\displaytablestate::current_plugin
     * @return void
     */
    public function test_current_plugin_falls_back_to_first(): void {
        $state = new displaytablestate(['local_bftranslate', 'mod_forum'], 5);
        $this->assertSame('local_bftranslate', $state->current_plugin());
    }

    /**
     * next_plugin() returns the following plugin, or null at the end of the list.
     *
     * @covers \local_bftranslate\displaytablestate::next_plugin
     * @return void
     */
    public function test_next_plugin(): void {
        $state = new displaytablestate(['local_bftranslate', 'mod_forum'], 0);
        $this->assertSame('mod_forum', $state->next_plugin());

        $last = new displaytablestate(['local_bftranslate', 'mod_forum'], 1);
        $this->assertNull($last->next_plugin());
    }
}

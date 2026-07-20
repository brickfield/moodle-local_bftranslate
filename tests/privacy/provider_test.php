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

namespace local_bftranslate\privacy;

/**
 * Tests for the privacy provider.
 *
 * @package    local_bftranslate
 * @group      local_bftranslate
 * @category   test
 * @copyright  2025 onward: Brickfield Education Labs, www.brickfield.ie
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class provider_test extends \advanced_testcase {
    /**
     * The null provider reason resolves to an existing language string.
     *
     * @covers \local_bftranslate\privacy\provider::get_reason
     * @return void
     */
    public function test_get_reason(): void {
        $reason = provider::get_reason();
        $this->assertSame('privacy:nullproviderreason', $reason);
        $this->assertTrue(
            get_string_manager()->string_exists($reason, 'local_bftranslate')
        );
    }
}

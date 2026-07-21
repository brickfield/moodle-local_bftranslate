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
 * Tests for the plugin access control (capabilities).
 *
 * @package    local_bftranslate
 * @group      local_bftranslate
 * @category   test
 * @copyright  2025 onward: Brickfield Education Labs, www.brickfield.ie
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class access_test extends \advanced_testcase {
    /**
     * The viewall capability gates the translator: ordinary users are denied,
     * site administrators (who bypass capability checks) are allowed.
     *
     * @covers \local_bftranslate\bftranslatelib
     * @return void
     */
    public function test_viewall_capability_gates_access(): void {
        $this->resetAfterTest();
        $context = \context_system::instance();

        // A freshly created user does not hold the capability.
        $user = $this->getDataGenerator()->create_user();
        $this->setUser($user);
        $this->assertFalse(has_capability('local/bftranslate:viewall', $context));

        // A site administrator does (capability checks are bypassed for admins).
        $this->setAdminUser();
        $this->assertTrue(has_capability('local/bftranslate:viewall', $context));
    }
}

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

namespace local_bftranslate\event;

/**
 * Tests for the request_submitted event.
 *
 * @package    local_bftranslate
 * @group      local_bftranslate
 * @category   test
 * @copyright  2025 onward: Brickfield Education Labs, www.brickfield.ie
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class request_submitted_test extends \advanced_testcase {
    /**
     * The event triggers and is logged with the expected context and payload.
     *
     * @covers \local_bftranslate\event\request_submitted
     * @return void
     */
    public function test_event_triggers_and_logs(): void {
        $this->resetAfterTest();
        $this->setAdminUser();

        $context = \context_system::instance();
        $event = request_submitted::create([
            'contextid' => $context->id,
            'other' => [
                'plugin' => 'local_bftranslate',
                'lang' => 'fr',
                'api' => 'deepl',
                'count' => 3,
                'status' => 'Success',
            ],
        ]);

        $sink = $this->redirectEvents();
        $event->trigger();
        $events = $sink->get_events();
        $sink->close();

        $this->assertCount(1, $events);
        $logged = reset($events);
        $this->assertInstanceOf(request_submitted::class, $logged);
        $this->assertSame($context->id, (int) $logged->contextid);
        $this->assertSame('c', $logged->crud);
        $this->assertNotEmpty($logged->get_name());
        $this->assertStringContainsString(
            '/local/bftranslate/index.php',
            $logged->get_url()->out_as_local_url(false)
        );
        $this->assertStringContainsString('local_bftranslate', $logged->get_description());
    }
}

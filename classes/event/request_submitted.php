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
 * The request_submitted event class.
 *
 * @package     local_bftranslate
 * @author      Karen Holland <karen@brickfieldlabs.ie>
 * @copyright   2025 Brickfield Education Labs <https://www.brickfield.ie/>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class request_submitted extends \core\event\base {
    #[\Override]
    protected function init() {
        $this->data['crud'] = 'c';
        $this->data['edulevel'] = self::LEVEL_OTHER;
        $this->data['contextid'] = SYSCONTEXTID;
    }

    #[\Override]
    public static function get_name() {
        return get_string('eventrequest_submitted', 'local_bftranslate');
    }

    #[\Override]
    public function get_description() {
        return get_string('eventrequest_submitteddesc', 'local_bftranslate', (object)$this->other);
    }

    #[\Override]
    public function get_url() {
        return new \moodle_url('/local/bftranslate/index.php');
    }
}

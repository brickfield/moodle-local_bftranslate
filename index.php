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

/**
 * Landing page for bftranslate.
 *
 * @package    local_bftranslate
 * @author     Karen Holland <karen@brickfieldlabs.ie>
 * @copyright  2025 onward Brickfield Education Labs Ltd, https://www.brickfield.ie
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use local_bftranslate\bftranslatelib;

require('../../config.php');
require_once($CFG->libdir . '/adminlib.php');

require_login();
require_capability('local/bftranslate:viewall', context_system::instance());

$url = new moodle_url('/local/bftranslate/index.php', []);
$PAGE->set_url($url);
$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string('pluginname', 'local_bftranslate'));

$plugin = optional_param('plugin', '', PARAM_TEXT);
$targetlang = optional_param('targetlang', '', PARAM_TEXT);

$PAGE->set_heading(get_string('pluginname', 'local_bftranslate'));

echo $OUTPUT->header();

$mform = new \local_bftranslate\form\translate_form();

// Handle the form submission.
$formdata = $mform->get_data();
if ($formdata !== null) {
    $results = bftranslatelib::process_translation($formdata);
    if (empty($results)) {
        echo $OUTPUT->notification(get_string('notranslationsneeded', 'local_bftranslate'), 'notifysuccess');
    } else {
        $uniqueid = 'local_bftranslate_displaytable';
        new \local_bftranslate\displaytable($uniqueid, [],
            ['plugin' => $plugin, 'targetlang' => $targetlang,
                'source' => $results[0], 'results' => $results[1],
                'selectoutput' => $formdata->selectoutput], $url);
    }

}

$mform->display();

echo $OUTPUT->footer();

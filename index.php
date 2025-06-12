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
use local_bftranslate\displaytablestate;
use local_bftranslate\displaytable;

require('../../config.php');
require_once($CFG->libdir . '/adminlib.php');

$doaction = optional_param('doaction', '', PARAM_TEXT);
$stateencoded = optional_param('state', '', PARAM_TEXT);

require_login();
require_capability('local/bftranslate:viewall', context_system::instance());

// Page setup.
$url = new moodle_url('/local/bftranslate/index.php', []);
$PAGE->set_url($url);
$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string('pluginname', 'local_bftranslate'));
$PAGE->set_heading(get_string('pluginname', 'local_bftranslate'));

$mform = new \local_bftranslate\form\translate_form();
$formdata = $mform->get_data();



// Decode state if available.
$state = null;
if (!empty($stateencoded)) {
    $state = displaytablestate::instance_from_encoded($stateencoded);
    $translations = optional_param_array('translations', [], PARAM_CLEANHTML);
    $decodedtranslations = [];
    foreach ($translations as $key => $value) {
        $decodedkey = base64_decode(str_replace(['-', '_'], ['+', '/'], $key));
        $state->results[$decodedkey] = $value;
    }
}

if (!empty($doaction) && !empty($state)) {
    // Translations already completed, do an action with the results.
    switch ($doaction) {
        case 'save':
            echo $OUTPUT->header();
            $saveresults = bftranslatelib::save_translation($state->results, $state->current_plugin(), $state->targetlang);
            echo $OUTPUT->notification(get_string('submitsuccess', 'local_bftranslate'), 'notifysuccess');
            new displaytable($state, $url);
            break;

        case 'switchview-langstring':
            $langstrings = "<?php\n";
            foreach ($state->results as $key => $string) {
                $langdata = [
                    'key' => $key,
                    'value' => str_replace('\'', '\\\'', $state->results[$key]),
                ];
                $langstrings .= get_string('langstringformat', 'local_bftranslate', $langdata)."\n";
            }
            send_file($langstrings, 'strings.php', null, 0, true, true);
            new displaytable($state, $url);
            break;

        case 'nextplugin':
            echo $OUTPUT->header();
            $state->currentpluginindex += 1;
            $results = bftranslatelib::process_translation($state->current_plugin(), $state->targetlang,
                                                            $state->selectapi, $state->batchlimit);
            if (empty($results)) {
                echo $OUTPUT->notification(get_string('notranslationsneeded', 'local_bftranslate'), 'notifysuccess');
            } else if (isset($results['error'])) {
                echo $OUTPUT->notification($results['error'], 'notifywarning');
            }

            $state->source = (isset($results[0])) ? $results[0] : [];
            $state->results = (isset($results[1])) ? $results[1] : [];
            new displaytable($state, $url);

            break;
    }
    $mform->set_data($state->formdata());
} else {
    echo $OUTPUT->header();
    // Handle the form submission.
    if ($formdata !== null) {
        $state = new displaytablestate($formdata->plugins, 0, $formdata->targetlang, [], [],
                                        $formdata->selectapi, $formdata->batchlimit);
        $results = bftranslatelib::process_translation_from_state($state);

        if (empty($results)) {
            echo $OUTPUT->notification(get_string('notranslationsneeded', 'local_bftranslate'), 'notifysuccess');
        } else if (isset($results['error'])) {
            echo $OUTPUT->notification($results['error'], 'notifywarning');
        }

        $state->source = (isset($results[0])) ? $results[0] : [];
        $state->results = (isset($results[1])) ? $results[1] : [];
        new displaytable($state, $url);
    }
}

$mform->display();
echo $OUTPUT->render_from_template('local_bftranslate/branding', []);
echo $OUTPUT->footer();

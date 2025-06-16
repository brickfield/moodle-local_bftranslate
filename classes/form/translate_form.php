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

namespace local_bftranslate\form;

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__.'/../../../../lib/formslib.php');

/**
 * Class translate_form
 *
 * @package    local_bftranslate
 * @copyright  2024 onward: Brickfield Education Labs, www.brickfield.ie
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class translate_form extends \moodleform {
    /**
     * Form definition.
     *
     * @return void
     */
    public function definition() {
        global $CFG;
        $mform = $this->_form;

        // Get list of installed plugins.
        $plugins = \local_bftranslate\bftranslatelib::get_plugins_dropdown_array();
        // Get list of available target languages.
        $targetlanguages = \local_bftranslate\bftranslatelib::get_languages_dropdown_array();

        // Sanity check which APIs to display.
        $config = get_config('local_bftranslate');
        $apis = [];
        if (!empty($config->azure_api_key)) {
            $apis['azure'] = get_string('selectazure', 'local_bftranslate');
        }
        if (!empty($config->deepl_api_key)) {
            $apis['deepl'] = get_string('selectdeepl', 'local_bftranslate');
        }
        if (!empty($config->showlocaltest)) {
            $apis['localtest'] = get_string('selectlocaltest', 'local_bftranslate');
        }
        if (count($apis) > 0) {
            $mform->addElement('select', 'selectapi',
                get_string('selectapi', 'local_bftranslate'), $apis);
            $mform->setType('selectapi', PARAM_ALPHANUMEXT);
        } else {
            $mform->addElement('static', 'selectapi', get_string('selectapi', 'local_bftranslate'),
                get_string('selectnoapis', 'local_bftranslate'));
        }

        $select = $mform->addElement('select', 'plugins', get_string('selectplugin', 'local_bftranslate'), $plugins);
        $select->setMultiple(true);
        $mform->setType('plugins', PARAM_ALPHANUMEXT);
        $mform->addHelpButton('plugins', 'selectplugin', 'local_bftranslate');

        $mform->addElement('select', 'targetlang',
            get_string('selectlanguage', 'local_bftranslate'), $targetlanguages);
        $mform->setType('targetlang', PARAM_ALPHANUMEXT);
        $mform->addHelpButton('targetlang', 'selectlanguage', 'local_bftranslate');

        $batchlimit = [0 => 0, 5 => 5, 10 => 10, 50 => 50, 100 => 100, 150 => 150, 200 => 200, 250 => 250, 300 => 300];
        $mform->addElement('select', 'batchlimit', get_string('selectbatchlimit', 'local_bftranslate'), $batchlimit);
        $mform->setType('batchlimit', PARAM_INT);

        $this->add_action_buttons(true, get_string('translate', 'local_bftranslate'));
    }

    /**
     * Form validation.
     *
     * @param array $data
     * @param array $files
     * @return array
     */
    public function validation($data, $files) {
        $errors = [];

        if (!isset($data['selectapi'])) {
            $errors['selectapi'] = get_string('selectnoapis', 'local_bftranslate');
        }

        if ($data['plugins'] === '') {
            $errors['plugins'] = get_string('emptyplugin', 'local_bftranslate');
        }

        if ($data['targetlang'] === '') {
            $errors['targetlang'] = get_string('emptytargetlang', 'local_bftranslate');
        }

        return $errors;
    }
}


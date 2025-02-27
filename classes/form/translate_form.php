<?php

namespace local_bftranslate\form;

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
        $mform = $this->_form;

        // Get list of installed plugins.
        $plugins = \local_bftranslate\bftranslatelib::get_plugins_dropdown_array();
        // Get list of available target languages.
        $targetlanguages = \local_bftranslate\bftranslatelib::get_languages_dropdown_array();

        $mform->addElement('select', 'selectapi',
            get_string('selectapi', 'local_bftranslate'), ['deepl' => 'DeepL', 'azure' => 'Azure']);
        $mform->setType('selectapi', PARAM_ALPHANUMEXT);

        $mform->addElement('select', 'plugin', get_string('selectplugin', 'local_bftranslate'), $plugins);
        $mform->setType('plugin', PARAM_ALPHANUMEXT);
        $mform->addHelpButton('plugin', 'selectplugin', 'local_bftranslate');

        $mform->addElement('select', 'targetlang',
            get_string('selectlanguage', 'local_bftranslate'), $targetlanguages);
        $mform->setType('targetlang', PARAM_ALPHANUMEXT);
        $mform->addHelpButton('targetlang', 'selectlanguage', 'local_bftranslate');

        $batchlimit = [0 => 0, 5 => 5, 10 => 10, 50 => 50, 100 => 100, 150 => 150, 200 => 200, 250 => 250, 300 => 300];
        $mform->addElement('select', 'batchlimit', get_string('selectbatchlimit', 'local_bftranslate'), $batchlimit);
        $mform->setType('batchlimit', PARAM_INT);

        $mform->addElement('select', 'selectoutput',
        get_string('selectoutput', 'local_bftranslate'), ['table' => 'Table', 'langstring' => 'PHP Language String']);
        $mform->setType('selectoutput', PARAM_ALPHANUMEXT);

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

        if ($data['plugin'] === '') {
            $errors['plugin'] = get_string('emptyplugin', 'local_bftranslate');
        }

        if ($data['targetlang'] === '') {
            $errors['targetlang'] = get_string('emptytargetlang', 'local_bftranslate');
        }

        return $errors;
    }
}


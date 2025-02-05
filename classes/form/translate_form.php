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

        // Get list of installed plugins
        $plugins = \local_bftranslate\bftranslatelib::get_plugins_dropdown();

        $stringmgr = get_string_manager();
        $languages = $stringmgr->get_list_of_translations();
        $languages = array_merge(['' => get_string('select')], $languages);

        $mform->addElement('select', 'plugin', get_string('selectplugin', 'local_bftranslate'), $plugins);
        $mform->setType('plugin', PARAM_ALPHANUMEXT);

        $mform->addElement('select', 'targetlang', get_string('selectlanguage', 'local_bftranslate'), $languages);
        $mform->setType('targetlang', PARAM_ALPHANUMEXT);

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


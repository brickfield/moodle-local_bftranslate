<?php

//use local_bftranslate\deepl_translator;
use \local_bftranslate\form\translate_form;
use local_bftranslate\bftranslatelib;
use core_plugin_manager;

require('../../config.php');
require_once($CFG->libdir . '/adminlib.php');

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
    bftranslatelib::process_translation($plugin, $targetlang);

} else {

}
$mform->display();

echo $OUTPUT->footer();

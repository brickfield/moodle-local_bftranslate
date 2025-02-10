<?php

defined('MOODLE_INTERNAL') || die();

$settings = new admin_settingpage('local_bftranslate', get_string('pluginname', 'local_bftranslate'));

$settings->add(new admin_setting_configtext('local_bftranslate/deepl_api_key',
    get_string('apikey:deepl', 'local_bftranslate'),
    get_string('apikey:deepl_desc', 'local_bftranslate'),
    '', PARAM_TEXT));

    $settings->add(new admin_setting_configtext('local_bftranslate/azure_api_key',
    get_string('apikey:azure', 'local_bftranslate'),
    get_string('apikey:azure_desc', 'local_bftranslate'),
    '', PARAM_TEXT));

$ADMIN->add('localplugins', $settings);


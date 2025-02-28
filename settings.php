<?php

defined('MOODLE_INTERNAL') || die();

$ADMIN->add('localplugins', new admin_category('local_bftranslate_folder', get_string('pluginname', 'local_bftranslate')));

$settings = new admin_settingpage('local_bftranslate', get_string('settings', 'local_bftranslate'));

$settings->add(new admin_setting_configtext('local_bftranslate/deepl_api_key',
    get_string('apikey:deepl', 'local_bftranslate'),
    get_string('apikey:deepl_desc', 'local_bftranslate'),
    '', PARAM_TEXT));

    $settings->add(new admin_setting_configtext('local_bftranslate/azure_api_key',
    get_string('apikey:azure', 'local_bftranslate'),
    get_string('apikey:azure_desc', 'local_bftranslate'),
    '', PARAM_TEXT));

$ADMIN->add('local_bftranslate_folder', $settings);

$ADMIN->add('local_bftranslate_folder', new admin_externalpage('local_bftranslate_report',
        get_string('report:heading', 'local_bftranslate'),
        new moodle_url('/local/bftranslate/index.php', []),
        'moodle/site:config'));

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
 * Plugin settings are defined here.
 *
 * @package    local_bftranslate
 * @author     Karen Holland <karen@brickfieldlabs.ie>
 * @copyright  2025 onward Brickfield Education Labs Ltd, https://www.brickfield.ie
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

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

$settings->add(new admin_setting_configcheckbox(
    'local_bftranslate/allowcoretranslation',
    get_string('settings:allowcoretranslation', 'local_bftranslate'),
    get_string('settings:allowcoretranslation_desc', 'local_bftranslate'),
    '0'
));

$settings->add(new admin_setting_configcheckbox(
    'local_bftranslate/showlocaltest',
    get_string('settings:showlocaltest', 'local_bftranslate'),
    get_string('settings:showlocaltest_desc', 'local_bftranslate'),
    '0'
));

$settings->add(new admin_setting_configtextarea('local_bftranslate/external_plugins',
    get_string('external_plugins', 'local_bftranslate'),
    get_string('external_plugins_desc', 'local_bftranslate'),
    '', PARAM_TEXT));

$ADMIN->add('local_bftranslate_folder', $settings);

$ADMIN->add('local_bftranslate_folder', new admin_externalpage('local_bftranslate_report',
        get_string('report:heading', 'local_bftranslate'),
        new moodle_url('/local/bftranslate/index.php', []),
        'moodle/site:config'));

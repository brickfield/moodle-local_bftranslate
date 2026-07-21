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
 * English language pack for local_bftranslate
 *
 * @package    local_bftranslate
 * @category   string
 * @copyright  2025 onward Brickfield Education Labs Ltd, https://www.brickfield.ie
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['apikey:azure'] = 'Azure API Key';
$string['apikey:azure_desc'] = 'Enter your Azure API key to enable translations.';
$string['apikey:deepl'] = 'DeepL API Key';
$string['apikey:deepl_desc'] = 'Enter your DeepL API key to enable translations.';
$string['apikey:deeplfree'] = 'DeepL Free API Key';
$string['apikey:deeplfree_desc'] = 'Enter your DeepL Free API key to enable translations.';
$string['apikey:invalid'] = 'Invalid API key ({$a})';
$string['bftranslate:viewall'] = 'View all Plugin Translator features';
$string['branding:academy'] = 'Training Academy';
$string['branding:assessment'] = 'Assessment Bundle';
$string['branding:audit'] = 'Accessibility Audit';
$string['branding:bflogo'] = 'Brickfield Education Labs';
$string['branding:contact'] = 'Contact Brickfield';
$string['branding:like'] = 'You might also like';
$string['branding:skills'] = 'Skills Bundle';
$string['branding:toolkit'] = 'Accessibility Toolkit';
$string['branding:ux'] = 'User Experience Bundle';
$string['copyright_text'] = 'Created with Plugin Translator by Brickfield https://www.brickfield.ie/brickfield-translator/';
$string['emptyplugin'] = 'No plugin submitted';
$string['emptytargetlang'] = 'No target language submitted';
$string['errorsencountered'] = 'Errors encountered:';
$string['eventrequest_submitted'] = 'Translation request submitted';
$string['eventrequest_submitteddesc'] = 'Translation request submitted, plugin was \'{$a->plugin}\', language was \'{$a->lang}\', API was \'{$a->api}\', count of strings submitted was \'{$a->count}\', status was \'{$a->status}\'.';
$string['filename'] = 'Translator download';
$string['invalidstatedata'] = 'The translation session data could not be read. Please start the translation again.';
$string['langnotsupported'] = 'Language \'{$a}\' is not supported on this submitted API.';
$string['matchingstrings'] = 'The following strings are identical in English and the target translation language: {$a}. <br /><br />Due to the way Moodle defaults to using English strings if none exist, identical existing strings will continue to show as being missing, so please ignore these matching strings in subsequent processing.';
$string['nextplugin'] = 'Next Plugin ({$a->plugin}) &rarr;';
$string['nofilefound'] = 'There was a problem locating the plugin language strings. Please check with your Site Administrator.';
$string['notranslationsneeded'] = 'All strings already exist in the target language.';
$string['pluginname'] = 'Plugin Translator by Brickfield';
$string['privacy:nullproviderreason'] = 'The Plugin Translator by Brickfield does not store any personal data.';
$string['report:heading'] = 'Translation page';
$string['reporttitle'] = 'Translator download report';
$string['request:failure'] = '{$a}';
$string['request:success'] = 'Success';
$string['savechanges'] = 'Save changes as custom language strings';
$string['selectapi'] = 'Select API';
$string['selectazure'] = 'Azure';
$string['selectbatchlimit'] = 'Select optional batch limit';
$string['selectdeepl'] = 'DeepL';
$string['selectdeeplfree'] = 'DeepL Free';
$string['selectlanguage'] = 'Select target language';
$string['selectlanguage_help'] = 'Includes languages which are installed as language packs.';
$string['selectlocaltest'] = 'Local test';
$string['selectnoapis'] = 'No APIs are currently configured. Please check with your Site Administrator.';
$string['selectplugin'] = 'Select plugin';
$string['selectplugin_help'] = 'Includes plugins which are either core or non-core, depending on configs.<br /><br /> Non-core plugins need to be both in the permitted list and installed.';
$string['settings'] = 'Settings';
$string['settings:allowcoretranslation'] = 'Allow core translation';
$string['settings:allowcoretranslation_desc'] = 'Allow core plugins to be translated.';
$string['settings:azureregion'] = 'Azure region';
$string['settings:azureregion_desc'] = 'The region your Azure Translator subscription key belongs to (for example, westeurope, eastus).';
$string['settings:showlocaltest'] = 'Show the "Local test" translator';
$string['settings:showlocaltest_desc'] = 'The "Local test" translator performs a simple Rot13 transformation on strings to demonstrate the plugin without external API calls.';
$string['showexisting'] = 'Show existing';
$string['showexisting_desc'] = 'Show pre-existing language strings as well';
$string['status:existing'] = 'Existing';
$string['status:new'] = 'New';
$string['submitsuccess'] = 'Submitted translation strings are now saved.';
$string['switchview-langstring'] = 'Download as PHP language strings';
$string['tablecaption'] = 'Table for plugin \'{$a->plugin}\' to language \'{$a->targetlang}\'';
$string['tableheader:key'] = 'Language key';
$string['tableheader:sourcestring'] = 'Source string';
$string['tableheader:status'] = 'Status';
$string['tableheader:targetstring'] = 'Target string';
$string['translate'] = 'Translate';
$string['translateerror'] = 'Error received from API: {$a}';
$string['translationlabel'] = 'Translation text for string key {$a}';

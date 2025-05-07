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

$string['pluginname'] = 'Brickfield Plugin Translator';
$string['apikey:deepl'] = 'DeepL API Key';
$string['apikey:deepl_desc'] = 'Enter your DeepL API key to enable translations.';
$string['apikey:azure'] = 'Azure API Key';
$string['apikey:azure_desc'] = 'Enter your Azure API key to enable translations.';
$string['emptyplugin'] = 'No Plugin Submitted';
$string['emptytargetlang'] = 'No Target Language Submitted';
$string['external_plugins'] = 'External Plugins';
$string['external_plugins_desc'] = 'Enter a comma separated list of installed external plugins.';
$string['filename'] = 'Translator Download';
$string['langstringformat'] = '$string[\'{$a->key}\'] = \'{$a->value}\';';
$string['matchingstrings'] = 'The following strings are identical in English and the target translation language: {$a}. <br /><br />Due to the way Moodle defaults to using English strings if none exist, identical existing strings will continue to show as being missing, so please ignore these matching strings in subsequent processing.';
$string['notranslationsneeded'] = 'All strings already exist in the target language.';
$string['privacy:nullproviderreason'] = 'The Brickfield Plugin Translator does not store any personal data.';
$string['report:heading'] = 'Translation page';
$string['reporttitle'] = 'Translator Download Report';
$string['savechanges'] = 'Save Changes as custom language strings';
$string['selectbatchlimit'] = 'Select Optional Batch Limit';
$string['selectapi'] = 'Select API';
$string['selectplugin'] = 'Select Plugin';
$string['selectplugin_help'] = 'Includes plugins which are both in the permitted list and installed.';
$string['selectlanguage'] = 'Select Target Language';
$string['selectlanguage_help'] = 'Includes languages which are both in the permitted list and installed as language packs.';
$string['selectoutput'] = 'Select Table or PHP Language String output';
$string['settings'] = 'Settings';
$string['settings:allowcoretranslation'] = 'Allow core translation';
$string['settings:allowcoretranslation_desc'] = 'Allow core plugins to be translated.';
$string['submitsuccess'] = 'Submitted translation strings are now saved.';
$string['tablecaption'] = 'Table for plugin \'{$a->plugin}\' to language \'{$a->targetlang}\'';
$string['tableheader:key'] = 'Language Key';
$string['tableheader:langstring'] = 'Language Strings';
$string['tableheader:sourcestring'] = 'Source String';
$string['tableheader:targetstring'] = 'Target String';
$string['translate'] = 'Translate';
$string['translationsuccess'] = 'Translation successful!';


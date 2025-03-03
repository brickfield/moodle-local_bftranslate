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

namespace local_bftranslate;

use local_bftranslate\deepl_translator;
use local_bftranslate\azure_translator;

/**
 * Library class containing main functions.
 *
 * @package    local_bftranslate
 * @author     Karen Holland <karen@brickfieldlabs.ie>
 * @copyright  2025 onward Brickfield Education Labs Ltd, https://www.brickfield.ie
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class bftranslatelib {

    /**
     * Returns an array of installed external plugins.
     *
     * @return array
     */
    public static function get_plugins(): array {
        $plugins = [
            'tool_bfplus',
            'accessibilityplustool_activityresults',
            'accessibilityplustool_analysismanagement',
            'accessibilityplustool_checkresults',
            'accessibilityplustool_checktyperesults',
            'accessibilityplustool_coursecategories',
            'accessibilityplustool_courses',
            'accessibilityplustool_coursetags',
            'accessibilityplustool_errors',
            'accessibilityplustool_exceptionmanagement',
            'accessibilityplustool_fixallcaps',
            'accessibilityplustool_fixareatarget',
            'accessibilityplustool_fixbold',
            'accessibilityplustool_fixemptyheaders',
            'accessibilityplustool_fiximgalt',
            'accessibilityplustool_fixitalics',
            'accessibilityplustool_fixlinktarget',
            'accessibilityplustool_fixlinktext',
            'accessibilityplustool_fixstrike',
            'accessibilityplustool_logbulkfix',
            'accessibilityplustool_loggingreport',
            'accessibilityplustool_outline',
            'accessibilityplustool_overview',
            'accessibilityplustool_printable',
            'local_bfaltformat',
            'block_bfmanager',
            'filter_bfurltolink',
            'atto_bfimage',
            'atto_bflink',
            'report_editlicence',
            'local_bfnewwindow',
            'local_bfguides',
            'assignfeedback_downloadbysurname',
            'assignsub_bfcompass',
            'block_badgeawarder',
            'block_category_results',
            'block_audiencemessage',
            'block_category_overview',
            'block_course_group_upload',
            'block_inviteuser',
            'block_myoverview2',
            'block_questionnaire_manager',
            'block_quicklinks',
            'block_reading_list',
            'block_reminders',
            'gradereport_markingguide',
            'gradereport_rubrics',
            'local_bfguides',
            'local_bfqanda',
            'local_bfquicklinks',
            'local_bfrescan',
            'local_hsa',
            'mod_board',
            'mod_compass',
            'mod_election',
            'mod_ltssubcourse',
            'mod_planner',
            'mod_referendum',
            'mod_nomination',
            'report_bfactivitytracking',
            'report_bfbadgeawarder',
            'report_bfcompetencyawarder',
            'report_bfcompreport',
            'report_compassstats',
            'report_supportservice',
            'report_tagstimeline',
            'tool_bfcompass',
            'tool_bfusertour',
            'tool_coursetagger',
            'tool_bfcompexport',
        ];

        return $plugins;
    }

    /**
     * Returns the values to populate the plugin dropdown.
     *
     * @return array
     */
    public static function get_plugins_dropdown_array(): array {
        $bfplugins = static::get_plugins();

        $plugins = ['' => get_string('select')];
        foreach ($bfplugins as $key => $bfplugin) {
            // Lang strings only retrievable from installed plugins on full plugins list.
            if (get_string_manager()->string_exists('pluginname', $bfplugin)) {
                $plugins[$bfplugin] = get_string('pluginname', $bfplugin);
            }
        }

        return $plugins;
    }

    /**
     * Return an array of language codes.
     *
     * @return array
     */
    public static function get_languages(): array {
        $languages = [
            'ar',
            'bg',
            'cs',
            'da',
            'de',
            'el',
            'es',
            'et',
            'fi',
            'fr',
            'ga',
            'hu',
            'id',
            'it',
            'ja',
            'ko',
            'lt',
            'lv',
            'nb',
            'nl',
            'pl',
            'pt-br',
            'pt-pt',
            'ro',
            'ru',
            'sk',
            'sl',
            'sv',
            'tr',
            'uk',
            'zh-hans',
            'zh-hant',
        ];

        // Reverse to have lang codes as keys.
        $languages = array_flip($languages);

        return $languages;
    }

    /**
     * Map specfic lang codes to the correct code.
     *
     * @return array
     */
    public static function get_language_mappings(): array {
        $languages = [
            'pt' => 'pt-pt',
            'en' => 'en-gb',
            'zh_cn' => 'zh-hans',
            'zh_tw' => 'zh-hant',
        ];

        return $languages;
    }

    /**
     * Return an array of install languages.
     *
     * @return array
     */
    public static function get_installed_languages(): array {

        $stringmgr = get_string_manager();
        $languages = $stringmgr->get_list_of_translations();
        // Adding specific mappings between Moodle lang codes and external API requirements.
        $mappings = static::get_language_mappings();
        foreach ($mappings as $key => $value) {
            if (isset($languages[$key])) {
                $languages[$value] = $languages[$key];
            }
        }

        return $languages;
    }

    /**
     * Returns the values to populate the languages dropdown.
     *
     * @return array
     */
    public static function get_languages_dropdown_array(): array {
        $targetlanguages = static::get_languages();

        $stringmgr = get_string_manager();
        $languages = static::get_installed_languages();
        $languages = array_intersect_key($languages, $targetlanguages);
        $languages = array_merge(['' => get_string('select')], $languages);

        return $languages;
    }

    /**
     * Process the translation request.
     *
     * @param object $formdata
     * @return array
     */
    public static function process_translation(object $formdata): array {

        $plugin = $formdata->plugin;
        $targetlang = $formdata->targetlang;
        $api = $formdata->selectapi;
        $batchlimit = $formdata->batchlimit;

        $config = get_config('local_bftranslate');
        $info = \core_plugin_manager::instance()->get_plugin_info($plugin);
        if (!file_exists($info->rootdir .'/lang/en/' . $plugin . '.php')) {
            return [];
        }

        // Load all strings from the English language pack.
        $englishstrings = get_string_manager()->load_component_strings($plugin, 'en');
        $targetstrings = get_string_manager()->load_component_strings($plugin, $targetlang);

        if ($batchlimit > 0) {
            $englishstrings = array_slice($englishstrings, 0, $batchlimit);
        }

        $missing = [];
        foreach ($englishstrings as $key => $string) {
            // Check if the string is missing or empty or identical in the target language.
            if ((!isset($targetstrings[$key])
                || empty(trim($targetstrings[$key])))
                || ($targetstrings[$key] == $string)) {
                $missing[$key] = $string;
            }
        }

        // Return early if there are no strings to be translated.
        if (empty($missing)) {
            return [];
        }

        if ($api == 'azure') {
            $work = new azure_translator($config->azure_api_key);
            $results = $work->translate_batch($missing, $targetlang);
        } else {
            $work = new deepl_translator($config->deepl_api_key);
            $results = $work->translate_batch($missing, $targetlang);
        }

        return [$missing, $results];
    }
}

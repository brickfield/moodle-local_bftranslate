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
use local_bftranslate\localtest_translator;

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
        // Get plugins from config.
        $config = get_config('local_bftranslate');
        $allplugins = \core_plugin_manager::instance()->get_plugins();
        $plugins = [];

        // If allowcoretranslation is enabled, then allow core plugins only.
        foreach ($allplugins as $type => $list) {
            foreach ($list as $name => $plugin) {
                if (!empty($config->allowcoretranslation) && $plugin->is_standard()) {
                    $plugins[] = $type . '_' .$name;
                } else if (empty($config->allowcoretranslation) && !$plugin->is_standard()) {
                    $plugins[] = $type . '_' .$name;
                }
            }
        }

        return $plugins;
    }

    /**
     * Returns the values to populate the plugin dropdown.
     *
     * @return array
     */
    public static function get_plugins_dropdown_array(): array {
        $bfplugins = static::get_plugins();

        $plugins = [];
        foreach ($bfplugins as $key => $bfplugin) {
            // Lang strings only retrievable from installed plugins on full plugins list.
            if (get_string_manager()->string_exists('pluginname', $bfplugin)) {
                $plugins[$bfplugin] = get_string('pluginname', $bfplugin) . ' (' . $bfplugin . ')';
            }
        }
        asort($plugins, SORT_STRING | SORT_FLAG_CASE);
        $plugins = ['' => get_string('select')] + $plugins;

        return $plugins;
    }

    /**
     * Map specfic lang code to the correct code for the specified API.
     *
     * @param string $api
     * @param string $targetlang
     * @return string
     */
    public static function get_language_mapped(string $api, string $targetlang): string {
        // Maps the Moodle lang codes to the API equivalents.

        // Deal with Workplace lang first, if submitted.
        $wpfound = stripos($targetlang, '_wp');
        if ($wpfound !== false) {
            $targetlang = substr($targetlang, 0, -3);
        }

        if ($api == 'deepl') {
            $languages = [
                'en' => 'en-gb',
                'pt' => 'pt-pt',
                'pt_br' => 'pt-br',
                'zh_cn' => 'zh-hans',
                'zh_tw' => 'zh-hant',
            ];
        } else if ($api == 'azure') {
            $languages = [
                'ckb' => 'ku',
                'fr_ca' => 'fr-ca',
                'lg' => 'lug',
                'mn' => 'mn-Cyrl',
                'mn_mong' => 'mn-Mong',
                'no' => 'nb',
                'pt' => 'pt-pt',
                'pt_br' => 'pt',
                'sr_cr' => 'sr-Cyrl',
                'sr_lt' => 'sr-Latn',
                'zh_cn' => 'zh-Hans',
                'zh_tw' => 'zh-Hant',
            ];
        }

        if (isset($languages[$targetlang])) {
            $targetlang = $languages[$targetlang];
        }
        return $targetlang;
    }

    /**
     * Return an array of install languages.
     *
     * @return array
     */
    public static function get_installed_languages(): array {

        $stringmgr = get_string_manager();
        $languages = $stringmgr->get_list_of_translations();

        return $languages;
    }

    /**
     * Returns the values to populate the languages dropdown.
     *
     * @return array
     */
    public static function get_languages_dropdown_array(): array {

        $languages = static::get_installed_languages();
        $languages = array_merge(['' => get_string('select')], $languages);

        return $languages;
    }

    /**
     * Process the translation request.
     *
     * @param \local_bftranslate\displaytablestate $state The state object to use.
     * @return array
     */
    public static function process_translation_from_state(\local_bftranslate\displaytablestate $state): array {
        return static::process_translation($state->current_plugin(), $state->targetlang, $state->selectapi, $state->batchlimit);
    }

    /**
     * Process the translation request.
     *
     * @param string $plugin The plugin to translate.
     * @param string $targetlang The language to translate to.
     * @param string $api The API to use.
     * @param int $batchlimit The batch limit to use (0 = unlimited).
     * @return array
     */
    public static function process_translation(string $plugin, string $targetlang, string $api, int $batchlimit): array {
        $config = get_config('local_bftranslate');
        $info = \core_plugin_manager::instance()->get_plugin_info($plugin);
        $langfilename = static::get_langfilename($plugin);
        $path = $info->rootdir .'/lang/en/' . $langfilename;
        $missing = [];
        $results = [];
        $errors = [];

        if (!file_exists($path)) {
            $errors[] = get_string('nofilefound', 'local_bftranslate');
            return [$missing, $results, $errors];
        }

        // Check API / targetlang is supported on API.
        $supported = static::lang_supported($api, $targetlang);
        if (!$supported) {
            $errors[] = get_string('langnotsupported', 'local_bftranslate', $targetlang);
            return [$missing, $results, $errors];
        }

        // Load all strings from the English language pack.
        $englishstrings = get_string_manager()->load_component_strings($plugin, 'en');
        $targetstrings = get_string_manager()->load_component_strings($plugin, $targetlang);

        $offset = 0;
        if ($batchlimit > 0 ) {
            // Loop through all english strings until missing strings are equal to batch limit, or all strings have been checked.
            while ($offset < count($englishstrings)) {
                $batchstrings = array_slice($englishstrings, $offset, $batchlimit);

                foreach ($batchstrings as $key => $string) {
                    // Check if string needs translating: is missing, empty, or identical to English in the target language.
                    if ((!isset($targetstrings[$key])
                        || empty(trim($targetstrings[$key])))
                        || ($targetstrings[$key] == $string)) {
                        $missing[$key] = $string;
                    }
                    if (count($missing) == $batchlimit) {
                        break 2;
                    }
                }
                $offset += $batchlimit;
            }
        } else {
            foreach ($englishstrings as $key => $string) {
                // Check if string needs translating: is missing, empty, or identical to English in the target language.
                if ((!isset($targetstrings[$key])
                    || empty(trim($targetstrings[$key])))
                    || ($targetstrings[$key] == $string)) {
                    $missing[$key] = $string;
                }
            }
        }

        // Return early if there are no strings to be translated.
        if (empty($missing)) {
            return [$missing, $results, $errors];
        }

        $targetlang = static::get_language_mapped($api, $targetlang);
        if ($api == 'azure') {
            $work = new azure_translator($config->azure_api_key);
            list($results, $errors) = $work->translate_batch($missing, $targetlang);
        } else if ($api == 'localtest') {
            $work = new localtest_translator();
            list($results, $errors) = $work->translate_batch($missing, $targetlang);
        } else {
            $work = new deepl_translator($config->deepl_api_key);
            list($results, $errors) = $work->translate_batch($missing, $targetlang);
        }

        return [$missing, $results, $errors];
    }

    /**
     * Handles saving the translations to the language file.
     *
     * @param array $translations
     * @param string $plugin
     * @param string $targetlang
     */
    public static function save_translation(array $translations, string $plugin, string $targetlang) {
        global $CFG;

        // Determine the language file location.
        $langfilename = static::get_langfilename($plugin);
        $langdir = $CFG->dataroot . "/lang/" . $targetlang . "_local";
        $langfile = "$langdir/$langfilename";

        // Ensure the language directory exists.
        if (!file_exists($langdir)) {
            mkdir($langdir, 0777);
        }

        // Read existing strings using file parsing.
        $existingstrings = [];
        if (file_exists($langfile)) {
            $content = file_get_contents($langfile);
            preg_match_all("/\\\$string\\['(.+?)'\\]\\s*=\\s*'(.*?)';/s", $content, $matches, PREG_SET_ORDER);
            foreach ($matches as $match) {
                // Do some processing on existing string here, to match translations on single quotes status.
                $match[2] = str_replace(['\\\''], ['\''], $match[2]);
                $existingstrings[$match[1]] = $match[2];
            }
        }

        // Merge and sort translations, ensuring 'pluginname' remains first.
        // $existingstrings go last in array_merge to retain any existing customisations already done.
        $mergedstrings = array_merge($translations, $existingstrings);
        if (isset($mergedstrings['pluginname'])) {
            $pluginname = $mergedstrings['pluginname'];
            unset($mergedstrings['pluginname']);
            ksort($mergedstrings);
            $mergedstrings = ['pluginname' => $pluginname] + $mergedstrings;
        } else {
            ksort($mergedstrings);
        }

        $content = static::generate_strings_file($mergedstrings, $plugin);

        // Write sorted content to the language file.
        file_put_contents($langfile, $content);

        // Purge language cache so strings aren't re-processed.
        purge_caches(['lang' => true]);
    }

    /**
     * Generate a PHP strings file given an array of strings and a plugin name.
     *
     * @param array $strings The strings to include, in the form [key] => string
     * @param string $plugin The plugin component name (ex local_bftranslate).
     * @return string The PHP code for a strings file.
     */
    public static function generate_strings_file(array $strings, string $plugin): string {
        $copyright = get_string('copyright_text', 'local_bftranslate');

        // Prepare language file content.
        $content = "<?php\n";
        $content .= "// This file is part of Moodle - http://moodle.org/\n//\n";
        $content .= "// Moodle is free software: you can redistribute it and/or modify\n";
        $content .= "// it under the terms of the GNU General Public License as published by\n";
        $content .= "// the Free Software Foundation, either version 3 of the License, or\n";
        $content .= "// (at your option) any later version.\n//\n";
        $content .= "// Moodle is distributed in the hope that it will be useful,\n";
        $content .= "// but WITHOUT ANY WARRANTY; without even the implied warranty of\n";
        $content .= "// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the\n";
        $content .= "// GNU General Public License for more details.\n//\n";
        $content .= "// You should have received a copy of the GNU General Public License\n";
        $content .= "// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.\n\n";
        $content .= "/**\n * Language strings for {$plugin}.\n";
        $content .= " *\n * @package    {$plugin}\n * @category   string\n";
        $content .= " * @copyright  {$copyright}\n * @license    ";
        $content .= "http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later\n */\n\n";
        $content .= "defined('MOODLE_INTERNAL') || die();\n\n";

        foreach ($strings as $key => $value) {
            // Do some processing on $value here, to maintain placeholders and ONLY escape single quotes.
            $value = str_replace(['\'', 'a-&gt;'], ['\\\'', 'a->'], $value);
            $content .= "\$string['" . $key . "'] = '" . $value . "';\n";
        }

        $content .= "\n"; // Include newline at end.
        return $content;
    }

    /**
     * Handles lang file name for plugins.
     *
     * @param string $plugin
     * @return string $langfilename
     */
    public static function get_langfilename(string $plugin): string {
        $parts = explode('_', $plugin, 2);

        if ($parts[0] == 'mod') {
            $plugin = $parts[1] . '.php';
        } else {
            $plugin = $plugin . '.php';
        }

        return $plugin;
    }

    /**
     * Handles checking lang support for apis.
     *
     * @param string $api
     * @param string $targetlang
     * @return bool
     */
    public static function lang_supported(string $api, string $targetlang): bool {
        // Check targetlang is supported on API.

        $config = get_config('local_bftranslate');
        $test = ['test' => 'test'];
        $supported = false;

        $targetlang = static::get_language_mapped($api, $targetlang);
        if ($api == 'azure') {
            $work = new azure_translator($config->azure_api_key);
            list($results, $errors) = $work->translate_batch($test, $targetlang);
            $errorfound = (isset($errors['test']) && stripos($errors['test'], '400036'));
            if ($errorfound === false) {
                return true;
            }
        } else if ($api == 'localtest') {
            return true;
        } else {
            $work = new deepl_translator($config->deepl_api_key);
            list($results, $errors) = $work->translate_batch($test, $targetlang);
            $errorfound = (isset($errors['test']) && stripos($errors['test'], 'Value for \'target_lang\' not supported.'));
            if ($errorfound === false) {
                return true;
            }
        }

        return $supported;
    }
}

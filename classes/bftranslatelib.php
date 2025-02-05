<?php

namespace local_bftranslate;

use local_bftranslate\deepl_translator;

class bftranslatelib {

    public static function get_plugins() {
        $plugins = [
            'tool_bfplus',
            'local_bfaltformat',
            'block_bfmanager',
            'filter_bfurltolink',
            'atto_bfimage',
            'atto_bflink',
            'report_editlicence',
            'local_bfnewwindow',
            'local_bfguides',
        ];

        return $plugins;
    }

    public static function get_plugins_dropdown() {
        $bfplugins = static::get_plugins();

        $plugins = ['' => get_string('select')];
        foreach ($bfplugins as $key => $bfplugin) {
            $plugins[$bfplugin] = get_string('pluginname', $bfplugin);
        }

        return $plugins;
    }

    public static function process_translation($plugin, $targetlang) {

        $config = get_config('local_bftranslate');
        $info = \core_plugin_manager::instance()->get_plugin_info($plugin);
        if (!file_exists($info->rootdir .'/lang/en/' . $plugin . '.php')) {
            return [];
        }

        // Load all strings from the English language pack
        $englishstrings = get_string_manager()->load_component_strings($plugin, 'en');
        $targetstrings = get_string_manager()->load_component_strings($plugin, $targetlang);

        $englishstrings = array_slice($englishstrings, 0, 10);

        $missing = [];
        foreach ($englishstrings as $key => $string) {
            //echo ('key '.$key.', ');
            // Check if the string is missing or empty or identical in the target language
            if ((!isset($targetstrings[$key])
                || empty(trim($targetstrings[$key])))
                || ($targetstrings[$key] == $string)) {
                $missing[$key] = $string;
            }
        }
        //print_r($englishstrings);
        //print_r($targetstrings);
        print_r($missing);
        echo('<hr><hr>');

        $work = new deepl_translator($config->deepl_api_key);
        $work->translate_batch($missing, $targetlang);
        print_r($work);
    }
}


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

use local_bftranslate\bftranslatelib;

/**
 * Tests for Brickfield Plugin Translator
 *
 * @package    local_bftranslate
 * @group      local_bftranslate
 * @category   test
 * @copyright  2025 onward: Brickfield Education Labs, www.brickfield.ie
 * @author     Jay Churchward <jay@brickfieldlabs.ie>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class bftranslatelib_test extends \advanced_testcase {

    /**
     * Installs language packs.
     *
     * @return void
     */
    protected static function install_languages(): void {
        $controller = new \tool_langimport\controller();
        $installedpacks = $controller->install_languagepacks(['fr', 'ga']);

        if ($installedpacks !== 2) {
            throw new \moodle_exception("Failed to install language packs");
        }
    }

    /**
     * Test get_plugins()
     *
     * @covers \local_bftranslate\bftranslatelib::get_plugins
     *
     * @return void
     */
    public function test_get_plugins() {
        $this->resetAfterTest();
        $this->preventResetByRollback();

        // Need to configure this plugin for detection.
        set_config('allowcoretranslation', 0, 'local_bftranslate');
        set_config('external_plugins', 'local_bftranslate', 'local_bftranslate');

        $results = bftranslatelib::get_plugins();

        $this->assertIsArray($results);
        $this->assertContains('local_bftranslate', $results);
    }

    /**
     * Test get_plugins_dropdown_array
     *
     * @covers \local_bftranslate\bftranslatelib::get_plugins_dropdown_array
     * @covers \local_bftranslate\bftranslatelib::get_plugins
     *
     * @return void
     */
    public function test_get_plugins_dropdown_array() {
        $this->resetAfterTest();
        $this->preventResetByRollback();

        // Need to configure this plugin for detection.
        set_config('allowcoretranslation', 0, 'local_bftranslate');
        set_config('external_plugins', 'local_bftranslate', 'local_bftranslate');

        $results = bftranslatelib::get_plugins_dropdown_array();

        $label = get_string('pluginname', 'local_bftranslate') . ' (local_bftranslate)';
        $this->assertIsArray($results);
        $this->assertContains('Select', $results);
        $this->assertContains($label, $results);
    }

    /**
     * Test get_languages()
     *
     * @covers \local_bftranslate\bftranslatelib::get_languages
     *
     * @return void
     */
    public function test_get_languages() {
        $results = bftranslatelib::get_languages();

        $this->assertIsArray($results);
        $this->assertArrayHasKey('ar', $results);
        $this->assertArrayHasKey('de', $results);
        $this->assertArrayHasKey('fr', $results);
    }

    /**
     * Test get_language_mappings()
     *
     * @covers \local_bftranslate\bftranslatelib::get_language_mappings
     *
     * @return void
     */
    public function test_get_language_mappings() {
        $results = bftranslatelib::get_language_mappings('azure');

        $this->assertIsArray($results);
        $this->assertArrayHasKey('pt', $results);
        $this->assertContains('pr-PT', $results);
        $this->assertArrayHasKey('fr_ca', $results);
        $this->assertContains('fr-CA', $results);
    }

    /**
     * Test get_installed_languages()
     *
     * @covers \local_bftranslate\bftranslatelib::get_installed_languages
     * @covers \local_bftranslate\bftranslatelib::get_language_mappings
     *
     * @return void
     */
    public function test_get_installed_languages() {
        self::install_languages();
        $results = bftranslatelib::get_installed_languages();

        $this->assertIsArray($results);
        $this->assertArrayHasKey('en', $results);
        $this->assertArrayHasKey('fr', $results);
        $this->assertArrayHasKey('ga', $results);
    }

    /**
     * Test get_languages_dropdown_array()
     *
     * @covers \local_bftranslate\bftranslatelib::get_languages_dropdown_array
     * @covers \local_bftranslate\bftranslatelib::get_languages
     * @covers \local_bftranslate\bftranslatelib::get_installed_languages
     *
     * @return void
     */
    public function test_get_languages_dropdown_array() {
        self::install_languages();
        $results = bftranslatelib::get_languages_dropdown_array();

        $this->assertIsArray($results);
        $this->assertContains('Select', $results);
        $this->assertArrayHasKey('fr', $results);
        $this->assertArrayHasKey('ga', $results);
    }

    /**
     * Test process_translation()
     *
     * @covers \local_bftranslate\bftranslatelib::process_translation
     */
    public function test_process_translation() {
        self::install_languages();

        // Create sample string arrays.
        $engstrings = [
            'pluginname' => 'String Translator',
            'greetings' => 'Hello, how are you?',
            'response' => 'I am good, thank you.',
            'farewell' => 'Have a good evening.',
            'goodbye' => 'Goodbye.',
        ];
        $targstrings = [
            'pluginname' => 'Traducteur de chaînes',
            'greetings' => 'Bonjour comment allez-vous?',
            'response' => 'Je vais bien, merci.',
            'farewell' => 'Passe une bonne soirée.',
            'goodbye' => 'Au revoir.',
        ];

        // Set sample form data.
        $formdata = new \stdClass();
        $formdata->selectapi = 'deepl';
        $formdata->plugin = 'local_bftranslate';
        $formdata->targetlang = 'fr';
        $formdata->batchlimit = '5';
        $formdata->selectoutput = 'table';
        $formdata->submitbutton = 'translate';

        // Test with existing strings - should not perform a translation.
        $results = self::mock_process_translation($formdata, $engstrings, $targstrings);
        $this->assertIsArray($results);
        $this->assertEmpty($results);

        // Test with missing strings.
        $results = self::mock_process_translation($formdata, $engstrings, []);
        $this->assertIsArray($results);
        $this->assertEquals($engstrings, $results[0]);
        $this->assertEquals($targstrings, $results[1]);

        // Test smaller batch.
        $formdata->batchlimit = 2;
        $results = self::mock_process_translation($formdata, $engstrings, []);
        $this->assertIsArray($results);
        $this->assertCount(2, $results[0]);
    }

    /**
     * Mock function for bftranslatelib::process_translation
     *
     * @param object $formdata
     * @return array
     */
    protected static function mock_process_translation(object $formdata, array $engstrings, array $targstrings): array {
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
        $englishstrings = $engstrings;
        $targetstrings = $targstrings;

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

        // Mimic the translation to avoid constant API calls.
        if ($api == 'azure') {
            $results = [
                'pluginname' => 'Traducteur de chaînes',
                'greetings' => 'Bonjour comment allez-vous?',
                'response' => 'Je vais bien, merci.',
                'farewell' => 'Passe une bonne soirée.',
                'goodbye' => 'Au revoir.',
            ];
        } else {
            $results = [
                'pluginname' => 'Traducteur de chaînes',
                'greetings' => 'Bonjour comment allez-vous?',
                'response' => 'Je vais bien, merci.',
                'farewell' => 'Passe une bonne soirée.',
                'goodbye' => 'Au revoir.',
            ];
        }

        return [$missing, $results];
    }
}
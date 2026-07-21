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
final class bftranslatelib_test extends \advanced_testcase {
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
     * Test get_language_mapped()
     *
     * @covers \local_bftranslate\bftranslatelib::get_language_mapped
     *
     * @return void
     */
    public function test_get_language_mapped(): void {
        $result = bftranslatelib::get_language_mapped('azure', 'pt');
        $this->assertEquals($result, 'pt-pt');

        $result = bftranslatelib::get_language_mapped('azure', 'fr_ca');
        $this->assertEquals($result, 'fr-ca');

        $result = bftranslatelib::get_language_mapped('azure', 'ca_wp');
        $this->assertEquals($result, 'ca');
    }

    /**
     * Test get_installed_languages()
     *
     * @covers \local_bftranslate\bftranslatelib::get_installed_languages
     *
     * @return void
     */
    public function test_get_installed_languages(): void {
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
     * @covers \local_bftranslate\bftranslatelib::get_installed_languages
     *
     * @return void
     */
    public function test_get_languages_dropdown_array(): void {
        self::install_languages();
        $results = bftranslatelib::get_languages_dropdown_array();

        $this->assertIsArray($results);
        $this->assertContains('Select', $results);
        $this->assertArrayHasKey('fr', $results);
        $this->assertArrayHasKey('ga', $results);
    }

    /**
     * Data provider for language code mapping.
     *
     * @return array
     */
    public static function language_mapped_provider(): array {
        return [
            'deepl en' => ['deepl', 'en', 'en-gb'],
            'deepl pt' => ['deepl', 'pt', 'pt-pt'],
            'deepl pt_br' => ['deepl', 'pt_br', 'pt-br'],
            'deepl zh_cn' => ['deepl', 'zh_cn', 'zh-hans'],
            'deepl zh_tw' => ['deepl', 'zh_tw', 'zh-hant'],
            'deepl _wp stripped then mapped' => ['deepl', 'pt_wp', 'pt-pt'],
            'deepl passthrough' => ['deepl', 'ga', 'ga'],
            'azure fr_ca' => ['azure', 'fr_ca', 'fr-ca'],
            'azure no' => ['azure', 'no', 'nb'],
            'azure zh_cn' => ['azure', 'zh_cn', 'zh-Hans'],
            'azure sr_cr' => ['azure', 'sr_cr', 'sr-Cyrl'],
            'azure passthrough' => ['azure', 'de', 'de'],
            'unknown api passthrough' => ['localtest', 'en', 'en'],
        ];
    }

    /**
     * Test get_language_mapped() across APIs, the _wp suffix, and passthrough.
     *
     * @dataProvider language_mapped_provider
     * @covers \local_bftranslate\bftranslatelib::get_language_mapped
     *
     * @param string $api The translation API.
     * @param string $lang The Moodle language code.
     * @param string $expected The expected mapped code.
     * @return void
     */
    public function test_get_language_mapped_cases(string $api, string $lang, string $expected): void {
        $this->assertSame($expected, bftranslatelib::get_language_mapped($api, $lang));
    }

    /**
     * Data provider for language file names.
     *
     * @return array
     */
    public static function langfilename_provider(): array {
        return [
            'activity module strips prefix' => ['mod_forum', 'forum.php'],
            'multi-part module keeps remainder' => ['mod_data_field_x', 'data_field_x.php'],
            'local plugin keeps full name' => ['local_bftranslate', 'local_bftranslate.php'],
            'block keeps full name' => ['block_html', 'block_html.php'],
        ];
    }

    /**
     * Test get_langfilename() prefix handling.
     *
     * @dataProvider langfilename_provider
     * @covers \local_bftranslate\bftranslatelib::get_langfilename
     *
     * @param string $plugin The component name.
     * @param string $expected The expected file name.
     * @return void
     */
    public function test_get_langfilename(string $plugin, string $expected): void {
        $this->assertSame($expected, bftranslatelib::get_langfilename($plugin));
    }

    /**
     * A request-controlled translation key cannot break out of the generated file.
     *
     * @covers \local_bftranslate\bftranslatelib::generate_strings_file
     * @return void
     */
    public function test_generate_strings_file_escapes_injection_key(): void {
        $this->resetAfterTest();
        $evilkey = "evil'];system('id');//";
        $content = bftranslatelib::generate_strings_file([$evilkey => 'safe'], 'local_bftranslate', 'en', false);

        $file = make_request_directory() . '/gen.php';
        file_put_contents($file, $content);
        $string = [];
        include($file);

        // The key is stored verbatim as data, proving it did not break out into code.
        $this->assertArrayHasKey($evilkey, $string);
        $this->assertSame('safe', $string[$evilkey]);
    }

    /**
     * A value ending in a backslash cannot escape the closing quote.
     *
     * @covers \local_bftranslate\bftranslatelib::generate_strings_file
     * @return void
     */
    public function test_generate_strings_file_escapes_injection_value(): void {
        $this->resetAfterTest();
        $content = bftranslatelib::generate_strings_file(['trailing' => 'value\\'], 'local_bftranslate', 'en', false);

        $file = make_request_directory() . '/gen.php';
        file_put_contents($file, $content);
        $string = [];
        include($file);

        $this->assertSame('value\\', $string['trailing']);
    }

    /**
     * Placeholders and encoded placeholder forms survive generation intact.
     *
     * @covers \local_bftranslate\bftranslatelib::generate_strings_file
     * @return void
     */
    public function test_generate_strings_file_preserves_placeholders(): void {
        $this->resetAfterTest();
        $strings = [
            'placeholder' => 'Hello {$a->name}',
            'encodedarrow' => 'a-&gt;b',
            'encodedbraces' => '%7Bx%7D;',
        ];
        $content = bftranslatelib::generate_strings_file($strings, 'local_bftranslate', 'en', false);

        $file = make_request_directory() . '/gen.php';
        file_put_contents($file, $content);
        $string = [];
        include($file);

        $this->assertSame('Hello {$a->name}', $string['placeholder']);
        $this->assertSame('a->b', $string['encodedarrow']);
        $this->assertSame('{x}', $string['encodedbraces']);
    }

    /**
     * get_langfile() sanitises the language code and plugin so no path traversal is possible.
     *
     * @covers \local_bftranslate\bftranslatelib::get_langfile
     * @return void
     */
    public function test_get_langfile_blocks_path_traversal(): void {
        global $CFG;
        $this->resetAfterTest();

        $bytargetlang = bftranslatelib::get_langfile('local_bftranslate', '../../../etc/evil');
        $this->assertStringStartsWith($CFG->dataroot . '/lang/', $bytargetlang);
        $this->assertStringNotContainsString('..', $bytargetlang);

        $byplugin = bftranslatelib::get_langfile('../../evil', 'fr');
        $this->assertStringStartsWith($CFG->dataroot . '/lang/', $byplugin);
        $this->assertStringNotContainsString('..', $byplugin);
    }

    /**
     * Test the batch/missing-string selection logic used by process_translation().
     *
     * Note: this exercises a local reimplementation (mock_process_translation) to
     * avoid live API calls; it does not cover the real process_translation method.
     * Direct coverage of that method is tracked for the PHPUnit test work.
     */
    public function test_process_translation(): void {
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
     * @param array $engstrings
     * @param array $targstrings
     * @return array
     */
    protected static function mock_process_translation(object $formdata, array $engstrings, array $targstrings): array {
        $plugin = $formdata->plugin;
        $targetlang = $formdata->targetlang;
        $api = $formdata->selectapi;
        $batchlimit = $formdata->batchlimit;

        $config = get_config('local_bftranslate');
        $info = \core_plugin_manager::instance()->get_plugin_info($plugin);
        if (!file_exists($info->rootdir . '/lang/en/' . $plugin . '.php')) {
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
            if (
                (!isset($targetstrings[$key])
                || empty(trim($targetstrings[$key])))
                || ($targetstrings[$key] == $string)
            ) {
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

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

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir . '/tablelib.php');

/**
 * Defines the table used in the local_bftranslate display.
 *
 * @package    local_bftranslate
 * @author     Karen Holland <karen@brickfieldlabs.ie>
 * @copyright  2025 onward Brickfield Education Labs Ltd, https://www.brickfield.ie
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class displaytable extends \flexible_table {
    /** @var stdClass Course report filters. */
    protected $filters;

    /** @var array The results data. */
    protected $results;

    /** @var int Sorting order. */
    protected $tdir;

    /** @var string Column to sort by. */
    protected $tsort;

    /**
     * Constructor.
     * @param string $uniqueid
     * @param stdClass $filters
     * @param array $results
     * @param string $url
     */
    public function __construct($uniqueid, $filters, $results, $url) {

        $this->filters = $filters;
        $this->results = $results;
        $this->define_baseurl($url);
        parent::__construct($uniqueid);

        $languages = bftranslatelib::get_installed_languages();
        // Array flip to map external lang codes back to Moodle lang codes for display.
        $mappings = array_flip(bftranslatelib::get_language_mappings());

        $this->tdir = optional_param($this->request[TABLE_VAR_DIR], '', PARAM_INT);
        $this->tsort = optional_param($this->request[TABLE_VAR_SORT], '', PARAM_ALPHANUMEXT);
        $langdesc = $languages[$results['targetlang']] ?? $languages[$mappings[$results['targetlang']]];
        if ($results['plugin'] !== '') {
            $captionparams = ['plugin' => get_string('pluginname', $results['plugin']),
                'targetlang' => $langdesc];
            $caption = get_string('tablecaption', 'local_bftranslate', $captionparams);
        } else {
            $caption = get_string('tablecaption_snippet', 'local_bftranslate', $langdesc);
        }
        $this->set_caption($caption, []);

        if (get_config('local_bftranslate', 'pagesize') > 0) {
            $this->use_pages = true;
        }

        // if ($this->use_pages) {
            // Setting pagesize for pagination.
            // $this->pagesize(get_config('local_bftranslate', 'pagesize'), count($this->users));
        // }

        // Setting up the tables columns.
        $columns = [
            'key',
            'sourcestring',
            'targetstring',
        ];
        if ($results['selectoutput'] == 'langstring') {
            $columns = ['langstringformat'];
        }

        $this->define_columns($columns);
        $this->no_sorting('all');

        // Setting up the tables headers.
        $headers = [
            get_string('tableheader:key', 'local_bftranslate'),
            get_string('tableheader:sourcestring', 'local_bftranslate'),
            get_string('tableheader:targetstring', 'local_bftranslate'),
        ];
        if ($results['selectoutput'] == 'langstring') {
            $headers = [get_string('tableheader:langstring', 'local_bftranslate')];
        }

        $this->define_headers($headers);

        // Applying general table classes.
        $this->set_attribute('class', 'generaltable generalbox');

        $testdata = [];
        foreach ($results['source'] as $key => $string) {
            $row = ['key' => $key,
                'sourcestring' => $string,
                'targetstring' => $results['results'][$key]];
            if ($results['selectoutput'] == 'langstring') {
                $langdata = [
                    'key' => $key,
                    'value' => str_replace('\'', '\\\'', $results['results'][$key]),
                ];
                $row = ['langstringformat' => s(get_string('langstringformat', 'local_bftranslate', $langdata))];
            }
            $testdata[] = $row;
        }
        $this->setup();
        $this->format_and_add_array_of_rows($testdata);
        echo('<hr><hr>');

        $this->is_downloading(
            optional_param('download', '', PARAM_ALPHA),
            get_string('filename', 'local_bftranslate'),
            get_string('reporttitle', 'local_bftranslate')
        );
    }
}

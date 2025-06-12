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

    /** @var \local_bftranslate\displaytablestate State data. */
    protected $state;

    /** @var int Sorting order. */
    protected $tdir;

    /** @var string Column to sort by. */
    protected $tsort;

    /**
     * Constructor.
     * @param string $uniqueid
     * @param \local_bftranslate\displaytablestate $state
     * @param string $url
     */
    public function __construct(\local_bftranslate\displaytablestate $state, $url) {
        $this->state = $state;
        $this->define_baseurl($url);
        parent::__construct('local_bftranslate_displaytable');

        $languages = bftranslatelib::get_installed_languages();
        $this->tdir = optional_param($this->request[TABLE_VAR_DIR], '', PARAM_INT);
        $this->tsort = optional_param($this->request[TABLE_VAR_SORT], '', PARAM_ALPHANUMEXT);
        $langdesc = $languages[$this->state->targetlang];
        $captionparams = ['plugin' => get_string('pluginname', $this->state->current_plugin()), 'targetlang' => $langdesc];
        $caption = get_string('tablecaption', 'local_bftranslate', $captionparams);
        $this->set_caption($caption, []);

        if (get_config('local_bftranslate', 'pagesize') > 0) {
            $this->use_pages = true;
        }

        // Setting up the tables columns.
        $columns = [
            'key',
            'sourcestring',
            'targetstring',
        ];
        if ($this->state->selectoutput == 'langstring') {
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
        if ($this->state->selectoutput == 'langstring') {
            $headers = [get_string('tableheader:langstring', 'local_bftranslate')];
        }

        $this->define_headers($headers);

        // Applying general table classes.
        $this->set_attribute('class', 'generaltable generalbox');

        $testdata = [];
        $matching = [];
        foreach ($this->state->source as $key => $string) {
            if (!isset($this->state->results[$key])) {
                continue;
            }
            if ($string == $this->state->results[$key]) {
                $matching[] = $string;
            }

            $row = [
                'key' => $key,
                'sourcestring' => $string,
                'targetstring' => s($this->state->results[$key]),
            ];
            if ($this->state->selectoutput == 'langstring') {
                $langdata = [
                    'key' => $key,
                    'value' => str_replace('\'', '\\\'', $this->state->results[$key]),
                ];
                $row = ['langstringformat' => s(get_string('langstringformat', 'local_bftranslate', $langdata))];
            }
            $testdata[] = $row;
        }
        $this->setup();
        // Detect if any matching strings have been detected.
        if (count($matching) > 0) {
            $matchingstr = implode(', ', $matching);
            echo \html_writer::tag('div', get_string('matchingstrings', 'local_bftranslate',
                $matchingstr), ['class' => 'alert alert-warning']);
        }
        echo \html_writer::start_tag('form', ['method' => 'post', 'action' => $url]);
        echo \html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'sesskey', 'value' => sesskey()]);
        echo \html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'state', 'value' => $this->state->encode()]);
        $this->format_and_add_array_of_rows($testdata);

        // Submit buttons.
        echo \html_writer::start_div('form-group');
        echo \html_writer::start_div('form-group', ['style' => 'margin-top:10px;display:flex;flex-flow:row wrap;gap:10px']);
        echo \html_writer::tag('button', get_string('savechanges', 'local_bftranslate'), [
            'type' => 'submit',
            'name' => 'doaction',
            'value' => 'save',
            'class' => 'btn btn-primary',
        ]);
        switch ($this->state->selectoutput) {
            case 'langstring':
                echo \html_writer::tag('button', get_string('switchview-table', 'local_bftranslate'), [
                    'type' => 'submit',
                    'name' => 'doaction',
                    'value' => 'switchview-table',
                    'class' => 'btn btn-primary',
                ]);
                break;

            case 'table':
                echo \html_writer::tag('button', get_string('switchview-langstring', 'local_bftranslate'), [
                    'type' => 'submit',
                    'name' => 'doaction',
                    'value' => 'switchview-langstring',
                    'class' => 'btn btn-primary',
                ]);
                break;
        }
        echo \html_writer::span('', '', ['style' => 'flex-grow: 1']);
        if (!empty($this->state->next_plugin())) {
            echo \html_writer::tag('button', get_string('nextplugin', 'local_bftranslate', ['plugin' => $this->state->next_plugin()]), [
                'type' => 'submit',
                'name' => 'doaction',
                'value' => 'nextplugin',
                'class' => 'btn btn-primary',
            ]);
        }
        echo \html_writer::end_div();
        echo \html_writer::end_div();
        echo \html_writer::end_tag('form');
        echo('<hr><hr>');

        $this->is_downloading(
            optional_param('download', '', PARAM_ALPHA),
            get_string('filename', 'local_bftranslate'),
            get_string('reporttitle', 'local_bftranslate')
        );
    }
}

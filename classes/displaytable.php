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

defined('MOODLE_INTERNAL') || die();

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

    /** @var bool Show existing translations. */
    protected $showexisting;

    /** @var int Sorting order. */
    protected $tdir;

    /** @var string Column to sort by. */
    protected $tsort;

    /**
     * Constructor.
     * @param \local_bftranslate\displaytablestate $state
     * @param \moodle_url $url
     * @param bool $showexisting
     */
    public function __construct(\local_bftranslate\displaytablestate $state, \moodle_url $url, bool $showexisting) {
        $this->state = $state;
        $this->define_baseurl($url);
        $this->showexisting = $showexisting;
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

        // Setting up the tables headers.
        $headers = [
            get_string('tableheader:key', 'local_bftranslate'),
            get_string('tableheader:sourcestring', 'local_bftranslate'),
            get_string('tableheader:targetstring', 'local_bftranslate'),
        ];

        $fullsource = $this->state->source;
        $fullresults = $this->state->results;
        if ($this->showexisting) {
            // Adding existing status to table.
            array_push($columns, 'status');
            array_push($headers, get_string('tableheader:status', 'local_bftranslate'));

            // Merging API translations and existing translations.
            $fullsource = $this->state->source + $this->state->existing;
            $fullresults = $this->state->results + $this->state->existingtranslated;
            ksort($fullsource);

            // Getting status strings.
            $existing = get_string('status:existing', 'local_bftranslate');
            $new = get_string('status:new', 'local_bftranslate');
        }

        $this->define_columns($columns);
        $this->no_sorting('all');
        $this->define_headers($headers);

        // Applying general table classes.
        $this->set_attribute('class', 'generaltable generalbox');

        $rows = [];
        $matching = [];
        foreach ($fullsource as $key => $string) {
            if (!isset($fullresults[$key])) {
                continue;
            }
            if ($string == $fullresults[$key]) {
                $matching[] = $string;
            }

            // Encode key in case of special chars.
            $encodedkey = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($key));
            $row = [
                'key' => s($key),
                'sourcestring' => s($string),
                'targetstring' => \html_writer::tag('textarea', s($fullresults[$key]), [
                    'name' => "translations[" . $encodedkey . "]",
                    'aria-label' => get_string('translationlabel', 'local_bftranslate', $key),
                    'rows' => 2,
                    'cols' => 50,
                ]),
            ];

            if ($this->showexisting) {
                $row['status'] = isset($this->state->existing[$key]) ? $existing : $new;
            }

            $rows[] = $row;
        }
        $this->setup();
        // Detect if any matching strings have been detected.
        if (count($matching) > 0) {
            $matchingstr = implode(', ', array_map('s', $matching));
            echo \html_writer::tag(
                'div',
                get_string('matchingstrings', 'local_bftranslate', $matchingstr),
                ['class' => 'alert alert-warning']
            );
        }
        echo \html_writer::start_tag('form', ['method' => 'post', 'action' => $url]);
        echo \html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'sesskey', 'value' => sesskey()]);
        echo \html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'state', 'value' => $this->state->encode()]);
        $this->format_and_add_array_of_rows($rows);

        // Submit buttons.
        echo \html_writer::start_div('form-group');
        echo \html_writer::start_div('form-group local_bftranslate_buttonrow');
        if (!empty($this->state->results) || $this->state->showexisting) {
            echo \html_writer::tag('button', get_string('savechanges', 'local_bftranslate'), [
                'type' => 'submit',
                'name' => 'doaction',
                'value' => 'save',
                'class' => 'btn btn-primary',
            ]);
            echo \html_writer::tag('button', get_string('switchview-langstring', 'local_bftranslate'), [
                'type' => 'submit',
                'name' => 'doaction',
                'value' => 'switchview-langstring',
                'class' => 'btn btn-primary',
            ]);
        }
        echo \html_writer::span('', 'local_bftranslate_spacer');
        if (!empty($this->state->next_plugin())) {
            echo \html_writer::tag(
                'button',
                get_string('nextplugin', 'local_bftranslate', ['plugin' => $this->state->next_plugin()]),
                [
                    'type' => 'submit',
                    'name' => 'doaction',
                    'value' => 'nextplugin',
                    'class' => 'btn btn-primary',
                ]
            );
        }
        echo \html_writer::end_div();
        echo \html_writer::end_div();
        echo \html_writer::end_tag('form');
        echo \html_writer::empty_tag('hr');

        $this->is_downloading(
            optional_param('download', '', PARAM_ALPHA),
            get_string('filename', 'local_bftranslate'),
            get_string('reporttitle', 'local_bftranslate')
        );
    }
}

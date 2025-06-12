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

/**
 * State data for the displaytable class.
 *
 * @package    local_bftranslate
 * @copyright  2025 onward Brickfield Education Labs Ltd, https://www.brickfield.ie
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class displaytablestate {
    /** @var array Array of all plugins requested. */
    public array $requestedplugins;

    /** @var int Array of plugins in the queue. */
    public int $currentpluginindex;

    /** @var string The language to translate into. */
    public string $targetlang;

    /** @var array The source language strings. Array like [language key] => language string. */
    public array $source;

    /** @var array The translated language strings. Array like [language key] => language string. */
    public array $results;

    /** @var string The selected translation API. */
    public string $selectapi;

    /** @var int The batch limit. 0 = no limit. */
    public int $batchlimit;

    /**
     * Constructor.
     *
     * @param array $requestedplugins Array of plugins in the queue.
     * @param int $currentpluginindex The index (in $requestedplugins) of the current plugin being translated.
     * @param string $targetlang The language to translate into.
     * @param array $source The source language strings. Array like [language key] => language string.
     * @param array $results The translated language strings. Array like [language key] => language string.
     * @param string $selectapi The selected translation API.
     * @param int $batchlimit The batch limit. 0 = no limit.
     */
    public function __construct(array $requestedplugins = [], int $currentpluginindex = 0, string $targetlang = '',
                                array $source = [], array $results = [], string $selectapi = '', int $batchlimit = 5) {
        $this->requestedplugins = $requestedplugins;
        $this->currentpluginindex = $currentpluginindex;
        $this->targetlang = $targetlang;
        $this->source = $source;
        $this->results = $results;
        $this->selectapi = $selectapi;
        $this->batchlimit = $batchlimit;
    }

    /**
     * Get the current plugin based on the configured plugin list and index.
     *
     * @return string The current plugin.
     */
    public function current_plugin(): string {
        return (isset($this->requestedplugins[$this->currentpluginindex]))
            ? $this->requestedplugins[$this->currentpluginindex]
            : $this->requestedplugins[0];
    }

    /**
     * Get the name of the next plugin based on the configured plugin list and index.
     *
     * @return ?string Array of next plugins.
     */
    public function next_plugin(): ?string {
        return (isset($this->requestedplugins[$this->currentpluginindex + 1]))
            ? $this->requestedplugins[$this->currentpluginindex + 1]
            : null;
    }

    /**
     * Get a list of next plugins based on the configured plugin list and index.
     *
     * @return array Array of next plugins.
     */
    public function next_plugins(): array {
        return array_slice($this->requestedplugins, $this->currentpluginindex);
    }

    /**
     * Encode this object into a compressed string suitable for POST transport.
     *
     * @return string The encoded data.
     */
    public function encode(): string {
        $statedata = [
            'requestedplugins' => $this->requestedplugins,
            'currentpluginindex' => $this->currentpluginindex,
            'targetlang' => $this->targetlang,
            'source' => $this->source,
            'results' => $this->results,
            'selectapi' => $this->selectapi,
            'batchlimit' => $this->batchlimit,
        ];
        return base64_encode(gzdeflate(json_encode($statedata)));
    }

    /**
     * Encode state as form data. Useful for setting the form to match the state.
     *
     * @return \stdClass Form data.
     */
    public function formdata(): \stdClass {
        return (object)[
            'plugins' => $this->requestedplugins,
            'targetlang' => $this->targetlang,
            'selectapi' => $this->selectapi,
            'batchlimit' => $this->batchlimit,
        ];
    }

    /**
     * Create an instace from an encoded string created by the ::encode method.
     *
     * @param string $encoded The raw encoded data.
     * @return displaytablestate The state object.
     */
    public static function instance_from_encoded(string $encoded): displaytablestate {
        $encoded = json_decode(gzinflate(base64_decode($encoded)), true);

        $requestedplugins = [];
        if (isset($encoded['requestedplugins']) && is_array($encoded['requestedplugins'])) {
            $requestedplugins = $encoded['requestedplugins'];
        }

        $currentpluginindex = 0;
        if (isset($encoded['currentpluginindex']) && is_numeric($encoded['currentpluginindex'])) {
            $currentpluginindex = (int)$encoded['currentpluginindex'];
        }

        $targetlang = '';
        if (!empty($encoded['targetlang']) && is_string($encoded['targetlang'])) {
            $targetlang = $encoded['targetlang'];
        }

        $selectapi = '';
        if (!empty($encoded['selectapi']) && is_string($encoded['selectapi'])) {
            $selectapi = $encoded['selectapi'];
        }

        $batchlimit = 5;
        if (isset($encoded['batchlimit']) && is_numeric($encoded['batchlimit'])) {
            $batchlimit = (int)$encoded['batchlimit'];
        }

        return new displaytablestate(
            $requestedplugins,
            $currentpluginindex,
            $targetlang,
            (isset($encoded['source']) && is_array($encoded['source'])) ? $encoded['source'] : [],
            (isset($encoded['results']) && is_array($encoded['results'])) ? $encoded['results'] : [],
            $selectapi,
            $batchlimit,
        );
    }
}

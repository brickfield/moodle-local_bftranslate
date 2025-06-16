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
 * Language strings for local_bftranslate.
 *
 * @package    local_bftranslate
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['apikey:azure'] = 'Clau API de l\'Azure';
$string['apikey:azure_desc'] = 'Introduïu la clau de l\'API de l\'Azure per habilitar les traduccions.';
$string['apikey:deepl'] = 'Clau de l\'API de DeepL';
$string['apikey:deepl_desc'] = 'Introduïu la vostra clau API de DeepL per habilitar les traduccions.';
$string['branding:academy'] = 'Formació';
$string['branding:assessment'] = 'Bundle d\'Avaluació';
$string['branding:audit'] = 'Auditoria d\'Accessibilitat';
$string['branding:bflogo'] = 'Brickfield Education Labs';
$string['branding:contact'] = 'Poseu-vos en contacte amb Brickfield';
$string['branding:like'] = 'També et pot agradar';
$string['branding:skills'] = 'Bundle d\'Habilitats';
$string['branding:toolkit'] = 'Kit d\'Accessibilitat';
$string['branding:ux'] = 'Bundle d\'Experiència d\'Usuari';
$string['emptyplugin'] = 'No s\'ha enviat cap connector';
$string['emptytargetlang'] = 'No s\'ha enviat cap idioma de destinació';
$string['external_plugins'] = 'Connectors externs';
$string['external_plugins_desc'] = 'Introduïu una llista separada per comes dels connectors externs instal·lats.';
$string['filename'] = 'Descarrega el Traductor';
$string['langnotsupported'] = 'La llengua "{$a}" no és compatible amb aquesta API enviada.';
$string['langstringformat'] = '$string[\'{$a->key}\'] = \'{$a->value}\';';
$string['matchingstrings'] = 'Les cadenes següents són idèntiques a l\'anglès i a l\'idioma de traducció de destinació: {$a}. <br /><br />A causa de la forma en què Moodle utilitza per defecte les cadenes en anglès si no n\'hi ha cap, les cadenes existents idèntiques continuaran mostrant-se com a desaparegudes, així que ignoreu aquestes cadenes coincidents en el processament posterior.';
$string['nextplugin'] = 'Següent connector ({$a->plugin}) →';
$string['nofilefound'] = 'Hi ha hagut un problema en localitzar les cadenes de l\'idioma del connector. Si us plau, consulteu-ho amb l\'administrador del vostre lloc.';
$string['notranslationsneeded'] = 'Totes les cadenes ja existeixen en l\'idioma de destinació.';
$string['pluginname'] = 'Traductor de Connectors de Brickfield';
$string['privacy:nullproviderreason'] = 'El Traductor de Connectors de Brickfield no emmagatzema cap dada personal.';
$string['report:heading'] = 'Pàgina de traducció';
$string['reporttitle'] = 'Descàrrega de l\'Informe del Traductor';
$string['savechanges'] = 'Desa els canvis com a cadenes d\'idioma personalitzades';
$string['selectapi'] = 'Selecciona l\'API';
$string['selectazure'] = 'Azure';
$string['selectdeepl'] = 'DeepL';
$string['selectbatchlimit'] = 'Seleccioneu el límit opcional de registres per lot';
$string['selectlanguage'] = 'Seleccioneu l\'idioma de destinació';
$string['selectlanguage_help'] = 'Inclou idiomes que es troben a la llista permesa i s\'instal·len com a paquets d\'idioma.';
$string['selectlocaltest'] = 'Test local';
$string['selectnoapis'] = 'No hi ha cap API configurada actualment. Si us plau, consulteu amb l\'administrador/a del vostre lloc.';
$string['selectplugin'] = 'Seleccioneu Connector';
$string['selectplugin_help'] = 'Inclou connectors que són core o no core, depenent de les configuracions.<br /><br /> Els connectors que no siguin core han d\'estar instal·lats i a la llista de permesos.';
$string['settings'] = 'Configuració';
$string['settings:allowcoretranslation'] = 'Permetre la traducció del core';
$string['settings:allowcoretranslation_desc'] = 'Permet traduir els connectors del core.';
$string['settings:showlocaltest'] = 'Mostra el traductor "Test local"';
$string['settings:showlocaltest_desc'] = 'El traductor "Test local" realitza una simple transformació Rot13 en cadenes per demostrar el connector sense crides API externes.';
$string['submitsuccess'] = 'S\'han desat les cadenes de traducció enviades.';
$string['switchview-langstring'] = 'Descarregar com a cadenes d\'idioma en PHP';
$string['switchview-table'] = 'Visualitza com a taula';
$string['tablecaption'] = 'Taula per al connector \'{$a->plugin}\' a l\'idioma \'{$a->targetlang}\'';
$string['tableheader:key'] = 'Clau d\'idioma';
$string['tableheader:langstring'] = 'Cadenes d\'idioma';
$string['tableheader:sourcestring'] = 'Cadena d\'origen';
$string['tableheader:targetstring'] = 'Cadena de destinació';
$string['translate'] = 'Tradueix';
$string['translationsuccess'] = 'Traducció realitzada!';

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

$string['apikey:azure'] = 'Clave de la API de Azure';
$string['apikey:azure_desc'] = 'Escriba la clave de la API de Azure para habilitar las traducciones.';
$string['apikey:deepl'] = 'Clave de la API de DeepL';
$string['apikey:deepl_desc'] = 'Introduce la clave de la API de DeepL para habilitar las traducciones.';
$string['branding:academy'] = 'Formación';
$string['branding:assessment'] = 'Bundle de Evaluación';
$string['branding:audit'] = 'Auditoría de Accesibilidad';
$string['branding:bflogo'] = 'Brickfield Education Labs';
$string['branding:contact'] = 'Póngase en contacto con Brickfield';
$string['branding:like'] = 'También te puede interesar';
$string['branding:skills'] = 'Bundle de Habilidades';
$string['branding:toolkit'] = 'Kit de Accesibilidad';
$string['branding:ux'] = 'Bundle de Experiencia de Usuario';
$string['emptyplugin'] = 'No se ha enviado ninguna extensión';
$string['emptytargetlang'] = 'No se ha enviado ningún idioma de destino';
$string['external_plugins'] = 'Extensiones externas';
$string['external_plugins_desc'] = 'Introduzca una lista separada por comas de las extensiones externas instalados.';
$string['filename'] = 'Descargar Traductor';
$string['langnotsupported'] = 'El idioma \'{$a}\' no es compatible con esta API enviada.';
$string['langstringformat'] = '$string[\'{$a->key}\'] = \'{$a->value}\';';
$string['matchingstrings'] = 'Las siguientes cadenas son idénticas en inglés y en el idioma de traducción de destino: {$a}. <br /><br />Debido a la forma en que Moodle usa de forma predeterminada cadenas en inglés si no existe ninguna, las cadenas existentes idénticas continuarán mostrándose como faltantes, así que ignore estas cadenas coincidentes en el procesamiento posterior.';
$string['nextplugin'] = 'Siguiente Extensión ({$a->plugin}) →';
$string['nofilefound'] = 'Hubo un problema al localizar las cadenas de idioma de la extensión. Por favor, consulte con el administrador del sitio.';
$string['notranslationsneeded'] = 'Todas las cadenas ya existen en el idioma de destino.';
$string['pluginname'] = 'Traductor de Extensiones de Brickfield';
$string['privacy:nullproviderreason'] = 'El Traductor de Extensiones de Brickfield no almacena ningún dato personal.';
$string['report:heading'] = 'Página de traducción';
$string['reporttitle'] = 'Descargar Informe del Traductor';
$string['savechanges'] = 'Guardar cambios como cadenas de idioma personalizadas';
$string['selectapi'] = 'Seleccione la API';
$string['selectazure'] = 'Azure';
$string['selectdeepl'] = 'DeepL';
$string['selectbatchlimit'] = 'Seleccione el límite de lote opcional';
$string['selectlanguage'] = 'Seleccione el idioma de destino';
$string['selectlanguage_help'] = 'Incluye los idiomas que están en la lista permitida y que se han instalado como paquetes de idioma.';
$string['selectlocaltest'] = 'Test local';
$string['selectnoapis'] = 'Actualmente no hay ninguna API configurada. Por favor, consulte con el administrador del sitio.';
$string['selectplugin'] = 'Seleccionar extensión';
$string['selectplugin_help'] = 'Incluye extensiones que son core o no-core, dependiendo de las configuraciones.<br /><br /> Las extensiones no core deben estar en la lista de permitidos e instalados.';
$string['settings'] = 'Configuración';
$string['settings:allowcoretranslation'] = 'Permitir traducción del core';
$string['settings:allowcoretranslation_desc'] = 'Permitir que se traduzcan las extensiones del core.';
$string['settings:showlocaltest'] = 'Mostrar el traductor "Test local"';
$string['settings:showlocaltest_desc'] = 'El traductor de "Test local" realiza una transformación Rot13 simple en cadenas para demostrar la extensión sin llamadas API externas.';
$string['submitsuccess'] = 'Las cadenas de traducción enviadas ahora se guardan.';
$string['switchview-langstring'] = 'Descargar como cadenas de idioma PHP';
$string['switchview-table'] = 'Ver como tabla';
$string['tablecaption'] = 'Tabla para la extensión \'{$a->plugin}\' al idioma \'{$a->targetlang}\'';
$string['tableheader:key'] = 'Clave de idioma';
$string['tableheader:langstring'] = 'Cadenas de idioma';
$string['tableheader:sourcestring'] = 'Cadena de origen';
$string['tableheader:targetstring'] = 'Cadena de destino';
$string['translate'] = 'Traducir';
$string['translationsuccess'] = '¡Traducción exitosa!';

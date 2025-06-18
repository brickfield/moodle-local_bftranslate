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
 * @category   string
 * @copyright  2025 onward Brickfield Education Labs Ltd, https://www.brickfield.ie
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['apikey:azure'] = 'Azure API-sleutel';
$string['apikey:azure_desc'] = 'Voer uw Azure API-sleutel in om vertalingen mogelijk te maken.';
$string['apikey:deepl'] = 'DeepL API-sleutel';
$string['apikey:deepl_desc'] = 'Voer uw DeepL API-sleutel in om vertalingen mogelijk te maken.';
$string['branding:academy'] = 'Training Academie';
$string['branding:assessment'] = 'Beoordeling Bundel';
$string['branding:audit'] = 'Audit van de toegankelijkheid';
$string['branding:bflogo'] = 'Brickfield Onderwijs Labs';
$string['branding:contact'] = 'Contact Brickfield';
$string['branding:like'] = 'Misschien vind je dit ook leuk';
$string['branding:skills'] = 'Vaardigheden bundel';
$string['branding:toolkit'] = 'Toolkit voor toegankelijkheid';
$string['branding:ux'] = 'Bundel Gebruikerservaring';
$string['emptyplugin'] = 'Geen plugin ingediend';
$string['emptytargetlang'] = 'Geen doeltaal ingediend';
$string['external_plugins'] = 'Externe plugins';
$string['external_plugins_desc'] = 'Voer een door komma\'s gescheiden lijst van geïnstalleerde externe plug-ins in.';
$string['filename'] = 'Vertaler downloaden';
$string['langnotsupported'] = 'Taal \'{$a}\' wordt niet ondersteund op deze ingediende API.';
$string['langstringformat'] = '$string[\'{$a->key}\'] = \'{$a->value}\';';
$string['matchingstrings'] = 'De volgende tekenreeksen zijn identiek in het Engels en in de doeltaal van de vertaling: {$a}. <br /><br />Vanwege de manier waarop Moodle standaard Engelse tekenreeksen gebruikt, ook als die niet bestaan, zullen identieke bestaande strings zichtbaar blijven als ontbrekend. Dus negeer deze overeenkomende strings bij de latere verwerking.';
$string['nextplugin'] = 'Volgende Plugin ({$a->plugin}) →';
$string['nofilefound'] = 'Er is een probleem opgetreden bij het vinden van de taalreeksen van de plugin. Neem contact op met uw sitebeheerder.';
$string['notranslationsneeded'] = 'Alle tekenreeksen bestaan al in de doeltaal.';
$string['pluginname'] = 'Brickfield Plugin Vertaler';
$string['privacy:nullproviderreason'] = 'De Brickfield Plugin Vertaler slaat geen persoonlijke gegevens op.';
$string['report:heading'] = 'Vertaalpagina';
$string['reporttitle'] = 'Vertaler Rapport downloaden';
$string['savechanges'] = 'Wijzigingen opslaan als aangepaste taalreeksen';
$string['selectapi'] = 'Selecteer API';
$string['selectazure'] = 'Azure';
$string['selectbatchlimit'] = 'Selecteer optionele batch-limiet';
$string['selectdeepl'] = 'DeepL';
$string['selectlanguage'] = 'Selecteer doeltaal';
$string['selectlanguage_help'] = 'Omvat talen die zowel in de lijst voorkomen met toegestane talen alsook in taalpakketten die zijn geïnstalleerd.';
$string['selectlocaltest'] = 'Lokale test';
$string['selectnoapis'] = 'Er zijn momenteel geen API\'s geconfigureerd. Neem contact op met uw sitebeheerder.';
$string['selectplugin'] = 'Selecteer Plugin';
$string['selectplugin_help'] = 'Bevat plugins die ofwel core of non-core zijn, afhankelijk van de configuraties.<br /><br /> Non-core plugins moeten zowel in de toegestane lijst staan alsook geïnstalleerd zijn.';
$string['settings'] = 'Instellingen';
$string['settings:allowcoretranslation'] = 'Kernvertaling toestaan';
$string['settings:allowcoretranslation_desc'] = 'Sta toe dat de core-plugins worden vertaald.';
$string['settings:showlocaltest'] = 'Toon de "Lokale test" vertaler';
$string['settings:showlocaltest_desc'] = 'De "Lokale test"-vertaler voert een eenvoudige Rot13-transformatie uit op tekenreeksen om de plugin te demonstreren zonder externe API-aanroepen.';
$string['submitsuccess'] = 'Ingediende vertaalreeksen worden nu opgeslagen.';
$string['switchview-langstring'] = 'Downloaden als PHP Taalreeksen';
$string['switchview-table'] = 'Bekijk als tabel';
$string['tablecaption'] = 'Tabel voor plugin \'{$a->plugin}\' naar taal \'{$a->targetlang}\'';
$string['tableheader:key'] = 'Taal Sleutel';
$string['tableheader:langstring'] = 'Taalreeksen';
$string['tableheader:sourcestring'] = 'Bron tekenreeks';
$string['tableheader:targetstring'] = 'Doel tekenreeks';
$string['translate'] = 'Vertalen';
$string['translationsuccess'] = 'Vertaling geslaagd!';

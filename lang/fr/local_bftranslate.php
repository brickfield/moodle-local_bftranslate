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
 * French language pack for local_bftranslate.
 *
 * @package    local_bftranslate
 * @category   string
 * @copyright  2025 onward Brickfield Education Labs Ltd, https://www.brickfield.ie
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['apikey:azure'] = 'Clé API Azure';
$string['apikey:azure_desc'] = 'Saisissez votre clé API Azure pour activer les traductions.';
$string['apikey:deepl'] = 'Clé API DeepL';
$string['apikey:deepl_desc'] = 'Saisissez votre clé API DeepL pour activer les traductions.';
$string['apikey:deeplfree'] = 'Clé API DeepL Free';
$string['apikey:deeplfree_desc'] = 'Saisissez votre clé API DeepL Free pour activer les traductions.';
$string['apikey:invalid'] = 'Clé API non valide ({$a})';
$string['bftranslate:viewall'] = 'Voir toutes les fonctionnalités du Plugin Translator';
$string['branding:academy'] = 'Formation';
$string['branding:assessment'] = 'Ensemble d’évaluation';
$string['branding:audit'] = 'Audit d’accessibilité';
$string['branding:bflogo'] = 'Laboratoires d’éducation Brickfield';
$string['branding:contact'] = 'Contactez Brickfield';
$string['branding:like'] = 'Vous aimerez aussi';
$string['branding:skills'] = 'Ensemble de compétences';
$string['branding:toolkit'] = 'Trousse à outils sur l’accessibilité';
$string['branding:ux'] = 'Expérience utilisateur';
$string['copyright_text'] = 'Créé à l\'aide du Plugin Translator de Brickfield https://www.brickfield.ie/brickfield-translator/';
$string['emptyplugin'] = 'Aucun plugin soumis';
$string['emptytargetlang'] = 'Pas de langue cible soumise';
$string['errorsencountered'] = 'Erreurs rencontrées :';
$string['eventrequest_submitted'] = 'Demande de traduction soumise';
$string['eventrequest_submitteddesc'] = 'Demande de traduction soumise, le plugin était \'{$a->plugin}\', la langue était \'{$a->lang}\', l\'API était \'{$a->api}\', le nombre de chaînes soumises était \'{$a->count}\', le statut était \'{$a->status}\'.';
$string['filename'] = 'Télécharger le traducteur';
$string['invalidstatedata'] = 'Les données de la session de traduction n\'ont pas pu être lues. Veuillez recommencer la traduction.';
$string['langnotsupported'] = 'La langue "{$a}" n\'est pas prise en charge par cette API.';
$string['matchingstrings'] = 'Les chaînes suivantes sont identiques en anglais et dans la langue de traduction cible : {$a}. <br /><br />En raison de la façon dont Moodle utilise par défaut les chaînes anglaises lorsqu\'aucune traduction n\'existe, les chaînes identiques existantes continueront d\'apparaître comme manquantes, veuillez donc ignorer ces chaînes correspondantes dans les traitements ultérieurs.';
$string['nextplugin'] = 'Plugin suivant ({$a->plugin}) →';
$string['nofilefound'] = 'Un problème est survenu lors de la localisation des chaînes linguistiques du plugin. Veuillez vérifier auprès de l\'administrateur de votre site.';
$string['notranslationsneeded'] = 'Toutes les chaînes sont déjà disponibles dans la langue cible.';
$string['pluginname'] = 'Plugin Translator de Brickfield';
$string['privacy:nullproviderreason'] = 'Le Plugin Translator de Brickfield ne stocke aucune donnée personnelle.';
$string['report:heading'] = 'Page de traduction';
$string['reporttitle'] = 'Rapport de téléchargement';
$string['request:failure'] = '{$a}';
$string['request:success'] = 'Succès';
$string['savechanges'] = 'Enregistrer les modifications en tant que chaînes linguistiques personnalisées';
$string['selectapi'] = 'Sélectionner l\'API';
$string['selectazure'] = 'Azure';
$string['selectbatchlimit'] = 'Sélectionner une limite de lot (facultatif)';
$string['selectdeepl'] = 'DeepL (en anglais)';
$string['selectdeeplfree'] = 'DeepL Free';
$string['selectlanguage'] = 'Sélectionner la langue cible';
$string['selectlanguage_help'] = 'Inclut les langues installées en tant que packs linguistiques';
$string['selectlocaltest'] = 'Test local';
$string['selectnoapis'] = 'Aucune API n\'est actuellement configurée. Veuillez contacter auprès de l\'administrateur de votre site.';
$string['selectplugin'] = 'Selectionner un plugin';
$string['selectplugin_help'] = 'Inclut les plugins principaux ou externes, selon la configuration.<br /><br /> Les plugins externes doivent figurer dans la liste des plugins autorisés et être installés.';
$string['settings'] = 'Paramètres';
$string['settings:allowcoretranslation'] = 'Autoriser la traduction du système principal';
$string['settings:allowcoretranslation_desc'] = 'Autoriser la traduction des plugins de base.';
$string['settings:showlocaltest'] = 'Afficher le traducteur "Test local"';
$string['settings:showlocaltest_desc'] = 'Le traducteur "Test local" effectue une simple transformation Rot13 sur les chaînes de caractères pour démontrer le plugin sans appel à une API externe.';
$string['showexisting'] = 'Montrer les éléments existants';
$string['showexisting_desc'] = 'Afficher également les chaînes de langues préexistantes';
$string['status:existing'] = 'Existant';
$string['status:new'] = 'Nouveau';
$string['submitsuccess'] = 'Les chaînes de traduction soumises sont désormais sauvegardées.';
$string['switchview-langstring'] = 'Télécharger en tant que chaînes de langues PHP';
$string['tablecaption'] = 'Tableau pour le plugin \'{$a->plugin}\' vers la langue \'{$a->targetlang}\'';
$string['tableheader:key'] = 'Clé de langue';
$string['tableheader:sourcestring'] = 'Chaîne source';
$string['tableheader:status'] = 'Statut';
$string['tableheader:targetstring'] = 'Chaîne cible';
$string['translate'] = 'Traduire';
$string['translateerror'] = 'Erreur reçue de l\'API : {$a}';
$string['translationlabel'] = 'Texte de traduction pour la clé de chaîne {$a}';

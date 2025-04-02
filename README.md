# Brickfield translate local plugin #
Copyright (C) 2025 [Brickfield Education Labs](https://www.brickfield.ie)

## What is Brickield Translate?
Brickfield Translate leverages DeepL and Azure APIs to provide the ability to translate a plugins language strings into any of the supported languages below. 

To setup Brickfield Translate:
1. Navigate to Site administrator > Plugins > Plugin Translator > Settings
2. Enter your DeepL API key to the "DeepL API Key" setting.
3. Enter your Azure API key to the "Azure API Key" setting.
4. Click the "Save changes" button.

To translate a plugins strings:
1. Navigate to Site administrator > Plugins > Plugin Translator > Translation page
2. Select an API to use in the "Select API" dropdown.
3. Select a plugin to translate in the "Select Plugin" dropdown.
   * If a plugin that you would like to translate is not listed, please reference the steps below for adding support for a new plugin.
4. Select the output language in the "Select Target Language" dropdown.
5. Select the batch limit for number of strings to translate in "Select Optional Batch Limit" dropdown.
6. Select how the translations are outputted in the "Select Table or PHP Language String output" dropdown.
   * The table option will display the original and translated strings for comparison.
   * The PHP language string option will provide you a list of the new languages strings.
7. Click the "Translate" button.

To add support for translating a plugin that is not listed by default:
1. Navigate to Site administrator > Plugins > Plugin Translator > Settings
2. Add the plugins component name to the "External Plugins" setting.
3. Click the "Save changes" button.

Currently supported languages: 
* Arabic / العربية (ar)
* Bulgarian / Български (bg)
* Czech / Čeština (cs)
* Danish / Dansk (da)
* German / Deutsch (de)
* Greek / Ελληνικά (el)
* Spanish (international) / Español - Internacional (es)
* Estonian / eesti (et)
* Finnish / Suomi (fi)
* French / Français (fr)
* Irish / Gaeilge (ga)
* Hungarian / magyar (hu)
* Indonesian / Bahasa Indonesia (id)
* Italian / Italiano (it)
* Japanese / 日本語 (ja)
* Korean / 한국어 (ko)
* Lithuanian / Lietuvių (lt)
* Latvian / Latviešu (lv)
* Norwegian Bokmål / Norsk - bokmål (nb)
* Dutch / Nederlands (nl)
* Polish / Polski (pl)
* Portuguese - Brazil / Português - Brasil (pt_br)
* Portuguese / Português - Portugal (pt)
* Romanian / Română (ro)
* Russian / Русский (ru)
* Slovak / Slovenčina (sk)
* Slovenian / Slovenščina (sl)
* Swedish / Svenska (sv)
* Turkish / Türkçe (tr)
* Ukrainian / Українська (uk)

## License ##
2025 Onward [Brickfield Education Labs](https://www.brickfield.ie)

## Version support #
This plugin has been developed to work on Moodle releases 4.01, 4.02, 4.03, 4.04, and 4.05.

## Development ##
This plugin has been developed and is maintained by Brickfield Education Labs.

## Important Links ##
* [Code repository](https://github.com/brickfield/moodle-local_bftranslate)

## Installation ##
* Unzip and copy "bftranslate" folder into Moodle's "local" folder
* Visit admin page to install module

Further installation instructions can be found on the
"[Installing plugins](http://docs.moodle.org/en/Installing_contributed_modules_or_plugins)" Moodle documentation page.
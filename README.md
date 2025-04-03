# Brickfield Translate Plugin #
Copyright (C) 2025 [Brickfield Education Labs](https://www.brickfield.ie)

## What is Brickield Translate?
Brickfield Translate enables the translation of plugin language strings, via the usage of multiple translation APIs, including DeepL and Azure. Translation results can either be displayed as a comparison table between English and new language strings, or as ready-to-go PHP strings for inserting into plugin language files.

To setup Brickfield Translate:
1. Navigate to Site administrator > Plugins > Plugin Translator > Settings.
2. Enter your DeepL API key to the "DeepL API Key" setting.
3. Enter your Azure API key to the "Azure API Key" setting.
4. Click the "Save changes" button.

To translate the strings for a plugin:
1. Navigate to Site administrator > Plugins > Plugin Translator > Translation page.
2. Select an API to use in the "Select API" dropdown.
3. Select a plugin to translate in the "Select Plugin" dropdown.
   * If a plugin that you would like to translate is not listed, please reference the steps further down for adding support for a new plugin.
4. Select the output language in the "Select Target Language" dropdown.
   * Languages will only appear as options if the language packs are installed on the Moodle site.
5. Leave the batch limit at 0 to process all strings, unless a batch limit is required with a large amount of strings to be processed.
6. Select how the translations are outputted in the "Select Table or PHP Language String output" dropdown.
   * The table option will display the original and translated strings for comparison.
   * The PHP language string option will provide you a list of the new languages strings, PHP pre-escaped so they can be copied and pasted directly into the relevant new language file in the plugin repository.
7. Click the "Translate" button.

To add support for translating a plugin that is not listed by default:
1. Navigate to Site administrator > Plugins > Plugin Translator > Settings.
2. Add the plugins component name to the "External Plugins" setting.
3. Click the "Save changes" button.

To translate a plugin in batches, if needed, but is more complicated:
1. Run the relevant transation process with the batch amount you need.
2. Save the produced translation language strings, either into the plugin lang file directly as a plugin developer, or via the customisation function in Site administration > Language > Language customisation > Open language pack for editing.
3. Remember to purge caches for language strings, as the cache needs to find the saved strings, in order to see them as done and use the next batch of strings to process next.

Currently supported languages: 
* Arabic / العربية (ar).
* Bulgarian / Български (bg).
* Czech / Čeština (cs).
* Danish / Dansk (da).
* German / Deutsch (de).
* Greek / Ελληνικά (el).
* Spanish (international) / Español - Internacional (es).
* Estonian / eesti (et).
* Finnish / Suomi (fi).
* French / Français (fr).
* Irish / Gaeilge (ga).
* Hungarian / magyar (hu).
* Indonesian / Bahasa Indonesia (id).
* Italian / Italiano (it).
* Japanese / 日本語 (ja).
* Korean / 한국어 (ko).
* Lithuanian / Lietuvių (lt).
* Latvian / Latviešu (lv).
* Norwegian Bokmål / Norsk - bokmål (nb).
* Dutch / Nederlands (nl).
* Polish / Polski (pl).
* Portuguese - Brazil / Português - Brasil (pt_br).
* Portuguese / Português - Portugal (pt).
* Romanian / Română (ro).
* Russian / Русский (ru).
* Slovak / Slovenčina (sk).
* Slovenian / Slovenščina (sl).
* Swedish / Svenska (sv).
* Turkish / Türkçe (tr).
* Ukrainian / Українська (uk).
* Chinese (simplified) / 简体中文 (zh_cn).
* Chinese (traditional/big5) / 正體中文 (zh_tw).

## License ##
2025 Onward [Brickfield Education Labs](https://www.brickfield.ie)

## Version support #
This plugin has been developed to work on Moodle releases 4.01, 4.02, 4.03, 4.04, and 4.05.

## Development ##
This plugin has been developed and is maintained by Brickfield Education Labs.

## Important Links ##
* [Code repository](https://github.com/brickfield/moodle-local_bftranslate)
* [Azure translation documentation](https://learn.microsoft.com/en-gb/azure/ai-services/translator/text-translation/reference/v3/reference)
* [DeepL translation documentation](https://developers.deepl.com/docs/api-reference/translate)

## Installation ##
* Unzip and copy "bftranslate" folder into Moodle's "local" folder
* Visit admin page to install module

Further installation instructions can be found on the
"[Installing plugins](http://docs.moodle.org/en/Installing_contributed_modules_or_plugins)" Moodle documentation page.
# Brickfield Translator Plugin #
Copyright (C) 2025 [Brickfield Education Labs](https://www.brickfield.ie)

## What is Brickfield Translator?
Brickfield Translator is a Moodle plugin to make generating plugin language strings easier using AI!

This tool enables the translation of plugin language strings, via the usage of multiple translation APIs, including DeepL and Azure. Translation results can either be displayed as a comparison table between English and new language strings, or as ready-to-go PHP strings for inserting into plugin language files.

To setup Brickfield Translator:
1. Navigate to Site administrator > Plugins > Plugin Translator > Settings.
2. Enter your DeepL API key to the "DeepL API Key" setting.
3. Enter your Azure API key to the "Azure API Key" setting.
4. Click the "Save changes" button.
5. Please note that languages must be installed in order to appear in the Target language dropdown.

To translate the strings for a plugin:
1. Navigate to Site administrator > Plugins > Plugin Translator > Translation page.
2. Select an API to use in the "Select API" dropdown.
3. Select a plugin to translate in the "Select Plugin" dropdown.
   * If a plugin that you would like to translate is not listed, please reference the steps further down for adding support for a new plugin.
4. Select the output language in the "Select Target Language" dropdown.
   * Languages will only appear as options if the language packs are installed on the Moodle site.
5. Leave the batch limit at 0 to process all strings, unless a batch limit is required with a large amount of strings to be processed.
6. Select how the translations are outputted in the "Select Table or PHP Language String output" dropdown.
7. The "Table" option will display the original and translated strings for comparison.
   * The new target language strings will be in editable fields.
   * This allows a user to review the strings supplied by the AI API, and to edit and update them as needed.
   * The user then has the option to click the "Save Changes as custom language strings" button at the bottom of the table form. This will then save the edited strings into the correct area of the Moodle site, where language customisations are all stored.
   * PLEASE NOTE: if not saved at this point, these strings AND any customisations will be lost and will need to be reprocessed. 
   * By safely and securely storing the strings in this way, this makes the translation strings instantly active for usage, and also ready to be managed via all the standard core Moodle language pack processes as well.
8. The "PHP language string" option will provide you a list of the new languages strings, PHP pre-escaped, so they can be copied and pasted directly into the relevant new language file in the plugin repository.
   * A roadmap task is to have this output tidied up to give a one-click option to have the full amount of language strings downloaded instead for easier usage.
9. The processing of language strings should be able to correctly handle the following:
   * Moodle placeholders for any dynamic values.
   * Any HTML tags included.
10. Click the "Translate" button.

To add support for translating a plugin that is not listed by default:
1. Navigate to Site administrator > Plugins > Plugin Translator > Settings.
2. Add the plugins component name to the "External Plugins" setting, in the format of a comma separated list.
3. Click the "Save changes" button.

To translate a plugin in batches, if needed, but is more complicated:
1. Run the relevant transation process with the batch amount you need.
2. Save the produced translation language strings, either into the plugin lang file directly as a plugin developer, or via the customisation function in Site administration > Language > Language customisation > Open language pack for editing.
3. Remember to purge caches for language strings, as the cache needs to find the saved strings, in order to see them as done and use the next batch of strings to process next.

For the currently supported languages, please refer to DeepL and Azure support documentation in the important links section below.

## License ##
2025 Onward [Brickfield Education Labs](https://www.brickfield.ie)

## Version support #
This plugin has been developed to work on Moodle releases 4.01, 4.02, 4.03, 4.04, and 4.05.

## Development ##
This plugin has been developed and is maintained by Brickfield Education Labs.

## Important Links ##
* [Code repository](https://github.com/brickfield/moodle-local_bftranslate)
* [Brickfield Translate user guide](https://docs.brickfield.ie/local-bftranslate/)
* [Azure translation documentation](https://learn.microsoft.com/en-gb/azure/ai-services/translator/text-translation/reference/v3/reference)
* [DeepL translation documentation](https://developers.deepl.com/docs/api-reference/translate)

## Installation ##
1. Unzip and copy the "bftranslate" folder into Moodle's "local" folder, please note the exact component name of this plugin.
2. Visit admin page to install module.

Further installation instructions can be found on the
"[Installing plugins](http://docs.moodle.org/en/Installing_contributed_modules_or_plugins)" Moodle documentation page.
# Brickfield Translator Plugin #
Copyright (C) 2025 [Brickfield Education Labs](https://www.brickfield.ie)

## What is Brickfield Translator?
Brickfield Translator is a Moodle plugin to make generating plugin language strings easier using AI!

This tool enables the translation of plugin language strings, via the usage of external translation APIs. Currently, these include:
* [DeepL](https://developers.deepl.com/docs/api-reference/translate) (account required)
* [Azure](https://learn.microsoft.com/en-gb/azure/ai-services/translator/text-translation/reference/v3/reference) (account required)

Translation results are displayed as a comparison table between the English strings and the new language strings. These strings can then be either saved as custom language strings or downloaded as ready-to-go PHP strings for inserting into plugin language files.

To set up Brickfield Translator:
1. Navigate to Site administrator > Plugins > Plugin Translator > Settings.
2. Enter one or more of your translation services API keys to the appropriate "API Key" setting.
3. Allow core translation: this allows you to switch between translating core or non-core plugins.
4. Show the "Local test" translator: if enabled, this displays the local test option, which allows Brickfield Translator to demonstrate usage without API connections.
5. Click the "Save changes" button.
6. Please note that languages must be installed in order to appear in the Target language dropdown.

To translate the strings for a plugin:
1. Navigate to Site administrator > Plugins > Plugin Translator > Translation page.
2. Select an API to use in the "Select API" dropdown.
3. Select a plugin to translate in the "Select Plugin" dropdown.
   * Multiple plugins may be selected at a time, with each plugin then being processed in turn.
4. Select the output language in the "Select Target Language" dropdown.
   * Languages will only appear as options if the language packs are installed on the Moodle site.
5. Leave the batch limit at 0 to process all strings, unless a batch limit is required with a large amount of strings to be processed.
6. Show existing: tick this if you also want to include pre-existing translation strings, for reviewing purposes.
6. Click the Translate button.
7. Once returned from the API, the page will now display the original and translated strings for comparison.
   * The new target language strings will be in editable fields.
   * This allows a user to review the strings supplied by the Translation API, and to edit and update them as needed.
8. PLEASE NOTE: if not saved or downloaded at this point, these strings AND any customisations will be lost and will need to be reprocessed.
9. The user now has two options with which to continue:
   * Click the "Save changes as custom language strings" button. This will save the strings, including any edits, into the correct area of the Moodle site, where standard language customisations are all stored.
      * By safely and securely storing the strings in this way, this makes the translation strings instantly active for usage, and also ready to be managed via all the standard core Moodle language pack processes as well.
   * Click the "Download as PHP language strings" button to receive a new language PHP file, which can be copied and pasted directly into the relevant new language file in the plugin repository.
10. The processing of language strings should be able to correctly handle the following:
   * Moodle placeholders for any dynamic values.
   * Any HTML tags included.

Translating a plugin in batches, if needed, is more complicated, but available if needed, for instance if the plugin has a very large amount of strings:
1. Run the relevant transation process with the batch amount you need.
2. Save the produced translation language strings, either into the plugin lang file directly as a plugin developer, or by using the "Save Changes as custom language strings" button at the bottom of the table form.

For the currently supported languages, please refer to DeepL and Azure support documentation in the important links section below.

## License ##
2025 Onward [Brickfield Education Labs](https://www.brickfield.ie)

## Version support #
This plugin has been developed to work on Moodle releases 4.5, 5.0, 5.1, and 5.2, on PHP 8.1 and up.

## Development ##
This plugin has been developed and is maintained by Brickfield Education Labs.

## Important Links ##
* [Code repository](https://github.com/brickfieldprojects/moodle-local_bftranslate)
* [Brickfield Translate user guide](https://docs.brickfield.ie/local-bftranslate/)
* [Azure translation documentation](https://learn.microsoft.com/en-gb/azure/ai-services/translator/text-translation/reference/v3/reference)
* [DeepL translation documentation](https://developers.deepl.com/docs/api-reference/translate)

## Installation ##
1. Unzip and copy the "bftranslate" folder into Moodle's "local" folder, please note the exact component name of this plugin.
2. Visit the Site Admin page to install this new module.

Further installation instructions can be found on the
"[Installing plugins](http://docs.moodle.org/en/Installing_contributed_modules_or_plugins)" Moodle documentation page.
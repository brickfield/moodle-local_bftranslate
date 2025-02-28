<?php

namespace local_bftranslate;

class azure_translator {
    private $api_key;
    private $api_url = 'https://api.cognitive.microsofttranslator.com/translate?api-version=3.0';

    public function __construct($api_key) {
        $this->api_key = $api_key;
    }

    public function translate_batch($strings, $target_lang) {
        $translations = [];

        foreach ($strings as $key => $text) {
            // Skip empty strings, but still add to array for display in the table.
            if ($text == '') {
                $translations[$key] = '';
                continue;
            }
            $translated_text = $this->translate($text, $target_lang);
            if ($translated_text) {
                $translations[$key] = $translated_text;
            }
        }

        return $translations;
    }

    // Function to wrap placeholders with Azure's notranslate tag.
    public function protectPlaceholdersForAzure($text) {
        return preg_replace('/(\{\$a->[^}]+\})/', '<span class="notranslate">$1</span>', $text);
    }

    // Function to remove Azure's notranslate tags from placeholders.
    public function removeNotranslateTags($translatedText) {
        return preg_replace('/<span class="notranslate">(.*?)<\/span>/', '$1', $translatedText);
    }

    public function com_create_guid() {
          return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
              mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
              mt_rand( 0, 0xffff ),
              mt_rand( 0, 0x0fff ) | 0x4000,
              mt_rand( 0, 0x3fff ) | 0x8000,
              mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
          );
    }

    private function translate($text, $target_lang) {
        $text = static::protectPlaceholdersForAzure($text);
        $string = "[{'Text': \"$text\"}]";
        // Lang needs to be added to local variable to avoid changing class variable.
        $url = $this->api_url . "&to=" . $target_lang;
        $url .= "&textType=html";
        $string = json_encode([["Text" => $text]]);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $string);
        $authstring = 'Ocp-Apim-Subscription-Key: ' . $this->api_key;
        $region = 'Ocp-Apim-Subscription-Region: westeurope';
        curl_setopt($ch, CURLOPT_HTTPHEADER, [$authstring, $region, 'Content-Type: application/json']);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response) {
            $decoded = json_decode($response, true);
            if (isset($decoded[0]['translations'][0]['text'])) {
                $decoded[0]['translations'][0]['text'] = static::removeNotranslateTags($decoded[0]['translations'][0]['text']);
            }
            return $decoded[0]['translations'][0]['text'] ?? null;
        }
        return null;
    }
}

<?php

namespace local_bftranslate;

class deepl_translator {
    private $api_key;
    private $api_url = 'https://api-free.deepl.com/v2/translate';

    public function __construct($api_key) {
        $this->api_key = $api_key;
    }

    public function translate_batch($strings, $target_lang) {
        $translations = [];

        foreach ($strings as $key => $text) {
            $translated_text = $this->translate($text, $target_lang);
            if ($translated_text) {
                $translations[$key] = $translated_text;
            }
        }
        return $translations;
    }

    private function translate($text, $target_lang) {
        $post_data = [
            //'auth_key' => $this->api_key,
            'text' => [$text],
            'target_lang' => strtoupper($target_lang)
        ];
        $string = json_encode($post_data);

        $ch = curl_init($this->api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $string);
        $authstring = 'Authorization: DeepL-Auth-Key ' . $this->api_key;
        curl_setopt($ch, CURLOPT_HTTPHEADER, [$authstring, 'Content-Type: application/json']);
        //curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
        //curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response) {
            $decoded = json_decode($response, true);
            return $decoded['translations'][0]['text'] ?? null;
        }
        return null;
    }
}

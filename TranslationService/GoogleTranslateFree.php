<?php
namespace \racs\wpmlat\translationService;

require_once WPMLAT__PLUGIN_DIR . 'external/GoogleTranslate.php';

use \Statickidz\GoogleTranslate;

class GoogleTranslatorFree implements TranslationService {
    
    private static $googleService = null;
    
    public function __constructor() {
        if (!self::$googleService) {
            self::$googleService = new GoogleTranslate();
        }
    }
    
    public function translate($text, $sourceLanguage, $destinationLanguage): string {
        return self::$googleService->translate($sourceLanguage, $destinationLanguage, $text);
    }

}
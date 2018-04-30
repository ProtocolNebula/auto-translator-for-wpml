<?php
namespace racs\wpmlat\TranslationService;

require_once WPMLAT__PLUGIN_DIR . 'external/GoogleTranslate.php';

use \Statickidz\GoogleTranslate;

class GoogleTranslateFree implements TranslationService {
    
    private static $googleService = null;
    
    public function __constructor() {
        if (!self::$googleService) {
            self::$googleService = new GoogleTranslate();
        }
    }
    
    public function translate($text, $sourceLanguage, $destinationLanguage) {
        return self::$googleService->translate($sourceLanguage, $destinationLanguage, $text);
    }

    public function globalConfiguration() {
        
    }

    public function saveGlobalConfiguration() {
        
    }

}
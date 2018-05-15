<?php
namespace racs\wpmlat\TranslationService;

require_once WPMLAT__PLUGIN_DIR . 'external/GoogleTranslate.php';

use \Statickidz\GoogleTranslate;

class GoogleTranslateFree implements TranslationService {
    
    private static $googleService = null;
    
    public function init() {
        if (self::$googleService === null) {
            self::$googleService = new GoogleTranslate();
        }
    }
    
    public function translate($text, $sourceLanguage, $destinationLanguage) {
        usleep(6000); // Wait 6 miliseconds between each petition
        return self::$googleService->translate($sourceLanguage, $destinationLanguage, $text);
    }

    public function globalConfiguration() {
        
    }

    public function saveGlobalConfiguration() {
        
    }

}
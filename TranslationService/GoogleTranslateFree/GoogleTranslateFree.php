<?php
namespace racs\wpmlat\TranslationService;

require_once __DIR__ . '/lib/GoogleTranslate.php';

use \Statickidz\GoogleTranslate;

class GoogleTranslateFree extends TranslationService {
    
    private static $googleService = null;
    
    public function init() {
        if (self::$googleService === null) {
            self::$googleService = new GoogleTranslate();
        }
        $this->initHelper();
    }
    
    public function translate($text, $sourceLanguage, $destinationLanguage) {
        // Wait until next request
        $this->helper->waitUntilNextRequest();
        
        // Get translation
        $result = self::$googleService->translate($sourceLanguage, $destinationLanguage, $text);
        
        // Update last query time
        $this->helper->updateLastQueryTime();
        
        return $result;
    }

    public function globalConfiguration() {
        
    }

    public function saveGlobalConfiguration() {
        
    }

}
<?php
namespace racs\wpmlat\TranslationService;

require __DIR__ . '/TranslationServiceI.php';
require __DIR__ . '/TranslationServiceHelpers.php';

/**
 * This class provide the base functionallity to create a TranslationService.
 * This will force to use the required interface and will help to load Helpers
 * This file is loaded in "auto-translator-for-wpml.php"
 */
class TranslationService implements TranslationServiceI {
    
    protected $helper;
    
    /**
     * This metod will init the helper in $this->helper;
     */
    protected function initHelper() {
        if ( ! $this->helper ) {
            $this->helper = new TranslationServiceHelpers();
        }
    }
    
}
<?php
namespace racs\wpmlat\TranslationService;

/**
 * This interface defines how must be all TranslationService.
 * This file is include in "./TranslationService.php"
 */
interface TranslationServiceI {
    
    /**
     * This function may be load (if is necessary) the original class
     * to use (if there any)
     */
    public function init();

    /**
     * Make a translation with the service
     * NOTE: Depending of Translation Service, $sourceLanguage will be autodetected 
     * even if specified (ex: google)
     * @param string $text Text to translate
     * @param string $sourceLanguage Original Language
     * @param string $destinationLanguage New Language
     * @return string Text translation
     */
    public function translate($text, $sourceLanguage, $destinationLanguage);
    
    /**
     * FOR FUTURE IMPLEMENTS:
     * This function show a form with all required data for this specific TranslationService (if is necessary)
     */
    public function globalConfiguration();
    
    /**
     * FOR FUTURE IMPLEMENTS:
     * This function save data from globalConfiguration() with all required data for this specific TranslationService (if is necessary)
     */
    public function saveGlobalConfiguration();
    
}
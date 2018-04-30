<?php
namespace \racs\wpmlat\translationService;

/**
 * This interface defines how must be all TranslationService.
 * This file is include in "wpml-auto-translator.php"
 */
interface TranslationService {
    
    /**
     * This function may be load (if is necessary) the original class
     * to use (if there any)
     */
    public function __constructor();

        /**
     * Make a translation with the service
     * @param string $text Text to translate
     * @param string $sourceLanguage Original Language
     * @param string $destinationLanguage New Language
     * @return string Text translation
     */
    public function translate($text, $sourceLanguage, $destinationLanguage);
    
}
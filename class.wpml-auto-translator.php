<?php

class WPMLAutoTranslator {

    private static $initiated = false;
    private static $translationService = null;

    public static function init() {
        if (!self::$initiated) {
            self::init_hooks();
            $this->translationService = new
        }
    }

    /**
     * Initializes WordPress hooks
     */
    private static function init_hooks() {
        self::$initiated = true;

        add_action('wpmlat_translate_item', array('WPMLAutoTranslator', 'translateItem'));
//        add_action('wp_insert_comment', array('WPMLAutoTranslator', 'auto_check_update_meta'), 10, 2);
//        add_filter('preprocess_comment', array('WPMLAutoTranslator', 'auto_check_comment'), 1);
    }

    /**
     * Attached to activate_{ plugin_basename( __FILES__ ) } by register_activation_hook()
     * @static
     */
    public static function plugin_activation() {
        if (!is_plugin_active('wpml-translation-management/plugin.php')) {
            $message .= __('WPML.org is not active. Please, install it before.');
            echo '<div class="notice notice-error"><p>' . esc_html($message) . '</p></div>';
        } else {
            $message .= __('WPML Auto Translator is now active.');
            echo '<div class="notice notice-success"><p>' . esc_html($message) . '</p></div>';
        }
    }

    /**
     * Removes all connection options
     * @static
     */
    public static function plugin_deactivation() {
        
    }

    public static function view($name, array $args = array()) {
        load_plugin_textdomain('wmplat');

        extract($args);

        $file = WPMLAT__PLUGIN_DIR . 'views/' . $name . '.php';

        include( $file );
    }

    /**
     * Make an item translation with plugin settings
     * @param array $args
     * @throws Exception If data is missing, it will throw an exception
     */
    public static function translateItem($args = array()) {
        if (!isset($args['element_id'])) {
            throw new Exception(__('No element_id specified to auto translate'));
        }
        
        
        $tm = new TranslationManagement();
        $meta = $tm->get_element_translation(63, 'en');
        $res = $tm->get_translation_job($meta->rid);

        $toSave = array(
            'job_type' => $meta->element_type,
            'job_id' => $meta->rid,
            'target' => $meta->languaje_code,
            'target_lang' => $meta->languaje_code,
            'source_lang' => $meta->source_language_code,
            'fields' => array(),
        );

        foreach ($res->elements as $k => $element) {
            if (!$element->field_data_translated and $element->field_data) {
                $sourceText = base64_decode($element->field_data);
                $newText = strrev($sourceText); // AQUI LA TRADUCCION
                
                // wpml-translation-management\inc\ajax.php
                $toSave['fields'][$element->field_type] = array(
                    'data' => $newText,
                    'tid' => $element->tid,
                    'format' => 'base64',
                );
            }
        }

        $ret = $tm->save_translation($toSave);
        
        return true;
    }
    
    /**
     * Make a translation using the current TranslationService
     * @param string $text Text to translate
     * @param string $destLang Language to translate
     * @param string $sourceLang Source language (optional)
     * @return string
     */
    public static function doTranslation($text, $destLang, $sourceLang = null) {
        
        return "";
    }

}
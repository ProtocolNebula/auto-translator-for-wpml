<?php
class WPMLAutoTranslator {

    private static $initiated = false;
    
    /**
     *
     * @var TranslationService\TranslationService 
     */
    private static $translationService = null;

    public static function init() {
        if (!self::$initiated) {
            self::init_hooks();
            self::init_service();
        }
    }

    /**
     * Initializes WordPress hooks
     */
    private static function init_hooks() {
        self::$initiated = true;

        if (current_user_can('edit_others_posts')) {
            add_action('wpmlat_translate_item', array('WPMLAutoTranslator', 'translateItem'));
        }
//        add_filter('preprocess_comment', array('WPMLAutoTranslator', 'auto_check_comment'), 1);
    }
    
    /**
     * Init the translation service
     * @return bool If file/class not exist it will return false
     */
    private static function init_service() {
        $service = get_option( 'wpmlat_translation_service' );
        
        $translationServicePath = WPMLAT__PLUGIN_DIR . 'TranslationService/'.$service.'/'.$service.'.php';
        if (is_file($translationServicePath)) {
            include_once $translationServicePath;
            $service = 'racs\\wpmlat\\TranslationService\\'.$service;
            if (class_exists($service)) {
                self::$translationService = new $service();
                self::$translationService->init();
                return true;
            }
        }
        
        return false;
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
            $message .= __('Auto Translator for WPML is now active.');
            echo '<div class="notice notice-success"><p>' . esc_html($message) . '</p></div>';
        }
    }

    /**
     * Removes all connection options
     * @static
     */
    public static function plugin_deactivation() {
        
    }
    
    /**
     * Check if wpml is available
     * @return type
     */
    public static function wpml_available () {
        return class_exists('TranslationManagement') and function_exists('wpml_tm_load_job_factory');
    }
    
    /**
     * Return all active languages (wpml_active_languages)
     * @return array
     */
    public static function get_active_languages() {
        return apply_filters( 'wpml_active_languages', null, array('orderby'=>'code', 'order'=>'asc'));
    }
    
    /**
     * Return an array with only keys and language names
     * @return array (id=>Language text)
     */
    public static function get_active_languages_short() {
        $langs = self::get_active_languages();
        $return = array();
        
        foreach ($langs as $k=>$v) {
            $return[$v['code']] = $v['default_locale'] . ' - ' . $v['native_name'];
        }
        
        return $return;
    }
    
    /**
     * Return an array with only all "TranslationServices"
     * @return array (id=>Translation service name)
     */
    public static function get_translation_services() {
        $return = array();
        
        $path = WPMLAT__TRANSLATION_SERVICES_DIR;
        $dh = opendir($path);
        while (($item = readdir($dh)) !== false) {
            $full_path = $path . $item;
            if ( $item === '.' or $item === '..' or !is_dir($full_path) ) continue;;
            
            $return[ $item ] = $item;
        }
        
        return $return;
    }

    /**
     * Show file from "view"
     * @param string $name File name (and parent folder if any). ex: admin/config
     * @param array $args Variables that will be mapped to use in the view.
     *  EX: array('var1' => 'thing'); // You can show in your view using $var1
     */
    public static function view($name, array $args = array()) {
        load_plugin_textdomain('wmplat');

        extract($args);

        $file = WPMLAT__PLUGIN_DIR . 'views/' . $name . '.php';

        include( $file );
    }

    /**
     * Make an item translation with plugin settings
     * Callable with:
     *      - $translated = WPMLAutoTranslator::translateItem([]);
     *      - do_action( 'wpmlat_translate_item', [ARGS]);
     * @param array $args
     *      - element_id: Post/Page/Element ID
     *      - lang: Destination language
     *      - translation_complete: Check for translation complete (bool)
     * @throws Exception If data is missing, it will throw an exception
     * @return bool Return true if some field is modified/translated
     *      It can return true and not make any modificaiton if wpml throws an error (like limit characters)
     *      It can return false if all is translated or destination language is the same
     */
    public static function translateItem($args = array()) {
        if (!isset($args['element_id'])) {
            throw new Exception(__('No element_id specified to auto translate'));
        }
        if (!isset($args['lang'])) {
            throw new Exception(__('No destination lang specified to auto translate'));
        }
        
        // Get the page/post to translate
        $tm = new TranslationManagement(); // WPML Translation Manager, NOT WPMLA
        $jobID = $tm->get_translation_job_id($args['element_id'], $args['lang']);
        
        // If not exist we must create the translation job
        if (!$jobID) {
            $res = $tm->create_translation_package($args['element_id']);
            $wpml_translation_job_factory = wpml_tm_load_job_factory();
            $jobID = $wpml_translation_job_factory->create_local_post_job($args['element_id'], $args['lang']);
        }
        
        // Load element details
        $meta = $tm->get_element_translation($args['element_id'], $args['lang']);
        $res = $tm->get_translation_job($jobID);

        $toSave = array(
            'job_type' => $meta->element_type,
            'job_id' => $jobID,
            'target' => $meta->language_code,
            'target_lang' => $meta->language_code,
            'source_lang' => $meta->source_language_code,
            'fields' => array(),
        );

        $sourceLang = $meta->source_language_code;
        $destLang = $meta->language_code;

        // Nothing to translate?
        if ('' == $sourceLang or $sourceLang == $destLang) return false;
        // Check all items of that element (for elementor or other composers)
        foreach ($res->elements as $k => $element) {
            if (!$element->field_data_translated and $element->field_data) {
                $sourceText = base64_decode($element->field_data);
                $newText = self::doTranslation($sourceText, $sourceLang, $destLang);

                if ($newText !== null) {
                    // Convert the first letter to mayus if necessary
                    if (ctype_upper($sourceText[0]) and !ctype_upper($newText[0])) {
                        $newText[0] = strtoupper($newText[0]);
                    }

                    // wpml-translation-management\inc\ajax.php
                    $toSave['fields'][$element->field_type] = array(
                        'data' => $newText,
                        'tid' => $element->tid,
                        'format' => 'base64',
                        'finished' => $args[ 'translation_complete' ] ? true : false,
                    );
                }
            }
        }

        // Something has changed
        if (!empty($toSave)) {
            // More information in: 
            // wpml-translation-management\inc\ajax.php
            // wpml-translation-management\inc\translation-jobs\helpers\wpml-save-translation-data-action.class.php
            $ret = $tm->save_translation($toSave);
            return true;
        }
        
        // Nothing changed
        return false;
    }
    
    /**
     * Make a translation using the current TranslationService
     * @param string $text Text to translate
     * @param string $sourceLang Source language (optional)
     * @param string $destLang Language to translate
     * @return string|null Return null if no TranslationService are loaded
     */
    public static function doTranslation($text, $sourceLang, $destLang) {
        try {
            if (self::$translationService) {
                return self::$translationService->translate($text, $sourceLang, $destLang);
            }
        } catch (Exception $ex) {
            echo '<div class="notice notice-success"><p>' . esc_html($ex->getMessage()) . '</p></div>';
        }
        return null;
    }

}

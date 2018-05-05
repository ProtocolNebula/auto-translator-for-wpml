<?php
require_once( WPMLAT__PLUGIN_DIR . 'includes/pageI.php' );

abstract class WPMLAutoTranslatorAdminPageBase implements WPMLAutoTranslatorAdminPageI {
    private static $initiated = false;
    private static $instance;
    
    /**
     * Init page items and show it using extended class
     */
    public static function show() {
        if (!self::$initiated) {
            self::$initiated = true;
            $className = get_called_class();
            self::$instance = self::initialize(new $className);
        }
        
//        if (isset($_POST['settings'])) {
//            self::enter_settings();
//        }
    }
    
    /**
     * Call to initialization methods and show
     * @param WPMLAutoTranslatorAdminPageI $instance (EX: new get_called_class())
     * @return WPMLAutoTranslatorAdminPageI Instance of page
     */
    public static function initialize(WPMLAutoTranslatorAdminPageI $instance) {
        $instance->init_hooks();
        $instance->init_settings();
        $instance->show_page();
        
        return $instance;
    }
}
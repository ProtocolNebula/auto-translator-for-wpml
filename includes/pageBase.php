<?php
require_once( WPMLAT__PLUGIN_DIR . 'includes/pageI.php' );

abstract class WPMLAutoTranslatorAdminPageBase implements WPMLAutoTranslatorAdminPageI {
    private static $initiated = false;

    /**
     * Init page items and show it using extended class
     */
    public static function show() {
        if (!self::$initiated) {
            static::init_hooks();
            static::settings_init();
        }
        
        self::show_page();
//        if (isset($_POST['settings'])) {
//            self::enter_settings();
//        }
    }

    public static function init_hooks() {
        self::$initiated = true;
    }
}
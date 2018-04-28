<?php

class WPMLAutoTranslator {

    private static $initiated = false;

    public static function init() {
        if (!self::$initiated) {
            self::init_hooks();
        }
    }

    /**
     * Initializes WordPress hooks
     */
    private static function init_hooks() {
        self::$initiated = true;

//        add_action('wp_insert_comment', array('WPMLAutoTranslator', 'auto_check_update_meta'), 10, 2);
//        add_filter('preprocess_comment', array('WPMLAutoTranslator', 'auto_check_comment'), 1);
    }

    /**
	 * Attached to activate_{ plugin_basename( __FILES__ ) } by register_activation_hook()
	 * @static
	 */
	public static function plugin_activation() {
		
	}

	/**
	 * Removes all connection options
	 * @static
	 */
	public static function plugin_deactivation( ) {
		
	}
	
    public static function view($name, array $args = array()) {
        load_plugin_textdomain('wmplat');

        extract($args);
        
        $file = WPMLAT__PLUGIN_DIR . 'views/' . $name . '.php';

        include( $file );
    }

}

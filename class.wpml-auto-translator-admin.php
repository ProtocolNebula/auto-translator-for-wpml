<?php

class WPMLAutoTranslatorAdmin {

    private static $initiated = false;

    public static function init() {
        if (!self::$initiated) {
            self::init_hooks();
        }

        if (isset($_POST['settings'])) {
            self::enter_settings();
        }
    }

    /**
     * Instantiate all hooks for admin panel (wp-admin)
     */
    public static function init_hooks() {
        self::$initiated = true;
        
        // include all admin pages
        foreach ( glob( WPMLAT__PLUGIN_DIR . "includes/admin/*.php" ) as $file ) {
            include_once $file;
        }

        add_action('admin_init', array('WPMLAutoTranslatorAdmin', 'admin_init'));
        add_action('admin_menu', array('WPMLAutoTranslatorAdmin', 'admin_menu'), 5);

//        add_filter('plugin_action_links_' . plugin_basename(plugin_dir_path(__FILE__) . 'akismet.php'), array('Akismet_Admin', 'admin_plugin_settings_link'));
    }

    /**
     * Instantiate admin fields
     */
    public static function admin_init() {
        load_plugin_textdomain('wpmalt');
//        add_meta_box('akismet-status', __('Comment History', 'akismet'), array('Akismet_Admin', 'comment_status_meta_box'), 'comment', 'normal');
    }
    
    /**
     * Add menu in admin sidebar
     */
    public static function admin_menu() {
        $hook = add_options_page( 
            __('WPML Auto Translation', 'wpmlat'), 
            __('WMPL Auto Translator', 'wpmlat'), 
            'manage_options', 
            'wpmlat_options', 
            array( 'WPMLAutoTranslatorAdminConfigPage', 'show' ) 
        );
    }

    public static function enter_settings() {
	}

}

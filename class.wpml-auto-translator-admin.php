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
        
        self::include_page_class();

        add_action('admin_init', array('WPMLAutoTranslatorAdmin', 'admin_init'));
        add_action('admin_menu', array('WPMLAutoTranslatorAdmin', 'admin_menu'), 20 );

        //add_filter('plugin_action_links_' . plugin_basename(plugin_dir_path(__FILE__) . 'akismet.php'), array('Akismet_Admin', 'admin_plugin_settings_link'));
    }
    
    /**
     * Include all page admin class
     * Is required load all for options/hooks things.
     * This only will be loaded in admin panel. And yes, this is too much slow action...
     */
    public static function include_page_class() {
        // include all admin pages and initialize settings and hooks
        $path = WPMLAT__PLUGIN_DIR . 'pages/admin/';
        $dh = opendir($path);
        while (($page = readdir($dh)) !== false) {
            if ( $page === '.' or $page === '..' ) continue;
            
            // Including php file
            $full_path = $path . $page;
            include_once $full_path;
            
            // Checking class
            $page = substr($page, 0, strpos($page, '.php'));
            $class = 'WPMLAutoTranslatorAdmin'.$page.'Page';
            if (!class_exists($class)) {
                die ('Class ' . $class .' not exist. Check that file ' . $full_path . ' exist and contain this class.' );
            }
            
            // Prepare class initialization
            add_action( 'admin_init', [ $class, 'initialize' ] );
        }
    }

    /**
     * Instantiate admin fields
     */
    public static function admin_init() {
        load_plugin_textdomain('wpmalt');
        // add_meta_box('akismet-status', __('Comment History', 'akismet'), array('Akismet_Admin', 'comment_status_meta_box'), 'comment', 'normal');
    }
    
    /**
     * Add menu in admin sidebar
     */
    public static function admin_menu() {
        add_options_page( 
            __('WPML Auto Translation', 'wpmlat'), 
            __('WMPL Auto Translator', 'wpmlat'), 
            'manage_options', 
            'wpmlat_config', 
            array( 'WPMLAutoTranslatorAdminConfigPage', 'show' ) 
        );
        
        $main_page = apply_filters( 'icl_menu_main_page', WPML_PLUGIN_FOLDER . '/menu/languages.php' );
        add_submenu_page( 
            $main_page, 
            __( 'WPML Auto Translation', 'wpmlat' ), 
            __( 'Do auto translation', 'wmplat' ), 
            'manage_options', 
            'wpmlat_execution', 
            array( 'WPMLAutoTranslatorAdminExecutionPage', 'show' ) 
        );
    }

    public static function enter_settings() {
	}

}

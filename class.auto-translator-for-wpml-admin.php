<?php
// Main url to sections
const WPMLAT_SETTINGS_URL = 'options-general.php?page=wpmlat_config';
const WPMLAT_EXECUTION_URL = 'admin.php?page=wpmlat_execution';
const WPML_REFERRAL_URL = 'https://wpml.org/?aid=188550&affiliate_key=ZNyQ9dyyFFii&dr=wpmlat-referral';

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
        add_action('admin_menu', array('WPMLAutoTranslatorAdmin', 'admin_menu'), 40 );
        add_action('admin_notices', array('WPMLAutoTranslatorAdmin', 'admin_notices'), 20 );

        //add_filter('plugin_action_links_' . plugin_basename(plugin_dir_path(__FILE__) . 'akismet.php'), array('Akismet_Admin', 'admin_plugin_settings_link'));
    }
    
    public static function admin_notices () {
        $use_translation_management = get_option( 'wpmlat_use_translation_management', false );
        if ( ! WPMLAutoTranslator::wpml_available() ) {
            self::print_admin_notices( 
                __('WPML Not found for WPMLAT.', 'wpmlat'), 
                sprintf ( __( 'Please, install <b>WPML</b>, if you do not haven it yet, you <a href="%s" target="_blank">can obtain it here</a>.', 'wpmlat' ), WPML_REFERRAL_URL ), 
                'error' 
            );
        }
        
        if ( $use_translation_management && ! WPMLAutoTranslator::wpml_translation_management_active() ) {
            self::print_admin_notices( 
                __('WPML Not found for WPMLAT.', 'wpmlat'), 
                sprintf ( __( 'Please, install <b>WPML Translation Management</b> extension, you <a href="%s" target="_blank">can obtain it here</a> or disable it in <a href="%s">Settings</a>.', 'wpmlat' ), WPML_REFERRAL_URL, WPMLAT_SETTINGS_URL ), 
                'error' 
            );
        }
        
    }
    
    /**
     * Show a message box
     * @param type $title
     * @param type $message
     * @param type $type
     */
    public static function print_admin_notices ( $title, $message, $type = 'error' ) {
        ?>
            <div class='error'>
                <?php if ($title) { ?>
                    <p><strong><?php echo $title; ?></strong></p>
                <?php }
                if ($message) { ?>
                    <p><?php echo $message; ?></p>
                <?php } ?>
            </div>
        <?php
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
            if ( $page === '.' or $page === '..' )   continue;
            
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
        // $main_page = null; // Hide from menu
        // $main_page = 'tools.php'; // Put in tools submenu
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

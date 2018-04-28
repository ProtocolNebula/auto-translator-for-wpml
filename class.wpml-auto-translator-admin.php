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

    public static function init_hooks() {
        // The standalone stats page was removed in 3.0 for an all-in-one config and stats page.
        // Redirect any links that might have been bookmarked or in browser history.
        if (isset($_GET['page']) && 'akismet-stats-display' == $_GET['page']) {
            wp_safe_redirect(esc_url_raw(self::get_page_url('stats')), 301);
            die;
        }

        self::$initiated = true;

        add_action('admin_init', array('WPMLAutoTranslatorAdmin', 'admin_init'));
        add_action('admin_menu', array('WPMLAutoTranslatorAdmin', 'admin_menu'), 5);

//        add_filter('plugin_action_links_' . plugin_basename(plugin_dir_path(__FILE__) . 'akismet.php'), array('Akismet_Admin', 'admin_plugin_settings_link'));
    }

    public static function admin_init() {
        load_plugin_textdomain('wpmalt');
//        add_meta_box('akismet-status', __('Comment History', 'akismet'), array('Akismet_Admin', 'comment_status_meta_box'), 'comment', 'normal');
    }
    
    public static function admin_menu() {
        $hook = add_options_page( __('WPML Auto Translation', 'wpmlat'), __('WMPL Auto Translator', 'wpmlat'), 'manage_options', 'wpmlat_options', array( 'WPMLAutoTranslatorAdmin', 'display_configuration_page' ) );
    }
    
    public static function display_configuration_page() {
        //WPMLAutoTranslator::view( 'config', compact( 'test' ) );
        WPMLAutoTranslator::view( 'config', compact( 'test' ) );
    }

    public static function enter_settings() {
	}

}

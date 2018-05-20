<?php
/*
Plugin Name:  Auto Translator for WPML (WPMLAT)
Description:  A plugin to autotranslate with google and WPML
Version:      1.0
Author:       Ruben Arroyo Ceruelo
Author URI:   https://racs.es/
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Domain Path:  /languages
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

define ('WPMLAT_VERSION', '1.0');
define ('WPMLAT__PLUGIN_DIR', plugin_dir_path( __FILE__ ));
define ('WPMLAT__PLUGIN_DIR_PUBLIC', WPMLAT__PLUGIN_DIR . 'public/' ); // If you use in plugins_url or similar, public must be "redeclared" on relative path
define ('WPMLAT__TRANSLATION_SERVICES_DIR', WPMLAT__PLUGIN_DIR . 'TranslationService/' );

register_activation_hook( __FILE__, array( 'WPMLAutoTranslator', 'plugin_activation' ) );
register_deactivation_hook( __FILE__, array( 'WPMLAutoTranslator', 'plugin_deactivation' ) );

// Interface to Translations Service
require_once( WPMLAT__PLUGIN_DIR . 'TranslationService/TranslationService.php' );

// Class
require_once( WPMLAT__PLUGIN_DIR . 'class.auto-translator-for-wpml.php' );

add_action( 'init', array( 'WPMLAutoTranslator', 'init' ) );

if ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
    // Abstract class for config pages
    require_once( WPMLAT__PLUGIN_DIR . 'pages/page-base.php' );

    require_once( WPMLAT__PLUGIN_DIR . 'class.auto-translator-for-wpml-admin.php' );
    add_action( 'init', array( 'WPMLAutoTranslatorAdmin', 'init' ) );
}
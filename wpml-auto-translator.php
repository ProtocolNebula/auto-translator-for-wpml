<?php
/*
Plugin Name:  WPML Auto Translator (WPMLAT)
Description:  A plugin to autotranslate with google and WPML
Version:      1.0
Author:       ProtocolNebula
Author URI:   https://racs.es/
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Domain Path:  /languages
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

define ('WPMLAT_VERSION', '1.0');
define ('WPMLAT__PLUGIN_DIR', plugin_dir_path( __FILE__ ));

register_activation_hook( __FILE__, array( 'WPMLAutoTranslator', 'plugin_activation' ) );
register_deactivation_hook( __FILE__, array( 'WPMLAutoTranslator', 'plugin_deactivation' ) );

// Interface to Translations Service
require_once( WPMLAT__PLUGIN_DIR . 'TranslationService/TranslationService.php' );

// Class
require_once( WPMLAT__PLUGIN_DIR . 'class.wpml-auto-translator.php' );

add_action( 'init', array( 'WPMLAutoTranslator', 'init' ) );

if ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
	require_once( WPMLAT__PLUGIN_DIR . 'class.wpml-auto-translator-admin.php' );
	add_action( 'init', array( 'WPMLAutoTranslatorAdmin', 'init' ) );
}
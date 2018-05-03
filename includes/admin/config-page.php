<?php

abstract class WPMLAutoTranslatorAdminConfigPage extends WPMLAutoTranslatorAdminPageBase {

    public static function init_hooks() {
        parent::init_hooks();

        add_action('admin_init', array('WPMLAutoTranslatorAdmin', 'admin_init'));
        add_action('admin_menu', array('WPMLAutoTranslatorAdmin', 'admin_menu'), 5);

        //add_filter('plugin_action_links_' . plugin_basename(plugin_dir_path(__FILE__) . 'akismet.php'), array('Akismet_Admin', 'admin_plugin_settings_link'));
    }

    public static function show_page() {
        if (current_user_can('manage_options')) {
            WPMLAutoTranslator::view( 'config', compact( 'test' ) );
        }
    }

    public static function settings_init() {
        // register a new setting for "reading" page
        register_setting('wpmlat', 'wpmlat_options');

        // register a new section in the "reading" page
        add_settings_section(
            'wpmlat_setting_section',
            __( 'WPMLAT Setting' ),
            array( 'WPMLAutoTranslatorAdmin', 'wporg_settings_section_cb' ),
            'wpmlat'
        );

        // register a new field in the "wporg_settings_section" section, inside the "reading" page
        add_settings_field(
            'wpmlat_settings_field',
            __('Campo Prueba', 'wpmla'),
            array( 'WPMLAutoTranslatorAdmin', 'wporg_settings_field_cb' ),
            'wpmlat',
            'wpmlat_setting_section'
        );
    }
    
    // section content cb
    static function wporg_settings_section_cb()
    {
        echo '<p>WPOrg Section Introduction.</p>';
    }

    // field content cb
    static function wporg_settings_field_cb($args)
    {
        // get the value of the setting we've registered with register_setting()
        $setting = get_option('wpmlat_options');
        // output the field
        ?>
        <input type="text" name="wporg_setting_name" value="<?php echo isset( $setting ) ? esc_attr( $setting ) : ''; ?>">
        <?php
    }
}

<?php
class WPMLAutoTranslatorAdminConfigPage extends WPMLAutoTranslatorAdminPageBase {

    public function init_hooks() {
    }

    public function init_settings() {
        // register a new setting for "reading" page
        register_setting('wpmlat', 'wpmlat_options');

        // register a new section in the "reading" page
        add_settings_section(
            'wpmlat_setting_section', __('WPMLAT Setting'), array('WPMLAutoTranslatorAdmin', 'wporg_settings_section_cb'), 'wpmlat'
        );

        // register a new field in the "wporg_settings_section" section, inside the "reading" page
        add_settings_field(
            'wpmlat_max_translations_step', 
            __('Max translations for every refresh', 'wpmlat'), 
            array('WPMLAutoTranslatorAdminPageBase', 'showInput'), 
            'wpmlat', 
            'wpmlat_setting_section',
            array('name'=>'wpmlat_max_translations_step')
        );
        
    }
    
    public function show_page() {
        if (current_user_can('manage_options')) {
            WPMLAutoTranslator::view('config', compact('test'));
        }
    }

    // section content cb
    function wporg_settings_section_cb() {
    }
}

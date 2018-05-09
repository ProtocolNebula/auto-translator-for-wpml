<?php
class WPMLAutoTranslatorAdminConfigPage extends WPMLAutoTranslatorAdminPageBase {

    /**
     * Data for elements (languages, etc...)
     * @var type 
     */
    private $elements = array();
    
    public function init_hooks() {
    }

    public function init_settings() {
        // register a new section in the "reading" page
        add_settings_section(
            'wpmlat_setting_section', __('WPMLAT Setting'), null, 'wpmlat'
        );

        $this->add_setting('wpmlat_max_translations_step', __('Max translations for every refresh', 'wpmlat'), 'intval');
    }
    
    public function add_setting($name, $text, $sanitize_callback = '', $section = 'wpmlat_setting_section', $type = 'Input') {
        $this->load_options_data();
        
        // register a new setting for "reading" page
        register_setting('wpmlat', $name, $sanitize_callback);
        
        // register a new field in the section
        add_settings_field(
            $name, $text, 
            array('WPMLAutoTranslatorAdminPageBase', 'showInput'), 
            'wpmlat', $section, array('name'=>$name)
        );
        
    }

    public function show_page() {
        if (current_user_can('manage_options')) {
            WPMLAutoTranslator::view('config', compact('test'));
        }
    }
    
    /**
     * Load the required data for elements (like languages)
     * @param type $param
     */
    public function load_options_data($param) {
        $this->elements['languages'] = WPMLAutoTranslator::get_active_languages_short();
    }
}

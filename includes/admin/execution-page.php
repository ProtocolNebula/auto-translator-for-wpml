<?php
class WPMLAutoTranslatorAdminExecutionPage extends WPMLAutoTranslatorAdminPageBase {

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
        $this->load_options_data();
    }

    /**
     * Do the translation process
     */
    public function show_page() {
        if (current_user_can('manage_options')) {
            // View file
            WPMLAutoTranslator::view('execution');
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

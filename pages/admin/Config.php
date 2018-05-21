<?php
class WPMLAutoTranslatorAdminConfigPage extends WPMLAutoTranslatorAdminPageBase {

    /**
     * Data for elements (languages, etc...)
     * @var type 
     */
    private $elements = array();
    
    public function init_hooks() {
    }

    public function init_page() {
        $this->load_options_data();
        $this->add_setting('wpmlat_max_translations_step', __('Max translations for every refresh (10 recommended for 4 or less languages)', 'wpmlat'), 10, 'intval');
        $this->add_setting_select('wpmlat_languages', $this->elements['languages'], __('Translate to (only active languages)', 'wpmlat'), true);
        $this->add_setting_select('wpmlat_post_types', $this->elements['posts_types'], __('Translate element types', 'wpmlat'), true);
        $this->add_setting_select('wpmlat_translation_service', $this->elements['translation_services'], __('Translate element types', 'wpmlat'), false);
        $this->add_setting_checkbox('wpmlat_set_as_translated', true, __('Check elements as translated (only for new translated elements) - BE CAREFUL WITH THIS', 'wpmlat'), false);
        
        // TODO: remove this on final merge with support_no_stringtranslator
        update_option( 'wpmlat_use_translation_management', true );
    }

    public function show_page() {
        // register a new section in the "reading" page
        add_settings_section(
            'wpmlat_setting_section', __( 'WPMLAT Settings', 'wpmlat' ), null, 'wpmlat'
        );
        
        if (current_user_can('manage_options')) {
            // load config.php view
            WPMLAutoTranslator::view('admin/config');
        }
    }
    
    /**
     * Load the required data for elements (like languages)
     * @param type $param
     */
    public function load_options_data() {
        $this->elements['languages'] = WPMLAutoTranslator::get_active_languages_short();
        $this->elements['posts_types'] = get_post_types();        
        $this->elements['translation_services'] = WPMLAutoTranslator::get_translation_services();
    }
}

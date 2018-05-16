<?php
class WPMLAutoTranslatorAdminExecutionPage extends WPMLAutoTranslatorAdminPageBase {

    /**
     * Data for elements (languages, etc...)
     * @var type 
     */
    private $settings = array();
    
    /**
     * If true, page will be refreshed/recalled to continue translations
     * @var bool 
     */
    private $refresh = false;
    
    /**
     * If script finished translating
     * @var type 
     */
    private $finished = false;
    
    /**
     * Next page to continue
     * @var int 
     */
    private $next_page = 0;
    
    public function init_hooks() {
    }

    public function init_page() {
        $this->load_options_data();
    }

    /**
     * Called to show the page. Call the translation process
     */
    public function show_page() {
        // Section configuration (include name)
        add_settings_section(
            'wpmlat_execution_section', __('WPMLAT Execution'), null, 'wpmlat'
        );
        
        if (current_user_can('manage_options')) {
            if ( $this->settings['current_page'] > 0 ) {
                $this->doTranslation();
            }
            
            // View file
            WPMLAutoTranslator::view('admin/execution', array(
                'settings' => $this->settings,
                'refresh' => $this->refresh,
                'finished' => $this->finished,
                'next_page' => $this->next_page,
                'next_url' => $this->prepareNextUrl(),
            ));
        }
    }
    
    /**
     * Do the translation process (it will do "paginated" auto refreshing)
     */
    private function doTranslation() {
        if (!WPMLAutoTranslator::wpml_available()) {
            $this->refresh = false;
            $this->finished = true;
            return false;
        }
        
        $langs = $this->settings['languages'];
        
        // Get all posts to check
        $elements = new WP_Query(array(
            'post_type' => $this->settings['post_types'],
            'posts_per_page' => $this->settings['max_step'],
            'paged' => $this->settings['current_page'],
        ));
        
        if (isset($elements->posts) and count($elements->posts[0])) {
            foreach ($elements->posts as $post) {
                echo 'Checking post ID: ',$post->ID,'<br />';
                foreach ($langs as $lang) {
                    $translated = WPMLAutoTranslator::translateItem(array(
                        'element_id' => $post->ID,
                        'lang' => $lang,
                    ));
                    // if ($translated) echo 'To: ' , $lang,'<br />';
                }
                // echo '<br />';
            }
            $this->next_page = $this->settings['current_page'] + 1;
            $this->refresh = true;
        } else {
            $this->finished = true;
        }
    }
    
    /**
     * Prepare url for the next "page" (or the first page)
     * This functin require that $this->next_page will be filled before called
     */
    private function prepareNextUrl() {
        $get = $_GET;
        $get['datapage'] = ($this->next_page > 0) ? $this->next_page : 1;
        $url = $_SERVER['SCRIPT_NAME'] . '?' . http_build_query($get);
        
        return $url;
    }
    
    /**
     * Load the required data for elements (like languages)
     * @param type $param
     */
    public function load_options_data($param) {
        $this->settings['max_step'] = get_option( 'wpmlat_max_translations_step', 50 );
        $this->settings['languages'] = get_option( 'wpmlat_languages' );
        $this->settings['post_types'] = get_option( 'wpmlat_post_types' );
        // $this->settings['translation_service'] = get_option( 'wpmlat_translation_service' );
        $this->settings['current_page'] = intval( $_GET['datapage'] ); // get_query_var( 'datapage', null );
    }
}

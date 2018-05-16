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
        add_action( 'wp_ajax_wpmlat_execute', [ $this, 'doTranslationAjax' ] );
        
        // load JS for ajax
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts_execution' ] );
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
            $result_execution = '';
            if ( $this->settings['current_page'] > 0 ) {
                ob_start();
                    $this->doTranslation();
                    $result_execution = ob_get_contents();
                ob_end_clean();
            }
            
            // View file
            WPMLAutoTranslator::view('admin/execution', array(
                'settings' => $this->settings,
                'refresh' => $this->refresh,
                'finished' => $this->finished,
                'next_page' => $this->next_page,
                'next_url' => $this->prepareNextUrl(),
                'result_execution' => $result_execution,
            ));
        }
    }
    
    /**
     * Function to execute "doTranslation" from ajax petition
     */
    public function doTranslationAjax() {
        // Faking data from post
        $_GET['datapage'] = $_POST['datapage'];
        $this->settings['current_page'] = $_GET['datapage'];
        
        ob_start();
            $this->doTranslation();
            $result = ob_get_contents();
        ob_end_clean();
        
        $data = array(
            'refresh' => $this->refresh,
            'finished' => $this->finished,
            'next_page' => $this->next_page,
            'result_execution' => $result,
            'next_url' => '',
        );
        
        if ($this->finished) {
            // Force for generate get
            $this->next_page = $this->settings['current_page'];
            $data['next_url'] = WPMLAT_EXECUTION_URL; // Return to main scren
        } else {
            $data['next_url'] = $this->prepareNextUrl(); // Next url
        }
        
        echo json_encode($data);
        
        //wp_die();
        die();
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
        // $url = $_SERVER['SCRIPT_NAME'] . '?' . http_build_query($get);
        
        // Sanitize:
        unset($get['page']); // This will be in WPMLAT_EXECUTION_URL
        $url = WPMLAT_EXECUTION_URL . '&' . http_build_query($get);
        
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
    
    public function enqueue_scripts_execution($hook) {
        if( 'wpml_page_wpmlat_execution' != $hook ) {
            // Only applies to dashboard panel
            return;
        }

        if ( $this->settings['current_page'] > 0 and !$this->finished ) {
            wp_enqueue_script( 'ajax-script', plugins_url( '/public/js/execution.js', WPMLAT__PLUGIN_DIR_PUBLIC ), array('jquery') );

            // in JavaScript, object properties are accessed as ajax_object.ajax_url, ajax_object.we_value
            wp_localize_script( 'ajax-script', 'ajax_object',
                array( 
                    'ajax_url' => admin_url( 'admin-ajax.php' ), 
                    'datapage' => $this->settings['current_page'],
                    'max_step' => $this->settings['max_step'],
                ));
        }
    }
}

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
     * Next offset to continue
     * @var int 
     */
    private $next_offset = 0;
    
    public function init_hooks() {
    }

    public function init_page() {
        $this->load_options_data();
    }

    /**
     * Called to show the page. Call the translation process
     */
    public function show_page() {
        if (current_user_can('manage_options')) {
            // View file
            
//            if ($this->settings['current_offset'] !== null) {
                $this->doTranslation();
//            }
            
            WPMLAutoTranslator::view('admin/execution', array(
                'settings'=>$this->settings,
                'refresh'=>$this->refresh,
                'finished'=>$this->finished,
                'next_offset'=>$this->next_offset,
            ));
        }
    }
    
    /**
     * Do the translation process (it will do "paginated" auto refreshing)
     */
    private function doTranslation() {
        // Get all posts to check
//        $posts = get_posts(array(
//            'numberposts'=>$this->settings['max_step'],
//            'offset'=>$this->settings['current_offset'],
//            'order'=>'desc',
//            'orderby'=>'ID'
//        ));
        
        $args = array(
            'post_type' => 'page'
//            'tax_query' => array(
//                'relation' => 'AND',
//                array(
//                    'taxonomy' => 'movie_genre',
//                    'field' => 'slug',
//                    'terms' => array('action', 'comedy'),
//                ),
//                array(
//                    'taxonomy' => 'actor',
//                    'field' => 'term_id',
//                    'terms' => array(103, 115, 206),
//                    'operator' => 'NOT IN',
//                ),
//            ),
        );
        $elements = new WP_Query($args);
        
        print_r($elements);

        if (!empty($posts)) {
            
            foreach ($posts as $post) {
                print_r($post);
                echo '<hr />';
            }
            $this->next_offset = $this->settings['current_offset'] + $this->settings['max_step'];
            $this->refresh = true;
        } else {
            $this->finished = true;
        }
        
    }
    
    /**
     * Load the required data for elements (like languages)
     * @param type $param
     */
    public function load_options_data($param) {
        $this->settings['max_step'] = get_option( 'wpmlat_max_translations_step', 50 );
        $this->settings['languages'] = get_option( 'wpmlat_languages' );
        $this->settings['current_offset'] = get_query_var( 'offset', null );
    }
}

<?php
/**
 * Interface to create new Page in wp-admin
 * All page classes must be named "WPMLAutoTranslatorAdmin{$PageCamelCase}Page" and
 * filename must be "{$PageCamelCase}.php"
 */
interface WPMLAutoTranslatorAdminPageI {
    
    /*
     * Initializers.
     * This functions will be executed in the same as declared in this file
     * If a function is not necessary, leave it empty but declared
     */
    
    /**
     * This function must create all hooks for the current page
     */
    public function init_hooks();
    
    /**
     * Instantiate elements for admin page, it include (if necessary):
     * register_setting, add_settings_section and add_settings_field
     * 
     * All callbacks (methods) must be from the new class (all public static)
     */
    public function init_settings();
    
    /**
     * Show the configuration related page.
     * 
     * @example 
     *  if (current_user_can('manage_options')) {
     *       WPMLAutoTranslator::view( 'config', compact( 'var1' ) );
     *   }
     */
    public function show_page();

}
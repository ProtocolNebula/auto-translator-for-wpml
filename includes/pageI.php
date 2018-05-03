<?php
/**
 * Interface to create new Page in wp-admin
 */
interface WPMLAutoTranslatorAdminPageI {
    /**
     * This function must create all hooks for the current page
     * After or before that, must call parent::init_hooks();
     * 
     */
    public static function init_hooks();
    
    /**
     * Show the configuration related page.
     * 
     * @example 
     *  if (current_user_can('manage_options')) {
     *       WPMLAutoTranslator::view( 'config', compact( 'test' ) );
     *   }
     */
    public static function show_page();
    
    /**
     * Instantiate elements for admin page, it include (if necessary):
     * register_setting, add_settings_section and add_settings_field
     * 
     * All callbacks (methods) must be from the new class (all public static)
     */
    public static function settings_init();

}
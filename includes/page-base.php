<?php
require_once( WPMLAT__PLUGIN_DIR . 'includes/pageI.php' );

abstract class WPMLAutoTranslatorAdminPageBase implements WPMLAutoTranslatorAdminPageI {
    private static $initiated = false;
    private static $instance;
    
    /**
     * Init page items and show it using extended class
     */
    public static function show() {
        if (!self::$initiated) {
            self::$initiated = true;
            $className = get_called_class();
            self::$instance = self::initialize(new $className);
        }
        
//        if (isset($_POST['settings'])) {
//            self::enter_settings();
//        }
    }
    
    /**
     * Call to initialization methods and show
     * @param WPMLAutoTranslatorAdminPageI $instance (EX: new get_called_class())
     * @return WPMLAutoTranslatorAdminPageI Instance of page
     */
    public static function initialize(WPMLAutoTranslatorAdminPageI $instance) {
        $instance->init_hooks();
        $instance->init_settings();
        $instance->show_page();
        
        return $instance;
    }
    
    
    /*
     * Generic inputs
     */
    
    /**
     * Show an input from "add_settings_field" callback
     * @param type $args Arguments to mount the input
     *      - name: (required) Option name (id field in add_settings_field)
     * 
     *  OPTIONAL VALUES for CORE function: (https://developer.wordpress.org/reference/functions/add_settings_field/)
     *      - label_for: (optional) When supplied, the setting title will be wrapped in a <label> element, its for attribute populated with this value.
     *      - class: (optional) CSS Class to be added to the <tr> element when the field is output.
     */
    public static function showInput($args) {
        if (!isset($args['name'])) {
            echo 'Name argument not specified for input field';
            return;
        }
        
        $name = $args['name'];
        
        // get the value of the setting we've registered with register_setting()
        $setting = get_option($name);
        
        ?>
        <input type="text" name="<?php echo $name; ?>" value="<?php echo isset($setting) ? esc_attr($setting) : ''; ?>">
        <?php
    }
}
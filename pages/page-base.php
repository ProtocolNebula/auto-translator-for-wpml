<?php
require_once( WPMLAT__PLUGIN_DIR . 'pages/pageI.php' );

abstract class WPMLAutoTranslatorAdminPageBase implements WPMLAutoTranslatorAdminPageI {
    /**
     * Contain a list with all page instances
     * This is required because static vars can't be modified into childs (php things)
     * @var array<WPMLAutoTranslatorAdminPageI>
     */
    private static $instances;
    
    /**
     * Init page items and show it using extended class
     */
    public static function show() {
        $instance = self::initialize();
        $instance->show_page();
        
//        if (isset($_POST['settings'])) {
//            self::enter_settings();
//        }
    }
    
    /**
     * Call to initialization methods and show
     * @return WPMLAutoTranslatorAdminPageI Instance of page
     */
    public static function initialize() {
        $className = get_called_class();

        if (!isset(self::$instances[$className])) {
            $instance = new $className;
            $instance->init_hooks();
            $instance->init_page();
            self::$instances[$className] = $instance;
        }
        return self::$instances[$className];
    }
    
    /*
     * Generic inputs
     */
    
    /**
     * Add a generic input setting
     * @param type $name
     * @param type $text
     * @param type $sanitize_callback
     * @param type $section
     * @param type $type
     */
    public function add_setting($name, $text, $sanitize_callback = '', $section = 'wpmlat_setting_section') {
        // register a new setting for "reading" page
        register_setting('wpmlat', $name, $sanitize_callback);
        
        // register a new field in the section
        add_settings_field(
            $name, $text, 
            array('WPMLAutoTranslatorAdminPageBase', 'show_input'), 
            'wpmlat', $section, array('name'=>$name)
        );
    }
    
    /**
     * 
     * @param type $name
     * @param array $items Items array(key=>value, ...)
     * @param type $text
     * @param bool $multiselect
     * @param type $sanitize_callback
     * @param type $section
     */
    public function add_setting_select($name, $items, $text, $multiselect = false, $sanitize_callback = '', $section = 'wpmlat_setting_section') {
        // register a new setting for "reading" page
        register_setting('wpmlat', $name, $sanitize_callback);
        
        // register a new field in the section
        add_settings_field(
            $name, $text, 
            array('WPMLAutoTranslatorAdminPageBase', ($multiselect) ? 'show_select_multiple' : 'show_select'), 
            'wpmlat', $section, array('name'=>$name, 'items'=>$items)
        );
    }
    
    
    /**
     * Show an input from "add_settings_field" callback
     * @param type $args Arguments to mount the input
     *      - name: (required) Option name (id field in add_settings_field)
     * 
     *  OPTIONAL VALUES for CORE function: (https://developer.wordpress.org/reference/functions/add_settings_field/)
     *      - label_for: (optional) When supplied, the setting title will be wrapped in a <label> element, its for attribute populated with this value.
     *      - class: (optional) CSS Class to be added to the <tr> element when the field is output.
     */
    public static function show_input($args) {
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
    
    /**
     * Show an input from "add_settings_field" callback
     * @param type $args Arguments to mount the input
     *      - name: (required) Option name (id field in add_settings_field)
     *      - data: (required) Array with pair key=>val
     * 
     *  OPTIONAL VALUES for CORE function: (https://developer.wordpress.org/reference/functions/add_settings_field/)
     *      - label_for: (optional) When supplied, the setting title will be wrapped in a <label> element, its for attribute populated with this value.
     *      - class: (optional) CSS Class to be added to the <tr> element when the field is output.
     */
    public static function show_select($args) {
        if (!isset($args['name'])) {
            echo 'Name argument not specified for input field';
            return;
        }
        
        $name = $args['name'];
        
        // get the value of the setting we've registered with register_setting()
        $setting = get_option($name);
        ?>
        
        <select name="<?php echo $name; ?>">
        <?php
        foreach ( $args['items'] as $k => $v ) { 
            $selected = ($k === $setting) ? ' selected ': '';
        ?>
            <option value="<?php echo $k; ?>" <?php echo $selected; ?>><?php echo $v; ?></option>
        <?php
        }
        ?>
        </select>
        <?php
    }
    
    /**
     * Show an input from "add_settings_field" callback
     * @param type $args Arguments to mount the input
     *      - name: (required) Option name (id field in add_settings_field)
     *      - data: (required) Array with pair key=>val
     * 
     *  OPTIONAL VALUES for CORE function: (https://developer.wordpress.org/reference/functions/add_settings_field/)
     *      - label_for: (optional) When supplied, the setting title will be wrapped in a <label> element, its for attribute populated with this value.
     *      - class: (optional) CSS Class to be added to the <tr> element when the field is output.
     */
    public static function show_select_multiple($args) {
        if (!isset($args['name'])) {
            echo 'Name argument not specified for input field';
            return;
        }
        
        $name = $args['name'];
        
        // get the value of the setting we've registered with register_setting()
        $setting = get_option($name);
        ?>
            <select name="<?php echo $name; ?>[]" multiple="multiple">
        <?php
        foreach ( $args['items'] as $k => $v ) { 
            $selected = (in_array($k, $setting)) ? ' selected ': '';
        ?>
            <option value="<?php echo $k; ?>" <?php echo $selected; ?>><?php echo $v; ?></option>
        <?php
        }
        ?>
        </select>
        <?php
    }
}
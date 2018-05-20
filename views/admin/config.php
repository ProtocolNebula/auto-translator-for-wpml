<?php
/**
 * Generic configuration page
 * Load this page on all configuration page if you don't need other custom view
 */

// check if the user have submitted the settings
// wordpress will add the "settings-updated" $_GET parameter to the url
//if (isset($_GET['settings-updated'])) {
    // add settings saved message with the class of "updated"
    // add_settings_error('wporg_messages', 'wporg_message', __('Settings Saved', 'wporg'), 'updated');
//}

// show error/update messages
?>
<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    <form method="post" action="options.php">
        <p><?php sprintf ( _e( 'Once you saved your preferences, you must go to <a href="%s">Execution WPMLA page</a>', 'wpmlat' ), WPMLAT_EXECUTION_URL ); ?></p>
        <?php        
        // output security fields for the registered setting "wporg"
        settings_fields('wpmlat');
        
        // output setting sections and their fields
        // (sections are registered for "wpmlat", each field is registered to a specific section)
        do_settings_sections('wpmlat');
        
        _e('Depending of configuration, some options can have suboptions, it will appear after save.', 'wpmlat');
        
        // output save settings button
        submit_button(__('Save Settings', 'wpmlat'));
        ?>
        <p>
            <a href="<?php echo WPMLAT_EXECUTION_URL; ?>" class="button"><?php _e( 'Execution WPMLA page', 'wpmlat' ); ?></a>
        </p>
        
        <p>
            <a href="https://wpml.org/documentation/translating-your-contents/using-the-translation-editor/" target="_blank"><?php _e( 'More information about Translation Manager'); ?></a>
        </p>
    </form>
</div>
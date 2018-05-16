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
settings_errors('wporg_messages');
?>
<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    <form method="post" action="options.php">
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
    </form>
</div>
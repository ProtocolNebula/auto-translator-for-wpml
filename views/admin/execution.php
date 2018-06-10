<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    <h2><?php _e( 'Execute auto translation', 'wpmlat' ); ?></h2>
    
    <div style="color: #ff0000;">
        <p>This is a beta version. Please, check <a href="https://github.com/ProtocolNebula/auto-translator-for-wpml/issues" target="_blank">issues</a> before do a translation.</p>
        <p>A backup is recommended before do translation</p>
    </div>
    <?php
    //print_r($settings);

    //echo get_admin_url();

    if (!WPMLAutoTranslator::wpml_available()) {
        echo '<p>',
            __( 'You can\'t use this plugin until WPML is configured.', 'wpmlat' ),
        '</p>';
    } else {
        echo '<div id="wpmlat_execution_content">';
        if ( '' != $result_execution ) {
            echo $result_execution;
        }
        echo '</div>';
        
        if ( $refresh ) {
            echo '<img class="spinner-wpmlat" src="',plugins_url( '/public/img/spinner.gif', WPMLAT__PLUGIN_DIR_PUBLIC ),'">';
            echo '<noscript><meta class="wpmlat_autorefresh" http-equiv="refresh" content="5; url=' , $next_url , '"></noscript>';
            echo '<p class="wpmlat_autorefresh">Refreshing... <a href="' , $next_url , '">Click here if this process freeze.</a></p>';
        } elseif ( $finished ) {
            if ( $total_posts > 0 ) {
                _e( 'All items translated', 'wpmlat' );
            } else {
                _e( 'No posts founds. Please, check your current language (in admin bar top). It must be your main language.', 'wpmlat' );
            }
        } else {
            echo '<p>',
                __( 'The translation process might take a long time, be sure to not close this page.', 'wpmlat' ),
            '</p>';
            echo '<p>',
                __( 'At this moment, is only posible to check ALL posts even are translated, so this process will be a bit slow.', 'wpmlat' ),
            '</p>';

            if ( $can_do_translation ) {
                echo "<a href='{$next_url}' class='button button-primary'>" . __( 'Start translation', 'wpmlat' ) . "</a> ";
            } else {
                echo '<p>' , __('Please, enable <b>WPML</b> and <b>WPML String Translator</b> (if you want to use it)', 'wpmlat') , '</p>';
            }
            echo "<p><a href='" . WPMLAT_SETTINGS_URL . "' class='button'>" . __( 'Configure WPMLA', 'wpmlat' ) . "</a></p>";
        }
    }
    ?>
</div>
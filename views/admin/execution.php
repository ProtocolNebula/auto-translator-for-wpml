<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    <h2><?php _e( 'Execute auto translation', 'wpmlat' ); ?></h2>
    <?php
    //print_r($settings);

    //echo get_admin_url();

    if (!WPMLAutoTranslator::wpml_available()) {
        echo '<p>',
            __( 'You can\'t use this plugin until WPML is configured.', 'wpmlat' ),
        '</p>';
    } else {
        if ( '' != $result_execution and !$refresh ) {
            echo '<div class="wpmlat_autorefresh" >',$result_execution,'</div>';
        }
        
        if ( $refresh ) {
            echo '<noscript><meta class="wpmlat_autorefresh" http-equiv="refresh" content="5; url=' , $next_url , '"></noscript>';
            echo '<div id="wpmlat_execution_content">Refreshing... <a href="' , $next_url , '">Click here if this process freeze.</a></div>';
        } elseif ( $finished ) {
            _e( 'All items translated', 'wpmlat' );
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
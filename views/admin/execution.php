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
        if ( $refresh ) {
            echo '<meta http-equiv="refresh" content="2; url=' , $next_url , '">';
            echo 'Refreshing... <a href="' , $next_url , '">Click here if this process freeze.';
        } elseif ( $finished ) {
            _e( 'All items translated', 'wpmlat' );
        } else {
            echo '<p>',
                __( 'The translation process might take a long time, be sure to not close this page.', 'wpmlat' ),
            '</p>';
            echo '<p>',
                __( 'At this moment, is only posible to check ALL posts even are translated, so this process will be a bit slow.', 'wpmlat' ),
            '</p>';

            echo "<a href='{$next_url}' class='button button-primary'>" . __( 'Start translation', 'wpmlat' ) . "</a> ";
            echo "<a href='" . WPMLAT_SETTINGS_URL . "' class='button'>" . __( 'Configure WPMLA', 'wpmlat' ) . "</a>";
        }    
    }
    ?>
</div>
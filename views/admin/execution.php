<?php
//print_r($settings);

//echo get_admin_url();

if ( $refresh ) {
    echo '<meta http-equiv="refresh" content="2; url=' , $next_url , '">';
    echo 'Refreshing... <a href="' , $next_url , '">Click here if this process freeze.';
} elseif ( $finished ) {
    _e( 'All items translated', 'wpmlat' );
} else {
    echo '<p>',
        __( 'The translation process might take a long time, be sure to not close this page.', 'wpmlat' ),
    '</p>';
    
    echo "<a href='{$next_url}' class='button button-primary'>" . __( 'Start translation', 'wpmlat' ) . "</a>";
}

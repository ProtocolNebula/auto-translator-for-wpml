<?php
if (!class_exists('TranslationManagement')) {
?>
    <div class='error'>
        <?php /*<p><strong><?php printf( esc_html__( 'Akismet Error Code: %s', 'akismet' ), $code ); ?></strong></p>*/ ?>
        <p><?php _e('Error: TranslationManagement class not found. Please, install wpml-string-translation.', 'wpmlat'); ?></p>
    </div>
<?php
} else {
    do_action('wpmlat_translate_item', array('element_id'=>15, 'lang'=>'ca'));
    echo 'Translated';
}

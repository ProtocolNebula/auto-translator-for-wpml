<?php
if (!class_exists('TranslationManagement')) {
?>
    <div class='error'>
        <?php /*<p><strong><?php printf( esc_html__( 'Akismet Error Code: %s', 'akismet' ), $code ); ?></strong></p>*/ ?>
        <p><?php _e('Error: TranslationManagement class not found. Please, install wpml-string-translation.', 'wpmlat'); ?></p>
    </div>
<?php
} else {
    echo 'Testing translation';
    
    $_GET['job_id'] = 3;
    $test = new TranslationManagement();
    $meta = $test->get_element_translation(63, 'en');
    $res = $test->get_translation_job($meta->rid);

    $toSave = array(
        'job_type' => $meta->element_type,
        'job_id' => $meta->rid,
        'target' => $meta->languaje_code,
        'target_lang' => $meta->languaje_code,
        'source_lang' => $meta->source_language_code,
        'fields'=>array(),
    );
    
    foreach ($res->elements as $k=>$element) {
        if (!$element->field_data_translated and $element->field_data) {
            $sourceText = base64_decode($element->field_data);
            $newText = strrev($text); // AQUI LA TRADUCCION

            $toSave['fields'][$element->field_type] = array(
                'data'=>$text,
                'tid'=>$element->tid,
                'format'=>'base64',
            );
        }
    }
    
    
    $test->save_translation($toSave);
    die();    
}


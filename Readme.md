# Auto Translator for WPML

This plugin extends the main funcitonallity to WPML.org plugin.

**You need WPML plugin to use this plugin** (so you need a license of it).

Read [readme.txt](readme.txt) to more information.

## Adding a new Translation Service

If you want to translate with other service that is not added yet, you can create it in ```TranslationService``` folder (implementing ```TranslationService``` interface).

It will be auto detected by the plugin and you only must configure it in Admin page.

You can push the new service to the main repository and it will be available in the next plugin versions.

## Call translation from my plugin/code

**This part is still under development**

If you need to translate some post, you can use:

### Translate specifying Element ID and Destionation Language:

```php
do_action( 'wpmlat_translate_item', [
    'element_id' => $postId,
    'lang' => 'en', // destination language
    'translation_complete' => false, // Mark as completed or not
])
``` 
# WPML Auto Translation

## Important

1- If you need extra files for TranslationService, add to ```external``` (you can use subfolders if you need). In future versions this can be moved to ```TranslationService``` folder.
2- This is NOT a ```WPML Translation Service```, is a Translation Service for ```WPML Auto Translator``` (independent plugin)

## Translation service

This folder contains all classes which interact with external functions to do a translation.

For example, you can use a "GoogleTranslation" API from GitHub, to make it compatible
with wpmlat you only need to make or edit the ```TranslationService``` including the
API file and changing functions.

After that, you can choose the TranslationService from ```Settings -> WPML Auto Translator``` in ```wp-admin``` dashboard. 

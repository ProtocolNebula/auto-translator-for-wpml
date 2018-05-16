# WPML Auto Translation

## Important

This is NOT a ```WPML Translation Service```, is a Translation Service for ```WPML Auto Translator``` (independent plugin)

## Translation service

This folder contains all classes which interact with external functions to do a translation.

For example, you can use a "GoogleTranslation" API from any GitHub repository, 
to make it compatible with wpmlat you only need to make or edit the corresponding folder
on ```TranslationService``` folder.

It's recommended to add external files (external files or extra functionalities) into subfolder of that 
(check ```GoogleTranslateFree``` as example). *Folder* and *main class file* must have *the same name*.

*Is required that translation service have their own directory and subfile even only require one file*

After that, you can choose the TranslationService from ```Settings -> WPML Auto Translator``` in ```wp-admin``` dashboard
(it will appear automatically). 

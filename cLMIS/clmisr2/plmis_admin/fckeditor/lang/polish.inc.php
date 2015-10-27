<?php
/*
 * Filename:       assets/plugins/fckeditor242/lang/polish.inc.php
 * Function:       Polish language file for FCKEditor
 * Encoding:       ISO-Latin-1
 * Author:         Jeff Whitfield
 * Date:           2007/05/08
 * Version:        2.4.2
 * MODx version:   0.9.5
*/

include_once(dirname(__FILE__).'/english.inc.php'); // fallback for missing defaults or new additions

$_lang['FCKEditor_settings'] = "FCKEditor Settings";
$_lang['fck_editor_style_title'] = "XML Style:";
$_lang['fck_editor_style_message'] = "Enter the path and file name to the FCKEditor xml style selector file.The best way to enter the path is to enter the path from the root of your server, for example: /assets/plugins/fckeditor242/fckstyles.xml. If you do not wish to load a stylesheet into the editor, leave this field blank.";
$_lang['fck_editor_toolbar_title'] = "Toolbar set:";
$_lang['fck_editor_toolbar_message'] = "Here you can select which toolbar set to use with FCKEditor.  Choose Basic for limited options, Standard for more options,  Advance for all the available options or Custom to customize your toolbar.";
$_lang['fck_editor_custom_toolbar'] = "Custom toolbar:";
$_lang['fck_editor_custom_message'] = "Use this option to customize the toolbar set for the FCKEditor. Here you should enter the javascript syntax supported by the editor. For Example, use ['Bold','Italic','-','Link'] to display the Bold, Italic and Link icons . Each icon must be separated by a comma (,) and grouped using the [] bracket.";
$_lang['fck_editor_autolang_title'] = "Auto Language:";
$_lang['fck_editor_autolang_message'] = "Select the 'Yes' option to have the FCKEditor automatically detect the language used by the browser and load the appropriate language files. FCKEditor language files must be added to the 'assets/plugins/fckeditor242/editor/lang' folder";
$_lang['fckeditor_toolbar_basic'] = "Basic";
$_lang['fckeditor_toolbar_standard'] = "Standard";
$_lang['fckeditor_toolbar_advanced'] = "Advanced";
$_lang['fckeditor_toolbar_custom'] = "Custom";
?>
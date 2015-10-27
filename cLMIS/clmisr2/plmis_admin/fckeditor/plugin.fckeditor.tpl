/*
 * FCKEditor - RichText Editor Plugin
 * Written By Jeff Whitfield - April 30, 2007
 * Updated on March 8, 2009
 *
 * Version 2.6.4
 * FCKeditor v2.6.4
 *
 * Events: OnRichTextEditorInit, OnRichTextEditorRegister, OnInterfaceSettingsRender
 *
 * Default Plugin Config: &fckwebset=Web Toolbar Set;list;basic,standard,advanced,custom;custom &fckwebcustom=Custom Web Toolbar;textarea;['Bold','Italic','Underline','-','Link','Unlink'] &fckwebautolang=Web Auto Language;list;enabled,disabled;enabled
 */

// Set the name of the plugin folder
$pluginfolder = "fckeditor264";
 
global $_lang;
include_once $modx->config['base_path'].'assets/plugins/'.$pluginfolder.'/fckeditor.lang.php';
include_once $modx->config['base_path'].'assets/plugins/'.$pluginfolder.'/fckeditor.functions.php';

// Set path and base setting variables
if(!isset($fckPath)) { 
	global $fckPath;
	$fckPath = $modx->config['base_path'].'assets/plugins/'.$pluginfolder; 
}
$base_url = $modx->config['base_url'];
$displayStyle = ( ($_SESSION['browser']=='mz') || ($_SESSION['browser']=='op') ) ? "table-row" : "block" ;

// Handle event
$e = &$modx->Event; 
switch ($e->name) { 
	case "OnRichTextEditorRegister": // register only for backend
		$e->output("FCKEditor");
		break;

	case "OnRichTextEditorInit": 
		if($editor=="FCKEditor") {
			$elementList = implode(",", $elements);
			if(isset($forfrontend)||$modx->isFrontend()){
				$frontend = 'true';
				$frontend_language = isset($modx->config['fe_editor_lang']) ? $modx->config['fe_editor_lang']:"";
				$fck_language = getFCKEditorLang($frontend_language);
				$webuser = (isset($modx->config['rb_webuser']) ? $modx->config['rb_webuser'] : null);
				$html = getFCKEditorScript($elementList,(isset($fckwebset) ? $fckwebset:"basic"),(isset($fckwebcustom) && ($fckwebset == "custom") ? $fckwebcustom:""),$width,$height,$fck_language,$frontend,$base_url,$modx->config['editor_css_path'], $modx->config['use_browser'],$fckwebautolang,null,$pluginfolder,$webuser);
			} else {
				$frontend = 'false';
				$manager_language = $modx->config['manager_language'];
				$fck_language = getFCKEditorLang($manager_language);
				$html = getFCKEditorScript($elementList,(!empty($modx->config['fck_editor_toolbar']) ? $modx->config['fck_editor_toolbar']:"basic"),(!empty($modx->config['fck_editor_custom_toolbar']) && ($modx->config['fck_editor_toolbar'] == "custom") ? $modx->config['fck_editor_custom_toolbar']:""),$width,$height,$fck_language,$frontend,$base_url,$modx->config['editor_css_path'], $modx->config['use_browser'],$modx->config['fck_editor_autolang'],$modx->config['fck_editor_style'],$pluginfolder,null);
			}
			$e->output($html);
		}		
		break;

	case "OnInterfaceSettingsRender":
		$manager_language = $modx->config['manager_language'];
		$html = getFCKEditorSettings($_lang, $fckPath, $modx->config['manager_language'], $modx->config['use_editor'], $modx->config['fck_editor_toolbar'], $modx->config['fck_editor_custom_toolbar'], $modx->config['fck_editor_autolang'], $displayStyle, $modx->config['fck_editor_style']);
		$e->output($html);
		break;

   default :    
      return; // stop here - this is very important. 
      break; 
}

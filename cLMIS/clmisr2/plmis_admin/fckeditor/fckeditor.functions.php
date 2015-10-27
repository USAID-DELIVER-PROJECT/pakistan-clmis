<?php
//FCKEditor RichText Editor Plugin v2.6.4

// getFCKEditorSettings function
if (!function_exists('getFCKEditorSettings')) {
	function getFCKEditorSettings($_lang, $path, $manager_language='english', $use_editor, $toolbarset, $toolbarcustom, $autolang, $displayStyle, $xmlstyle) {
		// language settings
		include_once($path.'/lang/'.$manager_language.'.inc.php');

		$display = $use_editor==1 ? $displayStyle : 'none';
		$cusDisplay = $use_editor==1 && $toolbarset=='custom' ? $displayStyle : 'none';

		$arrToolbar[] = array("basic",$_lang['fckeditor_toolbar_basic']);
		$arrToolbar[] = array("standard",$_lang['fckeditor_toolbar_standard']);
		$arrToolbar[] = array("advanced",$_lang['fckeditor_toolbar_advanced']);
		$arrToolbar[] = array("custom",$_lang['fckeditor_toolbar_custom']);
		$arrToolbarCount = count($arrToolbar);
		for ($i=0;$i<$arrToolbarCount;$i++) {
				$toolbarOptions .= "					<option value=\"".$arrToolbar[$i][0]."\"".($arrToolbar[$i][0] == $toolbarset ? " selected=\"selected\"" : "").">".$arrToolbar[$i][1]."</option>\n";
		}		
		$toolbarcustom = isset($toolbarcustom) ? htmlspecialchars($toolbarcustom) : "['Bold','Italic','Underline','-','Link','Unlink']";
		$autolang = !empty($autolang) ? $autolang : "disabled";
		$autoNo = ($autolang=='disabled' || !empty($autolang)) ? 'checked="checked"' : '';
		$autoYes = $autolang=='enabled' ? 'checked="checked"' : '';		
		$xmlstyle = !empty($xmlstyle) ? htmlspecialchars($xmlstyle) : "";
				
		return <<<FCKEDITOR_HTML
		<table id='editorRow_FCKEditor' style="width:inherit;" border="0" cellspacing="0" cellpadding="3"> 
		  <tr class='row1' style="display: {$display};"> 
            <td colspan="2" class="warning" style="color:#707070; background-color:#eeeeee"><h4>{$_lang["FCKEditor_settings"]}<h4></td> 
          </tr> 
          <tr class='row1' style="display: {$display}"> 
            <td nowrap class="warning"><b>{$_lang["fck_editor_autolang_title"]}</b></td> 
            <td> <input onChange="documentDirty=true;" type="radio" name="fck_editor_autolang" value="enabled" {$autoYes} /> 
              {$_lang["yes"]}<br /> 
              <input onChange="documentDirty=true;" type="radio" name="fck_editor_autolang" value="disabled" {$autoNo} /> 
              {$_lang["no"]} </td> 
          </tr> 
          <tr class='row1' style="display: {$display}"> 
            <td width="200">&nbsp;</td> 
            <td class='comment'>{$_lang["fck_editor_autolang_message"]}</td> 
          </tr> 
		  <tr class='row1' style="display: {$display}"> 
            <td colspan="2"><div class='split'></div></td> 
          </tr> 
          
          <tr class='row1' style="display: {$display}"> 
            <td nowrap class="warning"><b>{$_lang["fck_editor_style_title"]}</b></td> 
            <td><input onChange="documentDirty=true;" type='text' maxlength='255' style="width: 300px;" name="fck_editor_style" value="{$xmlstyle}" /> 
			</td> 
          </tr> 
          <tr class='row1' style="display: {$display}"> 
            <td width="200">&nbsp;</td> 
            <td class='comment'>{$_lang["fck_editor_style_message"]}</td> 
          </tr> 
		  <tr class='row1' style="display: {$display}"> 
            <td colspan="2"><div class='split'></div></td> 
          </tr> 
          <tr class='row1' style="display: {$display}"> 
            <td nowrap class="warning"><b>{$_lang["fck_editor_toolbar_title"]}</b></td> 
            <td>
            <select name="fck_editor_toolbar" onChange="documentDirty=true;if(this.selectedIndex==3) showHide(/fck_customset/,1); else showHide(/fck_customset/,0);">
{$toolbarOptions}
			</select>
			</td> 
          </tr> 
          <tr class='row1' style="display: {$display}"> 
            <td width="200">&nbsp;</td> 
            <td class='comment'>{$_lang["fck_editor_toolbar_message"]}</td> 
          </tr> 
		  <tr class='row1' style="display: {$display}"> 
            <td colspan="2"><div class='split'></div></td> 
          </tr> 
          <tr id='fck_customset1' class='row3' style="display: {$cusDisplay}"> 
            <td nowrap class="warning"><b>{$_lang["fck_editor_custom_toolbar"]}</b></td> 
            <td>
            <input name="fck_editor_custom_toolbar" type="text" style="width:300px;" maxlength='65000' onChange="documentDirty=true;" value="{$toolbarcustom}" />
			</td> 
          </tr> 
          <tr id='fck_customset2' class='row3' style="display: {$cusDisplay}"> 
            <td width="200">&nbsp;</td> 
            <td class='comment'>{$_lang["fck_editor_custom_message"]}</td> 
          </tr> 
		  <tr id='fck_customset3' class='row3' style="display: {$cusDisplay}"> 
            <td colspan="2"><div class='split'></div></td> 
          </tr> 
		</table>
<script>
alert({$toolbarset});
</script>
FCKEDITOR_HTML;
	}
}

// getTinyMCEScript function
if (!function_exists('getFCKEditorScript')) {
	function getFCKEditorScript($elementList,$toolbarset,$toolbarcustom,$width,$height,$language,$frontend,$base_url,$editor_css_path,$use_browser,$autoLang,$editorstyle,$pluginfolder,$webuser) {
	    $autoLang = ($autoLang == 'enabled' ? 'true': 'false');
		$editor_css_path = !empty($editor_css_path) ? $editor_css_path : $base_url."assets/plugins/".$pluginfolder."/editor/css/fck_editorarea.css";
		$toolbarcustom = !empty($toolbarcustom) ? "var FCKCustomToolbarSet = [ ".$toolbarcustom." ];" : "";
		$width = (!empty($width)) ? str_replace("px","",$width) : "100%";
		$height = (!empty($height)) ? str_replace("px","",$height) : "400";

		if($frontend=='false' || ($frontend=='true' && $webuser)){
			if($use_browser==1){
				$allowrb = true;
			}
		}

		// build fck instances
        $elementList = split(",",$elementList);
		foreach($elementList as $fckInstance) {
			$fckInstanceObj = "oFCK" . $fckInstance;
			$fckInstances .= "<script language='javascript' type='text/javascript'>";
			$fckInstances .= "var $fckInstanceObj = new FCKeditor('$fckInstance');";
			$fckInstances .= "$fckInstanceObj.Width = '".$width."';";
			$fckInstances .= "$fckInstanceObj.Height = '".$height."';";
			$fckInstances .= "$fckInstanceObj.BaseHref = '".$base_url."';";
			$fckInstances .= "$fckInstanceObj.BasePath = '".$base_url."assets/plugins/".$pluginfolder."/';";
			$fckInstances .= "$fckInstanceObj.Config['ImageUpload'] = ".($allowrb ? "true":"false").";";
			$fckInstances .= "$fckInstanceObj.Config['ImageBrowser'] = ".($allowrb ? "true":"false").";";
			$fckInstances .= ($allowrb ? "$fckInstanceObj.Config['ImageBrowserURL'] = FCKImageBrowserURL;" : "");
			$fckInstances .= "$fckInstanceObj.Config['LinkUpload'] = ".($allowrb ? "true":"false").";";
			$fckInstances .= "$fckInstanceObj.Config['LinkBrowser'] = ".($allowrb ? "true":"false").";";
			$fckInstances .= ($allowrb ? "$fckInstanceObj.Config['LinkBrowserURL'] = FCKLinkBrowserURL;" : "");
			$fckInstances .= "$fckInstanceObj.Config['FlashUpload'] = ".($allowrb ? "true":"false").";";
			$fckInstances .= "$fckInstanceObj.Config['FlashBrowser'] = ".($allowrb ? "true":"false").";";
			$fckInstances .= ($allowrb ? "$fckInstanceObj.Config['FlashBrowserURL'] = FCKFlashBrowserURL;" : "");
			$fckInstances .= "$fckInstanceObj.Config['SpellChecker'] = 'SpellerPages';";
			$fckInstances .= "$fckInstanceObj.Config['CustomConfigurationsPath'] = '".$base_url."assets/plugins/".$pluginfolder."/custom_config.js';";
			$fckInstances .= "$fckInstanceObj.ToolbarSet = '".$toolbarset."';";
			$fckInstances .= "$fckInstanceObj.Config['EditorAreaCSS'] = FCKEditorAreaCSS;";
			$fckInstances .= (!empty($editorstyle) ? "$fckInstanceObj.Config['StylesXmlPath'] = '".htmlspecialchars($editorstyle)."';" : "");
			$fckInstances .= "$fckInstanceObj.ReplaceTextarea();</script>\n";
		}
		$browserConfig .= $allowrb ? "			var FCKImageBrowserURL = '{$base_url}manager/media/browser/mcpuk/browser.html?Type=images&Connector={$base_url}manager/media/browser/mcpuk/connectors/php/connector.php&ServerPath={$base_url}&editor=fckeditor2';\n" : "";
		$browserConfig .= $allowrb ? "			var FCKLinkBrowserURL = '{$base_url}manager/media/browser/mcpuk/browser.html?Connector={$base_url}manager/media/browser/mcpuk/connectors/php/connector.php&ServerPath={$base_url}&editor=fckeditor2';\n" : "";
		$browserConfig .= $allowrb ? "			var FCKFlashBrowserURL = '{$base_url}manager/media/browser/mcpuk/browser.html?Type=flash&Connector={$base_url}manager/media/browser/mcpuk/connectors/php/connector.php&ServerPath={$base_url}&editor=fckeditor2';\n" : "";
        		    
$script = <<<FCK_SCRIPT
		<script language="javascript" type="text/javascript" src="{$base_url}assets/plugins/{$pluginfolder}/fckeditor.js"></script>
		<script language="javascript" type="text/javascript">
			var _FileBrowserLanguage = 'php';
			var _QuickUploadLanguage = 'php';
{$browserConfig}
			{$toolbarcustom}
			var FCKAutoLanguage = {$autoLang};
			var FCKDefaultLanguage = '{$language}';
			var FCKEditorAreaCSS = '{$editor_css_path}';
			function FCKeditor_OnComplete(edtInstance) {
				if (edtInstance){ // to-do: add better listener
					edtInstance.AttachToOnSelectionChange(tvOnFCKChangeCallback);
				}
			};
			
			function tvOnFCKChangeCallback(edtInstance) {
				if (edtInstance) {
					elm = edtInstance.LinkedField;
					if(elm && elm.onchange) elm.onchange();
				}
			}
		</script>
{$fckInstances}
FCK_SCRIPT;

		return $script;
	}
}
?>
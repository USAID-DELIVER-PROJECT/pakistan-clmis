<?php
/*
 * Filename:       assets/plugins/fckeditor242/lang/german.inc.php
 * Function:       German language file for FCKEditor.
 * Encoding:       ISO-Latin-1
 * Author:         Jeff Whitfield
 * Date:           2007/05/08
 * Version:        2.4.2
 * MODx version:   0.9.5
 */

include_once(dirname(__FILE__).'/english.inc.php'); // fallback for missing defaults or new additions

$_lang['FCKEditor_settings'] = "FCKEditor Einstellungen";
$_lang['fck_editor_style_title'] = "XML Style:";
$_lang['fck_editor_style_message'] = "Tragen Sie den Pfad- und Dateinamen zur FCKEditor xml style selector Datei ein. Dies geschieht am besten durch das Eingeben relativ zu Ihrem Root-Verzeichnis, zum Beispiel: /assets/plugins/fckeditor242/fckstyles.xml. Falls Sie kein Stylesheet in den Editor laden wollen, lassen Sie dieses Feld bitte leer.";
$_lang['fck_editor_toolbar_title'] = "Einstellung Werkzeugleiste (Icons):";
$_lang['fck_editor_toolbar_message'] = "Hier k&ouml;nnen Sie ausw&auml;hlen, welche Werkzeugleiste Sie im FCKEditor nutzen wollen. Basic bedeutet eingeschr&auml;nkte Werkzeuge, Standard bietet mehr Optionen. Advance steht f&uuml;r alle verf&uuml;gbaren Optionen, Custom f&uuml;r eine eigene Anpassung der Werkzeugleiste.";
$_lang['fck_editor_custom_toolbar'] = "Eigene Werkzeugleiste (Custom):";
$_lang['fck_editor_custom_message'] = "Nutzen Sie diese Option um Ihre Werkzeugleiste im FCKEditor selbst zu erstellen. Hier tragen Sie die Javascript-Eingabe ein, die der Editor unterst&uuml;tzt. Zum Beispiel ['Bold','Italic','-','Link'], um Fett, Kursiv, und Link Symbole anzuzeigen. Jedes Symbol muss in eckigen Klammer stehen [] und durch Komma getrennt werden. F&uuml;r eine vollst&auml;ndige Liste schauen Sie auf <a href='http://wiki.fckeditor.net/Developer%27s_Guide/Configuration/Toolbar'>der Homepage des FCKEditors</a>.";
$_lang['fck_editor_autolang_title'] = "Automatische Sprache:";
$_lang['fck_editor_autolang_message'] = "Wählen Sie 'Ja', damit der Editor automatisch die eingestellte Sprache des Browsers erkennt und die dementsprechenden Sprachdateien lädt. Sprachdateien m&uuml;ssen im Ordner 'assets/plugins/fckeditor242/editor/lang' liegen.";
$_lang['fckeditor_toolbar_basic'] = "Basic";
$_lang['fckeditor_toolbar_standard'] = "Standard";
$_lang['fckeditor_toolbar_advanced'] = "Advanced";
$_lang['fckeditor_toolbar_custom'] = "Custom";
?>
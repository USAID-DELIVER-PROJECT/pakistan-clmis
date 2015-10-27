<?php
/**
 * Filename:       assets/plugins/fckeditor242/lang/japanese-utf8.inc.php
 * Function:       Japanese language file for FCKEditor.
 * Encoding:       UTF-8
 * Author:         yama
 * Date:           2007/05/08
 * Version:        2.4.2
 * MODx version:   0.9.5
*/

include_once(dirname(__FILE__).'/english.inc.php'); // fallback for missing defaults or new additions

$_lang['FCKEditor_settings'] = "FCKEditorの設定";
$_lang['fck_editor_style_title'] = "スタイルセレクタ:";
$_lang['fck_editor_style_message'] = "スタイルセレクタ定義ファイル(xml)のパスをドキュメントルートから記入してください。 [例]/assets/plugins/fckeditor242/fckstyles.xml スタイルセレクタが不要であればこの欄は空白にしてください。";
$_lang['fck_editor_toolbar_title'] = "ツールバーセット:";
$_lang['fck_editor_toolbar_message'] = "ツールバーセットを選択してください。選択できるスタイルは3種類。シンプルな構成の「Basic」・標準的な構成の「Standard」・すべてのアイコンを表示する「アドバンスト」。「カスタム」を選ぶと好みのアイコンを自由に選択できます。";
$_lang['fck_editor_custom_toolbar'] = "カスタムツールバー:";
$_lang['fck_editor_custom_message'] = "このオプションでツールバースタイルを自由にカスタマイズできます。(例) ['Bold','Italic','-','Link'] → [太字・斜体・リンク]のアイコンが表示されます。アイコンの区切りには「-(ハイフン)」、アイコンのグルーピングには「 [ ](ブラケット)」が利用できます。具体的な記述例は<a href='http://wiki.fckeditor.net/Developer%27s_Guide/Configuration/Toolbar'>FCKEditorのサイト</a>にあるので参考にしてください。";
$_lang['fck_editor_autolang_title'] = "言語の自動検出:";
$_lang['fck_editor_autolang_message'] = "「Yes」を選択すると言語ファイルを自動的に検出できます。言語ファイルは以下のロケーションに含まれている必要があります。'assets/plugins/fckeditor242/editor/lang'";
$_lang['fckeditor_toolbar_basic'] = "Basic";
$_lang['fckeditor_toolbar_standard'] = "Standard";
$_lang['fckeditor_toolbar_advanced'] = "Advanced";
$_lang['fckeditor_toolbar_custom'] = "Custom";
?>
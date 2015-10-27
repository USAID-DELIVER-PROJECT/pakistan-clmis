<?php
/*
 * Filename:       assets/plugins/fckeditor242/lang/portuguese-br.inc.php
 * Function:       Brazilian Portuguese language file for FCKEditor
 * Encoding:       ISO-Latin-1
 * Author:         Renato Rosa
 * Date:           2007/05/08
 * Version:        2.4.2
 * MODx version:   0.9.5
*/

include_once(dirname(__FILE__).'/english.inc.php'); // fallback for missing defaults or new additions

$_lang['FCKEditor_settings'] = "Configura��es do FCKEditor";
$_lang['fck_editor_style_title'] = "Estilo XML:";
$_lang['fck_editor_style_message'] = "Digite o caminho e o nome do arquivo do Estilo XML do Editor. A melhor maneira de digitar o caminho � utilizando o caminho desde a raiz do servidor. Por exemplo: /assets/plugins/fckeditor242/fckstyles.xml. Se voc� n�o desejar carregar uma Folha de Estilos no editor, deixe este campo em branco.";
$_lang['fck_editor_toolbar_title'] = "Barra de Ferramentas:";
$_lang['fck_editor_toolbar_message'] = "Aqui voc� pode selecionar qual barra de ferramentas utilizar com o FCKEditor. Escolha B�sica para op��es limitadas, Padr�o para mais op��es, Ava�ada para todas as op��es dispon�veis e Personalizada para definir a sua barra de ferramentas";
$_lang['fck_editor_custom_toolbar'] = "Barra de Ferramentas Personalizada:";
$_lang['fck_editor_custom_message'] = "Use esta op��o para customizar a barra de ferramentas escolhida para o FCKEditor. Aqui voc� deve digitar a sintaxe javascript suportada pelo Editor. Por Exemplo, use ['Bold','Italic','-','Link'] para exibir os �cones Bold (negrito), Italic (it�lico) e Link (atalho). Cada �cone deve ser separado por uma v�rgula (,) e agrupados utilizando colchetes []. Para a lista completa acesse <a href='http://wiki.fckeditor.net/Developer%27s_Guide/Configuration/Toolbar'>Homepage of the FCKEditor</a>, em ingl�s.";
$_lang['fck_editor_autolang_title'] = "Idioma Autom�tico:";
$_lang['fck_editor_autolang_message'] = "Selecione a op��o 'Sim' para que o FCKEditor detecte automaticamente o idioma utilizado pelo navegador do usu�rio e carregue o arquivo de idioma apropriado. Arquivos de idiomas do FCKEditor devem ser adicionados ao diret�rio 'assets/plugins/fckeditor242/editor/lang'.";
$_lang['fckeditor_toolbar_basic'] = "Basic";
$_lang['fckeditor_toolbar_standard'] = "Standard";
$_lang['fckeditor_toolbar_advanced'] = "Advanced";
$_lang['fckeditor_toolbar_custom'] = "Custom";
?>
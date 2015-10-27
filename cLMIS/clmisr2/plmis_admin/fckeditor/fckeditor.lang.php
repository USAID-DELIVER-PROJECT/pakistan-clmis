<?php
global $fckLang;
$fckLang = array();

$fckLang[] = array("danish","da");
$fckLang[] = array("english","en");
$fckLang[] = array("english-british","en");
$fckLang[] = array("finnish","fi");
$fckLang[] = array("francais","fr");
$fckLang[] = array("francais-utf8","fr");
$fckLang[] = array("german","de");
$fckLang[] = array("italian","it");
$fckLang[] = array("japanese-utf8","ja_utf-8");
$fckLang[] = array("nederlands","nl");
$fckLang[] = array("norsk","nn");
$fckLang[] = array("persian","fa");
$fckLang[] = array("polish","pl");
$fckLang[] = array("portuguese","pt");
$fckLang[] = array("russian","ru");
$fckLang[] = array("russion-UTF8","ru");
$fckLang[] = array("simple_chinese-gb2312","zh_cn");
$fckLang[] = array("spanish","es");
$fckLang[] = array("svenska","sv");
$fckLang[] = array("svenska-utf8","sv");

global $fckLangCount;
$fckLangCount = count($fckLang);

if (!function_exists('getFCKEditorLang')) {
	function getFCKEditorLang($lang){
		global $fckLang;
		global $fckLangCount;
		$langSel = 'en';
		for ($i=0;$i<$fckLangCount;$i++) {
			if($fckLang[$i][0] == $lang){
				$langSel = $fckLang[$i][1];
			}
		}
		return $langSel;
	}
}
?>
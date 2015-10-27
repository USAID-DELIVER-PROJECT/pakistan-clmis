/*
 * Custom Configuration file 
 * 
 */


// Allow QuickEdit style tags <quickedit:name />
FCKConfig.ProtectedSource.Add( /(<quickedit:[^\>]+>[\s|\S]*?<\/quickedit:[^\>]+>)|(<quickedit:[^\>]+\/>)/gi ); 	
// For snippet calls, uncomment the next two lines if you wish to hide snippet calls from FCK visual editing mode (only show in source mode)
// FCKConfig.ProtectedSource.Add( /\[\[[\s\S]*?\]\]/gi );
// FCKConfig.ProtectedSource.Add( /\[\![\s\S]*?\!\]/gi );

/* *
 * Language settings
 *
 */
FCKConfig.AutoDetectLanguage	= parent.FCKAutoLanguage;
FCKConfig.DefaultLanguage		= parent.FCKDefaultLanguage;

/* *
 * Other Settings
 *
 */
FCKConfig.FormatSource		= false ;

/* *
 * setup toolbar sets 
 *
 */
// basic			
FCKConfig.ToolbarSets["basic"] = [
	['Bold','Italic','-','OrderedList','UnorderedList','-','Link','Unlink','Image']
];
// standard
FCKConfig.ToolbarSets["standard"] = [
	['Source','-','Preview','-','Templates'],
	['Cut','Copy','Paste','PasteText','PasteWord'],
	['Undo','Redo','-','Find','Replace','-','RemoveFormat'],
	['Bold','Italic','Underline'],
	['OrderedList','UnorderedList','-','Outdent','Indent'],
	['JustifyLeft','JustifyCenter','JustifyRight','JustifyFull'],
	['Link','Anchor'],
	['Image','Flash','Table','Rule','SpecialChar'],
	['Style'],['FontFormat'],['FontName'],['FontSize'],
	['TextColor','BGColor'],['FitWindow','-','About']
];
// advanced
FCKConfig.ToolbarSets["advanced"] = [
	['Source','DocProps','-','NewPage','Preview','-','Templates'],
	['Cut','Copy','Paste','PasteText','PasteWord','-','Print','SpellCheck'],
	['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
	['Bold','Italic','Underline','StrikeThrough','-','Subscript','Superscript'],
	['OrderedList','UnorderedList','-','Outdent','Indent'],
	['JustifyLeft','JustifyCenter','JustifyRight','JustifyFull'],
	['Link','Unlink','Anchor'],
	['Image','Flash','Table','Rule','Smiley','SpecialChar'],
	['Form','Checkbox','Radio','TextField','Textarea','Select','Button','ImageButton','HiddenField'],
	'/',
	['Style'],['FontFormat'],['FontName'],['FontSize'],
	['TextColor','BGColor'],['FitWindow','-','About']
];
// custom
FCKConfig.ToolbarSets["custom"] = parent.FCKCustomToolbarSet;


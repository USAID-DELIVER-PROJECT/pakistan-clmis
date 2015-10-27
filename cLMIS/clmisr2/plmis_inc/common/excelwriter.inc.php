<?php
/* --------------------------------------------------------------------------------------------------------------
Try-to-stop (TTS) - Excel Writer

Author : Harish Chauhan (file downloaded from http://www.phpclasses.org/)

Modification History (LIFO)
---------------------------
Jun 08, 2007 - mdeangelis - Extended the range of formatting options
Jun 07, 2007 - mdeangelis - Added options for formatting cell. (T-Text, D-Date, N-Number)

Jun 06, 2007 - mdeangelis - Changed Last Author to SugarCRM and default worksheet name to TTS Report
//-------------------------------------------------------------------------------------------------------------- */

/*
RT ... Report title          ( used to format as report title )
CH ... Column headers        ( used to format as column headers )
DT ... Date/time             ( e.g. mm/dd/yyyy hh:mm:ss ) (Character string)
T  ... Time                  ( e.g. hh:mm:ss:00 )
D  ... Date                  ( e.g. 6/7/2007 )
C  ... Character (text)
P  ... Percentage - defaults to P2
P0 ... Percentage 0 decimals ( e.g. 75% )
P1 ... Percentage 1 decimal  ( e.g. 75.1% )
P2 ... Percentage 2 decimals ( e.g. 75.12% )
N  ... Number - use this for boolean 1/0 values
N0 ... Same as N
N1 ... Number - 1 decimal    ( e.g. 123.1 )
N2 ... Number - 2 decimals   ( e.g. 123.45 )
*/
define('TTS_DEFAULT_EXCEL_CELL_FORMAT','C');

     /*
     ###############################################
     ####                                       ####
     ####    Author : Harish Chauhan            ####
     ####    Date   : 31 Dec,2004               ####
     ####    Updated:                           ####
     ####                                       ####
     ###############################################

     */

	 
	 /*
	 * Class is used for save the data into microsoft excel format.
	 * It takes data into array or you can write data column vise.
	 */


	Class ExcelWriter
	{
		var $fp=null;
		var $error;
		var $state="CLOSED";
		var $newRow=false;
		
		var $tts_format_attributes = null; // Jun 8, 2007 - mdeangelis - add extended format options
		
		/*
		* @Params : $file  : file name of excel file to be created.
		* @Return : On Success Valid File Pointer to file
		* 			On Failure return false	 
		*/
		 
		function ExcelWriter($file="")
		{
			//-----------------------------------------------------------------------------------------------------
			// Jun 8, 2007 - mdeangelis - add extended format options (see also header replacement for new options)
			//-----------------------------------------------------------------------------------------------------
			// Note: we can add more as needed
			$this->tts_format_attributes = array( 
				'RT' 	=> "height=17 colspan=50 width=3740 style='height:12.75pt;mso-ignore:colspan;width:2807pt'", 
				'CH'	=> "height=17 class=xl24 style='height:12.75pt'", 
				'DT'    => "align=right",
				'D'     => "class=xl29 align=right x:num",
				'T'     => "class=xl27 align=right x:num",
				'C'  	=> '',
				'N'  	=> "align=right x:num",
				'N0'  	=> "align=right x:num",  
				'N1'  	=> "class=xl32 align=right x:num",  
				'N2'  	=> "class=xl31 align=right x:num", 
				'P'  	=> "class=xl26 align=right x:num",
				'P0'  	=> "class=xl30 align=right x:num",  
				'P1'  	=> "class=xl28 align=right x:num",  
				'P2'  	=> "class=xl26 align=right x:num"   
			);
		
			return $this->open($file);
		}
		
		/*
		* @Params : $file  : file name of excel file to be created.
		* 			if you are using file name with directory i.e. test/myFile.xls
		* 			then the directory must be existed on the system and have permissioned properly
		* 			to write the file.
		* @Return : On Success Valid File Pointer to file
		* 			On Failure return false	 
		*/
		function open($file)
		{
			if($this->state!="CLOSED")
			{
				$this->error="Error : Another file is opend .Close it to save the file";
				return false;
			}	
			
			if(!empty($file))
			{
				$this->fp=@fopen($file,"w+");
			}
			else
			{
				$this->error="Usage : New ExcelWriter('fileName')";
				return false;
			}	
			if($this->fp==false)
			{
				$this->error="Error: Unable to open/create File.You may not have permmsion to write the file.";
				return false;
			}
			$this->state="OPENED";
			fwrite($this->fp,$this->GetHeader());
			return $this->fp;
		}
		
		function close()
		{
			if($this->state!="OPENED")
			{
				$this->error="Error : Please open the file.";
				return false;
			}	
			if($this->newRow)
			{
				fwrite($this->fp,"</tr>");
				$this->newRow=false;
			}
			
			fwrite($this->fp,$this->GetFooter());
			fclose($this->fp);
			$this->state="CLOSED";
			return ;
		}
		/* @Params : Void
		*  @return : Void
		* This function write the header of Excel file.
		*/
		 							
		function GetHeader()
		{
			// Jun 07, 2007 - mdeangelis - replaced header
	
			//-------------------------------------
/*
			$header = <<<EOH
				<html xmlns:o="urn:schemas-microsoft-com:office:office"
				xmlns:x="urn:schemas-microsoft-com:office:excel"
				xmlns="http://www.w3.org/TR/REC-html40">

				<head>
				<meta http-equiv=Content-Type content="text/html; charset=us-ascii">
				<meta name=ProgId content=Excel.Sheet>
				<!--[if gte mso 9]><xml>
				 <o:DocumentProperties>
				  <o:LastAuthor>SugarCRM</o:LastAuthor>
				  <o:LastSaved>2005-01-02T07:46:23Z</o:LastSaved>
				  <o:Version>10.2625</o:Version>
				 </o:DocumentProperties>
				 <o:OfficeDocumentSettings>
				  <o:DownloadComponents/>
				 </o:OfficeDocumentSettings>
				</xml><![endif]-->
				<style>
				<!--table
					{mso-displayed-decimal-separator:"\.";
					mso-displayed-thousand-separator:"\,";}
				@page
					{margin:1.0in .75in 1.0in .75in;
					mso-header-margin:.5in;
					mso-footer-margin:.5in;}
				tr
					{mso-height-source:auto;}
				col
					{mso-width-source:auto;}
				br
					{mso-data-placement:same-cell;}
				.style0
					{mso-number-format:General;
					text-align:general;
					vertical-align:bottom;
					white-space:nowrap;
					mso-rotate:0;
					mso-background-source:auto;
					mso-pattern:auto;
					color:windowtext;
					font-size:10.0pt;
					font-weight:400;
					font-style:normal;
					text-decoration:none;
					font-family:Arial;
					mso-generic-font-family:auto;
					mso-font-charset:0;
					border:none;
					mso-protection:locked visible;
					mso-style-name:Normal;
					mso-style-id:0;}
				td
					{mso-style-parent:style0;
					padding-top:1px;
					padding-right:1px;
					padding-left:1px;
					mso-ignore:padding;
					color:windowtext;
					font-size:10.0pt;
					font-weight:400;
					font-style:normal;
					text-decoration:none;
					font-family:Arial;
					mso-generic-font-family:auto;
					mso-font-charset:0;
					mso-number-format:General;
					text-align:general;
					vertical-align:bottom;
					border:none;
					mso-background-source:auto;
					mso-pattern:auto;
					mso-protection:locked visible;
					white-space:nowrap;
					mso-rotate:0;}
				.xl24
					{mso-style-parent:style0;
					white-space:normal;}
				.xl25
					{mso-style-parent:style0;
					mso-number-format:0;}
				.xl26
					{mso-style-parent:style0;
					mso-number-format:"mm\\-dd\\-yyyy";}															
				-->
				</style>
				<!--[if gte mso 9]><xml>
				 <x:ExcelWorkbook>
				  <x:ExcelWorksheets>
				   <x:ExcelWorksheet>
					<x:Name>TTS Report</x:Name>
					<x:WorksheetOptions>
					 <x:Selected/>
					 <x:ProtectContents>False</x:ProtectContents>
					 <x:ProtectObjects>False</x:ProtectObjects>
					 <x:ProtectScenarios>False</x:ProtectScenarios>
					</x:WorksheetOptions>
				   </x:ExcelWorksheet>
				  </x:ExcelWorksheets>
				  <x:WindowHeight>10005</x:WindowHeight>
				  <x:WindowWidth>10005</x:WindowWidth>
				  <x:WindowTopX>120</x:WindowTopX>
				  <x:WindowTopY>135</x:WindowTopY>
				  <x:ProtectStructure>False</x:ProtectStructure>
				  <x:ProtectWindows>False</x:ProtectWindows>
				 </x:ExcelWorkbook>
				</xml><![endif]-->
				</head>

				<body link=blue vlink=purple>
				<table x:str border=0 cellpadding=0 cellspacing=0 style='border-collapse: collapse;table-layout:fixed;'>
EOH;
*/
			$header = <<<EOH
<html xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:x="urn:schemas-microsoft-com:office:excel"
xmlns="http://www.w3.org/TR/REC-html40">
				

<head>
<meta http-equiv=Content-Type content="text/html; charset=us-ascii">
<meta name=ProgId content=Excel.Sheet>
<!--[if gte mso 9]><xml>
 <o:DocumentProperties>
  <o:LastAuthor>TTS</o:LastAuthor>
  <o:LastSaved>2007-06-07T16:16:24Z</o:LastSaved>
  <o:Version>11.6568</o:Version>
 </o:DocumentProperties>
 <o:OfficeDocumentSettings>
  <o:DownloadComponents/>
  <o:LocationOfComponents HRef="file:///E:\"/>
 </o:OfficeDocumentSettings>
</xml><![endif]-->
<style>
<!--table
	{mso-displayed-decimal-separator:"\.";
	mso-displayed-thousand-separator:"\,";}
@page
	{margin:1.0in .75in 1.0in .75in;
	mso-header-margin:.5in;
	mso-footer-margin:.5in;}
tr
	{mso-height-source:auto;}
col
	{mso-width-source:auto;}
br
	{mso-data-placement:same-cell;}
.style0
	{mso-number-format:General;
	text-align:general;
	vertical-align:bottom;
	white-space:nowrap;
	mso-rotate:0;
	mso-background-source:auto;
	mso-pattern:auto;
	color:windowtext;
	font-size:10.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	border:none;
	mso-protection:locked visible;
	mso-style-name:Normal;
	mso-style-id:0;}
td
	{mso-style-parent:style0;
	padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:windowtext;
	font-size:10.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	mso-number-format:General;
	text-align:general;
	vertical-align:bottom;
	border:none;
	mso-background-source:auto;
	mso-pattern:auto;
	mso-protection:locked visible;
	white-space:nowrap;
	mso-rotate:0;}
.xl24
	{mso-style-parent:style0;
	font-weight:700;
	text-decoration:underline;
	text-underline-style:single;}
.xl25
	{mso-style-parent:style0;
	mso-number-format:"Short Date";}
.xl26
	{mso-style-parent:style0;
	mso-number-format:Percent;}
.xl27
	{mso-style-parent:style0;
	mso-number-format:"Long Time";}
.xl28
	{mso-style-parent:style0;
	mso-number-format:"0\.0%";}
.xl29
	{mso-style-parent:style0;
	mso-number-format:"Short Date";}
.xl30
	{mso-style-parent:style0;
	mso-number-format:0%;}
.xl31
	{mso-style-parent:style0;
	mso-number-format:Fixed;}
.xl32
	{mso-style-parent:style0;
	mso-number-format:"0\.0";}
-->
</style>
<!--[if gte mso 9]><xml>
 <x:ExcelWorkbook>
  <x:ExcelWorksheets>
   <x:ExcelWorksheet>
    <x:Name>TTS Report</x:Name>
    <x:WorksheetOptions>
     <x:Selected/>
     <x:Panes>
      <x:Pane>
       <x:Number>3</x:Number>
       <x:ActiveRow>13</x:ActiveRow>
       <x:ActiveCol>9</x:ActiveCol>
      </x:Pane>
     </x:Panes>
     <x:ProtectContents>False</x:ProtectContents>
     <x:ProtectObjects>False</x:ProtectObjects>
     <x:ProtectScenarios>False</x:ProtectScenarios>
    </x:WorksheetOptions>
   </x:ExcelWorksheet>
  </x:ExcelWorksheets>
  <x:WindowHeight>10005</x:WindowHeight>
  <x:WindowWidth>10005</x:WindowWidth>
  <x:WindowTopX>120</x:WindowTopX>
  <x:WindowTopY>135</x:WindowTopY>
  <x:ProtectStructure>False</x:ProtectStructure>
  <x:ProtectWindows>False</x:ProtectWindows>
 </x:ExcelWorkbook>
</xml><![endif]-->
</head>	

<body link=blue vlink=purple>
<table x:str border=0 cellpadding=0 cellspacing=0 style='border-collapse: collapse;table-layout:fixed;'>
	
EOH;
			return $header;
		}
		
		function GetFooter()
		{
			return "</table></body></html>";
		}
		
		/*
		* @Params : $line_arr: An valid array 
		* @Return : Void
		*/
		
		/* 
		/////////////////////////////////////////////////////////////////////////////
		// Jun 07, 2007 - mdeangelis - removed 
		function writeLine($line_arr)
		{
			if($this->state!="OPENED")
			{
				$this->error="Error : Please open the file.";
				return false;
			}	
			if(!is_array($line_arr))
			{
				$this->error="Error : Argument is not valid. Supply an valid Array.";
				return false;
			}
			fwrite($this->fp,"<tr>");
			foreach($line_arr as $col)
				fwrite($this->fp,"<td class=xl24 width=64 >$col</td>");
			fwrite($this->fp,"</tr>");
		}
		////////////////////////////////////////////////////////////////////////////
		*/

		/*
		* @Params : Void
		* @Return : Void
		*/
		function writeRow()
		{
			if($this->state!="OPENED")
			{
				$this->error="Error : Please open the file.";
				return false;
			}	
			if($this->newRow==false)
				fwrite($this->fp,"<tr>\r\n");
			else
				fwrite($this->fp,"</tr>\r\n<tr>\r\n");
			$this->newRow=true;	
		}

		/*
		* @Params : $value : Column Value
		* @Return : Void
		*/
		function writeCol($value, $cell_format_spec = TTS_DEFAULT_EXCEL_CELL_FORMAT )
		{
			if($this->state!="OPENED")
			{
				$this->error="Error : Please open the file.";
				return false;
			}	
			 
			//------------------------------------------------------------------
			// Jun 08, 2007 - mdeangelis - if sec exists, use it
			//------------------------------------------------------------------
			$attributes = '';
			
			if ( array_key_exists($cell_format_spec, $this->tts_format_attributes) )
			{
				$attributes = $this->tts_format_attributes[$cell_format_spec];
			}
			
			$TD = "<td ".$attributes.">".$value."</td>\r\n";
			
			fwrite($this->fp, $TD);

			//----------------------------------------------------------------------------------------------------------------------
			//fwrite($this->fp,"<td class=xl24 width=64 >$value</td>"); // Jun 8, 2007 - mdeangelis - extend author's basic approach 
			//----------------------------------------------------------------------------------------------------------------------
		}
	}
?>
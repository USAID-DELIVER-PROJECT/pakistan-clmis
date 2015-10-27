<?php
	
/***********************************************************************************************************

Developed by Syed Aun Irtaza email: aun.irtaza@hotmail.com

This is our main graph stage which takes the parameters present in the querystring of the templategraphreport
this file is included in templategraphreport.php. It takes all parameters and on the basis of different graph
cases it passes the relevent parameters to the line_all_comp.php which is our graph file. It takes the cases
as on the basis of cases the graph series labels will appear.

This file serve as an area to mail the graphs which are in pdf form to the relevent email address aswell.

/***********************************************************************************************************/
include("FusionCharts/Code/PHP/Includes/FusionCharts.php");
   //print_r($_SESSION);
   
	$count= intval($_REQUEST['count']); 
	$year1=intval($_REQUEST['year1']);
	$year2=intval($_REQUEST['year2']);

// used to get report titles    
    $seluser = $_SESSION['seluser'];
    $optvals = $_SESSION['optvals'];
	$report_id = $_SESSION['rep_id'];

    
    
	$allfiles=$_REQUEST['allfiles']; // csv file names  
	$allfiles_pdf=$_REQUEST['allfiles'];   
	$allfiles=explode(",",$allfiles);
	$arrproducts = $_SESSION['prodtitles'];  // all product titles
	$seriescount = $_SESSION['seriescount'];	
	
	$titles = $_REQUEST['titles']; // graph titles
	$titles=explode(",",$titles);
	$yearcomp= $_REQUEST['yearcomp']; // yearly comparison graphs use this data
        $label = $_REQUEST['label'];
	$yearcomp= explode(",",$yearcomp);
	$yearcomp= array_reverse($yearcomp);
	$yearcomp=implode(",",$yearcomp);
	$provcomp= $_REQUEST['provinces']; // provincial comparison graphs use this data
	$distscomp= $_REQUEST['districts']; // district comparison graphs use this data
	$countyears = explode(",",$yearcomp);
	$countprov = explode(",",$provcomp);
	$countdists = explode(",",$distscomp);
	$countprods = explode(",",$arrproducts); 
	$seriescount= count($countyears);	
	$case=intval($_REQUEST['case']);
	$col=$_REQUEST['col'];
	$unit=$_REQUEST['unit']; // unit in graph like (millions)
	$rep_title1=$_REQUEST['rep_title1'];
	$rep_title2=$_REQUEST['rep_title2'];
	$rep_title3=$_REQUEST['rep_title3'];
	$rep_logo  =$_REQUEST['rep_logo'];
	$xaxis     =$_REQUEST['xaxis'];
	$period_lable     =$_REQUEST['period_lable'];
	$comparison_title     =$_REQUEST['comparison_title']; // every graph's title appearing above each graph
	$_SESSION['graphyear']=$year1;
	$_SESSION['arrayallproduct']=$arrproducts;

	//print_r($_SESSION['arrayallproduct']);
	//echo $_SESSION['arrayallproduct'];
	$allprotitle=explode(",",$arrproducts);
	$_SESSION['arrayallproduct']=$allprotitle;
        
        if ($label == '1') {
            $label_show = 'checked';
            $label_hide = 'unchecked';  
        }
        else if ($label == '0') {
            $label_hide = 'checked';
            $label_show = 'unchecked';
        }
        else{
            $label_show = 'checked';
            $label_hide = 'unchecked';
        }
        
        //if($graphCaption)
	//print_r($_SESSION['arrayallproduct']);
	
	/*	echo "<pre>";
	print_r($_REQUEST);
	print_r($_SESSION);
	echo "</pre>";		
	$file_handle = fopen($allfiles[0], "r");
				$month = array();
				while(!feof($file_handle)){
					$line_of_text = fgetcsv($file_handle);
					if($line_of_text[0]!=""){
					$month[] = $line_of_text[0];
					//$value = array();
					for($z=1; $z<4; $z++){
					//echo $month."==".$z;
					$line_of_text[$z];
					$value[$z][] = $line_of_text[$z];
					}
					
					}
					
					//$code[] = $line_of_text[2];
				}
	

 
		
		print_r($value);
				echo "<pre>";
				for($z=1; $z<4; $z++){
				echo "max".$value[$z];
				}
				echo "</pre>";*fclose($file_handle);/
			
		
			
	//print_r($allfiles);
	/*			
		//echo $allfiles[0];
		if($optvals==9){
		echo "National";}
		if($optvals==10){
		echo "Provincial";}
			if($optvals==11){
		echo "District";}	function recursive_array_max($a) {
    foreach ($a as $value) {
        if (is_array($value)) {
            $value = recursive_array_max($value);
        }
        if (!(isset($max))) {
            $max = $value;
        } else {
            $max = $value > $max ? $value : $max;
        }
    }
    return $max;
}
	$max = recursive_array_max($value);
echo $max=round($max,0);

echo "<p>The maximum value was: {$max}</p>";
	echo "length of max integer = ".$length_max_value=strlen($max);
			echo "<br>";
			echo "first two character = ".$first_two_max=substr($max,0,2);
			echo "<br>";
			echo "plus 1 = ".$first_two_plus_1=$first_two_max+5;
			echo "<br>";
			echo "Generated max value = ".$generated_max_value=str_pad($first_two_plus_1, $length_max_value, "0", STR_PAD_RIGHT);
			echo "<br>";
	 $_SESSION['graphtitles']=$titles[0];
	 echo "<pre>";
	//print_r($_SESSION['graphtitles']);
	
	//
	$_SESSION['$allfiles']=$allfiles;
	//print_r($_SESSION['$allfiles']);
	

			//print_r($value);
			//echo sizeof($value);
			echo "max value = ".max($value);
			echo "<br>";
			echo "length of max integer = ".$length_max_value=strlen(max($value));
			echo "<br>";
			echo "first two character = ".$first_two_max=substr(max($value),0,2);
			echo "<br>";
			echo "plus 1 = ".$first_two_plus_1=$first_two_max+1;
			echo "<br>";
			echo "Generated max value = ".$generated_max_value=str_pad($first_two_plus_1, $length_max_value, "0", STR_PAD_RIGHT);
			echo "<br>";
			
			*/
        $Title = explode("->",$graphCaption);
        $Title = preg_replace('/[^A-Za-z0-9\-]/', '', $Title);
        if($Title[0] == "CoupleYearProtection"){
            $yAxisTitle = "Couples";
            $decimal = "1";
        }
        else if($Title[0] == "MonthsOfStock-Field" || $Title[0] == "MonthsOfStock-Whse" || $Title[0] == "MonthsOfStock-Total"){
            $yAxisTitle = "Months";
            $decimal = "2";
        }
        else{
            $yAxisTitle = "Units";
            $decimal = "1";
        }
        
	$stakecomp= $_REQUEST['stakecomp']; // stakeholder wise comparison graphs will use it.
	$countstakes = explode(",",$stakecomp);
	if($case==1 || $case==2)
	{
		$seriescount= $seriescount;
	}
	if($case==3)
	{
		$seriescount= count($countstakes);
	}
	if($case==4)
	{
		$seriescount= count($countprov); 
	}
	if($case==5)
	{
		$seriescount= count($countdists); 
	}
	if($case==6)
	{
		$seriescount= count($countprods); 
	}
	
	$seriescount;
	if(isset($_REQUEST['ctype'])) 
    {		
	  $charttype = $_REQUEST['ctype'];
	 } else
	 {
	  $charttype = "line"; 
	  }
	  
	  
////////////// FUNCTION TO REMOVE ALL CSV FILES FROM "PLMIS_DATA" FOLDER
function EmptyDir($dir) {
	$handle=opendir($dir);
	
	while (($file = readdir($handle))!==false) {
	@unlink($dir.'/'.$file);
	}
	closedir($handle);
}

	
	
?>

<!--
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML>
	<HEAD>-->
		
		<SCRIPT LANGUAGE="JAVASCRIPT" TYPE="TEXT/JAVASCRIPT" SRC="../../plmis_js/UserGroup.js"></SCRIPT>
         <SCRIPT LANGUAGE="Javascript" SRC="FusionCharts/Code/FusionCharts/FusionCharts.js"></SCRIPT>
     	<SCRIPT LANGUAGE="JAVASCRIPT" TYPE="TEXT/JAVASCRIPT">
			<!--
				var width = 280, height = 360;
				window.onerror = ScriptError;				
				function ScriptError()
					{
						
					}
				function ShowData(RowID)
					{
						document.frmData.ActionType.value="EditShow"
						document.frmData.PrvRecordID.value=RowID
						document.frmData.submit();
					}
				function DeleteData(RowID)
					{
						var msg = confirm("Are You Sure, Want To Delete This Record ?",true);
						if(!(msg)) 
						{
							return false;
						}
						else
						{
							document.frmData.ActionType.value="DeleteData"
							document.frmData.PrvRecordID.value=RowID
							document.frmData.submit();
                            window.parent.refreshNoToggle();
						}
					}
					
					function emailfunc()
					{
						//$('#graph_div').fadeIn("slow");
						//$('#graph_div').hide("slow");
						var val= document.getElementById("chk").value;
						if(val==0)
						{
						document.getElementById("graph_div").style.display="none";
						document.getElementById("export_div").style.display="none";
						document.getElementById("import_div").style.display="none";
						$('#email_div').show("slow");
						document.getElementById("chk").value=1;
						}
						else
						{
						document.getElementById("email_div").style.display="none";
						document.getElementById("export_div").style.display="none";
						document.getElementById("import_div").style.display="none";
						$('#graph_div').show("slow");
						document.getElementById("chk").value=0;
						}
						
					}
					function emailpdf()
					{
						var email=document.getElementById('txtemail').value;						
						var txtSubject=document.getElementById('txtSubject').value;						
						var comment=document.getElementById('comment').value;
						window.location='emailpdf.php?allfiles=<?php echo $allfiles_pdf; ?>&comparison_title=<?php echo $_REQUEST['comparison_title'];?>&rep_desc=<?php  echo $_REQUEST['rep_desc']; ?>&email='+email+'&txtSubject='+txtSubject+'&comment='+comment;
					}
					function exportfunc()
					{
						var val= document.getElementById("chk").value;
						if(val==0)
						{
						document.getElementById("graph_div").style.display="none";
						document.getElementById("export_div").style.display="none";
						document.getElementById("email_div").style.display="none";
						$('#export_div').show("slow");
						document.getElementById("chk").value=1;
						}
						else
						{
						document.getElementById("import_div").style.display="none";
						document.getElementById("email_div").style.display="none";
						document.getElementById("export_div").style.display="none";
						$('#graph_div').show("slow");
						document.getElementById("chk").value=0;
						}
						
						
						
					}
					function exportdata()
					{
						var monthsel=document.getElementById('monthsel').value;
						var yearsel =document.getElementById('yearsel').value;
						window.location='exportdata.php?monthsel='+monthsel+'&yearsel='+yearsel;
						
					}
					
					function importfunc()
					{
						
						var val= document.getElementById("chk").value;
						if(val==0)
						{
						document.getElementById("graph_div").style.display="none";
						document.getElementById("export_div").style.display="none";
						document.getElementById("email_div").style.display="none";
						$('#import_div').show("slow");
						document.getElementById("chk").value=1;
						}
						else
						{
						document.getElementById("import_div").style.display="none";
						document.getElementById("email_div").style.display="none";
						document.getElementById("export_div").style.display="none";
						$('#graph_div').show("slow");
						document.getElementById("chk").value=0;
						}
						
					}
					
					function importdata()
					{
						var filedata=document.getElementById('filedata').value;
						alert(filedata);
						//window.location='exportdata.php?filedata='+filedata;	
					}
                                        
                                     
                                       function switchLabel(value){
                                           var URL = location.href;
                                           var split = URL.split("&label");  
                                           window.location.href  = split[0]+"&label="+value;  
                                       }
			//-->
		</SCRIPT> 
<!--        <script type="text/javascript">
            jQuery(document).ready(function($) {
              $('a[rel*=facebox]').facebox({
                loading_image : 'loading.gif',
                close_image   : 'closelabel.gif'
              }) 
            })
        </script>-->
        <script language="javascript">

		function CheckAll(){
			for (var a=0; a<document.frm.elements.length; a++){
				var e = document.frm.elements[a];
	
				if (e.name != 'selectUnselectAll'){
					e.checked = document.frm.selectUnselectAll.checked;
				}
			}
		}
	
</script>
<style>
.new_Input {
    border: 1px solid #CDCFCF;
    border-radius: 3px 3px 3px 3px;
    box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.1) inset;
    color: #858788;
    font-family: Arial,Helvetica,sans-serif;
    font-size: 14px;
    height: 35px;
    width: 200px;
}
.textArea {
    font-size:15px;
	color:#000;
	border: 1px solid #CDCFCF;
	border-radius: 3px 3px 3px 3px;
	box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.1) inset;
	color: #858788;
	font-size:14px;font-family:Arial, Helvetica, sans-serif; 
}
.new_Label {
    color: #333333;
    font-family: Arial,Helvetica,sans-serif;
    font-size: 15px;
    font-weight: bold;
    width: 100px;
}
.sb1Exception {
    color: red;
    font-family: Arial,Verdana,Helvetica,sans-serif;
    font-size: 18px;
    font-weight: bold;
    text-decoration: none;
}
</style>	
									
<!--	</HEAD>
	<BODY LEFTMARGIN="10" TOPMARGIN="5" MARGINWIDTH="0" MARGINHEIGHT="0" CLASS="WorkArea">-->
<script type="text/javascript">
    
        var win = window,
	doc = win.document,
	encode = win.encodeURIComponent || win.escape;
	// this function exports chart from JavaScript
	function exportChartStacked(exportFormat)
	{
		// checks if exportChart function is present and call exportChart function
		if ( FusionCharts("myFirst").exportChart ){
                          if (exportFormat == 'Excel')
		{
                    	var chartObj = FusionCharts("myFirst");  
			var csvData = chartObj.getDataAsCSV();
			//window.alert(chartObj.getDataAsCSV());
			
			var exportFormat = 'Excel',
			temporaryElement,
			obj,
			key;
			
		
				temporaryElement = doc.createElement('a');
				// We set the attributes of the temporary anchor element that
				// in such fashion as clicking on it induces download of the
				// CSV data from the chart.
				for (key in (obj = {
					href: 'data:attachment/csv,' + encode(csvData),
					target: '_blank',
					download: exportFormat+'.csv'
				}))
				{
					temporaryElement.setAttribute(key, obj[key]);
				}
				doc.body.appendChild(temporaryElement);
				
				// We emulate clicking by calling the click event handler and
				// post that get rid of the anchor to save very precious memory.
				temporaryElement.click();
				temporaryElement.parentNode.removeChild(temporaryElement);
				temporaryElement = null;
			
			
			return;
                } 
               else{
                    FusionCharts("myFirst").exportChart( { "exportFormat" : exportFormat } )
                }
                
                    }
		else{
			alert ( "Please wait till the chart completes rendering..." )
                    }
	}
	
       

	function exportChart(exportFormat, chartID)
	{
           
		// checks if exportChart function is present and call exportChart function
		if ( FusionCharts(chartID).exportChart ){
                    if (exportFormat == 'Excel')
		{
                   
			var chartObj = FusionCharts(chartID);  
			var csvData = chartObj.getDataAsCSV();
			//window.alert(chartObj.getDataAsCSV());
			
			var exportFormat = 'csv',
			temporaryElement,
			obj,
			key;
			
		
				temporaryElement = doc.createElement('a');
				// We set the attributes of the temporary anchor element that
				// in such fashion as clicking on it induces download of the
				// CSV data from the chart.
				for (key in (obj = {
					href: 'data:attachment/csv,' + encode(csvData),
					target: '_blank',
					download: chartID+'.csv'
				}))
				{
					temporaryElement.setAttribute(key, obj[key]);
				}
				doc.body.appendChild(temporaryElement);
				
				// We emulate clicking by calling the click event handler and
				// post that get rid of the anchor to save very precious memory.
				temporaryElement.click();
				temporaryElement.parentNode.removeChild(temporaryElement);
				temporaryElement = null;
			
			
			return;
		}
                else{
                    FusionCharts(chartID).exportChart( { "exportFormat" : exportFormat } )
                }
                
                
			
                    }
		else{
                    alert ( "Please wait till the chart completes rendering..." )
                    }
	}
	
	
function goLocation(){
	window.location = "sendgraphemail.php";	
}
</script>
<script type="text/javascript">
	var initiateExport = false;
	function exportCharts(exportFormat)
	{
		initiateExport = true;
		for ( var chartRef in FusionCharts.items )
		{
			//alert(chartRef);
			if ( FusionCharts.items[chartRef].exportChart ){
				//document.getElementById ( "linkToExportedFile" ).innerHTML = "Exporting...";
				FusionCharts.items[chartRef].exportChart( { "exportFormat" : exportFormat } );
			} else{
				//document.getElementById ( "linkToExportedFile" ).innerHTML = "Please wait till the chart completes rendering..." ;
			}
		}
	}
function FC_Exported ( statusObj )
{
	if (initiateExport){
		initiateExport = false;
		//document.getElementById ( "linkToExportedFile" ).innerHTML = "";
	}
	if ( statusObj.statusCode == "1" ){
		document.getElementById("confirm").disabled = false;
		return false;
	}else{
		//document.getElementById ( "linkToExportedFile" ).innerHTML += "Export unsuccessful. Notice from export handler : " + statusObj.notice + "<br/>" ;
	}
}


</script>

<div style="border:0px solid green; width:100% !important; min-height:679px;">
     
           <TABLE  WIDTH="100%" BORDER = "0"  ALIGN = "CENTER"  BORDERCOLOR = "#000000" style="background-color:#f1f1f1;" >
                <TR>
                    <TD ALIGN = "CENTER">
                        <INPUT TYPE="radio" name="label" <?php print $label_show; ?> onclick="switchLabel('1')"> Show Graph Label </INPUT>    
                    </TD> 
                    <TD ALIGN = "CENTER">
                       <INPUT TYPE="radio" name="label" <?php print $label_hide;?>  onclick="switchLabel('0')"> Hide Graph Label </INPUT>  
                    </TD>
                </TR>
      </TABLE>
    <BR/>
    <div id="graph_div" style="border:0px solid red; width:100%;">
    <table width="100%" cellspacing="0" cellpadding="0" class="maintbl" align="center">
		
		<tr>
			<td class="middlearea" valign="top">
			<table cellspacing="0" cellpadding="10" width="100%" height="100%" >
				<tr>
			<!--    	<td width="5%" valign="top" id="leftnav">&nbsp;</td>-->
                    			
                     
			        <td width="100%" valign="top" align="center">
                    <form name="frm" method="post" enctype="multipart/form-data">
					
                    <table width="100%" border="0" cellpadding="5" cellspacing="0" class="tbllisting"  style="border:0px solid #d1d1d1;" >
                    	
                      <?php  	global $counter;
						  		$counter = 0;?>
									<!--<table>
                                    	<tr>
                                        	<td><a id="imgID" href="JavaScript:exportCharts('PNG');increment()"><img id="imgID" src="../../images/email-icon.gif" style="cursor:pointer;" /></a></td>
                                            <td><input type="button" name="confirm" id="confirm" value="Confirm to send" disabled="disabled" onclick="goLocation()" /></td>
                                        </tr>
                                    </table>-->
								<?php
								//echo $graphCaption;
								
								for($i=0;$i<sizeof($allfiles);$i++) {//echo $_SERVER['QUERY_STRING'];
									$productsArray1 = explode(",", $_GET['titles']);
									
									$rplcArr = array("/","(",")",".","-"," ","1","2","3","4","5","6","7","8","9","0");
									$rplcArr1 = array("w","x","y","z","_","a","b","c","d","e","f","g","h","i","j","k");
									
									$temp = str_replace($rplcArr,$rplcArr1,$_GET['titles']);
									$productsArray = explode(",", $temp);
																	
									//define('ROOT_PATH', "E:\\wamp\\www\\paklmis_final\\plmis_src\\graph\\xml_cyp\\");
									?>
						<td colspan="7" style="border:0px solid green;">
                              <table width="100%">
                              </table>
                          </td>
						<tr>
                        	<td colspan="4">
							<div> 
                                                            
                                                  
                             <?php /* single year no comparison graphs */ if($case==1){ //echo "case-1"; ?>
<img src='linegraph_all_comp.php?filename=<?php echo $allfiles[$i];?>&title=<?php echo $titles[$i];?>&years=<?php echo $year1;?>&year=<?php echo $year1;?>&seriescount=<?php echo "1";?>&ctype=<?php echo $charttype;?>&seluser=<?php echo $seluser;?>&optvals=<?php echo $optvals;?>&report_id=<?php echo $report_id;?>' width="700">
                            <?php }?>
                            <?php /* multiple years comparison graphs */ if($case==2){ 
								
								if(isset($_GET['yearcomp']) && $_GET['yearcomp'] != ""){ //echo "case-2-IF";
									if ($counter == 0){
										$yearArray = explode(",", $_GET['yearcomp']);
                                                                                
                                                                                
										
										for ($x=0; $x<sizeof($productsArray);$x++){
											//$xmlfile_path= ROOT_PATH."/".$productsArray[$x].".xml";
                                                                                    $rangeValue = array();
                                                                                    $min;$max;$xAxisTitle;
                                                                                    
                                                                                    $fp = fopen($allfiles[$x],'r');
											for ($k=0; $k < sizeof($yearArray); $k++){
												$file_handle = fopen($allfiles[$x], "r");
												while(!feof($file_handle)){
													for ($z=0; $z<sizeof($yearArray); $z++){
														$line_of_text = fgetcsv($file_handle);
														if($line_of_text[1]!=""){	
                                                                                                                        array_push($rangeValue, $line_of_text[$k+1]);
														}
													}
												}
											}
                                                                                    
                                                                                    
                                                                                        $min = min($rangeValue);
                                                                                        $max = max($rangeValue);
                                                                                        $minMaxPercent = ($max * 10 / 100);
                                                                                        $min = $min - $minMaxPercent; 
                                                                                        $max = $max + $minMaxPercent; 
                                                                                        if($min < 0){$min = 0;}
                                                                                        
                                                                                        if($min < 1000 && $max < 1000){
                                                                                            $xAxisTitle = ""; 
                                                                                        }
                                                                                        else if($min < 1000000 && $max > 1000000){
                                                                                            $xAxisTitle = "(k = Thousand , M = Million)";
                                                                                        }
                                                                                        else if($min < 1000000 && $max < 1000000){
                                                                                            $xAxisTitle = "(k = Thousand)";
                                                                                        }
                                                                                        else{
                                                                                            $xAxisTitle = "(M = Million)";
                                                                                        }
                                                                                       
											$xmlstore="<chart exportEnabled='1' canvasPadding='23' decimals='$decimal' exportAction='Download' caption='$productsArray1[$x]' subCaption='$graphCaption' yAxisMinValue='$min'  yAxisMaxValue='$max' adjustDiv='0' numDivLines='3' yAxisName='$yAxisTitle $xAxisTitle' numberPrefix=''  showValues='$label'  formatNumberScale='1' exportHandler='FusionCharts/Code/PHP/ExportHandler/FCExporter.php' exportAtClient='0' >\n";
											$xmlstore .="<categories>\n";
											$fp = fopen($allfiles[$x],'r');
											while($csv_line = fgetcsv($fp,1024)) {
												//for ($i = 0, $j = count($csv_line); $i < $j; $i++) {
													$xmlstore .="\t<category label='".$csv_line[0]."'/>\n";
												//}
											}
											
											$xmlstore .="</categories>\n";
											for ($k=0; $k < sizeof($yearArray); $k++){
												$xmlstore .="<dataset seriesName='".$yearArray[sizeof($yearArray) - $k - 1]."'>\n";
												$file_handle = fopen($allfiles[$x], "r");
												while(!feof($file_handle)){
													for ($z=0; $z<sizeof($yearArray); $z++){
														$line_of_text = fgetcsv($file_handle);
														if($line_of_text[1]!=""){
															$xmlstore .= "\t<set value='" .$line_of_text[$k+1]. "'/>\n";
														}
													}
												}
												$xmlstore .="</dataset>\n";
											}
                                                                  
											$xmlstore .="</chart>\n";
                                                                                        
                                                                               
                                                                           
											//$handle = fopen($xmlfile_path, 'w');
											
											//fwrite($handle, $xmlstore);
											
											//$xmlFile = "xml_cyp/".$productsArray[$x].".xml";?>
											
											<div id="exportBittons" align="right">
                                               
                                                <img src="../../images/excel-16.png" onClick="JavaScript:exportChart('Excel', '<?php echo $productsArray[$x];?>')" style="cursor:pointer;width:20px;height:22px;" />                                        
                                                <img src="../../images/PDF.png" onClick="JavaScript:exportChart('PDF', '<?php echo $productsArray[$x];?>')" style="cursor:pointer;" />
                                                <img src="../../images/JPG.png" onClick="JavaScript:exportChart('JPG', '<?php echo $productsArray[$x];?>')" style="cursor:pointer;" />
                                                <img src="../../images/PNG.png" onClick="JavaScript:exportChart('PNG', '<?php echo $productsArray[$x];?>')" style="cursor:pointer;" />
                                            </div>
											
									<?php   if ($_GET['ctype'] == 'line'){								
												//echo renderChart("FusionCharts/Charts/MSLine.swf", $xmlFile, "", $productsArray[$x], 530, 250, false);
												echo renderChart("FusionCharts/Charts/MSLine.swf", "", $xmlstore, $productsArray[$x], 700, 395, false, false);
											}else if($_GET['ctype'] == 'bar'){
												//echo renderChart("FusionCharts/Charts/MSColumn3D.swf", $xmlFile, "", $productsArray[$x], 530, 250, false);
												echo renderChart("FusionCharts/Charts/MSColumn3D.swf", "", $xmlstore, $productsArray[$x], 700, 395, false, false);
											}echo "<br /><br />";
										fclose($file_handle);
										}
										
										$counter++;
									}
									
									
								}else{  //echo "case-2,Else";
								//////////////// READ CSV FILES AND PLOT GRAPH     
									
									for ($i=0; $i<count($allfiles); $i++){
									//$xmlfile_path= ROOT_PATH."/".$productsArray[$i].".xml";
                                                                                  
									$strXML = "<chart exportEnabled='1' canvasPadding='23' decimals='$decimal' exportAction='Download' caption='$productsArray1[$i]' subCaption='$graphCaption'  xAxisName='$_GET[year1]' yAxisName='$yAxisTitle' showValues='$label' formatNumberScale='1' lineColor='8E9D51' exportHandler='FusionCharts/Code/PHP/ExportHandler/FCExporter.php' exportAtClient='0' >\n";
																	
									$file_handle = fopen($allfiles[$i], "r");
									while(!feof($file_handle)){
										$line_of_text = fgetcsv($file_handle);
										if($line_of_text[0]!=""){
										//$month[] = $line_of_text[0];
										//$value[] = $line_of_text[1];
								
										$strXML .= "\t<set label='" .$line_of_text[0]. "' value='" . $line_of_text[1] . "'/>\n";
										}
									}
									fclose($file_handle);
									//Close <chart> element
									$strXML .= "</chart>";
									//$handle = fopen($xmlfile_path, 'w');
									//fwrite($handle, $strXML);
									//$xmlFile = "xml_cyp/".$productsArray[$i].".xml";
									?>
											
                                    <div id="exportBittons" align="right">
                                        <img src="../../images/excel-16.png" onClick="JavaScript:exportChart('Excel', '<?php echo $productsArray[$i];?>')" style="cursor:pointer;width:20px;height:22px;" />     
                                        <img src="../../images/PDF.png" onClick="JavaScript:exportChart('PDF', '<?php echo $productsArray[$i];?>')" style="cursor:pointer;" />
                                        <img src="../../images/JPG.png" onClick="JavaScript:exportChart('JPG', '<?php echo $productsArray[$i];?>')" style="cursor:pointer;" />
                                        <img src="../../images/PNG.png" onClick="JavaScript:exportChart('PNG', '<?php echo $productsArray[$i];?>')" style="cursor:pointer;" />
                                    </div>
									<?php //Create the chart - Column 2D Chart with data from strXML
									if ($_GET['ctype'] == 'line'){								
										//echo renderChart("FusionCharts/Charts/Line.swf", $xmlFile, "", $productsArray[$i], 530, 250, false);
										echo renderChart("FusionCharts/Charts/Line.swf", "", $strXML, $productsArray[$i], 700, 395, false, false);
									}else if($_GET['ctype'] == 'bar'){
										//echo renderChart("FusionCharts/Charts/Column3D.swf", $xmlFile, "", $productsArray[$i], 530, 250, false);
										echo renderChart("FusionCharts/Charts/Column3D.swf", "", $strXML, $productsArray[$i], 700, 395, false, false);
									}
										echo "<br /><br />";
									}
								
								}
								
								}?>
                            <?php /* stakeholder comparison graphs */ if($case==3){ //echo "case-3"; 
							
								if ($counter == 0){
										$stakeArray = explode(",", $_GET['stakecomp']);
										
										for ($x=0; $x<sizeof($productsArray);$x++){
											//$xmlfile_path= ROOT_PATH."/".$productsArray[$x].".xml";
                                                                                    
                                                                                  
                                                                                       $fp = fopen($allfiles[$x],'r');
											for ($k=0; $k < sizeof($stakeArray); $k++){
												 $rangeValue = array();
                                                                                                 $min;$max;$xAxisTitle;
												$file_handle = fopen($allfiles[$x], "r");
												while(!feof($file_handle)){
													for ($z=0; $z<sizeof($stakeArray); $z++){
														$line_of_text = fgetcsv($file_handle);
														if($line_of_text[1]!=""){	
                                                                                                                    array_push($rangeValue, $line_of_text[$k+1]);
														}
													}
												}
											}
                                                                                        
                                                                                        $min = min($rangeValue);
                                                                                        $max = max($rangeValue);
                                                                                        $minMaxPercent = ($max * 10 / 100);
                                                                                        $min = $min - $minMaxPercent;
                                                                                        $max = $max + $minMaxPercent; 
                                                                                        if($min < 0){$min = 0;}
                                                                                        
                                                                                        if($min < 1000 && $max < 1000){
                                                                                            $xAxisTitle = ""; 
                                                                                        }
                                                                                        else if($min < 1000000 && $max > 1000000){
                                                                                            $xAxisTitle = "(k = Thousand , M = Million)";
                                                                                        }
                                                                                        else if($min < 1000000 && $max < 1000000){
                                                                                            $xAxisTitle = "(k = Thousand)";
                                                                                        }
                                                                                        else{
                                                                                            $xAxisTitle = "(M = Million)";
                                                                                        }
                                                                                        
											$xmlstore="<chart exportEnabled='1' canvasPadding='23' decimals='$decimal' exportAction='Download' caption='$productsArray1[$x]' subCaption='$graphCaption' yAxisMinValue='$min'  yAxisMaxValue='$max' adjustDiv='0' numDivLines='3' yAxisName='$yAxisTitle $xAxisTitle' numberPrefix='' showValues='$label' formatNumberScale='1' exportHandler='FusionCharts/Code/PHP/ExportHandler/FCExporter.php' exportAtClient='0' >\n";
											$xmlstore .="<categories>\n";
											$fp = fopen($allfiles[$x],'r');
											while($csv_line = fgetcsv($fp,1024)) {
												//for ($i = 0, $j = count($csv_line); $i < $j; $i++) {
													$xmlstore .="\t<category label='".$csv_line[0]."'/>\n";
												//}
											}
											
											$xmlstore .="</categories>\n";
											for ($k=0; $k < sizeof($stakeArray); $k++){
												//$xmlstore .="<dataset seriesName='".$stakeArray[sizeof($stakeArray) - $k]."'>\n";
												$xmlstore .="<dataset seriesName='".$stakeArray[$k]."'>\n";
												$file_handle = fopen($allfiles[$x], "r");
												while(!feof($file_handle)){
													for ($z=0; $z<sizeof($stakeArray); $z++){
														$line_of_text = fgetcsv($file_handle);
														if($line_of_text[1]!=""){									
															$xmlstore .= "\t<set value='" .$line_of_text[$k+1]. "' />\n";
														}
													}
												}
												$xmlstore .="</dataset>\n";
											}
											$xmlstore .="</chart>\n";
											//$handle = fopen($xmlfile_path, 'w');
											
											//fwrite($handle, $xmlstore);
											//fclose($file_handle);
											//$xmlFile = "xml_cyp/".$productsArray[$x].".xml";?>
											<div id="exportBittons" align="right">
                                                <img src="../../images/excel-16.png" onClick="JavaScript:exportChart('Excel', '<?php echo $productsArray[$x];?>')" style="cursor:pointer;width:20px;height:22px;" />                                                 
                                                <img src="../../images/PDF.png" onClick="JavaScript:exportChart('PDF', '<?php echo $productsArray[$x];?>')" style="cursor:pointer;" />
                                                <img src="../../images/JPG.png" onClick="JavaScript:exportChart('JPG', '<?php echo $productsArray[$x];?>')" style="cursor:pointer;" />
                                                <img src="../../images/PNG.png" onClick="JavaScript:exportChart('PNG', '<?php echo $productsArray[$x];?>')" style="cursor:pointer;" />
                                            </div>
											<?php 
											if ($_GET['ctype'] == 'line'){								
												//echo renderChart("FusionCharts/Charts/MSLine.swf", $xmlFile, "", $productsArray[$x], 530, 250, false);
												echo renderChart("FusionCharts/Charts/MSLine.swf", "", $xmlstore, $productsArray[$x], 700, 395, false, false);
											}else if($_GET['ctype'] == 'bar'){
												//echo renderChart("FusionCharts/Charts/MSColumn3D.swf", $xmlFile, "", $productsArray[$x], 530, 250, false);
												echo renderChart("FusionCharts/Charts/MSColumn3D.swf", "", $xmlstore, $productsArray[$x], 700, 395, false, false);
											}echo "<br /><br />";
										}
										
										$counter++;
									}
							
							
							
							
							 }?>
                            <?php /* province comparioson graph */ if($case==4){ //echo "case-4";
								if ($counter == 0){
										$prvncArray = explode(",", $_GET['provinces']);
										
										for ($x=0; $x<sizeof($productsArray);$x++){
											//$xmlfile_path= ROOT_PATH."/".$productsArray[$x].".xml";
                                                                                $fp = fopen($allfiles[$x],'r');
                                                                                    for ($k=0; $k < sizeof($prvncArray); $k++){
                                                                                            $rangeValue = array();
                                                                                             $min;$max;$xAxisTitle;
                                                                                            $file_handle = fopen($allfiles[$x], "r");
                                                                                            while(!feof($file_handle)){
                                                                                                    for ($z=0; $z<sizeof($prvncArray); $z++){
                                                                                                            $line_of_text = fgetcsv($file_handle);
                                                                                                            if($line_of_text[1]!=""){
                                                                                                                array_push($rangeValue, $line_of_text[$k+1]);
                                                                                                            }
                                                                                                    }
                                                                                            }
                                                                                    }
                                                                                        
                                                                                $min = min($rangeValue);
                                                                                $max = max($rangeValue);
                                                                                $minMaxPercent = ($max * 10 / 100);
                                                                                $min = $min - $minMaxPercent;
                                                                                $max = $max + $minMaxPercent; 
                                                                                if($min < 0){$min = 0;} 
                                                                                
                                                                                if($min < 1000 && $max < 1000){
                                                                                    $xAxisTitle = ""; 
                                                                                }
                                                                                else if($min < 1000000 && $max > 1000000){
                                                                                    $xAxisTitle = "(k = Thousand , M = Million)";
                                                                                }
                                                                                else if($min < 1000000 && $max < 1000000){
                                                                                    $xAxisTitle = "(k = Thousand)";
                                                                                }
                                                                                else{
                                                                                    $xAxisTitle = "(M = Million)";
                                                                                }
                                                                                        
											$xmlstore="<chart exportEnabled='1' canvasPadding='23' decimals='$decimal' exportAction='Download' caption='$productsArray1[$x]' subCaption='$graphCaption' yAxisMinValue='$min'  yAxisMaxValue='$max' adjustDiv='0' numDivLines='3' yAxisName='$yAxisTitle $xAxisTitle' numberPrefix='' showValues='$label' formatNumberScale='1' exportHandler='FusionCharts/Code/PHP/ExportHandler/FCExporter.php' exportAtClient='0' >\n";
											$xmlstore .="<categories>\n";
											$fp = fopen($allfiles[$x],'r');
											while($csv_line = fgetcsv($fp,1024)) {
												//for ($i = 0, $j = count($csv_line); $i < $j; $i++) {
													$xmlstore .="\t<category label='".$csv_line[0]."'/>\n";
												//}
											}
											
											$xmlstore .="</categories>\n";
											for ($k=0; $k < sizeof($prvncArray); $k++){
												if($prvncArray[$k] == "")
													$prvncName = "CDA/ICT";
												else
													$prvncName = $prvncArray[$k];
												
												$xmlstore .="<dataset seriesName='".$prvncName."'>\n";
												$file_handle = fopen($allfiles[$x], "r");
												while(!feof($file_handle)){
													for ($z=0; $z<sizeof($prvncArray); $z++){
														$line_of_text = fgetcsv($file_handle);
														if($line_of_text[1]!=""){									
															$xmlstore .= "\t<set value='" .$line_of_text[$k+1]. "' />\n";
														}
													}
												}
												$xmlstore .="</dataset>\n";
											}
											$xmlstore .="</chart>\n";
											//$handle = fopen($xmlfile_path, 'w');
											
											//fwrite($handle, $xmlstore);
											//fclose($file_handle);
											//$xmlFile = "xml_cyp/".$productsArray[$x].".xml";?>
											<div id="exportBittons" align="right">
                                                <img src="../../images/excel-16.png" onClick="JavaScript:exportChart('Excel', '<?php echo $productsArray[$x];?>')" style="cursor:pointer;width:20px;height:22px;" />                                                 
                                                <img src="../../images/PDF.png" onClick="JavaScript:exportChart('PDF', '<?php echo $productsArray[$x];?>')" style="cursor:pointer;" />
                                                <img src="../../images/JPG.png" onClick="JavaScript:exportChart('JPG', '<?php echo $productsArray[$x];?>')" style="cursor:pointer;" />
                                                <img src="../../images/PNG.png" onClick="JavaScript:exportChart('PNG', '<?php echo $productsArray[$x];?>')" style="cursor:pointer;" />
                                            </div>
											<?php 
											if ($_GET['ctype'] == 'line'){								
												//echo renderChart("FusionCharts/Charts/MSLine.swf", $xmlFile, "", $productsArray[$x], 530, 250, false);
												echo renderChart("FusionCharts/Charts/MSLine.swf", "", $xmlstore, $productsArray[$x], 700, 395, false, false);
											}else if($_GET['ctype'] == 'bar'){
												//echo renderChart("FusionCharts/Charts/MSColumn3D.swf", $xmlFile, "", $productsArray[$x], 530, 250, false);
												echo renderChart("FusionCharts/Charts/MSColumn3D.swf", "", $xmlstore, $productsArray[$x], 700, 395, false, false);
											}
											echo "<br /><br />";										
										}
										
										$counter++;
									}		
							
							 }?>
                            <?php /* district comparison graphs */ if($case==5){ //echo "case-5"; 
								if ($counter == 0){
										$districtArray = explode(",", $_GET['districts']);
										
										for ($x=0; $x<sizeof($productsArray);$x++){
											//$xmlfile_path= ROOT_PATH."/".$productsArray[$x].".xml";
                                                                                $fp = fopen($allfiles[$x],'r');
                                                                                    for ($k=0; $k < sizeof($districtArray); $k++){
                                                                                            $rangeValue = array();
                                                                                             $min;$max;$xAxisTitle;
                                                                                            $file_handle = fopen($allfiles[$x], "r");
                                                                                            while(!feof($file_handle)){
                                                                                                    for ($z=0; $z<sizeof($districtArray); $z++){
                                                                                                            $line_of_text = fgetcsv($file_handle);
                                                                                                            if($line_of_text[1]!=""){	
                                                                                                                array_push($rangeValue, $line_of_text[$k+1]);
                                                                                                            }
                                                                                                    }
                                                                                            }
                                                                                    }

                                                                                $min = min($rangeValue);
                                                                                $max = max($rangeValue);
                                                                                $minMaxPercent = ($max * 10 / 100);
                                                                                $min = $min - $minMaxPercent;
                                                                                $max = $max + $minMaxPercent; 
                                                                                if($min < 0){$min = 0;} 
                                                                                
                                                                                if($min < 1000 && $max < 1000){
                                                                                    $xAxisTitle = ""; 
                                                                                }
                                                                                else if($min < 1000000 && $max > 1000000){
                                                                                    $xAxisTitle = "(k = Thousand , M = Million)";
                                                                                }
                                                                                else if($min < 1000000 && $max < 1000000){
                                                                                    $xAxisTitle = "(k = Thousand)";
                                                                                }
                                                                                else{
                                                                                    $xAxisTitle = "(M = Million)";
                                                                                }
                                                                                
											$xmlstore="<chart exportEnabled='1' canvasPadding='23' decimals='$decimal' exportAction='Download' caption='$productsArray1[$x]' subCaption='$graphCaption' yAxisMinValue='$min'  yAxisMaxValue='$max' adjustDiv='0' numDivLines='3' yAxisName='$yAxisTitle $xAxisTitle' numberPrefix='' showValues='$label' formatNumberScale='1' exportHandler='FusionCharts/Code/PHP/ExportHandler/FCExporter.php' exportAtClient='0' >\n";
											$xmlstore .="<categories>\n";
											$fp = fopen($allfiles[$x],'r');
											while($csv_line = fgetcsv($fp,1024)) {
												//for ($i = 0, $j = count($csv_line); $i < $j; $i++) {
													$xmlstore .="\t<category label='".$csv_line[0]."'/>\n";
												//}
											}
											
											$xmlstore .="</categories>\n";
											for ($k=0; $k < sizeof($districtArray); $k++){
												$xmlstore .="<dataset seriesName='".$districtArray[$k]."'>\n";
												$file_handle = fopen($allfiles[$x], "r");
												while(!feof($file_handle)){
													for ($z=0; $z<sizeof($districtArray); $z++){
														$line_of_text = fgetcsv($file_handle);
														if($line_of_text[1]!=""){									
															$xmlstore .= "\t<set value='" .$line_of_text[$k+1]. "' />\n";
														}
													}
												}
												$xmlstore .="</dataset>\n";
											}
											$xmlstore .="</chart>\n";
											//$handle = fopen($xmlfile_path, 'w');
											
											//fwrite($handle, $xmlstore);
											fclose($file_handle);
											//$xmlFile = "xml_cyp/".$productsArray[$x].".xml";?>
											<div id="exportBittons" align="right">
                                                <img src="../../images/excel-16.png" onClick="JavaScript:exportChart('Excel', '<?php echo $productsArray[$x];?>')" style="cursor:pointer;width:20px;height:22px;" />                                                 
                                                <img src="../../images/PDF.png" onClick="JavaScript:exportChart('PDF', '<?php echo $productsArray[$x];?>')" style="cursor:pointer;" />
                                                <img src="../../images/JPG.png" onClick="JavaScript:exportChart('JPG', '<?php echo $productsArray[$x];?>')" style="cursor:pointer;" />
                                                <img src="../../images/PNG.png" onClick="JavaScript:exportChart('PNG', '<?php echo $productsArray[$x];?>')" style="cursor:pointer;" />
                                            </div>
											<?php 
											if ($_GET['ctype'] == 'line'){								
												//echo renderChart("FusionCharts/Charts/MSLine.swf", $xmlFile, "", $productsArray[$x], 530, 250, false);
												echo renderChart("FusionCharts/Charts/MSLine.swf", "", $xmlstore, $productsArray[$x], 700, 395, false, false);
												
											}else if($_GET['ctype'] == 'bar'){
												//echo renderChart("FusionCharts/Charts/MSColumn3D.swf", $xmlFile, "", $productsArray[$x], 530, 250, false);
												echo renderChart("FusionCharts/Charts/MSColumn3D.swf", "", $xmlstore, $productsArray[$x], 700, 395, false, false);
											}
											echo "<br /><br />";										
										}
										
										$counter++;
									}
							
							
							 }?>
                            <?php /* contraceptive product mix graphs */ if($case==6){ //echo "case-6"; 
								$productNameArr = explode(",", $_SESSION['prodtitles']);
								
								
								       $file_handle = fopen($allfiles[0], "r");
									$month = array();
									while(!feof($file_handle)){
										$line_of_text = fgetcsv($file_handle);
										if($line_of_text[0]!=""){
											$month[] = $line_of_text[0];
											//$value = array();
											for($z=0; $z<$count+1; $z++){
												//echo $z."--";
												$value[$z][] = $line_of_text[$z];
											}
										}
										//$code[] = $line_of_text[2];
									}
									
									
								fclose($file_handle);
						?>

                        <?php				
								//XML write function
								//function writeXML($xmlfile, $value, $productNameArr)
								//{
								//$xmlfile_path= ROOT_PATH."/".$xmlfile;
                                                                
                                                                for($x=1; $x<sizeof($value); $x++){
                                                                        $rangeValue = array();
                                                                        $min;$max;$xAxisTitle;
									$arrSize = sizeof($value[$x]);
									for ($y=0; $y<$arrSize; $y++){
                                                                            array_push($rangeValue, $value[$x][$y]);
									}
                                                                    }
                                                                
								$min = min($rangeValue);
                                                                $max = max($rangeValue);
                                                                $minMaxPercent = ($max * 10 / 100);
                                                                $min = $min - $minMaxPercent;
                                                                $max = $max + $minMaxPercent; 
                                                                if($min < 0){$min = 0;}
                                                               
                                                                if($min < 1000 && $max < 1000){
                                                                    $xAxisTitle = ""; 
                                                                }
                                                                else if($min < 1000000 && $max > 1000000){
                                                                    $xAxisTitle = "(k = Thousand , M = Million)";
                                                                }
                                                                else if($min < 1000000 && $max < 1000000){
                                                                    $xAxisTitle = "(k = Thousand)";
                                                                }
                                                                else{
                                                                    $xAxisTitle = "(M = Million)";
                                                                }
                                                                         
								$xmlstore="<chart exportEnabled='1' canvasPadding='23' decimals='$decimal' exportAction='Download' caption='Couple Year Protection' subCaption='$graphCaption' yAxisMinValue='$min'  yAxisMaxValue='$max' yAxisName='$yAxisTitle $xAxisTitle' adjustDiv='0' numDivLines='3' numberPrefix='' showValues='$label' formatNumberScale='1' exportHandler='FusionCharts/Code/PHP/ExportHandler/FCExporter.php' exportAtClient='0'>\n";
								$xmlstore .="<categories>\n";
								//////////////Create XML for all categories
									$arrSize = sizeof($value[0]);
									for ($y=0; $y<$arrSize; $y++){
										$xmlstore .="\t<category label='".$value[0][$y]."'/>\n";
									}
								
								$xmlstore .="</categories>\n";
								////////////////Create XML for all values of the selected products
								for($x=1; $x<sizeof($value); $x++){
									$xmlstore .="<dataset seriesName='".$productNameArr[$x-1]."'>\n";
									$arrSize = sizeof($value[$x]);
									for ($y=0; $y<$arrSize; $y++){
										$xmlstore .="\t<set value='".$value[$x][$y]."' />\n";
									}
									$xmlstore .="</dataset>\n";
								}
								$xmlstore .="</chart>\n";
								//$handle = fopen($xmlfile_path, 'w');
								
								//fwrite($handle, $xmlstore);
								//}
								
								//Put XML file name and mysql table name simultaniously
								//writeXML('cyp.xml', $value, $productNameArr);
								?>
                                <div id="exportBittons" align="right">
                                    <img src="../../images/excel-16.png" onClick="JavaScript:exportChartStacked('Excel')" style="cursor:pointer;width:20px;height:22px;" />     
                                    <img src="../../images/PDF.png" onClick="JavaScript:exportChartStacked('PDF')" style="cursor:pointer;" />
                                    <img src="../../images/JPG.png" onClick="JavaScript:exportChartStacked('JPG')" style="cursor:pointer;" />
                                    <img src="../../images/PNG.png" onClick="JavaScript:exportChartStacked('PNG')" style="cursor:pointer;" />
                                </div>
							<?php 
								//echo renderChart("FusionCharts/Charts/StackedColumn3D.swf", "xml_cyp/cyp.xml", "", "myFirst", 530, 250, false);
								
								 echo renderChart("FusionCharts/Charts/StackedColumn3D.swf", "", $xmlstore, "myFirst", 700, 395, false, false);
								 
							}?>
                            </div></td>
                        </tr>
						<tr><td style="page-break-after:always;">&nbsp;</td></tr>
                        
						<?php
						
                      
                        ?>
					  <?php /*EmptyDir('../../plmis_data/');*/ }?>                      
                      
				
                    </table>
                   
                  </form></td>
			    </tr>
			</table></td>
		</tr>        
	</table>
    
   </div>
   
   <div id="email_div" style="display:none; height:600px; border:1px #d1d1d1 solid; width:100%;">
   <form name="emailfrm" method="post" >
   <input type="hidden" id="chk" value="0">
   <table width="100%" align="center">
   	<tr>
    	<td>&nbsp;</td>
    	<td height="150px;" valign="bottom" align="right" width="25%"><strong>Email:</strong></td>
        <td width="70%" valign="bottom"><input type="text" name="txtemail" id="txtemail" style="width:200px;" value="<?php echo $_POST['txtemail'];?>"></td>
    </tr>
   
     <tr>
        <td  width="39">&nbsp;</td>
       <td valign="bottom" align="right" width="25%"><strong>Subject:</strong></td>
        <td align="left"><input name="txtSubject" id="txtSubject" type="text" class="txtin1" style="width:200px;" value="<?php echo htmlspecialchars($_POST['txtSubject']) ;?>" /></td>
     </tr>
            
     <tr>
               <td  width="39">&nbsp;</td>
            <td valign="middle" align="right" width="25%"><strong>Message:</strong></td>
              <td colspan="2" align="left"><textarea   cols="35" rows="10" name="comment" id="comment" class="txtin1" style="width:90%;"><?php echo htmlspecialchars($_POST['comment']);?></textarea></td>
    </tr>
     <tr>
    <td colspan="2">&nbsp;</td>
    	<td colspan="1" align="left" style="padding-left:0px;"><input type="button" value="Done" onClick="emailpdf();"></td>        
    </tr>
   </table>
   </form>
   </div>
      <div id="export_div" style="display:none; height:600px; border:1px #d1d1d1 solid; width:100%;">
      	 <form name="frmexport">
         <table width="100%" align="center" style="background-color:#f1f1f1">
        <tr>
            <td colspan="3">&nbsp;</td>
            <td height="250px;" valign="bottom" align="right" width="30%"><strong>Choose Month:</strong></td>
            <td width="65%" valign="bottom">
            <select name="monthsel" id="monthsel" style="width:200px;">
            <option value="1">JANUARY</option>
            <option value="2">FEBURARY</option>
            <option value="3">MARCH</option>
            <option value="4">APRIL</option>
            <option value="5">MAY</option>
            <option value="6">JUNE</option>
            <option value="7">JULY</option>
            <option value="8">AUGUST</option>
            <option value="9">SEPTEMBER</option>
            <option value="10">OCTOBER</option>
            <option value="11">NOVEMBER</option>
            <option value="12">DECEMBER</option>
            </select>
            </td>
    	</tr>
         <tr>
            <td colspan="3">&nbsp;</td>
            <td valign="bottom" align="right" width="30%"><strong>Choose Year:</strong></td>
            <td width="65%" valign="bottom">
            <SELECT NAME = "yearsel" id = "yearsel" style = "width:200px;" >
                            <?
                            $EndYear  =1990;
                            $StartYear=(date('Y', $BST[7]));

                            for ($i=$StartYear; $i >= $EndYear; $i--)
                                {
                                ?>
                                   <OPTION VALUE="<?php echo $i;?>" <?php if(strpos($_REQUEST['arryearcomp'],"$i")==true || strpos($_REQUEST['arryearcomp'],"$i")===0){ echo $selected;} ?>><?php echo $i;?></OPTION>
                                <?php }
                            ?>
                        </SELECT>
            </td>
    	</tr>
         <tr>
             <td colspan="4">&nbsp;</td>
             <td colspan="1" align="left" style="padding-left:70px;">
             <input type="button" id="buttondne" value="Done" onClick="exportdata();">
             </td>        
   		 </tr>
         <tr>
             <td colspan="4" height="317px;">&nbsp;</td>
         </tr>
         </table>
         </form>
      </div>
      
      <div id="import_div" style="display:none; height:600px; border:1px #d1d1d1 solid; width:100%;">
      	 <form name="frmimport" enctype="multipart/form-data" action="importdata.php" method="post">
         <table width="100%" align="center" style="background-color:#f1f1f1">
        <tr>
            <td colspan="3">&nbsp;</td>
            <td height="250px;" valign="bottom" align="right" width="30%"><strong>Select CSV File:</strong></td>
            <td width="65%" valign="bottom">
            <input type="file" name="filedata" id="filedata" class="txtin">
            </td>
    	</tr>
        
         <tr>
             <td colspan="4">&nbsp;</td>
             <td colspan="1" align="left" style="padding-left:70px;">
             <input type="submit" name="buttondne" id="buttondne" value="Done" >
             </td>        
   		 </tr>
         <tr>
             <td colspan="4" height="317px;">&nbsp;</td>
         </tr>
         </table>
         </form>
      </div>

   </div>
 
<!--	</BODY>
</HTML>
-->
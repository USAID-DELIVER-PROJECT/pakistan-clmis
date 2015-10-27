<?php
	ob_start();
	include("../../html/adminhtml.inc.php");
// reports settings are used to display header and footer text, execute action page, and set parameter forms  
	$report_id = "SNASUMSTK";
    $report_title = "National Report"; 
    $actionpage = "nationalreportSTK.php";
    $parameters = "TI";
    include("../../plmis_inc/common/plmis_common_constants.php");	//Include Global Function File	
    
	//include("../../plmis_inc/common/CnnDb.php");	//Include Database Connection File
	//include("../../plmis_inc/common/FunctionLib.php");	//Include Global Function File
	//include("../../plmis_inc/common/plmis_common_constants.php");	//Include Global Function File
	
                                                                  
 //'user may have run '
    Login();
    if(isset($_GET['month_sel']) && !isset($_POST['go'])){
//		print_r($_GET);
		$sel_month = $_GET['month_sel'];
		$sel_year = $_GET['year_sel'];
		$sel_item = $_GET['item_sel'];
		$sel_groupid = $_GET['groupid'];
		
	}else if(isset($_POST['go'])){
		
		if(isset($_POST['month_sel']) && !empty($_POST['month_sel']))
			$sel_month = $_POST['month_sel'];
		
		if(isset($_POST['year_sel']) && !empty($_POST['year_sel']))
			$sel_year = $_POST['year_sel'];
	
		if(isset($_POST['prod_sel']) && !empty($_POST['prod_sel']))
			$sel_item = $_POST['prod_sel'];           
 		
	} elseif (isset($_GET['prod_sel']) && !empty($_GET['prod_sel'])) {

       if(isset($_GET['prod_sel']) && !empty($_GET['prod_sel']))
            $sel_item = $_POST['prod_sel'];
			
       if(isset($_POST['month_sel']) && !empty($_POST['month_sel']))
            $sel_month = $_POST['month_sel'];
			
       if(isset($_POST['year_sel']) && !empty($_POST['year_sel']))
            $sel_year = $_POST['year_sel'];        
    }    
     else {
		$sel_month = 1;
		$sel_year = 2010;
        $sel_item = "IT-001";					
	}


$in_type =  'S';
$in_id =  0;
$in_month =  $sel_month;
$in_year =  $sel_year;
$in_item =  $sel_item;
$in_stk = 0 ;
$in_prov = 0;    
					
?>
<html>
<HEAD>
<TITLE>Pakistan Logisticts Management Information System - Private Sector Report</TITLE>
 <link rel="stylesheet" href="../../plmis_css/nn_proj.css" type="text/css">
<!-- <script type="text/javascript" src="../../plmis_js/rhi.js"></script>-->
</HEAD>

<BODY text="#000000" bgColor="#FFFFFF" style="margin-left:10px;margin-top:0px,margin-right:0px;margin-bottom:0px;">
<!--- BEGIN  MAIN CONTENT AREA //--->
<TABLE cellSpacing="0" cellPadding="0" width="100%" border="0">
<TBODY>
  <TR>
    <TD valign="top" align="left" bgColor="#FFFFFF">
<!---->
 <?php
  // include("../../plmis_inc/report/reportheader.php");    //Include report header file
?>

<!--    -->

<table border="0" cellpadding="0" cellspacing="0" width="70%">
<tbody>  

            <tr>
               <td colspan="2" align="left"> 
                       <table cellpadding="0" cellspacing="1" border="0" width="650">
                         <tr height="28">
                            <td width="100px" align="center" class="sb1GreenInfoBoxMiddleText"><strong>Stakeholders</strong></td>
                            <td width="100px" align="center" style="padding-left:5px;" class="sb1GreenInfoBoxMiddleText"><strong>Consumption</strong>
                            <td width="130px" align="center" style="padding-left:5px;" class="sb1GreenInfoBoxMiddleText"><strong>AMC</strong></td>
                            <td width="100px" align="center" style="padding-left:5px;" class="sb1GreenInfoBoxMiddleText"><strong>On Hand</strong></td>
                            <td width="100px" align="center" colspan="2" class="sb1GreenInfoBoxMiddleText"><strong>MOS</strong></td>
                            <td width="100px" align="center" colspan="2" class="sb1GreenInfoBoxMiddleText"><strong>CYP</strong></td>                            
                        </tr>
                        <?php

						$queryvals = "SELECT  
								stakeholder.stkid,
								stakeholder.stkname
							FROM 
							  stakeholder
							WHERE ParentID IS NULL AND stakeholder.stk_type_id=1";
    

    
                        $rsvals = mysql_query($queryvals) or die(mysql_error());                                                                    
                            //while($rsSTK = mysql_fetch_array($querySTK)){
                            while($rowvals = mysql_fetch_array($rsvals)){
                        ?>
                        <tr height="22">
                          <td bgcolor="#FFFFFF" style="padding-left:5px;" class="sb1NormalFontArial">
                           <?php     
                             echo $rowvals['stkname'];                            
                            ?>
                          </td>
                          <?php
                                $queryvals2 =  "SELECT REPgetData('CABMY','S','X','$sel_month','$sel_year','".$sel_item."',".$rowvals['stkid'].",0,0) AS Value FROM DUAL"; 
                                $rsvals2 = mysql_query($queryvals2) or die(mysql_error());                
                                 $rowvals2 = mysql_fetch_array($rsvals2);   
                            
                                 $tmp = explode('*',$rowvals2['Value']);    
//<!-- begin data rending -->
                                 $sel_item = $sel_item;
                                 $sel_stk = $rowvals['stkid'];
                                 $sel_lvl = 1;
include("incl_data_render_private.php");                                    
?>
<!--End of data rending -->
                                   
                            <?php
                                //} 
                            }
                            ?>
                        </tr>                      
                    </table>
               </td>
            </tr>               
         </tbody>
      </table>
     </TD>
  </TR>
  </TBODY>
</TABLE>
</body>
</html>

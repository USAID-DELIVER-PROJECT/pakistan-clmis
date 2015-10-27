<?php
//session_start();

/***********************************************************************************************************
Developed by  Munir Ahmed
Email Id:    mnuniryousafzai@gmail.com
This is the file used to add/edit/delete the records from mosscale_tab. It has two forms one for adding the records and other
for editing the record.
we are taking 4 cases. one case to show add form, second case to show edit form, third case to save posted 
data entered through add form and fourth save the data enterd from the edit form
/***********************************************************************************************************/
include("../html/adminhtml.inc.php");
//Login();
/*
    if (!ini_get('register_globals')) {
      $superglobals = array( $_GET,  $_POST, $_COOKIE, $_SERVER );
      if (isset ($_SESSION)) {
        array_unshift($superglobals, $_SESSION);
      }
      foreach ($superglobals as $superglobal) {
        extract($superglobal, EXTR_SKIP);
      }
      ini_set('register_globals', true);
    }*/
?>
<?php 
	//startHtml("Add Edit MOS Scale");
	?><?php //siteMenu();?>
	<div class="wrraper">
	<div class="content" style="margin-left:-38px;">  
    	<script type="text/javascript" src="../../plmis_js/jscolor.js"></script>  
        <SCRIPT LANGUAGE="JAVASCRIPT" TYPE="TEXT/JAVASCRIPT" SRC="../../plmis_js/mosscale.js"></SCRIPT>
        <SCRIPT LANGUAGE="JAVASCRIPT" TYPE="TEXT/JAVASCRIPT">
            
                var width = 920, height = 170;
                window.onerror = ScriptError;                
                function ScriptError()
                    {
  /*                      window.parent.location="../Error.php";
                        return true;
 */                   }
               
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
                function Logout()
                    {
                          window.location="<?php echo SITE_URL; ?>Logout.php";
                    }

           //-->
			
        </SCRIPT>
        <?php        
            //include("../../plmis_inc/common/CnnDb.php");    //Include Database Connection File
            //include("../../plmis_inc/common/FunctionLib.php");    //Include Global Function File                
            //include('../../plmis_inc/common/DateTime.php');    //Include Date Function File
                     
            $BST=BST_DtTm();
			
			$objDB= new Database();
			$objDB->connect();
			
			$objDB2= new Database();
			$objDB2->connect();
			
			$objDB3= new Database();
			$objDB3->connect();
			
			$objDB4= new Database();
			$objDB4->connect();
			
			$objDB5= new Database();
			$objDB5->connect();
			
            $objDB6= new Database();
            $objDB6->connect();

			$stk_array =array();
						$sql = "select * from stakeholder";
						if($objDB2->executeScalar($sql) && $objDB2->get_num_rows() > 0)
						{
							for($i=0; $i< $objDB2->get_num_rows(); $i++)
							{
								$row2=$objDB2->fetch_row_assoc();
								array_push($stk_array,$row2);
							}
						}
// distribution levels 
                        $lvl_array =array();
                        $sql = "select * from tbl_dist_levels";
                        if($objDB6->executeScalar($sql) && $objDB6->get_num_rows() > 0)
                        {
                            for($i=0; $i< $objDB6->get_num_rows(); $i++)
                            {
                                $row6=$objDB6->fetch_row_assoc();
                                array_push($lvl_array,$row6);
                            }
                        }
       ?>
	<?php /*?>If user selects to delete the content the following portion of the code will be executed. <?php */?>
	<?php
        if($_REQUEST['ActionType']=="DeleteData")
            {
				
				  $PrvRecordID=$_GET['mosid'];
              // echo "delete from sysuser_tab where sysusrrec_id='$PrvRecordID'"; exit;
			    safe_query("delete from mosscale_tab where row_id='$PrvRecordID'");
				echo"<SCRIPT>document.location='view_admin_mos.php?msg=00';</Script>";
            }
        else if($_POST['ActionType']=="EditSave")
            {
				/*	$whrec_id 		= $_POST['whrec_id'];
					$whname		 	= $_POST['whname'];
					$province		= $_POST['provinces'];					
					$whadd			= $_POST['whadd'];
					$contactper		= $_POST['contactper'];
					$contactemail	= $_POST['contactemail'];
					$whrec_id_curr	= $_POST['whrec_id_curr'];*/
					
					$itmrec_id 		= $_POST['itmrec_id'];
					$shortterm	 	= $_POST['shortterm'];
					$longterm		= $_POST['longterm'];					
					$sclstart		= $_POST['sclstart'];
					$sclsend		= $_POST['sclsend'];					
					$colorcode		= $_POST['colorcode'];
					$stkid			= $_POST['stkid'];
                    $lvl_id         = $_POST['lvl_id'];
					
					$sql = "update  mosscale_tab set itmrec_id='".$itmrec_id."',
										shortterm='".$shortterm."',
										longterm='".$longterm."',
										sclstart='".$sclstart."',
										sclsend='".$sclsend."',
										stkid='".$stkid."',											
                                        lvl_id='".$lvl_id."',											
										colorcode='#".$colorcode."' where row_id='".$row_id."'"; 
					if($objDB->execute($sql))
					{
						
					}
					echo"<SCRIPT>document.location='view_admin_mos.php?msg=02';</Script>";
				
		
		        
               /* echo"<SCRIPT>document.location='AddEditMosScale.php';</Script>";*/
            }
        else if($_POST['ActionType']=="Add" && !empty($_POST['shortterm']))
            {
				
				$PrvRecordID = $_REQUEST['PrvRecordID'];
					$itmrec_id 		= $_POST['itmrec_id'];
					$shortterm	 	= $_POST['shortterm'];
					$longterm		= $_POST['longterm'];					
					$sclstart		= $_POST['sclstart'];
					$sclsend		= $_POST['sclsend'];					
					$colorcode		= $_POST['colorcode'];
					$stkid			= $_POST['stkid'];
                    $lvl_id          = $_POST['lvl_id'];
					
				if(!empty($itmrec_id) && empty($PrvRecordID))
				{
					$sql = "insert into mosscale_tab set itmrec_id='".$itmrec_id."',
										shortterm='".$shortterm."',
										longterm='".$longterm."',
										sclstart='".$sclstart."',
										sclsend='".$sclsend."',	
										stkid='".$stkid."',										
										colorcode='#".$colorcode."',
                                        lvl_id='".$lvl_id."'"; 
					$objDB->execute($sql);
				}
				echo"<SCRIPT>document.location='view_admin_mos.php?msg=01';</Script>";
			}
            ?>

<?php
    if($_REQUEST['ActionType']=="EditShow")
        {
?>
 <?php
                        $PrvRecordID=$_GET['mosid'];
						$strSQL="select * from mosscale_tab where row_id='$PrvRecordID'"; 
                      					
						if($objDB->query($strSQL) && $objDB->get_num_rows() >0)
						{
							$rowRec = $objDB->fetch_one_assoc();
							$row_id 		= $rowRec['row_id'];
							$itmrec_id 		= $rowRec['itmrec_id'];
							$shortterm		= $rowRec['shortterm'];
							$longterm	 	= $rowRec['longterm'];
							$sclstart		= $rowRec['sclstart'];					
							$sclsend		= $rowRec['sclsend'];
							$extra			= $rowRec['extra'];
							$colorcode		= $rowRec['colorcode'];
							$stkid			= $rowRec['stkid'];
                            $lvl_id         = $rowRec['lvl_id'];
							
						}
						
						$prov_array=array();
						$sql="select * from itminfo_tab ";
						if($objDB3->query($sql) && $objDB3->get_num_rows() >0)
						{
							for($i=0;$i<  $objDB3->get_num_rows(); $i++)
							{
								$row1=$objDB3->fetch_row_assoc();
								array_push($prov_array,$row1);
							}
						}
						
	?>
   
   
   <?php /*?>If user selects to edit the content the following portion of the code will be executed. <?php */?>
    <FORM NAME="frmData" ACTION="AddEditMosScale.php" METHOD="POST" ENCTYPE="MULTIPART/FORM-DATA" ONSUBMIT="return ValidateEditMOS();">
    
	  <div id="EditForm">           
           <table width="100%" border="0" style="padding-left: 7%;"> 
                <TR>
                  <td colspan="7" height="40px" align="left" valign="middle"><?php showBreadCrumb();?></TD>
                </TR>
                <TR>
                    <td colspan="7" align="left" valign=
          "middle"  title="Edit already existing Month of scale of a product"><p><span ><b class="new_Heading">Edit MOS Scale</b></span><span ><br />
                            <b style="font-size:15px">Fields marked with an
      asterisk </b>(</span><font color="red">*</font>)<b style="font-size:15px" >
      are required.</b></td>
              </TR>
                <TR>
                  <TD align="left" valign="top" >&nbsp;</TD>
                  <TD width="80" height="27" align="left" valign="top" NOWRAP CLASS="sb1GeenGradientBoxMiddle">&nbsp;</TD>
                  <TD colspan="3" align="left" valign="top" CLASS="TDRCOLLAN">&nbsp;</TD>
                </TR>
              <TR>
                <TD class="new_Label" NOWRAP title="Select new product for the MOS">Product:<a class="sb1Exception">*</a></TD>
                <TD align="left" valign="top">
             
                  <select title="Select new product for the MOS" name="itmrec_id" tabindex="6" id="itmrec_id" class="new_Input">
                    <option value="" selected="selected">Select Product</option>
                    <?php for($i=0; $i<$objDB3->get_num_rows(); $i++){?>
                    <option value="<?php echo $prov_array[$i]['itmrec_id'];?>" <?php if($prov_array[$i]['itmrec_id'] == $itmrec_id){ echo "selected";} ?> ><?php echo $prov_array[$i]['itm_name']; ?></option>
                    <?php }?>
                </SELECT></TD>
    		    <TD width="146" class="new_Label" title="Edit the scale end details of the MOS">Scale End:<a class="sb1Exception">*</a></TD>
    		    <TD width="204" align="left" valign="top"><input title="Edit the scale end details of the MOS" type="TEXT" name="sclsend" id="sclsend" class="new_Input" value="<?php echo $sclsend; ?>" /></TD>
              </TR>          
            
              <TR>
                <TD align="left" valign="top" NOWRAP CLASS="sb1GeenGradientBoxMiddle">&nbsp;</TD>
                <TD align="left" valign="top" NOWRAP CLASS="sb1GeenGradientBoxMiddle">&nbsp;</TD>
                <TD colspan="3" align="left" valign="top" CLASS="TDRCOLLAN" >&nbsp;</TD>
              </TR>
              <TR>
              <TD class="new_Label" NOWRAP title="Edit the short term details of the MOS">Short Term:<a class="sb1Exception">*</a></TD>
              <TD align="left" valign="top" >
              <INPUT  title="Edit the short term details of the MOS" TYPE="TEXT" NAME="shortterm" id="shortterm" class="new_Input" value="<?php echo $shortterm; ?>"></TD>
              <TD class="new_Label"  title="Edit the color code of the MOS">Color Code:<a class="sb1Exception">*</a></TD>
              <TD align="left" valign="top" CLASS="TDRCOLLAN" ><input title="Edit the color code of the MOS" class="color" type="TEXT" name="colorcode" id="colorcode" style="width:196px;border: 1px solid #CDCFCF;border-radius: 3px 3px 3px 3px;box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.1) inset;font-size:14px;font-family:Arial, Helvetica, sans-serif;height:35px;" value="<?php echo $colorcode; ?>" /></TD>
              </TR>
              <TR>
                <TD align="left" valign="top" NOWRAP CLASS="sb1GeenGradientBoxMiddle">&nbsp;</TD>
                <TD align="left" valign="top" NOWRAP CLASS="sb1GeenGradientBoxMiddle">&nbsp;</TD>
                <TD COLSPAN="3" align="left" valign="top" CLASS="TDRCOLLAN">&nbsp;</TD>
              </TR>
              <TR>
                <TD class="new_Label" NOWRAP title="Edit the long term details of the MOS">Long Term:<a class="sb1Exception">*</a></TD>
              <TD align="left" valign="top">
              	<INPUT title="Edit the long term details of the MOS" TYPE="TEXT" NAME="longterm" id="longterm" class="new_Input" value="<?php echo $longterm; ?>">
              </TD>
              <TD class="new_Label" title="Enter new stakeholder who will access to view or edit the MOS">Stakeholder :</TD>
              <TD align="left" valign="top"><select title="Enter new stakeholder who will access to view or edit the MOS"  name="stkid" id="stkid" class="new_Input">
                <option value="" selected="selected">Select Stakeholder</option>
                <?php //for($i=0; $i<$objDB2->get_num_rows(); $i++){
				  	$query = "SELECT * FROM `stakeholder`";
					$rs = mysql_query($query) or die(mysql_error());
					while($row1 = mysql_fetch_array($rs)){
			  ?>
                <option value="<?php echo $row1['stkid'];?>" <?php if($row1['stkid']==$stkid){ echo "selected='selected'";}?>><?php echo $row1['stkname'];?></option>
                <?php }?>
              </select>
                <span class="TDRCOLLAN">
                <!--<select name="stkid" style="width:196px;">
               <option value="">Select Stakeholder</option>
			  <?php //for($i=0; $i<$objDB2->get_num_rows(); $i++){?>
                <option value="<?php /*echo $stk_array[$i]['stkid'] ?>" <?php if($stk_array[$i]['stkid']==$stkid){ echo "selected='selected'";}?>><?php echo $stk_array[$i]['stkname'];*/?></option>
              <?php //}?>
			  
              </select>-->
              </TD>

</TR>       
<tr>
<TD align="left" valign="top" nowrap class="sb1GeenGradientBoxMiddle">&nbsp;</option>
                </span></TD>
              </TR>
              <TR>
                <TD align="left" valign="top" NOWRAP CLASS="sb1GeenGradientBoxMiddle">&nbsp;</TD>
                <TD align="left" valign="top" NOWRAP CLASS="sb1GeenGradientBoxMiddle">&nbsp;</TD>
                <TD COLSPAN="3" align="left" valign="top" CLASS="TDRCOLLAN">&nbsp;</TD>
              </TR>
              <TR>
                <TD class="new_Label" NOWRAP title="Edit the scale start details of the MOS">Scale Start:<a class="sb1Exception">*</a></TD>
              <TD align="left" valign="top">
              <INPUT title="Edit the scale start details of the MOS" TYPE="TEXT" NAME="sclstart" id="sclstart" class="new_Input" value="<?php echo $sclstart; ?>">              </TD>
              <TD class="new_Label" style="width:137px;" title="Edit the distribution level of MOS">Distribution Level:<a class="sb1Exception"></a></TD>
              <TD align="left" valign="top"><select title="Edit the distribution level of MOS" name="lvl_id" class="new_Input">
                <?php //for($i=0; $i<$objDB6->get_num_rows(); $i++){?>
                <option value="">Select Distribution Level</option>
                <?php 
			  		$query = "SELECT * FROM `tbl_dist_levels`";
					$rs = mysql_query($query) or die(mysql_error());
					while($row = mysql_fetch_array($rs)){
			  ?>
                <option value="<?php echo $row['lvl_id'] ?>" <?php if($row['lvl_id']==$lvl_id){ echo "selected='selected'";}?>><?php echo $row['lvl_name'];?></option>
                <?php }?>
              </select></TD>
              </TR>
<TR>
              <TD align="left" valign="top" NOWRAP CLASS="sb1GeenGradientBoxMiddle">&nbsp;</TD>
                <TD align="left" valign="top" NOWRAP CLASS="sb1GeenGradientBoxMiddle">&nbsp;</TD>
              <TD COLSPAN="3" align="left" valign="top" CLASS="TDRCOLLAN">&nbsp;</TD>
</TR>

     <TR>
       <TD align="left" valign="top" NOWRAP CLASS="sb1GeenGradientBoxMiddle">&nbsp;</TD>
       <td align="left" valign="top" nowrap="nowrap" class="sb1GeenGradientBoxMiddle">&nbsp;</td>
       <td colspan="3" align="left" valign="top" class="TDRCOLLAN">&nbsp;</td>
     </TR>
     <TR>
              <TD align="left" valign="top" NOWRAP CLASS="sb1GeenGradientBoxMiddle">&nbsp;</TD>
                <TD align="left" valign="top" NOWRAP CLASS="sb1GeenGradientBoxMiddle">&nbsp;</TD>
              <TD COLSPAN="3" align="left" valign="top" CLASS="TDRCOLLAN">&nbsp;</TD>
</TR>    
<TR>
              <TD align="left" valign="top" NOWRAP CLASS="sb1GeenGradientBoxMiddle">&nbsp;</TD>
                <TD align="left" valign="top" NOWRAP CLASS="sb1GeenGradientBoxMiddle">&nbsp;</TD>
                <TD COLSPAN="3" align="left" valign="top" CLASS="TDRCOLLAN"><option value="<?php /*echo $stk_array[$i]['stkid'] ?>" <?php if($stk_array[$i]['stkid']==$stkid){ echo "selected='selected'";}?>><?php echo $stk_array[$i]['stkname'];*/?></option>
              <?php //}?>
			  
              </select>-->
              </TD>

</TR>       
<tr>
<TD align="left" valign="top" NOWRAP CLASS="sb1GeenGradientBoxMiddle"></TD>
            </TR>        
           <input type="hidden" value="<?php echo $row_id; ?>" name="row_id" />
            
            <TR>
              <TD COLSPAN="5" ALIGN="CENTER" CLASS="TableHead" title="Save to database"><INPUT TYPE="IMAGE" SRC="../../plmis_img/CmdSave.gif" TABINDEX="16">&nbsp;&nbsp;<IMG SRC="../../plmis_img/CmdReset.gif" WIDTH="83" HEIGHT="21" BORDER="0" ALT="Reset" ONCLICK="document.frmData.reset()" CLASS="Himg" TABINDEX="17" title=" Reset all values" style="cursor:pointer"; ><INPUT TYPE="HIDDEN" NAME="ActionType" VALUE="EditSave"><INPUT TYPE="HIDDEN" NAME="PrvRecordID" VALUE="<? echo $wid;?>">
                <a href="view_admin_mos.php"><img src="../../plmis_img/cancel.gif" width="83" height="21" border="0" alt="Reset" onclick="document.frmData.reset()" class="Himg" tabindex="18" style="cursor:pointer" title="Reset all values" /></a></TD>
            </TR>            
        </TABLE>
	  </div>

           <INPUT TYPE="HIDDEN" NAME="LogedUser" VALUE="<?echo $LogedUser;?>">
            <INPUT TYPE="HIDDEN" NAME="LogedID" VALUE="<?echo $LogedID;?>">
            <INPUT TYPE="HIDDEN" NAME="LogedUserWH" VALUE="<?echo $LogedUserWH;?>">
            <INPUT TYPE="HIDDEN" NAME="LogedUserType" VALUE="<?echo $LogedUserType;?>">        
            
        </FORM>
    <?php
        }
    else
        {
    ?>
    
	
	
	<?php /*?>If user selects to add the content the following portion of the code will be executed. <?php */?>
	<FORM NAME="frmData" ACTION="AddEditMosScale.php" METHOD="POST" ENCTYPE="MULTIPART/FORM-DATA" ONSUBMIT="return ValidateAddMOS()">
    
    

<div id="AddForm">
       
<table width="100%" border="0" style="padding-left: 6%;">            
            <TR>
              <td colspan="9" height="40px" align="left" valign="middle"><?php showBreadCrumb1();?></TD>
            </TR>
            <TR>
                <td colspan="7" align="left" valign=
          "middle" title="Add new month of scale for a product"><p><span ><b class="new_Heading" style="padding-left:1%">Add MOS Scale</b></span><span ><br />
                            <b style="font-size:15px; padding-left:1%">Fields marked with an
      asterisk </b>(</span><font color="red">*</font>)<b style="font-size:15px" >
      are required.</b>
                  <span class="required">
                <?
                    if($msg=="NotSave")
                        {
                          //  echo"<DIV ALIGN='CENTER'><B><A CLASS='Forgot'>The User Can't Be Added. The Given Login ID Is Already Taken. Please Try Again. </A></B></DIV>";
                        }
                ?></span></TD>
            </TR>  
            <TR>
              <TD align="left" valign="top" >&nbsp;</TD>
              <TD width="82" height="27" align="left" valign="top">&nbsp;</TD>
              <TD colspan="3" align="left" valign="top" >&nbsp;</TD>
            </TR>
            <TR>
              <TD WIDTH="1" align="left" valign="top">&nbsp;</TD>
                <TD class="new_Label" NOWRAP title="Select the product for adding new month of scale">Product:<a class="sb1Exception">*</a></TD>
                <TD align="left" valign="top">
             
                  <select class="new_Input"  title="Select the product for adding new month of scale" name="itmrec_id" tabindex="6" id="itmrec_id" style="width:200px;">
                    <option value="" selected="selected">Select Product</option>
                    <?
                        $strSQL="select itmrec_id,itm_name from itminfo_tab order by itmrec_id";
                        $rsTemp1=safe_query($strSQL);
                        while($rsRow1=mysql_fetch_array($rsTemp1))
                            
                            {            
                                echo "<OPTION VALUE='$rsRow1[itmrec_id]'>$rsRow1[itm_name]</OPTION>";
                            }
                        mysql_free_result($rsTemp1);
                    ?>
                  </SELECT></TD>
    		    <TD width="143" class="new_Label" title="Enter scale end detail">Scale End:<a class="sb1Exception">*</a></TD>
   		      <TD width="207" align="left" valign="top"><input class="new_Input"   title="Add Scale End Detail"type="TEXT" name="sclsend" id="sclsend" style="width:196px;"></TD>
            </TR>          
            
            <TR>
              <TD align="left" valign="top" NOWRAP CLASS="sb1GeenGradientBoxMiddle">&nbsp;</TD>
              <TD align="left" valign="top" NOWRAP CLASS="sb1GeenGradientBoxMiddle">&nbsp;</TD>
              <TD colspan="3" align="left" valign="top" CLASS="TDRCOLLAN" >&nbsp;</TD>
            </TR>
            <TR>
              <TD align="left" valign="top" NOWRAP CLASS="sb1GeenGradientBoxMiddle">&nbsp;</TD>
              <TD class="new_Label" NOWRAP title="Enter the short term detail of the product">Short Term:<a class="sb1Exception">*</a></TD>
              <TD align="left" valign="top" >
              <INPUT class="new_Input"  title="Enter the short term detail of the product" TYPE="TEXT" NAME="shortterm" id="shortterm" style="width:196px;"></TD>
              <TD class="new_Label" style="width:125px;" title="Choose the color for representing the MOS of a product" >Color Code:<a class="sb1Exception">*</a></span></TD>
              <TD align="left" valign="top" CLASS="TDRCOLLAN" ><input title="Choose the color for representing the MOS of a product" class="color" type="TEXT" name="colorcode" id="colorcode" style="width:196px; border:1px solid #CDCFCF; border-radius: 3px 3px 3px 3px;  box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.1) inset; font-size:14px;font-family:Arial, Helvetica, sans-serif;height:35px; "></TD>
      </TR>
            <TR>
              <TD align="left" valign="top" NOWRAP CLASS="sb1GeenGradientBoxMiddle">&nbsp;</TD>
              <TD align="left" valign="top" NOWRAP CLASS="sb1GeenGradientBoxMiddle">&nbsp;</TD>
              <TD COLSPAN="3" align="left" valign="top" CLASS="TDRCOLLAN">&nbsp;</TD>
            </TR>
       <TR>
              <TD align="left" valign="top" NOWRAP CLASS="sb1GeenGradientBoxMiddle">&nbsp;</TD>
                <TD class="new_Label" NOWRAP title="Enter the long term detail of the product">Long Term:<a class="sb1Exception">*</a></TD>
              <TD align="left" valign="top">
              <INPUT class="new_Input"   title="Enter the long term detail of the product" TYPE="TEXT" NAME="longterm" id="longterm" style="width:196px;">              </TD>
              <TD class="new_Label" title="Select the stakeholder who can view or edit the MOS">Stakeholder:</TD>
              <TD align="left" valign="top"><select class="new_Input"   title="Select the stakeholder who can view or edit the MOS" name="stkid" id="stkid" style="width:196px;">
                <option value="" selected="selected">Select Stakeholder</option>
                <?php //for($i=0; $i<$objDB2->get_num_rows(); $i++){
				  	$query = "SELECT * FROM `stakeholder`";
					$rs = mysql_query($query) or die(mysql_error());
					while($row1 = mysql_fetch_array($rs)){
			  ?>
                <option value="<?php echo $row1['stkid'];?>"><?php echo $row1['stkname'];?></option>
                <?php }?>
            </select></TD>
      </TR>
       <TR>
         <TD align="left" valign="top" NOWRAP CLASS="sb1GeenGradientBoxMiddle">&nbsp;</TD>
         <TD align="left" valign="top" NOWRAP CLASS="sb1GeenGradientBoxMiddle">&nbsp;</TD>
         <TD COLSPAN="3" align="left" valign="top" CLASS="TDRCOLLAN">&nbsp;</TD>
       </TR>
      <TR>
              <TD align="left" valign="top" NOWRAP CLASS="sb1GeenGradientBoxMiddle">&nbsp;</TD>
                <TD class="new_Label" NOWRAP  title="Enter scale start detail">Scale Start:<span class="sb1GeenGradientBoxMiddle"><a class="sb1Exception">*</a></span></TD>
              <TD align="left" valign="top">
              <INPUT class="new_Input"  title="Add Scale Start Detail" TYPE="TEXT" NAME="sclstart" id="sclstart" style="width:196px;">              </TD>
              <TD class="new_Label" style="width:190px;" title="Select the level of distribution e.g National, Provincial etc">Distribution Level:<a class="sb1Exception"></a></TD>
              <TD align="left" valign="top"><select class="new_Input"   title="Select the level of distribution e.g National, Provincial etc"  name="lvl_id" id="lvl_id" style="width:196px;">
                <?php //for($i=0; $i<$objDB6->get_num_rows(); $i++){?>
                <option value="" selected="selected">Select Distribution Level</option>
                <?php 
			  		$query = "SELECT * FROM `tbl_dist_levels`";
					$rs = mysql_query($query) or die(mysql_error());
					while($row = mysql_fetch_array($rs)){
			  ?>
                <option value="<?php echo $row['lvl_id'];?>"><?php echo $row['lvl_name'];?></option>
                <?php }?>
            </select></TD>
      </TR>
      <TR>
        <TD align="left" valign="top" NOWRAP CLASS="sb1GeenGradientBoxMiddle">&nbsp;</TD>
        <TD align="left" valign="top" NOWRAP CLASS="sb1GeenGradientBoxMiddle">&nbsp;</TD>
        <TD COLSPAN="3" align="left" valign="top" CLASS="TDRCOLLAN">&nbsp;</TD>
      </TR>
      

        
      
           
      <tr>
        <TD align="left" valign="top" NOWRAP CLASS="sb1GeenGradientBoxMiddle">&nbsp;</TD>
        <TD align="left" valign="top" NOWRAP CLASS="sb1GeenGradientBoxMiddle">&nbsp;</TD>
        <TD COLSPAN="3" align="left" valign="top" CLASS="TDRCOLLAN">&nbsp;</TD>
      </TR>
           
            <TR>
              <TD COLSPAN="7" ALIGN="CENTER" CLASS="TableHead" title="Save to database"><INPUT TYPE="IMAGE" SRC="../../plmis_img/CmdSave.gif" TABINDEX="16">&nbsp;&nbsp;<IMG SRC="../plmis_img/CmdReset.gif" WIDTH="83" HEIGHT="21" BORDER="0" ALT="Reset" ONCLICK="document.frmData.reset()" CLASS="Himg" TABINDEX="17" title="Reset all values" style="cursor:pointer" ><INPUT TYPE="HIDDEN" NAME="ActionType" VALUE="Add"><INPUT TYPE="HIDDEN" NAME="PrvRecordID" VALUE="">
                <a href="view_admin_mos.php"><img src="../plmis_img/cancel.gif" width="83" height="21" border="0" alt="Reset" onclick="document.frmData.reset()" class="Himg" tabindex="18" style="cursor:pointer" title="Reset all values" /></a></TD>
            </TR>            
</TABLE>
      </div>
    <?php }?>
            <INPUT TYPE="HIDDEN" NAME="LogedUser" VALUE="<?echo $LogedUser;?>">
            <INPUT TYPE="HIDDEN" NAME="LogedID" VALUE="<?echo $LogedID;?>">
            <INPUT TYPE="HIDDEN" NAME="LogedUserWH" VALUE="<?echo $LogedUserWH;?>">
            <INPUT TYPE="HIDDEN" NAME="LogedUserType" VALUE="<?echo $LogedUserType;?>">        
            
        </FORM>    
<!--        <CENTER><DIV id=GRID style='border:1px solid #eeeeee; overflow:hidden;width:950;height:150;'></DIV></CENTER>
-->
   </div>
</div>
<?php footer();    ?>
<?php endHtml();?>
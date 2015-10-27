<?php
session_start();
include("../../html/adminhtml.inc.php");
Login();  

	startHtml($system_title." ");
    siteMenu();?>
<?php include "../../plmis_inc/common/top.php";?>
    <?php //include "../../plmis_inc/common/left.php";?>
	<div class="body_sec">
		<div class="wrraper">
		<div class="content"><br />            	
        
		<span style="padding-left:35px"><?php showBreadCrumb();?></span><div style="float:right; padding-left:40px"><?php echo readMeLinks($readMeTitle);?></div><br /><br />
    <?php 
 

	$objDB21 = new Database();
	$objDB21->connect();
	
	$objDB22 = new Database();
	$objDB22->connect();
	
	////////// Gte warehouse of logged in User.
	
	/*$loggedUserID =  base64_decode($_SESSION['user']['LogedUser']);
	$qryRes = mysql_fetch_array(mysql_query("SELECT whrec_id FROM sysuser_tab WHERE sysusrrec_id = '".$loggedUserID."' "));
	$wareHouseID = $qryRes['whrec_id'];*/
	
	$getWHs = mysql_query("SELECT wh_user.wh_id
						FROM wh_user
						WHERE wh_user.sysusrrec_id='".$_SESSION['userid']."'");
	while($row = mysql_fetch_array($getWHs))
	{
		$allWHs[] = $row['wh_id'];
	}
	
	///////////////////////////////////////////
	
		$pins="";
		if($_FILES['filedata']['name'] != "")
		{ 
			$tempFile = $_FILES['filedata']['tmp_name'];
			
			$ext = getExtension($_FILES['filedata']['name']);
			if(($ext!="csv"))
			{
				$error .= "&nbsp;&nbsp;Only CSVs can be uploaded. Please choose the file with right format"; 
						
			} 
		
			 
			
			//********************************************* Checking the valid csv file, must have 24 columns ***********************************
			////////////////////////////////////////// included on 21-10-2010 by Aun ////////////////////////////////////////////////////////////
			
				$items1 = array();
				$dataArr = array();
				$file_handle = fopen($tempFile, "rb");
				$count = 1;
				while (!feof($file_handle)  ) {
				
					$line_of_text = fgets($file_handle);
					$parts = explode('\n', $line_of_text);
					
					$items1 = explode(',', $parts[0]);
					
					if ( sizeof($items1) == 24 ){
						$total_records = count($items1);
						foreach($items1 as $val)
						{
							$dataArr[$count][] = $val;
						}
						$count++;
					}
					
					
				}
				
				
				/*echo "<pre>";
				print_r($dataArr);
				exit;*/
				
				if($total_records < 0)
				{
				 $error = "&nbsp;&nbsp;Some records have invalid data, so file cannot be uploaded<br>You may also check the last record it maybe null";
				}
				
			 //***********************************************************************************************************************************
			
			
		}
		else
		{
		$error .= "<br />&nbsp;&nbsp;CSV file is required. Please choose the file with right format";
		}
						
		
//////////////////////////////////////////////////
			if(empty($error)){
		
				$items = array();
				
				////////////// Get warehouse ID from export file. 
				
				$file_handle = fopen($tempFile, "rb");		
				while (!feof($file_handle) ) {		
					$line_of_text = fgets($file_handle);
					$parts = explode('\n', $line_of_text);			
					$items= explode(',', $parts[0]);
					$expWhIdArr[] =  intval($items[3]);
				}
				$expWhId = $expWhIdArr['0'];
				fclose($file_handle);
		
				
				if (in_array($expWhId, $allWHs)){
					$file_handle = fopen($tempFile, "rb");		
					while (!feof($file_handle) ) {		
						$line_of_text = fgets($file_handle);
						$parts = explode('\n', $line_of_text);					
						
						$items= explode(',', $parts[0]);
						
						//print_r($items); 
						$total=0; $total_serial=0;
						
						$sql_cnt ="Select count(*) from tbl_wh_data where report_month=".intval($items[0])." and report_year=".intval($items[1])." and item_id='".trim($items[2])."' and wh_id=".intval($items[3]); 
						$total   = $objDB21->executeScalar($sql_cnt);
						$expWhId =  intval($items[3]);
						
						if($total==0 && (intval($items[0]) !=0 || intval($items[3])!=0)){
									
									
									
									
									
										
							/*			1.	report_month
										2.	report_year
										3.	item_id
										4.	wh_id
										5.	wh_obl_a
										6.	wh_obl_c
										7.	wh_received
										8.	wh_issue_up
										9.	wh_cbl_c
										10.	mos
										11.	wh_cbl_a
										12.	wh_adja
										13.	wh_adjb
										14.	fld_obl_a
										15.	fld_obl_c
										16.	fld_recieved
										17.	fld_issue_up
										18.	fld_cbl_c
										19.	fld_cbl_a
										20.	fld_mos
										21.	fld_adja
										22.	fld_adjb
										23.	wh_entry
										24.	fld_entry
*/			
							
							//$getFldId['wh_id'];
							$sql = "insert into tbl_wh_data set
							report_month ='".$items[0]."',
							report_year  ='".$items[1]."',
							item_id		 ='".$items[2]."',
							wh_id		 ='".$items[3]."',
							wh_obl_a	 ='".$items[4]."',
							wh_obl_c	 ='".$items[5]."',				
							wh_received  ='".$items[6]."',
							wh_issue_up  ='".$items[7]."',
							wh_cbl_c	 ='".$items[8]."',
							wh_cbl_a     ='".$items[10]."',
							wh_adja		 ='".$items[11]."',
							wh_adjb		 ='".$items[12]."',
							RptDate = '".$items[1]."-".$items[0]."-01' ";
							
							
							
						
							if($objDB22->execute($sql))
							{
								
							}
							
							$ssQL="SELECT tbl_warehouse.wh_id  FROM tbl_warehouse
											Inner Join stakeholder ON  tbl_warehouse.stkofficeid = stakeholder.stkid
										WHERE
											stakeholder.lvl = 4 AND tbl_warehouse.dist_id
										IN (SELECT tbl_warehouse.dist_id  FROM tbl_warehouse WHERE
											tbl_warehouse.wh_id = '".$items[3]."') 
										and tbl_warehouse.stkofficeid in 
											(SELECT tbl_warehouse.stkofficeid+1 FROM tbl_warehouse WHERE
											tbl_warehouse.wh_id = '".$items[3]."')";
							$getFldId = mysql_fetch_array(mysql_query($ssQL));
							
							$sql = "insert into tbl_wh_data set
							report_month ='".$items[0]."',
							report_year  ='".$items[1]."',
							item_id		 ='".$items[2]."',
							wh_id		 ='".$getFldId['wh_id']."',
							wh_obl_a	 ='".$items[13]."',
							wh_obl_c	 ='".$items[14]."',				
							wh_received  ='".$items[15]."',
							wh_issue_up  ='".$items[16]."',
							wh_cbl_c	 ='".$items[17]."',
							wh_cbl_a     ='".$items[18]."',
							wh_adja		 ='".$items[20]."',
							wh_adjb		 ='".$items[21]."',
							RptDate = '".$items[1]."-".$items[0]."-01' ";
							
							
							
							/*
										13.	fld_obl_a
										14.	fld_obl_c
										15.	fld_recieved
										16.	fld_issue_up
										17.	fld_cbl_c
										18.	fld_cbl_a
										19.	fld_mos
										20.	fld_adja
										21.	fld_adjb
										22.	wh_entry
										23.	fld_entry*/
						
							if($objDB22->execute($sql))
							{
								
							}
								
							}
						else if($total!=0 && (intval($items[0]) !=0 || intval($items[3])!=0)){			
							$sql_cnt ="Select count(*) from tbl_waiting_data where report_month=".intval($items[0])." and report_year=".intval($items[1])." and item_id='".trim($items[2])."' and wh_id=".intval($items[3]); 
							$total1   = $objDB21->executeScalar($sql_cnt);
							
							if($total1==0 && (intval($items[0]) !=0 || intval($items[3])!=0))
							{									
							$sql = "insert into tbl_waiting_data set
							report_month ='".$items[0]."',
							report_year  ='".$items[1]."',
							item_id		 ='".$items[2]."',
							wh_id		 ='".$items[3]."',
							wh_obl_a	 ='".$items[4]."',
							wh_obl_c	 ='".$items[5]."',				
							wh_received  ='".$items[6]."',
							wh_issue_up  ='".$items[7]."',
							wh_cbl_c	 ='".$items[8]."',
							mos			 ='".$items[9]."',
							wh_cbl_a     ='".$items[10]."',
							wh_adja		 ='".$items[11]."',
							wh_adjb		 ='".$items[12]."',
								
							fld_obl_a	 ='".$items[13]."',
							fld_obl_c	 ='".$items[14]."',				
							fld_recieved ='".$items[15]."',
							fld_issue_up ='".$items[16]."',
							fld_cbl_c	 ='".$items[17]."',
							fld_cbl_a	 ='".$items[18]."',
							fld_mos	     ='".$items[19]."',
							fld_adja	 ='".$items[20]."',
							fld_adjb	 ='".$items[21]."',
							wh_entry	 ='".$items[22]."',
							fld_entry	 ='".$items[23]."'"	;			
							}
							else
							{
								$sql = "update tbl_waiting_data set
								report_month ='".$items[0]."',
								report_year  ='".$items[1]."',
								item_id		 ='".$items[2]."',
								wh_id		 ='".$items[3]."',
								wh_obl_a	 ='".$items[4]."',
								wh_obl_c	 ='".$items[5]."',				
								wh_received  ='".$items[6]."',
								wh_issue_up  ='".$items[7]."',
								wh_cbl_c	 ='".$items[8]."',
								mos			 ='".$items[9]."',
								wh_cbl_a     ='".$items[10]."',
								wh_adja		 ='".$items[11]."',
								wh_adjb		 ='".$items[12]."',
									
								fld_obl_a	 ='".$items[13]."',
								fld_obl_c	 ='".$items[14]."',				
								fld_recieved ='".$items[15]."',
								fld_issue_up ='".$items[16]."',
								fld_cbl_c	 ='".$items[17]."',
								fld_cbl_a	 ='".$items[18]."',
								fld_mos	     ='".$items[19]."',
								fld_adja	 ='".$items[20]."',
								fld_adjb	 ='".$items[21]."',
								wh_entry	 ='".$items[22]."',
								fld_entry	 ='".$items[23]."' where report_month=".intval($items[0])." and report_year=".intval($items[1])." and item_id='".trim($items[2])."' and wh_id=".intval($items[3]);
							}
							
							
						
								if($objDB22->execute($sql))
								{
									
								}
								
								$pins .= $items[2].",";
								
							}
						else
						{
							
						}
					}
				
					fclose($file_handle);
					if(!empty($pins)){
					$str='<div style="color:red; padding-left:20px; text-align:center;"><strong>';
					$error .="<br />&nbsp;&nbsp;Record already exists for following items<br>$pins<br> Admin will inspect and approve them.";
					echo $str.=$error."</strong></div></div>";
					?>
					
					<?php
					//header("location:templategraphreport.php?error=".$error);
					}else{
						$str='<div style="color:green; padding-left:20px; text-align:center;"><strong>';
						$error .="<strong><br />&bull;&nbsp;&nbsp;Records added successfully</strong>";
						echo $str.=$error."</div>";
					}
					
					
				}else {
					 $error .= "We are sorry, you are not allowed to import this file.";
					 $str='<div style="color:red; padding-left:20px; text-align:center;"><strong>';
					 //$error ="<br /><br>&bull;&nbsp;&nbsp;File cannot be uploaded, choose the right format ";
					 echo $str.="<br><br>".$error." </strong></div>";
				}
			}
			 else
			 {
			 	$str='<div style="color:red; padding-left:20px; text-align:center;"><strong>';
				//$error ="<br /><br>&nbsp;&nbsp;File cannot be uploaded, choose the right format ";
				echo $str.="<br><br>".$error."</strong></div>";

			 } 
			 
		function getExtension($Filename){ 
			$Extension 	 = explode (".", $Filename);
			$Extension_i = (count($Extension) - 1);
			return $Extension[$Extension_i];
		}
?>	
<div align="right" style=" margin-left:400px; float:left; margin-top:40px"><input style="width:80px; height:25px" type="submit" value="Back" onclick="window.location.href='mainImport.php'" /></div> 
</div>
</div>
	</div>
    <?php include "../../plmis_inc/common/right_inner.php";?>
	<?php include "../../plmis_inc/common/footer.php";?>
</body>
</html>
<?php
include('config.php');
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=8" />
<?php
function startHtml($title = "")

{?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
	
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo !empty($title)? ''.$title:''; ?></title>

    <?php /*?><link href="<?php echo PLMIS_CSS;?>style.css" rel="STYLESHEET" type="TEXT/CSS">
	<link href="<?php echo PLMIS_CSS;?>main.css" rel="STYLESHEET" type="TEXT/CSS">
	<link href="<?php echo PLMIS_CSS;?>cpanel.css" rel="STYLESHEET" type="TEXT/CSS">
	<link href="<?php echo PLMIS_CSS;?>new_forms.css" rel="STYLESHEET" type="TEXT/CSS">
	<LINK ID="GridCSS" href="<?php echo PLMIS_CSS;?>Grid.css" TYPE="TEXT/CSS" REL="STYLESHEET"><?php */?>
    

     <SCRIPT LANGUAGE="JAVASCRIPT" TYPE="TEXT/JAVASCRIPT" SRC="<?php echo PLMIS_JS;?>FunctionLib.js"></SCRIPT>
	 <SCRIPT LANGUAGE="JAVASCRIPT" TYPE="TEXT/JAVASCRIPT" SRC="<?php echo PLMIS_JS;?>ClockTime.js"></SCRIPT>
     <SCRIPT LANGUAGE="JAVASCRIPT" TYPE="TEXT/JAVASCRIPT" SRC="<?php echo PLMIS_JS;?>cms.js"></SCRIPT>
     <script src="<?php echo PLMIS_JS;?>jquery-1.4.4.js" type="text/javascript"></script>
     <script src="<?php echo PLMIS_JS;?>jquery.autoheight.js" type="text/javascript"></script>
     <link href="<?php echo PLMIS_JS;?>facebox/facebox.css" media="screen" rel="stylesheet" type="text/css"/>
     <script src="<?php echo PLMIS_JS;?>facebox/facebox.js" type="text/javascript"></script>
     <script type="text/javascript">
                jQuery(document).ready(function($) {
                  $('a[rel*=facebox]').facebox({
                    loading_image : '<?php echo PLMIS_IMG;?>loading.gif',
                    close_image   : '<?php echo PLMIS_IMG;?>closelabel.gif'
                  }) 
                })
     </script>

    </head>
	<?php }
	
function siteMenu() { //echo PLMIS_INC;?>
    <?php include(SITE_PATH.'header.php');
}
function contents($contents){?>
	<div class="wrraper">
		<div class="content">
    		<?php echo $contents;?>       
   		</div>
    </div>
<?php }

function footer(){?>
	<div style="clear:both"></div>
<div class="footer">
</div>

<?php }
function endHtml(){?>
 


	</body>
</html>
<?php } ?>
<?php
function Login(){
 	if(!isset($_SESSION['user']))
	{
		$location = MAINSITE_URL.'index.php';?>
		
        <script type="text/javascript">
			window.location = "<?php echo $location;?>";
        </script>
	<?php }
 }

//////////////////////////    CRUMB FUNCTION 
 
function showBreadCrumb(){
	$trail = explode('/', $_SERVER['PHP_SELF']);
	$url = '/';
	$imgPath = PLMIS_IMG."arrow011.gif";
	$homeLink = SITE_URL."dashboard.php";
	/*echo "<pre>";
	print_r($trail);
	echo "</pre>";*/?>
    
	<style type="text/css">
		.myLinkClass a {color:#006700; font-size:11px; font-weight:bold;}
		.myLinkClass:hover {text-decoration: underline; color:#09F;}
	</style>
<?php 
	$returnString = "<span class=\"myLinkClass\"><a href='$homeLink' class=\"myLinkClass\">Home</a></span>&nbsp;&nbsp;<img src=$imgPath>&nbsp;&nbsp;";
	$dirArr = array("reports", "graph", "operations", "dataMigration", "maps", "plmis_admin", "dashboard");
	for($i=2; $i<count($trail)-1; $i++)
	{
		if($trail[$i] == "plmis_src")continue;
		else{
			if(in_array($trail[$i], $dirArr)){
				if ($trail[$i+1] == "view_admin_whreport.php" || $trail[$i+1] == "view_admin_freport.php"){
					$returnString .= "<span style=\"color:#006700; font-size:11px; font-weight:bold;\">LMIS</span>&nbsp;&nbsp;<img src=$imgPath>&nbsp;&nbsp;";	
				}else{
					$returnString .= "<span class=\"\" style=\"color:#006700; font-size:11px; font-weight:bold;\">" . directoryNames($trail[$i])."</span>&nbsp;&nbsp;<img src=$imgPath>&nbsp;&nbsp;";
				}
			}else{
				$url .= $trail[$i].'/';
				$returnString .= "<a href='$url' style=\"color:#006700; font-size:11px; font-weight:bold;\">" . directoryNames($trail[$i])."</a>&nbsp;&nbsp;<img src=$imgPath>&nbsp;&nbsp;";
			}
		}
		
	}
	
	if($trail[$i] == "view_admin_user.php"){
		$returnString .= "<span class=\"myLinkClass\"><a href='AddEditUser.php' class=\"myLinkClass\">".linkNames($trail[$i])."</a></span>";
	}else if($trail[$i] == "AddEditUser.php"){
		$returnString .= "<span class=\"myLinkClass\"><a href='view_admin_user.php' class=\"myLinkClass\">".linkNames($trail[$i])."</a></span>";
	}else if($trail[$i] == "view_admin_group.php"){
		$returnString .= "<span class=\"myLinkClass\"><a href='AddEditUserGroup.php' class=\"myLinkClass\">".linkNames($trail[$i])."</a></span>";
	}else if($trail[$i] == "AddEditUserGroup.php"){
		$returnString .= "<span class=\"myLinkClass\"><a href='view_admin_group.php' class=\"myLinkClass\">".linkNames($trail[$i])."</a></span>";
	}else if($trail[$i] == "view_admin_item.php"){
		$returnString .= "<span class=\"myLinkClass\"><a href='AddEditItem.php' class=\"myLinkClass\">".linkNames($trail[$i])."</a></span>";
	}else if($trail[$i] == "AddEditItem.php"){
		$returnString .= "<span class=\"myLinkClass\"><a href='view_admin_item.php' class=\"myLinkClass\">".linkNames($trail[$i])."</a></span>";
	}else if($trail[$i] == "view_admin_warehouse.php"){
		$returnString .= "<span class=\"myLinkClass\"><a href='AddEditWarehouse.php' class=\"myLinkClass\">".linkNames($trail[$i])."</a></span>";
	}else if($trail[$i] == "AddEditWarehouse.php"){
		$returnString .= "<span class=\"myLinkClass\"><a href='view_admin_warehouse.php' class=\"myLinkClass\">".linkNames($trail[$i])."</a></span>";
	}else if($trail[$i] == "view_admin_district.php"){
		$returnString .= "<span class=\"myLinkClass\"><a href='AddEditDistrict.php' class=\"myLinkClass\">".linkNames($trail[$i])."</a></span>";
	}else if($trail[$i] == "AddEditDistrict.php"){
		$returnString .= "<span class=\"myLinkClass\"><a href='view_admin_district.php' class=\"myLinkClass\">".linkNames($trail[$i])."</a></span>";
	}else if($trail[$i] == "view_admin_mos.php"){
		$returnString .= "<span class=\"myLinkClass\"><a href='AddEditMosScale.php' class=\"myLinkClass\">".linkNames($trail[$i])."</a></span>";
	}else if($trail[$i] == "AddEditMosScale.php"){
		$returnString .= "<span class=\"myLinkClass\"><a href='view_admin_mos.php' class=\"myLinkClass\">".linkNames($trail[$i])."</a></span>";
	}else if($trail[$i] == "view_admin_menu.php"){
		$returnString .= "<span class=\"myLinkClass\"><a href='AddEditMenu.php' class=\"myLinkClass\">".linkNames($trail[$i])."</a></span>";
	}else if($trail[$i] == "AddEditMenu.php"){
		$returnString .= "<span class=\"myLinkClass\"><a href='view_admin_menu.php' class=\"myLinkClass\">".linkNames($trail[$i])."</a></span>";
	}else if($trail[$i] == "view_admin_submenu.php"){
		$returnString .= "<span class=\"myLinkClass\"><a href='AddEditSubmenu.php' class=\"myLinkClass\">".linkNames($trail[$i])."</a></span>";
	}else if($trail[$i] == "AddEditSubmenu.php"){
		$returnString .= "<span class=\"myLinkClass\"><a href='view_admin_submenu.php' class=\"myLinkClass\">".linkNames($trail[$i])."</a></span>";
	}else if($trail[$i] == "view_admin_content.php"){
		$returnString .= "<span class=\"myLinkClass\"><a href='AddEditContent.php' class=\"myLinkClass\">".linkNames($trail[$i])."</a></span>";
	}else if($trail[$i] == "AddEditContent.php"){
		$returnString .= "<span class=\"myLinkClass\"><a href='view_admin_content.php' class=\"myLinkClass\">".linkNames($trail[$i])."</a></span>";
	}
	
	
	else{
		$pageName = basename($_SERVER['PHP_SELF']);
		$returnString .= "<span class=\"\" style=\"color:#006700; font-size:11px; font-weight:bold;\">".linkNames($pageName);
	}
	echo $returnString;
}
function directoryNames($dir){
	$directory = array();
	$directory['paklmis_final']='Home';
	$directory['reports']='Reports';
	$directory['graph']='Graph Reports';
	$directory['operations']='Manage';
	$directory['dataMigration']='Data Migration';
	$directory['maps']='Maps';
	$directory['plmis_admin']='Manage';
	$directory['dashboard']='Dashboard';
	
	
	if($directory[$dir]!='')
	{
		$dir = $directory[$dir];
	}
	return $dir;
}
function linkNames($pageLink){
	$imgPath = PLMIS_IMG."arrow011.gif";
	$linkArray = array();
	$linkArray['nationalreport.php']="National Report";
	$linkArray['nationalreportSTK.php']='National Report By Stakeholder';
	$linkArray['provincialreport.php']='Provincial Report';
	$linkArray['diststkreport.php']='District Report';
	$linkArray['fieldstkreport.php']='Field Report';
	$linkArray['itemsreport.php']='Item Availability Report';
	$linkArray['non_report.php']='Non&#47;Reported Store/Facility Report';
	$linkArray['templategraphreport.php']='Comparison Graphs';
	$linkArray['templategraphreport2.php']='Simple Graphs';
	$linkArray['view_admin_user.php']='Add User';
	$linkArray['AddEditUser.php']='View Users';
	$linkArray['view_admin_group.php']='Add User Group';
	$linkArray['AddEditUserGroup.php']='View User Group';
	$linkArray['view_admin_item.php']='Add Item';
	$linkArray['AddEditItem.php']='View Items';
	$linkArray['view_admin_warehouse.php']='Add Warehouse';
	$linkArray['AddEditWarehouse.php']='View Warehouse';
	$linkArray['view_admin_district.php']='Add District';
	$linkArray['AddEditDistrict.php']='View Districts';
	$linkArray['view_admin_mos.php']='Add MOS Scale';
	$linkArray['AddEditMosScale.php']='View MOS Scale';
	$linkArray['view_admin_menu.php']='Add Menu';
	$linkArray['AddEditMenu.php']='View Menus';
	$linkArray['view_admin_submenu.php']='Add Sub Menu';
	$linkArray['AddEditSubmenu.php']='View Sub Menus';
	$linkArray['view_admin_whreport.php']='Monthly Warehouse Report';
	$linkArray['view_admin_whreport1.php']='Monthly Warehouse Report';
	$linkArray['view_admin_freport.php']='Monthly Field Report';
	$linkArray['view_admin_content.php']='Add Content';
	$linkArray['AddEditContent.php']='View Contents';
	$linkArray['AddEditF7.php']='LMIS Report';
	$linkArray['mainImport.php']='Import';
	$linkArray['mainExport.php']='Export';
	$linkArray['importdata.php']='Import';
	$linkArray['exportdata.php']='Export';
	$linkArray['view_admin_waitingdata.php']='Waiting Data';
	$linkArray['provincial_warehouse_report.php']='Provincial Yearly Report';
	$linkArray['central_warehouse_report.php']='Central/Provincial Warehouse Report';
	$linkArray['private_sector_report.php']='Private Sector Report';
	$linkArray['nationalreport_ncp.php']="National Report&nbsp;&nbsp;<img src=$imgPath>&nbsp;&nbsp;Non-Contraceptives";
	$linkArray['nationalreport_tb.php']="National Report&nbsp;&nbsp;<img src=$imgPath>&nbsp;&nbsp;TB-Drugs";
	$linkArray['new_clr.php']="CLR-6";
	$linkArray['clr6.php']="CLR-6";
	$linkArray['clr_view.php']="CLR-6 View";
	$linkArray['stock.php']="District Stock";
	$linkArray['balance.php']="Stock Comparison";
	$linkArray['requisitions.php']="Requisitions";
	$linkArray['issue.php']="Issuance Form";
	$linkArray['new_gatepass.php']="New Gatepass";
	$linkArray['view_gatepass.php']="View Gatepass";
	$linkArray['edit_gatepass.php']="Edit Gatepass";
	$linkArray['mos.php']="Month Of Stock";
	$linkArray['consumption.php']="Consumption";
	$linkArray['cyp.php']="Couple Year Protection";
	$linkArray['cyp_pop.php']="CYP By Population";
	$linkArray['reporting_rate.php']= "Reporting Rate";
	$linkArray['new_clr_results.php']= "Projected Contraceptive Requirement";
	$linkArray['quarterly_rate.php']= "Quarterly Reporting Rate"; 
	$linkArray['province_rate.php']= "Provincial Reporting Rate"; 
	$linkArray['dashboard.php']= "Public Sector Dashboard"; 
	$linkArray['dashboard_private.php']= "Private Sector Dashboard";
	$linkArray['wh_data_entry.php']= "Data Entry";
	$linkArray['hf_type_data_entry.php']= "Health Facility Type wise Data Entry";
	$linkArray['approve_clr6.php']= "Approval Form";
	$linkArray['shipment.php']= "Distribution and SOH";
	$linkArray['expiry_schedule.php']= "Expiry Schedule";
	$linkArray['new_receive.php']= "Stock Receive";
	$linkArray['stock_adjustment.php']= "Stock Adjustment";
	$linkArray['new_receive_wh.php']= "Stock Receive (From Warehouse)";
	$linkArray['stock_receive.php']= "Stock Receive";
	$linkArray['stock_placement.php']= "Manage Locations";
	$linkArray['placement_locations.php']= "Location Status";
	$linkArray['stock_location.php']= "Location Status";
	$linkArray['add_stock.php']= "Add Stock";
	$linkArray['batch_management.php']= "Batch Management";
	$linkArray['new_issue.php']= "New Issue";
	$linkArray['stock_issue.php']= "Stock Issue";
	$linkArray['pick_stock.php']= "Pick Stock";
	$linkArray['add_adjustment.php']= "Add Adjustment";
	$linkArray['stock_summary.php']= "Stock Summary";
	$linkArray['bin_card.php']= "Bin Card";
	$linkArray['import.php']= "Import";
	$linkArray['countrywise_distribution.php']= "Countrywise Distribution Report";
	$linkArray['form14.php']= "Form 14";
	$linkArray['clr11.php']= "CLR-11";
	$linkArray['spr1.php']= "SPR-1";
	$linkArray['spr2.php']= "SPR-2";
	$linkArray['pwd3.php']= "PWD-3";
	$linkArray['spr3.php']= "SPR-3";
	$linkArray['outlet_cyp_comparison.php']= "Outlet CYP Comparison";
	$linkArray['district_cyp_comparison.php']= "District CYP Comparison";
	$linkArray['spr8.php']= "SPR-8";
	$linkArray['spr9.php']= "SPR-9";
	$linkArray['spr10.php']= "SPR-10";
	$linkArray['spr11.php']= "SPR-11";
	$linkArray['clr13.php']= "CLR-13";
	$linkArray['clr15.php']= "CLR-15";
	$linkArray['dpw_f1.php']= "DPW-F1";
	$linkArray['sale_proceeds.php']= "Sale Proceeds";
	$linkArray['stock_status.php']= "Stock Issuane Status";
    
	if($linkArray[$pageLink]!='')
	{
		$linkTitle = $linkArray[$pageLink];
	}
	return $linkTitle;
}
 ?>
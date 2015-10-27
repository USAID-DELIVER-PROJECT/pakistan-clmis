<link href="<?php echo PLMIS_CSS?>styles.css" rel="stylesheet" type="text/css" />
<script src="<?php echo PLMIS_JS?>jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL;?>plmis_admin/Scripts/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL;?>plmis_admin/Scripts/custom.js"></script>



<script type="text/javascript">
	$(document).ready(function(){
		$('#sidebar_left li').mouseover(function(){
			var id = '#'+ $(this).attr('id');
			if(id != '#')
				$(id+' .sub_menu').css('display','block');
		}).mouseout(function(){
			var id = '#'+ $(this).attr('id');
			if(id != '#')
				$(id+' .sub_menu').css('display','none');
		});
	});
</script>

<script language="JAVASCRIPT" type="TEXT/JAVASCRIPT">
	function Logout()
	{
		window.parent.location="<?php echo SITE_URL;?>Logout.php";
	}
</script>
<style>
div.content {
    background-color: #FFFFFF !important;
    float: left;
    padding: 13px !important;
    width: 970px;
}
div.menu ul li a
{
	padding:0 18px !important;
}
div.menu ul li ul.navigation-2 a
{
	padding:0 10px !important;
}
</style>

<?php
	$welcome_msg =  '<h2 id="textBold">Welcome, '.$_SESSION['user'].'&nbsp;&nbsp;';
	$welcome_msg .=  '<img style="vertical-align:middle; height:14px; width:14px; margin-right:2px; margin-left:10px;" src="'.PLMIS_IMG.'logout.jpg" /><a href="JavaScript:Logout()">Logout</a></h2>';
?>

<?php 

$sql="select stkid,province,sysusr_type,sysusr_name from sysuser_tab where UserID='".$_SESSION['userid']."'";
$sql2=mysql_query($sql);
$row_logo=mysql_fetch_array($sql2);

$province=$row_logo['province'];
$stkid=$row_logo['stkid'];
$ut=$row_logo['sysusr_type'];
$uname=$row_logo['sysusr_name'];


if($ut=='UT-005'){

	$flagGuest = TRUE;
}
else{
	$flagGuest = FALSE;
}

$query=mysql_query("select Stkid,province_id,logo from tbl_cms where homepage_chk=1 and Stkid='".$stkid."' AND province_id='".$province."'");
//print "select Stkid,province_id,logo from tbl_cms where homepage_chk=1 and Stkid='".$stkid."' AND province_id='".$province."'";
//exit;
$row_image=mysql_fetch_array($query);
$logo=$row_image['logo'];
if($logo=='')
{
	$logo="pak_federal.jpg";
}
?>


<!--header-->
<div id="header">
    <div id="header_section">
		<img src="<?php echo ADMIN_IMGS.$logo;?>" alt="" width="1002" height="125"/>
		<div id="shadow"> 
			<?php if ( isset($_SESSION['user']) &&  $flagGuest == FALSE ){ echo "<div style='color:Red;text-align:center'>For any problem please contact Wasif -> Email: wasif@deliver-pk.org Mobile: 0345-5115722 Landline: 051-2655425-6</div>";}?>
			<link href="<?php echo SITE_URL?>css/PAK.css" rel="stylesheet" type="text/css" />
<div class="menu">
	<div class="wrraper">
		<ul>
			<li><a href="#"><?php echo $uname.' ';?><img src="<?php echo SITE_URL;?>images/menu-arrow.png"></a>
				<ul class="navigation-2">
					<li><a href="<?php echo SITE_URL;?>Logout.php">Logout</a></li>
					<?php  
			if (  isset($_SESSION['user']) &&  $flagGuest == FALSE )
			{
			?>
					<li><a href="<?php echo SITE_URL;?>plmis_admin/changePassUser.php">Change Password</a></li>
					<?php	
			}
			?>
				</ul>
				</li>
			<li><a href="<?php echo SITE_URL;?>Cpanel.php"><img src="<?php echo ADMIN_IMGS;?>green-home-icon.png" alt="" width="25" height="25"/></a></li>
			<?php  
			if (  isset($_SESSION['user']) &&  $flagGuest == FALSE )
			{
			?>
			<li><a href="<?php echo SITE_URL;?>plmis_admin/wh_data_entry.php">Data Entry</a> </li>
			<li><a href="#">Monthly Reports <img src="<?php echo SITE_URL;?>images/menu-arrow.png"></a>
				<ul class="navigation-2">
					<li><a href="<?php echo SITE_URL;?>plmis_admin/view_admin_whreport.php">My Reports </a></li>
					<li><a href="<?php echo PLMIS_SRC;?>operations/view_admin_whreport.php">Other Warehouse Reports</a></li>
				</ul>
			
			 </li>
			<?php	
			}
			?>
			<li><a href="#">Reports <img src="<?php echo SITE_URL;?>images/menu-arrow.png"></a> 
                <ul class="navigation-2">
                    <li><a href="<?php echo PLMIS_SRC;?>reports/nationalreport.php">National Summary Report</a></li>
                    <li><a href="<?php echo PLMIS_SRC;?>reports/nationalreportSTK.php">Stakeholder Summary Report</a></li>
                    <li><a href="<?php echo PLMIS_SRC;?>reports/provincialreport.php">Provincial Summary Report</a></li>
                    <li><a href="<?php echo PLMIS_SRC;?>reports/diststkreport.php">District Summary Report</a></li>
                    <li><a href="<?php echo PLMIS_SRC;?>reports/stock.php">District Stock Report</a></li>
                  <!--  <li><a href="<?php  //echo PLMIS_SRC;?>reports/fieldstkreport.php">Field Report</a></li>-->
                    <li><a href="<?php echo PLMIS_SRC;?>reports/itemsreport.php">Stock Availability Report</a></li>
                    <li><a href="<?php echo PLMIS_SRC;?>reports/non_report.php">Non&#47;Reported Districts</a></li>
                    <li><a href="<?php echo PLMIS_SRC;?>reports/quarterly_rate.php">Quarterly Reporting Rate</a></li>
                    <li><a href="<?php echo PLMIS_SRC;?>reports/province_rate.php">Provincial Reporting Rate</a></li>
                    <li><a href="<?php echo PLMIS_SRC;?>operations/new_clr_results.php">Projected Contraceptive Requirements</a></li>
                    <li><a href="<?php echo PLMIS_SRC;?>reports/central_warehouse_report.php">Central/Provincial Warehouse</a></li>
                    <li><a href="<?php echo PLMIS_SRC;?>reports/provincial_warehouse_report.php">Provincial Yearly Report</a></li>
                    <li><a href="<?php echo PLMIS_SRC;?>reports/private_sector_report.php">Private Sector Yearly Report</a></li>
                  <!--  <li><a href="<?php echo PLMIS_SRC;?>reports/qtr_report.php">Quaterly Report</a></li>-->
                    <li><a href="<?php echo PLMIS_SRC;?>reports/pp_sector_report.php">Public-Private Sector Report</a></li>
                </ul>
            </li>
			<li><a href="#">Maps <img src="<?php echo SITE_URL;?>images/menu-arrow.png"></a>
                            <ul class="navigation-2">
                                <li><a href="<?php echo PLMIS_SRC;?>maps/mos.php">Month of Stock</a></li>
                                <li><a href="<?php echo PLMIS_SRC;?>maps/consumption.php">Consumption</a></li>
                                <li><a href="<?php echo PLMIS_SRC;?>maps/cyp.php">Couple Year Protection (CYP)</a></li>
                                <li><a href="<?php echo PLMIS_SRC;?>maps/cyp_pop.php">CYP By Population</a></li>
                                <li><a href="<?php echo PLMIS_SRC;?>maps/reporting_rate.php">Reporting rate</a></li>
<!--                                <li><a href="<?php echo PLMIS_SRC;?>maps/completeness_of_reporting.php">Completeness of Reporting</a></li>-->
                            </ul>
             </li>
			<li><a href="#">Graphs <img src="<?php echo SITE_URL;?>images/menu-arrow.png"></a>
				<ul class="navigation-2">
                    <li id=""><a href="<?php echo PLMIS_SRC;?>graph/templategraphreport.php">Comparison Graphs</a></li>
                    <li id=""><a href="<?php echo PLMIS_SRC;?>graph/templategraphreport2.php">Simple Graphs</a></li>
				</ul>
			</li>
			<?php if ( $flagGuest == TRUE || isset($_SESSION['user'])==FALSE){ ?>
			<li><a href="#">LMIS Explorer <img src="<?php echo SITE_URL;?>images/menu-arrow.png"></a>
				<ul class="navigation-2">
                    <li id=""><a href="<?php echo PLMIS_SRC;?>operations/view_admin_whreport.php">Monthly Warehouse Report</a></li>
				</ul>
			</li>
                        <li>
                            <a href="<?php echo SITE_URL;?>manuals.php">Training Manuals</a> 
                        </li>
			<?php }
			if ( isset($_SESSION['user']) &&  $flagGuest == FALSE )
			{
			?>
            <?php	
			}?>
            <?php
			if ( isset($_SESSION['user']) && $_SESSION['sysgroup_id'] == 'SG-014' &&  $flagGuest == FALSE )
			{
			?>
			<li><a href="#">CLR-6 <img src="<?php echo SITE_URL;?>images/menu-arrow.png"></a>
				<ul class="navigation-2">
                    <li id=""><a href="<?php echo PLMIS_SRC;?>operations/new_clr.php">New CLR-6</a></li>
                    <li id=""><a href="<?php echo PLMIS_SRC;?>operations/clr6.php">View CLR-6</a></li>
				</ul>
			</li>
			<?php	
			}
			if (isset($_SESSION['userdata'][8]) && $_SESSION['userdata'][8] == 1 && $_SESSION['userdata'][2] != 'Guest')
			{
			?>
			 
			<li><a href="#">Requisitions<img src="<?php echo SITE_URL;?>images/menu-arrow.png"></a>
				<ul class="navigation-2">
                    <li id=""><a href="<?php echo PLMIS_SRC;?>operations/requisitions.php">Requisition Requests</a></li>
				</ul>
			</li>
                        <li><a href="#">Gate Pass<img src="<?php echo SITE_URL;?>images/menu-arrow.png"></a>
				<ul class="navigation-2">
                    <li id=""><a href="<?php echo PLMIS_SRC;?>operations/new_gatepass.php">New Gate Pass</a></li>
                    <li id=""><a href="<?php echo PLMIS_SRC;?>operations/view_gatepass.php">View Gate Pass</a></li>
				</ul>
			</li>
			<?php	
			}?>
			<?php //if (isset($_SESSION['userdata'][8]) && $_SESSION['userdata'][8] == 1 && $_SESSION['userdata'][2] != 'Guest')
			if( $flagGuest == FALSE )
			{?>
			<li><a href="#">Stock <img src="<?php echo SITE_URL;?>images/menu-arrow.png"></a>
				<ul class="navigation-2">
                    <li id=""><a href="<?php echo SITE_URL;?>plmis_admin/new_receive.php">Stock Receive (Supplier)</a></li>
                    <li id=""><a href="<?php echo SITE_URL;?>plmis_admin/new_receive_wh.php">Stock Receive (Warehouse)</a></li>
                    <li id=""><a href="<?php echo SITE_URL;?>plmis_admin/stock_receive.php">Stock Receive Search</a></li>
                    <li id=""><a href="<?php echo SITE_URL;?>plmis_admin/new_issue.php">Stock Issue</a></li>
                    <li id=""><a href="<?php echo SITE_URL;?>plmis_admin/stock_issue.php">Stock Issue Search</a></li>
                    <li id=""><a href="<?php echo SITE_URL;?>plmis_admin/batch_management.php">Batch Management</a></li>
                    <li id=""><a href="<?php echo SITE_URL;?>plmis_admin/add_adjustment.php">New Adjustments</a></li>
                    <li id=""><a href="<?php echo SITE_URL;?>plmis_admin/stock_adjustment.php">Search Adjustments</a></li>
                    <li id=""><a href="<?php echo SITE_URL;?>plmis_admin/stock_placement.php">Stock Placement</a></li>
                    <li id=""><a href="<?php echo SITE_URL;?>plmis_admin/pick_stock.php">Stock Pick</a></li>
                    <li id=""><a href="<?php echo SITE_URL;?>plmis_admin/placement_locations.php">Placement Locations</a></li>
				</ul>
			</li>
            <?php }?>
		</ul>
	</div>
</div>
		</div>
	</div>
</div>
<!--headerm end--> 

<!--contaiiner-->
<div id="container">

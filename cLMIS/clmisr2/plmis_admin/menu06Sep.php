<?php
include("../html/adminhtml.inc.php");
$sql="select stkid,province from sysuser_tab where UserID='".$_SESSION['userid']."'";
$sql2=mysql_query($sql);
$row_logo=mysql_fetch_array($sql2);
$province=$row_logo['province'];
$stkid=$row_logo['stkid'];
$query=mysql_query("select Stkid,province_id,logo from tbl_cms where Stkid='".$stkid."' AND province_id='".$province."'");
$row_image=mysql_fetch_array($query);
$logo=$row_image['logo'];

?>

<style>
	div.menu ul li a
	{
		padding:0 20px !important;
	}
</style>
<div id="header">
    <div id="header_section">
    	<div id="shadow">
        	<img src="images/<?php echo $logo; ?>" alt="" />
				<link href="../css/PAK.css" rel="stylesheet" type="text/css" />
                <div class="menu">
                    <div class="wrraper">
                        <ul>    
                            <li><a href="ManageUser.php">Users</a> </li>
                            <li><a href="ManageWarehouse.php">Warehouses</a> </li>
                            <li><a href="ManageLocations.php">Locations</a> </li>
                            <li><a href="#">Stakeholders <img src="../images/menu-arrow.png"></a> 
                                <ul class="navigation-2">
                                    <li><a href="ManageStakeholders.php">Manage Stakeholders</a> 
                                    <li><a href="ManageStakeholdersOfficeTypes.php">Stakeholders' Offices</a> </li>
                                    <li><a href="ManageStakeholdersItems.php">Stakeholders' Products</a> </li>
                                </ul>
                            </li>
                            <li><a href="#">Products <img src="../images/menu-arrow.png"></a> 
                                <ul class="navigation-2">
                                    <li><a href="ManageItems.php">Manage Product</a> </li>
                                    <li><a href="ManageItemsGroups.php">Manage Group</a> </li>
                                    <li><a href="ManageItemsofGroups.php">Manage Group Product</a></li>
                                    <li><a href="MoSManage.php">Manage Mos Scale</a></li>
                                    <li><a href="ManageProductType.php">Manage Product Type</a></li>
                                    <li><a href="ManageProductCategory.php">Manage Category</a></li>
                                    <li><a href="ManageProductStatus.php">Manage Status</a></li>
                                </ul>
                            </li>
                            <li><a href="view_admin_waitingdata.php">Waiting Data</a>
                            <li><a href="changePass.php">Change Password</a></li>
                            <li><a href="Logout.php">Logout</a></li>
                        </ul>
                    </div>
            </div>
        </div>
	</div>
</div>
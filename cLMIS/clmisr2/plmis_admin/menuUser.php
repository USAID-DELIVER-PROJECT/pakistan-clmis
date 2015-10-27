<style>
	div.menu ul li a
	{
		padding:0 20px !important;
	}
	div.menu ul li ul.navigation-2 a
	{
		padding:0 10px !important;
	}
</style>

<link href="../css/PAK.css" rel="stylesheet" type="text/css" />
<div class="menu">
	<div class="wrraper">
		<ul>    
			<li><a href="data_entry.php">Data Entry</a> </li>
			<li><a href="view_admin_whreport.php">Previous Month's Report </a> </li>
			<li><a href="#">Reports <img src="../images/menu-arrow.png"></a> 
                <ul class="navigation-2">
                    <li><a href="../plmis_src/reports/nationalreport.php">National Summary Report</a></li>
                    <li><a href="../plmis_src/reports/provincialreport.php">Provincial Summary Report</a></li>
                    <li><a href="../plmis_src/reports/diststkreport.php">District Report</a></li>
                    <li><a href="../plmis_src/reports/itemsreport.php">Stock Availability Report</a></li>
                    <li><a href="../plmis_src/reports/non_report.php">Non Reported Districts</a></li>
                    <li><a href="../plmis_src/reports/central_warehouse_report.php">Central/Provincial Warehouse</a></li>
                    <li><a href="../plmis_src/reports/provincial_warehouse_report.php">Yearly Report</a></li>
                    <li><a href="../plmis_src/reports/qtr_report.php">Quaterly Report</a></li>
                    </li>
                </ul>
            </li>
			<li><a href="#">Graphs <img src="../images/menu-arrow.png"></a>
				<ul class="navigation-2">
                    <li id=""><a href="../plmis_src/graph/templategraphreport.php">Comparison Graphs</a></li>
                    <li id=""><a href="../plmis_src/graph/templategraphreport2.php">Simple Graphs</a></li>
				</ul>
			</li>
			<li><a href="#">LMIS Explorer <img src="../images/menu-arrow.png"></a>
				<ul class="navigation-2">
                    <li id=""><a href="../plmis_src/operations/view_admin_whreport.php">Monthly Warehouse Report</a></li>
				</ul>
			</li>
			<li><a href="changePass.php">Change Password</a></li>
			<li><a href="Logout.php">Logout</a></li>
		</ul>
	</div>
</div>

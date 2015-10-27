<?php
require_once("Includes/Configuration.inc.php");
require_once("Includes/clsConfiguration.php");
session_start();

$objConfiguration=new clsConfiguration();
$nStat=$objConfiguration->GetDB($strHost, $strDatabase, $strUserName, $strPassword);

require_once("Includes/clsLocations.php");
require_once("Includes/clsswharehouse.php");

$objloc = new clslocations();
$objwarehouse = new clswarehouse();

if(!empty($_SESSION['prov_id'])){
	$UserProvID = $_SESSION['prov_id'];
}

//include("Includes/AllClasses.php");


if(isset($_REQUEST['office']) && !empty($_REQUEST['office'])){
	$office = $_REQUEST['office'];
	$stk_id = $_REQUEST['mainstkid'];
	if($office=='1'){
		$result = $objwarehouse->GetFederalWarehouses($stk_id); 
		?>
		<option value="">Select</option>
		<?php
		while($row = mysql_fetch_object($result)){
			?>
			<option value="<?php echo $row->wh_id; ?>">
				<?php echo $row->wh_name; ?></option>
			<?php
		}
	}
	else
	{
	//print $office;exit;
		//$result = $objloc->GetLocationsByLevel(10, 2);
		$objloc->LocLvl = 2;
		$result = $objloc->GetAllLocations();
		?>
		<option value="">Select</option>
		<?php while($row = mysql_fetch_object($result)){
			?>
			<option value="<?php echo $row->PkLocID; ?>" <?php if (!empty($_SESSION['lastTransProv']) && $row->PkLocID==$_SESSION['lastTransProv']) print ' selected="selected"'?>>
				<?php echo $row->LocName; ?></option>
			<?php
		} 
	}
}?>
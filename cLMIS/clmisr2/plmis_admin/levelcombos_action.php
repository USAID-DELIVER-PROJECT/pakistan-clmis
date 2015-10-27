<?php
include("Includes/AllClasses.php");
$lvl = $_SESSION['UserLvl'];
$stk_id = $_SESSION['stk_id'];

if(!empty($_SESSION['prov_id'])){
	$UserProvID = $_SESSION['prov_id'];
}
if(!empty($_SESSION['dist_id'])){
	$UserDistID = $_SESSION['dist_id'];
}

if(isset($_REQUEST['office']) && !empty($_REQUEST['office']))
{
	$OfficeLvl = $_REQUEST['office'];
	$mainstkid = $_REQUEST['mainstkid'];
	switch($OfficeLvl)
	{
		case '1': break;
		case '2': $result = $objloc->GetLocationsByLevel($UserProvID, $OfficeLvl);
		?>
			<option value="">Select</option>
			<?php while($row = mysql_fetch_object($result))
			{
			?>
				<option value="<?php echo $row->PkLocID; ?>">
				<?php echo $row->LocName; ?></option>
				<?php
			}	
		break;
		case '3':  $result = $objwarehouse->GetDistrictWarehousesofProvince($UserProvID, $stk_id, $mainstkid); 
		
	?>
		<option value="">Select</option>
		<?php while($row = mysql_fetch_object($result))
		{
		?>
			<option value="<?php echo $row->wh_id; ?>">
			<?php echo $row->wh_name; ?></option>
		<?php	
		}
		break;
		case '4': $result = $objloc->GetLocationsByLevel($UserProvID, $OfficeLvl);
		?>
		<option value="">Select</option>
		<?php while($row = mysql_fetch_object($result))
		{
		?>
			<option value="<?php echo $row->PkLocID; ?>">
			<?php echo $row->LocName; ?></option>
		<?php
		}
		break;	
		case '5':  $result = $objwarehouse->GetProvincialWarehouses($UserProvID, $stk_id); 
	?>
		<option value="">Select</option>
		<?php while($row = mysql_fetch_object($result))
		{
		?>
			<option value="<?php echo $row->wh_id; ?>">
			<?php echo $row->wh_name; ?></option>
		<?php	
		}
		break;
		case '6':  $result = $objwarehouse->GetDivsionalWarehousesofProvince($UserProvID, $stk_id); 
	?>
		<option value="">Select</option>
		<?php while($row = mysql_fetch_object($result))
		{
		?>
			<option value="<?php echo $row->wh_id; ?>">
			<?php echo $row->wh_name; ?></option>
		<?php	
		}
		break;
		case '7':  $result = $objwarehouse->GetDistrictWarehousesofProvince($UserProvID, $stk_id); 
	?>
		<option value="">Select</option>
		<?php while($row = mysql_fetch_object($result))
		{
		?>
			<option value="<?php echo $row->wh_id; ?>">
			<?php echo $row->wh_name; ?></option>
		<?php	
		}
		break;
		case '8': 
			$result = $objwarehouse->GetTehsilWarehousesofDistrict($UserDistID, $stk_id);
	?>
		<option value="">Select</option>
		<?php while($row = mysql_fetch_object($result))
		{
		?>
			<option value="<?php echo $row->wh_id; ?>">
			<?php echo $row->wh_name; ?></option>
		<?php	
		}
		 break;
		case '9': 
		$result = $objwarehouse->GetUCWarehousesofDistrict($UserDistID, $stk_id);
	?>
		<option value="">Select</option>
		<?php while($row = mysql_fetch_object($result))
		{
		?>
			<option value="<?php echo $row->wh_id; ?>">
			<?php echo $row->wh_name; ?></option>
		<?php	
		}
		 break;
	}
} 
?>
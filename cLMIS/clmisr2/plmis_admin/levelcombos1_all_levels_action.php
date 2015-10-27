<?php
@session_start();
require_once("Includes/Configuration.inc.php");
require_once("Includes/clsConfiguration.php");

$objConfiguration=new clsConfiguration();
$nStat=$objConfiguration->GetDB($strHost, $strDatabase, $strUserName, $strPassword);
$page_name = $_SESSION['page_name'];

require_once("Includes/clsLocations.php");
require_once("Includes/clsswharehouse.php");

$objloc = new clslocations();
$objwarehouse = new clswarehouse();

//include("Includes/AllClasses.php");
$stk_id = '';

if(isset($_REQUEST['combo1']) && !empty($_REQUEST['combo1']))
{
	$office = $_REQUEST['office'];
	$combo1=$_REQUEST['combo1'];
	$mainstk=$_REQUEST['mainstkid'];
	// print $combo1; exit;
	switch($office)
	{
		case 2: 
		$result = $objwarehouse->GetProvincialWarehouses($combo1, $stk_id,$mainstk);
		?>
		<option value="">Select</option>
		<?php 
		while($row = mysql_fetch_object($result))
		{
                    if($_SESSION['wh_id'] != $row->wh_id) {
			?>
			<option value="<?php echo $row->wh_id; ?>">
				<?php echo $row->wh_name.' ('.$row->stkname.')'; ?>
                        </option>
			<?php
                    }
		} 
		break;
		
		case 3: $result = $objwarehouse->GetDistrictWarehousesofProvince($combo1, $stk_id,$mainstk);
		?>
		<option value="">Select</option>
		<?php
		while($row = mysql_fetch_object($result))
		{
        	if($_SESSION['wh_id'] != $row->wh_id) {
		?>
			<option value="<?php echo $row->wh_id; ?>" <?php echo (isset($_SESSION['lastTransWH']) && $row->wh_id == $_SESSION['lastTransWH']) ? 'selected="selected"' : '';?>><?php echo $row->wh_name.' ('.$row->stkname.')'; ?></option>
		<?php
        	}
		} 
		break;

		case 4: $result = $objwarehouse->GetFieldWarehousesofProvince($combo1, $stk_id,$mainstk); 
		?>
		<option value="">Select</option>
		<?php
		while($row = mysql_fetch_object($result))
		{
                    if($_SESSION['wh_id'] != $row->wh_id) {
		?>
			<option value="<?php echo $row->wh_id; ?>">
				<?php echo $row->wh_name.' ('.$row->stkname.')'; ?></option>
		<?php
                    }
		} 
		break; 
		
		case 6: $result = $objloc->GetLocationsByLevelByProvince($combo1, 4); 
		?>
		<option value="">Select</option>
		<?php 
		while($row = mysql_fetch_object($result))
		{
		?>
			<option value="<?php echo $row->PkLocID; ?>">
				<?php echo $row->LocName; ?></option>
		<?php
		} 
		break;
		
		case 7: $result = $objloc->GetLocationsByLevel($combo1, 3);
		?>
		<option value="">Select</option>
		<?php
		while($row = mysql_fetch_object($result))
		{
            //if($_SESSION['wh_id'] != $row->PkLocID)
			{
		?>
			<option value="<?php echo $row->PkLocID; ?>" <?php echo (isset($_SESSION['lastTransDist']) && $row->PkLocID == $_SESSION['lastTransDist']) ? 'selected="selected"' : '';?>><?php echo $row->LocName; ?></option>
		<?php
            }
		} 
		break;
		
		case 8: $result = $objloc->GetLocationsByLevel($combo1, 3);
		?>
		<option value="">Select</option>
		<?php
		while($row = mysql_fetch_object($result))
		{
            //if($_SESSION['wh_id'] != $row->PkLocID)
			{
		?>
			<option value="<?php echo $row->PkLocID; ?>"><?php echo $row->LocName; ?></option>
		<?php
            }
		} 
		break; 
	} 
}
?> 
												   
												   
												   
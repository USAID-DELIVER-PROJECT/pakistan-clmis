<?php
@session_start();
require_once("Includes/Configuration.inc.php");
require_once("Includes/clsConfiguration.php");

$objConfiguration=new clsConfiguration();
$nStat=$objConfiguration->GetDB($strHost, $strDatabase, $strUserName, $strPassword);
$page_name = $_SESSION['page_name'];

require_once("Includes/clsswharehouse.php");

$objwarehouse = new clswarehouse();
$stk_id = 1;

if(isset($_REQUEST['combo2']) && !empty($_REQUEST['combo2']))
{
	$combo2=$_REQUEST['combo2'];
	$office=$_REQUEST['office'];
	$mainstk=$_REQUEST['mainstkid'];
	//print $office; exit;
	switch($office)
	{
		case 5: $result = $objwarehouse->GetTehsilWarehousesofDistrict($combo2, $stk_id);
		?>
		<option value="">Select</option>
		<?php
		while($row = mysql_fetch_object($result))
		{
                    if($_SESSION['wh_id'] != $row->wh_id) {
		?>
			<option value="<?php echo $row->wh_id; ?>">
				<?php echo $row->wh_name; ?></option>
		<?php
                    }
		} 
		break;
		
		case 6: $result = $objwarehouse->GetUCWarehousesofDistrict($combo2, $stk_id);
		?>
		<option value="">Select</option>
		<?php
		while($row = mysql_fetch_object($result))
		{
                    if($_SESSION['wh_id'] != $row->wh_id) {
		?>
			<option value="<?php echo $row->wh_id; ?>">
				<?php echo $row->wh_name; ?></option>
		<?php
                    }
		} 
		break;
		
		case 7: $result = $objwarehouse->GetHealthFacilityWarehousesofDistrict($combo2, $mainstk);
		?>
		<option value="">Select</option>
		<?php
		while($row = mysql_fetch_object($result))
		{
                    if($_SESSION['wh_id'] != $row->wh_id) {
		?>
			<option value="<?php echo $row->wh_id; ?>" <?php echo (isset($_SESSION['lastTransWH']) && $row->wh_id == $_SESSION['lastTransWH']) ? 'selected="selected"' : '';?>><?php echo $row->wh_name; ?></option>
		<?php
                    }
		} 
		break;
		
		case 8: $result = $objwarehouse->GetLevel8WarehousesofDistrict($combo2, $mainstk);
		?>
		<option value="">Select</option>
		<?php
		while($row = mysql_fetch_object($result))
		{
                    if($_SESSION['wh_id'] != $row->wh_id) {
		?>
			<option value="<?php echo $row->wh_id; ?>" <?php echo (isset($_SESSION['lastTransWH']) && $row->wh_id == $_SESSION['lastTransWH']) ? 'selected="selected"' : '';?>><?php echo $row->wh_name; ?></option>
		<?php
                    }
		} 
		break;
	} 
}
?> 
												   
												   
												   
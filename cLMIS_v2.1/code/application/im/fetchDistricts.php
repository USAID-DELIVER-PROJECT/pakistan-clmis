<?php
/**
 * fetchDistricts
* @package im
 * 
 * @author     Muhammad Waqas Azeem 
 * @email <waqas@deliver-pk.org>
 * 
 * @version    2.2
 * 
 */

//Including required files
include("../includes/classes/AllClasses.php");

//Province Id
$pid = $_REQUEST['pid'];
//Distrist Id
$distId = $_REQUEST['distId'];
$result = "";
//for getting districts
$result .= " <select name=\"districts\" id=\"districts\" class=\"form-control input-medium\">";
$qry  = "SELECT
			PkLocID,
			LocName
		FROM
			tbl_locations
		WHERE
			ParentID = ".$pid."
		ORDER BY
			LocName";
$rsfd = mysql_query($qry) or die(mysql_error());
while($row = mysql_fetch_array($rsfd)){
	$sel = ($distId == $row['PkLocID']) ? 'selected="selected"' : '';
	$result .="<option value=\"".$row['PkLocID']."\" $sel>".$row['LocName']."</option>";
}	
$result .="</select>";
echo $result;
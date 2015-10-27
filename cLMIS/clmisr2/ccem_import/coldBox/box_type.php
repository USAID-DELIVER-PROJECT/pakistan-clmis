<?php
include('../config.php');

$mapArr = array(
	1 => 124,
	2 => 125,
	3 => 126,
	4 => 127,
	5 => 128
);

foreach( $mapArr as $key=>$val )
{
	$qry = "UPDATE import_cold_boxes
		SET box_type = $val
			WHERE
				import_cold_boxes.fi_cold_box_type = $key";
	mysql_query($qry);
}

echo 'Cold box types are updated.';
?>
<a href="../regulator">Next</a>
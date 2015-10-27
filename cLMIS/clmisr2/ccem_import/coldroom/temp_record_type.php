<?php
include('../config.php');

$mapArr = array(
	1 => 5,
	2 => 8,
	3 => 9,
	4 => 10,
	5 => 11,
	6 => 12,
	7 => 13,
	8 => 14
);

foreach( $mapArr as $key=>$val )
{
	$qry = "UPDATE import_cold_room
		SET temp_record_type = $val
			WHERE
				import_cold_room.fi_temp_record_type = $key";
	mysql_query($qry);
}
// For unknown type
$qry = "UPDATE import_cold_room
	SET temp_record_type = 14
		WHERE
			import_cold_room.fi_temp_record_type = 0";
mysql_query($qry);

echo 'Temprature record types are updated.<a href="gas_type.php">Continue</a>';
<?php
include('../config.php');

$mapArr = array(
	1 => 26,
	2 => 27,
	3 => 28
);

foreach( $mapArr as $key=>$val )
{
	$qry = "UPDATE import_transport
		SET fuel_type = $val
			WHERE
				import_transport.ft_fuel_type = $key";
	mysql_query($qry);
}

echo 'Fuel types are updated.';
?>
<a href="../data_import/ImportRef.php">Next</a>
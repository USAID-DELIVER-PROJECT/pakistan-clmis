<?php
include('../config.php');

$mapArr = array(
	1 => 38,
	2 => 39,
	3 => 40,
	4 => 41,
	5 => 42
);

foreach( $mapArr as $key=>$val )
{
	$qry = "UPDATE import_transport
		SET transport_type = $val
			WHERE
				import_transport.ft_transport_type = $key";
	mysql_query($qry);
}

echo 'Asset sub-types are updated.<a href="fuel_type.php">Next</a>';
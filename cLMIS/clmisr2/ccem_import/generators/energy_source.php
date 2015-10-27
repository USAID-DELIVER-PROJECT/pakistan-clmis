<?php
include('../config.php');

$mapArr = array(
	1 => 27,
	2 => 26,
	3 => 28
);

foreach( $mapArr as $key=>$val )
{
	$qry = "UPDATE import_generators
		SET energy_source = $val
			WHERE
				import_generators.fi_energy_source = $key";
	mysql_query($qry);
}

echo 'Energy source is updated.';
?>
<a href="../vehicles">Next</a>
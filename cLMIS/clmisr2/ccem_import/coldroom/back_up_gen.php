<?php
include('../config.php');

$qry = "UPDATE import_cold_room
	SET backup_generator = 23
		WHERE
			import_cold_room.fi_has_generator = 0";
mysql_query($qry);

$qry = "UPDATE import_cold_room
	SET backup_generator = 24
		WHERE
			import_cold_room.fi_has_generator = 1";
mysql_query($qry);

$qry = "UPDATE import_cold_room
	SET backup_generator = 25
		WHERE
			import_cold_room.fi_has_generator = 2";
mysql_query($qry);
?>
Backup generator are updated.
<a href="../refrigerators">Next</a>
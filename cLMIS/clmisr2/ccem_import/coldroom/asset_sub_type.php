<?php
include('../config.php');

$qry = "UPDATE import_cold_room
	SET asset_sub_type = 16
		WHERE
			import_cold_room.fn_gross_volume_4deg != 0
		AND import_cold_room.fn_net_volume_4deg != 0
		AND import_cold_room.fn_gross_volume_4deg != ''
		AND import_cold_room.fn_net_volume_4deg != ''";
mysql_query($qry);

$qry = "UPDATE import_cold_room
	SET asset_sub_type = 15
		WHERE
			import_cold_room.fn_gross_volume_20deg != 0
		AND import_cold_room.fn_net_volume_20deg != 0
		AND import_cold_room.fn_gross_volume_20deg != ''
		AND import_cold_room.fn_net_volume_20deg != ''";
mysql_query($qry);


echo 'Asset sub-types are updated.<a href="temp_record_type.php">Continue</a>';
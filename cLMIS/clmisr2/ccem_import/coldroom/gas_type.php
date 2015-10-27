<?php
include('../config.php');

$qry = "UPDATE import_cold_room,
 list_detail
SET import_cold_room.gas_type = list_detail.pk_id
WHERE
	import_cold_room.ft_ref_gas_type = list_detail.list_value
AND list_detail.list_master_id = 3";
mysql_query($qry);

echo 'Gas types are updated.<a href="back_up_gen.php">Continue</a>';
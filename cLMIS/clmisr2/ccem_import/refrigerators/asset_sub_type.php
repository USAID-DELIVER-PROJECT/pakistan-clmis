<?php

include('../config.php');

$qry = "UPDATE import_refrigerators
	SET ref_sub_type = 8
		WHERE
	        import_refrigerators.ft_item_type = 'CFAC' ";
mysql_query($qry);

$qry = "UPDATE import_refrigerators
	SET ref_sub_type = 11
		WHERE
	        import_refrigerators.ft_item_type = 'CRAC' ";
mysql_query($qry);

$qry = "UPDATE import_refrigerators
	SET ref_sub_type = 13
		WHERE
	        import_refrigerators.ft_item_type = 'CREG' ";
mysql_query($qry);

$qry = "UPDATE import_refrigerators
	SET ref_sub_type = 14
		WHERE
	        import_refrigerators.ft_item_type = 'CREK' ";
mysql_query($qry);
$qry = "UPDATE import_refrigerators
	SET ref_sub_type = 20
		WHERE
	        import_refrigerators.ft_item_type = 'ILR' ";
mysql_query($qry);
$qry = "UPDATE import_refrigerators
	SET ref_sub_type = 21
		WHERE
	        import_refrigerators.ft_item_type = 'SPR' ";
mysql_query($qry);
$qry = "UPDATE import_refrigerators
	SET ref_sub_type = 23
		WHERE
	        import_refrigerators.ft_item_type = 'URAC' ";
mysql_query($qry);

$qry = "UPDATE import_refrigerators
	SET ref_sub_type = 25
		WHERE
	        import_refrigerators.ft_item_type = 'UREG' ";
mysql_query($qry);
$qry = "UPDATE import_refrigerators
	SET ref_sub_type = 26
		WHERE
	        import_refrigerators.ft_item_type = 'UREK' ";
mysql_query($qry);

$qry = "UPDATE import_refrigerators
	SET ref_sub_type = 26
		WHERE
	        import_refrigerators.ft_item_type = 'UREK' ";
mysql_query($qry);

echo 'Asset sub-types are updated.';
?>
<a href="../coldBox">Next</a>
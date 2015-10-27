<?php

include('../config.php');

$qry = "UPDATE 
	import_refrigerators SET fi_cfc_free = 0
WHERE fi_cfc_free = ''";
mysql_query($qry);

// Add fields in the table
mysql_query("ALTER TABLE `import_refrigerators` ADD `MakeID` INT NOT NULL");
mysql_query("ALTER TABLE `import_refrigerators` ADD `ModelID` TINYINT NOT NULL");
mysql_query("ALTER TABLE `import_refrigerators` ADD `ref_sub_type` TINYINT NOT NULL");



echo 'Table is altered. <a href="update_make.php">Next</a>';

<?php
include('../config.php');

// Add fields in the table
mysql_query("ALTER TABLE `import_cold_room` ADD `MakeID` INT NOT NULL");
mysql_query("ALTER TABLE `import_cold_room` ADD `asset_sub_type` INT NOT NULL");
mysql_query("ALTER TABLE `import_cold_room` ADD `temp_record_type` INT NOT NULL");
mysql_query("ALTER TABLE `import_cold_room` ADD `gas_type` INT NOT NULL");
mysql_query("ALTER TABLE `import_cold_room` ADD `backup_generator` INT NOT NULL");


echo 'Table is altered. <a href="update_make.php">Continue</a>';

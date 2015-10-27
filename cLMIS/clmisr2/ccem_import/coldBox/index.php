<?php
include('../config.php');

// Add fields in the table
mysql_query("ALTER TABLE `import_cold_boxes` ADD `MakeID` INT NOT NULL");
mysql_query("ALTER TABLE `import_cold_boxes` ADD `box_type` INT NOT NULL");

echo 'Table is altered. <a href="update_make.php">Next</a>';
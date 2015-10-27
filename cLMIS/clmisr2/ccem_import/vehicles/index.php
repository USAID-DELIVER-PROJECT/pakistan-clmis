<?php
include('../config.php');
$table = "import_transport";

// Add fields in the table
mysql_query("ALTER TABLE $table ADD `MakeID` INT NOT NULL");
mysql_query("ALTER TABLE $table ADD `transport_type` INT NOT NULL");
mysql_query("ALTER TABLE $table ADD `fuel_type` INT NOT NULL");

echo 'Table is altered. <a href="update_make.php">Next</a>';
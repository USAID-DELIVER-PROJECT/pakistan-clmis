<?php
include('../config.php');

// Add fields in the table
mysql_query("ALTER TABLE import_regulator ADD `MakeID` INT NOT NULL");

echo 'Table is altered. <a href="update_make.php">Next</a>';
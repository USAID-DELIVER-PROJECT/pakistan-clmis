<?php
include('../config.php');
$table = "import_transport";

// Add fields in the table
mysql_query("ALTER TABLE `import_generators` ADD `MakeID` INT NOT NULL");
mysql_query("ALTER TABLE `import_generators` ADD `energy_source` INT NOT NULL");


echo 'Table is altered. <a href="update_make.php">Next</a>';

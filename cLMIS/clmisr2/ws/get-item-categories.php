<?php
include_once("DBCon.php");          // Include Database Connection File
include('auth.php');


$rows = array('pk_id'=>'2','item_category_name'=>'Contraceptives');

print json_encode($rows);

// example: http://localhost/lmis/ws/locations.php?ID=4
?>
<?php
// specify connection info
$connect = mysql_connect('localhost','root','');
if (!$connect)
{
   die('Could not <span id="IL_AD1" class="IL_AD">
    connect to</span> MySQL: ' . mysql_error());
}

$cid =mysql_select_db('clmisr2',$connect); //specify db name

define('CSV_PATH','C:/wamp/www/clmisr2/data/'); // specify CSV file path

$csv_file = CSV_PATH . "muzaffargarh.csv"; // Name of your CSV file
$csvfile = fopen($csv_file, 'r');
$theData = fgets($csvfile);
$i = 0;
while (!feof($csvfile))
{

   $csv_data[] = fgets($csvfile, 1024);
   $csv_array = explode(",", $csv_data[$i]);
   $insert_csv = array();
   $insert_csv['warehouse_name'] = $csv_array[0];
   $insert_csv['dist_id'] = $csv_array[1];
   $insert_csv['prov_id'] = $csv_array[2];

   $insert_csv['stkid'] = $csv_array[3];

   $insert_csv['locid'] = $csv_array[4];

   $insert_csv['stkofficeid'] = $csv_array[5];


   $query = "INSERT INTO tbl_warehouse(wh_id,wh_name,dist_id,prov_id,stkid,locid,stkofficeid)
     VALUES('','".$insert_csv['warehouse_name']."','".$insert_csv['dist_id']."','".$insert_csv['prov_id']."','".$insert_csv['stkid']."' ,'".$insert_csv['locid']."' ,'".$insert_csv['stkofficeid'] ."')";
   $n=mysql_query($query, $connect ) or die(mysql_error());
   	$wh_user_qry="SELECT
	wh_user.sysusrrec_id
	FROM
	wh_user
	INNER JOIN tbl_warehouse ON tbl_warehouse.wh_id = wh_user.wh_id
	WHERE
	tbl_warehouse.dist_id = $csv_array[1] AND
	tbl_warehouse.stkofficeid = 17
		";
   
   $i++;
}
fclose($csvfile);
echo "File data successfully imported to database!!";
mysql_close($connect); // closing connection
?>
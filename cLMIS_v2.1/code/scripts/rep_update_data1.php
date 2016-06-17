<?php
include("../application/includes/classes/Configuration.inc.php");
include("../application/includes/classes/db.php");


$startDate = '2015-01-01';
$endDate = '2015-12-01';
$user = 2014;
$wh_id = 123;
$itm_id = 1;
$itmrec_id = 'IT-001';

$begin = new DateTime( $startDate );
$end = new DateTime( $endDate );
$diff = $begin->diff($end);
$interval = DateInterval::createFromDateString('1 month');
$period = new DatePeriod($begin, $interval, $end);
foreach ( $period as $date )
{
	$month = $date->format('m');
	$year = $date->format('Y');
	$updateQry = "SELECT REPUpdateData($month, $year, $itm_id, $wh_id, $user, '$itmrec_id')";
	mysql_query($updateQry);
}
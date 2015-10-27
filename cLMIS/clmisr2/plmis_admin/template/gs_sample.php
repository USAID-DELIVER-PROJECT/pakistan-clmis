<?php
session_start();
include_once("../../plmis_inc/common/CnnDb.php"); // Include Database Connection File

include_once("../php-excel/xlsxwriter.class.php");
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);

$filename = "GS.xlsx";
header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate');
header('Pragma: public');

$header = array(
    'Store Name'=>'string',
    'Store Code'=>'string',
    'Product ID'=>'string',
    'Opening Balance'=>'integer',
    'Received'=>'integer',
    'Adjustment + '=>'double',
    'Adjustment - '=>'double',
    'Sale'=>'integer',
    'Closing Balance '=>'double'
);

$qry = "SELECT DISTINCT
			tbl_warehouse.wh_name,
			tbl_warehouse.wh_id,
			itminfo_tab.itmrec_id,
			'' AS opening_balance,
			'' AS receive,
			'' AS `adjustment+`,
			'' AS `adjustment-`,
			'' AS sale,
			'' AS closing_balance
		FROM
			tbl_warehouse
		INNER JOIN wh_user ON tbl_warehouse.wh_id = wh_user.wh_id
		INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid,
		 itminfo_tab
		INNER JOIN stakeholder_item ON itminfo_tab.itm_id = stakeholder_item.stk_item
		WHERE
			tbl_warehouse.stkid = ".$_SESSION['userdata'][7]."
		AND stakeholder.lvl = 4
		AND stakeholder_item.stkid = ".$_SESSION['userdata'][7]."
		ORDER BY
			tbl_warehouse.wh_id ASC,
			itminfo_tab.frmindex ASC";
$rows = mysql_query($qry);
$data = array();
while( $row = mysql_fetch_row($rows) )
{
	$data[] = $row;
}
/*$data2 = array(
    array('2003','01','343.12'),
    array('2003','02','345.12'),
);*/

$writer = new XLSXWriter();
$writer->setAuthor('LMIS');
$writer->writeSheet($data,'Sheet1',$header);
//$writer->writeSheet($data2,'Sheet2');
$writer->writeToStdOut();
//$writer->writeToFile('example.xlsx');
//echo $writer->writeToString();
exit(0);
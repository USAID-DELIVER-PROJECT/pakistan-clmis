<?php
session_start();
include_once("../plmis_inc/common/CnnDb.php"); // Include Database Connection File

if ( $_POST['submit'] )
{
	$error = '';
	$stakeholder = $_SESSION['userdata'][7];
	$month = mysql_real_escape_string($_POST['month']);
	$year = mysql_real_escape_string($_POST['year']);
	
	// File upload
	$info = pathinfo($_FILES['data_file']['name']);
	$extension = $info['extension'];
	$fileName = md5(uniqid()) . '.' . $extension;
	$uploadDir = './';
	move_uploaded_file($_FILES['data_file']['tmp_name'], $uploadDir . $fileName);
	
	
	/**
	* XLS parsing uses php-excel-reader from http://code.google.com/p/php-excel-reader/
	*/
	header('Content-Type: text/plain');
	
	$Filepath = $fileName;
	// Excel reader from http://code.google.com/p/php-excel-reader/
	require('php-excel/excel_reader2.php');
	require('php-excel/SpreadsheetReader.php');
	
	date_default_timezone_set('UTC');
	
	try
	{
		$Spreadsheet = new SpreadsheetReader($Filepath);
		$Sheets = $Spreadsheet -> Sheets();
		
		foreach ($Sheets as $Index => $Name)
		{
			$Time = microtime(true);
			$Spreadsheet -> ChangeSheet($Index);
	
			foreach ($Spreadsheet as $Key => $Row)
			{
				if ( $Key == 0 ) // Check if headers are correct
				{
					if (
						$Row[0] != 'Store Name' || 
						$Row[1] != 'Store Code' || 
						$Row[2] != 'Product ID' || 
						$Row[3] != 'Opening Balance' ||
						$Row[4] != 'Received' || 
						$Row[5] != 'Adjustment +' ||
						$Row[6] != 'Adjustment -' ||
						$Row[7] != 'Sale' ||
						$Row[8] != 'Closing Balance')
					{
						$error = "Data is not correctly formatted. <br />";
						break;
					}
				}
				else // If correct data then read it
				{
					$cb = $Row[3] + $Row[4] + $Row[5] - $Row[6] - $Row[7];
					if ( $cb == $Row[8] )
					{
						$dataArr[$Row[1]][$Row[2]]['ob'] = $Row[3];
						$dataArr[$Row[1]][$Row[2]]['rcv'] = $Row[4];
						$dataArr[$Row[1]][$Row[2]]['adjust+'] = $Row[5];
						$dataArr[$Row[1]][$Row[2]]['adjust-'] = $Row[6];
						$dataArr[$Row[1]][$Row[2]]['issue'] = $Row[7];
						$dataArr[$Row[1]][$Row[2]]['cb'] = $Row[8];
					}
					else
					{
						$error .= 'In-correct closing balance at line ' . $Key . '. By formula it must be <b>' .$cb . '</b><br />';
					}
				}
			}
		}
		
		if ( empty($error) )
		{
			foreach( $dataArr as $whId => $whData )
			{
				foreach( $whData as $itemId => $data )
				{
					if (!empty($data['ob']) || !empty($data['rcv']))
					{
						// Check if data already exists
						$qry = "SELECT
									tbl_wh_data.w_id
								FROM
									tbl_wh_data
								WHERE
									tbl_wh_data.report_month = $month
								AND tbl_wh_data.report_year = $year
								AND tbl_wh_data.item_id = '$itemId'
								AND tbl_wh_data.wh_id = $whId ";
						if (mysql_num_rows(mysql_query($qry)) > 0) // If data exists then delete the old data
						{
							$qry = "DELETE
								FROM
									tbl_wh_data
								WHERE
									tbl_wh_data.report_month = $month
								AND tbl_wh_data.report_year = $year
								AND tbl_wh_data.item_id = '$itemId'
								AND tbl_wh_data.wh_id = $whId ";
							mysql_query($qry);
						}
						// Insert data
						$qry = "INSERT INTO tbl_wh_data
							SET
								tbl_wh_data.report_month = $month,
								tbl_wh_data.report_year = $year,
								tbl_wh_data.item_id = '$itemId',
								tbl_wh_data.wh_id = '$whId',
								tbl_wh_data.wh_obl_a = '".$data['ob']."',
								tbl_wh_data.wh_received = '".$data['rcv']."',
								tbl_wh_data.wh_issue_up = '".$data['issue']."',
								tbl_wh_data.wh_cbl_a = '".$data['cb']."',
								tbl_wh_data.wh_adja = '".$data['adjust+']."',
								tbl_wh_data.wh_adjb = '".$data['adjust-']."',
								tbl_wh_data.RptDate = '".$year.'-'.$month."-01',
								tbl_wh_data.add_date = '".date('Y-m-d H:i:s')."',
								tbl_wh_data.last_update = '".date('Y-m-d H:i:s')."',
								tbl_wh_data.ip_address = '".$_SERVER['REMOTE_ADDR']."',
								tbl_wh_data.created_by = '".$_SESSION['userdata'][0]."'";
						mysql_query($qry);
					}
				}
			}
			
			// Delete the fiile 
			@unlink('./'.$fileName);
			$_SESSION['msg'] = 'Data imported successfully.';
			header("Location: import.php");
			exit;
		}
		else
		{
			// Delete the fiile 
			@unlink('./'.$fileName);
			$_SESSION['error'] = $error;
			header("Location: import.php");
			exit;
		}
		
	}
	catch (Exception $E)
	{
		//echo $E -> getMessage();
		$_SESSION['error'] = 'There is an error with your request. Please try re-importing the data.';
		header("Location: import.php");
		exit;
	}
}

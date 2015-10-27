<?php
include("Includes/AllClassesUnSecure.php");
session_start();

if (isset($_REQUEST['usrlogin_id']))
{
	$val = mysql_real_escape_string($_REQUEST['usrlogin_id']);
	$qry = "SELECT
				COUNT(sysuser_tab.usrlogin_id) AS num
			FROM
				sysuser_tab
			WHERE
				sysuser_tab.usrlogin_id = '$val'";
	$qryRes = mysql_fetch_array(mysql_query($qry));
	if ( $qryRes['num'] == 1 )
	{
		$isValid = 'false';
	}
	else
	{
		$isValid = 'true';
	}
	echo $isValid;
}

if (isset($_REQUEST['login']))
{
	$val = mysql_real_escape_string($_REQUEST['login']);
	$qry = "SELECT
				COUNT(sysuser_tab.usrlogin_id) AS num
			FROM
				sysuser_tab
			WHERE
				sysuser_tab.usrlogin_id = '$val'";
	$qryRes = mysql_fetch_array(mysql_query($qry));
	if ( $qryRes['num'] == 1 )
	{
		$isValid = 'false';
	}
	else
	{
		$isValid = 'true';
	}
	echo $isValid;
}

if (isset($_REQUEST['txtStkName']))
{
	$val = mysql_real_escape_string($_REQUEST['txtStkName']);
	$where = '';
	if (isset($_SESSION['pk_id']))
	{
		$where = " AND stakeholder.stkid != '".$_SESSION['pk_id']."'";
	}
	$qry = "SELECT
				COUNT(stakeholder.stkid) AS num
			FROM
				stakeholder
			WHERE
				stakeholder.stkname = '$val'
			$where ";
	$qryRes = mysql_fetch_array(mysql_query($qry));
	if ( $qryRes['num'] == 1 )
	{
		$isValid = 'false';
	}
	else
	{
		$isValid = 'true';
	}
	echo $isValid;
}
if (isset($_REQUEST['txtStktype']))
{
	$val = mysql_real_escape_string($_REQUEST['txtStktype']);
	$where = '';
	if (isset($_SESSION['pk_id']))
	{
		$where = " AND stakeholder.stkid != '".$_SESSION['pk_id']."'";
	}
	$qry = "SELECT
				COUNT(stakeholder.stkid) AS num
			FROM
				stakeholder
			WHERE
				stakeholder.stkname = '$val'
			$where ";
	$qryRes = mysql_fetch_array(mysql_query($qry));
	if ( $qryRes['num'] == 1 )
	{
		$isValid = 'false';
	}
	else
	{
		$isValid = 'true';
	}
	echo $isValid;
}
if (isset($_REQUEST['txtStkName1']))
{
	$val = mysql_real_escape_string($_REQUEST['txtStkName1']);
	$where = '';
	if (isset($_SESSION['pk_id']))
	{
		$where = " AND itminfo_tab.itm_id != '".$_SESSION['pk_id']."'";
	}
	$qry = "SELECT
				COUNT(itminfo_tab.itm_id) AS num
			FROM
				itminfo_tab
			WHERE
				itminfo_tab.itm_name = '$val'
			$where ";
	$qryRes = mysql_fetch_array(mysql_query($qry));
	if ( $qryRes['num'] == 1 )
	{
		$isValid = 'false';
	}
	else
	{
		$isValid = 'true';
	}
	echo $isValid;
}
if (isset($_REQUEST['ItemGroupName']))
{
	$val = mysql_real_escape_string($_REQUEST['ItemGroupName']);
	$where = '';
	if (isset($_SESSION['pk_id']))
	{
		$where = " AND itemgroups.PKItemGroupID != '".$_SESSION['pk_id']."'";
	}
	$qry = "SELECT
				COUNT(itemgroups.PKItemGroupID) AS num
			FROM
				itemgroups
			WHERE
				itemgroups.ItemGroupName = '$val'
			$where ";
	$qryRes = mysql_fetch_array(mysql_query($qry));
	if ( $qryRes['num'] == 1 )
	{
		$isValid = 'false';
	}
	else
	{
		$isValid = 'true';
	}
	echo $isValid;
}
if (isset($_REQUEST['productcategory']))
{
	$val = mysql_real_escape_string($_REQUEST['productcategory']);
	$where = '';
	if (isset($_SESSION['pk_id']))
	{
		$where = " AND tbl_product_category.PKItemCategoryID != '".$_SESSION['pk_id']."'";
	}
	$qry = "SELECT
				COUNT(tbl_product_category.PKItemCategoryID) AS num
			FROM
				tbl_product_category
			WHERE
				tbl_product_category.ItemCategoryName = '$val'
			$where ";
	$qryRes = mysql_fetch_array(mysql_query($qry));
	if ( $qryRes['num'] == 1 )
	{
		$isValid = 'false';
	}
	else
	{
		$isValid = 'true';
	}
	echo $isValid;
}
if (isset($_REQUEST['productstatus']))
{
	$val = mysql_real_escape_string($_REQUEST['productstatus']);
	$where = '';
	if (isset($_SESSION['pk_id']))
	{
		$where = " AND tbl_product_status.PKItemStatusID != '".$_SESSION['pk_id']."'";
	}
	$qry = "SELECT
				COUNT(tbl_product_status.PKItemStatusID) AS num
			FROM
				tbl_product_status
			WHERE
				tbl_product_status.ItemStatusName = '$val'
			$where ";
	$qryRes = mysql_fetch_array(mysql_query($qry));
	if ( $qryRes['num'] == 1 )
	{
		$isValid = 'false';
	}
	else
	{
		$isValid = 'true';
	}
	echo $isValid;
}
if (isset($_REQUEST['producttype']))
{
	$val = mysql_real_escape_string($_REQUEST['producttype']);
	$where = '';
	if (isset($_SESSION['pk_id']))
	{
		$where = " AND tbl_product_type.PKItemTypeID != '".$_SESSION['pk_id']."'";
	}
	$qry = "SELECT
				COUNT(tbl_product_type.PKItemTypeID) AS num
			FROM
				tbl_product_type
			WHERE
				tbl_product_type.ItemTypeName = '$val'
			$where ";
	$qryRes = mysql_fetch_array(mysql_query($qry));
	if ( $qryRes['num'] == 1 )
	{
		$isValid = 'false';
	}
	else
	{
		$isValid = 'true';
	}
	echo $isValid;
}
if (isset($_REQUEST['loc_name']))
{
	$val = mysql_real_escape_string($_REQUEST['loc_name']);
	$where = '';
	if (isset($_SESSION['pk_id']))
	{
		$where = " AND tbl_locations.PkLocID != '".$_SESSION['pk_id']."'";
	}
	$qry = "SELECT
				COUNT(tbl_locations.PkLocID) AS num
			FROM
				tbl_locations
			WHERE
				tbl_locations.LocName = '$val'
			$where ";
	$qryRes = mysql_fetch_array(mysql_query($qry));
	if ( $qryRes['num'] == 1 )
	{
		$isValid = 'false';
	}
	else
	{
		$isValid = 'true';
	}
	echo $isValid;
}
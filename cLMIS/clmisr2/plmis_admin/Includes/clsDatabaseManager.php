<?php
/********************************************************************************
* Date 30-01-2006																*
* owner DIGITALSPINNERS 														*
* Address House No 13, Street 48, F-7/4, Islamabad, Pakistan					*
* Phone No. +9251 2274909,2872546,2874983 Fax:2879134							*
* Author: Yasir Abbasi (Project Manager)											*
* version: 1.0.0.0																*
*********************************************************************************/

//---------------------  START CLASS FOR Database Manager -------------------------
class clsDatabaseManager
{
	var $strTableName;
	var $strColumns;
	var $strWhere;
	var $strOrderBy;
	var $strGroupBy;
	var $nLimit;
	var $nLimitEnd;
	
	/**
	 * Used to select a table given columns, table name, and where clause.
	 * 
	 * @param strTableName
	 * @param strColumns    Columns which need to be selected, by default strColumns='*'
	 * @param strWhere   Where clause, default is null
	 */
	function SelectTable($strTableName, $strColumns=' * ', $strWhere='',$strOrderBy='', $strGroupBy='' ,$nLimit="",$nLimitEnd="")	
	{
		if ($strWhere != '')
			$strQry = 'SELECT DISTINCT '.$strColumns.' FROM '.$strTableName.' WHERE '.$strWhere;
		else
			$strQry = 'SELECT DISTINCT '.$strColumns.' FROM '.$strTableName;
	
		if ($strGroupBy != '')
			$strQry .= ' Group BY '.$strGroupBy;

		if ($strOrderBy != '')
			$strQry .= ' ORDER BY '.$strOrderBy;
	 
		if ($nLimitEnd != "")
			$strQry .= ' LIMIT '.$nLimit.','.$nLimitEnd;
		elseif ($nLimit != "")
			$strQry .= ' LIMIT '.$nLimit;
		//echo "||||".$strWhere."||||";
		//echo $strQry."<br><hr>";
		//exit; 
		
		$result = mysql_query($strQry) or die("Unable to select, Error: ".mysql_error());
		return $result;
	}
	function SelectTable1($strTableName, $strColumns=' * ', $strWhere='',$strOrderBy='', $strGroupBy='' ,$nLimit="",$nLimitEnd="")	
	{
		if ($strWhere != '')
			$strQry = 'SELECT DISTINCT '.$strColumns.' FROM '.$strTableName.' WHERE '.$strWhere;
		else
			$strQry = 'SELECT DISTINCT '.$strColumns.' FROM '.$strTableName;
	
		if ($strGroupBy != '')
			$strQry .= ' Group BY '.$strGroupBy;

		if ($strOrderBy != '')
			$strQry .= ' ORDER BY '.$strOrderBy;
	 
		if ($nLimitEnd != "")
			$strQry .= ' LIMIT '.$nLimit.','.$nLimitEnd;
		elseif ($nLimit != "")
			$strQry .= ' LIMIT '.$nLimit;
		echo "||||".$strQry."||||";
		//echo $strQry."<br><hr>";
		//exit; 
		
		$result = mysql_query($strQry) or die("Unable to select, Error: ".mysql_error());
		return $result;
	}
	/**
	 * Used to delete a row from table.
	 * 
	 * @param strTableName
	 * @param strWhere   Where clause, default is null
	 */
	function DeleteTable($strTableName, $strWhere='')	//return number of rows affected
	{
		if ($strWhere == '')
			die('Cannot delete all rows in the table');

		$strQry = 'DELETE FROM '.$strTableName.' WHERE '.$strWhere;
		//echo $strQry;
		//exit;
		mysql_query($strQry) or die("Unable to delete, Error: ".mysql_error());
		
		return mysql_affected_rows();
	}

	/**
	 * Used to delete a row from table.
	 * 
	 * @param strTableName
	 * @param strWhere   Where clause, default is null
	 */
	function DeleteAllTable($strTableName)	//return number of rows affected
	{
		$strQry = 'DELETE FROM '.$strTableName;
		//echo $strQry;
		//exit;
		mysql_query($strQry) or die("Unable to delete, Error: ".mysql_error());
		
		return mysql_affected_rows();
	}

	/**
	 * Used to update a table.
	 * 
	 * @param strTableName
	 * @param strUpdateData  
	 * @param strWhere   Where clause, default is null
	 */
	function UpdateTable($strTableName, $strUpdateData='',$strWhere='')	//returns number of rows affected
	{
		if ($strWhere == '')
			die('Cannot update all rows in the table');
		if ($strUpdateData == '')
			die('What to update?');
		$strQry = 'UPDATE '.$strTableName.' SET '.$strUpdateData.' WHERE '.$strWhere;
		//echo $strQry."<br><hr>";exit;
		mysql_query($strQry) or die("Unable to update, Error: ".mysql_error());
		return mysql_affected_rows();
	}
	/**
	 * Used to insert a row into table.
	 * 
	 * @param strTableName
	 * @param strColumns  
	 * @param strValues
	 */
	function InsertTable($strTableName, $strColumns='',$strValues='')	//returns number of rows affected
	{
		if ($strValues == '')
			die('What to insert?');
		if ($strColumns == '')
			$strQry = 'INSERT INTO '.$strTableName.' VALUES('.$strValues.')';
		else
			$strQry = 'INSERT INTO '.$strTableName.'('.$strColumns.') VALUES('.$strValues.')';
		//echo $strQry."<br><hr>"; exit;
   		mysql_query($strQry) or die("Unable to insert, Error: ".mysql_error());
		return mysql_insert_id();
	}
	function InsertTable1($strTableName, $strColumns='',$strValues='')	//returns number of rows affected
	{
		if ($strValues == '')
			die('What to insert?');
		if ($strColumns == '')
			$strQry = 'INSERT INTO '.$strTableName.' VALUES('.$strValues.')';
		else
			$strQry = 'INSERT INTO '.$strTableName.'('.$strColumns.') VALUES('.$strValues.')';
		echo $strQry."<br><hr>"; exit;
   		mysql_query($strQry) or die("Unable to insert, Error: ".mysql_error());
		return mysql_insert_id();
	}
	
	function InsertSelect($strTableName, $strColumns='',$strQuery='')	//returns number of rows affected
	{
		if ($strQuery == '')
			error('What to insert?');
		if ($strQuery == '')
			error('Nothing to select!');
		if ($strColumns == '')
			$strQry = 'INSERT INTO '.$strTableName.' '.$strQuery;
		else
			$strQry = 'INSERT INTO '.$strTableName.'('.$strColumns.') '.$strQuery;
	
 		/*
		echo "<pre>";
		echo $strQry;
		echo "</pre>";
		*/
		mysql_query($strQry) or die("Unable to insert, Error: ".mysql_error());
		return mysql_affected_rows();
	}
	
	function InsertAutoTable($strTableName, $strColumns='',$strValues='')	//returns autoincremented value
	{
		
		//echo "insert values".$strValues."<br>";
		if ($strValues == '')
			die('What to insert?');
		if ($strColumns == '')
			$strQry = 'INSERT INTO '.$strTableName.' VALUES('.$strValues.')';
		else
			$strQry = 'INSERT INTO '.$strTableName.'('.$strColumns.') VALUES('.$strValues.')';
        //echo "<pre>";
		
		//echo $strQry."<br>"; 
		//exit;
		//echo "</pre>";
   		mysql_query($strQry) or die("Unable to insert auto, Error: ".mysql_error());
		return mysql_insert_id();
	}
	
	function GetTableFields($strTableName)
	{
		$strFields="";
		$strQry = 'SHOW COLUMNS FROM '.$strTableName;
/* 		echo $strQry;
		exit;
 */
 		$rsTableFields=mysql_query($strQry) or die("Unable to select fields Error: ".mysql_error());
		if(mysql_num_rows($rsTableFields)>0)
		{
			while($objTableFields=mysql_fetch_object($rsTableFields))
			{
				$strFields=$strFields.$objTableFields->Field.",";
			}
			$strFields=substr($strFields,0,strlen($strFields)-1);	
			return $strFields;		
		}
		else
			return false;
	}

	function GetTableData($TabelName,$SortField,$SordOrder='ASC')
	{
		$rsSql = mysql_query("SELECT * FROM `".$TabelName."` ORDER BY `".$SortField."` ".$SordOrder."");
		if(mysql_num_rows($rsSql)>0) return $rsSql;
		else return false;
	}

}
?>
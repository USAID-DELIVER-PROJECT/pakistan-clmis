<?php /*?><?php
$act=2;
include("Includes/AllClasses.php");
$strDo = "Add";
$nstkId =0;

$stkname="";
$stkgroupid=0;
$strNewGroupName="";
$stktype=0;
$stkorder=0;
$newRank=0;

if(isset($_REQUEST['Do']) && !empty($_REQUEST['Do']))
{
	$strDo = $_REQUEST['Do'];
}

if(isset($_REQUEST['Id']) && !empty($_REQUEST['Id']))
{
	$nstkId  = $_REQUEST['Id'];
}


if($strDo == "Delete" )
{
	$objstk->m_npkId=$nstkId; 	
	$rsEditCat = $objstk->DeleteStakeholder();
}

if($strDo == "Edit" )
{
	$objstk->m_npkId=$nstkId; 	$rsEditstk = $objstk->GetStakeholdersById();
	if($rsEditstk!=FALSE && mysql_num_rows($rsEditstk)>0)
	{
		$RowEditStk = mysql_fetch_object($rsEditstk);
		$stkname=$RowEditStk->stkname;
		$stkgroupid = $RowEditStk->stkgroupid;
		$stktype = $RowEditStk->stk_type_id;
		$stkorder = $RowEditStk->stkorder;
	}
}

$rsStakeholders = $objstk->GetAllStakeholders();
$rsGroups = $objstk->GetStakeholderGroups();
$rsstktype = $objstkType->GetAllstk_types();
$rsranks=$objstk->GetRanks();

?><?php */?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Manage Passport</title>
<script src="Includes/CalendarControl.js" language="javascript" type="text/javascript"></script>
<link href="Includes/CalendarControl.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.style1 {color: #FF0000}
-->
</style>
</head>
<body style="font-family:Verdana, Geneva, sans-serif; font-size: 0.8em;color:Black;">
<p>
  <?php /*?><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><?php include("header.php");?></td>
  </tr>
  <tr>
    <td><form method="post" action="ManageWaitingDataAction.php">
        <table width="100%" border="1" cellspacing="0" cellpadding="4">
          <tr>
            <td>Product</td>
            <td><select name="lstgroups">
                <?php
    if($rsGroups!=FALSE && mysql_num_rows($rsGroups)>0)
  	{
	  while($RowGroups = mysql_fetch_object($rsGroups))
	  {
	?>
                <option value="<?=$RowGroups->stkgroupid?>" <?php if($RowGroups->stkgroupid==$stkgroupid) echo 'selected="selected"';?>>
                <?=$RowGroups->GroupName?>
                </option>
                <?php
	  }
	}
	?>
              </select></td>
          </tr>
          <tr>
            <td>Month</td>
            <td><select name="lstgroups">
                <?php
    if($rsGroups!=FALSE && mysql_num_rows($rsGroups)>0)
  	{
	  while($RowGroups = mysql_fetch_object($rsGroups))
	  {
	?>
                <option value="<?=$RowGroups->stkgroupid?>" <?php if($RowGroups->stkgroupid==$stkgroupid) echo 'selected="selected"';?>>
                <?=$RowGroups->GroupName?>
                </option>
                <?php
	  }
	}
	?>
              </select>
              &nbsp;&nbsp;</td>
          </tr>
          <tr>
            <td>Year</td>
            <td><select name="lstStktype">
                <?php
    if($rsstktype!=FALSE && mysql_num_rows($rsstktype)>0)
  	{
	  while($Rowstktype = mysql_fetch_object($rsstktype))
	  {
	?>
                <option value="<?=$Rowstktype->stk_type_id?>" <?php if($Rowstktype->stk_type_id==$stktype) echo 'selected="selected"';?>>
                <?=$Rowstktype->stk_type_descr?>
                </option>
                <?php
	  }
	}
	?>
              </select>
              &nbsp;&nbsp;
              <!-- <a href="" onclick="window.open('ManageCategory.php','','height=600,width=600,scrollbars=yes');return false;">Add Another</a>    -->            </td>
          </tr>
          <tr>
            <td>Warehouse</td>
            <td><select name="lstRank">
                <?php
    if($rsranks!=FALSE && mysql_num_rows($rsranks)>0)
  	{
	  while($Rowranks = mysql_fetch_object($rsranks))
	  {
	?>
                <option value="<?=$Rowranks->stkorder?>" <?php if($Rowranks->stkorder==$stkorder) echo 'selected="selected"';?>>
                <?=$Rowranks->stkorder?>
                </option>
                <?php
	  }
	}
	?>
              </select>
               &nbsp;&nbsp;</td>
          </tr>
		  <tr>
            <td>Opening Balance</td>
            <td><input type="text" name="txtStkName" value="<?=$stkname?>" />
              &nbsp;&nbsp;
              <!-- <a href="" onclick="window.open('ManageCategory.php','','height=600,width=600,scrollbars=yes');return false;">Add Another</a>    -->            </td>
          </tr>
		  <tr>
            <td>Received </td>
            <td><input type="text" name="txtStkName" value="<?=$stkname?>" />
              &nbsp;&nbsp;
              <!-- <a href="" onclick="window.open('ManageCategory.php','','height=600,width=600,scrollbars=yes');return false;">Add Another</a>    -->            </td>
          </tr>
		  <tr>
            <td>Issued </td>
            <td><input type="text" name="txtStkName" value="<?=$stkname?>" />
              &nbsp;&nbsp;
              <!-- <a href="" onclick="window.open('ManageCategory.php','','height=600,width=600,scrollbars=yes');return false;">Add Another</a>    -->            </td>
          </tr><tr>
            <td>Closing Balance </td>
            <td><input type="text" name="txtStkName" value="<?=$stkname?>" />
              &nbsp;&nbsp;
              <!-- <a href="" onclick="window.open('ManageCategory.php','','height=600,width=600,scrollbars=yes');return false;">Add Another</a>    -->            </td>
          </tr><tr>
            <td>Field Open Balance </td>
            <td><input type="text" name="txtStkName" value="<?=$stkname?>" />
              &nbsp;&nbsp;
              <!-- <a href="" onclick="window.open('ManageCategory.php','','height=600,width=600,scrollbars=yes');return false;">Add Another</a>    -->            </td>
          </tr><tr>
            <td>Field Received </td>
            <td><input type="text" name="txtStkName" value="<?=$stkname?>" />
              &nbsp;&nbsp;
              <!-- <a href="" onclick="window.open('ManageCategory.php','','height=600,width=600,scrollbars=yes');return false;">Add Another</a>    -->            </td>
          </tr>
		  <tr>
            <td>Field Issued </td>
            <td><input type="text" name="txtStkName" value="<?=$stkname?>" />
              &nbsp;&nbsp;
              <!-- <a href="" onclick="window.open('ManageCategory.php','','height=600,width=600,scrollbars=yes');return false;">Add Another</a>    -->            </td>
          </tr><tr>
            <td>Field Closing Balance</td>
            <td><input type="text" name="txtStkName" value="<?=$stkname?>" />
              &nbsp;&nbsp;
              <!-- <a href="" onclick="window.open('ManageCategory.php','','height=600,width=600,scrollbars=yes');return false;">Add Another</a>    -->            </td>
          </tr><tr>
            <td>Closing Balance</td>
            <td><input type="text" name="txtStkName" value="<?=$stkname?>" />
              &nbsp;&nbsp;
              <!-- <a href="" onclick="window.open('ManageCategory.php','','height=600,width=600,scrollbars=yes');return false;">Add Another</a>    -->            </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td><input type="hidden" name="hdnstkId" value="<?=$nstkId?>" />
              <input  type="hidden" name="hdnToDo" value="<?=$strDo?>" />
              <input type="submit" value="<?=$strDo?>" />            </td>
          </tr>
          <tr>
            <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="4">
                <tr style="background-color:#CCC">
                  <td>Month</td>
                  <td>Year</td>
                  <td>Product</td>
                  <td>Warehouse</td>
				  <td>Opening Balance</td>
				  <td>Received</td>
				  <td>Issued </td>
				  <td>Closing Balance</td>
				  <td>Field Open Balance</td>
				  <td>Field Received</td>
				  <td>Field Issued </td>
				  <td>Field Closing Balance</td>
				  <td>Closing Balance</td>
                  <td>Options</td>
                </tr>
                <?php
  if($rsStakeholders!=FALSE && mysql_num_rows($rsStakeholders)>0)
  {
	  while($RowStakeholders = mysql_fetch_object($rsStakeholders))
	  {
		
  ?>
                <tr>
                  <td><?=$RowStakeholders ->stkname?></td>
                  <td><?=$RowStakeholders ->GroupName?></td>
                  <td><?=$RowStakeholders ->stk_type_descr?></td>
                  <td><?=$RowStakeholders ->stkorder?></td>
				  <td><?=$RowStakeholders ->stkorder?></td>
				  <td><?=$RowStakeholders ->stkorder?></td>
				  <td><?=$RowStakeholders ->stkorder?></td>
				  <td><?=$RowStakeholders ->stkorder?></td>
				  <td><?=$RowStakeholders ->stkorder?></td>
				  <td><?=$RowStakeholders ->stkorder?></td>
				  <td><?=$RowStakeholders ->stkorder?></td>
				  <td><?=$RowStakeholders ->stkorder?></td>
				  <td><?=$RowStakeholders ->stkorder?></td>
                  <td><a href="<?=$_SERVER['PHP_SELF']?>?Do=Edit&amp;Id=<?=$RowStakeholders->stkid?>">Edit</a>&nbsp;&nbsp;/&nbsp;&nbsp;<a href="<?=$_SERVER['PHP_SELF']?>?Do=Delete&amp;Id=<?=$RowStakeholders->stkid?>">Delete</a></td>
                </tr>
                <?php
	  }
  }
  ?>
              </table></td>
          </tr>
        </table>
      </form></td>
  </tr>
  <tr>
    <td>All Rights Reserved</td>
  </tr>
</table>
<p>&nbsp;</p><?php */?>
</p>
<p>&nbsp;</p>
<h2 align="center" class="style1">This page is under construction</h2>
</body>
</html>

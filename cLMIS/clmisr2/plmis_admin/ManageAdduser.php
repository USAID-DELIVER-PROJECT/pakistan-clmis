<?php
$act=2;
include("Includes/AllClasses.php");
$strDo = "Add";
$nstkId =0;
$userid=0;
$username=0;

if(isset($_REQUEST['Do']) && !empty($_REQUEST['Do']))
{
	$strDo = $_REQUEST['Do'];
}

if(isset($_REQUEST['Id']) && !empty($_REQUEST['Id']))
{
	$nstkId  = $_REQUEST['Id'];
}


//if($strDo == "Delete" )
//{
//	$ItemGroup->m_npkId=$nstkId; 	
//	$rsEditCat = $ItemGroup->DeleteItemGroup();
//}

if($strDo == "Edit" || $strDo == "Delete" )
{
	$ItemGroup->m_npkId=$nstkId; 	
	$rsEditstk = $ItemGroup->GetItemGroupById();
	if($rsEditstk!=FALSE && mysql_num_rows($rsEditstk)>0)
	{
		$RowEditStk = mysql_fetch_object($rsEditstk);
		$ItemGroupName=$RowEditStk->ItemGroupName;
		
	}
}
$ItmGrp = $ItemGroup->GetAllItemGroup();
/*$rsStakeholders = $objstk->GetAllStakeholders();
$rsGroups = $objstk->GetStakeholderGroups();
$rsstktype = $objstkType->GetAllstk_types();
$rsranks=$objstk->GetRanks();*/

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Manage Passport</title>
<script src="Includes/CalendarControl.js" language="javascript" type="text/javascript"></script>
<link href="Includes/CalendarControl.css" rel="stylesheet" type="text/css">
</head>
<body style="font-family:Verdana, Geneva, sans-serif; font-size: 0.8em;color:Black;">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><?php include("header.php");?></td>
  </tr>
  <tr>
    <td><form method="post" action="ManageItemGroupAction.php">
        <table width="100%" border="1" cellspacing="0" cellpadding="4">
		<tr>
            <td>User Name </td>
            <td><input type="text" name="ItemGroupName" value="<?=$ItemGroupName?>" />              
            &nbsp;&nbsp;</td>
	      </tr>
		  

          <tr>
            <td>&nbsp;</td>
            <td><input type="hidden" name="hdnstkId" value="<?=$nstkId?>" />
              <input  type="hidden" name="hdnToDo" value="<?=$strDo?>" />
              <input type="submit" value="<?=$strDo?>" />
			  <input name="btnAdd" type="button" id="btnCancel" value="Cancel" OnClick="window.location='<?=$_SERVER["PHP_SELF"];?>';"></td>
          </tr>
          <tr>
            <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="4">
                <tr style="background-color:#CCC">
                  <td width="28%">Group Name </td>
                  <td width="23%">Options</td>
                </tr>
                <?php
  if($ItmGrp!=FALSE && mysql_num_rows($ItmGrp)>0)
  {
	  while($RowItmGrp = mysql_fetch_object($ItmGrp))
	  {
		
  ?>
                <tr>
                  <td><?=$RowItmGrp->ItemGroupName?></td>
                  <td><a href="<?=$_SERVER['PHP_SELF']?>?Do=Edit&amp;Id=<?=$RowItmGrp->PKItemGroupID?>">Edit</a>&nbsp;&nbsp;/&nbsp;&nbsp;<a href="<?=$_SERVER['PHP_SELF']?>?Do=Delete&amp;Id=<?=$RowItmGrp->PKItemGroupID?>">Delete</a></td>
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
<p>&nbsp;</p>
</body>
</html>

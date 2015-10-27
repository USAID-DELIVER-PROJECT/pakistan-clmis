<?php 
include("Includes/AllClasses.php");
require_once("Includes/clsLogin.php");
include_once("fckeditor/fckeditor.php") ;
$strDo = "Add";
$title="";
$heading="";
$description="";
$stkname="";
$Stkid="";
$province_id="";
$nstkId="";

$isrequired='required=required';

if(isset($_REQUEST['Do']) && !empty($_REQUEST['Do']))
{
	$strDo = $_REQUEST['Do'];
}

if(isset($_REQUEST['Id']) && !empty($_REQUEST['Id']))
{
	$nstkId  = $_REQUEST['Id'];
}

if($strDo == "Edit"){
	
		$isrequired='';

		$sql="SELECT id, title, heading, description,Stkid,province_id,logo,homepage_chk from tbl_cms where id='".$nstkId."'";
		$result_id = mysql_query($sql);
		$row_id=mysql_fetch_array($result_id);
		
		$title=$row_id['title'];
		$heading=$row_id['heading'];
		$description=html_entity_decode($row_id['description']);
		$Stkid=$row_id['Stkid'];
		$province_id=$row_id['province_id'];
		$logo=$row_id['logo'];
		$homepage_check=$row_id['homepage_chk'];
		/*echo $homepage_check;
		exit;*/
 }


$rsStakeholders = $objstk->GetAllStakeholders();

//get all provinces
$objloc->LocLvl=2;
$rsloc=$objloc->GetAllLocations();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Add/Edit Content</title>
<script>
function check(){
	var x=document.forms["content_form"]["page_description"].value;
	alert(x);
	if (x==null || x=="")
	  {
		  alert("First description must be filled out");
		  return false;
	  }
	else { return true; }
}
</script>

</head>
<body style="font-family:Verdana, Geneva, sans-serif; font-size: 0.8em;color:Black;">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td><?php include("header.php");?></td>
      </tr>
      <tr>
        <td>
        	<form id="content_form" name="content_form" method="post" action="ManageContentAction.php" enctype='multipart/form-data' >
                <table width="70%" align="center">
                    <tr>
                        <td width="200"><label>Page Title</label></td>
                        <td><input type="text" name="page_title" id="page_title" value="<?php echo $title; ?>" />
                         </td>
                    </tr>
                    <tr>
                        <td><label>Page Heading</label></td>
                        <td><input type="text" name="page_heading" id="page_heading" value="<?php echo $heading; ?>" /></td>
                    </tr>
                    <tr>
                        <td valign="top"><label>Page Description</label></td>
                        <td>
                        <?php
							$oFCKeditor = new FCKeditor('page_description') ;
							$oFCKeditor->BasePath = 'fckeditor/';
							$oFCKeditor->Height = "400px";
							$oFCKeditor->Width = "80%";
							$oFCKeditor->Value	 = $description;
							$oFCKeditor->ToolbarSet	= 'Default';
							$oFCKeditor->Skin	= 'silver';
							$oFCKeditor->Create();
						?>
                        </td>
                    </tr>
                    <tr>
                    	<td>Stakeholders</td>
                        <td><?=$stkname?>
                        <select name="stakeholders" id="stakeholders">
                              <option value="0">Choose...</option>
                                          <?php
                                if($rsStakeholders!=FALSE && mysql_num_rows($rsStakeholders)>0)
                                {
                                  while($RowGroups = mysql_fetch_object($rsStakeholders))
                                  {
                                ?>
                              <option value="<?=$RowGroups->stkid?>" <?php if($RowGroups->stkid==$Stkid) echo 'selected="selected"';?>>
                              <?php echo $RowGroups->stkname; ?>
                              </option>
                                <?php
                                  }
                                }
                                ?>
                            </select>
            			</td>
                    </tr>
                    <tr>
                    	<td>Provinces</td>
                        <td>  
                        <select name="provinces" id="provinces">
                        	<option value="0">Choose...</option>
                          <?php
                         if($rsloc!=FALSE && mysql_num_rows($rsloc)>0)
                            {
                              while($RowLoc = mysql_fetch_object($rsloc))
                              {
                          ?>
                                <option value="<?=$RowLoc->PkLocID?>" <?php if($RowLoc->PkLocID==$province_id) echo 'selected="selected"';?>>
                                <?php echo $RowLoc->LocName; ?>
                                </option>
                                <?php
                             }
                            }
                            ?>
                          </select>
                        </td>
                    </tr>
                    <tr><td>Top Banner (1002*25)</td>
                    	<td><input type="file" id="logo" name="logo" /></td>
                    </tr>
					    <tr><td><input type="checkbox" name="ishomepage" <?php if ($homepage_check == "1") {echo "checked";} ?>>Is this a home page?</td>

					 </tr>

                    <tr>
                    	<td>
                        <input type="hidden" name="hdnstkId" value="<?=$nstkId?>" />
              			<input  type="hidden" name="hdnToDo" value="<?=$strDo?>" />
             			<input type="submit" value="<?=$strDo?>" /><input name="btnAdd" type="button" id="btnCancel" value="Cancel" onclick="history.go(-1)" >
                        </td>
                        <td>
                        <?php 
                            if(isset($_REQUEST['msg']) && !empty($_REQUEST['msg']))
                                {
                                    print '<p style=\'color:#FF0000\'>Error:'.$_REQUEST['msg']."</p>";
                                }
                        ?>
                       </td>
                    </tr>
                </table>
            </form>
        </td>
    </tr>
    <tr>
        <td>All Rights Reserved</td>
    </tr>
</table>
</body>
</html>
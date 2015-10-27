<?php
include("Includes/AllClasses.php");

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
$id2 = isset($_REQUEST['id2']) ? $_REQUEST['id2'] : '';
$ctype = $_REQUEST['ctype'];
$PkLocID = '';
$wh_id = '';
$stkid = '';
$StkparentID = '';
$lvl_id = '';

if(isset($_REQUEST['id']) && !empty($_REQUEST['id']))
{
	switch($ctype)
	{
		case 1: // Get stakeholders' offices list  *****************************************
		$objstk->m_npkId=$_REQUEST['id'];
		$rsStakeholders=$objstk->GetStakeholdersFamilyById();
		if($rsStakeholders!=FALSE && mysql_num_rows($rsStakeholders)>0)
		{
			//print "<select name='stkofficeid' onchange='showUser(\"getfromajax.php?ctype=2&id=\"+this.value,\"txtProv\");' id='stkofficeid'>";
			print "<option value=''>Select</option>";
			while($RowGroups = mysql_fetch_object($rsStakeholders))
			{
				print "<option value='".$RowGroups->stkid."' ".($RowGroups->stkid==$_SESSION['stkOfficeId']?'selected=selected':"")." >".$RowGroups->stkname;
				print "</option>";
			}
		}
		//print "</select>";
		break;
		
		case 2:  // Get province from stakeholder  *****************************************
		$PkLocID=0;
		$objstk->m_npkId=$_REQUEST['id'];
		$rsStakeholders=$objstk->GetStakeholdersById();
		if($rsStakeholders!=FALSE && mysql_num_rows($rsStakeholders)>0)
		{
			while($RowGroups = mysql_fetch_object($rsStakeholders))
			{
				$lvl=$RowGroups->lvl;
			}
		}
		$objloc->LocLvl=$lvl;
		$rsprov=$objloc->GetAllLocations();
		if($rsprov!=FALSE && mysql_num_rows($rsprov)>0)
		{
		//print "<select name='prov_id' onchange='showUser(\"getfromajax.php?ctype=3&id=\"+this.value,\"txtDist\")'>";
			print "<option value=''>Select</option>";
			while($RowProv = mysql_fetch_object($rsprov))
			{
				print "<option value='".$RowProv->PkLocID.($RowProv->PkLocID==$PkLocID?'selected=selected':"")."' >".$RowProv->LocName;
				print "</option>";
			}
		}
		//print "</select>";
		break;


		case 3:  // Get districts from province *****************************************
		$objloc->ParentID=$id;;
		$rsprov=$objloc->GetAllLocationsfromParent();
		
		
		if($rsprov!=FALSE && mysql_num_rows($rsprov)>0)
		{
			//print "<select name='dist_id' onchange='showWHfromDistrict(\"getfromajax.php?ctype=5&id=\"+this.value,\"txtWH\")'>";
	
			//print "<option value=''>Select....</option>";
			while($RowProv = mysql_fetch_object($rsprov))
			{
				?>
				
			  <input type="checkbox" name="select4[]" id="select4" value='<?php echo $RowProv->PkLocID;?>' <?php  if(in_array($RowProv->PkLocID, $_SESSION['distArr'])){ echo "checked=checked";} ?> onclick="showwarehouse()"> <?php echo $RowProv->LocName;  ?></br>
              <?php
				//print "<option value='".$RowProv->PkLocID.($RowProv->PkLocID==$PkLocID?'selected=selected':"")."' >".$RowProv->LocName;
				//print "</option>";
			}
		}
		//print "</select>";
		break;
		
		case 4:  // Get sub-stakeholders parent at level  *****************************************
		$objstk->m_lvl=$_REQUEST['id'];
		$objstk->m_MainStakeholder=$_REQUEST['id2'];
		
		$rsStakeholders=$objstk->GetAllStakeholdersAtParentlevel();
		//print $rsStakeholders;
		//exit;
		if($rsStakeholders!=FALSE && mysql_num_rows($rsStakeholders)>0)
		{
			//print "<select name='lststkholdersParent'>";
			print "<option value=''>Select</option>";
			while($RowGroups = mysql_fetch_object($rsStakeholders))
			{
				print "<option value='".$RowGroups->stkid."' ".($RowGroups->stkid==$_SESSION['parent_id']?'selected=selected':"")." >".$RowGroups->stkname;
				print "</option>";
			}
		}
		//print "</select>";
		break;

		case 5:  // Get districts from province *****************************************
		
		$objwarehouse->m_dist_id=$id;
		$objwarehouse->m_stkofficeid=$id2;
		
		$rsprov=$objwarehouse->GetWarehouseBylocByStakeholderOffice();
		
		
		if($rsprov!=FALSE && mysql_num_rows($rsprov)>0)
		{

			print "<option value=''  size='5' multiple='multiple'>Select....</option>";
			while($RowProv = mysql_fetch_object($rsprov))
			{
				print "<option value='".$RowProv->wh_id.($RowProv->wh_id==$wh_id?'selected=selected':"")."' >".$RowProv->wh_name;
				print "</option>";
			}
		}
		//else
		//print "<option value='0'>[".$id."][".$id2."]</option>";

		break;
		
		case 6:  // Get districts from province *****************************************
		$id=implode(',',$_REQUEST['id']);
		$objwarehouse->m_dist_id=$id;
		
		$objwarehouse->m_stkid=$id2;
		
		$rsprov=$objwarehouse->GetWarehouseBylocByStakeholder();
		
		
		if($rsprov!=FALSE && mysql_num_rows($rsprov)>0)
		{

			
			while($RowProv = mysql_fetch_object($rsprov))
			{
		?>
        
          <input type="checkbox" name="warehouses[]" id="warehouses" value='<?php echo $RowProv->wh_id ?>' <?php  if(in_array($RowProv->wh_id, $_SESSION['whArr'])){ echo "checked=checked";} ?>> <?php echo $RowProv->wh_name;  ?></br>
        <?php
				//print "<option value='".$RowProv->wh_id.($RowProv->wh_id==$wh_id?'selected=selected':"")."' >".$RowProv->wh_name;
				//print "</option>";
			}
		}
		//else
		//print "<option value='0'>[".$id."][".$id2."]</option>";

		break;
		
		case 7:  // Get province from stakeholder  *****************************************
		$PkLocID=0;
		$objstk->m_npkId=$_REQUEST['id'];
		$rsStakeholders=$objstk->GetStakeholdersById();
		if($rsStakeholders!=FALSE && mysql_num_rows($rsStakeholders)>0)
		{
			while($RowGroups = mysql_fetch_object($rsStakeholders))
			{
				$lvl=$RowGroups->lvl;
			}
		}
		$objlvl->m_npkId=$lvl;
		$rsprov=$objlvl->GetLowerLevels();
		if($rsprov!=FALSE && mysql_num_rows($rsprov)>0)
		{
			echo '<option value="">Select</option>';
			while($RowProv = mysql_fetch_object($rsprov))
			{
				if ($RowProv->lvl_id != 1)
				{
					print "<option value='".$RowProv->lvl_id."' ".($RowProv->lvl_id==$_SESSION['level_id']?'selected=selected':"")." >".$RowProv->lvl_name;
					print "</option>";
				}
			}
		}
		break;
		
			case 8:  // Get districts from province *****************************************
		$objloc->ParentID=$id;
		$rsprov=$objloc->GetAllLocationsfromParent();
		
		
		if($rsprov!=FALSE && mysql_num_rows($rsprov)>0)
		{
			//print "<select name='dist_id' onchange='showWHfromDistrict(\"getfromajax.php?ctype=5&id=\"+this.value,\"txtWH\")'>";
	
			print "<option value=''>Select</option>";
			while($RowProv = mysql_fetch_object($rsprov))
			{
				?>
				
              <?php
				print "<option value='".$RowProv->PkLocID."' ".($RowProv->PkLocID==$_SESSION['dist_id']?'selected=selected':"")." >".$RowProv->LocName;
				print "</option>";
			}
		}
		break;
		
			case 9:  // Get districts from province *****************************************
			//$id contains the location level
		$objManageLocations->TypeLvl=$id;
		$rsprov=$objManageLocations->GetAllLocationsType();
		
		
		if($rsprov!=FALSE && mysql_num_rows($rsprov)>0)
		{
			//print "<select name='dist_id' onchange='showWHfromDistrict(\"getfromajax.php?ctype=5&id=\"+this.value,\"txtWH\")'>";
	
			print "<option value=''>Select</option>";
			while($RowProv = mysql_fetch_object($rsprov))
			{
				?>
				
              <?php
				print "<option value='".$RowProv->LoctypeID."' ".($RowProv->LoctypeID==$_SESSION['loc_type']?'selected=selected':"")." >".$RowProv->LoctypeName;
				print "</option>";
			}
		}
		break;
	}
}
?>

<?php
include("Includes/AllClasses.php");
$strDo = "Add";
$nstkId = 0;
$autorun = false;


if (isset($_REQUEST['add_action']) && !empty($_REQUEST['new_manufacturer'])) {

    $new_manufacturer = $_REQUEST['new_manufacturer'];
    $item_pack_size_id = $_REQUEST['item_pack_size_id'];
	//check manufacturer
    $checkManufacturer=mysql_query("select stkid,stkname from stakeholder where stkname='".$new_manufacturer."' AND stk_type_id=3") or die(mysql_error());
	$manufacturer=mysql_num_rows($checkManufacturer);
	$stkRow=mysql_fetch_assoc($checkManufacturer);
	//if not exist for any product
	if($manufacturer<1)
	{
	$objstk->m_stkname = $new_manufacturer;
    $objstk->m_stkorder = '1';
    $objstk->ParentID = '1';
    $objstk->m_stk_type_id = '3';
    $objstk->m_lvl = '1';

    $stkid = $objstk->AddStakeholder();

	}
	else{
		$stkid=$stkRow['stkid'];
	}
	$getStkItem="select * from stakeholder_item where stk_item=".$item_pack_size_id." AND stk_id=".$stkid;
	$resStkItem=mysql_query($getStkItem) or die(mysql_error());
	$numStkItem=mysql_num_rows($resStkItem);
	if($numStkItem<1)
	{
	$objstakeholderitem->m_stkid = $stkid;
    $objstakeholderitem->m_stk_item = $item_pack_size_id;
    $objstakeholderitem->Addstakeholderitem1();
	}
    $checkManufacturer=mysql_query("select stkid,stkname from stakeholder where stk_type_id=3 order by stkname ASC") or die(mysql_error());
	$manufacturer=mysql_num_rows($checkManufacturer);
	

	echo '<option value="">Select</option>';
    while($val=mysql_fetch_assoc($checkManufacturer)) {
        echo '<option value="' . $val['stkid'] . '">' . $val['stkname'] . '</option>';
    }
}

if (isset($_REQUEST['show'])) {


    //$item_pack_size_id = $_REQUEST['product'];

	$checkManufacturer=mysql_query("select stkid,stkname from stakeholder where stk_type_id=3 order by stkname ASC") or die(mysql_error());
	$manufacturer=mysql_num_rows($checkManufacturer);
	

	echo '<option value="">Select</option>';
    while($val=mysql_fetch_assoc($checkManufacturer)) {
        echo '<option value="' . $val['stkid'] . '">' . $val['stkname'] . '</option>';
    }
}


?>

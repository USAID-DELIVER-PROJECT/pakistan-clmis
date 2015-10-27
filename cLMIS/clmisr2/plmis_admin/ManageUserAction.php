<?php

include("Includes/AllClasses.php");
$strDo = "Add";
$nstkId = 0;
$stkid = 0;
$prov_id = 0;
$dist_id = 0;
$usrlogin_id = "";
$sysusr_pwd = "";
$wh_id = array('');

function deleteFile($dir, $fileName) {
    $handle = opendir($dir);

    while (($file = readdir($handle)) !== false) {
        if ($file == $fileName) {
            @unlink($dir . '/' . $file);
        }
    }
    closedir($handle);
}

if (isset($_REQUEST['hdnstkId']) && !empty($_REQUEST['hdnstkId'])) {
    // in case of edit
    $nstkId = $_REQUEST['hdnstkId'];
}
if (isset($_REQUEST['hdnToDo']) && !empty($_REQUEST['hdnToDo'])) {
    //add or edit
    $strDo = $_REQUEST['hdnToDo'];
}
if (isset($_REQUEST['select']) && !empty($_REQUEST['select'])) {
    // 1 stakeholder
    $stkid = $_REQUEST['select'];
} else {
    $stkid = 0;
}
if (isset($_REQUEST['select3']) && !empty($_REQUEST['select3'])) {
    //province
    $prov_id = $_REQUEST['select3'];
} else {
    $prov_id = 0;
}
if (isset($_REQUEST['select4']) && !empty($_REQUEST['select4'])) {
    //1 district
    $dist_id = $_REQUEST['select4'];
}
if (isset($_REQUEST['warehouses']) && !empty($_REQUEST['warehouses'])) {
    //1 or many warehouses
    $wh_id = $_REQUEST['warehouses'];
}
if (isset($_REQUEST['usrlogin_id']) && !empty($_REQUEST['usrlogin_id'])) {
    //login name
    $usrlogin_id = $_REQUEST['usrlogin_id'];
}
if (isset($_REQUEST['txtStkName2']) && !empty($_REQUEST['txtStkName2'])) {
    //password
    $sysusr_pwd = $_REQUEST['txtStkName2'];
}

if (isset($_REQUEST['full_name']) && !empty($_REQUEST['full_name'])) {
    //full name
    $full_name = $_REQUEST['full_name'];
}

if (isset($_REQUEST['email_id']) && !empty($_REQUEST['email_id'])) {
    //email id
    $email_id = $_REQUEST['email_id'];
}

if (isset($_REQUEST['phone_no']) && !empty($_REQUEST['phone_no'])) {
    //phone no
    $phone_no = $_REQUEST['phone_no'];
}

if (isset($_REQUEST['fax_no']) && !empty($_REQUEST['fax_no'])) {
    //fax no
    $fax_no = $_REQUEST['fax_no'];
}

if (isset($_REQUEST['address']) && !empty($_REQUEST['address'])) {
    //address
    $address = $_REQUEST['address'];
}

if (isset($_REQUEST['sysusr_dept']) && !empty($_REQUEST['sysusr_dept'])) {
    //user department
    $sysusr_dept = $_REQUEST['sysusr_dept'];
}

if (isset($_REQUEST['sysusr_deg']) && !empty($_REQUEST['sysusr_deg'])) {
    //designation code
    $sysusr_deg = $_REQUEST['sysusr_deg'];
}
//user type
$sysusr_type = 'UT-002';

//Filling value in stakeholder objects variables
$objuser->m_npkId = $nstkId;
$objuser->m_stkid = $stkid;
$objuser->m_stkname = $stkname;
$objuser->m_whrec_id = $wh_id[0];
$objuser->m_prov_id = $prov_id;
$objuser->m_dist_id = $dist_id;
$objuser->m_wh_name = $wh_name;
$objuser->m_usrlogin_id = $usrlogin_id;
$objuser->m_sysusr_pwd = $sysusr_pwd;
//adding additional options
$objuser->m_full_name = $full_name;
$objuser->m_email_id = $email_id;
$objuser->m_phone_no = $phone_no;
$objuser->m_fax_no = $fax_no;
$objuser->m_address = $address;
$objuser->m_sysusr_photo = $sysusr_photo;
$objuser->m_sysusr_dept = $sysusr_dept;
$objuser->m_sysusr_deg = $sysusr_deg;
$objuser->m_sysusr_type = $sysusr_type;

if ($strDo == "Edit") {
    $sql = "select sysusr_photo from sysuser_tab where UserID = '" . $nstkId . "'";
    $result = mysql_fetch_array(mysql_query($sql));

    //deleting previous image
    deleteFile('images/', $result['sysusr_photo']);

    $ext = explode('.', $_FILES['sysusr_photo']['name']);
    $sysusrimg = time() . '.' . $ext[1];
    move_uploaded_file($_FILES['sysusr_photo']['tmp_name'], 'images/' . $sysusrimg);
    $objuser->m_sysusr_photo = $sysusrimg;
    //edit user 
    $objuser->EditUser();

    if (!empty($wh_id[0])) { //If user changed the assigned warehouses
        //editing values of warehouses
        //deleting already existing values
        $objwharehouse_user->m_npkId = $nstkId;
        $objwharehouse_user->wh_userdelete();
        foreach ($wh_id as $arec) {
            //save users warehouses
            //print $arec."<br>".$nstkId;.

            $objwharehouse_user->m_wh_id = $arec;
            $objwharehouse_user->m_sysusrrec_id = $nstkId;
            $objwharehouse_user->Addwh_user();
        }
    }
    $_SESSION['err']['text'] = 'Data has been successfully updated.';
    $_SESSION['err']['type'] = 'success';
}

if ($strDo == "Add") {
    $sql = "select usrlogin_id from sysuser_tab where usrlogin_id = '" . $usrlogin_id . "'";
    $result = mysql_query($sql);

    if ($result != FALSE && mysql_num_rows($result) > 0) {
        header("location:ManageUser.php?msg=username+is+not+available");
        exit;
    }

    if (empty($wh_id[0])) { //If warehouses is not assigned to user 
        header("location:ManageUser.php?msg=Warehouse+not+selected");
        exit;
    }

    // Add image
    $ext = explode('.', $_FILES['sysusr_photo']['name']);
    $sysusrimg = time() . '.' . $ext[1];
    move_uploaded_file($_FILES['sysusr_photo']['tmp_name'], 'images/' . $sysusrimg);
    $objuser->m_sysusr_photo = $sysusrimg;


    //save user
    $objuser->AddUser();
    //get user ID 
    $rez = $objuser->GetUserByid();
    if ($rez != FALSE && mysql_num_rows($rez) > 0) {
        while ($Rowobjuser = mysql_fetch_object($rez)) {
            $usrID = $Rowobjuser->UserID;
        }
    }
    foreach ($wh_id as $arec) {
        //save users warehouses		
        $objwharehouse_user->m_wh_id = $arec;
        $objwharehouse_user->m_sysusrrec_id = $usrID;
        $objwharehouse_user->Addwh_user();
    }
    $_SESSION['err']['text'] = 'Data has been successfully added.';
    $_SESSION['err']['type'] = 'success';
}
unset($_SESSION['whArr']);
unset($_SESSION['distArr']);
header("location:ManageUser.php");
exit;
?>
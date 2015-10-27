<?php

require_once("db.php");

$act_page = $_GET['action'];
switch ($act_page) {
    case "update":

        $user_id = $_POST['userId'];
        $address = $_POST['address'];
        $phone = $_POST['phone'];
        $realname = $_POST['realname'];
        $designation = $_POST['designation'];
        $email = $_POST['email'];
        $strSql = "UPDATE sysuser_tab  set sysusr_email='" . $email . "',sysusr_addr='" . $address . "',sysusr_cell='" . $phone . "',sysusr_name='" . $realname . "',sysusr_deg='" . $designation . "' where UserID='" . $user_id . "'";

        $rsSql = mysql_query($strSql);

        header("Location:http://localhost/clmis/plmis_admin/pd/update-user.php?user=$user_id&success=1");
        exit;

        break;
    case "add":

        $wh_id = $_POST['wh_id'];
        $user_id = $_POST['user_id'];
        $ccm_wh_id = $_POST['ccm_wh_id'];

        $strSql = "UPDATE tbl_warehouse  set ccm_wh_id='" . $ccm_wh_id . "'  where wh_id='" . $wh_id . "'";

        $rsSql = mysql_query($strSql);

        exit;

        break;
}
?>
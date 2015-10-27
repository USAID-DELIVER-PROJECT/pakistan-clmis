<?php
include "../html/config.php";
include "template/header.php";

$act = 2;
include("Includes/AllClasses.php");
$strDo = "Add";
$nstkId = 0;
$stkid = 0;
$prov_id = 0;
$dist_id = 0;
$usrlogin_id = "";
$sysusr_pwd = "";
$stkname = "";
$district = "";
$province = "";
$wh_name = "";
$test = 'false';

include("xml/xml_genaration_user.php");

if (!ini_get('register_globals')) {
    $superglobals = array($_GET, $_POST, $_COOKIE, $_SERVER);
    if (isset($_SESSION)) {
        array_unshift($superglobals, $_SESSION);
    }
    foreach ($superglobals as $superglobal) {
        extract($superglobal, EXTR_SKIP);
    }
    ini_set('register_globals', true);
}

$sql = mysql_query("SELECT
sysuser_tab.UserID,
sysuser_tab.stkid,
sysuser_tab.province,
stakeholder.stkname AS stkname,
province.LocName AS provincename
FROM
sysuser_tab
Left Join stakeholder ON sysuser_tab.stkid = stakeholder.stkid
Left Join tbl_locations AS province ON sysuser_tab.province = province.PkLocID
WHERE sysuser_tab.UserID='" . $_SESSION['userid'] . "'");

$sql_row = mysql_fetch_array($sql);
$stakeholder = $sql_row['stkid'];
$provinceidd = $sql_row['province'];
$stkname = $sql_row['stkname'];
$provincename = $sql_row['provincename'];

if ($stakeholder == '-1' && $provinceidd == '-1') {
    $test = 'true'; //checking it as administrator
}

function deleteFile($dir, $fileName) {
    $handle = opendir($dir);

    while (($file = readdir($handle)) !== false) {
        if ($file == $fileName) {
            @unlink($dir . '/' . $file);
        }
    }
    closedir($handle);
}

if (isset($_REQUEST['Do']) && !empty($_REQUEST['Do'])) {
    $strDo = $_REQUEST['Do'];
}

if (isset($_REQUEST['Id']) && !empty($_REQUEST['Id'])) {
    $nstkId = $_REQUEST['Id'];
}


if ($strDo == "Delete") {
    //deleting image from the folder
    $sql = "select sysusr_photo from sysuser_tab where UserID = '" . $nstkId . "'";
    $result = mysql_fetch_array(mysql_query($sql));

    //deleting previous image
    if ($result['sysusr_photo']) {
        deleteFile('images/', $result['sysusr_photo']);
    }

    $objuser->m_npkId = $nstkId;
    $objuser->DeleteUser();

    //deleting from warehouse user table
    $objwharehouse_user->m_sysusrrec_id = $nstkId;
    $objwharehouse_user->Deletewh_userbyuserid();

    $_SESSION['err']['text'] = 'Data has been successfully deleted.';
    $_SESSION['err']['type'] = 'success';

    //header("location:ManageUser.php");
    echo '<script>window.location="ManageUser.php"</script>';
    exit;
}

if ($strDo == "Edit") {
    $objuser->m_npkId = $nstkId;
    $rsuser = $objuser->GetUserByUserID();
    if ($rsuser != FALSE && mysql_num_rows($rsuser) > 0) {
        $RowEditStk = mysql_fetch_object($rsuser);
        $stkid = $RowEditStk->stkid;
        $stkname = $RowEditStk->stkname;
        $province22 = $RowEditStk->province;
        $PkLocID = $RowEditStk->prov_id;
        //$stkOfficeId = $RowEditStk->stkofficeid;
        $district = $RowEditStk->district;
        $dist_id = $RowEditStk->dist_id;
        //retrieving optional values
        $wh_id = $RowEditStk->wh_ids;
        $_SESSION['whArr'] = explode(',', $wh_id);
        $_SESSION['distArr'] = explode(',', $dist_id);

        $wh_name = $RowEditStk->wh_name;
        $usrlogin_id = $RowEditStk->usrlogin_id;
        $sysusr_pwd = $RowEditStk->sysusr_pwd;
        $sysusr_name = $RowEditStk->sysusr_name;
        $sysusr_email = $RowEditStk->sysusr_email;
        $sysusr_ph = $RowEditStk->sysusr_ph;
        $sysusr_fax = $RowEditStk->sysusr_cell;
        $sysusr_addr = $RowEditStk->sysusr_addr;
        $sysusr_dept = $RowEditStk->sysusr_dept;
        $sysusr_deg = $RowEditStk->sysusr_deg;
        //retrieving user id
        $sysusr_UserID = $RowEditStk->UserID;
        //retrieving warehouse name
    }
}

$objuser->m_stkid = $stakeholder;
$objuser->m_provid = $provinceidd;
$objuser1 = $objuser->GetAllUser1();

$rsStakeholders = $objstk->GetAllStakeholders();
$objloc->LocLvl = 2;
$rsloc = $objloc->GetAllLocations();

//////////// GET FILE NAME FROM THE URL

$arr = explode("?", basename($_SERVER['REQUEST_URI']));
$basename = $arr[0];
$filePath = "plmis_src/operations/" . $basename;

//////// GET Read Me Title From DB. 
$qryResult = mysql_fetch_array(mysql_query("select extra from sysmenusub_tab where menu_filepath = '" . $filePath . "' and active = 1"));
$readMeTitle = $qryResult['extra'];
?>
</head>

<body class="page-header-fixed page-quick-sidebar-over-content" onLoad="doInitGrid()">
    <!-- BEGIN HEADER -->
    <div class="page-container">
        <?php include "template/menu.php"; ?>

        <div class="page-content-wrapper">
            <div class="page-content">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="page-title row-br-b-wp">User Management</h3>
                        <div class="widget" data-toggle="collapse-widget">
                            <div class="widget-head">
                                <h3 class="heading"><?php echo $strDo; ?> User</h3>
                            </div>
                            <div class="widget-body">
                                <form method="post" action="ManageUserAction.php" name="manageuser" id="manageuser" enctype='multipart/form-data'>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-3">
                                                <div class="control-group">
                                                    <label>Stakeholder<font color="#FF0000">*</font></label>
                                                    <div class="controls">
                                                        <select name="select" id="Stakeholders" class="form-control input-medium">
                                                            <option value="">Select</option>
                                                            <?php
                                                            if ($rsStakeholders != FALSE && mysql_num_rows($rsStakeholders) > 0) {
                                                                while ($RowGroups = mysql_fetch_object($rsStakeholders)) {
                                                                    ?>
                                                                    <option value="<?= $RowGroups->stkid ?>" <?php if ($RowGroups->stkid == $stkid) echo 'selected="selected"'; ?>> <?php echo $RowGroups->stkname; ?> </option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="control-group">
                                                    <label>Province<font color="#FF0000">*</font></label>
                                                    <div class="controls">
                                                        <select name="select3" id="Provinces" class="form-control input-medium">
                                                            <option value="">Select</option>
                                                            <?php
                                                            if ($rsloc != FALSE && mysql_num_rows($rsloc) > 0) {
                                                                while ($RowLoc = mysql_fetch_object($rsloc)) {
                                                                    ?>
                                                                    <option value="<?= $RowLoc->PkLocID ?>" <?php if ($RowLoc->PkLocID == $PkLocID) echo 'selected="selected"'; ?>> <?php echo $RowLoc->LocName; ?> </option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="control-group">
                                                    <label>District<font color="#FF0000">*</font></label>
                                                    <div class="controls">
                                                        <div style="height:100px;overflow:scroll;" id="districts"> </div>
                                                        <label for="select4[]" style="display:none" class="error">Select district</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="control-group">
                                                    <label>Warehouse<font color="#FF0000">*</font></label>
                                                    <div class="controls">
                                                        <div style="height:100px;overflow:scroll;" id="Warehouses1"></div>
                                                        <label for="warehouses[]" style="display:none" class="error">Select at least 1 warehouse</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if (!isset($_REQUEST['Do'])) { ?>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="col-md-3">
                                                    <div class="control-group">
                                                        <label>Login ID<font color="#FF0000">*</font></label>
                                                        <div class="controls">
                                                            <input autocomplete="off" type="text" name="usrlogin_id" value="<?= $usrlogin_id ?>" id='usrlogin_id' class="form-control input-medium">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="control-group">
                                                        <label>Password<font color="#FF0000">*</font></label>
                                                        <div class="controls">
                                                            <input type="password" name="txtStkName2" id="txtStkName2" class="form-control input-medium" value="<?php echo $sysusr_pwd; ?>" />
                                                        </div>
                                                    </div>
                                                </div> 
                                                <div class="col-md-3">
                                                    <div class="control-group">
                                                        <label>Confirm Password<font color="#FF0000">*</font></label>
                                                        <div class="controls">
                                                            <input type="password" name="txtStkName22" class="form-control input-medium" value="<?php echo $sysusr_pwd; ?>" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-3">
                                                <div class="control-group">
                                                    <label>Full Name<font color="#FF0000">*</font></label>
                                                    <div class="controls">
                                                        <input type="text" name="full_name" id='full_name' value="<?= $sysusr_name; ?>" class="form-control input-medium">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="control-group">
                                                    <label>Email<font color="#FF0000">*</font></label>
                                                    <div class="controls">
                                                        <input type="text" name="email_id" value="<?= $sysusr_email; ?>" id='email_id' class="form-control input-medium">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="control-group">
                                                    <label>Phone/Cell No<font color="#FF0000">*</font></label>
                                                    <div class="controls">
                                                        <input type="text" name="phone_no" value="<?= $sysusr_ph; ?>" id='phone_no' class="form-control input-medium">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="control-group">
                                                    <label>Fax No.</label>
                                                    <div class="controls">
                                                        <input type="text" name="fax_no" value="<?= $sysusr_fax; ?>" id='fax_no' class="form-control input-medium">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-3">
                                                <div class="control-group">
                                                    <label>Address</label>
                                                    <div class="controls">
                                                        <input type="text" name="address" value="<?= $sysusr_addr; ?>" id='address' class="form-control input-medium">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="control-group">
                                                    <label>User Picture</label>
                                                    <div class="controls">
                                                        <input type="file" name="sysusr_photo" id="sysusr_photo" class="input-medium" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="control-group">
                                                    <label>Department</label>
                                                    <div class="controls">
                                                        <select name="sysusr_dept" tabindex="8" class="form-control input-medium">
                                                            <option value="No Department">Select</option>
                                                            <?
                                                            $strSQL="select distinct sysusr_dept from sysuser_tab where sysusr_dept not like'' order by sysusr_dept";
                                                            $rsTemp1=mysql_query($strSQL);
                                                            while($rsRow1=mysql_fetch_array($rsTemp1))
                                                            {
                                                            $sel = ($sysusr_dept == $rsRow1[sysusr_dept]) ? 'selected="selected"' : '';
                                                            echo "<option VALUE='$rsRow1[sysusr_dept]' $sel>$rsRow1[sysusr_dept]</option>";
                                                            }
                                                            mysql_free_result($rsTemp1);
                                                            ?>
                                                            <option value="New"> New Department</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="control-group">
                                                    <label>Designation</label>
                                                    <div class="controls">
                                                        <select name="sysusr_deg" id="sysusr_deg" tabindex="7" class="form-control input-medium">
                                                            <option value="No Designation" >Select</option>
                                                            <?
                                                            $strSQL="select distinct sysusr_deg from sysuser_tab where sysusr_deg not like'' order by sysusr_deg";
                                                            $rsTemp1=mysql_query($strSQL);
                                                            while($rsRow1=mysql_fetch_array($rsTemp1))
                                                            {
                                                            $sel = ($sysusr_deg == $rsRow1[sysusr_deg]) ? 'selected="selected"' : '';
                                                            echo "<option value='$rsRow1[sysusr_deg]' $sel>$rsRow1[sysusr_deg]</option>";
                                                            }
                                                            mysql_free_result($rsTemp1);
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-12 right">
                                                <div class="control-group">
                                                    <label>&nbsp;</label>
                                                    <div class="controls">
                                                        <input type="hidden" name="hdnstkId" value="<?= $nstkId ?>" />
                                                        <input  type="hidden" name="hdnToDo" value="<?= $strDo ?>" />
                                                        <input type="submit" value="<?= $strDo ?>" class="btn btn-primary" />
                                                        <input name="btnAdd" type="reset" id="btnCancel" value="Cancel" class="btn btn-info" OnClick="window.location = '<?= $_SERVER["PHP_SELF"]; ?>';">
                                                        <?php
                                                        if (isset($_REQUEST['msg']) && !empty($_REQUEST['msg'])) {
                                                            print '<p style=\'color:#FF0000\'>Error:' . $_REQUEST['msg'] . "</p>";
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget">
                            <div class="widget-head">
                                <h3 class="heading">All Users</h3>
                            </div>
                            <div class="widget-body">
                                <table width="100%" cellpadding="0" cellspacing="0" align="center">
                                    <tr>
                                        <td style="text-align:right;">
                                            <img style="cursor:pointer;" src="../images/pdf-32.png" onClick="mygrid.setColumnHidden(5, true);
                                                    mygrid.setColumnHidden(6, true);
                                                    mygrid.toPDF('../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/grid2pdf/server/generate.php');
                                                    mygrid.setColumnHidden(5, false);
                                                    mygrid.setColumnHidden(6, false);" />
                                            <img style="cursor:pointer;" src="../images/excel-32.png" onClick="mygrid.setColumnHidden(5, true);
                                                    mygrid.setColumnHidden(6, true);
                                                    mygrid.toExcel('../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/grid2excel/server/generate.php');
                                                    mygrid.setColumnHidden(5, false);
                                                    mygrid.setColumnHidden(6, false);" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><div id="mygrid_container" style="width:100%; height:390px; background-color:white;overflow:hidden"></div></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include "template/footer.php"; ?>
    <link rel="STYLESHEET" type="text/css" href="../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/dhtmlxgrid.css">
    <link rel="STYLESHEET" type="text/css" href="../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/skins/dhtmlxgrid_dhx_blue.css">
    <link rel="STYLESHEET" type="text/css" href="../plmis_src/operations/dhtmlxToolbar/codebase/skins/dhtmlxtoolbar_dhx_skyblue.css">
    <link rel="stylesheet" type="text/css" href="../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/skins/dhtmlxgrid_dhx_skyblue.css">
    <link rel="STYLESHEET" type="text/css" href="../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/ext/dhtmlxgrid_pgn_bricks.css">
    <!-- <link rel="stylesheet" type="text/css" href="lightbox/themes/default/jquery.lightbox.css" />-->

    <script src="../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/dhtmlxcommon.js"></script>
    <script src="../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/dhtmlxgrid.js"></script>
    <script src="../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/dhtmlxgridcell.js"></script>
    <script src='../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/grid2pdf/client/dhtmlxgrid_export.js'></script>
    <script src="../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/excells/dhtmlxgrid_excell_link.js"></script>
    <script src="../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/ext/dhtmlxgrid_filter.js"></script>

    <script>
                                                function editFunction(val) {
                                                    window.location = "ManageUser.php?Do=Edit&Id=" + val;
                                                }
                                                function delFunction(val) {
                                                    if (confirm("Are you sure you want to delete the record?")) {
                                                        window.location = "ManageUser.php?Do=Delete&Id=" + val;
                                                    }
                                                }
                                                var mygrid;
                                                function doInitGrid() {
                                                    mygrid = new dhtmlXGridObject('mygrid_container');
                                                    mygrid.selMultiRows = true;
                                                    mygrid.setImagePath("../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/");
                                                    mygrid.setHeader("<span title='Stakeholder'>Stakeholder</span>,<span title='Province'>Province</span>,<span title='District'>District</span>,<span title='Warehouse'>Warehouse</span>,<span title='Username'>Username</span>,<span title='Use this column to perform the desired operation'>Actions</span>,#cspan");
                                                    mygrid.attachHeader(",#select_filter,#text_filter,#text_filter,#text_filter");
                                                    mygrid.setInitWidths("120,150,150,*,120,30,30");
                                                    mygrid.setColAlign("left,left,left,left,left")
                                                    mygrid.setColSorting("str");
                                                    mygrid.enableMultiline(true);
                                                    mygrid.setColTypes("ro,ro,ro,ro,ro,img,img");
                                                    //mygrid.enableLightMouseNavigation(true);
                                                    mygrid.enableRowsHover(true, 'onMouseOver');   // `onMouseOver` is the css class name.
                                                    mygrid.setSkin("light");

                                                    mygrid.init();
                                                    mygrid.loadXML("xml/user.xml");
                                                }
    </script>
    <script>
<?php if ($test == 'true') { ?>
            $(function() {
                showDistricts();
                //Disabling sub-combos start 
                $("#districts").attr('disabled', false);
                $("#Warehouses").attr('disabled', false);
                // end

                $("#Provinces").change(function() {
                    showDistricts();
                });
            });
<?php } else { ?>
            $(function() {
    <?php if ($stakeholder != 0) { ?>

                    var bid =<?php echo $provinceidd; ?>;
                    $.post("getfromajax.php", {ctype: 3, id: bid}, function(data) {
                        $("#districts").html(data);
                    });


    <?php } ?>

            });
<?php } ?>
        function showDistricts()
        {
            $("#districts").html("<option>Please wait...</option>");

            var bid = $("#Provinces").val();
            $.post("getfromajax.php", {ctype: 3, id: bid}, function(data) {
                $("#districts").html(data);
<?php if (isset($_REQUEST['Do']) && $_REQUEST['Do'] == 'Edit') {
    ?>
                    showwarehouse();
<?php } ?>
            });
        }
        ///////////// Function that will remove NULL values from array
        function removeEmptyElem(ary) {
            for (var i = ary.length; i >= 0; i--) {
                if (ary[i] == undefined) {
                    ary.splice(i, 1);
                }
            }
            return ary;
        }

        function showwarehouse() {
            var districts = new Array();
            var bid = new Array();
            var id = new Array();

            for (var i = 0; i < document.manageuser.select4.length; i++) {
                if (document.manageuser.select4[i].checked == true) {
                    districts[i] = document.manageuser.select4[i].value;
                }
            }

            var bid = removeEmptyElem(districts);
            //alert(bid);
            var pid = $("#Stakeholders").val();

            if ($('#Stakeholders').val() != '')
            {
                //alert(pid);
                $.post("getfromajax.php", {ctype: 6, id: bid, id2: pid}, function(data) {
                    $("#Warehouses1").html(data);
                });
            }
            else
            {
                alert('Please select stakeholder');
                return false;
            }

        }
    </script>
    <?php
    if (isset($_SESSION['err'])) {
        ?>
        <script>
            var self = $('[data-toggle="notyfy"]');
            notyfy({
                force: true,
                text: '<?php echo $_SESSION['err']['text']; ?>',
                type: '<?php echo $_SESSION['err']['type']; ?>',
                layout: self.data('layout')
            });
        </script>
        <?php
        unset($_SESSION['err']);
    }
    ?>
</body>
</html>
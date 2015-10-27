<?php
include "../html/config.php";
include "template/header.php";
include("Includes/AllClasses.php");
require_once("Includes/clsLogin.php");

$objLogin = new clsLogin();
$strMsg = NULL;

if (isset($_REQUEST['Submit']) && !empty($_REQUEST['Submit'])) {
    $objLogin->m_strPass = base64_encode($_REQUEST['new_pass']);
    $objLogin->m_login = $_SESSION['userid'];
    $rs = $objLogin->Update();
    $_SESSION['e'] = 1;
    header("location:changePass.php");
    exit;
}

$objLogin->m_login = $_SESSION['userid'];
$oldPass = $objLogin->getOldPass();
?>
</head>

<body class="page-header-fixed page-quick-sidebar-over-content">
    <!-- BEGIN HEADER -->
    <div class="page-container">
        <?php include "template/" . $_SESSION['menu']; ?>

        <div class="page-content-wrapper">
            <div class="page-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget">
                            <div class="widget-head">
                                <h3 class="heading">Change Pasword</h3>
                            </div>
                            <div class="widget-body">
                                <form id="form1" name="form1" method="post" action="">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-3">
                                                <div class="control-group">
                                                    <label>Old Password<font color="#FF0000">*</font></label>
                                                    <div class="controls">
                                                        <input type="password" name="old_pass" id="old_pass" class="form-control input-sm input-medium" />
                                                        <input type="hidden" name="old_pass_hid" id="old_pass_hid" value="<?php echo base64_decode($oldPass) ?>"  />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-3">
                                                <div class="control-group">
                                                    <label>New Password<font color="#FF0000">*</font></label>
                                                    <div class="controls">
                                                        <input type="password" name="new_pass" id="new_pass" class="form-control input-sm input-medium" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-3">
                                                <div class="control-group">
                                                    <label>Confirm New Password<font color="#FF0000">*</font></label>
                                                    <div class="controls">
                                                        <input type="password" name="confirm_pas" id="confirm_pas" class="form-control input-sm input-medium" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-3">
                                                <div class="control-group">
                                                    <label>&nbsp;</label>
                                                    <div class="controls">
                                                        <input type="submit" name="Submit" id="Submit" value="Change Password" class="btn green" />
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
            </div>
        </div>
    </div>
    <?php include "template/footer.php"; ?>


    <?php
    if (isset($_SESSION['e'])) {
        ?>
        <script>
            var self = $('[data-toggle="notyfy"]');
            notyfy({
                force: true,
                text: 'Password changed successfully',
                type: 'success',
                layout: self.data('layout')
            });
        </script>
        <?php
        unset($_SESSION['e']);
    }
    ?>
</body>
</html>
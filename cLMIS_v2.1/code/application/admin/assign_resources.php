<?php
/**
 * Assign Resources
 * @package Admin
 * 
 * @author     Muhammad Waqas Azeem 
 * @email <waqas@deliver-pk.org>
 * 
 * @version    2.2
 * 
 */
//Including required files
include("../includes/classes/AllClasses.php");
include(PUBLIC_PATH . "html/header.php");
?>
<style>
    .modal {
        display:    none;
        position:   fixed;
        z-index:    10000;
        top:       	0;
        left:       0;
        height:     100%;
        width:      100%;
        background: rgba( 255, 255, 255, 0.3 )
            url('../../public/images/loader.gif')
            50% 50%
            no-repeat;
    }
    /* When the body has the loading class, we turn
       the scrollbar off with overflow:hidden */
    body.loading {
        overflow: auto;
    }
    /* Anytime the body has the loading class, our
       modal element will be visible */
    body.loading .modal {
        display: block;
    }
</style>
</head>
<!-- BEGIN body -->
<body class="page-header-fixed page-quick-sidebar-over-content">
    <div class="modal"></div>
    <!-- BEGIN HEADER -->
    <div class="page-container">
        <?php
        //Including required files
        include $_SESSION['menu'];
        include PUBLIC_PATH . "html/top_im.php";
        ?>
        <!-- BEGIN page-content-wrapper -->
        <div class="page-content-wrapper">
            <div class="page-content">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="page-title row-br-b-wp">Assign Resources</h3>
                        <div class="widget" data-toggle="collapse-widget">
                            <!-- BEGIN widget-head -->
                            <div class="widget-head">
                                <h3 class="heading">Assign Resources to User Groups</h3>
                            </div>
                            <!-- END widget-head -->
                            <!-- BEGIN widget-body -->
                            <div class="widget-body">
                                <form method="post" action="" name="frm" id="frm">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-3">
                                                <div class="control-group">
                                                    <label>Role Type<font color="#FF0000">*</font></label>
                                                    <div class="controls">
                                                        <select required name="role_id" id="role_id" class="form-control input-medium">
                                                            <option value="">Select</option>
                                                            <?php //get roles
                                                            $qry = "SELECT
																	roles.pk_id,
																	roles.role_name
																FROM
																	roles
																WHERE
																	roles.pk_id != 1";
                                                            $qryRes = mysql_query($qry);
                                                            while ($row = mysql_fetch_array($qryRes)) {
                                                                $sel = ($role_id == $row['pk_id']) ? 'selected="selected"' : '';
                                                                echo "<option value=\"" . $row['pk_id'] . "\" $sel>" . $row['role_name'] . "</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>                                        
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12" id="resources_div" style="margin:20px 0 0 15px;">
                                            <div class="col-md-12">
                                                <div class="control-group">
                                                    <div class="controls">
                                                        <label>Select Role Type to see Resources</label>
                                                    </div>
                                                </div>
                                            </div>                                        
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 right">
                                            <div class="control-group">
                                                <div class="control-group">
                                                    <label>&nbsp;</label>
                                                    <div class="controls">
                                                        <input type="hidden" name="ctype" value="2" />
                                                        <input type="button" id="submit" class="btn btn-primary" value="Assign Resources" />
                                                        <input name="btnAdd" class="btn btn-info" type="button" id="btnCancel" value="Cancel" OnClick="window.location = '<?= $_SERVER["PHP_SELF"]; ?>';">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- END widget-body -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END page-content-wrapper -->
    </div>
    <?php
    //Including required files
    include PUBLIC_PATH . "/html/footer.php";
    include PUBLIC_PATH . "/html/reports_includes.php";
    ?>
    <script>
        $(function() {
            $('#role_id').change(function(e) {
                if ($(this).val() != '') {
                    $.ajax({
                        url: 'assign_resources_ajax.php',
                        type: 'POST',
                        data: {ctype: 1, role_id: $(this).val()},
                        success: function(data) {
                            $('#resources_div').html(data);
                        }
                    })
                } else {
                    $('#resources_div').html('');
                }
            });

            $('#submit').click(function(e) {
                $('body').addClass("loading");
                $.ajax({
                    url: 'assign_resources_ajax.php',
                    type: 'POST',
                    data: $('#frm').serialize(),
                    success: function(data) {
                        $('body').removeClass("loading");
                        $('#resources_div').html(data);
                        window.location = window.location;
                    }
                })
            });
        })
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
        //Unset session
        unset($_SESSION['err']);
    }
    ?>
</body>
<!-- END body -->
</html>
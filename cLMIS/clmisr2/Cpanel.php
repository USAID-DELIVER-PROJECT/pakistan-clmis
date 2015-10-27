<?php
include("html/adminhtml.inc.php");
//print "sfsdfsdf";
Login();
?>


<?php include "plmis_inc/common/_header.php"; ?>
</head>
<!-- END HEAD -->

<body class="page-header-fixed page-quick-sidebar-over-content" onLoad="doInitGrid()">
<!-- BEGIN HEADER -->
<div class="page-container">
    <?php include "plmis_inc/common/_top.php";?>
    <?php

//include "plmis_inc/common/top.php";
    $sql = "select UserID from sysuser_tab where UserID='" . $_SESSION['userid'] . "'";
//echo $sql .'<br/>';
    $sql2 = mysql_query($sql);
    $row_logo = mysql_fetch_array($sql2);
    $mSQL = "select id, heading,description from tbl_cms where homepage_chk=1 and Stkid='" . $stkid . "' AND province_id='" . $province . "'";
//echo $mSQL;
    $query = mysql_query($mSQL);

    $row_text = mysql_fetch_array($query);

    ?>

    <div class="page-content-wrapper">
        <div class="page-content">

            <!-- BEGIN PAGE HEADER-->
            <div class="row">

                <div class="row">
                    <div class="col-md-12">
                        <div class="body_sec" style="width: 998px;">
                            <?php if (strlen($row_text['heading']) > 0) echo "<h2>" . $row_text['heading'] . "</h2>"; ?> <?php echo "<br>" . $row_text['description'] . "</br>"; ?>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>

    <?php include "plmis_inc/common/footer.php";?>


</body>
<!-- END BODY -->
</html>



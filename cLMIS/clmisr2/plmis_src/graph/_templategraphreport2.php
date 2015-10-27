<?php

/***********************************************************************************************************

Developed by Syed Aun Irtaza email: aun.irtaza@hotmail.com

This is our main graph container. It includes two files one is the graphparameterform.php which is used to
take all kinds of parameters. Second one is graphtable.php which is the stage for our graphs. the scheme used
is that it checks if the parameters are passed to it, if yes then graphtable.php use them directly, if no then
it brings the record of parameters from database table tbl_favouritgraphsettings and post a form having all the
hidden variables; and graphtable.php will use them directly.


/***********************************************************************************************************/

ob_start();
include("../../html/adminhtml.inc.php");
include ("../../plmis_inc/classes/cCms.php");
Login();

//////////// GET FILE NAME FROM THE URL
$_SERVER['REQUEST_URI'];
$arr = explode("?", $_SERVER['REQUEST_URI']);
$arr2 = explode("/", $arr[0]);
$arrSize = sizeof($arr2);
$basename = $arr2[$arrSize-1];
$filePath = "plmis_src/graph/".$basename;

//////// GET Read Me Title From DB.

$qryResult = mysql_fetch_array(mysql_query("select extra from sysmenusub_tab where menu_filepath = '".$filePath."' and active = 1"));
$readMeTitle = str_replace(" ", "%20", $qryResult['extra']); /// Encode space


//print_r($_REQUEST);

$userlogged = base64_decode($_SESSION['user']['LogedUser']);
$objDB_fav =new Database();
$objDB_fav->connect();

$db_ins = new Database();
$db_ins->connect();

$db_ins1 = new Database();
$db_ins1->connect();

$db_ins2 = new Database();
$db_ins2->connect();

$db_ins3 = new Database();
$db_ins3->connect();

$db_ins4 = new Database();
$db_ins4->connect();


$sql   = "select * from tbl_favgraphsettings where user='".$userlogged."'";
if($objDB_fav->query($sql) && $objDB_fav->get_num_rows() >0)
{
    $row_fav= $objDB_fav->fetch_one_assoc();
}
//we are checking different graph cases
if(!empty($row_fav['arryearcomp'])) // see the yearly comparison graph
{
    $case1=2;
}
else
    if(!empty($row_fav['arrstakecomp'])) // see the stakeholder wise comparison graph
    {
        $case1=3;
    }
    else
        if(!empty($row_fav['arrprovinces'])) // see the province wise comparison graph
        {
            $case1=4;
        }
        else
            if(!empty($row_fav['districts'])) // see the district wise comparison graph
            {
                $case1=5;
            }
            else
            {
                $case1=1;
            }

$seriescount= count($countyears);
if($case1==3)
{
    $seriescount= count($countstakes);
}
if($case1==1)
{
    $seriescount= 1;
}
$_SESSION['newtitle'] = $_SESSION['comparison_title'];

?>

<?php include "../../plmis_inc/common/_header.php";?>
</head>
<!-- END HEAD -->

<body class="page-header-fixed page-quick-sidebar-over-content" onLoad="doInitGrid()">
<!-- BEGIN HEADER -->
<div class="page-container">
<?php include "../../plmis_inc/common/_top.php";?>
<?php include "../../plmis_inc/common/top_im.php";?>


<div class="page-content-wrapper">
	<div class="page-content">

    <!-- BEGIN PAGE HEADER-->
        <div class="row">
            <div class="col-md-12">
            	<h3 class="page-title row-br-b-wp">Simple Graphs</h3>
                <div class="wrraper" style="height:auto; padding-left:5px">
                    <div class="content" align="" style="width:90%"><br>
<!--                            --><?php // showBreadCrumb();?><!--<div style="float:right; padding-right:2px">--><?php ////echo readMeLinks($readMeTitle);?><!--</div><br><br>-->

                        <script language="javascript" type="text/javascript">
                            function frmfunc()
                            {
                                var casevar = <?php echo intval($_REQUEST['case']);?>;
                                //alert(casevar);
                                //alert(casevar+"here");
                                if(casevar!=0)
                                {

                                    //alert("here");
                                    //document.getElementById('hiddenfrm').submit();
                                }
                                else
                                {
                                    document.forms["hiddenfrm"].submit();
                                    //casevar=-1;
                                    //document.getElementById('hiddenfrm').submit();
                                }
                                showhidefuncs(); // this function is given in graphParameterForm.php
                                //fetchproducts();
                            }
                        </script>

                        <TABLE cellSpacing="0" cellPadding="0" width="99%" border="0" style= "float:left;" >
                            <TBODY>
                            <TR>
                                <!-- Left Hand Side Spacer -->
                                <!--[add code to add left hand spacer]-->

                                <TD vAlign=top width="23%" >

                                    <!--  BEGIN: REFINEMENT PANEL -->

                                    <?php

                                    include("graphParameterForm2.php"); // parameter form
                                    ?>

                                    <!--  END: REFINEMENT PANEL -->

                                </TD>
                                <TD valign="top" width="1%">&nbsp;</td>
                                <TD valign="top" width="75%" align="left" bgColor="#FFFFFF">

                                    <!--  BEGIN: REPORT DESCRIPTION -->
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tbody>
                                        <?php /*?><tr>
                                            <td height="25" align="center" class="sb1GreenInfoBoxLabel">
                                                <?php  if(!empty($_REQUEST['col'])){ echo $_REQUEST['col']." Report"; } else
                                            { echo "Graph Reports"; }  ?>   <!--Report Title-->

                                            </td>
                                        </tr><?php */?>
                                        <?php if(!empty($_REQUEST['error'])){?>
                                        <tr>
                                            <td style="color:red;"><?php echo $_REQUEST['error'];?></td>
                                        </tr>
                                            <? }?>
                                        <tr>
                                            <td class="sb1NormalFont" style="padding-right:20px;">

                                                <?php if(!empty($_REQUEST['col'])){
                                                $colval=$_REQUEST['col']; // brings the graph description from tbl_cms as per static page define in "reports" table
                                                $sql = "select staticpage from reports where report_title='$colval' ";
                                                $staticpage= $db_ins4->executeScalar($sql);
                                                $sql = "select  description from tbl_cms where title ='".$staticpage."'";
                                                echo $desc = $db_ins4->executeScalar($sql);
                                            } else {?>
                                                <?php
                                            } ?><br>
                                            </td>
                                        </tr>

                                        <?php /*?> <tr>
                            <td align="right">
                                <a href="generatepdf.php?allfiles=<?php echo $_REQUEST['allfiles']; ?>&comparison_title=<?php echo $_REQUEST['comparison_title'];?>&rep_desc=<?php  echo $desc; ?>&col=<?php  echo $_REQUEST['col']; ?>"><img src="../../plmis_img/pdficon.jpg" width="25" height="25" title="Generate Pdf" border="0" /></a>
                                <a style="text-decoration:none;" href="javaScript:emailfunc()" title="Email graph pdf"><img src="../../plmis_img/email-icon.gif" width="25" height="25" border="0" /></a>
                                </td>            </a>
                            </tr><?php */?>

                                        <?php if(!empty($_REQUEST['case'])){ ?>

                                        <tr>
                                            <td class="sb1NormalFont" style="padding-right:20px;">
                                                <?php include("graphtable.php"); ?>
                                            </td>
                                        </tr>
                                            <?php } else { //echo $row_fav['sel_user']."fdf";?>
                                        <tr>
                                            <td class="sb1NormalFont" style="padding-right:20px;">
                                                <?php // will have values of favouritgraph settings ?>
                                                <form name="hiddenfrm" id="hiddenfrm" action="templategraphreport2.php" method="get">
                                                    <input type="hidden" name="sel_user" value="<?php echo $row_fav['sel_user'];?>">
                                                    <input type="hidden" name="period" value="<?php echo $row_fav['period'];?>">
                                                    <input type="hidden" name="sel_stakeholder" value="<?php echo $row_fav['sel_stakeholder'];?>">
                                                    <input type="hidden" name="year1" value="<?php echo $row_fav['year'];?>">
                                                    <input type="hidden" name="year2" value="<?php echo $row_fav['year'];?>">
                                                    <input type="hidden" name="case" value="<?php echo $case1;?>">
                                                    <input type="hidden" name="yearcomp" value="<?php echo $row_fav['arryearcomp'];?>">
                                                    <input type="hidden" name="arryearcomp" value="<?php echo $row_fav['arryearcomp'];?>">
                                                    <input type="hidden" name="stakecomp" value="<?php echo $row_fav['arrstakecomp'];?>">
                                                    <input type="hidden" name="arrstakecomp" value="<?php echo $row_fav['arrstakecomp'];?>">
                                                    <input type="hidden" name="products" value="<?php echo $row_fav['arrproducts'];?>">

                                                    <input type="hidden" name="compare_opt" value="<?php echo $row_fav['compare_opt'];?>">
                                                    <input type="hidden" name="optvals" value="<?php echo $row_fav['optvals'];?>">
                                                    <input type="hidden" name="arrproducts" value="<?php echo $row_fav['arrproducts'];?>">
                                                    <input type="hidden" name="titles" value="<?php echo $row_fav['titles'];?>">
                                                    <input type="hidden" name="allfiles" value="<?php echo $row_fav['allfiles'];?>">
                                                    <input type="hidden" name="col" value="<?php echo $row_fav['col'];?>">
                                                    <input type="hidden" name="unit" value="<?php echo $row_fav['unit'];?>">
                                                    <input type="hidden" name="xaxis" value="<?php echo $row_fav['xaxis'];?>">
                                                    <input type="hidden" name="ctype" value="<?php echo $row_fav['ctype'];?>">
                                                    <input type="hidden" name="rep_title1" value="<?php echo $row_fav['rep_title1'];?>">
                                                    <input type="hidden" name="rep_title2" value="<?php echo $row_fav['rep_title2'];?>">
                                                    <input type="hidden" name="rep_title3" value="<?php echo $row_fav['rep_title3'];?>">
                                                    <input type="hidden" name="rep_logo" value="<?php echo $row_fav['rep_logo'];?>">
                                                    <input type="hidden" name="rep_desc" value="<?php echo $row_fav['rep_desc'];?>">
                                                    <input type="hidden" name="period_label" value="<?php echo $row_fav['period_label'];?>">
                                                    <input type="hidden" name="comparison_title" value="<?php echo $row_fav['comparison_title'];?>">
                                                    <input type="hidden" name="arrgroupcomp" value="<?php echo $row_fav['arrgroupcomp'];?>">
                                                    <input type="hidden" name="seriescount" value="<?php echo $seriescount;?>">
                                                    <input type="hidden" name="case" value="<?php echo $case1?>">
                                                    <input type="hidden" name="period_label" value="<?php echo $row_fav['period_label'];?>">
                                                    <input type="hidden" name="arrprovinces" value="<?php echo $row_fav['arrprovinces'];?>">
                                                    <input type="hidden" name="provinces" value="<?php echo $row_fav['provinces'];?>">
                                                    <input type="hidden" name="arrdistricts" value="<?php echo $row_fav['arrdistricts'];?>">
                                                    <input type="hidden" name="districts" value="<?php echo $row_fav['districts'];?>">
                                                    <input type="hidden" name="sel_prov" value="<?php echo $row_fav['sel_prov'];?>">

                                                </form>






                                            </td>
                                        </tr>
                                            <?php }?>
                                        <tr>
                                            <td class="sb1NormalFont" style="padding-right:20px;">
                                                <?php echo $RefineDescription; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <?php echo $descriptionSeperator; ?>
                                            </td>
                                        </tr>

                                    </table>
                                </TD>
                            </TR>
                            </TBODY>
                        </TABLE>
                    </div>
                </div>
            </div>
        </div>

	</div>
</div>
</div>

<!-- END FOOTER -->
    <?php include "../../plmis_inc/common/footer.php";?>

</body>
<!-- END BODY -->
</html>
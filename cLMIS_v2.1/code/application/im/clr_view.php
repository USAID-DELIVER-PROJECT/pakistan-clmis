<?php
/**
 * clr_view
 * @package im
 * 
 * @author     Muhammad Waqas Azeem 
 * @email <waqas@deliver-pk.org>
 * 
 * @version    2.2
 * 
 */
//include AllClasses
include("../includes/classes/AllClasses.php");
//include header
include(PUBLIC_PATH . "html/header.php");
//check id
if (isset($_REQUEST['id']) && isset($_REQUEST['wh_id'])) {
    //get to warehouse
    $whTo = mysql_real_escape_string($_REQUEST['wh_id']);
    //get id
    $id = mysql_real_escape_string($_REQUEST['id']);
    //select query
    //gets
    //district id
    //province id
    //stakeholder id
    //location name
    //main stakeholder
    $qry = "SELECT
				tbl_warehouse.dist_id,
				tbl_warehouse.prov_id,
				tbl_warehouse.stkid,
				tbl_locations.LocName,
				MainStk.stkname AS MainStk
			FROM
			tbl_warehouse
			INNER JOIN tbl_locations ON tbl_warehouse.dist_id = tbl_locations.PkLocID
			INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
			INNER JOIN stakeholder AS MainStk ON stakeholder.MainStakeholder = MainStk.stkid
			WHERE
			tbl_warehouse.wh_id = " . $whTo;
    //query result
    $qryRes = mysql_fetch_array(mysql_query($qry));
    //distrct id
    $distId = $qryRes['dist_id'];
    //province id
    $provId = $qryRes['prov_id'];
    //stakeholder id
    $stkid = $qryRes['stkid'];
    //location name
    $distName = $qryRes['LocName'];
    //main stakeholder
    $mainStk = $qryRes['MainStk'];
//select query
    //gets
    //requisition num,
    //date from,
    //date to,
    //pk id,
    //pk master id,
    //avg consumption,
    //soh dist,
    //soh field,
    //total stock,
    //desired stock,
    //replenishment,
    // requested on,
    //item name,
    //item id,
    //item rec id,
    //item type,
    //generic name,
    //method type
    $qry = "SELECT
				clr_master.requisition_num,
				clr_master.date_from,
				clr_master.date_to,
				clr_details.pk_id,
				clr_details.pk_master_id,
				clr_details.avg_consumption,
				clr_details.soh_dist,
				clr_details.soh_field,
				clr_details.total_stock,
				clr_details.desired_stock,
				clr_details.replenishment,
				DATE_FORMAT(clr_master.requested_on, '%d/%m/%Y') AS requested_on,
				itminfo_tab.itm_name,
				itminfo_tab.itm_id,
				itminfo_tab.itmrec_id,
				itminfo_tab.itm_type,
				itminfo_tab.generic_name,
				itminfo_tab.method_type
			FROM
				clr_master
				INNER JOIN clr_details ON clr_details.pk_master_id = clr_master.pk_id
				INNER JOIN itminfo_tab ON clr_details.itm_id = itminfo_tab.itm_id
			WHERE
				clr_master.pk_id = " . $id;
    //query result
    $qryRes = mysql_query($qry);
    //fetch result
    while ($row = mysql_fetch_array($qryRes)) {
        //requisition Num 
        $requisitionNum = $row['requisition_num'];
        //date from
        $dateFrom = date('M-Y', strtotime($row['date_from']));
        //date to
        $dateTo = date('M-Y', strtotime($row['date_to']));
        //requested on 
        $requestedOn = $row['requested_on'];
        //item ids
        $itemIds[] = $row['itm_id'];
        //product
        $product[$row['method_type']][] = $row['itm_name'];
        if ($row['itm_id'] == 8) {
            //set avg Consumption
            $avgConsumption[$row['itm_id']] = '';
            //set SOH Dist
            $SOHDist[$row['itm_id']] = '';
            //set SOH Field
            $SOHField[$row['itm_id']] = '';
            //set total Stock
            $totalStock[$row['itm_id']] = '';
            //set desired Stock
            $desiredStock[$row['itm_id']] = '';
            //set replenishment
            $replenishment[$row['itm_id']] = '';
        } else {
            //set avg Consumption
            $avgConsumption[$row['itm_id']] = number_format($row['avg_consumption']);
            //set SOH Dist
            $SOHDist[$row['itm_id']] = number_format($row['soh_dist']);
            //set SOH Field
            $SOHField[$row['itm_id']] = number_format($row['soh_field']);
            //set total Stock
            $totalStock[$row['itm_id']] = number_format($row['total_stock']);
            //set desired Stock
            $desiredStock[$row['itm_id']] = number_format($row['desired_stock']);
            //set replenishment
            $replenishment[$row['itm_id']] = number_format($row['replenishment']);
        }

        if (strtoupper($row['method_type']) == strtoupper($row['generic_name'])) {
            $methodType[$row['method_type']]['rowspan'] = 2;
        } else {
            $genericName[$row['generic_name']][] = $row['itm_name'];
        }
    }
    $duration = $dateFrom . ' to ' . $dateTo;
}
?>
<script>
    function printContents() {
        var dispSetting = "toolbar=yes,location=no,directories=yes,menubar=yes,scrollbars=yes, left=100, top=25";
        var printingContents = document.getElementById("printing").innerHTML;

        var docprint = window.open("", "", printing);
        docprint.document.open();
        docprint.document.write('<html><head><title>CLR6</title>');
        docprint.document.write('</head><body onLoad="self.print(); self.close()"><center>');
        docprint.document.write(printingContents);
        docprint.document.write('</center></body></html>');
        docprint.document.close();
        docprint.focus();
    }
</script>
</head>
<!-- END HEAD -->

<body class="page-header-fixed page-quick-sidebar-over-content">
    <!-- BEGIN HEADER -->
    <div class="page-container">
        <?php include PUBLIC_PATH . "html/top.php"; ?>
        <?php include PUBLIC_PATH . "html/top_im.php"; ?>
        <div class="page-content-wrapper">
            <div class="page-content"> 

                <!-- BEGIN PAGE HEADER-->
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget">
                            <div class="widget-head">
                                <h3 class="heading">Requisitions</h3>
                            </div>
                            <div class="widget-body">
                                <div id="printing" style="clear:both;margin-top:20px;">
                                    <div style="margin-left:0px !important; width:100% !important;">
                                        <style>
                                            body {
                                                margin: 0px !important;
                                                font-family: Arial, Helvetica, sans-serif;
                                            }

                                            table#myTable {
                                                margin-top: 20px;
                                                border-collapse: collapse;
                                                border-spacing: 0;
                                            }

                                            table#myTable tr td, table#myTable tr th {
                                                font-size: 11px;
                                                padding-left: 5px;
                                                text-align: left;
                                                border: 1px solid #999;
                                            }

                                            table#myTable tr td.TAR {
                                                text-align: right;
                                                padding: 5px;
                                                width: 50px !important;
                                            }

                                            .sb1NormalFont {
                                                color: #444444;
                                                font-family: Verdana, Arial, Helvetica, sans-serif;
                                                font-size: 11px;
                                                font-weight: bold;
                                                text-decoration: none;
                                            }

                                            p {
                                                margin-bottom: 5px;
                                                font-size: 11px !important;
                                                line-height: 1 !important;
                                                padding: 0 !important;
                                            }

                                            table#headerTable tr td {
                                                font-size: 11px;
                                            }

                                            /* Print styles */
                                            @media only print {
                                                table#myTable tr td, table#myTable tr th {
                                                    font-size: 8px;
                                                    padding-left: 2 !important;
                                                    text-align: left;
                                                    border: 1px solid #999;
                                                }

                                                #doNotPrint {
                                                    display: none !important;
                                                }
                                            }
                                        </style>
                                        <p style="color: #000000; font-size: 20px;text-align:center"><b><u>Contraceptive Requisition Form</u></b><span style="float:right; font-weight:normal;">CLR-6</span></p>
                                        <p style="text-align:center;margin-right:35px;">(<?php echo "For $mainStk District $distName"; ?>)</p>
                                        <table width="200" id="headerTable" align="right">
                                            <tr>
                                                <td align="left"><p style="width: 100%; display: table;"> <span style="display: table-cell; width: 20px;">For: </span> <span style="display: table-cell; border-bottom: 1px solid black;"><?php echo $duration; ?></span> </p></td>
                                            </tr>
                                            <tr>
                                                <td><p style="width: 100%; display: table;"> <span style="display: table-cell; width: 75px;">Requisition No: </span> <span style="display: table-cell; border-bottom: 1px solid black;"><?php echo $requisitionNum; ?></span> </p></td>
                                            </tr>
                                            <tr>
                                                <td><p style="width: 100%; display: table;"> <span style="display: table-cell; width: 83px;">Requisition Date: </span> <span style="display: table-cell; border-bottom: 1px solid black;"><?php echo $requestedOn; ?></span> </p></td>
                                            </tr>
                                        </table>
                                        <div style="clear:both;"></div>
                                        <table width="100%" id="myTable" cellspacing="0" align="center">
                                            <thead>
                                                <tr>
                                                    <td rowspan="2" width="40" style="text-align:center;">S. No.</td>
                                                    <td rowspan="2" width="150" id="desc">Description</td>
                                                    <?php
                                                    foreach ($product as $proType => $proNames) {
                                                        echo "<td style=\"text-align:center !important;\" colspan=" . sizeof($proNames) . ">$proType</td>";
                                                    }
                                                    ?>
                                                    <td rowspan="2" style="width:80px;">Remarks</td>
                                                </tr>
                                                <tr>
                                                    <?php
                                                    $col = '';
                                                    foreach ($product as $proType => $proNames) {
                                                        foreach ($proNames as $name) {
                                                            $names[] = $name;
                                                            echo "<td>$name</td>";
                                                            $col .= "<td>&nbsp;</td>";
                                                        }
                                                    }
                                                    ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $colNum = 1;
                                                if ($mainStk == 'PWD') {
                                                    ?>
                                                    <tr height="30">
                                                        <td colspan="<?php echo count($itemIds) + 3; ?>">Part - A (District Population Welfare Office - DPWO)</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align:center;"><?php echo $colNum++; ?></td>
                                                        <td>Quarterly Sale on the basis of last 3 months consumption</td>
                                                        <?php echo $col; ?>
                                                        <td>&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align:center;"><?php echo $colNum++; ?></td>
                                                        <td>Sale/Use last month</td>
                                                        <?php echo $col; ?>
                                                        <td>&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align:center;"><?php echo $colNum++; ?></td>
                                                        <td>Amount of sales proceeds deposited in bank/treasury (Attached original paid challan)</td>
                                                        <td colspan="<?php echo count($itemIds) + 1; ?>">&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align:center;"><?php echo $colNum++; ?></td>
                                                        <td>Bank/Treasury challan no. & Date</td>
                                                        <td colspan="<?php echo count($itemIds) + 1; ?>">&nbsp;</td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>
                                                <tr height="30">
                                                    <td colspan="<?php echo count($itemIds) + 3; ?>">Part - <?php echo ($mainStk == 'PWD') ? 'B' : 'A'; ?> (To be
                                                        filled by Requester) </td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align:center;">A-1</td>
                                                    <td>Consumption During Last Quarter</td>
                                                    <?php
                                                    foreach ($avgConsumption as $key => $val) {
                                                        echo "<td class=\"TAR\">" . $val . "</td>";
                                                    }
                                                    ?>
                                                    <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align:center;">A-2</td>
                                                    <td>Stock at the end of last quarter at district Store</td>
                                                    <?php
                                                    foreach ($SOHDist as $key => $val) {
                                                        echo "<td class=\"TAR\">" . $val . "</td>";
                                                    }
                                                    ?>
                                                    <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align:center;">A-3</td>
                                                    <td>Stock at the end of last quarter at health outlets</td>
                                                    <?php
                                                    foreach ($SOHField as $key => $val) {
                                                        echo "<td class=\"TAR\">" . $val . "</td>";
                                                    }
                                                    ?>
                                                    <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align:center;">A-4</td>
                                                    <td>Total Stock Available (A2+A3)</td>
                                                    <?php
                                                    foreach ($totalStock as $key => $val) {
                                                        echo "<td class=\"TAR\">" . $val . "</td>";
                                                    }
                                                    ?>
                                                    <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align:center;">A-5</td>
                                                    <td>Desired stock level for 2 quarters (A1x2)</td>
                                                    <?php
                                                    foreach ($desiredStock as $key => $val) {
                                                        echo "<td class=\"TAR\">" . $val . "</td>";
                                                    }
                                                    ?>
                                                    <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align:center;">A-6</td>
                                                    <td>Replenishment Requested (A5-A4)</td>
                                                    <?php
                                                    foreach ($replenishment as $key => $val) {
                                                        echo "<td class=\"TAR\">" . $val . "</td>";
                                                    }
                                                    ?>
                                                    <td>&nbsp;</td>
                                                </tr>
                                                <tr height="30">
                                                    <td colspan="<?php echo count($itemIds) + 3; ?>">Part - <?php echo ($mainStk == 'PWD') ? 'C' : 'B'; ?> (To be
                                                        filled at warehouse) </td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align:center;"><?php echo $colNum++; ?></td>
                                                    <td>Quantity Approved</td>
                                                    <?php echo $col; ?>
                                                    <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align:center;"><?php echo $colNum++; ?></td>
                                                    <td>Relevant Issue Voucher</td>
                                                    <?php echo $col; ?>
                                                    <td>&nbsp;</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <table width="100%">
                                            <tr>
                                                <td colspan="4">&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td style="text-align:right;" width="10%" class="sb1NormalFont">Name:</td>
                                                <td width="40%">__________________________</td>
                                                <td width="30%" style="text-align:right;" class="sb1NormalFont">Signature:</td>
                                                <td width="20%">__________________________</td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td style="text-align:right;" class="sb1NormalFont">Designation:</td>
                                                <td>__________________________</td>
                                                <td style="text-align:right;" class="sb1NormalFont">Date:</td>
                                                <td>__________________________</td>
                                            </tr>
                                            <tr id="doNotPrint">
                                                <td colspan="4" style="text-align:right; border:none; padding-top:15px;">
                                                    <input type="button" onClick="history.go(-1)" value="Back" class="btn btn-primary" />
                                                    <input type="button" onClick="printContents()" value="Print" class="btn btn-warning" />
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END FOOTER -->
    <?php include PUBLIC_PATH . "/html/footer.php"; ?>
    <!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
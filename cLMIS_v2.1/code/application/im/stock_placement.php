<?php
/**
 * stock_placement
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
//get warehouse id
$wh_id = $_SESSION['user_warehouse'];
//get rack count
$getRackCount = 0;
//get row count
$getRowCount = 0;
//area
$area = $level = '';
//check area
if (isset($_GET) && !empty($_GET['area'])) {
    if (isset($_GET['area']) && !empty($_GET['area'])) {
        //get area
        $area = $_GET['area'];
    }
    //check level
    if (isset($_GET['level']) && !empty($_GET['level'])) {
        //get vevel	
        $level = $_GET['level'];
    }
    //get warehouse id
    $wh_id = $_SESSION['user_warehouse'];
    //select query
    //gets
    //pk id
    //location name
    //row
    //pallet
    //rack
    $mainSQL = "SELECT
				placement_config.pk_id,
				placement_config.location_name,
				rows.list_value AS myrow,
				Pallets.list_value AS mypallet,
				racks.list_value AS myrack
			FROM
				placement_config
			INNER JOIN list_detail AS rows ON placement_config.`row` = rows.pk_id
			INNER JOIN list_detail AS racks ON placement_config.rack = racks.pk_id
			INNER JOIN list_detail AS Pallets ON placement_config.pallet = Pallets.pk_id
			WHERE (area=" . $area . " AND level=" . $level . ")
				AND warehouse_id=" . $wh_id . "
				AND placement_config.`status` = 1
			ORDER BY
				myrow,
				myrack,
				mypallet";
    //query result
    $getLocationStatus = mysql_query($mainSQL) or die(mysql_error());
    //number of locations
    $NoofLocations = mysql_num_rows($getLocationStatus);

    //select query
    //gets
    //rows count
    $rowCountSQL = "SELECT
						ifnull(max(rows.list_value),0) AS rows
						FROM
						placement_config
						INNER JOIN list_detail AS rows ON placement_config.`row` = rows.pk_id
				WHERE
					area=$area AND level=$level AND warehouse_id =  $wh_id
				GROUP BY
					placement_config.warehouse_id";
    //query result
    $getRowCount = mysql_query($rowCountSQL) or die($rowCountSQL);
    //fetch result
    $getRowCount = mysql_fetch_row($getRowCount);
    //select query
    //gets
    //rack count
    $rackCountSQL = "SELECT ifnull(max(rack.list_value),0) AS racks 
				FROM placement_config INNER JOIN list_detail AS rack ON placement_config.`rack` = rack.pk_id
				WHERE
					area=$area AND level=$level AND warehouse_id =  $wh_id
				GROUP BY
					placement_config.warehouse_id";
    //query result
    $getRackCount = mysql_query($rackCountSQL) or die("Err Countracks");
    $getRackCount = mysql_fetch_row($getRackCount);
}
//row count
$Rowcounter = 0;
//rack count
$Rackcounter = 0;
?>
<style>
    .btn-link {
        color: #fff !important;
        text-shadow: none;
    }
</style>

<!-- END HEAD -->

<body class="page-header-fixed page-quick-sidebar-over-content">
    <!-- BEGIN HEADER -->
    <div class="page-container">
        <?php
//include top
        include PUBLIC_PATH . "html/top.php";
//include top_im
        include PUBLIC_PATH . "html/top_im.php";
        ?>
        <div class="page-content-wrapper">
            <div class="page-content"> 

                <!-- BEGIN PAGE HEADER-->

                <div class="row">
                    <div class="col-md-12">
                        <div class="widget" data-toggle="collapse-widget">
                            <div class="widget-head">
                                <h3 class="heading">Location Status</h3>
                            </div>
                            <div class="widget-body">
                                <form method="GET" name="placement_location" id="placement_location" action="">
                                    <!-- Row -->
                                    <div class="row-fluid">
                                        <div class="col-md-2"> 
                                            <!-- Group Receive No-->
                                            <div class="control-group">
                                                <label class="control-label" for="receive_no"> Area <span style="color: red">*</span> </label>
                                                <div class="controls">
                                                    <select class="form-control input-small" name="area" id="area" required>
                                                        <option value="">Select</option>
                                                        <?php
                                                        //select query
                                                        //grts
                                                        //area
                                                        $getArea = mysql_query("SELECT
        
                                                        list_detail.pk_id,
                                                        list_detail.list_value
                                                        FROM
                                                        list_master
                                                        INNER JOIN list_detail ON list_master.pk_id = list_detail.list_master_id
                                                        WHERE
                                                        list_master.pk_id = 14") or die("ERR Get Area");
                                                        //fetch result
                                                        while ($rowArea = mysql_fetch_assoc($getArea)) {
                                                            //populate area combo
                                                            ?>
                                                            <option value="<?php echo $rowArea['pk_id']; ?>"
                                                            <?php
                                                            if ($rowArea['pk_id'] == $area) {
                                                                echo "selected=selected";
                                                            }
                                                            ?>> <?php echo $rowArea['list_value']; ?> </option>
<?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="control-group">
                                                <label class="control-label" for="receive_no"> Level <span
                                                        style="color: red">*</span> </label>
                                                <div class="controls">
                                                    <select class="form-control input-small" name="level" id="level" required>
                                                        <option value="">Select</option>
                                                        <?php
                                                        //select query
                                                        //grts
                                                        //area
                                                        $getArea = mysql_query("SELECT
        
                                                        list_detail.pk_id,
                                                        list_detail.list_value
                                                        FROM
                                                        list_master
                                                        INNER JOIN list_detail ON list_master.pk_id = list_detail.list_master_id
                                                        WHERE
                                                        list_master.pk_id = 19") or die("ERR Get Area");
                                                        //fetch result
                                                        while ($rowArea = mysql_fetch_assoc($getArea)) {
                                                            //populate level combo
                                                            ?>
                                                            <option value="<?php echo $rowArea['pk_id']; ?>"
                                                                    <?php
                                                                    if ($rowArea['pk_id'] == $level) {
                                                                        echo "selected=selected";
                                                                    }
                                                                    ?>> <?php echo $rowArea['list_value']; ?> </option>
<?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="control-group">
                                                <label class="control-label" for="firstname"> &nbsp; </label>
                                                <div class="controls">
                                                    <button type="submit" class="btn btn-primary"
                                                            id="location_status">Show Status</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <?php
                                            if (isset($_GET) && !empty($_GET['area'])) {
                                                //select query
                                                //grts
                                                //item name
                                                //carton quantity
                                                //
                                                $qry = "SELECT
													A.itm_name,
													(A.placedQty / A.qty_carton) AS cartonQty
												FROM
													(
														SELECT
															itminfo_tab.itm_name,
															SUM(placements.quantity) AS placedQty,
															itminfo_tab.qty_carton
														FROM
															placements
														INNER JOIN stock_batch ON placements.stock_batch_id = stock_batch.batch_id
														INNER JOIN itminfo_tab ON stock_batch.item_id = itminfo_tab.itm_id
														INNER JOIN placement_config ON placements.placement_location_id = placement_config.pk_id
														WHERE
															placement_config.area = $area
														AND placement_config.`level` = $level
														GROUP BY
															itminfo_tab.itm_id
													) A
												WHERE
													A.placedQty > 0";
                                                //query result
                                                $qryRes = mysql_query($qry);
                                                $total = 0;
                                                while ($row = mysql_fetch_array($qryRes)) {
                                                    //total
                                                    $total += $row['cartonQty'];
                                                    //carton quantity
                                                    $cartonQty = (floor($row['cartonQty']) != $row['cartonQty']) ? number_format($row['cartonQty'], 2) : number_format($row['cartonQty']);
                                                    $arr[] = '<b>' . $row['itm_name'] . ': </b>' . $cartonQty;
                                                }
                                                echo implode(', ', $arr);
                                                $total = (floor($total) != $total) ? number_format($total, 2) : number_format($total);
                                                echo '<br><b>Total Cartons in Area: ' . $total . '</b>';
                                            }
                                            ?>
                                        </div>
                                        <div style="clear:both;"></div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- // Row END -->
<?php
if (isset($_GET) && !empty($_GET['area'])) {
    ?>
                    <div class="row">
                        <div class="col-md-12"> 

                            <!-- Widget -->
                            <div class="widget" data-toggle="collapse-widget">
                                <div class="widget-head">
                                    <h3 class="heading">Location Information</h3>
                                </div>
                                <div class="widget-body" style="overflow:auto;">
                                    
                                        <?php
                                        //check if result exists
                                        if ($getRowCount[0] > 0) {
                                            ?>
                                        <table style="border: none; width: 100%;">
                                            <?php
                                            //location found flag
                                            $locationFound = 1;
                                            $hit = 0;

                                            for ($rr = 1; $rr <= $getRowCount[0]; $rr++) {
                                                for ($cc = 1; $cc <= $getRackCount[0]; $cc++) {

                                                    for ($pp = 1; $pp < 5; $pp++) {
                                                        if ($hit == 0) {
                                                            $rowStatus = array();
                                                            //fetch result
                                                            $rowStatus[$Rowcounter] = mysql_fetch_assoc($getLocationStatus);
                                                            foreach ($rowStatus as $row):
                                                                //location id
                                                                $locid = $row['pk_id'];
                                                                //placement location id    
                                                                $plc_locid = $locid;
                                                                //location name    
                                                                $locname = $row['location_name'];
                                                                //row
                                                                $row1 = (int) $row['myrow'];
                                                                //rack
                                                                $rack = (int) $row['myrack'];
                                                                //pallet
                                                                $pallet = (int) $row['mypallet'];


                                                            endforeach;
                                                        }
                                                        if ($rr == $row1 && $cc == $rack && $pallet == $pp) {
                                                            $locArray[$rr][$cc][$pp] = $locname . "|" . $plc_locid;
                                                            $hit = 0;
                                                        } else {
                                                            $locArray[$rr][$cc][$pp] = "&nbsp;";
                                                            $hit = 5;
                                                        }
                                                    }

                                                    if ($hit == 0) {
                                                        $Rowcounter++;
                                                    }
                                                }
                                            }

                                            for ($a = 1; $a <= $getRowCount[0]; $a++):
                                                ?>
                                                <tr style="border: 3px solid green;" >
                                                        <?php
                                                        for ($x = 1; $x <= $getRackCount[0]; $x++):
                                                            ?>
                                                        <td style="width:<?php print round((100 / $getRackCount[0]), 2) . '%'; ?>; height:86px;padding: 4px; border-right: 4px solid green; border-left: 4px solid green;"><?php
                                            if ($locArray[$a][$x][1] != "&nbsp;" || $locArray[$a][$x][2] != "&nbsp;" ||
                                                    $locArray[$a][$x][3] != "&nbsp;" || $locArray[$a][$x][4] != "&nbsp;") {
                                                                ?>
                                                                <table style="border: 2px solid green; width:100%;">
                                                                    <tr>
                                                                        <td style="width:50%;border: 2px solid white; background-color: green;">
                                                                            <?php
                                                                            list($l1, $loc1) = explode('|', $locArray[$a][$x][1]);
                                                                            if (!empty($loc1)) {
                                                                                //url
                                                                                $url = "stock_location.php?loc_id=$loc1&area=$area&level=$level";
                                                                                ?>
                                                                                <a itemid="<?php echo $loc1; ?>" class="btn product-location  btn-link btn-mini" href="<?php echo $url; ?>"> <?php echo $l1; ?></a>
                                                                                <?php
                                                                            } else {
                                                                                ?>
                                                                                <span class="btn product-location  btn-link btn-mini" style="text-decoration:none; cursor:default;">&nbsp;</span>
                                                                                <?php
                                                                            }
                                                                            ?>
                                                                        </td>
                                                                        <td style="width:50%;border: 2px solid white; background-color: green;">
                                                                            <?php
                                                                            list($l1, $loc1) = explode('|', $locArray[$a][$x][2]);
                                                                            if (!empty($loc1)) {
                                                                                //url
                                                                                $url = "stock_location.php?loc_id=$loc1&area=$area&level=$level";
                                                                                ?>
                                                                                <a itemid="<?php echo $loc1; ?>" class="btn product-location  btn-link btn-mini" href="<?php echo $url; ?>"> <?php echo $l1; ?></a>
                                                                                <?php
                                                                            } else {
                                                                                ?>
                                                                                <span class="btn product-location  btn-link btn-mini" style="text-decoration:none; cursor:default;">&nbsp;</span>
                        <?php
                    }
                    ?>
                                                                        </td>
                                                                    </tr>
                                                                    <tr >
                                                                        <td style="width:50%;border: 2px solid white; background-color: green;">
                                                                            <?php
                                                                            list($l1, $loc1) = explode('|', $locArray[$a][$x][3]);
                                                                            if (!empty($loc1)) {
                                                                                //url
                                                                                $url = "stock_location.php?loc_id=$loc1&area=$area&level=$level";
                                                                                ?>
                                                                                <a itemid="<?php echo $loc1; ?>" class="btn product-location  btn-link btn-mini" href="<?php echo $url; ?>"> <?php echo $l1; ?></a>
                                                                                <?php
                                                                            } else {
                                                                                ?>
                                                                                <span class="btn product-location  btn-link btn-mini" style="text-decoration:none; cursor:default;">&nbsp;</span>
                                                                                <?php
                                                                            }
                                                                            ?>
                                                                        </td>
                                                                        <td style="width:50%;border: 2px solid white; background-color: green;">
                                                                            <?php
                                                                            list($l1, $loc1) = explode('|', $locArray[$a][$x][4]);
                                                                            if (!empty($loc1)) {
                                                                                //url
                                                                                $url = "stock_location.php?loc_id=$loc1&area=$area&level=$level";
                                                                                ?>
                                                                                <a itemid="<?php echo $loc1; ?>" class="btn product-location  btn-link btn-mini" href="<?php echo $url; ?>"> <?php echo $l1; ?></a>
                                                                                <?php
                                                                            } else {
                                                                                ?>
                                                                                <span class="btn product-location  btn-link btn-mini" style="text-decoration:none; cursor:default;">&nbsp;</span>
                        <?php
                    }
                    ?>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                    <?php } ?></td>
                                                <?php endfor; ?>
                                                </tr>
                                            <?php
                                        endfor;
                                        ?>
                                        </table>
                                        <?php
                                    } else {
                                        echo "No record found.";
                                    }
                                    ?>
                                </div>
                            </div>
                            <!-- Widget --> 
                        </div>
                    </div>
    <?php
}
?>
            </div>
        </div>
    </div>

    <!-- // Content END -->
<?php 
//include footer
include PUBLIC_PATH . "/html/footer.php"; ?>
    <script src="<?php echo PUBLIC_URL; ?>js/dataentry/stockplacement.js"></script>
</body>
<!-- END BODY -->
</html>

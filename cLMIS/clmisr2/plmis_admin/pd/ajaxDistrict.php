<?php
require_once("db.php");
?>
<option value="">Select District</option>
<?php
if (!empty($_POST['province_id'])) {
    $district = '';
    if (!empty($_POST['dist_id'])) {
        $district = $_POST['dist_id'];
    }
   $getAllDist = "SELECT tbl_locations.PkLocID, tbl_locations.LocName FROM tbl_locations WHERE tbl_locations.ParentID='" . $_POST['province_id'] . "' and tbl_locations.LocType = 4 AND PkLocID IN ( SELECT DISTINCT  tbl_warehouse.dist_id FROM  tbl_warehouse WHERE   prov_id='" . $_POST['province_id'] . "')";
   
    $rs1 = mysql_query($getAllDist) or die(mysql_error());
    while ($rowProv = mysql_fetch_assoc($rs1)) {
        ?>
        <option value="<?php echo $rowProv['PkLocID'] ?>" <?php if ($district == $rowProv['PkLocID']) {
            echo "selected=selected";
        } ?>><?php echo $rowProv['LocName'] ?></option>
        <?php
    }
}
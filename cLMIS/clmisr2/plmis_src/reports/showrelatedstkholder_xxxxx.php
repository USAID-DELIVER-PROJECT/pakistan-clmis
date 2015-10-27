<?php 
	include("../../html/adminhtml.inc.php");
	
	if (isset($_REQUEST['province']) && !empty($_REQUEST['province'])){
	$prov=$_REQUEST['province'];
	
	if ($prov=='all')
	{
		$whQry = "SELECT DISTINCT(stkname), stakeholder.stkid
						FROM stakeholder
						LEFT JOIN tbl_warehouse ON tbl_warehouse.stkid = stakeholder.stkid
						Where stakeholder.Parentid is null
						 ORDER BY stakeholder.stkid ASC";
	}
	else
	{
		$whQry = "SELECT DISTINCT(stkname), stakeholder.stkid
						FROM stakeholder
						LEFT JOIN tbl_warehouse ON tbl_warehouse.stkid = stakeholder.stkid
						WHERE stakeholder.Parentid is null and tbl_warehouse.prov_id=".$_REQUEST['province']." ORDER BY stakeholder.stkid ASC";
	}		
	//print $whQry;
				
		 $qryRes = mysql_query($whQry) or die(mysql_error());
		 $result = "";
		 $result .= '<select name="proWh" id="proWh" style="width:90px" class="input_select">';
         $result .= '<option value="all">All</option>';
		 
		 while($whRow = mysql_fetch_array($qryRes)) {
			if ($whRow['stkid'] == $_SESSION['PROSTKHOLDER']){
				$result .= "<option value=$whRow[stkid] selected=selected>$whRow[stkname]</option>";	 
			}else {
			 	$result .= "<option value=$whRow[stkid]>$whRow[stkname]</option>";
			}
		 }
		 $result .= "</select>";
		 echo $result;
	}
?>
<?php
$query_xmlw = "SELECT
					*
				FROM
					(
						SELECT
							tbl_warehouse.wh_name,
							stakeholder.stkname,
							itminfo_tab.itm_name,
							itminfo_tab.frmindex,
							tbl_wh_data.wh_obl_a,
							tbl_wh_data.wh_received,
							tbl_wh_data.wh_issue_up,
							tbl_wh_data.wh_adja,
							tbl_wh_data.wh_adjb,
							tbl_wh_data.wh_cbl_a,
							tbl_wh_data.last_update,
							tbl_warehouse.wh_rank,
							tbl_hf_type_rank.hf_type_rank,
							District.LocName AS district,
							Province.PkLocID,
							Province.LocName AS province
						FROM
							tbl_wh_data
						LEFT JOIN itminfo_tab ON tbl_wh_data.item_id = itminfo_tab.itmrec_id
						LEFT JOIN tbl_warehouse ON tbl_wh_data.wh_id = tbl_warehouse.wh_id
						LEFT JOIN tbl_hf_type_rank ON tbl_warehouse.hf_type_id = tbl_hf_type_rank.hf_type_id
						LEFT JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
						LEFT JOIN tbl_locations AS District ON tbl_warehouse.dist_id = District.PkLocID
						LEFT JOIN tbl_locations AS Province ON tbl_warehouse.prov_id = Province.PkLocID
						WHERE
							$where
						AND itminfo_tab.itm_category = 1
						UNION
							SELECT
								tbl_warehouse.wh_name,
								stakeholder.stkname,
								itminfo_tab.itm_name,
								itminfo_tab.frmindex,
								tbl_hf_data.opening_balance AS wh_obl_a,
								tbl_hf_data.received_balance AS wh_received,
								tbl_hf_data.issue_balance AS wh_issue_up,
								tbl_hf_data.adjustment_positive AS wh_adja,
								tbl_hf_data.adjustment_negative AS wh_adjb,
								tbl_hf_data.closing_balance AS wh_cbl_a,
								tbl_hf_data.last_update,
								tbl_warehouse.wh_rank,
								tbl_hf_type_rank.hf_type_rank,
								District.LocName AS district,
								Province.PkLocID,
								Province.LocName AS province
							FROM
								tbl_hf_data
							INNER JOIN itminfo_tab ON tbl_hf_data.item_id = itminfo_tab.itm_id
							INNER JOIN tbl_warehouse ON tbl_hf_data.warehouse_id = tbl_warehouse.wh_id
							INNER JOIN tbl_hf_type_rank ON tbl_warehouse.hf_type_id = tbl_hf_type_rank.hf_type_id
							INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
							INNER JOIN tbl_locations AS District ON tbl_warehouse.dist_id = District.PkLocID
							INNER JOIN tbl_locations AS Province ON tbl_warehouse.prov_id = Province.PkLocID
							WHERE
								$where1
								$where2
							AND itminfo_tab.itm_category = 1
					) A
				ORDER BY
					A.PkLocID,
					A.district,
					IF (A.wh_rank = '' OR A.wh_rank IS NULL, 1, 0),
					A.wh_rank,
				  	A.hf_type_rank ASC,
					A.frmindex ASC,
					A.wh_name ASC";

$result_xmlw = mysql_query($query_xmlw);
$xmlstore = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
$xmlstore .="<rows>";
$counter = 0;
$numOfRows = mysql_num_rows($result_xmlw);
if($numOfRows > 0){
	while($row_xmlw = mysql_fetch_array($result_xmlw)) {
		$lastModified = (empty($row_xmlw['last_update'])) ? '' : date('d/m/Y h:i A', strtotime($row_xmlw['last_update']));
		$xmlstore .="<row id=\"$counter\">";
		$xmlstore .= "<cell><![CDATA[".$row_xmlw['province']."]]></cell>";
		$xmlstore .= "<cell><![CDATA[".$row_xmlw['district']."]]></cell>";
		$xmlstore .= "<cell><![CDATA[".$row_xmlw['stkname']."]]></cell>";
		$xmlstore .= "<cell><![CDATA[".$row_xmlw['wh_name']."]]></cell>";
		$xmlstore .= "<cell><![CDATA[".$row_xmlw['itm_name']."]]></cell>";
		$xmlstore .="<cell>".number_format($row_xmlw['wh_obl_a'])."</cell>";
		$xmlstore .="<cell>".number_format($row_xmlw['wh_received'])."</cell>";
		$xmlstore .="<cell>".number_format($row_xmlw['wh_issue_up'])."</cell>";
		$xmlstore .="<cell>".number_format($row_xmlw['wh_adja'])."</cell>";
		$xmlstore .="<cell>".number_format($row_xmlw['wh_adjb'])."</cell>";
		$xmlstore .="<cell>".number_format($row_xmlw['wh_cbl_a'])."</cell>";
		$xmlstore .="<cell>".$lastModified."</cell>";
		$xmlstore .="</row>";
		$counter++;
	}
}
$xmlstore .="</rows>";
?>
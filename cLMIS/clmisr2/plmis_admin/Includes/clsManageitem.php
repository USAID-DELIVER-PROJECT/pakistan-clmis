<?php
class clsManageItem
{
	var $m_npkId;
	var $m_itm_id;
 	var $m_itm_name;
 	var $m_itm_type;
 	var $m_itm_category;
 	var $m_qty_carton;
 	var $m_field_color;
	var $m_itm_des;
	var $m_itm_status;
	var $m_frmindex;
	var $m_extra;
	var $m_stkid;
	var $m_item;
	var $string;
	var $number;


	function AddManageItem()
	{
		//if ($this->m_itm_id=='') $this->m_itm_id='NULL';
		if ($this->m_itm_name=='') $this->m_itm_name='NULL';
		if ($this->m_itm_type =='') $this->m_itm_type='NULL';
		if ($this->m_itm_category=='') $this->m_itm_category='NULL';
		if ($this->m_qty_carton=='') $this->m_qty_carton=0;
		//if ($this->m_field_color=='') $this->m_field_color='NULL';
		/*if ($this->m_itm_des=='') $this->m_itm_des='NULL';*/
		if ($this->m_itm_status=='') $this->m_itm_status='NULL';
		if ($this->m_frmindex=='') $this->m_frmindex=0;
		//if ($this->m_extra=='') $this->m_extra='NULL';

//itm_id,     '".$this->m_itm_id."',         
		 $strSql = "INSERT INTO itminfo_tab (itm_name,itm_type,itm_category,qty_carton,itm_des,itm_status,frmindex) VALUES ('".$this->m_itm_name."','".$this->m_itm_type."','".$this->m_itm_category."',".$this->m_qty_carton.",'".$this->m_itm_des."','".$this->m_itm_status."','".$this->m_frmindex."')";
	
	     //exit;
		$rsSql = mysql_query($strSql) or die("Error AddManageItembbbbbbbbbbb");
		$number=mysql_insert_id();
		
		$string='IT-';
		$string.=str_pad($number, 3, '0', STR_PAD_LEFT);
		
		$strSQL1="update itminfo_tab set itmrec_id='".$string."' where itm_id='".$number."'";
		mysql_query($strSQL1);
		
		
			if($number!=0)
			return $number;
		
		
	}
	
	function EditManageItem()
	{
			
		
	/*print "inside EditStakeholder";*/
	
//UPDATE itminfo_tab SET itmrec_id=".$this->m_npkId;  	

//$itm_id=",itm_id='".$this->m_itm_id."'";                             
//		if ($this->m_itm_id!='') $strSql .=$itm_id;
	
		$strSql = "UPDATE itminfo_tab SET itm_id=".$this->m_npkId;  
		
		
		
		$itm_name=",itm_name='".$this->m_itm_name."'";
		if ($this->m_itm_name!='') $strSql .=$itm_name;       
		
		$itm_type=",itm_type='".$this->m_itm_type."'";
		if ($this->m_itm_type !='') $strSql .=$itm_type;
		
		$itm_category=",itm_category='".$this->m_itm_category."'";
		if ($this->m_itm_category!='') $strSql .=$itm_category;
		
		$qty_carton=",qty_carton=".$this->m_qty_carton;
		if ($this->m_qty_carton!='') $strSql .=$qty_carton;
		
		$field_color=",field_color='".$this->m_field_color."'";
		if ($this->m_field_color!='') $strSql .=$field_color;
		
		$itm_des=",itm_des='".$this->m_itm_des."'";
	    $strSql .=$itm_des;
		
		$itm_status=",itm_status='".$this->m_itm_status."'";
		if ($this->m_itm_status!='') $strSql .=$itm_status;
		
		$frmindex=",frmindex=".$this->m_frmindex;
		if ($this->m_frmindex!='') $strSql .=$frmindex;
		
		$extra=",extra='".$this->m_extra."'";
		if ($this->m_extra!='') $strSql .=$extra;
		
		$strSql .=" WHERE itm_id=".$this->m_npkId;
		
		
		
		
		//print $strSql; 
//		exit;
		$rsSql = mysql_query($strSql) or die("Error EditManageItem");
		if(mysql_affected_rows())
			return $rsSql;
		else
			return FALSE;
	}
	function DeleteManageItem()
	{
		$strSql = "DELETE FROM  itminfo_tab WHERE itm_id=".$this->m_npkId;
		//print $strSql; 
		//exit;
		$rsSql = mysql_query($strSql) or die("Error DeleteManageItem");
		if(mysql_affected_rows())
			return TRUE;
		else
			return FALSE;
	}
	
	function GetAllManageItem()
	{
	
		$strSql = 	"SELECT
						itminfo_tab.itmrec_id,
						itminfo_tab.itm_id,
						itminfo_tab.itm_name,
						itminfo_tab.itm_type,
						itminfo_tab.itm_category,
						itminfo_tab.qty_carton,
						itminfo_tab.field_color,
						itminfo_tab.itm_des,
						itminfo_tab.itm_status,
						itminfo_tab.frmindex,
						itminfo_tab.extra
					FROM
						itminfo_tab
					INNER JOIN stakeholder_item ON itminfo_tab.itm_id = stakeholder_item.stk_item
					WHERE
						stakeholder_item.stkid = ".$_SESSION['userdata'][7]."
					AND itminfo_tab.itm_category = 1
					AND itminfo_tab.itm_status = 'Current'
					ORDER BY
						itminfo_tab.frmindex";
						//print $strSql;
		$rsSql = mysql_query($strSql) or die("Error GetAllManageItem");
		if(mysql_num_rows($rsSql)>0)
			return $rsSql;
		else
			return FALSE;
	}
	
	
	function GetManageItemById()
	{
			//$strSql = "
//				SELECT
//				itminfo_tab.itmrec_id,
//				itminfo_tab.itm_id,
//				itminfo_tab.itm_name,
//				itminfo_tab.itm_type,
//				itminfo_tab.itm_category,
//				itminfo_tab.qty_carton,
//				itminfo_tab.field_color,
//				stakeholder.stkname,
//				itminfo_tab.itm_des,
//				itminfo_tab.itm_status,
//				itminfo_tab.frmindex,
//				itminfo_tab.extra,
//				itminfo_tab.stkid
//				FROM
//				itminfo_tab
//				Left Join stakeholder ON stakeholder.stkid = itminfo_tab.stkid 
//				WHERE itm_id=".$this->m_npkId;
	
	//new query here
	
	
	         $strSql="SELECT
				itminfo_tab.itm_id,
				itminfo_tab.itm_name,
				itminfo_tab.itm_type,
				itminfo_tab.itm_category,
				itminfo_tab.qty_carton,
				itminfo_tab.itm_des,
				itminfo_tab.itm_status,
				itminfo_tab.frmindex,
				itminfo_tab.extra,
				stakeholder_item.stkid,
                stakeholder_item.stk_item,
				itemsofgroups.ItemID,
				itemsofgroups.GroupID
				
				FROM
				itminfo_tab
				Left Join stakeholder_item ON stakeholder_item.stk_item = itminfo_tab.itm_id
				Left Join itemsofgroups ON itemsofgroups.ItemID = itminfo_tab.itm_id 
				WHERE itminfo_tab.itm_id=".$this->m_npkId;
				
				;
	
//		$strSql = "
//				SELECT
//				itminfo_tab.itmrec_id,
//				itminfo_tab.itm_id,
//				itminfo_tab.itm_name,
//				itminfo_tab.itm_type,
//				itminfo_tab.itm_category,
//				itminfo_tab.qty_carton,
//				itminfo_tab.field_color,
//				itminfo_tab.itm_des,
//				itminfo_tab.itm_status,
//				itminfo_tab.frmindex,
//				itminfo_tab.extra
//				FROM
//				itminfo_tab 
//				WHERE itmrec_id=".$this->m_npkId;
//					print $strSql;
//					exit;
		$rsSql = mysql_query($strSql) or die("Error GetManageItemById");
		if(mysql_num_rows($rsSql)>0)
			return $rsSql;
		else
			return FALSE;
	}
function GetAllWHProduct()
	{
	 $strSql = "SELECT DISTINCT
		itminfo_tab.itm_id,
		itminfo_tab.itm_name
		FROM
		itminfo_tab
		INNER JOIN stakeholder_item ON stakeholder_item.stk_item = itminfo_tab.itm_id
		INNER JOIN stock_batch ON stock_batch.item_id = itminfo_tab.itm_id
		WHERE
		stakeholder_item.stkid = ".$_SESSION['userdata'][7]." 
		AND stock_batch.wh_id = ".$_SESSION['wh_id']." 
		AND stock_batch.status = 'Running' AND stock_batch.Qty > 0
		ORDER BY
		itminfo_tab.frmindex ASC";
		$rsSql  = mysql_query($strSql) or die("Error GetAllWHProduct");
		if (mysql_num_rows($rsSql) > 0) {
			while ($row = mysql_fetch_object($rsSql)) {
				$array[] = array(
				'id'  => $row->itm_id,
				'name'=> $row->itm_name
				);
			}
			return $array;
			}else {
			return false;
		}
	}
	function GetAllProduct()
	{
		$strSql = "SELECT
		itminfo_tab.itm_id,
		itminfo_tab.itm_name
		FROM
		itminfo_tab
		INNER JOIN stakeholder_item ON stakeholder_item.stk_item = itminfo_tab.itm_id
		WHERE
		stakeholder_item.stkid = 1
		ORDER BY
		itminfo_tab.frmindex ASC";
		$rsSql  = mysql_query($strSql) or die("Error GetAllProduct");
		if (mysql_num_rows($rsSql) > 0) {
			while ($row = mysql_fetch_object($rsSql)) {
				$array[] = array(
				'id'  => $row->itm_id,
				'name'=> $row->itm_name
				);
			}
			return $array;
			}else {
			return false;
		}
	}
 function GetProductName($item_id)
    {
        $strSql = "SELECT
        itminfo_tab.itm_name
        FROM
        itminfo_tab
        WHERE
        itminfo_tab.itm_id = $item_id";
        $rsSql = mysql_query($strSql) or die("Error GetProductCat");
        if (mysql_num_rows($rsSql) > 0) {
            $row = mysql_fetch_object($rsSql);
            return $row->itm_name;
        } else {
            return false;
        }
    }
function GetProductDoses($product)
	{
		$strSql = "SELECT
					itminfo_tab.itm_type
				FROM
					itminfo_tab
				WHERE
					itminfo_tab.itm_id = ".$product;
		
		$rsSql  = mysql_query($strSql) or die("Error GetManageItemById");
		
        if (mysql_num_rows($rsSql) > 0) {
            $row = mysql_fetch_object($rsSql);
            return $row->itm_type;
        } else {
            return false;
        }
	}
}
?>

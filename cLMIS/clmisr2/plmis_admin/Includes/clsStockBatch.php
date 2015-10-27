<?php

class clsStockBatch {

    public $batch_id;
    public $batch_no;
    public $batch_expiry;
    public $item_id;
    public $Qty;
    public $TranRef;
    public $BatchQty;
    public $status;
    public $production_date;
    public $vvm_type;
    public $unit_price;
    public $funding_source;
    public $wh_id;
    protected static $table_name = "stock_batch";
    protected static $db_fields = array('batch_no', 'batch_expiry', 'item_id', 'Qty', 'TranRef', 'BatchQty', 'status', 'production_date', 'vvm_type', 'unit_price', 'wh_id', 'funding_source');

    // Common Database Methods
    public static
            function find_all() {
        return self::find_by_sql("SELECT * FROM " . self::$table_name);
    }

    public static
            function find_by_id($id = 0) {
        $result_array = self::find_by_sql("SELECT * FROM " . self::$table_name . " WHERE batch_id={$id} LIMIT 1");
        return !empty($result_array) ? array_shift($result_array) : false;
    }

    public function search($item_id, $batch_no, $TranRef, $status) {
        switch ($status) {
            case 1:
                $status = 'Running';
                break;
            case 2:
                $status = 'Stacked';
                break;
            case 3:
                $status = 'Finished';
                break;
            default:
                break;
        }

        $sql = "SELECT
					stock_batch.batch_id,
					stock_batch.batch_no,
					stock_batch.batch_expiry,
					stock_batch.`status`,
					Sum(tbl_stock_detail.Qty) AS BatchQty,
					itminfo_tab.itm_name,
					itminfo_tab.qty_carton,
					tbl_itemunits.UnitType,
					tbl_warehouse.wh_name AS funding_source
				FROM
					tbl_stock_detail
				INNER JOIN tbl_stock_master ON tbl_stock_detail.fkStockID = tbl_stock_master.PkStockID
				INNER JOIN stock_batch ON stock_batch.batch_id = tbl_stock_detail.BatchID
				INNER JOIN itminfo_tab ON stock_batch.item_id = itminfo_tab.itm_id
				LEFT JOIN tbl_itemunits ON itminfo_tab.itm_type = tbl_itemunits.UnitType
				INNER JOIN tbl_trans_type ON tbl_stock_master.TranTypeID = tbl_trans_type.trans_id
				LEFT JOIN tbl_warehouse ON stock_batch.funding_source = tbl_warehouse.wh_id";

        if (!empty($item_id)) {
            $where[] = " stock_batch.item_id = " . $item_id;
        }
        if (!empty($batch_no)) {
            $where[] = " stock_batch.batch_no LIKE '" . $batch_no . "%'";
        }
        if (!empty($TranRef)) {
            $where[] = " tbl_stock_master.TranRef LIKE '%" . $TranRef . "%'";
        }
        if ($status == 4) {
            $where[] = " stock_batch.`status` IN ('Running', 'Stacked')";
        } else {
            $where[] = " stock_batch.`status` = '" . $status . "'";
        }
        if (!empty($this->funding_source)) {
            $where[] = " stock_batch.funding_source = ".$this->funding_source." ";
        }
        $where[] = " stock_batch.`wh_id` = " . $_SESSION['wh_id'] . "";

        // Finished means 0 quantity, We don't need this check for Finished Qty
        if ($status != 'Finished') {
            $where[] = " stock_batch.Qty <> 0";
        }

        if (!empty($where)) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }
		//$sql .= "AND tbl_trans_type.trans_nature <> '-' ";
        $sql .= " group by stock_batch.item_id, stock_batch.batch_id, stock_batch.batch_expiry, stock_batch.status ";

        /*echo $sql;
        exit;*/
        $result = mysql_query($sql);

        if (mysql_num_rows($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    public static
            function find_by_sql($sql = "") {
        $result_set = mysql_query($sql);
        $object_array = array();
        while ($row = mysql_fetch_array($result_set)) {
            $object_array[] = self::instantiate($row);
        }
        return $object_array;
    }

    public static
            function find_by_item($item_id) {
        //print $item_id;
          $sql = "SELECT
			stock_batch.batch_no,
			itminfo_tab.itm_name,
			itminfo_tab.itm_id,
			itminfo_tab.itm_type,
			sum(CASE WHEN stock_batch.`status` = 'Stacked' THEN stock_batch.Qty ELSE 0 END) as StackedQty,
			sum(CASE WHEN stock_batch.`status` = 'Running' THEN stock_batch.Qty ELSE 0 END) as RunningQty,
			sum(CASE WHEN stock_batch.`status` = 'Finished' THEN stock_batch.Qty ELSE 0 END) as FinishedQty,
			sum(CASE WHEN stock_batch.`status` = 'Stacked' THEN	1 ELSE 0 END) stacked,
			sum(CASE WHEN stock_batch.`status` = 'Running' THEN 1 ELSE 0 END) running,
			sum(CASE WHEN stock_batch.`status` = 'Finished' THEN 1 ELSE 0 END) finished
		FROM
			stock_batch
		INNER JOIN itminfo_tab ON stock_batch.item_id = itminfo_tab.itm_id
		WHERE
			stock_batch.item_id = " . $item_id . " AND stock_batch.wh_id = " . $_SESSION['wh_id'];

        $result_array = mysql_query($sql);

        $object_array = array();
        while ($row = mysql_fetch_object($result_array)) {
            $object_array[] = $row;
        }

        return !empty($object_array) ? array_shift($object_array) : false;
    }

    public static
            function count_all() {
        global $database;
        $sql = "SELECT COUNT(*) FROM " . self::$table_name;
        $result_set = $database->query($sql);
        $row = $database->fetch_array($result_set);
        return array_shift($row);
    }

    private static
            function instantiate($record) {
        // Could check that $record exists and is an array
        $object = new self;
        // Simple, long - form approach:
        // $object->id = $record['id'];
        // $object->username = $record['username'];
        // $object->password = $record['password'];
        // $object->first_name = $record['first_name'];
        // $object->last_name = $record['last_name'];
        // More dynamic, short - form approach:
        foreach ($record as $attribute => $value) {
            if ($object->has_attribute($attribute)) {
                $object->$attribute = $value;
            }
        }
        return $object;
    }

    private
            function has_attribute($attribute) {
        // We don't care about the value, we just want to know if the key exists
        // Will return true or false
        return array_key_exists($attribute, $this->attributes());
    }

    protected
            function attributes() {
        // return an array of attribute names and their values
        $attributes = array();
        foreach (self::$db_fields as $field) {
          //  if ($field != 'TranRef' && $field != 'BatchQty') {
                if (property_exists($this, $field)) {
                    if (!empty($this->$field)) {
                        $attributes[$field] = $this->$field;
                    }
                }
          //  }
        }
        return $attributes;
    }

    function sanitized_attributes() {
        global $database;
        $clean_attributes = array();
        // sanitize the values before submitting
        // Note: does not alter the actual value of each attribute

        foreach ($this->attributes() as $key => $value) {
             $clean_attributes[$key] = $database->escape_value($value);
        }
          
             return $clean_attributes;
    }

    public
            function save() {
        // A new record won't have an id yet.
        return isset($this->batch_id) ? $this->update() : $this->create();
    }

    public
            function create() {
        $batchid = $this->checkBatch();
        if ($batchid == FALSE) {
        
            global $database;
            // Don't forget your SQL syntax and good habits:
            // - INSERT INTO table (key, key) VALUES ('value', 'value')
            // - single - quotes around all values
            // - escape all values to prevent SQL injection
            $attributes = $this->sanitized_attributes();

            $sql = "INSERT INTO " . self::$table_name . " (";
            $sql .= join(", ", array_keys($attributes));
            $sql .= ") VALUES ('";
            $sql .= join("', '", array_values($attributes));
            $sql .= "')";
            //return $sql;
            if ($database->query($sql)) {
                //$this->batch_id = $database->insert_id();
                return $database->insert_id();
            } else {
                return false;
            }
        } else {
            return $batchid;
        }
    }

    public function update() {
        global $database;
        // Don't forget your SQL syntax and good habits:
        // - UPDATE table SET key = 'value', key = 'value' WHERE condition
        // - single - quotes around all values
        // - escape all values to prevent SQL injection
        $attributes = $this->sanitized_attributes();
        $attribute_pairs = array();
        foreach ($attributes as $key => $value) {
            $attribute_pairs[] = "{$key}='{$value}'";
        }
        $sql = "UPDATE " . self::$table_name . " SET ";
        $sql .= join(", ", $attribute_pairs);
        $sql .= " WHERE batch_id=" . $database->escape_value($this->batch_id);
        $database->query($sql);
        return ($database->affected_rows() == 1) ? true : false;
    }

    public function FindItemQtyByBatchId($wh_id, $batch_id, $item_id) {
        $strSql = "SELECT
			stock_batch.Qty,
			tbl_warehouse.wh_name,
			itminfo_tab.itm_type
			FROM
			stock_batch
			INNER JOIN tbl_warehouse ON stock_batch.wh_id = tbl_warehouse.wh_id
			INNER JOIN itminfo_tab ON stock_batch.item_id = itminfo_tab.itm_id
			WHERE
			stock_batch.wh_id = $wh_id AND
			stock_batch.batch_id = $batch_id AND
			stock_batch.item_id = $item_id";
        $rsSql = mysql_query($strSql) or die("Error: FindItemQtyByBatchId");
        if (mysql_num_rows($rsSql) > 0) {
            return $rsSql;
        } else {
            return FALSE;
        }
    }

    public
            function delete() {
        global $database;
        // Don't forget your SQL syntax and good habits:
        // - DELETE FROM table WHERE condition LIMIT 1
        // - escape all values to prevent SQL injection
        // - use LIMIT 1
        $sql = "DELETE FROM " . self::$table_name;
        $sql .= " WHERE batch_id=" . $database->escape_value($this->batch_id);
        $sql .= " LIMIT 1";
        $database->query($sql);
        return ($database->affected_rows() == 1) ? true : false;

        // NB: After deleting, the instance of User still
        // exists, even though the database entry does not.
        // This can be useful, as in:
        // echo $user->first_name . " was deleted";
        // but, for example, we can't call $user->update()
        // after calling $user->delete().
    }

    function checkBatch() {
		if( $_SESSION['wh_id'] == 123 ){
			$fundingSource = " AND stock_batch.funding_source = '".$this->funding_source."' ";
		}else{
			$fundingSource = '';
		}
        $strSql = "SELECT
					stock_batch.batch_no,
					stock_batch.batch_id
				FROM
					stock_batch
				WHERE
					stock_batch.batch_no = '" . $this->batch_no . "'
					AND stock_batch.item_id = '" . $this->item_id . "'
					AND stock_batch.wh_id = '" . $_SESSION['wh_id'] . "'
					$fundingSource
				LIMIT 1";

        $result_obj = mysql_query($strSql);
        if (mysql_num_rows($result_obj) > 0) {
            $row = mysql_fetch_object($result_obj);
            $batchid = $row->batch_id;
            $this->updateQty($batchid);
            return $batchid;
        } else {
            return FALSE;
        }
    }

    function checkBatchQuantity($batch_id) {
        $strSql = "SELECT
		stock_batch.Qty
		FROM
		stock_batch
		WHERE
		stock_batch.batch_id = " . $batch_id . " LIMIT 1";

        $result_obj = mysql_query($strSql);
        if (mysql_num_rows($result_obj) > 0) {
            $row = mysql_fetch_object($result_obj);
            $qty = $row->Qty;
            return $qty;
        } else {
            return -1;
        }
    }

    function updateQty($id) {
        $strSql = "UPDATE " . self::$table_name . " SET Qty=Qty+" . $this->Qty;
        $strSql .= " WHERE batch_id='" . $id . "'";
        $rsSql = mysql_query($strSql) or die("Error updateQty");
        if (mysql_affected_rows()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function adjustQtyByWh($batch_id, $wh_id) {
        $strSql = "SELECT AdjustQty($batch_id,$wh_id) from DUAL";
        $rsSql = mysql_query($strSql) or die("Error adjustQtyByWh".$rsSql);
        if (isset($rsSql) && mysql_num_rows($rsSql) > 0) {
            $row = mysql_fetch_array($rsSql);
            return $row[0];
        } else {
            return FALSE;
        }
    }

    function adjustQty($id, $qty) {
        $strSql = "UPDATE " . self::$table_name . " SET Qty=" . $qty;
        $strSql .= " WHERE batch_id='" . $id . "'";
        $rsSql = mysql_query($strSql) or die("Error adjustQty".$rsSql);
        if (mysql_affected_rows()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
	
    function editBatchNo($detail_id, $batch_no) {
		$wh_id = $_SESSION['wh_id'];
        $objStockDetail = new clsStockDetail();
        $QtyAndBatch = $objStockDetail->getQtyById($detail_id);
        $this->batch_no = $batch_no;
        $this->item_id = $QtyAndBatch['item_id'];
        $this->Qty = $QtyAndBatch['Qty'];
        $batch_id = $this->checkBatch();
		
		//exit($this->checkBatchQuantity($QtyAndBatch['BatchID']));
		
        if (!$batch_id) {
			if ($this->checkBatchQuantity($QtyAndBatch['BatchID']) <= 0)
			{
            	return $this->updateName($QtyAndBatch['BatchID'], $batch_no);
			}
			else
			{
				$this->wh_id = $wh_id;
				$batchInfo = $this->GetBatchExpiry($QtyAndBatch['BatchID']);
				$this->batch_expiry = $batchInfo['date'];
				$new_batch_id = $this->create();
           	 	$objStockDetail->updateDetail($QtyAndBatch['BatchID'], $new_batch_id, $detail_id);
				$this->adjustQtyByWh($QtyAndBatch['BatchID'], $wh_id);
			}
        } else {
            $objStockDetail->updateDetail($QtyAndBatch['BatchID'], $batch_id, $detail_id);
            $this->batch_id = $QtyAndBatch['BatchID'];
			$this->adjustQtyByWh($QtyAndBatch['BatchID'], $wh_id);
			
			// Check if Quantoty is Greater than 0 then delete it
			$batchInfo = $this->GetBatchExpiry($QtyAndBatch['BatchID']);
			if ( $batchInfo['qty'] == 0 )
			{
            	return $this->delete();
			}
			else
			{
				return true;
			}
        }
    }

    function updateName($id, $batch_no) {
        $strSql = "UPDATE " . self::$table_name . " SET batch_no='" . $batch_no . "'";
        $strSql .= " WHERE batch_id='" . $id . "'";
        $rsSql = mysql_query($strSql) or die("Error updateName");
        if (mysql_affected_rows()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function changeStatus($batch_id, $status) {
        $strSql = "UPDATE " . self::$table_name . " SET status='" . $status . "'";
        $strSql .= " WHERE batch_id=" . $batch_id;

        $rsSql = mysql_query($strSql) or die("Error changeStatus");
        if (mysql_affected_rows()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    function changeWh($batch_id){
        $strSql = "UPDATE " . self::$table_name . " SET wh_id=0 ";
        $strSql .= " WHERE batch_id=" . $batch_id;

        $rsSql = mysql_query($strSql) or die("Error changeWh");
        if (mysql_affected_rows()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function GetAllRunningBatches($item) {
        $wh_id = $_SESSION['wh_id'];
		if ( $wh_id == 123 ){
			$fundingSource = " AND stock_batch.funding_source = '".$this->funding_source."' ";
		}else{
			$fundingSource = '';
		}
       $strSql = "SELECT
						stock_batch.batch_no,
						stock_batch.batch_id,
						stock_batch.batch_expiry,
						stock_batch.item_id,
						SUM(tbl_stock_detail.Qty) AS Qty
					FROM
						stock_batch
					INNER JOIN tbl_stock_detail ON stock_batch.batch_id = tbl_stock_detail.BatchID
					WHERE
						stock_batch.Qty <> 0
					$fundingSource
					AND stock_batch.`status` = 'Running'
					AND stock_batch.item_id = $item
					AND stock_batch.wh_id = $wh_id
					AND tbl_stock_detail.temp = 0
					GROUP BY
						stock_batch.batch_no";

        $rsSql = mysql_query($strSql) or die("Error: GetAllRunningBatches");
        if (mysql_num_rows($rsSql) > 0) {
            return $rsSql;
        } else {
            return FALSE;
        }
    }

    function autoRunningLEFOBatch($product_id, $wh_id) {
        $expiry_date = $this->IsBatchExists($product_id, $wh_id);
        if ($expiry_date != false) {

            // Make stacked all the batches excluding the batches that user set to running
            $strSql = "UPDATE stock_batch
						SET STATUS = 'Stacked'
						WHERE
							item_id = $product_id
						AND wh_id = $wh_id
						AND stock_batch.Qty > 0
						AND batch_id NOT IN (
							SELECT
								*
							FROM
								(
									SELECT
										stock_batch.batch_id
									FROM
										stock_batch
									WHERE
										stock_batch.`status` = 'Running'
									AND stock_batch.wh_id = $wh_id
									AND stock_batch.item_id = $product_id
									UNION
										SELECT
											tbl_stock_detail.BatchID
										FROM
											tbl_stock_detail
										WHERE
											tbl_stock_detail.temp = 1
								) A
						)";
            $rsSql = mysql_query($strSql) or die("Error: autoStackedAllBatches");

            // Make Running near to expiry batch
            $strSql = "UPDATE stock_batch
					SET `status` = 'Running'
					WHERE
						item_id = $product_id
					AND wh_id = $wh_id
					AND batch_expiry = '$expiry_date'
					AND stock_batch.Qty > 0
					AND batch_id NOT IN (
						SELECT
							*
						FROM
							(
								SELECT
									stock_batch.batch_id
								FROM
									stock_batch
								WHERE
									stock_batch.`status` = 'Running'
								AND stock_batch.wh_id = $wh_id
								AND stock_batch.item_id = $product_id
								UNION
									SELECT
										tbl_stock_detail.BatchID
									FROM
										tbl_stock_detail
									WHERE
										tbl_stock_detail.temp = 1
							) A
					)
					LIMIT 1";
            $rsSql = mysql_query($strSql) or die("Error: autoRunningLEFOBatch");
            if ($rsSql) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    function IsBatchExists($product_id, $wh_id) {
        $strSql = "SELECT
		MIN(batch_expiry) AS MinDate
		FROM
		stock_batch
		WHERE
		item_id = $product_id
		AND wh_id = $wh_id
		AND Qty <> 0 AND batch_id Not IN(
                SELECT
                tbl_stock_detail.BatchID
                FROM
                tbl_stock_detail
                WHERE
                tbl_stock_detail.temp = 1) LIMIT 1";
        $rsSql = mysql_query($strSql) or die("Error: IsBatchExists");
        if (!empty($rsSql) && mysql_num_rows($rsSql) > 0) {
            $row = mysql_fetch_object($rsSql);
            return $row->MinDate;
        } else {
            return FALSE;
        }
    }

    function GetBatchExpiry($id) {
        $strSql = "SELECT
            stock_batch.batch_expiry,
            itminfo_tab.itm_category,
            SUM(tbl_stock_detail.Qty) as Qty
            FROM
            stock_batch
            INNER JOIN itminfo_tab ON stock_batch.item_id = itminfo_tab.itm_id
            INNER JOIN tbl_stock_detail ON stock_batch.batch_id = tbl_stock_detail.BatchID
            WHERE
            stock_batch.batch_id = $id";
        /*echo $strSql;
        exit;*/
        $rsSql = mysql_query($strSql) or die("Error: GetBatchExpiry");
        if (mysql_num_rows($rsSql) > 0) {
            $row = mysql_fetch_object($rsSql);
            $data = array(
                'date' => $row->batch_expiry,
                'qty' => $row->Qty,
                'cat' => $row->itm_category
            );
            return $data;
        } else {
            return FALSE;
        }
    }

    function editBatchExpiry($id, $date) {
        $strSql = "UPDATE " . self::$table_name . " SET batch_expiry='" . $date . "'";
        $strSql .= " WHERE batch_id='" . $id . "'";
        $rsSql = mysql_query($strSql) or die("Error editBatchExpiry");
        if (mysql_affected_rows()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}

?>
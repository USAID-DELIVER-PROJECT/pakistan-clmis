<?php

// If it's going to need the database, then it's
// probably smart to require it before we start.
class clsStockDetail {

    protected static $table_name = "tbl_stock_detail";
    protected static $db_fields = array('fkStockID', 'BatchID', 'fkUnitID', 'Qty', 'temp', 'vvm_stage', 'IsReceived', 'adjustmentType', 'manufacturer');
    public $PkDetailID;
    public $fkStockID;
    public $BatchID;
    public $fkUnitID;
    public $Qty;
    public $temp;
    public $vvmstage;
    public $IsReceived;
    public $adjustmentType;
    public $manufacturer;

    // Common Database Methods
    public static
            function find_all() {
        return self::find_by_sql("SELECT * FROM " . self::$table_name);
    }

    public static
            function find_by_id($id = 0) {
        $result_array = self::find_by_sql("SELECT * FROM " . self::$table_name . " WHERE PkDetailID={$id} LIMIT 1");
        return !empty($result_array) ? array_shift($result_array) : false;
    }

    public
            function find_by_stock_id($id = 0) {
        $strSql = "SELECT
        tbl_stock_detail.PkDetailID,
        tbl_stock_detail.fkStockID,
        tbl_stock_detail.BatchID,
        tbl_stock_detail.fkUnitID,
        tbl_stock_detail.Qty,
        tbl_stock_detail.temp,
        tbl_stock_detail.vvm_stage,
        tbl_stock_detail.IsReceived,
        tbl_stock_detail.adjustmentType,
        tbl_stock_master.WHIDTo,
		tbl_stock_master.WHIDFrom,
		tbl_stock_master.issued_by
        FROM
        tbl_stock_detail
        INNER JOIN tbl_stock_master ON tbl_stock_detail.fkStockID = tbl_stock_master.PkStockID
        WHERE
        tbl_stock_master.PkStockID = $id";
        $rsSql = mysql_query($strSql) or die("Error: find_by_stock_id: " . mysql_error());
        if (mysql_num_rows($rsSql) > 0) {
            return $rsSql;
        } else {
            return FALSE;
        }
    }

    public
            function find_by_detail_id($id = 0) {
        $strSql = "SELECT
        tbl_stock_detail.PkDetailID,
        tbl_stock_detail.fkStockID,
        tbl_stock_detail.BatchID,
        tbl_stock_detail.fkUnitID,
        tbl_stock_detail.Qty,
        tbl_stock_detail.temp,
        tbl_stock_detail.vvm_stage,
        tbl_stock_detail.IsReceived,
        tbl_stock_detail.adjustmentType,
        tbl_stock_master.WHIDTo,
		tbl_stock_master.WHIDFrom
        FROM
        tbl_stock_detail
        INNER JOIN tbl_stock_master ON tbl_stock_detail.fkStockID = tbl_stock_master.PkStockID
        WHERE
        tbl_stock_detail.PkDetailID = $id";
        $rsSql = mysql_query($strSql) or die("Error find_by_detail_id" . $strSql);
        if (mysql_num_rows($rsSql) > 0) {
            return $rsSql;
        } else {
            return FALSE;
        }
    }

    public function StockReceived($id) {
        $strSql = "Update " . self::$table_name . " set isReceived=1 where PkDetailID=$id";
        $rsSql = mysql_query($strSql) or die("Error StockReceived");
        if (mysql_affected_rows() > 0) {
            return true;
        } else {
            return FALSE;
        }
    }

    public function updateDetail($oldid, $new_batch_id, $stk_detail_id) {
        mysql_query("Update " . self::$table_name . " set BatchID=$new_batch_id where PkDetailID = ".$stk_detail_id." ") or die("Error updateDetail");
        if (mysql_affected_rows() > 0) {
            $row = mysql_fetch_array(mysql_query("SELECT
                    SUM(tbl_stock_detail.Qty) as quantity
                    FROM
                    tbl_stock_detail
                    INNER JOIN stock_batch ON tbl_stock_detail.BatchID = stock_batch.batch_id
                    WHERE
                    stock_batch.wh_id = " . $_SESSION['wh_id'] . " AND
                    tbl_stock_detail.BatchID = $new_batch_id"));
            mysql_query("Update stock_batch set Qty=" . $row['quantity'] . " where batch_id = $new_batch_id");
            return true;
        } else {
            return FALSE;
        }
    }

    public function deleteReceive($id) {
         $result = $this->getQtyById($id);
        $qty = $result['Qty'];
        $batch_id = $result['BatchID'];
        $stock_id = $result['fkStockID'];

        $objWarehouseData = new clsWarehouseData();
        $objWarehouseData->detail_id = $id;
        $params = $objWarehouseData->getStockReportParams();
         $bQty = $this->getBatchQtyById($batch_id);

        $objStockBatch = new clsStockBatch();
        if ($bQty == $qty) {
            //$objStockBatch->batch_id = $batch_id;
            //$objStockBatch->delete();
            $objStockBatch->adjustQty($batch_id, "Qty-$qty");
            $objStockBatch->changeWh($batch_id);
        } else if ($bQty > $qty) {
            $objStockBatch->adjustQty($batch_id, "Qty-$qty");
        }

        $this->PkDetailID = $id;
        $del = $this->delete($id);

        $objStockMaster = new clsStockMaster();
        $objStockMaster->PkStockID = $stock_id;
        $objStockMaster->delete();

        $objWarehouseData->report_month = $params['month'];
        $objWarehouseData->report_year = $params['year'];
        $objWarehouseData->item_id = $params['item_id'];
        $objWarehouseData->wh_id = $params['wh_id'];
        $objWarehouseData->created_by = $params['created_by'];
        $objWarehouseData->adjustStockReport();
        //$objWarehouseData->adjustReport();

        if ($del) {
            return true;
        } else {
            return FALSE;
        }
    }

    public function editReceive($id, $uQty) {
        $result = $this->getQtyById($id);
        $dQty = $result['Qty'];
        $batch_id = $result['BatchID'];
        $bQty = $this->getBatchQtyById($batch_id);

        $objStockBatch = new clsStockBatch();
        if ($uQty == $dQty) {
            return true;
        } else if ($uQty < $dQty) {
            $resultQty = $dQty - $uQty;
            $objStockBatch->adjustQty($batch_id, "Qty-$resultQty");
            return $this->adjustQty($id, $uQty);
        } else if ($uQty > $dQty) {
            $resultQty = $uQty - $dQty;
            $objStockBatch->adjustQty($batch_id, "Qty+$resultQty");
            return $this->adjustQty($id, $uQty);
        }
    }

    public function deleteIssue($id) {
        $result = $this->getQtyById($id);
        $qty = abs($result['Qty']);
        $batch_id = $result['BatchID'];
        $stock_id = $result['fkStockID'];

        $objWarehouseData = new clsWarehouseData();
        $objWarehouseData->detail_id = $id;
        $params = $objWarehouseData->getStockReportParams();

        $objStockBatch = new clsStockBatch();
        $objStockBatch->adjustQty($batch_id, "Qty+$qty");
        $objStockBatch->changeStatus($batch_id, 'Running');

        $this->PkDetailID = $id;
        $del = $this->delete($id);

        $objStockMaster = new clsStockMaster();
        $objStockMaster->PkStockID = $stock_id;
        $objStockMaster->delete();

        $objWarehouseData->report_month = $params['month'];
        $objWarehouseData->report_year = $params['year'];
        $objWarehouseData->item_id = $params['item_id'];
        $objWarehouseData->wh_id = $params['wh_id'];
        $objWarehouseData->created_by = $params['created_by'];
        $objWarehouseData->adjustStockReport();
        //$objWarehouseData->adjustReport();

        if ($del) {
            return true;
        } else {
            return FALSE;
        }
    }

    public function editIssue($id, $uQty) {
        $result = $this->getQtyById($id);
        $dQty = abs($result['Qty']);
        $batch_id = $result['BatchID'];
        $bQty = $this->getBatchQtyById($batch_id);

        $objStockBatch = new clsStockBatch();
        if ($uQty == $dQty) {
            return true;
        } else if ($uQty < $dQty) {
            $resultQty = $dQty - $uQty;
            $objStockBatch->adjustQty($batch_id, "Qty+$resultQty");
            return $this->adjustQty($id, "-" . $uQty);
        } else if ($uQty > $dQty) {
            $resultQty = $uQty - $dQty;
            $objStockBatch->adjustQty($batch_id, "Qty-$resultQty");
            return $this->adjustQty($id, "-" . $uQty);
        }
    }

    function adjustQty($id, $qty) {
        $strSql = "UPDATE " . self::$table_name . " SET Qty=" . $qty;
        $strSql .= " WHERE PkDetailID='" . $id . "'";
        $rsSql = mysql_query($strSql) or die("Error adjustQty");
        if (mysql_affected_rows()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function getQtyById($id) {
        $strSql = "SELECT
                tbl_stock_detail.Qty,
                tbl_stock_detail.BatchID,
                tbl_stock_detail.fkStockID,
                stock_batch.item_id
                FROM
                " . self::$table_name . "
                INNER JOIN stock_batch ON tbl_stock_detail.BatchID = stock_batch.batch_id
                where PkDetailID=$id";
        $rsSql = mysql_query($strSql) or die("Error getQtyById");
        if (!empty($rsSql) && mysql_num_rows($rsSql) > 0) {
            $row = mysql_fetch_object($rsSql);
            return array(
                'Qty' => $row->Qty,
                'BatchID' => $row->BatchID,
                'fkStockID' => $row->fkStockID,
                'item_id' => $row->item_id
            );
        } else {
            return FALSE;
        }
    }

    public function getBatchQtyById($id) {
       $strSql = "Select Qty from stock_batch where batch_id=$id";
        $rsSql = mysql_query($strSql) or die("Error getBatchQtyById");
        if (!empty($rsSql) && mysql_num_rows($rsSql) > 0) {
            $row = mysql_fetch_object($rsSql);
            return $row->Qty;
        } else {
            return FALSE;
        }
    }

    public function GetBatchDetail($id) {
        $strSql = "SELECT
			stock_batch.batch_no,
			stock_batch.batch_expiry,
			stock_batch.item_id,
			stock_batch.unit_price,
			stock_batch.production_date,
			stock_batch.vvm_type,
			tbl_stock_detail.Qty
			FROM
			tbl_stock_detail
			INNER JOIN stock_batch ON tbl_stock_detail.BatchID = stock_batch.batch_id
			WHERE
			tbl_stock_detail.PkDetailID = $id";
        $rsSql = mysql_query($strSql) or die("Error GetBatchDetail");
        if (mysql_num_rows($rsSql) > 0) {
            return mysql_fetch_object($rsSql);
        } else {
            return FALSE;
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
            if (property_exists($this, $field)) {
                if (!empty($this->$field) || $this->$field == 0) {
                    $attributes[$field] = $this->$field;
                }
            }
        }
        return $attributes;
    }

    protected
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
        return isset($this->PkDetailID) ? $this->update() : $this->create();
    }

    public
            function create() {
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
		
		/*echo $sql;
		exit;*/

        if ($database->query($sql)) {
            //$this->PkDetailID = $database->insert_id();
            return $database->insert_id();
        } else {
            return false;
        }
    }

    public
            function update() {
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
        $sql .= " WHERE PkDetailID=" . $database->escape_value($this->PkDetailID);
        $database->query($sql);
        return ($database->affected_rows() == 1) ? true : false;
    }

    function updateTemp($id) {
         $strSql = "Update tbl_stock_detail set temp=0 where fkStockID=$id";
        $rsSql = mysql_query($strSql) or die("Error updateTemp");
        if (mysql_affected_rows() > 0) {
             $strSql2 = "SELECT DISTINCT
                                itminfo_tab.itm_id,
                                tbl_stock_master.WHIDTo
                        FROM
                                tbl_stock_detail
                        INNER JOIN stock_batch ON tbl_stock_detail.BatchID = stock_batch.batch_id
                        INNER JOIN itminfo_tab ON stock_batch.item_id = itminfo_tab.itm_id
                        INNER JOIN tbl_stock_master ON tbl_stock_master.PkStockID = tbl_stock_detail.fkStockID
                        WHERE
                                tbl_stock_detail.fkStockID = $id";
            $rsSql2 = mysql_query($strSql2);
            if (!empty($rsSql2) && mysql_num_rows($rsSql2) > 0) {
                while ($row = mysql_fetch_object($rsSql2)) {
                    $objStockBatch = new clsStockBatch();
                    $objStockBatch->autoRunningLEFOBatch($row->itm_id, $row->WHIDTo);
                }
            }
            return true;
        } else {
            return FALSE;
        }
    }

    public
            function updatefk() {
        global $database;
        $sql = "UPDATE " . self::$table_name . " SET ";
        $sql .= "temp = 0";
        $sql .= " WHERE fkStockID=" . $database->escape_value($this->fkStockID);
        $database->query($sql);
        return ($database->affected_rows() == 1) ? true : false;
    }

    public
            function delete() {
        global $database;
        // Don't forget your SQL syntax and good habits:
        // - DELETE FROM table WHERE condition LIMIT 1
        // - escape all values to prevent SQL injection
        // - use LIMIT 1
        $sql = "DELETE FROM " . self::$table_name;
        $sql .= " WHERE PkDetailID=" . $database->escape_value($this->PkDetailID);
        $sql .= " LIMIT 1";
        $database->query($sql);
        return ($database->affected_rows() == 1) ? true : false;

        // NB: After deleting, the instance of User still
        // exists, even though the database entry does not.
        // This can be useful, as in:
        //   echo $user->first_name . " was deleted";
        // but, for example, we can't call $user->update()
        // after calling $user->delete().
    }

}

?>
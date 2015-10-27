<?php
// If it's going to need the database, then it's
// probably smart to require it before we start.
class clsStockMaster {

    protected static $table_name = "tbl_stock_master";
    protected static $db_fields = array('TranDate', 'TranNo', 'TranTypeID', 'TranRef', 'WHIDFrom', 'WHIDTo', 'CreatedBy', 'CreatedOn', 'ReceivedRemarks', 'temp', 'trNo', 'LinkedTr', 'issued_by');
    public $PkStockID;
    public $TranDate;
    public $TranNo;
    public $TranTypeID;
    public $TranRef;
    public $WHIDFrom;
    public $WHIDTo;
    public $CreatedBy;
    public $CreatedOn;
    public $ReceivedRemarks;
    public $temp;
    public $trNo;
    public $batch_no;
    public $item_id;
    public $fromDate;
    public $toDate;
    public $LinkedTr;
    public $stakeholder;
    public $province;
    public $issued_by;
    public $funding_source;

    // Common Database Methods
    public
            function find_all() {
        return self::find_by_sql("SELECT * FROM " . self::$table_name);
    }

    public
            function find_by_id($id = 0) {
        $strSql = "SELECT * FROM " . self::$table_name . " WHERE PkStockID={$id} LIMIT 1";
        $result_array = self::find_by_sql($strSql);
        return !empty($result_array) ? array_shift($result_array) : false;
    }

    public
            function find_by_trans_no($trans_no = '') {
        $result_array = self::find_by_sql("SELECT * FROM " . self::$table_name . " WHERE TranNo='{$trans_no}' LIMIT 1 DESC");
        return !empty($result_array) ? array_shift($result_array) : false;
    }

    public
            function find_by_sql($sql = "") {
        $result_set = mysql_query($sql);
        $object_array = array();
        while ($row = mysql_fetch_array($result_set)) {
            $object_array[] = self::instantiate($row);
        }
        return $object_array;
    }

    public
            function count_all() {
        global $database;
        $sql = "SELECT COUNT(*) FROM " . self::$table_name;
        $result_set = $database->query($sql);
        $row = $database->fetch_array($result_set);
        return array_shift($row);
    }

    private
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
                $attributes[$field] = $this->$field;
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
        return isset($this->PkStockID) ? $this->update() : $this->create();
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
        /*print $sql;
        exit;*/ 
        if ($database->query($sql)) {
            //$this->PkStockID = $database->insert_id();
            return $database->insert_id();
        } else {
            return false;
        }
    }

    public
            function update() {
        global $database;
        $sql = "UPDATE " . self::$table_name . " SET ";
        $sql .= " temp=0";
        $sql .= " WHERE PkStockID=" . $database->escape_value($this->PkStockID);
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
        if (!$this->stockExists() && ($this->PkStockID)) {
            $sql = "DELETE FROM " . self::$table_name;
            $sql .= " WHERE PkStockID=" . $database->escape_value($this->PkStockID);
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

    function stockExists() {
        $strSql = "SELECT
            tbl_stock_detail.PkDetailID
            FROM
            tbl_stock_detail
            WHERE
            tbl_stock_detail.fkStockID = " . $this->PkStockID;
        $rsSql = mysql_query($strSql) or die("Error stockExists");
        if (mysql_num_rows($rsSql) > 0) {
            return true;
        } else {
            return false;
        }
    }

    function GetTempStockIssue($user, $wh_id, $type) {
        $strSql = "SELECT
					tbl_stock_master.TranNo,
					tbl_stock_master.TranRef,
					tbl_stock_master.TranDate,
					tbl_stock_master.PkStockID,
					CONCAT(tbl_warehouse.wh_name, ' (', stakeholder.stkname, ')') AS wh_name,
					tbl_stock_master.WHIDTo,
					tbl_stock_master.issued_by,
					stock_batch.funding_source
				FROM
					tbl_stock_master
				INNER JOIN tbl_stock_detail ON tbl_stock_master.PkStockID = tbl_stock_detail.fkStockID
				INNER JOIN stock_batch ON tbl_stock_detail.BatchID = stock_batch.batch_id
				INNER JOIN tbl_warehouse ON tbl_warehouse.wh_id = tbl_stock_master.WHIDTo
				INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
				WHERE
					tbl_stock_master.CreatedBy = $user
				AND tbl_stock_master.WHIDFrom = $wh_id
				AND tbl_stock_master.temp = 1
				AND tbl_stock_master.TranTypeID = $type";

		/*print $strSql;
		exit;*/
		$rsSql = mysql_query($strSql) or die("Error GetTempStockIssue");
        if (mysql_num_rows($rsSql) > 0) {
            return $rsSql;
        } else {
            return FALSE;
        }
    }

    function GetStocksIssueList($userid, $wh_id, $type, $stockid) {
        $strSql = "SELECT
					tbl_stock_master.TranDate,
					tbl_stock_detail.Qty,
					stock_batch.batch_no,
					stock_batch.production_date,
					stock_batch.batch_expiry,
					itminfo_tab.itm_name,
					itminfo_tab.qty_carton,
					tbl_warehouse.wh_id,
					CONCAT(tbl_warehouse.wh_name, ' (', stakeholder.stkname, ')') AS wh_name,
					tbl_itemunits.UnitType,
					tbl_stock_master.PkStockID,
					tbl_stock_master.TranNo,
					tbl_stock_master.TranRef,
					list_detail.list_value AS issued_by,
					tbl_stock_detail.PkDetailID
				FROM
					tbl_stock_master
				INNER JOIN tbl_stock_detail ON tbl_stock_master.PkStockID = tbl_stock_detail.fkStockID
				INNER JOIN stock_batch ON tbl_stock_detail.BatchID = stock_batch.batch_id
				INNER JOIN itminfo_tab ON stock_batch.item_id = itminfo_tab.itm_id
				INNER JOIN tbl_warehouse ON tbl_stock_master.WHIDTo = tbl_warehouse.wh_id
				INNER JOIN tbl_itemunits ON itminfo_tab.itm_type = tbl_itemunits.UnitType
				INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
				LEFT JOIN list_detail ON tbl_stock_master.issued_by = list_detail.pk_id
				WHERE
					tbl_stock_detail.temp = 0 AND
					tbl_stock_master.temp = 0 AND
					tbl_stock_master.WHIDFrom = '" . $wh_id . "' AND
					tbl_stock_master.CreatedBy = " . $userid . " AND 
					tbl_stock_master.TranTypeID =" . $type . " AND tbl_stock_master.PkStockID = $stockid
				ORDER BY
					itminfo_tab.frmindex ASC";
        $rsSql = mysql_query($strSql) or die("Error GetStocksIssueList");
        if (mysql_num_rows($rsSql) > 0) {
            return $rsSql;
        } else {
            return FALSE;
        }
    }

    function updateTemp($id) {
        $strSql = "Update tbl_stock_master set temp=0 where PkStockID=$id";
        $rsSql = mysql_query($strSql) or die("Error updateTemp");
        if (mysql_affected_rows() > 0) {
            return true;
        } else {
            return FALSE;
        }
    }

    function GetTempStocksIssueList($userid, $wh_id, $type) {
        $strSql = "SELECT
        tbl_stock_master.TranDate,
        tbl_stock_detail.Qty,
        stock_batch.batch_no,
		stock_batch.unit_price,
        stock_batch.batch_expiry,
        itminfo_tab.itm_name,
		itminfo_tab.qty_carton,
        CONCAT(tbl_warehouse.wh_name, ' (', stakeholder.stkname, ')') AS wh_name,
        tbl_itemunits.UnitType,
        tbl_stock_master.PkStockID,
        tbl_stock_master.TranNo,
        tbl_stock_master.TranRef,
		tbl_stock_detail.PkDetailID
        FROM
        tbl_stock_master
        INNER JOIN tbl_stock_detail ON tbl_stock_master.PkStockID = tbl_stock_detail.fkStockID
        INNER JOIN stock_batch ON tbl_stock_detail.BatchID = stock_batch.batch_id
        INNER JOIN itminfo_tab ON stock_batch.item_id = itminfo_tab.itm_id
        INNER JOIN tbl_warehouse ON tbl_stock_master.WHIDTo = tbl_warehouse.wh_id
        INNER JOIN tbl_itemunits ON itminfo_tab.itm_type = tbl_itemunits.UnitType
		INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
        WHERE
        tbl_stock_detail.temp = 1 AND
        tbl_stock_master.temp = 1 AND
        tbl_stock_master.WHIDFrom = '" . $wh_id . "' AND
        tbl_stock_master.CreatedBy = " . $userid . " AND 
		tbl_stock_master.TranTypeID =" . $type ." 
		ORDER BY itminfo_tab.frmindex ASC";

        $rsSql = mysql_query($strSql) or die("Error GetTempStocksIssueList");
        if (mysql_num_rows($rsSql) > 0) {
            return $rsSql;
        } else {
            return FALSE;
        }
    }

    function GetTempStockReceive($user, $wh_id, $type) {
        $strSql = "SELECT
			tbl_stock_master.TranNo,
			tbl_stock_master.TranRef,
			tbl_stock_master.TranDate,
			tbl_stock_master.PkStockID,
			tbl_warehouse.wh_name,
			tbl_stock_master.WHIDFrom
		FROM
			tbl_stock_master
		INNER JOIN tbl_warehouse ON tbl_warehouse.wh_id = tbl_stock_master.WHIDTo
		WHERE
			tbl_stock_master.CreatedBy = $user
		AND tbl_stock_master.WHIDTo = $wh_id
		AND tbl_stock_master.temp = 1
		AND tbl_stock_master.TranTypeID = $type";

        $rsSql = mysql_query($strSql) or die("Error GetTempStockReceive");
        if (mysql_num_rows($rsSql) > 0) {
            return $rsSql;
        } else {
            return FALSE;
        }
    }

    function GetTempStockRUpdate($PkStockID = 0) {
        $strSql = "SELECT
			tbl_stock_master.PkStockID,
			tbl_stock_master.TranNo,
			tbl_stock_master.TranRef,
			tbl_stock_master.TranDate,
			tbl_stock_master.PkStockID,
			tbl_warehouse.wh_name,
			itminfo_tab.itm_id,
			tbl_stock_master.WHIDFrom
		FROM
			tbl_stock_master
		INNER JOIN tbl_warehouse ON tbl_warehouse.wh_id = tbl_stock_master.WHIDFrom
		 INNER JOIN tbl_stock_detail ON tbl_stock_master.PkStockID = tbl_stock_detail.fkStockID
        INNER JOIN stock_batch ON tbl_stock_detail.BatchID = stock_batch.batch_id
		INNER JOIN itminfo_tab ON stock_batch.item_id = itminfo_tab.itm_id
		WHERE tbl_stock_master.PkStockID = $PkStockID";

        $rsSql = mysql_query($strSql) or die("Error GetTempStockRUpdate");
        if (mysql_num_rows($rsSql) > 0) {
            return $rsSql;
        } else {
            return FALSE;
        }
    }

    function GetTempStocksReceiveList($userid, $wh_id, $type) {
        $strSql = "
        SELECT
			tbl_stock_master.TranDate,
			tbl_stock_detail.Qty,
			tbl_stock_detail.manufacturer AS manufacturer,
			stock_batch.batch_no,
			stock_batch.production_date,
			stock_batch.batch_expiry,
			itminfo_tab.itm_name,
			itminfo_tab.qty_carton,
			tbl_warehouse.wh_name,
			tbl_stock_master.PkStockID,
			tbl_stock_master.TranNo,
			tbl_stock_master.TranRef,
			tbl_stock_detail.PkDetailID,
			tbl_itemunits.UnitType
		FROM
			tbl_stock_master
		INNER JOIN tbl_stock_detail ON tbl_stock_master.PkStockID = tbl_stock_detail.fkStockID
		LEFT JOIN stakeholder ON tbl_stock_detail.manufacturer = stakeholder.stkid
		LEFT JOIN stakeholder_item ON stakeholder.stkid = stakeholder_item.stkid
		INNER JOIN stock_batch ON tbl_stock_detail.BatchID = stock_batch.batch_id
		INNER JOIN itminfo_tab ON stock_batch.item_id = itminfo_tab.itm_id
		INNER JOIN tbl_warehouse ON tbl_stock_master.WHIDFrom = tbl_warehouse.wh_id
		INNER JOIN tbl_itemunits ON  itminfo_tab.itm_type= tbl_itemunits.UnitType
		WHERE
			tbl_stock_detail.temp = 1 AND
			tbl_stock_master.temp = 1 AND
			tbl_stock_master.WHIDTo = '" . $wh_id . "' AND
			tbl_stock_master.CreatedBy = " . $userid . " AND 
			tbl_stock_master.TranTypeID =" . $type ."
		ORDER BY itminfo_tab.frmindex ASC ";


        $rsSql = mysql_query($strSql) or die("Error GetTempStocksReceiveList");
        if (mysql_num_rows($rsSql) > 0) {
            return $rsSql;
        } else {
            return FALSE;
        }
    }

    function GetLastInseredTempStocksReceiveList($userid, $wh_id, $type) {
        $strSql = "SELECT
        tbl_stock_master.TranDate,
        tbl_stock_detail.Qty,
        tbl_stock_detail.manufacturer,
        stock_batch.batch_no,
        stock_batch.production_date,
		stock_batch.batch_expiry,
        itminfo_tab.itm_name,
        tbl_warehouse.wh_name,
        tbl_stock_master.PkStockID,
        tbl_stock_master.TranNo,
        tbl_stock_master.TranRef,
		tbl_stock_detail.PkDetailID,
		itminfo_tab.itm_id,
		stock_batch.unit_price
        FROM
        tbl_stock_master
        INNER JOIN tbl_stock_detail ON tbl_stock_master.PkStockID = tbl_stock_detail.fkStockID
        INNER JOIN stock_batch ON tbl_stock_detail.BatchID = stock_batch.batch_id
        INNER JOIN itminfo_tab ON stock_batch.item_id = itminfo_tab.itm_id
        INNER JOIN tbl_warehouse ON tbl_stock_master.WHIDFrom = tbl_warehouse.wh_id
        WHERE
        tbl_stock_detail.temp = 1 AND
        tbl_stock_master.temp = 1 AND
        tbl_stock_master.WHIDTo = '" . $wh_id . "' AND
        tbl_stock_master.CreatedBy = " . $userid . " AND 
		tbl_stock_master.TranTypeID =" . $type . "
		ORDER BY tbl_stock_detail.PkDetailID DESC LIMIT 1 ";
        $rsSql = mysql_query($strSql) or die("Error :" . $strSql);
        if (mysql_num_rows($rsSql) > 0) {
            return $rsSql;
        } else {
            return FALSE;
        }
    }

    function GetStocksReceiveList($userid, $wh_id, $type, $stockid) {
        $strSql = "SELECT
        tbl_stock_master.TranDate,
        tbl_stock_detail.Qty,
        stock_batch.batch_no,
        stock_batch.production_date,
		stock_batch.batch_expiry,
        itminfo_tab.itm_name,
		itminfo_tab.qty_carton,
        tbl_warehouse.wh_name,
        tbl_stock_master.PkStockID,
        tbl_stock_master.TranNo,
        tbl_stock_master.TranRef,
        tbl_itemunits.UnitType,
		tbl_stock_detail.PkDetailID
        FROM
        tbl_stock_master
        INNER JOIN tbl_stock_detail ON tbl_stock_master.PkStockID = tbl_stock_detail.fkStockID
        INNER JOIN stock_batch ON tbl_stock_detail.BatchID = stock_batch.batch_id
        INNER JOIN itminfo_tab ON stock_batch.item_id = itminfo_tab.itm_id
        INNER JOIN tbl_warehouse ON tbl_stock_master.WHIDFrom = tbl_warehouse.wh_id
        INNER JOIN tbl_itemunits ON  itminfo_tab.itm_type= tbl_itemunits.UnitType
        WHERE
        tbl_stock_detail.temp = 0 AND
        tbl_stock_master.temp = 0 AND
        tbl_stock_master.WHIDTo = '" . $wh_id . "' AND
        tbl_stock_master.CreatedBy = " . $userid . " AND 
		tbl_stock_master.TranTypeID =" . $type . " AND tbl_stock_master.PkStockID = $stockid
		ORDER BY itminfo_tab.frmindex ASC";
        $rsSql = mysql_query($strSql) or die("Error GetStocksReceiveList");
        if (mysql_num_rows($rsSql) > 0) {
            return $rsSql;
        } else {
            return FALSE;
        }
    }

    function StockAdjustmentSearch() {
        $strSql = "SELECT
        tbl_stock_master.TranDate,
        tbl_stock_master.TranNo,
        tbl_stock_master.TranRef,
         tbl_stock_master.PkStockID,
        itminfo_tab.itm_name,
        stock_batch.batch_no,
        tbl_stock_detail.Qty,
        tbl_stock_master.ReceivedRemarks,
        tbl_trans_type.trans_type,
        itminfo_tab.itm_type,
        itminfo_tab.qty_carton
        FROM
        tbl_stock_master
        INNER JOIN tbl_stock_detail ON tbl_stock_detail.fkStockID = tbl_stock_master.PkStockID
        INNER JOIN stock_batch ON tbl_stock_detail.BatchID = stock_batch.batch_id
        INNER JOIN itminfo_tab ON stock_batch.item_id = itminfo_tab.itm_id
        INNER JOIN tbl_trans_type ON tbl_stock_master.TranTypeID = tbl_trans_type.trans_id";
        if (!empty($this->TranNo)) {
            $where[] = "tbl_stock_master.TranNo = '" . $this->TranNo . "'";
        }
        if (!empty($this->TranTypeID)) {
            $where[] = "tbl_stock_master.TranTypeID = '" . $this->TranTypeID . "'";
        }
        if (!empty($this->WHIDFrom)) {
            $where[] = "tbl_stock_master.WHIDFrom = '" . $this->WHIDFrom . "'";
        }
        if (!empty($this->WHIDTo)) {
            $where[] = "tbl_stock_master.WHIDTo = '" . $this->WHIDTo . "'";
        }
        if (!empty($this->item_id)) {
            $where[] = "stock_batch.item_id = '" . $this->item_id . "'";
        }
        if (!empty($this->fromDate) && !empty($this->toDate)) {
            $where[] = "tbl_stock_master.TranDate BETWEEN '" . $this->fromDate . "' AND '" . $this->toDate . "'";
        }

        if (is_array($where)) {
            $strSql .= " WHERE " . implode(" AND ", $where);
        }

        $strSql .= " GROUP BY tbl_stock_master.TranNo ORDER BY tbl_stock_master.TranNo DESC";
//echo $strSql;exit;
        $rsSql = mysql_query($strSql) or die("Error StockAdjustmentSearch");
        if (mysql_num_rows($rsSql) > 0) {
            return $rsSql;
        } else {
            return FALSE;
        }
    }

    function StockAdjustmentSearchList($stockpkid) {
        $strSql = "SELECT
        tbl_stock_master.TranDate,
        tbl_stock_master.TranNo,
        tbl_stock_master.TranRef,
         tbl_stock_master.PkStockID,
        itminfo_tab.itm_name,
        itminfo_tab.qty_carton,
        stock_batch.batch_no,
		stock_batch.batch_expiry,
        tbl_stock_detail.Qty,
        tbl_stock_master.ReceivedRemarks,
        tbl_trans_type.trans_type,
        itminfo_tab.itm_type,
		tbl_warehouse.wh_name
        FROM
        tbl_stock_master
        INNER JOIN tbl_stock_detail ON tbl_stock_detail.fkStockID = tbl_stock_master.PkStockID
        INNER JOIN stock_batch ON tbl_stock_detail.BatchID = stock_batch.batch_id
        INNER JOIN itminfo_tab ON stock_batch.item_id = itminfo_tab.itm_id
        INNER JOIN tbl_trans_type ON tbl_stock_master.TranTypeID = tbl_trans_type.trans_id
		INNER JOIN tbl_warehouse ON stock_batch.wh_id = tbl_warehouse.wh_id";

        $strSql .= " WHERE tbl_stock_master.PkStockID = " . $stockpkid . " ORDER BY tbl_stock_master.TranNo DESC";
		//echo $strSql;exit;
        $rsSql = mysql_query($strSql) or die("Error StockAdjustmentSearch");
        if (mysql_num_rows($rsSql) > 0) {
            return $rsSql;
        } else {
            return FALSE;
        }
    }

    function StockSearch($type, $wh_id) {
        $strSql = "SELECT
	tbl_stock_master.TranDate,
	tbl_stock_master.PkStockID,
	tbl_stock_master.TranNo,
	tbl_stock_master.TranRef,
	tbl_warehouse.wh_name,
	stock_batch.batch_no,
	stock_batch.batch_expiry,
	tbl_stock_detail.Qty,
	itminfo_tab.itm_type,
	tbl_stock_detail.PkDetailID,
	tbl_stock_detail.BatchID,
	itminfo_tab.itm_id,
	itminfo_tab.itm_name,
	itminfo_tab.qty_carton,
	(
		SELECT
			sum(placements.quantity) AS remValue
		FROM
			placements
		WHERE
			placements.stock_detail_id = tbl_stock_detail.PkDetailID
	) AS sumQty
	FROM
        tbl_stock_master
        INNER JOIN tbl_stock_detail ON tbl_stock_master.PkStockID = tbl_stock_detail.fkStockID
        INNER JOIN tbl_warehouse ON tbl_stock_master.WHIDFrom = tbl_warehouse.wh_id
        INNER JOIN stock_batch ON tbl_stock_detail.BatchID = stock_batch.batch_id
        INNER JOIN itminfo_tab ON stock_batch.item_id = itminfo_tab.itm_id";

        if (!empty($this->TranNo)) {
            $where[] = "tbl_stock_master.TranNo = '" . $this->TranNo . "'";
        }
        if (!empty($this->batch_no)) {
            $where[] = "stock_batch.batch_no = '" . $this->batch_no . "'";
        }
        if (!empty($this->TranRef)) {
            $where[] = "tbl_stock_master.TranRef = '" . $this->TranRef . "'";
        }
        if (!empty($this->WHIDFrom)) {
            $where[] = "tbl_stock_master.WHIDFrom = '" . $this->WHIDFrom . "'";
        }
        if (!empty($this->item_id)) {
            $where[] = "stock_batch.item_id = '" . $this->item_id . "'";
        }
        if (!empty($this->manufacturer)) {
            $where[] = "tbl_stock_detail.manufacturer = '" . $this->manufacturer . "'";
        }
        if (!empty($this->fromDate) && !empty($this->toDate)) {
            $where[] = "DATE_FORMAT(tbl_stock_master.TranDate, '%Y-%m-%d') BETWEEN '" . $this->fromDate . "' AND '" . $this->toDate . "'";
        }

        $where[] = "tbl_stock_master.TranTypeID = $type";
        $where[] = "stock_batch.wh_id = $wh_id";
        $where[] = "tbl_stock_detail.temp = 0";

        if (is_array($where)) {
            $strSql .= " WHERE " . implode(" AND ", $where);
        }
		
        $_SESSION['stock_rcv_list'] = $strSql;
		
        $strSql = $strSql . ' GROUP BY tbl_stock_master.TranNo ORDER BY tbl_stock_master.TranNo DESC';
		
		//echo $strSql;
		//exit();
        $_SESSION['rcv_qry'] = $strSql;
        $rsSql = mysql_query($strSql) or trigger_error(mysql_error() . $strSql);
        if (mysql_num_rows($rsSql) > 0) {
            return $rsSql;
        } else {
            return FALSE;
        }
    }

    function addStockSearch($type, $wh_id) {
        $strSql = "SELECT
	tbl_stock_master.PkStockID,
	tbl_stock_detail.PkDetailID,
	tbl_stock_detail.BatchID,
	tbl_stock_master.TranDate,
	itminfo_tab.itm_name,
	tbl_stock_master.TranNo,
	stock_batch.batch_no,
	stock_batch.batch_expiry,
	(
		tbl_stock_detail.Qty / itminfo_tab.qty_carton
	) AS receivedQty,
	sum(placements.quantity) AS allocated,
	ROUND(
		tbl_stock_detail.Qty / itminfo_tab.qty_carton
	) - sum(placements.quantity) AS unallocated
FROM
	tbl_stock_master
INNER JOIN tbl_stock_detail ON tbl_stock_master.PkStockID = tbl_stock_detail.fkStockID
INNER JOIN stock_batch ON tbl_stock_detail.BatchID = stock_batch.batch_id
INNER JOIN itminfo_tab ON stock_batch.item_id = itminfo_tab.itm_id
INNER JOIN placements ON stock_batch.batch_id = placements.stock_batch_id
WHERE
	tbl_stock_master.TranTypeID = $type
AND tbl_stock_master.WHIDTo = $wh_id
AND placements.quantity > 0
GROUP BY
	tbl_stock_detail.BatchID";

        if (!empty($this->TranNo)) {
            $where[] = "tbl_stock_master.TranNo = '" . $this->TranNo . "'";
        }
        if (!empty($this->batch_no)) {
            $where[] = "stock_batch.batch_no = '" . $this->batch_no . "'";
        }
        if (!empty($this->TranRef)) {
            $where[] = "tbl_stock_master.TranRef = '" . $this->TranRef . "'";
        }
        if (!empty($this->WHIDFrom)) {
            $where[] = "tbl_stock_master.WHIDFrom = '" . $this->WHIDFrom . "'";
        }
        if (!empty($this->item_id)) {
            $where[] = "stock_batch.item_id = '" . $this->item_id . "'";
        }
        if (!empty($this->manufacturer)) {
            $where[] = "tbl_stock_detail.manufacturer = '" . $this->manufacturer . "'";
        }
        if (!empty($this->fromDate) && !empty($this->toDate)) {
            $where[] = "tbl_stock_master.TranDate BETWEEN '" . $this->fromDate . "' AND '" . $this->toDate . "'";
        }

        $where[] = "tbl_stock_master.TranTypeID = $type";
        $where[] = "stock_batch.wh_id = $wh_id";
        $where[] = "tbl_stock_detail.temp = 0";

        if (is_array($where)) {
            $strSql .= " WHERE " . implode(" AND ", $where);
        }

        $strSql = $strSql . ' ORDER BY tbl_stock_master.TranNo DESC';

        $_SESSION['rcv_qry'] = $strSql;
        $rsSql = mysql_query($strSql) or trigger_error(mysql_error() . $strSql);
        if (mysql_num_rows($rsSql) > 0) {
            return $rsSql;
        } else {
            return FALSE;
        }
    }

    function DelStockSearch($type, $wh_id) {
        $strSql = "SELECT
        log_tbl_stock_master.TranDate,
		log_tbl_stock_master.PkStockID,
        log_tbl_stock_master.TranNo,
        log_tbl_stock_master.TranRef,
        tbl_warehouse.wh_name,
        log_stock_batch.batch_no,
        log_stock_batch.batch_expiry,
        log_tbl_stock_detail.Qty,
        tbl_itemunits.UnitType,
		log_tbl_stock_detail.PkDetailID,
		log_tbl_stock_detail.BatchID,
		itminfo_tab.doses_per_unit,
		itminfo_tab.itm_id,
		CONCAT(DATE_FORMAT(log_tbl_stock_master.deleted_on, '%d/%m/%Y %h:%i:%s %p')) AS DeletedOn,
		sysuser_tab.sysusr_name,
        itminfo_tab.itm_name,(select  sum(placement.qty) as remValue from placement where placement.StockIDetaild=log_tbl_stock_detail.PkDetailID and is_placed=1) as sumQty
        FROM
        log_tbl_stock_master
        INNER JOIN log_tbl_stock_detail ON log_tbl_stock_master.PkStockID = log_tbl_stock_detail.fkStockID
        INNER JOIN tbl_warehouse ON log_tbl_stock_master.WHIDFrom = tbl_warehouse.wh_id
        INNER JOIN log_stock_batch ON log_tbl_stock_detail.BatchID = log_stock_batch.batch_id
        INNER JOIN itminfo_tab ON log_stock_batch.item_id = itminfo_tab.itm_id
        INNER JOIN tbl_itemunits ON itminfo_tab.fkUnitID = tbl_itemunits.pkUnitID
		INNER JOIN sysuser_tab ON log_tbl_stock_master.deleted_by = sysuser_tab.UserID";

        if (!empty($this->TranNo)) {
            $where[] = "log_tbl_stock_master.TranNo = '" . $this->TranNo . "'";
        }
        if (!empty($this->batch_no)) {
            $where[] = "log_stock_batch.batch_no = '" . $this->batch_no . "'";
        }
        if (!empty($this->TranRef)) {
            $where[] = "log_tbl_stock_master.TranRef = '" . $this->TranRef . "'";
        }
        if (!empty($this->WHIDFrom)) {
            $where[] = "log_tbl_stock_master.WHIDFrom = '" . $this->WHIDFrom . "'";
        }
        if (!empty($this->item_id)) {
            $where[] = "log_stock_batch.item_id = '" . $this->item_id . "'";
        }
        if (!empty($this->fromDate) && !empty($this->toDate)) {
            $where[] = "log_tbl_stock_master.TranDate BETWEEN '" . $this->fromDate . "' AND '" . $this->toDate . "'";
        }

        $where[] = "log_tbl_stock_master.TranTypeID = $type";
        $where[] = "log_tbl_stock_detail.temp = 0";

        if (is_array($where)) {
            $strSql .= " WHERE " . implode(" AND ", $where);
        }

        $strSql = $strSql . ' ORDER BY log_tbl_stock_master.TranNo DESC';

        $rsSql = mysql_query($strSql) or trigger_error(mysql_error() . $strSql);
        if (mysql_num_rows($rsSql) > 0) {
            return $rsSql;
        } else {
            return FALSE;
        }
    }

    function StockIssueSearch($type, $wh_id) {
        $strSql = "SELECT
        tbl_stock_master.TranDate,
		tbl_stock_master.PkStockID,
        tbl_stock_master.TranNo,
        tbl_stock_master.TranRef,
		tbl_stock_detail.PkDetailID,
        CONCAT(tbl_warehouse.wh_name, ' (', stakeholder.stkname, ')') AS wh_name,
		fundingSource.wh_name AS funding_source,
        stock_batch.batch_no,
        stock_batch.batch_expiry,
		tbl_stock_detail.BatchID,
        tbl_stock_detail.Qty,
        tbl_itemunits.UnitType,
        itminfo_tab.itm_name,
        itminfo_tab.qty_carton,
		(
			SELECT
				sum(placements.quantity) AS remValue
			FROM
				placements
			WHERE
				placements.stock_detail_id = tbl_stock_detail.PkDetailID
			AND is_placed = 0
		) AS sumIssueQty
        FROM
        tbl_stock_master
        INNER JOIN tbl_stock_detail ON tbl_stock_master.PkStockID = tbl_stock_detail.fkStockID
        INNER JOIN tbl_warehouse ON tbl_stock_master.WHIDTo = tbl_warehouse.wh_id
        INNER JOIN stock_batch ON tbl_stock_detail.BatchID = stock_batch.batch_id
		LEFT JOIN tbl_warehouse AS fundingSource ON stock_batch.funding_source = fundingSource.wh_id
        INNER JOIN itminfo_tab ON stock_batch.item_id = itminfo_tab.itm_id
       	INNER JOIN tbl_itemunits ON itminfo_tab.itm_type = tbl_itemunits.UnitType
		INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid";

        if (!empty($this->TranNo)) {
            $where[] = "tbl_stock_master.TranNo like '" . $this->TranNo . "%'";
        }
        if (!empty($this->batch_no)) {
            $where[] = "stock_batch.batch_no like '" . $this->batch_no . "%'";
        }
        if (!empty($this->TranRef)) {
            $where[] = "tbl_stock_master.TranRef like '" . $this->TranRef . "%'";
        }
        if (!empty($this->WHIDTo)) {
            $where[] = "tbl_stock_master.WHIDTo = '" . $this->WHIDTo . "'";
        }
        if (!empty($this->province)) {
            $where[] = "tbl_warehouse.prov_id = '" . $this->province . "'";
        }
        if (!empty($this->stakeholder)) {
            $where[] = "tbl_warehouse.stkid = '" . $this->stakeholder . "'";
        }
        if (!empty($this->item_id)) {
            $where[] = "stock_batch.item_id = '" . $this->item_id . "'";
        }
        if (!empty($this->fromDate) && !empty($this->toDate)) {
            $where[] = "tbl_stock_master.TranDate BETWEEN '" . $this->fromDate . "' AND '" . $this->toDate . "'";
        }

        if (!empty($this->funding_source)) {
            $where[] = " stock_batch.funding_source = ".$this->funding_source." ";
        }
		
        $where[] = "tbl_stock_master.TranTypeID = $type";
        $where[] = "stock_batch.wh_id = $wh_id";
        $where[] = "tbl_stock_detail.temp = 0";

        if (is_array($where)) {
            $strSql .= " WHERE " . implode(" AND ", $where);
        }
        $_SESSION['qry'] = $strSql;
        $strSql = $strSql . ' ORDER BY tbl_stock_master.TranDate DESC, tbl_stock_master.TranNo DESC';
        //echo $strSql;die;
        $rsSql = mysql_query($strSql) or trigger_error(mysql_error() . $strSql);
        if (mysql_num_rows($rsSql) > 0) {
            return $rsSql;
        } else {
            return FALSE;
        }
    }

    function DelStockIssueSearch($type, $wh_id) {
        $strSql = "SELECT
        log_tbl_stock_master.TranDate,
		log_tbl_stock_master.PkStockID,
        log_tbl_stock_master.TranNo,
        log_tbl_stock_master.TranRef,
		log_tbl_stock_detail.PkDetailID,
        tbl_warehouse.wh_name,
        log_stock_batch.batch_no,
        log_stock_batch.batch_expiry,
		log_tbl_stock_detail.BatchID,
        log_tbl_stock_detail.Qty,
        tbl_itemunits.UnitType,
        itminfo_tab.itm_name,
		itminfo_tab.doses_per_unit,
		CONCAT(DATE_FORMAT(log_tbl_stock_master.deleted_on, '%d/%m/%Y %h:%i:%s %p')) AS DeletedOn,
		sysuser_tab.sysusr_name,
		(select sum(placement.qty) as remValue from placement where placement.StockIDetaild=log_tbl_stock_detail.PkDetailID and is_placed=0) as sumIssueQty
        FROM
        log_tbl_stock_master
        INNER JOIN log_tbl_stock_detail ON log_tbl_stock_master.PkStockID = log_tbl_stock_detail.fkStockID
        INNER JOIN tbl_warehouse ON log_tbl_stock_master.WHIDTo = tbl_warehouse.wh_id
        INNER JOIN log_stock_batch ON log_tbl_stock_detail.BatchID = log_stock_batch.batch_id
        INNER JOIN itminfo_tab ON log_stock_batch.item_id = itminfo_tab.itm_id
        INNER JOIN tbl_itemunits ON itminfo_tab.fkUnitID = tbl_itemunits.pkUnitID
		INNER JOIN sysuser_tab ON log_tbl_stock_master.deleted_by = sysuser_tab.UserID";

        if (!empty($this->TranNo)) {
            $where[] = "log_tbl_stock_master.TranNo like '" . $this->TranNo . "%'";
        }
        if (!empty($this->batch_no)) {
            $where[] = "log_stock_batch.batch_no like '" . $this->batch_no . "%'";
        }
        if (!empty($this->TranRef)) {
            $where[] = "log_tbl_stock_master.TranRef like '" . $this->TranRef . "%'";
        }
        if (!empty($this->WHIDTo)) {
            $where[] = "log_tbl_stock_master.WHIDTo = '" . $this->WHIDTo . "'";
        }
        if (!empty($this->item_id)) {
            $where[] = "log_stock_batch.item_id = '" . $this->item_id . "'";
        }
        if (!empty($this->fromDate) && !empty($this->toDate)) {
            $where[] = "log_tbl_stock_master.TranDate BETWEEN '" . $this->fromDate . "' AND '" . $this->toDate . "'";
        }

        $where[] = "log_tbl_stock_master.TranTypeID = $type";
        $where[] = "log_tbl_stock_detail.temp = 0";

        if (is_array($where)) {
            $strSql .= " WHERE " . implode(" AND ", $where);
        }
        $_SESSION['qry'] = $strSql;
        $strSql = $strSql . ' GROUP BY log_stock_batch.batch_id, log_tbl_stock_master.WHIDTo ORDER BY log_tbl_stock_master.TranNo DESC';
        //echo $strSql;die;
        $rsSql = mysql_query($strSql) or trigger_error(mysql_error() . $strSql);
        if (mysql_num_rows($rsSql) > 0) {
            return $rsSql;
        } else {
            return FALSE;
        }
    }

    function getLastID($from, $to, $tr_type) {
        if ($tr_type == 1) {
            $str = "AND WHIDTo='" . $_SESSION['wh_id'] . "'";
        } else {
            $str = "AND WHIDFrom='" . $_SESSION['wh_id'] . "'";
        }
        $strSql = "SELECT MAX(trNo) as Maxtr from " . self::$table_name . " where TranDate between '" . $from . "' and '" . $to . "' AND TranTypeID = '" . $tr_type . "' $str";

        $rsSql = mysql_query($strSql) or die("Error getLastID $strSql");
        if (mysql_num_rows($rsSql) > 0) {
            while ($row = mysql_fetch_object($rsSql)) {
                return $row->Maxtr;
            }
        } else {
            return FALSE;
        }
    }

    function getAdjLastID($from, $to) {
        if (!empty($tr_type) && $tr_type == 1) {
            $str = "AND WHIDTo='" . $_SESSION['wh_id'] . "'";
        } else {
            $str = "AND WHIDFrom='" . $_SESSION['wh_id'] . "'";
        }
        $strSql = "SELECT MAX(trNo) as Maxtr from " . self::$table_name . " where TranDate between '" . $from . "' and '" . $to . "' AND TranTypeID != 1 AND TranTypeID != 2 $str";

        $rsSql = mysql_query($strSql) or die("Error getLastID $strSql");
        if (mysql_num_rows($rsSql) > 0) {
            while ($row = mysql_fetch_object($rsSql)) {
                return $row->Maxtr;
            }
        } else {
            return FALSE;
        }
    }

    function GetWHStockByIssueNo() {
        $strSql = "SELECT
        tbl_stock_detail.Qty,
        stock_batch.batch_no,
        itminfo_tab.itm_name,
        tbl_stock_detail.fkStockID,
        tbl_stock_detail.PkDetailID
        FROM
        tbl_stock_master
        INNER JOIN tbl_stock_detail ON tbl_stock_detail.fkStockID = tbl_stock_master.PkStockID
        INNER JOIN stock_batch ON tbl_stock_detail.BatchID = stock_batch.batch_id
        INNER JOIN itminfo_tab ON itminfo_tab.itm_id = stock_batch.item_id
        WHERE
        tbl_stock_master.TranNo = '" . $this->TranNo . "' AND
        tbl_stock_master.temp = 0 AND
        tbl_stock_master.WHIDTo = " . $this->WHIDTo . " AND
		(tbl_stock_detail.IsReceived is NULL or tbl_stock_detail.IsReceived = 0) AND tbl_stock_master.TranTypeID = 2";

        $rsSql = mysql_query($strSql) or die("Error GetWHStockByIssueNo");
        if (mysql_num_rows($rsSql) > 0) {
            return $rsSql;
        } else {
            return FALSE;
        }
    }

    function getWhLevelByStockID($stock_id) {
        $strSql = "SELECT
			tbl_stock_master.WHIDTo,
			stakeholder.lvl
			FROM
			tbl_stock_master
			INNER JOIN tbl_warehouse ON tbl_stock_master.WHIDTo = tbl_warehouse.wh_id
			INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
			WHERE
			tbl_stock_master.PkStockID = $stock_id";
        $rsSql = mysql_query($strSql) or die("Error getWhLevelByStockID");
        if (mysql_num_rows($rsSql) > 0) {
            $row = mysql_fetch_object($rsSql);
            return array(
                'uc_wh_id' => $row->WHIDTo,
                'level' => $row->lvl
            );
        } else {
            return FALSE;
        }
    }

    function getDateAndItemfromStock($stock_id) {
        $strSql = "SELECT
				tbl_stock_master.TranDate,
				tbl_stock_detail.Qty AS Qty,
				itminfo_tab.itmrec_id as itemrec_id,
				itminfo_tab.itm_id as item_id
				FROM
				tbl_stock_master
				INNER JOIN tbl_stock_detail ON tbl_stock_detail.fkStockID = tbl_stock_master.PkStockID
				INNER JOIN stock_batch ON tbl_stock_detail.BatchID = stock_batch.batch_id
				INNER JOIN itminfo_tab ON stock_batch.item_id = itminfo_tab.itm_id
				WHERE
				tbl_stock_master.PkStockID = $stock_id AND tbl_stock_detail.IsReceived = 1
         ";
        $rsSql = mysql_query($strSql) or die("Error getDateAndItemfromStock");
        if (mysql_num_rows($rsSql) > 0) {
            while ($row = mysql_fetch_object($rsSql)) {
                $array[] = array(
                    'date' => $row->TranDate,
                    'item_id' => $row->item_id,
                    'itemrec_id' => $row->itemrec_id,
                    'qty' => $row->Qty
                );
            }
            return $array;
        } else {
            return FALSE;
        }
    }

    function getItemDetailfromStock($stock_id) {
        $strSql = "SELECT
					tbl_stock_master.TranDate,
					tbl_stock_detail.Qty AS Qty,
					itminfo_tab.itmrec_id as itemrec_id,
					itminfo_tab.itm_id as item_id
					FROM
					tbl_stock_master
					INNER JOIN tbl_stock_detail ON tbl_stock_detail.fkStockID = tbl_stock_master.PkStockID
					INNER JOIN stock_batch ON tbl_stock_detail.BatchID = stock_batch.batch_id
					INNER JOIN itminfo_tab ON stock_batch.item_id = itminfo_tab.itm_id
					WHERE	tbl_stock_master.PkStockID =  $stock_id";
        $rsSql = mysql_query($strSql) or die("Error getItemDetailfromStock");
        if (mysql_num_rows($rsSql) > 0) {
            while ($row = mysql_fetch_object($rsSql)) {
                $array[] = array(
                    'date' => $row->TranDate,
                    'item_id' => $row->item_id,
                    'itemrec_id' => $row->itemrec_id,
                    'qty' => $row->Qty
                );
            }
            return $array;
        } else {
            return FALSE;
        }
    }

    function updateMasterIssueDate($stock_id) {
        $strSql = "UPDATE
						tbl_stock_master 
					SET
						tbl_stock_master.TranDate = '" . $this->TranDate . "',
						tbl_stock_master.issued_by = '" . $this->issued_by . "'
					WHERE
						tbl_stock_master.PkStockID = '" . $stock_id . "' ";
        mysql_query($strSql);
        return true;
    }

}

?>
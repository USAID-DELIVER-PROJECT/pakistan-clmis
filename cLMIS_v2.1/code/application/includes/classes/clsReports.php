<?php

/**
 * clsReports
 * @package includes/class
 * 
 * @author     Muhammad Waqas Azeem 
 * @email <waqas@deliver-pk.org>
 * 
 * @version    2.2
 * 
 */
class ClsReports
{
    //wh id
	var $wh_id;
    //province id
        var $province_id;
    //district id
        var $district_id;
        //stakeholder
	var $stk;
		
        /**
         * editableMonths
         * @return type
         */
    function editableMonths()
	{
            //select query
            //gets
            //editable data entry months
		$qry = "SELECT
					tbl_warehouse.editable_data_entry_months
				FROM
					tbl_warehouse
				WHERE
					tbl_warehouse.wh_id = " . $this->wh_id;
                //query result
		$qryRes = mysql_fetch_array(mysql_query($qry));
		if ( !empty($qryRes['editable_data_entry_months']) )
		{
			$months = $qryRes['editable_data_entry_months'];
		}
		return (isset($_SESSION['LIMIT']) ? $_SESSION['LIMIT'] : $months);
	}
	
        /**
         * getReportingStartMonth
         * @return type
         */
    function getReportingStartMonth()
	{
            //select query
            //gets
            //reporting start month
		$qry = "SELECT
					ADDDATE(tbl_warehouse.reporting_start_month,INTERVAL -1 MONTH) AS reporting_start_month
				FROM
					tbl_warehouse
				WHERE
					tbl_warehouse.wh_id = " . $this->wh_id;
                //query result
		$firstMonth = mysql_fetch_array(mysql_query($qry));
		if ( !empty($firstMonth['reporting_start_month']) )
		{
			$NewDate = $firstMonth['reporting_start_month'];
		}
		return $NewDate;
	}

	/**
         * GetLastReportDate
         * @return type
         */
    function GetLastReportDate()
	{
		$d="2014-12-01";
       	//select query
		$query = "SELECT max(RptDate) as MaxDate FROM tbl_wh_data WHERE wh_id=".$this->wh_id;
		//query result
        $rs = mysql_fetch_object(mysql_query($query));
		if(!empty($rs->MaxDate)) {
			$d = $rs->MaxDate;
		}else if(empty($rs->MaxDate)) {
            //getReportingStartMonth
			$d = $this->getReportingStartMonth();
		}
		return $d;
	}
	
        /**
         * GetLastReportDateHF
         * @return type
         */
    function GetLastReportDateHF()
	{
		$d = '2015-01-01';
		//select query
        $query="SELECT max(reporting_date) as MaxDate FROM tbl_hf_data WHERE warehouse_id=".$this->wh_id;
		//query result
        $rs = mysql_fetch_object(mysql_query($query));
		if(!empty($rs->MaxDate)) {
			$d = $rs->MaxDate;
		}else if(empty($rs->MaxDate)) {
                    //getReportingStartMonth
			$d = $this->getReportingStartMonth();
		}
		return $d;
	}
	
        /**
         * GetLastReportDateHFSatellite
         * @return type
         */
    function GetLastReportDateHFSatellite()
	{		
		$d = '2014-12-01';
         //select query
		$query="SELECT max(reporting_date) as MaxDate FROM tbl_hf_satellite_data WHERE warehouse_id=".$this->wh_id;
		//query result
                $rs = mysql_fetch_object(mysql_query($query));
		if(!empty($rs->MaxDate)) {
			$d = $rs->MaxDate;
		}else if(empty($rs->MaxDate)) {
                    //getReportingStartMonth
			$d = $this->getReportingStartMonth();
		}
		return $d;
		
	}
        
	/**
         * GetLastReportDateHFType
         * @return type
         */
        function GetLastReportDateHFType()
	{
		$d="2014-12-01";
		//select query
                $query="SELECT max(reporting_date) as MaxDate FROM tbl_hf_type_data WHERE facility_type_id=".$this->wh_id." AND district_id = " . $_SESSION['dist_id'];
		//query result
                $rs = mysql_query($query) or die(print mysql_error());
		while($r = mysql_fetch_object($rs))
		{
			if(!empty($r->MaxDate)) {
				$d = $r->MaxDate;
			}
		}
		return $d;
	}
	
        /**
         * GetLast3Months
         * @return type
         */
	function GetLast3Months()
	{
		$limit = $this->editableMonths();
		$last3Months=array();
        //select query
                $query="SELECT DATE_FORMAT(RptDate,'%Y-%m-%d') as MaxDate FROM tbl_wh_data WHERE wh_id=".$this->wh_id." GROUP BY MaxDate ORDER BY MaxDate DESC LIMIT " . $limit;
		//query result
                $rs = mysql_query($query) or die(print mysql_error());
		
		while($r = mysql_fetch_object($rs))
		{
			$last3Months[] = $r->MaxDate;
		}
		return $last3Months;
	}
	
        /**
         * GetLast3MonthsHF
         * @return type
         */
	function GetLast3MonthsHF()
	{
		$limit = $this->editableMonths();
		$last3Months=array();
		//select query
                $query="SELECT DATE_FORMAT(reporting_date,'%Y-%m-%d') as MaxDate FROM tbl_hf_data WHERE warehouse_id=".$this->wh_id." GROUP BY MaxDate ORDER BY MaxDate DESC LIMIT " . $limit;
		//query result
		$rs = mysql_query($query) or die(print mysql_error());
		
		while($r = mysql_fetch_object($rs))
		{
			$last3Months[] = $r->MaxDate;
		}
		return $last3Months;
	}
	
        /**
         * GetLast3MonthsHFSatellite
         * @return type
         */
	function GetLast3MonthsHFSatellite()
	{
		$limit = $this->editableMonths();
		$last3Months=array();
		//select query
                $query="SELECT DATE_FORMAT(reporting_date,'%Y-%m-%d') as MaxDate FROM tbl_hf_satellite_data WHERE warehouse_id=".$this->wh_id." GROUP BY MaxDate ORDER BY MaxDate DESC LIMIT " . $limit;
		//query result
		$rs = mysql_query($query) or die(print mysql_error());
		//fetch result
		while($r = mysql_fetch_object($rs))
		{
			$last3Months[] = $r->MaxDate;
		}
		return $last3Months;
	}
	
        /**
         * GetLast3MonthsHFType
         * @return type
         */
	function GetLast3MonthsHFType()
	{
		$limit = $this->editableMonths();
		$last3Months=array();
		//select query
                $query="SELECT DATE_FORMAT(reporting_date,'%Y-%m-%d') as MaxDate FROM tbl_hf_type_data WHERE facility_type_id=".$this->wh_id." AND district_id = " . $_SESSION['dist_id']." GROUP BY MaxDate ORDER BY MaxDate DESC LIMIT " . $limit;
		//query result
		$rs = mysql_query($query) or die(print mysql_error());
		//fetch result
		while($r = mysql_fetch_object($rs))
		{
			$last3Months[] = $r->MaxDate;
		}
		return $last3Months;
	}
	
        /**
         * GetPendingReportMonth
         * @return string
         */
	function GetPendingReportMonth()
	{
            //GetLastReportDate
		$LRM=$this->GetLastReportDate();
		
		$NewDatetemp=$this->add($LRM, 2); 
		$NewDate=$NewDatetemp->format('Y-m-d');
		$NewDatetemp2= date('Y-m-d', strtotime('-1 day', strtotime($NewDatetemp->format('Y-m-d'))));
		$NewMonth_dt = new DateTime($NewDatetemp2);
		
		$today = date("Y-m-d");
		$today_dt = new DateTime($today);

		if ($NewMonth_dt < $today_dt) {
		 	return $this->add($LRM, 1)->format('Y-m-d');
		}else{
			return "";
		}
	}
	
        /**
         * GetPendingReportMonthHF
         * @return string
         */
	function GetPendingReportMonthHF()
	{
		$LRM = $this->GetLastReportDateHF();
		$NewDatetemp=$this->add($LRM, 2); 
		$NewDate=$NewDatetemp->format('Y-m-d');
		$NewDatetemp2= date('Y-m-d', strtotime('-1 day', strtotime($NewDatetemp->format('Y-m-d'))));
		$NewMonth_dt = new DateTime($NewDatetemp2);
		
		$today = date("Y-m-d");
		$today_dt = new DateTime($today);

		if ($NewMonth_dt < $today_dt) {		
			return $this->add($LRM, 1)->format('Y-m-d');
		} else {
			return "";
		}
			
	}
	
        /**
         * GetPendingReportMonthHFSatellite
         * @return string
         */
	function GetPendingReportMonthHFSatellite()
	{
            //GetLastReportDateHFSatellite
		$LRM=$this->GetLastReportDateHFSatellite();
		
		$NewDatetemp=$this->add($LRM, 2); 
		$NewDate=$NewDatetemp->format('Y-m-d');
		$NewDatetemp2= date('Y-m-d', strtotime('-1 day', strtotime($NewDatetemp->format('Y-m-d'))));
		$NewMonth_dt = new DateTime($NewDatetemp2);
		
		$today = date("Y-m-d");
		$today_dt = new DateTime($today);		

		if ($NewMonth_dt < $today_dt) {		
			return $this->add($LRM, 1)->format('Y-m-d');
		} else {
			return "";
		}
			
	}
	
        /**
         * GetPendingReportMonthHFType
         * @return string
         */
	function GetPendingReportMonthHFType()
	{
            //GetLastReportDateHFType
		$LRM=$this->GetLastReportDateHFType();
		
		$NewDatetemp=$this->add($LRM, 2); 
		$NewDate=$NewDatetemp->format('Y-m-d');
		$NewDatetemp2= date('Y-m-d', strtotime('-1 day', strtotime($NewDatetemp->format('Y-m-d'))));
		$NewMonth_dt = new DateTime($NewDatetemp2);
		
		$today = date("Y-m-d");
		$today_dt = new DateTime($today);

		if ($NewMonth_dt < $today_dt) {
		 	return $this->add($LRM, 1)->format('Y-m-d');
		}else{
			return "";
		}
	}
        
        /**
         * GetThisMonthReportDate
         * @return type
         */
	function GetThisMonthReportDate()
	{
		$LRM=date("Y-m-d");
		$NewDatetemp=$this->add($LRM, -1);
		$NewDate=$NewDatetemp->format('Y-m-d');
		return $NewDate;
	}
	
        /**
         * GetPreviousMonthReportDate
         * @param type $thismonth
         * @return type
         */
	function GetPreviousMonthReportDate($thismonth)
	{
		$NewDatetemp=$this->add($thismonth, -1); 
		$NewDate=$NewDatetemp->format('Y-m-d');
		return $NewDate;
	}

        /**
         * add
         * @param type $date_str
         * @param type $months
         * @return \DateTime
         */
	function add($date_str, $months)
	{
		$date = new DateTime($date_str);
		$start_day = $date->format('j');

		$date->modify("+{$months} month");
		$end_day = $date->format('j');

		if ($start_day != $end_day){
			$date->modify('last day of last month');
		}

		return $date;
	}
	
	/////// Function for KPK Tank district & FATA disticts to start off with provided date.
	/**
         * GetAllMonthsTillDate
         * @param type $date
         * @param type $year
         * @return type
         */
        function GetAllMonthsTillDate($date,$year)
	{
		$date1=$date;
		$yr1=$year;
		$yr2='2014';
		$date2=date('Y-m-d');
		$time1  = strtotime($date1);
		$time2  = strtotime($date2);
		$my     = date('mY', $time2);

		$months = array(date($yr1.'-m-d', $time1));

		while($time1 < $time2) {
			$time1 = strtotime(date('Y-m-d', $time1).' +1 month');
			if(date('mY', $time1) != $my && ($time1 < $time2)){
				if(count($months)>=12)
				{$yr1=$yr2;}
				
				$months[] = date($yr1.'-m-d', $time1);

			}
		}

		return $months;
			
	}

}
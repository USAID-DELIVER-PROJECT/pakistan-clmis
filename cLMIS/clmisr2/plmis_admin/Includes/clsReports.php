<?php
class ClsReports
{

	var $wh_id;
    var $province_id;
	var $stk;

	function GetLastReportDate()
	{
		 $d="2000-01-01";
		 $query="SELECT max(RptDate) as MaxDate FROM tbl_wh_data WHERE wh_id=".$this->wh_id;
		//print $query;
		$rs = mysql_query($query) or die(print mysql_error());
		while($r = mysql_fetch_object($rs))
		{
			$d= $r->MaxDate;
		}
		//if ($this->province_id == 2){
		//	return  $d="2014-08-01";
		//}
		//else {
		//	return $d;
		// }
		return $d;
	}
	function GetLastReportDateHF()
	{
		if ( $this->stk == 1 ){
			if ($this->province_id == 2){
				$d = "2015-02-01";
			}else{
				$d="2014-12-01";
			}
		}elseif ( $this->stk == 7 ){
			$d = "2015-03-01";
		}else{
			$d="2014-12-01";
		}
		$query="SELECT max(reporting_date) as MaxDate FROM tbl_hf_data WHERE warehouse_id=".$this->wh_id;
		//print $query;
		$rs = mysql_query($query) or die(print mysql_error());
		while($r = mysql_fetch_object($rs))
		{
			if(!empty($r->MaxDate)) {
				$d = $r->MaxDate;
			}
		}
		return $d;
	}
	function GetLastReportDateHFSatellite()
	{
		$d="2014-12-01";
		$query="SELECT max(reporting_date) as MaxDate FROM tbl_hf_satellite_data WHERE warehouse_id=".$this->wh_id;
		//print $query;
		$rs = mysql_query($query) or die(print mysql_error());
		while($r = mysql_fetch_object($rs))
		{
			if(!empty($r->MaxDate)) {
				$d = $r->MaxDate;
			}
		}
		return $d;
	}
	function GetLastReportDateHFType()
	{
		$d="2014-12-01";
		$query="SELECT max(reporting_date) as MaxDate FROM tbl_hf_type_data WHERE facility_type_id=".$this->wh_id." AND district_id = " . $_SESSION['dist_id'];
		//print $query;
		$rs = mysql_query($query) or die(print mysql_error());
		while($r = mysql_fetch_object($rs))
		{
			if(!empty($r->MaxDate)) {
				$d = $r->MaxDate;
			}
		}
		return $d;
	}
	
	function GetLast3Months()
	{
		$last3Months=array();
        $query="SELECT DATE_FORMAT(RptDate,'%Y-%m-%d') as MaxDate FROM tbl_wh_data WHERE wh_id=".$this->wh_id." GROUP BY MaxDate ORDER BY MaxDate DESC LIMIT " . $_SESSION['LIMIT'];
		$rs = mysql_query($query) or die(print mysql_error());
		
		while($r = mysql_fetch_object($rs))
		{
			$last3Months[] = $r->MaxDate;
		}
		//print_r($last3Months);
		return $last3Months;
	}
	
	function GetLast3MonthsHF()
	{
		$last3Months=array();
		$query="SELECT DATE_FORMAT(reporting_date,'%Y-%m-%d') as MaxDate FROM tbl_hf_data WHERE warehouse_id=".$this->wh_id." GROUP BY MaxDate ORDER BY MaxDate DESC LIMIT " . $_SESSION['LIMIT'];
		
		$rs = mysql_query($query) or die(print mysql_error());
		
		while($r = mysql_fetch_object($rs))
		{
			$last3Months[] = $r->MaxDate;
		}
		//print_r($last3Months);
		return $last3Months;
	}
	
	function GetLast3MonthsHFSatellite()
	{
		$last3Months=array();
		$query="SELECT DATE_FORMAT(reporting_date,'%Y-%m-%d') as MaxDate FROM tbl_hf_satellite_data WHERE warehouse_id=".$this->wh_id." GROUP BY MaxDate ORDER BY MaxDate DESC LIMIT " . $_SESSION['LIMIT'];
		
		$rs = mysql_query($query) or die(print mysql_error());
		
		while($r = mysql_fetch_object($rs))
		{
			$last3Months[] = $r->MaxDate;
		}
		//print_r($last3Months);
		return $last3Months;
	}
	
	function GetLast3MonthsHFType()
	{
		$last3Months=array();
		$query="SELECT DATE_FORMAT(reporting_date,'%Y-%m-%d') as MaxDate FROM tbl_hf_type_data WHERE facility_type_id=".$this->wh_id." AND district_id = " . $_SESSION['dist_id']." GROUP BY MaxDate ORDER BY MaxDate DESC LIMIT " . $_SESSION['LIMIT'];
		
		$rs = mysql_query($query) or die(print mysql_error());
		
		while($r = mysql_fetch_object($rs))
		{
			$last3Months[] = $r->MaxDate;
		}
		//print_r($last3Months);
		return $last3Months;
	}
	
	function GetPendingReportMonth()
	{
		$LRM=$this->GetLastReportDate();
		
		$NewDatetemp=$this->add($LRM, 2); 
		$NewDate=$NewDatetemp->format('Y-m-d');
		$NewDatetemp2= date('Y-m-d', strtotime('-1 day', strtotime($NewDatetemp->format('Y-m-d'))));
		
		$today = date("Y-m-d");
		$today_dt = new DateTime($today);

		

		$NewMonth_dt = new DateTime($NewDatetemp2);
		//print $NewMonth_dt->format('Y-m-d');

		if ($NewMonth_dt < $today_dt) 
		{
		
		 	return $this->add($LRM, 1)->format('Y-m-d');
		}
		else
			return "";
	}
	
	function GetPendingReportMonthHF()
	{
		$LRM=$this->GetLastReportDateHF();
		
		$NewDatetemp=$this->add($LRM, 2); 
		$NewDate=$NewDatetemp->format('Y-m-d');
		$NewDatetemp2= date('Y-m-d', strtotime('-1 day', strtotime($NewDatetemp->format('Y-m-d'))));
		
		$today = date("Y-m-d");
		$today_dt = new DateTime($today);		

		$NewMonth_dt = new DateTime($NewDatetemp2);
		//print $NewMonth_dt->format('Y-m-d');

		if ($NewMonth_dt < $today_dt) {		
                    return $this->add($LRM, 1)->format('Y-m-d');
		} else {
                    return "";
                }
			
	}
	
	function GetPendingReportMonthHFSatellite()
	{
		$LRM=$this->GetLastReportDateHFSatellite();
		
		$NewDatetemp=$this->add($LRM, 2); 
		$NewDate=$NewDatetemp->format('Y-m-d');
		$NewDatetemp2= date('Y-m-d', strtotime('-1 day', strtotime($NewDatetemp->format('Y-m-d'))));
		
		$today = date("Y-m-d");
		$today_dt = new DateTime($today);		

		$NewMonth_dt = new DateTime($NewDatetemp2);
		//print $NewMonth_dt->format('Y-m-d');

		if ($NewMonth_dt < $today_dt) {		
			return $this->add($LRM, 1)->format('Y-m-d');
		} else {
			return "";
		}
			
	}
	
	function GetPendingReportMonthHFType()
	{
		$LRM=$this->GetLastReportDateHFType();
		
		$NewDatetemp=$this->add($LRM, 2); 
		$NewDate=$NewDatetemp->format('Y-m-d');
		$NewDatetemp2= date('Y-m-d', strtotime('-1 day', strtotime($NewDatetemp->format('Y-m-d'))));
		
		$today = date("Y-m-d");
		$today_dt = new DateTime($today);

		

		$NewMonth_dt = new DateTime($NewDatetemp2);
		//print $NewMonth_dt->format('Y-m-d');

		if ($NewMonth_dt < $today_dt) 
		{
		
		 	return $this->add($LRM, 1)->format('Y-m-d');
		}
		else
			return "";
	}

function GetThisMonthReportDate()
	{
		$LRM=date("Y-m-d");
		$NewDatetemp=$this->add($LRM, -1);
		$NewDate=$NewDatetemp->format('Y-m-d');
                
                
		return $NewDate;
	}
	
function GetPreviousMonthReportDate($thismonth)
	{
		$NewDatetemp=$this->add($thismonth, -1); 
		$NewDate=$NewDatetemp->format('Y-m-d');
		return $NewDate;
	}


	function add($date_str, $months)
	{
		$date = new DateTime($date_str);
		$start_day = $date->format('j');

		$date->modify("+{$months} month");
		$end_day = $date->format('j');

		if ($start_day != $end_day)
		$date->modify('last day of last month');

		return $date;
	}
	
	/////// Function for KPK Tank district & FATA disticts to start off with provided date.
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


?>
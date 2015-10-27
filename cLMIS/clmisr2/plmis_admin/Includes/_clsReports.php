<?php
class ClsReports
{

	var $wh_id;

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
		return $d;
	}
	
	function GetLast3Months()
	{
		$last3Months=array();
		$query="SELECT DATE_FORMAT(RptDate,'%Y-%m-%d') as MaxDate FROM tbl_wh_data WHERE wh_id=".$this->wh_id." GROUP BY MaxDate ORDER BY MaxDate DESC LIMIT 1";
		
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

}


?>
<?php

/////// Graph Caption Code
$getIndicator = mysql_query("select report_id, report_title from reports where report_type=1 and report_show_comp = 1 order by report_group, report_order");

while($row = mysql_fetch_array($getIndicator)){
	if ($row['report_id'] == $_SESSION['sel_stakeholder']){
		$indicator = $row['report_title'];
		break;
	}else {
		$indicator = "";
	}
}


$compareOption = array("", "Year - National", "Year - Provincial", "Year - District", "Stakeholder - National", "Stakeholder - Provincial", "Stakeholder - District", "Geographical - Provinical", "Geographical - District");

$cmpOption = $compareOption[$_SESSION['optvals']];

if ($_SESSION['seluser'] == 0){ 
	$stakeHolder = "All Stakeholders";	
}else{
	$getStk = mysql_query("select stkid, stkname from `stakeholder` where Parentid is null AND stakeholder.stk_type_id IN (0,1) order by stkorder");
	while($row = mysql_fetch_array($getStk)){
		if ($row['stkid'] == $_SESSION['seluser']){
			$stakeHolder = $row['stkname'];
			break;
		}else{
			$stakeHolder = "";
		}
	}
}

$getProvince = mysql_query("SELECT PkLocID as prov_id, LocName as prov_title FROM tbl_locations
							WHERE loclvl=2 and ParentID is not null");
while($row = mysql_fetch_array($getProvince)){
	if ($row['prov_id'] == $_SESSION['all_provinces']){
		$province = $row['prov_title'];
		break;
	}else{
		$province = "";
	}
}

$getDistrict = mysql_query("SELECT PkLocID as whrec_id, LocName as wh_name FROM tbl_locations
							WHERE loclvl=3 order by LocName");

while($row = mysql_fetch_array($getDistrict)){
	if ($row['whrec_id'] == $_SESSION['all_districts']){
		$districts = $row['wh_name'];
		break;
	}else{
		$districts = "";
	}
}

$graphYear = $_SESSION['graphyear'];

if($_SESSION['optvals'] == 1){
	$graphCaption = $indicator." -> ".$cmpOption." -> ".$stakeHolder;
}
if($_SESSION['optvals'] == 2){
	$graphCaption = $indicator." -> ".$cmpOption." -> ".$stakeHolder." -> ".$province;
}
if($_SESSION['optvals'] == 3){
	$graphCaption = $indicator." -> ".$cmpOption." -> ".$stakeHolder." -> ".$districts;
}
if($_SESSION['optvals'] == 4){
	$graphCaption = $indicator." -> ".$cmpOption." -> ".$graphYear;
}
if($_SESSION['optvals'] == 5){
	$graphCaption = $indicator." -> ".$cmpOption." -> ".$province." -> ".$graphYear;
}
if($_SESSION['optvals'] == 6){
	$graphCaption = $indicator." -> ".$cmpOption." -> ".$districts." -> ".$graphYear;
}
if($_SESSION['optvals'] == 7){
	$graphCaption = $indicator." -> ".$cmpOption." -> ".$stakeHolder." -> ".$graphYear;
}
if($_SESSION['optvals'] == 8){
	$graphCaption = $indicator." -> ".$cmpOption." -> ".$stakeHolder." -> ".$graphYear;
}

/***********************************************************************************************************

Developed by Syed Aun Irtaza email: aun.irtaza@hotmail.com

Major File which is used for all type of graph operations. This is the parameter form which performs all kinds
of graph related works. This file has a form which allows various kinds of graph combinations. 

This file is included in templategraphreport.php it takes the parameters from form generate csv files and pass the parameters 
to templategraphreport.php

this file is making the use of stored procedures whose details are

column values are coming from reports table.

the stored procedure work in a way that  

1st parameter: column type 
2nd parameter: Graph/Report check
3rd parameter: it will have different case 'F' field, 'W' warehouse, 'T' Total 'M' month of stock if stakeholder will come then we will
increase 'S' with each case like 'FS','WS' etc, if province is also coming then add 'P' as well like 'FSP', 'WSP'
If we are including the district aswell then include 'D' aswell e.g. 'FSPD','WSPD','TSPD' etc

4th parameter: Products will appear in fourth paramater
5th parameter: stakeholder id will appear here, if we are taking all stakeholders then place '0' here
6th parameter: Province id will appear here
7th parameter: district id will appaer here in single quotes


							if($col_bk=='fld_issue_up') //means we are checking consumption with stakeholder
							{
								$col="REPgetData('C','G','FS',$i,$j,'$products[$k]',$seluser,0,0)";

							}
							if($col_bk=='wh_cbl_a') //means we are checking closingbalance with stakeholder
							{
								$col="REPgetData('B','G','WS',$i,$j,'$products[$k]',$seluser,0,0)
";

							}
							if($col_bk=='fld_cbl_a') //means we are checking field closingbalsnce with stakeholder
							{
								$col="REPgetData('B','G','FS',$i,$j,'$products[$k]',$seluser,0,0)";

							}
							if($col_bk=='wh_cbl_a+fld_cbl_a') //means we are checking total closingbalance with stakeholder
							{
								$col="REPgetData('B','G','TS',$i,$j,'$products[$k]',$seluser,0,0)";

							}
							if($col_bk=='amc') //means we are checking amc with stakeholder
							{
								$col="REPgetData('A','G','FS',$i,$j,'$products[$k]',$seluser,0,0)";

							}
							if($col_bk=='getMOSW()') //means we are checking month of stock for warehouse with stakeholder
							{
								$col="REPgetData('M','G','WS',$i,$j,'$products[$k]',$seluser,0,0)";

							}
							if($col_bk=='getMOSF()') //means we are checking field month of stock with stakeholder
							{
								$col="REPgetData('M','G','FS',$i,$j,'$products[$k]',$seluser,0,0)";

							}
							if($col_bk=='getMOS()') //means we are checking total month of stock with stakeholder
							{
								$col="REPgetData('M','G','TS',$i,$j,'$products[$k]',$seluser,0,0)";

							}
							if($col_bk=='CYP') //means we are checking couple year protection values with stakeholder
							{
								$col="REPgetData('Y','G','FS',$i,$j,'$products[$k]',$seluser,0,0)";

							}
							
							
							
							if($col_bk=='fld_issue_up') //means we are checking consumption without stakeholder
							{
								$col="REPgetData('C','G','F',$i,$j,'$products[$k]',0,0,0)";

							}
							if($col_bk=='wh_cbl_a') //means we are checking closingbalance without stakeholder
							{
								$col="REPgetData('B','G','W',$i,$j,'$products[$k]',0,0,0)
";

							}
							if($col_bk=='fld_cbl_a') //means we are checking field closingbalsnce without stakeholder
							{
								$col="REPgetData('B','G','F',$i,$j,'$products[$k]',0,0,0)";

							}
							if($col_bk=='wh_cbl_a+fld_cbl_a') //means we are checking total closingbalance without stakeholder
							{
								$col="REPgetData('B','G','T',$i,$j,'$products[$k]',0,0,0)";

							}
							if($col_bk=='amc') //means we are checking amc without stakeholder
							{
								$col="REPgetData('A','G','F',$i,$j,'$products[$k]',0,0,0)";

							}
							if($col_bk=='getMOSW()') //means we are checking month of stock for warehouse without stakeholder
							{
								$col="REPgetData('M','G','W',$i,$j,'$products[$k]',0,0,0)";

							}
							if($col_bk=='getMOSF()') //means we are checking field month of stock without stakeholder
							{
								$col="REPgetData('M','G','F',$i,$j,'$products[$k]',0,0,0)";

							}
							if($col_bk=='getMOS()') //means we are checking total month of stock without stakeholder
							{
								$col="REPgetData('M','G','T',$i,$j,'$products[$k]',0,0,0)";

							}
							if($col_bk=='CYP') //means we are checking couple year protection values without stakeholder
							{
								$col="REPgetData('Y','G','F',$i,$j,'$products[$k]',0,0,0)";

							}
							
							
							
							
							if($col_bk=='fld_issue_up') //means we are checking consumption with stakeholder in province 
							{
								$col="REPgetData('C','G','FSP',$i,$j,'$products[$k]',$seluser,$provinces[0],0)";

							}
							if($col_bk=='wh_cbl_a') //means we are checking closingbalance with stakeholder in province 
							{
								$col="REPgetData('B','G','WSP',$i,$j,'$products[$k]',$seluser,$provinces[0],0)
";

							}
							if($col_bk=='fld_cbl_a') //means we are checking field closingbalsnce with stakeholder in province 
							{
								$col="REPgetData('B','G','FSP',$i,$j,'$products[$k]',$seluser,$provinces[0],0)";

							}
							if($col_bk=='wh_cbl_a+fld_cbl_a') //means we are checking total closingbalance with stakeholder in province 
							{
								$col="REPgetData('B','G','TSP',$i,$j,'$products[$k]',$seluser,$provinces[0],0)";

							}
							if($col_bk=='amc') //means we are checking amc with stakeholder in province 
							{
								$col="REPgetData('A','G','FSP',$i,$j,'$products[$k]',$seluser,$provinces[0],0)";

							}
							if($col_bk=='getMOSW()') //means we are checking month of stock for warehouse with stakeholder in province 
							{
								$col="REPgetData('M','G','WSP',$i,$j,'$products[$k]',$seluser,$provinces[0],0)";

							}
							if($col_bk=='getMOSF()') //means we are checking field month of stock with stakeholder in province 
							{
								$col="REPgetData('M','G','FSP',$i,$j,'$products[$k]',$seluser,$provinces[0],0)";

							}
							if($col_bk=='getMOS()') //means we are checking total month of stock with stakeholder in province 
							{
								$col="REPgetData('M','G','TSP',$i,$j,'$products[$k]',$seluser,$provinces[0],0)";

							}
							if($col_bk=='CYP') //means we are checking couple year protection values with stakeholder in province 
							{
								$col="REPgetData('Y','G','FSP',$i,$j,'$products[$k]',$seluser,$provinces[0],0)";

							}
							
							
							
							
							
							
							
							if($col_bk=='fld_issue_up') //means we are checking consumption with stakeholder in DISTRICT 
							{
								$col="REPgetData('C','G','FSPD',$i,$j,'$products[$k]',$seluser,$provinces[0],$dists[0])";

							}
							if($col_bk=='wh_cbl_a') //means we are checking closingbalance with stakeholder in DISTRICT 
							{
								$col="REPgetData('B','G','WSPD',$i,$j,'$products[$k]',$seluser,$provinces[0],$dists[0])
";

							}
							if($col_bk=='fld_cbl_a') //means we are checking field closingbalsnce with stakeholder in DISTRICT 
							{
								$col="REPgetData('B','G','FSPD',$i,$j,'$products[$k]',$seluser,$provinces[0],$dists[0])";

							}
							if($col_bk=='wh_cbl_a+fld_cbl_a') //means we are checking total closingbalance with stakeholder in DISTRICT 
							{
								$col="REPgetData('B','G','TSPD',$i,$j,'$products[$k]',$seluser,$provinces[0],$dists[0])";

							}
							if($col_bk=='amc') //means we are checking amc with stakeholder in DISTRICT 
							{
								$col="REPgetData('A','G','FSPD',$i,$j,'$products[$k]',$seluser,$provinces[0],$dists[0])";

							}
							if($col_bk=='getMOSW()') //means we are checking month of stock for warehouse with stakeholder in DISTRICT 
							{
								$col="REPgetData('M','G','WSPD',$i,$j,'$products[$k]',$seluser,$provinces[0],$dists[0])";

							}
							if($col_bk=='getMOSF()') //means we are checking field month of stock with stakeholder in DISTRICT 
							{
								$col="REPgetData('M','G','FSPD',$i,$j,'$products[$k]',$seluser,$provinces[0],$dists[0])";

							}
							if($col_bk=='getMOS()') //means we are checking total month of stock with stakeholder in DISTRICT 
							{
								$col="REPgetData('M','G','TSPD',$i,$j,'$products[$k]',$seluser,$provinces[0],$dists[0])";

							}
							if($col_bk=='CYP') //means we are checking couple year protection values with stakeholder in DISTRICT 
							{
								$col="REPgetData('Y','G','FSPD',$i,$j,'$products[$k]',$seluser,$provinces[0],$dists[0])";

							}
							
							
							



/***********************************************************************************************************/

session_start();
	ob_start();

$objDB=new Database();
$objDB->connect();

$objDB2=new Database();
$objDB2->connect();

$objDB3=new Database();
$objDB3->connect();

$objDB31=new Database();
$objDB31->connect();


$objDB4=new Database();
$objDB4->connect();

$objDB5=new Database();
$objDB5->connect();

$objDB6=new Database();
$objDB6->connect();

$objDB7=new Database();
$objDB7->connect();

$objDB8=new Database();
$objDB8->connect();


$objDB_fav1=new Database();
$objDB_fav1->connect();

$objDB_prov=new Database();
$objDB_prov->connect();

$objDB_dist=new Database();
$objDB_dist->connect();

$objDB_dist1=new Database();
$objDB_dist1->connect();

$db=new Database();
$db->connect();

$db2=new Database();
$db2->connect();

$db3=new Database();
$db3->connect();

$db4=new Database();
$db4->connect();




$objCms=new cCms();

$data_array=array();

/**************************** Section for Befor Submit of the form**************************/

// products select box option values using table itminfo_tab

$sql   ="select * from `itminfo_tab` where itm_status='Current' order by frmindex";

if ($objDB->query($sql) and $objDB->get_num_rows() > 0)
    {
    for ($i=0; $i < $objDB->get_num_rows(); $i++)
        {
        $row = $objDB->fetch_row_assoc();
        array_push($data_array, $row);
        }
    }

// graph xy labels devide factors etc using table "reports"
$data_array_rep=array();

$sql   ="select * from reports where report_type=1 and report_show_comp = 1 order by report_group, report_order";

if ($objDB4->query($sql) and $objDB4->get_num_rows() > 0)
    {
    for ($i=0; $i < $objDB4->get_num_rows(); $i++)
        {
        $row = $objDB4->fetch_row_assoc();
        array_push($data_array_rep, $row);
        }
    }
//// All province list using table province
$all_prov_array=array();

$sql   ="SELECT PkLocID as prov_id, LocName as prov_title FROM tbl_locations
							WHERE loclvl=2 and ParentID is not null order by prov_id ";

if ($db3->query($sql) and $db3->get_num_rows() > 0)
    {
    for ($i=0; $i < $db3->get_num_rows(); $i++)
        {
        $row = $db3->fetch_row_assoc();
        array_push($all_prov_array, $row);
        }
    }

//// All district list
$all_dist_array=array();

$sql   ="SELECT PkLocID as whrec_id, LocName as wh_name FROM tbl_locations
							WHERE loclvl=3 order by LocName";

if ($db4->query($sql) and $db4->get_num_rows() > 0)
    {
    for ($i=0; $i < $db4->get_num_rows(); $i++)
        {
        $row = $db4->fetch_row_assoc();
        array_push($all_dist_array, $row);
        }
    }

$data_array_per=array();

$sql="select * from `period` where begin_month!=0 and period_code >12";

if ($objDB2->query($sql) and $objDB2->get_num_rows() > 0)
    {
    for ($i=0; $i < $objDB2->get_num_rows(); $i++)
        {
        $row = $objDB2->fetch_row_assoc();
        array_push($data_array_per, $row);
        }
    }

// stakeholder box option values
$data_array_stake=array();

$sql="select * from `stakeholder` where ParentID is null AND stakeholder.stk_type_id IN (0,1) order by stkorder";

if ($objDB3->query($sql) and $objDB3->get_num_rows() > 0)
    {
    for ($i=0; $i < $objDB3->get_num_rows(); $i++)
        {
        $row = $objDB3->fetch_row_assoc();
        array_push($data_array_stake, $row);
        }
    }

$data_array_groups=array();


$sql2="SELECT
facilitygroupname.fac_group_name
FROM
facilitygroupname
ORDER BY
facilitygroupname.fac_group_name ASC";

if ($objDB6->query($sql2) and $objDB6->get_num_rows() > 0)
    {
    for ($i=0; $i < $objDB6->get_num_rows(); $i++)
        {
        $row = $objDB6->fetch_row_assoc();
        array_push($data_array_groups, $row);
        }
    }
//month array      
$monthval=array
    (
    "JAN",
    "FEB",
    "MAR",
    "APR",
    "MAY",
    "JUN",
    "JUL",
    "AUG",
    "SEP",
    "OCT",
    "NOV",
    "DEC"
    );
$provarray= array
	(
	"Punjab",
	"Sindh",
	"Khyber Pakhtoonkhwa",
	"Balochistan",
	"AJK",
	"FATA",
	"Gilgit Baltistan",
	"Islamabad"
	);
	
	 $unq = (rand()*9);
	//echo $userlogged = base64_decode($_REQUEST['logged']);  exit;
	
if (count($_POST))
    {
		
/*********************** Sessions for option retaining because problem started in request ***************/		

	$_SESSION['seluser']    =""; 
    $_SESSION['report_type']="";
    $_SESSION['period']     ="";
    $_SESSION['year']       ="";
	$_SESSION['sel_stakeholder'] = "";
    $_SESSION['products']   ="";
	$_SESSION['all_provinces'] ="";
	$_SESSION['all_districts'] = "";
	$_SESSION['arrproducts1']	="";
	$_SESSION['compare_opt']="";
    $_SESSION['optvals']    ="";	
    $_SESSION['yearcomp']   ="";	
	$_SESSION['provinces']  ="";	
	$_SESSION['dists']   	="";	
	$_SESSION['sel_prov']   ="";
	$_SESSION['compare_opt']="";
    $_SESSION['optvals']    ="";	
    $_SESSION['yearcomp']   ="";	
	$_SESSION['provinces']  ="";	
	$_SESSION['dists']		="";	
	$_SESSION['sel_prov']   ="";
    $_SESSION['stakecomp']  ="";
    $_SESSION['groupcomp']  ="";
	$_SESSION['rep_id']		="";
	
	$_SESSION['rep_desc']   ="";
	$_SESSION['arrgroupcomp']="";
	$_SESSION['ctype']  	="";
	//$_SESSION['comparison_title']="";
	$_SESSION['allyears']="";
	$_SESSION['allprovinces']="";
	$_SESSION['alldistricts']="";
	$_SESSION['allstakes']="";
	$_SESSION['period'] = 1;
	$_SESSION['arryearcomp']="";
    $_SESSION['proc']="";
    
    $_SESSION['report_id']="";
    $_SESSION['optvals2']    =0;
	
		
	
/***************************** Forms Posted Values are recived here***************************************/
		
	$userlogged = base64_decode($_SESSION['user']['LogedUser']); 
    $seluser    =$_POST['sel_user'];
	
	$_SESSION['seluser'] = $seluser ;
	 
    $report_type=$_POST['sel_stakeholder'];
	$_SESSION['report_type']=$report_type;
	
	$col		= $report_type; 
    $period     =$_POST['period'];
	$_SESSION['period'] = $period;
	
    $year       =intval($_POST['year']);
	$_SESSION['year']=$year;
	
	$sel_stakeholder = $_REQUEST['sel_stakeholder'];
	$_SESSION['sel_stakeholder'] = $sel_stakeholder;
	
    $products   =$_POST['products'];
	$_SESSION['products']=$products;
	
    // disabled favourite setting feature
	//$chkfav     =$_POST['chkfav'];

    $chkfav = 0;
	$_SESSION['chkfav'] = $chkfav;
	
	$all_provinces = $_POST['all_provinces'];
	$_SESSION['all_provinces']=$all_provinces;
	
	$all_districts = $_POST['all_districts'];
	$_SESSION['all_districts']=$all_districts;
	
	$arrproducts=implode(",",$_POST['products']); 
	$_SESSION['arrproducts1'] = $arrproducts; 
	
	$wh_id="0,";
	if($seluser==0)
	{
	$sql="select wh_id from tbl_warehouse where 1=1 order by wh_id asc";
	}
	else
	{
	$sql="select wh_id from tbl_warehouse where stkid=".$seluser;
	}
	if ($db2->query($sql) and $db2->get_num_rows() > 0)
		{
		for ($ii=0; $ii < $db2->get_num_rows(); $ii++)
			{
			$row = $db2->fetch_row_assoc();
			$wh_id .=$row['wh_id'].",";
			
			}
		}
	$wh_id =substr($wh_id,0,-1);
	
    $compare_opt=$_POST['compare_opt'];
	$_SESSION['compare_opt']=$compare_opt;
	
    $optvals    =$_POST['optvals'];
	$_SESSION['optvals'] = $optvals;
	
    $yearcomp   =$_POST['yearcomp'];
	$_SESSION['yearcomp'] = $yearcomp;	
	
	$provinces   =$_POST['provinces'];	
	$_SESSION['provinces'] = $provinces;
	
	$dists   =$_POST['districts'];	
	$_SESSION['districts']="";
	$_SESSION['districts']=$districts;
	
	$sel_prov   =$_POST['sel_prov'];
	$_SESSION['sel_prov']= $sel_prov;
	
    $stakecomp  =$_POST['stakecomp'];
	$_SESSION['stakecomp']=$stakecomp;
	
    $groupcomp  =$_POST['groupcomp'];	
	$_SESSION['groupcomp'] = $groupcomp;
	
	$rep_id=$db->executeScalar("select report_id from reports where report_id='$report_type'");
	$_SESSION['rep_id'] = $rep_id;
	
	$rep_desc=getReportDescription($rep_id); // this function is definced in functionlib.php
	$_SESSION['rep_desc']=$rep_desc;
	
	if(empty($_REQUEST['arrgroupcomp']) && !empty($_REQUEST['groupcomp']))
	{
	$arrgroupcomp=implode(",",$_POST['groupcomp']);
	$_SESSION['arrgroupcomp'] = $arrgroupcomp; 
	}
	else
	{
		$arrgroupcomp=$_REQUEST['arrgroupcomp'];
		$_SESSION['arrgroupcomp'] = $arrgroupcomp; 
	}
	
	$ctype  =$_POST['ctype'];
	$_SESSION['ctype'] = $ctype; 
	
	if($optvals == 1 )
	{
		$comparison_title=" Yearly Comparison";
	}
	if($optvals == 2)
	{
		$comparison_title=" Stakeholder wise Comparison";
	}
	if($optvals == 3)
	{
		$comparison_title=" Group wise Comparison";
	}
	
	
	$productname=array();

	for($i=0; $i < sizeof($products); $i++)
		{
		$sql="select itm_name from itminfo_tab where itmrec_id='" . $products[$i] . "'";
		array_push($productname,$objDB2->executeScalar($sql));		
		}
		
		
    $allyears   ="";
	
    for ($i=0; $i < sizeof($yearcomp); $i++)
        {
        $allyears.=$yearcomp[$i] . ",";
        }

    $allyears=substr($allyears, 0, -1);
	$_SESSION['allyears'] = $allyears; 
	
	$allprovinces   ="";
	
    for ($i=0; $i < sizeof($provinces); $i++)
        {
        $allprovinces.=$provarray[$provinces[$i]-1] . ",";
        }

    $allprovinces=substr($allprovinces, 0, -1);
	$_SESSION['allprovinces'] = $allprovinces; 
	 $allstakes   ="";
	
    for ($i=0; $i < sizeof($dists); $i++)
        {
			$sqldist= "SELECT PkLocID as whrec_id, LocName as wh_name FROM tbl_locations
							WHERE PkLocID='".$dists[$i]."'";
			$distlabel = $objDB_dist1->executeScalar($sqldist);
       		$alldistricts.=$distlabel . ",";
        }

    $alldistricts=substr($alldistricts, 0, -1);
	$_SESSION['alldistricts'] = $alldistricts;
	
	for ($i=0; $i < sizeof($stakecomp); $i++)
        {
        $allstakes.= urlencode($stakecomp[$i]) . ",";
        }

    $allstakes=substr($allstakes, 0, -1);
	$_SESSION['allstakes'] = $allstakes;
		
	$sql="select report_title from reports where report_id='" . $col . "'";
	$col_title=$objDB2->executeScalar($sql);
	$_SESSION['col_title'] = $col_title;
	
	$sql="select report_units from reports where report_id='" . $col . "'";
	$unit=$objDB2->executeScalar($sql); 
	
	$sql="select report_xaxis from reports where report_id='" . $col . "'";
	$xaxis=$objDB2->executeScalar($sql); 
	
	$sql="select report_factor from reports where report_id='" . $col . "'";
	$col_factor=$objDB2->executeScalar($sql);
	
	$sql="select report_factor from reports where report_id='" . $col . "'";
	$col_factor=$objDB2->executeScalar($sql);
	
	
	if($seluser !="all")
	{ 
	$sql=$objCms->Select("stakeholder", " * ", " and stkname='$seluser' "); 
                        

	if ($objDB5->query($sql) and $objDB5->get_num_rows() > 0)
		{
		for ($ii=0; $ii < $objDB5->get_num_rows(); $ii++)
			{
			$row = $objDB5->fetch_one_assoc();
			$rep_title1=$row['report_title1']; 
			$rep_title2=$row['report_title2'];
			$rep_title3=$row['report_title3'];
			$rep_logo=$row['report_logo'];
			
			}
		}
	}
	else
	{
			$rep_title1="Govt. of Pakistan"; 
			$rep_title2="MOPW,DOH,LHW";
			$rep_title3="";
			$rep_logo="add.gif";
			
	}
   
    if ($period == 1)
        {
        $start_mth=$data_array_per[0]['begin_month'];
        $end_mth  =$data_array_per[0]['end_month'];
		$period_lable=$monthval[ $start_mth-1]." To ".$monthval[$end_mth-1];
        }

    if ($period == 2)
        {
        $start_mth=$data_array_per[1]['begin_month'];
        $end_mth  =$data_array_per[1]['end_month'];
		$period_lable=$monthval[ $start_mth-1]." To ".$monthval[$end_mth-1];
        }

    if ($period == 3)
        {
        $start_mth=$data_array_per[2]['begin_month'];
        $end_mth  =$data_array_per[2]['end_month'];
		$period_lable=$monthval[ $start_mth-1]." To ".$monthval[$end_mth-1];
        }

    if ($period == 4)
        {
        $start_mth=$data_array_per[3]['begin_month'];
        $end_mth  =$data_array_per[3]['end_month'];
		$period_lable=$monthval[ $start_mth-1]." To ".$monthval[$end_mth-1];
        }

    if ($period == 5)
        {
        $start_mth=$data_array_per[4]['begin_month'];
        $end_mth  =$data_array_per[4]['end_month'];
		$period_lable=$monthval[ $start_mth-1]." To ".$monthval[$end_mth-1];
        }

    if ($period == 6)
        {
        $start_mth=$data_array_per[5]['begin_month'];
        $end_mth  =$data_array_per[5]['end_month'];
		$period_lable=$monthval[ $start_mth-1]." To ".$monthval[$end_mth-1];
        }

    if ($period == 7)
        {
        $start_mth=$data_array_per[6]['begin_month'];
        $end_mth  =$data_array_per[6]['end_month'];
		$period_lable=$monthval[ $start_mth-1]." To ".$monthval[$end_mth-1];
        }
		
	
	$col_bk = $col;

	$_SESSION['col_bk'] = $col_bk;


//flag for all or particular stakeholder
 $sel_stk_opt = ($seluser == 0) ? 0 : 1;
   
/*
 retrieve procedure call    
 */
 
  $sqlproc="select report_data_sql as proc, report_data_pos as dpos, report_id  from report_options where report_stk = ".$sel_stk_opt.
 " and report_id='".$report_type."' and report_comp = '".$optvals."'   "; 
 //print $sqlproc;
 //exit;
                        if ($objDB8->query($sqlproc) and $objDB8->get_num_rows() > 0)
                            { 
                               for ($ii=0; $ii < $objDB8->get_num_rows(); $ii++)
                               {
                                    $proc = "";
                                    $row = $objDB8->fetch_row_assoc();
                                    $proc  = $row['proc'];
                                    $dpos = $row['dpos'];
                                    $_SESSION['report_id']= $row['report_id'];                                    
                                }
                            }
    
 $col = $proc;
 
/*
 Yearly Comparision - National 
*/ 
        if ($optvals == 1)
            { 

				$filedata="";
				$filedata1="";
				$flg =0;
				$yc = $_POST['yearcomp'];
				$test = array();
				if(empty($_REQUEST['arryearcomp']) && !empty($_REQUEST['yearcomp']))
				{				
				
				$_POST['yearcomp'] = array_reverse($_POST['yearcomp']);
				 $arryearcomp=implode(",",$_POST['yearcomp']); 
				 $_SESSION['arryearcomp'] = $arryearcomp;
				
				}
				if(!empty($_REQUEST['arryearcomp']) && empty($_REQUEST['yearcomp']))
				{
					$arryearcomp=$_REQUEST['arryearcomp']; 
					$_SESSION['arryearcomp'] = $arryearcomp;
				}
				if(!empty($_REQUEST['arryearcomp']) && !empty($_REQUEST['yearcomp']))
				{
				$_POST['yearcomp'] = array_reverse($_POST['yearcomp']);
				$arryearcomp=implode(",",$_POST['yearcomp']); 
				$_SESSION['arryearcomp'] = $arryearcomp;
				 
				}			
				
            $year1     =$year;
            $year2     =$year;
            $allfiles  ="";
            $titles="";
			$wh_id_obt="";
            $dbg_sql="";

/*
 Yearly Comparision - National 
*/            
    
            for ($k=0; $k < sizeof($products); $k++)
                {
                for ($i=$start_mth; $i <= $end_mth; $i++)
                    {
                    $filedata1 = "";

                    for ($j=sizeof($yearcomp)-1; $j >= 0; $j--)
                     {   
        				$sql="select ".str_replace("\$i",$i,$col)." as xyz  from dual ";
                        $sql= str_replace("\$yearcomp[\$j]",$yearcomp[$j],$sql);
                        $sql= str_replace("'\$products[\$k]'","'".$products[$k]."'",$sql);
                        $sql= str_replace("\$seluser",$seluser,$sql);
                        $sql= str_replace("\$all_provinces",$all_provinces,$sql);
                        $sql= str_replace("\$all_diststricts","'".$all_districts."'",$sql);
                        $dbg_sql.=$sql.'<br>';

                       //print $dbg_sql;
					   //exit;
      				if ($objDB3->query($sql) and $objDB3->get_num_rows() > 0)
                        	{ 
                       		for ($ii=0; $ii < $objDB3->get_num_rows(); $ii++)
                            {
							$res = "";
                            $row = $objDB3->fetch_row_assoc();
							$res = explode('*',$row['xyz']);
                            $row['xyz']=$res[$dpos]/$col_factor; 
                            $filedata1.=$row['xyz'] . ",";
                            }
                        }
                       }                       
                    $filedata1=substr($filedata1, 0, -1);
                    $filedata.=$monthval[$i - 1] . "," . $filedata1 . "\n"; 
                    }

$_SESSION['proc'] = $optvals.'***'.$dbg_sql.'***'. $filedata;			       
                //echo $filedata1."here"; exit;
                $myFile="../../plmis_data/testdata".$unq . $k . ".csv"; 
                $fh    =fopen($myFile, 'w+');
                fwrite($fh, $filedata);
                fclose ($fh);
                $allfiles.=$myFile . ",";
                $titles.=$productname[$k] . ",";
                $filedata ="";
                $filedata1="";
                }
				
		    // echo $allfiles; exit;
			
            header ("Location: templategraphreport.php?yearcomp=$allyears&count=". sizeof($products). "&titles=" . substr($titles,0,-1)."&allfiles=" . substr($allfiles,0,-1) 
                                . "&case=2" 
                                . "&sel_user=$seluser"
                                . "&ctype=$ctype"
                                . "&optvals=$optvals"
                                . "&sel_stakeholder=$sel_stakeholder"
								. "&period=$period"
                                . "&year1=$year1"
								. "&arrproducts=$arrproducts"
								. "&compare_opt=$compare_opt"
								. "&arryearcomp=$arryearcomp"
								. "&arrstakecomp=$arrstakecomp"
								. "&arrgroupcomp=$arrgroupcomp"														
			       );
            }
/*
 Yearly Comparision - Provincial 
*/
        if ($optvals == 2)
            { 

                $filedata="";
                $filedata1="";
                $flg =0;
                $yc = $_POST['yearcomp'];
                $test = array();
                if(empty($_REQUEST['arryearcomp']) && !empty($_REQUEST['yearcomp']))
                {                
                
                $_POST['yearcomp'] = array_reverse($_POST['yearcomp']);
                 $arryearcomp=implode(",",$_POST['yearcomp']); 
                 $_SESSION['arryearcomp'] = $arryearcomp;
                
                }
                if(!empty($_REQUEST['arryearcomp']) && empty($_REQUEST['yearcomp']))
                {
                    $arryearcomp=$_REQUEST['arryearcomp']; 
                    $_SESSION['arryearcomp'] = $arryearcomp;
                }
                if(!empty($_REQUEST['arryearcomp']) && !empty($_REQUEST['yearcomp']))
                {
                $_POST['yearcomp'] = array_reverse($_POST['yearcomp']);
                $arryearcomp=implode(",",$_POST['yearcomp']); 
                $_SESSION['arryearcomp'] = $arryearcomp;
                 
                }            
                
            $year1     =$year;
            $year2     =$year;
            $allfiles  ="";
            $titles="";
            $wh_id_obt="";
            $dbg_sql ="";

    
            for ($k=0; $k < sizeof($products); $k++)
                {
                for ($i=$start_mth; $i <= $end_mth; $i++)
                    {
                    $filedata1 = "";

                    for ($j=sizeof($yearcomp)-1; $j >= 0; $j--)
                     {   
                        $sql="select ".str_replace("\$i",$i,$col)." as xyz  from dual ";
                        $sql= str_replace("\$yearcomp[\$j]",$yearcomp[$j],$sql);
                        $sql= str_replace("'\$products[\$k]'","'".$products[$k]."'",$sql);
                        $sql= str_replace("\$seluser",$seluser,$sql);
                        $sql= str_replace("\$all_provinces",$all_provinces,$sql);
                        $sql= str_replace("\$all_districts","'".$all_districts."'",$sql);
                        $dbg_sql.=$sql.'<br>';

                        
                      if ($objDB3->query($sql) and $objDB3->get_num_rows() > 0)
                            { 
                               for ($ii=0; $ii < $objDB3->get_num_rows(); $ii++)
                            {
                            $res = "";
                            $row = $objDB3->fetch_row_assoc();
                            $res = explode('*',$row['xyz']);
                            $row['xyz']=$res[$dpos]/$col_factor; 
                            $filedata1.=$row['xyz'] . ",";
                            }
                        }
                       }                       
                    $filedata1=substr($filedata1, 0, -1);
                    $filedata.=$monthval[$i - 1] . "," . $filedata1 . "\n"; 
                    }

$_SESSION['proc'] = $optvals.'***'.$dbg_sql.'***'.$filedata ;                   
                //echo $filedata1."here"; exit;
                $myFile="../../plmis_data/testdata".$unq . $k . ".csv"; 
                $fh    =fopen($myFile, 'w+');
                fwrite($fh, $filedata);
                fclose ($fh);
                $allfiles.=$myFile . ",";
                $titles.=$productname[$k] . ",";
                $filedata ="";
                $filedata1="";
                }
                
             
            header ("Location: templategraphreport.php?yearcomp=$allyears&count=". sizeof($products). "&titles=" . substr($titles,0,-1)."&allfiles=" . substr($allfiles,0,-1) 
                                . "&case=2" 
                                . "&sel_user=$seluser"
                                . "&ctype=$ctype"
                                . "&optvals=$optvals"
                                . "&sel_stakeholder=$sel_stakeholder"
                                . "&period=$period"
                                . "&year1=$year1"
                                . "&arrproducts=$arrproducts"
                                . "&compare_opt=$compare_opt"
                                . "&arryearcomp=$arryearcomp"
                                . "&arrstakecomp=$arrstakecomp"
                                . "&arrgroupcomp=$arrgroupcomp"                                                        
                   );
            }
 /*
 Yearly Comparision - District 
*/
        if ($optvals == 3)
            { 

                $filedata="";
                $filedata1="";
                $flg =0;
                $yc = $_POST['yearcomp'];
                
                $test = array();
                if(empty($_REQUEST['arryearcomp']) && !empty($_REQUEST['yearcomp']))
                {                
                
                $_POST['yearcomp'] = array_reverse($_POST['yearcomp']);
                 $arryearcomp=implode(",",$_POST['yearcomp']); 
                 $_SESSION['arryearcomp'] = $arryearcomp;
                
                }
                if(!empty($_REQUEST['arryearcomp']) && empty($_REQUEST['yearcomp']))
                {
                    $arryearcomp=$_REQUEST['arryearcomp']; 
                    $_SESSION['arryearcomp'] = $arryearcomp;
                }
                if(!empty($_REQUEST['arryearcomp']) && !empty($_REQUEST['yearcomp']))
                {
                $_POST['yearcomp'] = array_reverse($_POST['yearcomp']);
                $arryearcomp=implode(",",$_POST['yearcomp']); 
                $_SESSION['arryearcomp'] = $arryearcomp;
                 
                }            
                
            $year1     =$year;
            $year2     =$year;
            $allfiles  ="";
            $titles="";
            $wh_id_obt="";
            $dbg_sql = "";

    
            for ($k=0; $k < sizeof($products); $k++)
                {
                for ($i=$start_mth; $i <= $end_mth; $i++)
                    {
                    $filedata1 = "";

                    for ($j=sizeof($yearcomp)-1; $j >= 0; $j--)
                     {   
                        $sql="select ".str_replace("\$i",$i,$col)." as xyz  from dual ";
                        $sql= str_replace("\$yearcomp[\$j]",$yearcomp[$j],$sql);
                        $sql= str_replace("'\$products[\$k]'","'".$products[$k]."'",$sql);
                        $sql= str_replace("\$seluser",$seluser,$sql);
                        $sql= str_replace("\$all_provinces",$all_provinces,$sql);
                        $sql= str_replace("\$all_districts","'".$all_districts."'",$sql);
                        $dbg_sql.=$sql.'<br>';

                      if ($objDB3->query($sql) and $objDB3->get_num_rows() > 0)
                            { 
                               for ($ii=0; $ii < $objDB3->get_num_rows(); $ii++)
                            {
                            $res = "";
                            $row = $objDB3->fetch_row_assoc();
                            $res = explode('*',$row['xyz']);
                            $row['xyz']=$res[$dpos]/$col_factor; 
                            $filedata1.=$row['xyz'] . ",";
                            }
                        }
                       }                       
                    $filedata1=substr($filedata1, 0, -1);
                    $filedata.=$monthval[$i - 1] . "," . $filedata1 . "\n"; 
                    }

$_SESSION['proc'] = $optvals.'***'.$dbg_sql.'***'.$filedata ;                   
                //echo $filedata1."here"; exit;
                $myFile="../../plmis_data/testdata".$unq . $k . ".csv"; 
                $fh    =fopen($myFile, 'w+');
                fwrite($fh, $filedata);
                fclose ($fh);
                $allfiles.=$myFile . ",";
                $titles.=$productname[$k] . ",";
                $filedata ="";
                $filedata1="";
                }
                
             
            header ("Location: templategraphreport.php?yearcomp=$allyears&count=". sizeof($products) 
                                . "&titles=" . substr($titles,0,-1)
                                . "&allfiles=" . substr($allfiles,0,-1) 
                                . "&case=2" 
                                . "&sel_user=$seluser"
                                . "&ctype=$ctype"
                                . "&optvals=$optvals"
                                . "&sel_stakeholder=$sel_stakeholder"
                                . "&period=$period"
                                . "&year1=$year1"
                                . "&arrproducts=$arrproducts"
                                . "&compare_opt=$compare_opt"
                                . "&arryearcomp=$arryearcomp"
                                . "&arrstakecomp=$arrstakecomp"
                                . "&arrgroupcomp=$arrgroupcomp"                                                        
                   );
            }
/*
 Stakeholder - National
*/
   if ($optvals == 4 or $optvals == 5 or $optvals == 6) 
   {
   /*
 retrieve procedure call    
 */
 
            $sqlproc="select report_data_sql as proc, report_data_pos as dpos  from report_options where report_stk = 1".
                 " and report_id='".$report_type."' and report_comp =".$optvals; 
//print $sqlproc;
//exit;
                        if ($objDB8->query($sqlproc) and $objDB8->get_num_rows() > 0)
                            { 
                               for ($ii=0; $ii < $objDB8->get_num_rows(); $ii++)
                               {
                                    $proc = "";
                                    $row = $objDB8->fetch_row_assoc();
                                    $proc  = $row['proc'];
                                    $dpos = $row['dpos'];                                    
                                }
                            }
   $col = $proc;
   $objDB8->close();          
   }
   
/*
 Stakeholder - National
*/    
    if ($optvals == 4 )
            {
                
                if(empty($_REQUEST['arrstakecomp']) && !empty($_REQUEST['stakecomp']))
                {
                $arrstakecomp=implode(",",$_POST['stakecomp']);
                $_SESSION['arrstakecomp'] = $arrstakecomp; 
                }
                else
                {
                $arrstakecomp=$_REQUEST['arrstakecomp'];
                $_SESSION['arrstakecomp'] = $arrstakecomp; 
                }
                
        
            $year1     =$year;
            $year2     =$year;
            $allfiles  ="";
            $titles="";
            $dbg_sql ="";

           
             for ($k=0; $k < sizeof($products); $k++)
                {
                for ($i=$start_mth; $i <= $end_mth; $i++)
                    {
                    $filedata1 = "";

                    for ($j=0; $j < sizeof($stakecomp); $j++)
                     {   
  
                      $sql3="select stkid from stakeholder where stkname='".$stakecomp[$j]."'";
                      $sid=$objDB7->executeScalar($sql3);
                      //$col="REPgetData('C','G','FS',$i,$year1,'$products[$k]',$sid,0,0)";
                        $sql="select ".str_replace("\$i",$i,$col)." as xyz  from dual ";
                        $sql= str_replace("\$year1",$year1,$sql);
                        $sql= str_replace("'\$products[\$k]'","'".$products[$k]."'",$sql);
                        $sql= str_replace("\$sid",$sid,$sql);
                        $sql= str_replace("\$all_provinces",$all_provinces,$sql);
                        $sql= str_replace("\$all_districts","'".$all_districts."'",$sql);
                        $dbg_sql.=$sql.'<br>';

      

                      if ($objDB3->query($sql) and $objDB3->get_num_rows() > 0)
                            { 
                               for ($ii=0; $ii < $objDB3->get_num_rows(); $ii++)
                            {
                            $res = "";
                            $row = $objDB3->fetch_row_assoc();
                            $res = explode('*',$row['xyz']);
                            $row['xyz']=$res[$dpos]/$col_factor; 
                            $filedata1.=$row['xyz'] . ",";
                            }
                        }
                       }                       
                    $filedata1=substr($filedata1, 0, -1);
                    $filedata.=$monthval[$i - 1] . "," . $filedata1 . "\n";
                    }  
$_SESSION['proc'] = $optvals.'***'.$dbg_sql.'***'.$filedata ;  
                $myFile="../../plmis_data/testdata".$unq . $k . ".csv";
                $fh    =fopen($myFile, 'w+');             
                fwrite($fh, $filedata);
                fclose ($fh);
                $allfiles.=$myFile . ",";
                $titles.=$productname[$k] . ",";
                $filedata ="";
                $filedata1="";
                }
            
            header ("Location: templategraphreport.php?yearcomp=$year&stakecomp=$allstakes&count=". sizeof($products) 
                                . "&titles=" . substr($titles,0,-1)
                                . "&allfiles=" . substr($allfiles,0,-1) 
                                . "&case=3" 
                                . "&sel_user=$seluser"
                                . "&ctype=$ctype"
                                . "&optvals=$optvals"
                                . "&sel_stakeholder=$sel_stakeholder"
                                . "&period=$period"
                                . "&year1=$year1"
                                . "&arrproducts=$arrproducts"
                                . "&compare_opt=$compare_opt"
                                . "&arryearcomp=$arryearcomp"
                                . "&arrstakecomp=$arrstakecomp"
                                . "&arrgroupcomp=$arrgroupcomp"                                                        
                   );
           
            }
/*
 Stakeholder - Provincial
*/
    if ($optvals == 5 )
            {
                
                if(empty($_REQUEST['arrstakecomp']) && !empty($_REQUEST['stakecomp']))
                {
                $arrstakecomp=implode(",",$_POST['stakecomp']);
                $_SESSION['arrstakecomp'] = $arrstakecomp; 
                }
                else
                {
                $arrstakecomp=$_REQUEST['arrstakecomp'];
                $_SESSION['arrstakecomp'] = $arrstakecomp; 
                }
                
        
            $year1     =$year;
            $year2     =$year;
            $allfiles  ="";
            $titles="";
            $dbg_sql ="";

           
             for ($k=0; $k < sizeof($products); $k++)
                {
                for ($i=$start_mth; $i <= $end_mth; $i++)
                    {
                    $filedata1 = "";

                    for ($j=0; $j < sizeof($stakecomp); $j++)
                     {   
  
                      $sql3="select stkid from stakeholder where stkname='".$stakecomp[$j]."'";
                      $sid=$objDB7->executeScalar($sql3);
                      //$col="REPgetData('C','G','FS',$i,$year1,'$products[$k]',$sid,0,0)";
                        $sql="select ".str_replace("\$i",$i,$col)." as xyz  from dual ";
                        $sql= str_replace("\$year1",$year1,$sql);
                        $sql= str_replace("'\$products[\$k]'","'".$products[$k]."'",$sql);
                        $sql= str_replace("\$sid",$sid,$sql);
                        $sql= str_replace("\$all_provinces",$all_provinces,$sql);
                        $sql= str_replace("\$all_districts","'".$all_districts."'",$sql);
                        $dbg_sql.=$sql.'<br>';
      

                      if ($objDB3->query($sql) and $objDB3->get_num_rows() > 0)
                            { 
                               for ($ii=0; $ii < $objDB3->get_num_rows(); $ii++)
                            {
                            $res = "";
                            $row = $objDB3->fetch_row_assoc();
                            $res = explode('*',$row['xyz']);
                            $row['xyz']=$res[$dpos]/$col_factor; 
                            $filedata1.=$row['xyz'] . ",";
                            }
                        }
                       }                       
                    $filedata1=substr($filedata1, 0, -1);
                    $filedata.=$monthval[$i - 1] . "," . $filedata1 . "\n"; 
                    }  

$_SESSION['proc'] = $optvals.'***'.$dbg_sql.'***'.$filedata ;
                    
                $myFile="../../plmis_data/testdata".$unq . $k . ".csv";
                $fh    =fopen($myFile, 'w+');
                
                fwrite($fh, $filedata);
                fclose ($fh);
                $allfiles.=$myFile . ",";
                $titles.=$productname[$k] . ",";
                $filedata ="";
                $filedata1="";
                }
            
            header ("Location: templategraphreport.php?yearcomp=$year&stakecomp=$allstakes&count=". sizeof($products) 
                                . "&titles=" . substr($titles,0,-1)
                                . "&allfiles=" . substr($allfiles,0,-1) 
                                . "&case=3" 
                                . "&sel_user=$seluser"
                                . "&ctype=$ctype"
                                . "&optvals=$optvals"
                                . "&sel_stakeholder=$sel_stakeholder"
                                . "&period=$period"
                                . "&year1=$year1"
                                . "&arrproducts=$arrproducts"
                                . "&compare_opt=$compare_opt"
                                . "&arryearcomp=$arryearcomp"
                                . "&arrstakecomp=$arrstakecomp"
                                . "&arrgroupcomp=$arrgroupcomp"                                                        
                   );
           
            
            
            
            
            
            }
/*
 Stakeholder - District
*/
    if ($optvals == 6 )
            {
                
                if(empty($_REQUEST['arrstakecomp']) && !empty($_REQUEST['stakecomp']))
                {
                $arrstakecomp=implode(",",$_POST['stakecomp']);
                $_SESSION['arrstakecomp'] = $arrstakecomp; 
                }
                else
                {
                $arrstakecomp=$_REQUEST['arrstakecomp'];
                $_SESSION['arrstakecomp'] = $arrstakecomp; 
                }
                
        
            $year1     =$year;
            $year2     =$year;
            $allfiles  ="";
            $titles="";
            $dbg_sql="";

           
             for ($k=0; $k < sizeof($products); $k++)
                {
                for ($i=$start_mth; $i <= $end_mth; $i++)
                    {
                    $filedata1 = "";

                    for ($j=0; $j < sizeof($stakecomp); $j++)
                     {   
  
                      $sql3="select stkid from stakeholder where stkname='".$stakecomp[$j]."'";
                      $sid=$objDB7->executeScalar($sql3);
                      //$col="REPgetData('C','G','FS',$i,$year1,'$products[$k]',$sid,0,0)";
                        $sql="select ".str_replace("\$i",$i,$col)." as xyz  from dual ";
                        $sql= str_replace("\$year1",$year1,$sql);
                        $sql= str_replace("'\$products[\$k]'","'".$products[$k]."'",$sql);
                        $sql= str_replace("\$sid",$sid,$sql);
                        $sql= str_replace("\$all_provinces",$all_provinces,$sql);
                        $sql= str_replace("\$all_districts","'".$all_districts."'",$sql);
                        $dbg_sql.=$sql.'<br>';

 
                      if ($objDB3->query($sql) and $objDB3->get_num_rows() > 0)
                            { 
                               for ($ii=0; $ii < $objDB3->get_num_rows(); $ii++)
                            {
                            $res = "";
                            $row = $objDB3->fetch_row_assoc();
                            $res = explode('*',$row['xyz']);
                            $row['xyz']=$res[$dpos]/$col_factor; 
                            $filedata1.=$row['xyz'] . ",";
                            }
                        }
                       }                       
                    $filedata1=substr($filedata1, 0, -1);
                    $filedata.=$monthval[$i - 1] . "," . $filedata1 . "\n"; 
                    }  
$_SESSION['proc'] = $optvals.'***'.$dbg_sql.'***'.$filedata ;
                    
                $myFile="../../plmis_data/testdata".$unq . $k . ".csv";
                $fh    =fopen($myFile, 'w+');
                
                fwrite($fh, $filedata);
                fclose ($fh);
                $allfiles.=$myFile . ",";
                $titles.=$productname[$k] . ",";
                $filedata ="";
                $filedata1="";
                }
            
            header ("Location: templategraphreport.php?yearcomp=$year&stakecomp=$allstakes&count=". sizeof($products) 
                                . "&titles=" . substr($titles,0,-1)
                                . "&allfiles=" . substr($allfiles,0,-1) 
                                . "&case=3" 
                                . "&sel_user=$seluser"
                                . "&ctype=$ctype"
                                . "&optvals=$optvals"
                                . "&sel_stakeholder=$sel_stakeholder"
                                . "&period=$period"
                                . "&year1=$year1"
                                . "&arrproducts=$arrproducts"
                                . "&compare_opt=$compare_opt"
                                . "&arryearcomp=$arryearcomp"
                                . "&arrstakecomp=$arrstakecomp"
                                . "&arrgroupcomp=$arrgroupcomp"                                                        
                   );
           }
        
/*
 Provincial
*/
			if ($optvals == 7)
            {

            $districts = "";    				
			if(empty($_REQUEST['arrprovinces']) && !empty($_REQUEST['provinces']))
			{
			$arrprovinces=implode(",",$_POST['provinces']); 
			$_SESSION['arrprovinces'] = $arrprovinces;
			}
			else
			{
				$arrprovinces=$_REQUEST['arrprovinces'];
				$_SESSION['arrprovinces'] = $arrprovinces;
			}
            $year1     =$year;
            $year2     =$year;
            $allfiles  ="";
            $titles="";
			$wh_dis="0,";
            $dbg_sql.=$sql.'<br>';
           
	
			
              for ($k=0; $k < sizeof($products); $k++)
                {
                for ($i=$start_mth; $i <= $end_mth; $i++)
                    {
                    $filedata1 = "";

                   for ($j=0; $j < sizeof($provinces); $j++)
                        {
  
                      $sql3="select stkid from stakeholder where stkname='".$stakecomp[$j]."'";
                      $sid=$objDB7->executeScalar($sql3);
                        //$col="REPgetData('C','G','F',$i,$year1,'$products[$k]',0,$provinces[$j],0)
                        $sql="select ".str_replace("\$i",$i,$col)." as xyz  from dual ";
                        $sql= str_replace("\$year1",$year1,$sql);
                        $sql= str_replace("'\$products[\$k]'","'".$products[$k]."'",$sql);                 
                        $sql= str_replace("\$seluser",$seluser,$sql);
                        $sql= str_replace("\$provinces[\$j]",$provinces[$j],$sql);
                        $dbg_sql.=$sql.'<br>';
 
  
                      if ($objDB3->query($sql) and $objDB3->get_num_rows() > 0)
                            { 
                               for ($ii=0; $ii < $objDB3->get_num_rows(); $ii++)
                            {
                            $res = "";
                            $row = $objDB3->fetch_row_assoc();
                            $res = explode('*',$row['xyz']);
                            $row['xyz']=$res[$dpos]/$col_factor; 
                            $filedata1.=$row['xyz'] . ",";
                            }
                        }
                       }                       
                    $filedata1=substr($filedata1, 0, -1);
                    $filedata.=$monthval[$i - 1] . "," . $filedata1 . "\n"; 
                    }  

$_SESSION['proc'] = $optvals.'***'.$dbg_sql.'***'.$filedata ;
                    
                $myFile="../../plmis_data/testdata".$unq . $k . ".csv";
                $fh    =fopen($myFile, 'w+');
				
                fwrite($fh, $filedata);
                fclose ($fh);
                $allfiles.=$myFile . ",";
                $titles.=$productname[$k] . ",";
                $filedata ="";
                $filedata1="";
                }
			
		
             header ("Location: templategraphreport.php?yearcomp=$year&stakecomp=$allstakes&provinces=$allprovinces                           &count=". sizeof($products) 
                                . "&titles=" . substr($titles,0,-1)
                                . "&allfiles=" . substr($allfiles,0,-1) 
                                . "&case=4" 
                                . "&sel_user=$seluser"
                                . "&ctype=$ctype"
                                . "&optvals=$optvals"
                                . "&sel_stakeholder=$sel_stakeholder"
                                . "&period=$period"
                                . "&year1=$year1"
                                . "&arrproducts=$arrproducts"
                                . "&compare_opt=$compare_opt"
                                . "&arryearcomp=$arryearcomp"
                                . "&arrstakecomp=$arrstakecomp"
                                . "&arrgroupcomp=$arrgroupcomp"                                                        
                   );
              
     }
/*
 District
*/	
			if ($optvals == 8 )
            {
				
			if(empty($_REQUEST['arrdistricts']) && !empty($_REQUEST['districts']))
			{
			$arrdistricts=implode(",",$_POST['districts']); 
			$_SESSION['arrdistricts'] = $arrdistricts;
			}
			else
			{
				$arrdistricts=$_REQUEST['arrdistricts'];
				$_SESSION['arrdistricts'] = $arrdistricts;
			}
			
			
            $year1     =$year;
            $year2     =$year;
            $allfiles  ="";
            $titles="";
            $dbg_sql ="";			
           
               for ($k=0; $k < sizeof($products); $k++)
                {
                for ($i=$start_mth; $i <= $end_mth; $i++)
                    {
                    $filedata1 = "";

                    for ($j=0; $j < sizeof($dists); $j++)
                        {
  
                      $sql3="select stkid from stakeholder where stkname='".$stakecomp[$j]."'";
                      $sid=$objDB7->executeScalar($sql3);
                        //$col="REPgetData('C','G','FSPD',$i,$year1,'$products[$k]',$seluser,$provinces[0],'$dists[$j]')"; 
                        $sql="select ".str_replace("\$i",$i,$col)." as xyz  from dual ";
                        $sql= str_replace("\$year1",$year1,$sql);
                        $sql= str_replace("'\$products[\$k]'","'".$products[$k]."'",$sql);                 
                        $sql= str_replace("\$seluser",$seluser,$sql);
                        $sql= str_replace("\$provinces[0]",$provinces[0],$sql);
                        $sql= str_replace("\$dists[\$j]",$dists[$j],$sql);
                        $dbg_sql.=$sql.'<br>';
 

  
                      if ($objDB3->query($sql) and $objDB3->get_num_rows() > 0)
                            { 
                               for ($ii=0; $ii < $objDB3->get_num_rows(); $ii++)
                            {
                            $res = "";
                            $row = $objDB3->fetch_row_assoc();
                            $res = explode('*',$row['xyz']);
                            $row['xyz']=$res[$dpos]/$col_factor; 
                            $filedata1.=$row['xyz'] . ",";
                            }
                        }
                       }                       
                    $filedata1=substr($filedata1, 0, -1);
                    $filedata.=$monthval[$i - 1] . "," . $filedata1 . "\n"; 
                    }

$_SESSION['proc'] = $optvals.'***'.$dbg_sql.'***'.$filedata ;

                $myFile="../../plmis_data/testdata".$unq . $k . ".csv";
                $fh    =fopen($myFile, 'w+');
				
                fwrite($fh, $filedata);
                fclose ($fh);
                $allfiles.=$myFile . ",";
                $titles.=$productname[$k] . ",";
                $filedata ="";
                $filedata1="";
                }
			
			
             header ("Location: templategraphreport.php?yearcomp=$year&stakecomp=$allstakes&provinces=$allprovinces&districts=$alldistricts&count=". sizeof($products) 
                                . "&titles=" . substr($titles,0,-1)
                                . "&allfiles=" . substr($allfiles,0,-1) 
                                . "&case=5" 
                                . "&sel_user=$seluser"
                                . "&ctype=$ctype"
                                . "&optvals=$optvals"
                                . "&sel_stakeholder=$sel_stakeholder"
                                . "&period=$period"
                                . "&year1=$year1"
                                . "&arrproducts=$arrproducts"
                                . "&compare_opt=$compare_opt"
                                . "&arryearcomp=$arryearcomp"
                                . "&arrstakecomp=$arrstakecomp"
                                . "&arrgroupcomp=$arrgroupcomp"                                                        
                   );

            
            
            }
        
	}	
	
	function retwh_id($input)
	{
		
		$sql="select wh_id from tbl_warehouse where stkid=$input";
	$wh_id1="0,";
	$db2 = new database();
	$db2->connect();
	if ($db2->query($sql) and $db2->get_num_rows() > 0)
		{ 
		for ($ii=0; $ii < $db2->get_num_rows(); $ii++)
			{
			$row3 = $db2->fetch_row_assoc();
			$wh_id1 .=$row3['wh_id'].",";
			
			}
		}
	$wh_id1 =substr($wh_id1,0,-1); 
	return $wh_id1;
	}
	
	function retwh_idStake($input,$dist_id)
	{
		
		$sql="select wh_id from tbl_warehouse where stkid=$input and dist_id=$dist_id";
	$wh_id1="0,";
	$db2 = new database();
	$db2->connect();
	if ($db2->query($sql) and $db2->get_num_rows() > 0)
		{ 
		for ($ii=0; $ii < $db2->get_num_rows(); $ii++)
			{
			$row3 = $db2->fetch_row_assoc();
			$wh_id1 .=$row3['wh_id'].",";
			
			}
		}
	$wh_id1 =substr($wh_id1,0,-1); 
	return $wh_id1;
	}
	
	$selected="selected";
	$objDB->close();
	$objDB2->close();
	$objDB3->close();
	$objDB4->close();
	
?>
        <SCRIPT LANGUAGE = "JAVASCRIPT" TYPE = "TEXT/JAVASCRIPT">
            <!--
 //window.onerror = ScriptError;
            
            function ScriptError()
                {
                window.parent.location = "../Error.php";
                return true;
                }
            function ReportPrint(strSQL, Article, Type)
                {
                URL = "PrintNationalCon.php?strSQL=" + strSQL + "&Type=" + Type + "&Article=" + Article
                day = new Date();
                id = day.getTime();
                eval("page" + id + " = window.open(URL, '" + id
                        + "', 'toolbar=0,scrollbars=yes ,location=0,statusbar=0 ,menubar=0,resizable=1,width=750,height=650,left = 20,top = 20');");
                }

            function Add_Remove(fromBox, toBox, selecBox)
                {
                fromList = document.getElementById(fromBox);
                toList = document.getElementById(toBox);
                //----------------------------------//
                //fromLength=fromList.options.length;
                toLength = toList.options.length;
                //alert(toLength);
                //---------------------------------//
                from_selIndx = fromList.selectedIndex; // alert(from_selIndex);
                //alert(from_selIndx);
                //----------------------------------//
                if (from_selIndx != -1)
                    {
                    optObj_text = fromList.options[from_selIndx].text;
                    optObj_value = fromList.options[from_selIndx].value;
                    toList.options[toLength] = new Option(optObj_text, optObj_value, true, false);
                    //-------------------remove option------------------------//
                    fromList.options[from_selIndx] = null;
                    }

                //---------------------------------------//
                boxSelect = document.getElementById(selecBox);

                for (i = 0; i < boxSelect.options.length; i++)
                    {
                    boxSelect.options[i].selected = true;
                    }
                }
            //-->

            function showhidemain()
                {
					
                var opt;
                opt = document.getElementById('compare_opt').value;

                if (opt == 1)
                    {
						//alert("main12");
                     //document.getElementById('optvals').disabled = false;
					 //document.getElementById('repoptions').style.display= "blo";
					 $('#repoptions').show("slow");
						
                    }

                else
                    {
						$('#repoptions').hide("slow");
						$('#rowyearcomp').hide("slow");
						$('#rowstakecomp').hide("slow");
						$('#rowprovreg_opt').hide("slow");
						$('#rowprovreg_optStake').hide("slow");
						$('#rowprov').hide("slow");
						$('#rowdistrictscomp').hide("slow");
						$('#row_all_prov').hide("slow");
						$('#row_all_dist').hide("slow");
						
						document.getElementById('stake').selectedIndex = -1;
                        document.getElementById('yearcomp').selectedIndex = -1;
                        //document.getElementById('provinces').selectedIndex = -1;
						//document.getElementById('districts').selectedIndex = -1;
						//document.getElementById('all_provinces').selectedIndex = -1;
						//document.getElementById('all_districts').selectedIndex = -1;
					
                  
                    }
               

                }


            function showhidestart()
                {
 
                    
                var opt;
                opt = document.getElementById('sel_stakeholder').value;
                if (opt != 0)
                    {                       
                     $('#repoptions').show("slow");
                        
                    }

                else
                    {
                        $('#repoptions').hide("slow");
                        $('#liststakeholders').hide("slow");
                        $('#productlist').hide("slow");
                        $('#listtimeinterval').hide("slow");
                        $('#listyears').hide("slow");
                        $('#compareflag').hide("slow");                 
                        $('#rowyearcomp').hide("slow");
                        $('#rowstakecomp').hide("slow");
                        $('#rowprovreg_opt').hide("slow");
                        $('#rowprovreg_optStake').hide("slow");
                        $('#rowprov').hide("slow");
                        $('#rowdistrictscomp').hide("slow");
                        $('#row_all_prov').hide("slow");
                        $('#row_all_dist').hide("slow");
                        $('#rowcharttype').hide("slow");
                        $('#rowgenerate').hide("slow");
                        
                        document.getElementById('stake').selectedIndex = -1;
                        document.getElementById('yearcomp').selectedIndex = -1;
                        document.getElementById('provinces').selectedIndex = -1;
                        document.getElementById('districts').selectedIndex = -1;
                        document.getElementById('all_provinces').selectedIndex = -1;
                        document.getElementById('all_districts').selectedIndex = -1;
                    
                  
                    }
               

                }


            function releventrow()
                {
                var opt;
                opt = document.getElementById('optvals').value;

                    $('#productlist').show("slow");  
                    $('#rowgenerate').show("slow");
                    $('#rowcharttype').show("slow");
					
					if((opt == 1 ) || (opt ==2) || (opt == 3))
					{
                        // alert(opt);
                        
						$('#rowyearcomp').show("slow");
						$('#rowstakecomp').hide("slow");
						$('#rowprovreg_opt').hide("slow");
						$('#rowprovreg_optStake').hide("slow");
						$('#rowprov').hide("slow");
						$('#rowdistrictscomp').hide("slow");
						$('#row_all_prov').hide("slow");
						$('#row_all_dist').hide("slow");
                        $('#liststakeholders').show("slow");
                        $('#rowdistrictscomp').hide("slow");
                        $('#listyears').hide("slow");

                        
                        
                        if (opt == 2 ) $('#row_all_prov').show("slow");
                        if (opt == 3 ) $('#row_all_dist').show("slow");
						
						document.getElementById('firstopt').style.display="block";
						document.getElementById('secondopt').style.display="block";
						//document.getElementById('thirdopt').style.display="none";
						//document.getElementById('ctype').selectedIndex = 0;
						document.getElementById('stake').selectedIndex = -1;
                        //document.getElementById('provinces').selectedIndex = -1;
						//document.getElementById('districts').selectedIndex = -1;
						//document.getElementById('all_provinces').selectedIndex = -1;
						//document.getElementById('all_districts').selectedIndex = -1;
											
					}
					if((opt == 4 ) || (opt ==5) || (opt == 6))
					{
						$('#rowyearcomp').hide("slow");
						$('#rowstakecomp').show("slow");
						$('#rowprovreg_opt').hide("slow");
						$('#rowprovreg_optStake').hide("slow");
						$('#rowprov').hide("slow");
						$('#rowdistrictscomp').hide("slow");
						$('#row_all_prov').hide("slow");
						$('#row_all_dist').hide("slow");
                        $('#liststakeholders').hide("slow");
                        $('#rowdistrictscomp').hide("slow");
                        $('#listyears').show("slow");

                        if (opt == 5 ) $('#row_all_prov').show("slow");
                        if (opt == 6 ) $('#row_all_dist').show("slow");
						
						//document.getElementById('all_provinces').selectedIndex = -1;
						//document.getElementById('all_districts').selectedIndex = -1;
						
						document.getElementById('firstopt').style.display="block";
						document.getElementById('secondopt').style.display="block";
						//document.getElementById('thirdopt').style.display="none";
						
						//document.getElementById('ctype').selectedIndex = 0;
                        //document.getElementById('yearcomp').selectedIndex = -1;
                        //document.getElementById('provinces').selectedIndex = -1;
						//document.getElementById('districts').selectedIndex = -1;
											
					}
					if(opt == 7)
					{
                        
                        //alert('7hi');
                        
						$('#rowyearcomp').hide("slow");
						$('#rowstakecomp').hide("slow");
						$('#rowprovreg_optStake').hide("slow");
						$('#rowprovreg_opt').hide("slow");
						$('#rowprov').show("slow");
						$('#row_all_prov').hide("slow");
						$('#row_all_dist').hide("slow");
                        $('#liststakeholders').show("slow");
                        $('#rowdistrictscomp').hide("slow");
                        $('#listyears').show("slow");
						
						//document.getElementById('all_provinces').selectedIndex = -1;
						//document.getElementById('all_districts').selectedIndex = -1;
						
						
						//document.getElementById('firstopt').style.display="block";
						//document.getElementById('secondopt').style.display="block";
						//document.getElementById('thirdopt').style.display="none";
						//document.getElementById('ctype').selectedIndex = 0;
						//document.getElementById('stake').selectedIndex = -1;
                        //document.getElementById('yearcomp').selectedIndex = -1;
						//document.getElementById('districts').selectedIndex = -1;
						
						
						fetchProvReg();
					}
					if(opt == 8)
					{
						$('#rowyearcomp').hide("slow");
						$('#rowstakecomp').hide("slow");
						$('#rowprovreg_optStake').hide("slow");
						$('#rowprov').hide("slow");
						$('#row_all_prov').hide("slow");
						$('#row_all_dist').hide("slow");
                        $('#liststakeholders').show("slow");
                        $('#rowdistrictscomp').show("slow");
						$('#listyears').show("slow");
                        
						
						//document.getElementById('all_provinces').selectedIndex = -1;
						//document.getElementById('all_districts').selectedIndex = -1;
						
						
						//document.getElementById('stake').selectedIndex = -1;
                        //document.getElementById('firstopt').style.display="block";
                        //document.getElementById('secondopt').style.display="block";
                        //document.getElementById('thirdopt').style.display="none";
						
						//document.getElementById('ctype').selectedIndex = 0;
                        //document.getElementById('yearcomp').selectedIndex = -1;
						//document.getElementById('districts').selectedIndex = -1;
			
						
						fetchDistrictsAll();
					}
    //              }
                }
				
			function fetchProducts()
			{
			var val;
			//$('#selectchilddiv').hide("slow");//by me
			val = $('#sel_user').val();
			
			if(val){	
				var html = $.ajax({
					
					beforeSend: function(){
						// Handle the beforeSend event
						//alert('before Send!');
					},
					url: "fetchProducts.php",
					data: "pid="+val,			 
					async: false,
					complete: function(){
					}
				}).responseText;
				
				
				$('#productsArea').html(html);
				
				//fetchGroups();
			}
			
			//$('#selectchilddiv').show("slow");//by me
			}
			
			function fetchProducts2(val){
				if(val){	
				var html = $.ajax({
					beforeSend: function(){
						// Handle the beforeSend event
						//alert('before Send!');
					},
					url: "fetchProducts.php",
					data: "pid="+val,			 
					async: false,
					complete: function(){
					}
				}).responseText;

				$('#productsArea').html(html);
			}
			
			//$('#selectchilddiv').show("slow");//by me
			}
			
			function fetchProv()
			{
			var val;
			
			//$('#selectchilddiv').hide("slow");//by me
			val = $('#sel_prov').val();
			
			//alert(val1);
			if(val){	
				var html = $.ajax({
					
					beforeSend: function(){
						// Handle the beforeSend event
						//alert('before Send!');
					},
					url: "fetchProv.php",
					data: "pid="+val,			 
					async: false,
					complete: function(){
					}
				}).responseText;
				
				
				$('#regionArea').html(html);	
				
				if(val==4)
				{
				fetchDistricts();
				
				//$('#rowprov').show("slow");
				$('#rowdistrictscomp').show("slow");
				
				document.getElementById('stake').selectedIndex = -1;
                document.getElementById('yearcomp').selectedIndex = -1;
				}
				else
				{
					//alert("thei");
					
				$('#rowprov').show("slow");
				$('#rowdistrictscomp').hide("slow");	
				}
				
			}
			
		//releventrow();
			//$('#selectchilddiv').show("slow");//by me
			}




            function fetchProvReg()
            {
                 var html = $.ajax({
                    
                    beforeSend: function(){
                        // Handle the beforeSend event
                        //alert('before Send!');
                    },
                    url: "fetchProv.php",
                    data: "pid=3",             
                    async: false,
                    complete: function(){
                    }
                }).responseText;
                
               $('#regionArea').html(html);    
                
            
            
        //releventrow();
            //$('#selectchilddiv').show("slow");//by me
            }


			
			
			function fetchDistricts()
			{
			var val;
			//alert("here");
			//$('#selectchilddiv').hide("slow");//by me
			val = $('#provinces').val();
			
				if(val)
				{	
					var html = $.ajax({
						
						beforeSend: function(){
							// Handle the beforeSend event
							//alert('before Send!');
						},
						url: "fetchDistricts.php",
						//data: "pid=0"+val,
                        data: "pid="+val,             			 
						async: false,
						complete: function(){
						}
					}).responseText;
					
					
					$('#districtsArea').html(html);
					
					
				}
			}
			


            function fetchDistrictsAll()
            {
                   var html = $.ajax({
                        
                        beforeSend: function(){
                            // Handle the beforeSend event
                            //alert('before Send!');
                        },
                        url: "fetchDistricts.php",
                        //data: "pid=0"+val,
                        data: "pid=0",                          
                        async: false,
                        complete: function(){
                        }
                    }).responseText;
                    
                    
                    $('#districtsArea').html(html);
                    
                    
                
            }


			
			
			
			function fetchGroups()
			{
			var val;
			
			//$('#selectchilddiv').hide("slow");//by me
			val = $('#sel_user').val();
			
				if(val)
				{	
					var html = $.ajax({
						
						beforeSend: function(){
							// Handle the beforeSend event
							//alert('before Send!');
						},
						url: "fetchGroups.php",
						data: "pid="+val,			 
						async: false,
						complete: function(){
						}
					}).responseText;
					
					
					$('#groupsArea').html(html);
					
					
				}
			}
			
			//////////////////////////////////////////////////////////////////////////////////////////////////////////////
		   //////////////////////////////// Stakeholder comparison for provinces and districts//////////////////////////
			
			function fetchProvStake()
			{
			var val;
			
			//$('#selectchilddiv').hide("slow");//by me
			val = $('#sel_provStake').val();
			
			//alert(val1);
			if(val){	
				var html = $.ajax({
					
					beforeSend: function(){
						// Handle the beforeSend event
						//alert('before Send!');
					},
					url: "fetchProvStake.php",
					data: "pid="+val,			 
					async: false,
					complete: function(){
					}
				}).responseText;
				
				
				$('#regionArea').html(html);	
				
				if(val==4)
				{
				fetchDistrictsStake();
				
				//$('#rowprov').show("slow");
				$('#rowdistrictscomp').show("slow");
				
				//document.getElementById('stake').selectedIndex = -1;
                document.getElementById('yearcomp').selectedIndex = -1;
				}
				else
				{
					//alert("thei");
					
				$('#rowprov').show("slow");
				$('#rowdistrictscomp').hide("slow");	
				}
				
			}
			
		//releventrow();
			//$('#selectchilddiv').show("slow");//by me
			}
			
			
			function fetchDistrictsStake()
			{
			var val;
			//alert("here");
			//$('#selectchilddiv').hide("slow");//by me
			val = $('#provinces').val();
			
				if(val)
				{	
					var html = $.ajax({
						
						beforeSend: function(){
							// Handle the beforeSend event
							//alert('before Send!');
						},
						url: "fetchDistrictsStake.php",
						data: "pid="+val,			 
						async: false,
						complete: function(){
						}
					}).responseText;
					
					
					$('#districtsArea').html(html);
					
					
				}
			}
			
			
			
			
			
			
			
			function showhidefuncs()
			{
				var opt1;
                opt1 = document.getElementById('compare_opt').value;
			
				//alert(opt1);
				showhidemain();
				if(opt1==1)
				{
				releventrow();
				}
					//fetchproducts();
					fetchProducts();
				//document.getElementById('userlog')=window.parent('
	
            
            }
			
        </SCRIPT>

        <?php
        //include ("../../plmis_inc/common/FunctionLib.php"); //Include Global Function File
        //include ('../../plmis_inc/common/DateTime.php');    //Include Date Function File    
        //include("../NationalConData/linegraph.php") ;            
        $BST      =BST_DtTm();
        $FTipItem ="";
        $FTipMonth="";
        $TipHELP  = "";
        ?>
        <script language="JavaScript" src="../../plmis_js/gen_validatorv31.js" type="text/javascript"></script>    
<!--    </HEAD>

    <BODY TOPMARGIN = "10" MARGINWIDTH = "0" MARGINHEIGHT = "0" onLoad="releventrow()">-->
       

        <FORM  NAME = "frmReport" METHOD = "POST" ENCTYPE = "MULTIPART/FORM-DATA">
 
 
        <div style="height:600px;  overflow: visible">           
            <TABLE CELLPADDING = "5" CELLSPACING = "0"  WIDTH = "200" BORDER = "0" 
 
                   ALIGN = "LEFT"  BORDERCOLOR = "#000000" style="background-color:#f1f1f1; BORDER-COLLAPSE: COLLAPSE" >
              
                <tr>
                    <TD CLASS = "TDRCOLLAN"
                        WIDTH = "70" align="justify"> <label>Indicators:</label>
                    <br> 
                        <select name = "sel_stakeholder" id = "sel_stakeholder" style = "width:200;" class="form-control input-sm input-medium">
                                                 
						   <?php
						    $gc = 0;
							$prevGroup = "X"; 
                            for ($i=0; $i < $objDB4->get_num_rows(); $i++)
                                {
							 if ($prevGroup !== $data_array_rep[$i]['report_group']) 
							 {
                                 if ($prevGroup !== 'X') echo "</optgroup>";
                                 echo "<optgroup label = ".$data_array_rep[$i]['report_group']."><br>";
								 $prevGroup = $data_array_rep[$i]['report_group'];
                                 
								 }		
                            ?>            
                                <OPTION VALUE = "<?php echo $data_array_rep[$i]['report_id']?>" <?php if($_SESSION['sel_stakeholder']==$data_array_rep[$i]['report_id']){ echo "selected";} ?>>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo
    $data_array_rep[$i]['report_title'] ?></OPTION>";

                            <?php
                                }
                            ?>
                        </select>
                    </td>
                </tr>



                 <tr id="repoptions" style="display:block;">
                        <TD CLASS = "TDRCOLLAN"
                            WIDTH = "70"><label> Compare Options</label>
                        <br>
 <select name = "optvals" id = "optvals" style = "width:200" onChange = "releventrow()" class="form-control input-sm input-medium">
 
  <optgroup label = "Years">
    <option value = "1" <?php if($_SESSION['optvals']==1 and ($_SESSION['compare_opt']==1)){ echo "selected";} ?>>Year - National</option>
    <option value = "2" <?php if($_SESSION['optvals']==2 and ($_SESSION['compare_opt']==1)){ echo "selected";} ?>>Year - Provincial</option>
    <option value = "3" <?php if($_SESSION['optvals']==3 and ($_SESSION['compare_opt']==1)){ echo "selected";} ?>>Year - District</option>
  </optgroup>
  <optgroup label = "Stakeholder">
    <option value = "4" <?php if($_SESSION['optvals']==4 and ($_SESSION['compare_opt']==1)){ echo "selected";} ?>>Stakeholder - National</option>
    <option value = "5" <?php if($_SESSION['optvals']==5 and ($_SESSION['compare_opt']==1)){ echo "selected";} ?>>Stakeholder - Provincial</option>
    <option value = "6" <?php if($_SESSION['optvals']==6 and ($_SESSION['compare_opt']==1)){ echo "selected";} ?>>Stakeholder - District</option>
  </optgroup>
  <optgroup label = "Geographical">   
    <option value = "7" <?php if($_SESSION['optvals']==7 and ($_SESSION['compare_opt']==1)){ echo "selected";} ?>>Geographical - Provinical</option>
    <option value = "8" <?php if($_SESSION['optvals']==8 and ($_SESSION['compare_opt']==1)){ echo "selected";} ?>>Geographical - District</option>
  </optgroup>
  </select>
                        </td>
                    </tr>


                <tr id="liststakeholders" style="display:none;">
                    <TD CLASS = "TDRCOLLAN"
                        WIDTH = "70"><label> Stakeholder :</label>
                    <br>
                   
                        <select name = "sel_user" id = "sel_user" style = "width:200" onChange="fetchProducts();" class="form-control input-sm input-medium">
                            <option value = "0" <?php if($_SESSION['seluser']==0){ echo "selected='selected'";} ?>>All Stakeholders</option>
                            <?php 
                            for ($i=0; $i < $objDB3->get_num_rows(); $i++)
                                { 
									
                            ?>

                                <OPTION VALUE = "<?php echo $data_array_stake[$i]['stkid']?>" <?php if($_SESSION['seluser']==$data_array_stake[$i]['stkid']){ echo "selected='selected'";} ?> ><?php echo
    $data_array_stake[$i]['stkname'] ?></OPTION>";

                            <?php
                                }
                            ?>

                           
                        </select>
                    </td>
                </tr>


                <TR id="productlist" style="display:block;">
                    <TD CLASS = "TDRCOLLAN"
                        WIDTH = "70"><label>Products:</label>
                    <br> 
                    <?php //echo $_SESSION['arrproducts']; exit; ?>
                        <div id="productsArea">
                        <?php if(isset($_SESSION['seluser']) && !empty($_SESSION['seluser'])){?>
								<script type="text/javascript">
                                	fetchProducts2('<?php echo $_SESSION['seluser']?>');
                                </script>
							<?php }else {?>
                        	<SELECT NAME = "products[]" id = "products" multiple = "multiple" size = "5"
                                style = "width:200px;" class="form-control input-sm input-medium">
                            <?php
                            for ($i=0; $i < $objDB->get_num_rows(); $i++)
                                {
                            ?>
								
                                <OPTION VALUE = "<?php echo $data_array[$i]['itmrec_id']?>" <?php if(strpos($_SESSION['arrproducts1'],$data_array[$i]['itmrec_id'])==true || strpos($_SESSION['arrproducts1'],$data_array[$i]['itmrec_id'])===0){ echo "selected='selected'";} ?>><?php echo
    $data_array[$i]['itm_name'] ?></OPTION>";

                            <?php
                                }
                            ?>
	                        </SELECT>
                        <?php }?>	
                        </div>
                    </TD>
                </TR>


                <TR id="listtimeinterval" style="display:block;">
                    <TD CLASS = "TDRCOLLAN"
                        WIDTH = "70"> <label>Time Interval:</label>
                    <br>
                        <SELECT NAME = "period" style = "width:200" class="form-control input-sm input-medium">
                            <optgroup label = "Quarter">
                                <OPTION VALUE = "1" <?php if($_SESSION['period']==1){ echo "selected";} ?>>First Quarter</OPTION>

                                <OPTION VALUE = "2" <?php if($_SESSION['period']==2){ echo "selected";} ?>>Second Quarter</OPTION>

                                <OPTION VALUE = "3" <?php if($_SESSION['period']==3){ echo "selected";} ?>>Third Quarter</OPTION>

                                <OPTION VALUE = "4" <?php if($_SESSION['period']==4){ echo "selected";} ?>>Fourth Quarter</OPTION>
                            </optgroup>

                            <optgroup label = "Half">
                                <OPTION VALUE = "5" <?php if($_SESSION['period']==5){ echo "selected";} ?>>First Half</OPTION>

                                <OPTION VALUE = "6" <?php if($_SESSION['period']==6){ echo "selected";} ?>>Second Half</OPTION>
                            </optgroup>

                            <optgroup label = "Annual">
                                <OPTION VALUE = "7" <?php if($_SESSION['period']==7){ echo "selected";} ?>>Annual</OPTION>
                            </optgroup
                            >
                        </SELECT>
                    </TD>
                </TR>
				<input type="hidden" name="userlogged" value="<?php echo base64_decode($_REQUEST['logged']); ?>" class="form-control input-sm input-medium">
                <tr id="listyears" style="display:none;">
                    <TD CLASS = "TDRCOLLAN"
                        WIDTH = "70"> <label>Year :</label>
                    <br>
                        <SELECT NAME = "year" id = "years" style = "width:200" class="form-control input-sm input-medium">
                            <?
                            $EndYear=2010;
                            $StartYear=(date('Y', $BST[7]));

                            for ($i=$StartYear; $i >= $EndYear; $i--)
                                {
                                ?>
                                    
                                    <OPTION VALUE="<?php echo $i;?>" <?php if($_SESSION['year']==$i){ echo "selected";} ?>><?php echo $i;?></OPTION>;
                               <?php }
                            ?>
                        </SELECT>
                    </TD>
                </TR>


                <TR id="compareflag" style="display:none;">
                    <TD CLASS = "TDRCOLLAN"
                        WIDTH = "70"> <label>Compare Accross</label>
                   <br>
                        <select name = "compare_opt" id = "compare_opt" style = "width:200" onChange = "showhidemain()" class="form-control input-sm input-medium">
                            <option value = "1" <?php if($_SESSION['compare_opt']==1){ echo "selected";} ?>>Yes</option>

                            <option value = "2" <?php if($_SESSION['compare_opt']==2){ echo "selected";} ?>>No</option>
                        </select>
                    </TD>
                </TR>
				
                                       
                    
                     <input type="hidden" name="userlog" id="userlog">               

                <TR id="rowyearcomp" style="display:none;">
                    <TD CLASS = "TDRCOLLAN"
                        WIDTH = "70"> <label>Years</label>
                   <br>
                        <SELECT NAME = "yearcomp[]" id = "yearcomp" style = "width:200" multiple size = "5" class="form-control input-sm input-medium">
                            <?
                            $EndYear  =2010;
                            $StartYear=(date('Y', $BST[7]));

                            for ($i=$StartYear; $i >= $EndYear; $i--)
                                {
                                ?>
                                   <OPTION VALUE="<?php echo $i;?>" <?php if(strpos($_SESSION['arryearcomp'],"$i")==true || strpos($_SESSION['arryearcomp'],"$i")===0){ echo $selected;} ?>><?php echo $i;?></OPTION>
                                <?php }
                            ?>
                        </SELECT>
                    </TD>
                </TR>

                <tr id="rowstakecomp" style="display:none;">
                    <TD CLASS = "TDRCOLLAN"
                        WIDTH = "70"> <label>Stakeholders</label>
                   <br>
                        <SELECT NAME = "stakecomp[]" id = "stake" style = "width:200" multiple size = "3" class="form-control input-sm input-medium">
                           <?php //echo $_SESSION['arrstakecomp']; exit;
                            for ($i=0; $i < $objDB3->get_num_rows(); $i++)
                                {
                            ?>
								
                                <OPTION VALUE = "<?php echo $data_array_stake[$i]['stkname']?>" <?php if(strpos($_SESSION['arrstakecomp'],$data_array_stake[$i]['stkname'])==true || strpos($_SESSION['arrstakecomp'],$data_array_stake[$i]['stkname'])===0){ echo $selected;} ?>><?php echo
    $data_array_stake[$i]['stkname'] ?></OPTION>";

                            <?php
                                }
								?>
                         </SELECT>
                    </td>
                </tr>
                
              
                <tr id="rowprovreg_opt" style="display:none;" >
                    <TD CLASS = "TDRCOLLAN"
                        WIDTH = "70"> <label>Province/Regions</label>
                        <select name = "sel_prov" id = "sel_prov" style = "width:200" onChange="fetchProv();" class="form-control input-sm input-medium">
                            <option value = "1" <?php if($_SESSION['sel_prov1']=="1"){ echo "selected='selected'";} ?> >Provinces</option>
                            <option value = "2" <?php if($_SESSION['sel_prov1']=="2"){ echo "selected='selected'";} ?> >Regions</option>
                            <option value = "3" <?php if($_SESSION['sel_prov1']=="3"){ echo "selected='selected'";} ?> >Provinces and Regions</option>
                            <option value = "4" <?php if($_SESSION['sel_prov1']=="4"){ echo "selected='selected'";} ?> >Districts</option>
                        </select>
                    </td>
                </tr>
                <tr id="rowprovreg_optStake" style="display:none;" >
                    <TD CLASS = "TDRCOLLAN"
                        WIDTH = "70"> <label>Province/Regions Opt</label>
                    <br> 
                        <select name = "sel_provStake" id = "sel_provStake" style = "width:200" onChange="fetchProvStake();" class="form-control input-sm input-medium">
                           <?php /*?> <option value = "1" <?php if($_REQUEST['sel_prov1']=="1"){ echo "selected='selected'";} ?> >Provinces</option>
                            <option value = "2" <?php if($_REQUEST['sel_prov1']=="2"){ echo "selected='selected'";} ?> >Regions</option>
                            <option value = "3" <?php if($_REQUEST['sel_prov1']=="3"){ echo "selected='selected'";} ?> >Provinces and Regions</option><?php */?>
                            <option value = "4" <?php if($_SESSION['sel_prov1']=="4"){ echo "selected='selected'";} ?> >Districts</option>
                        </select>
                    </td>
                </tr>
               <TR  id="rowprov" style="display:none;">
                    <TD CLASS = "TDRCOLLAN"
                        WIDTH = "70"> <label> Province/Regions:</label>
                    <br> 
                        <div id="regionArea"><SELECT NAME = "provinces[]" id = "provinces" multiple = "multiple" size = "5"
                                style = "width:200" class="form-control input-sm input-medium">
                           <option value="0" >Choose Prov/Region Option first</option>
                        </SELECT></div>
                    </TD>
                </TR>
              
				
                <tr id="rowdistrictscomp" style="display:none;">
                    <TD CLASS = "TDRCOLLAN"
                        WIDTH = "70"> <label>Districts</label>
                    <br>
                    <div id="districtsArea">
                        <SELECT NAME = "districts[]" id = "districts" style = "width:200" disabled multiple size = "5" class="form-control input-sm input-medium">
                         
                                <OPTION VALUE = "0">Choose Province</OPTION>";
                        </SELECT>
                     </div>
                    </td>
                </tr>
                  <tr id="row_all_prov" style="display:none;">
                    <TD CLASS = "TDRCOLLAN"
                        WIDTH = "70"> <label>Province/Region</label>
                   <br>
                        <SELECT NAME = "all_provinces" id = "all_provinces" style = "width:200" class="form-control input-sm input-medium">
                           <?php
                            for ($i=0; $i < $db3->get_num_rows(); $i++)
                                {
                            ?>
								
                                <OPTION VALUE = "<?php echo $all_prov_array[$i]['prov_id']?>" <?php if($_SESSION['all_provinces']==$all_prov_array[$i]['prov_id']){ echo "selected='selected'";} ?>><?php echo $all_prov_array[$i]['prov_title'] ?></OPTION>";

                            <?php
                                }
								?>
                         </SELECT>
                    </td>
                </tr>
                <tr id="row_all_dist" style="display:none;">
                    <TD CLASS = "TDRCOLLAN"
                        WIDTH = "70"><label> Districts</label>
                   <br>
                        <SELECT NAME = "all_districts" id = "all_districts" style = "width:200" class="form-control input-sm input-medium">
                           <?php
                            for ($i=0; $i < $db4->get_num_rows(); $i++)
                                {
                            ?>
								
                                <OPTION VALUE = "<?php echo $all_dist_array[$i]['whrec_id']?>" <?php if($_SESSION['all_districts']==$all_dist_array[$i]['whrec_id']){ echo "selected='selected'";} ?>><?php echo $all_dist_array[$i]['wh_name'] ?></OPTION>";

                            <?php
                                }
								?>
                         </SELECT>
                    </td>
                </tr>
                
             


            
                 <tr id="rowcharttype" style="display:block;">
                    <TD CLASS = "TDRCOLLAN"
                        WIDTH = "70"><label> Chart Type</label>
                    <br>
                    <div align="left"><!--  <input type="radio" name="ctype" value="pie"> Pie-->
                     <select name="ctype" id="ctype" style="width:200px;" class="form-control input-sm input-medium">
              <option value="line" id="firstopt" style="display:block;" <?php if($_SESSION['ctype']=='line'){ echo "selected='selected'";} ?>> Line</option>
              <option value="bar" id="secondopt" style="display:block;" <?php if($_SESSION['ctype']=='bar'){ echo "selected='selected'";} ?>> Bar</option>
  </select>
                    </div>
                    </td>
                </tr>
                <TR id="rowgenerate" style="display:block; padding-top:5px;">
                    <TD COLSPAN = "4"  style="text-align:left"
                        CLASS = "TableHead1" ><INPUT id="generategraph" TYPE = "image"
                                                   SRC = "../../plmis_img/CmdReport.gif"
                                                   TITLE = "Click This Button To Generate And View The Report"></TD>
                                                
                </TR>
            </TABLE>
</div>
            

     
        </FORM>
 <!--<script language="JavaScript" type="text/javascript">
    var frmvalidator = new Validator("frmReport");
    frmvalidator.addValidation("sel_stakeholder","req");
    frmvalidator.addValidation("optvals","req","Please select stakeholder");
    frmvalidator.addValidation("products[]","req", "Please select product");
    frmvalidator.addValidation("period","req","Please select period");
    frmvalidator.addValidation("year","req","Please select year");
    frmvalidator.addValidation("ctype","req","please select chart type");      
   </script>-->
<script type="text/javascript"> 
window.onload = function() { 
releventrow();
  
}; 

$("#generategraph").click(function(){
	var options = $('#products > option:selected');
        var yearSelection = $ ('#yearcomp > option:selected');
         if(options.length == 0){
             alert('Please Select Products');
             return false;
         }
         if(yearSelection.length < 2 ){
             alert('Please Select more than one year for comparison');
             return false;
         }
});
</script> 

  
    <?php
    
    //echo $sql;
       //echo $sqlproc;
    //print_r($_SESSION);
	 
?>
<!--</HTML>-->
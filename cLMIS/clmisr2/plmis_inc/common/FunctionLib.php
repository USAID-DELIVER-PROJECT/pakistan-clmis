<?php
 //Run Query Function
function safe_query($query="")
    {
        if(empty($query))
        {return false;}
        $result=mysql_query($query) or die("Query Fails:"
        ."<li> Errno=".mysql_errno()
        ."<li> ErrDetails=".mysql_error()
        ."<li> Query=".$query);
        return $result;
    }
function selField($field,$table,$id,$val){
		$querySF = "SELECT ".$field." as myval FROM `".$table."` WHERE ".$id." = '".$val."'";
		$rsSF = mysql_query($querySF) or die(mysql_error());
		$rowSF = mysql_fetch_array($rsSF);
		return $rowSF['myval'];
	}
//Calculate Time Difference
function time_diff( $time1, $time2 )
{
    if( $time2 > $time1 )
        {
            die( "error: Time 1 has to be >= Time 2 in calcDateDiff($date1, $date2)" );
        }

    $diff = $time1-$time2;
    $seconds=0;
    $hours=0;
    $minutes=0;
    
    if($diff % 86400 > 0)
        {
            $rest = ($diff % 86400);
            $days = ($diff - $rest) / 86400;
                if( $rest % 3600 > 0 )
                    {
                        $rest1 = ($rest % 3600);
                        $hours = ($rest - $rest1) / 3600;
                            if( $rest1 % 60 > 0 )
                                {
                                    $rest2 = ($rest1 % 60);
                                    $minutes = ($rest1 - $rest2) / 60;
                                    $seconds = $rest2;
                                }
                            else
                                $minutes = $rest1 / 60;
                                }
                            else
                                    $hours = $rest / 3600;
                                }
                            else
                                    $days = $diff / 86400;
                            return array($days,$hours,$minutes,$seconds);
                //call syntex
                //$arr=time_diff($logout_time, $login_time);
                //$duration=$arr[1].":".$arr[2].":".$arr[3];

}
//Calculate Time Difference

//Convert to HTML Format
function get_html($details)
{
    $str="";
    $c=strlen($details);
    for($i=0;$i<$c;$i++)
        {
            $x=ord($details[$i]);
            if($x==13){$str="$str<br>";}
            else{ $str=$str.$details[$i];}                                            
        }
return $str;
}
//Convert to HTML Format

//Check/Create Table
function CheckTable($Y,$Db,$prefix,$CreateQuery)
{
$TableFound=False;

$TableName=$Y.$prefix."_tab";
$rsTable = mysql_list_tables($Db);

while ($TableRow = mysql_fetch_row($rsTable))
    {
        if($TableRow[0]==$TableName)
        $TableFound=True;
    }
mysql_free_result($rsTable);

if($TableFound==True)
    {
        return $TableName;
    }
else
    {
        include('../../plmis_inc/common/TableQuery.php');    //Include Table Create Query File
        safe_query($$CreateQuery);
        return $TableName;
    }
}

//Check/Create Table

//Check/Create Table
function CheckTableInner($Y,$Db,$prefix,$CreateQuery)
{
$TableFound=False;

$TableName=$Y.$prefix."_tab";
$rsTable = mysql_list_tables($Db);

while ($TableRow = mysql_fetch_row($rsTable))
    {
        if($TableRow[0]==$TableName)
        $TableFound=True;
    }
mysql_free_result($rsTable);

if($TableFound==True)
    {
        return $TableName;
    }
else
    {
        include('lib/TableQuery.lib');    //Include Table Create Query File
        safe_query($$CreateQuery);
        return $TableName;
    }
}


function create_definitions($arrProfile,$arrSQL,$pageid,$month,$year,$wh)
{
    $definition = "";  //return value
    $tmp = "";    
    
    $qryfields = get_fields_list($arrProfile); //get select columns
    $qryFrom = $arrSQL['from']; //get select columns
    $qryJoin = $arrSQL['join']; //get select columns
    $qryWhereClause = $arrSQL['wherec']; //get select columns
    $qryOrderBy = $arrSQL['orderby']; //get select columns
    $qryUserFilter = ""; //get select   
    $qryAllowEdit = $arrSQL['allowEdit']; //is record editable
    $qryAllowDelete = $arrSQL['allowDelete']; //is record Deletable
    $EscapeHTML = $arrSQL['EscapeHTML']; //is record Deletable
    $EditURL = $arrSQL['EditURL']; //is record Deletable 
    
    $qryAllowFilter = $arrSQL['allowfilter']; //is record Deletable
    $qryFilterID = $arrSQL['filterid']; //is record Deletable 
    $WhereStr = " WHERE ";
    
    //add where clause 
    if  (trim($qryWhereClause) != ""  ) {    
    $qryWhereClause =  $WhereStr . $qryWhereClause;
    $WhereStr = " AND ";
    }    
    
 //WARNING: The a custom handling and should not be used application   
   
      
   if ($qryAllowFilter) {
    if(trim($pageid) != "") {
      if ($pageid == "sdpmonthly" or $pageid == "whsemonthly"){    
        //if ($month == 0) $month = 1;
        if ($year == 0)  $year = date('Y');
          
        if ($month>0){
         $qryUserFilter .=  $WhereStr. " report_month=" . $month;
         $WhereStr = " AND ";    
        }

      
        if ($year>0){
         $qryUserFilter .= $WhereStr. " report_year=" . $year;
          $WhereStr = " AND ";  
        }

        if ($wh>0){
         $qryUserFilter .= $WhereStr. " wh_id=" . $wh;
         $WhereStr = " AND ";  
        }
      
      }
      
       elseif ($pageid == "wms"){
      
         if ($month>0){
         $qryUserFilter .=  $WhereStr. " DATE_FORMAT(TransactionDate,'%m') =" . $month;
         $WhereStr = " AND ";    
        }

        if ($year>0){
         $qryUserFilter .= $WhereStr. " DATE_FORMAT(TransactionDate,'%Y')=" . $year; 
        }    
      
      }
     
      
      
          
    }
   }
 //$dbg = "*". $qryAllowFilter. "*" . $pageid. "*" . $month . "*" . $year;
 //End WARNING   
     //add order by clause
    if  (trim($qryOrderBy) != ""  ) $qryOrderBy =  " ORDER BY " . $qryOrderBy;
     
    
    $col =  explode(",", $qryfields);  //convert fields list to array
    $colnum =  count($col);
    $qry = "select ".$qryfields." from " . $qryFrom . $qryJoin. $qryWhereClause. $qryUserFilter. $qryOrderBy;
    //echo $qry; exit;

    //$qry .= " where whrec_id = 'WH-001'";
    
    
    $result = safe_query($qry);  //generate select statement
    $num_result = mysql_num_rows ($result); // get record count 
    $quotes = '"';
    
    
    //begin creating definitions
    $data = "["; //begin parenthesis for a record
                                  
   for ($i = 0; $i<$num_result; $i++) //loop through number of records
   {      
        if ($i>0) $data .= ', ';  //value seperator     
 
        $data .= '[';
        $row = mysql_fetch_array($result);
         
        for ($j = 0; $j<$colnum; $j++) //fetch field values
        { 
          $val = $row[$j] ;                    
          if ($j>0) $data .= ', ';  //value seperator
          if ($j == 0) {
          
           //if ($qryAllowEdit == "1")                 
           $data .=  $quotes; 
           
           if ($qryAllowEdit) {
           $data .= '<IMG CLASS=\'Himg\' src=\'../../plmis_img/edit.gif\' onclick=window.parent.ShowDataEdit(\''.$row[$j].'\')> ';                      }
           
           if ($EditURL) {
           
           //$data .= '<a href="plmis_static/lhw/content-edit.php?cid=18&starting=0>Edit</a>';     
           }
            

           
           if ($qryAllowDelete)  {
           $data .=  '&nbsp;&nbsp;<IMG CLASS=\'Himg\' src=\'../../plmis_img/Delete.gif\' onclick=window.parent.frames.AddEditFrame.DeleteData(\''.$row[$j].'\')> ';
           }
           $data .=  $quotes;              
                        
          
          } else {
           //$data .= $quotes. '<a href=# >'.$val .'</a>' . $quotes;                  
           if ($EscapeHTML) { 
            $data .= $quotes. mysql_real_escape_string($val) . $quotes;        
           } else {
               $data .= $quotes. $val . $quotes; 
               
           }
           
           
           
          }          
           
          }   
        
        $data .= ']';   
   }     
    $data .= ']';
    
    $coldef = get_array_elems($arrProfile);
    
    $definition = "var DATE_FORMAT = 'dd.mm.yyyy', CURRENCY_FORMAT = ' $'; ";
    $definition .= "var EMPTY_ROW = ''; "; 
    $definition .= "var tableDef={ amountPerPage:10, useMultiSort:true, datatype:0, data: ". $data.", \ncolDef: " . $coldef;
    $definition .= ', keyCol : "id", rowStyle : {     markClass:  "mark",    darkClass:  "dark",    ' .
    ' lightClass: "light", hoverClass: "hover"}';
    $definition .= ', imgSortAsc:    {src: "../../plmis_img/img/sortasc.gif", width: 10, height: 10}';
    $definition .= ', imgSortDesc:    {src: "../../plmis_img/img/sortdesc.gif", width: 10, height: 10}';  
    $definition .= ',imgSortAscActive:    {src: "../../plmis_img/img/sortasca.gif", width: 10, height: 10}';
    $definition .= ',imgSortDescActive:    {src: "../../plmis_img/img/sortdesca.gif", width: 10, height: 10}';
    $definition .= ',imgMultiSortAscActive:    {src: "../../plmis_img/img/sortascma.gif", width: 10, height: 10}';
    $definition .= ',imgMultiSortDescActive:    {src: "../../plmis_img/img/sortdescma.gif", width: 10, height: 10}';
    $definition .= ',imgFirstPage: {src: "../../plmis_img/img/firstpage.gif", width: 16, height: 16}';
    $definition .= ',imgLastPage:    {src: "../../plmis_img/img/lastpage.gif", width: 16, height: 16}';
    $definition .= ',imgPrevPage:    {src: "../../plmis_img/img/prevpage.gif", width: 16, height: 16}';
    $definition .= ',imgNextPage:    {src: "../../plmis_img/img/nextpage.gif", width: 16, height: 16},';
   
   
  $definition .= 'tableStyle:{';
  $definition .= 'tableClass: "common",';
  $definition .= 'thClass : "common",'; 
  $definition .= 'border : 0,';
  $definition .= 'cellpadding: 2,';
  $definition .= 'cellspacing: 1';
  $definition .= '}';   
  $definition .= '};';
     
    return $definition;
} 

function get_array_elems($arrResult, $where="")

{
           $c = 0;
           $comma = "";
           $str = "[";
           $open_paren = "\n{";
           $close_paren = "}"; 
           $dq_indx = '0,1,2,3,4';
           $quote = '"';
           $pos = 0;
           //if(strpos($string, $find ) 

           while(list($key,$value)=each($arrResult)){
                 if (is_array($value)){
                     $str .= $comma."\n{\n";
                     $i = 0;
                     $quote = null;                                           
                   while(list($key, $arrvalue) = each($value)){ 
                     $quote = null;                       
                     if ($i>0) $str .= ', ' . "\n"; 
                     if (($i == 0) or ($i == 1) or ($i == 2) or ($i == 4) ) $quote = '"';                   
                    // if ((strpos("0,1,3", (String)$i) === 1) || (strpos("0,1,3", (String)$i) === true)) $quote = '"';           

                     $str .= $key.' : '. $quote . $arrvalue. $quote;
                     $i = $i + 1;                   
                   }               
                  $str .= "\n}";
                  $comma = ",\n";
                                         
                 }
           }       
          $str .= "]";                                                 
    return $str;       
  }

  
function get_fields_list($arrResult, $where="")

{          
           $c = 0;
           $comma = "";
           $str = ""; 
           while( list($key,$value) =each($arrResult)){
                 if ($c == 1) $comma = ", ";   
                 if (is_array($value)){
                    get_fields_list($value, $where."$key");
                  $str =  $str.$comma.$key;
                  $c = 1;
                 }
           }
  return $str;          
  }
 

##############################################################################
# breadcrumb.php                  Version 1.1                                #
# Copyright 2000 Jacob Stetser    jstetser@icongarden.com                    #
# Created Dec 30, 2000            Last Modified May 2, 2001                 #
##############################################################################
# COPYRIGHT NOTICE                                                           #
# Copyright [and -left] 2000 Jacob Stetser. All Rights Reserved except as    #
# provided below.                                                            #
#                                                                            #
# breadcrumb.php may be used and modified free of charge by anyone so long   #
# as this copyright notice and the comments above remain intact. By using    #
# this code you agree to indemnify Jacob Stetser from any liability that     #
# might arise from it's use.                                                 #
#                                                                            #
# This script is released under the BSD license.                             #
# The author recognizes this script's indebtedness to evolt.org, Martin      #
# Burns, Adrian Roselli and countless other ideas of its kind. This script   #
# is therefore unencumbered free code.                                       #
##############################################################################

function breadCrumb($PATH_INFO) {
    global $page_title, $root_url;

    // Remove these comments if you like, but only distribute 
    // commented versions.
    
    // Replace all instances of _ with a space
    $PATH_INFO = str_replace("_", " ", $PATH_INFO);
    // split up the path at each slash
    $pathArray = explode("/",$PATH_INFO);
    
    // Initialize variable and add link to home page
    if(!isset($root_url)) { $root_url=""; }
    $breadCrumbHTML = '<a href="'.$root_url.'/" title="Home Page">Home</a> &gt; ';
    
    // initialize newTrail
    $newTrail = $root_url."/";
    
    // starting for loop at 1 to remove root
    for($a=1;$a<count($pathArray)-1;$a++) {
        // capitalize the first letter of each word in the section name
        $crumbDisplayName = ucwords($pathArray[$a]);
        // rebuild the navigation path
        $newTrail .= $pathArray[$a].'/';
        // build the HTML for the breadcrumb trail
        $breadCrumbHTML .= '<a href="'.$newTrail.'">'.$crumbDisplayName.'</a> &gt; ';
    }
    // Add the current page
    if(!isset($page_title)) { $page_title = "Current Page"; }
    $breadCrumbHTML .= '<strong>'.$page_title.'</strong>';
    
    // print the generated HTML
    print($breadCrumbHTML);
    
    // return success (not necessary, but maybe the 
    // user wants to test its success?
    return true;
}



function breadCrumb2($arr,$launchURL,$helpid) {

$str = "&nbsp;";
$help_str = "";


if(!empty($helpid)) $help_str = '<img src="plmis_img/book02.gif" title="Detail" width="20" border="0" height="10"><a href="content-detail.php?title='.$helpid.'" rel="facebox">Readme</a>'; 

$str .= '<table border="0" width="967"><tr><td></td><td><A HREF="Cpanel.php" TITLE="Home" ONMOUSEOVER="ChangeStatus(\'Home\'); return true;" ';
$str .= 'ONMOUSEOUT="ChangeStatus(\'\'); return true;">Home</A>';
$str .= '&nbsp;&nbsp;<img src="plmis_img/arrow011.gif" alt="">&nbsp;&nbsp;<A >'.$arr['title'].'</A>&nbsp;&nbsp;<img src="plmis_img/arrow011.gif" alt=""> &nbsp;&nbsp; <A id="Action" HREF="'.$arr['url'].'"';
$str .= 'ONMOUSEOVER="ChangeStatus(\'Add/Edit User\'); return true;" ONMOUSEOUT="ChangeStatus(\'\'); return true;">'.$arr['subtitle'].'</A><br>';
$str .= '</td><td align="right">'.$help_str.'</td></tr></table>';   
return $str;    
}

//function getReportingRate($type,$id,$month,$year){
//         $query = "SELECT getFieldReportsRec('".$type."',".$id.",".$month.",".$year.") rec, ";
//         $query .= "getFieldReportsExp('".$type."',".$id.") exp ";
//         $query .= "FROM DUAL";
//        
//         $result = safe_query($query);
//         $row = mysql_fetch_array($result); 
         //echo $row['rec'] . " ". $row['exp'];
//         $reportingrate = round($row['rec'] / $row['exp'] * 100,1);
//        
//        
//        return $reportingrate;
//    }
    
 function getReportDescription($id){
               
         $query = "SELECT  tbl_cms.description ";
         $query .= " FROM reports ";
         $query .= " Inner Join tbl_cms ON reports.staticpage = tbl_cms.title ";
         $query .= " WHERE reports.staticpage = tbl_cms.title and  reports.report_id = '".$id."'";
          
                 //print $query;
         $result = safe_query($query);
         $row = mysql_fetch_array($result); 
         echo $row['description'];
		 
		 if ( $id == 'SNASUM' || $id=='SPROVINCEREPORT' || $id=='SDISTRICTREPORT'){
			 $query = "SELECT distinct mosscale_tab.longterm, mosscale_tab.colorcode FROM mosscale_tab";    
	                 
	         $result = safe_query($query);
			 echo "<br><span class='sb1NormalFont'>MOS: </span>";
	         while($row = mysql_fetch_array($result)){
			 	echo "<div style='display:inline-block;margin-left:5px;'>".$row['longterm'].'</div>';
				echo "<div style='display:inline-block;width:15px; height:12px; background-color:$row[colorcode];margin-left:5px;'></div> ";
			 }
			 echo '<br><br>';
		 }
		 
    }   

    
function getAnnoucement(){
               
         $query = "SELECT  tbl_cms.description ";
         $query .= " FROM tbl_cms ";
         $query .= " WHERE tbl_cms.title = 'Annoucement'";
               
         $result = safe_query($query);
         $row = mysql_fetch_array($result); 
         return $row['description'];
    }   
    
    
 function getReportDescriptionFooter($id){
               
         $query = "SELECT  tbl_cms.description ";
         $query .= " FROM reports ";
         $query .= " Inner Join tbl_cms ON reports.footer_staticpage = tbl_cms.title ";
         $query .= " WHERE reports.footer_staticpage = tbl_cms.title and  reports.report_ID = '".$id."'";
          
         $result = safe_query($query);
         $row = mysql_fetch_array($result); 
         return $row['description'];
    }   


 function getReportingRateStr($in_type,$in_month,$in_year,$in_item,$in_WF,$in_skt,$in_prov, $in_dist,$inStk_type){
               
         $query = "SELECT REPgetReportingRateStr('".$in_type."',".$in_month.",".$in_year.",'".$in_item."','".$in_WF."',".$in_skt.",".$in_prov.",".$in_dist.",".$inStk_type.") As Rate";
         $query .= " FROM DUAL;";
  		 //echo $query;
         $result = safe_query($query);
         $row = mysql_fetch_array($result); 
         return $row['Rate'];
    }   

    
  function getAvailabilityRateStr($in_type,$in_month,$in_year,$in_item,$in_WF,$in_skt,$in_prov, $in_dist,$inStk_type){
               
         
		 $query = "SELECT REPgetAvailabilityRateStr('".$in_type."',".$in_month.",".$in_year.",'".$in_item."','".$in_WF."',".$in_skt.",".$in_prov.",".$in_dist.",".$inStk_type.") As Rate";
         $query .= " FROM DUAL;";
  		 //echo $query;  
         $result = safe_query($query);
         $row = mysql_fetch_array($result); 
         return $row['Rate'];
    }  


 function getHelpPage($in_name){
            
         $query = "SELECT UTILgetHelpPage('".$in_name."') As retval";
         $query .= " FROM DUAL;";
           //echo $query;
         $result = safe_query($query);
         $row = mysql_fetch_array($result); 
         return $row['retval'];
    }   
        
function readMeLinks($helpid){
	$str = "&nbsp;";
	$help_str = "";
	
	$golink = SITE_URL."content-detail.php?title=$helpid";
	if(!empty($helpid)) $help_str = "<img src=../../plmis_img/book02.gif title=Detail width=20 border=0 height=10><a title='Click here to see the detail about this page' href=$golink rel=facebox><strong>Readme</strong></a>"; 
	$str .= '</td><td align="right">'.$help_str.'</td></tr></table>';   
	return $str;	
}                                                                                                                    
?>
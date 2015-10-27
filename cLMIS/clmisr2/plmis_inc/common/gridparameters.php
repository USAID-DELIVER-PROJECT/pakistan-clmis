<!--  BEGIN: REPORT Header  -->
<style>
.input_select{
	border:#D1D1D1 1px solid;
	color:#474747;
	font-family:Arial, Helvetica, sans-serif;
	font-size:11px;
	height:18px;
}

.input_button{
	border:#D1D1D1 1px solid;
	background-color:#999;
	color:#000;
	height:24px;	
	font-family:Arial, Helvetica, sans-serif;
	font-size:11px;
	height:18px;
}
</style>
<SCRIPT LANGUAGE = "JAVASCRIPT" TYPE = "TEXT/JAVASCRIPT">
            <!--
    function formvalidate(){
      
        if(selAtleastOneInArray('month_sel')==false){
            //document.getElementById('month_sel').style.borderColor = '#FD8E3C';
            alert('Select Month');
            return false;
        } else {
            //document.getElementById('month_sel').style.borderColor = '#FFFFFF';
        }
            
        if(selAtleastOneInArray('year_sel')==false){
            //document.getElementById('year_sel').style.borderColor = '#FD8E3C';
            alert('Select Year');
            return false;
        } else {
            //document.getElementById('year_sel').style.borderColor = '#FFFFFF';
        }
                
        if(selAtleastOneInArray('prov_sel')==false){
            //document.getElementById('prov_sel').style.borderColor = '#FD8E3C';
            alert('Select Province');
            return false;
        } else {
            //document.getElementById('prov_sel').style.borderColor = '#FFFFFF';
        }       
        
        if(selAtleastOneInArray('wh_sel')==false){
            //document.getElementById('wh_sel').style.borderColor = '#FD8E3C';
            alert('Select Warehouse');            
            return false;    
        } else {
            //document.getElementById('wh_sel').style.borderColor = '#FFFFFF';
        }        
         
    }
            
    function selAtleastOneInArray(varHTMLArray){
        var HTMLlength = document.getElementById(varHTMLArray).length;
        var optionSelected = false;
        //alert(document.getElementById(varHTMLArray).name + "*" + document.getElementById(varHTMLArray)./// length + "*" + document.getElementById(varHTMLArray).value);
        
        if ((document.getElementById(varHTMLArray).value.length==0) 
        ||  (document.getElementById(varHTMLArray).value==null)) 
        {
           return false; 
        }
        
        for(var i=0; i<HTMLlength; i++){
            if(document.getElementById(varHTMLArray)[i].selected==true )
                optionSelected = true;
        }    
        return optionSelected;
    }
           
    function getSelWarehouse(){
        
        if(document.getElementById('prov_sel').value=='all'){
            getAllWarehouse();    
        } else if(document.getElementById('prov_sel').value=='oth'){
         getOtherWarehouse();
        }
        else {
            removeWarehouse();
            if (window.XMLHttpRequest)
            {// code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp=new XMLHttpRequest();
            }
            else
            {// code for IE6, IE5
                xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
            }
            var querystring = "";
            querystring = document.getElementById('prov_sel').value
              
            var URL = "plmis_inc/common/loadWarehouse.php?act=prov&prov="+querystring;
            xmlhttp.open("GET",URL,true);
            xmlhttp.send();
            xmlhttp.onreadystatechange=function()
               {
                  if (xmlhttp.readyState==4 && xmlhttp.status==200)
                  {
                     var xmlDoc  = xmlhttp.responseXML;
                     var newOpt = new Option("Select","",true);
                     document.getElementById("wh_sel").options[0] = newOpt;
 
                    for( i=0; i<xmlDoc.getElementsByTagName("sel").length;i++)
                    {
                         value = xmlDoc.getElementsByTagName("optvalue")[i].firstChild.data;
                         data  = xmlDoc.getElementsByTagName("optlabel")[i].firstChild.data;
                         var newOpt = new Option(data,value,false);
                         document.getElementById("wh_sel").options[i+1] = newOpt;
                    }
                   }
               }
        }
    }
    
    function getAllWarehouse(){
        
        removeWarehouse();
        if (window.XMLHttpRequest)
        {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp=new XMLHttpRequest();
        }
        else
        {// code for IE6, IE5
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }      
        var URL = "plmis_inc/common/loadWarehouse.php?act=all";
        xmlhttp.open("GET",URL,true);
        xmlhttp.send();
        xmlhttp.onreadystatechange=function()
           {
              if (xmlhttp.readyState==4 && xmlhttp.status==200)
              {
               var xmlDoc  = xmlhttp.responseXML;
               var newOpt = new Option("Select","",true);
               document.getElementById("wh_sel").options[0] = newOpt;
               for( i=0; i<xmlDoc.getElementsByTagName("sel").length;i++)
                {
                     value = xmlDoc.getElementsByTagName("optvalue")[i].firstChild.data;
                     data  = xmlDoc.getElementsByTagName("optlabel")[i].firstChild.data;
                     var newOpt = new Option(data,value,false);
                     document.getElementById("wh_sel").options[i+1] = newOpt;
                }                
              }
           }
    }

    function getOtherWarehouse(){
        
        removeWarehouse();
        if (window.XMLHttpRequest)
        {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp=new XMLHttpRequest();
        }
        else
        {// code for IE6, IE5
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }      
        var URL = "plmis_inc/common/loadWarehouse.php?act=oth";
        xmlhttp.open("GET",URL,true);
        xmlhttp.send();
        xmlhttp.onreadystatechange=function()
           {
              if (xmlhttp.readyState==4 && xmlhttp.status==200)
              {
               var xmlDoc  = xmlhttp.responseXML;
               
               var newOpt = new Option("Select","",true);
               document.getElementById("wh_sel").options[0] = newOpt;
                              
               for( i=0; i<xmlDoc.getElementsByTagName("sel").length;i++)
                {
                     value = xmlDoc.getElementsByTagName("optvalue")[i].firstChild.data;
                     data  = xmlDoc.getElementsByTagName("optlabel")[i].firstChild.data;
                     var newOpt = new Option(data,value,false);
                     document.getElementById("wh_sel").options[i+1] = newOpt;
                }                
              }
           }
    }
    
    
     function removeWarehouse(){
        document.getElementById('wh_sel').length = 0;
    }
 //-->             
</SCRIPT>
 <?php

 //echo '<br>'. $gridparam;
 $test = $queryURL = explode('&',$gridparam);
 
 $selected_month = explode('=', $test[1]);
 $selected_month = $selected_month[1];

 $selected_year = explode('=', $test[2]);
 $selected_year = $selected_year[1];

 $selected_wh = explode('=', $test[3]);
 $selected_wh = $selected_wh[1];

 $selected_prov = explode('=', $test[4]);
 $selected_prov = $selected_prov[1];
   
 //echo '<br>'. $selected_month;
 //echo '<br>'. $selected_year;
 //echo '<br>'. $selected_wh;
 //echo '<br>'. $selected_prov; 
 
 
 $actionpage = "Cpanel.php".$extended_parmlist."&LogedUser=&LogedUserType=&LogedUserWH=".$gridparam;
 ?>
<table cellpadding="0" cellspacing="0" border="0" width="<?php echo $parameter_width;?>">
 <form name="searchfrm" id="searchfrm" action="<?php echo $actionpage;?>" method="post">
<tr bgcolor="#FFFFFF">
 <td class="sb1NormalFont" bgcolor="#FFFFFF"><strong></strong></td>

 <!-- TimePeriod -->
 <?php
 $pos = strrpos($parameters, "T");
 if ($pos !== FALSE   ) {
 // note: three equal signs
 // add time period elelment
   ?>
                        <td class="sb1NormalFont" bgcolor="#FFFFFF" style="padding:5px;">Month:</td>
                        <td bgcolor="#FFFFFF">
                            <select name="month_sel" id="month_sel" class="input_select">
                                <option value="">Select</option>
                                  <?php
                                  for ($i = 1; $i <= 12; $i++) {
                                    if ($selected_month == $i)
                                      $sel = "selected='selected'";
                                    else
                                      if ($i == 1)
                                        $sel = "selected='selected'";
                                      else
                                        $sel = "";
                                    ?>
                                <option value="<?php echo $i; ?>"<?php echo $sel; ?> ><?php echo date('F', mktime(0, 0, 0, $i, 1)); ?></option>
                                    <?php
                                  }
                                  ?>
                            </select>
                        </td>
                        <td class="sb1NormalFont" bgcolor="#FFFFFF" style="padding:5px;">Year:</td>
                        <td bgcolor="#FFFFFF">
                            <select name="year_sel" id="year_sel" class="input_select">
                                <option value="">Select</option>
                                  <?php
                                  for ($j = date('Y'); $j >= 2002; $j--) {
                                    if ($selected_year == $j)
                                      $sel = "selected='selected'";
                                    else
                                      if ($j == 2010)
                                        $sel = "selected='selected'";
                                      else
                                        $sel = "";
                                    ?>
                                <option value="<?php echo $j; ?>" <?php echo $sel; ?> ><?php echo $j; ?></option>
                                    <?php
                                  }
                                  ?>
                            </select>
                        </td>
   <?php
 }
 ?>
 

<!-- Province -->
<?php
$pos = strrpos($parameters, "P");
 if ($pos !== FALSE   ) { 
?>
              <td class="sb1NormalFont" bgcolor="#FFFFFF" style="padding:5px;">Province:</td>
                        <td bgcolor="#FFFFFF">
                        <select name="prov_sel" id="prov_sel" class="input_select" onChange="getSelWarehouse();">
                                <option value="">Select</option>
                                <option value="all" selected>All</option>
                                  <?php
                                  $queryprov = "SELECT prov_id,prov_title FROM province";
                                  $rsprov = mysql_query($queryprov) or die();
                                  
                                  while ($rowprov = mysql_fetch_array($rsprov)) {
                                    if ($selected_prov == $rowprov['prov_id'])
                                      $sel = "selected='selected'";
                                    else
                                      $sel = "";
                                    ?>
                                    <option value="<?php echo $rowprov['prov_id']; ?>" <?php echo $sel; ?>><?php echo $rowprov['prov_title']; ?></option>
                                    <?php
                                  }                                 
                                  ?>
                                   <option value="oth">Others</option>
                            </select>
                        </td>
  <?php
}
?>
 

<!-- Warehouse -->
<?php
$pos = strrpos($parameters, "W");
 if ($pos !== FALSE   ) { 
// note: three equal signs
// add product element


 $sysusrtype=$sysusr_type;
             //echo $_REQUEST['sysusr_type']; 
            //print_r($_SESSION);

/* Bugzillaj# XXX: All data viewable to authenticated users, only data entry is restricted to roles
            if($sysusrtype=="Central User"){
             

            
            } else {
                
                if($sysusrtype=="UT-001"){

                  $cws = $_REQUEST['cws1'];
                  $rsTemp1=safe_query("SELECT wh_id,rpad(wh_type_id,7,' ') As wh_type_id, dist_id,wh_name FROM tbl_warehouse WHERE wh_type_id = 'CWH' order by wh_name,wh_type_id");        
                
                } 
                else if($sysusrtype=="UT-003"){
                  $rsTemp1=safe_query("SELECT wh_id,rpad(wh_type_id,7,' ') As wh_type_id, wh_name FROM tbl_warehouse WHERE wh_type_id = 'PPIU' AND wh_id='".base64_decode($_SESSION['user']['LogedUserWH'])."' order by wh_name,wh_type_id");                            
                }
				else if($sysusrtype=="UT-002"){
				//echo "SELECT wh_id,rpad(wh_type_id,7,' ') As wh_type_id, wh_name FROM tbl_warehouse WHERE wh_id='".base64_decode($_SESSION[' user']['LogedUserWH'])."' order by wh_name,wh_type_id";
                  $rsTemp1=safe_query("SELECT wh_id,rpad(wh_type_id,7,' ') As wh_type_id, wh_name FROM tbl_warehouse WHERE wh_id='".base64_decode($_SESSION['user']['LogedUserWH'])."' order by wh_name,wh_type_id");                            
                }
                else {
            
                  $cws = $_REQUEST['cws1'];
                  $rsTemp1=safe_query("SELECT wh_id,dist_id, rpad(wh_type_id,7,' ') As wh_type_id, wh_name FROM tbl_warehouse  WHERE `wh_id` = '$cws'    order by wh_name,wh_type_id");    
    
                }
            }

*/
            $rsTemp1=safe_query("SELECT dist_id, wh_id, rpad(wh_type_id,7,' ') As wh_type_id, wh_name FROM
                                   tbl_warehouse order by wh_name,wh_type_id");    
            
            while($rsRow1=mysql_fetch_array($rsTemp1))
                {                        
                    $WHRecArray[]=$rsRow1['wh_id'];        
                    if(!empty($rsRow1['wh_name']))
                       $WHNameArray[]= $rsRow1['wh_name'].' ['.trim($rsRow1['wh_type_id']).']'; 
                    else{
                        $qWRName = "SELECT whrec_id,wh_name FROM tbl_districts WHERE whrec_id='".$rsRow1['dist_id']."' order by wh_name,wh_type_id";
                        $rWRName = mysql_query($qWRName) or die(mysql_error());
                        $rsWRName = mysql_fetch_array($rWRName);
                        $WHNameArray[]= $rsWRName['wh_name'].' ['.trim($rsRow1['wh_type_id']).']';

                    }     

                
                }
            mysql_free_result($rsTemp1);

  ?>
                   <td class="sb1NormalFont" bgcolor="#FFFFFF" style="padding:5px;">Warehouse:</td>
                   <td bgcolor="#FFFFFF">
                   <SELECT NAME="wh_sel" id="wh_sel" CLASS="sb1GeenGradientBoxMiddle" TABINDEX="1">
                        <OPTION VALUE="<?php echo $cws; ?>">--- Select ---</OPTION>
                        <? 
                            for($i=0;$i<sizeof($WHRecArray);$i++)
                                {                                
                                if($WHRecArray[$i]==$selected_wh)                                
                                {
                                $chk = "Selected = 'Selected'";    
                                }
                                else
                                {
                                $chk = "";    
                                }
                                    echo"<OPTION VALUE=\"$WHRecArray[$i]\" $chk>$WHNameArray[$i]</OPTION>";
                                }
                        ?>
                    </SELECT>                                        </td>

  <?php
}
?> 
 
 
 
<!-- Stakeholder -->
<?php
$pos = strrpos($parameters, "S");
 if ($pos !== FALSE   ) { 
  ?>
                       <td class="sb1NormalFont" bgcolor="#FFFFFF" style="padding-right:5px;">Stakeholder:</td>
                        <td bgcolor="#FFFFFF">
                            <select name="stk_sel" id="stk_sel" class="input_select" onChange="document.searchfrm.stkid.value = this.value;">
                                   <?php
                                  $querystk = "SELECT stkid,stkname FROM stakeholder";
                                  $rsstk = mysql_query($querystk) or die();
                                  while ($rowstk = mysql_fetch_array($rsstk)) {
                                    if ($sel_stk == $rowstk['stkid'])
                                      $sel = "selected='selected'";
                                    else
                                      $sel = "";
                                    ?>
                                    <option value="<?php echo $rowstk['stkid'];?>" <?php  echo $sel; ?>><?php echo $rowstk['stkname']; ?></option>
                                    <?php
                                  }
                                  ?>
                            </select>
                        </td>

  <?php
}
?>
 <!-- Product -->
 <?php
 $pos = strrpos($parameters, "I");
 if ($pos !== FALSE   ) { 
 // note: three equal signs
 // add province element
   ?>
                        <td width="9%" class="sb1NormalFont" bgcolor="#FFFFFF">Product:</td>
                        <td width="18%" bgcolor="#FFFFFF">
                            <select name="prod_sel" id="prod_sel" class="input_select">
	                            
                                <option value="">Select</option>
                                
                                  <?php
                                  $querypro = "SELECT itmrec_id,itm_id,itm_name FROM itminfo_tab WHERE itm_status='Current' ORDER BY frmindex";
                                  $rspro = mysql_query($querypro) or die();
                                  while ($rowpro = mysql_fetch_array($rspro)) {
                                    if ($rowpro['itmrec_id'] == $sel_item)
                                      $sel = "selected='selected'";
                                    else
                                      $sel = "";
                                    ?>
                                    <option value="<?php echo $rowpro['itmrec_id']; ?>" <?php echo $sel; ?>><?php echo $rowpro['itm_name']; ?></option>
                                    <?php
                                  }
                                  ?>
                            </select>
                        </td>
					  <?php } ?>
                        <td class="sb1NormalFont" bgcolor="#FFFFFF" style="padding:5px;"><input type="submit" name="go" id="go" value="GO" class="input_button"  onClick="return formvalidate();"></td>
                    </tr>
                   </form>
                </table>

<div style="height:4px;"></div>
              
<!--  END: REPORT Header -->                 

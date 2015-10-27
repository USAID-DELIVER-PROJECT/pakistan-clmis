<?php
session_start();
//print_r($_SESSION['user']);
$PLMIS_LOGO_TEXT = "Pakistan Logistics Management Information System";

$stk_logo = '<img src="plmis_img/islamabad_logo.jpg" width="465" height="100" />';
$stk_pict =  '<img src="plmis_img/islamabad_pic.jpg" width="317" height="100" />';    


//if(!empty($_SESSION['user']['LogedUser'])) {
       // echo $Stakeholder;
        if ($Stakeholder==1) {
// 'MOPW'
            $stk_logo =  '<img src="plmis_img/mopw-logo.png" style="padding-left:10px;" />';
            $stk_pict =  '<img src="plmis_img/banner1.png" width="278" height="100" />'; 
        } 
        if ($Stakeholder==2) {
//'LHW'
            $stk_logo =  '<img src="plmis_img/lhw-logo.png" style="padding-left:10px;" />';
            $stk_pict =  '<img src="plmis_img/banner2.png" width="278" height="100" />';            
        }        
        if ($Stakeholder==4) {
//'FPAP'
             $stk_logo = '<img src="plmis_img/fpap-logo.png" style="padding-left:10px;" />';
            $stk_pict =  '<img src="plmis_img/banner3.png" width="278" height="100" />';            
        }

        if ($Stakeholder==5) {
//'GS'
             $stk_logo = '<img src="plmis_img/gs-logo.png" style="padding-left:10px;" />';
            $stk_pict =  '<img src="plmis_img/banner1.png" width="278" height="100" />'; 
            
        }        
        if ($Stakeholder==6 ) {
//'MSI'
            $stk_logo = '<img src="plmis_img/msi-logo.png" style="padding-left:10px;" />';
            $stk_pict =  '<img src="plmis_img/banner2.png" width="278" height="100" />'; 
        }
            
        if ($Stakeholder==7) {
//'DOH Punjab'
            $stk_logo = '<img src="plmis_img/punjab-logo.png" style="padding-left:10px;" />';
            $stk_pict =  '<img src="plmis_img/banner3.png" width="278" height="100" />'; 
        }
            
        if ($Stakeholder==8) {
//'DOH Sindh'
            $stk_logo = '<img src="plmis_img/sindh-logo.png" style="padding-left:10px;" />';
            $stk_pict =  '<img src="plmis_img/banner1.png" width="278" height="100" />'; 
        }            
        if ($Stakeholder==10) {
//'DOH Khyber Pakhtoonkhwa'
            $stk_logo = '<img src="plmis_img/kpk-logo.png" style="padding-left:10px;" />';
            $stk_pict =  '<img src="plmis_img/banner2.png" width="278" height="100" />'; 
        }            
        if ($Stakeholder==9) {
//'DOH Balochistan'
            $stk_logo = '<img src="plmis_img/balochistan-logo.png" style="padding-left:10px;" />';
            $stk_pict =  '<img src="plmis_img/banner3.png" width="278" height="100" />'; 
        }                    
        if ($Stakeholder==11) {
//'DOH AJK')
            $stk_logo = '<img src="plmis_img/ajk-logo.png" style="padding-left:10px;" />';
            $stk_pict =  '<img src="plmis_img/banner1.png" width="278" height="100" />';           
        }        
                
        if ($Stakeholder==12) {
//'DOH FATA')
             $stk_logo = '<img src="plmis_img/fata-logo.png" style="padding-left:10px;" />';
            $stk_pict =  '<img src="plmis_img/banner2.png" width="278" height="100" />';            
        }                    
        if ($Stakeholder==13) {
//'DOH Gilgit Baltistan')
            $stk_logo = '<img src="plmis_img/gilgit-logo.png" style="padding-left:10px;" />';
            $stk_pict =  '<img src="plmis_img/banner3.png" width="278" height="100" />'; 
        }
                        
        if ($Stakeholder==14) {
//'DOH Islamabad')
             $stk_logo = '<img src="plmis_img/isb-logo.png" style="padding-left:10px;" />';
            $stk_pict =  '<img src="plmis_img/banner1.png" width="278" height="100" />'; 
            
        } 
?>
<link href="css/PAK-admin.css" rel="stylesheet" type="text/css" />
 <script type="text/javascript" id="sothink_dhtmlmenu">
 <!--
 st_siteroot="C:/apachefriends/xampp/htdocs/PAKLMIS/";
 st_jspath="plmis_js/stmenu.js";
 if(!window.location.href.indexOf("file:") && st_jspath.charAt(0)=="/")
  document.write('<script type="text/javascript" src="'+st_siteroot+st_jspath+'"><\/script>');
 else
  document.write('<script type="text/javascript" src="'+st_jspath+'"><\/script>');
//-->
</script>

 <div class="header">
 <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="80%" height="100" background="plmis_img/header.jpg">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td align="left">
        <?php echo $stk_logo; ?>
		<img src="images/Paklog.png" width="256" height="73" style="padding-left:30px;" />
        </td>
        <td align="right">
        <?php echo $stk_pict; ?>
        </td>
      </tr>
    </table></td>
    <td width="20%" class="top_right_text" style="padding-left:20px"><div class="" style="vertical-align:middle"><?php echo $welcome_msg;?></div></td>
  </tr>
  <tr>
    <td height="36" colspan="2" valign="bottom" background="plmis_img/menu_bg.jpg" style="padding-left:10px;">

<?php if(isset($_SESSION['user']['LogedUser'])) { 
	include_once("plmis_inc/common/CnnDb.php");
	include_once("plmis_inc/common/FunctionLib.php");
?>    


<?php /*?>
stm_bm(["menu41c9",900,"plmis_img","blank.gif",0,"","",0,0,250,0,1000,1,0,0,"","100%",0,0,1,2,"default","hand","",1,25],this);
stm_bp("p0",[0,4,0,0,1,4,0,7,100,"",-2,"",-2,50,2,2,"#999999","transparent","",3,0,0,"#000000"]);<?php */?>

<?
    $sysgroup_prv=str_replace("~","','",$sysgroup_prv);
    $sysgroup_subprv=str_replace("~","','",$sysgroup_subprv);

$rsTemp1=safe_query("select menu_id,menu_name from sysmenu_tab where menu_id in ('$sysgroup_prv') and active = 1 and staticmenu = 0 order by menu_order");
 $M=2;
 $S=1;
 $A=1;?>
 
 
 
 <div class="menu">
	<div class="wrraper">
    	<ul>
 
 <?php 
while ($rsRow1=mysql_fetch_array($rsTemp1))// Start Menu
    {                                
        $menu_id=$rsRow1['menu_id'];
   
?>
			<li><a href="#"><?php echo $rsRow1['menu_name'];?>&nbsp;<img src="images/menu-arrow.png" /> </a>
            	<ul class="navigation-2">
<?php /*?>stm_ai("p0i0",[0,"<?=$rsRow1[menu_name]?>","","",-1,-1,0,"","_self","","","","",0,0,0,"","",0,0,0,1,1,"#E6EFF9",1,"#FFD602",1,"","menu-hover.png",3,3,0,0,"#E6EFF9","#000000","#000000","#000000","8pt Verdana","8pt Verdana",0,0,"","","","",6,6,22],80,0);
stm_bp("p1",[1,4,0,-1,0,4,0,0,100,"",-2,"",-2,50,2,2,"#CCCCCC","#ffcc33","",3,1,1,"#ffcc33"]);<?php */?>

<?

$rsTemp2=safe_query("select DISTINCTROW if(if(submenu_name_group ='',null,submenu_name_group) is null,submenu_name, submenu_name_group) submenu_name, if(if(submenu_name_group = '',null,submenu_name_group) is null,menu_filepath, '') menu_filepath, submenu_name_group from sysmenusub_tab where menu_id='$menu_id' and submenu_id in ('$sysgroup_subprv') and active = 1 order by submenu_order") ;

    while ($rsRow2=mysql_fetch_array($rsTemp2))// Start Submenu
        {
     $helppage = getHelpPage($rsRow2['submenu_name']);
                                        
     $menu_filepath="?".$rsRow2['menu_filepath']."&LogedUser=$LogedUser"."&LogedUserType=$LogedUserType&LogedUserWH=$LogedUserWH&Helpid=".$helppage;
?>  
<?php if(empty($rsRow2[submenu_name_group])){ $target=$menu_filepath;} else { $target="#"; }

?>

					<li><a href="<?php echo $rsRow2['menu_filepath'];?><?php //echo $target;?>"><?php echo $rsRow2['submenu_name']; if($target=="#"){?><img src="images/drop-arrow.png" /><?php } ?></a>
<?php                    if($target=="#"){?>
                    	<ul class="navigation-3">
                    
                    
                    	

<?php /*?>stm_ai("p1i0",[0,"<?=$rsRow2[submenu_name]?>","","",-1,-1,0,"<?=$target?>","_self","","","","",0,0,0,"","",0,0,0,0,1,"#FF9900",1,"#FFDF88",0,"","",0,0,1,1,"#FFCC33","#BF931D","#643C00","#000000","8pt Verdana","8pt Verdana",0,0,"","","","",0,0,0],100,0);
stm_bp("p2",[1,2,6,0,0,2,0,0,100,"stEffect(\"slip\")",-2,"",-2,85,0,0,"#FF9900","#FFCC33","",3,1,1,"#FFDF88"]);<?php */?>

<?
$rsTemp3=safe_query("select submenu_name,menu_filepath, extra from sysmenusub_tab where menu_id='$menu_id' and submenu_id in ('$sysgroup_subprv') and submenu_name_group = '".$rsRow2[submenu_name]."' and active = 1 order by submenu_order") ;
    while ($rsRow3=mysql_fetch_array($rsTemp3))// Start Submenu
        {                                
$menu_filepath3="?".$rsRow3['menu_filepath']."&LogedUser=$LogedUser"."&LogedUserType=$LogedUserType&LogedUserWH=$LogedUserWH&Helpid=".$rsRow3['extra'];

?>  
							<li><a href="<?php echo $rsRow3['menu_filepath'];?>"><?php echo $rsRow3['submenu_name'];?></a></li>
                            					
<?php /*?>stm_aix("p1i1","p1i0",[0,"<?=$rsRow3[submenu_name]?>","","",-1,-1,0,"<?=$menu_filepath3?>","_self","","","","",0,0,0,"icon_01.gif","icon_01.gif",10,7],200,18);
<?php */?>
<?
        }// End SubSubmenu

?>
</ul>
<?php } /*?>stm_ep(); <?php */?>

</li>

<?
$S++;
        }// End Submenu
$M++;
$M++;
?>
</ul>
<?php /*?>stm_ep();<?php */?>
</li>
<?
$M++;    
}// End Menu
?>			
  		</ul>
    </div>
</div>
<?php /*?>stm_ep();
stm_em();
//--><?php */?>
<?php } ?>


    </td>
  </tr>
  <tr>
    <td height="5" colspan="2" background="plmis_img/bg_gradient.jpg">&nbsp;</td>
  </tr>
</table>
</div>




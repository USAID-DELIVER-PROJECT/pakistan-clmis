<?php
session_start();
//include_once ("plmis_inc/common/plmis_common_constants.php");
//include_once ("plmis_inc/common/plmis_common_globals.php");
$PLMIS_LOGO_TEXT = "Pakistan Logistics Management Information System";
?>

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


 <?php
 	/*if($StakeHolderName=='MOPW'){
		$bgcolor = '#66BEE8';//CYAN
		$imagename = 'plmis_banner_r2a.gif';		
	} elseif($StakeHolderName=='LHW') {	
		$bgcolor = '#C75B4F';//RED	
		$imagename = 'plmis_banner_r2a.gif';		
	} elseif($StakeHolderName=='DOH') {	
		$bgcolor = '#92C262';//GREEN
		$imagename = 'plmis_banner_r2a.gif';		
	} elseif($StakeHolderName=='PPIU') {			
		$bgcolor = '#CBDD57';//LIGHT GREEN	
		$imagename = 'plmis_banner_r2a.gif';		
	} else { 
		$bgcolor = '#90FF90';
		$imagename = 'plmis_banner_r2a.gif';
	}*/
	
 ?>
 <div class="header">
 <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="80%" height="100" background="plmis_img/bg.jpg"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="43%" align="left">
        <?php 
		if(!empty($_SESSION['user']['LogedUser'])) {
			
			/*if(base64_decode($_SESSION['user']['LogedUserType'])=='UT-003') {
				
				if(getProvinceName($cws)=='Punjab')
					echo '<img src="plmis_img/gov_punjab.jpg" width="465" height="100" />';
				if(getProvinceName($cws)=='Sindh')
					echo '<img src="plmis_img/sindh_logo.jpg" width="465" height="100" />';			
				if(getProvinceName($cws)=='Khyber Pakhtunkhwa')
					echo '<img src="plmis_img/KP_logo.jpg" width="465" height="100" />';			
				if(getProvinceName($cws)=='Balochistan')
					echo '<img src="plmis_img/balochistan_logo.jpg" width="465" height="100" />';			
				if(getProvinceName($cws)=='AJK')
					echo '<img src="plmis_img/AJK_logo.jpg" width="465" height="100" />';			
				if(getProvinceName($cws)=='FATA')
					echo '<img src="plmis_img/FATA_logo.jpg" width="465" height="100" />';			
				if(getProvinceName($cws)=='Gilgit Baltistan')
					echo '<img src="plmis_img/gilgit_logo.jpg" width="465" height="100" />';			
				if(getProvinceName($cws)=='Islamabad')
					echo '<img src="plmis_img/islamabad_logo.jpg" width="465" height="100" />';	
			
			} else {*/
				//echo $StakeHolderName;
				if($StakeHolderName=='MOPW')
					echo '<img src="plmis_img/MPOW_logo.jpg" width="465" height="100" />';
				
				if($StakeHolderName=='LHW')
					echo '<img src="plmis_img/LHW_logo.jpg" width="465" height="100" />';
				
				if($StakeHolderName=='FPAP')
					echo '<img src="plmis_img/fpap_logo.jpg" width="465" height="100" />';
	
				if($StakeHolderName=='GS')
					echo '<img src="plmis_img/greenstar_logo.jpg" width="465" height="100" />';
				
				if($StakeHolderName=='MSI')
					echo '<img src="plmis_img/MSI_logo.jpg" width="465" height="100" />';
					
				if($StakeHolderName=='DOH Punjab')
					echo '<img src="plmis_img/gov_punjab.jpg" width="465" height="100" />';
					
				if($StakeHolderName=='DOH Sindh')
					echo '<img src="plmis_img/sindh_logo.jpg" width="465" height="100" />';	
							
				if($StakeHolderName=='DOH Khyber Pakhtoonkhwa')
					echo '<img src="plmis_img/KP_logo.jpg" width="465" height="100" />';	
							
				if($StakeHolderName=='DOH Balochistan')
					echo '<img src="plmis_img/balochistan_logo.jpg" width="465" height="100" />';	
							
				if($StakeHolderName=='DOH AJK')
					echo '<img src="plmis_img/AJK_logo.jpg" width="465" height="100" />';		
						
				if($StakeHolderName=='DOH FATA')
					echo '<img src="plmis_img/FATA_logo.jpg" width="465" height="100" />';	
							
				if($StakeHolderName=='DOH Gilgit Baltistan')
					echo '<img src="plmis_img/gilgit_logo.jpg" width="465" height="100" />';
								
				if($StakeHolderName=='DOH Islamabad')
					echo '<img src="plmis_img/islamabad_logo.jpg" width="465" height="100" />';	
										
			//}
			
		} else {
			echo '<img src="plmis_img/islamabad_logo.jpg" width="465" height="100" />';	
		}
		?>
        </td>
        <td width="57%" align="right">
        <?php 
		if(!empty($_SESSION['user']['LogedUser'])) {
			
			/*if(base64_decode($_SESSION['user']['LogedUserType'])=='UT-003') {
				if(getProvinceName($cws)=='Punjab')
					echo '<img src="plmis_img/gov_punjab_pic.jpg" width="317" height="100" />';
				if(getProvinceName($cws)=='Sindh')
					echo '<img src="plmis_img/sindh_pic.jpg" width="317" height="100" />';			
				if(getProvinceName($cws)=='Khyber Pakhtunkhwa')
					echo '<img src="plmis_img/KP_pic.jpg" width="317" height="100" />';			
				if(getProvinceName($cws)=='Balochistan')
					echo '<img src="plmis_img/balochistan_pic.jpg" width="317" height="100" />';			
				if(getProvinceName($cws)=='AJK')
					echo '<img src="plmis_img/AJK_pic.jpg" width="317" height="100" />';			
				if(getProvinceName($cws)=='FATA')
					echo '<img src="plmis_img/FATA_pic.jpg" width="317" height="100" />';			
				if(getProvinceName($cws)=='Gilgit Baltistan')
					echo '<img src="plmis_img/gilgit_pic.jpg" width="317" height="100" />';			
				if(getProvinceName($cws)=='Islamabad')
					echo '<img src="plmis_img/islamabad_pic.jpg" width="317" height="100" />';	
			
			} else {*/
				
				if($StakeHolderName=='MOPW')
					echo '<img src="plmis_img/MPOW_pic.jpg" width="317" height="100" />';
				
				if($StakeHolderName=='LHW')
					echo '<img src="plmis_img/LHW_pic.jpg" width="317" height="100" />';
				
				if($StakeHolderName=='FPAP')
					echo '<img src="plmis_img/fpap_pic.jpg" width="317" height="100" />';
	
				if($StakeHolderName=='GS')
					echo '<img src="plmis_img/greenstar_pic.jpg" width="317" height="100" />';
				
				if($StakeHolderName=='MSI')
					echo '<img src="plmis_img/MSI_pic.jpg" width="317" height="100" />';
				
				if($StakeHolderName=='DOH Punjab')
					echo '<img src="plmis_img/gov_punjab_pic.jpg" width="317" height="100" />';
					
				if($StakeHolderName=='DOH Sindh')
					echo '<img src="plmis_img/sindh_pic.jpg" width="317" height="100" />';	
							
				if($StakeHolderName=='DOH Khyber Pakhtoonkhwa')
					echo '<img src="plmis_img/KP_pic.jpg" width="317" height="100" />';	
							
				if($StakeHolderName=='DOH Balochistan')
					echo '<img src="plmis_img/balochistan_pic.jpg" width="317" height="100" />';	
							
				if($StakeHolderName=='DOH AJK')
					echo '<img src="plmis_img/AJK_pic.jpg" width="317" height="100" />';		
						
				if($StakeHolderName=='DOH FATA')
					echo '<img src="plmis_img/FATA_pic.jpg" width="317" height="100" />';	
							
				if($StakeHolderName=='DOH Gilgit Baltistan')
					echo '<img src="plmis_img/gilgit_pic.jpg" width="317" height="100" />';
								
				if($StakeHolderName=='DOH Islamabad')
					echo '<img src="plmis_img/islamabad_pic.jpg" width="317" height="100" />';	
				
		//	}
		
		} else {
		    echo '<img src="plmis_img/islamabad_pic.jpg" width="317" height="100" />';				
		}
		?>
       
        </td>
      </tr>
    </table></td>
    <td width="20%"  class="top_right_text"><span class="top_right_text_black"><?php echo $welcome_msg;?></span></td>
  </tr>
  <tr>
    <td height="36" colspan="2" valign="bottom" background="plmis_img/menu_bg.jpg" style="padding-left:10px;">

<?php if(isset($_SESSION['user']['LogedUser'])) { 
	include_once("plmis_inc/common/CnnDb.php");
	include_once("plmis_inc/common/FunctionLib.php");
?>    
	    <script type="text/javascript">

//stm_bm(["menu61b2",900,"plmis_img","blank.gif",0,"","",0,0,250,0,1000,1,0,0,"","838",0,0,1,2,"default","hand","",1,25],this);
//stm_bp("p0",[0,4,0,0,0,0,0,0,100,"",-2,"",-2,50,0,0,"#799BD8","transparent","",3,0,0,"#000000"]);
stm_bm(["menu41c9",900,"plmis_img","blank.gif",0,"","",0,0,250,0,1000,1,0,0,"","100%",0,0,1,2,"default","hand","",1,25],this);
stm_bp("p0",[0,4,0,0,1,4,0,7,100,"",-2,"",-2,50,2,2,"#999999","transparent","",3,0,0,"#000000"]);

<?
    $sysgroup_prv=str_replace("~","','",$sysgroup_prv);
    $sysgroup_subprv=str_replace("~","','",$sysgroup_subprv);

$rsTemp1=safe_query("select menu_id,menu_name from sysmenu_tab where menu_id in ('$sysgroup_prv') and active = 1 and staticmenu = 0 order by menu_order");
 $M=2;
 $S=1;
 $A=1;
while ($rsRow1=mysql_fetch_array($rsTemp1))// Start Menu
    {                                
        $menu_id=$rsRow1['menu_id'];
        
  
                
?>

//stm_ai("p0i0",[0," <?=$rsRow1[menu_name]?> ","","",-1,-1,0,"#","_self","","","","",0,0,0,"","",0,0,0,1,1,"#FFFFF7",1,"#FFFFF7",1,"bg5.gif","bg2.gif",3,3,0,0,"#FFFFF7","#000000","#FFFFFF","#009933","bold 8pt Verdana","bold 8pt Verdana",0,0,"bg4.gif","bg1.gif","bg6.gif","bg3.gif",6,6,24],80,24);
//stm_ai("p0i0",[0,"<?=$rsRow1[menu_name]?>","","",-1,-1,0,"","_self","","","","",0,0,0,"","",0,0,0,1,1,"#E6EFF9",1,"#FFD602",1,"bg2.gif","bg5.gif",3,3,0,0,"#E6EFF9","#000000","#000000","#000000","8pt Verdana","8pt Verdana",0,0,"bg1.gif","bg4.gif","bg3.gif","bg6.gif",6,6,22],80,0);
//stm_bp("p1",[1,4,0,0,4,0,10,10,100,"",-2,"",-2,80,0,0,"#799BD8","#CCCCCC","",0,1,1,"#CCCCCC #B2B2B2 #B2B2B2"]);

stm_ai("p0i0",[0,"<?=$rsRow1[menu_name]?>","","",-1,-1,0,"","_self","","","","",0,0,0,"","",0,0,0,1,1,"#E6EFF9",1,"#FFD602",1,"bg2.gif","bg5.gif",3,3,0,0,"#E6EFF9","#000000","#000000","#000000","8pt Verdana","8pt Verdana",0,0,"bg1.gif","bg4.gif","bg3.gif","bg6.gif",6,6,22],80,0);
stm_bp("p1",[1,4,0,-1,0,4,0,0,100,"",-2,"",-2,50,2,2,"#CCCCCC","#ffcc33","",3,1,1,"#ffcc33"]);


<?
//$rsTemp2=safe_query("select submenu_name,menu_filepath from sysmenusub_tab where menu_id='$menu_id' and submenu_id in ('$sysgroup_subprv') and active = 1 order by submenu_order") ;

$rsTemp2=safe_query("select DISTINCTROW if(if(submenu_name_group ='',null,submenu_name_group) is null,submenu_name, submenu_name_group) submenu_name, if(if(submenu_name_group = '',null,submenu_name_group) is null,menu_filepath, '') menu_filepath, submenu_name_group from sysmenusub_tab where menu_id='$menu_id' and submenu_id in ('$sysgroup_subprv') and active = 1 order by submenu_order") ;

    while ($rsRow2=mysql_fetch_array($rsTemp2))// Start Submenu
        {
     $helppage = getHelpPage($rsRow2['submenu_name']);
                                        
     $menu_filepath="?".$rsRow2['menu_filepath']."&LogedUser=$LogedUser"."&LogedUserType=$LogedUserType&LogedUserWH=$LogedUserWH&Helpid=".$helppage;
?>  
<?php if(empty($rsRow2[submenu_name_group])){ $target=$menu_filepath;} else { $target="#"; }?>
//stm_ai("p1i0",[1,"<?=$rsRow2[submenu_name]?>","","",-1,-1,0,"<?= $target ?>","_self","","","","",0,0,0,"","",0,0,0,0,1,"#E6EFF9",0,"#FFD602",0,"","",3,3,1,1,"#E6EFF9","#000000","#000000","#000000","8pt Verdana","8pt Verdana",0,0]);
stm_ai("p1i0",[0,"<?=$rsRow2[submenu_name]?>","","",-1,-1,0,"<?=$target?>","_self","","","","",0,0,0,"","",0,0,0,0,1,"#FF9900",1,"#FFDF88",0,"","",0,0,1,1,"#FFCC33","#BF931D","#643C00","#000000","8pt Verdana","8pt Verdana",0,0,"","","","",0,0,0],100,0);



// start
//begin subsub menu
//stm_bp("p2",[1,4,0,0,4,0,10,10,100,"",-2,"",-2,80,0,0,"#799BD8","#CCCCCC","",0,1,1,"#CCCCCC #B2B2B2 #B2B2B2"]);
//stm_bp("p2",[1,2,6,0,0,2,0,0,100,"stEffect(\"slip\")",-2,"",-2,85,0,0,"#7F7F7F","#333333","",3,1,1,"#999999"]);

stm_bp("p2",[1,2,6,0,0,2,0,0,100,"stEffect(\"slip\")",-2,"",-2,85,0,0,"#FF9900","#FFCC33","",3,1,1,"#FFDF88"]);


<?
$rsTemp3=safe_query("select submenu_name,menu_filepath, extra from sysmenusub_tab where menu_id='$menu_id' and submenu_id in ('$sysgroup_subprv') and submenu_name_group = '".$rsRow2[submenu_name]."' and active = 1 order by submenu_order") ;
    while ($rsRow3=mysql_fetch_array($rsTemp3))// Start Submenu
        {                                
$menu_filepath3="?".$rsRow3['menu_filepath']."&LogedUser=$LogedUser"."&LogedUserType=$LogedUserType&LogedUserWH=$LogedUserWH&Helpid=".$rsRow3['extra'];
?>  
stm_aix("p1i1","p1i0",[0,"<?=$rsRow3[submenu_name]?>","","",-1,-1,0,"<?=$menu_filepath3?>","_self","","","","",0,0,0,"icon_01.gif","icon_01.gif",10,7],200,18);

<?
        }// End SubSubmenu

?>
stm_ep(); 

//end 

<?
$S++;
        }// End Submenu
$M++;
$M++;
?>
stm_ep();

<?
$M++;    
}// End Menu
?>

stm_ep();
stm_em();
//-->

</script>
<?php } ?>


    </td>
  </tr>
  <tr>
    <td height="5" colspan="2" background="plmis_img/bg_gradient.jpg">&nbsp;</td>
  </tr>
</table>
</div>




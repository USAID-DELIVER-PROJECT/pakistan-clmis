<!--  BEGIN: REPORT Header -->
<style>
.input_select {
	border: #D1D1D1 1px solid;
	color: #474747;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 11px;
	height: 24px;
	max-width: 150px;
}
.input_button {
	border: #D1D1D1 1px solid;
	background-color: #006700;
	color: #FFFFFF;
	height: 25px;
	font-family: Arial, Helvetica, sans-serif;
	vertical-align: bottom;
	width: 60px;/*	font-size:11px;*/
}
.tdpadding {
	padding-left: 10px !important;
}
</style>
<?php 
	if (!empty($sel_month)){
		$reportMonth = date('F',mktime(0,0,0,$sel_month));	
	}else {
		$reportMonth = "";	
	}
	
	if ( isset( $_REQUEST['districts'] ) ){
		$_SESSION['districts'] = $_POST['districts'];
	}
?>
<?php
include "../../plmis_inc/common/top_im.php";
?>
<script language="JavaScript" src="../../plmis_js/gen_validatorv31.js" type="text/javascript"></script>
<form name="searchfrm" id="searchfrm" action="<?php $actionpage ?>" method="post">
    <div class="row">
        <div class="col-md-12">
            <h3 class="page-title row-br-b-wp"> <?php echo $report_title; ?> <span class="green-clr-txt"><?php echo $reportMonth ; echo ' '.$sel_year; ?></span> </h3>
            <?php
// Do not include summary section for non reporting districts reports and availability reate reports
    if ($report_id!=='STOCKOUTRPT' && $report_id !== 'SNASUMSTOCKLOC' && $report_id !== 'SNONREPDIST' && $report_id !== "CENTRALWAREHOUSE" && $report_id !== "PROVINCIALWAREHOUSE")
    {
        include('ratesummary.php');
    }
    ?>
        </div>
    </div>
    
    
        	<div class="widget" data-toggle="collapse-widget">
                <div class="widget-head">
                    <h3 class="heading">Filter by</h3>
                </div>
                <div class="widget-body">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-top:-2px">
                        <tbody>
                            <tr>
                                <td></td>
                            </tr>
                            <tr bgcolor="#FFFFFF">
                                <td colspan="2" style="padding-right:20px; padding-top: 10px;font-family: Arial, Verdana, Helvetica, sans-serif; 	color: #444444; 	font-size: 12px;"><?php echo stripslashes(getReportDescription($report_id)); ?></td>
                            </tr>
                            <tr>
                                <td colspan="2" bgcolor="#FFFFFF" width="100%">
                                    <table cellpadding="0" cellspacing="0" border="0" width="<?php echo $parameter_width;?>" height="28">
                                        <tr> 
                                            
                                            <!-- TimePeriod -->
                                            <?php
                        //initialize flag for Select and All options in the drop down
                        $posT11 = False;
                        $posT10 = False;
                        $posT01 = False;
                        $paramTsel = False;
                        $paramTall = False;
                    
                        $posI11 = False;
                        $posI10 = False;
                        $posI01 = False;
                        $paramIsel = False;
                        $paramIall = False;
                    
                        $posS11 = False;
                        $posS10 = False;
                        $posS01 = False;
                        $paramSsel = False;
                        $paramSall = False;
                    
                        $posP11 = False;
                        $posP10 = False;
                        $posP01 = False;
                        $paramPsel = False;
                        $paramPall = False;
                    
                     $pos = strrpos($parameters, "T");
                     if ($pos !== FALSE   )
                     {
                    
                       $posT11 = strpos($parameters,"T11");
                       $posT10 = strpos($parameters,"T10");
                       $posT01 = strpos($parameters,"T01");
                    
                       if ($posT11 !== FALSE)
                       {
                           $paramTsel = 1;
                           $paramTall = 1;
                       }
                       if ($posT10 !== FALSE)
                       {
                           $paramTsel = 1;
                           $paramTall = 0;
                       }
                       if ($posT01 !== FALSE)
                       {
                           $paramTsel = 0;
                           $paramTall = 1;
                       }
                     //echo  $posT11.'*'.$posT10.'*'.$posT01.'*'.$paramTsel.'*'.$paramTall;
                     //echo  $paramTsel.'*'.$paramTall;
                    ?>
                                            <?php if ($report_id !== "CENTRALWAREHOUSE" && $report_id !== "PROVINCIALWAREHOUSE" && $report_id !== "QTRREPORT")
                                           {
                                           ?>
                                            <td bgcolor="#FFFFFF" valign="" class="tdpadding"><label class="control-label">Month</label>
                                                <select name="month_sel" id="month_sel" class="form-control input-small input-sm">
                                                    <?php if ($paramTsel == 1 ) { ?>
                                                    <option value="">Select</option>
                                                    <?php
                                                    }?>
                                                    <?php if ($paramTall == 1 ) { ?>
                                                    <option value="all">All</option>
                                                    <?php
                                                    }?>
                                                    <?php
                                                      for ($i = 1; $i <= 12; $i++) {
                                                        if ($sel_month == $i)
                                                          $sel = "selected='selected'";
                                                        else
                                                          if ($i == 1)
                                                            $sel = "selected='selected'";
                                                          else
                                                            $sel = "";
                                                        ?>
                                                    <option value="<?php echo $i; ?>"<?php echo $sel; ?> ><?php echo date('M', mktime(0, 0, 0, $i, 1)); ?></option>
                                                    <?php
                                                      }
                                                      ?>
                                                </select>
                                                <?php
                    
                                            } ?></td>
                                            <td bgcolor="#FFFFFF"  valign="" width="90px" class="tdpadding"><label class="control-label">Year</label>
                                                <select name="year_sel" id="year_sel" class="form-control input-small input-sm">
                                                    <?php if ($paramTsel == 1   ) { ?>
                                                    <option value="">Select</option>
                                                    <?php
                                                    }?>
                                                    <?php if ($paramTall == 1   ) { ?>
                                                    <option value="all">All</option>
                                                    <?php
                                                    }?>
                                                    <?php                                   
                                                      for ($j = date('Y'); $j >= 2010; $j--) {
                                                        if ($sel_year == $j)
                                                          $sel = "selected='selected'";
                                                        else
                                                          if ($j == date("Y"))
                                                            $sel = "selected='selected'";
                                                          else
                                                            $sel = "";
                                                        ?>
                                                    <option value="<?php echo $j; ?>" <?php echo $sel; ?> ><?php echo $j; ?></option>
                                                    <?php
                                                      }
                                                      ?>
                                                </select></td>
                                            <?php
                     }
                    
                    if ( $report_id=="STOCKOUTRPT" )
                    {?>
                                            <td bgcolor="#FFFFFF"  valign="" width="90px" class="tdpadding"><label class="control-label">Year</label>
                                                <select name="year_sel" id="year_sel" class="form-control input-small input-sm" style="width:60px">
                                                    <?php if ($paramTsel == 1   ) { ?>
                                                    <option value="">Select</option>
                                                    <?php
                                                    }?>
                                                    <?php if ($paramTall == 1   ) { ?>
                                                    <option value="all">All</option>
                                                    <?php
                                                    }?>
                                                    <?php                                   
                                                      for ($j = date('Y'); $j >= 2010; $j--) {
                                                        if ($sel_year == $j)
                                                          $sel = "selected='selected'";
                                                        else
                                                          if ($j == date("Y"))
                                                            $sel = "selected='selected'";
                                                          else
                                                            $sel = "";
                                                        ?>
                                                    <option value="<?php echo $j; ?>" <?php echo $sel; ?> ><?php echo $j; ?></option>
                                                    <?php
                                                      }
                                                      ?>
                                                </select></td>
                                            <?php
                    }
                    
                    if ( $report_id=="STOCKOUTRPT" )
                    {?>
                                            <td bgcolor="#FFFFFF"  valign="" width="90px" class="tdpadding"><label class="control-label">MOS</label>
                                                <input type="text" name="in_MOS" id="in_MOS" class="form-control input-small input-sm" style="width:60px" onblur="frmvalidate();"/></td>
                                            <?php
                    }
                    
                     if ( $report_id == "SNASUM" || $report_id=="STOCKOUTRPT" || $report_id == "SPROVINCEREPORT" || $report_id == "SDISTRICTREPORT" || $report_id == "PROVINCIALWAREHOUSE" || $report_id == "SNASUM" )
                     {
                     //|| $report_id == "SDISTRICTREPORT"
                        ?>
                                            <td bgcolor="#FFFFFF" width="100px" class="tdpadding"><label class="control-label">Sector</label>
                                                <?php 
                                                $selected="";
                                                if (isset($_POST['sector']))
                                                {
                                                    if ($_POST['sector'] == 'public')
                                                    $selected='public';
                                                    if ($_POST['sector'] == 'private')
                                                    $selected='private';
                                                }
                                                else
                                                {
                                                if ($lvl_stktype == '0')
                                                    $selected='public';
                                                    
                                                if ($lvl_stktype == '1')
                                                    $selected='private';
                                                }
                                                
                                                ?>
                                                <select name="sector" id="sector" class="form-control input-small input-sm">
                                                    <?php
                                                if ($report_id == "SPROVINCEREPORT" || $report_id == "SDISTRICTREPORT" || $report_id == "PROVINCIALWAREHOUSE" || $report_id == "SNASUM"){
                                                    ?>
                                                    <option value="all" <?php echo ($selected == 'all') ? 'selected=selected' : '';?>>All</option>
                                                    <?php
                                                }?>
                                                    <option value="public" <?php echo ($selected == 'public') ? 'selected=selected' : '';?>>Public</option>
                                                    <option value="private" <?php echo ($selected == 'private') ? 'selected=selected' : '';?>>Private</option>
                                                </select></td>
                                            <?php
                     }
                     ?>
                                            <?php if ($report_id == "QTRREPORT")
                      { ?>
                                            <td bgcolor="#FFFFFF" class="tdpadding"><label class="control-label">Qtr</label>
                                                <select name="qtr_sel" id="qtr_sel" class="form-control input-small input-sm" style="width:60px">
                                                    <option value="1" <?php echo ($_POST['qtr_sel'] == 1) ? 'selected="selected"' : '';?>>1</option>
                                                    <option value="2" <?php echo ($_POST['qtr_sel'] == 2) ? 'selected="selected"' : '';?>>2</option>
                                                    <option value="3" <?php echo ($_POST['qtr_sel'] == 3) ? 'selected="selected"' : '';?>>3</option>
                                                    <option value="4" <?php echo ($_POST['qtr_sel'] == 4) ? 'selected="selected"' : '';?>>4</option>
                                                </select></td>
                                            <?php }?>
                                            
                                            <!-- Stakeholder -->
                                            <?php
                    $stkFilter = '';
                    $pos = strrpos($parameters, "S");
                     if ($pos !== FALSE   ) { 
                       $posS11 = strpos ($parameters,"S11");
                       $posS10 = strpos ($parameters,"S10");
                       $posS01 = strpos ($parameters,"S01");
                       $posS00 = strpos ($parameters,"SPI");
                       
                       if ($posS11 !== FALSE)
                       {
                           $paramSsel = 1;
                           $paramSall = 1;
                       } 
                       if ($posS10 !== FALSE)
                       {
                           $paramSsel = 1;
                           $paramSall = 0;
                       } 
                       if ($posS01 !== FALSE)
                       {
                           $paramSsel = 0;
                           $paramSall = 1;
                       }  
                       if ($posS00 !== FALSE)
                       {
                           $paramSsel = 0;
                           $paramSall = 1;
                           $stkFilter = " AND stk_type_id = 1";
                       }  
                    
                      ?>
                                            <!-- <td class="sb1NormalFont" bgcolor="#FFFFFF">Stakeholder:</td>-->
                                            <td bgcolor="#FFFFFF" width="120px" class="tdpadding"><label class="control-label">Stakeholder</label>
                                                <?php 
                                            if ($report_id !== "CENTRALWAREHOUSE")
                                            {
                                                $sel_stk = isset($proStkID) ? $proStkID : $sel_stk;
                                            ?>
                                                <select name="stk_sel" id="stk_sel" width="150px" class="form-control input-small input-sm" onChange="document.searchfrm.stkid.value = this.value;">
                                                    <?php }
                                                    else {
                                                        echo '<select name="stk_sel" id="stk_sel" class="form-control input-small input-sm" onChange="func()">';
                                                    }
                                                if ($paramSsel == 1   ) { ?>
                                                    <option value="">Select</option>
                                                    <?php
                                                    }?>
                                                    <?php if ($paramSall == 1 || $stakeHolder == 1 ) { ?>
                                                    <option value="all">All</option>
                                                    <?php
                                                    }?>
                                                    <?php
                                                      $querystk = "SELECT stkid,stkname FROM stakeholder Where ParentID is null AND stakeholder.stk_type_id IN (0,1) $stkFilter order by stkorder";
                                                      $rsstk = mysql_query($querystk) or die();
                                                      while ($rowstk = mysql_fetch_array($rsstk)) {
                                                        if ($sel_stk == $rowstk['stkid'])
                                                          $sel = "selected='selected'";
                                                        else
                                                          $sel = "";
                                                         
                                                        if ($report_id == "CENTRALWAREHOUSE" && $rowstk['stkname'] == 'PWD')
                                                        {
                                                            $stkName = 'PPW/CWH';
                                                        }
                                                        else
                                                        {
                                                            $stkName = $rowstk['stkname'];
                                                        }
                                                        ?>
                                                    <option value="<?php echo $rowstk['stkid'];?>" <?php  echo $sel; ?>><?php echo $stkName; ?></option>
                                                    <?php
                                                      }
                                                      ?>
                                                </select></td>
                                            <?php
                    }
                    ?>
                                            
                                            <!-- Province -->
                                            <?php
                    $pos = strrpos($parameters, "P");
                     if ($pos !== FALSE) { 
                    // note: three equal signs
                    // add product element
                    
                       $posP11 = strpos ($parameters,"P11");
                       $posP10 = strpos ($parameters,"P10");
                       $posP01 = strpos ($parameters,"P01");
                       
                       if ($posP11 !== FALSE)
                       {
                           $paramPsel = 1;
                           $paramPall = 1;
                       } 
                       if ($posP10 !== FALSE)
                       {
                           $paramPsel = 1;
                           $paramPall = 0;
                       } 
                       if ($posP01 !== FALSE)
                       {
                           $paramPsel = 0;
                           $paramPall = 1;
                       }  
                    
                      ?>
                                            <!-- <td class="sb1NormalFont" bgcolor="#FFFFFF">Province/Region:</td>-->
                                            <td bgcolor="#FFFFFF" width="165px" class="tdpadding"><label class="control-label">Province/Region</label>
                                                <select name="prov_sel" id="prov_sel" class="form-control input-small input-sm">
                                                    <?php
                                                //$report_id === "SDISTRICTREPORT" || onchange="showstkHolders()"
                        //						 if ( $report_id === "SFIELDREPORT"){  }
                                                  ?>
                                                    <?php if ($paramPsel == 1   ) { ?>
                                                    <option value="">Select</option>
                                                    <?php
                                                    }?>
                                                    <?php //if ($paramPall == 1 || $province == 1 || $province == 0) { ?>
                                                    <option value="all">All</option>
                                                    <?php
                                                    //}?>
                                                    <?php
                                                      $queryprov = "SELECT tbl_locations.PkLocID as prov_id, tbl_locations.LocName as prov_title
                                                                    FROM tbl_locations where LocLvl=2 and parentid is not null";
                                                      $rsprov = mysql_query($queryprov) or die();
                                                      while ($rowprov = mysql_fetch_array($rsprov)) {
                                                    /*if($_POST['prov_sel']==$rowprov['prov_id'])
                                                    $sel = "selected='selected'";*/
                                                        if ($sel_prov == $rowprov['prov_id'])
                                                          $sel = "selected='selected'";
                                                        else
                                                          $sel = "";
                                                        ?>
                                                    <option value="<?php echo $rowprov['prov_id']; ?>" <?php echo $sel; ?>><?php echo $rowprov['prov_title']; ?></option>
                                                    <?php
                                                      }
                                                      ?>
                                                </select></td>
                                            <!--<td id="stkheading" style="display:none" class="sb1NormalFont" bgcolor="#FFFFFF">Stakeholder..:</td>--> 
                                            <!--<div class="sb1NormalFont">Stakeholder:</div><br>-->
                                            <td id="whID" width="120" style="display:none;" class="tdpadding"></td>
                                            <?php
                                                if ($report_id == 'SNONREPDIST')
                                                {
                                                ?>
                                            <td id="districtsCol" width="130"><label class="control-label">District</label>
                                                <select name="dist_id" id="dist_id" class="form-control input-small input-sm">
                                                    <option value=''>Select Province First</option>
                                                </select></td>
                                            <?php 
                                                }
                                                if ( $report_id == 'SNONREPDIST' || $report_id == 'QTRREPORT' )
                                                {
                                                ?>
                                                <td class="sb1NormalFont" width="80px" class="tdpadding">
                                                <label class="control-label">Type</label>
                                                <select name="lvl_type" id="lvl_type" class="form-control input-small input-sm">
                                                    <option value='all'>All</option>
                                                    <?php
                                                                if ( $report_id == 'SNONREPDIST')
                                                                {
                                                                    $getDistrLvl = mysql_query("SELECT tbl_dist_levels.lvl_id, tbl_dist_levels.lvl_name 
                                                                                        FROM 
                                                                                            tbl_dist_levels 
                                                                                        WHERE
                                                                                            tbl_dist_levels.lvl_id > 2");
                                                                }
                                                                if ( $report_id == 'QTRREPORT' )
                                                                {
                                                                    $getDistrLvl = mysql_query("SELECT tbl_dist_levels.lvl_id, tbl_dist_levels.lvl_name 
                                                                                        FROM 
                                                                                            tbl_dist_levels 
                                                                                        WHERE
                                                                                            tbl_dist_levels.lvl_id IN(2,3)");
                                                                }
                                                                while($distrLvlRow = mysql_fetch_array($getDistrLvl))
                                                                {
                                                                if ($_REQUEST['lvl_type'] == $distrLvlRow['lvl_id'])
                                                                  $sel = "selected='selected'";
                                                                else
                                                                  $sel = "";
                                                                ?>
                                                    <option value="<?php echo $distrLvlRow['lvl_id']; ?>" <?php echo $sel;?>><?php echo $distrLvlRow['lvl_name']; ?></option>
                                                    <?php
                                                                }
                                                            ?>
                                                </select>
                                            </td>
                                        
                                            <td class="sb1NormalFont" class="tdpadding" style="padding-top: 22px; padding-left: 10px;">
                                        
                                        <label class="control-label">Report Type</label>
                                        <select name="rptType" id="rptType" class="form-control input-small input-sm">
                                            <option value="non-reported" <?php echo ($_POST['rptType'] == 'non-reported') ? 'selected' : '';?>>Non-reported</option>
                                            <option value="reported" <?php echo ($_POST['rptType'] == 'reported') ? 'selected' : '';?>>Reported - All</option>
                                            <option value="intime" <?php echo ($_POST['rptType'] == 'intime') ? 'selected' : '';?>>Reported - In-time</option>
                                            <option value="late" <?php echo ($_POST['rptType'] == 'late') ? 'selected' : '';?>>Reported - Late</option>
                                        </select>
                                        &nbsp;
                                            </td>
                                        
                                            <td class="sb1NormalFont" id="districts_td_id" class="tdpadding"></td>
                                        
                                        <?php
                                                }
                                                ?>
                                        <?php
                    }
                    ?>
                                        
                                        <!-- Product -->
                                        <?php
                     $pos = strrpos($parameters, "I");
                     if ($pos !== FALSE   ) { 
                     // note: three equal signs
                     // add province element
                    
                       $posI11 = strpos ($parameters,"I11");
                       $posI10 = strpos ($parameters,"I10");
                       $posI01 = strpos ($parameters,"I01");
                       
                       if ($posI11 !== FALSE)
                       {
                           $paramIsel = 1;
                           $paramIall = 1;
                       } 
                       if ($posI10 !== FALSE)
                       {
                           $paramIsel = 1;
                           $paramIall = 0;
                       } 
                       if ($posI01 !== FALSE)
                       {
                           $paramIsel = 0;
                           $paramIall = 1;
                       }  
                     
                       ?>
                                        <?php if ($report_id !== "CENTRALWAREHOUSE" && $report_id !== "PROVINCIALWAREHOUSE" && $report_id !== "QTRREPORT"){?>
                                        <!--                        <td width="9%" class="sb1NormalFont" bgcolor="#FFFFFF">Product:</td>-->
                                        
                                        
                                            <td width="135px" bgcolor="#FFFFFF" class="tdpadding"><label class="control-label">Product</label>
                                                <select name="prod_sel" id="prod_sel" class="form-control input-small input-sm">
                                                    <?php if ($paramIsel == 1   ) { ?>
                                                    <option value="">Select</option>
                                                    <?php
                                                    }?>
                                                    <?php if ($paramIall == 1   ) { ?>
                                                    <option value="all">All</option>
                                                    <?php
                                                    }?>
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
                                                </select></td>
                                            <?php }?>
                                            <!--<td id="ppiuList" style="display:none" class="sb1NormalFont" bgcolor="#FFFFFF">CWH/PPIU Name:</td>
                                                    <td id="ppiuList1" style="display:none" bgcolor="#FFFFFF">
                                                    <?php 
                                                        $ppiuQryRes = mysql_query("SELECT * FROM `tbl_warehouse` WHERE (wh_type_id = 'PPIU' OR wh_type_id = 'CWH') AND stkid = 2 ");
                                                    ?>
                                                        <select class="form-control input-sm" name="ppiuName" id="ppiuName">
                                                            <?php 
                                                            while($ppiuRow = mysql_fetch_array($ppiuQryRes)){
                                                                if ($ppiuRow['wh_id'] == $ppiuName)
                                                                    $ppiuSel = "selected='selected'";
                                                                else 	
                                                                    $ppiuSel = "";
                                                                ?>
                                                                <option value="<?php echo $ppiuRow['wh_id'];?>"<?php echo $ppiuSel;?>><?php if ($ppiuRow['wh_name'] == "LHW"){echo "CWH";}else {echo $ppiuRow['wh_name'];}?></option>
                                                            <?php }
                                                            ?>
                                                        </select>	
                                                    </td>-->
                                            
                                            <?php if ($report_id == "CENTRALWAREHOUSE"){?>
                                            <!--                                 <td class="sb1NormalFont" bgcolor="#FFFFFF">Indicator:</td>-->
                                            <td bgcolor="#FFFFFF" width="140px" class="tdpadding"><label class="control-label">Indicator</label>
                                                <select class="form-control input-small" name="repIndicators" id="repIndicators">
                                                    <option value="1" <?php if ($sel_indicator == 1){echo 'selected="selected"';}?>>Issued</option>
                                                    <option value="2" <?php if ($sel_indicator == 2){echo 'selected="selected"';}?>>Stock on Hand</option>
                                                    <option value="3" <?php if ($sel_indicator == 3){echo 'selected="selected"';}?>>Received</option>
                                                </select></td>
                                            <?php
                                          }
                                        if ($report_id == "PROVINCIALWAREHOUSE")
                                        {?>
                                            <!--                                 <td class="sb1NormalFont" bgcolor="#FFFFFF">Indicator:</td>-->
                                            <td bgcolor="#FFFFFF" width="140px" class="tdpadding"><label class="control-label">Indicator</label>
                                                <select class="form-control input-small input-sm" name="repIndicators" id="repIndicators">
                                                    <option value="1" <?php if ($sel_indicator == 1){echo 'selected="selected"';}?>>Consumption</option>
                                                    <option value="3" <?php if ($sel_indicator == 3){echo 'selected="selected"';}?>>CYP</option>
                                                    <option value="2" <?php if ($sel_indicator == 2){echo 'selected="selected"';}?>>Stock on Hand</option>
                                                    <option value="4" <?php if ($sel_indicator == 4){echo 'selected="selected"';}?>>Received(District)</option>
                                                    <option value="5" <?php if ($sel_indicator == 5){echo 'selected="selected"';}?>>Received(Field)</option>
                                                </select></td>
                                            <?php
                                            
                                        }
                                                if ($report_id == "CENTRALWAREHOUSE"){ 
                                                ?>
                                                <td class="sb1NormalFont" width="140px" class="tdpadding">
                                            <label class="control-label">Warehouse</label>
                                            <select class="form-control input-small input-sm" name="wh_type" id="wh_type">
                                                <option value="all" <?php if ($_REQUEST['wh_type'] == 'all'){echo 'selected="selected"';}?>>All</option>
                                                <option value="1" <?php if ($_REQUEST['wh_type'] == 1){echo 'selected="selected"';}?>>Central</option>
                                                <option value="2" <?php if ($_REQUEST['wh_type'] == 2){echo 'selected="selected"';}?>>Provincial</option>
                                            </select>
                                                </td>
                                            <?php
                                                }
                                        }?>
                                            <td bgcolor="#FFFFFF" style="margin-left:20px; padding-top: 22px;" valign="" class="tdpadding"><input type="submit" name="go" id="go" value="GO" class="btn btn-primary input-sm" /></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td height="8"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
                <?php
                
                //if ($backpage !=="") {
                if (!empty($backpage) && isset($backpage)){
                 // build back and forward page links
                   $posT = strpos ($backparameters,"T");
                   $posI = strpos ($backparameters,"I");
                   $posS = strpos ($backparameters,"S");
                   $posP = strpos ($backparameters,"P");
                 
                   // check time period
                   if ($posT >= 0)
                   {
                     $paramT = 'month_sel='.$sel_month.'&year_sel='.$sel_year;  
                   } 
                   
                   // check product
                   if ($posI >= 0)
                   {
                     $paramI = '&item_sel='.$sel_item;  
                   } 
                   
                  // check stakeholder
                   if ($posS >= 0)
                   {
                     $paramS = '&stkid='.$sel_stk;  
                   } 
                   
                  // check province
                   if ($posP >= 0)
                   {
                     $paramP = '&prov_sel='.$sel_prov;  
                   } 
                      
                   //$backurl = '<a href="'.$backpage.'?month_sel'.$sel_month.'&year_sel='.$sel_year.'">Back</a>';
                   $backurl = '<img src="../../plmis_img/arrow011.gif" alt=""><a href="'.$backpage.'?'.$paramT.$paramI.$paramS.$paramP.'">Previous</a>';
                   } 
                //forward page link
                
                if (!empty($forwardpage) && isset($forwardpage)) { 
                 // build forward and forward page links
                   $posT = strpos ($forwardparameters,"T");
                   $posI = strpos ($forwardparameters,"I");
                
                   $posS = strpos ($forwardparameters,"S");
                   $posP = strpos ($forwardparameters,"P");
                 
                   // check time period
                   if ($posT >= 0)
                   {
                     $paramT = 'month_sel='.$sel_month.'&year_sel='.$sel_year;  
                   } 
                   
                   // check product
                   if ($posI > 0)
                   {
                     $paramI = '&item_sel='.$sel_item;  
                   } 
                   
                  // check stakeholder
                   if ($posS > 0)
                   {
                     $paramS = '&stkid='.$sel_stk;  
                   } 
                   
                  // check province
                   if ($posP > 0)
                   {
                     $paramP = '&prov_sel='.$sel_prov;  
                   } 
                      
                   //$forwardurl = '<a href="'.$forwardpage.'?month_sel'.$sel_month.'&year_sel='.$sel_year.'">Next</a>';
                   $forwardurl = '<img src="../../plmis_img/arrow011.gif" alt=""><a href="'.$forwardpage.'?'.$paramT.$paramI.$paramS.$paramP.'">Next</a><img src="../../plmis_img/arrow011.gif" alt="">';
                   }
			?>
            <div class="col-md-2">
                <div class="control-group ">
                    <div class="controls"><?php echo $backurl;?></div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="control-group ">
                    <div class="controls">&nbsp;</div>
                </div>
            </div>
            <div class="col-md-1">
                <div class="control-group ">
                    <div class="controls"><?php echo $forwardurl;?></div>
                </div>
            </div>
            
        </div>
    </div>
</form>

<!--  END: REPORT Header --> 
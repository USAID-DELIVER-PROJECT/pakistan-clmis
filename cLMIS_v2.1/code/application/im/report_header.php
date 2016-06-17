<?php 
/**
 * report_header
 * @package im
 * 
 * @author     Muhammad Waqas Azeem 
 * @email <waqas@deliver-pk.org>
 * 
 * @version    2.2
 * 
 */

?>

<style>
*{font-family:"Open Sans",sans-serif;}
b{font-size:12px;}
h3{font-size:13px;}
#report_type{
font-size:12px;
font-family: arial;}
#content_print
{
	width:624px;
	margin-left:50px;
}
table#myTable{
	border:1px solid #E5E5E5;
	font-size:9pt;
	width:100%;
}
table, table#myTable tr td{
	border-collapse: collapse;
	border:1px solid #E5E5E5;
	font-size:12px;
}
table, table#myTable tr th{
	border:1px solid #E5E5E5;
	border-collapse: collapse;
	font-size:12px;
}
</style>
<?php
$getWHName="select wh_name,stkid from tbl_warehouse where wh_id='".$_SESSION['user_warehouse']."'";
$resWHName=mysql_query($getWHName) or die(mysql_error());
$whName=mysql_fetch_row($resWHName);

$getStkLogo="select report_logo,report_title3 from stakeholder where stkid='".$whName[1]."'";
$resStkLogo=mysql_query($getStkLogo) or die(mysql_error());
$logo=mysql_fetch_row($resStkLogo);
?>
<div style="line-height:1;">
    <div id="logoLeft" style="float:left; width:107px; text-align:right;">
    <img src="<?php echo PUBLIC_URL;?>images/gop.png" / >
    </div>
    <div id="report_type" style="float:left; width:440px; text-align:center;">
        <span style="line-height:20px"><?php echo $logo[1]?></span><br/>
        <span style="line-height:15px"><b>Store: </b><?php echo $whName[0];?></span>
        <hr style="margin:3px 10px;" />
        <p><b><?php echo $rptName;?> as on: <?php echo date('d/m/y');?></b>
        </p>
    </div>
</div>
<div style="clear:both"></div>
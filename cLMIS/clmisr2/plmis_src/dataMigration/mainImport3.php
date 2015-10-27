<?php
session_start();
include("../../html/adminhtml.inc.php");
Login();


?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
	
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo $system_title." - Import Data"; ?></title>
    <link href="<?php echo PLMIS_CSS;?>style.css" rel="STYLESHEET" type="TEXT/CSS">
	<link href="<?php echo PLMIS_CSS;?>main.css" rel="STYLESHEET" type="TEXT/CSS">
	<link href="<?php echo PLMIS_CSS;?>cpanel.css" rel="STYLESHEET" type="TEXT/CSS">
	<link href="<?php echo PLMIS_CSS;?>new_forms.css" rel="STYLESHEET" type="TEXT/CSS">
	<LINK ID="GridCSS" href="<?php echo PLMIS_CSS;?>Grid.css" TYPE="TEXT/CSS" REL="STYLESHEET">
    <link href="<?php echo PLMIS_CSS;?>nn_proj.css" rel="stylesheet" type="text/css">
 	
     <SCRIPT LANGUAGE="JAVASCRIPT" TYPE="TEXT/JAVASCRIPT" SRC="<?php echo PLMIS_JS;?>FunctionLib.js"></SCRIPT>
	 <SCRIPT LANGUAGE="JAVASCRIPT" TYPE="TEXT/JAVASCRIPT" SRC="<?php echo PLMIS_JS;?>ClockTime.js"></SCRIPT>
     <SCRIPT LANGUAGE="JAVASCRIPT" TYPE="TEXT/JAVASCRIPT" SRC="<?php echo PLMIS_JS;?>cms.js"></SCRIPT>
     <script src="<?php echo PLMIS_JS;?>jquery.js" type="text/javascript"></script>
     <script src="<?php echo PLMIS_JS;?>jquery.autoheight.js" type="text/javascript"></script>
     <link href="<?php echo PLMIS_JS;?>facebox/facebox.css" media="screen" rel="stylesheet" type="text/css"/>
     <script src="<?php echo PLMIS_JS;?>facebox/facebox.js" type="text/javascript"></script> 
     <script type="text/javascript">
                jQuery(document).ready(function($) {
                  $('a[rel*=facebox]').facebox({
                    loading_image : '<?php echo PLMIS_IMG;?>loading.gif',
                    close_image   : '<?php echo PLMIS_IMG;?>closelabel.gif'
                  }) 
                })
     </script>
	<script language="javascript" type="text/javascript">
    function importdata(){
        var filedata=document.getElementById('filedata').value;
        alert(filedata);
        //window.location='exportdata.php?filedata='+filedata;
        
    }
    
    function formValidate(){
        if (document.getElementById("filedata").value == ""){
            alert("Select CSV file to import.");
            return false;
        }
    }				
    </script>
    </head>
<BODY text="#000000" bgColor="#FFFFFF" style="margin-left:0px;margin-top:0px,margin-right:0px;margin-bottom:0px; overflow:visible;">
	<?php include "../../plmis_inc/common/top.php";?>
    <?php //include "../../plmis_inc/common/left.php";?>
	<div class="body_sec">
		<div class="wrraper">
			<div class="content" style="min-height:679px;"><br />            	
        
				<span style="padding-left:35px"><?php showBreadCrumb();?></span><br /><br />
        
				<div id="import_div" style="display:block; height:600px; border:0px #d1d1d1 solid; width:100%;">
                  <div><h1>This feature is temp unavailable</h1></div>
				</div>
			</div>
		</div>
	</div>
    <?php include "../../plmis_inc/common/right_inner.php";?>
	<?php include "../../plmis_inc/common/footer.php";?>
</body>
</html>
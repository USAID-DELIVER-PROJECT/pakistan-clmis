<?php
include_once('html/html.inc.php');
include("html/config.php");
include "plmis_inc/common/top_im.php";
startHtml('Contact Us');
siteMenu("Contact Us");
?>
<style>
	.green{ color:#000;}
	.nav-tabs > li > a:hover, .nav-pills > li > a, .nav-pills > li > a:hover{color:#000;}
</style>

<div class=" page-content-wrapper">
    <div class="page-content landing-content"><br />

        <div class="col-md-12 "> 
            <div class="portlet box green">
                <div class="portlet-title">
                    <div class="caption">Contact Us</div>
                </div>
                <div class="portlet-body" style="min-height:300px;">
                	 For any comments and suggestions please write to
					<a href="mailto:support@lmis.gov.pk">support@lmis.gov.pk</a>
                </div>
            </div>
        </div>
        <!-- BEGIN  PATNERS -->
        <div class="row">
            <div class="col-lg-12">
                <div class="tab-content stake-holder">stakeholders</div>
                <ul class="nav nav-tabs stake-holder-list">
                    <li> <img src="assets/frontend/layout/img/contraceptive-partners/govt-of-pak-logo.png" alt=""/></li>
                    <li> <img src="assets/frontend/layout/img/contraceptive-partners/us-aid-logo.png" alt=""/></li>
                </ul>                    
            </div>
        </div>
        <!-- END PATNERS -->
    </div>
</div>

<?php include "plmis_inc/common/footer.php";?>
<?php
rightContents();
endHtml();
?>
	<script language="JAVASCRIPT" type="TEXT/JAVASCRIPT">
<!--
	//Start Validation Function for Not Guest User Login
	function validate(form_id,email) {	
	if(document.frm.txtEmail.value=="")
			{
				alert("Please Enter Your Email ID")
				document.frm.txtEmail.focus();
				return false
			}
 
   var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
   var address = document.frm.txtEmail.value;
   if(reg.test(address) == false) {
 
      alert('Invalid Email Address');
      return false;
   }
}
	//End Validation Function for Not Guest User Login
//-->
</script>

</script>
<?php
/***********************************************************************************************************
Developed by Syed Aun Irtaza email: aun.irtaza@hotmail.com
This is the file which will be use to send forgeted password it uses php mailer class
/***********************************************************************************************************/
include("plmis_inc/common/CnnDb.php");

include("plmis_inc/classes/cCms.php");
include_once('plmis_inc/classes/class.phpmailer.php');

$db=new Database();
$db->connect();
$objDB=new Database();
$objDB->connect();

$objDB2=new Database();
$objDB2->connect();

$db1=new Database();
$db1->connect();

$db2=new Database();
$db2->connect();

$db3=new Database();
$db3->connect();

$objContents=new cCms();

$mail=new PHPMailer(true);
$objContents=new cCms();

$articles_array=array();

if ($_POST['btn_snd'])
    {
    $uemail=htmlspecialchars($_POST['txtEmail']);

    if (empty($_POST['txtEmail']))
        {
        $error.="&bull;&nbsp;&nbsp;Email Required.<br />";
        }
    else
        {
        if (!strpos(trim($_POST['txtEmail']), "@") || !strpos(trim($_POST['txtEmail']), "."))
            {
            $error.="&nbsp;&bull;&nbsp;Invalid email address entered.<br>";
            }
        }

    if (empty($error))
        {
        $sql="select * from sysuser_tab where sysusr_email='".$_POST['txtEmail']."'";
        if ($db->query($sql) and $db->get_num_rows() > 0)
            {
            for ($i=0; $i < $db->get_num_rows(); $i++)
                {
                $row=$db->fetch_one_assoc();
                //array_push($articles_array,$row);
                }

            $sysusr_pwd=base64_decode($row['sysusr_pwd']);
            $body      =
                "<TABLE ALIGN='CENTER' CELLSPACING='0' CELLPADDING='5' BORDER='1' BORDERCOLOR='BLACK' BGCOLOR='#E9E9E9' WIDTH='550'>
                        <TR>
                            <TD>
                                <TABLE ALIGN='CENTER' CELLSPACING='0' CELLPADDING='5' BORDER='0' BORDERCOLOR='BLACK' BGCOLOR='#E9E9E9' WIDTH='98%'>                                    
                                    <TR>
                                        <TD COLSPAN='4' BGCOLOR='#E9E9E9'>
                                        <font face='Arial, Myriad, Verdana,Tahoma, Comic Sans MS, Courier New' size='2'>
                                        <B>Dear $rsRow1[sysusr_name]</B>,
                                        <BR><BR>Your account's information are as follows:</font>
                                        </TD>
                                    </TR>
                                    <TR>
                                        <TD COLSPAN='4' BGCOLOR='#E9E9E9'><HR WIDTH='530'COLOR='#000000' HEIGHT='1'></TD>
                                    </TR>            
                                    <TR>                                                
                                        <TD ALIGN='LEFT' WIDTH='25%'><B>Name</B></TD>
                                        <TD ALIGN='LEFT' WIDTH='4%'><B>:</B></TD>
                                        <TD ALIGN='LEFT' COLSPAN='2'>$rsRow1[sysusr_name]</TD>
                                    </TR>
                                    <TR>                                                
                                        <TD ALIGN='LEFT'><B>Designation</B></TD>
                                        <TD ALIGN='LEFT'><B>:</B></TD>
                                        <TD ALIGN='LEFT' COLSPAN='2'>$rsRow1[sysusr_deg]</TD>
                                    </TR>";

            if ($row['vis_org'] == "Visitor")
                {
                $body=$body
                    . "<TR>                                                
                                                <TD ALIGN='LEFT'><B>Organization</B></TD>
                                                <TD ALIGN='LEFT'><B>:</B></TD>
                                                <TD ALIGN='LEFT' COLSPAN='2'>$rsRow1[vis_org]</TD>
                                            </TR>";
                }
            else
                {
                $body=$body
                    . "<TR>                                                
                                                <TD ALIGN='LEFT'><B>Department</B></TD>
                                                <TD ALIGN='LEFT'><B>:</B></TD>
                                                <TD ALIGN='LEFT' COLSPAN='2'>$rsRow1[sysusr_dept]</TD>
                                            </TR>";
                }

            $body   =
                $body . "<TR>
                                        <TD ALIGN='LEFT'><B>Phone</B></TD>
                                        <TD ALIGN='LEFT'><B>:</B></TD>
                                        <TD ALIGN='LEFT' COLSPAN='2'>$rsRow1[sysusr_ph]</TD>
                                    </TR>
                                    <TR>                                                
                                        <TD ALIGN='LEFT'><B>Fax</B></TD>
                                        <TD ALIGN='LEFT'><B>:</B></TD>
                                        <TD ALIGN='LEFT' COLSPAN='2'>$rsRow1[sysusr_cell]</TD>
                                    </TR>
                                    <TR>                                                
                                        <TD ALIGN='LEFT'><B>E-mail</B></TD>
                                        <TD ALIGN='LEFT'><B>:</B></TD>
                                        <TD ALIGN='LEFT' COLSPAN='2'>$rsRow1[sysusr_email]</TD>
                                    </TR>
                                    <TR>                                                
                                        <TD ALIGN='LEFT'><B>Login ID</B></TD>
                                        <TD ALIGN='LEFT'><B>:</B></TD>
                                        <TD ALIGN='LEFT' COLSPAN='2'>$rsRow1[usrlogin_id]</TD>
                                    </TR>
                                    <TR>                                                
                                        <TD ALIGN='LEFT'><B>Password</B></TD>
                                        <TD ALIGN='LEFT'><B>:</B></TD>
                                        <TD ALIGN='LEFT' COLSPAN='2'>$sysusr_pwd</TD>
                                    </TR>
                                    <TR>                                                
                                        <TD ALIGN='LEFT'><B>Account Status </B></TD>
                                        <TD ALIGN='LEFT'><B>:</B></TD>
                                        <TD ALIGN='LEFT' COLSPAN='2'>$rsRow1[sysusr_status]</TD>
                                    </TR>
                                    <TR>
                                        <TD COLSPAN='4' BGCOLOR='#E9E9E9'>
                                        <font face='Arial, Myriad, Verdana,Tahoma, Comic Sans MS, Courier New' size='2'><a href='https://paklmisdev.jsi.com' target='_new()'>Please click here to Login.</a></font>
                                        </TD>
                                    </TR>
                                    <TR>
                                        <TD COLSPAN='4' BGCOLOR='#E9E9E9'><HR WIDTH='530'COLOR='#000000' HEIGHT='1'></TD>
                                    </TR>
                                    <TR>
                                        <TD COLSPAN='3' BGCOLOR='#E9E9E9'>
                                        <font face='Arial, Myriad, Verdana,Tahoma, Comic Sans MS, Courier New' size='2'>Thank you for staying with us.</font>
                                        </TD>
                                    </TR>
                                    <TR>
                                        <TD COLSPAN='3' BGCOLOR='#E9E9E9' ALIGN='RIGHT'></TD>
                                        <TD WIDTH='29%' BGCOLOR='#E9E9E9' ALIGN='CENTER'>
                                        <font face='Arial, Myriad, Verdana,Tahoma, Comic Sans MS, Courier New' size='2'><B>Best Regards,<BR>Administrator<BR>www.paklmis.com</B></font>
                                        </TD>
                                    </TR>
                                </TABLE>
                            </TD>
                        </TR>
                    </TABLE>";

            $msgbody=$body;

            try
                {
                $mail->AddReplyTo('admin@pklmis.com', 'Admin');
                $mail->AddAddress('aun.irtaza@hotmail.com', 'Aun Irtaza');
                $mail->SetFrom($uemail, $uname);
                $mail->AddReplyTo($uemail, $uname);
                $mail->Subject='www.pklmis.com Contact Us query';
                $mail->AltBody=
                    'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
                $mail->MsgHTML($msgbody);
                //  $mail->AddAttachment('attachment.pdf');      // attachment
                //$mail->AddAttachment('images/phpmailer_mini.gif'); // attachment
                $mail->Send();
                header("Location: contactus.php?msg=1");
                }
            catch( phpmailerException $e )
                {
                $error.=$e->errorMessage(); //Pretty error messages from PHPMailer
                }
            catch( Exception $e )
                {
              // $error.=$e->getMessage(); //Boring error messages from anything else!
                }
            }
        else
            {
            //$error.="No record exists for this email address";?>
			<script type="text/javascript">
			alert("No record exists for this email address");
			</script>
			<?php
            }
        //$msgbody ="<div align=\"center\"><img src=\"images/Logo.png\"></div><br><br>";

        }
    }
?>

<?php
	
include "plmis_inc/common/Global.php";
include_once('html/html.inc.php');
include("html/config.php");
include "plmis_inc/common/top_im.php";
startHtml($articles_array[0]['title']);
siteMenu("Home");
?>

<div class="page-content landing-content">
    <!-- BEGIN BANNER -->
    <div class="contraceptive-banner">
        <div class="container">
            <p class="contraceptives-landing-orange">Management Information System</p>
            <h1><img src="assets/img/landing-images/contraceptives-images/contraceptives_log.png" height="71"></h1>
            <p class="vaccine-headerTxt">Manages the national immunization programmes by providing a well-designed logistics management information system for vaccines. Supports Data Collection ..</p>
        </div>

    </div>
    <div class="orange-bar"><span class="blue-bar pull-right"></span><span class="grey-bar pull-right"></span></div>
    <!-- END BANNER -->

<div style="float:left;  background-color:#FFFFFF; float:left; width:960px;; margin-left:140px; padding-right:20px; text-align:left; padding-left:0px;" >


<?php
rightContents();?>
</div>
    <div class="clearfix"></div>
    <div class="row login-area">
        <form name = "frm" method = "post" enctype = "multipart/form-data" onsubmit="javascript:return validate('form_id','email');">
            <INPUT TYPE = "HIDDEN" NAME = "ActionType" VALUE = "Forgot">
            <SCRIPT LANGUAGE = "JavaScript">
                <!--


                SetFocus('sysusr_email');
                //-->
            </SCRIPT>

            <div class="col-md-2">
                <div class="form-group">
                    <div class="login-img">
                        <img src="assets/img/landing-images/contraceptives-images/contraceptives_login.png" height="48" width="172" alt="Contraceptive Login" class="img-responsive">
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">

                    <label for="txtEmail" class="control-label">You Account E-Mail <span class="require">*</span></label>
                    <div class="input-group col-md-12">
                        <div align="center" id="errMsg" style="color:#060"> <?php if (!empty($error)) { ?>
                            <?php echo $error; ?>
                            <?php } ?>
                            <?php if (isset($_GET['msg'])){ echo $_GET['msg']; } ?></div>
                        <input name="txtEmail"  type = "TEXT" class = "login" tabindex = "4" value = "<?php echo $txtEmail;?>" size = "40"   maxlength = "50" class="form-control input-sm- input-medium">
                    </div>
                </div>
            </div>

            <div class="col-md-5" style="margin-left: 20px; margin-top: 2px">
                <div class="form-group">
                    <input type = "SUBMIT" name = "btn_snd" value = " Send Request " class = "btn btn-default green btn-form-orange " >
                </div>
            </div>
        </form>
    </div>

    <div class="row feature-area">
        <div class="col-md-12">
            <h3 class="feature-heading"> Achieving Logistics Data Visibility and Availability of Health Products at all levels through <span> LMIS!</span></h3>
            <ul class="nav nav-tabs nav-justified list-feature">
                <li><img src="assets/img/landing-images/contraceptives-images/health_icon.png" height="63" width="76">
                    <p>Are you interested in improving supply management of health commodities?</p></li>
                <li><img src="assets/img/landing-images/contraceptives-images/evidence_icon.png" height="72" width="76">
                    <p>Are you making informed, evidence-based decisions?</p></li>
                <li><img src="assets/img/landing-images/contraceptives-images/committment_icon.png" height="70" width="82">
                    <p>Are you committed to improving collaboration among resource providers</p>
                </li>
                <li><img src="assets/img/landing-images/contraceptives-images/role_icon.png" height="70" width="86">
                    <p>Are you playing a role in forecasting, quantification and supply planning?</p></li>
                <li><img src="assets/img/landing-images/contraceptives-images/emergency_icon.png" height="70" width="93">
                    <p>Are you preventing supply emergencies?</p></li>
            </ul>
        </div>
    </div>
    <!-- END FEATURES -->
    <!-- BEGIN  PAGE CONTENT -->
    <div class="row content-area">
        <div class="col-md-12">
            <h3>Strengthening Supply Chain Logistics Management</h3>
            <p>Strengthening the Logistics Management Information Systems (LMIS) is an important objective in addressing the challenges of health commodities distribution in Pakistan. A systematic architected and rational approach was applied to define the country&rsquo;s needs for supply chain and logistics information management and most effective technology solutions were identified to address those needs. The LMIS is designed and implemented to be a sustainable source for health commodity supply chain monitoring and for informed decision making.</p>
            <h4 class="heading-orange">Logistics Management Information System for Vaccines (cLIMS)</h4>

            <ul class="nav nav-tabs nav-justified content-features">
                <li><span class="heading-orange">01</span>
                    <p>The LMIS is a web-based health commodity information system catering the logistics management of health products for both population and health sectors.</p>

                    <img src="assets/img/landing-images/contraceptives-images/tick_icon.png" height="40" width="43">

                </li>
                <li><span class="heading-orange">02</span>
                    <p>The LMIS helps the decision makers in planning, quantification, procurement and distribution of health commodities to the end user level.</p>

                    <img src="assets/img/landing-images/contraceptives-images/tick_icon.png" height="40" width="43"></li>

                <li><span class="heading-orange">03</span>
                    <p>The LMIS helps to ensure commodity security through historical and up-to-date data from public and private stakeholders across the country.</p>

                    <img src="assets/img/landing-images/contraceptives-images/tick_icon.png" height="40" width="43"></li>

                <li><span class="heading-orange">04</span>
                    <p>The LMIS provides continuous monitoring and availability of health products at all levels from national, provincial, districts and health facilities in both public and private sector thus preventing stock-outs and expiries to prevent No Commodity No Program situation.</p>

                    <img src="assets/img/landing-images/contraceptives-images/tick_icon.png" height="40" width="43"></li>

            </ul>

        </div>
    </div>
    <!-- END PAGE CONTENT -->

    <!-- BEGIN  PATNERS -->
    <div class="row">
        <div class="col-lg-12">
            <div class="tab-content stake-holder">stakeholders</div>
            <ul class="nav nav-tabs stake-holder-list">
                <li> <img src="assets/frontend/layout/img/contraceptive-partners/govt-of-pak-logo.png" alt=""/></li>
                <li> <img src="assets/frontend/layout/img/contraceptive-partners/unicef-logo.png" alt=""/></li>
                <li> <img src="assets/frontend/layout/img/contraceptive-partners/us-aid-logo.png" alt=""/></li>
                <li> <img src="assets/frontend/layout/img/contraceptive-partners/who-logo.png" alt=""/></li>
            </ul>
        </div>

    </div>
    <!-- END PATNERS -->

    <?php
footer();
endHtml();


?>

<?php
include("html/adminhtml.inc.php");
include($_SESSION['menu']);
?>


<?php include "plmis_inc/common/_header.php"; ?>
<?php include "plmis_inc/common/top_im.php"; ?>
<style>
h4{ color:#666666 !important; margin:20px 0 10px 0 !important;}
p{line-height:1.5 !important;}
</style>
<script>
    $(function() {
        $( "#tabs" ).tabs();
        // Hover states on the static widgets
        $( "#dialog-link, #icons li" ).hover(
                function() {
                    $( this ).addClass( "ui-state-hover" );
                },
                function() {
                    $( this ).removeClass( "ui-state-hover" );
                }
        );
    });
	
    /* DataTables */
    if ($('.receiveSearch').size() > 0)
    {
        var datatable = $('.receiveSearch').dataTable({
            "sPaginationType": "bootstrap",
            //"sDom": 'W<"clear">lfrtip',
            "sDom": 'T<"clear">lfrtip',
            // "sDom": '<"clear">lfrtipT',
            "oLanguage": {
                "sLengthMenu": "_MENU_ records per page"
            },
            "oColumnFilterWidgets": {
                "aiExclude": [0, 5, 6, 7, 8, 9]
            },
            "oTableTools": {
                "aButtons": [
                    {
                        "sExtends": "copy",
                        "sButtonText": "Copy",
                        "mColumns": [0, 1, 2, 3, 4, 5, 6, 7, 8]
                    }

                ],
                "sSwfPath": appName + "/common/theme/scripts/plugins/tables/DataTables/extras/TableTools/media/swf/copy_csv_xls_pdf.swf"
            }

        });

    }
</script>
</head>

<body class="page-header-fixed page-quick-sidebar-over-content" onLoad="doInitGrid()">
<!-- BEGIN HEADER -->


<div class="page-container">
<?php include "plmis_inc/common/_top.php";
include "plmis_inc/common/top_im.php";
?>
<div class="page-content-wrapper">
<div class="page-content">

<!-- BEGIN PAGE HEADER-->
<div class="row">

<div class="row">
<div class="col-md-12">

	<div class="box-generic">
	
		<!-- Tabs Heading -->
		<div class="tabsbar">
			<ul>
				<li class="glyphicons camera active"><a href="#tab1-3" data-toggle="tab"> <b>Contraceptives</b></a></li>
				<li class="glyphicons folder_open"><a href="#tab2-3" data-toggle="tab"> <b>Courses</b></a></li>
				<li class="glyphicons circle_plus tab-stacked"><a href="#tab3-3" data-toggle="tab"> <b>Logistics</b></a></li>
			</ul>
		</div>
		<!-- // Tabs Heading END -->
		
		<div class="tab-content">
				
			<!-- Tab content -->
			<div class="tab-pane active" id="tab1-3">
				<p>The USAID | DELIVER PROJECT Pakistan has been tasked by the Government of Pakistan with implementing a functioning LogisticsManagement Information System (LMIS) using a web based approach. During enhancement process LMIS was contextualized to local stakeholder structure and devolution. The LMIS was launched by Prime Minister of Pakistan on July 2011 as first Logistics Management Information System of Pakistan.</p>
                <p>LMIS has the flexibility to integrate other health commodities in addition to contraceptives. In addition to public sector, application is also able to record contraceptives national sales data of private sector. Currently, the system is able to respond to district level reporting by aggregating facilities level data through paper based reports. The future vision is to enhance the application for facility level reporting on logistics indicators for each district along with district store commodities status. To strengthen reporting and visibility of private sector contraceptives, district level interface will also be incorporated in LMIS applications which will enables provincial and regional health and population departments to see the contribution of private sector in their geographical areas.</p>
                <p>For nationwide implementation of LMIS, the Project needed to train the expected users of the system on its uses and functionality. The two day training provides skills and knowledge required to independently enter and upload data into web-based LMIS. Web-based LMIS played a significant role in improving stock monitoring at the district level. The real time monitoring helped eliminate stock-outs at district level.</p>
                <p>Timely and accurate data entry and submission of a monthly report at the district level is critical to the functioning of the LMIS. The data collected from the LMIS can then subsequently be used at each level of the supply chain to enhance informed decision making to meet service delivery demands. Utilization of the LMIS will depend heavily on the level of understanding of those trained on its various functionalities.</p>
                <p>The below mentioned training material has been developed to train the master trainers and different level users on LMIS</p>
                <h4>Pakistan&#39;s Logistics Management Information System: Training of Trainers Manual</h4>
                <p>This TOT manual contains the training sessions for the &quot;Training on Pakistan Logistics Management Information System (LMIS)&quot;. The trainers will be using the manual when training master trainers on LMIS.</p>
                 <a target="_blank" href="http://lmis.gov.pk/training_manuals/clmis_tot_r2_draft.pdf">Download (Draft)</a>
    
                <h4>Pakistan&#39;s Logistics Management Information System: Training of Trainers Manual - TOT Course Trainer Modules</h4>
                <p>The manual has been designed for use of trainers on training skills and communication elements during training of trainers. In addition to the modules that instruct participants how to train there are practice sessions for the participants.</p>
                <a target="_blank" href="http://lmis.gov.pk/training_manuals/participants_comm_skills_manual_draft.pdf" >Download (Draft)</a>
    
                <h4>Pakistan&#39;s Logistics Management Information System: Modules Guide for Participants</h4>
                <p>The guide has been designed for participants of training of trainers and contains handouts on training skills and communication elements.</p>
                <a target="_blank" href="http://lmis.gov.pk/training_manuals/clmis_participant_manual_draft.pdf">Download (Draft)</a>
    
                <h4>Pakistan&#39;s Logistics Management Information System: Facilitator Manual</h4>
    
                <p>This facilitator manual contains the training sessions for the &quot;Training on Pakistan Logistics Management Information System(LMIS)&quot;. The trainers will be using the manual when training users on LMIS.</p>
                <a target="_blank" href="http://lmis.gov.pk/training_manuals/clmis_facilitator_manual_draft.pdf">Download (Draft)</a>
    
                <h4>Pakistan&#39;s Logistics Management Information System: User Manual</h4>
                <p>The Logistics Management Information System for Contraceptives User Guide provides step-by-step instructions that help you get started with Logistics Management Information System (LMIS) functions and features and provides guidelines on managing relevant Logistics data using the Logistics Management Information System accounts. This guide is organized according to the logical flow of Logistics Management Information System features and describes tasks in the same order you can use while working with the product.</p>
                <a target="_blank" href="http://lmis.gov.pk/training_manuals/clmis_user_manual_draft.pdf">Download (Draft)</a>
			</div>
			<!-- // Tab content END -->
			
			<!-- Tab content -->
			
			<div class="tab-pane active" id="tab2-3">
            	<p>For the first time Health Services Academy (HSA) Islamabad offers following courses, in conjunction with the USAID|DELIVER Project;</p>
    
                <ul id="bullets">
                    <li>Three Credit Course for Supply Chain Management of Public Health Commodities</li>
                    <li>Certificate Course on Supply Chain Management of Health Commodities</li>
                </ul>
                <p>The courses will help to increase the participants&#39; understanding of the fundamentals of logistics management and the relationship between supply chain logistics and commodity security. It also will strengthen the participants&#39; ability to implement improvements to basic elements of their logistics systems.</p>
                <h4>Lecturer&#39;s Guide: Three Credit Course for Supply Chain Management of Public Health Commodities</h2>
                <p>The Lecture Guide for the Three Credit Course on Supply Chain Management of Public Health Commodities is meant for lecturers whoare supposed to conduct the course. This curriculum is written following Adult Learning Theory principles which emphasize more interactive and participatory approaches than traditional teaching techniques. Small group exercises are placed into the curriculum and Lecturers are encouraged to run these activities as written where ever possible. The curriculum not only provides basic principles for running a logistic system but includes elements that are Pakistan specific. For this course each major unit of learning is referred to as a Session and each section within is referred to as an Activity. This course has 17 different Sessions. Lecturers are highly encouraged to read the Lecturer Preparation section at the beginning of each session well before leading each session. Note that Synthesis Questions are presented at the end of each session. These can be used to ensure students are grasping the key concepts of each session. Unlike the Certificate course this course is competency-based.</p>
                <a target="_blank" href="http://lmis.gov.pk/training_manuals/three_credit_lecturer_guide.pdf">Download</a>
                <h4>Student Guide: Three Credit Course on Supply Chain Management of Health Commodities</h2>
                <p>The Guide is meant for use of three credit course students.</p>
                <a target="_blank" href="http://lmis.gov.pk/training_manuals/three_credit_student_guide.pdf">Download</a>
                <h4><br>Lecturer&#39;s Guide: Certificate Course on Supply Chain Management of Health Commodities</h2>
    
                <p>The Lecture Guide for the Certificate Course on Supply Chain Management of Public Health Commodities is meant for lecturers who are supposed to conduct the course. This will become a three credit course and likely expand to a larger offering in time. This curriculum is written following Adult Learning Theory principles which emphasize more interactive and participatory approaches than traditional teaching techniques. Small group exercises are placed into the curriculum and Lecturers are encouraged to run these activities as written. The curriculum not only provides basic principles for running a logistic system but includes elements that are Pakistan specific. For this course each major unit of learning is referred to as a Session and each part of a Session is referred to as an Activity. This course has 16 different Sessions. Lecturers are highly encouraged to read the &#39;Lecturer Preparation&#39; section at the beginning of each session well before leading each session.</p>
                <a target="_blank" href="http://lmis.gov.pk/training_manuals/certificate_course_lect_guide.pdf">Download</a>
                <h4>Student Handbook: Certificate Course on Supply Chain Management of Health Commodities</h2>
    
                <p>The handbook is meant for use of certificate course students.</p>
                <a target="_blank" href="http://lmis.gov.pk/training_manuals/certificate_course_student_guide.pdf">Download</a>
            </div>
			<!-- // Tab content END -->
			
			<!-- Tab content -->
			<div class="tab-pane" id="tab3-3">
				<h4>Contraceptives Procurement Manuals</h4>
                <br>
                <p>The Province specific Contraceptive Procurement Manual has been developed to provide the Provincial Governments&#39; Population Welfare and Health Departments&#39; procurement personnel with the information and instructions needed to procure contraceptives of good quality on the international market to support the coordinated goals of the Provincial Governments towards improving maternal and child health in their respective provinces. The Contraceptive Procurement Manual incorporates best international procurement practices that help promote transparency, accountability and efficiency in the public sector procurement process.  The Procurement Manual addresses the key phases of the procurement cycle, from procurement planning and issuing invitations to bid, to bid evaluation, supplier selection, contract award and management.
                </p>
                <p><br>The primary audience for the Contraceptive Procurement Manual is procurement officers and other direct procurement staff who are assigned responsibility for procuring quality contraceptives.   The Procurement Manual provides these personnel with step-by-step instructions for completing standard bidding documents, opening bids from suppliers, evaluating supplier bids and monitoring supplier performance.   The manual also includes supplementary materials, such as information on pre-qualification and pre-shipment compliance programs, which are designed to support procurement officers in effectively implementing public sector procurement of contraceptives.</p>
                <p><br>The Contraceptive Procurement Manual also provides pertinent information for policymakers and mid-level decision makers who are not required to understand the detailed procedures of the procurement process, but should understand the overall procurement process for contraceptives and the role they can play to help ensure the procurement process is effectively implemented.  It is recommended that this audience review Appendix IV: Summary Guide for Policymakers, Directors and Managers.
                    The Contraceptive Procurement Manual also includes key reference documents, such as the Public Procurement Rules 2010 and the Drugs (Labeling and Packing) Rules 1986, to ensure that procurement officers have access to original resource documents as they prepare for and conduct public sector procurement of contraceptives.
                    Users of the Contraceptive Procurement Manual are encouraged to thoroughly review the Manual in order to fully understand the breadth and scope of the information it contains so that they can be fully prepared to conduct effective public sector procurement of quality contraceptives for the people of their respective provinces.
                </p><br>
                <a target="_blank" href="http://lmis.gov.pk/training_manuals/contraceptive_manual_kpk.pdf">Government of Khyber Pakhtunkhwa</a>
                <br/>
                <a target="_blank" href="http://lmis.gov.pk/training_manuals/contraceptive_manual_punjab.pdf">Government of Punjab</a>
                <br/>
                <a target="_blank" href="http://lmis.gov.pk/training_manuals/contraceptive_manual_sindh.pdf">Government of Sindh</a>
			</div>
			<!-- // Tab content END -->
			
		</div>
	</div>

</div>
</div>
</div>


</div>
</div>
<div class="clearfix"></div>
<?php include "plmis_inc/common/footer.php";?>


</body>
<!-- END BODY -->
</html>
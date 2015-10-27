<?php
include("Includes/AllClasses.php");
include("../html/adminhtml.inc.php");

$title = "New Cold Chain Asset";
include('../template/header-top.php');
include('../template/header-bottom.php');
//include('../'.$_SESSION['menu']); ?>

<!-- Content -->

<div id="content">
  <div class="innerLR">
	<div class="widget"> 
      <!-- Widget Heading -->
      <div class="widget-body">
          <!-- Row -->
          <div class="row-fluid">
		  <div class="span11 center"><h4>Stock Recieve List</h4>
		  </div>
		  <div class="span9">
			  <div class="span2">R.V.No</div>
			  <div class="span3"><?php echo $_REQUEST['rec_no'];?></div>
			  <div class="span1">&nbsp;</div>
			  <div class="span3">Date of Arrival</div>
			  <div class="span3"><?php echo $_REQUEST['rec_date'];?></div>
		  </div>
		   <div class="span9">
			  <div class="span2">Source</div>
			  <div class="span4"><?php echo $_REQUEST['rec_from'];?></div>
		  </div>
		  <div class="span10"> 
		<br />
			<table class="table table-striped table-bordered table-condensed">
			  <tr class="gradeX">
			    <td rowspan="2" width="5%">S. NO.</td>
			    <td rowspan="2" width="10%">Vaccine</td>
			    <td rowspan="2" width="10%">Batch NO</td>
				<td colspan="2" width="18%" align="center">Quantity</td>
			    <td rowspan="2" width="9%">Doses Per Vial</td>
				<td rowspan="2" width="9%">VVM Type</td>
			    <td rowspan="2" width="9%">VVM Stage</td>
			    <td rowspan="2" width="12%">Production Date</td>
			    <td rowspan="2" width="10%">Expiry Date</td>
		      </tr>
			  <tr style="background-color: #F8F8F8;">
			    <td width="8%">Vials</td>
			    <td width="10%">Doses</td>
		      </tr>
			  
			  <tbody>
			  <?php
			  $i=0; 
			  $vacPlace=$_SESSION['stock_rec_supplier'];
			  if(!empty($vacPlace)){
			  	foreach($vacPlace as $val){
					$i++;
					?>
			  <tr>
			    <td><?php echo $i;?></td>
			    <td><?php echo $val->itm_name;?></td>	    
				<td><?php echo $val->batch_no; ?></td>
			    <td><?php echo $val->Qty; ?></td>
			    <td><?php echo $val->Qty*$val->doses_per_unit; ?></td>
				<td><?php echo $val->doses_per_unit;?></td>
				<td><?php echo $val->vvm_type;?></td>
				<td><?php echo $val->vvm_stage; ?></td>
				<td><?php echo date("d M,Y", strtotime($val->production_date)); ?></td>
			    <td> <?php echo date("d M,Y", strtotime($val->batch_expiry)); ?></td>
		    </tr>
			<?php 	}
			  } ?>
			  </tbody>
				
			</table>
			<br />
	  
      </div>
	<div class="span12">
		<div class="span12">&nbsp;</div>
	  <div class="span5">
	  	<div class="span4">Name</div>
		<div class="span8">_________________________ </div>
	  </div>
	  <div class="span5">
	  	<div class="span4">Signature;</div>
		<div class="span8">_________________________ </div>
	  </div>
	  <div class="span5">
	  	<div class="span4">Designation</div>
		<div class="span8">_________________________ </div>
	  </div>
	  <div class="span5">
	  	<div class="span4">Date</div>
		<div class="span8"><?php echo date("d M,Y");?> </div>
	  </div>	
	  </div>  
    </div>
    <!-- // Row END --> 
  </div>
</div>
</div>
<!-- // Content END -->
<?php include('../template/footer.php'); ?>
<script src="<?php echo SITE_DOMAIN; ?>plmis_js/dataentry/newcoldchain.js"></script>
<script src="<?php echo SITE_DOMAIN; ?>plmis_js/dataentry/levelcombos_all_levels.js"></script>
<?php
unset($_SESSION['stock_id']);
?>

<script language="javascript">
window.print();	
</script>
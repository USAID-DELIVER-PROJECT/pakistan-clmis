<?php 

/**
 * combos
 * @package reports
 * 
 * @author     Muhammad Waqas Azeem 
 * @email <waqas@deliver-pk.org>
 * 
 * @version    2.2
 * 
 */
$showHFTypeArr = array('pwd3', 'spr2');
?>
<script>
	$(function() {
		<?php 
		if ( in_array($rptId, $showHFTypeArr) )
		{?>
			$('#prov_sel').change(function(e) {
				$('#hf_type_sel').html('<option value="">Select</option>');
				showHFType($(this).val(), '');
			});
		<?php 
		}
		else
		{
		?>
			showDistricts();
			$('#prov_sel').change(function(e) {
				$('#district').html('<option value="">Select</option>');
				showDistricts();
			});
		<?php
		}
		?>
	})
	function showDistricts()
	{
		var provinceId = $('#prov_sel').val();
		if (provinceId != '')
		{
			$.ajax({
				url: 'ajax_calls.php',
				data: {provinceId: provinceId, dId: '<?php echo $districtId; ?>', stkId: 1},
				type: 'POST',
				success: function(data)
				{
					$('#districtDiv').html(data);
				}
			})
		}
	}
	
	function showHFType(provId, hfTypeId)
	{
		if ( provId != '' )
		{
			$.ajax({
				url: 'ajax_calls.php',
				type: 'post',
				data: {provId: provId, hfTypeId: hfTypeId},
				success: function(data){
					$('#hf_type_sel').html(data);
				}
			})
		}
	}
	
	
	$(function() {
		$('#from_date, #to_date').datepicker({
			dateFormat: "yy-mm",
			constrainInput: false,
			changeMonth: true,
			changeYear: true,
			maxDate: 0
		});
	})
</script>
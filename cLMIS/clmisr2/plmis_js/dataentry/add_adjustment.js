$(function(){
    $('#available_div').hide();
	$('#product').change(function(){
        $("#available").val('');
		$.ajax({
			type: "POST",
			url: "ajaxrunningbatches.php",
			data: {product: $('#product').val()},
			dataType: 'html',
			success: function(data){
				$('#batch_no').html(data);
			}		
		});
		$.ajax({
			type: "POST",
			url: "ajaxproductbatch.php",
			data: {product: $(this).val()},
			dataType: 'html',
			success: function(data){
				$('#product-unit').html(data);
			}		
		});
	});
	$('#quantity').priceFormat({
		prefix: '',
		thousandsSeparator: ',',
		suffix: '',
		centsLimit: 0,
		limit: 10
	});
	
	$('#quantity').keyup(function(e){
		var ava_qty = $("#available").val();
		ava_qty = parseInt(ava_qty.replace(/,/g,""),10);
		
		var qty = $(this).val();
		qty = parseInt(qty.replace(/,/g,""),10);
		
		if(qty > ava_qty) {
			alert("Quantity should not greater then "+ava_qty);
			$(this).focus();
		}
	});
	
	$('#batch_search').submit(function(e){		
		var ava_qty = $("#available").val();
		ava_qty = parseInt(ava_qty.replace(/,/g,""),10);

		var qty = $('#quantity').val();
		qty = parseInt(qty.replace(/,/g,""),10);
		
		if(qty > ava_qty) {
			e.preventDefault();
			alert("Quantity should not greater then "+ava_qty);
			$('#quantity').focus();
		}
	});
	
	$('#batch_no').change(function(){
		$.ajax({
			type: "POST",
			url: "ajaxrunningbatches.php",
			data: {batch_no: $('#batch_no').val()},
			dataType: 'html',
			success: function(data){
                $('#available_div').fadeIn(1000);
				$('#itembatches').html(data);
			}
		});
	});

    $('#adjustment_date').datepicker({
        dateFormat: "dd/mm/yy",
		constrainInput: false
    });
	
	$('#adjustment_date').mask('00/00/0000');
});

// validate signup form on keyup and submit
$("#batch_search").validate({
    rules: {
        'product': {
            required: true
        },
        'batch_no': {
            required: true
        },
        'quantity': {
            required: true
        },
        'types': {
            required: true
        }
    },
    messages: {
        'product': {
            required: "Please select product"
        },
        'batch_no': {
            required: "Please enter batch number"
        },
        'qty': {
            required: "Please enter quantity"
        },
        'types': {
            required: "Please enter adjustment type"
        }
    },
	submitHandler: function(form) {
		$('#add_adjustment').attr('disabled', true);
		$('#add_adjustment').html('Submitting...');
		form.submit();
	}
});

$("#add_adjustment").click(function(e){
    e.preventDefault();
    var validator = $( "#batch_search" ).validate();
    if($("#quantity").val() <= 0){
        validator.showErrors({
            "quantity": "Quantity should greater then 0"
        });
    } else {
        $("#batch_search").submit();
    }
});

$("#reset").click(function(){
    $('#available_div').fadeOut(1000);
    $('#batch_no').empty();
});
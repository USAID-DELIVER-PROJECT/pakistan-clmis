$(function() {

    var product = $('#product').val();

    if (product != '') {
        $("#add_m_p").show();
        
         $.ajax({
            type: "POST",
            url: "ajaxproductcat.php",
            data: {
                product: product
            },
            dataType: 'html',
            success: function(data) {
                if (data == '2') {
                    $("#expiry_date").rules("remove", "required");
                    //$( "#expiry_div" ).hide();
                      $("#vvmtype").val("");
                       $("#vvmstage").val("");
                    $("#vvmtype").attr("disabled", "disabled");
                    $("#vvmstage").attr("disabled", "disabled");
                    
                  //  $("#vvmtype_div").hide();
                    $("#vvmstage_div").hide();
                } else {
                    $("#expiry_date").rules("add", "required");
                    //$( "#expiry_div" ).show();
                 
                     $("#vvmtype").removeAttr("disabled");
                      $("#vvmstage").removeAttr("disabled");
                   // $("#vvmtype_div").show();
                  //  $("#vvmstage_div").show();
                }
            }
        });
        

    }
    else {
        $("#add_m_p").hide();
    }


    $("#save_manufacturer").click(function() {
        var product = $('#product').val();
        var manufacturer = $('#new_manufacturer').val();
        if (manufacturer == '') {
            alert('Please enter Manufacturer.');
            return false;
        }
        $.ajax({
            type: "POST",
            url: "add_action_manufacturer.php",
            data: {
                add_action: 1,
                new_manufacturer: manufacturer,
                item_pack_size_id: product

            },
            dataType: 'html',
            success: function(data) {

                $('#manufacturer').html(data);
                
            }
        });
    });


    /*$("#receive_date").datepicker({
        dateFormat: 'dd/mm/yy',
        constrainInput: false,
        changeMonth: true,
        changeYear: true
    });*/
	
	$('#receive_date').datetimepicker({
		format: "dd/mm/yyyy HH:ii P",
	    showMeridian: true,
	    autoclose: true,
	    startDate: "dd/mm/yyyy 10:00",
	    todayBtn: true,
        changeMonth: true,
        changeYear: true
	});

    $("#expiry_date").datepicker({
        minDate: 0,
        maxDate: "+10Y",
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true
    });

    $("#prod_date").datepicker({
        minDate: "-10Y",
        maxDate: 0,
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        constrainInput: false
    });

    //$('#receive_date, #expiry_date, #prod_date').mask('00/00/0000');

    $("#product").change(function() {
        var product = $('#product').val();
         
           

        if (product != '') {
            $("#add_m_p").show();

        }
        else {
            $("#add_m_p").hide();
        }
$.ajax({
            type: "POST",
            url: "ajaxproductname.php",
            data: {
                
                product: $(this).val()
            },
            dataType: 'html',
            success: function(data) {
                
               $("#pro_loc").html('<h5>Add Manufacturer for '+data+'</h5>'); 
            }
        });

        $.ajax({
            type: "POST",
            url: "ajaxproductbatch.php",
            data: {
                product: $(this).val()
            },
            dataType: 'html',
            success: function(data) {
                $('#product-unit').html(data);
            }
        });
        $.ajax({
            type: "POST",
            url: "ajaxproductcat.php",
            data: {
                product: $(this).val()
            },
            dataType: 'html',
            success: function(data) {
                if (data == '2') {
                    $("#expiry_date").rules("remove", "required");
                    //$( "#expiry_div" ).hide();
                      $("#vvmtype").val("");
                       $("#vvmstage").val("");
                    $("#vvmtype").attr("disabled", "disabled");
                    $("#vvmstage").attr("disabled", "disabled");
                    
                  //  $("#vvmtype_div").hide();
                    $("#vvmstage_div").hide();
                } else {
                    $("#expiry_date").rules("add", "required");
                    //$( "#expiry_div" ).show();
                 
                     $("#vvmtype").removeAttr("disabled");
                      $("#vvmstage").removeAttr("disabled");
                   // $("#vvmtype_div").show();
                  //  $("#vvmstage_div").show();
                }
            }
        });

        $.ajax({
            type: "POST",
            url: "add_action_manufacturer.php",
            data: {
                show: 1,
                product: $(this).val()
            },
            dataType: 'html',
            success: function(data) {
                
                $('#manufacturer').html(data);
            }
        });

     



    });

    $.inlineEdit({
        Qty: 'ajaxReceive.php?type=qty&Id=',
        Batch: 'ajaxReceive.php?type=batch&Id='

    }, {
        animate: false,
        filterElementValue: function($o) {
            return $o.html().trim();
        },
        afterSave: function() {
        }

    });

    $('#unit_price').priceFormat({
        prefix: '',
        thousandsSeparator: '',
        suffix: '',
        centsLimit: 2
    });

    $('[data-toggle="notyfy"]').click(function() {
        var self = $(this);
		$.notyfy.closeAll();
        notyfy({
            text: notification[self.data('type')],
            type: self.data('type'),
            dismissQueue: true,
            layout: self.data('layout'),
            buttons: (self.data('type') != 'confirm') ? false : [
                {
                    addClass: 'btn btn-success btn-medium btn-icon glyphicons ok_2',
                    text: '<i></i> Ok',
                    onClick: function($notyfy) {
                        var id = self.attr("id");
                        $notyfy.close();
                        window.location.href = 'delete_receive.php?id=' + id;
                    }
                },
                {
                    addClass: 'btn btn-danger btn-medium btn-icon glyphicons remove_2',
                    text: '<i></i> Cancel',
                    onClick: function($notyfy) {
                        $notyfy.close();
                        /*notyfy({
                            force: true,
                            text: '<strong>You clicked "Cancel" button<strong>',
                            type: 'error',
                            layout: self.data('layout')
                        });*/
                    }
                }
            ]
        });
        return false;
    });

    $.validator.setDefaults({
        ignore: ':hidden, [readonly=readonly]'
    });

    $('#reset').click(function() {
        window.location.href = basePath + 'plmis_admin/new_receive.php';
    });
});

var notification = [];
notification['confirm'] = 'Do you want to continue?';

/*$('#vvmstage').priceFormat({
    prefix: '',
    thousandsSeparator: ',',
    suffix: '',
    centsLimit: 0,
    limit: 2
});*/
$('#qty').priceFormat({
    prefix: '',
    thousandsSeparator: ',',
    suffix: '',
    centsLimit: 0,
    limit: 10
});

$('#print_vaccine_placement').click(function() {
    var ref_no, rec_no, rec_date, unit_pric, rec_from;
    ref_no = $('#receive_ref').val();
    rec_no = $('#receive_no').val();
    rec_date = $('#receive_date').val();
    rec_from = $('#source_name').val();
    window.open('stockRecivePrint.php?rec_no=' + rec_no + '&ref_no=' + ref_no + '&rec_date=' + rec_date + '&rec_from=' + rec_from, '_blank', 'scrollbars=1,width=842,height=595');
});

$('#qty').focusout(function() {
    if ($(this).val() == 0)
    {
        $(this).val(1);
    }
    else
    {
        $(this).val($(this).val());
    }
})

$('#qty, #product').change(function(e) {

    var qty = $('#qty').val();
    var itemId = $('#product').val();

    $('#product-unit1').css('display', 'none');

    if (qty != 0 && qty != '' && itemId != '')
    {
        $.ajax({
            type: "POST",
            url: "ajaxproductcat.php",
            data: 'qty=' + qty + '&itemId=' + itemId,
            success: function(doses) {
                if (doses != '')
                {
                    $('#product-unit1').css('display', 'table-row');
                    $('#product-unit1').html(doses);
                }
            }
        });
    }
});

/*$("#add_receive").click(function (e) {
 e.preventDefault();
 var validator = $("#new_receive").validate();
 if ($("#qty").val() <= 0) {
 validator.showErrors({
 "qty": "Quantity should greater then 0"
 });
 } else {
 $("#new_receive").submit();
 }
 });*/

// validate signup form on keyup and submit
jQuery.validator.addMethod("mindate", function(value, element) {

    var x = new Date();
    var str = value;
    var day = str.substr(0, 2);
    var month = parseInt(str.substr(3, 2)) - 1;
    var year = str.substr(6);

    x.setFullYear(year, month, day);
    var today = new Date();

    return x > today;
}, ("Expiry date must be future date."));

jQuery.validator.addMethod("maxdate", function(value, element) {

    if (value != '')
    {
        var x = new Date();
        var str = value;
        var day = str.substr(0, 2);
        var month = parseInt(str.substr(3, 2)) - 1;
        var year = str.substr(6);

        x.setFullYear(year, month, day);
        var today = new Date();
        return x < today;
    }
    else
    {
        return true;
    }
}, ("Production date must be past date."));

$("#new_receive").validate({
    rules: {
        'product': {
            required: true
        },
        'batch': {
            required: true
        },
        'prod_date': {
            maxdate: true
        },
        'qty': {
            required: true
        },
        'expiry_date': {
            required: true,
            mindate: true
        }
    },
    messages: {
        'receive_ref': {
            required: "Please enter refernce number"
        },
        'product': {
            required: "Please select product"
        },
        'batch': {
            required: "Please enter batch number"
        },
        'qty': {
            required: "Please enter quantity"
        },
        'expiry_date': {
            required: "Expiry date is required"
        }
    },
	submitHandler: function(form) {
		$('#add_receive').attr('disabled', true);
		$('#add_receive').html('Submitting...');
		form.submit();
	}
});

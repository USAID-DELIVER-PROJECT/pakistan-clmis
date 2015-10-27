$(function() {

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
                    addClass: 'btn btn-success btn-small btn-icon glyphicons ok_2',
                    text: '<i></i> Ok',
                    onClick: function($notyfy) {
                        var id = self.attr("id");
                        $notyfy.close();
                        window.location.href = 'delete_placement.php?id=' + id;
                    }
                },
                {
                    addClass: 'btn btn-danger btn-small btn-icon glyphicons remove_2',
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

 /*   $.validator.setDefaults({
        ignore: ':hidden, [readonly=readonly]'
    });*/

    $('#reset').click(function() {
        window.location.href = basePath + 'plmis_admin/stock_placement.php';
    });
    $("button[id^='add_stock_']").click(function(){

    	 var suffix = this.id.match(/\d+/);
    	 window.location.href = basePath + 'plmis_admin/add_stock.php?loc_id='+suffix+'&'+$('#hiddFld').attr('value');
    	});
    $("button[id^='back_location_']").click(function(){

   	 var suffix = this.id.match(/\d+/);
   	 window.location.href = basePath + 'plmis_admin/stock_location.php?loc_id='+suffix+'&'+$('#hiddFld').attr('value');
   	});
    var notification = [];
    notification['confirm'] = 'Do you want to continue?';

   /* $('#tran_no').change(function() {
    	var tran_no=$('#tran_no').val();

    	$.ajax({
            type: "POST",
            url: "ajax_stock_pick.php",
            data: {
            	tran_no: tran_no
            },
            dataType: 'html',
            success: function(data) {
                $('#pick_stock').html(data);
            }
        });
    });*/
    
    /*$("a[id^='pick_']").click(function() {
    	var id=$(this).attr('id');
    	id2=id.split('_');
    	item_id=id2[1];
    	batch_no=id2[2];
    	batch_expiry=id2[3];
    	item1=id2[4];
    	detail=id2[5];
    	$.ajax({
            type: "GET",
            url: "ajax_stock_pick.php",
            data: {
            	item_id: item_id,
            	batch_no: batch_no,
            	batch_expiry: batch_expiry,
            	itm:item1,
            	detail:detail
            },
            dataType: 'html',
            success: function(data) {
				//alert(data);
               //$('#pick_stock').html(data);
            }
        });
    	
    });*/
    
    //$('#pick_qty').focus(function(){
    
    $('#save_pick').attr('disabled','disabled');
    //});
    $('#pick_qty').change(function(){
    	var pick=$('#pick_qty').val();
    	var available=$('#available_qty').val();
    	if(pick>available){
    		$('#pick_qty').addClass('error');
    		$('#save_pick').attr('disabled','disabled');
    	}
    	if(pick<=available){
    		$('#save_pick').removeAttr('disabled','');
    	}
    });
	
	$("#new_receive").validate({
		rules: {
			'area': {
				required: true
			},
			'row': {
				required: true
			},
			'rack': {
				required: true
			},
			'rack_type': {
				required: true
			},
			'pallet': {
				required: true
			},
			'level': {
				required: true
			}
		},
		submitHandler: function(form) {
			$('#add_receive').attr('disabled', true);
			$('#add_receive').html('Submitting...');
			form.submit();
		}
	});

});
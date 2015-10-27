$(function(){
    var startDateTextBox = $('#date_from');
	var endDateTextBox = $('#date_to');
	
	startDateTextBox.datepicker({
		minDate: "-10Y",
        maxDate: 0,
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
		onClose: function(dateText, inst) {
			if (endDateTextBox.val() != '') {
				var testStartDate = startDateTextBox.datepicker('getDate');
				var testEndDate = endDateTextBox.datepicker('getDate');
				if (testStartDate > testEndDate)
					endDateTextBox.datepicker('setDate', testStartDate);
			}
			else {
				endDateTextBox.val(dateText);
			}
		},
		onSelect: function (selectedDateTime){
			endDateTextBox.datepicker('option', 'minDate', startDateTextBox.datepicker('getDate') );
		}
	});
	endDateTextBox.datepicker({
        maxDate: 0,
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
		onClose: function(dateText, inst) {
			if (startDateTextBox.val() != '') {
				var testStartDate = startDateTextBox.datepicker('getDate');
				var testEndDate = endDateTextBox.datepicker('getDate');
				if (testStartDate > testEndDate)
					startDateTextBox.datepicker('setDate', testEndDate);
			}
			else {
				startDateTextBox.val(dateText);
			}
		},
		onSelect: function (selectedDateTime){
			startDateTextBox.datepicker('option', 'maxDate', endDateTextBox.datepicker('getDate') );
		}
	});

    $('#reset').click(function(){
        window.location.href = basePath + 'plmis_admin/stock_adjustment.php';
    });
});
$('#print_stock').click(function(){
	window.open('stock_adjustmentPrint.php','_blank','width=842,height=595');
});

function PrintIt(){
	window.print();
}
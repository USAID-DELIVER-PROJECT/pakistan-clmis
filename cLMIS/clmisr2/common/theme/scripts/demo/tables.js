/* ==========================================================
 * AdminKIT v1.5
 * tables.js
 * 
 * http://www.mosaicpro.biz
 * Copyright MosaicPro
 *
 * Built exclusively for sale @Envato Marketplaces
 * ========================================================== */ 


$(function()
{
	/* DataTables */
	if ($('.dynamicTable').size() > 0)
	{
		$('.dynamicTable').dataTable({
                        "sPaginationType": "bootstrap",
			"sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
			"oLanguage": {
				"sLengthMenu": "_MENU_ records per page"
			},
			"aaSorting": []
		});
	}/* DataTables */

	if ($('.dynamicTable2').size() > 0)
    {
        var datatable = $('.dynamicTable2').dataTable({
            "sPaginationType": "bootstrap",
            //"sDom": 'W<"clear">lfrtip',
           // "sDom": 'T<"clear">lfrtip',
          "sDom": "<'row'<'col-md-11'>T<'clear'>><'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
            // "sDom": '<"clear">lfrtipT',
            "oLanguage": {
                "sLengthMenu": "_MENU_ records per page"
            },
            "oTableTools": {
                "aButtons": [
                    {
                        "sExtends": "xls",
                        "sButtonText": "<img src=../images/excel-16.png>",
                        "sTitle": "Location List",
                        "mColumns": [0]
                    },
                    {
                        "sExtends": "pdf",
                        "sButtonText": "<img src=../images/pdf-16.png>",
                        "mColumns": [0],
                        "sTitle": "Location List",
                        "sPdfOrientation": "landscape"
                    }
                    
                ],
                
                "sSwfPath": basePath  + "/common/theme/scripts/plugins/tables/DataTables/extras/TableTools/media/swf/copy_csv_xls_pdf.swf"
            }

        });

    }
	if ($('.receivesearch').size() > 0)
    {
        var datatable = $('.receivesearch').dataTable({
            "sPaginationType": "bootstrap",
            //"sDom": 'W<"clear">lfrtip',
           // "sDom": 'T<"clear">lfrtip',
          "sDom": "<'row'<'col-md-11'>T<'clear'>><'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
            // "sDom": '<"clear">lfrtipT',
            "oLanguage": {
                "sLengthMenu": "_MENU_ records per page"
            },
            "oTableTools": {
                "aButtons": [
                    {
                        "sExtends": "xls",
                        "sTitle": "Stock Receive Search",
                        "sButtonText": "<img src=../images/excel-16.png>",
                        "mColumns": "visible"
                    },
                    {
                        "sExtends": "pdf",
                        "sButtonText": "<img src=../images/pdf-16.png>",
                        "mColumns": "visible",
                        "sTitle": "Stock Receive Search",
                        "sPdfOrientation": "landscape"
                    }
                    
                ],
                
                "sSwfPath": basePath  + "/common/theme/scripts/plugins/tables/DataTables/extras/TableTools/media/swf/copy_csv_xls_pdf.swf"
            }

        });

    }
    
	if ($('.bincard').size() > 0)
    {
        var datatable = $('.bincard').dataTable({
            "sPaginationType": "bootstrap",
            //"sDom": 'W<"clear">lfrtip',
           // "sDom": 'T<"clear">lfrtip',
          "sDom": "<'row'<'col-md-11'>T<'clear'>><'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
            // "sDom": '<"clear">lfrtipT',
            "oLanguage": {
                "sLengthMenu": "_MENU_ records per page"
            },
            "oTableTools": {
                "aButtons": [
                    {
                        "sExtends": "xls",
                        "sTitle": "Bin Card",
                        "sButtonText": "<img src=../images/excel-16.png>",
                        "mColumns": "visible"
                    },
                    {
                        "sExtends": "pdf",
                        "sButtonText": "<img src=../images/pdf-16.png>",
                        "mColumns": "visible",
                        "sTitle": "Bin Card",
                        "sPdfOrientation": "landscape"
                    }
                    
                ],
                
                "sSwfPath": basePath  + "/common/theme/scripts/plugins/tables/DataTables/extras/TableTools/media/swf/copy_csv_xls_pdf.swf"
            }

        });

    }
	if ($('.issuesearch').size() > 0)
    {
        var datatable = $('.issuesearch').dataTable({
            "sPaginationType": "bootstrap",
            //"sDom": 'W<"clear">lfrtip',
           // "sDom": 'T<"clear">lfrtip',
          "sDom": "<'row'<'col-md-11'>T<'clear'>><'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
            // "sDom": '<"clear">lfrtipT',
            "oLanguage": {
                "sLengthMenu": "_MENU_ records per page"
            },
			//"aaSorting": [[1, 'desc'], [4, 'desc']],
            "oTableTools": {
                "aButtons": [
                    {
                        "sExtends": "xls",
                        "sTitle": "Stock Issue Search",
                        "sButtonText": "<img src=../images/excel-16.png>",
                        "mColumns": "visible"
                    },
                    {
                        "sExtends": "pdf",
                        "sButtonText": "<img src=../images/pdf-16.png>",
                        "mColumns": "visible",
                        "sTitle": "Stock Issue Search",
                        "sPdfOrientation": "landscape"
                    }
                    
                ],
                
                "sSwfPath": basePath  + "/common/theme/scripts/plugins/tables/DataTables/extras/TableTools/media/swf/copy_csv_xls_pdf.swf"
            }

        });

    }
});
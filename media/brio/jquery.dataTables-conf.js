
$(document).ready(function() {


	/************************************
	Basic Data Table
	************************************/
    //$('#basic-datatable').dataTable();
	var table = $('#dt-wrapper').dataTable({
        "lengthMenu": [25,50,100],
        "stateSave": true,
        "stateDuration": 60*60*24,
        "order": []
    });

    $('#bt-delete-selection').click(function(){
        alert( table.rows('tr.selected').data().length +' row(s) selected' );
        table.row('.selected').remove().draw( false );
    });
    /*$('#dt-wrapper tbody').on('click','tr', function(){
        console.log(table.row(this).data());
    });*/

    $('#txo-dumps').dataTable({scrollX: true, ordering:false});
	$('#site-list').dataTable();
    $('.cylinder-table').dataTable({ordering:false});    
    
	/************************************
	Toggle Column
	************************************/
	var toggleColumnTable = $('#toggleColumn-datatable').DataTable();
 
    $('a.toggle-vis').on( 'click', function (e) {
        e.preventDefault();
 
        // Get the column API object
        var column = toggleColumnTable.column( $(this).attr('data-column') );
 
        // Toggle the visibility
        column.visible( ! column.visible() );
    });
	
	/* Formatting function for row details - modify as you need */
	function format ( d ) {
		// `d` is the original data object for the row
		return '<table class="extra-info">'+
			'<tr>'+
				'<td>Full name:</td>'+
				'<td>'+d.name+'</td>'+
			'</tr>'+
			'<tr>'+
				'<td>Extension number:</td>'+
				'<td>'+d.extn+'</td>'+
			'</tr>'+
			'<tr>'+
				'<td>Extra info:</td>'+
				'<td>And any further details here (images etc)...</td>'+
			'</tr>'+
		'</table>';
	}
 

    var table = $('#hiddendta-datatable').DataTable( {
        "ajax": "assets/js/plugins/datatables/objects.txt",
        "columns": [
            {
                "class":          'details-control',
                "orderable":      false,
                "data":           null,
                "defaultContent": '<a class="btn btn-link"><i class="fa fa-plus-square"></i></a>'
            },
            { "data": "name" },
            { "data": "position" },
            { "data": "office" },
            { "data": "salary" }
        ],
        "order": [[1, 'asc']]
    } );
     
    // Add event listener for opening and closing details
    $('#hiddendta-datatable tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = table.row( tr );
 
        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            // Open this row
            row.child( format(row.data()) ).show();
            tr.addClass('shown');
        }
    } );

	
	
	
});
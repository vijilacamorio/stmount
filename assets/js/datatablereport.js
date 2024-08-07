$(document).ready(function() {
    'use strict';
    $("#rexdatatable").DataTable({
        dom:"<'row m-0'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
        lengthMenu:[[10,25,50,-1],[10,25,50,"All"]],
        buttons: [{
            extend: "copy",
            className: "btn-sm prints",
            exportOptions:
                {
                    columns: ':visible'
                }
        },
        {
            extend: "csv",
            footer: true,
            title: "Report",
            className: "btn-sm prints",
            exportOptions:
                {
                    columns: ':visible'
                }
        },
        {
            extend: "pdf",
            title: "Report",
            className: "btn-sm prints",
            exportOptions:
                {
                    columns: ':visible'
                }
        },
        {
            extend: "print",
            className: "btn-sm prints",
            exportOptions:
                {
                    columns: ':visible'
                }
        },
        {
                extend:"colvis",
                className:"btn-sm prints"
        }
    ],
   "footerCallback": function(row, data, start, end, display) {
    var api = this.api();
    
    var intVal = function(i) {
        return typeof i === 'string' ?
            i.replace(/[\$,]/g, '') * 1 :
            typeof i === 'number' ?
            i : 0;
    };

    // Total over all pages for total_amount
    var totalAmountTotal = api
        .column(9)
        .data()
        .reduce(function(a, b) {
            return intVal(a) + intVal(b);
        }, 0);

    // Total over all pages for due_amount
    var dueTotal = api
        .column(10)
        .data()
        .reduce(function(a, b) {
            return intVal(a) + intVal(b);
        }, 0);

    // Update footer for total_amount
    $( api.column( 9 ).footer() ).html(
        totalAmountTotal.toFixed(2)
    );

    // Update footer for due_amount
    $( api.column( 10 ).footer() ).html(
        dueTotal.toFixed(2)
    );

    // Update colspan cell
    // $( api.column( 10 ).footer() ).html(
    //     "Total:"
    // );

    $( api.column( 12 ).footer() ).html(
        "Due Amount:"
    );
}
    }),

	$('.dataTables_filter').addClass('');  
	$('.dataTables_filter label').addClass('search__inner');  
	$('.dataTables_filter input').addClass('search__text');
	});
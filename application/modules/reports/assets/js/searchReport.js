function showinvoice(){
    'use strict';
    $('#hide_report').hide();
    $('#report_show').show();
    var csrf = $('#csrf_token').val();
    var geturl=$("#invoiceurl").val();
    var customer=$("#customer").val();
    var status=$("#status").val();
    var payment_status=$("#payment_status").val();
    var start_date=$("#start_date").val();
    var to_date=$("#to_date").val();
        $.ajax({
        type: "POST",
        url: geturl,
        data: {csrf_test_name:csrf, customer: customer, payment_status:payment_status, status: status, start_date: start_date, to_date: to_date},
        success: function(data) {
            $('#itemlist').html(data);
        } 
   });
   
   }
   var i = $("#checktable").val();
if(i>1){
    $(document).ready(function() {
        'use strict';
        $("#reportfooter").attr("hidden", false);
        $("#rexdatatable").DataTable({
                dom: "<'row m-0'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
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
                        title: "Data List",
                        className: "btn-sm prints",
                        exportOptions:
                        {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: "pdf",
                        title: "Data List",
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

    // Total over all pages for total amount
    var totalAmountTotal = api
        .column(9)
        .data()
        .reduce(function(a, b) {
            return intVal(a) + intVal(b);
        }, 0);

    // Total over all pages for paid amount
    var paidAmountTotal = api
        .column(10)
        .data()
        .reduce(function(a, b) {
            return intVal(a) + intVal(b);
        }, 0);
        var adv_amount = api
        .column(11)
        .data()
        .reduce(function(a, b) {
            return intVal(a) + intVal(b);
        }, 0);
var dueAmountTotal = api
        .column(12)
        .data()
        .reduce(function(a, b) {
            return intVal(a) + intVal(b);
        }, 0);
    // Calculate total due amount
    //var dueAmountTotal = totalAmountTotal - paidAmountTotal;

    // Update footer for total amount
    $( api.column( 9 ).footer() ).html(
        totalAmountTotal.toFixed(2)
    );

    // Update footer for paid amount
    $( api.column( 10 ).footer() ).html(
        paidAmountTotal.toFixed(2)
    );

    // Update footer for due amount
    $( api.column( 11 ).footer() ).html(
        adv_amount.toFixed(2)
    );
     $( api.column( 12 ).footer() ).html(
        dueAmountTotal.toFixed(2)
    );
}


            }),

        $('.dataTables_filter').addClass('');
        $('.dataTables_filter label').addClass('search__inner');
        $('.dataTables_filter input').addClass('search__text');
    });
}else{
    $("#reportfooter").attr("hidden", true);
}
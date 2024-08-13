"use strict";
$("#paymentmode").on("change", function() {
    var pmode = $(this).find(":selected").val();
    // Hide all mode details fields first
    $('#modedetails, #modedetailsupi, #modedetailsonline, #modedetailscheque, #modedetailscard').prop('hidden', true);
    if (pmode == "RTGS") {
        $('#modedetails').prop('hidden', false);
    } else if (pmode == "Cash Payment") {
        // No input field for Cash Payment
    } else if (pmode == "Online Payment") {
        $('#modedetailsonline').prop('hidden', false);
    } else if (pmode == "Card Payment") {
        $('#modedetailscard').prop('hidden', false);
    } else if (pmode == "Cheque") {
        $('#modedetailscheque').prop('hidden', false);
    } else if (pmode == "UPI") {
        $('#modedetailsupi').prop('hidden', false);
    } else if (pmode == "Bill to company") {
        // No input field for Bill to company
    } else {
        // Hide all mode details fields for other modes
        $('#modedetails, #modedetailsupi, #modedetailsonline, #modedetailscheque, #modedetailscard').prop('hidden', true);
    }
});
$("#previous").on("click", function() {
    $("#openregister").modal('hide');
    $('.modal').modal('hide');
});
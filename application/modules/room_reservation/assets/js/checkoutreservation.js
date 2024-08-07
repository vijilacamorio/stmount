"use strict";
$('.print-btn').on('click', function() { // select print button with class "print," then on click run callback function
    $.print(".print-content"); // inside callback function the section with class "content" will be printed
});
"use strict";
$("#chroomno").on("click", function() {
    var chform = $("#chroomno").val();
    if (chform) {
        $("#go").attr("disabled", false);
    } else {
        $("#go").attr("disabled", true);
    }
});

function checkoutinfo() {
    "use strict";
    $("#checkoutdetail").attr("hidden", false);
    var chroomno = $("#chroomno").val();
    if (chroomno == null) {
        $("#cmsg").attr("hidden", false);
        $("#cmsg").text("Checkout Room No is required");
        return false;
    } else {
        $("#cmsg").attr("hidden", true);
    }
    var csrf = $('#csrf_token').val();
    var myurl = baseurl + "room_reservation/room_reservation/checkoutall/" + chroomno;
    $.ajax({
        url: myurl,
        type: "POST",
        data: {
            csrf_test_name: csrf,
        },
        success: function(data) {
            $("#checkoutdetail").html(data);
        }
    });
}
"use strict";
$("#disreason").on("change", function() {
    var disreason = $("#disreason").find(":selected").val();
    if (disreason) {
        $("#percent").attr("disabled", false);
        $("#amount").attr("disabled", false);
    } else {
        $("#percent").attr("disabled", true);
        $("#amount").attr("disabled", true);
        $("#percent,#amount").val('');
        $("#percent,#amount").trigger('change');
    }
});
var roomrent = parseFloat($("#allroomrent").text());
var roomrentandtax = parseFloat($("#oldallroomrentandtax").text());
var allbpccharge = parseFloat($("#allbpccharge").text());
var alladvanceamount = parseFloat($("#alladvanceamount").text());
var poolbill = parseFloat($("#poolbill").text());
var hallbill = parseFloat($("#hallbill").text());
var restbill = parseFloat($("#restbill").text());
var parkingbill = parseFloat($("#parkingbill").text());

if (isNaN(poolbill)) {
    poolbill = 0;
}
if (isNaN(hallbill)) {
    hallbill = 0;
}
if (isNaN(restbill)) {
    restbill = 0;
}
if (isNaN(parkingbill)) {
    parkingbill = 0;
}
"use strict";
$("#percent").on("keyup change", function() {
    debugger;
    var percent = parseFloat($("#percent").val());
    var additionalcharge = parseFloat($("#additionalcharge").val());
   
    var complementaryamount = parseFloat($("#allcomplementarycharge").text());
    if (isNaN(percent)) {
        percent = 0;
    }
    if (isNaN(additionalcharge)) {
        additionalcharge = 0;
    }
   
    if (isNaN(complementaryamount)) {
        complementaryamount = 0;
    }
    if (percent >= 0 && percent <= 100) {
	
        var totalrentandtax = roomrentandtax - (roomrentandtax * percent) / 100;
        var payableamount = totalrentandtax - alladvanceamount;
       
       $("#amount").val((roomrent * percent) / 100);
        $("#disamount").text((roomrent * percent) / 100);
        var roomrent = parseFloat($('#roomrent').val()); // Assuming roomrent is fetched from an input field
var percent1 = parseFloat($('#percent').val()); // Assuming percent is fetched from an input field

var rentafterdiscount1 = roomrent - ((roomrent * percent1) / 100);

if (!isNaN(rentafterdiscount1)) {
    $('#rentafterdiscount').val(rentafterdiscount1);
}
         // $('#rentafterdiscount').val(parseFloat(roomrent-((roomrent * percent) / 100)));
    $("#totalroomrentamount").text(allbpccharge+parseFloat(rentafterdiscount1));
        $("#allroomrentandtax").text(totalrentandtax.toFixed(2));
        $("#payableamount").text(totalrentandtax.toFixed(2));
        $("#netpayableamount").text(totalrentandtax.toFixed(2));
        $("#payableamt").text(totalrentandtax.toFixed(2));
        $("#balance").text(totalrentandtax.toFixed(2));
        $("#cash_0").trigger('change');
    }
});


"use strict";
$("#amount").on("keyup change", function() {
      debugger;
    var amount = parseFloat($("#amount").val());
    var additionalcharge = parseFloat($("#additionalcharge").val());
    var cgst = additionalcharge * 0.09;
    var sgst = additionalcharge * 0.09;
    additionalcharge = cgst + sgst + additionalcharge;
   
    if (isNaN(amount)) {
        amount = 0;
    }
    if (isNaN(additionalcharge)) {
        additionalcharge = 0;
    }
   
    var totalrentandtax = allbpccharge + roomrent - amount;
    var CGST = (totalrentandtax * 6) / 100;
    var SGST = (totalrentandtax * 6) / 100;
    var final = totalrentandtax + CGST + SGST;
    var payableamount = final - alladvanceamount   +additionalcharge ;
    var payableamt = payableamount + poolbill + restbill + hallbill + parkingbill ;
     var rentandextras=(allbpccharge+roomrent);
    if (amount >= 0 && amount <= roomrent) {
        $("#percent").val((amount * 100) / roomrent);
        $("#disamount").text(amount);
        $("#totalroomrentamount").text(final.toFixed(2));
        $('#rentafterdiscount').val(final.toFixed(2));
        $("#allroomrentandtax").text(final.toFixed(2));
         $("#sub").text('₹'+totalrentandtax.toFixed(2));
        $("#payableamount").text((final-alladvanceamount).toFixed(2));
        $("#netpayableamount").text(payableamount.toFixed(2));
        $("#payableamt").text(payableamount.toFixed(2));
        $("#balance").text(payableamount.toFixed(2));
        $("#discount_cgst").val(CGST);
        $("#discount_sgst").val(SGST);
         $(".discount_cgst").text(CGST);
        $(".discount_sgst").text(SGST);
        $("#cash_0").trigger('change');
    }
});


var netpayableamount = parseFloat($("#netpayableamount").text());
var payableamt = parseFloat($("#payableamt").text());
$("#balance").text(payableamt);
"use strict";
$("#additionalcharge").on("keyup change", function() {
    debugger;
    var alladvanceamount = parseFloat($("#alladvanceamount").text());
    var additionalcharge = parseFloat($("#additionalcharge").val());
       var additional_reason = $('#additional_reason').find(":selected").val();
     var additional_remarks = $('#additional_remarks').val();
    var cgst = additionalcharge * 0.09;
    var sgst = additionalcharge * 0.09;
    additionalcharge = cgst + sgst + additionalcharge;
    var creditamount = parseFloat($("#creditamount").val());
    var amount = parseFloat($("#amount").val());
    var complemetaryamt = parseFloat($("#complementaryamount").val());
    var payableamount = parseFloat($("#payableamount").text());
   if (isNaN(additionalcharge)) {
        additionalcharge = 0;
        $('.additional_table').hide();
    }else{
          $("#adv_amt").html('₹'+additionalcharge);
          $('.additional_table').show(); 
    }
    if (isNaN(creditamount)) {
        creditamount = 0;
    }
    if (isNaN(complemetaryamt)) {
        complemetaryamt = 0;
    }
      if (isNaN(amount)) {
        amount = '0.00';
    }
        var totalrentandtax = allbpccharge + roomrent - amount;
        var CGST = (totalrentandtax * 6) / 100;
        var SGST = (totalrentandtax * 6) / 100;
        var final = totalrentandtax + CGST + SGST + additionalcharge -alladvanceamount;
        var payableamount = totalrentandtax - alladvanceamount  - amount + additionalcharge;
        var payableamt = payableamount + poolbill + restbill + hallbill + parkingbill + additionalcharge;
        var rentandextras=(allbpccharge+roomrent);
           $("#ad_rsn").text(additional_reason);
            $("#ad_rmk").text(additional_remarks);
            
        $("#percent").val((amount * 100) / roomrent);
        $("#disamount").text(amount);
        //$("#totalroomrentamount").text(final.toFixed(2));
       // $('#rentafterdiscount').val(final.toFixed(2));
      //  $("#allroomrentandtax").text(final.toFixed(2));
       // $("#payableamount").text(final.toFixed(2));
        $("#netpayableamount").text(final.toFixed(2));
        $("#payableamt").text(final.toFixed(2));
        $("#balance").text(final.toFixed(2));
       
        $("#discount_cgst").val(CGST);
        $("#discount_sgst").val(SGST);
         $(".discount_cgst").text(CGST);
        $(".discount_sgst").text(SGST);
        $("#additional_cgst").val(cgst);
        $("#additional_sgst").val(sgst);
        $("#cash_0").trigger('change');
});
$("#creditamount").on("keyup change", function() {
    var creditamount = parseFloat($("#creditamount").val());
    var additionalcharge = parseFloat($("#additionalcharge").val());
    var complemetaryamt = parseFloat($("#complementaryamount").val());
    if (isNaN(creditamount)) {
        creditamount = 0;
    }
    if (isNaN(additionalcharge)) {
        additionalcharge = 0;
    }
    if (isNaN(complemetaryamt)) {
        complemetaryamt = 0;
    }
    if (creditamount >= 0) {
        $("#payableamt").text(payableamt - creditamount + additionalcharge - complemetaryamt);
        $("#balance").text(payableamt - creditamount + additionalcharge - complemetaryamt);
        $("#creditamt").text(creditamount);
        $("#crmsg").attr("hidden", true);
        $("#cash_0").trigger('change');
        if (creditamount > 0) {
            var newTr = $("<tr id='paymentmethod_-1' hidden>");
            var tr = "";
            tr +=
                '<td class="res-padding" id="pmode_-1"></td><td class="res-allign-padding" id="pamount_-1">Amount</td>';
            newTr.append(tr);
            $("table.paymentdetails").append(newTr);
            $("#paymentmethod_-1").prop("hidden", false);
            $('#pmode_-1').text("On Credited");
            $('#pamount_-1').text(creditamount);
        } else {
            $("#paymentmethod_-1").prop("hidden", true);
        }
    } else {
        $("#crmsg").attr("hidden", false);
        $("#crmsg").text("Field Required");
    }
    $("#balance").trigger("change");
});
"use strict";
$("#credit").on("change", function() {
    var credit = $("#credit").find(":selected").val();
    if (credit) {
        $("#creditamount").attr("disabled", false);
        $("#crmsg").attr("hidden", false);
        $("#crmsg").text("Field Required");
    } else {
        $("#crmsg").attr("hidden", true);
        var creditamount = parseFloat($("#creditamount").val());
        var payableamt = parseFloat($("#payableamt").text());
        $("#payableamt").text(payableamt + creditamount);
        $("#balance").text(payableamt + creditamount);
        $("#creditamount").val(0);
        $("#creditamt").text(0);
        $("#creditamount").attr("disabled", true);
        $("#creditamount").trigger('change');
        $("#cash_0").trigger('change');
    }
});
"use strict";
$("#complementaryamount").on("keyup change", function() {
    var creditamount = parseFloat($("#creditamount").val());
    var additionalcharge = parseFloat($("#additionalcharge").val());
    var complemetaryamt = parseFloat($("#complementaryamount").val());
    if (isNaN(creditamount)) {
        creditamount = 0;
    }
    if (isNaN(additionalcharge)) {
        additionalcharge = 0;
    }
    if (isNaN(complemetaryamt)) {
        complemetaryamt = 0;
    }
    if (complemetaryamt >= 0) {
        $("#payableamt").text(payableamt - creditamount + additionalcharge - complemetaryamt);
        $("#balance").text(payableamt - creditamount + additionalcharge - complemetaryamt);
        $("#complemetaryamt").text(complemetaryamt);
        $("#crmsg").attr("hidden", true);
        $("#cash_0").trigger('change');
    } else {
        $("#crmsg").attr("hidden", false);
        $("#crmsg").text("Field Required");
    }
    $("#balance").trigger("change");
});
"use strict";
$("#complementary").on("change", function() {
    var complementary = $("#complementary").find(":selected").val();
    if (complementary) {
        $("#complementaryamount").attr("disabled", false);
        $("#crmsg1").attr("hidden", false);
        $("#crmsg1").text("Field Required");
    } else {
        $("#crmsg1").attr("hidden", true);
        var complementaryamount = parseFloat($("#complementaryamount").val());
        var payableamt = parseFloat($("#payableamt").text());
        $("#payableamt").text(payableamt + complementaryamount);
        $("#balance").text(payableamt + complementaryamount);
        $("#complementaryamount").val(0);
        $("#complemetaryamt").text(0);
        $("#complementaryamount").attr("disabled", true);
        $("#complementaryamount").trigger("change");
    }
});

"use strict";
function toastrErrorMsg(r) {
    setTimeout(function() {
        toastr.options = {
            closeButton: true,
            progressBar: true,
            showMethod: 'slideDown',
            timeOut: 1500,
        };
        toastr.error(r);
    }, 1000);
}
"use strict";
function toastrSuccessMsg(r) {
    setTimeout(function() {
        toastr.options = {
            closeButton: true,
            progressBar: true,
            showMethod: 'slideDown',
            timeOut: 1500,
        };
        toastr.success(r);
    }, 1000);
}

function checkout() {
    "use strict";
    var finyear = $("#finyear").val();
    if (finyear <= 0) {
        swal({
            title: "Failed",
            text: "Please Create Financial Year First",
            type: "error",
            confirmButtonColor: "#28a745",
            confirmButtonText: "Ok",
            closeOnConfirm: true
        });
        return false;
    }
    debugger;
    var bookedid = $("#bookedid").val();
    var collectedamt = $("#collectedamt").text();
    var refunddamt = $("#refunddamt").text();
    var roomRent = $("#allroomrent").text();
    var creditamount = $("#creditamount").val();
    var disamount = $("#disamount").text();
   var Additionalcgst = $("#additional_cgst").val();
    var Additionalsgst = $("#additional_sgst").val();
     var rentafterdiscount = $("#rentafterdiscount").val();
    var allcomplementarycharge = $("#allcomplementarycharge").text();
    var allbpccharge = $("#allbpccharge").text();
    var additionalcharge = $("#additionalcharge").val();
    var additional_reason = $('#additional_reason').find(":selected").val();
     var additional_remarks = $('#additional_remarks').text();
    var specialdis = $("#complementaryamount").val();
    var poolbill = $("#poolbill").text();
    // var bc = $("#bedcharge_rate").text();
    // var pc = $("#personcharge_rate").text();
    // var cc = $("#childcharge_rate").text();
    var restbill = $("#restbill").text();
    var poolid = $("#poolid").val();
    var hallid = $("#hallid").val();
    
    var discount_cgst1 = $("#discount_cgst").val();
    var discount_sgst1 = $("#discount_sgst").val();
    
    var bc = $("#bed_amount").val();
    var pc = $("#person_amount").val();
    var cc = $("#child_amount").val();
    //

    var parking_id = $("#parking_id").text();
    var parkingbill = $("#parkingbill").text();
    var orderid = $("#orderid").val();
    var nod = $("#nod").text();
    var scharge = $("#scharge").text();
    var tax = $("#total_tax").text();
  var payableamt = $("#payableamt").text();
 var alladvanceamount = $("#alladvanceamount").text();
    var pl = $("table.payment tbody tr").length;
    var paymentmode = "";
    var paymentamount = "";
    //var cardno = "";
 
        var cheque_no = $("#cheque_no").val();
    var cheque_date = $("#cheque_date").val();
    var online_referenceno = $("#online_referenceno").val();
    var rtgs_referenceno = $("#rtgs_referenceno").val();
    var upi_referenceno = $("#upi_referenceno").val();
    var cardno = $("#cardno").val();
    var expirydate = $("#expirydate").val();
    var rtgs_referenceno = $("#ccv").val();
    var bankname = $("#bankname").val();
    var accountnumber = $("#accountnumber").val();
           
    
    
    
    
    
    
    for (var i = 0; i < pl; i++) {
        paymentmode += $("#paymentmode_" + i).find(":selected").val() + ",";
        paymentamount += $("#cash_" + i).val() + ",";
        // bankname += $("#bankname_" + i).find(":selected").val() + ",";
        cardno += $("#cardno_" + i).val() + ",";
         bankname += $("#bankname_" + i).val() + ",";
         accountnumber += $("#accountnumber_" + i).val() + ",";
        
        
        cheque_no += $("#cheque_no_" + i).val() + ",";
        cheque_date += $("#cheque_date_" + i).val() + ",";
        online_referenceno += $("#online_referenceno_" + i).val() + ",";
        rtgs_referenceno += $("#rtgs_referenceno_" + i).val() + ",";
        upi_referenceno += $("#upi_referenceno_" + i).val() + ",";
        expirydate += $("#expirydate_" + i).val() + ",";
        
        
        
        
        $('.selectpicker').selectpicker('refresh');
        $("#paymentmode_" + i).parent().removeClass("is-invalid");
        if ($("#paymentmode_" + i).find(":selected").val() == "" & parseFloat($("#balance").text()) > 0) {
            $("#paymentmode_" + i).parent().addClass("is-invalid");
            $('.selectpicker').selectpicker('refresh');
            return false;
        }
        $("#cash_" + i).removeClass("is-invalid");
        if ($("#cash_" + i).val() == "" & parseFloat($("#balance").text()) > 0) {
            $("#cash_" + i).addClass("is-invalid");
            return false;
        }
        $("#cardno_" + i).removeClass("is-invalid");
        $("#bankname_" + i).parent().removeClass("is-invalid");
        if ($("#paymentmode_" + i).find(":selected").val() != 'Cash Payment' & $("#cardno_" + i).val() == "" &
            parseFloat($("#balance").text()) > 0) {
            $("#cardno_" + i).addClass("is-invalid");
            return false;
        }
        $("#bankname_" + i).parent().removeClass("is-invalid");
        if ($("#paymentmode_" + i).find(":selected").val() == 'Bank Payment' & $("#bankname_" + i).find(":selected")
            .val() == "") {
            $("#bankname_" + i).parent().addClass("is-invalid");
            return false;
        }
        
        
        
        
        
        $("#cheque_no_" + i).parent().removeClass("is-invalid");
        if ($("#paymentmode_" + i).find(":selected").val() == 'Cheque NO' & $("#cheque_no_" + i).find(":selected")
            .val() == "") {
            $("#cheque_no_" + i).parent().addClass("is-invalid");
            return false;
        }


        $("#cheque_date_" + i).parent().removeClass("is-invalid");
        if ($("#paymentmode_" + i).find(":selected").val() == 'Cheque Date' & $("#cheque_date_" + i).find(":selected")
            .val() == "") {
            $("#cheque_date_" + i).parent().addClass("is-invalid");
            return false;
        }

        $("#expirydate_" + i).parent().removeClass("is-invalid");
        if ($("#paymentmode_" + i).find(":selected").val() == 'EXP DATE' & $("#expirydate_" + i).find(":selected")
            .val() == "") {
            $("#expirydate_" + i).parent().addClass("is-invalid");
            return false;
        }




        $("#online_referenceno_" + i).parent().removeClass("is-invalid");
        if ($("#paymentmode_" + i).find(":selected").val() == 'Online Ref' & $("#online_referenceno_" + i).find(":selected")
            .val() == "") {
            $("#online_referenceno_" + i).parent().addClass("is-invalid");
            return false;
        }



        $("#rtgs_referenceno_" + i).parent().removeClass("is-invalid");
        if ($("#paymentmode_" + i).find(":selected").val() == 'RTGS' & $("#rtgs_referenceno_" + i).find(":selected")
            .val() == "") {
            $("#rtgs_referenceno_" + i).parent().addClass("is-invalid");
            return false;
        }


        $("#upi_referenceno_" + i).parent().removeClass("is-invalid");
        if ($("#paymentmode_" + i).find(":selected").val() == 'UPI' & $("#upi_referenceno_" + i).find(":selected")
            .val() == "") {
            $("#upi_referenceno_" + i).parent().addClass("is-invalid");
            return false;
        }
        
        
        
        
        
        
        
        
    }
    paymentmode = paymentmode.replace(/,\s*$/, "");
    paymentamount = paymentamount.replace(/,\s*$/, "");
    // bankname = bankname.replace(/,\s*$/, "");
    cardno = cardno.replace(/,\s*$/, "");
      bankname = bankname.replace(/,\s*$/, "");
    accountnumber = accountnumber.replace(/,\s*$/, "");
    cheque_no = cheque_no.replace(/,\s*$/, "");
    cheque_date = cheque_date.replace(/,\s*$/, "");
    expirydate = expirydate.replace(/,\s*$/, "");
    online_referenceno = online_referenceno.replace(/,\s*$/, "");
    rtgs_referenceno = rtgs_referenceno.replace(/,\s*$/, "");
    upi_referenceno = upi_referenceno.replace(/,\s*$/, "");
    cardno = cardno.replace(/,\s*$/, "");
    var csrf = $('#csrf_token').val();
    var myurl = baseurl + "room_reservation/room_reservation/submitcheckout/" + bookedid;
    $.ajax({
        url: myurl,
        type: "POST",
        data: {
            csrf_test_name: csrf,
            collectedamt: collectedamt,
            creditamount: creditamount,
            refunddamt: refunddamt,
            additional_reason: additional_reason,
            disamount: disamount,
            additional_remarks:additional_remarks,
            Additionalcgst: Additionalcgst,
            Additionalsgst: Additionalsgst,
            discount_cgst: discount_cgst1,
            discount_sgst: discount_sgst1,
            allcomplementarycharge: allcomplementarycharge,
            allbpccharge: allbpccharge,
            additionalcharge: additionalcharge,
            specialdis: specialdis,
            poolbill: poolbill,
            restbill: restbill,
            poolid: poolid,
            hallid: hallid,
            roomRent:roomRent,
            rent: rentafterdiscount,
            bc:bc,
            pc:pc,
            cc:cc,
            parking_id: parking_id,
            parkingbill: parkingbill,
            orderid: orderid,
            nod: nod,
            taxamount: tax,
            scharge: scharge,
          payableamt: payableamt,
            alladvanceamount: alladvanceamount,
           paymentmode: paymentmode,
         cardno: cardno,
         accountnumber:accountnumber,
         bankname : bankname,
             cheque_no: cheque_no,
             rtgs_referenceno:rtgs_referenceno,
            cheque_date: cheque_date,
            expirydate: expirydate,
            online_referenceno: online_referenceno,
            upi_referenceno: upi_referenceno,
            paymentamount: paymentamount,
          
        },
        success: function(data) {
            if (data.substr(4, 1) === "S") {
                $("#checkoutdetail").attr("hidden", true);
                $("#go").attr("disabled", true);
                var val = $('#chroomno').val();
                var id = val.toString().split(",");
                $('select.testselect2')[0].sumo.unSelectAll();
                for (var i = 0; i < id.length; i++) {
                    var index = $("#chroomno option[value='" + id[i] + "']").index();
                    $('select.testselect2')[0].sumo.remove(index);
                }
                $(".print-btn").trigger('click');
                toastrSuccessMsg(data);
                $(".sidebar-mini").removeClass('sidebar-collapse');
            } else
                toastrErrorMsg(data);
            setTimeout(function() {}, 1000);
        }
    });
}
var inbknumber = $("#inbknumber").text();
var inname = $("#inname").text();
var inemail = $("#inemail").text();
var inmobile = $("#inmobile").text();
var currency = $("#currency").val();
var position = $("#position").val();
$("#invbknumber").text("Order #" + inbknumber + "");
inmobile == '' ? $("#invmobiletitle").attr("hidden", true) : $("#invmobiletitle").attr("hidden", false);
inemail == '' ? $("#invemailtitle").attr("hidden", true) : $("#invemailtitle").attr("hidden", false);
$("#invname").text(inname);
$("#invmobile").text(inmobile);
$("#invemail").text(inemail);
"use strict";
$("#amount,#percent,#additionalcharge,#additional_reason,#creditamount,#complementaryamount").on("keyup change", function() {
    var invdis = $("#disamount").text();
    var invadc = $("#additionalcharge").val();
    var invcredit = $("#creditamount").val();
    var add_reason = $('#additional_reason').find(":selected").val();
    var invsdis = $("#complementaryamount").val();
    var disreason = $("#disreason").find(":selected").text();
    var creditreason = $("#credit").find(":selected").text();
    var complementaryreason = $("#complementary").find(":selected").text();
    var inpay = $("#payableamt").text();
    if (invdis > 0) {
        $("#invdis").prop("hidden", false);
        $("#indistitle").text("Discount in " + disreason);
        $("#indisamt").text((position==1?currency:'')+invdis+(position==2?currency:''));
        $("#inpayableamt").text((position==1?currency:'')+inpay+(position==2?currency:''));
    } else {
        $("#invdis").prop("hidden", true);
        $("#indisamt").text(0);
        $("#inpayableamt").text((position==1?currency:'')+inpay+(position==2?currency:''));
    }
    if (invadc > 0) {
        $("#invadc").prop("hidden", false);
        $("#inadcamt").text((position==1?currency:'')+invadc+(position==2?currency:''));
        $("#inpayableamt").text((position==1?currency:'')+inpay+(position==2?currency:''));
    } else {
        $("#invadc").prop("hidden", true);
        $("#inadcamt").text(0);
        $("#inpayableamt").text((position==1?currency:'')+inpay+(position==2?currency:''));
    }
    if (add_reason) {
        $("#add_reason").prop("hidden", false);
        $("#add_reason1").text(add_reason);
    } else {
        $("#add_reason").prop("hidden", true);
    }
    if (invsdis > 0) {
        $("#invsdis").prop("hidden", false);
        $("#insdis").text((position==1?currency:'')+invsdis+(position==2?currency:''));
        $("#invsdistitle").text("Special Discount in " + complementaryreason);
        $("#inpayableamt").text((position==1?currency:'')+inpay+(position==2?currency:''));
    } else {
        $("#invsdis").prop("hidden", true);
        $("#insdis").text(0);
        $("#inpayableamt").text((position==1?currency:'')+inpay+(position==2?currency:''));
    }
    if (invcredit > 0) {
        var inpayableamt = parseFloat(inpay) - parseFloat(invcredit);
        $("#invcredit").prop("hidden", false);
        $("#increditamt").text((position==1?currency:'')+invcredit+(position==2?currency:''));
        $("#creditreason").text("Credit in " + creditreason);
        $("#inpayableamt").text((position==1?currency:'')+inpayableamt+(position==2?currency:''));
        $("#ipaid").removeClass("color-red");
        $("#ipaid").text("Credit");
    } else {
        $("#invcredit").prop("hidden", true);
        $("#increditamt").text(0);
        $("#inpayableamt").text((position==1?currency:'')+inpay+(position==2?currency:''));
    }
});
if (poolbill > 0) {
    $("#poolamttitle").prop("hidden", false);
    $("#poolamt").text((position==1?currency:'')+poolbill+(position==2?currency:''));
} else {
    $("#poolamttitle").prop("hidden", true);
    $("#poolamt").text(0);
}
if (restbill > 0) {
    $("#restbillamttitle").prop("hidden", false);
    $("#restbillamt").text((position==1?currency:'')+restbill+(position==2?currency:''));
} else {
    $("#restbillamttitle").prop("hidden", true);
    $("#restbillamt").text(0);
}
if (hallbill > 0) {
    $("#hallbillamttitle").prop("hidden", false);
    $("#hallbillamt").text((position==1?currency:'')+hallbill+(position==2?currency:''));
} else {
    $("#hallbillamttitle").prop("hidden", true);
    $("#hallbillamt").text(0);
}
if (parkingbill > 0) {
    $("#parkingbillamttitle").prop("hidden", false);
    $("#parkingbillamt").text((position==1?currency:'')+parkingbill+(position==2?currency:''));
} else {
    $("#parkingbillamttitle").prop("hidden", true);
    $("#parkingbillamt").text(0);
}
var payinfo = $("#paymentinfo").html();
var bankinfo = $("#bankinfo").html();
var i = 1;
"use strict";
$("#multipayment").on("click", function () {
  var newRow = $("<tr>");
  var td = "";
  td +=
    '<td class="border-0 pl-0"><div class="form-floating with-icon"><select class="selectpicker form-select" data-live-search="true" data-width="100%" onchange="paymode(' +
    i +
    ')" id="paymentmode_' +
    i +
    '">' +
    +"" +
    payinfo +
    '</select><label for="paymentmode">Payment Mode</label><i class="fas fa-credit-card"></i></div><div class="row mt-2" id="modedetails_' +
    i +
    '" hidden>' +
    '<div class="col"><input type="text" id="cardno_' +
    i +
    '" class="form-control form-control-sm"></div><div class="col"><input type="password" id="cvv_' +
        i +
        '" class="form-control form-control-sm" placeholder="CVV" style="display:none;"></div></div><div class="col"><input type="text" id="rtgs_referenceno_' +
        i +
        '" class="form-control form-control-sm" placeholder="RTGS Reference Number" style="display:none;"></div>' +
        '<div class="col"><input type="text" id="upi_referenceno_' +
        i +
        '" class="form-control form-control-sm" placeholder="UPI Reference Number" style="display:none;"></div>' +
 '<div class="col"><input type="text" id="bankname_' +
        i +
        '" class="form-control form-control-sm" placeholder="Bank Name" style="display:none;"><input type="text" id="accountnumber_' +
        i +
        '" class="form-control form-control-sm" placeholder="Account Number" style="display:none;"></div>' +
        '<div class="col"><input type="number" id="cheque_no_' +
        i +
        '" class="form-control form-control-sm" placeholder="Cheque Number" style="display:none;"></div><div class="col"><input type="date" id="cheque_date_' +
        i +
        '" class="form-control form-control-sm" placeholder="CVV" style="display:none;"></div><div class="col"><input type="password" id="cvv_' +
        i +
        '" class="form-control form-control-sm" placeholder="CVV" style="display:none;"></div>' +
        '</div></td>';
  td +=
    '<th class="border-0"><input type="text" disabled class="form-control" id="cash_' +
    i +
    '" placeholder="Amount" value=""></th>';
  td +=
    '<td class="border-0 pr-0 text-right"><button type="button" onclick="delrow(' +
    i +
    ')" id="del' +
    i +
    '" class="btn btn-danger-soft del' +
    i +
    '"><i class="far fa-times-circle"></i></button></td>';
  newRow.append(td);
  $("table.payment").append(newRow);
  $(".selectpicker").selectpicker("refresh");
  i++;
});

var paidtotal = 0;

function delrow(r) {
    "use strict";
    var tc = $("table.payment tbody tr").length;
    if (1 == tc) {
        swal({
            title: "Failed",
            text: "There only one row you can't delete.",
            type: "error",
            confirmButtonColor: "#28a745",
            confirmButtonText: "Ok",
            closeOnConfirm: true
        });
        return false;
    } else if (tc == (r + 1)) {
        $('.del' + r).closest("tr").remove();
        $("#cash_0").trigger('change');
        $("#paymentmethod_" + tc + "").closest("tr").remove();
        i--;
    } else {
        var k = r;
        $('.del' + k).closest("tr").remove();
        for (k = r + 1; k < tc; k++) {
            $("table.payment tbody > tr > td select[id=paymentmode_" + k + "]").attr({
                onchange: "paymode(" + r + ")",
                id: "paymentmode_" + r + ""
            });
            $("table.payment tbody > tr > td [id=modedetails_" + k + "]").attr({
                id: "modedetails_" + r + ""
            });
            $("table.payment tbody > tr > td [id=cardno_" + k + "]").attr({
                id: "cardno_" + r + ""
            });
            $("table.payment tbody > tr > td [id=recdate_" + k + "]").attr({
                id: "recdate_" + r + ""
            });
            $("table.payment tbody > tr > td [id=bankname_" + k + "]").attr({
                id: "bankname_" + r + ""
            });
            $("table.payment tbody > tr > th [id=cash_" + k + "]").attr({
                id: "cash_" + r + ""
            });
            $("table.payment tbody > tr > td button[id=del" + k + "]").attr({
                onclick: "delrow(" + r + ")",
                id: "del" + r + "",
                class: "btn btn-danger-soft del" + r + ""
            });
            r++;
        }
        $("#paymentmode_0").trigger('change');
        $("#cash_0").trigger('change');
        $("#paymentmethod_" + r + "").closest("tr").remove();
        i--;
    }
}

function paymode(l) {
    "use strict";
   $("#paymentmode_" + l).on("change", function () {
      debugger;
    var pmode = $("#paymentmode_" + l)
      .find(":selected")
      .val();
if (pmode == "Card Payment") {
  $("#bankname_" + l).selectpicker("hide");
  $("#recdate_" + l).prop("hidden", false).attr({
    pattern: "^(0[1-9]|1[0-2])\/(\\d{2})$", // Regular expression for MM/YY format
    maxlength: 7, // Maximum length including slash
    placeholder: "mm/yyyy" // Placeholder for expiry date
  }).on("input", function() {
    // Remove non-numeric characters and any extra characters
    $(this).val(function(index, value) {
      return value.replace(/\D/g, "").slice(0, 6).replace(/(\d{2})(\d{2})/, "$1/$2");
    });
  }).addClass("datefilter2");
  
  $("#rtgs_referenceno_" + l).prop("style", "display:none;");
  $("#upi_referenceno_" + l).prop("style", "display:none;");
 $("#bankname_" + l).prop("style", "display:none;");
       $("#accountnumber_" + l).prop("style", "display:none;");
       $('#banknamediv').prop("hidden", true);
        $('#accountnumberdiv').prop("hidden", true);
  $("#cardno_" + l).prop("hidden", false).attr({
    pattern: "[0-9]{16}", // Restricting to exactly 16 digits
    maxlength: 16,
    placeholder: "Card Number" // Placeholder for card number
  }).on("input", function() {
    // Remove non-numeric characters from input
    $(this).val(function(index, value) {
      return value.replace(/\D/g, "");
    });
  });
  $("#cheque_no_" + l).prop("style", "display:none;");
  $("#cheque_date_" + l).prop("style", "display:none;");
  $("#cvv_" + l).prop("style", "display:block;").attr({
       pattern: "[0-9]{3}",
    maxlength: 3 // Restricting CVV to three digits
  });
  $("#modedetails_" + l).prop("hidden", false);
  $("#cash_" + l).prop("disabled", false);
  var balance = $("#balance").text();
  $("#cash_" + l).val(balance);
  $("#cash_" + l).trigger("change");
}
 else if (pmode == "Bank Payment") {
      $("#bankname_" + l + "").selectpicker("hide");
        $("#bankname_" + l).prop("style", "display:block;");
       $("#accountnumber_" + l).prop("style", "display:block;");
       $('#banknamediv').prop("hidden", false);
        $('#accountnumberdiv').prop("hidden", false);
    
      $("#modedetails_" + l + "").prop("hidden", false);
      $("#recdate_" + l + "").prop("hidden", true);
        $("#cardno_" + l + "").prop("hidden", true);
         $("#cvv_" + l + "").prop("hidden", true);
        $("#rtgs_referenceno_" + l).prop("style", "display:none;");
          $("#upi_referenceno_" + l).prop("style", "display:none;");
         $("#online_referenceno_" + l).prop("style", "display:block;");
      $("#cash_" + l + "").prop("disabled", false);
      var balance = $("#balance").text();
      $("#cash_" + l + "").val(balance);
      $("#cash_" + l + "").trigger("change");
  
    } else if (pmode == "RTGS") {
      $("#bankname_" + l + "").selectpicker("hide");
     $("#bankname_" + l).prop("style", "display:none;");
       $("#accountnumber_" + l).prop("style", "display:none;");
       $('#banknamediv').prop("hidden", true);
        $('#accountnumberdiv').prop("hidden", true);
        
       $("#upi_referenceno_" + l).prop("style", "display:none;");
      $("#modedetails_" + l + "").prop("hidden", false);
      $("#recdate_" + l + "").prop("hidden", true);
        $("#cardno_" + l + "").prop("hidden", true);
      $("#cash_" + l + "").prop("disabled", false);
      var balance = $("#balance").text();
      $("#cash_" + l + "").val(balance);
      $("#cash_" + l + "").trigger("change");
  
    }
    else if (pmode == "Bill to company") {
          
      $("#bankname_" + l + "").selectpicker("hide");
      $("#bankname_" + l).prop("style", "display:none;");
       $("#accountnumber_" + l).prop("style", "display:none;");
       $('#banknamediv').prop("hidden", true);
        $('#accountnumberdiv').prop("hidden", true);
      $("#modedetails_" + l + "").prop("hidden", false);
  $("#recdate_" + l + "").prop("hidden", true);
        $("#cardno_" + l + "").prop("hidden", true);
         $("#cheque_no_" + l + "").prop("hidden", true);
          $("#cheque_date_" + l + "").prop("hidden", true);
          $("#cvv_" + l + "").prop("hidden", true);
      $("#cash_" + l + "").prop("disabled", false);
       // $("#rtgs_referenceno_" + l).prop("style", "display:block;");
      var balance = $("#balance").text();
      $("#cash_" + l + "").val(balance);
      $("#cash_" + l + "").trigger("change");

    }
    else if (pmode == "UPI") {
     
        $("#upi_referenceno_" + l).prop("style", "display:block;");
     
  $("#bankname_" + l + "").selectpicker("hide");
  $("#bankname_" + l).prop("style", "display:none;");
       $("#accountnumber_" + l).prop("style", "display:none;");
       $('#banknamediv').prop("hidden", true);
        $('#accountnumberdiv').prop("hidden", true);
      $("#modedetails_" + l + "").prop("hidden", false);
       $("#recdate_" + l + "").prop("hidden", true);
        $("#cardno_" + l + "").prop("hidden", true);
         $("#cheque_no_" + l + "").prop("hidden", true);
          $("#cheque_date_" + l + "").prop("hidden", true);
          $("#cvv_" + l + "").prop("hidden", true);
      $("#cash_" + l + "").prop("disabled", false);
     
      var balance = $("#balance").text();
      $("#cash_" + l + "").val(balance);
      $("#cash_" + l + "").trigger("change");
    }else if (pmode == "Online Payment") {
      $("#bankname_" + l + "").selectpicker("hide");
      $("#modedetails_" + l + "").prop("hidden", false);
     $("#recdate_" + l + "").prop("hidden", true);
        $("#cardno_" + l + "").prop("hidden", true);
         $("#cheque_no_" + l + "").prop("hidden", true);
          $("#cheque_date_" + l + "").prop("hidden", true);
          $("#cvv_" + l + "").prop("hidden", true);
      $("#cash_" + l + "").prop("disabled", false);
     $("#bankname_" + l).prop("style", "display:none;");
       $("#accountnumber_" + l).prop("style", "display:none;");
       $('#banknamediv').prop("hidden", true);
        $('#accountnumberdiv').prop("hidden", true);
      var balance = $("#balance").text();
      $("#cash_" + l + "").val(balance);
      $("#cash_" + l + "").trigger("change");

    }
    else if (pmode.trim() == "Cheque") {
      $("#bankname_" + l + "").selectpicker("hide");
      $("#modedetails_" + l + "").prop("hidden", false);
      $("#recdate_" + l + "").prop("hidden", true);

        $("#cardno_" + l + "").prop("hidden", true);
       $("#bankname_" + l).prop("style", "display:none;");
       $("#accountnumber_" + l).prop("style", "display:none;");
       $('#banknamediv').prop("hidden", true);
        $('#accountnumberdiv').prop("hidden", true);
          $("#cheque_no_" + l).prop("style", "display:block;");
            $("#cheque_date_" + l).prop("style", "display:block;");
              $("#cvv_" + l).prop("style", "display:none;");
                    $("#cash_" + l + "").prop("disabled", false);
      var balance = $("#balance").text();
      $("#cash_" + l + "").val(balance);
      $("#cash_" + l + "").trigger("change");

    }
    else {
      $("#modedetails_" + l + "").prop("hidden", true);
      $("#cash_" + l + "").trigger("change");
      if (pmode == "Cash Payment") {
           $("#rtgs_referenceno_" + l).prop("style", "display:none;");
           $("#upi_referenceno_" + l).prop("style", "display:none;");
           $("#bankname_" + l).prop("style", "display:none;");
       $("#accountnumber_" + l).prop("style", "display:none;");
       $('#banknamediv').prop("hidden", true);
        $('#accountnumberdiv').prop("hidden", true);
      $("#bankname_" + l + "").selectpicker("hide");
      $("#modedetails_" + l + "").prop("hidden", false);
      $("#recdate_" + l + "").prop("hidden", true);
        $("#cardno_" + l + "").prop("hidden", true);
         $("#cheque_no_" + l + "").prop("hidden", true);
          $("#cheque_date_" + l + "").prop("hidden", true);
          $("#cvv_" + l + "").prop("hidden", true);
        var balance = $("#balance").text();
        $("#cash_" + l + "").val(balance);
        $("#cash_" + l + "").trigger("change");
        $("#cash_" + l + "").prop("disabled", false);
      } else {
               var balance = $("#balance").text();
        $("#cash_" + l + "").val(balance);
        $("#cash_" + l + "").trigger("change");
        $("#cash_" + l + "").prop("disabled", false);
        // $("#cash_" + l + "").val("");
        // $("#cash_" + l + "").prop("disabled", true);
      }
    }
  });
    $("#cash_" + l + "").on("keyup change", function() {
        var balance = parseFloat($("#payableamt").text());
        var invcredit = $("#creditamount").val();
        var len = $("table.payment tbody tr").length;
        var cash = 0;
        for (var nl = 0; nl < len; nl++) {
            var s = nl + 1;
            var newTr = $("<tr id='paymentmethod_" + s + "' hidden>");
            var tr = "";
            tr += '<td class="res-padding" id="pmode_' + s +
                '"></td><td class="res-allign-padding" id="pamount_' + s + '">Amount</td>';
            newTr.append(tr);
            $("table.paymentdetails").append(newTr);
            var newcash = parseFloat($("#cash_" + nl + "").val());
            if (isNaN(newcash)) {
                newcash = 0;
            }
            if (newcash > 0) {
                $("#paymentmethod_" + s + "").prop("hidden", false);
                var method = $("#paymentmode_" + nl).find(":selected").val();
                $('#pmode_' + s + '').text(method);
                $('#pamount_' + s + '').text(newcash);
            } else {
                $("#paymentmethod_" + s + "").closest("tr").remove();
            }
            cash += newcash;
        }
        paidtotal = cash;
        if (cash >= 0) {
            var bal = balance - paidtotal;
            $("#balance").text(balance - paidtotal >= 0 ? bal.toFixed(2) : 0);
            $("#collectedamt").text(paidtotal.toFixed(2));
            var refam = paidtotal - balance;
            $("#refunddamt").text(paidtotal - balance > 0 ? refam.toFixed(2) : 0);
            if (paidtotal - balance >= 0) {
                $("#ipaid").text("Paid");
                $("#checkout").attr("disabled", false);
                $("#ipaid").removeClass("color-red");
            } else {
                $("#ipaid").text("Unpaid");
                $("#ipaid").addClass("color-red");
                $("#checkout").attr("disabled", true);
            }
            if (paidtotal > 0) {
                $("#paidamounttitle").attr("hidden", false);
                $("#paidamount").text((position==1?currency:'')+paidtotal+(position==2?currency:''));
            } else {
                $("#paidamounttitle").attr("hidden", true);
            }
            if (paidtotal - balance > 0) {
                var amt = paidtotal - balance;
                $("#changeamounttitle").attr("hidden", false);
                $("#changeamount").text((position==1?currency:'')+amt+(position==2?currency:''));
            } else {
                $("#changeamounttitle").attr("hidden", true);
            }
            if (invcredit > 0) {
                $("#ipaid").removeClass("color-red");
                $("#ipaid").text("Credit");
            }
        }
    });
}
"use strict";
$("#balance").on('change', function() {
    var balance = parseFloat($("#balance").text());
    var creditamount = parseFloat($("#creditamount").val());
    if (isNaN(creditamount)) {
        creditamount = 0;
    }
    if (balance > 0) {
        $("#checkout").attr("disabled", true);
    } else {
        if (creditamount <= 0) {
            $("#ipaid").text("Paid");
            $("#ipaid").removeClass("color-red");
        }
        $("#checkout").attr("disabled", false);
    }
});

function podataprintflist() {
    "use strict";
    var base = $('#base_url').val();
    var csrf = $('#csrf_token').val();
    var pb_id = $('#poolid').val();

    $.ajax({
        type: "POST",

        url: base + "room_reservation/room_reservation/smpooldetails",
        data: {
            csrf_test_name: csrf,
            pid: pb_id

        },

        success: function(data) {
            $('#smpooldetails').html(data);
        }
    });
}

function restaurantBill() {
    "use strict";
    var base = $('#base_url').val();
    var csrf = $('#csrf_token').val();
    var order_id = $('#orderid').val();

    $.ajax({
        type: "POST",
        url: base + "room_reservation/room_reservation/restaurantDetails",
        data: {
            csrf_test_name: csrf,
            order_id: order_id

        },

        success: function(data) {
            $('#restdetails').html(data);
        }
    });
}
function hallRoomBill() {
    "use strict";
    var base = $('#base_url').val();
    var csrf = $('#csrf_token').val();
    var hall_id = $('#hallid').val();

    $.ajax({
        type: "POST",
        url: base + "room_reservation/room_reservation/hallDetails",
        data: {
            csrf_test_name: csrf,
            hall_id: hall_id

        },

        success: function(data) {
            $('#halldetails').html(data);
        }
    });
}
function carParkingBill() {
    "use strict";
    var base = $('#base_url').val();
    var csrf = $('#csrf_token').val();
    var parking_id = $('#parkingid').val();

    $.ajax({
        type: "POST",
        url: base + "room_reservation/room_reservation/parkingDetails",
        data: {
            csrf_test_name: csrf,
            parking_id: parking_id

        },

        success: function(data) {
            $('#parkingdetails').html(data);
        }
    });
}
if ($('#poolid').val() == "") {
    $("#poolprint").prop("hidden", true);
} else {
    $("#poolprint").prop("hidden", false);
}
if ($('#orderid').val() == "") {
    $("#orderprint").prop("hidden", true);
} else {
    $("#orderprint").prop("hidden", false);
}
if ($('#hallid').val() == "") {
    $("#hallprint").prop("hidden", true);
} else {
    $("#hallprint").prop("hidden", false);
}
if ($('#parkingid').val() == "") {
    $("#parkingprint").prop("hidden", true);
} else {
    $("#parkingprint").prop("hidden", false);
}
$("#balance").trigger("change");
'use strict';
$("#previous").on("click", function() {
    window.location.href=baseurl+"room_reservation/checkout-list";
    $("#openregister").modal('hide');
});
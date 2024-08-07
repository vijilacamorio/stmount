'use strict';
$("#rent-1").trigger('change');
function getbsource() {
    'use strict';
    var booking_type = $("#booking_type").find(":selected").text();
    var csrf = $('#csrf_token').val();
    var myurl = baseurl + "room_reservation/room_reservation/bookingSource";
    if ($('#booking_source')[0].options.length > 1)
        $('#booking_source').find('option').not(':first').remove();
    $("#commissionrate").val('');
    $("#commissionamount").val('');
    $.ajax({
        url: myurl,
        type: "POST",
        data: {
            csrf_test_name: csrf,
            booking_type: booking_type
        },
        success: function(data) {
            var obj = JSON.parse(data);
            $.each(obj, function(key, value) {
                for (var i = 0; i < value.length; i++) {
                    $('#booking_source').append('<option value="' + value[i].btypeinfoid +
                        '">' +
                        value[i].booking_sourse + '</option>');
                }
            });
            $('.selectpicker').selectpicker('refresh');
        }
    });
}
     function calculateTotal() {

                var all_person = parseFloat($("#amount2-1").val()) || 0;
            var all_child = parseFloat($("#amount3-1").val()) || 0;
            var all_bed = parseFloat($("#amount1-1").val()) || 0;
              var all = $("table.room-list > tbody tr").length;
              var baseRent = parseFloat($("#rent-1").val()) || 0;
              var totalCGSTPercentage = parseFloat($("#cgst").val()) || 0;
              var totalSGSTPercentage = parseFloat($("#sgst").val()) || 0;
              var alltotal = parseFloat($(".get_total").val()) || 0;
              
              for (var s = 0; s <= all; s++) {
                  var rentValue = parseFloat($("#rent" + s).val()) || 0;
                  if (!isNaN(rentValue)) {
                      baseRent += rentValue;
                  }
              }
              for (var s = 0; s <= all; s++) {
                var rentValue = parseFloat($("#amount1" + s).val()) || 0;
                if (!isNaN(rentValue)) {
                    all_bed += rentValue;
                }
            }
            for (var s = 0; s <= all; s++) {
                var rentValue = parseFloat($("#amount2" + s).val()) || 0;
                if (!isNaN(rentValue)) {
                    all_person += rentValue;
                }
            }
            for (var s = 0; s <= all; s++) {
                var rentValue = parseFloat($("#amount3" + s).val()) || 0;
                if (!isNaN(rentValue)) {
                    all_child += rentValue;
                }
            }
              var bc=parseFloat($('#booking_charge').text());
              console.log(bc);
              var totalCGST = (totalCGSTPercentage * (baseRent+all_person+all_child+all_bed)) / 100;
              var totalSGST = (totalSGSTPercentage * (baseRent+all_person+all_child+all_bed)) / 100;
          
              // var grandTotal = baseRent + totalCGST + totalSGST + alltotal;
              var grandTotal = baseRent+all_person+all_child+all_bed + totalCGST + totalSGST;
              var discount=$('#discountrate').val();
    grandTotal=parseFloat(grandTotal-discount);
             // $("#totalamount").val(grandTotal.toFixed(2));
              $("#booking_charge").text((baseRent+all_person+all_child+all_bed).toFixed(2));
              $("#cgst_charge").text(totalCGST.toFixed(2));
              $("#sgst_charge").text(totalSGST.toFixed(2));
              $(".cgst_charge").val(totalCGST.toFixed(2));
              $(".sgst_charge").val(totalSGST.toFixed(2));
            //  $("#total_charge").text(grandTotal.toFixed(2));
    
           
             // $("#amount3").val(childprice);
              $("#total_charge").text(grandTotal.toFixed(2));
              $('.get_total').val(grandTotal.toFixed(2));
              $('#totalamount').val(grandTotal.toFixed(2));
}
    $(document).ready(function() {
   $("#rent-1").on("change", function(){
        calculateTotal();
    });

    $(document).on("change", "[id^='rent']", function() {
        calculateTotal();
    });
});
// function bedprices() {
//     'use strict';
    
//     var room_type = $("#room_type-1").find(":selected").val();
//     var total = parseFloat($(".get_total").val());
//     var start = $("#from_date1").val();
//     var end = $("#to_date1").val();
//     // alert(total);
//     if (room_type == "") {
//         $("#room_type-1").addClass("is-invalid");
//         return false;
//     }
//     var bed = $("#bed-1").val();
//     var csrf = $('#csrf_token').val();
//     var myurl = baseurl + "room_reservation/room_reservation/bedprice";
//     $.ajax({
//         url: myurl,
//         type: "POST",
//         data: {
//             csrf_test_name: csrf,
//             room_type: room_type,
//             bed: bed,
//             start: start,
//             end: end
//         },
//         success: function(data) {
//             var obj =JSON.parse(data);
//           debugger;
//           var bedrate = parseInt(obj.bedrate);
//           var newtotal = total + bedrate;
         
//           var all = $("table.room-list > tbody tr").length;
//           var baseRent = parseFloat($("#rent").val()) || 0;
//           var totalCGSTPercentage = parseFloat($("#cgst").val()) || 0;
//           var totalSGSTPercentage = parseFloat($("#sgst").val()) || 0;
//           var alltotal = parseFloat($(".get_total").val()) || 0;
      
//           for (var s = 1; s <= all; s++) {
//               var rentValue = parseFloat($("#rent" + s).val()) || 0;
//               if (!isNaN(rentValue)) {
//                   baseRent += rentValue;
//               }
//           }
      
//           var totalCGST = (totalCGSTPercentage * (baseRent+bedrate)) / 100;
//           var totalSGST = (totalSGSTPercentage * (baseRent+bedrate)) / 100;
      
//           // var grandTotal = baseRent + totalCGST + totalSGST + alltotal;
//           var grandTotal = bedrate+baseRent + totalCGST + totalSGST;
//           $("#amount1-1").val(bedrate);
//           $("#totalamount").val(grandTotal.toFixed(2));
//           $("#booking_charge").text((bedrate+baseRent).toFixed(2));
//           $("#cgst_charge").text(totalCGST.toFixed(2));
//           $("#sgst_charge").text(totalSGST.toFixed(2));
//           $(".cgst_charge").val(totalCGST.toFixed(2));
//           $(".sgst_charge").val(totalSGST.toFixed(2));
//           $("#total_charge").text(grandTotal.toFixed(2));
//           $('#get_total').val(grandTotal.toFixed(2));

//           $("#total_charge").text(grandTotal.toFixed(2));
//           $('.get_total').val(grandTotal.toFixed(2));
//           $('#totalamount').val(grandTotal.toFixed(2));
//         }
//     });
// }
// function bedprices() {
//     'use strict';
//     debugger;
//     var room_type = $("#room_type").find(":selected").val();
//     var total = parseFloat($(".get_total").val());
//     // alert(total);
//     if (room_type == "") {
//         $("#room_type").addClass("is-invalid");
//         return false;
//     }
//     var bed = $("#bed-1").val();
//     var csrf = $('#csrf_token').val();
//     var myurl = baseurl + "room_reservation/room_reservation/bedprice";
//     $.ajax({
//         url: myurl,
//         type: "POST",
//         data: {
//             csrf_test_name: csrf,
//             room_type: room_type,
//             bed: bed
//         },
//         success: function(data) {
//             var obj =JSON.parse(data);
//           debugger;
//           var bedrate = parseInt(obj.bedrate);
//           var newtotal = total + bedrate;
         
//           var all = $("table.room-list > tbody tr").length;
//           var baseRent = parseFloat($("#rent").val()) || 0;
//           var totalCGSTPercentage = parseFloat($("#cgst").val()) || 0;
//           var totalSGSTPercentage = parseFloat($("#sgst").val()) || 0;
//           var alltotal = parseFloat($(".get_total").val()) || 0;
      
//           for (var s = 1; s <= all; s++) {
//               var rentValue = parseFloat($("#rent" + s).val()) || 0;
//               if (!isNaN(rentValue)) {
//                   baseRent += rentValue;
//               }
//           }
      
//           var totalCGST = (totalCGSTPercentage * (baseRent+bedrate)) / 100;
//           var totalSGST = (totalSGSTPercentage * (baseRent+bedrate)) / 100;
      
//           // var grandTotal = baseRent + totalCGST + totalSGST + alltotal;
//           var grandTotal = bedrate+baseRent + totalCGST + totalSGST;
//           $("#amount1-1").val(bedrate);
//           $("#totalamount").val(grandTotal.toFixed(2));
//           $("#booking_charge").text((bedrate+baseRent).toFixed(2));
//           $("#cgst_charge").text(totalCGST.toFixed(2));
//           $("#sgst_charge").text(totalSGST.toFixed(2));
//           $(".cgst_charge").val(totalCGST.toFixed(2));
//           $(".sgst_charge").val(totalSGST.toFixed(2));
//           $("#total_charge").text(grandTotal.toFixed(2));
//           $('#get_total').val(grandTotal.toFixed(2));

//           $("#total_charge").text(grandTotal.toFixed(2));
//           $('.get_total').val(grandTotal.toFixed(2));
//           $('#totalamount').val(grandTotal.toFixed(2));
//         }
//     });
// }
function getcomplementprice(l) {
    "use strict";
    $("#complementary" + l).on("change", function() {
        var ecm = $("#complementary" + l).find(":selected").val();
        if (ecm > 0) {
            $("#compamount" + l).attr("hidden", false);
            $("#compamount" + l).text("Amount: " + ecm);
        } else {
            $("#compamount" + l).attr("hidden", true);
        }
    });
}
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
// //            ========= its for toastr error message =============
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

'use strict';
$("#existmobile").on("keyup", function() {
    var search = $(this).val();
    $("#addoldcustomer").attr("disabled", true);
    $("#existcustid").val("");
    $("#existname").val("");
    $("#existmobile").removeClass("is-valid");
    if (search != "") {
        var csrf = $('#csrf_token').val();
        var myurl = baseurl + "room_reservation/room_reservation/existcustomer";
        $.ajax({
            url: myurl,
            type: 'post',
            data: {
                csrf_test_name: csrf,
                search: search,
                type: 1
            },
            dataType: 'json',
            success: function(response) {
                var len = response.user.length;
                if (response.user != "Not found") {
                    $("#searchResult").empty();
                    for (var i = 0; i < len; i++) {
                        var mobile = response.user[i].cust_phone;;
                        var name = response.user[i].firstname;
                        $("#searchResult").append("<li value=" + mobile + ">" + mobile + '-' +
                            name + "</li>");
                    }
                    // binding click event to li
                    $("#searchResult li").bind("click", function() {
                        existuser(this);
                    });
                }
            }
        });
    } else {
        $("#searchResult").empty();
        $("#existcustid").val("");
        $("#existmobile").val("");
        $("#existname").val("");
        $("#existmobile").removeClass("is-valid");
    }
});

function existuser(value) {
    'use strict';
    $("#existmobile").removeClass("is-valid").removeClass("is-invalid");
    var num = $(value).text();
    var existmobile = num.split("-")[0];
    $("#existmobile").val(existmobile);
    $("#searchResult").empty();
    if (existmobile == "") {
        $("#existmobile").addClass("is-invalid");
        return false;
    }
    var csrf = $('#csrf_token').val();
    var myurl = baseurl + "room_reservation/room_reservation/existcustomer";
    $.ajax({
        url: myurl,
        type: "POST",
        data: {
            csrf_test_name: csrf,
            existmobile: existmobile,
        },
        success: function(data) {
            var obj = JSON.parse(data);
            $("#existname").val(obj.user);
            if (obj.existuser == 1) {
                $("#existmobile").addClass("is-valid")
                $("#existmobile").val(existmobile);
                $("#existcustid").val(obj.userid);
                $("#addoldcustomer").attr("disabled", false);
            } else {
                $("#existmobile").addClass("is-invalid")
                $("#existcustid").val("");
                $("#existmobile").val("");
                $("#addoldcustomer").attr("disabled", true);
            }
        }
    });
}
$("#mobileNo").on('keyup', mobilenocheck);
$("#mobileNo").on('change', mobilenocheck);

function mobilenocheck() {
    'use strict';
    var mobileno = $("#mobileNo").val();
    if (mobileno) {
        var csrf = $('#csrf_token').val();
        var myurl = baseurl + "room_reservation/room_reservation/mobilenocheck";
        $.ajax({
            url: myurl,
            type: "POST",
            data: {
                csrf_test_name: csrf,
                mobileno: mobileno,
            },
            success: function(data) {
                var obj = JSON.parse(data);
                if (obj.existuser == 1) {
                    $("#mobileNo").addClass("is-invalid");
                    $("#addcustomer").attr("hidden", true);
                } else {
                    $("#mobileNo").removeClass("is-invalid");
                    $("#addcustomer").attr("hidden", false);
                }
            }
        });
    } else {
        $("#mobileNo").removeClass("is-invalid");
        $("#addcustomer").attr("hidden", false);
    }
}
var pmode = $("#paymentmode").find(":selected").val();
if (pmode != "Bank Payment") {
    $("#advanceamount").attr("disabled", false);
}

function checkinBooking() {
    'use strict';
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
    $("#msg").text("");
    $("#msg1").text("");
    var datefilter1 = $("#datefilter1").val();
    if (datefilter1 == "") {
        $("#msg").text("Start date and time field is required");
        return false;
    }
    var datefilter2 = $("#datefilter2").val();
    if (datefilter2 == "") {
        $("#msg").text("End date and time field is required");
        return false;
    }
    if (datefilter2 <= datefilter1) {
        $("#msg").text("Checkout field can not equal or smaller than Checkin field");
        return false;
    }
    var currtime = $("#currtime").val();
    if (currtime < datefilter1) {
        swal({
            title: "Warning",
            text: "Checkin time is greater than current time",
            type: "warning",
            confirmButtonColor: "#28a745",
            confirmButtonText: "Ok",
            closeOnConfirm: true
        });
        return false;
    }
    //roomdetails
    var all = $("table.room-list > tbody").length;
    var room_type = $('#room_type').find(":selected").val();
    if (room_type == null) {
        room_type = $('#room_type-1').find(":selected").val();
    }
    for (var s = 0; s < all - 1; s++) {
        room_type += ",".concat($("#room_type"+s).val());
    }
    if (room_type == "") {
        $("#msg1").text("Room type field is required");
        return false;
    }
    var roomno = $('#roomno').find(":selected").val();
    if (roomno == null) {
        roomno = $('#roomno-1').find(":selected").val();
    }
    for (var s = 0; s < all - 1; s++) {
        roomno += ",".concat($("#roomno"+s).val());
    }
    if (roomno == "") {
        $("#msg1").text("Room type field is required");
        return false;
    }
    var adults = $("#adults").val();
    if (adults == null) {
        adults = $("#adults-1").val();
    }
    for (var s = 0; s < all - 1; s++) {
        adults += ",".concat($("#adults"+s).val());
    }
    if (adults == "") {
        $("#msg1").text("Adults field is required");
        return false;
    }
    var children = $("#children").val();
    if (children == null) {
        children = $("#children-1").val();
    }
    for (var s = 0; s < all - 1; s++) {
        children += ",".concat($("#children"+s).val());
    }
    var bed = $("#bed-1").val();
    if (bed == "") {
        bed = 0;
    }
    for (var s = 0; s < all - 1; s++) {
        var bedval = $("#bed"+s).val();
        if (bedval == "") {
            bedval = 0;
        }
        bed += ",".concat(bedval);
    }
    var amount1 = $("#amount1").val();
    if (amount1 == null) {
        amount1 = $("#amount1-1").val();
    }
    for (var s = 0; s < all - 1; s++) {
        amount1 += ",".concat($("#amount1"+s).val());
    }
    var person = $("#person-1").val();
    if (person == "") {
        person = 0;
    }
    for (var s = 0; s < all - 1; s++) {
        var personval = $("#person"+s).val();
        if (personval == "") {
            personval = 0;
        }
        person += ",".concat(personval);
    }
    var amount2 = $("#amount2-1").val();
    for (var s = 0; s < all - 1; s++) {
        amount2 += ",".concat($("#amount2"+s).val());
    }
    var child = $("#child1-1").val();
    if (child == "") {
        child = 0;
    }
    for (var s = 0; s < all - 1; s++) {
        var childval = $("#child1"+s).val();
        if (childval == "") {
            childval = 0;
        }
        child += ",".concat(childval);
    }
    var amount3 = $("#amount3").val();
    if (amount3 == null) {
        amount3 = $("#amount3-1").val();
    }
    for (var s = 0; s < all - 1; s++) {
        amount3 += ",".concat($("#amount3"+s).val());
    }
    if (amount3 == "") {
        amount3 = 0;
    }
    var extrastart = $('#from_date2').val();
    if (extrastart == null) {
        extrastart = $("#from_date2-1").val();
    }
    for (var s = 0; s < all - 1; s++) {
        extrastart += ",".concat($("#from_date2"+s).val());
    }
    var extraend = $('#to_date2').val();
    if (extraend == null) {
        extraend = $("#to_date2-1").val();
    }
    for (var s = 0; s < all - 1; s++) {
        extraend += ",".concat($("#to_date2"+s).val());
    }
    // var diff = Math.ceil((Date.parse(datefilter2) - Date.parse(datefilter1)) / 86400000);
    // var rentval = parseFloat($("#rent").val());
    // var rent = rentval / parseFloat(diff);
    // if (rent == null | isNaN(rent)) {
    //     var rentval = parseFloat($("#rent-1").val());
    //     var rent = rentval / parseFloat(diff);
    // }
    // for (var s = 0; s < all - 1; s++) {
    //     var rentval = parseFloat($("#rent"+s).val());
    //     var rentdiv = rentval / parseFloat(diff);
    //     rent += ",".concat(rentdiv);
    // }
    
    var rent = parseFloat($("#rent-1").val());
    for (var s = 0; s < all -1; s++) {
        var rentval = parseFloat($("#rent"+s).val());
        rent += ",".concat(rentval);
    }
    
    var complementary = $("#complementary-1").find(":selected").text();
    if (complementary == "Choose Complementary") {
        complementary = "no";
    }
    for (var s = 0; s < all - 1; s++) {
       var newcomplementary = $("#complementary"+s).find(":selected").text();
        if (newcomplementary == "Choose Complementary") {
            newcomplementary = "no";
        }
        complementary += ",".concat(newcomplementary);
    }
    complementary = $.trim(complementary.replace(/\s+/g, " "));

    var complementaryprice = $("#complementary").find(":selected").val();
    if (complementaryprice == null) {
        complementaryprice = $("#complementary-1").find(":selected").val();
    }
    for (var s = 0; s < all - 1; s++) {
        complementaryprice += ",".concat($("#complementary"+s).find(":selected").val());
    }
    var offer_price = $("#offer_price-1").text();
    if (offer_price == '') {
        offer_price = 0;
    }
    for (var s = 0; s < all - 1; s++) {
        offer_price += ",".concat(($("#offer_price"+s).text() ? $("#offer_price"+s).text() : 0));
    }
    //end
    var name = $("#alluser").val();
    var userid = $("#alluserid").val();
    if (name == "") {
        var tc = $("table.customerdetail-1 tbody tr").length;
        var newname = $("#username0").text();
        var newuserid = $("#userid0").text();
        for (var s = 1; s < tc; s++) {
            newname += ",".concat($("#username" + s).text());
            newuserid += ",".concat($("#userid" + s).text());
        }
        if (name.length < newname.length) {
            userid = $.trim(newuserid.replace(/\s+/g, " "));
            if (userid === '') {
                name = $.trim(newname.replace(/\s+/g, " "));
            } else {
                name = "";
            }
        }
    }
    //reservation details
    var booking_type = $("#booking_type").find(":selected").val();
    var booking_source = $("#booking_source").find(":selected").val();
    var bsorurce_no = $("#bsorurce_no").val();
    var arrival_from = $("#arrival_from").val();
    var pof_visit = $("#pof_visit").val();
    var booking_remarks = $("#booking_remarks").val();
    var name = $('#username0').text();
    //user details
    var email = $("#allemail").val();
    var mobile = $("#allmobile").val();
    var lastname = $("#alllastname").val();
    var gender = $("#allgender").val();
    var father = $("#allfather").val();
    var occupation = $("#alloccupation").val();
    var dob = $("#alldob").val();
    var anniversary = $("#allanniversary").val();
    var pitype = $("#allpitype").val();
    var imgfront = $("#allimgfront").val();
    var imgback = $("#allimgback").val();
    var imgguest = $("#allimgguest").val();
    var contacttype = $("#allcontacttype").val();
    var state = $("#allstate").val();
    var city = $("#allcity").val();
    var zipcode = $("#allzipcode").val();
    var address = $("#alladdress").val();
    var country = $("#allcountry").val();
    var total_price = $('.get_total').val();
    var cgst = $(".cgst_charge").val();
    var sgst = $(".sgst_charge").val();
    //payment details
    var discountreason = $("#discountreason").val();
    var discountamount = $("#discountrate").val();
    var commissionrate = $("#commissionrate").val();
    var commissionamount = $("#commissionamount").val();
    var paymentmode = $("#paymentmode").find(":selected").val();
    if (paymentmode == "Bank Payment") {
        if ($("#cardno").val() == "") {
            $("#cardno").addClass("is-invalid");
            return false;
        } else if ($("#bankname").find(":selected").val() == "") {
            $("#cardno").removeClass("is-invalid");
            $("#bankname").parent().addClass("is-invalid");
            return false;
        } else {
            $("#cardno").removeClass("is-invalid");
            $("#bankname").parent().removeClass("is-invalid");
        }
    }
    var bankname = $("#bankname").find(":selected").val();
    var cardno = $("#cardno").val();
    var advanceamount = $("#advanceamount").val();
    var advanceremarks = $("#advanceremarks").val();
  
    var advanceid = $("#advanceid").val();

    var bookingid = $("#bookingid").val();



    var expirydate = $('#expirydate').val();
    var ccv = $('#ccv').val();
    var upi_referenceno = $('#upi_referenceno').val();
    var rtgs_referenceno = $('#rtgs_referenceno').val();
    var cheque_no = $('#cheque_no').val();
    var cheque_date = $('#cheque_date').val();
    var online_referenceno = $('#online_referenceno').val();

  
  
  
    var csrf = $('#csrf_token').val();
    var myurl = baseurl + "room_reservation/room_reservation/checkinBooking";
    $.ajax({
        url: myurl,
        type: "POST",
        data: {
            csrf_test_name: csrf,
            booking_type: booking_type,
            booking_source: booking_source,
            bsorurce_no: bsorurce_no,
            arrival_from: arrival_from,
            pof_visit: pof_visit,
            booking_remarks: booking_remarks,
            datefilter1: datefilter1,
            datefilter2: datefilter2,
            room_type: room_type,
            roomno: roomno,
            adults: adults,
            children: children,
            rent: rent,
            discount_price: offer_price,
            complementary: complementary,
            complementaryprice: complementaryprice,
            name: name,
            mobile: mobile,
            email: email,
            lastname: lastname,
            gender: gender,
            father: father,
            occupation: occupation,
            dob: dob,
            anniversary: anniversary,
            pitype: pitype,
            imgfront: imgfront,
            imgback: imgback,
            imgguest: imgguest,
            contacttype: contacttype,
            state: state,
            city: city,
            zipcode: zipcode,
            address: address,
            country: country,
            bed: bed,
            amount1: amount1,
            person: person,
            amount2: amount2,
            child: child,
            amount3: amount3,
            cgst: cgst,
            sgst: sgst,
            extrastart: extrastart,
            extraend: extraend,
            discountreason: discountreason,
            discountamount: discountamount,
            commissionrate: commissionrate,
            commissionamount: commissionamount,
           paymentmode: paymentmode,
            cardno: cardno,
            expirydate: expirydate,
            ccv: ccv,
            upi_referenceno: upi_referenceno,
            rtgs_referenceno: rtgs_referenceno,
            cheque_no: cheque_no,
            cheque_date: cheque_date,
            online_referenceno: online_referenceno,
          
            advanceamount: advanceamount,
            advanceremarks: advanceremarks,
                  advanceid: advanceid,
            total_price: total_price,
            bookingid: bookingid
        },
        success: function(data) {
            if (data.substr(4, 1) === "S") {
                $("#booking_list").show();
                $("#reservation").hide();
                toastrSuccessMsg(data);
                $("#bookingdetails").DataTable().ajax.reload();
                $(".sidebar-mini").removeClass('sidebar-collapse');
            } else
                toastrErrorMsg(data);
            setTimeout(function() {}, 1000);
        }
    });
}
"use strict";
$("#view_checin,#previous").on("click", function() {
    $("#booking_list").show();
    $("#reservation").hide();
    $("#openregister").modal('hide');
    $(".sidebar-mini").removeClass('sidebar-collapse');
});
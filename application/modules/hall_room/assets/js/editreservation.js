function getbsource() {
    'use strict';
    var booking_type = $("#booking_type").find(":selected").text();
    var csrf = $('#csrf_token').val();
    var myurl = baseurl + "hall_room/hallroom/bookingSource";
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

"use strict";
$("#existmobile").on("keyup", function() {
    var search = $(this).val();
    $("#addoldcustomer").attr("disabled", true);
    $("#existcustid").val("");
    $("#existname").val("");
    $("#existmobile").removeClass("is-valid");
    if (search != "") {
        var csrf = $('#csrf_token').val();
        var myurl = baseurl + "hall_room/hallroom/existcustomer";
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
    var myurl = baseurl + "hall_room/hallroom/existcustomer";
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
    "use strict";
    var mobileno = $("#mobileNo").val();
    if (mobileno) {
        var csrf = $('#csrf_token').val();
        var myurl = baseurl + "hall_room/hallroom/mobilenocheck";
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

function newBooking() {
   
   
    debugger;
    // 'use strict';
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
        $("#datefilter1").addClass("is-invalid");
        return false;
    }
    var datefilter2 = $("#datefilter2").val();
    if (datefilter2 == "") {
        $("#datefilter2").addClass("is-invalid");
        return false;
    }
    if (datefilter2 <= datefilter1) {
        $("#msg").text("End field can not equal or smaller than Start field");
        $("#datefilter1").addClass("is-invalid");
        $("#datefilter2").addClass("is-invalid");
        return false;
    }
    //Event name & type
    var event_name = $("#event_name").val();
    if (event_name == "") {
        $("#event_name").addClass("is-invalid");
        return false;
    }else{
        $("#event_name").removeClass("is-invalid");
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
        $("#room_type").addClass("is-invalid");
        return false;
    }
    // var roomno = $('#roomno').find(":selected").val();
    // if (roomno == null) {
    //     roomno = $('#roomno-1').find(":selected").val();
    // }
    // for (var s = 0; s < all - 1; s++) {
    //     roomno += ",".concat($("#roomno" + s).val());
    // }
    // if (roomno == "") {
    //     $("#roomno").addClass("is-invalid");
    //     return false;
    // }
    // var adults = $("#adults").val();
    // if (adults == null) {
    //     adults = $("#adults-1").val();
    // }
    // for (var s = 0; s < all - 1; s++) {
    //     adults += ",".concat($("#adults" + s).val());
    // }
    // if (adults == "") {
    //     $("#adults").addClass("is-invalid");
    //     return false;
    // }
    var diff = Math.ceil((Date.parse(datefilter2) - Date.parse(datefilter1)) / 86400000);
    var rentval = parseFloat($("#rent").val());
    var rent = rentval ;
    if (rent == null | isNaN(rent)) {
        var rentval = parseFloat($("#rent-1").val());
        var rent = rentval ;
    }
    
    var seatplan = $("#seatplan").val();
    if (seatplan == null) {
        seatplan = $("#seatplan-1").val();
    }
    for (var s = 0; s < all - 1; s++) {
        seatplan += ",".concat($("#seatplan"+s).val());
    }
    var offer_price = $("#offer_price").text();
    if (offer_price == null) {
        offer_price = $("#offer_price-1").text();
    }
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
            name = $.trim(newname.replace(/\s+/g, " "));
            userid = $.trim(newuserid.replace(/\s+/g, " "));
        }
    }
    //reservation details
    var event_name = $("#event_name").val();
    var event_type = $("#event_type").val();
    var booking_remarks = $("#booking_remarks").val();
    var checkinbookedid = $("#checkinbookedid").val();
    //user details
    var email = $("#allemail").val();
    var mobile = $("#allmobile").val();
    var lastname = $("#alllastname").val();
    var gender = $("#allgender").val();
    var father = $("#allfather").val();
    var occupation = $("#alloccupation").val();
    var c_reservation = $("#c_reservation").val();
    var c_reg = $("#c_reg").val();
    var c_roomno = $("#c_roomno").val();
    var c_full_name = $("#c_full_name").val();
    var c_company_taname = $("#c_company_taname").val();
    var c_address = $("#c_address").val();
    var c_pincode = $("#c_pincode").val();
    var c_res_off = $("#c_res_off").val();
    var c_moblie = $("#c_moblie").val();
    var c_res = $("#c_res").val();
    var c_dob = $("#c_dob").val();
    var c_email = $("#c_email").val();
    var c_vehicleno = $("#c_vehicleno").val();
    var c_nationality = $("#c_nationality").val();
    var c_noofperson = $("#c_noofperson").val();
    var c_adults = $("#c_adults").val();
    var c_children = $("#c_children").val();
    var c_arrival = $("#c_arrival").val();
    var c_atime = $("#c_atime").val();
    var c_departutedate = $("#c_departutedate").val();
    var c_dtime = $("#c_dtime").val();
    var c_aform = $("#c_aform").val();
    var c_pov = $("#c_pov").val();
    var c_proceedingto = $("#c_proceedingto").val();
    var c_amount = $("#c_amount").val();
    var modeofpayment = $("#modeofpayment").val();
    var c_passport = $("#c_passport").val();
    var c_country = $("#c_country").val();
    var c_issued = $("#c_issued").val();
    var c_poi = $("#c_poi").val();
    var c_vaild = $("#c_vaild").val();
    var visa_validtill = $("#visa_validtill").val();
    var c_pdfi = $("#c_pdfi").val();
    var c_weii = $("#c_weii").val();
    var dob = $("#alldob").val();
    var anniversary = $("#allanniversary").val();
    var pitype = $("#allpitype").val();
    var pid = $("#allpid").val();
    var imgfront = $("#allimgfront").val();
    var imgback = $("#allimgback").val();
    var imgguest = $("#allimgguest").val();
    var contacttype = $("#allcontacttype").val();
    var state = $("#allstate").val();
    var city = $("#allcity").val();
    var zipcode = $("#allzipcode").val();
    var address = $("#alladdress").val();
    var country = $("#allcountry").val();
    var t_amount = $('#totalamount').val();
    var checkin = $(".checkinData").val();
  
    var cgst = $('#c_charge').val();
    var sgst = $('#s_charge').val();
    var update = $(".update_hallroom").val();
   
    var discountreason = $("#discountreason").val();
    var discountamount = $("#discountamount").val();
    var commissionrate = $("#commissionrate").val();
    var commissionamount = $("#commissionamount").val();
    var paymentmode = $("#paymentmode").find(":selected").val();
    
    
    
    
    // $("#paymentmode").parent().removeClass("is-invalid");
    // if (paymentmode == "Bank Payment") {
    //     if ($("#cardno").val() == "") {
    //         $("#cardno").addClass("is-invalid");
    //         return false;
    //     } else if ($("#bankname").find(":selected").val() == "") {
    //         $("#cardno").removeClass("is-invalid");
    //         $("#bankname").parent().addClass("is-invalid");
    //         return false;
    //     } else {
    //         $("#cardno").removeClass("is-invalid");
    //         $("#bankname").parent().removeClass("is-invalid");
    //     }
    // }else{
    //     if($("#advanceamount").val()>0 & paymentmode==""){
    //         $("#paymentmode").removeClass("is-invalid");
    //         return false;
    //     }
    // }
    
     if (paymentmode == "Card Payment") {
        if ($("#cardno").val() == "") {
            $("#cardno").addClass("is-invalid");
            return false;
        } else if ($("#expirydate").val() == "") {
            $("#cardno").removeClass("is-invalid");
            $("#expirydate").addClass("is-invalid");
            return false;
        } else if ($("#ccv").val() == "") {
            $("#cardno").removeClass("is-invalid");
            $("#expirydate").removeClass("is-invalid");
            $("#ccv").addClass("is-invalid");
            return false;
        } else {
            $("#cardno").removeClass("is-invalid");
            $("#expirydate").removeClass("is-invalid");
            $("#ccv").removeClass("is-invalid");
        }
    }

    if (paymentmode == "UPI") {
        if ($("#upi_referenceno").val() == "") {
            $("#upi_referenceno").addClass("is-invalid");
            return false;
        } else {
            $("#upi_referenceno").removeClass("is-invalid");
        }
    }

 

    if (paymentmode == "Cheque") {
        if ($("#cheque_no").val() == "") {
            $("#cheque_no").addClass("is-invalid");
            return false;
        } else if ($("#cheque_date").val() == "") {
            $("#cheque_no").removeClass("is-invalid");
            $("#cheque_date").addClass("is-invalid");
            return false;
        } else {
            $("#cheque_no").removeClass("is-invalid");
            $("#cheque_date").removeClass("is-invalid");
        }
    }

    if (paymentmode == "Online Payment") {
        if ($("#online_referenceno").val() == "") {
            $("#online_referenceno").addClass("is-invalid");
            return false;
        } else {
            $("#online_referenceno").removeClass("is-invalid");
        }
    }

   if (paymentmode == ""){
       paymentmode='';
   }
    // var bankname = $("#bankname").find(":selected").val();
    // var cardno = $("#cardno").val();
    // var advanceamount = $("#advanceamount").val();
    // var advanceremarks = $("#advanceremarks").val();
    var bookingid = $("#bookingid").val();
    
     var bankname = $('#bankname').val();
     var accountnumber = $('#accountnumber').val();
        var cardno = $("#cardno").val();
    var expirydate = $('#expirydate').val();
    var ccv = $('#ccv').val();
    var upi_referenceno = $('#upi_referenceno').val();
    var rtgs_referenceno = $('#rtgs_referenceno').val();
    var cheque_no = $('#cheque_no').val();
    var cheque_date = $('#cheque_date').val();
    var online_referenceno = $('#online_referenceno').val();
    var advanceamount = $("#advanceamount").val();
    var advanceremarks = $("#advanceremarks").val();
    var paymentmode = $("#paymentmode").find(":selected").val();
    var csrf = $('#csrf_token').val();
    var myurl = baseurl + "hall_room/hallroom/newBooking";
    $.ajax({
        url: myurl,
        type: "POST",
        data: {
            csrf_test_name: csrf,
            event_name: event_name,
            event_type: event_type,
            booking_remarks: booking_remarks,
            datefilter1: datefilter1,
            datefilter2: datefilter2,
            room_type: room_type,
            t_amount: t_amount,
            cgst: cgst,
            sgst: sgst,
            rent: rent,
            c_reservation: c_reservation,
            c_reg: c_reg,
            c_roomno: c_roomno,
            c_full_name: c_full_name,
            c_company_taname: c_company_taname,
            c_address: c_address,
            c_pincode: c_pincode,
            c_res_off: c_res_off,
            c_moblie: c_moblie,
            c_res: c_res,
            c_dob: c_dob,
            c_email: c_email,
            c_vehicleno: c_vehicleno,
            c_nationality: c_nationality,
            c_noofperson: c_noofperson,
            c_adults: c_adults,
            c_children: c_children,
            c_arrival: c_arrival,
            c_atime: c_atime,
            c_departutedate: c_departutedate,
            c_dtime: c_dtime,
            c_aform: c_aform,
            c_pov: c_pov,
            c_proceedingto: c_proceedingto,
            c_amount: c_amount,
            modeofpayment: modeofpayment,
            c_passport: c_passport,
            c_country: c_country,
            c_issued: c_issued,
            c_poi: c_poi,
            c_vaild: c_vaild,
            visa_validtill: visa_validtill,
            c_pdfi: c_pdfi,
            c_weii: c_weii,
            discount_price: offer_price,
            seatplan: seatplan,
            checkinbookedid: checkinbookedid,
            userid: userid,
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
            pid: pid,
            imgfront: imgfront,
            imgback: imgback,
            imgguest: imgguest,
            contacttype: contacttype,
            state: state,
            update: update,
            city: city,
              accountnumber : accountnumber,
             bankname  : bankname,
            zipcode: zipcode,
            address: address,
            country: country,
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
            bookingid: bookingid,
            checkin: checkin
        },
        success: function(data) {
            if (data.substr(4, 1) === "S") {
                $("#booking_list").show();
                $("#checkin_list").show();
                $("#reservation").hide();
                toastrSuccessMsg(data);
                $("#bookingdetails").DataTable().ajax.reload();
                $("#checkindetails").DataTable().ajax.reload();
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
    $("#checkin_list").show();
    $("#reservation").hide();
    $("#openregister").modal('hide');
    $(".sidebar-mini").removeClass('sidebar-collapse');
});
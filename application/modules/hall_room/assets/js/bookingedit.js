var saveall = 0;
var saveone = 1;
var num;
$(document).ready(function() {
    "use strict";
    $('#booking_type').on('change', function() {
        var bookingtype = $('#booking_type').find(":selected").val();
        if (bookingtype) {
            $('.selectpicker').selectpicker('refresh');
            $('#booking_source').attr("disabled", false);
        } else {
            $('#booking_source').attr("disabled", true);
            $('#booking_source :nth-child(1)').prop('selected', true);
            $('#booking_source').trigger('change');
            $("#commissionrate").val('');
            $("#commissionrate").trigger('change');
        }
    });
    $("#discountreason").on('change', function(){
        var discountreason = $("#discountreason").val();
        if(discountreason){
        var rent = $("#rent").val();
        if (rent == null) {
            rent = $("#rent-1").val();
        }
        if(rent>0){
        $("#msg1").text('');
        $("#discountrate").attr("disabled", false);
        $("#discountamount").val(0);
        }else{
            $("#msg1").text("Please select room and room no first");
        }
        }else{
            $("#discountrate").val('');
            $("#discountamount").val('');
            $("#discountrate").attr("disabled", true);
            $("#discountamount").attr("disabled", true);
        }
    });
    $("#booking_source").on('change', function(){
        var booking_source = $("#booking_source").find(":selected").val();
        if(booking_source){
        $("#commissionrate").attr("disabled", false);
        var csrf = $('#csrf_token').val();
        var myurl = baseurl + "hall_room/hallroom/bsourcerate";
        $.ajax({
            url: myurl,
            type: "POST",
            data: {
                csrf_test_name: csrf,
                booking_source: booking_source
            },
            success: function(data) {
                var obj = JSON.parse(data);
                $("#commissionrate").val(obj.commissionrate);
                $("#commissionrate").trigger('change');
            }
        });
        }else{
            $("#commissionrate").attr("disabled", true);
            $("#commissionamount").attr("disabled", true);
            $("#commissionrate").val('');
            $("#commissionrate").trigger('change');
        }
    });
    var tlength = $("table.room-list > tbody").length;
    function editBooking(i){
        $('#room_type'+i).on('change', function() {
            var roomtype = $('#room_type'+i).find(":selected").val();
            if(roomtype){
                $('.selectpicker').selectpicker('refresh');
                $('#roomno'+i).attr("disabled", false);
                $('#adults'+i).val(0);
                $('#adults'+i).attr("disabled", false);
                $('#children'+i).attr("disabled", false);
                $('#from_date1'+i).attr("disabled", false);
                $('#to_date1'+i).attr("disabled", false);
                
                $('#rent'+i).attr("disabled", false);
                $('#roomno'+i).trigger('change');
                $('#seatplan'+i).attr("disabled", false);
                $("#compamount"+i).attr("hidden",true);
                $('#seatplan :nth-child(1)'+i).prop('selected', true);
                $('#addrow'+i).attr("disabled", false);
                $('#discountreason').val('');
                $('#discountreason'+i).trigger('change');
                $('#commissionrate').trigger('change');
            }else{
                $('.selectpicker').selectpicker('refresh');
                $('#roomno'+i).attr("disabled", true);
                $('#roomno'+i+' :nth-child(1)').prop('selected', true);
                $('.selectpicker').selectpicker('refresh');
                $('#children'+i).attr("disabled", true);
                $('#children'+i).val(0);
                $('#from_date1'+i).attr("disabled", true);
                $('#to_date1'+i).attr("disabled", true);
                $('#from_date1'+i).val($("#datefilter1").val());
                $('#to_date1'+i).val($("#datefilter2").val());
                $('#rent'+i).attr("disabled", true);
                $('#seatplan'+i).attr("disabled", true);
                $("#compamount"+i).attr("hidden",true);
                $('#seatplan'+i+' :nth-child(1)').prop('selected', true);
                $('.selectpicker').selectpicker('refresh');
                $('#addrow'+i).attr("disabled", true);
                $('#roomno'+i).trigger('change');
            }
        });
        $('#roomno'+i).on('change', function() {
            var roomno = $('#roomno'+i).find(':selected').val();
            if(roomno){
                $('.selectpicker').selectpicker('refresh');
                $('#from_date2'+i).attr("disabled", false);
                $('#to_date2'+i).attr("disabled", false);
                $('#bed'+i).attr("disabled", false);
                $('#person'+i).attr("disabled", false);
                $('#child1'+i).attr("disabled", false);
                $('#discountreason').val('');
                $('#newroom'+i).attr("disabled", false);
                var radioValue = $("input[name='customRadio']:checked").val();
                if(saveone==1 && i!=-1){
                    $("#bookingsave").attr("disabled", false);
                }else if(i==-1){
                    if(radioValue){
                        $('#newroom'+i).attr("disabled", false);
                        $("#bookingsave").attr("disabled", false);
                    }else{
                        $('#newroom'+i).attr("disabled", true);
                        $("#bookingsave").attr("disabled", true);
                    }
                }else{
                    saveall=1;
                    $("#bookingsave").attr("disabled", true);
                }
            }else{
                $('.selectpicker').selectpicker('refresh');
                $('#adults'+i).attr("disabled", true);
                $('#adults'+i).val(0);
                $('#from_date2'+i).attr("disabled", true);
                $('#to_date2'+i).attr("disabled", true);
                $('#from_date2'+i).val($("#datefilter1").val());
                $('#to_date2'+i).val($("#datefilter2").val());
                $('#rent'+i).attr("disabled", true);
                $('#rent'+i).val(0);
                $('#bed'+i).attr("disabled", true);
                $('#bed'+i).val('');
                $('#amount1'+i).attr("disabled", true);
                $('#amount1'+i).val('0');
                $('#person'+i).attr("disabled", true);
                $('#person'+i).val('');
                $('#amount2'+i).attr("disabled", true);
                $('#amount2'+i).val('0');
                $('#child1'+i).attr("disabled", true);
                $('#child1'+i).val('');
                $('#amount3'+i).attr("disabled", true);
                $('#amount3'+i).val('0');
                $('#nofroom'+i).attr("disabled", true);
                $('#newroom'+i).attr("disabled", true);
                saveall=0;
                $("#bookingsave").attr("disabled", true);
                $("#rent"+i).trigger('change');
            }
        });
    }
    $('.datefilter').on('apply.daterangepicker', function (ev, picker) {
        for(var j=-1; j<tlength-1; j++){
            $('#room_type'+j+' :nth-child(1)').prop('selected', true);
            editBooking(j);
            $('#room_type'+j).trigger('change');
        }
    });
    $("#discountrate").on('change', function(){
        var discountrate = $("#discountrate").val();
        if(discountrate){
        if(discountrate<=100){
        $("#msg2").text('');
        var all = $("table.room-list > tbody").length;
        var rent = parseFloat($("#rent-1").val());
        for (var s = 0; s < all - 1; s++) {
            rent += parseFloat($("#rent" + s).val());
        }
        var disamount = (rent*discountrate)/100;
        $("#discountamount").val(disamount.toFixed(2));
        }else{
            $("#msg2").text("Invalid percentage");
        }

        }else{
            $("#discountamount").val('');
            $("#discountamount").attr("disabled", true);
        }
    });
    $("#commissionrate").on('change', function(){
        var commissionrate = $("#commissionrate").val();
        if(commissionrate){
        if(commissionrate<=100){
        $("#msg2").text('');
        var all = $("table.room-list > tbody").length;
        var rent = parseFloat($("#rent-1").val());
        for (var s = 0; s < all - 1; s++) {
            rent += parseFloat($("#rent" + s).val());
        }
        var commissionamount = (rent*commissionrate)/100;
        $("#commissionamount").val(commissionamount.toFixed(2));
        }else{
            $("#msg2").text("Invalid percentage");
        }
        }else{
            $("#commissionamount").val('');
        }
    });
    $("#rent-1").on("change", function(){
        var all = $("table.room-list > tbody tr").length;
        var baseRent = parseFloat($("#rent-1").val()) || 0;
        var totalCGSTPercentage = parseFloat($("#cgst").val()) || 0;
        var totalSGSTPercentage = parseFloat($("#sgst").val()) || 0;
       
        for (var s = 1; s <= all; s++) {
            var rentValue = parseFloat($("#rent-1"+s).val()) || 0;
            if (!isNaN(rentValue)) {
                baseRent += rentValue;
            }
        }
    
        var totalCGST = (totalCGSTPercentage * baseRent) / 100;
        var totalSGST = (totalSGSTPercentage * baseRent) / 100;
    
        var grandTotal = baseRent + totalCGST + totalSGST;
    
        $("#totalamount").val(grandTotal.toFixed(2));
        $(".cgst_charge").val(totalCGST.toFixed(2));
        $(".sgst_charge").val(totalSGST.toFixed(2));
        $(".cgst_charge").text(totalCGST.toFixed(2));
        $(".sgst_charge").text(totalSGST.toFixed(2));
    });
    $("#rent-1").on("input", function(){
        var all = $("table.room-list > tbody tr").length;
        var baseRent = parseFloat($("#rent-1").val()) || 0;
        var totalCGSTPercentage = parseFloat($("#cgst").val()) || 0;
        var totalSGSTPercentage = parseFloat($("#sgst").val()) || 0;
       
        for (var s = 1; s <= all; s++) {
            var rentValue = parseFloat($("#rent-1"+s).val()) || 0;
            if (!isNaN(rentValue)) {
                baseRent += rentValue;
            }
        }
    
        var totalCGST = (totalCGSTPercentage * baseRent) / 100;
        var totalSGST = (totalSGSTPercentage * baseRent) / 100;
    
        var grandTotal = baseRent + totalCGST + totalSGST;
    
        $("#totalamount").val(grandTotal.toFixed(2));
        $(".cgst_charge").val(totalCGST.toFixed(2));
        $(".sgst_charge").val(totalSGST.toFixed(2));
        $(".cgst_charge").text(totalCGST.toFixed(2));
        $(".sgst_charge").text(totalSGST.toFixed(2));
    });
    $("#paymentmode").on('change', function(){
        var paymentmode = $("#paymentmode").find(":selected").val();
        if(paymentmode=="Bank Payment"){
            $("#cardno").val("");
            $("#bankname option:first").val("");
            $("#cardno").attr("disabled", false);
            $("#bankname").attr("disabled", false);
            $("#carddiv").attr("hidden", false);
            $("#bankdiv").attr("hidden", false);
        }else{
            $("#cardno").attr("disabled", true);
            $("#bankname").attr("disabled", true);
            $("#carddiv").attr("hidden", true);
            $("#bankdiv").attr("hidden", true);
        }
        if(paymentmode){
        $("#advanceamount").attr("disabled", false);
        $("#advanceremarks").attr("disabled", false);
        }else{
            $("#advanceamount").attr("disabled", true);
            $("#advanceremarks").attr("disabled", true);
        }
    });
    $("#firstname").on('change', function(){
        var firstname = $("#firstname").val();
        if(firstname){
            $("#addcustomer").attr("disabled", false);
        }else{
            $("#addcustomer").attr("disabled", true);
        }
    });
    $("#addcustomer,#addoldcustomer").on('click', function(){
        var ch = 0;
        var trl = $("table.customerdetail-1 tbody tr").length;
        num=trl;
        var exmobile = $("#existmobile").val();
            for(var mn=0; mn<num; mn++){
                if($("#usermobile"+mn).text()!=""){
                    ch = 1;
                }
            }
            if(ch==0 && $("#mobileNo").val()=="" && exmobile==""){
                $("#mobileNo").addClass("is-invalid");
                return false;
            }else{
                $("#mobileNo").removeClass("is-invalid");
                $("#exampleModal").modal("hide");
                $("#exampleModal2").modal("hide");
                $("#addcustomer").attr("disabled", true);
                $("#addoldcustomer").attr("disabled", true);
            }
        var newRow = $("<tr>");
        var cols = "";

        if(exmobile==""){
            cols += '<th class="border-0 pl-0"><div class="custom-control custom-radio"><input type="radio" onclick="getradio('+num+')" id="pri'+num+'" name="customRadio"'
            +'class="custom-control-input"><label class="custom-control-label" for="pri'+num+'"></label></div></th>';
            cols += '<td class="border-0" id="username'+num+'">'+$("#title").find(":selected").val()+" "+$("#firstname").val()+" "+$("#lastname").val()+'</td>';
            cols += '<td class="border-0" id="usermobile'+num+'">'+$("#mobileNo").val()+'</td>';
            cols += '<td class="border-0" id="useremail'+num+'" hidden>'+$("#email").val()+'</td>';
            cols += '<td class="border-0" id="userlastname'+num+'" hidden>'+$("#lastname").val()+'</td>';
            cols += '<td class="border-0" id="usergender'+num+'" hidden>'+($('input[name="customRadioInline"]:checked').val()?$('input[name="customRadioInline"]:checked').val():"")+'</td>';
            cols += '<td class="border-0" id="userfathername'+num+'" hidden>'+$("#fathername").val()+'</td>';
            cols += '<td class="border-0" id="useroccupation'+num+'" hidden>'+$("#occupation").val()+'</td>';
            cols += '<td class="border-0" id="userdob'+num+'" hidden>'+$("#dob").val()+'</td>';
            cols += '<td class="border-0" id="useranniversary'+num+'" hidden>'+$("#anniversary").val()+'</td>';
            cols += '<td class="border-0" id="usernationality'+num+'" hidden>'+$("#nationality").val()+'</td>';
            cols += '<td class="border-0" id="uservip'+num+'" hidden>'+($('input[name="vip"]:checked').val()?$('input[name="vip"]:checked').val():"")+'</td>';
            cols += '<td class="border-0" id="userpitype'+num+'" hidden>'+$("#pitype").val()+'</td>';
            cols += '<td class="border-0" id="userpid'+num+'" hidden>'+$("#pid").val()+'</td>';
            cols += '<td class="border-0" id="userimgfront'+num+'" hidden>'+$("#imgffront").val()+'</td>';
            cols += '<td class="border-0" id="userimgback'+num+'" hidden>'+$("#imgbback").val()+'</td>';
            cols += '<td class="border-0" id="usercomments'+num+'" hidden>'+$("#comments").val()+'</td>';
            cols += '<td class="border-0" id="userimgguest'+num+'" hidden>'+$("#imggguest").val()+'</td>';
            cols += '<td class="border-0" id="usercontacttype'+num+'" hidden>'+$("#contacttype").find(":selected").val()+'</td>';
            cols += '<td class="border-0" id="usercountry'+num+'" hidden>'+$("#country").val()+'</td>';
            cols += '<td class="border-0" id="userstate'+num+'" hidden>'+$("#state").val()+'</td>';
            cols += '<td class="border-0" id="usercity'+num+'" hidden>'+$("#city").val()+'</td>';
            cols += '<td class="border-0" id="userzipcode'+num+'" hidden>'+$("#zipcode").val()+'</td>';
            cols += '<td class="border-0" id="useraddress'+num+'" hidden>'+$("#address").val()+'</td>';
            cols += '<td class="border-0 pr-0 text-right"><button type="button" onclick="custdel('+num+')" id="custdel'+num+'" class="btn btn-danger-soft btn-xs custdelete'+num+'"><i class="far fa-trash-alt"></i></button></td>';
        }else{
            cols += '<th class="border-0 pl-0"><div class="custom-control custom-radio"><input type="radio" onclick="getradio('+num+')" id="pri'+num+'" name="customRadio"'
            +'class="custom-control-input"><label class="custom-control-label" for="pri'+num+'"></label></div></th>';
            cols += '<td class="border-0" id="userid'+num+'" hidden>'+$("#existcustid").val()+'</td>';
            cols += '<td class="border-0" id="username'+num+'">'+$("#existname").val()+'</td>';
            cols += '<td class="border-0" id="usermobile'+num+'">'+$("#existmobile").val()+'</td>';
            cols += '<td class="border-0 pr-0 text-right"><button type="button" onclick="custdel('+num+')" id="custdel'+num+'" class="btn btn-danger-soft btn-xs custdelete'+num+'"><i class="far fa-trash-alt"></i></button></td>';
            $("#existmobile").removeClass("is-valid").removeClass("is-invalid");
            $("#existname").val("");
            $("#existmobile").val("");
            $("#existcustid").val("");
        }

        newRow.append(cols);
        $("table.customerdetail-1").append(newRow);
        num++;
        $("#firstname").val("");$("#title").val($("#title option:first").val());$("#code").val("");$("#mobileNo").val("");$("#email").val("");$("#imgbbcak").val();
        $("#lastname").val("");$('input[name="customRadioInline"]:checked').prop("checked",false);$("#country").val("");$("#imgffront").val('');$("#imggguest").val('');
        $("#fathername").val("");$("#occupation").val("");$("#dob").val("");$("#anniversary").val("");$('#image-preview').attr('src', 'http://localhost/Hotel//assets/img/proof_icon.png');
        $("#nationality").val("");$('input[name="vip"]:checked').prop("checked",false);$("#pitype").val("");$("#filename").text("Drag and Drop");
        $("#pid").val("");$('#image-preview2').attr('src', 'http://localhost/Hotel//assets/img/proof_icon.png');$("#filename2").text("Drag and Drop");$("#comments").val("");$("#filename3").text("Drag and Drop");
        $('#image-preview3').attr('src', 'http://localhost/Hotel//assets/img/user.png');$("#contacttype").prop("selectedIndex", 0);$("#state").val("");$("#city").val("");$("#zipcode").val("");$("#address").val("");
    });

});
function custdel(r) {
    num==null?num=r:num;
    var tc = $("table.customerdetail-1 tbody tr").length;
    if (1 == tc){
        swal({
            title: "Failed",
            text: "There only one row you can't delete.",
            type: "error",
            confirmButtonColor: "#28a745",
            confirmButtonText: "Ok",
            closeOnConfirm: true
        });
        return false;
    }
    else if(tc==(r+1)){
        $('.custdelete'+r).closest("tr").remove();
        num--;
    }
    else{
        var k=r;
        $('.custdelete'+k).closest("tr").remove(); 
        for(k=r+1; k<tc; k++){
            $("table.customerdetail tbody > tr > th label[for=pri"+k+"]").attr({for:"pri"+r+""});
            $("table.customerdetail tbody > tr > th input[id=pri"+k+"]").attr({onclick:"getradio("+r+")"});
            $("table.customerdetail tbody > tr > th input[id=pri"+k+"]").attr({id:"pri"+r+""});
            $("table.customerdetail tbody > tr > td[id=username"+k+"]").attr("id", "username"+r);
            $("table.customerdetail tbody > tr > td[id=usermobile"+k+"]").attr("id", "usermobile"+r);
            $("table.customerdetail tbody > tr > td[id=useremail"+k+"]").attr("id", "useremail"+r);
            $("table.customerdetail tbody > tr > td[id=usergender"+k+"]").attr("id", "usergender"+r);
            $("table.customerdetail tbody > tr > td[id=userpitype"+k+"]").attr("id", "userpitype"+r);
            $("table.customerdetail tbody > tr > td[id=userpid"+k+"]").attr("id", "userpid"+r);
            $("table.customerdetail tbody > tr > td[id=userimgfront"+k+"]").attr("id", "userimgfront"+r);
            $("table.customerdetail tbody > tr > td[id=userimgback"+k+"]").attr("id", "userimgback"+r);
            $("table.customerdetail tbody > tr > td[id=userimgguest"+k+"]").attr("id", "userimgguest"+r);
            $("table.customerdetail tbody > tr > td button[id=custdel"+k+"]").attr({onclick:"custdel("+r+")",id:"custdel"+r,class:"btn btn-danger-soft btn-xs custdelete"+r});
            $("table.customerdetail tbody > tr > td[id=userid"+k+"]").attr("id", "userid"+r);
            $("table.customerdetail-1 tbody > tr > th label[for=pri"+k+"]").attr({for:"pri"+r+""});
            $("table.customerdetail-1 tbody > tr > th input[id=pri"+k+"]").attr({onclick:"getradio("+r+")"});
            $("table.customerdetail-1 tbody > tr > th input[id=pri"+k+"]").attr({id:"pri"+r+""});
            $("table.customerdetail-1 tbody > tr > td[id=username"+k+"]").attr("id", "username"+r);
            $("table.customerdetail-1 tbody > tr > td[id=usermobile"+k+"]").attr("id", "usermobile"+r);
            $("table.customerdetail-1 tbody > tr > td[id=useremail"+k+"]").attr("id", "useremail"+r);
            $("table.customerdetail-1 tbody > tr > td[id=usergender"+k+"]").attr("id", "usergender"+r);
            $("table.customerdetail-1 tbody > tr > td[id=userpitype"+k+"]").attr("id", "userpitype"+r);
            $("table.customerdetail-1 tbody > tr > td[id=userpid"+k+"]").attr("id", "userpid"+r);
            $("table.customerdetail-1 tbody > tr > td[id=userimgfront"+k+"]").attr("id", "userimgfront"+r);
            $("table.customerdetail-1 tbody > tr > td[id=userimgback"+k+"]").attr("id", "userimgback"+r);
            $("table.customerdetail-1 tbody > tr > td[id=userimgguest"+k+"]").attr("id", "userimgguest"+r);
            $("table.customerdetail-1 tbody > tr > td button[id=custdel"+k+"]").attr({onclick:"custdel("+r+")",id:"custdel"+r,class:"btn btn-danger-soft btn-xs custdelete"+r});
            $("table.customerdetail-1 tbody > tr > td[id=userid"+k+"]").attr("id", "userid"+r);
            r++;
        }
        num--;
    }
    $('#roomno-1').trigger('change');
    $('#roomno-1').removeClass('is-valid');
    var len = $("table.room-list > tbody").length-2;
    var radioValue = $("input[name='customRadio']:checked").val();
    if(radioValue){
        $("#bookingsave").attr("disabled", false);
        $("#newroom"+len).attr("disabled", false);
    }else{
        $("#bookingsave").attr("disabled", true);
        $("#newroom"+len).attr("disabled", true);
    }
    }
function getradio(nums){
        var trl = $("table.customerdetail-1 tbody tr").length;
        var userid = $("#userid"+nums).text();var name = $("#username"+nums).text();var mobile = $("#usermobile"+nums).text();var email = $("#useremail"+nums).text();
        var lastname = $("#userlastname"+nums).text();var gender = $("#usergender"+nums).text();var father = $("#userfathername"+nums).text();
        var occupation = $("#useroccupation"+nums).text();var dob = $("#userdob"+nums).text();var anniversary = $("#useranniversary"+nums).text();
        var nationality = $("#usernationality"+nums).text();var vip = $("#uservip"+nums).text();var pitype = $("#userpitype"+nums).text();
        var pid = $("#userpid"+nums).text();var imgfront = $("#userimgfront"+nums).text();var imgback = $("#userimgback"+nums).text();var country = $("#usercountry"+nums).text()
        var comments = $("#usercomments"+nums).text();var imgguest = $("#userimgguest"+nums).text();var contacttype = $("#usercontacttype"+nums).text();
        var state = $("#userstate"+nums).text();var city = $("#usercity"+nums).text();var zipcode = $("#userzipcode"+nums).text();var address = $("#useraddress"+nums).text();
        for(var s=0; s<trl; s++){
            if(s!=nums){
                userid += ",".concat($("#userid"+s).text());
                name += ",".concat($("#username"+s).text());
                lastname += ",".concat($("#userlastname"+s).text());
                mobile += ",".concat($("#usermobile"+s).text());
                email += ",".concat($("#useremail"+s).text());
                gender += ",".concat($("#usergender"+s).text());
                pitype += ",".concat($("#userpitype"+s).text());
                pid += ",".concat($("#userpid"+s).text());
                imgfront += ",".concat($("#userimgfront"+s).text());
                imgback += ",".concat($("#userimgback"+s).text());
                imgguest += ",".concat($("#userimgguest"+s).text());
            }
        }
    $("#alluserid").val(userid);$("#alluser").val($.trim(name.replace(/\s+/g, " ")));$("#allmobile").val(mobile);$("#allemail").val(email);$("#alllastname").val(lastname);$("#allgender").val(gender);$("#allcountry").val(country);
    $("#allfather").val(father);$("#alloccupation").val(occupation);$("#alldob").val(dob);$("#allanniversary").val(anniversary);$("#allnationality").val(nationality);
    $("#allvip").val(vip);$("#allcomments").val(comments);$("#allpitype").val(pitype);$("#allpid").val(pid);$("#allimgfront").val(imgfront);$("#allimgback").val(imgback);
    $("#allimgguest").val(imgguest);$("#allcontacttype").val(contacttype);$("#allstate").val(state);$("#allcity").val(city);$("#allzipcode").val(zipcode);$("#alladdress").val(address);
    var len = $("table.room-list > tbody").length;
    var rtype= $("#room_type").find(":selected").val();
    if(rtype==null){
        var rtype= $("#room_type-1").find(":selected").val();
    }
    var rno = $("#roomno").find(":selected").val();
    if(rno==null){
        var rno = $("#roomno-1").find(":selected").val();
    }
    if(rtype && rno && len>=1){
        $('#newroom').attr("disabled", false);
        $('#newroom-1').attr("disabled", false);
        $("#bookingsave").attr("disabled", false);
    }else{
            $('#newroom').attr("disabled", true);
            $('#newroom-1').attr("disabled", true);
            $("#bookingsave").attr("disabled", true);
    }
}

var rcount;
function room(r,s=null){
    $(".newroom"+r).hide();
    $("#bookingsave").attr("disabled", true);
    if(rcount!=-1){
        $("#custdetailbtn-1").hide();
    }
    if(s!=null){
    rcount = r;
    r++;
    }else{
    r++;
    rcount = r;
    }
    var i = rcount;
    var alltype = $("#roomtlist").html();
    var newRoom = $("<tbody>");
    var col = "";
    col = '<tr><th colspan="2">Hall Room Info</th><th>Action</th></tr>';
    col += '<tr><td colspan="2"><table class="table table-borderless mb-0"><tbody><tr>'
           +'<td class="border-0"><div class="form-group mb-0"><label class="font-weight-600 mb-1">Hall Room Type <span class="text-danger">*</span></label>'
           +'<div class="icon-addon addon-md input-left-icon"><select class="selectpicker form-select"data-live-search="true" data-width="100%" onchange="getroomnos('+i+')" id="room_type'+i+'">'
           +'<option value="" selected>Choose Hall Room Type</option>'+alltype+'</select><label class="fas fa-sort-amount-down"></label></div></div></td>'
           +'<td class="border-0"><div class="form-group mb-0"><label class="font-weight-600 mb-1">Hall Room No <span class="text-danger">*</span></label><div class="icon-addon addon-md input-left-icon">'
           +'<select name=roomno[] class="selectpicker form-select"data-live-search="true" data-width="100%"onchange="getcapcitys('+i+')" disabled id="roomno'+i+'">'
           +'<option value="" selected>Choose Hall Room No.</option></select><label class="fas fa-sort-numeric-down"></label></div></div></td><td class="border-0">'
           +'<div class="form-group mb-0"><label class="font-weight-600 mb-1">#People</label><div class="icon-addon addon-md input-left-icon"><input type="number" min="1" disabled class="form-control"'
           +'id="adults'+i+'" placeholder="People"><label class="fas fa-restroom"></label></div></div></td><td class="border-0"><div class="form-group mb-0">'
           +'</tr></tbody></table></td><td rowspan="3" class="text-center"><button type="button" class="btn btn-danger btn-sm rdel'+r+'" onclick="rdel('+r+')"><i class="far fa-trash-alt"></i></button>'
           +'</td></tr>';
    col += '<tr><th><div class="d-flex justify-content-between align-items-center"><span>Customer Info</span><div class="dropdown"><button class="btn btn-sm btn-primary dropdown-toggle no-caret" type="button"'
            +'id="custdetailbtn'+i+'" data-toggle="dropdown" aria-haspopup="true"aria-expanded="false"><i class="fas fa-plus"></i></button><div class="dropdown-menu" aria-labelledby="dropdownMenuButton">'
            +'<a class="dropdown-item" href="javascript:void(0)" data-toggle="modal"data-target="#exampleModal">New Customer</a><a class="dropdown-item" href="javascript:void(0)" data-toggle="modal"data-target="#exampleModal2">Old Customer</a>'
            +'</div></div></div></div></th><th class="rentinfo-seatplan">Seatplan And Rent Info</th></tr>';
    col += '<tr><td><table class="table table-borderless customerdetail'+i+'"><thead><tr><th class="pl-0" width="20">Pri?</th><th>Name</th><th>Mobile No.</th>'
           +'<th class="text-right pr-0">Action</th></tr></thead><tbody></tbody></table></td><td><table class="table table-borderless mb-0 order-list'+i+'">'
           +'<tbody><tr><td class="border-0"><div class="form-group mb-0"><label class="font-weight-600 mb-1">Seatplan</label><div class="icon-addon addon-md">'
           +'<select class="selectpicker form-select" disabled data-live-search="true"data-width="100%" id="seatplan'+i+'"><option value="" selected>Choose Seatplan</option>'
           +'</select><label class="fas fa-chair"></label></div></div></td><td class="border-0"><div class="form-group mb-0"><label class="font-weight-600 mb-1">Hall Room Rent(Hourly)</label><div class="icon-addon addon-md"><input type="number" disabled class="form-control form-control" id="rent'+i+'"'
           +'value="0"></div><div><span class="p-2"><del class="text-danger" id="offer_price'+i+'"></del></span></div></div></td></tr></tbody></table></td></tr>';
    col += '<tr><td class="p-0"></td><td class="p-0"></td><td class="text-center res-v-allign"><button type="button" disabled class="btn btn-primary btn-sm newroom'+r+'" onclick="room('+r+')" id="newroom'+i+'">'
           +'<i class="fas fa-plus"></i></button></td></tr>';
    
    $("#nofroom-1").val((((s!=null)?s:i)+2));
    newRoom.append(col);
    $("table.room-list").append(newRoom);
    if(i>=0){
        $("#nofroom"+i).val('');
    }else{
        $("#nofroom"+i).attr("hidden",false);
    }
    $('.selectpicker').selectpicker('refresh');
    $("table.customerdetail tbody").clone().find('input:radio').attr('name', 'customRadio'+i).end().appendTo("table.customerdetail"+i);
    $("table.customerdetail-1 tbody").clone().find('input:radio').attr('name', 'customRadio'+i).end().appendTo("table.customerdetail"+i);
    if(i==-1){
        $("#nofroomdiv-1").attr("hidden",false);
        $("#custdetailbtn"+i).show();
    }else{
        $("#custdetailbtn"+i).hide();
    }
    $("table.customerdetail"+i+" tbody > tr > th input").attr("name", "customRadio"+i);
    var l = $("table.customerdetail"+i+" tbody tr").length;
    for(var x=0; x<l; x++){
    $("table.customerdetail"+i+" tbody > tr > th label[for=pri"+x+"]").attr({for:"pri"+x+""+i, disabled:"true"});
    $("table.customerdetail"+i+" tbody > tr > th input[id=pri"+x+"]").attr({id:"pri"+x+""+i, disabled:"true"});
    $("table.customerdetail"+i+" tbody > tr > td button").attr("disabled",true);
    $("table.customerdetail tbody > tr > th input[id=pri"+x+"]").attr("disabled",true);
    $("table.customerdetail-1 tbody > tr > th input[id=pri"+x+"]").attr("disabled",true);
    }
    $('.datefilter').on('apply.daterangepicker', function (ev, picker) {
        $('#room_type'+i+' :nth-child(1)').prop('selected', true);
        $('#room_type'+i+'').trigger('change');
    });
    $('#room_type'+i).on('change', function() {
        var roomtype = $('#room_type'+i).find(":selected").val();
        if(roomtype){
            $('.selectpicker').selectpicker('refresh');
            $('#roomno'+i).attr("disabled", false);
            $('#adults'+i).val(0);
            $('#adults'+i).attr("disabled", false);
            $('#children'+i).attr("disabled", false);
            $('#from_date1'+i).attr("disabled", false);
            $('#to_date1'+i).attr("disabled", false);
            
            $('#rent'+i).attr("disabled", false);
            $('#seatplan'+i+' :nth-child(1)').prop('selected', true);
            $('#seatplan'+i).attr("disabled", true);
            $('#roomno'+i).trigger('change');
            $('#seatplan'+i).attr("disabled", false);
            $("#compamount"+i).attr("hidden",true);
            $('#seatplan'+i+' :nth-child(1)').prop('selected', true);
            $('#addrow'+i).attr("disabled", false);
            $('#discountreason').val('');
            $('#discountreason'+i).trigger('change');
            $('#commissionrate').trigger('change');
        }else{
            $('.selectpicker').selectpicker('refresh');
            $('#roomno'+i).attr("disabled", true);
            $('#roomno'+i+' :nth-child(1)').prop('selected', true);
            $('#children'+i).attr("disabled", true);
            $('#children'+i).val(0);
            $('#from_date1'+i).attr("disabled", true);
            $('#to_date1'+i).attr("disabled", true);
            $('#rent'+i).attr("disabled", true);
            $('#seatplan'+i+' :nth-child(1)').prop('selected', true);
            $('#seatplan'+i).attr("disabled", true);
            $('#seatplan'+i).attr("disabled", true);
            $("#compamount"+i).attr("hidden",true);
            $('#seatplan'+i+' :nth-child(1)').prop('selected', true);
            $('#addrow'+i).attr("disabled", true);
            $('#roomno'+i).trigger('change');
        }
    });
    $('#roomno'+i).on('change', function() {
        var roomno = $('#roomno'+i).find(':selected').val();
        if(roomno){
            $('.selectpicker').selectpicker('refresh');
            $('#from_date2'+i).attr("disabled", false);
            $('#to_date2'+i).attr("disabled", false);
            $('#seatplan'+i).attr("disabled", false);
            $('#bed'+i).attr("disabled", false);
            $('#person'+i).attr("disabled", false);
            $('#child1'+i).attr("disabled", false);
            $('#discountreason').val('');
            $('#newroom'+i).attr("disabled", false);
            var radioValue = $("input[name='customRadio']:checked").val();
            if(saveone==1 && i!=-1){
                $("#bookingsave").attr("disabled", false);
            }else if(i==-1){
                if(radioValue){
                    $('#newroom'+i).attr("disabled", false);
                    $("#bookingsave").attr("disabled", false);
                }else{
                    $('#newroom'+i).attr("disabled", true);
                    $("#bookingsave").attr("disabled", true);
                }
            }else{
                saveall=1;
                $("#bookingsave").attr("disabled", true);
            }
        }else{
            $('.selectpicker').selectpicker('refresh');
            $('#adults'+i).attr("disabled", true);
            $('#adults'+i).val(0);
            $('#from_date2'+i).attr("disabled", true);
            $('#to_date2'+i).attr("disabled", true);
            $('#seatplan'+i).attr("disabled", true);
            $('#rent'+i).attr("disabled", true);
            $('#rent'+i).val(0);
            $('#bed'+i).attr("disabled", true);
            $('#bed'+i).val('');
            $('#amount1'+i).attr("disabled", true);
            $('#amount1'+i).val('0');
            $('#person'+i).attr("disabled", true);
            $('#person'+i).val('');
            $('#amount2'+i).attr("disabled", true);
            $('#amount2'+i).val('0');
            $('#child1'+i).attr("disabled", true);
            $('#child1'+i).val('');
            $('#amount3'+i).attr("disabled", true);
            $('#amount3'+i).val('0');
            $('#nofroom'+i).attr("disabled", true);
            $('#newroom'+i).attr("disabled", true);
            saveall=0;
            $("#bookingsave").attr("disabled", true);
            $("#rent"+i).trigger('change');
        }
    });
    var startnew = $("#datefilter1").val();
    var endnew = $("#datefilter2").val();
    $(".datefilter3").val(startnew);
    $(".datefilter4").val(endnew);
    rcount++;

}
function rdel(r) {
    "use strict";
    var t = $("table.room-list > tbody").length;
    if (1 == t){
        swal({
            title: "Failed",
            text: "There only one row you can't delete.",
            type: "error",
            confirmButtonColor: "#28a745",
            confirmButtonText: "Ok",
            closeOnConfirm: true
        });
    }
    else if(t==(r+2)){
        $('.rdel'+r).closest("tbody").remove();
        r--;
        $(".newroom"+r).show();
        $(".newroom"+r).attr("hidden",false);
        if(t==2){
            $("#custdetailbtn").show();
            $("#custdetailbtn-1").show();
            var l = $("table.customerdetail tbody tr").length;
            if(l==null || l==0){
                var l = $("table.customerdetail-1 tbody tr").length;
            }
            for(var x=0; x<l; x++){
                $("table.customerdetail tbody > tr > th input[id=pri"+x+"]").attr("disabled",false);
                $("table.customerdetail-1 tbody > tr > th input[id=pri"+x+"]").attr("disabled",false);
            }
        }
        $('#room_type'+r+1).trigger('change');
        var roomno = $('#roomno-1').find(':selected').val();
        if(roomno == null){
            roomno = $('#roomno'+r-1).find(':selected').val();
        }
        var radioValue = $("input[name='customRadio']:checked").val();
        if(radioValue && roomno){
            $('#newroom').attr("disabled", false);
            $("#bookingsave").attr("disabled", false);
        }else{
            $('#newroom').attr("disabled", true);
            $("#bookingsave").attr("disabled", true);
        }
        $("#nofroom-1").val(rcount==null?1:rcount);
        $("#rent-1").trigger('change');
        rcount--;
    }
    else{
        var l=0;
        for(var k=r; k<t-1; k++){
            l++;
            $('.rdel'+k).closest("tbody").remove(); 
        }
        rcount==null?rcount=r:rcount -= l;
        for(var j=r;j<t-2;j++){
        var s=j-1;
            room(rcount,s);
            if(j!=t-3)
            $(".newroom"+j).hide();
        }
        $('#room_type'+j).trigger('change');
        $("#rent-1").trigger('change');
        $("#nofroom-1").val(rcount==null?1:rcount+1);
    }
}
var count=0;
function addrow(n) {
    var newRow = $("<tr>");
    var cols = "";

    cols += '<td class="border-0 pl-0"><input type="text" class="form-control form-control-xs datefilter3"/></td>';
    cols += '<td class="border-0"><input type="text" class="form-control form-control-xs datefilter4"/></td>';
    cols += '<td class="border-0"><div class="d-flex"> <input type="number" class="form-control form-control-xs" value="0"><div class="dropdown dropdown-custom ml-1" hidden> <button class="btn btn-inverse-soft btn-xs dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Tariff </button><div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton"><table class="table table-sm table-borderless mb-0"><tbody><tr><td class="border-0">Base Rent</td><td class="border-0">0</td></tr><tr><td class="border-0">Net Rent</td><td class="border-0">0</td></tr></tbody></table></div></div></div></td>';

    cols += '<td class="border-0 pr-0 text-right"><button type="button" onmouseenter="delrow('+n+')" class="ibtnDel'+n+' btn btn-danger-soft btn-xs"><i class="far fa-times-circle"></i></button></td>';
    newRow.append(cols);
    $("table.order-list"+n).append(newRow);
    count++;
}
function delrow(n) {
    $("table.order-list"+n).on("click", ".ibtnDel"+n, function (event) {
    $(this).closest("tr").remove();
    count -= 1;
    });
}
function getroomnos(n){
    'use strict';
    var allroom = $("#roomno").val();
    if(allroom==null){
        allroom = $("#roomno-1").val();
    }
    var all = $("table.room-list > tbody").length;
    for(var s=0; s<all-1; s++){
        allroom += ",".concat($("#roomno"+s).val());
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
    }else{
        $("#datefilter1").removeClass("is-invalid");
        $("#datefilter2").removeClass("is-invalid");
        $("#datefilter1").addClass("is-valid");
        $("#datefilter2").addClass("is-valid");
    }
    var room_type= $("#room_type"+n).find(":selected").val();
    if(room_type==""){
        $("#room_type"+n).removeClass("is-valid");
        $("#room_type"+n).closest('div').removeClass("is-valid");
        $("#room_type"+n).addClass("is-invalid");
        $("#roomno"+n).removeClass("is-valid");
        $("#roomno"+n).closest('div').removeClass("is-valid");
        return false;
    }else{
        $("#room_type"+n).removeClass("is-invalid");
        $("#room_type"+n).addClass("is-valid");
    }
    var csrf = $('#csrf_token').val();
    var myurl=baseurl+"hall_room/hallroom/checknewroom";
      if($('#roomno'+n)[0].options.length>1)
      $('#roomno'+n).find('option').not(':first').remove();
    $.ajax({
          url: myurl,
          type: "POST",
          data: {csrf_test_name: csrf, room_type: room_type,datefilter1: datefilter1,datefilter2: datefilter2},
          success: function(data) {
            var obj = JSON.parse(data);
            var rlen = obj.roomno;
            for (var i = 0; i < rlen.length; i++) {
                var split_room = allroom.split(",");
                if (split_room.indexOf("" + obj.roomno[i] + "") == -1) {
                    $('#roomno'+n).append('<option value="' + obj.roomno[i] + '">' + obj.roomno[i] + '</option>');
                }
            }
            $('.selectpicker').selectpicker('refresh');
            $("#bed"+n).prop("disabled",true);
            $("#child1"+n).prop("disabled",true);
            $("#person"+n).prop("disabled",true);
            $("#offer_price"+n).attr('hidden', true);
        }
    });
  var i =n;
  $('.datefilter').on('apply.daterangepicker', function (ev, picker) {
    $('#room_type'+i+' :nth-child(1)').prop('selected', true);
    $('#room_type'+i+'').trigger('change');
  });
  $('#room_type'+i).on('change', function() {
    var roomtype = $('#room_type'+i).find(":selected").val();
    if(roomtype){
        $('.selectpicker').selectpicker('refresh');
        $('#roomno'+i).attr("disabled", false);
        $('#adults'+i).val(0);
        $('#adults'+i).attr("disabled", false);
        $('#children'+i).attr("disabled", false);
        $('#from_date1'+i).attr("disabled", false);
        $('#to_date1'+i).attr("disabled", false);
        $('#rent'+i).attr("disabled", false);
        $('#seatplan'+i+' :nth-child(1)').prop('selected', true);
        $('#seatplan'+i).attr("disabled", true);
        $('#roomno'+i).trigger('change');
        $('#seatplan'+i).attr("disabled", false);
        $("#compamount"+i).attr("hidden",true);
        $('#seatplan'+i+' :nth-child(1)').prop('selected', true);
        $('#addrow'+i).attr("disabled", false);
        $('#discountreason').val('');
        $('#discountreason'+i).trigger('change');
        $('#commissionrate').trigger('change');
    }else{
        $('.selectpicker').selectpicker('refresh');
        $('#roomno'+i).attr("disabled", true);
        $('#roomno'+i+' :nth-child(1)').prop('selected', true);
        $('#children'+i).attr("disabled", true);
        $('#children'+i).val(0);
        $('#from_date1'+i).attr("disabled", true);
        $('#to_date1'+i).attr("disabled", true);
        $('#rent'+i).attr("disabled", true);
        $('#seatplan'+i+' :nth-child(1)').prop('selected', true);
        $('#seatplan'+i).attr("disabled", true);
        $('#seatplan'+i).attr("disabled", true);
        $("#compamount"+i).attr("hidden",true);
        $('#seatplan :nth-child(1)'+i).prop('selected', true);
        $('#addrow'+i).attr("disabled", true);
        $('#roomno'+i).trigger('change');
    }
});
$('#roomno'+i).on('change', function() {
    var roomno = $('#roomno'+i).find(':selected').val();
    if(roomno){
        $('.selectpicker').selectpicker('refresh');
        $('#from_date2'+i).attr("disabled", false);
        $('#to_date2'+i).attr("disabled", false);
        $('#seatplan'+i).attr("disabled", false);
        $('#bed'+i).attr("disabled", false);
        $('#person'+i).attr("disabled", false);
        $('#child1'+i).attr("disabled", false);
        $('#discountreason').val('');
        $('#newroom'+i).attr("disabled", false);
        var radioValue = $("input[name='customRadio']:checked").val();
        if(saveone==1 && i!=-1){
            $("#bookingsave").attr("disabled", false);
        }else if(i==-1){
            if(radioValue){
                $('#newroom'+i).attr("disabled", false);
                $("#bookingsave").attr("disabled", false);
            }else{
                $('#newroom'+i).attr("disabled", true);
                $("#bookingsave").attr("disabled", true);
            }
        }else{
            saveall=1;
            $("#bookingsave").attr("disabled", true);
        }
    }else{
        $('.selectpicker').selectpicker('refresh');
        $('#adults'+i).attr("disabled", true);
        $('#adults'+i).val(0);
        $('#from_date2'+i).attr("disabled", true);
        $('#seatplan'+i).attr("disabled", true);
        $('#to_date2'+i).attr("disabled", true);
        $('#rent'+i).attr("disabled", true);
        $('#rent'+i).val(0);
        $('#bed'+i).attr("disabled", true);
        $('#bed'+i).val('');
        $('#amount1'+i).attr("disabled", true);
        $('#amount1'+i).val('0');
        $('#person'+i).attr("disabled", true);
        $('#person'+i).val('');
        $('#amount2'+i).attr("disabled", true);
        $('#amount2'+i).val('0');
        $('#child1'+i).attr("disabled", true);
        $('#child1'+i).val('');
        $('#amount3'+i).attr("disabled", true);
        $('#amount3'+i).val('0');
        $('#nofroom'+i).attr("disabled", true);
        $('#newroom'+i).attr("disabled", true);
        saveall=0;
        $("#bookingsave").attr("disabled", true);
        $("#rent"+i).trigger('change');
    }
});
}
function getcapcitys(n){
    'use strict';
    var roomno= $("#roomno"+n).find(":selected").val();
    var room_type= $("#room_type"+n).find(":selected").val();
    if(roomno==""){
        if(room_type!=""){
        $("#roomno"+n).removeClass("is-valid");
        $("#roomno"+n).closest('div').removeClass("is-valid");
        $("#roomno"+n).addClass("is-invalid");
        return false;
        }
        return false;
    }else{
        $("#roomno"+n).removeClass("is-invalid");
        $("#roomno"+n).closest('div').removeClass("is-invalid");
        $("#roomno"+n).addClass("is-valid");
    }
    var csrf = $('#csrf_token').val();
    var start = $("#datefilter1").val();
    var end = $("#datefilter2").val();
var date1Value = $('#datefilter1').val().split(' ')[0];
var date2Value = $('#datefilter2').val().split(' ')[0];
   var difference = Math.abs(parseFloat(date2Value) - parseFloat(date1Value));

    // Convert the difference from milliseconds to days
   
    var myurl=baseurl+"hall_room/hallroom/getcapacity";
    if ($('#seatplan'+n)[0].options.length > 1)
        $('#seatplan'+n).find('option').not(':first').remove();
    $.ajax({
        url: myurl,
        type: "POST",
        data: {csrf_test_name: csrf,start: start,end: end, roomno: roomno, room_type: room_type},
        success: function(data) {
            var obj =JSON.parse(data);
          
    //         var date1 = new Date($('#datefilter1').val());
    // var date2 = new Date($('#datefilter2').val());
    // var difference = Math.abs(date2 - date1);
    // var days_difference = Math.ceil(difference / (1000 * 60 * 60 * 24));
    var rent = (obj.hallData.rate) * (difference);

    $("#rent-1").val(rent); // Set the rent field value
    var all = $("table.room-list > tbody tr").length;
    var baseRent = parseFloat($("#rent"+n).val()) || 0;
    var totalCGSTPercentage = parseFloat($("#cgst").val()) || 0;
    var totalSGSTPercentage = parseFloat($("#sgst").val()) || 0;
    // var alltotal = parseFloat($(".get_total").val()) || 0;

    for (var s = 1; s <= all; s++) {
        var rentValue = parseFloat($("#rent" + s).val()) || 0;
        if (!isNaN(rentValue)) {
            baseRent += rentValue;
        }
    }

    var totalCGST = (totalCGSTPercentage * baseRent) / 100;
    var totalSGST = (totalSGSTPercentage * baseRent) / 100;

    var grandTotal = baseRent + totalCGST + totalSGST;

    $("#totalamount").val(grandTotal.toFixed(2));
    $(".cgst_charge").val(totalCGST.toFixed(2));
    $(".sgst_charge").val(totalSGST.toFixed(2));
    $(".cgst_charge").text(totalCGST.toFixed(2));
    $(".sgst_charge").text(totalSGST.toFixed(2));
    // Update the payload data
    // $("#t_amount").val(rent); // Assuming t_amount corresponds to the rent in your payload
    // payloadData.t_amount = rent;

    // Trigger change event for any dependent fields
    $('#commissionrate').trigger('change');
    $("#rent-1").trigger('change');
    $('.selectpicker').selectpicker('refresh');
           
    }
    });
}
function bedprices(n){
    'use strict';
    var room_type= $("#room_type"+n).find(":selected").val();
    if(room_type==""){
    $("#msg1").text("Room type field is required");
    return false;
    }
    var bed= $("#bed"+n).val();
    var csrf = $('#csrf_token').val();
    var myurl=baseurl+"hall_room/hallroom/bedprice";
    $.ajax({
        url: myurl,
        type: "POST",
        data: {csrf_test_name: csrf, room_type: room_type, bed:bed},
        success: function(data) {
            var obj =JSON.parse(data);
            $("#amount1"+n).val(obj.bedrate);
    }
    });
}
function personprices(n){
    'use strict';
    var room_type= $("#room_type"+n).find(":selected").val();
    if(room_type==""){
    $("#msg1").text("Room type field is required");
    return false;
    }
    var person= $("#person"+n).val();
    var csrf = $('#csrf_token').val();
    var myurl=baseurl+"hall_room/hallroom/personprice";
    $.ajax({
        url: myurl,
        type: "POST",
        data: {csrf_test_name: csrf, room_type: room_type, person:person},
        success: function(data) {
            var obj =JSON.parse(data);
            $("#amount2"+n).val(obj.personrate);
    }
    });
}
function childprices(n){
    'use strict';
    var room_type= $("#room_type"+n).find(":selected").val();
    if(room_type==""){
    $("#msg1").text("Room type field is required");
    return false;
    }
    var child= $("#child1"+n).val();
    var csrf = $('#csrf_token').val();
    var myurl=baseurl+"hall_room/hallroom/childprice";
    $.ajax({
        url: myurl,
        type: "POST",
        data: {csrf_test_name: csrf, room_type: room_type, child:child},
        success: function(data) {
            var obj =JSON.parse(data);
            $("#amount3"+n).val(obj.childrate);
    }
    });
}
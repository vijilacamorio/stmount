"use strict"
var base_url = $("#base_url").val();
function fileValueOne(value) {
    'use strict';
    var path = value.value;
    var extenstion = path.split('.').pop();
    if (extenstion == "jpg" || extenstion == "svg" || extenstion == "jpeg" || extenstion == "png" || extenstion == "gif") {
        document.getElementById('image-preview').src = window.URL.createObjectURL(value.files[0]);
        var filename = path.replace(/^.*[\\\/]/, '').split('.').slice(0, -1).join('.');
        document.getElementById("filename").innerHTML = filename;
        var fd = new FormData();
        var CSRF_TOKEN = $('#csrf_token').val();
        fd.append('img', $('#imgfront')[0].files[0]);
        fd.append('csrf_test_name', CSRF_TOKEN);
        $.ajax({
            url: base_url + "room_reservation/room_reservation/imageupload",
            type: "POST",
            data: fd,
            enctype: 'multipart/form-data',
            processData: false,
            contentType: false,
            success: function (r) {
                $("#imgffront").val(r);
            }
        });
    } else {
        swal({
            title: "Failed",
            text: "File not supported. Kindly Upload the Image of below given extension",
            type: "error",
            confirmButtonColor: "#28a745",
            confirmButtonText: "Ok",
            closeOnConfirm: true
        });
    }
}
function fileValuesTwo(value) {
    'use strict';
    var path = value.value;
    var extenstion = path.split('.').pop();
    if (extenstion == "jpg" || extenstion == "svg" || extenstion == "jpeg" || extenstion == "png" || extenstion == "gif") {
        document.getElementById('image-preview2').src = window.URL.createObjectURL(value.files[0]);
        var filename = path.replace(/^.*[\\\/]/, '').split('.').slice(0, -1).join('.');
        document.getElementById("filename2").innerHTML = filename;
        var fd = new FormData();
        var CSRF_TOKEN = $('#csrf_token').val();
        fd.append('img', $('#imgback')[0].files[0]);
        fd.append('csrf_test_name', CSRF_TOKEN);
        $.ajax({
            url: base_url + "room_reservation/room_reservation/imageupload",
            type: "POST",
            data: fd,
            enctype: 'multipart/form-data',
            processData: false,
            contentType: false,
            success: function (r) {
                $("#imgbback").val(r);
            }
        });
    } else {
        swal({
            title: "Failed",
            text: "File not supported. Kindly Upload the Image of below given extension",
            type: "error",
            confirmButtonColor: "#28a745",
            confirmButtonText: "Ok",
            closeOnConfirm: true
        });
    }
}
function fileValuesThree(value) {
    'use strict';
    var path = value.value;
    var extenstion = path.split('.').pop();
    if (extenstion == "jpg" || extenstion == "svg" || extenstion == "jpeg" || extenstion == "png" || extenstion == "gif") {
        document.getElementById('image-preview3').src = window.URL.createObjectURL(value.files[0]);
        var filename = path.replace(/^.*[\\\/]/, '').split('.').slice(0, -1).join('.');
        document.getElementById("filename3").innerHTML = filename;
        var fd = new FormData();
        var CSRF_TOKEN = $('#csrf_token').val();
        fd.append('img', $('#imgguest')[0].files[0]);
        fd.append('csrf_test_name', CSRF_TOKEN);
        $.ajax({
            url: base_url + "room_reservation/room_reservation/imageupload",
            type: "POST",
            data: fd,
            enctype: 'multipart/form-data',
            processData: false,
            contentType: false,
            success: function (r) {
                $("#imggguest").val(r);
            }
        });
    } else {
        swal({
            title: "Failed",
            text: "File not supported. Kindly Upload the Image of below given extension",
            type: "error",
            confirmButtonColor: "#28a745",
            confirmButtonText: "Ok",
            closeOnConfirm: true
        });
    }
}
$(document).ready(function () {
    "use strict";
    
    $('.selectpicker').selectpicker();
    $('.testselect2').SumoSelect({
        search: true,
        placeholder: 'Room Select',
        csvDispCount: 5
    });

    $('.datefilter').daterangepicker({
        "singleDatePicker": true,
        "showDropdowns": true,
        "timePicker": true,
        startDate: moment(),
        endDate: moment().add(32, 'hour'),
        minDate: moment().startOf('day'),
        locale: {
            format: 'DD-MM-YYYY HH:mm' // Date format: DD-MM-YYYY
        }
    });

    var nowDate = new Date();
    var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), nowDate.getHours(), nowDate.getMinutes(), 0, 0);
    var intime = $("#intime").val();
    var out = $("#outtime").val();
    var utime = new Date(out);
    utime.setDate(utime.getDate() + 1);
    var tt = new Date(out).toTimeString().substr(0, 8);
    var dd = utime.getDate();
    var mm = utime.getMonth() + 1;
    var y = utime.getFullYear();
    var outtime = y + '-' + mm + '-' + dd + ' ' + tt;
    var findate = $("#findate").val();
    
    $("#datefilter1").val(intime);
    $("#datefilter2").val(outtime);
    
    $('.datefilter2').daterangepicker({
        "singleDatePicker": true,
        "showDropdowns": true,
        autoUpdateInput: false,
        timePicker: true,
        timePicker24Hour: true, // 24-hour format
        timePickerIncrement: 15,
        locale: {
            cancelLabel: 'Clear',
            format: 'DD-MM-YYYY HH:mm' // Date format: DD-MM-YYYY
        }
    });
    $('.datefilter2').on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format('DD-MM-YYYY HH:mm'));
    });
    $('.datefilter2').on('cancel.daterangepicker', function (ev, picker) {
        $(this).val('');
    });

    var start = $("#datefilter1").val();
    var end = $("#datefilter2").val();
    $("#datefilter1").on("change", function () {
        var start = $("#datefilter1").val();
        var end = moment(start).add(32, 'hour');
        $('.datefilter3').daterangepicker({
            "singleDatePicker": true,
            "showDropdowns": true,
            "timePicker": true,
            startDate: moment(),
            endDate: end,
            minDate: moment().startOf('day'),
            'maxDate': end,
            locale: {
                format: 'DD-MM-YYYY HH:mm' // Date format: DD-MM-YYYY
            }
        });
        $(".datefilter3").val(start);
        $('.datefilter4').daterangepicker({
            "singleDatePicker": true,
            "showDropdowns": true,
            "timePicker": true,
            startDate: moment(),
            endDate: end,
            minDate: moment().startOf('day'),
            'maxDate': end,
            locale: {
                format: 'DD-MM-YYYY HH:mm' // Date format: DD-MM-YYYY
            }
        });
        $(".datefilter4").val(end.format('DD-MM-YYYY HH:mm'));
    });
    $("#datefilter2").on("change", function () {
        end = $("#datefilter2").val();
        $('.datefilter4').daterangepicker({
            "singleDatePicker": true,
            "showDropdowns": true,
            "timePicker": true,
            startDate: moment(),
            endDate: moment().add(32, 'hour'),
            minDate: moment().startOf('day'),
            'maxDate': end,
            locale: {
                format: 'DD-MM-YYYY HH:mm' // Date format: DD-MM-YYYY
            }
        });
        $(".datefilter4").val(end);
    });
    $('.datefilter3').daterangepicker({
        "singleDatePicker": true,
        "showDropdowns": true,
        "timePicker": true,
        startDate: moment(),
        endDate: moment().add(32, 'hour'),
        minDate: moment().startOf('day'),
        'maxDate': end,
        locale: {
            format: 'DD-MM-YYYY HH:mm' // Date format: DD-MM-YYYY
        }
    });
    $('.datefilter4').daterangepicker({
        "singleDatePicker": true,
        "showDropdowns": true,
        "timePicker": true,
        startDate: moment(),
        endDate: moment().add(32, 'hour'),
        minDate: moment().startOf('day'),
        'maxDate': end,
        locale: {
            format: 'DD-MM-YYYY HH:mm' // Date format: DD-MM-YYYY
        }
    });
    $("#from_date1").val(start);
    $("#to_date1").val(end);
    $("#from_date2").val(start);
    $("#to_date2").val(end);

    // Table Add row
    var counter = 0;

    $("#addrow").on("click", function () {
        var newRow = $("<tr>");
        var cols = "";

        cols += '<td class="border-0 pl-0"><input type="text" class="form-control form-control-xs datefilter2"/></td>';
        cols += '<td class="border-0"><input type="text" class="form-control form-control-xs datefilter2"/></td>';
        cols += '<td class="border-0"><div class="d-flex"> <input type="number" class="form-control form-control-xs" value="0"><div class="dropdown dropdown-custom ml-1"> <button class="btn btn-inverse-soft btn-xs dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Tariff </button><div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton"><table class="table table-sm table-borderless mb-0"><tbody><tr><td class="border-0">Base Rent</td><td class="border-0">0</td></tr><tr><td class="border-0">Net Rent</td><td class="border-0">0</td></tr></tbody></table></div></div></div></td>';
        cols += '<td class="border-0 pr-0 text-right"><button type="button" class="ibtnDel btn btn-danger-soft btn-xs"><i class="far fa-times-circle"></i></button></td>';
        newRow.append(cols);
        $("table.order-list").append(newRow);
        counter++;
    });

    $("table.order-list").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();
        counter -= 1;
    });

    function calculateRow(row) {
        var price = +row.find('input[name^="price"]').val();
    }

    function calculateGrandTotal() {
        var grandTotal = 0;
        $("table.order-list").find('input[name^="price"]').each(function () {
            grandTotal += +$(this).val();
        });
        $("#grandtotal").text(grandTotal.toFixed(2));
    }
});

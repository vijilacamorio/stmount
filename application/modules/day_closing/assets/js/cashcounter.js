// JavaScript Document
"use strict";
var baseurl = $("#base_url").val();
function editmenu(counter,ccid){
	$("#counter").val(counter);
	$("#btnchnage").show();
	$("#btnchnage").text("Update");
	$("#upbtn").show();
	$('#menuurl').attr('action', baseurl+"day_closing/cashregister/editcounter/"+ccid);
	$(window).scrollTop(0);
	}
$(document).ready(function(){
	   "use strict";
        $(".payment_button").click(function(){
            $(".payment_method").toggle();

            //Select Option
            $("select.form-control:not(.dont-select-me)").select2({
                placeholder: "Select option",
                allowClear: true
            });
        });
		$("#card_type").on('change', function(){ 
			var cardtype=$("#card_type").val();
			if(cardtype==1){
				$("#cholder").show();
				$("#cnumber").show();
				}
			else{
				$("#cholder").hide();
				$("#cnumber").hide();
				}
		});
    });
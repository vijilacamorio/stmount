  // JavaScript Document
  $(document).ready(function () {
    "use strict";
    // select 2 dropdown 
    $("select.form-control:not(.dont-select-me)").select2({
        placeholder: "Select option",
        allowClear: true
    });
});
"use strict";
 function changedue(){
     debugger;
		var main=$("#totalamount_marge").val();
		var paid=$("#paidamount_marge").val();
		var change=main-paid;
		$("#change").val(Math.round(customRound(change)));
	}
	function customRound(amount) {
    debugger;
  const integerPart = Math.floor(amount);
const fractionalPart = amount - integerPart;
let roundedAmount;
  if (fractionalPart <= 0.49) {
    roundedAmount = integerPart;
  } else {
    roundedAmount = integerPart + 1;
  }
  return roundedAmount.toFixed(2);
}
function changetype(){
	var distypech=$("#discountttch").val();
	if(distypech==0){
	}
	else{
		var thistype="%";
		}
	$("#chty").text(thistype);
	$("#discounttype").val(distypech);
	$("#discount").val('');
	$( "#discount" ).trigger("change");
	}
	$('body').on('change', '#discount', function(e){
	//	debugger;
            var discount = $("#discount").val();
			
		var total = parseFloat($("#ordertotal").val()-discount);

			// Calculate 5% GST
			var gst = total * 0.05;
			var due=$("#orderdue").val();
			if(discount>0){
				 $("#totalamount_marge").text(total);
				 $("#due-amount").text(due);
				 $("#grandtotal").val(total+gst);
				  $("#gst").val(gst);
				   $("#d_amt").val(total-discount);
				 $("#granddiscount").val(discount);
				 $(".firstpay").val(total);
				}
			 else{
				 
					  var totaldis=discount;
					  
					 var afterdiscount=parseFloat(total-totaldis);
					 var newtotal=afterdiscount.toFixed(2);
					 var granddiscount=parseFloat(totaldis);
				 $("#totalamount_marge").text(newtotal);
				 $("#paidamount_marge").val(customRound(newtotal));
				 $("#gst").val(gst);
				    $("#d_amt").val(total-discount);
				 $("#grandtotal").val(newtotal);
				 $("#due-amount").text(newtotal);
				 $("#granddiscount").val(granddiscount.toFixed(2));				 
				 }
		$("#adddiscount").addClass('display-none');
		$("#add_new_payment").empty();
});
$('body').on('click','#paymentnow',function(){
         $("#adddiscount").removeClass('display-none');
        });
$('input[type="checkbox"]').click(function(){
			if($(this).is(":checked")){
				var test =$('input[name="redeemit"]:checked').val();
				$("#isredeempoint").val(test);
				}
			else{
				$("#isredeempoint").val('');
				}		
		});
// 		}
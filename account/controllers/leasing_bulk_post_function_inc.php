<script type="text/javascript">
function loadCalc() {
var error = false;


var amount1 = isNaN(parseFloat(document.getElementById('amount1').value))? 0 : parseFloat(document.getElementById('amount1').value);
var amount2 = isNaN(parseFloat(document.getElementById('amount2').value))? 0 : parseFloat(document.getElementById('amount2').value);
var amount3 = isNaN(parseFloat(document.getElementById('amount3').value))? 0 : parseFloat(document.getElementById('amount3').value);
var amount4 = isNaN(parseFloat(document.getElementById('amount4').value))? 0 : parseFloat(document.getElementById('amount4').value);
var amount5 = isNaN(parseFloat(document.getElementById('amount5').value))? 0 : parseFloat(document.getElementById('amount5').value);
var amount6 = isNaN(parseFloat(document.getElementById('amount6').value))? 0 : parseFloat(document.getElementById('amount6').value);
var amount7 = isNaN(parseFloat(document.getElementById('amount7').value))? 0 : parseFloat(document.getElementById('amount7').value);
var amount8 = isNaN(parseFloat(document.getElementById('amount8').value))? 0 : parseFloat(document.getElementById('amount8').value);
var amount9 = isNaN(parseFloat(document.getElementById('amount9').value))? 0 : parseFloat(document.getElementById('amount9').value);
var amount10 = isNaN(parseFloat(document.getElementById('amount10').value))? 0 : parseFloat(document.getElementById('amount10').value);
var amount11 = isNaN(parseFloat(document.getElementById('amount11').value))? 0 : parseFloat(document.getElementById('amount11').value);
var amount12 = isNaN(parseFloat(document.getElementById('amount12').value))? 0 : parseFloat(document.getElementById('amount12').value);


var monthly_expected = document.getElementById('monthly_expected').value;

if (amount1 != "" || amount1.length > 0) {	
	$("#row_month2").show();
} else {
	$("#row_month2").hide();
	$("#row_month3").hide();
	$("#row_month4").hide();
	$("#row_month5").hide();
	$("#row_month6").hide();
	$("#row_month7").hide();
	$("#row_month8").hide();
	$("#row_month9").hide();
	$("#row_month10").hide();
	$("#row_month11").hide();
	$("#row_month12").hide();
}

if (amount2 != "" || amount2.length > 0) {	
	$("#row_month3").show();
} else {
	$("#row_month3").hide();
	$("#row_month4").hide();
	$("#row_month5").hide();
	$("#row_month6").hide();
	$("#row_month7").hide();
	$("#row_month8").hide();
	$("#row_month9").hide();
	$("#row_month10").hide();
	$("#row_month11").hide();
	$("#row_month12").hide();
}

if (amount3 != "" || amount3.length > 0) {	
	$("#row_month4").show();
} else {
	$("#row_month4").hide();
	$("#row_month5").hide();
	$("#row_month6").hide();
	$("#row_month7").hide();
	$("#row_month8").hide();
	$("#row_month9").hide();
	$("#row_month10").hide();
	$("#row_month11").hide();
	$("#row_month12").hide();
}


if (amount4 != "" || amount4.length > 0) {	
	$("#row_month5").show();
} else {
	$("#row_month5").hide();
	$("#row_month6").hide();
	$("#row_month7").hide();
	$("#row_month8").hide();
	$("#row_month9").hide();
	$("#row_month10").hide();
	$("#row_month11").hide();
	$("#row_month12").hide();
}


if (amount5 != "" || amount5.length > 0) {	
	$("#row_month6").show();
} else {
	$("#row_month6").hide();
	$("#row_month7").hide();
	$("#row_month8").hide();
	$("#row_month9").hide();
	$("#row_month10").hide();
	$("#row_month11").hide();
	$("#row_month12").hide();
}


if (amount6 != "" || amount6.length > 0) {	
	$("#row_month7").show();
} else {
	$("#row_month7").hide();
	$("#row_month8").hide();
	$("#row_month9").hide();
	$("#row_month10").hide();
	$("#row_month11").hide();
	$("#row_month12").hide();
}


if (amount7 != "" || amount7.length > 0) {	
	$("#row_month8").show();
} else {
	$("#row_month8").hide();
	$("#row_month9").hide();
	$("#row_month10").hide();
	$("#row_month11").hide();
	$("#row_month12").hide();
}


if (amount8 != "" || amount8.length > 0) {	
	$("#row_month9").show();
} else {
	$("#row_month9").hide();
	$("#row_month10").hide();
	$("#row_month11").hide();
	$("#row_month12").hide();
}


if (amount9 != "" || amount9.length > 0) {	
	$("#row_month10").show();
} else {
	$("#row_month10").hide();
	$("#row_month11").hide();
	$("#row_month12").hide();
}

if (amount10 != "" || amount10.length > 0) {	
	$("#row_month11").show();
} else {
	$("#row_month11").hide();
	$("#row_month12").hide();
}

if (amount11 != "" || amount11.length > 0) {	
	$("#row_month12").show();
} else {
	$("#row_month12").hide();
}



var total_amount = (amount1 + amount2 + amount3 + amount4 + amount5 + amount6 + amount7 + amount8 + amount9 + amount10 + amount11 + amount12);
document.getElementById('amount_paid').value = total_amount;


$(document).ready(function() {
   $('input').change(function()  {
      var count = 0;
      $("input").each(function() {           
         if($(this).val() == "0"){
			count = 1;
         }
       });          

       if(count == 0){
				$("#btn_post").removeAttr("disabled","disabled");
            }
        else {
				$("#btn_post").attr("disabled","disabled");                          
            }
      });
   });
}
</script>
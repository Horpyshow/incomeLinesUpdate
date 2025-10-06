<script type="text/javascript">
function loadCalc() {
var error = false;


//get variables from month option button.
var month1 = document.getElementById('month1').value;
var month2 = document.getElementById('month2').value;
var month3 = document.getElementById('month3').value;
var month4 = document.getElementById('month4').value;
var month5 = document.getElementById('month5').value;
var month6 = document.getElementById('month6').value;
var month7 = document.getElementById('month7').value;
var month8 = document.getElementById('month8').value;
var month9 = document.getElementById('month9').value;
var month10 = document.getElementById('month10').value;
var month11 = document.getElementById('month11').value;
var month12 = document.getElementById('month12').value;


//get variables from year option button
var year1 = document.getElementById('year1').value;
var year2 = document.getElementById('year2').value;
var year3 = document.getElementById('year3').value;
var year4 = document.getElementById('year4').value;
var year5 = document.getElementById('year5').value;
var year6 = document.getElementById('year6').value;
var year7 = document.getElementById('year7').value;
var year8 = document.getElementById('year8').value;
var year9 = document.getElementById('year9').value;
var year10 = document.getElementById('year10').value;
var year11 = document.getElementById('year11').value;
var year12 = document.getElementById('year12').value;


//Concantenate month and year
var monthyear1 = month1 + ' ' + year1;
var monthyear2 = month2 + ' ' + year2;
var monthyear3 = month3 + ' ' + year3;
var monthyear4 = month4 + ' ' + year4;
var monthyear5 = month5 + ' ' + year5;
var monthyear6 = month6 + ' ' + year6;
var monthyear7 = month7 + ' ' + year7;
var monthyear8 = month8 + ' ' + year8;
var monthyear9 = month9 + ' ' + year9;
var monthyear10 = month10 + ' ' + year10;
var monthyear11 = month11 + ' ' + year11;
var monthyear12 = month12 + ' ' + year12;


//Assign concantenated result to a textfield
document.getElementById('month_year1').value = monthyear1;
document.getElementById('month_year2').value = monthyear2;
document.getElementById('month_year3').value = monthyear3;
document.getElementById('month_year4').value = monthyear4;
document.getElementById('month_year5').value = monthyear5;
document.getElementById('month_year6').value = monthyear6;
document.getElementById('month_year7').value = monthyear7;
document.getElementById('month_year8').value = monthyear8;
document.getElementById('month_year9').value = monthyear9;
document.getElementById('month_year10').value = monthyear10;
document.getElementById('month_year11').value = monthyear11;
document.getElementById('month_year12').value = monthyear12;


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

document.getElementById('amount1').onchange = function(){
	if(this.value > parseFloat(monthly_expected)){
	   this.value = '';
	}
}

document.getElementById('amount2').onchange = function(){
	if(this.value > parseFloat(monthly_expected)){
	   this.value = '';
	}
}

document.getElementById('amount3').onchange = function(){
	if(this.value > parseFloat(monthly_expected)){
	   this.value = '';
	}
}

document.getElementById('amount4').onchange = function(){
	if(this.value > parseFloat(monthly_expected)){
	   this.value = '';
	}
}

document.getElementById('amount5').onchange = function(){
	if(this.value > parseFloat(monthly_expected)){
	   this.value = '';
	}
}

document.getElementById('amount6').onchange = function(){
	if(this.value > parseFloat(monthly_expected)){
	   this.value = '';
	}
}

document.getElementById('amount7').onchange = function(){
	if(this.value > parseFloat(monthly_expected)){
	   this.value = '';
	}
}

document.getElementById('amount8').onchange = function(){
	if(this.value > parseFloat(monthly_expected)){
	   this.value = '';
	}
}

document.getElementById('amount9').onchange = function(){
	if(this.value > parseFloat(monthly_expected)){
	   this.value = '';
	}
}

document.getElementById('amount10').onchange = function(){
	if(this.value > parseFloat(monthly_expected)){
	   this.value = '';
	}
}

document.getElementById('amount11').onchange = function(){
	if(this.value > parseFloat(monthly_expected)){
	   this.value = '';
	}
}

document.getElementById('amount12').onchange = function(){
	if(this.value > parseFloat(monthly_expected)){
	   this.value = '';
	}
}


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
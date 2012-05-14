/**
 * rex_ajax.js
 *
 * Beispiel für Autofill Ort bei Eingabe einer Postleitzahl
 *
 */

$(document).ready(function(){
 
	// Bankleitzahlen
	$("p.formlabel-blz").children('input').focus(function(){
		if($("p.formlabel-blz").children('input').val()==''){
			$("p.formlabel-bank").children('input').val('');
		}
	});
	$("p.formlabel-blz").children('input').blur(function(){
		if($("p.formlabel-blz").children('input').val()==''){
			$("p.formlabel-bank").children('input').val('');
		}
	});
	
	$("p.formlabel-blz").children('input').autocomplete('index.php?rex_ajax=bank_autofill', {
		delay: 200,
		width: 350,
		max: 50,
		multiple: false,
		minChars: 1,
		mustMatch: true,
		matchContains: false,
		formatItem: 
			function(row){
				return row[0] + " - <strong>" + row[1] + "</strong>";
			},
		formatResult: 
			function(row){
				return row[0];
			}
	});

	$("p.formlabel-blz").children('input').result(function(event, data, formatted){
		$("p.formlabel-bank").children('input').val(data[1]);
	});
	
	// Postleitzahlen
	$("p.formlabel-zip").children('input').focus(function(){
		if($("p.formlabel-zip").children('input').val()==''){
			$("p.formlabel-city").children('input').val('');
		}
	});
	$("p.formlabel-zip").children('input').blur(function(){
		if($("p.formlabel-zip").children('input').val()==''){
			$("p.formlabel-city").children('input').val('');
		}
	});
	
	$("p.formlabel-zip").children('input').autocomplete('index.php?rex_ajax=ort_autofill', {
		delay: 200,
		width: 350,
		max: 50,
		multiple: false,
		minChars: 1,
		mustMatch: true,
		matchContains: false,
		formatItem: 
			function(row){
				return row[0] + " - <strong>" + row[1] + "</strong>";
			},
		formatResult: 
			function(row){
				return row[0];
			}
	});

	$("p.formlabel-zip").children('input').result(function(event, data, formatted){
		$("p.formlabel-city").children('input').val(data[1]);
	}); 

}); // ende document ready
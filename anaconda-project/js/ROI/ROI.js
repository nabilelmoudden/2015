
var MainDialog		= false;


$(document).ready( function()
{
	MainDialog		= new Diag( null );
	datepick();
	monthpicker2();
	
});


function updateRoi( EltOrUrl )
{
	if( typeof(EltOrUrl) == 'object' )
		Url = EltOrUrl.attr("href");
	else
		Url = baseUrl+'/index.php/'+EltOrUrl;
	
	$.ajax( Url,
	{
		success : function( data )
		{
			MainDialog.setContent( data );
			$( document ).tooltip();
			MainDialog.open();
			datepick();
		}
	} );
	return false;
}


function sendUpdate()
{	
	$.ajax(
	{
		type: "POST",
		url: $('#ROIForm').attr('action'),
		data: $('#ROIForm').serialize(),
		success: function( reponse )
		{
			//alert(reponse);
			MainDialog.setContent( reponse );
			//$( document ).tooltip();
			
			if( $('.alert-error, .alert-warning').length == 0 )
				window.setTimeout( 'MainDialog.close();', 1500 );

			if( document.getElementById('gridViewROI') ){
				$('#gridViewROI').yiiGridView('update');
			}
		}		
  });
}


//************ Export Excel Analyse ROI sur un mois
function Excel(port, perd)
{	
	tbody = $('#gridViewROI table tbody')[0].outerHTML;
	tfoot = $('#gridViewROI table tfoot')[0].outerHTML;
	
	var regex = new RegExp('"', 'g');
	tbody = tbody.replace(regex, '\'');
	tfoot = tfoot.replace(regex, '\''); 
	
	thead = "<style>table, th, td { border: 1px solid black; border-collapse: collapse;}</style><style> td{text-align: left;}</style><table><thead><tr><th style='background-color: #B0CC99;'>Plateforme Affiliation</th><th style='background-color: #B0CC99;'>Total Leads</th><th style='background-color: #B0CC99;'>% Leads:  (Nbr Leads / Tot Leads)</th><th style='background-color: #B0CC99;'>CA (&euro;)</th><th style='background-color: #B0CC99;'>% CA: (CA / Tot CA)</th><th style='background-color: #B0CC99;'>Montant Facturé (&euro;)</th><th style='background-color: #B0CC99;'>ROI</th><th style='background-color: #B0CC99;'>% Pub/CA</th></tr></thead>";
	
	//tbl = "<div style='padding-left: 20px;'><br><h2><b>" + port + " :</b></h2>Analyse ROI <b>"+perd+"</b><br><br><br>" + thead + tbody + tfoot + "</table> </div>";
	tbl = "<!DOCTYPE html><html lang='fr'><head><meta charset='UTF-8'></head><body><div style='padding-left: 20px;'><table><tr></tr><tr><td></td><td><br><br><h2><b><u>" + port + " :</u></b></h2>Analyse ROI <b>"+perd+"</b><br><br><br>" + thead + tbody + tfoot + "</table> </td></table></div></body></html>";
	lien = this.href + "/" + tbl;
	 
	document.getElementById('tblexcel').value = tbl;
}
 

// ************ Export Excel Analyse ROI sur une période
function Excels(port, perd)
{	
	tbody = $('#gridViewROI table tbody')[0].outerHTML;
	thead = $('#gridViewROI table thead')[0].outerHTML;
	
	var regex = new RegExp('"', 'g');
	tbody = tbody.replace(regex, '\'');

	tbl = "<!DOCTYPE html><html lang='fr'><head><meta charset='UTF-8'></head><body><style>th{ height: 20px;}</style><style> td{text-align: left;}</style><div style='margin-left: 20px;'><table><tr></tr><tr><td></td><td><br><br><h2><b><u>" + port + " :</u></b></h2>Analyse ROI <b>"+perd+"</b><br><br><br><table>" + thead + tbody + "</table></td></table></div></body></html>";
	lien = this.href + "/" + tbl;
	
	document.getElementById('tblexcels').value = tbl;

}
  
function Excels2(port , perd)
{	
	tbody = $('#gridViewROI table tbody')[0].outerHTML;
	thead = $('#gridViewROI table thead')[0].outerHTML;
	
	var regex = new RegExp('"', 'g');
	tbody = tbody.replace(regex, '\'');

	tbl = "<!DOCTYPE html><html lang='fr'><head><meta charset='UTF-8'></head><body><style>th{ height: 20px;}</style><style> td{text-align: left;}</style><div style='margin-left: 20px;'><table><tr></tr><tr><td></td><td><br><br><h2><b><u>" + port + " :</u></b></h2>les demandes de remboursements <b>"+perd+"</b><br><br><br><table>" + thead + tbody + "</table></td></table></div></body></html>";
	lien = this.href + "/" + tbl;
 
	document.getElementById('tblexcel').value = tbl;
	
}



$(function(){ datepick(); } );


function datepick(){
	if( $('.DatePicker').length > 0 )
	{
		$.each( $('.DatePicker'), function( index, Elt )
		{
			var val = $(Elt).val();

			$(Elt).datepicker( $.datepicker.regional['fr'] );
			$(Elt).datepicker( 'option', 'dateFormat', 'yy-mm-dd' );
			$(Elt).datepicker( 'setDate', val );
		});
	}
}



function monthpicker(){
	
	$('.monthYearPicker').datepicker({
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true,
		dateFormat: 'yy-mm'
	}).focus(function() {
		var thisCalendar = $(this);
		$('.ui-datepicker-calendar').detach();
		$('.ui-datepicker-close').click(function() {
			var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
			var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
			thisCalendar.datepicker('setDate', new Date(year, month, 1));
					});
				});
}


function monthpicker2(){
	$('.monthYearPicker').monthpicker({pattern: 'yyyy-mm', 
	    selectedYear: 2015,
	    startYear: 1900,
	    finalYear: 2212,});
		var options = {
	    selectedYear: 2015,
	    startYear: 2008,
	    finalYear: 2018,
	    openOnFocus: false // Let's now use a button to show the widget
	};
}


function validateanalyse(){
	
	dtf = document.getElementById('datefin').value;
	dtd = document.getElementById('datedebut').value;
	
	if(dtf == "")
		$('#recherche-form').attr('action', './analyse');
	else{
		$('#recherche-form').attr('action', './recherche4');
		if(dtd > dtf){
			alert("Veuillez choisir une date fin suppérieure à la date de début de l'analyse");
			return false;
		}
	}		
}

function validateanalyse2(){
	
	dtf = document.getElementById('datefin').value;
	dtd = document.getElementById('datedebut').value;
	
	if(dtf == "")
		$('#recherche-form').attr('action', './Ask');
	else{
		$('#recherche-form').attr('action', './Ask');
		if(dtd > dtf){
			alert("Veuillez choisir une date fin suppérieure à la date de début de l'analyse");
			return false;
		}
	}		
}



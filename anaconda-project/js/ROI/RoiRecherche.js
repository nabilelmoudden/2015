
Number.prototype.format = function(n, x, s, c) {
    var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
        num = this.toFixed(Math.max(0, ~~n));

    return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
};


$(document).ready( function()
{
	calculTotal();
});


//******************* Fonction calculant le total de l'analyse ROI sur un mois
function calculTotal(){

	rows = $('#gridViewROI table tbody tr');
	n = rows.length;
	var col1 = 0;
	var col2 = 0;
	var col3 = 0;
	var col4 = 0;
	var col5 = 0;
	var col6 = 0;
	var col7 = 0;

	for (i = 0; i < n; ++i){
		cells = rows[i].getElementsByTagName('td');
		col1 = col1 + Number(cells[1].innerHTML.replace(/,/g,".").replace(/ /g,""));
		col2 = col2 + Number(cells[2].innerHTML.replace(/,/g,".").replace(/ /g,""));
		col3 = col3 + Number(cells[3].innerHTML.replace(/,/g,".").replace(/ /g,""));
		col4 = col4 + Number(cells[4].innerHTML.replace(/,/g,".").replace(/ /g,""));
		col5 = col5 + Number(cells[5].innerHTML.replace(/,/g,".").replace(/ /g,""));
		col6 = col6 + Number(cells[6].innerHTML.replace(/,/g,".").replace(/ /g,""));
		col7 = col7 + Number(cells[7].innerHTML.replace(/,/g,".").replace(/ /g,""));
	}
	
	$('#gridViewROI table tfoot')[0].innerHTML = "<tr><td style='background: #E2B4C9;'><b><a href='#' onclick='calculTotal();'>Total :</a></b></td><td style='background: #E2B4C9;'><b>"+col1+"</b></td><td style='background: #E2B4C9;'><b>"+col2.format(2, 3, ' ', ',')+"</b></td><td style='background: #E2B4C9;'><b>"+col3.format(2, 3, ' ', ',')+"</b></td><td style='background: #E2B4C9;'><b>"+col4.format(2, 3, ' ', ',')+"</b></td><td style='background: #E2B4C9;'><b>"+col5.format(2, 3, ' ', ',')+"</b></td><td style='background: #E2B4C9;'><b>"+col6.format(2, 3, ' ', ',')+"</b></td><td style='background: #E2B4C9;'><b>"+col7.format(2, 3, ' ', ',')+"</b></td></tr>";
	
}





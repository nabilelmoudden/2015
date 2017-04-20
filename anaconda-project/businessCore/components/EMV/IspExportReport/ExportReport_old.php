<?php

/**
 * le composant d'exportation des rapports FAIs .
 *
 * il contient les methodes permettant la lecture des données d'un rapport FAI généré  dans Smarftocus .
 *
 *@package businessCore\components\EMV\IspExportReport
 */



class ExportReport extends CApplicationComponent {
 
  

  /**
   * cette methode retourne les lignes et les colonnes d'un rapport FAI.
   * @return array indique les informations contenues dans un rapport FAI 
   */
function exportereport($token,$customReportId,$page,$porteur,$compte){


  /*  $client = new SoapClient(Yii::app()->params['isp_acces_api'][$porteur]['ispsiteApireport']['url']); 

   $xml_array = ['token'=>$token,'customReportId'=>$customReportId,'page'=>$page];

   // $client = new SoapClient('http://p4apie.emv3.com/apiexport/services/ExportService?wsdl');
    //$logincred = new getExportableCampaignss($token,1,100,'TRIGGER','2015-12-28T11:00:50+01:00','2015-12-31T11:00:50+01:00');
   
		 $response =  $client->getIspReportResults($xml_array);
		 //$one=(array)$response['return'];
		// $idreport=(int) $one['0'];
		 
		//print_r($one);*/
   
if($compte=="acq"){
  $conf = \Yii::app()->params['MKT_EMV_ACQ'];                       
  $client = new SoapClient($conf['wdsl_rpt']);
  $xml_array = array( 'token'=>$token,'customReportId'=>$customReportId,'page'=>$page );
  $response=(array) $client->getIspReportResults($xml_array) ;


}
elseif($compte=="fid"){
  $conf = \Yii::app()->params['MKT_EMV_FID'];                       
  $client = new SoapClient($conf['wdsl_rpt']);
  $xml_array = array( 'token'=>$token,'customReportId'=>$customReportId,'page'=>$page );
  $response=(array) $client->getIspReportResults($xml_array) ;

}
		return $response ;

}

 /**
   * cette methode était utilisée dans l'ancienne version
   */
function parsetoxcel($data,$nbre){


//echo "<table><tr><th>Header1</th></tr><tr><td>value1</td></tr></table>";

   
		header( "Content-Type: application/vnd.ms-excel" );
		header( "Content-disposition: attachment; filename=spreadsheet.xls" );
		
		//readfile(filename);
		  
		  



 		}


 





}

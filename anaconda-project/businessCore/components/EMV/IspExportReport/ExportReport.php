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




   
		header( "Content-Type: application/vnd.ms-excel" );
		header( "Content-disposition: attachment; filename=spreadsheet.xls" );
		

		  
		  



 		}


 





}

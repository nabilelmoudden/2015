<?php
/**
 * le composant de génération des rapports FAIs .
 *
 * il contient les methodes permettant la creation des rapports créés dans Smarftocus et dans le serveur de production  .
 *
 *@package businessCore\components\EMV\IspExportReport
 */



use \Business\IspReport;
use \Business\IspCompaign;


class GenererReport extends CApplicationComponent {
 

  /**
   * cette methode permet la creation d'un rapport FAI dans smartfocus.
   * @return int indique id du rapport créé
   */
function generatereport($token,$reportName,$campaignId,$porteur,$compte){

 /*  $client = new SoapClient(Yii::app()->params['isp_acces_api'][$porteur]['ispsiteApireport']['url']);  
   $xml_array = ['token'=>$token,'reportName'=>$reportName,'campaignId'=>$campaignId,'managerEmail'=>'','callBackUrl'=>''];

   // $client = new SoapClient('http://p4apie.emv3.com/apiexport/services/ExportService?wsdl');
    //$logincred = new getExportableCampaignss($token,1,100,'TRIGGER','2015-12-28T11:00:50+01:00','2015-12-31T11:00:50+01:00');
   
		 $response =  (array)$client->createIspReport($xml_array);
		 $one=(array)$response['return'];
		 $idreport=(int) $one['0'];
		 
		
		return $idreport ;*/
 
     
if($compte=="acq"){
  $conf = \Yii::app()->params['MKT_EMV_ACQ'];                       
  $client = new SoapClient($conf['wdsl_rpt']);

 
  $xml_array = array( 'token'=>$token,'reportName'=>$reportName,'campaignId'=>$campaignId,'managerEmail'=>'','callBackUrl'=>'' );
  $response=(array) $client->createIspReport($xml_array) ;
  $one=(array)($response['return']); 
  $idreport=(int) $one['0'];

}
elseif($compte=="fid"){
   $conf = \Yii::app()->params['MKT_EMV_FID'];                       
  $client = new SoapClient($conf['wdsl_rpt']);

  $xml_array = array( 'token'=>$token,'reportName'=>$reportName,'campaignId'=>$campaignId,'managerEmail'=>'','callBackUrl'=>'' );
  $response=(array) $client->createIspReport($xml_array) ;
  $one=(array)($response['return']); 
  $idreport=(int) $one['0'];

}


    return $idreport;

}
  /**
   * cette methode  retourne   les informations d'un rapport.
   * @return array indique les informations relatives à un rapport FAI
   */
function infosreport($token,$customReportId,$porteur,$compte){

/*   $client = new SoapClient(Yii::app()->params['isp_acces_api'][$porteur]['ispsiteApireport']['url']);  
   $xml_array = ['token'=>$token,'customReportId'=>$customReportId];

   // $client = new SoapClient('http://p4apie.emv3.com/apiexport/services/ExportService?wsdl');
    //$logincred = new getExportableCampaignss($token,1,100,'TRIGGER','2015-12-28T11:00:50+01:00','2015-12-31T11:00:50+01:00');
   
	$response = $client->getCustomReportInfoById($xml_array);*/
		
    
if($compte=="acq"){
  $conf = \Yii::app()->params['MKT_EMV_ACQ'];                       
  $client = new SoapClient($conf['wdsl_rpt']);
  $xml_array = array( 'token'=>$token,'customReportId'=>$customReportId );
  $response=(array) $client->getCustomReportInfoById($xml_array) ;


}
elseif($compte=="fid"){
 $conf = \Yii::app()->params['MKT_EMV_FID'];                       
  $client = new SoapClient($conf['wdsl_rpt']);
  $xml_array = array( 'token'=>$token,'customReportId'=>$customReportId );
  $response=(array) $client->getCustomReportInfoById($xml_array) ;

}

		return $response ;

}
 /**
   * cette methode retourne le chemin côté serveur production.
   * @return string indique le chemin serveur de creation d'un rapport FAIs
   */
function createfolders($reportId){


\Controller::loadConfigForPorteur('fr_rinalda');
	
    $query=IspReport::model()->with('IspCompaign')->findAll(array(
                      
                      'condition' => 'idreport_sf = :idreport_sf',
                      'params'    => array(':idreport_sf' =>$reportId),
                  ));
      //$query = IspReport::find()->joinWith('compaign')->where('report.idreport = :idreport', [':idreport' =>$reportId])->all();

      $reportname=$query['0']->namereport;


      $porteur=$query['0']->IspCompaign['porteur'];
      $site   =$query['0']->IspCompaign['site'];
      $triggernamee=$query['0']->IspCompaign->triggername;
      $triggername=str_replace(">","-",$triggernamee);
      $messagename=$query['0']->IspCompaign->messagename;
      $senddatetime   =$query['0']->IspCompaign->senddate;
      $dates = explode(' ', $senddatetime);
      $senddatee = $dates[0];
      $senddate=str_replace(":","-",$senddatee);
     
         //set the directory

      $path='/downloadedreport/'.$porteur.'/'.$site.'/'.$triggername.'/'.$messagename.'/'.$senddate;
      $filename=$path.'/'.$reportname.'.xlsx';
   
            if (!file_exists($path)) {

                  if (!file_exists('./downloadedreport/'.$porteur)) {
                   // mkdir('./downloadedreport/test',0777);
                       mkdir('./downloadedreport/'.$porteur,0777,true);
                  }
                  if (!file_exists('./downloadedreport/'.$porteur.'/'.$site)) {
                        mkdir('./downloadedreport/'.$porteur.'/'.$site,0777,true);
                  }  
                  if (!file_exists('./downloadedreport/'.$porteur.'/'.$site.'/'.$triggername)) {
                            mkdir('./downloadedreport/'.$porteur.'/'.$site.'/'.$triggername,0777,true);
                  }
                  if (!file_exists('./downloadedreport/'.$porteur.'/'.$site.'/'.$triggername.'/'.$messagename)) {
                            mkdir('./downloadedreport/'.$porteur.'/'.$site.'/'.$triggername.'/'.$messagename,0777,true);
                  }
                  if (!file_exists('./downloadedreport/'.$porteur.'/'.$site.'/'.$triggername.'/'.$messagename.'/'.$senddate)) {
                            mkdir('./downloadedreport/'.$porteur.'/'.$site.'/'.$triggername.'/'.$messagename.'/'.$senddate,0777,true);
                  }
                  
            }
            
            return $filename;

          

}




}
?>
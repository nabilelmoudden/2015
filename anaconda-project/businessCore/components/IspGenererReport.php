<?php


/**
 * le composant de génération des rapports FAIs .
 *
 * il contient les methodes permettant la creation des rapports créés dans Smarftocus et dans le serveur de production  .
 *
 *@package businessCore\components\EMV\IspExportReport
 */

define('wdsl_rpt1', 'wdsl_rpt');
define('token1', 'token');
define('downloadedreport1', './downloadedreport/');


use \Business\IspReport;
use \Business\IspCompaign;


class IspGenererReport  {
 

  /**
   * cette methode permet la creation d'un rapport FAI dans smartfocus.
   * @return int indique id du rapport créé
   */
function generatereport($token,$reportName,$campaignId,$porteur,$compte){

 
 
     
if($compte=="acq"){
  $conf = \Yii::app()->params['MKT_EMV_ACQ'];                       
  $client = new SoapClient($conf[wdsl_rpt1]);

 
  $xml_array = array( token1=>$token,'reportName'=>$reportName,'campaignId'=>$campaignId,'managerEmail'=>'','callBackUrl'=>'' );
  $response=(array) $client->createIspReport($xml_array) ;
  $one=(array)($response['return']); 
  $idreport=(int) $one['0'];

}
elseif($compte=="fid"){
   $conf = \Yii::app()->params['MKT_EMV_FID'];                       
  $client = new SoapClient($conf[wdsl_rpt1]);

  $xml_array = array( token1=>$token,'reportName'=>$reportName,'campaignId'=>$campaignId,'managerEmail'=>'','callBackUrl'=>'' );
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

		
    
if($compte=="acq"){
  $conf = \Yii::app()->params['MKT_EMV_ACQ'];                       
  $client = new SoapClient($conf[wdsl_rpt1]);
  $xml_array = array( token1=>$token,'customReportId'=>$customReportId );
  $response=(array) $client->getCustomReportInfoById($xml_array) ;


}
elseif($compte=="fid"){
 $conf = \Yii::app()->params['MKT_EMV_FID'];                       
  $client = new SoapClient($conf[wdsl_rpt1]);
  $xml_array = array( token1=>$token,'customReportId'=>$customReportId );
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
    

      $reportname=$query['0']->namereport;


      $porteur=$query['0']->IspCompaign['porteur'];
      $site   =$query['0']->IspCompaign['site'];
      $triggernamee=$query['0']->IspCompaign->triggername;
      $triggername=trim(str_replace(">","-",$triggernamee));
      $messagename=$query['0']->IspCompaign->messagename;
      $senddatetime   =$query['0']->IspCompaign->senddate;
      $dates = explode(' ', $senddatetime);
      $senddatee = $dates[0];
      $senddate=str_replace(":","-",$senddatee);
     
  

      $path='/downloadedreport/'.$porteur.'/'.$site.'/'.$triggername.'/'.$messagename.'/'.$senddate;
      $filename=$path.'/'.$reportname.'.xlsx';
   
            if (!file_exists($path)) {

                  if (!file_exists(downloadedreport1.$porteur)) {
                  
                       mkdir(downloadedreport1.$porteur,0777,true);
                  }
                  if (!file_exists(downloadedreport1.$porteur.'/'.$site)) {
                        mkdir(downloadedreport1.$porteur.'/'.$site,0777,true);
                  }  
                  if (!file_exists(downloadedreport1.$porteur.'/'.$site.'/'.$triggername)) {
                            mkdir(downloadedreport1.$porteur.'/'.$site.'/'.$triggername,0777,true);
                  }
                  if (!file_exists(downloadedreport1.$porteur.'/'.$site.'/'.$triggername.'/'.$messagename)) {
                            mkdir(downloadedreport1.$porteur.'/'.$site.'/'.$triggername.'/'.$messagename,0777,true);
                  }
                  if (!file_exists(downloadedreport1.$porteur.'/'.$site.'/'.$triggername.'/'.$messagename.'/'.$senddate)) {
                            mkdir(downloadedreport1.$porteur.'/'.$site.'/'.$triggername.'/'.$messagename.'/'.$senddate,0777,true);
                  }
                  
            }
            
            return $filename;

          

}




}
?>
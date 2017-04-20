<?php

/**
 * le composant d'interaction  avec smartfocus.
 *
 * il contient les methodes permettant l'insertion et la mise à jour des données de la base de données par les informations récupérées depuis smartfocus.
 *
 *@package businessCore\components\EMV\IspExportReport
 */
define('MKT_EMV_ACQ', 'MKT_EMV_ACQ');
define('MKT_EMV_FID', 'MKT_EMV_FID');

define('login', 'login');
use \Business\IspCompaign ;


class AlimenterBD extends CApplicationComponent {

/**
   * cette methode permet de retourner le token genéré par porteur.
   * @return string indique le token genéré par porteur
*/

 function gettoken($porteur,$compte){

  

if($compte=="acq"){
  $conf = \Yii::app()->params[MKT_EMV_ACQ];                       
  $client = new SoapClient($conf['wdsl']);



  $xml_array = array( login => $conf[login],'pwd' => $conf['pwd'], 'key' => $conf['key'] );

  $response=(array) $client->openApiConnection($xml_array) ;
  $token=(string)($response['return']); 

}
elseif($compte=="fid"){
  $conf = \Yii::app()->params[MKT_EMV_FID];                       
  $client = new SoapClient($conf['wdsl']);

  $xml_array = array( login => $conf[login],'pwd' => $conf['pwd'], 'key' => $conf['key'] );
  $response=(array) $client->openApiConnection($xml_array) ;
  $token=(string)($response['return']); 

}

   
 
return $token;
}

/**
   * cette methode permet de retourner le token genéré par porteur, cette methode est utilisé pour le cron.
   * @return array indique le token genéré par porteur ainsi que les messages d'erreurs et de validation 
*/
 function gettokencron($porteur){
              $msg = NULL;
              $err = NULL;
              $token_acq = "";
              $token_fid = "";

if (!empty($porteur)) {
  if (!isset($GLOBALS['porteurMap'][$porteur]) || !\Controller::loadConfigForPorteur($porteur)){
                $msg = '<div style="color:red"><u>' . $porteur . '</u> : Le porteur est introuvable</div>';
  }              
  else {
              
           
         
            
              if (isset(\Yii::app()->params[MKT_EMV_ACQ]['wdsl']) ) {
                   
                    try {
                        $conf = \Yii::app()->params['MKT_EMV_ACQ'];                       
                        $client = new SoapClient($conf['wdsl']);
                        $xml_array = array( login => $conf[login],'pwd' => $conf['pwd'], 'key' => $conf['key'] );
                        $response=(array) $client->openApiConnection($xml_array) ;
                        $token_acq=(string)($response['return']); 
                        
                     
                    } catch (Exception $E) {
                        $err .= 'impossible de se connecter au serveur: <b>' . $E->detail->ConnectionServiceException->status . ' ('. $E->detail->ConnectionServiceException->description .')</b><br />';
                        $msg .= '<div style="color: red"><u>' . $porteur . ' ( ACQ )</u> : ' . $err . '</div><br /><br />';
                       
                     
                    }  
               }
              
              else{
                    $msg .= '<div style="color:red"><u>' . $porteur . '</u> : Aucune configuration de l\'API EMV pour le Compte ACQUISITION</div>';
              }

               if (isset(\Yii::app()->params[MKT_EMV_FID]['wdsl_rpt']) ) {
                   
                    try {
                        $conf = \Yii::app()->params[MKT_EMV_FID];                       
                        $client = new SoapClient($conf['wdsl_rpt']);
                        $xml_array = array( login => $conf[login],'pwd' => $conf['pwd'], 'key' => $conf['key'] );
                        $response=(array) $client->openApiConnection($xml_array) ;
                        $token_fid=(string)($response['return']); 
                  
                     
                    } catch (Exception $E) {
                        $err .= 'impossible de se connecter au serveur: <b>' . $E->detail->ConnectionServiceException->status . ' ('. $E->detail->ConnectionServiceException->description .')</b><br />';
                        $msg .= '<div style="color: red"><u>' . $porteur . ' ( FID )</u> : ' . $err . '</div><br /><br />';
                       
                       
                    }  
               }
               else{
                    $msg .= '<div style="color:red"><u>' . $porteur . '</u> : Aucune configuration de l\'API EMV pour le Compte FID</div></br>';
                    
              }




}


   
      return array($token_acq,$token_fid,$msg) ;
}

}
/**
   * cette methode permet de retourner les compagnes lancées suivant les critères passés en paramètre.
   * @return array indique les compagnes lancées dans smartfocus ainsi que les messages d'erreurs et de validation
*/
function getcompaigns($token,$page,$porteur,$compte){

 $msg=NULL;
 $err=NULL;
 $one="";
 if (!isset($GLOBALS['porteurMap'][$porteur]) || !\Controller::loadConfigForPorteur($porteur)){
                $msg = '<div style="color:red"><u>' . $porteur . '</u> : Le porteur est introuvable , incapable d\'acquerir les compagnes</div>';
  }              
  else {

    if($compte=="acq"){
        if (isset(\Yii::app()->params[MKT_EMV_ACQ]['wdsl']) ) {
             
              try {
                  $conf = \Yii::app()->params[MKT_EMV_ACQ];                       
                  $client = new SoapClient($conf['wdsl']);
                  $xml_array = array('token'=>$token,'page'=>$page,'perPage'=>'100','campaignType'=>'TRIGGER','beginDate'=>date("Y-m-d\T00:00:01+01:00", strtotime('-1 day')),'endDate'=>date("Y-m-d\T00:00:00+01:00"));
                  $response=(array) $client->getExportableCampaigns($xml_array) ;
                  $one=(array)($response['return']); 
                  
               
              } catch (Exception $E) {
                  $err .= 'impossible de se connecter pour faire acquerir des compagnes depuis serveur: <b>' . $E->detail->ConnectionServiceException->status . ' ('. $E->detail->ConnectionServiceException->description .')</b><br />';
                  $msg .= '<div style="color: red"><u>' . $porteur . '(ACQ) </u> : ' . $err . '</div><br /><br />';
                 
               
              }  
         }
        
        else{
              $msg .= '<div style="color:red"><u>' . $porteur . '(ACQ)</u> : Aucune configuration de l\'API EMV EXPORT COMPAIGNS pour le Compte ACQ </div></br>';
        }
}
elseif($compte=="fid"){
         if (isset(\Yii::app()->params[MKT_EMV_FID]['wdsl']) ) {
                   
                    try {
                        $conf = \Yii::app()->params[MKT_EMV_FID];                       
                        $client = new SoapClient($conf['wdsl']);
                        $xml_array = array('token'=>$token,'page'=>$page,'perPage'=>'100','campaignType'=>'TRIGGER','beginDate'=>date("Y-m-d\T00:00:01+01:00", strtotime('-1 day')),'endDate'=>date("Y-m-d\T00:00:00+01:00"));
                        $response=(array) $client->getExportableCampaigns($xml_array) ;
                        $one=(array)($response['return']); 
                  
                     
                    } catch (Exception $E) {
                        $err .= 'impossible de se connecter pour faire acquerir des compagnes depuis serveur: <b>' . $E->detail->ConnectionServiceException->status . ' ('. $E->detail->ConnectionServiceException->description .')</b><br />';
                        $msg .= '<div style="color: red"><u>' . $porteur . ' ( FID )</u> : ' . $err . '</div><br /><br />';
                       
                       
                    }  
               }
               else{
                    $msg .= '<div style="color:red"><u>' . $porteur . '</u> : Aucune configuration de l\'API EMV EXPORT COMPAIGNS pour le Compte FID</div></br>';
                    
              }

}              
}




            


          

              






   
      return array($one,$msg) ;


}

/**
   * cette methode permet de retourner les ids messages depuis smartfocus suivant les critères passés en paramètre.
   * @return array indique les ids messages associés à chaque compagne lancées dans smartfocus ainsi que les messages d'erreur associés
*/
function getmesageid($token,$campaignId,$porteur,$compte){

  

 $msg=NULL;
 $err=NULL;

 $messageid_acq="";
 $messageid_fid="";
 if (!isset($GLOBALS['porteurMap'][$porteur]) || !\Controller::loadConfigForPorteur($porteur)){
                $msg = '<div style="color:red"><u>' . $porteur . '</u> : Le porteur est introuvable , incapable d\'acquerir les compagnes</div>';
  }              
  else {

    if($compte=="acq"){
        if (isset(\Yii::app()->params['CMD_EMV_ACQ']['wdsl']) ) {
             
              try {
                  $conf = \Yii::app()->params['CMD_EMV_ACQ'];                       
                  $client = new SoapClient($conf['wdsl']);
                  $xml_array = array('token'=>$token,'campaignId'=>$campaignId);
                  $response=(array) $client->getCampaignReport($xml_array) ;
                  $messaginfo=(array)($response['return']); 
                  $messageid_acq=(string) $messaginfo['messageId'];
                  
               
              } catch (Exception $E) {
                  $err .= 'impossible de se connecter pour faire acquerir les ids messages depuis serveur: <b>' . $E->detail->ConnectionServiceException->status . ' ('. $E->detail->ConnectionServiceException->description .')</b><br />';
                  $msg .= '<div style="color: red"><u>' . $porteur . '(ACQ) </u> : ' . $err . '</div><br /><br />';
                 
               
              }  
         }
        
        else{
              $msg .= '<div style="color:red"><u>' . $porteur . '(ACQ)</u> : Aucune configuration de l\'API EMV GET ID MESSAGE pour le Compte ACQ </div></br>';
        }
}
elseif($compte=="fid"){
         if (isset(\Yii::app()->params['CMD_EMV_FID']['wdsl']) ) {
                   
                    try {
                        $conf = \Yii::app()->params['CMD_EMV_FID'];                       
                        $client = new SoapClient($conf['wdsl']);
                        $xml_array = array('token'=>$token,'campaignId'=>$campaignId);
                        $response=(array) $client->getCampaignReport($xml_array) ;
                        $messaginfo=(array)($response['return']); 
                        $messageid_fid=(string) $messaginfo['messageId'];
                  
                     
                    } catch (Exception $E) {
                        $err .= 'impossible de se connecter pour faire acquerir les ids messages depuis serveur: <b>' . $E->detail->ConnectionServiceException->status . ' ('. $E->detail->ConnectionServiceException->description .')</b><br />';
                        $msg .= '<div style="color: red"><u>' . $porteur . ' ( FID )</u> : ' . $err . '</div><br /><br />';
                       
                       
                    }  
               }
               else{
                    $msg .= '<div style="color:red"><u>' . $porteur . '</u> : Aucune configuration de l\'API EMV GET ID MESSAGE pour le Compte FID</div></br>';
                    
              }

}              
}




            


          

              





   
      return array($messageid_acq,$messageid_fid,$msg) ;

}
/**
   * cette methode permet de retourner les noms des messages depuis smartfocus suivant les critères passés en paramètre.
   * @return array indique les nom des messages associés à chaque compagne lancées dans smartfocus
*/
function getmesagename($token,$messageid,$porteur,$compte){

      




 $msg=NULL;
 $err=NULL;

 $messagename_acq="";
 $messagename_fid="";
 if (!isset($GLOBALS['porteurMap'][$porteur]) || !\Controller::loadConfigForPorteur($porteur)){
                $msg = '<div style="color:red"><u>' . $porteur . '</u> : Le porteur est introuvable , incapable d\'acquerir les compagnes</div>';
  }              
  else {

    if($compte=="acq"){
        if (isset(\Yii::app()->params['CMD_EMV_ACQ']['wdsl']) ) {
             
              try {
                  $conf = \Yii::app()->params['CMD_EMV_ACQ'];                       
                  $client = new SoapClient($conf['wdsl']);
                  $xml_array = array('token'=>$token,'id'=>$messageid);
                  $response=(array) $client->getMessage($xml_array) ;
                  $messaginfo=(array)($response['return']); 
                  $messagename_acq=(string) $messaginfo['name'];
                  
               
              } catch (Exception $E) {
                  $err .= 'impossible de se connecter pour faire acquerir les noms des messages depuis serveur: <b></b><br />';
                  $msg .= '<div style="color: red"><u>' . $porteur . '(ACQ) </u> : ' . $err . '</div><br /><br />';
                 
               
              }  
         }
        
        else{
              $msg .= '<div style="color:red"><u>' . $porteur . '(ACQ)</u> : Aucune configuration de l\'API EMV GET NAME MESSAGE pour le Compte ACQ </div></br>';
        }
}
elseif($compte=="fid"){
         if (isset(\Yii::app()->params['CMD_EMV_FID']['wdsl']) ) {
                   
                    try {
                        $conf = \Yii::app()->params['CMD_EMV_FID'];                       
                        $client = new SoapClient($conf['wdsl']);
                        $xml_array = array('token'=>$token,'id'=>$messageid);
                        $response=(array) $client->getMessage($xml_array) ;
                        $messaginfo=(array)($response['return']); 
                        $messagename_fid=(string) $messaginfo['name'];
                  
                     
                    } catch (Exception $E) {
                        $err .= 'impossible de se connecter pour faire acquerir les noms des messages depuis serveur: <b></b><br />';
                        $msg .= '<div style="color: red"><u>' . $porteur . ' ( FID )</u> : ' . $err . '</div><br /><br />';
                       
                       
                    }  
               }
               else{
                    $msg .= '<div style="color:red"><u>' . $porteur . '</u> : Aucune configuration de l\'API EMV GET NAME MESSAGE pour le Compte FID</div></br>';
                    
              }

}              
}




            


          

              




   
      return array($messagename_acq,$messagename_fid,$msg) ;
}


}
?>
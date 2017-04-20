<?php

/**
 * le composant CRUD.
 *
 * il contient les methodes permettant l'insertion et la mise à jour des données des deux tables  V2_ispreport et V2_ispcompaign .
 *
 *@package businessCore\components\EMV\IspExportReport
 */

define('MKT_EMV_ACQ', 'MKT_EMV_ACQ');
define('MKT_EMV_FID', 'MKT_EMV_FID');

define('login', 'login');

use \Business\InfosCompaignAffiliate ;



class SeuilSF {


  public function GetAddress($date_actuel_cron,$date_premiere_cron,$porteur,$CompteEMVactif){

        \Controller::loadConfigForPorteur('fr_rinalda');
         $date_creation_actuel=date("Y-m-d", strtotime("-2 day", strtotime($date_actuel_cron)));
         $date_creation_initiale=date("Y-m-d", strtotime("-2 day", strtotime($date_premiere_cron)));
         $date_achat_actuel=date("Y-m-d", strtotime("-1 day", strtotime($date_actuel_cron)));
     
         $divporteur = explode('_', $porteur);
         $site= $divporteur[0];
         $comptes=explode('_', $CompteEMVactif);
         if(isset($comptes[2])  && $comptes[2]=='FID'){$compte='fid' ;$site_porteur=$porteur.'_'.$compte; } else {$compte=''; $site_porteur=$porteur; }
       
    
     /**/
          $connection=Yii::app()->db; 
         /* $AllAdresses=$connection->createCommand("
      SELECT  l.idAffiliatePlatformSubId as subID, i.email as adresse, Date(l.creationDate) as DateLead,'".$site_porteur."' as porteur,'0' as Clicktotal
       FROM `internaute` i
      inner join lead_affiliateplatform l
      on i.ID=l.idInternaute
      inner join invoice inv
      on i.ID=inv.IDInternaute
      WHERE Date(l.creationDate) BETWEEN '".$date_creation_initiale."' and '".$date_creation_actuel."' and l.idAffiliatePlatformSubId!='0' and inv.site='".$site."' and i.CompteEMVactif='".$CompteEMVactif."'
      GROUP BY l.idAffiliatePlatformSubId  ,i.id")->query();*/

          $DistinctAllAdresses=$connection->createCommand("
           SELECT subID ,idAffiliatePlatform
           from  infos_compaign_affiliate

           WHERE date_jour='".$date_achat_actuel."' and porteur='".$site_porteur."'


           ")->query();


/*
          $AdressJ_2=$connection->createCommand("
      SELECT  l.idAffiliatePlatformSubId as subID, i.email as adresse, Date(l.creationDate) as DateLead ,'".$site_porteur."' as porteur
       FROM `internaute` i
      inner join lead_affiliateplatform l
      on i.ID=l.idInternaute
      inner join invoice inv
      on i.ID=inv.IDInternaute
      WHERE Date(l.creationDate)='".$date_creation_actuel."' and l.idAffiliatePlatformSubId!='0' and inv.site='".$site."' and i.CompteEMVactif='".$CompteEMVactif."'
      GROUP BY l.idAffiliatePlatformSubId  ,i.id")->query();*/
       /*   $DistinctAdressJ_2=$connection->createCommand("
            select distinct d.subID
      from ( SELECT  sub.subID as subID, i.email as adresse,Date(l.creationDate) as DateLead ,'".$site_porteur."' as porteur
       FROM `internaute` i
      inner join lead_affiliateplatform l
      on i.ID=l.idInternaute
      inner join invoice inv
      on i.ID=inv.IDInternaute
      inner join V2_affiliateplatformsubid sub
      on l.idAffiliatePlatformSubId=sub.id
      WHERE Date(l.creationDate)='".$date_creation_actuel."' and sub.subID!='__0__' and inv.site='".$site."' and i.CompteEMVactif='".$CompteEMVactif."'
      GROUP BY l.idAffiliatePlatformSubId  ,i.id) as d")->query();


*/
       
        // récupération de toutes les lignes d'un coup dans un tableau
 /*   $array1=$AllAdresses->readAll();*/
    $array1_1=$DistinctAllAdresses->readAll();
    /*$array2=$AdressJ_2->readAll();*/
    /*$array2_2=$DistinctAdressJ_2->readAll();*/
    //$array_added=array('click'=>'0');

    //var_dump($array2);
   /* $_out=array_map(function ($v){ return $v['subID']; }, $array1);
     $DistinctAllAdresses=array_unique($_out,SORT_REGULAR);*/
     
     /*array_map(function ($v){ return 'subID'; }, array_flip($DistinctAllAdresses));

     foreach ($DistinctAllAdresses as $k => $v) {

       # code...
      $k='subID';
     }*/
 /*  $p= array_fill_keys(array_flip($DistinctAllAdresses),'subID');
   $pp=array_flip($p);
   var_dump($p);
    die();*/
  
    return $array1_1;
    }
  
  

 function gettoken($porteur,$compte){

   $token = "";

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
public function creatSegment($token, $name, $description, $sampleType,$compte)
  {
    
              $msg = NULL;
              $err = NULL;
              $segmentid = "";
if($compte=="acq"){    

      if (isset(\Yii::app()->params['CMD_EMV_ACQ']['wdsl']) ) {
        try
             {
                          $conf = \Yii::app()->params['CMD_EMV_ACQ'];                       
                          $client = new SoapClient($conf['wdsl']);
                          $xml_array = array('token'=>$token, 'apiSegmentation'=> array( 'id'=>'111', 'name'=>$name,'description'=>$description,  'sampleType'=>$sampleType, 'sampleRate'=>'11', 'dateCreate'=>'2016-05-17', 'dateModif'=>'2016-05-17') );
                          $response=(array) $client->segmentationCreateSegment($xml_array) ;
                          $segmentid=(string)($response['return']); 
                        
          
               }
        catch (Exception $E) {
                        $err .= 'impossible de se connecter au serveur: <b>)</b><br />';
                        $msg .= '<div style="color: red"><u> ( ACQ )</u> : ' . $err . '</div><br /><br />';
                       
                     
        }  
     }
     else{
                    $msg .= '<div style="color:red"><u></u> : Aucune configuration de l\'API EMV pour le Compte ACQUISITION</div>';
          }
 }
 if($compte=="fid"){             
    if (isset(\Yii::app()->params['CMD_EMV_FID']['wdsl']) ) {
                   
                    try {
                          $conf = \Yii::app()->params['CMD_EMV_FID'];                       
                          $client = new SoapClient($conf['wdsl']);
                          $xml_array = array('token'=>$token, 'apiSegmentation'=> array( 'id'=>'1111111', 'name'=>$name,'description'=>$description,  'sampleType'=>$sampleType, 'sampleRate'=>'11', 'dateCreate'=>'2016-05-17', 'dateModif'=>'2016-05-17') );
                          $response=(array) $client->segmentationCreateSegment($xml_array) ;
                          $segmentid=(string)($response['return']); 
                                  
                     
                    } catch (Exception $E) {
                        $err .= 'impossible de se connecter au serveur: <b></b><br />';
                        $msg .= '<div style="color: red"><u>( FID )</u> : ' . $err . '</div><br /><br />';
                       
                       
                    }  
     }
      else{
                    $msg .= '<div style="color:red"><u></u> : Aucune configuration de l\'API EMV pour le Compte FID</div></br>';
                    
      }
}

     return $segmentid ;
  }  

  public function criteriaSegment($token, $id, $columnName,$groupName,$groupNumber,$orderFrag, $operator,$values,$compte)
  {
              $msg = NULL;
              $err = NULL;
              $segmentid = "";
if($compte=="acq"){               
     if (isset(\Yii::app()->params['CMD_EMV_ACQ']['wdsl']) ) {
        try
        {
          $conf = \Yii::app()->params['CMD_EMV_ACQ'];                       
                  $client = new SoapClient($conf['wdsl']);
                   $xml_array = array('token'=>$token, 'stringDemographicCriteria'=> array( 'id'=>$id, 'groupName'=>$groupName,  'groupNumber'=>$groupNumber, 'orderFrag'=>$orderFrag,  'columnName'=>$columnName,  'operator'=>$operator,  'values'=>$values) );
                  $response=(array) $client->segmentationAddStringDemographicCriteriaByObj($xml_array) ;
                  $segmentid=(string)($response['return']); 
        }
       catch (Exception $E) {
                        $err .= 'impossible de se connecter au serveur: <b>)</b><br />';
                        $msg .= '<div style="color: red"><u> ( ACQ )</u> : ' . $err . '</div><br /><br />';
                       
                     
       }  
     }
     else{
                    $msg .= '<div style="color:red"><u></u> : Aucune configuration de l\'API EMV pour le Compte ACQUISITION</div>';
    }
}  
if($compte=="fid"){      
    if (isset(\Yii::app()->params['CMD_EMV_FID']['wdsl']) ) {
                   
                    try {
                          $conf = \Yii::app()->params['CMD_EMV_FID'];                       
                          $client = new SoapClient($conf['wdsl']);
                           $xml_array = array('token'=>$token, 'stringDemographicCriteria'=> array( 'id'=>$id, 'groupName'=>$groupName,  'groupNumber'=>$groupNumber, 'orderFrag'=>$orderFrag,  'columnName'=>$columnName,  'operator'=>$operator,  'values'=>$values));
                          $response=(array) $client->segmentationAddStringDemographicCriteriaByObj($xml_array) ;
                          $segmentid=(string)($response['return']); 
                  
                     
                    } catch (Exception $E) {
                        $err .= 'impossible de se connecter au serveur: <b></b><br />';
                        $msg .= '<div style="color: red"><u>( FID )</u> : ' . $err . '</div><br /><br />';
                       
                       
                 }  
    }
    else{
                    $msg .= '<div style="color:red"><u></u> : Aucune configuration de l\'API EMV pour le Compte FID</div></br>';
                    
    }
}
     return $segmentid ;
  }

  public function deletecriteriaSegment($token, $segmentid, $orderCriteria,$compte)
  {
              $msg = NULL;
              $err = NULL;
        
            
              
if($compte=="acq"){               
     if (isset(\Yii::app()->params['CMD_EMV_ACQ']['wdsl']) ) {
        try
        {
          $conf = \Yii::app()->params['CMD_EMV_ACQ'];                       
                  $client = new SoapClient($conf['wdsl']);
                   $xml_array = array('token'=>$token, 'difflistId'=>$segmentid ,  'orderCriteria'=>$orderCriteria );
                  $response=(array) $client->segmentationDeleteCriteria($xml_array) ;
                 
                    $segmentid=(string)($response['return']); 
        }
       catch (Exception $E) {
                        $err .= 'impossible de se connecter au serveur: <b>)</b><br />';
                        $msg .= '<div style="color: red"><u> ( ACQ )</u> : ' . $err . '</div><br /><br />';
                       
       }  
     }
     else{
   
                    $msg .= '<div style="color:red"><u></u> : Aucune configuration de l\'API EMV pour le Compte ACQUISITION</div>';
    }
}  
if($compte=="fid"){      
    if (isset(\Yii::app()->params['CMD_EMV_FID']['wdsl']) ) {
                   
                    try {
                          $conf = \Yii::app()->params['CMD_EMV_FID'];                       
                          $client = new SoapClient($conf['wdsl']);
                          $xml_array = array('token'=>$token, 'difflistId'=>$segmentid ,  'orderCriteria'=>$orderCriteria );
                          $response=(array) $client->segmentationDeleteCriteria($xml_array) ;
                          $segmentid=(string)($response['return']); 
                  
                     
                    } catch (Exception $E) {
                        $err .= 'impossible de se connecter au serveur: <b></b><br />';
                        $msg .= '<div style="color: red"><u>( FID )</u> : ' . $err . '</div><br /><br />';
                       
                       
                 }  
    }
    else{
                    $msg .= '<div style="color:red"><u></u> : Aucune configuration de l\'API EMV pour le Compte FID</div></br>';
                    
    }
}
     return $segmentid ;
  }


  public function criteriadateSegment($token, $id, $columnName, $operator,$firstAbsoluteDate,$secondAbsoluteDate ,$compte)
  {
              $msg = NULL;
              $err = NULL;
              $segmentid = "";
if($compte=="acq"){                  
     if (isset(\Yii::app()->params['CMD_EMV_ACQ']['wdsl']) ) {
        try
        {
          $conf = \Yii::app()->params['CMD_EMV_ACQ'];                       
                  $client = new SoapClient($conf['wdsl']);
                   $xml_array = array('token'=>$token, 'dateDemographicCriteria'=> array( 'id'=>$id, 'groupName'=>'Groupe2',  'groupNumber'=>'2', 'orderFrag'=>'2',  'columnName'=>$columnName, 'absoluteDate'=>0, 'firstAbsoluteDate'=>$firstAbsoluteDate, 'operator'=>$operator,  'secondAbsoluteDate'=>$secondAbsoluteDate) );
                  $response=(array) $client->segmentationAddDateDemographicCriteriaByObj($xml_array) ;
                  $segmentid=(string)($response['return']); 
        }
       catch (Exception $E) {
                        $err .= 'impossible de se connecter au serveur: <b>)</b><br />';
                        $msg .= '<div style="color: red"><u> ( ACQ )</u> : ' . $err . '</div><br /><br />';
                       
                     
       }  
     }
     else{
                    $msg .= '<div style="color:red"><u></u> : Aucune configuration de l\'API EMV pour le Compte ACQUISITION</div>';
    }
}
if($compte=="fid"){        
    if (isset(\Yii::app()->params['CMD_EMV_FID']['wdsl']) ) {
                   
                    try {
                          $conf = \Yii::app()->params['CMD_EMV_FID'];                       
                          $client = new SoapClient($conf['wdsl']);
                           $xml_array = array('token'=>$token, 'dateDemographicCriteria'=> array( 'id'=>$id, 'groupName'=>'Groupe2',  'groupNumber'=>'2', 'orderFrag'=>'2',  'columnName'=>$columnName, 'absoluteDate'=>0, 'firstAbsoluteDate'=>$firstAbsoluteDate, 'operator'=>$operator,  'secondAbsoluteDate'=>$secondAbsoluteDate) );
                          $response=(array) $client->segmentationAddDateDemographicCriteriaByObj($xml_array) ;
                          $segmentid=(string)($response['return']); 
                  
                     
                    } catch (Exception $E) {
                        $err .= 'impossible de se connecter au serveur: <b></b><br />';
                        $msg .= '<div style="color: red"><u>( FID )</u> : ' . $err . '</div><br /><br />';
                       
                       
                 }  
    }
    else{
                    $msg .= '<div style="color:red"><u></u> : Aucune configuration de l\'API EMV pour le Compte FID</div></br>';
                    
    }
}
     return $segmentid ;
  }
  
  public function criteriaRecencySegment($token, $id, $columnName, $operator,$firstStaticValue,$compte )
  {
              $msg = NULL;
              $err = NULL;
              $segmentid = "";
if($compte=="acq"){              
     if (isset(\Yii::app()->params['CMD_EMV_ACQ']['wdsl']) ) {
        try
        {
          $conf = \Yii::app()->params['CMD_EMV_ACQ'];                       
                  $client = new SoapClient($conf['wdsl']);
                   $xml_array = array('token'=>$token, 'recencyCriteria'=> array( 'id'=>$id, 'groupName'=>'Groupe3',  'groupNumber'=>'3', 'orderFrag'=>'3',  'columnName'=>$columnName,  'firstStaticValue'=>$firstStaticValue, 'operator'=>$operator,  'secondStaticValue'=>'2016-05-17') );
                  $response=(array) $client->segmentationAddRecencyCriteriaByObj($xml_array) ;
                  $segmentid=(string)($response['return']); 
        }
       catch (Exception $E) {
                        $err .= 'impossible de se connecter au serveur: <b>)</b><br />';
                        $msg .= '<div style="color: red"><u> ( ACQ )</u> : ' . $err . '</div><br /><br />';
                       
                     
       }  
     }
     else{
                    $msg .= '<div style="color:red"><u></u> : Aucune configuration de l\'API EMV pour le Compte ACQUISITION</div>';
    }
}   
elseif($compte=="fid"){ 
    if (isset(\Yii::app()->params['CMD_EMV_FID']['wdsl']) ) {
                   
                    try {
                          $conf = \Yii::app()->params['CMD_EMV_FID'];                       
                          $client = new SoapClient($conf['wdsl']);
                           $xml_array = array('token'=>$token, 'recencyCriteria'=> array( 'id'=>$id, 'groupName'=>'Groupe3',  'groupNumber'=>'3', 'orderFrag'=>'3',  'columnName'=>$columnName,  'firstStaticValue'=>$firstStaticValue, 'operator'=>$operator,  'secondStaticValue'=>'2016-05-17') );
                          $response=(array) $client->segmentationAddRecencyCriteriaByObj($xml_array) ;
                          $segmentid=(string)($response['return']); 
                  
                     
                    } catch (Exception $E) {
                        $err .= 'impossible de se connecter au serveur: <b></b><br />';
                        $msg .= '<div style="color: red"><u>( FID )</u> : ' . $err . '</div><br /><br />';
                       
                       
                 }  
    }
    else{
                    $msg .= '<div style="color:red"><u></u> : Aucune configuration de l\'API EMV pour le Compte FID</div></br>';
                    
    }
}    

     return $segmentid ;
  }

  function CountSegment($token,$id,$compte,$porteur){

 $msg=NULL;
 $err=NULL;
 $one="";
          
  

    if($compte=="acq"){
        if (isset(\Yii::app()->params['CMD_EMV_ACQ']['wdsl']) ) {
             
              try {
                 $conf = \Yii::app()->params['CMD_EMV_ACQ'];                       
                  $client = new SoapClient($conf['wdsl']);
                  $xml_array = array('token'=>$token,'id'=>$id);
                  $response=(array) $client->segmentationDistinctCount($xml_array) ;
                  $one=(string)($response['return']); 
                  
               
              } catch (Exception $E) {
                  $err .= 'impossible de se connecter pour faire acquerir fiche client depuis serveur: <b>)</b><br />';
                  $msg .= '<div style="color: red"><u>' . $porteur . '(ACQ) </u> : ' . $err . '</div><br /><br />';
                 
               
              }  
         }
        
        else{
              $msg .= '<div style="color:red"><u>' . $porteur . '(ACQ)</u> : Aucune configuration de l\'API EMV MKT  pour le Compte ACQ </div></br>';
        }
}
elseif($compte=="fid"){
        if (isset(\Yii::app()->params['CMD_EMV_FID']['wdsl']) ) {
                   
                    try {
                      $conf = \Yii::app()->params['CMD_EMV_FID'];                       
                      $client = new SoapClient($conf['wdsl']);
                      $xml_array = array('token'=>$token,'id'=>$id);
                      $response=(array) $client->segmentationDistinctCount($xml_array) ;
                      $one=(string)($response['return']); 
                      
                     
                    } catch (Exception $E) {
                        $err .= 'impossible de se connecter pour faire acquerir des  fiche client depuis serveur: <b>)</b><br />';
                        $msg .= '<div style="color: red"><u>' . $porteur . ' ( FID )</u> : ' . $err . '</div><br /><br />';
                       
                       
                    }  
        }
        else{
                    $msg .= '<div style="color:red"><u>' . $porteur . '</u> : Aucune configuration de l\'API MKT pour le Compte FID</div></br>';
                    
        }

}              


      return $one ;


}
  
  public function criteriaSegmente($token, $id, $columnName, $firstAbsoluteDate, $operator)
  {
              $msg = NULL;
              $err = NULL;
              $segmentid = "";
     if (isset(\Yii::app()->params['CMD_EMV_ACQ']['wdsl']) ) {
        try
        {
          $conf = \Yii::app()->params['CMD_EMV_ACQ'];                       
                  $client = new SoapClient($conf['wdsl']);
                   $xml_array = array('token'=>$token, 'dateDemographicCriteria'=> array( 'id'=>$id, 'groupName'=>'Groupe1',  'groupNumber'=>'1', 'orderFrag'=>'1',  'columnName'=>$columnName, 'absoluteDate'=>0, 'firstAbsoluteDate'=>$firstAbsoluteDate, 'operator'=>$operator,  'secondAbsoluteDate'=>'2016-05-17') );
                  $response=(array) $client->segmentationAddDateDemographicCriteriaByObj($xml_array) ;
                  $segmentid=(array)($response['return']); 
        }
       catch (Exception $E) {
                        $err .= 'impossible de se connecter au serveur: <b>)</b><br />';
                        $msg .= '<div style="color: red"><u> ( ACQ )</u> : ' . $err . '</div><br /><br />';
                       
                     
       }  
     }
     else{
                    $msg .= '<div style="color:red"><u></u> : Aucune configuration de l\'API EMV pour le Compte ACQUISITION</div>';
    }
    if (isset(\Yii::app()->params['CMD_EMV_FID']['wdsl']) ) {
                   
                    try {
                          $conf = \Yii::app()->params['CMD_EMV_FID'];                       
                          $client = new SoapClient($conf['wdsl']);
                           $xml_array = array('token'=>$token, 'dateDemographicCriteria'=> array( 'id'=>$id, 'groupName'=>'Groupe1',  'groupNumber'=>'1', 'orderFrag'=>'1',  'columnName'=>$columnName, 'absoluteDate'=>0, 'firstAbsoluteDate'=>$firstAbsoluteDate, 'operator'=>$operator,  'secondAbsoluteDate'=>'2016-05-17') );
                          $response=(array) $client->segmentationAddDateDemographicCriteriaByObj($xml_array) ;
                          $segmentid=(array)($response['return']); 
                  
                     
                    } catch (Exception $E) {
                        $err .= 'impossible de se connecter au serveur: <b></b><br />';
                        $msg .= '<div style="color: red"><u>( FID )</u> : ' . $err . '</div><br /><br />';
                       
                       
                 }  
    }
    else{
                    $msg .= '<div style="color:red"><u></u> : Aucune configuration de l\'API EMV pour le Compte FID</div></br>';
                    
    }

     return array($segmentid) ;
  }
   public function updatecriteriaSegment($token, $id, $columnName,$groupNumber,$groupName,$orderFrag ,$operator,$values,$compte)
  {
              $msg = NULL;
              $err = NULL;
              $segmentid = "";
if($compte=="acq"){               
     if (isset(\Yii::app()->params['CMD_EMV_ACQ']['wdsl']) ) {
        try
        {
          $conf = \Yii::app()->params['CMD_EMV_ACQ'];                       
                  $client = new SoapClient($conf['wdsl']);
                   $xml_array = array('token'=>$token, 'stringDemographicCriteria'=> array( 'id'=>$id, 'groupName'=>$groupName,  'groupNumber'=>$groupNumber, 'orderFrag'=>$orderFrag,  'columnName'=>$columnName,  'operator'=>$operator,  'values'=>$values) );
                  $response=(array) $client->segmentationUpdateStringDemographicCriteriaByObj($xml_array) ;
                  $segmentid=(string)($response['return']); 
        }
       catch (Exception $E) {
                        $err .= 'impossible de se connecter au serveur: <b>)</b><br />';
                        $msg .= '<div style="color: red"><u> ( ACQ )</u> : ' . $err . '</div><br /><br />';
                       
                     
       }  
     }
     else{
                    $msg .= '<div style="color:red"><u></u> : Aucune configuration de l\'API EMV update critere segment pour le Compte ACQUISITION</div>';
    }
}  
if($compte=="fid"){      
    if (isset(\Yii::app()->params['CMD_EMV_FID']['wdsl']) ) {
                   
                    try {
                          $conf = \Yii::app()->params['CMD_EMV_FID'];                       
                          $client = new SoapClient($conf['wdsl']);
                           $xml_array = array('token'=>$token, 'stringDemographicCriteria'=> array( 'id'=>$id, 'groupName'=>$groupName,  'groupNumber'=>$groupNumber, 'orderFrag'=>$orderFrag,  'columnName'=>$columnName,  'operator'=>$operator,  'values'=>$values));
                          $response=(array) $client->segmentationUpdateStringDemographicCriteriaByObj($xml_array) ;
                          $segmentid=(string)($response['return']); 
                  
                     
                    } catch (Exception $E) {
                        $err .= 'impossible de se connecter au serveur: <b></b><br />';
                        $msg .= '<div style="color: red"><u>( FID )</u> : ' . $err . '</div><br /><br />';
                       
                       
                 }  
    }
    else{
                    $msg .= '<div style="color:red"><u></u> : Aucune configuration de l\'API EMV  update segment pour le Compte FID</div></br>';
                    
    }
}
     return $segmentid ;
  }

   public function updatecriteriaRecencySegment($token, $id, $columnName, $operator,$firstStaticValue,$orderFrag,$compte )
  {
              $msg = NULL;
              $err = NULL;
              $segmentid = "";
if($compte=="acq"){              
     if (isset(\Yii::app()->params['CMD_EMV_ACQ']['wdsl']) ) {
        try
        {
          $conf = \Yii::app()->params['CMD_EMV_ACQ'];                       
                  $client = new SoapClient($conf['wdsl']);
                   $xml_array = array('token'=>$token, 'recencyCriteria'=> array( 'id'=>$id, 'groupName'=>'Groupe3',  'groupNumber'=>'3', 'orderFrag'=>$orderFrag,  'columnName'=>$columnName,  'firstStaticValue'=>$firstStaticValue, 'operator'=>$operator,  'secondStaticValue'=>'2016-05-17') );
                  $response=(array) $client->segmentationUpdateRecencyCriteriaByObj($xml_array) ;
                  $segmentid=(string)($response['return']); 
        }
       catch (Exception $E) {
                        $err .= 'impossible de se connecter au serveur: <b>)</b><br />';
                        $msg .= '<div style="color: red"><u> ( ACQ )</u> : ' . $err . '</div><br /><br />';
                       
                     
       }  
     }
     else{
                    $msg .= '<div style="color:red"><u></u> : Aucune configuration de l\'API EMV pour le Compte ACQUISITION</div>';
    }
}   
elseif($compte=="fid"){ 
    if (isset(\Yii::app()->params['CMD_EMV_FID']['wdsl']) ) {
                   
                    try {
                          $conf = \Yii::app()->params['CMD_EMV_FID'];                       
                          $client = new SoapClient($conf['wdsl']);
                           $xml_array = array('token'=>$token, 'recencyCriteria'=> array( 'id'=>$id, 'groupName'=>'Groupe3',  'groupNumber'=>'3', 'orderFrag'=>$orderFrag,  'columnName'=>$columnName,  'firstStaticValue'=>$firstStaticValue, 'operator'=>$operator,  'secondStaticValue'=>'2016-05-17') );
                          $response=(array) $client->segmentationUpdateRecencyCriteriaByObj($xml_array) ;
                          $segmentid=(string)($response['return']); 
                  
                     
                    } catch (Exception $E) {
                        $err .= 'impossible de se connecter au serveur: <b></b><br />';
                        $msg .= '<div style="color: red"><u>( FID )</u> : ' . $err . '</div><br /><br />';
                       
                       
                 }  
    }
    else{
                    $msg .= '<div style="color:red"><u></u> : Aucune configuration de l\'API EMV pour le Compte FID</div></br>';
                    
    }
}    

     return $segmentid ;
  }

  public function updatecriteriaRecencySegment2($token, $id, $columnName, $operator,$firstStaticValue,$secondStaticValue,$orderFrag,$compte )
  {
              $msg = NULL;
              $err = NULL;
              $segmentid = "";
if($compte=="acq"){              
     if (isset(\Yii::app()->params['CMD_EMV_ACQ']['wdsl']) ) {
        try
        {
          $conf = \Yii::app()->params['CMD_EMV_ACQ'];                       
                  $client = new SoapClient($conf['wdsl']);
                   $xml_array = array('token'=>$token, 'recencyCriteria'=> array( 'id'=>$id, 'groupName'=>'Groupe3',  'groupNumber'=>'3', 'orderFrag'=>$orderFrag,  'columnName'=>$columnName,  'firstStaticValue'=>$firstStaticValue, 'operator'=>$operator,  'secondStaticValue'=>$secondStaticValue) );
                  $response=(array) $client->segmentationUpdateRecencyCriteriaByObj($xml_array) ;
                  $segmentid=(string)($response['return']); 
        }
       catch (Exception $E) {
                        $err .= 'impossible de se connecter au serveur: <b>)</b><br />';
                        $msg .= '<div style="color: red"><u> ( ACQ )</u> : ' . $err . '</div><br /><br />';
                       
                     
       }  
     }
     else{
                    $msg .= '<div style="color:red"><u></u> : Aucune configuration de l\'API EMV pour le Compte ACQUISITION</div>';
    }
}   
elseif($compte=="fid"){ 
    if (isset(\Yii::app()->params['CMD_EMV_FID']['wdsl']) ) {
                   
                    try {
                          $conf = \Yii::app()->params['CMD_EMV_FID'];                       
                          $client = new SoapClient($conf['wdsl']);
                           $xml_array = array('token'=>$token, 'recencyCriteria'=> array( 'id'=>$id, 'groupName'=>'Groupe3',  'groupNumber'=>'3', 'orderFrag'=>$orderFrag,  'columnName'=>$columnName,  'firstStaticValue'=>$firstStaticValue, 'operator'=>$operator,  'secondStaticValue'=>'2016-05-17') );
                          $response=(array) $client->segmentationUpdateRecencyCriteriaByObj($xml_array) ;
                          $segmentid=(string)($response['return']); 
                  
                     
                    } catch (Exception $E) {
                        $err .= 'impossible de se connecter au serveur: <b></b><br />';
                        $msg .= '<div style="color: red"><u>( FID )</u> : ' . $err . '</div><br /><br />';
                       
                       
                 }  
    }
    else{
                    $msg .= '<div style="color:red"><u></u> : Aucune configuration de l\'API EMV pour le Compte FID</div></br>';
                    
    }
}    

     return $segmentid ;
  }

     public function updatedatesegment($token, $id, $columnName, $firstAbsoluteDate, $operator,$secondAbsoluteDate,$compte)
   {
       $msg = NULL;
              $err = NULL;
              $segmentid = "";
 if($compte=="acq"){              
         if (isset(\Yii::app()->params['CMD_EMV_ACQ']['wdsl']) ) {
             try
         {
           $conf = \Yii::app()->params['CMD_EMV_ACQ'];                       
                 $client = new SoapClient($conf['wdsl']);
                 $xml_array = array('token'=>$token, 'dateDemographicCriteria'=> array( 'id'=>$id, 'groupName'=>'Groupe2',  'groupNumber'=>'2', 'orderFrag'=>'0',  'columnName'=>$columnName, 'absoluteDate'=>0, 'firstAbsoluteDate'=>$firstAbsoluteDate, 'operator'=>$operator, 'secondAbsoluteDate'=>$secondAbsoluteDate) );
                 $response=(array) $client->segmentationUpdateDateDemographicCriteriaByObj($xml_array) ;
                 $segmentid=(string)($response['return']); 
           // return array($segmentid) ;
    }
           catch (Exception $E) {
                            $err .= 'impossible de se connecter au serveur: <b>)</b><br />';
                            $msg .= '<div style="color: red"><u> ( ACQ )</u> : ' . $err . '</div><br /><br />';
                           
                         
                        }  
         }
          else{
                        $msg .= '<div style="color:red"><u></u> : Aucune configuration de l\'API EMV pour le Compte ACQUISITION</div>';
                  }
}
elseif($compte=="fid"){ 

     if (isset(\Yii::app()->params['CMD_EMV_FID']['wdsl']) ) {
                   
                    try {
        $conf = \Yii::app()->params['CMD_EMV_FID'];                       
        $client = new SoapClient($conf['wdsl']);
        $xml_array = array('token'=>$token, 'dateDemographicCriteria'=> array( 'id'=>$id, 'groupName'=>'Groupe2',  'groupNumber'=>'2', 'orderFrag'=>'0',  'columnName'=>$columnName, 'absoluteDate'=>0, 'firstAbsoluteDate'=>$firstAbsoluteDate, 'operator'=>$operator, 'secondAbsoluteDate'=>$secondAbsoluteDate) );
        $response=(array) $client->segmentationUpdateDateDemographicCriteriaByObj($xml_array) ;
        $segmentid=(string)($response['return']); 
                  
                     
                    } catch (Exception $E) {
                        $err .= 'impossible de se connecter au serveur: <b></b><br />';
                        $msg .= '<div style="color: red"><u>( FID )</u> : ' . $err . '</div><br /><br />';
                       
                       
                    }  
               }
               else{
                    $msg .= '<div style="color:red"><u></u> : Aucune configuration de l\'API EMV pour le Compte FID</div></br>';
                    
              }
}
     return $segmentid ; 
  }
  

  public function updatedatesegment43($token, $id, $columnName, $firstAbsoluteDate, $operator,$secondAbsoluteDate,$orderFrag,$compte)
   {
       $msg = NULL;
              $err = NULL;
              $segmentid = "";
 if($compte=="acq"){              
         if (isset(\Yii::app()->params['CMD_EMV_ACQ']['wdsl']) ) {
             try
         {
           $conf = \Yii::app()->params['CMD_EMV_ACQ'];                       
                 $client = new SoapClient($conf['wdsl']);
                 $xml_array = array('token'=>$token, 'dateDemographicCriteria'=> array( 'id'=>$id, 'groupName'=>'Groupe2',  'groupNumber'=>'2', 'orderFrag'=>$orderFrag,  'columnName'=>$columnName, 'absoluteDate'=>0, 'firstAbsoluteDate'=>$firstAbsoluteDate, 'operator'=>$operator, 'secondAbsoluteDate'=>$secondAbsoluteDate) );
                 $response=(array) $client->segmentationUpdateDateDemographicCriteriaByObj($xml_array) ;
                 $segmentid=(string)($response['return']); 
           // return array($segmentid) ;
    }
           catch (Exception $E) {
                            $err .= 'impossible de se connecter au serveur: <b>)</b><br />';
                            $msg .= '<div style="color: red"><u> ( ACQ )</u> : ' . $err . '</div><br /><br />';
                           
                         
                        }  
         }
          else{
                        $msg .= '<div style="color:red"><u></u> : Aucune configuration de l\'API EMV pour le Compte ACQUISITION</div>';
                  }
}
elseif($compte=="fid"){ 

     if (isset(\Yii::app()->params['CMD_EMV_FID']['wdsl']) ) {
                   
                    try {
        $conf = \Yii::app()->params['CMD_EMV_FID'];                       
        $client = new SoapClient($conf['wdsl']);
        $xml_array = array('token'=>$token, 'dateDemographicCriteria'=> array( 'id'=>$id, 'groupName'=>'Groupe2',  'groupNumber'=>'2', 'orderFrag'=>'0',  'columnName'=>$columnName, 'absoluteDate'=>0, 'firstAbsoluteDate'=>$firstAbsoluteDate, 'operator'=>$operator, 'secondAbsoluteDate'=>$secondAbsoluteDate) );
        $response=(array) $client->segmentationUpdateDateDemographicCriteriaByObj($xml_array) ;
        $segmentid=(string)($response['return']); 
                  
                     
                    } catch (Exception $E) {
                        $err .= 'impossible de se connecter au serveur: <b></b><br />';
                        $msg .= '<div style="color: red"><u>( FID )</u> : ' . $err . '</div><br /><br />';
                       
                       
                    }  
               }
               else{
                    $msg .= '<div style="color:red"><u></u> : Aucune configuration de l\'API EMV pour le Compte FID</div></br>';
                    
              }
}
     return $segmentid ; 
  }
function getmemberfiche($token,$email,$compte,$porteur){

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
                  $hostname = parse_url($conf['wdsl'], PHP_URL_HOST);                      
                  $client = new SoapClient('http://'.$hostname.'/apimember/services/MemberService?wsdl');
                  $xml_array = array('token'=>$token,'email'=>$email);
                  $response=(array) $client->getMemberByEmail($xml_array) ;
                  $one=(array)($response['return']); 
                  
               
              } catch (Exception $E) {
                  $err .= 'impossible de se connecter pour faire acquerir fiche client depuis serveur: <b>)</b><br />';
                  $msg .= '<div style="color: red"><u>' . $porteur . '(ACQ) </u> : ' . $err . '</div><br /><br />';
                 
               
              }  
         }
        
        else{
              $msg .= '<div style="color:red"><u>' . $porteur . '(ACQ)</u> : Aucune configuration de l\'API EMV MKT  pour le Compte ACQ </div></br>';
        }
}
elseif($compte=="fid"){
        if (isset(\Yii::app()->params[MKT_EMV_FID]['wdsl']) ) {
                   
                    try {
                        $conf = \Yii::app()->params[MKT_EMV_FID];
                        $hostname = parse_url($conf['wdsl'], PHP_URL_HOST);                      
                        $client = new SoapClient('http://'.$hostname.'/apimember/services/MemberService?wsdl');
                        $xml_array = array('token'=>$token,'email'=>$email);
                        $response=(array) $client->getMemberByEmail($xml_array) ;
                        $one=(array)($response['return']); 
                  
                     
                    } catch (Exception $E) {
                        $err .= 'impossible de se connecter pour faire acquerir des  fiche client depuis serveur: <b>)</b><br />';
                        $msg .= '<div style="color: red"><u>' . $porteur . ' ( FID )</u> : ' . $err . '</div><br /><br />';
                       
                       
                    }  
        }
        else{
                    $msg .= '<div style="color:red"><u>' . $porteur . '</u> : Aucune configuration de l\'API MKT pour le Compte FID</div></br>';
                    
        }

}              
}

      return array($one,$msg) ;


}




function getmemberclick($token,$memberid,$compte,$porteur,$page){

 $msg=NULL;
 $err=NULL;
 $two="";
 if (!isset($GLOBALS['porteurMap'][$porteur]) || !\Controller::loadConfigForPorteur($porteur)){
                $msg = '<div style="color:red"><u>' . $porteur . '</u> : Le porteur est introuvable , incapable d\'acquerir les compagnes</div>';
  }              
  else {

    if($compte=="acq"){
        if (isset(\Yii::app()->params[MKT_EMV_ACQ]['wdsl']) ) {
             
              try {
                  $conf = \Yii::app()->params[MKT_EMV_ACQ];                       
                  $client = new SoapClient($conf['wdsl_rpt']);
                  $xml_array = array('token'=>$token,'memberId'=>$memberid,'page'=>$page);
                  $response=(array) $client->getSentMessagesByMember($xml_array) ;
                  $two=(array)($response['return']); 
                  
               
              } catch (Exception $E) {
                  $err .= 'impossible de se connecter pour faire acquerir member click depuis serveur: <b>)</b><br />';
                  $msg .= '<div style="color: red"><u>' . $porteur . '(ACQ) </u> : ' . $err . '</div><br /><br />';
                 
               
              }  
         }
        
        else{
              $msg .= '<div style="color:red"><u>' . $porteur . '(ACQ)</u> : Aucune configuration de l\'API EMV MEMBER click  pour le Compte ACQ </div></br>';
        }
}
elseif($compte=="fid"){
        if (isset(\Yii::app()->params[MKT_EMV_FID]['wdsl']) ) {
                   
                    try {
                        $conf = \Yii::app()->params[MKT_EMV_FID];                       
                        $client = new SoapClient($conf['wdsl_rpt']);
                        $xml_array = array('token'=>$token,'memberId'=>$memberid,'page'=>$page);
                        $response=(array) $client->getSentMessagesByMember($xml_array) ;
                        $two=(array)($response['return']); 
                  
                     
                    } catch (Exception $E) {
                        $err .= 'impossible de se connecter pour faire acquerir des  member click  depuis serveur: <b>)</b><br />';
                        $msg .= '<div style="color: red"><u>' . $porteur . ' ( FID )</u> : ' . $err . '</div><br /><br />';
                       
                       
                    }  
        }
        else{
                    $msg .= '<div style="color:red"><u>' . $porteur . '</u> : Aucune configuration de l\'API member click  pour le Compte FID</div></br>';
                    
        }

}              
}

      return array($two,$msg) ;


}


  /**
   * cette methode permet d'inserer les données d'une compagne dans la dase de données.
   * @return void 
   */
  public function GetNbreClick($adresse,$porteur,$compte){
    $msg=NULL;
    $longeur=NULL;
   $MEMBER_ID=NULL;
    $clicks=0;
    $token=$this->gettoken($porteur,$compte); 
    // return the MEMBER_ID
    $resultat_membere=$this->getmemberfiche($token,$adresse,$compte,$porteur);
    
   
    for ($m=0; $m <count($resultat_membere) ; $m++) { 
      # code...
      if(isset($resultat_membere[$m]['attributes'])){

           $entry=$resultat_membere[$m]['attributes']->entry;
           $source=explode('_',$entry[83]->value);
           
            if($source[1]!=''){
                echo '</br>' ;
                echo $adresse ;
                echo '</br>' ;
                  $MEMBER_ID=$entry[54]->value;
                  // return the CLICK/MEMEBR_ID
    $resultat_click=$this->getmemberclick($token,$MEMBER_ID,$compte,$porteur,1);

     $oi =$resultat_click[0];
     


    $nbTotalItems= $oi['nbTotalItems'];
    $nextPage= $oi['nextPage'];
    $k=1;
    $data=array();
    if(isset( $oi['list'])){
          $data[]= $oi['list'];



    }

  

    
    
      

        
      $k++;

      while ($nextPage) {
        $resultat_click=$this->getmemberclick($token,$MEMBER_ID,$compte,$porteur,$k);
         $oi =$resultat_click[0];
          $nextPage= $oi['nextPage'];
     
        
         foreach($oi['list'] as $node){
          if(isset($node)){
              $data[] = $node;
          }

         }
         // $longeur2=$resultat_click[0]['list'];
         //array_push($data, $longeur2);
        $k++;
      }


if($nbTotalItems>1){
    for ($i=0; $i <$nbTotalItems  ; $i++) { 
        
    
          # code...
        if($data[0])  {
          $alldata = $data[0];
     
          if(isset($alldata[$i]->sendDate ) ){
            $date_divided=  explode('T', $alldata[$i]->sendDate);
            $sendDate=$date_divided[0];
                if( $sendDate == date('Y-m-d')){
                  echo "NOTIF : </br>" ;
                echo $alldata[$i]->messageName ;
                  echo "</br>" ;
               
              }
          }
        }
    }
}
if($nbTotalItems == 1){
       for ($i=0; $i <$nbTotalItems  ; $i++) { 
        
    
          # code...
        if($data[0])  {
          $alldata = $data[0];
         
          if(isset($alldata->sendDate ) ){
            $date_divided=  explode('T', $alldata->sendDate);
            $sendDate=$date_divided[0];
                if( $sendDate == date('Y-m-d')){
                  echo "NOTIF : </br>" ;
                echo $alldata->messageName ;
                  echo "</br>" ;
               
              }
          }
        }
    }

}
      

                  $m=count($resultat_membere) ;
            }
        }
   
    }





    


   
   

    


  }

 
}
?>
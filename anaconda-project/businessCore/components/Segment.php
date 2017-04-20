<?php
/**
 * Description of EMVConnection
 * Classe de communication avec l'API d'EMV.
 *
 * @author JulienL
 * @package	EMV.API
 */
class Segment
{

	
	 function gettoken($porteur,$compte){

  

if($compte=="acq"){
  $conf = \Yii::app()->params['CMD_EMV_ACQ'];                       
  $client = new SoapClient($conf['wdsl']);



  $xml_array = array( 'login' => $conf['login'],'pwd' => $conf['pwd'], 'key' => $conf['key'] );

  $response=(array) $client->openApiConnection($xml_array) ;
  $token=(string)($response['return']); 

}
elseif($compte=="fid"){
  $conf = \Yii::app()->params['CMD_EMV_FID'];                       
  $client = new SoapClient($conf['wdsl']);

  $xml_array = array( 'login' => $conf['login'],'pwd' => $conf['pwd'], 'key' => $conf['key'] );
  $response=(array) $client->openApiConnection($xml_array) ;
  $token=(string)($response['return']); 

}

   
 
return $token;
}
	
	
	
		public function creatSegment($token, $name, $description, $sampleType)
	{

              $msg = NULL;
              $err = NULL;
              $segmentid = "";


			 if (isset(\Yii::app()->params['CMD_EMV_ACQ']['wdsl']) ) {
				 try
		 {
				$conf = \Yii::app()->params['CMD_EMV_ACQ'];                       
                  $client = new SoapClient($conf['wdsl']);
                   $xml_array = array('token'=>$token, 'apiSegmentation'=> array( 'id'=>'111', 'name'=>$name,'description'=>$description,  'sampleType'=>$sampleType, 'sampleRate'=>'11', 'dateCreate'=>'2016-05-17', 'dateModif'=>'2016-05-17') );
                  $response=(array) $client->segmentationCreateSegment($xml_array) ;
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
					$xml_array = array('token'=>$token, 'apiSegmentation'=> array( 'id'=>'1111111', 'name'=>$name,'description'=>$description,  'sampleType'=>$sampleType, 'sampleRate'=>'11', 'dateCreate'=>'2016-05-17', 'dateModif'=>'2016-05-17') );
					$response=(array) $client->segmentationCreateSegment($xml_array) ;
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
	public function criteriaSegment($token, $id, $columnName, $firstAbsoluteDate, $operator)
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
	public function exportHB($token, $mailinglistId, $operationType, $fieldSelection, $dedupFlag, $fileFormat)
	{
		  $msg = NULL;
              $err = NULL;
              $segmentid = "";
		 if (isset(\Yii::app()->params['MKT_EMV_ACQ']['wdsl']) ) {
				 try
		 {
			$conf = \Yii::app()->params['MKT_EMV_ACQ'];                       
            $client = new SoapClient($conf['wdsl']);
            $xml_array = array('token'=>$token, 'mailinglistId'=>$mailinglistId, 'operationType'=>$operationType,  'fieldSelection'=>$fieldSelection, 'dedupFlag'=>$dedupFlag,  'fileFormat'=>$fileFormat, 'keepFirst'=>'false');
            $response=(array) $client->createDownloadByMailinglist($xml_array) ;
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
			                 if (isset(\Yii::app()->params['MKT_EMV_FID']['wdsl']) ) {
                   
                    try {
			$conf = \Yii::app()->params['MKT_EMV_FID'];                       
            $client = new SoapClient($conf['wdsl']);
            $xml_array = array('token'=>$token, 'mailinglistId'=>$mailinglistId, 'operationType'=>$operationType,  'fieldSelection'=>$fieldSelection, 'dedupFlag'=>$dedupFlag,  'fileFormat'=>$fileFormat, 'keepFirst'=>'false');
            $response=(array) $client->createDownloadByMailinglist($xml_array) ;
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
	
	
	
			 	 public function updatesegment($token, $id, $columnName, $firstAbsoluteDate, $operator)
	 {
		   $msg = NULL;
              $err = NULL;
              $segmentid = "";
		 if (isset(\Yii::app()->params['CMD_EMV_ACQ']['wdsl']) ) {
				 try
		 {
			 $conf = \Yii::app()->params['CMD_EMV_ACQ'];                       
             $client = new SoapClient($conf['wdsl']);
             $xml_array = array('token'=>$token, 'dateDemographicCriteria'=> array( 'id'=>$id, 'groupName'=>'Groupe1',  'groupNumber'=>'1', 'orderFrag'=>'0',  'columnName'=>$columnName, 'absoluteDate'=>1, 'firstAbsoluteDate'=>$firstAbsoluteDate, 'operator'=>$operator, 'secondAbsoluteDate'=>'2016-05-17') );
             $response=(array) $client->segmentationUpdateDateDemographicCriteriaByObj($xml_array) ;
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
				$xml_array = array('token'=>$token, 'dateDemographicCriteria'=> array( 'id'=>$id, 'groupName'=>'Groupe1',  'groupNumber'=>'1', 'orderFrag'=>'0',  'columnName'=>$columnName, 'absoluteDate'=>1, 'firstAbsoluteDate'=>$firstAbsoluteDate, 'operator'=>$operator, 'secondAbsoluteDate'=>'2016-05-17') );
				$response=(array) $client->segmentationUpdateDateDemographicCriteriaByObj($xml_array) ;
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
	
		 	public function downloadexport($token, $Idexport)
	{
			$conf = \Yii::app()->params['MKT_EMV_ACQ'];                       
            $client = new SoapClient($conf['wdsl']);
            $xml_array = array('token'=>$token, 'id'=>$Idexport);
            $response=(array) $client->getDownloadFile($xml_array) ;
            $segmentid=(array)($response['return']); 
			return array($segmentid) ;
	}
	
	
	
	 	public function statusexport($token, $Idexp)
	{
			$conf = \Yii::app()->params['MKT_EMV_ACQ'];                       
            $client = new SoapClient($conf['wdsl']);
            $xml_array = array('token'=>$token, 'id'=>$Idexp);
            $response=(array) $client->getDownloadStatus($xml_array) ;
            $segmentid=(array)($response['return']); 
			return array($segmentid) ;
	}
	
 	public function getsegmentbyid($token, $difflistId)
	{
			$conf = \Yii::app()->params['CMD_EMV_ACQ'];                       
            $client = new SoapClient($conf['wdsl']);
            $xml_array = array('token'=>$token, 'difflistId'=>$difflistId);
            $response=(array) $client->segmentationGetSegmentById($xml_array) ;
            $segmentid=(array)($response['return']); 
			return array($segmentid) ;
	}
	
function conn($key){
       
         include(realpath(dirname(__FILE__).'/../../../mod/HB_Affiliate__porteur/connection.php'));
 global $mysqli;
        $mysqli = new mysqli($bdd_server, $bdd_login, $bdd_mdp, $bdd_database);
        
                if ($mysqli->connect_errno) {
                    printf("Échec de la connexion : %s\n", $mysqli->connect_error);
                    exit();
                }
                else echo ' <br> connected to: '.$bdd_database;
               

        return  $mysqli;

 }
 function updateInteraute($sheetData, $key){

 $mysqli= $this->conn($key);
      $req="SELECT email FROM internaute where email like '" .$sheetData."'";
echo '<br>';
// var_dump ($mysqli);
     if ($result = $mysqli->query($req)) {
         $row = $result->fetch_row();

         if($row >=1){
                  echo $row[0]."OK <br>";
				  // echo "OK";
                        $q2="UPDATE `internaute` SET `hardbounce`=1 where email='" .$row[0]."'";
                        $mysqli->query($q2);
       
		 }
        
        

     } // end of db
    
 }
 
 
  function HBaffil($key){
 $mysqli= $this->conn($key);
 
      $yesterday=date('Y-m-d',strtotime("-1 days"));  
	$get_aff_domains = 
				"SELECT f.label AS plateforme, l.`idAffiliatePlatformSubId` AS IDsubID, s.subID AS SubID, v.site AS site, COUNT( email ) AS leads
				FROM internaute i
				INNER JOIN lead_affiliateplatform l ON i.id = l.idInternaute
				INNER JOIN V2_affiliateplatformsubid s ON l.idaffiliateplatformsubid = s.id
				INNER JOIN V2_affiliateplatform f ON l.`idAffiliatePlatform` = f.id
				INNER JOIN invoice v ON i.id = v.idInternaute
				WHERE i.hardBounce =1
				AND DATE( l.creationDate ) >=  '".$yesterday."'
				GROUP BY l.`idAffiliatePlatform` , l.`idAffiliatePlatformSubId` 
				ORDER BY  COUNT( email )DESC, v.site DESC ";
	$get_aff_dom = 
				"SELECT f.label AS plateforme, l.`idAffiliatePlatformSubId` AS IDsubID, s.subID AS SubID, v.site AS site, COUNT( email ) AS leadss
				FROM internaute i
				INNER JOIN lead_affiliateplatform l ON i.id = l.idInternaute
				INNER JOIN V2_affiliateplatformsubid s ON l.idaffiliateplatformsubid = s.id
				INNER JOIN V2_affiliateplatform f ON l.`idAffiliatePlatform` = f.id
				INNER JOIN invoice v ON i.id = v.idInternaute
				WHERE
				DATE( l.creationDate ) >=  '".$yesterday."'
				GROUP BY l.`idAffiliatePlatform` , l.`idAffiliatePlatformSubId` 
				ORDER BY  COUNT( email )DESC, v.site DESC ";
		$donn= $mysqli->query($get_aff_domains);
		$donnaa= $mysqli->query($get_aff_dom);
	$get_aff_doma = 
				"SELECT f.label AS plateforme, v.site AS site, COUNT( email ) AS leads
				FROM internaute i
				INNER JOIN lead_affiliateplatform l ON i.id = l.idInternaute
				INNER JOIN V2_affiliateplatform f ON l.`idAffiliatePlatform` = f.id
				INNER JOIN invoice v ON i.id = v.idInternaute
				WHERE i.hardBounce =1
				AND DATE( l.creationDate ) >=  '".$yesterday."'
				GROUP BY l.`idAffiliatePlatform`
				ORDER BY   v.site DESC ";
		$don= $mysqli->query($get_aff_doma);
      $txt = "";
      $txxt = "";
	  	  	   	 $pr=$key;
	  $lng=explode('_', $pr);
	  $site=$lng[0];
     while ($rowGre = mysqli_fetch_assoc($donnaa)) 
     {
		    if($rowGreyDom = mysqli_fetch_assoc($donn)) 
     { 
		 if (($rowGreyDom["site"] == $site) && ($rowGreyDom["leads"] >= 10)) {
        $txt .= "<tr>  <td> ".$rowGreyDom["plateforme"]."  </td><td>".$rowGreyDom["IDsubID"]."</td> <td>".$rowGreyDom["SubID"]."</td>  <td style='background:yellow' >".$rowGreyDom["leads"]."</td><td >".$rowGre["leadss"]."</td><td >".round(($rowGreyDom["leads"]*100/$rowGre["leadss"]),2)."%</td>  </tr>"; 
        $txxt .= "<tr>  <td> ".$rowGreyDom["plateforme"]."  </td><td>".$rowGreyDom["IDsubID"]."</td> <td>".$rowGreyDom["SubID"]."</td> <td>".$rowGreyDom["site"]."</td> <td style='background:yellow' >".$rowGreyDom["leads"]."</td> <td >".$rowGre["leadss"]."</td><td >".round(($rowGreyDom["leads"]*100/$rowGre["leadss"]),2)."%</td>  </tr>"; 
	  }
	  }
	 }
 

	 
echo '<table border="1"><tr><th scope="col">plateforme</th><th scope="col">IDsubID</th><th scope="col">subID</th><th scope="col">Site</th> <th scope="col">HardBounce</th><th scope="col">Total leads</th><th scope="col">Pourcentage</th></tr>'.$txxt.'  </table>'; 
	  
	//email receiver
	switch ($key) {
			case "au_alisha":
			case "ca_alisha":
			case "uk_alisha":
			case "nz_alisha":
			case "sf_alisha":
				$to = 'aub.deliv.ki@gmail.com, asma.sautane.ki@gmail.com, Najoua.benbahakka.ki@gmail.com, tominfodatamedia@gmail.com, hicham@infodata-media.com';
        break;
			case "uk_aasha":
			case "ca_aasha":
			case "au_aasha":
			$to = 'mod.deliv3@gmail.com, asma.sautane.ki@gmail.com, zakaria.elouafi.ki@gmail.com, tominfodatamedia@gmail.com, hicham@infodata-media.com';
		break;
			case "uk_laetizia":
			case "au_rinalda":
			case "nl_rinalda":
			case "ca_rinalda":
			case "ie_laetizia":
			$to = 'mod.deliv3@gmail.com, zakaria.elouafi.ki@gmail.com, tominfodatamedia@gmail.com, hicham@infodata-media.com';
		break;
			case "nl_laetizia":
			case "nl_rmay":
			$to = 'ayoub.elhabhoub.ki@gmail.com, soufiane.laanibi.ki@gmail.com, tominfodatamedia@gmail.com, hicham@infodata-media.com';
		break;
			case "ar_laetizia":
			case "cl_laetizia":
			$to = 'ayoub.elhabhoub.ki@gmail.com, soufiane.laanibi.ki@gmail.com, tominfodatamedia@gmail.com, hicham@infodata-media.com';
		break;
		
			case "es_laetizia":
			case "mx_laetizia":
			case "de_laetizia":	
			case "fr_rucker":
			case "de_theodor":
			$to = 'aub.deliv.ki@gmail.com, Najoua.benbahakka.ki@gmail.com, idm.leana@gmail.com, hicham@infodata-media.com';
        break;	
			case "sg_alisha":
			$to = 'aub.deliv.ki@gmail.com, asma.sautane.ki@gmail.com, Najoua.benbahakka.ki@gmail.com, idm.leana@gmail.com, hicham@infodata-media.com';
        break;	
				
			case "fr_rmay":
			case "fr_laetizia":
			case "tr_rmay":
			case "it_ml":
			case "es_rmay":
			case "mx_rmay":
			case "de_rmay":
			case "pl_rmay":
			$to = 'mod.deliv3@gmail.com, soufiane.laanibi.ki@gmail.com, idm.leana@gmail.com, hicham@infodata-media.com';
        break;
			case "fr_rinalda":
			case "de_rinalda":
			case "it_rinalda":
			case "it_laetizia":
			$to = 'mod.deliv3@gmail.com, zakaria.elouafi.ki@gmail.com, idm.leana@gmail.com, hicham@infodata-media.com';
        break;

			case "se_laetizia":
			case "no_laetizia":	
			case "no_rinalda":
			case "se_rinalda":
			case "dk_laetizia":
			case "fi_laetizia":
			$to = 'aub.deliv.ki@gmail.com, Najoua.benbahakka.ki@gmail.com, idm.arthurmurphy@gmail.com, hicham@infodata-media.com';
        break;
	
			case "pt_rinalda":
			case "br_rinalda":
			case "dk_rinalda":
			$to = 'mod.deliv3@gmail.com, zakaria.elouafi.ki@gmail.com, idm.arthurmurphy@gmail.com, hicham@infodata-media.com';
		break;

			case "dk_rmay":	
			case "se_rmay":
			case "no_ml":
			case "pt_rmay":
			case "br_rmay":
			case "pt_laetizia":
			case "br_laetizia":
			$to = 'ayoub.elhabhoub.ki@gmail.com, soufiane.laanibi.ki@gmail.com, idm.arthurmurphy@gmail.com, hicham@infodata-media.com';
	break;	
		
			case "in_alisha":
			$to = 'aub.deliv.ki@gmail.com, asma.sautane.ki@gmail.com, Najoua.benbahakka.ki@gmail.com, idm.arthurmurphy@gmail.com, hicham@infodata-media.com';
	break;
	
			case "in_aasha":
			$to = 'mod.deliv3@gmail.com, asma.sautane.ki@gmail.com, zakaria.elouafi.ki@gmail.com, idm.arthurmurphy@gmail.com, hicham@infodata-media.com';
	break;	

			case "cl_ml":
			case "ar_ml":
			$to = 'ayoub.elhabhoub.ki@gmail.com, soufiane.laanibi.ki@gmail.com';
	break;
			case "tr_laetizia":
			$to = 'mod.deliv3@gmail.com, zakaria.elouafi.ki@gmail.com';
		break;
}
       //email subject
       $subject = 'Alert Hard bounce - '.$key.' : ';
     
       $message = "List is : ".$txt;
       $message = '<html><head>  <title> '.$key.' </title></head><body>  <h1 style="color:#274E9C; text-decoration:underline"></h1>  ';
       $message .='<br /> <h3>Thank you for reviewing the following Affiliate Platform SubId : </h3>  <table border="1"><tr style="background: green;"><th scope="col">plateforme</th><th scope="col">IDsubID</th><th scope="col">subID</th><th scope="col">HardBounce</th><th scope="col">Total des Leads</th><th scope="col">Pourcentage</th></tr>'.$txt.'  </table>';
       $message .='<br /> <h3 style="color:#274E9C"><i>Regards, </i><br /> RMDD</h3></body></html>';
     
       $headers  = "From: Analyseur HB<alerts@kindyinfomaroc.com> \r\nReply-To: alerts@kindyinfomaroc.com ";
       $headers .= 'MIME-Version: 1.0' . "\r\n";
       $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
       //send the email  
      $txtt = "";
	  	   	 $pr=$key;
	  $lng=explode('_', $pr);
	  $site=$lng[0];
	  $tar = 'true';
	        while ($rowGreyD = mysqli_fetch_assoc($don)) 
     {
         $txtt .= "<tr>  <td> ".$rowGreyD["plateforme"]."  </td> <td>".$rowGreyD["site"]."</td> <td style='background:yellow' >".$rowGreyD["leads"]."</td>  </tr>"; 

	 if ( $txt !="" && $tar== 'true') {
		 // \Yii::import( 'ext.MailHelper' ); 
	// $mail_sent= \MailHelper::sendMail( $to, 'alerts@kindyinfomaroc.com', $subject, $message );	
	$mail_sent = @mail( $to, $subject, $message, $headers );

       // message sent verification
   echo $mail_sent ? "<br /><h2>Mail sent</h2>" : "<h2>Mail failed</h2>"; 
$tar = 'false';
	 }
 }	

 }
	
}
	?>
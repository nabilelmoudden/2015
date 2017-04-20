<?php
class SegmentController extends Controller
{
public function actionTest()
    {
		
	  	  $Segment=new \Segment();
	  
	  
    \Yii::import( 'ext.HBporteur' ); 
    $HBporteur=new \HBporteur();
	  foreach($GLOBALS['porteurMap'] as $key=>$value){
		$lng=explode('_', $key);
        $site=$lng[0];
        $_GET['site']=$site;
	  \Controller::loadConfigForPorteur($key);
	  
       $token=$Segment->gettoken($value,'acq'); 
       echo '<br>';
	   echo ( '<div><b><u style="color:green">'.$key.' : '.\Yii::app()->params['CMD_EMV_ACQ']['login'].'</u></b></div>');
	    
	   
	   	  	
		
		
	\Yii::import( 'ext.HBswitch' ); 
    $HBswitch=new \HBswitch();
	$id = $HBswitch->HBswi($key);
	  /*******----------------------------------------Créer segment-------------------------------------------------------------------******/
		
		
		
		
	 
		
		
		
		
		
		
		
		
		
		
		
		/*******----------------------------------------Créer critère du segment-------------------------------------------------------------------******/
		
		
			 
	  	
		
		/*******----------------------------------------Mise à jour du segment-------------------------------------------------------------------******/
		$columnName='EMVADMIN5';
		$firstAbsoluteDate=date("Y-m-d", strtotime('-1 day'));
		$operator='ABSOLUTE_ON';
		
		$Critersegment_array=$Segment->updatesegment($token, $id, $columnName, $firstAbsoluteDate, $operator);
		$SegmentCriter=$Critersegment_array[0];
		
		
		/*******----------------------------------------Export des @ HB à partir du segment-------------------------------------------------------------------******/
		
		$mailinglistId=$id;
		$operationType='QUARANTINED_MEMBERS';
		$fieldSelection='EMAIL';
		$dedupFlag='false';
		$fileFormat='pipe';
		$exporsegment_array=$Segment->exportHB($token, $mailinglistId, $operationType, $fieldSelection, $dedupFlag, $fileFormat);
		$Segmentexport=$exporsegment_array[0][0];
		echo('<div><b><u style="color:green">Id de l\'export: '.$Segmentexport.'</u></b></div>');

		/*******----------------------------------------Recuperer Status de l'export HB----------------------------------***/
		$Idexp=$Segmentexport;
		$exporsegment_array=$Segment->statusexport($token, $Idexp);
		$statexpor=$exporsegment_array[0][0];
		

		/*******----------------------------------------Recuperer les adresses de l'export HB----------------------------------***/
		while ($statexpor!="SUCCESS"){
			 
		$Idexp=$Segmentexport;
		$exporsegment_array=$Segment->statusexport($token, $Idexp);
		$statexpor=$exporsegment_array[0][0];
		

		}
		
		$Idexport=$Segmentexport;
		$exporsegment_array=$Segment->downloadexport($token, $Idexport);
		$downloadexpor=$exporsegment_array[0]['fileContent'];
		
		
		
		
		
		
		
	
// Assia porteur
    // 'fr_rucker'         => 'rucker',
    // 'de_theodor'        => 'de_theodor', 
    // 'no_laetizia'       => 'no_laetizia',
    // 'no_rinalda'        => 'no_rinalda',
    // 'se_rinalda'       => 'se_rinalda',
    // 'in_alisha'         => 'en_alisha', 
    // 'au_alisha'         => 'en_alisha',
    // 'sg_alisha'         => 'en_alisha',  
    // 'ca_alisha'         => 'en_alisha',   
    // 'uk_alisha'         => 'en_alisha',
    // 'sf_alisha'         => 'en_alisha',  
    // 'nz_alisha'         => 'en_alisha',    
    // 'de_laetizia'       => 'de_laetizia',  
    // 'dk_laetizia'       => 'dk_laetizia',
    // 'uk_aasha'          => 'en_aasha',
    // 'in_aasha'          => 'en_aasha',   
    // 'ca_aasha'          => 'en_aasha',
    // 'au_aasha'          => 'en_aasha',  
    // 'sg_aasha'          => 'en_aasha', 
    // 'ar_laetizia'       => 'es_laetizia', 
    // 'cl_laetizia'       => 'es_laetizia',
    // 'es_laetizia'       => 'es_laetizia', 
    // 'mx_laetizia'       => 'es_laetizia', 
    // 'se_laetizia'       => 'se_laetizia',
    // 'it_ml'             => 'it_ml',   
    // 'se_rmay'           => 'se_rmay',  
    // 'fr_rinalda'        => 'rinalda',
    // 'nl_rinalda'        => 'nl_rinalda',
    // 'dk_rinalda'        => 'dk_rinalda',
    
    
    
   
    
      
    
          
      
     
    
    
    
    
    
    
    
    
    
    
    
     
    
    
    
	
	
		
		$tabMails = explode("\n", $downloadexpor); 
		
		
		$cont = (count($tabMails)-2);
		echo '<div style="color:blue"><u>Nombre d\'adresse HB pour le porteur '.$key.' est de : '.$cont.'</u></div>';
		if ($cont == 0){
			echo '<div style="color:red"><u>' . $key . '</u> : Pas de HardBounce</div>';
		}
		for ($j=1; $j < $cont; $j++) {
		 
		$email = explode(" ", $tabMails[$j]);
		
		
		
			$lemail=$email[0];
			



		}
		 $HBaff=$Segment->HBaffil($key);
		
	 }
	 
	 


		 
		 
		 
		 
		
		
		
		
		
		
		
		
		
      
	  
	  	
		
		
		
		
		
		
	  
	  
	  
	  	  
	  	
    }
}
	?>
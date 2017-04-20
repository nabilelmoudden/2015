<?php

/**
 * Description of Controller controller
 *
 * @author YacineR
 * @package Controllers
 */


// importer l'extension AnacondaBehavior     
\Yii::import( 'ext.AnacondaBehavior' );

Yii::import( 'ext.Class_API', true );

class SaadController extends AdminController
{
	
	public $layout	= '//login/menu';


	// ************************** ACTION ************************** //
        
    public function actionIndex()
    {
    	
    	$ab= new AnacondaBehavior();
    	
    	$listEmails=$ab->fileToArray("../Anaconda Data/test_import.txt");
    	$gp=3;
     	$ab->ShootPlanification($listEmails,"egg","10/10/2017",$gp);
   	   	   	
    	
    }
    
    public function actionWebFormUpdated()
    {
    	$name = \Yii::app()->request->getQuery('name', false);
    	$ab = new AnacondaBehavior();
    	$ab->updateWebForm($name);  	 
    }
        
	public function actionAnacondaSetting(){
		    
		 
		    	$anaset=null;
		    	if( !($anaset = \Business\AnacondaSettings::load( 20 )) )
		    	{
		    		return false;
		    	}
		    	echo "<br/>".$anaset->groupPrice;
		    	echo "<br/>".$anaset->nextStepSum;
		    	echo "<br/>".$anaset->previousStepClicks;
		    	echo "<br/>".$anaset->durationNext;
		    	echo "<br/>".$anaset->durationPrevious;
		    	echo "<br/>".$anaset->idFirstCampaign;
		    	
		    	
		    	echo "<br/><br/><br/>";
		    	
		    	$analist=null;
		    	
		    	if( !($analist = \Business\AnacondaSettings::loadByGroupPrice( 3 )) )
		    		return false;
		    	
		    	var_dump($analist[0]->durationNext);
		    	echo "<table border=2>
		    			<th>Group Price</th>
		    			<th>Next Step Sum</th>
		    			<th>Previous Step Clicks</th>
		    			<th>Duration Next</th>
		    			<th>Duration Previous</th>
		    			<th>id First Campaign</th>";
		    	for($i=0;$i<sizeof($analist);$i++){
		    	        
		    			echo "<tr>";
		    		  	echo "<td>".$analist[$i]->groupPrice."</td>";
				    	echo "<td>".$analist[$i]->nextStepSum."</td>";
				    	echo "<td>".$analist[$i]->previousStepClicks."</td>";
				    	echo "<td>".$analist[$i]->durationNext."</td>";
				    	echo "<td>".$analist[$i]->durationPrevious."</td>";
				    	echo "<td>".$analist[$i]->idFirstCampaign."</td>";
				    	echo "</tr>";
		    	}
		    	echo "</table>";
	}

    
    public function actionLotCampaign(){
    
    	echo "create lotCampaign";
    	$LotCamp= new \Business\LotCampaign();
    	$LotCamp->numLot=1;
    	$LotCamp->creationDate=date( \Yii::app()->params['dbDateTime']);
    	$LotCamp->save();
    	 
    	
    }
    
    
	//---------------------------------------------------------
	
    
    public function actionImport(){

    	
    	$ab= new AnacondaBehavior();

    	$ab->import("../Anaconda Data/test_import.txt"); 
    	
    	
    }
    
    //---------------------------------------------------------Test de Performance Anas ---------------------------------------------------
    
    //------------------------------------------ Methode 1-------------------------
    static public function getR4R2List()
    {
    
    	$listR4R2=null;
    	$compt=0;
    	//======================================== Traitement R4 ========================================================//
    
    	// J - 8
    	$DateMin8=new DateTime(date('Y-m-d'));
    	$DateMin8->sub(new DateInterval('P8D'));
    	$DateMin8=$DateMin8->format('Y-m-d');
    

    	//Recuperer les leads qui n'ont pas achete (status = 0) et qui ont recu la 4eme relance le jour m�me
    	$listDateMin8=  \Business\CampaignHistory::loadByStatusAndModifiedShootDate(0,$DateMin8);

    	//Recuperer les leads qui n'ont pas achete (status = 0) et qui ont recu la 4eme relance le jour m�me
    	$listDateMin8=\Business\CampaignHistory::loadByStatusAndModifiedShootDate(0,$DateMin8);

    
    	//Pour chaque Campaign History s'il s'agit du P1 ou M1, alimenter la liste
    	foreach($listDateMin8 as $row9)
    	{
    		if($row9->SubCampaign->position==1)
    		{
    			$listR4R2[$compt]=$row9;
    			$compt++;
    			$row9 = '' ;
    		}
    	}
    	unset($DateMin8);
    	unset($listDateMin8);
    	//======================================== Traitement R2 ========================================================//
    
    	// J - 4
    	$DateMin5=new DateTime(date('Y-m-d'));
    	$DateMin5->sub(new DateInterval('P5D'));
    	$DateMin5=$DateMin5->format('Y-m-d');
    
    	//Recuperer les leads qui n'ont pas achete (status = 0) et qui ont recu la 2eme relance le jour m�me
    	$listDateMin5=\Business\CampaignHistory::loadByStatusAndModifiedShootDate(0,$DateMin5);
    
    	//Pour chaque Campaign History s'il s'agit du P2, alimenter la liste
    	foreach($listDateMin5 as $row5)
    	{
    
    		if($row5->SubCampaign->position==2)
    		{
    			$listR4R2[$compt]=$row5;
    			$compt++;
    			$row5 = '' ;
    		}
    	}
    	unset($DateMin5);
    	unset($listDateMin5);
    	// retourner la liste
    	return $listR4R2;
    
    }
    //-------------------------------------------------------Methode 2 ------------------------------------------//

    static public function actionNextGridByUser() {
    	
    	$campaignHistory = \Business\CampaignHistory::load(11);
    	
    	
    	// recuperer le dernier gp
    	if ( isset ( $campaignHistory )) {
    		
    		$LastGpForUser = $campaignHistory->groupPrice;
    		// recuperer settings gp
    		
    		$gpSettings = \Business\AnacondaSettings::getStepSumByGp ( $LastGpForUser );	
    		
    		if (isset ( $gpSettings ) ) {
    			// verifier si le gp est superieur de 1 et indice d'implication = next step sum de la table gp settigns du gp courrant
    			if (($campaignHistory->User->indiceImplication >= end ( $gpSettings )) && $LastGpForUser > 1) {
    			// update indice d'implication par 0 et augmenter le nombre des points
    				echo gettype($gpSettings) ;
    				echo 'sucess' ; 	
    			//	$campaignHistory->User->updateTotalIndice ();
    				return true;
    			}
    
    		}
    	}
    
    	return false;
    }
    
    
    //-------------------------------------------------------Methode 3 Reactivaation  ------------------------------------------//
   public function actionReac() {
   	
   
   	$users = \Business\User::getReactUsersByOpening() ;
   
   $users += \Business\User::getReactUsersByClick() ; 
   	
   	
   
   foreach( $users as $user ) {
   	
   	echo $user->id ; 
   	$behaviourHour =  \AnacondaBehavior::getBehaviourHourLastActionByUser($user->id) ; 
   	
   	echo $behaviourHour ; 
   	
   }
   
   }
   
   //**************************************************** Methode 4 Reactivation ---------------------------------------------------//
   
   public function actionReactivat()
   {
   	 
   	$porteur = \Yii::app()->params['porteur']; 
   	
   	$msg='<br/><div style="color:green">Ex&eacute;cut&eacute; le : '.date( \Yii::app()->params['dbDateTime']).'</div><br/>';
   	 
   	if( !empty($porteur) )
   	{
   		if( !isset($GLOBALS['porteurMap'][$porteur]) || !\Controller::loadConfigForPorteur( $porteur ) )
   			$msg = '<div style="color:red"><u>'.$porteur.'</u> : Le porteur est introuvable</div>';
   			else
   			{
   				$date=new \DateTime();
   				$date->sub( new DateInterval('P2M') );
   				
   			// 	$users=\Business\User::loadInactiveUsers($date->format('Y-m-d'));
   			 
   			  // v2.0 ouverture ou click 
   				$users = \Business\User::getReactUsersByOpening() ;
   				$users += \Business\User::getReactUsersByClick() ;
   			  
   				
   				
   				foreach ($users as $user){
   					$campaign = \AnacondaBehavior::getNextCampaign($user->id);
   					$subCampaign=\Business\SubCampaign::loadByCampaignAndPosition( $campaign->id, 1 );
   					
   				//	$lastCampaignHistory=\Business\CampaignHistory::getLastCampaignHistorybyIdUSer($user->id);
   					//taritement fid suivante
   					$newCampaignHistory=new \Business\CampaignHistory();
   					 
   				//	$gp=\AnacondaBehavior::getGridPriceReactivationByUser($user->email);
   					$gp = 2 ;	 
   					$token[ '__m__' ] = $user->email;
   					$shootDate=new \DateTime();
   					$shootDate->add( new DateInterval('P1D') );
   					$token[ '__date__' ] =$shootDate->format('m/d/Y');
   					$token[ '__h__' ] = 4;
   					$token[ '__gp__' ]=$gp;
   					$token[ '__s__' ] =$campaign->ref."_0";
   					 
   					
   					 
   					$newCampaignHistory->modifiedShootDate=$shootDate->format('Y-m-d');
   					$newCampaignHistory->initialShootDate=$shootDate->format('Y-m-d');
   					$newCampaignHistory->groupPrice=$gp;
   					$newCampaignHistory->status=0;
   					$newCampaignHistory->behaviorHour=4;
   					$newCampaignHistory->idUser=$user->id;
   					$newCampaignHistory->idSubCampaign=$subCampaign->id;
   			// 		$newCampaignHistory->save();
   					 
   					//unbann
   					 
   					$user->dateBanning=NULL;
   					$user->bannReason=0;
   			//		$user->save();
   					 
   			/*		//recupereation du webform de passage inter fid
   					$wf_interfid =\Yii::app()->params['wf_interfid'];
   					$webForm = str_replace( array_keys($token), $token, $wf_interfid );
   					 
   					//execution du webform
   					$Curl	= new \CurlHelper();
   					$Curl->setTimeout(CURL_TIMEOUT);
   					$Curl->sendRequest( $webForm ); */
   					 
   					print_r($token) ;  
   					
   					$msg.="<br/> <span style='color:green;'> R&eacute;activation OK pour:  ".$user->email."</span>";
   
   				}
   			}
   			 
   		/*	if( $sendMail )
   			{
   				echo $msg;
   				\MailHelper::sendMail( $this->anacondaMails, 'Cron', 'Reactivation', $msg );
   				return $msg;
   
   			}
   			else
   				return $msg; */ 
   	}
   }
   
    
    //-------------------*************--------------Methode 5 les acheteurs dhier -------------------------*****************-------------------------------------
   
   	public function actionAchatYes() {
   		
   		
   		//***************les achats ****************************//	
		   		$email = "anas@test.co" ;
		   		$email2 = "evamaria.simul@yahoo.com" ;
		   		
		   		$achat = \Business\Invoice::LoadByEmailPayedInv($email) ;
		   		$achat += \Business\Invoice_V1::LoadByEmailPayedInv($email2) ;
		   		if(isset($achat)) { echo $achat[0] ;    return 0 ; }
		   		
	  	//***************les clicks  ****************************//	
				
				
				$click = \Business\UserBehavior::LoadBdcClickDateByID(64) ;
		   		echo count($click) ; 
				echo $click[0] ; 
		   		
   				
   		//******************************** les Ouvertures *************************//
   		
   				
   				$ouverture = \Business\Openedlinkmail::LoadOuvertureDateByID(115970) ; 
   				echo count($ouverture) ;
   				echo $ouverture[0] ;
   	}
   	
   	
   	
   
   	//---------------------------------------------------------Moteur de test Anas ---------------------------------------------------//
   	
   
   	public function actionGetOpenedLinkMail($idUser , $currentSubCampaign) {
   		
   		$subCampaign = new \Business\SubCampaign ();
   		
   		$reflation = new \Business\SubCampaignReflation ();
   		
   		$openedlinkmail = new \Business\Openedlinkmail ();
   		
   		$positionSubCampaign = $subCampaign->getPositionBySubCampaign ( $currentSubCampaign );
   		
   		$myreflation = $reflation->getSubCampaignReflationBySubCampaign ( $currentSubCampaign );
   		
   		$countOpenedLinkmail = 0;
   		
   		// recuperation du compteur des ouvertures de la fid currentSubCampaign qui ont effectue des decalages de DE
   		for($i = 0; $i < sizeof ( $myreflation ); $i ++) {
   			
   			// recuperation des ouvertures selon les reflations
   			$myopenedlinkmail [$i] = $openedlinkmail->LoadopenedlinkmailBySubCampaignReflationAndUser ( $myreflation [$i]->id, $idUser );
   				
   			if (($myreflation [$i]->number !== 5 && $positionSubCampaign == 1) || ($myreflation [$i]->number !== 3 && $positionSubCampaign == 2)) {
   				if (is_object ( $myopenedlinkmail [$i] ) && $myopenedlinkmail [$i]->shiftDe == 1) {
   						
   					$countOpenedLinkmail ++;
   				}
   			}
   		}
   		
   		return $countOpenedLinkmail ;
   		
   	}
   	
   	
   	
   	public function actionGetNextShootDate($idUser , $currentSubCampaign) {
   		
   		
   		
   		// Recuperation des objets de la subCampaign
   		
   		$campaignHistory = new \Business\CampaignHistory ();
   		
   		$mycampaignHistory = $campaignHistory->seachByIdUSerIdSubCampaign ( $idUser, $currentSubCampaign );
   		
   		$mycampaignHistory = $mycampaignHistory->getData ();

   		//
   		
   		$subCampaign = new \Business\SubCampaign ();
   		
   		$positionSubCampaign = $subCampaign->getPositionBySubCampaign ( $currentSubCampaign );
   		
   		//
   		
   		$deliveryDate = $mycampaignHistory [0]->deliveryDate;
   		
   		$modifiedShootDate = $mycampaignHistory [0]->modifiedShootDate;
   		
   		
   		
if(isset($deliveryDate)) 
   		{    
		   			 var_dump(date ( 'Y-m-d', strtotime ( $modifiedShootDate . ' + 1 days' ) )) ; 
		
   		}
 else {
 	
		$countOpenedLinkMail = self::actionGetOpenedLinkMail($idUser , $currentSubCampaign) ; 
		
		
		 $temp = "P".$countOpenedLinkMail. "D" ; 
			
		// $modifiedShootDate	= new Date ( 'Y-m-d', strtotime ( $modifiedShootDate . $countOpenedLinkMail ) );
		
		$modifiedShootDate = DateTime::createFromFormat('Y-m-d' , $modifiedShootDate) ; 
		
		$modifiedShootDate->sub(new DateInterval ( $temp )) ; 
 				
 			
 		   	if ($positionSubCampaign == 1) {
			
					if ($countOpenedLinkmail != 0) {
				
						//	echo  var_dump(date ( 'Y-m-d', strtotime ( $modifiedShootDate . ' + 10 days' ) ));
						
						$modifiedShootDate->add ( new DateInterval ( 'P10D' ) );
						
						$modifiedShootDate = $modifiedShootDate->format ( 'Y-m-d' );
						
						var_dump($modifiedShootDate) ; 
						
			     	} else {
				
						//	echo var_dump(date ( 'Y-m-d', strtotime ( $modifiedShootDate . ' + 13 days' ) ));
			     		
			     		$modifiedShootDate->add ( new DateInterval ( 'P13D' ) );
			     		
			     		$modifiedShootDate = $modifiedShootDate->format ( 'Y-m-d' );
			     		
			     		var_dump($modifiedShootDate) ;
				    }
			} else {
			
						//	echo var_dump(date ( 'Y-m-d', strtotime ( $modifiedShootDate . ' + 7 days' ) ));
						
						$modifiedShootDate->add ( new DateInterval ( 'P7D' ) );
								
						$modifiedShootDate = $modifiedShootDate->format ( 'Y-m-d' );
						
						var_dump($modifiedShootDate) ;
				}}  		

   	}
    
   	
   	
   	
   	
   	//-------------------*************---------------------------------------*****************--------------------------------------
    //---------------------------------------------------------
    public function actionFileToArray(){
    
    	 
    	$ab= new AnacondaBehavior();
    
    	$listEmails=$ab->fileToArray("../Anaconda Data/test_import.txt");
    	foreach ($listEmails as $email) {
    		echo $email;
    		echo "<br>";
    	} 
    	 
    	 
    }
    
    
	public function actionFirstCampaign(){ 
		
		$ab= new AnacondaBehavior();
    	
		$firstCampaign = new \Business\Campaign();
		$firstCampaign = $ab->getFirstCampaign();
		echo $firstCampaign['ref'];  
    } 
    
    public function actionNextCampaign(){
    	$ab= new AnacondaBehavior();
    	$firstCampaign = new \Business\Campaign();
    	$nextCampaign = new \Business\Campaign();
    	$firstCampaign = $ab->getFirstCampaign();
    	$nextCampaign = $ab->getNextCampaign($firstCampaign);
    	echo $nextCampaign['ref'];  
    }
    
    public function actionR3(){
    	$ab= new AnacondaBehavior();
    	$listEmails=$ab->getReceivedR3("../");
    	foreach ($listEmails as $email) {
    		echo $email;
    		echo "<br>";
    	}
    }
    
    public function actionVP(){
    	$ab= new AnacondaBehavior();
    	$listEmails=$ab->getPayedVP("../");
    	foreach ($listEmails as $email) {
    		echo $email;
    		echo "<br>";
    	}
    }
    
    public function actionSegmentation()
    {
    	 
    	$ab= new AnacondaBehavior();
    
    	$listEmails=$ab->fileToArray("../Anaconda Data/test_import.txt");
    	$gp=3;
    	$result=$ab->segmentation($listEmails,"10/10/2017",$gp);
    	var_dump ($result);
    	 
    	 
    }
    public function actionInterFid()
    {
    	\AnacondaBehavior::passInterFidPayed(\Business\CampaignHistory::load(176)); 
    }
    public function actionWebFormController()
    {
    	
    	$ab= new AnacondaBehavior();
    	
    	$ab->createWebFormByCampaign(563);
    }
    
    public function actionStb()
    {
    	$campaignHistory=\Business\CampaignHistory::load(176);
    	$lastCH=\Business\CampaignHistory::getLastCampaignHistorybyIdUSer($campaignHistory->idUser);
    	if($campaignHistory->id!=$lastCH->id && $lastCH->status==0)
    	{
  
    		$dateToday=new DateTime(date('Y-m-d'));
    		$modifiedShootDate = new DateTime($lastCH->modifiedShootDate);
    		$interval = $dateToday->diff($modifiedShootDate)->format('%a');
    		if($campaignHistory->SubCampaign->position==1 && $interval<9)
    		{
    			switch($interval)
    			{
    				case 0:
    				case 1:
    					$lastCH->status=-1;
    					$lastCH->save();
    					break;
    					
    				case 2:
    				case 3:
    					$lastCH->status=-2;
    					$lastCH->save();
    					break;
    					
    				case 4:
    				case 5:
    					$lastCH->status=-3;
    					$lastCH->save();
    					break;
    					
    				case 6:
    				case 7:
    					$lastCH->status=-4;
    					$lastCH->save();
    					break;
    					
    				case 8:
    					$lastCH->status=-5;
    					$lastCH->save();
    					break;
    			}
    		}
    		else if($campaignHistory->SubCampaign->position==2 && $interval<5)
    		{
    			switch($interval) 
    			{
    				case 0:
    				case 1:
    					$lastCH->status=-1;
    					$lastCH->save();
    					break;
    						
    				case 2:
    				case 3:
    					$lastCH->status=-2;
    					$lastCH->save();
    					break;
    						
    				case 4:
    					$lastCH->status=-3;
    					$lastCH->save();
    					break;
    			}
    		}
    	}
    }
    
    public function actionFirstStb() 
    {
    	$ch=\Business\CampaignHistory::getFistStbByUser(126342);
    	var_dump($ch); 
    }
    
    public function actionCreateSb()
    {
    	$ab= new AnacondaBehavior();
    	$ab->CreateSbSegment();
    	echo "hello";
    }
    
    public function actionTestReflationUser()
    {
    	$ru=\Business\Reflationuser::loadByShootDate('2016-07-05');
    	foreach ($ru as $line)
    	{
    		echo ($line->User->email. "<br/>");
    	}
    }
    public function actionDeleteSeg(){
    	$idSeg = \Yii::app()->request->getQuery('idSeg', false);
    	$ab= new AnacondaBehavior();
    	$ab->deleteSegmentById($idSeg);
    	echo "hello";
    }
    
    public function actionReactivate()
    {
    	\Yii::import( 'ext.CurlHelper' );
    	
    	$date=new \DateTime();
    	$date->sub( new DateInterval('P4M') );
    	$users=\Business\User::loadInactiveUsers($date->format('Y-m-d'));
    	
    	$ab= new AnacondaBehavior();
    	
    	foreach ($users as $user){
    		$campaign = $ab->getFirstCampaign();
    		$subCampaign=\Business\SubCampaign::loadByCampaignAndPosition( $campaign->id, 1 );
    		$camph=\Business\CampaignHistory::loadByUserAndSubCampaign( $user->id, $subCampaign->id );
    		
    		while (isset($camph))
    		{
    			$campaign=$campaign->getNextCampaign();
    			$subCampaign=\Business\SubCampaign::loadByCampaignAndPosition( $campaign->id, 1 );
    			$camph=\Business\CampaignHistory::loadByUserAndSubCampaign( $user->id, $subCampaign->id );
    		}
    		
    		//taritement fid suivante
    		$newCampaignHistory=new \Business\CampaignHistory();
    		
    		$gp=1;//a modifier
    		
    		$token[ '__m__' ] = $user->email;
    		$shootDate=new \DateTime();
    		$shootDate->add( new DateInterval('P1D') );
    		$token[ '__date__' ] =$shootDate->format('m/d/Y');
    		$token[ '__gp__' ]=$gp;
    		$token[ '__s__' ] =$campaign->ref."_0";
    		
    		$lastCampaignHistory=\Business\CampaignHistory::getLastCampaignHistorybyIdUSer($user->id);
    			 
    		$newCampaignHistory->modifiedShootDate=$shootDate->format('Y-m-d');
    		$newCampaignHistory->initialShootDate=$shootDate->format('Y-m-d');
    		$newCampaignHistory->groupPrice=$gp;
    		$newCampaignHistory->status=0;
    		$newCampaignHistory->behaviorHour=$lastCampaignHistory->behaviorHour;
    		$newCampaignHistory->idUser=$user->id;
    		$newCampaignHistory->idSubCampaign=$subCampaign->id;
    		$newCampaignHistory->save();
    		
    		//unbann
    		
    		$user->dateBanning=NULL;
    		$user->bannReason=0;
    		$user->save();
    		
    		//recupereation du webform de passage inter fid
		 	$wf_interfid =\Yii::app()->params['wf_interfid'];
		 	$webForm = str_replace( array_keys($token), $token, $wf_interfid );
		 	 
		 	echo "<br/><br/>". $webForm;
		 	
		 	//execution du webform
		 	$Curl	= new \CurlHelper();
		 	$Curl->setTimeout(CURL_TIMEOUT);
		 	return $Curl->sendRequest( $webForm );
    			
    	}
    }

    
    public function actionExecRecativate()
    {
    	Yii::import('application.commands.*');
    	$command = new CronCommand('test','test');
    	$command->actionReactivate( "fr_evamaria" );
    }
    
    
    public function actionUpdateGridPriceReactivationByUser($email){
    	switch (\Business\Invoice::getPurchasedAnaconda ( $email )) {
			case 0 :
				 $GP = 3;
				break;
			case 1 :
				 $GP = 2;
				break;
			
			default :
				 $GP = 1;
				break;
    	}
    	return $GP;
    }
    
    public function actionShooted(){
    	$list=\Business\CampaignHistory::getShooted('2016-12-06', '2016-12-14', 536);
    	print_r($list);
    }
    
    public function actionStatus(){
    	$campaign=\Business\Campaign::load(578);
    	$status=$campaign->getStatus();
    	echo ($status);
    }
    
    public function actionFirstSC(){
    	$first=\Business\SubCampaign::loadfirst();
    	echo $first->id;
    }
    
    public function actionUpdateseg(){
    	$ab= new AnacondaBehavior();
    	$ab->updateSbSegment(1,13840);
    }
    
    public function actionUpdateSbSegment(){
    	//appel de la classe api
    	Yii::import( 'ext.Class_API', true );
    	
    	$porteurMapp = Yii::app()->params['porteur'];
    	\Controller::loadConfigForPorteur($porteurMapp);
    	
    	//r�cup�ration des parametres de l'API
    	$mkt_wdsl =\Yii::app()->params['MKT_EMV_ACQ']['wdsl'];
    	$mkt_login =\Yii::app()->params['MKT_EMV_ACQ']['login'];
    	$mkt_pwd =\Yii::app()->params['MKT_EMV_ACQ']['pwd'];
    	$mkt_key =\Yii::app()->params['MKT_EMV_ACQ']['key'];
    	
    	$class_api = new Class_API($mkt_wdsl,$mkt_login,$mkt_pwd,$mkt_key);
    	
    	//token de connexion
    	$token = $class_api->connexion();
    	$triggers=array();
    	
    	$result=$class_api->soap_client->getExportableCampaigns(
    			array('token'=> $token,
    					'page'=>'1',
    					'perPage'=>'100',
    					'beginDate'=>'2016-07-28T13:22:04+01:00',
    					'endDate'=>'2016-10-07T13:22:04+01:00',
    					'campaignType'=>'TRIGGER'
    			)
    			);
    	
    	$nbTotalItems=$result->return->nbTotalItems;
    	$nbrPages=ceil($nbTotalItems/100);
    	echo "nbTotalItems:  ".$nbTotalItems."<br/>nbrPages:   ".$nbrPages."<br/>";
    	
    	if (isset($result->return->campaigns->campaign))
    	{
    		$triggers=array_merge($triggers, $result->return->campaigns->campaign);
    	}
    	
    	for($i=1;$i<$nbrPages;$i++)
    	{
    		$result=$class_api->soap_client->getExportableCampaigns(
    				array('token'=> $token,
    						'page'=>$i+1,
    						'perPage'=>'100',
    						'beginDate'=>'2016-07-28T13:22:04+01:00',
    						'endDate'=>'2016-10-07T13:22:04+01:00',
    						'campaignType'=>'TRIGGER'
    				)
    				);
    		$triggers=array_merge($triggers, $result->return->campaigns->campaign);
    	}
    	
    	$ab= new AnacondaBehavior();
    	foreach ($triggers as $trigger) {
    		
    	
    		$output_array=array();
    		preg_match("/(.*)\s*>\s*LDV\s*(.*)/", $trigger->name, $output_array);
    	
    		if(isset($output_array[1]))
    		{
    			$anacondaTrigger=\Business\AnacondaTrigger::loadByIdTrigger($trigger->triggerId);
    			if(!isset($anacondaTrigger))
    			{
    				$name=$output_array[1];
    				
    				$campaign=\Business\Campaign::loadByRef(trim($name));
    				if(!empty($campaign))
    				{
    					if(stripos($name, 'ct')!== false && $campaign->hasCT()){
    						$position=2;
    						$tmp=explode('ct', $name);
    						$name=$tmp[0];
    					}
    					else
    					{
    						$position=1;
    					}
		    			
		    			$subCampaign=\Business\SubCampaign::loadByCampaignAndPosition( $campaign->id, $position );
		    			
		    			$anacondaTrigger= new \Business\AnacondaTrigger();
		    			$anacondaTrigger->idTrigger=$trigger->triggerId;
		    			$anacondaTrigger->nameTrigger=$trigger->name;
		    			$anacondaTrigger->idSubCampaign=$subCampaign->id;
						$anacondaTrigger->save();
		    			
		    			$ab->updateSbSegment($position, $trigger->triggerId);
		    			
		    			echo $trigger->triggerId ." ------------ ".$name." ------------ ".$position."<br/>";
    				}
    			}
    		}
    	}
    }
    
    public function actionSoftB(){
    	$ab= new AnacondaBehavior();
    	$ab->setSb("../");
    
    	 
    }
    
    public function actionNextCampaign2(){
    	$nextCampaign = \AnacondaBehavior::getNextCampaign(126343);
    	echo $nextCampaign['ref'];
    	echo "<br/> hello yo yo <br/>";
    }
  
  
    
    public function actionHours(){
    	$dateToday = new DateTime();
    	$ch=\Business\CampaignHistory::load(296);
    	$result=$ch->hasPurshasedSixHour( $dateToday );
    	if($result)
    		echo "yes";
    	else 
    		echo " no!!! <br/> #OKBYE ";
    }
    
    public function actionUpdateSoftBounce()
    {
    	Yii::import('application.commands.*');
    	$command = new CronCommand('test','test');
    	$command->actionUpdateSoftBounce( "fr_evamaria" );
    }
public function actionTestWebForm(){
	$idProduct = \Yii::app()->request->getQuery('idProduct', false);
	$type = \Yii::app()->request->getQuery('type', false);

	if(count(\Business\RouterEMV::loadByTypeAndIdProductAndCompteEMV($idProduct,$type,'FR_EMA'))==0)
		echo 'ouiii';
	else
		echo 'noooon';
}

public function actionLeadaffiliate()
{
	
	$list=\Business\LeadAffiliatePlatform::getReceivedR3();
	foreach($list as $lead)
	{
		echo $lead ."<br/>";
	}
}

public function actionSetting()
{
	$setting=\Business\PorteurSettings::getAllSettings();
	echo $setting[0]->periodAnaconda;
}

public function actionPayedVP()
{
	$list=\Business\Invoice_V1::getPayedVPV1();
	foreach($list as $lead)
		{
			echo $lead ."<br/>";
		}

}

public function actionReferenceVPV2()
{
	$list=\Business\Invoice::getPayedVP();
	foreach($list as $lead)
	{
		echo $lead ."<br/>";
	}
}

public function actionWebformPayment()
{
	\AnacondaBehavior::execWebFormPayment(\Business\CampaignHistory::load(208));
}
public function actionTestSub()
{
	$email = \Yii::app()->request->getQuery('m', false);
echo \Business\Invoice_V1::getNbrPurshasedOldAnacondaByEmail($email);
}

public function actionExecDeliverStandByInter()
{
	Yii::import('application.commands.*');
	$command = new CronCommand('test','test');
	$command->actionDeliverStandByInter( "fr_evamaria", 6 );
}

public function actionAllWebFormRouterEmv()
{
	//echo count(\Business\RouterEMV::GetWebFormAnaconda());
	$j = 1 ;
	foreach(\Business\RouterEMV::GetWebFormAnaconda() as $element)
	{
		echo  '-------------------------  '. $j .  '  -----------------------<br>';
		echo 'ancien url : ' . $element->url .'<br>';
		$router=new \Business\RouterEMV();
		$router->updateWebForm($element->id);
		$j++;
	}
	$j = 1 ;
	foreach(\Business\RouterEMV::GetWebFormAnaconda() as $element)
	{
		echo  '-------------------------  '. $j .  '  -----------------------<br>';
		echo 'nouveau url : ' . $element->url .'<br>';
		$j++;
	}
}

public function actionListCampaignHistory(){
$dateMin = \Yii::app()->request->getQuery('date',false); 
$dateMax = \Yii::app()->request->getQuery('date2',false);
$list = array('599','602');
$cussus=new \Business\CampaignHistory();

echo count($cussus->getCampaignHistoryByDate($dateMin,$dateMax,$list));

foreach($cussus->getCampaignHistoryByDate($dateMin,$dateMax,$list) as $element)
{	echo $element->id.'<br>';
}
}

public function actionLastCampaignHistory(){
	$idUSer = \Yii::app()->request->getQuery('user',false);
	$subCampaign = \Yii::app()->request->getQuery('campaign',false);

	$cussus=new \Business\CampaignHistory();

	//echo count($cussus->getLastCampaignHistoryByIdUserBeforeSixDay($idUSer));

	foreach($cussus->getLastCampaignHistoryByIdUserBeforeSixDay($idUSer) as $element)
	{	echo $element->id.'<br>';
	}
}

public function actionHasPurshasedInterNextSixHour()
{
	$ch=\Business\CampaignHistory::load(468);
	$date = new \DateTime();
	if(!$ch->hasPurshasedInterInSixHour($date))
	{
		echo "true";
	}
	else 
	{
		echo "false";
	}
}


public function actionSetSupposedGp() 
{
		//user de la campaignHistory
		$idUSer = \Yii::app ()->request->getQuery ( 'user', false );

		//$idCampaignHistory2 = c'est la campaign a verifier (envoyee par le cron)
		$idCampaignHistory2 = \Yii::app ()->request->getQuery ( 'campaign', false );
		\AnacondaBehavior::SupposedGp($idUSer,$idCampaignHistory2);
		
	
	}
	
	public function actionTestReflataionuser()
	{
		$idUSer = \Yii::app ()->request->getQuery ( 'user', false );
		$idCampaign2 = \Yii::app ()->request->getQuery ( 'campaign', false );
		foreach(\Business\Reflationuser::loadByIdUserAndIdSubCamapaign ( $idUSer, 	$idCampaign2 ) as $element){
		echo $element->id; 				
		} 
		
	}
	public function actionGetSBBannList()
	{
		$list=\AnacondaBehavior::GetSBBannList(7);
		var_dump($list);
	}
	
	public function actionGetInactiveUsersSB()
	{
		\AnacondaBehavior::getInactiveUsersSB("../");
	}
	
	public function actionBannAnacondaUsers()
	{
		Yii::import('application.commands.*');
		$command = new CronCommand('test','test');
		$command->actionBannAnacondaUsers( "fr_evamaria" );
	}
	
	
	public function actionGetUsersByOriginAndDate()
	{
		$date=new DateTime(date('Y-m-d'));
		print_r(\Business\User::getUsersByOriginAndDate ($date,1));
	}
	
	public function actionGetReactivatedUsersByDate()
	{
		$date=new DateTime(date('Y-m-d'));
		print_r(\Business\User::getReactivatedUsersByDate ($date));
	}
	
	public function actionGetCampaignByPosition(){
	
		$campaign=\AnacondaBehavior::getCampaignByPosition($_GET['position']);
		if(isset($campaign))
			echo $campaign->ref;
		else  
			echo "no";
	}
	
	public function actionGetPositionByCampaign(){
	
		//$campaign=\AnacondaBehavior::getFirstCampaign ();
		$campaign=\Business\Campaign::load($_GET['id']);
		echo \AnacondaBehavior::getPositionByCampaign($campaign);
	}
	
	public function actionGetSentMessagesByReflationAndDate(){
	
		$date=new DateTime($_GET['date']);
		\AnacondaBehavior::getSentMessagesByReflationAndDate($date,$_GET['idSubCampaignReflation'],$_GET['vague']);
		/*foreach ($list as $ligne)
		{
			$user=\Business\User::load($ligne->idUser);
			$ligne->origin = $user->origin;
		}
		$json = \CJSON::encode($list);
		var_dump($json);*/
		//var_dump($list);
		
	}
	
	public function actionGetLastShootedSubCampaign()
	{
		$ch=\Business\CampaignHistory::load($_GET['idCH']);
		$list=$ch->getLastShootedSubCampaign();
		$json = \CJSON::encode($list);
		echo $json;
	}
	
	public function actionLoadByDateAndSubcampaignreflationAndTimeSlot()
	{
		$dateMin=new DateTime($_GET['dateMin']);
		$dateMax=new DateTime($_GET['dateMax']);
		$list=\Business\EcartShoot::loadByDateAndSubcampaignreflationAndTimeSlot($dateMin, $dateMax, $_GET['idSubCampaignReflation'], $_GET['vague']);
		$json = \CJSON::encode($list);
		echo $json;
	}
	
}
?>
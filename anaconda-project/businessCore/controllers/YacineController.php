<?php
/**
 * Description of Controller controller
 *
 * @author YacineR
 * @package Controllers
 */
 
// importer l'extension AnacondaBehavior
\Yii::import( 'ext.AnacondaBehavior' );
\Yii::import( 'ext.MailHelper' );


class YacineController extends AdminController
{
	
	public $layout	= '';
 	
	/**
	 * Initialisation du controleur
	 */
	
    public function actionIndex()
    {
		    		//var_dump($userlist[0]->User);
		    		
		    		$ch=new \Business\CampaignHistory(); 
		    		$result=$ch->searchUsersByIdSubCampaign(499);
		    		$results=$result->getData();
		    		var_dump($results[0]->idUser);
		    		
		    		
		    		$ch1=new \Business\CampaignHistory();
		    		$ch1->groupPrice=3;
		    		$ch1->idUser=40;
		    		$ch1->idSubCampaign=487;
		    		if($ch1->save())
		    		{
		    			echo "<br/>saved";
		    		}
		    		else
		    		{
		    			echo "<br/>not saved";
		    		}
    }
        
	public function actionAnacondaSetting(){
		    

		    	
		    	$anaset=null;
		    	if( !($anaset = \Business\AnacondaSettings::load( 20 )) )
		    		return false;
		    	
		    	echo "<br/>".$anaset->groupPrice;
		    	echo "<br/>".$anaset->nextStepSum;
		    	echo "<br/>".$anaset->previousStepClicks;
		    	echo "<br/>".$anaset->durationNext;
		    	echo "<br/>".$anaset->durationPrevious;
		    	echo "<br/>".$anaset->idFirstCampaign;
		    	
		    	
		    	echo "<br/><br/><br/>";
		    	
		    	$analist=null;
		    	
		    	if( !($analist = \Business\AnacondaSettings::getSettingsByGp( 3 )) )
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
    	$LotCamp= new \Business\CampaignHistory();
    	$LotCamp->status=4;
    	$LotCamp->idSubCampaign=424;
    	$LotCamp->idUser=16;
    	$LotCamp->save();
    	 
    	
    }
    
    
	//---------------------------------------------------------
	
    public function actionSetUserDateBan()
    {
    	$ab= new AnacondaBehavior();
    	$email=$_GET['m'];
    	echo "<br/>".$email."<br/>";
    	if($ab->setUserDateBanningByUser($email))
    		echo "Date Bann Updated : ".date( \Yii::app()->params['dbDateTime']);
    	else 
    		echo "mail not found";
    	
    	echo "<br/><br/>===================================<br/><br/>";
   
    	if($ab->setBannReasonByUser($email,2))
    		echo "Bann Reason Updated: ".date( \Yii::app()->params['dbDateTime']);
    		else
    			echo "mail not found";

    }
    
    public function actionImport(){

    	
    	$ab= new AnacondaBehavior();

    	$ab->import("../Anaconda Data/test_import.txt"); 
    	
    	
    }
    
    
    //---------------------------------------------------------
    
    public function actionFileToArray(){
    
    	 
    	$ab= new AnacondaBehavior();
    
    	$listEmails=$ab->fileToArray("../AnacondaData/test_import.txt");
    	foreach ($listEmails as $email) {
    		echo $email;
    		echo "<br>";
    	} 
    	 
    	 
    }
    
    //----------------------------------------------------------
    
	public function actionFirstCampaign(){ 
    	
		$firstCampaign = new \Business\Campaign();
		$firstCampaign->getFirstCampaign();
		echo $firstCampaign['ref'];
    } 
    
    
    //test de la m�thode shoot planification
    public function actionShootPlanification()
    {
    	
   	 
		$list=null;
		
		$list[0]="EMAIL";
		for($i=1;$i<=$_GET['number'];$i++)
		{
			$list[]="email".$i."@mail.mco";
		}
		
    	$gp=3;
    	$path=\AnacondaBehavior::ShootPlanification($list,"egg","10/10/2017",$gp,"../");
    	echo "<br/>".$path; 

    	
    	
    }
    
    //test list banned users
    public function actionSblist()
    {
    	$absb= new AnacondaBehavior();
    	$list=$absb->GetSBBannList();
    	for($i=0;$i<sizeof($list);$i++)
    	{
    		echo "<br/>".$list[$i]."===============>".\Business\User::loadByEmail( $list[$i] )->countSoftBounce."<br/>";
    	}
    }
    /**
     *  @author soufiane balkaid
     *  @desc creation des segment shoot/livraison au niveau de smartFocus
     *  
     */
    public function actionCreateListOfSegmeent()
    {
    	$site=\Yii::app()->request->getQuery('site', false);
    	$nbrInterval=\Yii::app()->request->getQuery('nbrInterval', false);
    	$idCampaign = \Yii::app()->request->getQuery('idcampaign', false);
    	$ab= new AnacondaBehavior();
    	$ab->CreateSegmentByCampagin($idCampaign,$site,$nbrInterval);
    }
    
    public function actionTestmodel()
    {


		$ab= new AnacondaBehavior();
		$oldestDate=$ab->getInactiveUsersByPeriod(4);
		echo "<br/>".$oldestDate;
		
		
    }
    
    public function actionInactiveUsers(){
    	
    	$listEmails=\AnacondaBehavior::getInactiveUsers("../");
    	
    	foreach ($listEmails as $email) {
    		echo $email;
    		echo "<br>";
    	}
    }
    
	public function actionInitAnaconda()
    {
		//initialisation des variables $msg : corps du mail � envoyer lors de l'execution du cron, $gp : groupe de prix initial des leads � integrer dans le processus Anaconda.
        $msg='<br/><div style="color:green">Ex&eacute;cut&eacute; le : '.date( \Yii::app()->params['dbDateTime']).'</div><br/>';  
        $listR3=null;
        $listVP=null;
        $listToImport=null;
        $gp=3;
        $dir="../";
        
        //Instantiation de l'extension Anaconda Behavior
        $anacbehavior=new AnacondaBehavior();
        
        $listR3=$anacbehavior->getReceivedR3($dir);
        $listVP=$anacbehavior->getPayedVP($dir);
        
        //r�cupere les leads qui on re�u la 3 �me relance de la VP sans  ou bien achet� la VP
        if($listR3!=null && $listVP!=null)
        {
            $msg.='<br/><div style="color:green">Nouveaux leads VP et R3 export&eacute;s avec succ&egrave;s, le : '.date('Y-m-d')."</div><br/>";
            
            //supprimer la ligne EMAIL de la liste des acheteurs de la VP
            unset($listVP[0]);
            
            //concat�ner les deux listes
            $listToImport=array_merge($listR3,$listVP);
            
            // ShootDate J+1
            $dateToday = new DateTime(date('Y-m-d'));
            $shootDate=$dateToday->format('d/m/Y');
           
            //Segmentation : mise � jour fiche client + BD
            $statutSegmentation=false;
            $statutSegmentation=$anacbehavior->segmentation($listToImport,$shootDate,$gp,$dir);

            if($statutSegmentation==1)
            {    
                $msg.='<br/><div style="color:green">Nouveaux leads VP et R3 import&eacute;s avec succ&egrave;s, le  : '.date('Y-m-d')."</div><br/>";
            }
            else if($statutSegmentation==2)
            {
                $msg.='<br/><div style="color:red">Importation Smart Focus KO  : '.date('Y-m-d')."</div><br/>";
            }
            else if($statutSegmentation==3)
            {
                $msg.='<br/><div style="color:red">La premi&egrave;re FID Anaoncda inexsistante : '.date('Y-m-d')."</div><br/>";
            }
            else 
            {
                $msg.='<br/><div style="color:green">Nouveaux leads VP et R3 import&eacute;s avec succ&egrave;s le '.date('Y-m-d').'<br/>Sauf  les leads suivants qui n\'existent pas dans la BD : </div><br/>';
                foreach($statutSegmentation as $emailKO)
                {
                    $msg.='<br/><div style="color:red">'.$emailKO.'</div><br/>';
                }
            }

        }
        else 
        {
            $msg.='<br/><div style="color:red">probl&egrave;me d\'export des nouveaux leads Anaconda le : '.date('Y-m-d')."</div><br/>";
        }
        
        
        
        echo "shoot date : ".$shootDate."<br/>";
        foreach($listToImport as $email)
        {
        	echo "<br/>---------<br/>".$email;
        }
        
        echo "<br/>".$msg."<br/>";
        
        $adminMails = array(
                //'julienl@esoter2015.com',
                //'laurent.dere@ld-ci.com',
                //'othmane.halhouli.ki@gmail.com',
                //'jalal.bensaad@kindyinfomaroc.com',
                //'fabienc@esoter2015.com'
                'yacine.rami@kindyinfomaroc.com'
        );
            
        $MH=new MailHelper();
        $MH->sendMail( $adminMails, 'Cron', 'Init Anaconda', $msg);
        
    }
    
    public function actionLoadConfig()
    {
    	$mkt_wdsl =\Yii::app()->params['MKT_EMV_ACQ']['wdsl'];
    	$mkt_login =\Yii::app()->params['MKT_EMV_ACQ']['login'];
    	$mkt_pwd =\Yii::app()->params['MKT_EMV_ACQ']['pwd'];
    	$mkt_key =\Yii::app()->params['MKT_EMV_ACQ']['key'];
    	echo "<br/>------------------------<br/>".$mkt_wdsl."<br/>";
    	echo "<br/>------------------------<br/>".$mkt_login."<br/>";
    	echo "<br/>------------------------<br/>".$mkt_pwd."<br/>";
    	echo "<br/>------------------------<br/>".$mkt_key."<br/>";
    	\Controller::loadConfigForPorteur('fr_rmay');
    	$mkt_wdsl =\Yii::app()->params['MKT_EMV_ACQ']['wdsl'];
    	$mkt_login =\Yii::app()->params['MKT_EMV_ACQ']['login'];
    	$mkt_pwd =\Yii::app()->params['MKT_EMV_ACQ']['pwd'];
    	$mkt_key =\Yii::app()->params['MKT_EMV_ACQ']['key'];
		echo "<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>";
    	echo "<br/>------------------------<br/>".$mkt_wdsl."<br/>";
    	echo "<br/>------------------------<br/>".$mkt_login."<br/>";
    	echo "<br/>------------------------<br/>".$mkt_pwd."<br/>";
    	echo "<br/>------------------------<br/>".$mkt_key."<br/>";
    	\Controller::loadConfigForPorteur('no_rinalda');
    	$mkt_wdsl =\Yii::app()->params['MKT_EMV_ACQ']['wdsl'];
    	$mkt_login =\Yii::app()->params['MKT_EMV_ACQ']['login'];
    	$mkt_pwd =\Yii::app()->params['MKT_EMV_ACQ']['pwd'];
    	$mkt_key =\Yii::app()->params['MKT_EMV_ACQ']['key'];
    	echo "<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>";
    	echo "<br/>------------------------<br/>".$mkt_wdsl."<br/>";
    	echo "<br/>------------------------<br/>".$mkt_login."<br/>";
    	echo "<br/>------------------------<br/>".$mkt_pwd."<br/>";
    	echo "<br/>------------------------<br/>".$mkt_key."<br/>";
    }
    
    public function ActionExecCron()
    {
    	Yii::import('application.commands.*');
    	$command = new CronCommand('test','test');
    	$command->actionInitAnaconda2( "fr_evamaria" );
    	
    }
    
    public function actionTestError()
    {
    	$porteur = null;
    	$porteurMapp = $GLOBALS['porteurMap'][explode('_',$porteur)[0].'_'.explode('_',$porteur)[1]];
    	
    	\Controller::loadConfigForPorteur($porteurMapp);
    }	
    
    public function actionUpdateEMV() 
    {
    	$list=array('ahmed.daoudi@kindyinfomaroc.com'=>'617',
    			'Hamid.dihaji@kindyinfomaroc.com'=>'627',
    			'ranya.cuyesse@kindyinfomaroc.com'=>'627',
    			'yacine.de.lucia@kindyinfomaroc.com'=>'627',
    			'marroc@live.ca'=>'484',
    			'saa.d.hdidou@gmail.com'=>'484'
    	);
    	
    	\AnacondaBehavior::updateEMVDE($list,"../");
    }
    
    public function actionDeliverSB()
    {
    	//Initialiser les objets
    	$list=null;
    	$product=null;
    	$subCamp=null;
    	$campaign=null;
    	$invoices=null;
    	$user=null;
    	//S'il existe des campaigns en Stand By
    	if($list=\Business\CampaignHistory::loadStandByCampaigns())
    	{
	    	
	    	foreach($list as $camph)
	    	{
	    		$product=$camph->SubCampaign->Product;
	    		$subCamp=$camph->SubCampaign;
			    $campaign=$camph->SubCampaign->Campaign;
			    $refCamp=$campaign->ref;
				$email=$camph->User->email;		 
				
				//Tester si tous les objets sont rꤵp곩s
				if($product && $subCamp && $campaign && $refCamp && $refCamp && $email)
				{
				    		
					//charger l'invoice de la campaign History par mail et ref Campaign
					if($invoices=\Business\Invoice::loadByMail( $email, $refCamp))
					{
					    			
					    foreach($invoices as $invoice)
					    {
					    	//Tester si l'invoice est validꥠet le produit li顠 l'invoice est identique ࡣelui rꤵp곥r de la subCampaign
					    	if($invoice->invoiceStatus==2 && $invoice->RecordInvoice[0]->refProduct==$product->ref)
					    	{
					    					
					    		//charger le processeur de paiement par invoice
								$PaymentProcessor = \Business\PaymentProcessor::loadByInvoice( $invoice->id );
								if( !is_object($PaymentProcessor) )
								throw new \EsoterException( 200, \Yii::t( 'error', '200' ) );
								    		
								//Traitement du paiement
								if( !$PaymentProcessor->treatResultStandBy() )
								throw new \EsoterException( 201, \Yii::t( 'error', '201' ) );
								    			
								//mise ࡪour du statuts de la campaign History
								$camph->status=2;
								$camph->save();
								    		
								    		
					    	}
						}
					}
					    		
				 }
	    	}
	    	

	    	
    	}
    }
    
    public function ActionExecDeliverCron()
    {
    	Yii::import('application.commands.*');
    	$command = new CronCommand('test','test');
    	$command->actionDeliverStandBy( "fr_evamaria" , $_GET['numcron'] );
    	 
    }
    
    public function actionDisplayServer()
    {
    	echo $_SERVER['SERVER_NAME'];
    	$ConfDNS = \Business\Config::loadByKey( 'DNS' );
    	$_SERVER['SERVER_NAME']=$ConfDNS->value;
    	echo"<br/><br/><br/>";
    	echo $_SERVER['SERVER_NAME'];
    	
    	 
    }
    
    public function actiongetR4R2()
    {
    	$AB= new AnacondaBehavior();
    	$list=$AB->getR4R2List();
    	$AB->passInterCampaignR4R2($list);
    	if($list)
    	{
    		foreach($list as $row)
    		{
    			echo "<br/>".$row->id;
    		}
    		
    		
    		echo "<br/><br/>";
    		echo "<table border=2>
    				<th>id</th>
    				<th>User Email</th>
    				<th>Product Ref</th>
    				<th> Date Modifiable DE</th>";
    		
    		foreach($list as $row)
    		{
    			echo "<tr>";
    			echo "<td>".$row->id."</td>";
    			echo "<td>".$row->User->email."</td>";
    			echo "<td>".$row->SubCampaign->id."</td>";
    			echo "<td>".$row->modifiedShootDate."</td>";
    			echo "</tr>";
    		}
    		
    		echo "</table>";
    		
    	}
    	
    }
    
    
    public function actiongetOpeners()
    { 
    	$list=AnacondaBehavior::getOpenersList($_GET['shoot']);
    	echo "<br/><br/><br/>";
    	if(isset($list))
    	foreach($list as $key => $value)
    	{
    		echo "email : $key , subcampid : $value<br/>";
    	}
    	AnacondaBehavior::updateEMVDE($list,"../");
    }
    
    public function actionExecUpdate()
    {
    	Yii::import('application.commands.*');
    	$command = new CronCommand('test','test');
    	$command->actionUpdateOpenersListDE( "fr_evamaria",$_GET['shoot']);
    }
    
    public function actionSubdivision()
    {
		$AB =new AnacondaBehavior();
    	if($AB->Subdivision())
    	{
    		echo "Subdivison Done";
    	}
    	else 
    	{
    		echo "Subdivision Already Done";
    	}
    }
    
    public function actionGetFirstRows()
    {
    	$list=\Business\AnacondaSubdivision::loadFirstNbr($_GET['number']);
    	foreach($list as $row)
    	{
    		echo "<br/>".$row->id."==========> email :".$row->emailUser."==========> number of purchased :".$row->purchasedOldAnaconda."<br/>";
    	}
    }
    public function actionGetFirstRows1()
    {
    	$list=\Business\AnacondaSubdivision::loadFirstNbr((int)$_GET['number']);
    	foreach($list as $row)
    	{
    		echo "<br/>".$row->id."==========> email :".$row->emailUser."==========> number of purchased :".$row->purchasedOldAnaconda."<br/>";
    	}
    }
    
    public function actionGetPurchased()
    {
    	$subdivision_anaconda_client =\Yii::app()->params['subdivision_anaconda_client'];
    	$fileCLT=AnacondaBehavior::exportSegment($subdivision_anaconda_client, "SubdivisionCLIENT",'../');
    	$listCLT=AnacondaBehavior::fileToArray($fileCLT);
    	
    	foreach($listCLT as $mail)
    	{
    		// Recuperer le nombre d'achat dans des anciennes fids Anaconda
    		$PurchasedOldAnaconda=\Business\Invoice::getNbrPurshasedOldAnacondaByEmail($mail);
    		echo $mail."======>".$PurchasedOldAnaconda."<br/>";
    	}
    }
    
    public function actionBannUsers()
    {

    	
    	//Recuperation des leads inactifs
    	$listEmailsInactifs=\AnacondaBehavior::getInactiveUsers("../");
    	if($listEmailsInactifs)
    	{
    		//Pour chaque User affecter la valeur 1 comme raison de bann => Bann a cause de l'inactivite
	    	foreach($listEmailsInactifs as $email)
	    	{
	    		if($user = \Business\User::loadByEmail($email))
	    		{
	    			\AnacondaBehavior::setBannReasonByUser($user->email,1);
	    		}
	    	}
    	}

    	//Recuperation des leads qui ont atteint 10 SoftBouncesSuccessives
    	$listEmailsSB=\AnacondaBehavior::GetSBBannList();
    	
    	if($listEmailsSB)
    	{
    		// Pour chaque User affecter la valeur 2 comme raison de bann => Bann a cause des Soft Bounces successives
	    	foreach($listEmailsSB as $email)
	    	{
	    		if($user = \Business\User::loadByEmail($email))
	    		{
	    			echo $user->email;
	    			\AnacondaBehavior::setBannReasonByUser($user->email,2);
	    			$user->countSoftBounce=0;
	    			$user->save(); 
	    		}
	    	}
    	}
    }
    
    public function ActionExecBann()
    {
    	
    	Yii::import('application.commands.*');
    	$command = new CronCommand('test','test');
    	$command->actionBannAnacondaUsers( "fr_evamaria" );
    	 
    }
    public function actionSubdivisonAnaconda()
    {
   	
    	$list=\Business\AnacondaSubdivision::loadFirstNbr($_GET['number']);
    	foreach($list as $row)
    	{
    		echo "<br/>====================================<br/>";
    		echo "<br/>".$row->id."==========> email :".$row->emailUser."==========> number of purchased :".$row->purchasedOldAnaconda."<br/>";

    	}
    	$ab =  new \AnacondaBehavior();
    	$ab->SubdiviseByNumber($_GET['number']);
    }
    public function actionDSB()
    {
    	$list=\Business\CampaignHistory::loadStandByCampaigns($_GET['numcron']);
    	echo "----------------------<br/>";
    	echo "id|idUser.|idSubCampaign|behaviorHour|Status";
    	echo "----------------------<br/>";
    	foreach($list as $row)
    	{
    		echo "----------------------<br/>";
    		echo $row->id."|".$row->idUser."|".$row->idSubCampaign."|".$row->behaviorHour."|".$row->status."|";
    	}
    }
    
    
    public function ActionExecIndiceSTC()
    {
    	 
    	Yii::import('application.commands.*');
    	$command = new CronCommand('test','test');
    	$command->actionPurchasefAnacondaStc( "fr_evamaria" );
    
    }
    
    public function actionloadSBC()
    {
    	$list=\Business\CampaignHistory::loadStandByCampaigns($_GET['numcron']);
    	foreach ($list as $l)
    	{
    		echo $l->id."--".$l->behaviorHour."<br/>";
    	}
    }
    
    public function ActionExecPassage()
    {
    
    	
    	Yii::import('application.commands.*');
    	$command = new CronCommand('test','test');
    	$command->actionInterCampaignR4R2( "fr_evamaria" );
    
    }    
    
    public function actionReflationUser()
    {
    	$user = \Business\User::loadByEmail($_GET['emailUser']);
    	if($user)
    	{
    		$ch=new \Business\Reflationuser();
    		
    		$ch->indiceImplication=$user->indiceImplication;
    		$ch->isNewLead=0;
    		$ch->openerJ1=0;
    		$ch->notOpenerJ2=0;
    		$ch->buyerJ2=0;
    		$ch->idUser=$user->id;
    		$ch->idSubCampaignReflation=1570;
    		$ch->shootDate=date(\Yii::app()->params['dbDateTime']);
    		$ch->save();
    		echo "inserted";
    		echo $user->lastName;
    		echo "<br/>".$ch->shootDate;
    	}
    	else 
    	{
    		echo "not inserted";
    	}

    }   
    
    
    ///////////////////////////// Moteur de test ///////////////////////////////////////////////////////
    
    public function actionGetPreDeliveries()
    {
    	$date = $_GET['datedeliver'];
    	$list = \AnacondaBehavior::preDeliveriesByDate($date); 
    	$listFilteredByStep = \AnacondaBehavior::groupDeliveriesByStep($list);
  	
    	if(!\Business\Ecart::ExecutedEcartDeliveryByDate($date))
    	{
    		\AnacondaBehavior::mergeDeliveriesBySubcampaignAndStep($listFilteredByStep,$date);
    	}
    	
    	$list= \Business\EcartDelivery::loadNonUpdateDeliveries();
    	
	  	foreach($list as $ecartDeliv)
	  	{
	  		$datetime = explode(" ",$ecartDeliv->Ecart->creationDate);
	  		$date = $datetime[0];
	  		$ecarts = \AnacondaBehavior::deliveriesBySubcampDateStep($ecartDeliv->idSubCampaign,$date,$ecartDeliv->step);
	  		$ecartDeliv->delivered = $ecarts[0];
	  		$ecartDeliv->testDeliveries = $ecarts[1];
	  		$ecartDeliv->save();
	  		echo $ecartDeliv->Ecart->id;
	  		$theoDelivs = $ecartDeliv->buyerdJ + $ecartDeliv->buyerdJ1 + $ecartDeliv->buyerdJ2;
	  		$realDelivs = $ecartDeliv->delivered -	$ecartDeliv->testDeliveries;

	  		if(($realDelivs - $theoDelivs) != 0)
	  		{
	  			echo "<br/>".($realDelivs - $theoDelivs)."<br/>";
		  		$alert = new \Business\Alert();
		  		$alert->idEcart=$ecartDeliv->Ecart->id;
		  		$alert->idSubCampaign=$ecartDeliv->idSubCampaign;
		  		$alert->statut=0;
		  		$alert->creationDate=date( \Yii::app()->params['dbDateTime']);
		  		$alert->save();
	  		}
	  		
	  	}
	  	
	  	echo "done";
  	
    }
    
   static public function groupDeliveriesByStep($list)
   {
   	$ind=0;
   	foreach($list as $ch)
   	{
	   	$invoice = \Business\Invoice::loadByEmailAndProductPayed($ch->User->email,$ch->SubCampaign->Product->ref);
	   	
	   	if($invoice)
	   	{
	   		if($invoice->priceStep != 1503 && $invoice->priceStep != 1504)
	   		{
	   			$ch->step = $invoice->priceStep;
	   		}
	   		else
	   		{
	   			unset($list[$ind]);
	   		}
	   	}
	   	else
	   	{
	   		unset($list[$ind]);
	   	}
   	}
   	
   	return $list;
   	
   }
    
   public function actionGroupBy()
   {
	   	$list = \Business\CampaignHistory::loadGrouped();
	   	foreach($list as $ch)
	   	{
	   		$isint= $ch->SubCampaign->isInter() ? 1 : 0;
	   		echo $ch->id."====> j - ".$ch->provenance." =====> is INT : $isint =====> Price Step : ".$ch->step." =====>".$ch->deliveryDate." ===> ".$ch->behaviorHour."count => ".$ch->count."<br/>";
	   		echo "<br/>";
	   		
	   	}
	   	
	   	var_dump($list);
   }
   
   public function actionTest()
   {
   	echo "hiiiiiiiiii";
   	
   }
   
   public function actionResubdivise()
   {
   	$AB =new AnacondaBehavior();
   	if($AB->ReSubdivise())
   	{
   		echo "Subdivison Done";
   	}
   	else
   	{
   		echo "Subdivision Already Done";
   	}
   }
   
   public function actionUpdateSegment()
   {
	   	// appel de la classe api
	   	Yii::import ( 'ext.Class_API', true );
	   	
	   	$porteurMapp = Yii::app ()->params ['porteur'];
	   	\Controller::loadConfigForPorteur ( $porteurMapp );
	   	
	   	// rï¿½cupï¿½ration des parametres de l'API
	   	$mkt_wdsl = \Yii::app ()->params ['CMD_EMV_ACQ'] ['wdsl'];
	   	$mkt_login = \Yii::app ()->params ['CMD_EMV_ACQ'] ['login'];
	   	$mkt_pwd = \Yii::app ()->params ['CMD_EMV_ACQ'] ['pwd'];
	   	$mkt_key = \Yii::app ()->params ['CMD_EMV_ACQ'] ['key'];
	   	
	   	$class_api = new Class_API ( $mkt_wdsl, $mkt_login, $mkt_pwd, $mkt_key );
	   	
	   	/////////////////////////////////////////////////// Dates
	   	
	   	//J
	   	$dateToday = new Datetime ( date ( 'd-m-Y' ) );
	   	
	   	$dateJ = $dateToday->format('Y-m-d')."T00:00:00";
	   	
	   	// J - 8 mois
	   	$dateSub8Months = new Datetime ( date ( 'd-m-Y' ) );
	   	$dateSub8Months = $dateSub8Months->sub ( new DateInterval ( 'P8M' ) );
	   	
	   	$date8= $dateSub8Months->format('Y-m-d')."T00:00:00";
	   	// J - 4 mois
	   	$dateSub4Months = new Datetime ( date ( 'd-m-Y' ) );
	   	$dateSub4Months = $dateSub4Months->sub ( new DateInterval ( 'P4M' ) );
	   	
	   	
	   	$date4 = $dateSub4Months->format('Y-m-d')."T00:00:00";
	   	
	   	
	   	/////////////////////////////////////////////////// token de connexion
	   	$token = $class_api->connexion ();
	   	
	   	////////////////////////////////////////////////////// Clients //////////////////////////////////////
	   	$id_segment = \Yii::app()->params['subdivision_clients'];
	   	$operator = "ISBETWEEN_STATIC";
	   	$first_value = $date8;
	   	$second_value = $dateJ;
	   	$groupNumber = 1;
	   	
	   	//Segment Dernier Click
	   	$column_name = "LAST_DATE_CLICK";
	   	$orderFrag= 0;	   	
	   	$class_api->update_segment_criteria_recent($token,$id_segment,$column_name,$operator,$first_value,$second_value,$groupNumber,$orderFrag);
	   	
	   	// Segment Derniere ouverture
	   	$column_name = "LAST_DATE_OPEN";
	   	$orderFrag= 1 ;	   	 
	   	$class_api->update_segment_criteria_recent($token,$id_segment,$column_name,$operator,$first_value,$second_value,$groupNumber,$orderFrag);	   	
	   	
	   	//////////////////////////////////////////////// Leads Doubles //////////////////////////////////////////////////////////////////////
	   	$id_segment = \Yii::app()->params['subdivision_leads_doubles'];
	   	$operator = "ISBETWEEN_STATIC";
	   	$first_value = $date8;
	   	$second_value = $date4;
	   	$groupNumber = 1;
	   	
	   	// Dernier Clic
	   	$column_name = "LAST_DATE_CLICK";
	   	$orderFrag= 0 ;	 
	   	$class_api->update_segment_criteria_recent($token,$id_segment,$column_name,$operator,$first_value,$second_value,$groupNumber,$orderFrag);
	   	
	   	//Derniere Ouverture
	   	$column_name = "LAST_DATE_OPEN";
	   	$orderFrag= 1 ;
	   	$class_api->update_segment_criteria_recent($token,$id_segment,$column_name,$operator,$first_value,$second_value,$groupNumber,$orderFrag);	   	
	   	
	   	// fermer la connexion de l'API
	   	$class_api->closeConnection ( $token );
   	
   }
    //////////////////////////////////////////////////////////////////////////////////////////////////

   
   
   
   public function actionGetTag()
   {
   		echo \Business\AnacondaSubdivision::loadTagByEmail($_GET['email']);
   }
    
  public function actionGetEcartDelivery()
  {
  		$ed = \Business\EcartDelivery::load(1);
  		echo $ed->idSubCampaign. " ======> ".$ed->idEcart."<br/>";
  		
  }
  
  public function actionAddDelivery()
  {
  	$ch=new \Business\CampaignHistory();
  	$ch->status = -4 ; 
  	$ch->save();
  	
//   	$ed = new \EcartDelivery();
//   	$ed->idSubCampaign=424;
//   	$ed->step=5;
//   	$ed->fidPosition=0;
//   	echo $ed->step;

  }
   
  public function actionEcart()
  {
  	$ch=new \Business\Ecart();
  	$ch->type = 2 ;
  	$ch->creationDate = '2017-03-22 00:00:00' ;
  	$ch->save();
  	echo $ch->id;

  }
  
  public function actionGetDeliveryMerge()
  {
  	//\Business\EcartDelivery::loadBySubcampaignAndStepAndDate($idSubCampaign,$step,$date);
  	$data = CJSON::encode(\Business\EcartDelivery::loadAll());
  	echo $data;
  }
  
  public function actionSetSTB()
  {
  	echo \AnacondaBehavior::SetReflationStbByCampaignHistory($_GET['scr'], $_GET['ch']);
  }
  
  public function actionSetQuarantaine()
  {
  	\AnacondaBehavior::setQuarantaine("../");
  }
  
  public function actionGetSTB()
  {
  	$list = \Business\CampaignHistory::getInStbByReflationAndDate($_GET['scr'],$_GET['date']);
  	foreach($list as $r)
  	{
  		echo $r->id."<br/>";
  	}
  }
  
  public function actionGetSubcampaignRefs()
  {
  	\AnacondaBehavior::deliveriesBySubcampDateStep($_GET['scr'],$_GET['date'],$_GET['step']);

  }
  
  public function actionGetdeliveriesBySubcampRefDateStep()
  {
  		$idSub=$_GET['idsubcamp'];
	  	$countAll=0;
	  	$countTest=0;
	  
	  
	  
	  	// Recuperer la subcampaign
	  	$subcamp=\Business\SubCampaign::load($idSub);
	  
	  	// Recuperer la subcampaignreflation
	  	if($subcamp->isAsile())
	  	{
	  		echo "Asile<br/>";
	  		$subcampCT=\Business\SubCampaign::loadByCampaignAndPosition( $subcamp->idCampaign, 2 );
	  		$scr = \Business\SubCampaignReflation::loadByCampStep($subcampCT->id,1);
	  	}
	  	else
	  	{
	  		echo "Pas Asile<br/>";
	  		$scr = \Business\SubCampaignReflation::loadByCampStep($subcamp->id,111);
	  	}
	  
	  	echo $scr->id;
  }
  
  public function actionloadNonUpdateDeliveries()
  {
  	$list= \Business\EcartDelivery::loadNonUpdateDeliveries();
  	foreach($list as $r)
  	{
  		echo $r->id."<br/>";
  	}
  }
  
  public function loadEcartDeliveriesByPeriod()
  {
  	$data = \Business\EcartDelivery::loadByPeriod($_GET['from'],$_GET['to']);
  	var_dump($data);

  }
  public function actionGetDelivsByPeriodSubcamp()
  {
  	$data = \Business\EcartDelivery::loadEcartDeliveriesByPeriodCampaignProduct($_GET['from'],$_GET['to'],$_GET['idCamp'],$_GET['position']);
  	echo $data;
  }
  
  public function ActionExecDeliveriesEcartCron()
  {
  	Yii::import('application.commands.*');
  	$command = new CronCommand('test','test');
  	$command->actionEcartDeliveriesAnaconda( "fr_evamaria" );  	
  }


public function actionManageHistory()
{
	\AnacondaBehavior::manageHistory($_GET['idAlert'],"teeeeeeeeext");
	
}
	
}

?>
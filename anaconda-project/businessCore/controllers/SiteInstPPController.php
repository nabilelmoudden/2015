<?php
\Yii::import('ext.CurlHelper');
/**
 * Description of SiteInstPPController
 *
 * @author Youssef HARRATI
 * @package Controllers
 */
class SiteInstPPController extends Controller
{
	/**
	 * Contexte
	 * @var \Business\ContextSite
	 */
	protected $Context	= false;

	/**
	 * Initialise le controller generique des site
	 * Instancie l'Objet Context
	 * @throws EsoterException	Si l'instanciation du Context a posé probleme
	 */
	public function init(){
		// Choix du porteur :
		if( Yii::app()->request->getParam('p') !== NULL )
		{Yii::app()->session['porteur'] = Yii::app()->request->getParam('p');}
		else if( empty(Yii::app()->session['porteur']) )
		{Yii::app()->session['porteur'] = Yii::app()->params['porteur'];}
		
		parent::init();
		$this->pageTitle = 'Home page';
		

		// Defini le dossier dans lequel sont les vues :
		\Yii::app()->setViewPath( $this->portViewDir(true) );

		// Defini la langue :
		\Yii::app()->setLanguage( \Yii::app()->params['lang'] );
		// Defini le dossier contenant les traductions : :
		\Yii::app()->messages->basePath = $this->portViewDir(true).'messages';

		// Insertion de JQuery :
		$this->includeJQuerySCript( true );

		// Layout du porteur :
		

		// Chargement du context :
		$this->Context = new \Business\ContextSite();
		
	}

	/*
	 * Action generique pour l'affichage d'une LDV : /site/ldv?m=test@test.com&...
	 */
	public function actionIndex(){
		
		// Log l'action courante :
		$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_OPEN ) ) );
		
		
		$site = "";
		if(Yii::app()->request->getParam( 'site' ) !== NULL)
		{$site = "_".Yii::app()->request->getParam( 'site' );}

		$porteur			= \Yii::app()->params['porteur'];
		$viewDir = $this->portViewDir.'/'.$porteur.'/siteInstPP/';
		
		$ref = "";
		$email = "";
		if(Yii::app()->request->getParam( 'm' ) !== NULL && filter_var(Yii::app()->request->getParam( 'm' ), FILTER_VALIDATE_EMAIL)){
			$email = Yii::app()->request->getParam( 'm' );
		}
		if(Yii::app()->request->getParam( 'ref' ) !== NULL){
			$ref = Yii::app()->request->getParam( 'ref' );
		}
		
		// Rendu de la page :
		$this->render( '//'.$porteur.'/siteInstPP/index'.$site, array('porteur' => $porteur, 'viewDir' => $viewDir, 'email' => $email, 'ref' => $ref, 'baseDir' => \Yii::app()->baseUrl) );
	}
	
	public function actionTestimonial(){
		
		// Log l'action courante :
		$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_OPEN ) ) );
		
		
		$site = "";
		if(Yii::app()->request->getParam( 'site' ) !== NULL)
		{$site = "_".Yii::app()->request->getParam( 'site' );}

		$porteur			= \Yii::app()->params['porteur'];
		$viewDir = $this->portViewDir.'/'.$porteur.'/siteInstPP/';
		
		$ref = "";
		$email = "";
		if(Yii::app()->request->getParam( 'm' ) !== NULL && filter_var(Yii::app()->request->getParam( 'm' ), FILTER_VALIDATE_EMAIL)){
			$email = Yii::app()->request->getParam( 'm' );
		}
		if(Yii::app()->request->getParam( 'ref' ) !== NULL){
			$ref = Yii::app()->request->getParam( 'ref' );
		}
		
		// Rendu de la page :
		$this->render( '//'.$porteur.'/siteInstPP/testimonial'.$site, array('porteur' => $porteur, 'viewDir' => $viewDir, 'email' => $email, 'ref' => $ref, 'baseDir' => \Yii::app()->baseUrl) );
	}
	
	public function actionTerms(){
		
		// Log l'action courante :
		$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_OPEN ) ) );
		
		
		$site = "";
		if(Yii::app()->request->getParam( 'site' ) !== NULL)
		{$site = "_".Yii::app()->request->getParam( 'site' );}

		$porteur			= \Yii::app()->params['porteur'];
		$viewDir = $this->portViewDir.'/'.$porteur.'/siteInstPP/';
		
		$ref = "";
		$email = "";
		if(Yii::app()->request->getParam( 'm' ) !== NULL && filter_var(Yii::app()->request->getParam( 'm' ), FILTER_VALIDATE_EMAIL)){
			$email = Yii::app()->request->getParam( 'm' );
		}
		if(Yii::app()->request->getParam( 'ref' ) !== NULL){
			$ref = Yii::app()->request->getParam( 'ref' );
		}
		
		// Rendu de la page :
		$this->render( '//'.$porteur.'/siteInstPP/terms'.$site, array('porteur' => $porteur, 'viewDir' => $viewDir, 'email' => $email, 'ref' => $ref, 'baseDir' => \Yii::app()->baseUrl) );
	}
	
	public function actionFAQ(){
		
		// Log l'action courante :
		$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_OPEN ) ) );
		
		
		$site = "";
		if(Yii::app()->request->getParam( 'site' ) !== NULL)
		{$site = "_".Yii::app()->request->getParam( 'site' );}
		
		$sections = [];
		$Section = \Section::model()->findAll();
		$Question = \Question::model()->findAll();
		foreach($Section AS $s){
			$questions = [];
			foreach($Question AS $q){
				if($q->id_sec_FK == $s->id_section){
					$questions[trim($q->titre)] = $q->description;
				}
			}
			$sections[trim($s->section)] = $questions;
		}
		
		$messconf = "";
		$email = "";
		$objet = "";
		$message = "";
		$ref = "";
		
		$m = true;
		$t = true;
		if(isset($_GET[ 'm' ]) && filter_var($_GET[ 'm' ], FILTER_VALIDATE_EMAIL)){
			$m = false;
			$email = $_GET[ 'm' ];
		}
		
		if(isset($_GET[ 'titre' ])){
			$t = false;
			$objet = $_GET[ 'titre' ];
		}
		
		if(isset($_GET[ 'ref' ])){
			$ref = $_GET[ 'ref' ];
		}
		
		if(isset($_GET[ 'desabonner' ])){
			
			$User = \Business\User::model();
			$User = $User->findByAttributes(array('email'=>$email));
			if($User){
				$User->visibleDesinscrire = 1;
				$User->save();
			}
			$ch = curl_init($_GET[ 'urlEMV' ]);
			curl_exec($ch);
			curl_close($ch);
		}
		
		if(Yii::app()->request->getParam( 'email' ) !== NULL){
			if(Yii::app()->request->getParam( 'email' ) == ""){
				$messconf = "txtEmailEmpty";
			}else if(!filter_var(Yii::app()->request->getParam( 'email' ), FILTER_VALIDATE_EMAIL)){
				$messconf = "txtEmailInvalid";
			}else if(Yii::app()->request->getParam( 'objet' ) == ""){
				$messconf = "txtObjEmpty";
			}else if(Yii::app()->request->getParam( 'message' ) == ""){
				$messconf = "txtMsgEmpty";
			}else{
				$messconf = "txtFaqOK";
				$email = Yii::app()->request->getParam( 'email' );
				$objet = Yii::app()->request->getParam( 'objet' );
				$message = Yii::app()->request->getParam( 'message' );
				
				$User = \Business\User::model();
				$User = $User->findByAttributes(array('email'=>$email));
				$typeClient = "prospect";
				if($User){
					$typeClient = "client";
				}
				
				$Product = \Business\Product::model();
				$Product = $Product->findByAttributes(array('ref'=>$ref));
				$description = "";
				if($Product){
					$description = $Product->description;
				}
				
				$params = [
					'porteur' => Yii::app()->request->getParam( 'porteur' ),
					'email' => $email,
					'objet' => $objet,
					'message' => $message,
					'reference' => $ref,
					'description' => $description,
					'typeClient' => $typeClient,
				];
				
				
				
				
				$ch = curl_init('http://otrsws.new-divin.com/ins_ticket.php');
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

				$response = curl_exec($ch);
				curl_close($ch);
				
				
			}
		}
		
		$porteur			= \Yii::app()->params['porteur'];
		$viewDir = $this->portViewDir.'/'.$porteur.'/siteInstPP/';
		// Rendu de la page :
		$this->render( '//'.$porteur.'/siteInstPP/faq'.$site, 
			array(
				'porteur' => $porteur, 'messconf' => Yii::t( 'siteInst', $messconf ), 'm' => $m, 't' => $t, 'ref' => $ref, 
				'email' => $email, 'objet' => $objet, 'message' => $message, 
				'content' => $sections, 'viewDir' => $viewDir, 
				'baseDir' => \Yii::app()->baseUrl
			)
		);
	}
	
	public function actionStore(){
		
		// Log l'action courante :
		$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_OPEN ) ) );
		
		
		$site = "";
		if(Yii::app()->request->getParam( 'site' ) !== NULL)
		{$site = "_".Yii::app()->request->getParam( 'site' );}

		$porteur			= \Yii::app()->params['porteur'];
		$viewDir = $this->portViewDir.'/'.$porteur.'/siteInstPP/';
		
		$ref = "";
		$email = "";
		if(Yii::app()->request->getParam( 'm' ) !== NULL && filter_var(Yii::app()->request->getParam( 'm' ), FILTER_VALIDATE_EMAIL)){
			$email = Yii::app()->request->getParam( 'm' );
		}
		if(Yii::app()->request->getParam( 'ref' ) !== NULL){
			$ref = Yii::app()->request->getParam( 'ref' );
		}
		
		// Rendu de la page :
		$this->render( '//'.$porteur.'/siteInstPP/store'.$site, array('porteur' => $porteur, 'viewDir' => $viewDir, 'email' => $email, 'ref' => $ref, 'baseDir' => \Yii::app()->baseUrl) );
	}
	
	public function actionForm(){
		
		// Log l'action courante :
		$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_OPEN ) ) );
		
		$site = "";
		$siteCode = substr(Yii::app()->session['porteur'],0,2);
		if(Yii::app()->request->getParam( 'site' ) !== NULL){
			$site = "_".Yii::app()->request->getParam( 'site' );
			$siteCode = Yii::app()->request->getParam( 'site' );
		}
		
		$emvParameterArray = array("FIRSTNAME", "LASTNAME", "EMAIL", "EMVCELLPHONE", "TITLE", "DATEOFBIRTH", "SEGMENT", "SOURCE", "OPTIN", "SITE",
					  "EMVADMIN1", "EMVADMIN2", "EMVADMIN3", "EMVADMIN4", "EMVADMIN5", "EMVADMIN6", "EMVADMIN7", "EMVADMIN8", "EMVADMIN9",
		"EMVADMIN10", "EMVADMIN11", "EMVADMIN12", "EMVADMIN13", "EMVADMIN14", "EMVADMIN15", "EMVADMIN16", "EMVADMIN17", "EMVADMIN18", "EMVADMIN19",
		"EMVADMIN20", "EMVADMIN21", "EMVADMIN22", "EMVADMIN23", "EMVADMIN24", "EMVADMIN25", "EMVADMIN26", "EMVADMIN27", "EMVADMIN28", "EMVADMIN29",
		"EMVADMIN30", "EMVADMIN31", "EMVADMIN32", "EMVADMIN33", "EMVADMIN34", "EMVADMIN35", "EMVADMIN36", "EMVADMIN37", "EMVADMIN38", "EMVADMIN39",
		"EMVADMIN40", "EMVADMIN41", "EMVADMIN42", "EMVADMIN43", "EMVADMIN44", "EMVADMIN45", "EMVADMIN46", "EMVADMIN47", "EMVADMIN48", "EMVADMIN49",
		"EMVADMIN50", "EMVADMIN51", "EMVADMIN52", "EMVADMIN53", "EMVADMIN54", "EMVADMIN55", "EMVADMIN56", "EMVADMIN57", "EMVADMIN58", "EMVADMIN59",
		"EMVADMIN60", "EMVADMIN61", "EMVADMIN62", "EMVADMIN63", "EMVADMIN64", "EMVADMIN65", "EMVADMIN66", "EMVADMIN67", "EMVADMIN68", "EMVADMIN69",
		"EMVADMIN73", "EMVADMIN85", "EMVADMIN86", "EMVADMIN89", "ACTIF_SUR_CE_COMPTE", "TRANSFERT" );
		
		if(Yii::app()->request->getParam( 'form_validate' ) !== NULL){
			
			
			$firstname 	= (isset($_POST['FIRSTNAME']) ? $_POST['FIRSTNAME'] : "");
			$lastname 	= (isset($_POST['LASTNAME']) ? $_POST['LASTNAME'] : "");
			if (isset($_POST['EMVURL'])) {
				$_POST['FIRSTNAME'] = urlencode($_POST['FIRSTNAME']);
				$_POST['LASTNAME'] = urlencode($_POST['LASTNAME']);

				if( isset($_POST['SOURCE']) && strlen($_POST['SOURCE']) > 24 )
				{$_POST['SOURCE'] = substr ( $_POST['SOURCE'], 0, 24 );}
				
				$parameterToAdd = "";
				foreach($emvParameterArray as $parameter) {
					if (isset($_POST[$parameter]) && !empty($_POST[$parameter])) {
						if (strlen($parameterToAdd) != 0) {$parameterToAdd .= '&';}

						
						$parameterToAdd .= $parameter .'_FIELD=' .($_POST[$parameter]);
					}
				}
				$emvURL = $_POST['EMVURL'] .(strpos($_POST['EMVURL'], '&') > 0 ? '&' : '?') .$parameterToAdd;
				$emvURL = $emvURL .(strpos($emvURL, '&') > 0 ? '&' : '?') .'DATEUNJOIN_FIELD=';
			} else {
				$emvURL = "";
			}
			$Curl = new CurlHelper();
			
			$Site = new \Business\Site();
			$Site = $Site->findByAttributes(array('code'=>$siteCode));
			
			$User = \Business\User::model();
			$User = $User->findByAttributes(array('email'=>$_POST['EMAIL']));
			
			
			$double = false;
			if($User){
				$date1=date_create($User->creationDate);
				$date2=date_create(date("Y-m-d"));
				$diff=date_diff($date1,$date2);
				$double = ($diff->days/30)>3?true:false;
				
				if($double){
					$Leadaff = new \Business\Leadaffiliateplatform();
					$Leadaff->idUser = $User->id;
					$Leadaff->idSite = $Site->id;
					$Leadaff->creationDate = date("Y-m-d");
					$Leadaff->isDouble = 1;
					$Leadaff->save();
					
					
					// LeadAffiliatePlatform V1
					$LeadaffV1 = new \Business\Leadaff();
					$LeadaffV1->idInternaute = $UserV1->id;
					$LeadaffV1->creationDate = date("Y-m-d");
					$LeadaffV1->isDouble = 0;
					if(!$LeadaffV1->save()){
						
						
					}
					
					
					$c = new CDbCriteria();
					$c->addSearchCondition('ref', 'voygratuit');
					$Product = \Business\Product::model()->find($c);
					
					if($Product){
						$Invoice = new \Business\Invoice();
						$Invoice->emailUser = $User->email;
						$Invoice->creationDate = date("Y-m-d");
						$Invoice->invoiceStatus = 1;
						$Invoice->currency = $Site->codeDevise;
						$Invoice->codeSite = $Site->code;
						$Invoice->save();
						
						$RecordInvoice = new \Business\RecordInvoice();
						$RecordInvoice->idInvoice = $Invoice->id;
						$RecordInvoice->refProduct = $Product->ref;
						$RecordInvoice->priceATI = $Product->priceATI;
						$RecordInvoice->priceVAT = $Product->priceVAT;
						$RecordInvoice->save();
					}
				}
			}
			else{
				$User = new \Business\User();
				
				$User->civility = $_POST['TITLE']==1?"M":($_POST['TITLE']==2?"Md":"Mlle");
				$User->firstName = $firstname;
				$User->lastName = $lastname;
				$User->birthday = $_POST["YEAR_BIRTHDAY"]."-" .$_POST["MONTH_BIRTHDAY"]."-" .$_POST["DAY_BIRTHDAY"];
				$User->email = $_POST['EMAIL'];
				$User->creationDate = date("Y-m-d");
				$User->optinPartner = $_POST['OPTINPARTNER']="on"?1:0;
				$User->compteEMVactif = $GLOBALS['porteurCompteEMVactif'][Yii::app()->session['porteur']];
				$User->optin = 0;
				if(!$User->save()){
					
				}
				
				$return = $Curl->sendRequest($emvURL);
				
				//Insertion dans Internaute
				$UserV1 = new \Business\User_V1();
				
				$UserV1->Civility = $_POST['TITLE']==1?"M":($_POST['TITLE']==2?"Md":"Mlle");
				$UserV1->Firstname = $firstname;
				$UserV1->Lastname = $lastname;
				$UserV1->Birthday = $_POST["YEAR_BIRTHDAY"]."-" .$_POST["MONTH_BIRTHDAY"]."-" .$_POST["DAY_BIRTHDAY"];
				$UserV1->Email = $_POST['EMAIL'];
				$UserV1->CreationDate = date("Y-m-d");
				$UserV1->OptinPartner = $_POST['OPTINPARTNER']="on"?1:0;
				$UserV1->CompteEMVactif = $GLOBALS['porteurCompteEMVactif'][Yii::app()->session['porteur']];
				$UserV1->Optin = 0;
				if(!$UserV1->save()){
					
				}
				
				
				$Leadaff = new \Business\LeadAffiliatePlatform();
				$Leadaff->idUser = $User->id;
				$Leadaff->idSite = $Site->id;
				$Leadaff->creationDate = date("Y-m-d");
				$Leadaff->isDouble = 0;
				if(!$Leadaff->save()){
					
				}
				
				// LeadAffiliatePlatform V1
				$LeadaffV1 = new \Business\Leadaff();
				$LeadaffV1->idInternaute = $UserV1->id;
				$LeadaffV1->creationDate = date("Y-m-d");
				$LeadaffV1->isDouble = 0;
				if(!$LeadaffV1->save()){
					
				}
				
				
				$c = new CDbCriteria();
				$c->addSearchCondition('ref', 'voygratuit');
				$Product = \Business\Product::model()->find($c);
				
				if($Product){
					$Invoice = new \Business\Invoice();
					$Invoice->emailUser = $User->email;
					$Invoice->creationDate = date("Y-m-d");
					$Invoice->invoiceStatus = 1;
					$Invoice->currency = $Site->codeDevise;
					$Invoice->codeSite = $Site->code;
					if(!$Invoice->save()){
						
					}
					
					$RecordInvoice = new \Business\RecordInvoice();
					$RecordInvoice->idInvoice = $Invoice->id;
					$RecordInvoice->refProduct = $Product->ref;
					$RecordInvoice->priceATI = $Product->priceATI;
					$RecordInvoice->priceVAT = $Product->priceVAT;
					if(!$RecordInvoice->save()){
						
					}
				}
			}
			$this->redirect(array('siteInstPP/thanks'));
		}

		$porteur			= \Yii::app()->params['porteur'];
		$viewDir = $this->portViewDir.'/'.$porteur.'/siteInstPP/';
		
		$ref = "";
		$email = "";
		if(Yii::app()->request->getParam( 'm' ) !== NULL && filter_var(Yii::app()->request->getParam( 'm' ), FILTER_VALIDATE_EMAIL)){
			$email = Yii::app()->request->getParam( 'm' );
		}
		if(Yii::app()->request->getParam( 'ref' ) !== NULL){
			$ref = Yii::app()->request->getParam( 'ref' );
		}
		
		// Rendu de la page :
		$this->render( '//'.$porteur.'/siteInstPP/form'.$site, array('porteur' => $porteur, 'viewDir' => $viewDir, 'email' => $email, 'ref' => $ref, 'baseDir' => \Yii::app()->baseUrl) );
	}
	
	public function actionPrivacy(){
		
		// Log l'action courante :
		$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_OPEN ) ) );
		
		
		$site = "";
		if(Yii::app()->request->getParam( 'site' ) !== NULL)
			$site = "_".Yii::app()->request->getParam( 'site' );

		$porteur			= \Yii::app()->params['porteur'];
		$viewDir = $this->portViewDir.'/'.$porteur.'/siteInstPP/';
		
		$ref = "";
		$email = "";
		if(Yii::app()->request->getParam( 'm' ) !== NULL && filter_var(Yii::app()->request->getParam( 'm' ), FILTER_VALIDATE_EMAIL)){
			$email = Yii::app()->request->getParam( 'm' );
		}
		if(Yii::app()->request->getParam( 'ref' ) !== NULL){
			$ref = Yii::app()->request->getParam( 'ref' );
		}
		
		// Rendu de la page :
		$this->render( '//'.$porteur.'/siteInstPP/privacy'.$site, array('porteur' => $porteur, 'viewDir' => $viewDir, 'email' => $email, 'ref' => $ref, 'baseDir' => \Yii::app()->baseUrl) );
	}
	
	public function actionConsultation(){
		
		// Log l'action courante :
		$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_OPEN ) ) );
		
		
		$site = "";
		if(Yii::app()->request->getParam( 'site' ) !== NULL)
		{$site = "_".Yii::app()->request->getParam( 'site' );}

		$porteur			= \Yii::app()->params['porteur'];
		$viewDir = $this->portViewDir.'/'.$porteur.'/siteInstPP/';
		
		$ref = "";
		$email = "";
		if(Yii::app()->request->getParam( 'm' ) !== NULL && filter_var(Yii::app()->request->getParam( 'm' ), FILTER_VALIDATE_EMAIL)){
			$email = Yii::app()->request->getParam( 'm' );
		}
		if(Yii::app()->request->getParam( 'ref' ) !== NULL){
			$ref = Yii::app()->request->getParam( 'ref' );
		}
		
		// Rendu de la page :
		$this->render( '//'.$porteur.'/siteInstPP/consultation'.$site, array('porteur' => $porteur, 'viewDir' => $viewDir, 'email' => $email, 'ref' => $ref, 'baseDir' => \Yii::app()->baseUrl) );
	}
	
	public function actionThanks(){
		// Log l'action courante :
		$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_OPEN ) ) );
		
		
		$site = "";
		if(Yii::app()->request->getParam( 'site' ) !== NULL)
		{$site = "_".Yii::app()->request->getParam( 'site' );}

		$porteur			= \Yii::app()->params['porteur'];
		$viewDir = $this->portViewDir.'/'.$porteur.'/siteInstPP/';
		
		$ref = "";
		$email = "";
		if(Yii::app()->request->getParam( 'm' ) !== NULL && filter_var(Yii::app()->request->getParam( 'm' ), FILTER_VALIDATE_EMAIL)){
			$email = Yii::app()->request->getParam( 'm' );
		}
		if(Yii::app()->request->getParam( 'ref' ) !== NULL){
			$ref = Yii::app()->request->getParam( 'ref' );
		}
		
		// Rendu de la page :
		$this->render( '//'.$porteur.'/siteInstPP/thanks'.$site, array('porteur' => $porteur, 'viewDir' => $viewDir, 'email' => $email, 'ref' => $ref, 'baseDir' => \Yii::app()->baseUrl) );
	}
}

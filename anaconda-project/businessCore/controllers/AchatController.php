<?php



/**
 * Description of SiteController
 *
 * @author Chekir Brahim
 * @package Controllers
 */
\Yii::import( 'ext.MailHelper' );
class AchatController extends AdminController {

    /**
     * Initialise le controller generique des site
     * Instancie l'Objet Context
     * @throws EsoterException	Si l'instanciation du Context a posé probleme
     */
	
	public $layout	= '//achat/menu';
    public function init(){
		parent::init();
		$action = Yii::app()->getUrlManager()->parseUrl(Yii::app()->getRequest());
		if( $action=='Achat/login' || $action=='Achat/index' ||  $action == 'Achat/liste' || $action == 'Achat/cmd' || $action == 'Achat/accueil'){
			// Url de la page de login ( pour les redirections faites par les Rules ) :
			
			Yii::app()->user->loginUrl = array( '/Achat/login' );
		}
		$this->setPageTitle( 'Achat Administration' );
		}
    
    public function filters(){
    	return array( 'accessControl' );
    }
    
	public function accessRules(){
		return array(
			array(
				'allow',
				'users' => array('@'),
				'roles' => array( 'ADMIN_ACHAT'  )
			),
			array(
				'allow',
				'actions' => array( 'login', 'logout' ),
				'users' => array('*')
			),
			array('allow'),
		);
    }
    
    public function actionIndex() {
    	$user = \Business\User::load( Yii::app()->user->getId() );
		!$user  ? $this->redirect('login') : $this->redirect('liste');
    }
    

    
    
public function actionUpdateCommande() {
	
    	\Controller::loadConfigForPorteur('rinalda');

    	$id = Yii::app()->request->getParam( 'id' );
		$Achat = new Business\Achat();
		$Achat = \Achat::model()->findByPk($id);


		if(Yii::app()->request->getParam('Achat'))
		{

			$achat = Yii::app()->request->getParam('Achat');
			
			$Achat->designation = $achat['designation'];
			$Achat->date_livraison = $achat['date_livraison'];
	        $Achat->service_demandeur     = $achat['service_demandeur'];
	        $Achat->service_acheteur     = $achat['service_acheteur'];

		
			if ($Achat->save()) {
		          	Yii::app()->user->setFlash( "success", Yii::t( 'common', 'updateOK' ) );

		          	
				}else
				{
					Yii::app()->user->setFlash( "error", Yii::t( 'common', 'updateNOK' ) );
                }			    	   	
	    }	

    	
    	$this->renderPartial( '//achat/updateCommande', array( 'fac' => $Achat));
    	
    }


    public function actionCmd() {


    	\Yii::app()->clientScript->registerScriptFile( Yii::app()->baseUrl.'/businessCore/views/ap/js/campaign.js' );
		\Controller::loadConfigForPorteur('rinalda');

		if(Yii::app()->request->getParam('id'))
		{
    	$id = Yii::app()->request->getParam( 'id' );
		$achat = new Business\Achat();
		$achat = \Achat::model()->findByPk($id);

		$user = \Business\User::load( Yii::app()->user->getId() );
		!$user  ? $this->redirect('../login') : $this->render( '//achat/cmd', array('fac'=>$achat, 'baseUrl'=> Yii::app()->baseUrl)); 	
		
    	
    	}
		
    }
    
    
public function actionAjoutArticle() {
	
    	\Controller::loadConfigForPorteur('rinalda');
    	   	
    	
    	$this->renderPartial( '//achat/AjoutArticle' );
    		
    	
    	
    	
    }
    
public function actionUpdateArticle() {
	
    	\Controller::loadConfigForPorteur('rinalda');
    	   	
    	
    	$this->renderPartial( '//achat/updateArticle' );
    		
    	
    	
    	   
    }
    
public function actionSubListeArticle() {
	
    	\Controller::loadConfigForPorteur('rinalda');
    	   	
    	
    	$this->renderPartial( '//achat/subListeArticle' );
    		
    	
    	
    	
    }
    
public function actionAjoutFournisseur() {
	
    	\Controller::loadConfigForPorteur('rinalda');
    	   	
    	
    	$this->renderPartial( '//achat/AjoutFournisseur' );
    		
    	
    	
    	
    }
    
public function actionUpdateFournisseur() {
	
    	\Controller::loadConfigForPorteur('rinalda');
    	   	
    	
    	$this->renderPartial( '//achat/updateFournisseur' );
    		
    	
    	
    	   
    }
    
public function actionSubListeFournisseur() {
	
    	\Controller::loadConfigForPorteur('rinalda');
    	   	
    	
    	$this->renderPartial( '//achat/subListeFournisseur' );
    		
    	
    	
    	
    }

    public function actionAjoutCommande() {
	
	
    	\Controller::loadConfigForPorteur('rinalda');

          $Achat = new Business\Achat();


         

         
         
         
       
         
         
         
         
         
         
       
       

		if(Yii::app()->request->getParam('Business\Achat')){

			$achat = Yii::app()->request->getParam('Business\Achat');
	    
	        $Achat->designation = $achat['designation'];
	        $Achat->date_livraison = $achat['date_livraison'];
	        $Achat->service_demandeur     = $achat['service_demandeur'];
	        $Achat->service_acheteur     = $achat['service_acheteur'];

	        $Achat->date     = gmdate("Y-m-d H:i:s");
	        $Achat->statut     = 'Brouillon';
	        $Achat->color     = 'Brouillon';
	        $pdf_file = TMP_DIR . '/Achat/Achat_'.$Achat->designation.'_'.$Achat->date.'.pdf';
	        $Achat->pdf=$pdf_file;


	        if ($Achat->save()) {
	          	Yii::app()->user->setFlash( "success", Yii::t( 'common', 'create' ) );
	          	
			}else
			{Yii::app()->user->setFlash( "error", Yii::t( 'common', 'erreur lors de la création' ) );}
		  		
	  	}

  		 $this->renderPartial( '//achat/AjoutCommande', array( 'fac' => $Achat) );
        /* affichage de la vue contenant le formulaire */
    	
    }
    
    
public function actionAjoutLigneCommande() {

	\Controller::loadConfigForPorteur('rinalda');

		$fac = Yii::app()->request->getParam( 'id' );

		$lachat = new Business\LigneAchat();

		$tab_inf = array('i5', 'i7','i3','1m40', '1m50','1m70');

		

		if(Yii::app()->request->getParam('Business\LigneAchat')){

			$lfac = Yii::app()->request->getParam('Business\LigneAchat');
		
		
		
		$lachat->idAchat = $fac; 

		$lachat->article = $lfac['article'];
		$lachat->demandeur = $lfac['demandeur'];
		$lachat->motif = $lfac['motif'];
		$lachat->fournisseur = $lfac['fournisseur'];
		$lachat->reference = $lfac['reference'];
		$lachat->code = $lfac['code'];
		$lachat->quantite = $lfac['quantite'];
		$lachat->statut = 'En cours';
		$lachat->color = 'En cours';
		
	
		
	
		if ($lachat->save()) {
	          	Yii::app()->user->setFlash( "success", Yii::t( 'common', 'create' ) );
	          	
			}else
			{Yii::app()->user->setFlash( "error", Yii::t( 'common', 'Erreur lors de la creation' ) );}
	}
    	
    	$this->renderPartial( '//achat/AjoutLigneCommande', array( 'fac' => $lachat,'tab_inf'=> $tab_inf) );
    		
    	
    	
    }


    public function actionUpdateLC() {
		
		\Controller::loadConfigForPorteur('rinalda');
		$id = Yii::app()->request->getParam( 'id' );
		$lfac = new Business\LigneAchat();
		$lfac = \LigneAchat::model()->findByPk($id);

		if(Yii::app()->request->getParam('LigneAchat'))
		{

			$lifac = Yii::app()->request->getParam('LigneAchat');
			
			$lfac->article = $lifac['article'];
			$lfac->demandeur = $lifac['demandeur'];
			$lfac->motif = $lifac['motif'];
			$lfac->fournisseur = $lifac['fournisseur'];
			$lfac->reference = $lifac['reference'];
			$lfac->code = $lifac['code'];
			$lfac->quantite = $lifac['quantite'];

		
			if ($lfac->save()) {
		          	Yii::app()->user->setFlash( "success", Yii::t( 'common', 'updateOK' ) );
		          	
				}else
				{Yii::app()->user->setFlash( "error", Yii::t( 'common', 'updateNOK' ) );}
			    	   	
	    }	
    	$this->renderPartial( '//achat/updateLC', array( 'lfac' => $lfac ) );
		
    }


    
    public function actionAccueil() {
    $this->render( '//achat/accueil' );
    }

    
    public function actionArticles() {
	\Yii::app()->clientScript->registerScriptFile( Yii::app()->baseUrl.'/businessCore/views/ap/js/campaign.js' );
		\Controller::loadConfigForPorteur('rinalda');
		$fac = new \Facture('search');
		
    	
		
    	// Filtre recherche :
    	if( Yii::app()->request->getParam( 'Facture' ) !== NULL ){
    		$fac->attributes = Yii::app()->request->getParam( 'Facture' );
    		
		}
		
		$year = Yii::app()->request->getParam( 'year' );
		if( Yii::app()->request->getParam( 'year' ) !== NULL && Yii::app()->request->getParam( 'year' ) != "all" ){
    		$fac->year = Yii::app()->request->getParam( 'year' );
    	}else{
			$year = "all";
		}
		
    	$this->render( '//achat/articles', array('fac'=>$fac, 'baseUrl'=> Yii::app()->baseUrl,'year'=>$year));    	  	
    	
    }
    
 public function actionFournisseur() {
	\Yii::app()->clientScript->registerScriptFile( Yii::app()->baseUrl.'/businessCore/views/ap/js/campaign.js' );
		\Controller::loadConfigForPorteur('rinalda');
		$fac = new \Facture('search');
		
    	
		
    	// Filtre recherche :
    	if( Yii::app()->request->getParam( 'Facture' ) !== NULL ){
    		$fac->attributes = Yii::app()->request->getParam( 'Facture' );
    		
		}
		
		$year = Yii::app()->request->getParam( 'year' );
		if( Yii::app()->request->getParam( 'year' ) !== NULL && Yii::app()->request->getParam( 'year' ) != "all" ){
    		$fac->year = Yii::app()->request->getParam( 'year' );
    	}else{
			$year = "all";
		}
		
    	$this->render( '//achat/fournisseur', array('fac'=>$fac, 'baseUrl'=> Yii::app()->baseUrl,'year'=>$year));    	  	
    	
    }

     public function actionInventaire() {
     	\Yii::app()->clientScript->registerScriptFile( Yii::app()->baseUrl.'/businessCore/views/ap/js/campaign.js' );
		\Controller::loadConfigForPorteur('rinalda');
		$fac = new \Facture('search');
		
    	
		
    	// Filtre recherche :
    	if( Yii::app()->request->getParam( 'Facture' ) !== NULL ){
    		$fac->attributes = Yii::app()->request->getParam( 'Facture' );
    		
		}
		
		$year = Yii::app()->request->getParam( 'year' );
		if( Yii::app()->request->getParam( 'year' ) !== NULL && Yii::app()->request->getParam( 'year' ) != "all" ){
    		$fac->year = Yii::app()->request->getParam( 'year' );
    	}else{
			$year = "all";
		}
		
    	$this->render( '//achat/inventaire', array('fac'=>$fac, 'baseUrl'=> Yii::app()->baseUrl,'year'=>$year));
	
    }

    public function actionStock() {


    	\Yii::app()->clientScript->registerScriptFile( Yii::app()->baseUrl.'/businessCore/views/ap/js/campaign.js' );
		\Controller::loadConfigForPorteur('rinalda');
		$fac = new \Facture('search');
		
    	
		
    	// Filtre recherche :
    	if( Yii::app()->request->getParam( 'Facture' ) !== NULL ){
    		$fac->attributes = Yii::app()->request->getParam( 'Facture' );
    		
		}
		
		$year = Yii::app()->request->getParam( 'year' );
		if( Yii::app()->request->getParam( 'year' ) !== NULL && Yii::app()->request->getParam( 'year' ) != "all" ){
    		$fac->year = Yii::app()->request->getParam( 'year' );
    	}else{
			$year = "all";
		}
		
    	$this->render( '//achat/stock', array('fac'=>$fac, 'baseUrl'=> Yii::app()->baseUrl,'year'=>$year));
		  	
    	
    }
  
    
	public function actionListe() {

		\Yii::app()->clientScript->registerScriptFile( Yii::app()->baseUrl.'/businessCore/views/ap/js/campaign.js' );
		\Controller::loadConfigForPorteur('rinalda');
		if(Yii::App()->User->checkAccess("QUALITE_SERVICE")){
			$role_user = 'QUALITE_SERVICE';
			$fac = Business\Achat::getListeByservice('Qualite');
		}
		if(Yii::App()->User->checkAccess("ADMIN_ACHAT")){
			$role_user = 'ADMIN_ACHAT';
			$fac = new \Achat('search');
		}
		else if(Yii::App()->User->checkAccess("MARKETING_SERVICE")){
			$role_user = 'MARKETING_SERVICE';
			$fac = new \Achat('search');
		}
		else if(Yii::App()->User->checkAccess("Achat_IT")){
			$role_user = 'Achat_IT';
			$fac = new \Achat('search');
		}
		else if(Yii::App()->User->checkAccess("Achat_Marketing")){
			$role_user = 'Achat_Marketing';
			$fac = new \Achat('search');
		}
		else if(Yii::App()->User->checkAccess("Achat_RMDD")){
			$role_user = 'Achat_RMDD';
			$fac = new \Achat('search');
		}
		else if(Yii::App()->User->checkAccess("Achat_Affiliation")){
			$role_user = 'Achat_Affiliation';
			$fac = new \Achat('search');
		}
		else if(Yii::App()->User->checkAccess("Achat_Qualite")){
			$role_user = 'Achat_Qualite';
			$fac = new \Achat('search');
		}
		else if(Yii::App()->User->checkAccess("Achat_BM")){
			$role_user = 'Achat_BM';
			$fac = new \Achat('search');
		}
		else if(Yii::App()->User->checkAccess("Achat_Finance")){
			$role_user = 'Achat_Finance';
			$fac = new \Achat('search');
		}
		else if(Yii::App()->User->checkAccess("Achat_Operation")){
			$role_user = 'Achat_Operation';
			$fac = new \Achat('search');
		}
		else if(Yii::App()->User->checkAccess("Achat_SAchat")){
			$role_user = 'Achat_SAchat';
			$fac = new \Achat('search');
		}
		else if(Yii::App()->User->checkAccess("Achat_RH")){
			$role_user = 'Achat_RH';
			$fac = new \Achat('search');
		}
		else if(Yii::App()->User->checkAccess("Achat_SAV")){
			$role_user = 'Achat_SAV';
			$fac = new \Achat('search');
		}
		else if(Yii::App()->User->checkAccess("ADMIN")){
			$role_user = 'ADMIN_ACHAT';
			
			$fac = new \Achat('search');			

		}
		else{
			$this->redirect('login');
		}

		
    	if( Yii::app()->request->getParam( 'Achat' ) !== NULL ){
    		$fac->attributes = Yii::app()->request->getParam( 'Achat' );
    		
    		
		}
		
		$year = Yii::app()->request->getParam( 'year' );
		if( Yii::app()->request->getParam( 'year' ) !== NULL && Yii::app()->request->getParam( 'year' ) != "all" ){
    		$fac->year = Yii::app()->request->getParam( 'year' );
    	}else{
			$year = "all";
		}

		$user = \Business\User::load( Yii::app()->user->getId() );
		!$user  ? $this->redirect('login') : $this->render( '//achat/liste', array('fac'=>$fac,'role'=>$role_user,'baseUrl'=> Yii::app()->baseUrl,'year'=>$year));    

    }


	
	public function actionPdf() {
    	\Controller::loadConfigForPorteur('rinalda');
		$id = Yii::app()->request->getParam( 'id' );
		$Achat = \Achat::model()->findByPk($id);
		$path = $Achat->pdf;
		


		require_once(Yii::app()->basePath.'/extensions/html2pdf/html2pdf.class.php');
			$html2pdf = new HTML2PDF('P','A4','fr');
			$html2pdf->WriteHTML($this->getPdfContent($Achat));
			
			$html2pdf->Output($path,'F');	
		$file = 'Achat_'.$Achat->date.'.pdf';
    	if(file_exists($path)){
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream; charset=utf-8');
			header("Content-Disposition: attachment; filename=".$file);
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			header('Content-Length: ' . filesize($path));
			readfile($path);
			unlink($path);
		}
    	$this->render( '//achat/liste', array('fac'=>$Achat));
    }


public function actionDeleteLcmd() {
	\Yii::app()->clientScript->registerScriptFile( Yii::app()->baseUrl.'/businessCore/views/ap/js/campaign.js' );
    	\Controller::loadConfigForPorteur('rinalda');
		$id = Yii::app()->request->getParam( 'id' );
		$lfac = \LigneAchat::model()->findByPk($id);
		$lfac->statut = 'Refusée';
		$lfac->color = 'Refuse';
		
		
		if($lfac->save() )
		{Yii::app()->user->setFlash( "success", Yii::t( 'common', 'Ligne commande refuse' ) );}
		else
		{Yii::app()->user->setFlash( "error", Yii::t( 'common', 'error' ) );}
		
		\Yii::app()->clientScript->registerScriptFile( Yii::app()->baseUrl.'/businessCore/views/ap/js/campaign.js' );
    	$this->renderPartial( '//achat/subListe', array('lfac'=>$lfac));
    }

    public function actionLaccepte() {
    	\Yii::app()->clientScript->registerScriptFile( Yii::app()->baseUrl.'/businessCore/views/ap/js/campaign.js' );

    	\Controller::loadConfigForPorteur('rinalda');
		$id = Yii::app()->request->getParam( 'id' );
		$lfac = \LigneAchat::model()->findByPk($id);
		$lfac->statut = 'Acceptée';
		$lfac->color = 'Accepte';
		
		
		if($lfac->save() )
		{Yii::app()->user->setFlash( "success", Yii::t( 'common', 'Ligne commande accepte' ) );}
		else
		{Yii::app()->user->setFlash( "error", Yii::t( 'common', 'error' ) );}
		
	\Yii::app()->clientScript->registerScriptFile( Yii::app()->baseUrl.'/businessCore/views/ap/js/campaign.js' );	
    	$this->renderPartial( '//achat/subListe', array('lfac'=>$lfac));


    }

    public function actionEnvoie() {
    	\Controller::loadConfigForPorteur('rinalda');
		$id = Yii::app()->request->getParam( 'id' );

		$fac = \Achat::model()->findByPk($id);
		$fac->statut = 'En cours';
		$fac->color = 'En cours';			
			
			
			\Yii::app()->clientScript->registerScriptFile( Yii::app()->baseUrl.'/businessCore/views/ap/js/campaign.js' );
			


		$personne = array("Service IT" => "chadia.tagnani@kindyinfomaroc.com", "Service Achat" => "Nadia.MOUHIB@kindyinfomaroc.com", "Service Marketing" => "amine.elkhoukhi@kindyinfomaroc.com", "Service Qualité" => "amal.amazouz@kindyinfomaroc.com", "Service Finance" => "amal.khana@kindyinfomaroc.com ", "Service RMDD" => "n.akhana@kindyinfomaroc.com","Service Affiliation" => "mariane@infodata-media.com","Service Brand Management" => "elhassan.laachach@kindyinfomaroc.com","Service Opération" => "ikrame.bensaoud@kindyinfomaroc.com","Service SAV" => "laila.bahloul@kindyinfomaroc.com");
		
		if( $fac->save() )
				{				   
					$msg  = '<div style=" font-size:15px;">Bonjour, <br><br>je vous envoie cet e-mail pour vous informer que je viens d&acute;ajouter une nouvelle demande d&acute;achat. <br><br> Voici le lien pour la nouvelle demande : <a href="http://www.chancepure.com/voyances/index.php/Achat/cmd/'.$fac->id.'">http://www.chancepure.com/voyances/index.php/Achat/cmd/'.$fac->id.' <br><br> </a>Cordialement, </div>';
					
					$service=$fac->service_demandeur;
					
					$email=$personne["$service"];

					
					$expediteur =$email;
					$mail='khadija.elhadini.kim@gmail.com'; 
					$mailNadia='nadia.mouhib.ki@gmail.com';
					$port='test';
					$sendAlert  = \MailHelper::sendMail( $mail, $expediteur, 'Une nouvelle demande pour achat', $msg );
					if (!$sendAlert) {
						die('error envoie email');
					}
					$sendAlertSecond  = \MailHelper::sendMail( $mailNadia, $expediteur, 'Une nouvelle demande pour achat', $msg );
					if (!$sendAlertSecond) {
						die('error envoie email Nadia');
					}

					Yii::app()->user->setFlash( "success", Yii::t( 'common', 'updateOK' ) );
				}else{
					Yii::app()->user->setFlash( "error", Yii::t( 'product', 'updateNOK' ) );
				}

		
	
		\Yii::app()->clientScript->registerScriptFile( Yii::app()->baseUrl.'/businessCore/views/ap/js/campaign.js' );
    
    	\Yii::app()->end();
    }

     public function actionEncours() {
    	\Yii::app()->clientScript->registerScriptFile( Yii::app()->baseUrl.'/businessCore/views/ap/js/campaign.js' );
		\Controller::loadConfigForPorteur('rinalda');
		if(Yii::App()->User->checkAccess("QUALITE_SERVICE")){
			$role_user = 'QUALITE_SERVICE';
			$fac = Business\Achat::getListeByservice('Qualite');
		}
		if(Yii::App()->User->checkAccess("ADMIN_ACHAT")){
			$role_user = 'ADMIN_ACHAT';
			$fac = new \Achat('search');
		}
		else if(Yii::App()->User->checkAccess("MARKETING_SERVICE")){
			$role_user = 'MARKETING_SERVICE';
			$fac = new \Achat('search');
		}
		else if(Yii::App()->User->checkAccess("Achat_IT")){
			$role_user = 'Achat_IT';
			$fac = new \Achat('search');
		}
		else if(Yii::App()->User->checkAccess("Achat_Marketing")){
			$role_user = 'Achat_Marketing';
			$fac = new \Achat('search');
		}
		else if(Yii::App()->User->checkAccess("Achat_RMDD")){
			$role_user = 'Achat_RMDD';
			$fac = new \Achat('search');
		}
		else if(Yii::App()->User->checkAccess("Achat_Affiliation")){
			$role_user = 'Achat_Affiliation';
			$fac = new \Achat('search');
		}
		else if(Yii::App()->User->checkAccess("Achat_Qualite")){
			$role_user = 'Achat_Qualite';
			$fac = new \Achat('search');
		}
		else if(Yii::App()->User->checkAccess("Achat_BM")){
			$role_user = 'Achat_BM';
			$fac = new \Achat('search');
		}
		else if(Yii::App()->User->checkAccess("Achat_Finance")){
			$role_user = 'Achat_Finance';
			$fac = new \Achat('search');
		}
		else if(Yii::App()->User->checkAccess("Achat_Operation")){
			$role_user = 'Achat_Operation';
			$fac = new \Achat('search');
		}
		else if(Yii::App()->User->checkAccess("Achat_SAchat")){
			$role_user = 'Achat_SAchat';
			$fac = new \Achat('search');
		}
		else if(Yii::App()->User->checkAccess("Achat_RH")){
			$role_user = 'Achat_RH';
			$fac = new \Achat('search');
		}
		else if(Yii::App()->User->checkAccess("Achat_SAV")){
			$role_user = 'Achat_SAV';
			$fac = new \Achat('search');
		}
		else if(Yii::App()->User->checkAccess("ADMIN")){
			$role_user = 'ADMIN_ACHAT';
			
			$fac = new \Achat('search');			

		}
		else{
			$this->redirect('login');
		}
		
    	// Filtre recherche :
    	if( Yii::app()->request->getParam( 'Achat' ) !== NULL ){
    		$fac->attributes = Yii::app()->request->getParam( 'Achat' );
    		
		}
		
		$year = Yii::app()->request->getParam( 'year' );
		if( Yii::app()->request->getParam( 'year' ) !== NULL && Yii::app()->request->getParam( 'year' ) != "all" ){
    		$fac->year = Yii::app()->request->getParam( 'year' );
    	}else{
			$year = "all";
		}

		$user = \Business\User::load( Yii::app()->user->getId() );
		!$user  ? $this->redirect('login') : $this->render( '//achat/encours', array('fac'=>$fac,'role'=>$role_user, 'baseUrl'=> Yii::app()->baseUrl,'year'=>$year));
		
    
    }

    public function actionAccepte() {
    	\Yii::app()->clientScript->registerScriptFile( Yii::app()->baseUrl.'/businessCore/views/ap/js/campaign.js' );
		\Controller::loadConfigForPorteur('rinalda');
		if(Yii::App()->User->checkAccess("QUALITE_SERVICE")){
			$role_user = 'QUALITE_SERVICE';
			$fac = Business\Achat::getListeByservice('Qualite');
		}
		if(Yii::App()->User->checkAccess("ADMIN_ACHAT")){
			$role_user = 'ADMIN_ACHAT';
			$fac = new \Achat('search');
		}
		else if(Yii::App()->User->checkAccess("MARKETING_SERVICE")){
			$role_user = 'MARKETING_SERVICE';
			$fac = new \Achat('search');
		}
		else if(Yii::App()->User->checkAccess("Achat_IT")){
			$role_user = 'Achat_IT';
			$fac = new \Achat('search');
		}
		else if(Yii::App()->User->checkAccess("Achat_Marketing")){
			$role_user = 'Achat_Marketing';
			$fac = new \Achat('search');
		}
		else if(Yii::App()->User->checkAccess("Achat_RMDD")){
			$role_user = 'Achat_RMDD';
			$fac = new \Achat('search');
		}
		else if(Yii::App()->User->checkAccess("Achat_Affiliation")){
			$role_user = 'Achat_Affiliation';
			$fac = new \Achat('search');
		}
		else if(Yii::App()->User->checkAccess("Achat_Qualite")){
			$role_user = 'Achat_Qualite';
			$fac = new \Achat('search');
		}
		else if(Yii::App()->User->checkAccess("Achat_BM")){
			$role_user = 'Achat_BM';
			$fac = new \Achat('search');
		}
		else if(Yii::App()->User->checkAccess("Achat_Finance")){
			$role_user = 'Achat_Finance';
			$fac = new \Achat('search');
		}
		else if(Yii::App()->User->checkAccess("Achat_Operation")){
			$role_user = 'Achat_Operation';
			$fac = new \Achat('search');
		}
		else if(Yii::App()->User->checkAccess("Achat_SAchat")){
			$role_user = 'Achat_SAchat';
			$fac = new \Achat('search');
		}
		else if(Yii::App()->User->checkAccess("Achat_RH")){
			$role_user = 'Achat_RH';
			$fac = new \Achat('search');
		}
		else if(Yii::App()->User->checkAccess("Achat_SAV")){
			$role_user = 'Achat_SAV';
			$fac = new \Achat('search');
		}
		else if(Yii::App()->User->checkAccess("ADMIN")){
			$role_user = 'ADMIN_ACHAT';
			
			$fac = new \Achat('search');			

		}
		else{
			$this->redirect('login');
		}
		
    	// Filtre recherche :
    	if( Yii::app()->request->getParam( 'Achat' ) !== NULL ){
    		$fac->attributes = Yii::app()->request->getParam( 'Achat' );
    		
		}
		
		$year = Yii::app()->request->getParam( 'year' );
		if( Yii::app()->request->getParam( 'year' ) !== NULL && Yii::app()->request->getParam( 'year' ) != "all" ){
    		$fac->year = Yii::app()->request->getParam( 'year' );
    	}else{
			$year = "all";
		}
	
          $user = \Business\User::load( Yii::app()->user->getId() );
		!$user  ? $this->redirect('login') : $this->render( '//achat/accepte', array('fac'=>$fac,'role'=>$role_user, 'baseUrl'=> Yii::app()->baseUrl/*,'year'=>$year*/));

    	
    }

    public function actionRefus() {
    	\Yii::app()->clientScript->registerScriptFile( Yii::app()->baseUrl.'/businessCore/views/ap/js/campaign.js' );
		\Controller::loadConfigForPorteur('rinalda');
		if(Yii::App()->User->checkAccess("QUALITE_SERVICE")){
			$role_user = 'QUALITE_SERVICE';
			$fac = Business\Achat::getListeByservice('Qualite');
		}
		if(Yii::App()->User->checkAccess("ADMIN_ACHAT")){
			$role_user = 'ADMIN_ACHAT';
			$fac = new \Achat('search');
		}
		else if(Yii::App()->User->checkAccess("MARKETING_SERVICE")){
			$role_user = 'MARKETING_SERVICE';
			$fac = new \Achat('search');
		}
		else if(Yii::App()->User->checkAccess("Achat_IT")){
			$role_user = 'Achat_IT';
			$fac = new \Achat('search');
		}
		else if(Yii::App()->User->checkAccess("Achat_Marketing")){
			$role_user = 'Achat_Marketing';
			$fac = new \Achat('search');
		}
		else if(Yii::App()->User->checkAccess("Achat_RMDD")){
			$role_user = 'Achat_RMDD';
			$fac = new \Achat('search');
		}
		else if(Yii::App()->User->checkAccess("Achat_Affiliation")){
			$role_user = 'Achat_Affiliation';
			$fac = new \Achat('search');
		}
		else if(Yii::App()->User->checkAccess("Achat_Qualite")){
			$role_user = 'Achat_Qualite';
			$fac = new \Achat('search');
		}
		else if(Yii::App()->User->checkAccess("Achat_BM")){
			$role_user = 'Achat_BM';
			$fac = new \Achat('search');
		}
		else if(Yii::App()->User->checkAccess("Achat_Finance")){
			$role_user = 'Achat_Finance';
			$fac = new \Achat('search');
		}
		else if(Yii::App()->User->checkAccess("Achat_Operation")){
			$role_user = 'Achat_Operation';
			$fac = new \Achat('search');
		}
		else if(Yii::App()->User->checkAccess("Achat_SAchat")){
			$role_user = 'Achat_SAchat';
			$fac = new \Achat('search');
		}
		else if(Yii::App()->User->checkAccess("Achat_RH")){
			$role_user = 'Achat_RH';
			$fac = new \Achat('search');
		}
		else if(Yii::App()->User->checkAccess("Achat_SAV")){
			$role_user = 'Achat_SAV';
			$fac = new \Achat('search');
		}
		else if(Yii::App()->User->checkAccess("ADMIN")){
			$role_user = 'ADMIN_ACHAT';
			
			$fac = new \Achat('search');			

		}
		else{
			$this->redirect('login');
		}
		
    	
		
    	// Filtre recherche :
    	if( Yii::app()->request->getParam( 'Achat' ) !== NULL ){
    		$fac->attributes = Yii::app()->request->getParam( 'Achat' );
    		
		}
		
		$year = Yii::app()->request->getParam( 'year' );
		if( Yii::app()->request->getParam( 'year' ) !== NULL && Yii::app()->request->getParam( 'year' ) != "all" ){
    		$fac->year = Yii::app()->request->getParam( 'year' );
    	}else{
			$year = "all";
		}

		 $user = \Business\User::load( Yii::app()->user->getId() );
		!$user  ? $this->redirect('login') : $this->render( '//achat/refus', array('fac'=>$fac,'role'=>$role_user, 'baseUrl'=> Yii::app()->baseUrl,'year'=>$year));
		
    	
    }

    public function actionDeleteLc() {
    	\Controller::loadConfigForPorteur('rinalda');
		$id = Yii::app()->request->getParam( 'id' );
		$lfac = \LigneAchat::model()->findByPk($id);
		
		$ok = true;
		if(!$lfac->delete())
		{$ok = false;}
		
		if( $ok )
		{Yii::app()->user->setFlash( "success", Yii::t( 'common', 'deleteOK' ) );}
		else
		{Yii::app()->user->setFlash( "error", Yii::t( 'common', 'updateNOK' ) );}
		
		
		\Yii::app()->clientScript->registerScriptFile( Yii::app()->baseUrl.'/businessCore/views/ap/js/campaign.js' );
    	$this->render( '//achat/subListe', array('lfac'=>$lfac));
    }

	
	public function actionDelete() {
    	\Controller::loadConfigForPorteur('rinalda');
		$id = Yii::app()->request->getParam( 'id' );
		$fac = \Achat::model()->findByPk($id);
		
		$ok = true;
		if(!\LigneAchat::model()->deleteAll(" idAchat ='$id' " ))
		{$ok = false;}
		if(!$fac->delete())
		{$ok = false;}
		
		
		if( $ok )
		{Yii::app()->user->setFlash( "success", Yii::t( 'common', 'deleteOK' ) );}
		else
		{Yii::app()->user->setFlash( "error", Yii::t( 'common', 'updateNOK' ) );}
		
		$fac = new \Achat('search');
		\Yii::app()->clientScript->registerScriptFile( Yii::app()->baseUrl.'/businessCore/views/ap/js/campaign.js' );
    	$this->render( '//achat/liste', array('fac'=>$fac));
    }
	
	public function actionWord() {
    	\Controller::loadConfigForPorteur('rinalda');
		$id = Yii::app()->request->getParam( 'id' );
		$fac = \Facture::model()->findByPk($id);
		$path = $fac->docx;
		$file = 'Facture_'.$fac->ref.'.docx';
    	if(file_exists($path)){
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream; charset=utf-8');
			header("Content-Disposition: attachment; filename=".$file);
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			header('Content-Length: ' . filesize($path));
			readfile($path);
		}
    	$this->render( '//achat/liste', array('fac'=>$fac));
    }
	
	public function actionSubListe() {
		\Yii::app()->clientScript->registerScriptFile( Yii::app()->baseUrl.'/businessCore/views/ap/js/campaign.js' );


		
		if(Yii::App()->User->checkAccess("ADMIN_ACHAT"))
		{$role_user = 'ADMIN_ACHAT';}
		else if(Yii::App()->User->checkAccess("MARKETING_SERVICE"))
		{$role_user = 'MARKETING_SERVICE';}
		else if(Yii::App()->User->checkAccess("ADMIN"))
		{$role_user = 'ADMIN';}
		else
		{$role_user = 'AUTRE';}
		
		\Controller::loadConfigForPorteur('rinalda');

		$fac    = Yii::app()->request->getParam( 'id' );	
		$lfac 	= Business\LigneAchat::getLigneAchatByFac($fac);
		$res    = $lfac;

		

		$personne = array("Service IT" => "chadia.tagnani@kindyinfomaroc.com", "Service Achat" => "Nadia.MOUHIB@kindyinfomaroc.com", "Service Marketing" => "amine.elkhoukhi@kindyinfomaroc.com", "Service Qualité" => "amal.amazouz@kindyinfomaroc.com", "Service Finance" => "amal.khana@kindyinfomaroc.com ", "Service RMDD" => "n.akhana@kindyinfomaroc.com","Service Affiliation" => "mariane@infodata-media.com","Service Brand Management" => "elhassan.laachach@kindyinfomaroc.com","Service Opération" => "ikrame.bensaoud@kindyinfomaroc.com","Service SAV" => "laila.bahloul@kindyinfomaroc.com");

		if(isset($_POST['Business\Achat']))
		{
			$id    = $_POST['Business\Achat']['id'];
			$achat = \Achat::model()->findByPk($id); 

			if (($_POST['Business\Achat']['statut'])=='Validée') {
				$achat->statut = $_POST['Business\Achat']['statut'];
				$achat->color = 'Valide';
			}
			elseif (($_POST['Business\Achat']['statut'])=='Refusée') {
				$achat->statut = $_POST['Business\Achat']['statut'];
				$achat->color = 'Refuse';
			}
			
			
			if( $achat->save() ) 
				{				   
					
					$status = $achat->statut;
					if($status=='Validée') {
						$msg  = '<div style=" font-size:15px;">Bonjour,<br> <br> Je viens de recevoir votre demande d&acute;achat qui a pour titre '.$achat->designation.'  et je vous informe que je l&acute;ai accepté .<br><br>
						Voici le lien de la demande d&acute;achat acceptée. <a href="http://direct.chancepure.com/voyances/index.php/Achat/cmd/'.$achat->id.'">http://direct.chancepure.com/voyances/index.php/Achat/cmd/'.$achat->id.'</a><br><br> Cordialement, </div>';
					}  else {

						$msg  = '<div style=" font-size:15px;">Bonjour, <br><br> Je viens de recevoir votre demande d&acute;achat qui a pour titre '.$achat->designation.'  et je vous informe que je l&acute;ai réfusé .<br><br>
						Voici le lien de la demande d&acute;achat refusée. <a href="http://direct.chancepure.com/voyances/index.php/Achat/cmd/'.$achat->id.'">http://direct.chancepure.com/voyances/index.php/Achat/cmd/'.$achat->id.'</a><br><br> Cordialement, </div>';

					}
					
					
					$service=$achat->service_demandeur;
					
					$email=$personne["$service"];
					
					$expediteur = "elhadini.khadija@kindyinfomaroc.com";
					$mail=$email;

										
					$sendAlert  = \MailHelper::sendMail( $mail, $expediteur, 'Votre demande pour achat est '.$achat->statut, $msg );

					Yii::app()->user->setFlash( "success", Yii::t( 'common', 'updateOK' ) );
				}else{
					Yii::app()->user->setFlash( "error", Yii::t( 'product', 'updateNOK' ) );
				}

        }
		
    	// Filtre recherche :
    	if( Yii::app()->request->getParam( 'Business\LigneAchat' ) !== NULL )
		{$lfac->attributes = Yii::app()->request->getParam( 'Business\LigneAchat' );}

    	$this->renderPartial( '//achat/subListe', array( 'fac' => $fac,'RoleUser' => $role_user, 'lfac' => $res ) );
		
    }


	public function actionExcel_v2()
	{
		\Controller::loadConfigForPorteur('rinalda');
				
		$fac	= new \Business\Achat();
	 	$facs       = $fac->search();

	 	if(!empty($facs->data)){	
			
				$tab = array();
				foreach($facs->data as $fac)
				{
					$id= $fac->id;
					$designation=$fac->designation;
					$date_livraison=$fac->date_livraison;
					$service_demandeur=$fac->service_demandeur;
					$service_acheteur=$fac->service_acheteur;
					$date= $fac->date;
					$statut=$fac->statut;

					$tab[] = array(
											$id.'|__#__|',
											$designation.'|__#__|',
											$service_demandeur.'|__#__|',
											$service_acheteur.'|__#__|',
											$date_livraison.'|__#__|',
											$date.'|__#__|',
											$statut.'|__#__|'
									);

				}


		
		

		if(isset($_POST['tblexcel']) OR 1){

			
			 \Yii::import('ext.PHPExcel');
			 Yii::import('application.vendors.PHPExcel',true);

			 
			 $objPHPExcel = new PHPExcel();
			 

			
           $objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('B5', 'ID')
			->setCellValue('C5', 'Designation')
			->setCellValue('D5', 'Service demandeur')
			->setCellValue('E5', 'Service acheteur')
			->setCellValue('F5', 'Date de livraison souhaitée')
			->setCellValue('G5', 'Date')
			->setCellValue('H5', 'Statut');			
			foreach(range('B','H') as $columnID)
			{
				$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
			}
			

			$objPHPExcel->getActiveSheet()->setTitle('test');
			$objPHPExcel->setActiveSheetIndex(0);

			$sheet = $tab;

			$rowInitID = 6;
			$rowID = $rowInitID;
			$track  = '___init___';
			$merge = array();
			foreach($sheet as $rowArray) {
			   $columnID = 'B';
			   
				   $track  = $rowArray[0];
			  
			   if ($track  === $rowArray[0]) {
				   
				   if (!isset($merge[$rowArray[0]])) {
				   		$merge[$rowArray[0]] = array();
				   }
				   array_push($merge[$rowArray[0]], $rowID);
			   }
			   foreach($rowArray as $columnValue) {
					 $track  = $rowArray[0];

				  $tab_value = explode("|__#__|",$columnValue);
				  $objPHPExcel->getActiveSheet()->setCellValueExplicit($columnID.$rowID, $tab_value[0], PHPExcel_Cell_DataType::TYPE_STRING);
				  $objPHPExcel->getActiveSheet()->getStyle($columnID.$rowID)
							  ->getAlignment()
							  ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				 $objPHPExcel->getActiveSheet()->getStyle($columnID.$rowID)
							  ->getAlignment()
							  ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				  $objPHPExcel->getActiveSheet()->getRowDimension($rowID)->setRowHeight(30);
			

				  if(isset($tab_value[1]) && $tab_value[1]!='')
				  {
				    $objPHPExcel->getActiveSheet()
					->getStyle($columnID.$rowID)
					->getFill()
					->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
					->getStartColor()
					->setARGB($tab_value[1]);
				  }

				  $objPHPExcel->getActiveSheet()->getStyle($columnID.$rowID)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

				  $columnID++;
			   }
			   $rowID++;
			}
			
			foreach ($merge as $elts) {
				$eltInit = $elts[0];
				$eltEnd = end($elts);
				$objPHPExcel->getActiveSheet()->mergeCells("B{$eltInit}:B{$eltEnd}");
			}

			$objPHPExcel->createSheet();
			$objPHPExcel->setActiveSheetIndex(1)->setCellValue('A8', 'Codification');
			$objPHPExcel->getActiveSheet()->getStyle("A4")->getFont()->setBold(true)
			 					->setSize(24)
                                ->getColor()
								->setRGB('6F6F6F');
			$objPHPExcel->getActiveSheet()
						->getStyle("A8")
						->getFill()
						->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
						->getStartColor()
						->setARGB('ccffff');

			$objPHPExcel->getActiveSheet()
			->getStyle("A8")
			->getAlignment()
			->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn('A')->setAutoSize(false);
			$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn('A')->setWidth('30');
			$objPHPExcel->getActiveSheet()->getStyle("A8")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

			$objPHPExcel->getActiveSheet()->getStyle('B10')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('&H50d092');
			$objPHPExcel->getActiveSheet()->getStyle('B11')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('&Hff');
			$objPHPExcel->getActiveSheet()->getStyle('B12')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('&Hd9d9d9');
			$objPHPExcel->getActiveSheet()->getStyle('B13')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('&Hffff');

			$objPHPExcel->getActiveSheet()->setTitle('Template');
			$objPHPExcel->setActiveSheetIndex(0);

			ob_end_clean();
			ob_start();

			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="Achats('.date('d_m_Y').').xls"');
			header('Cache-Control: max-age=0');
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$objWriter->save('php://output');



		}
	}
		else
		{Yii::app()->user->setFlash( "error", Yii::t( 'common', 'Erreur lors de la récupération du tableau à exporter' ) );}
	}


public function actionExcel()
	{
		\Controller::loadConfigForPorteur('rinalda');


		$ach    = Yii::app()->request->getParam( 'id' );	
		$lach = \LigneAchat::model()->searchByAchat($ach);
		
		$res    = $lach;
		
	 	if(!empty($res->data)){	
			
				$tab = array();
				foreach($res->data as $lach)
				{
					$id= $lach->id;
					$article=$lach->article;
					$demandeur=$lach->demandeur;
					$motif=$lach->motif;
					$fournisseur=$lach->fournisseur;
					$reference= $lach->reference;
					$code=$lach->code;
					$quantite=$lach->quantite;
					$statut=$lach->statut;
					$idAchat=$lach->idAchat;

					$tab[] = array(
											$id.'|__#__|',
											$article.'|__#__|',
											$demandeur.'|__#__|',
											$motif.'|__#__|',
											$fournisseur.'|__#__|',
											$reference.'|__#__|',
											$code.'|__#__|',
											$quantite.'|__#__|',
											$idAchat.'|__#__|',
											$statut.'|__#__|'
									);

				}

		if(1){

			
			 \Yii::import('ext.PHPExcel');
			 Yii::import('application.vendors.PHPExcel',true);
			 
			 $objPHPExcel = new PHPExcel();
			
			
           $objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('B9', 'ID')
			->setCellValue('C9', 'Article')
			->setCellValue('D9', 'demandeur')
			->setCellValue('E9', 'Motif')
			->setCellValue('F9', 'reference')
			->setCellValue('G9', 'code')
			->setCellValue('H9', 'quantite')
			->setCellValue('I9', 'fournisseur')
			->setCellValue('J9', 'idAchat')
			->setCellValue('K9', 'statut');			
			foreach(range('B','K') as $columnID)
			{
				$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
			}
			



			$objPHPExcel->getActiveSheet()->setTitle('test');
			$objPHPExcel->setActiveSheetIndex(0);

			$sheet = $tab;

			$rowInitID = 10;
			$rowID = $rowInitID;
			$track  = '___init___';
			$merge = array();
			foreach($sheet as $rowArray) {
			   $columnID = 'B';
			   
				   $track  = $rowArray[0];
			   
			   if ($track  === $rowArray[0]) {
				   
				   if (!isset($merge[$rowArray[0]])) {
				   		$merge[$rowArray[0]] = array();
				   }
				   array_push($merge[$rowArray[0]], $rowID);
			   }
			   foreach($rowArray as $columnValue) {
					 $track  = $rowArray[0];

				  $tab_value = explode("|__#__|",$columnValue);
				  $objPHPExcel->getActiveSheet()->setCellValueExplicit($columnID.$rowID, $tab_value[0], PHPExcel_Cell_DataType::TYPE_STRING);
				  $objPHPExcel->getActiveSheet()->getStyle($columnID.$rowID)
							  ->getAlignment()
							  ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				 $objPHPExcel->getActiveSheet()->getStyle($columnID.$rowID)
							  ->getAlignment()
							  ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				  $objPHPExcel->getActiveSheet()->getRowDimension($rowID)->setRowHeight(30);
				
				 


				  if(isset($tab_value[1]) && $tab_value[1]!='')
				  {
				    $objPHPExcel->getActiveSheet()
					->getStyle($columnID.$rowID)
					->getFill()
					->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
					->getStartColor()
					->setARGB($tab_value[1]);
				  }

				  $objPHPExcel->getActiveSheet()->getStyle($columnID.$rowID)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

				  $columnID++;
			   }
			   $rowID++;
			}
			
			foreach ($merge as $elts) {
				$eltInit = $elts[0];
				$eltEnd = end($elts);
				$objPHPExcel->getActiveSheet()->mergeCells("B{$eltInit}:B{$eltEnd}");
			}

			$objPHPExcel->createSheet();
			$objPHPExcel->setActiveSheetIndex(1)->setCellValue('A8', 'Codification');
			$objPHPExcel->getActiveSheet()->getStyle("A4")->getFont()->setBold(true)
			 					->setSize(24)
                                ->getColor()
								->setRGB('6F6F6F');
			$objPHPExcel->getActiveSheet()
						->getStyle("A8")
						->getFill()
						->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
						->getStartColor()
						->setARGB('ccffff');

			$objPHPExcel->getActiveSheet()
			->getStyle("A8")
			->getAlignment()
			->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn('A')->setAutoSize(false);
			$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn('A')->setWidth('30');
			$objPHPExcel->getActiveSheet()->getStyle("A8")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

			$objPHPExcel->getActiveSheet()->getStyle('B10')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('&H50d092');
			$objPHPExcel->getActiveSheet()->getStyle('B11')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('&Hff');
			$objPHPExcel->getActiveSheet()->getStyle('B12')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('&Hd9d9d9');
			$objPHPExcel->getActiveSheet()->getStyle('B13')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('&Hffff');

			$objPHPExcel->getActiveSheet()->setTitle('Template');
			$objPHPExcel->setActiveSheetIndex(0);

			ob_end_clean();
			ob_start();

			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="LigneAchats('.date('d_m_Y').').xls"');
			header('Cache-Control: max-age=0');
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$objWriter->save('php://output');



		}
	}
		else
			Yii::app()->user->setFlash( "error", Yii::t( 'common', 'Erreur lors de la récupération du tableau à exporter' ) );
	}






    
      
	public function actionUpdate() {
		
		$months = ["Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre"];
		\Controller::loadConfigForPorteur('rinalda');
		$id = Yii::app()->request->getParam( 'id' );
		
		
		$fac = \Facture::model()->findByPk($id); 
		$lfac = \LigneFacture::model()->findAllByAttributes(array( 'idFacture' => $id ));
		
		if(Yii::app()->request->getParam('type')){
			$type = Yii::app()->request->getParam('type'); 
		}else{
			$type = $fac->type;
		}
		
		
		if(Yii::app()->request->getParam('ok') && $type=='kindy'){
			$facture = Yii::app()->request->getParam('facture');
			
			\Controller::loadConfigForPorteur('rinalda');
			
			$Facture = new Facture();
			$Facture = $Facture->findByAttributes(array('id' => $facture['id']));
			
			$date = explode(' ',$facture['date']);
			$Facture->date = $date[2].'-'.(array_search($date[1], $months)+1).'-'.$date[0];
			$Facture->titre = $facture['titre'];
			$Facture->ref = $facture['ref'];
			$Facture->nom_client = $facture['client-name'];
			$Facture->adresse_client = $facture['client-adresse'];
			$Facture->nom_compte = $facture['compte'];
			$Facture->banque = $facture['banque'];
			$Facture->adresse_banque = $facture['banque-adresse'];
			$Facture->IBAN = $facture['IBAN'];
			$Facture->swift = $facture['Swift'];
			$Facture->save();
			
			$pdf_file = $Facture->pdf;
			$word_file = $Facture->docx;
			
			
			for($i=1;$i<=count($facture['items']);$i++){
				$LigneFacture = new LigneFacture();
				$LigneFacture = $LigneFacture->findByAttributes(array('id' => $facture['items'][$i]['id'],'idFacture' => $facture['id']));
				$LigneFacture->idFacture = $Facture->id;
				$LigneFacture->nom = $facture['items'][$i]['text'];
				$LigneFacture->montant = str_replace(' ', '', $facture['items'][$i]['prix']);
				$LigneFacture->subLignes = serialize($facture['items'][$i]['subs']);
				$LigneFacture->save();
			}
			
			require_once(Yii::app()->basePath.'/extensions/html2pdf/html2pdf.class.php');
			$html2pdf = new HTML2PDF('P','A4','fr');
			$html2pdf->WriteHTML($this->getPdfContent($facture));
			@unlink($pdf_file);
			$html2pdf->Output($pdf_file,'F');
			
			$this->fillWord($facture,$word_file);
			
			$fac = new \Facture('search');
			\Yii::app()->clientScript->registerScriptFile( Yii::app()->baseUrl.'/businessCore/views/ap/js/campaign.js' );
			$this->redirect(array('Facture/liste'));
		}
		if(Yii::app()->request->getParam('ok') && $type=='note_credit_kindy'){
			$facture = Yii::app()->request->getParam('facture');
			
			\Controller::loadConfigForPorteur('rinalda');
			
			$Facture = new Facture();
			$Facture = $Facture->findByAttributes(array('id' => $facture['id']));
			
			$date = explode(' ',$facture['date']);
			$Facture->date = $date[2].'-'.(array_search($date[1], $months)+1).'-'.$date[0];
			$Facture->titre = $facture['titre'];
			$Facture->ref = $facture['ref'];
			$Facture->nom_client = $facture['client-name'];
			$Facture->adresse_client = $facture['client-adresse'];
			$Facture->nom_compte = $facture['compte'];
			$Facture->banque = $facture['banque'];
			$Facture->adresse_banque = $facture['banque-adresse'];
			$Facture->IBAN = $facture['IBAN'];
			$Facture->swift = $facture['Swift'];
			$Facture->save();
			
			$pdf_file = $Facture->pdf;
			$word_file = $Facture->docx;
			
			
			for($i=1;$i<=count($facture['items']);$i++){
				$LigneFacture = new LigneFacture();
				$LigneFacture = $LigneFacture->findByAttributes(array('id' => $facture['items'][$i]['id'],'idFacture' => $facture['id']));
				$LigneFacture->idFacture = $Facture->id;
				$LigneFacture->nom = $facture['items'][$i]['text'];
				$LigneFacture->montant = str_replace(' ', '', $facture['items'][$i]['prix']);
				$LigneFacture->subLignes = serialize($facture['items'][$i]['subs']);
				$LigneFacture->save();
			}
			
			require_once(Yii::app()->basePath.'/extensions/html2pdf/html2pdf.class.php');
			$html2pdf = new HTML2PDF('P','A4','fr');
			$html2pdf->WriteHTML($this->getPdfContentNoteKindy($facture));
			@unlink($pdf_file);
			$html2pdf->Output($pdf_file,'F');
			
			$this->fillWordNoteKindy($facture,$word_file);
			
			$fac = new \Facture('search');
			\Yii::app()->clientScript->registerScriptFile( Yii::app()->baseUrl.'/businessCore/views/ap/js/campaign.js' );
			$this->redirect(array('Facture/liste'));
		}
		if(Yii::app()->request->getParam('ok') && $type=='IDM'){
			$facture = Yii::app()->request->getParam('facture');
			
			\Controller::loadConfigForPorteur('rinalda');
			
			$Facture = new Facture();
			$Facture = $Facture->findByAttributes(array('id' => $facture['id']));
			
			$date = explode(' ',$facture['date']);
			$Facture->date = $date[2].'-'.(array_search($date[1], $months)+1).'-'.$date[0];
			$Facture->nom_client = $facture['client-name'];
			$Facture->adresse_client = $facture['client-adresse'];
			$Facture->nom_compte = $facture['compte'];
			$Facture->banque = $facture['banque'];
			$Facture->adresse_banque = $facture['banque-adresse'];
			$Facture->ref = $facture['ref'];
			$Facture->IBAN = $facture['IBAN'];
			$Facture->swift = $facture['Swift'];
			$Facture->save();
			
			$pdf_file = $Facture->pdf;
			$word_file = $Facture->docx;
			
			
			for($i=1;$i<=count($facture['items']);$i++){
				$LigneFacture = new LigneFacture();
				$LigneFacture = $LigneFacture->findByAttributes(array('id' => $facture['items'][$i]['id'],'idFacture' => $facture['id']));
				$LigneFacture->idFacture = $Facture->id;
				$LigneFacture->nom = $facture['items'][$i]['text'];
				$LigneFacture->montant = str_replace(' ', '', $facture['items'][$i]['prix']);
				$LigneFacture->save();
			}
			
			require_once(Yii::app()->basePath.'/extensions/html2pdf/html2pdf.class.php');
			$html2pdf = new HTML2PDF('P','A4','fr');
			$html2pdf->WriteHTML($this->getPdfContentIDM($facture));
			@unlink($pdf_file);
			$html2pdf->Output($pdf_file,'F');
			
			$this->fillWordIDM($facture,$word_file);
			
			$fac = new \Facture('search');
			\Yii::app()->clientScript->registerScriptFile( Yii::app()->baseUrl.'/businessCore/views/ap/js/campaign.js' );
			$this->redirect(array('Facture/liste'));
		}
		if(Yii::app()->request->getParam('ok') && $type=='note_credit_idm'){
			$facture = Yii::app()->request->getParam('facture');
			
			\Controller::loadConfigForPorteur('rinalda');
			
			$Facture = new Facture();
			$Facture = $Facture->findByAttributes(array('id' => $facture['id']));
			//print_r($Facture);die;
			$date = explode(' ',$facture['date']);
			$Facture->date = $date[2].'-'.(array_search($date[1], $months)+1).'-'.$date[0];
			$Facture->nom_client = $facture['client-name'];
			$Facture->adresse_client = $facture['client-adresse'];
			$Facture->nom_compte = $facture['compte'];
			$Facture->banque = $facture['banque'];
			$Facture->adresse_banque = $facture['banque-adresse'];
			$Facture->ref = $facture['ref'];
			$Facture->IBAN = $facture['IBAN'];
			$Facture->swift = $facture['Swift'];
			$Facture->save();
			
			$pdf_file = $Facture->pdf;
			$word_file = $Facture->docx;
			
			
			for($i=1;$i<=count($facture['items']);$i++){
				$LigneFacture = new LigneFacture();
				$LigneFacture = $LigneFacture->findByAttributes(array('id' => $facture['items'][$i]['id'],'idFacture' => $facture['id']));
				$LigneFacture->idFacture = $Facture->id;
				$LigneFacture->nom = $facture['items'][$i]['text'];
				$LigneFacture->montant = str_replace(' ', '', $facture['items'][$i]['prix']);
				$LigneFacture->save();
			}
			
			require_once(Yii::app()->basePath.'/extensions/html2pdf/html2pdf.class.php');
			$html2pdf = new HTML2PDF('P','A4','fr');
			$html2pdf->WriteHTML($this->getPdfContentIDMNote($facture));
			@unlink($pdf_file);
			$html2pdf->Output($pdf_file,'F');
			
			$this->fillWordIDMNote($facture,$word_file);
			
			$fac = new \Facture('search');
			\Yii::app()->clientScript->registerScriptFile( Yii::app()->baseUrl.'/businessCore/views/ap/js/campaign.js' );
			$this->redirect(array('Facture/liste'));
		}
		Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/font-awesome/css/font-awesome.min.css');
		Yii::app()->clientScript->registerScriptFile( Yii::app()->baseUrl.'/businessCore/views/js/datepicker-fr.js' );
    	$this->render( '//facture/update', array( 'fac' => $fac, 'lfac' => $lfac, 'count' => (count($lfac)+1),'type'=> $type) );
		
    }
	
	public function actionAdd() {
		$type = Yii::app()->request->getParam('type');
		$months = ["Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre"];
		$today =  date("d").' '.$months[date("m")-1].' '.date("Y");
		
		\Controller::loadConfigForPorteur('rinalda');
		$criteria = new CDbCriteria;
		$criteria->order = 'id DESC';
		$row = Facture::model()->find($criteria);
		if($row == null)
		{$maxid = '1.'.substr(date("Y"), -2);}
		else
		{$maxid = ($row->id+1).'.'.substr(date("Y"), -2);}
		
		
		if(Yii::app()->request->getParam('ok') && $type=="kindy"){
			$facture = Yii::app()->request->getParam('facture');
			if (!is_dir(TMP_DIR . '/Facture'))
			{mkdir(TMP_DIR . '/Facture', 0777);}
			
			$pdf_file = TMP_DIR . '/Facture/Facture_'.$facture['ref'].'.pdf';
			$word_file = TMP_DIR . '/Facture/Facture_'.$facture['ref'].'.docx';
			
			$Facture = new Facture();
			
			$date = explode(' ',$facture['date']);
			$Facture->date = $date[2].'-'.(array_search($date[1], $months)+1).'-'.$date[0];
			$Facture->titre = $facture['titre'];
			$Facture->type = $type;
			$Facture->nom_client = $facture['client-name'];
			$Facture->adresse_client = $facture['client-adresse'];
			$Facture->nom_compte = $facture['compte'];
			$Facture->banque = $facture['banque'];
			$Facture->adresse_banque = $facture['banque-adresse'];
			$Facture->IBAN = $facture['IBAN'];
			$Facture->swift = $facture['Swift'];
			$Facture->ref = $facture['ref'];
			$Facture->docx = $word_file;
			$Facture->pdf = $pdf_file;
			$Facture->save();
			
			
			for($i=1;$i<=count($facture['items']);$i++){
				$LigneFacture = new LigneFacture();
				$LigneFacture->idFacture = $Facture->id;
				$LigneFacture->nom = $facture['items'][$i]['text'];
				$LigneFacture->montant = str_replace(' ', '', $facture['items'][$i]['prix']);
				$LigneFacture->subLignes = serialize($facture['items'][$i]['subs']);
				$LigneFacture->save();
			}
			
			
			require_once(Yii::app()->basePath.'/extensions/html2pdf/html2pdf.class.php');
			$html2pdf = new HTML2PDF('P','A4','fr');
			$html2pdf->WriteHTML($this->getPdfContent($facture));
			$html2pdf->Output($pdf_file,'F');
			
			$this->fillWord($facture,$word_file);
			
			$temp_file = $pdf_file;
			
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream; charset=utf-8');
			header("Content-Disposition: attachment; filename=Facture_".$facture['ref'].".pdf");
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			header('Content-Length: ' . filesize($temp_file));
			readfile($temp_file);
		}
		if(Yii::app()->request->getParam('ok') && $type=="note_credit_kindy"){
			$facture = Yii::app()->request->getParam('facture');
			if (!is_dir(TMP_DIR . '/Facture'))
			{mkdir(TMP_DIR . '/Facture', 0777);}
			
			$pdf_file = TMP_DIR . '/Facture/Facture_'.$facture['ref'].'.pdf';
			$word_file = TMP_DIR . '/Facture/Facture_'.$facture['ref'].'.docx';
			
			$Facture = new Facture();
			
			$date = explode(' ',$facture['date']);
			$Facture->date = $date[2].'-'.(array_search($date[1], $months)+1).'-'.$date[0];
			$Facture->titre = $facture['titre'];
			$Facture->type = 'note_credit_kindy';
			$Facture->nom_client = $facture['client-name'];
			$Facture->adresse_client = $facture['client-adresse'];
			$Facture->nom_compte = $facture['compte'];
			$Facture->banque = $facture['banque'];
			$Facture->adresse_banque = $facture['banque-adresse'];
			$Facture->IBAN = $facture['IBAN'];
			$Facture->swift = $facture['Swift'];
			$Facture->ref = $facture['ref'];
			$Facture->docx = $word_file;
			$Facture->pdf = $pdf_file;
			$Facture->save();
			
			
			for($i=1;$i<=count($facture['items']);$i++){
				$LigneFacture = new LigneFacture();
				$LigneFacture->idFacture = $Facture->id;
				$LigneFacture->nom = $facture['items'][$i]['text'];
				$LigneFacture->montant = str_replace(' ', '', $facture['items'][$i]['prix']);
				$LigneFacture->subLignes = serialize($facture['items'][$i]['subs']);
				$LigneFacture->save();
			}
			
			
			require_once(Yii::app()->basePath.'/extensions/html2pdf/html2pdf.class.php');
			$html2pdf = new HTML2PDF('P','A4','fr');
			$html2pdf->WriteHTML($this->getPdfContentNoteKindy($facture));
			$html2pdf->Output($pdf_file,'F');
			
			$this->fillWordNoteKindy($facture,$word_file);
			
			$temp_file = $pdf_file;
			
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream; charset=utf-8');
			header("Content-Disposition: attachment; filename=Facture_".$facture['ref'].".pdf");
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			header('Content-Length: ' . filesize($temp_file));
			readfile($temp_file);
		}
		if(Yii::app()->request->getParam('ok') && $type=="IDM"){
			$facture = Yii::app()->request->getParam('facture');
			if (!is_dir(TMP_DIR . '/Facture'))
			{mkdir(TMP_DIR . '/Facture', 0777);}
			
			$pdf_file = TMP_DIR . '/Facture/Facture_'.$facture['ref'].'.pdf';
			$word_file = TMP_DIR . '/Facture/Facture_'.$facture['ref'].'.docx';
			
			$Facture = new Facture();
			
			$date = explode(' ',$facture['date']);
			$Facture->date = $date[2].'-'.(array_search($date[1], $months)+1).'-'.$date[0];
			$Facture->type = $type;
			$Facture->nom_client = $facture['client-name'];
			$Facture->titre = ' ';
			$Facture->adresse_client = $facture['client-adresse'];
			$Facture->nom_compte = $facture['compte'];
			$Facture->banque = $facture['banque'];
			$Facture->adresse_banque = $facture['banque-adresse'];
			$Facture->IBAN = $facture['IBAN'];
			$Facture->swift = $facture['Swift'];
			$Facture->ref = $facture['ref'];
			$Facture->docx = $word_file;
			$Facture->pdf = $pdf_file;
			$Facture->save();
			
			
			for($i=1;$i<=count($facture['items']);$i++){
				$LigneFacture = new LigneFacture();
				$LigneFacture->idFacture = $Facture->id;
				$LigneFacture->nom = $facture['items'][$i]['text'];
				$LigneFacture->montant = str_replace(' ', '', $facture['items'][$i]['prix']);
				$LigneFacture->save();
			}
			require_once(Yii::app()->basePath.'/extensions/html2pdf/html2pdf.class.php');
			$html2pdf = new HTML2PDF('P','A4','fr');
			$html2pdf->WriteHTML($this->getPdfContentIDM($facture));
			$html2pdf->Output($pdf_file,'F');
			
			$this->fillWordIDM($facture,$word_file);
			
			$temp_file = $pdf_file;
			
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream; charset=utf-8');
			header("Content-Disposition: attachment; filename=Facture_".$facture['ref'].".pdf");
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			header('Content-Length: ' . filesize($temp_file));
			readfile($temp_file);
		}
		if(Yii::app()->request->getParam('ok') && $type=="note_credit_idm"){
			$facture = Yii::app()->request->getParam('facture');
			if (!is_dir(TMP_DIR . '/Facture'))
			{mkdir(TMP_DIR . '/Facture', 0777);}
			
			$pdf_file = TMP_DIR . '/Facture/Facture_'.$facture['ref'].'.pdf';
			$word_file = TMP_DIR . '/Facture/Facture_'.$facture['ref'].'.docx';
			
			$Facture = new Facture();
			
			$date = explode(' ',$facture['date']);
			$Facture->date = $date[2].'-'.(array_search($date[1], $months)+1).'-'.$date[0];
			$Facture->type = 'note_credit_idm';
			$Facture->nom_client = $facture['client-name'];
			$Facture->titre = ' ';
			$Facture->adresse_client = $facture['client-adresse'];
			$Facture->nom_compte = $facture['compte'];
			$Facture->banque = $facture['banque'];
			$Facture->adresse_banque = $facture['banque-adresse'];
			$Facture->IBAN = $facture['IBAN'];
			$Facture->swift = $facture['Swift'];
			$Facture->ref = $facture['ref'];
			$Facture->docx = $word_file;
			$Facture->pdf = $pdf_file;
			$Facture->save();
			
			
			for($i=1;$i<=count($facture['items']);$i++){
				$LigneFacture = new LigneFacture();
				$LigneFacture->idFacture = $Facture->id;
				$LigneFacture->nom = $facture['items'][$i]['text'];
				$LigneFacture->montant = str_replace(' ', '', $facture['items'][$i]['prix']);
				$LigneFacture->save();
			}
			require_once(Yii::app()->basePath.'/extensions/html2pdf/html2pdf.class.php');
			$html2pdf = new HTML2PDF('P','A4','fr');
			$html2pdf->WriteHTML($this->getPdfContentIDMNote($facture));
			$html2pdf->Output($pdf_file,'F');
			
			$this->fillWordIDMNote($facture,$word_file);
			
			$temp_file = $pdf_file;
			
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream; charset=utf-8');
			header("Content-Disposition: attachment; filename=Facture_".$facture['ref'].".pdf");
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			header('Content-Length: ' . filesize($temp_file));
			readfile($temp_file);
		}
		Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/font-awesome/css/font-awesome.min.css');
		Yii::app()->clientScript->registerScriptFile( Yii::app()->baseUrl.'/businessCore/views/js/datepicker-fr.js' );
		$this->render( '//achat/form', array( 'baseUrl'=> Yii::app()->baseUrl,'today' => $today,'maxid'=>$maxid, 'type'=> $type) );
    }
	
	
	
	private function getPdfContent($Achat) {
		$content = '
			<page>
				<page_header>
					<img src="'.Yii::app()->basePath.'/../images/header_kindy.jpg" alt="image_php" />
				</page_header>
				<br /><br /><br /><br />
				<table>
					<tr>
						<td width="341">
							<p style="text-align: center; margin-left: 200px; margin-bottom: 50px;"></p>
						</td>
						<td >							<p style="margin-left: 100px; font-weight: bold; text-align: center; margin-bottom: 0px; font-size: 14pt;">
								DEMANDE D&acute;ACHAT     N°'.$Achat['id'].'<br /><br />
								DATE '.$Achat['date'].'
							</p>
						</td>
					</tr>
					
					
				</table>
				<br /><br />
				<table border="1" align="center" style="border-collapse: collapse;" >
					<thead>
						<tr>
							<td style="text-align: center; font-weight: bold;">
								Service demandeur
							</td>
							<td style="text-align: center; font-weight: bold;">
								Service utilisateur
							</td>
							<td style="text-align: center; font-weight: bold;" >
							
							</td>
							<td style="text-align: center; font-weight: bold;">
							
							</td>
							<td style="text-align: center; font-weight: bold;">
							
							</td>
							<td style="text-align: center; font-weight: bold;">
							
							</td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td style="text-align: center;" valign="bottom" >
								<p>
									 '.$Achat['service_demandeur'].'
								</p>
							</td>
							<td style="text-align: center;" valign="bottom" >
								<p>
									 '.$Achat['service_acheteur'].'
								</p>
							</td>

							<td style="text-align: center;" valign="bottom" >
								
							</td>
							<td style="text-align: center;" valign="bottom" >
								
							</td>
							<td style="text-align: center;" valign="bottom">
								
							</td>
							<td style="text-align: center;" valign="bottom" >
								
							</td>
							
						</tr>
						<tr>
							<td style="text-align: center;" valign="bottom" >
								<p>
									<strong>CODE<br>ARTICLE</strong>
								</p>
							</td>
							<td style="text-align: center;" valign="bottom" >
								<p>
									<strong>DESIGNATION</strong>
								</p>
							</td>

							<td style="text-align: center;" valign="bottom" >
								<p>
									<strong>QTE</strong>
								</p>
							</td>
							<td style="text-align: center;" valign="bottom" >
								<p>
									<strong>Delai de Réception<br>souhaité</strong>
								</p>
							</td>
							<td style="text-align: center;" valign="bottom">
								<p>
									<strong>FOURNISSEUR<br>SUGGERE</strong>
								</p>
							</td>
							<td style="text-align: center;" valign="bottom" >
								<p>
									<strong>AUTRES</strong>
								</p>
							</td>
							
						</tr>';




                        $ach    = $Achat->id;
						
		              $lach = \LigneAchat::model()->searchByAchat($ach);
		
		                $res    = $lach;
		
	 	             if(!empty($res->data)){	
			
				
				foreach($res->data as $lach)
				{
					$id= $lach->id;
					$article=$lach->article;
					$demandeur=$lach->demandeur;
					$motifss=$lach->motif;
					$motif=$lach->fournisseur;
					$reference= $lach->reference;
					$code=$lach->code;
					$quantite=$lach->quantite;
					$statut=$lach->statut;
					$idAchat=$lach->idAchat;
					$motifs=wordwrap($motifss, 10, "\n", true);
					$designations=wordwrap($article, 15, "\n", true);

			$content .= '<tr >
							<td style="text-align: center;" valign="center" >
								<p>
									'.$lach->code.'
									</p>
									</td>
									<td style="text-align: center;width:10%;" valign="center" >
								<p style="max-width:30px;">
										'."$designations\n".' 
									</p>
									</td>
									<td style="text-align: center;" valign="center" >
								<p>
									'.$lach->quantite.'
									</p>
									</td>
									<td style="text-align: center;" valign="center" >
								<p>
									'.$Achat['date_livraison'].'
									</p>
									</td>
									<td style="text-align: center;" valign="center" >
								<p>
									'.$lach->fournisseur.'
									</p>
									</td>
									<td style="text-align: center;width:15%;" valign="center" >
								<p style="max-width:40px;">
									'."$motifs\n".' 
									</p>
									</td>';
			
			$content .= '</tr>';}}
			
			
		$content .= '</tbody>
					
				</table>




				<br /><br />
				<table border="1" align="center" style="border-collapse: collapse;" >
					<thead>
						<tr>
							<td style="text-align: center; font-weight: bold;width:250px;">
								DEMANDEUR
							</td>
							<td style="text-align: center; font-weight: bold;width:250px;">
								Approbation DG
							</td>
							
						</tr>
					</thead>
					<tbody>

					<tr>
							<td style="text-align: center; font-weight: bold;width:250px;">
								&nbsp;<br><br><br>&nbsp;
							</td>
							<td style="text-align: center; font-weight: bold;width:250px;">
								&nbsp;<br><br><br>&nbsp;
							</td>
							
						</tr>
					</tbody>
					</table>





				
				
				<page_footer>
					<p style="text-align: center;"> 
						<hr style="color:#E17006;" />
						Kindy Info Maroc – 3 Rue Alfred Musset Casablanca – Tél: 05 22 20 32 79
						<br />
						IF: 402236541 – N° Patente: 36393327 - RC: 226845
						<br />
						Site Web: http://www.kindyinfomaroc.com/
					</p>
				</page_footer>
			</page>';
		return $content;
    }
	
	private function getPdfContentIDM($facture) {
		$content = '
			<page>
				<page_header>
					<img src="'.Yii::app()->basePath.'/../images/header_IDM.jpg" alt="image_php" />
				</page_header>
				<br /><br /><br /><br />
				<table>
					<tr>
						<td width="341">
							<p style="text-align: center; margin-left: 200px; margin-bottom: 50px;"></p>
						</td>
						<td >
							<p style="margin-left: 50px; font-weight: bold; text-align: right; margin-bottom: 0px; font-size: 14pt;">
								'.$facture['client-name'].'<br /><br />
								'.str_replace("\n",'<br />',$facture['client-adresse']).'
							</p>
						</td>
					</tr>
					<tr>
						<td>
							<p style="margin-left: 100px; font-weight: bold; text-align: left; font-size: 14pt;">
								Date: '.$facture['date'].'
							</p>
						</td>
						<td >
							<p style="text-align: center; margin-left: 200px; margin-bottom: 50px;"></p>
						</td>
					</tr>
					<tr>
						<td>
							<p style="text-align: center; margin-left: 200px; margin-top: 60px; margin-bottom: 50px; font-size: 14pt;">
								FACTURE '.$facture['ref'].'
							</p>
						</td>
						<td >
							<p style="margin-left: 100px; text-align: center; margin-bottom: 0px;"></p>
						</td>
					</tr>
				</table>
				<table border="1" align="center" style="border-collapse: collapse;" width="650">
					<thead>
						<tr>
							<th style="text-align: center; font-weight: bold;" width="390">
								Libell&eacute;
							</th>
							<th style="text-align: center; font-weight: bold;" width="242">
								Montant En Euros
							</th>
						</tr>
					</thead>
					<tbody>';
		for($i=1;$i<=count($facture['items']);$i++){
			$content .= '<tr >
							<td style="padding-left: 50px;" valign="bottom" width="390">
								<p>
									<strong style="font-size: 14pt;">'.nl2br($facture['items'][$i]['text']).'</strong>
								</p>
							</td>';
			$prix = str_replace(' ', '', $facture['items'][$i]['prix']);
			$prix = round($prix,2);
			$prix = number_format($prix,2,'.',' ');
			$content .= '<td style="text-align: center;" width="242">
								<p>
									<strong>
										'.$prix.'
									</strong>
								</p>
							</td></tr>';
		}
		$total = str_replace(' ', '', $facture['total']);
		$total = round($total,2);
		$total = number_format($total,2,'.',' ');
		$content .= '</tbody>
				</table>
				<br />Arr&ecirc;t&eacute; la pr&eacute;sente facture au montant de: <b>'.$facture['total_text'].'</b>.<br /><br /><br />
				<table>
					<tr>
						<td style="width: 130px;">
							<b>Nom de compte:</b><br />
							<b>Banque :</b><br />
							<b>Adresse banque :</b><br />
							<b>IBAN :</b><br />
							<b>Swift :</b><br />
						</td>
						<td>
							'.$facture['compte'].'<br />
							'.$facture['banque'].'<br />
							'.$facture['banque-adresse'].'<br />
							'.$facture['IBAN'].'<br />
							'.$facture['Swift'].'<br />
						</td>
					</tr>
				</table>
				<page_footer>
					<p style="text-align: left; color:#365F91;"> 
						INFO&DATA MEDIA – Rue Soumaya Résidence Chahrazade III 5 eme étage N°22 Palmiers<br />  
						Casablanca – Tél: 05 22 26 26 98<br />
						IF 14484837 : – N° patente : 34770357 - RC : 2930<br />
						Site Web: http://www.infodata-media.com/
					</p>
				</page_footer>
			</page>';
		return $content;
    }
	private function getPdfContentIDMNote($facture) {
		$content = '
			<page>
				<page_header>
					<img src="'.Yii::app()->basePath.'/../images/header_IDM.jpg" alt="image_php" />
				</page_header>
				<br /><br /><br /><br />
				<table>
					<tr>
						<td width="341">
							<p style="text-align: center; margin-left: 200px; margin-bottom: 50px;"></p>
						</td>
						<td >
							<p style="margin-left: 50px; font-weight: bold; text-align: right; margin-bottom: 0px; font-size: 14pt;">
								'.$facture['client-name'].'<br /><br />
								'.str_replace("\n",'<br />',$facture['client-adresse']).'
							</p>
						</td>
					</tr>
					<tr>
						<td>
							<p style="margin-left: 100px; font-weight: bold; text-align: left; font-size: 14pt;">
								Date: '.$facture['date'].'
							</p>
						</td>
						<td >
							<p style="text-align: center; margin-left: 200px; margin-bottom: 50px;"></p>
						</td>
					</tr>
					<tr>
						<td>
							<p style="text-align: center; margin-left: 200px; margin-top: 60px; margin-bottom: 50px; font-size: 14pt;">
								Note de crédit '.$facture['ref'].'
							</p>
						</td>
						<td >
							<p style="margin-left: 100px; text-align: center; margin-bottom: 0px;"></p>
						</td>
					</tr>
				</table>
				<table border="1" align="center" style="border-collapse: collapse;" width="650">
					<thead>
						<tr>
							<th style="text-align: center; font-weight: bold;" width="390">
								Libell&eacute;
							</th>
							<th style="text-align: center; font-weight: bold;" width="242">
								Montant En Euros
							</th>
						</tr>
					</thead>
					<tbody>';
		for($i=1;$i<=count($facture['items']);$i++){
			$content .= '<tr >
							<td style="padding-left: 50px;" valign="bottom" width="390">
								<p>
									<strong style="font-size: 14pt;">'.nl2br($facture['items'][$i]['text']).'</strong>
								</p>
							</td>';
			$prix = str_replace(' ', '', $facture['items'][$i]['prix']);
			$prix = round($prix,2);
			$prix = number_format($prix,2,'.',' ');
			$content .= '<td style="text-align: center;" width="242">
								<p>
									<strong>
										- '.$prix.'
									</strong>
								</p>
							</td></tr>';
		}
		$total = str_replace(' ', '', $facture['total']);
		$total = round($total,2);
		$total = number_format($total,2,'.',' ');
		$content .= '</tbody>
				</table>
				<br />Arr&ecirc;t&eacute; la pr&eacute;sente note de crédit au montant de: <b>'.$facture['total_text'].'</b>.<br /><br /><br />
				<table>
					<tr>
						<td style="width: 130px;">
							<b>Nom de compte:</b><br />
							<b>Banque :</b><br />
							<b>Adresse banque :</b><br />
							<b>IBAN :</b><br />
							<b>Swift :</b><br />
						</td>
						<td>
							'.$facture['compte'].'<br />
							'.$facture['banque'].'<br />
							'.$facture['banque-adresse'].'<br />
							'.$facture['IBAN'].'<br />
							'.$facture['Swift'].'<br />
						</td>
					</tr>
				</table>
				<page_footer>
					<p style="text-align: left; color:#365F91;"> 
						INFO&DATA MEDIA – Rue Soumaya Résidence Chahrazade III 5 eme étage N°22 Palmiers<br />  
						Casablanca – Tél: 05 22 26 26 98<br />
						IF 14484837 : – N° patente : 34770357 - RC : 2930<br />
						Site Web: http://www.infodata-media.com/
					</p>
				</page_footer>
			</page>';
		return $content;
    }
	
	private function fillWord($facture,$word_file) {
		spl_autoload_unregister(array('YiiBase', 'autoload'));
		try {
			Yii::import('ext.PHPWord', true);
		} catch (Exception $e) {
			spl_autoload_register(array('YiiBase', 'autoload'));
			throw $e;
		}
		
		
		// New Word Document
		$PHPWord = new PHPWord();
		spl_autoload_register(array('YiiBase', 'autoload'));
		
		$document = $PHPWord->loadTemplate(\Yii::app()->basePath . '/vendors/template_facture.docx');
		
		$document->setValue('fact_id', $facture['ref']);
		$document->setValue('client', $facture['client-name']);
		$document->setValue('date', $facture['date']);
		$document->setValue('mois', $facture['titre']);
		$document->setValue('adr_client', str_replace("\n",'<w:br/>',$facture['client-adresse']));
		$document->cloneRow('prix', count($facture['items']));
		for($i=1;$i<=count($facture['items']);$i++){
			$prix = str_replace(' ', '', $facture['items'][$i]['prix']);
			$prix = round($prix,2);
			$prix = number_format($prix,2,'.',' ');
			$document->setValue('prix#'.$i, $prix);
			$document->setValue('titre#'.$i, $facture['items'][$i]['text']);
			$det = '';
			foreach($facture['items'][$i]['subs'] AS $sub){
				$det .= '• '.$sub.'<w:br/>';
			}
			$document->setValue('detail#'.$i, $det);
		}
		$total = str_replace(' ', '', $facture['total']);
		$total = round($total,2);
		$total = number_format($total,2,'.',' ');
		$document->setValue('total', $total);
		$document->setValue('total_text', $facture['total_text']);
		
		$document->setValue('compte', $facture['compte']);
		$document->setValue('bank', $facture['banque']);
		$document->setValue('adr_bank', $facture['banque-adresse']);
		$document->setValue('IBAN',  $facture['IBAN']);
		$document->setValue('swift', $facture['Swift']);
		
		@unlink($word_file);
		$document->save($word_file);
    }

	private function fillWordNoteKindy($facture,$word_file) {
		spl_autoload_unregister(array('YiiBase', 'autoload'));
		try {
			Yii::import('ext.PHPWord', true);
		} catch (Exception $e) {
			spl_autoload_register(array('YiiBase', 'autoload'));
			throw $e;
		}
		
		
		// New Word Document
		$PHPWord = new PHPWord();
		spl_autoload_register(array('YiiBase', 'autoload'));
		
		$document = $PHPWord->loadTemplate(\Yii::app()->basePath . '/vendors/template_note.docx');
		
		$document->setValue('fact_id', $facture['ref']);
		$document->setValue('client', $facture['client-name']);
		$document->setValue('date', $facture['date']);
		$document->setValue('mois', $facture['titre']);
		$document->setValue('adr_client', str_replace("\n",'<w:br/>',$facture['client-adresse']));
		$document->cloneRow('prix', count($facture['items']));
		for($i=1;$i<=count($facture['items']);$i++){
			$prix = str_replace(' ', '', $facture['items'][$i]['prix']);
			$prix = round($prix,2);
			$prix = number_format($prix,2,'.',' ');
			$document->setValue('prix#'.$i, $prix);
			$document->setValue('titre#'.$i, $facture['items'][$i]['text']);
			$det = '';
			foreach($facture['items'][$i]['subs'] AS $sub){
				$det .= '• '.$sub.'<w:br/>';
			}
			$document->setValue('detail#'.$i, $det);
		}
		$total = str_replace(' ', '', $facture['total']);
		$total = round($total,2);
		$total = number_format($total,2,'.',' ');
		$document->setValue('total', $total);
		$document->setValue('total_text', $facture['total_text']);
		
		$document->setValue('compte', $facture['compte']);
		$document->setValue('bank', $facture['banque']);
		$document->setValue('adr_bank', $facture['banque-adresse']);
		$document->setValue('IBAN',  $facture['IBAN']);
		$document->setValue('swift', $facture['Swift']);
		
		@unlink($word_file);
		$document->save($word_file);
    }
	
	private function fillWordIDM($facture,$word_file) {
		spl_autoload_unregister(array('YiiBase', 'autoload'));
		try {
			Yii::import('ext.PHPWord', true);
		} catch (Exception $e) {
			spl_autoload_register(array('YiiBase', 'autoload'));
			throw $e;
		}
		
		
		// New Word Document
		$PHPWord = new PHPWord();
		spl_autoload_register(array('YiiBase', 'autoload'));
		
		$document = $PHPWord->loadTemplate(\Yii::app()->basePath . '/vendors/template_facture_idm.docx');
		
		$document->setValue('fact_id', $facture['ref']);
		$document->setValue('client', $facture['client-name']);
		$document->setValue('date', $facture['date']);
		$document->setValue('adr_client', str_replace("\n",'<w:br/>',$facture['client-adresse']));
		$document->cloneRow('prix', count($facture['items']));
		for($i=1;$i<=count($facture['items']);$i++){
			$prix = str_replace(' ', '', $facture['items'][$i]['prix']);
			$prix = round($prix,2);
			$prix = number_format($prix,2,'.',' ');
			$document->setValue('prix#'.$i, $prix);
			$document->setValue('titre#'.$i, $facture['items'][$i]['text']);
		}
		$document->setValue('total_text', $facture['total_text']);
		
		$document->setValue('compte', htmlspecialchars($facture['compte']));
		$document->setValue('bank', $facture['banque']);
		$document->setValue('adr_bank', $facture['banque-adresse']);
		$document->setValue('IBAN',  $facture['IBAN']);
		$document->setValue('swift', $facture['Swift']);
		
		@unlink($word_file);
		$document->save($word_file);
    }
	
	private function fillWordIDMNote($facture,$word_file) {
		spl_autoload_unregister(array('YiiBase', 'autoload'));
		try {
			Yii::import('ext.PHPWord', true);
		} catch (Exception $e) {
			spl_autoload_register(array('YiiBase', 'autoload'));
			throw $e;
		}
		
		
		// New Word Document
		$PHPWord = new PHPWord();
		spl_autoload_register(array('YiiBase', 'autoload'));
		
		$document = $PHPWord->loadTemplate(\Yii::app()->basePath . '/vendors/template_note_idm.docx');
		
		$document->setValue('fact_id', $facture['ref']);
		$document->setValue('client', $facture['client-name']);
		$document->setValue('date', $facture['date']);
		$document->setValue('adr_client', str_replace("\n",'<w:br/>',$facture['client-adresse']));
		$document->cloneRow('prix', count($facture['items']));
		for($i=1;$i<=count($facture['items']);$i++){
			$prix = str_replace(' ', '', $facture['items'][$i]['prix']);
			$prix = round($prix,2);
			$prix = number_format($prix,2,'.',' ');
			$document->setValue('prix#'.$i, $prix);
			$document->setValue('titre#'.$i, $facture['items'][$i]['text']);
		}
		$document->setValue('total_text', $facture['total_text']);
		
		$document->setValue('compte', htmlspecialchars($facture['compte']));
		$document->setValue('bank', $facture['banque']);
		$document->setValue('adr_bank', $facture['banque-adresse']);
		$document->setValue('IBAN',  $facture['IBAN']);
		$document->setValue('swift', $facture['Swift']);
		
		@unlink($word_file);
		$document->save($word_file);
    }
}

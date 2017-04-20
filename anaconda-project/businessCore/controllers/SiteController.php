<?php
/**
 * Description of SiteController
 *
 * @author JulienL
 * @package Controllers
 */
class SiteController extends Controller
{
	/**
	 * Contexte
	 * @var \Business\ContextSite
	 */
	protected $Context	= false;

	/**
	 * Initialise le controller generique des site
	 * Instancie l'Objet Context
	 * @throws EsoterException	Si l'instanciation du Context a posÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â© probleme
	 */
	public function init()
	{
		parent::init();

		// Defini le dossier dans lequel sont les vues :
		\Yii::app()->setViewPath( $this->portViewDir(true) );

		// Defini la langue :
		\Yii::app()->setLanguage( \Yii::app()->params['lang'] );
		// Defini le dossier contenant les traductions : :
		\Yii::app()->messages->basePath = $this->portViewDir(true).'messages';

		// Insertion de JQuery :
		$this->includeJQuerySCript( true );

		// Layout du porteur :
		$this->layout		= '//'.\Yii::app()->params['porteur'].'/porteur';

		// Chargement du context :
		$this->Context = new \Business\ContextSite();

		if( ($res = $this->Context->loadContext()) !== true )
		{throw new EsoterException( 10, \Yii::t( 'error', 10 ).'<br />Param GET : '.implode( ', ', $_GET ).'<br>Param POST : '.implode( ', ', $_POST ) );}
	}

	public function getBdcForm()
	{
		\Yii::import( 'ext.Shared' );

		$Bdc	= new Bdc( $this->getSite()->id, $this->getCurrentPrice() , \Shared::getDateBirthAfter($this->getUser()->birthday, 20));
		$Form	= new BdcForm( $Bdc );

		// Si un BDC est soumis et validÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â© :
		if( $Form->submitted('validate') && $Form->validate() )
		{
			// Creation de la commande :
			$Invoice	= new \Business\Invoice();

// Restriction de paiement pour la grande voyance


			    // Campagne du produit :
				$porteur			= \Yii::app()->params['porteur'];
				$campName			= $this->getCampaign()->ref;
				$refProduct			= $this->getProduct()->ref;

$type_paiement = $Bdc->getPaymentProcessorType()->type;
$date_systeme = date('m/d/Y');


				$idpr = $this->getProduct()->id;

				$expirationproduit=(int)$this->getProduct()->produit_expirable;

if($expirationproduit == 1 )
				{

				$var1 = \Business\Restriction_paiement::loadpp($idpr,$type_paiement);







				if(!empty($var1))
			{


if(strtotime($date_systeme) >= strtotime($var1[0]->date_fin))
					{


						// Titre de la page :
						$this->pageTitle	= "Produit expirÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©";



					// Recherche de la vue a utiliser :

	if( $this->isView( '//'.$porteur.'/'.$campName.'/'.$refProduct.'/restriction_GrandeVoyance' ) )
	{$view	= '//'.$porteur.'/'.$campName.'/'.$refProduct.'/restriction_GrandeVoyance';}
						else if( $this->isView( '//'.$porteur.'/'.$campName.'/restriction_GrandeVoyance' ) )
						{$view	= '//'.$porteur.'/'.$campName.'/restriction_GrandeVoyance';}
						else
						{$view	= '//'.$porteur.'/restriction_GrandeVoyance';}

						// Rendu de la page :
						$this->render( $view, array( 'Invoice' => $Invoice ) );
						Yii::app()->end();

			}}	}



			if($Invoice->InvoicePayedUser($this->getUser()->email, $this->getProduct()->ref )  > 0  && $this->getCurrentTR() < 1503)
			{

				// Campagne du produit :
				$porteur			= \Yii::app()->params['porteur'];
				$campName			= $this->getCampaign()->ref;
				$refProduct			= $this->getProduct()->ref;

				// Titre de la page :
				$this->pageTitle	= ucfirst( strtolower( substr(\Yii::app()->name, 0, strlen(\Yii::app()->name)-2) ) );

				// Recherche de la vue a utiliser :
				if( $this->isView( '//'.$porteur.'/'.$campName.'/'.$refProduct.'/restriction_paiement' ) )
				{$view	= '//'.$porteur.'/'.$campName.'/'.$refProduct.'/restriction_paiement';}
				else if( $this->isView( '//'.$porteur.'/'.$campName.'/restriction_paiement' ) )
				{$view	= '//'.$porteur.'/'.$campName.'/restriction_paiement';}
				else
				{$view	= '//'.$porteur.'/restriction_paiement';}

				// Rendu de la page :
				$this->render( $view, array( 'Invoice' => $Invoice ) );
				Yii::app()->end();

			}else{

				$Invoice->create( $this->getUser(), $this->getSubCampaign(), $this->getSite(), $Bdc );
				$Invoice->addRecord( $this->getProduct(), 1, $this->getPriceEngine(), $this->getSubCampaignReflation() );

				$AP			= $this->getCurrentAP();
				$Product	= \Business\Product::load($this->getProduct()->id);

				if( $Product->ctdate >0)
				{
				  
				  $Invoice->setAdditionnalValue('CTdate',json_decode(json_decode($Form->getStringCTdate())));
				  $Invoice->setAdditionnalValue('CTdate2',json_decode(json_decode($Form->getStringCTdate())));
				  $Invoice->setAdditionnalValue('CTdate3',json_decode(json_decode($Form->getStringCTdate())));

				}

				// Pour les traitements speciaux des produits :
				if( ($WebCampaign = \WebCampaign::getWebCampaign( $this->getSubCampaign(), $this->getUser() )) )
				{$WebCampaign->onSaveInvoice( $Invoice );}

				if( ($Res = $Invoice->finalize()) )
				{
					if( is_object($Res) && is_a($Res, '\Business\ConfigPaymentProcessor') )
					{$this->redirectToPaymentProcessor( $Res );}
					else
					{throw new EsoterException( 13, \Yii::t( 'error', 13 ) );}
				}
				else
				{throw new EsoterException( 11, \Yii::t( 'error', 11 ) );}
			}
		}

		return $Form;
	}
	/**
	 * @author [Hatem Tarek] <[<tarek.hatim@kindyinfomaroc.com>]>
	 * cette methode fait l'appel a la methode getDatelune dans la class DateHelper2 et retourne sa valeur
	 */

	public function returnDatesLune($numberOfDates,$sd,$codePays){

		\Yii::import('ext.DateHelper2');
		$this->updateInitialShootDateAnaconda();
		$codePays = strtoupper($codePays);
		return DateHelper2::getDatesLune($numberOfDates,$sd,$codePays);
	}

	public function getBdcFormPP()
	{
		\Yii::import( 'ext.Shared' );
		
		

		$Bdc	= new Bdc( $this->getSite()->id, $this->getCurrentPrice() , \Shared::getDateBirthAfter($this->getUser()->birthday, 20));
		$Form	= new BdcFormPP( $Bdc );
		


		// Si un BDC est soumis et validÃ© :
		if( $Form->submitted('validate') && $Form->validate() )
		{
			

			// Creation de la commande :
			$Invoice	= new \Business\Invoice();


			// Campagne du produit :
			$porteur			= \Yii::app()->params['porteur'];
			$campName			= $this->getCampaign()->ref;
			$refProduct			= $this->getProduct()->ref;

			// Restriction de paiement pour la grande voyance

			$type_paiement = $Bdc->getPaymentProcessorType()->type;
			$date_systeme = date('m/d/Y');


				$idpr = $this->getProduct()->id;

			$expirationproduit=(int)$this->getProduct()->produit_expirable;




			if($expirationproduit == 1 )
			{

			$var1 = \Business\Restriction_paiement::loadpp($idpr,$type_paiement);







				if(!empty($var1))
			{
		if(strtotime($date_systeme) >= strtotime($var1[0]->date_fin))
					{


						// Titre de la page :
						$this->pageTitle	= "Produit expirÃ©";



					// Recherche de la vue a utiliser :
					if( $this->isView( '//'.$porteur.'/'.$campName.'/'.$refProduct.'/restriction_GrandeVoyance' ) )
					{$view	= '//'.$porteur.'/'.$campName.'/'.$refProduct.'/restriction_GrandeVoyance';}
						else if( $this->isView( '//'.$porteur.'/'.$campName.'/restriction_GrandeVoyance' ) )
						{$view	= '//'.$porteur.'/'.$campName.'/restriction_GrandeVoyance';}
						else
						{$view	= '//'.$porteur.'/restriction_GrandeVoyance';}

						// Rendu de la page :
						$this->render( $view, array( 'Invoice' => $Invoice ) );
						Yii::app()->end();

			}
		}
		}

			/////// Fin de la Fonction de restriction de paiement
if($Invoice->InvoicePayedUser($this->getUser()->email, $this->getProduct()->ref )  > 0 && $this->getCurrentTR() < 1503)
			{
				// Campagne du produit :
				$porteur			= \Yii::app()->params['porteur'];
				$campName			= $this->getCampaign()->ref;
				$refProduct			= $this->getProduct()->ref;

				// Titre de la page :
				$this->pageTitle	= ucfirst( strtolower( \Yii::app()->name ) );

				// Recherche de la vue a utiliser :
				if( $this->isView( '//'.$porteur.'/'.$campName.'/'.$refProduct.'/restriction_paiement' ) )
				{$view	= '//'.$porteur.'/'.$campName.'/'.$refProduct.'/restriction_paiement';}
				else if( $this->isView( '//'.$porteur.'/'.$campName.'/restriction_paiement' ) )
				{$view	= '//'.$porteur.'/'.$campName.'/restriction_paiement';}
				else
				{$view	= '//'.$porteur.'/restriction_paiement';}

				// Rendu de la page :
				$this->render( $view, array( 'Invoice' => $Invoice ) );
				Yii::app()->end();

			}
			else{

				$Invoice->create( $this->getUser(), $this->getSubCampaign(), $this->getSite(), $Bdc );
				$Invoice->addRecord( $this->getProduct(), 1, $this->getPriceEngine(), $this->getSubCampaignReflation() );

				$AP			= $this->getCurrentAP();
				$Product	= \Business\Product::load($this->getProduct()->id);

				if( $Product->ctdate >0  && $AP!=1)
				{

					$Invoice->setAdditionnalValue('CTdate',json_decode(json_decode($Form->getStringCTdate())));

				}

				// Pour les traitements speciaux des produits :
				if( ($WebCampaign = \WebCampaign::getWebCampaign( $this->getSubCampaign(), $this->getUser() )) )
				{$WebCampaign->onSaveInvoice( $Invoice );}


				if( ($Res = $Invoice->finalize()) )
				{
					if( is_object($Res) && is_a($Res, '\Business\ConfigPaymentProcessor') )
					{$this->redirectToPaymentProcessor( $Res );}
					else
					{throw new EsoterException( 13, \Yii::t( 'error', 13 ) );}
				}
				else
				{throw new EsoterException( 11, \Yii::t( 'error', 11 ) );}
			}
		}

		return $Form;
	}


	public function getBdcFormFieldss()
	{
		\Yii::import( 'ext.Shared' );
		
		

		$Bdc	= new Bdc( $this->getSite()->id, $this->getCurrentPrice() , \Shared::getDateBirthAfter($this->getUser()->birthday, 20));
		$Form	= new BdcFormFieldss( $Bdc );
		// Si un BDC est soumis et validÃ© :
		if( $Form->submitted('validate') && $Form->validate() )
		{
			

			// Creation de la commande :
			$Invoice	= new \Business\Invoice();



				// Campagne du produit :
				$porteur			= \Yii::app()->params['porteur'];
				$campName			= $this->getCampaign()->ref;
				$refProduct			= $this->getProduct()->ref;

// Restriction de paiement pour la grande voyance

$type_paiement = $Bdc->getPaymentProcessorType()->type;
$date_systeme = date('m/d/Y');


				$idpr = $this->getProduct()->id;

			$expirationproduit=(int)$this->getProduct()->produit_expirable;




			if($expirationproduit == 1 )
			{

			$var1 = \Business\Restriction_paiement::loadpp($idpr,$type_paiement);







				if(!empty($var1))
			{
		if(strtotime($date_systeme) >= strtotime($var1[0]->date_fin))
					{


						// Titre de la page :
						$this->pageTitle	= "Produit expirÃ©";



					// Recherche de la vue a utiliser :
					if( $this->isView( '//'.$porteur.'/'.$campName.'/'.$refProduct.'/restriction_GrandeVoyance' ) )
					{$view	= '//'.$porteur.'/'.$campName.'/'.$refProduct.'/restriction_GrandeVoyance';}
						else if( $this->isView( '//'.$porteur.'/'.$campName.'/restriction_GrandeVoyance' ) )
						{$view	= '//'.$porteur.'/'.$campName.'/restriction_GrandeVoyance';}
						else
						{$view	= '//'.$porteur.'/restriction_GrandeVoyance';}

						// Rendu de la page :
						$this->render( $view, array( 'Invoice' => $Invoice ) );
						Yii::app()->end();

			}
		}
		}

			/////// Fin de la Fonction de restriction de paiement
if($Invoice->InvoicePayedUser($this->getUser()->email, $this->getProduct()->ref )  > 0 && $this->getCurrentTR() < 1503)
			{
				// Campagne du produit :
				$porteur			= \Yii::app()->params['porteur'];
				$campName			= $this->getCampaign()->ref;
				$refProduct			= $this->getProduct()->ref;

				// Titre de la page :
				$this->pageTitle	= ucfirst( strtolower( \Yii::app()->name ) );

				// Recherche de la vue a utiliser :
				if( $this->isView( '//'.$porteur.'/'.$campName.'/'.$refProduct.'/restriction_paiement' ) )
				{$view	= '//'.$porteur.'/'.$campName.'/'.$refProduct.'/restriction_paiement';}
				else if( $this->isView( '//'.$porteur.'/'.$campName.'/restriction_paiement' ) )
				{$view	= '//'.$porteur.'/'.$campName.'/restriction_paiement';}
				else
				{$view	= '//'.$porteur.'/restriction_paiement';}

				// Rendu de la page :
				$this->render( $view, array( 'Invoice' => $Invoice ) );
				Yii::app()->end();

			}
			else{

				$Invoice->create( $this->getUser(), $this->getSubCampaign(), $this->getSite(), $Bdc );
				$Invoice->addRecord( $this->getProduct(), 1, $this->getPriceEngine(), $this->getSubCampaignReflation() );

				$AP			= $this->getCurrentAP();
				$Product	= \Business\Product::load($this->getProduct()->id);

				if( $Product->ctdate >0  && $AP!=1)
				{

					$Invoice->setAdditionnalValue('CTdate',json_decode(json_decode($Form->getStringCTdate())));

				}

				// Pour les traitements speciaux des produits :
				if( ($WebCampaign = \WebCampaign::getWebCampaign( $this->getSubCampaign(), $this->getUser() )) )
				{$WebCampaign->onSaveInvoice( $Invoice );}


				if( ($Res = $Invoice->finalize()) )
				{
					if( is_object($Res) && is_a($Res, '\Business\ConfigPaymentProcessor') )
					{$this->redirectToPaymentProcessor( $Res );}
					else
					{throw new EsoterException( 13, \Yii::t( 'error', 13 ) );}
				}
				else
				{throw new EsoterException( 11, \Yii::t( 'error', 11 ) );}
			}
		}

		return $Form;
	}



    //This function create by Ftaich Youssef
    /**
     * cette fonction permet de retourner la dateCT selon les parametres d'entrÃ©es
     *  param $lang:  signfie la format du date selon le site du porteur
     *  param $numberEMVAdmin : Le nombre de la date CT Ã  afficher
     *
     */
    public function getViewDateCT($numberEMVAdmin, $lang) {
        //Get email costomer
        $email = \Yii::app()->request->getQuery('m', false);
        //Get ref compain
        $campaign = \Yii::app()->request->getQuery('ref', false);
        //Get SP
        $sp = \Yii::app()->request->getQuery('sp', false);
        $Inv = new \Business\Invoice;
        $invoices = $Inv->findAllByAttributes(array('emailUser' => $email, 'campaign' => $campaign, 'invoiceStatus' => 2));

        // Si le client achetÃ© un ou plusieur produits et ces produits contient au moins une dateCt le traitement de dateCT sera comme suit
        if ( count($invoices) > 0 ) {
            foreach ( $invoices as $value ) {
                $recordInvoice = $value->RecordInvoice[0];
                $refProduit1 = $recordInvoice->refProduct;
                $refProduit2 = $campaign . '_' . $sp;
                if ( $refProduit1 == $refProduit2 ) {
                    if ( is_object($recordInvoice->RecordInvoiceAnnexe) ) {
                        if ( $recordInvoice->RecordInvoiceAnnexe->productExt ) {
                            $arrayDCT = $recordInvoice->RecordInvoiceAnnexe->productExt->CTdate;
                            if ( is_object($arrayDCT) ) {
                                $jsonDateCt = json_decode(json_encode($arrayDCT), true);
                                // Verified the $NumberDateCt is not higher then size of Array $arrayDCT
                                if ( $numberEMVAdmin > 0 ) {
                                    if ( $numberEMVAdmin <= count($jsonDateCt) ) {
                                        $dateCT = array_values($jsonDateCt)[$numberEMVAdmin - 1];
                                        if ( strcmp($lang, "en") !== 0 ) {
                                            //    Get format EN
                                            return date("d/m/Y", strtotime($dateCT));
                                        }
                                        else {
                                            //     Get format FR
                                            return $dateCT;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

	/**
	 * Permettre l'affichage du calendrier des contacts tÃ©lÃ©phatiques
	 *
	 * @param int $datect : calendrier
	 *
	 * @return string
	 */
	public function getDateCT ($datect)
			{ 	$Product		    = \Business\Product::load($this->getProduct()->id);
				if ($datect == 0){

					$CTdate         = $Product->CTdateSHM($Product);
				}
				else if ($datect == 1) {
					$CTdate         = $Product->CTdateAHM($Product);
				}
				else if ($datect == 2) {
					$CTdate         = $Product->CTdateGAR($Product);
				}
				else if ($datect == 3) {
					$CTdate         = $Product->CTdateGSR($Product);
				}
				else if ($datect == 4){
					$CTdate         = $Product->CTdateMYR($Product);
				}

		return 	$CTdate;
	}







	/*
	 * Action generique pour l'affichage d'une LDV : /site/ldv?m=test@test.com&...
	 */
	public function actionIndex()
	{


		// Log l'action courante :
		$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_OPEN ) ) );

		// Campagne du produit :

		$porteur			= \Yii::app()->params['porteur'];
		$campName			= $this->getCampaign()->ref;
		$view				= $this->getSubCampaignReflation()->view;
		$refProduct			= $this->getProduct()->ref;
		$BDC				= $this->getBdcForm();
		$BDCFIELDSS				= $this->getBdcFormFieldss();
		$BDCPP				= $this->getBdcFormPP();
		$CodeSite           = $this->getCurrentSITE();
		$TR                 = $this->getCurrentTR();
		$AP				    = $this->getCurrentAP();
		$SP				    = $this->getCurrentSP();
		$Voeux				= $this->setFormvoeux();

		// Titre de la page :
		
		$pageTitle	= ucfirst( strtolower( substr(\Yii::app()->name, 0, strlen(\Yii::app()->name)-2) ) );

		// Pour les traitements speciaux des produits :
		$WebCampaign = \WebCampaign::getWebCampaign( $this->getSubCampaign(), $this->getUser() );

		// Mode Asynchrone verifier le nombre de commande chÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©que non validÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©e
		$Invoice		    = new \Business\Invoice();
		$InvoiceCanPay      = $Invoice->InvoiceCanPay( $this->getUser()->email, $this->getProduct()->ref );
		$InvoiceCanPay_pro1      = $Invoice->InvoiceCanPay_pro1( $this->getUser()->email, $this->getProduct()->ref );

		// Si c'est un TR pro on va insÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©rer la date d ouverture dans l invocie
		$date_ouverture = "";
		if($TR == 111){
			$now = new DateTime();
			$date_ouverture = $now->format('Y-m-d H:i:s');
			$Invoice->SetOpeningDate($this->getUser()->email, $this->getProduct()->ref, $date_ouverture);
		}

		// Mode Asynchrone verifier le nombre de commande chÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©que non validÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©e
		$Product		    = \Business\Product::load($this->getProduct()->id);

		
		
			$CTdate         = $Product->CTdate($Product);
			$CTdate2         = $Product->CTdate2($Product);
			$CTdate3         = $Product->CTdate3($Product);
			$CTdateNl         = $Product->CTdateNl($Product);
			$CTdateIT         = $Product->CTdateIT($Product);
			$CTdateDE         = $Product->CTdateDE($Product);
			$CTdateDE3         = $Product->CTdateDE3($Product);
			$CTdateDE4         = $Product->CTdateDE4($Product);
			$CTdateNO         = $Product->CTdateNO($Product);
			$CTdateTR         = $Product->CTdateTR($Product);
			$CTdatePT         = $Product->CTdatePT($Product);
			$CTdateES         = $Product->CTdateES($Product);
			$CTdateDK         = $Product->CTdateDK($Product);
			$CTdateFr         = $Product->CTdateFr($Product);
			$CTdatePCN         = $Product->CTdatePCN($Product);
			$CTdateSE         = $Product->CTdateSE($Product);
			$CTdateMLPT        = $Product->CTdateMLPT($Product);
			$CTdateFr2        = $Product->CTdateFr2($Product);
			$CTdatetr3         = $Product->CTdatetr3($Product);
			$CTdateEN3        = $Product->CTdateEN3($Product);
			$CTdateDEBis         = $Product->CTdateDEBis($Product);
			$CTdateMLBR         = $Product->CTdateMLBR($Product);

		
			 
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
		

		// Javascript des dates CT de la page Poduct
		if($Product->ctdate && $Product->ctdate >0)
		{
			$this->includeJS('ct_date');
		}



		// Javascript de la page Poduct
		if($InvoiceCanPay == 0){
			$this->includeJS('blur_plugin');
		}

		//Parametres url
		$Urlparams  	= $_SERVER['REQUEST_URI'];
		$chain_ref 	 	= 'ref='.$campName;
		$params     	= str_replace($chain_ref,'',parse_url($Urlparams)['query']);
		$chain_tr       = 'tr='.$TR;
		$params         = str_replace($chain_tr,'',$params);
		$chain_sp       = 'sp='.$SP;
		$params         = str_replace($chain_sp,'',$params);
		
		$parametres_url = preg_replace('#&+#', '&amp;', $params);

		
		$perso = new \Business\Variablesperso('search');
		$perso = $perso->search()->getData();
		$variablesperso = array();
		foreach($perso as $p)
		{
			if($this->getUser()->civility==1)
			{$variablesperso[$p->nom]= $p->valueM;}
			else
			{$variablesperso[$p->nom]= $p->valueF;}
		}
		$Site = new \Business\Site();
		$DeviseSite = $Site->DeviseSite($CodeSite);
		/********* DEBUT Mise en place des 12 signes du produit en question - Ajouter par othmane ************/
		$signe= \Business\Signes::loadByIdProductBySigneNumber($this->getProduct()->id,$this->getUser()->getNumberSignAstro());
		$lessignes = array();
		if($signe)
		{
		$signeVariables = \Business\SignesVariables::loadByIdSigne($signe->id);
		foreach($signeVariables as $s)
		{
			$lessignes[$s->name] = $s->value;
		}
		}
		/********* FIN Mise en place des 12 signes du produit en question - Ajouter par othmane ************/

			/**** Partie des Variables Ange ****/
		if (preg_match("/althea/i", $porteur))
		{
			$ange = \Business\Anges::loadByIdProductByAngeNumber($this->getProduct()->id,$this->getUser()->getNumberAngeAstro());
					$lesanges = array();
					if($ange)
					{
						$angeVariables = \Business\AngesVariables::loadByIdAnge($ange->id);
						foreach($angeVariables as $t)
						{
							$lesanges[$t->name] = $t->value;
						}
					}
				
		}
		/******** FIN Partie des Variables Ange ******/
	
		// Rendu de la page :
		if (preg_match("/althea/i", $porteur))
		{
			if(isset($_GET['emv8']))
			{
				if(Yii::app()->request->getParam( 'emv8' ) !== NULL ){
					$emv8 = Yii::app()->request->getParam( 'emv8' );
				}
			}
			else
			{
				$emv8 = 1;
			}
			$this->render( '//'.$porteur.'/'.$campName.'/'.$refProduct.'/'.$view, array('BDCFIELDS' => $BDCFIELDSS,'BDC' => $BDC,'BDCPP' => $BDCPP,'CTdateMLBR'=> $CTdateMLBR,'CTdate' => $CTdate,'Parametres_Url' => $parametres_url, 'WebCampaign' => $WebCampaign, 'nbInvoiceAsynch' => $InvoiceCanPay, 'InvoiceCanPay_pro1' => $InvoiceCanPay_pro1, 'perso' => $variablesperso, 'pageTitle' => $pageTitle, 'DeviseSite' => $DeviseSite, 'Les12Signes' => $lessignes, 'Les9Anges' => $lesanges , 'emv8' => $emv8 ) );
		}
		else 
			
		
		{
		$this->render( '//'.$porteur.'/'.$campName.'/'.$refProduct.'/'.$view, array('BDCFIELDS' => $BDCFIELDSS,'BDC' => $BDC,'BDCPP' => $BDCPP,'CTdateMLBR'=> $CTdateMLBR,'CTdate' => $CTdate,'Parametres_Url' => $parametres_url, 'WebCampaign' => $WebCampaign, 'nbInvoiceAsynch' => $InvoiceCanPay, 'InvoiceCanPay_pro1' => $InvoiceCanPay_pro1, 'perso' => $variablesperso, 'pageTitle' => $pageTitle, 'DeviseSite' => $DeviseSite, 'Les12Signes' => $lessignes ) );	
			
		}
	}

	public function actionThankYou()
	{
		// Log l'action courante :
		$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_TY_CB ) ) );

		// Campagne du produit :
		$porteur			= \Yii::app()->params['porteur'];
		$campName			= $this->getCampaign()->ref;
		$refProduct			= $this->getProduct()->ref;

		// Titre de la page :
		
		
	    $this->pageTitle	= ucfirst( strtolower( substr(\Yii::app()->name, 0, strlen(\Yii::app()->name)-2) ) );


		// Recuperation de la commande :
		$Invoice	= \Business\Invoice::load( \Yii::app()->request->getParam('idInvoice') );

		// Recherche de la vue a utiliser :
		if( $this->isView( '//'.$porteur.'/'.$campName.'/'.$refProduct.'/ty' ) )
		{$view	= '//'.$porteur.'/'.$campName.'/'.$refProduct.'/ty';}
		else if( $this->isView( '//'.$porteur.'/'.$campName.'/ty' ) )
		{$view	= '//'.$porteur.'/'.$campName.'/ty';}
		else
		{$view	= '//'.$porteur.'/ty';}

		$perso = new \Business\Variablesperso('search');
		$perso = $perso->search()->getData();
		$variablesperso = array();
		foreach($perso as $p)
		{
			if($this->getUser()->civility==1)
			{$variablesperso[$p->nom]= $p->valueM;}
			else
			{$variablesperso[$p->nom]= $p->valueF;}
		}

		// Rendu de la page :
		$this->render( $view, array( 'Invoice' => $Invoice, 'perso' => $variablesperso ) );
	}

	public function actionThankYouDm()
	{
		// Log l'action courante :
		$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_TY_CHECK ) ) );

		// Campagne du produit :
		$porteur			= \Yii::app()->params['porteur'];
		$campName			= $this->getCampaign()->ref;
		$refProduct			= $this->getProduct()->ref;

		// Titre de la page :
		$this->pageTitle	= ucfirst( strtolower( \Yii::app()->name ) ).' - '.\Yii::t( 'site', 'ty' );

		// Recuperation de la commande :
		$Invoice	= \Business\Invoice::load( \Yii::app()->request->getParam('idInvoice') );

		// Recuperation des variables Prso
		$perso = new \Business\Variablesperso('search');
		$perso = $perso->search()->getData();
		$variablesperso = array();
		foreach($perso as $p){
			if($this->getUser()->civility==1)
			{$variablesperso[$p->nom]= $p->valueM;}
			else
			{$variablesperso[$p->nom]= $p->valueF;}
		}

		// Recherche de la vue a utiliser :
		if( $this->isView( '//'.$porteur.'/'.$campName.'/'.$refProduct.'/tyDm' ) )
		{$view	= '//'.$porteur.'/'.$campName.'/'.$refProduct.'/tyDm';}
		else if( $this->isView( '//'.$porteur.'/'.$campName.'/tyDm' ) )
		{$view	= '//'.$porteur.'/'.$campName.'/tyDm';}
		else
		{$view	= '//'.$porteur.'/tyDm';}

		// Rendu de la page :
		$this->render( $view, array( 'Invoice' => $Invoice, 'perso' => $variablesperso) );
	}

	public function actionThankYouVG()
	{
		// Log l'action courante :
		$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_TY_CB ) ) );

		// Campagne du produit :
		$porteur			= \Yii::app()->params['porteur'];
		$campName			= $this->getCampaign()->ref;
		$refProduct			= $this->getProduct()->ref;

		// Titre de la page :
		
		$this->pageTitle	= ucfirst( strtolower( substr(\Yii::app()->name, 0, strlen(\Yii::app()->name)-2) ) );

		// Recuperation de la commande :
		$Invoice	= \Business\Invoice::load( \Yii::app()->request->getParam('idInvoice') );

		// Recherche de la vue a utiliser :
		if( $this->isView( '//'.$porteur.'/'.$campName.'/'.$refProduct.'/tyVG' ) )
			$view	= '//'.$porteur.'/'.$campName.'/'.$refProduct.'/tyVG';
		else if( $this->isView( '//'.$porteur.'/'.$campName.'/tyVG' ) )
			$view	= '//'.$porteur.'/'.$campName.'/tyVG';
		else
			$view	= '//'.$porteur.'/tyVG';

		$perso = new \Business\Variablesperso('search');
		$perso = $perso->search()->getData();
		$variablesperso = array();
		foreach($perso as $p)
		{
			if($this->getUser()->civility==1)
				$variablesperso[$p->nom]= $p->valueM;
			else
				$variablesperso[$p->nom]= $p->valueF;
		}

		// Rendu de la page :
		$this->render( $view, array( 'Invoice' => $Invoice, 'perso' => $variablesperso ) );
	}

	public function actionFailed()
	{
		// Log l'action courante :
		$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_TY_FAILED ) ) );

		// Campagne du produit :
		$porteur			= \Yii::app()->params['porteur'];
		$campName			= $this->getCampaign()->ref;
		$refProduct			= $this->getProduct()->ref;

		// Titre de la page :
		$this->pageTitle	= ucfirst( strtolower( substr(\Yii::app()->name, 0, strlen(\Yii::app()->name)-2) ) );

		// Recuperation de la commande :
		$Invoice	= \Business\Invoice::load( \Yii::app()->request->getParam('idInvoice') );

		// Recherche de la vue a utiliser :
		if( $this->isView( '//'.$porteur.'/'.$campName.'/'.$refProduct.'/failed' ) )
			$view	= '//'.$porteur.'/'.$campName.'/'.$refProduct.'/failed';
		else if( $this->isView( '//'.$porteur.'/'.$campName.'/failed' ) )
			$view	= '//'.$porteur.'/'.$campName.'/failed';
		else
			$view	= '//'.$porteur.'/failed';

		// Rendu de la page :
		$this->render( $view, array( 'Invoice' => $Invoice ) );
	}

	public function actionThankYouCheck()
	{
		// Log l'action courante :
		$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_TY_CHECK ) ) );

		// Campagne du produit :
		$porteur			= \Yii::app()->params['porteur'];
		$campName			= $this->getCampaign()->ref;
		$refProduct			= $this->getProduct()->ref;

		// Titre de la page :
		
		$this->pageTitle	= ucfirst( strtolower( substr(\Yii::app()->name, 0, strlen(\Yii::app()->name)-2) ) );


		// Recuperation de la commande :
		$Invoice	= \Business\Invoice::load( \Yii::app()->request->getParam('idInvoice') );

		// Recuperation des variables Prso
		$perso = new \Business\Variablesperso('search');
		$perso = $perso->search()->getData();
		$variablesperso = array();
		foreach($perso as $p)
		{
			if($this->getUser()->civility==1)
			$variablesperso[$p->nom]= $p->valueM;
			else
			$variablesperso[$p->nom]= $p->valueF;
		}

		// Recherche de la vue a utiliser :
		if( $this->isView( '//'.$porteur.'/'.$campName.'/'.$refProduct.'/tyCheck' ) )
			$view	= '//'.$porteur.'/'.$campName.'/'.$refProduct.'/tyCheck';
		else if( $this->isView( '//'.$porteur.'/'.$campName.'/tyCheck' ) )
			$view	= '//'.$porteur.'/'.$campName.'/tyCheck';
		else
			$view	= '//'.$porteur.'/tyCheck';

		// Rendu de la page :
		$this->render( $view, array( 'Invoice' => $Invoice, 'perso' => $variablesperso) );
	}

	public function actionThankYouMB(){
		
		// Log l'action courante :
		$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_TY_CHECK ) ) );

		// Campagne du produit :
		$porteur			= \Yii::app()->params['porteur'];
		$campName			= $this->getCampaign()->ref;
		$refProduct			= $this->getProduct()->ref;

		// Titre de la page :
		
		$this->pageTitle	= ucfirst( strtolower( substr(\Yii::app()->name, 0, strlen(\Yii::app()->name)-2) ) );

		// Recuperation de la commande :
		$Invoice	= \Business\Invoice::load( \Yii::app()->request->getParam('idInvoice') );

		// Send intention de paiement MB:
		$Invoice->sendRequestToEMV( \Business\RouterEMV::URL_INTENTION_MULTIBANCO );

		// Recuperation des variables Prso
		$perso = new \Business\Variablesperso('search');
		$perso = $perso->search()->getData();
		$variablesperso = array();
		foreach($perso as $p){
			if($this->getUser()->civility==1)
				$variablesperso[$p->nom]= $p->valueM;
			else
				$variablesperso[$p->nom]= $p->valueF;
		}

		// Recherche de la vue a utiliser :
		
		 
			
				
				
			$view	= '//'.$porteur.'/tyMB';

			// Rendu de la page :
			$this->render( $view, array( 'Invoice' => $Invoice, 'perso' => $variablesperso) );
	}


	/**
	 * Action transformant les GET en POST
	 * attend au moins un parametre GET['url'] ( URL ver laquel redirigÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â© )
	 */
	public function actionGetToPost()
	{
		// Log l'action courante :
		$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_REDIRECT_PP ) ) );

		// Titre de la page :
		$this->pageTitle	= \Yii::t( 'site', 'redirect' );

		$url = \Yii::app()->request->getParam('url');
		unset($_GET['url']);

		// Rendu de la page :
		$this->render( '//common/redirectPaymentProcessor', array( 'url' => $url, 'GET' => $_GET ) );
	}

	/**
	 * Redirige vers le processur de paiement,
	 * transforme eventuellement les parametres GET en POST
	 * @param \Business\ConfigPaymentProcessor $Res	Objet de config du processeur de payment
	 */
	protected function redirectToPaymentProcessor( $Res )
	{
		$paramGet = $this->Context->getQueryString().$Res->getParamURL();

		if( $Res->getTransformGetToPost() )
		{
			$paramGet .= '&url='.$Res->getUrl();
			/**** DEBUT - traitement pour G2S Aasha 2 noms de domaine - Ajouter par Othmane le 07/08/2015 ****/
			$domain=\Yii::app()->getBaseUrl(true);
			if(strpos($domain, 'aasha-f') !== false)
			{
				$domain=str_replace('aasha-f','aasha-online',$domain);
				\Yii::app()->request->redirect( $domain.'/index.php/site/getToPost/?'.$paramGet );
			/**** FIN - traitement pour G2S Aasha 2 noms de domaine - Ajouter par Othmane le 07/08/2015 ****/
			}
			else
			{
				\Yii::app()->request->redirect( \Yii::app()->baseUrl.'/index.php/site/getToPost/?'.$paramGet );
			}

		}
		else
		{
			$newUrl = $Res->getUrl().( strpos( $Res->getUrl(), '?' ) === false ? '?' : '&' ).$paramGet;
			\Yii::app()->request->redirect( $newUrl );
		}
	}

	// ****************************** GETTER ****************************** //
	/**
	 * Retourne l'utilisateur courant
	 * @return \Business\User
	 */


	/***********************************Nom ange**************************************************  */
	public function getNomange(){
		\Yii::import( 'ext.DateAnge' );
		return DateAnge::nomange($this->getUser()->birthday);

	}

	/*************************************************************************************  */
	
	public function getAngeName(){
		\Yii::import( 'ext.DateTabAnge' );
		return DateTabAnge::AngeName($this->getUser()->birthday);

	}
	/*************************************************************************************  */
	
	/*************************************************************************************  */
	
	 public function getAngeNameSE(){
	  \Yii::import( 'ext.DateTabAnge' );
	  return DateTabAnge::AngeNameSE($this->getUser()->birthday);
	
	 }
	/*************************************************************************************  */
        /*************************************************************************************  */
	
	 public function getAngeNameNO(){
	  \Yii::import( 'ext.DateTabAnge' );
	  return DateTabAnge::AngeNameNO($this->getUser()->birthday);
	
	 }
	/*************************************************************************************  */

	public function getUser()
	{
	
		return ( is_object($this->Context) ) ? $this->Context->getUser() : false;
	}
	/**
	 * Retourne le produit courant
	 * @return \Business\Product
	 */
	public function getProduct()
	{
		return ( is_object($this->Context) ) ? $this->Context->getProduct() : false;
	}
	/**
	 * Retourne la campagne courante
	 * @return \Business\SubCampaign
	 */
	public function getSubCampaign()
	{
		return ( is_object($this->Context) ) ? $this->Context->getSubCampaign() : false;
	}
	/**
	 * Retourne la campagne courante
	 * @return \Business\SubCampaignReflation
	 */
	public function getSubCampaignReflation()
	{
		return ( is_object($this->Context) ) ? $this->Context->getSubCampaignReflation() : false;
	}
	/**
	 * Retourne la campagne courante
	 * @return \Business\Campaign
	 */
	public function getCampaign()
	{
		return ( is_object($this->Context) ) ? $this->Context->getCampaign() : false;
	}
	/**
	 * Retourne le moteur de prix courant
	 * @return \Business\PriceEngine
	 */
	public function getPriceEngine()
	{
	
		return ( is_object($this->Context) ) ? $this->Context->getPriceEngine() : false;
	} 
	/**
	 * Retourne le context
	 * @return \Business\Context
	 */
	public function getContext()
	{
		return ( is_object($this->Context) ) ? $this->Context : false;
	}

	/**
	 * Retourne le site
	 * @return \Business\Site
	 */
	public function getSite()
	{
		return ( is_object($this->Context) ) ? $this->Context->getSite() : false;
	}
	/**
	 * Retourne le prix du produit courant
	 * @return int Prix
	 */
	public function getCurrentPrice($tag = false) {
		/**
		 *  @author soufiane balkaid 
		 *  @desc ajout de la mise Ã  jour du gp si l s'agit d'une fid anaconda
		 *  debut
		 */
		$this->getPriceEngine ()->setRefPricingGrid ( $this->getCurrentGp() );
		
		/**
		 * fin
		 */
		
		if (! $this->getPriceEngine () || ! $this->getProduct ())
			throw new EsoterException ( 106, \Yii::t ( 'error', 106 ) );
		
		if (! ($PG = $this->getPriceEngine ()->getPrice ( $this->getSubCampaignReflation () )))
			throw new EsoterException ( 106, \Yii::t ( 'error', 106 ) );
		if ($tag == true) {
			
			return '<span name="getprice_r">' . $PG->priceATI . ' ' . $this->getSite ()->devise . '</span>';
		} else {
			return $PG->priceATI . ' ' . $this->getSite ()->devise;
		}
	}

	/**
	 * Retourne le prix thÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©orique du produit courant
	 * @return int	Prix
	 */
	public function getCurrentTheoPrice()
	{
		if( !$this->getProduct() )
			throw new EsoterException( 106, \Yii::t( 'error', 106 ) );

		$GP	= $this->getCurrentGP();

		switch( $GP )
		{
            default:
            case 1:
                return $this->getProduct()->theoPricePros.' '.$this->getSite()->devise;
                break;
            case 2:
               return $this->getProduct()->theoPriceVg.' '.$this->getSite()->devise;
                break;
            case 3:
               return $this->getProduct()->theoPriceVp.' '.$this->getSite()->devise;
                break;
            case 4:
               return $this->getProduct()->theoPriceCt.' '.$this->getSite()->devise;
                break;
        }

		return false;
	}



	/**
     * Description de la mÃ©thode
     *
     * @author balkaid soufiane
     * @param <int> $nombre nombre de chiffres aprÃ©s la virgule
     * @throws EsoterException
     * @version 1.0
     *
     * @return boolean|double
     *     - double : le prix thÃ©orique
     *     - false  : en cas d'erreur
     */
 public function getCurrentTheoPriceVF($nombre,$tag = false)
 {

    $PG = $this->getPriceEngine()->getPrice( $this->getSubCampaignReflation());

    if( !( $PG ) || !$this->getPriceEngine() || !$this->getProduct() )
    throw new EsoterException( 106, \Yii::t( 'error', 106 ) );

    $GP = $this->getCurrentGP();
    if( $tag == true )
    {
	if( $GP == 1 )
	{
        if( $PG->prixTheorique > 0 )
            return sprintf('<span name="getprice_t">'.'%6.'.$nombre.'f',floor($PG->prixTheorique*pow(10,$nombre))/pow(10,$nombre)).
		        ' '.$this->getSite()->devise .'</span>';
        return sprintf('<span name="getprice_t">'.'%6.'.$nombre.'f',floor($this->getProduct()->theoPricePros*pow(10,$nombre))/pow(10,$nombre) ).
		        ' '.$this->getSite()->devise.'</span>';
    }

    elseif( $GP == 2 )
    {
        if(  $PG->prixTheorique > 0 )
            return sprintf('<span name="getprice_t">'.'%6.'.$nombre.'f',floor($PG->prixTheorique*pow(10,$nombre))/pow(10,$nombre)).
		        ' '.$this->getSite()->devise.'</span>';

        return sprintf('<span name="getprice_t">'.'%6.'.$nombre.'f',floor($this->getProduct()->theoPriceVg*pow(10,$nombre))/pow(10,$nombre)).
		    ' '.$this->getSite()->devise.'</span>';
    }

    elseif( $GP == 3 )
    {

        if(  $PG->prixTheorique > 0 )
            return sprintf('<span name="getprice_t">'.'%6.'.$nombre.'f',floor($PG->prixTheorique*pow(10,$nombre))/pow(10,$nombre)).
		        ' '.$this->getSite()->devise.'</span>';

        return sprintf('<span name="getprice_t">'.'%6.'.$nombre.'f',floor($this->getProduct()->theoPriceVp*pow(10,$nombre))/pow(10,$nombre) ).
		        ' '.$this->getSite()->devise.'</span>';
    }

    elseif( $GP == 4 )
    {
        if(  $PG->prixTheorique > 0 )
            return sprintf('<span name="getprice_t">'.'%6.'.$nombre.'f',floor($PG->prixTheorique*pow(10,$nombre))/pow(10,$nombre)).
		        ' '.$this->getSite()->devise.'</span>';

        return sprintf('<span name="getprice_t">'.'%6.'.$nombre.'f',floor($this->getProduct()->theoPriceCt*pow(10,$nombre))/pow(10,$nombre)).
		        ' '.$this->getSite()->devise.'</span>';
    }
    }else {
    	if( $GP == 1 )
    	{
    		if( $PG->prixTheorique > 0 )
    			return sprintf('%6.'.$nombre.'f',floor($PG->prixTheorique*pow(10,$nombre))/pow(10,$nombre)).
    			' '.$this->getSite()->devise ;
    		return sprintf('%6.'.$nombre.'f',floor($this->getProduct()->theoPricePros*pow(10,$nombre))/pow(10,$nombre) ).
    		' '.$this->getSite()->devise;
    	}
    	
    	elseif( $GP == 2 )
    	{
    		if(  $PG->prixTheorique > 0 )
    			return sprintf('%6.'.$nombre.'f',floor($PG->prixTheorique*pow(10,$nombre))/pow(10,$nombre)).
    			' '.$this->getSite()->devise;
    	
    		return sprintf('%6.'.$nombre.'f',floor($this->getProduct()->theoPriceVg*pow(10,$nombre))/pow(10,$nombre)).
    		' '.$this->getSite()->devise;
    	}
    	
    	elseif( $GP == 3 )
    	{
    	
    		if(  $PG->prixTheorique > 0 )
    			return sprintf('%6.'.$nombre.'f',floor($PG->prixTheorique*pow(10,$nombre))/pow(10,$nombre)).
    			' '.$this->getSite()->devise;
    	
    		return sprintf('%6.'.$nombre.'f',floor($this->getProduct()->theoPriceVp*pow(10,$nombre))/pow(10,$nombre) ).
    		' '.$this->getSite()->devise;
    	}
    	
    	elseif( $GP == 4 )
    	{
    		if(  $PG->prixTheorique > 0 )
    			return sprintf('%6.'.$nombre.'f',floor($PG->prixTheorique*pow(10,$nombre))/pow(10,$nombre)).
    			' '.$this->getSite()->devise;
    	
    		return sprintf('%6.'.$nombre.'f',floor($this->getProduct()->theoPriceCt*pow(10,$nombre))/pow(10,$nombre)).
    		' '.$this->getSite()->devise;
    	}
    
    
    }
    
    
    
  return false;


 }




	public function getCurrentTheoPrice2()
	{

		$PG =  new \Business\PricingGrid;
		$priceStep = $this->getCurrentSP();
		$refBatchSelling = $this->getPriceEngine()->getRefBatchSelling();
		$GP	= $this->getCurrentGP();
		$idSite = $this->GetIdSite();
		$idSubCompain = $this->GetIdSubCampaign();
		$MYPG = $PG->get($idSubCompain,$refBatchSelling,$priceStep,$GP,$idSite );
		$Site =  new \Business\Site;
		
		$myprixTheorique = isset($MYPG->prixTheorique) ? $MYPG->prixTheorique : 0;
		return $myprixTheorique.' '.$this->getSite()->devise;
	}

	/**
	 * Retourne la date d'anniversaire aprÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¨s une nombre de jour
	 * @return date
	 */
	public function getDateBirthAfter($birthday, $days = 20){
		\Yii::import( 'ext.Shared' );
		return Shared::getDateBirthAfter($birthday, $days);
	}

	/**
	 * DEV: DERRAZ Fatima-zahra
	 * @return la photo, ou bien le prÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©nom et l'adresse mail de l'internaute
	 **/
	public function getphotoUser($withPhoto = 'photo')
	 {

	  $porteur = \Yii::app()->params['porteur'];

	  $User_V1	     = \Business\User_V1::loadByEmail( $this->getUser()->email );

	  if (isset($User_V1) && $User_V1->ID >0) {
		  $chemin   = $_SERVER['DOCUMENT_ROOT']."/lib/uploads/".$GLOBALS['porteurPathPhoto'][$porteur]."/enabled/".$User_V1->ID.".jpg";

		  if (file_exists($chemin) && $User_V1->ID != '')
		  {
		    if($withPhoto == 'photo')
		   return '<img src="/lib/uploads/'.$GLOBALS['porteurPathPhoto'][$porteur].'/enabled/'.$User_V1->ID.'.jpg" alt="" />';
		 else
		   return 'une'.' '.'la'.' '.'photo';

		  }
		  else
		  {
		    if($withPhoto == 'photo')
		  	return  $this->getUser()->firstName."<br />".$this->getUser()->email ;
		 else
		    return 'un'.' '.'le'.' '.'dossier';
		  }
	 }
	 else
	 	return  $this->getUser()->firstName."<br />".$this->getUser()->email ;
	  }

	/**
	 * DEV: EDDAGHOUR Fatima-zahra
	 * @return la photo, ou bien le prÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©nom et l'adresse mail de l'internaute
	 **/
public function getphotoUserIT($withPhoto = 'photo')
	 {

	  $porteur = \Yii::app()->params['porteur'];

	  $User_V1	     = \Business\User_V1::loadByEmail( $this->getUser()->email );

	  if (isset($User_V1) && $User_V1->ID >0) {
		  $chemin   = $_SERVER['DOCUMENT_ROOT']."/lib/uploads/".$GLOBALS['porteurPathPhoto'][$porteur]."/enabled/".$User_V1->ID.".jpg";

		  if (file_exists($chemin) && $User_V1->ID != '')
		  {
		    if($withPhoto == 'photo')
		   return '<img src="/lib/uploads/'.$GLOBALS['porteurPathPhoto'][$porteur].'/enabled/'.$User_V1->ID.'.jpg" alt="" />';
		 else
		   return 'una'.' '.'foto'.' '.'foto';

		  }
		  else
		  {
		    if($withPhoto == 'photo')
		  	return  $this->getUser()->firstName."<br/>".$this->getUser()->email ;
		 else
		    return 'un'.' '.'dossier'.' '.'cartella';
		  }
	 }
	 else
	 	return  $this->getUser()->firstName."<br/>".$this->getUser()->email ;
	  }

/**
	 * DEV: Mehdi AIT AHMED
	 * @return la photo, ou bien le prÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©nom et l'adresse mail de l'internaute
	 **/
	public function getphotoUserDe($withPhoto = 'photo')
	 {

	  $porteur = \Yii::app()->params['porteur'];

	  $chemin   = $_SERVER['DOCUMENT_ROOT']."/lib/uploads/".$GLOBALS['porteurPathPhoto'][$porteur]."/".$this->getUser()->id.".jpg";
	  if (file_exists($chemin) && $this->getUser()->id != '')
	  {
	    if($withPhoto == 'photo')
	   return '<img src="/lib/uploads/'.$GLOBALS['porteurPathPhoto'][$porteur].'/'.$this->getUser()->id.'.jpg" alt="" />';
	 else
	   return 'einem '.' '.'Foto';

	  }else{
	    if($withPhoto == 'photo')
	  return  $this->getUser()->firstName."<br />".$this->getUser()->email ;
	 else
	   return 'einigen '.' '.'Unterlagen';
	  }

	  }
/**
	 * DEV: EL ABOUDI OUMNIYA
	 * @return la photo, ou bien le prÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©nom et l'adresse mail de l'internaute
	 **/
public function getphotoUserES($withPhoto = 'photo')
	 {

	  $porteur = \Yii::app()->params['porteur'];

	  $User_V1	     = \Business\User_V1::loadByEmail( $this->getUser()->email );

	   if (isset($User_V1) && $User_V1->ID >0) {
	  $chemin   = $_SERVER['DOCUMENT_ROOT']."/lib/uploads/".$GLOBALS['porteurPathPhoto'][$porteur]."/enabled/".$User_V1->ID.".jpg";

	  if (file_exists($chemin) && $User_V1->ID != '')
	  {
	    if($withPhoto == 'photo')
	   return '<img src="/lib/uploads/'.$GLOBALS['porteurPathPhoto'][$porteur].'/enabled/'.$User_V1->ID.'.jpg" alt="" />';
	 else
	   return 'una '.' '.'foto';

	  }else{
	    if($withPhoto == 'photo')
	  return  $this->getUser()->firstName."<br />".$this->getUser()->email ;
	 else
	   return 'un '.' '.'informe';
	  }
	 }
	 else
	 	return  $this->getUser()->firstName."<br />".$this->getUser()->email ;

	  }
	/**
	 * Retourne la date j+x
	 * @return date
	 */
	public function getDateComplete($dt=null,$lang=null,$format=null,$sep=null){
		\Yii::import( 'ext.DateHelper2' );
		$this->updateInitialShootDateAnaconda();
		return DateHelper2::completDate($dt,$lang,$format,$sep);

	}
		public function getDateCompleteopact($dt=null,$lang=null,$format=null,$sep=null){
		\Yii::import( 'ext.DateHelper2' );
		$this->updateInitialShootDateAnaconda();
		return DateHelper2::completDateopact($dt,$lang,$format,$sep);

	}
	
	public function getcompletDateFMAC($dt=null,$lang=null,$format=null,$sep=null){
		\Yii::import( 'ext.DateHelper2' );
		$this->updateInitialShootDateAnaconda();
		return DateHelper2::completDateFMAC($dt,$lang,$format,$sep);

	}
	public function getDateComplete_short($dt=null){
		\Yii::import( 'ext.DateHelper2' );
		$this->updateInitialShootDateAnaconda();
		return DateHelper2::completDate_short($dt);

	}
	/**
	 * Retourne la date de+x
	 * @return date
	 */
	public function getDateComplete_DE($dt=null,$lang=null,$format=null,$sep=null){
		\Yii::import( 'ext.DateHelper2' );
		$this->updateInitialShootDateAnaconda();
		return DateHelper2::completDate_DE($dt,$lang,$format,$sep);

	}
	/**
	 * Retourne Le premier Jour apres [S+X]
	 * @return date
	 */
	public function getDayOfWeek($NBweek,$Numjour){
		\Yii::import( 'ext.DateHelper2' );
		$this->updateInitialShootDateAnaconda();
		return DateHelper2::DayOfWeek($NBweek,$Numjour);

	}
	/**
	 * Retourne Date du premier Jour aprÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¨s le [J+X]
	 * @return date
	 */
	public function getDayAfterDate($NBjour,$Numjour){
		\Yii::import( 'ext.DateHelper2' );
		$this->updateInitialShootDateAnaconda();
		return DateHelper2::DayAfterDate($NBjour,$Numjour);

	}
	/**
	 * Retourne la variable SITE
	 * @return codeSite
	 */

    public function getCurrentSITE()
	{
		return ( is_object($this->Context) ) ? $this->Context->codeSite : false;
	}
	
	/**
	 * Retourne la variable GP
	 * 
	 * @return GP 
	 */
	public function getCurrentGp() {
		/**
		 *  @author soufiane balkaid
		 *  @desc modifier le gp s'il s'agit d'une fid anaconda et user appartient a anaconda
		 *  @return int gp 
		 *  debut
		 */
		
		if (isset ( $this->getUser()->id ) && isset ( $this->getCampaign()->id )) {
			if (($this->getUser ()->bannReason == 2 || is_null ( $this->getUser ()->bannReason )) 
					&& ! is_null ( $this->getCampaign ()->isAnaconda )) {
				
				$campaignHistory = new \Business\CampaignHistory ();
				$campaignHistorys = $campaignHistory->seachByIdUSerIdSubCampaign ( $this->getUser ()->id, $this->getSubCampaign ()->id );
				$listCampaignsHistorys = $campaignHistorys->getData ();
				
				if (isset ( $listCampaignsHistorys [0]->groupPrice )) {
					
					return $listCampaignsHistorys [0]->groupPrice;
				}
			}
		}
		/**
		 * fin
		 */
		return (is_object ( $this->Context )) ? $this->Context->gp : false;
	}
  
	/**
	 * Retourne la variable tr
	 * @return TR
	 */

    public function getCurrentTR()
	{
		return ( is_object($this->Context) ) ? $this->Context->tr : false; 
	}

	/**
	 * Retourne la variable Abandon Panier
	 * @return AP
	 */

    public function getCurrentAP()
	{
		return ( is_object($this->Context) ) ? $this->Context->ap : false;
	}

	/**
	 * Retourne la variable SP
	 * @return SP
	 */

    public function getCurrentSP()
	{
		return ( is_object($this->Context) ) ? $this->Context->sp : false;
	}

	/**
	 * Retourne le mom du Porteur
	 * @return SP
	 */

	public function getNameporetur()
	{
		$porteur = \Yii::app()->params['porteur'];

		return  $GLOBALS['porteurMap'][$porteur];

	}

	/**
	* @return Nom du porteur
	*/
	function getPorteur()
	{
		$porteur = \Yii::app()->params['porteur'];
		$temp  =  explode('_', $porteur);

		if(count($temp) !== 2) :
			return $porteur;
		endif;

		$site  =  $temp[0];
		$name  =  $temp[1];
		
		if(strcasecmp('evamaria', $name) == 0):
				return "Eva Maria";
			endif;
			
		if(strcasecmp('fr', $site) == 0) :

			if(strcasecmp('rmay', $name) == 0):
				return "Mona Louisa";
			endif;
			if(strcasecmp('rucker', $name) == 0):
				return "ThÃ©odor";
			endif;
			return $name;

		else :

			if(strcasecmp('dk', $site) == 0) :

				if(strcasecmp('rmay', $name) == 0):
					return "Mona Louisa";
				endif;
				if(strcasecmp('rucker', $name) == 0):
					return "Theodor";
				endif;
				return $name ;

			elseif(strcasecmp($site, 'in') == 0 && strcasecmp('Aasha', $name) == 0) :
				return htmlspecialchars_decode('&#x906;&#x936;&#x93E;');
			elseif(strcasecmp($site, 'sg') == 0 && strcasecmp('Alisha', $name) == 0) :
				return htmlspecialchars_decode('&#x827E;&#x4E3D;&#x838E;');
			else :

				if(strcasecmp('rmay', $name) == 0):
					return "Mona Luisa";
				endif;
				if(strcasecmp('rucker', $name) == 0):
					return"Theodor";
				endif;
				return $name;

			endif;

		endif;
	}

	/*::::::::::::::::::::::::::::::VOEUX:::::::::::::::::::::::::::::::::*/
	//Function changement de Formule d'activation de Voeux. >> HH
	public function getFormulevoeux(){
			if(isset($_GET['de'])){
			$date    		= $_GET['de'];
			}
			$dateTokens 	= explode('/', $date );
			$de_time 		= strtotime($dateTokens[0] . '/' . $dateTokens[1] . '/' . $dateTokens[2]);

			switch(strtotime(date("m/d/Y")))
			{
				case strtotime('+31 days', $de_time) :  $src="{{ productDir() }}/images/sceau_sacre_formule2.gif";
				break;
				case strtotime('+62 days', $de_time) :  $src="{{ productDir() }}/images/sceau_sacre_formule3.gif";
				break;
				default:
					$src="{{ productDir() }}/images/sceau_sacre_formule1.gif";
			}

			return $src;
	}

	// Function pour recuperer les donnÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â© de formulaire de validation de voeux >> HH
	public function setFormvoeux(){

		if(\Yii::app()->request->getPost( 'Vname', false ))
		{
			$InvoiceVoeux =  new \Business\Invoice();

				// 2: POST ; 1: GET
			$Vname	= \Yii::app()->request->getPost( 'Vname', false );
			$VdateNaissance	= \Yii::app()->request->getPost( 'VdateNaissance', false );
			$Voeu1	= \Yii::app()->request->getPost( 'voeu1', false );
			$Voeu2	= \Yii::app()->request->getPost( 'voeu2', false );
			$Voeu3	= \Yii::app()->request->getPost( 'voeu3', false );
			$valid	= \Yii::app()->request->getPost( 'valid', false );
			$email	= \Yii::app()->request->getQuery( 'm', false );
			$ref	= \Yii::app()->request->getQuery( 'ref', false );
			$InvoiceVoeux->ValideVoeux($Vname,$VdateNaissance,$Voeu1,$Voeu2,$Voeu3,$email,$ref,$valid);
		}
	}

	// Function pour recuperer les donnÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â© de formulaire de validation de voeux >> HH
	public function getVoeuxBDD(){

		$email	= \Yii::app()->request->getQuery( 'm', false );
		$ref	= \Yii::app()->request->getQuery( 'ref', false );

		$invoices =  new \Business\Invoice();
		$invoice = $invoices->findByAttributes(array('emailUser' => $email, 'invoiceStatus' => 2, 'campaign' => $ref));

			if(is_object($invoice))
			{
					if( !count($invoice->RecordInvoice) <= 0 )
					{
						$RecordInvoiceAnnexe = $invoice->RecordInvoice[0]->RecordInvoiceAnnexe;
						if(is_object($RecordInvoiceAnnexe)){
							$Voeux = $RecordInvoiceAnnexe->productExt->Voeux;
						}else{

							$Voeux = NULL;
							return false;
						}
					}
		    }else{

				$Voeux = NULL;
				return false;
			}

		return $Voeux = (object) $Voeux;
	}

	// Fonction de Comparaison entre Date sys et de+X
	public function ComparDate(){

			if(isset($_GET['de'])){
				$date    		= $_GET['de'];
			}

			$dateTokens 	= explode('/', $date );
			$de_time 		= strtotime($dateTokens[0] . '/' . $dateTokens[1] . '/' . $dateTokens[2]);

			if(strtotime(date("m/d/Y"))>=strtotime('+31 days', $de_time) && strtotime(date("m/d/Y"))<strtotime('+62 days', $de_time))
				return true;

			if( strtotime(date("m/d/Y"))>=strtotime('+62 days', $de_time))
				return false;

			if(strtotime(date("m/d/Y"))<strtotime('+31 days', $de_time))
			   return false;

	}
//::::::::::::::::::::::::::::::: Numeros de chances Aleatoire ::::::::::::::::::::::::::::::::::
	public function getNumsChance($format,$sep,$min1,$max1,$min2,$max2)
		{
			$email	= \Yii::app()->request->getQuery( 'm', false );
			$invoices =  new \Business\Invoice;
			$refProduct			= $this->getProduct()->ref;

			
			$invoice = $invoices->searchInvoiceForUserAndProduct($email, $refProduct);
			$tabInvoice = $invoice->getData();

			if(isset($tabInvoice[0]->id))
			{
		        $MyInvoice	= \Business\Invoice::load($tabInvoice[0]->id);

				if( !count($MyInvoice->RecordInvoice) <= 0 )
				{
						$RecordInvoiceAnnexe = $MyInvoice->RecordInvoice[0]->RecordInvoiceAnnexe;

						if(is_object($RecordInvoiceAnnexe))
						{
								if(isset($RecordInvoiceAnnexe->productExt->NumsChance))
								{
								  $NumsChance = $RecordInvoiceAnnexe->productExt->NumsChance;
								  return $NumsChance = $NumsChance->NumsChance;
								}
							 else{

								  $invoices->setNumsChance($email,$refProduct,$format,$sep,$min1,$max1,$min2,$max2);

								 }
					  }
					  else
					  {
					   	$invoices->setNumsChance($email,$refProduct,$format,$sep,$min1,$max1,$min2,$max2);

					  }
				}
		    }
			else{

				$invoices->setNumsChance($email,$refProduct,$format,$sep,$min1,$max1,$min2,$max2);

			}
	}


/**	 * @Author CHNIBER Zakaria
	 * @param string $separateur : sÃ©parateur entre les chiffres de chaque sÃ©rie
	 * @param string $sepS  : sÃ©parateur entre les sÃ©ries
	 * @param int $nbreNC : nombre de chiffres Ã  gÃ©nÃ©rer
	 * @param int $max : nombre maximal que peut atteindre un chiffre
	 * @param int $nbreS : nombre de sÃ©rie Ã  gÃ©nÃ©rer
	 * @param int $mid : la valeur Ã  laquelle serait infÃ©rieur le nombre $nbreMid de chiffres alÃ©atoires
	 * @param int $nbreMid : nombre de chiffres Ã  gÃ©nÃ©rer qui soit infÃ©rieur Ã  $mid
	 * @param int $page : la page dans laquelle on souhaite effectuer notre appel
	 * @param int $appel : 0: retourne une chaine de caractere, 1: retourne un tableau des sÃ©ries
	 *
	 * @return string ou array selon $appel
	 */
	// MÃ©thode finalisÃ©e pour les numÃ©ros de chance par Zakaria CHNIBER
public function getMultiNumsChanceVF($separateur,$sepS,$nbreNC,$max,$nbreS,$mid,$nbreMid,$page,$appel){

	$email	= \Yii::app()->request->getQuery( 'm', false );
	$idpr = $this->getProduct()->id;
	$user = \Business\User::loadByEmail($email);
	$invoices =  new \Business\Invoice;
	$refProduct			= $this->getProduct()->ref;
	$invoice = $invoices->searchInvoiceForUserAndProduct($email, $refProduct);
	$tabInvoice = $invoice->getData();

	if (!empty($user)){
		$iduser= $user->id;
		$numchance =  new \Business\NumChance;
		$numRecord = numchance::model()->findByAttributes(array('id_user'=>$iduser,'id_product'=>$idpr));
		if ($page==111)
		{		// NumÃ©roes de chance uniquement dans la page PRO
			if (empty($numRecord))
			{
				if (isset($tabInvoice[0]->id))
				{
					$MyInvoice	= \Business\Invoice::load($tabInvoice[0]->id);

					if ( !count($MyInvoice->RecordInvoice) <= 0 )
					{
							$RecordInvoiceAnnexe = $MyInvoice->RecordInvoice[0]->RecordInvoiceAnnexe;

							if (is_object($RecordInvoiceAnnexe))
							{
									if (isset($RecordInvoiceAnnexe->productExt->NumsChance))
									{
									  $NumsChance = $RecordInvoiceAnnexe->productExt->NumsChance;
									  $chaine=explode($sepS,$NumsChance->NumsChance);
										if ($appel)

											return $chaine;
										else
											return $NumsChance->NumsChance;

									}
									else{

									  return $invoices->setMultiNumsChanceVF($separateur,$sepS,$email,$refProduct,$nbreNC,$max,$nbreS,$mid,$nbreMid,$appel);

									 }
						  }
						  else
						  {
							return $invoices->setMultiNumsChanceVF($separateur,$sepS,$email,$refProduct,$nbreNC,$max,$nbreS,$mid,$nbreMid,$appel);

						  }
					}
				}
				else
				{

					return $invoices->setMultiNumsChanceVF($separateur,$sepS,$email,$refProduct,$nbreNC,$max,$nbreS,$mid,$nbreMid,$appel);

				}
			}

			else{
				
				$chaine=explode($sepS,$numRecord->numChance);

					if ($appel)
								return $chaine;
							else
								return $numRecord->numChance;
				
			}


		}else{
			// NumÃ©ros dans la page LDV
			if (empty($numRecord))
				{
					$Rand="";
					for($i=1;$i<=$nbreS;$i++)
					{

						$RandSup10=array();
						$RandInf10=array();
						// Partie >10
						$sup=0;
						$inf=0;
						while($sup<$nbreNC-$nbreMid)
						{
							$RandSup10Nbre=mt_rand($mid,$max);
							 if (!in_array($RandSup10Nbre,$RandSup10))
								 {
									$RandSup10[]=$RandSup10Nbre;
									$sup++;
								 }

						}
							// Partie <10
							while($inf<$nbreMid)
							{

								$RandInf10Nbre=mt_rand(1,$mid-1);
								 if (!in_array($RandInf10Nbre,$RandInf10))
									 {
										$RandInf10[]=$RandInf10Nbre;
										$inf++;
									 }

							}
						// ConcatÃ©ner les 2 tableaux <10 et >10
						$Rand1 = array_merge($RandSup10,$RandInf10);
						// Changer l'ordre des element de tableau
						shuffle($Rand1);
						// Transformer le tableau de numero Ã  une chaine.
						$Rand1=implode($separateur,$Rand1).$sepS;
						$Rand.=$Rand1;
					}

						$Rand=rtrim($Rand,$sepS);
						// Mise Ã  jour de la BDD
						$numchance->numChance=$Rand;
						$numchance->id_user=$iduser;
						$numchance->id_product=$idpr;
						$numchance->save();
						$numRecord = numchance::model()->findByAttributes(array('id_user'=>$iduser,'id_product'=>$idpr));
						// SÃ©paration des sÃ©ries
						$chaine=explode($sepS,$numRecord->numChance);
						// Test de sÃ©paration de sÃ©ries
						if ($appel)
								return $chaine;
							else
								return $numRecord->numChance;

				}
				else
				{
					$chaine=explode($sepS,$numRecord->numChance);

					if ($appel)
								return $chaine;
							else
								return $numRecord->numChance;
					}

			}
	}
}

/**	 * @Author CHNIBER Zakaria
	 * @param string $separateur : sÃ©parateur entre les chiffres de chaque sÃ©rie
	 * @param string $sepS  : sÃ©parateur entre les sÃ©ries
	 * @param int $nbreNC : nombre de chiffres Ã  gÃ©nÃ©rer
	 * @param int $max : nombre maximal que peut atteindre un chiffre
	 * @param int $nbreS : nombre de sÃ©rie Ã  gÃ©nÃ©rer
	 * @param int $mid : la valeur Ã  laquelle serait infÃ©rieur le nombre $nbreMid de chiffres alÃ©atoires
	 * @param int $nbreMid : nombre de chiffres Ã  gÃ©nÃ©rer qui soit infÃ©rieur Ã  $mid
	 * @param int $page : la page dans laquelle on souhaite effectuer notre appel
	 * @param int $appel : 0: retourne une chaine de caractere, 1: retourne un tableau des sÃ©ries
	 * @param int $exceptions: matrice des valeurs auxquels les chiffres seraient diffÃ©rents correspondant a chaque signe
	 *
	 * @return string ou array selon $appel
	 */

//AmÃ©lioration effectuÃ©e par CHNIBER Zakaria le 01/04/2016
public function getNumsChanceVF($separateur,$sepS,$nbreNC,$max,$nbreS,$mid,$nbreMid,$page,$appel,$exceptions){

	$email	= \Yii::app()->request->getQuery( 'm', false );
	$idpr = $this->getProduct()->id;
	$user = \Business\User::loadByEmail($email);
	$invoices =  new \Business\Invoice;
	$refProduct			= $this->getProduct()->ref;
	$invoice = $invoices->searchInvoiceForUserAndProduct($email, $refProduct);
	$tabInvoice = $invoice->getData();
	//RÃ©cupÃ¨re le numÃ©ro du signe astrologique
	$NumberOfSigne=$user->getNumberSignAstro();
	// Parcourt la matrice 'exceptions' avec l'indice du signe
	$exceptions=$exceptions[$NumberOfSigne];

	
	if (!empty($user)){
		$iduser= $user->id;
		$numchance =  new \Business\NumChance;
		$numRecord = numchance::model()->findByAttributes(array('id_user'=>$iduser,'id_product'=>$idpr));
		if ($page==111)
		{		// NumÃ©roes de chance uniquement dans la page PRO
			if (empty($numRecord))
			{
				if (isset($tabInvoice[0]->id))
				{
					$MyInvoice	= \Business\Invoice::load($tabInvoice[0]->id);

					if ( !count($MyInvoice->RecordInvoice) <= 0 )
					{
							$RecordInvoiceAnnexe = $MyInvoice->RecordInvoice[0]->RecordInvoiceAnnexe;
							if (is_object($RecordInvoiceAnnexe))
							{
									if (isset($RecordInvoiceAnnexe->productExt->NumsChance))
									{
									  $NumsChance = $RecordInvoiceAnnexe->productExt->NumsChance;
									  $chaine=explode($sepS,$NumsChance->NumsChance);
										if ($appel)
											return $chaine;
										else
											return $NumsChance->NumsChance;

									}
									else{

									  $invoices->setNumsChanceVF($separateur,$sepS,$email,$refProduct,$nbreNC,$max,$nbreS,$mid,$nbreMid,$exceptions);

									 }
						  }
						  else
						  {
							$invoices->setNumsChanceVF($separateur,$sepS,$email,$refProduct,$nbreNC,$max,$nbreS,$mid,$nbreMid,$exceptions);

						  }
					}
				}
				else
				{

					$invoices->setNumsChanceVF($separateur,$sepS,$email,$refProduct,$nbreNC,$max,$nbreS,$mid,$nbreMid,$exceptions);

				}
			}

			else{
				$chaine=explode($sepS,$numRecord->numChance);

					if($appel)
								return $chaine;
							else
								return $numRecord->numChance;
			}

		}else{
			// NumÃ©ros dans la page LDV
			if (empty($numRecord))
				{
					$Rand="";
					for($i=1;$i<=$nbreS;$i++)
					{

						$RandSup10=array();
						$RandInf10=array();
						// Partie >10
						$sup=0;
						$inf=0;
						while($sup<$nbreNC-$nbreMid)
						{	//GÃ©nÃ©rer les chiffres alÃ©atoires diffÃ©rents de l'array 'exceptions' pour les 12 signes
							$RandSup10Nbre=mt_rand($mid,$max);
							if (!in_array($RandSup10Nbre,$RandSup10))
							{
								if (!empty($exceptions)){
									 if(!in_array($RandSup10Nbre,$exceptions)){
										$RandSup10[]=$RandSup10Nbre;
										$sup++;
									}

								}else{
										$RandSup10[]=$RandSup10Nbre;
										$sup++;
								 }

							}

						}
						// Partie <10
						while($inf<$nbreMid)
						{
								//GÃ©nÃ©rer les chiffres alÃ©atoires diffÃ©rents de l'array 'exceptions' pour les 12 signes
								$RandInf10Nbre=mt_rand(1,$mid-1);
								if (!in_array($RandInf10Nbre,$RandInf10))
								{
										if (!empty($exceptions)){
											if (!in_array($RandInf10Nbre,$exceptions)){
												$RandInf10[]=$RandInf10Nbre;
												$inf++;
												}
											}else{
												$RandInf10[]=$RandInf10Nbre;
												$inf++;
											}


								}

						}
						// ConcatÃ©ner les 2 tableaux <10 et >10
						$Rand1 = array_merge($RandSup10,$RandInf10);

						// Changer l'ordre des elements du tableau
						shuffle($Rand1);
						// Transformer le tableau de numero Ã  une chaine.
						$Rand1=implode($separateur,$Rand1).$sepS;
						$Rand.=$Rand1;
					}

						$Rand=rtrim($Rand,$sepS);
						// Mise Ã  jour de la BDD
						$numchance->numChance=$Rand;
						$numchance->id_user=$iduser;
						$numchance->id_product=$idpr;
						$numchance->save();
						$numRecord = numchance::model()->findByAttributes(array('id_user'=>$iduser,'id_product'=>$idpr));
						// SÃ©paration des sÃ©ries
						$chaine=explode($sepS,$numRecord->numChance);
						// Test de sÃ©paration de sÃ©ries
						if ($appel)
								return $chaine;
							else
								return $numRecord->numChance;

				}
				else
				{
					$chaine=explode($sepS,$numRecord->numChance);

					if ($appel)
					{return $chaine;}
							else
							{return $numRecord->numChance;}
					}

			}
	}
}


/**	 * @Author HDIDOU Saad
	 * @param string $separateur : sÃ©parateur entre les chiffres de chaque sÃ©rie
	 * @param string $sepS  : sÃ©parateur entre les sÃ©ries
	 * @param int $nbreNC : nombre de chiffres Ã  gÃ©nÃ©rer
	 * @param int $max : nombre maximal que peut atteindre un chiffre
	 * @param int $nbreS : nombre de sÃ©rie Ã  gÃ©nÃ©rer
	 * @param int $mid : la valeur Ã  laquelle serait infÃ©rieur le nombre $nbreMid de chiffres alÃ©atoires
	 * @param int $nbreMid : nombre de chiffres Ã  gÃ©nÃ©rer qui soit infÃ©rieur Ã  $mid
	 * @param int $page : la page dans laquelle on souhaite effectuer notre appel
	 * @param int $appel : 0: retourne une chaine de caractere, 1: retourne un tableau des sÃ©ries
	 * @param int $signe : 1 si la fid est de type 12 signes, 0 sinon
	 * @param int $exceptions: si la fid n'est pas de type 12 signes: tableau qui contient les chiffres Ã  exclure 
							   sinon: matrice qui contient les chiffres Ã  exclure selon le signe astrologique
	 *
	 * @return string ou array selon $appel
	 */

//AmÃ©lioration effectuÃ©e par HDIDOU Saad le 30/06/2016
public function getNumsChanceWithExceptionVF($separateur,$sepS,$nbreNC,$max,$nbreS,$mid,$nbreMid,$page,$appel,$signe,$exceptions){

	$email	= \Yii::app()->request->getQuery( 'm', false );
	
	$idpr = $this->getProduct()->id;
	$user = \Business\User::loadByEmail($email);
	$invoices =  new \Business\Invoice;
	$refProduct			= $this->getProduct()->ref;
	$invoice = $invoices->searchInvoiceForUserAndProduct($email, $refProduct);
	$tabInvoice = $invoice->getData();
	//si la fid est de type 12 signes
	if($signe==1){
	//RÃ©cupÃ¨re le numÃ©ro du signe astrologique
	$NumberOfSigne=$this->getUser()->getNumberSignAstro();
	// Parcourt la matrice 'exceptions' avec l'indice du signe
	$exceptions=$exceptions[$NumberOfSigne];
	}

	
	if (!empty($user)){
		$iduser= $user->id;
		$numchance =  new \Business\NumChance;
		$numRecord = numchance::model()->findByAttributes(array('id_user'=>$iduser,'id_product'=>$idpr));
		if ($page==111)
		{		// NumÃ©roes de chance uniquement dans la page PRO
			if (empty($numRecord))
			{
				if (isset($tabInvoice[0]->id))
				{
					$MyInvoice	= \Business\Invoice::load($tabInvoice[0]->id);

					if ( !count($MyInvoice->RecordInvoice) <= 0 )
					{
							$RecordInvoiceAnnexe = $MyInvoice->RecordInvoice[0]->RecordInvoiceAnnexe;
							if (is_object($RecordInvoiceAnnexe))
							{
									if (isset($RecordInvoiceAnnexe->productExt->NumsChance))
									{
									  $NumsChance = $RecordInvoiceAnnexe->productExt->NumsChance;
									  $chaine=explode($sepS,$NumsChance->NumsChance);
										if ($appel){
											{return $chaine;}
										}
										else{
											{return $NumsChance->NumsChance;}
										}

									}
									else{

									  return $invoices->setNumsChanceVF($separateur,$sepS,$email,$refProduct,$nbreNC,$max,$nbreS,$mid,$nbreMid,$exceptions);

									 }
						  }
						  else
						  {
							$invoices->setNumsChanceVF($separateur,$sepS,$email,$refProduct,$nbreNC,$max,$nbreS,$mid,$nbreMid,$exceptions);

						  }
					}
				}
				else
				{

					return $invoices->setNumsChanceVF($separateur,$sepS,$email,$refProduct,$nbreNC,$max,$nbreS,$mid,$nbreMid,$exceptions);

				}
			}

			else{
				$chaine=explode($sepS,$numRecord->numChance);

					if($appel){
						
					
								return $chaine;
					}
							else{
								
								return $numRecord->numChance;
							}
			}

		}else{
			// NumÃ©ros dans la page LDV
			if (empty($numRecord))
				{
					$Rand="";
					for($i=1;$i<=$nbreS;$i++)
					{

						$RandSup10=array();
						$RandInf10=array();
						// Partie >10
						$sup=0;
						$inf=0;
						while($sup<$nbreNC-$nbreMid)
						{	//GÃ©nÃ©rer les chiffres alÃ©atoires diffÃ©rents de l'array 'exceptions' pour les 12 signes
							$RandSup10Nbre=mt_rand($mid,$max);
							if (!in_array($RandSup10Nbre,$RandSup10))
							{
								if (!empty($exceptions)){
									 if(!in_array($RandSup10Nbre,$exceptions)){
										$RandSup10[]=$RandSup10Nbre;
										$sup++;
									}

								}else{
										$RandSup10[]=$RandSup10Nbre;
										$sup++;
								 }

							}

						}
						// Partie <10
						while($inf<$nbreMid)
						{
								//GÃ©nÃ©rer les chiffres alÃ©atoires diffÃ©rents de l'array 'exceptions' pour les 12 signes
								$RandInf10Nbre=mt_rand(1,$mid-1);
								if (!in_array($RandInf10Nbre,$RandInf10))
								{
										if (!empty($exceptions)){
											if (!in_array($RandInf10Nbre,$exceptions)){
												$RandInf10[]=$RandInf10Nbre;
												$inf++;
												}
											}else{
												$RandInf10[]=$RandInf10Nbre;
												$inf++;
											}


								}

						}
						// ConcatÃ©ner les 2 tableaux <10 et >10
						$Rand1 = array_merge($RandSup10,$RandInf10);

						// Changer l'ordre des elements du tableau
						shuffle($Rand1);
						// Transformer le tableau de numero Ã  une chaine.
						$Rand1=implode($separateur,$Rand1).$sepS;
						$Rand.=$Rand1;
					}

						$Rand=rtrim($Rand,$sepS);
						// Mise Ã  jour de la BDD
						$numchance->numChance=$Rand;
						$numchance->id_user=$iduser;
						$numchance->id_product=$idpr;
						$numchance->save();
						$numRecord = numchance::model()->findByAttributes(array('id_user'=>$iduser,'id_product'=>$idpr));
						// SÃ©paration des sÃ©ries
						$chaine=explode($sepS,$numRecord->numChance);
						// Test de sÃ©paration de sÃ©ries
						if ($appel)
								return $chaine;
							else
								return $numRecord->numChance;

				}
				else
				{
					$chaine=explode($sepS,$numRecord->numChance);

					if ($appel)
								return $chaine;
							else
								return $numRecord->numChance;
					}

			}
	}
}


//::::::::::::::::::::::::::::::: N series de Numeros de chances Aleatoire  dÃ©veloppÃ©e par Zakaria::::::::::::::::::::::::::::::::::
	public function getMultiNumsChanceV3($nbreNC,$max,$nbreS,$mid,$nbreMid)
	{

		$email	= \Yii::app()->request->getQuery( 'm', false );
		$idpr = $this->getProduct()->id;
		$user = \Business\User::loadByEmail($email);

		if(!empty($user))
		{
			$iduser= $user->id;
			$numchance =  new \Business\NumChance;
			$numRecord = numchance::model()->findByAttributes(array('id_user'=>$iduser,'id_product'=>$idpr));

			if(empty($numRecord))
			{
				$Rand="";
				for($i=1;$i<=$nbreS;$i++)
				{

					$RandSup10=array();
					$RandInf10=array();
					// Partie >10
					$sup=0;
					$inf=0;
				 	while($sup<$nbreNC-$nbreMid)
					{
						$RandSup10Nbre=mt_rand($mid,$max);
						 if (!in_array($RandSup10Nbre,$RandSup10))
							 {
								$RandSup10[]=$RandSup10Nbre;
								$sup++;
							 }

					}
					// Partie <10
					while($inf<$nbreMid)
					{

						$RandInf10Nbre=mt_rand(1,$mid-1);
						 if (!in_array($RandInf10Nbre,$RandInf10))
							 {
								$RandInf10[]=$RandInf10Nbre;
								$inf++;
							 }

					}
					// Concatiner les 2 tableaux <10 et >10
					$Rand1 = array_merge($RandSup10,$RandInf10);
					// Changer l'ordre des element de tableau
					shuffle($Rand1);
					// Transformer le tableau de numero Ã  une la chaine.
					$Rand1=implode(" - ",$Rand1)."|";
					$Rand.=$Rand1;
				}

				$Rand=rtrim($Rand,"|");

				$numchance->numChance=$Rand;
				$numchance->id_user=$iduser;
				$numchance->id_product=$idpr;
				$numchance->save();
				$numRecord = numchance::model()->findByAttributes(array('id_user'=>$iduser,'id_product'=>$idpr));
				return $numRecord->numChance;

			}else
			{
				return $numRecord->numChance;
			}
		}

	}


	//::::::::::::::::::::::::::::::: N series de Numeros de chances Aleatoire  Mise ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â  jour par HANIA::::::::::::::::::::::::::::::::::
	public function getMultiNumsChance($nbreNC,$max,$nbreS)
		{
			$email	= \Yii::app()->request->getQuery( 'm', false );
			$invoices =  new \Business\Invoice;
			$refProduct			= $this->getProduct()->ref;

			
			$invoice = $invoices->searchInvoiceForUserAndProduct($email, $refProduct);
			$tabInvoice = $invoice->getData();

			if(isset($tabInvoice[0]->id))
			{
		        $MyInvoice	= \Business\Invoice::load($tabInvoice[0]->id);

				if( !count($MyInvoice->RecordInvoice) <= 0 )
				{
						$RecordInvoiceAnnexe = $MyInvoice->RecordInvoice[0]->RecordInvoiceAnnexe;

						if(is_object($RecordInvoiceAnnexe))
						{
								if(isset($RecordInvoiceAnnexe->productExt->NumsChance))
								{
								  $NumsChance = $RecordInvoiceAnnexe->productExt->NumsChance;
								  return $NumsChance = $NumsChance->NumsChance;
								}
							 else{

								  $invoices->setMultiNumsChance($email,$refProduct,$nbreNC,$max,$nbreS);

								 }
					  }
					  else
					  {
					   	$invoices->setMultiNumsChance($email,$refProduct,$nbreNC,$max,$nbreS);

					  }
				}
		    }
			else{

				$invoices->setMultiNumsChance($email,$refProduct,$nbreNC,$max,$nbreS);

			}
	}

//:::::::::::::::::::::::::::::::FIN  N series de Numeros de chances Aleatoire::::::::::::::::::::::::::::::::::
	// function retourne mercredi apres 3 semaines
	public function getMercredi()
	{
		$sd = date('d F Y');
		if(isset($_GET['sd'])){
			if(!empty($_GET['sd']))
				$sd=$_GET['sd'];
		}
		$duree = 22;
		$array = array();
		for ($i = 0; $i <= $duree; $i++)
		{

			$dateDepartTimestamp = strtotime($sd);
			$dateFin = date('d-m-Y', strtotime('+'.$i.' days' , $dateDepartTimestamp ));
			$datetemp = explode("-",$dateFin);
			$datetemp = $datetemp[2].'/'.$datetemp[1].'/'.$datetemp[0];
			if (date('w', strtotime($datetemp)) == 3)
			{
				$res = date('d F Y', strtotime($datetemp));
				array_push($array, $res);
			}
		}
		$datevar = $array[sizeof($array)-1];
		return $datevar;
	}


	//get5SymbolName retourne les 5 premiÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¨res lettres de la chaine de caractÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¨re en Symbole Mongole
	public function get5SymbolName()
	{

		$pUrlParam					= \Yii::app()->request->getParam('p');
		$firstName = preg_replace('/\PL/u', '', strip_tags($pUrlParam));

		$alphaWithAccents 	 = array('Ã€', 'Ã�', 'Ã‚', 'Ãƒ', 'Ã„', 'Ã…', 'Ã†', 'Ã‡', 'Ãˆ', 'Ã‰', 'ÃŠ', 'Ã‹', 'ÃŒ', 'Ã�', 'ÃŽ', 'Ã�', 'Ã�', 'Ã‘', 'Ã’', 'Ã“', 'Ã”', 'Ã•', 'Ã–', 'Ã˜', 'Ã™', 'Ãš', 'Ã›', 'Ãœ', 'Ã�', 'ÃŸ', 'Ã ', 'Ã¡', 'Ã¢', 'Ã£', 'Ã¤', 'Ã¥', 'Ã¦', 'Ã§', 'Ã¨', 'Ã©', 'Ãª', 'Ã«', 'Ã¬', 'Ã­', 'Ã®', 'Ã¯', 'Ã±', 'Ã²', 'Ã³', 'Ã´', 'Ãµ', 'Ã¶', 'Ã¸', 'Ã¹', 'Ãº', 'Ã»', 'Ã¼', 'Ã½', 'Ã¿', 'Ä€', 'Ä�', 'Ä‚', 'Äƒ', 'Ä„', 'Ä…', 'Ä†', 'Ä‡', 'Äˆ', 'Ä‰', 'ÄŠ', 'Ä‹', 'ÄŒ', 'Ä�', 'ÄŽ', 'Ä�', 'Ä�', 'Ä‘', 'Ä’', 'Ä“', 'Ä”', 'Ä•', 'Ä–', 'Ä—', 'Ä˜', 'Ä™', 'Äš', 'Ä›', 'Äœ', 'Ä�', 'Äž', 'ÄŸ', 'Ä ', 'Ä¡', 'Ä¢', 'Ä£', 'Ä¤', 'Ä¥', 'Ä¦', 'Ä§', 'Ä¨', 'Ä©', 'Äª', 'Ä«', 'Ä¬', 'Ä­', 'Ä®', 'Ä¯', 'Ä°', 'Ä±', 'Ä²', 'Ä³', 'Ä´', 'Äµ', 'Ä¶', 'Ä·', 'Ä¹', 'Äº', 'Ä»', 'Ä¼', 'Ä½', 'Ä¾', 'Ä¿', 'Å€', 'Å�', 'Å‚', 'Åƒ', 'Å„', 'Å…', 'Å†', 'Å‡', 'Åˆ', 'Å‰', 'ÅŒ', 'Å�', 'ÅŽ', 'Å�', 'Å�', 'Å‘', 'Å’', 'Å“', 'Å”', 'Å•', 'Å–', 'Å—', 'Å˜', 'Å™', 'Åš', 'Å›', 'Åœ', 'Å�', 'Åž', 'ÅŸ', 'Å ', 'Å¡', 'Å¢', 'Å£', 'Å¤', 'Å¥', 'Å¦', 'Å§', 'Å¨', 'Å©', 'Åª', 'Å«', 'Å¬', 'Å­', 'Å®', 'Å¯', 'Å°', 'Å±', 'Å²', 'Å³', 'Å´', 'Åµ', 'Å¶', 'Å·', 'Å¸', 'Å¹', 'Åº', 'Å»', 'Å¼', 'Å½', 'Å¾', 'Å¿', 'Æ’', 'Æ ', 'Æ¡', 'Æ¯', 'Æ°', 'Ç�', 'ÇŽ', 'Ç�', 'Ç�', 'Ç‘', 'Ç’', 'Ç“', 'Ç”', 'Ç•', 'Ç–', 'Ç—', 'Ç˜', 'Ç™', 'Çš', 'Ç›', 'Çœ', 'Çº', 'Ç»', 'Ç¼', 'Ç½', 'Ç¾', 'Ç¿');
	  	$alphaWithoutAccents = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o');
		$alphaSpecialArray	 = array("á  ","á¡‹","á¡—","á ³","á¡”","á¡«","á ­","á ¬","á ¢","á¡…","á¡†","á ±","á §","á ¥","á¡†","á¡„","á ¼","á¡†","á¡ˆ","á¡—","á¡’","á ¾","á ²","á¡¨","á¡„","á¡‚");
		$alphaFrenchArray	 = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");

		if($firstName != ""){
			$key = "";$FiveSymbolName = "";
			$firstNameChars = str_replace($alphaWithAccents, $alphaWithoutAccents, $firstName);

			for ($i=0;$i<5;$i++){
				if($i>=strlen($firstNameChars)) break;
				else{
				$key  = array_search(strtoupper($firstNameChars[$i]), $alphaFrenchArray);
				$FiveSymbolName     .= $alphaSpecialArray[$key]." - ";
				}
			}

			return "<span style=\"font-family: 'Mongolian Baiti' 'Baga Chagan', 'Code2000', 'STFangsong', 'SimSun-18030'\">".substr($FiveSymbolName, 0, -3)."</span>";
		}

	}

	//get5firstName retourne les 5 premiÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¨res lettres de la chaine de caractÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¨re
	public function get5firstName()
	{

		$pUrlParam					= \Yii::app()->request->getParam('p');
		$firstName = preg_replace('/\PL/u', '', strip_tags($pUrlParam));

		$alphaWithAccents 	 = array('Ã€', 'Ã�', 'Ã‚', 'Ãƒ', 'Ã„', 'Ã…', 'Ã†', 'Ã‡', 'Ãˆ', 'Ã‰', 'ÃŠ', 'Ã‹', 'ÃŒ', 'Ã�', 'ÃŽ', 'Ã�', 'Ã�', 'Ã‘', 'Ã’', 'Ã“', 'Ã”', 'Ã•', 'Ã–', 'Ã˜', 'Ã™', 'Ãš', 'Ã›', 'Ãœ', 'Ã�', 'ÃŸ', 'Ã ', 'Ã¡', 'Ã¢', 'Ã£', 'Ã¤', 'Ã¥', 'Ã¦', 'Ã§', 'Ã¨', 'Ã©', 'Ãª', 'Ã«', 'Ã¬', 'Ã­', 'Ã®', 'Ã¯', 'Ã±', 'Ã²', 'Ã³', 'Ã´', 'Ãµ', 'Ã¶', 'Ã¸', 'Ã¹', 'Ãº', 'Ã»', 'Ã¼', 'Ã½', 'Ã¿', 'Ä€', 'Ä�', 'Ä‚', 'Äƒ', 'Ä„', 'Ä…', 'Ä†', 'Ä‡', 'Äˆ', 'Ä‰', 'ÄŠ', 'Ä‹', 'ÄŒ', 'Ä�', 'ÄŽ', 'Ä�', 'Ä�', 'Ä‘', 'Ä’', 'Ä“', 'Ä”', 'Ä•', 'Ä–', 'Ä—', 'Ä˜', 'Ä™', 'Äš', 'Ä›', 'Äœ', 'Ä�', 'Äž', 'ÄŸ', 'Ä ', 'Ä¡', 'Ä¢', 'Ä£', 'Ä¤', 'Ä¥', 'Ä¦', 'Ä§', 'Ä¨', 'Ä©', 'Äª', 'Ä«', 'Ä¬', 'Ä­', 'Ä®', 'Ä¯', 'Ä°', 'Ä±', 'Ä²', 'Ä³', 'Ä´', 'Äµ', 'Ä¶', 'Ä·', 'Ä¹', 'Äº', 'Ä»', 'Ä¼', 'Ä½', 'Ä¾', 'Ä¿', 'Å€', 'Å�', 'Å‚', 'Åƒ', 'Å„', 'Å…', 'Å†', 'Å‡', 'Åˆ', 'Å‰', 'ÅŒ', 'Å�', 'ÅŽ', 'Å�', 'Å�', 'Å‘', 'Å’', 'Å“', 'Å”', 'Å•', 'Å–', 'Å—', 'Å˜', 'Å™', 'Åš', 'Å›', 'Åœ', 'Å�', 'Åž', 'ÅŸ', 'Å ', 'Å¡', 'Å¢', 'Å£', 'Å¤', 'Å¥', 'Å¦', 'Å§', 'Å¨', 'Å©', 'Åª', 'Å«', 'Å¬', 'Å­', 'Å®', 'Å¯', 'Å°', 'Å±', 'Å²', 'Å³', 'Å´', 'Åµ', 'Å¶', 'Å·', 'Å¸', 'Å¹', 'Åº', 'Å»', 'Å¼', 'Å½', 'Å¾', 'Å¿', 'Æ’', 'Æ ', 'Æ¡', 'Æ¯', 'Æ°', 'Ç�', 'ÇŽ', 'Ç�', 'Ç�', 'Ç‘', 'Ç’', 'Ç“', 'Ç”', 'Ç•', 'Ç–', 'Ç—', 'Ç˜', 'Ç™', 'Çš', 'Ç›', 'Çœ', 'Çº', 'Ç»', 'Ç¼', 'Ç½', 'Ç¾', 'Ç¿');
	  	$alphaWithoutAccents = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o');

		if($firstName != ""){
			$FiveOriginalChars = "";
			$firstNameChars = str_replace($alphaWithAccents, $alphaWithoutAccents, $firstName);

			for ($i=0;$i<5;$i++){
				if($i>=strlen($firstNameChars)) break;
				else{
				$FiveOriginalChars  .= strtoupper($firstNameChars[$i])." - ";
				}
			}

			return "<span>".substr($FiveOriginalChars, 0, -3)."</span>";
		}
	}


	public function actionDependP()
	{
		$porteur			= \Yii::app()->params['porteur'];

		// Titre de la page :
		$this->pageTitle	= ucfirst( strtolower( \Yii::app()->name ) ).' - '.\Yii::t( 'site', 'dep' );

		$view	= '//'.$porteur.'/dep';

		// Rendu de la page :
		$this->render( $view );
	}


	/**
	 * Retourne la devise du site courant
	 * @return int	devise
	 */
	public function GetDevise()
	{
		return $this->getSite()->devise;
	}

	public function GetIdSite()
	{
		return $this->getSite()->id;
	}

	public function GetIdSubCampaign()
	{
		return $this->getSubCampaign()->id;
	}

	/**
	 * Retourne le mois [M+X]
	 * @return string
	 */
	public function getMonth($dt = null, $lang = null, $type = "sd"){
		\Yii::import( 'ext.DateHelper2' );
		$this->updateInitialShootDateAnaconda();
		return DateHelper2::getMonth($dt, $lang, $type = "sd"); 
	}

	
	
	//::::::::::::::::::::::::: Creer par Rida::::::::::::::::::::::::::::://
		public function CreatelisteIACVC()
	{
		//IACVC : (Introduction,Amour,CGC,VRS,Conclusion)
		$Invoice		    = new \Business\Invoice();
		$InvoiceCanPay      = $Invoice->InvoiceCanPay($this->getUser()->email, $this->getProduct()->ref );
		$refProduct			= $this->getProduct()->ref;
		$listdata=null;
			if($refProduct=="VP" && $InvoiceCanPay!=0){

				$InvoiceIdSelected=$Invoice->searchInvoiceForUserAndProduct( $this->getUser()->email,$this->getProduct()->ref )->getData();
				$TemplateInvoice = new TemplateInvoice('findbyIdInvoice');
				$GetTemplateInvoice = $TemplateInvoice->findbyIdInvoice($InvoiceIdSelected[0]->id)->getData();

				if(count($GetTemplateInvoice)==0){

					$TemplateInvoice=new TemplateInvoice('CreateListeTemplate');
					$TemplateInvoice->CreateListeTemplate($InvoiceIdSelected[0]->id);

				}
			/** Remplire des donnÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©es dÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÂ¢Ã¢â‚¬Å¾Ã‚Â¢introduction dÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÂ¢Ã¢â‚¬Å¾Ã‚Â¢amour CGV VRS conclusion **/
				$listdata=array('introduction'=>array(),'amour'=>array(),'CGV'=>array(),'conclusion'=>array());
				$GetTemplateInvoice=$TemplateInvoice->findbyIdInvoice($InvoiceIdSelected[0]->id,'introduction')->getData();
				foreach($GetTemplateInvoice as $s){
				$listdata['introduction'][]=$s->Text;
				}
					$GetTemplateInvoice=$TemplateInvoice->findbyIdInvoice($InvoiceIdSelected[0]->id,'amour')->getData();
				foreach($GetTemplateInvoice as $s){
				$listdata['amour'][]=$s->Text;
				}
					$GetTemplateInvoice=$TemplateInvoice->findbyIdInvoice($InvoiceIdSelected[0]->id,'CGV')->getData();
				foreach($GetTemplateInvoice as $s){
				$listdata['CGV'][]=$s->Text;
				}
				$GetTemplateInvoice=$TemplateInvoice->findbyIdInvoice($InvoiceIdSelected[0]->id,'VRS')->getData();
				foreach($GetTemplateInvoice as $s){
				$listdata['VRS'][]=$s->Text;
				}
					$GetTemplateInvoice=$TemplateInvoice->findbyIdInvoice($InvoiceIdSelected[0]->id,'conclusion')->getData();
				foreach($GetTemplateInvoice as $s){
				$listdata['conclusion'][]=$s->Text;
				}
			}
			return $listdata;
	}
		/**
	 /**
	 * Retourne BS
	 * @return mot
	 */
	  public function getCurrentBS()
 {
  return ( is_object($this->Context) ) ? $this->Context->bs : false;
 }
 
 /*::::::::::::::::::::::::: Creation d'un enregistrement dans la table userBehavior 
 une fois que le client a ouvrit la page LDV :::::::::::::::::::::::::::::*/
 
 /**	
  * @Author Balkaid Soufiane
  * @desc creation d'un user behavior lors le chargement de la page

  */
 
	public function CreateUserBehavior() { 
		$campaign = $this->getSubCampaign ()->id;
		
		$tr = $this->getCurrentTR (); 
		
		if (isset ( $this->getUser ()->id ) && isset ( $campaign )) {
			if (($this->getUser ()->bannReason == 2 || is_null($this->getUser ()->bannReason )) &&  !is_null($this->getCampaign()->isAnaconda)) {
				$campaignhistorys = new \Business\CampaignHistory ();
				$campaignhistory = $campaignhistorys->seachByIdUSerIdSubCampaign ( $this->getUser ()->id, $campaign );
				$tablecampaignhistorys = $campaignhistory->getData ();
				if (isset ( $tablecampaignhistorys [0] )) { 
					$userBehaviors = new \Business\UserBehavior ();
					  
					$userBehavior = $userBehaviors->searchByIdCampaingHistoryReflation ( $tablecampaignhistorys [0]->getId (), $tr );
					$tableuserBehavior = $userBehavior->getData ();
					
					if (! isset ( $tableuserBehavior [0] )) {
						$userBehaviors->lastDateClick = date ( 'Y-m-d H:i:s' );
						$userBehaviors->reflation = $this->getCurrentTR ();
						$userBehaviors->bdcClicks = 0;
						$userBehaviors->idCampaignHistory = $tablecampaignhistorys [0]->getId ();
						$userBehaviors->create_user_behavior ( $userBehaviors );
					}
				}
			}
		}
	}
	
	/*::::::::::::::::::::::::: Mise Ã  jours du champs bdcClicks de l'enregistrement courrant de la 
	userBehavior une fois que le client a consultÃ© le BDCV :::::::::::::::::::::::::::::*/
	
	/**	 
	 * @Author Balkaid Soufiane
	 * @desc update du champs bdc click par 1 si le client click sur un lien BDC ou bien sur le boutton ascenseur 
	*/
	
	public function actionUpdateBdcClick() {
		$campaign = $this->getSubCampaign ()->id;
		
		$tr = $this->getCurrentTR ();
		
		if (isset ( $this->getUser ()->id ) && isset ( $campaign )) {
			$campaignhistorys = new \Business\CampaignHistory ();
			$campaignhistory = $campaignhistorys->seachByIdUSerIdSubCampaign ( $this->getUser ()->id, $campaign );
			$tablecampaignhistorys = $campaignhistory->getData ();
			if (isset ( $tablecampaignhistorys [0] )) {
				
				$userBehaviors = new \Business\UserBehavior ();
				
				$userBehavior = $userBehaviors->searchByIdCampaingHistoryReflation ( $tablecampaignhistorys [0]->getId (), $tr );
				
				$tableuserBehavior = $userBehavior->getData ();
				$userBehaviors->setBdcClic ( $tableuserBehavior [0]->getID () );
			}
		}
	}

	//::::::::::::::::::::::::: Un Numero de chance Aleatoire AddaptÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â© par Rida::::::::::::::::::::::::::::://
		public function getOneNumChanceV2($nbreNC,$max,$nbreS)
	{
			$email	= \Yii::app()->request->getQuery( 'm', false );
			$invoices =  new \Business\Invoice;
			$refProduct			= $this->getProduct()->ref;

			
			$invoice = $invoices->searchInvoiceForUserAndProduct($email, $refProduct);
			$tabInvoice = $invoice->getData();

			if(isset($tabInvoice[0]->id))
			{
		        $MyInvoice	= \Business\Invoice::load($tabInvoice[0]->id);

				if( !count($MyInvoice->RecordInvoice) <= 0 )
				{
						$RecordInvoiceAnnexe = $MyInvoice->RecordInvoice[0]->RecordInvoiceAnnexe;

						if(is_object($RecordInvoiceAnnexe))
						{
								if(isset($RecordInvoiceAnnexe->productExt->NumsChance))
								{
								  $NumsChance = $RecordInvoiceAnnexe->productExt->NumsChance;
								  return $NumsChance = $NumsChance->NumsChance;
								}
							 else{

								  $invoices->setOneNumChanceV2($email,$refProduct,$nbreNC,$max,$nbreS);

								 }
					  }
					  else
					  {
					   	$invoices->setOneNumChanceV2($email,$refProduct,$nbreNC,$max,$nbreS);

					  }
				}
		    }
			else{

				$invoices->setOneNumChanceV2($email,$refProduct,$nbreNC,$max,$nbreS);

			}
	}

//:::::::::::::::::::::::::::::::FIN Un Numero de chance Aleatoire AddaptÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â© par Rida::::::::::::::::::::::::::::::::::

	/*************** Fonction d'Ajout de Multi Numero de jour avec un interval definit  ***************** >> Creer par soufiane*/
	public function getMultiJoursAleatoir($numSerie,$nbreNC,$min,$max,$nbreS)
		{
			$email	= \Yii::app()->request->getQuery( 'm', false );
			$invoices =  new \Business\Invoice;
			$refProduct	= $this->getProduct()->ref;

			
			$invoice = $invoices->searchInvoiceForUserAndProduct($email, $refProduct);
			$tabInvoice = $invoice->getData();
			$NumsJours='';
			if(isset($tabInvoice[0]->id))
			{
		        $MyInvoice	= \Business\Invoice::load($tabInvoice[0]->id);

				if( count($MyInvoice->RecordInvoice) > 0 )
				{
						$RecordInvoiceAnnexe = $MyInvoice->RecordInvoice[0]->RecordInvoiceAnnexe;

						if(is_object($RecordInvoiceAnnexe))
						{
								if(isset($RecordInvoiceAnnexe->productExt->NumsChance))
								{

								  $NumsChance = $RecordInvoiceAnnexe->productExt->NumsChance;
								  $NumsJours=$NumsChance->NumsChance;
								  $NumsChance = explode('|',$NumsChance->NumsChance);
								 if( $NumsChance[0]<$nbreS) {

								 $invoices->setMultiJoursAleatoir($email,$refProduct,$nbreNC,$min,$max,$NumsJours);
								  }
								  else
								  {

								 return $NumsChance = $NumsChance[$numSerie] ;
								  }
								}
							 else{

								  $invoices->setMultiJoursAleatoir($email,$refProduct,$nbreNC,$min,$max,$NumsJours);

								 }
					  }
					  else
					  {

					   	$invoices->setMultiJoursAleatoir($email,$refProduct,$nbreNC,$min,$max,$NumsJours);

					  }
				}
		    }
			else{

				$invoices->setMultiJoursAleatoir($email,$refProduct,$nbreNC,$min,$max,$NumsJours);

			}
	}




	/**
	 * Retourne la valeur d'un paramÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©tre
	 * @return string
	 */
	public function getParamValue($par){
		$value = \Yii::app()->request->getParam($par);

		if(isset($value) && !empty($value))
			return $value;
	}


		// getXSymbolName retourne les X premiÃ¨res lettres de la chaine de caractÃ¨re en Symbole Mongole : wafae cheglal

	/**
	 * Permettre l'affichage d'un nombre bien prÃ©cis des premiÃ¨res lettres du prÃ©nom du client en Symbole Mangole
	 *
	 * @param int : nombre de symboles
	 *
	 * @return string
	 */
	public function getXSymbolName($number)
	{

		$pUrlParam					= \Yii::app()->request->getParam('p');
		$firstName = preg_replace('/\PL/u', '', strip_tags($pUrlParam));

		$alphaWithAccents 	 = array('Ã€', 'Ã�', 'Ã‚', 'Ãƒ', 'Ã„', 'Ã…', 'Ã†', 'Ã‡', 'Ãˆ', 'Ã‰', 'ÃŠ', 'Ã‹', 'ÃŒ', 'Ã�', 'ÃŽ', 'Ã�', 'Ã�', 'Ã‘', 'Ã’', 'Ã“', 'Ã”', 'Ã•', 'Ã–', 'Ã˜', 'Ã™', 'Ãš', 'Ã›', 'Ãœ', 'Ã�', 'ÃŸ', 'Ã ', 'Ã¡', 'Ã¢', 'Ã£', 'Ã¤', 'Ã¥', 'Ã¦', 'Ã§', 'Ã¨', 'Ã©', 'Ãª', 'Ã«', 'Ã¬', 'Ã­', 'Ã®', 'Ã¯', 'Ã±', 'Ã²', 'Ã³', 'Ã´', 'Ãµ', 'Ã¶', 'Ã¸', 'Ã¹', 'Ãº', 'Ã»', 'Ã¼', 'Ã½', 'Ã¿', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'Ã�', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', '?', '?', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', '?', '?', 'L', 'l', 'N', 'n', 'N', 'n', 'N', 'n', '?', 'O', 'o', 'O', 'o', 'O', 'o', 'Å’', 'Å“', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'Å ', 'Å¡', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Å¸', 'Z', 'z', 'Z', 'z', 'Å½', 'Å¾', '?', 'Æ’', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', '?', '?', '?', '?', '?', '?');
	  	$alphaWithoutAccents = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o');
		$alphaSpecialArray  = array('á  ','á¡‹','á¡—','á ³','á¡”','á¡«','á ­','á ¬','á ¢','á¡…','á¡†','á ±','á §','á ¥','á¡†','á¡„','á ¼','á¡†','á¡ˆ','á¡—','á¡’','á ¾','á ²','á¡¨','á¡„','á¡‚');
		$alphaFrenchArray	 = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');

		if($firstName != ""){
			$key = "";$FiveSymbolName = "";
			$firstNameChars = str_replace($alphaWithAccents, $alphaWithoutAccents, $firstName);
			$firstNameCharsLength = strlen($firstNameChars);

			for ($i=0;$i<$number;$i++){
				if($i>= $firstNameCharsLength) break;
				else{
				$key  = array_search(strtoupper($firstNameChars[$i]), $alphaFrenchArray);
				$FiveSymbolName     .= $alphaSpecialArray[$key]." - ";
				}
			}

			return "<span style=\"font-family: 'Menk Hawang Tig', 'Menk Qagan Tig', 'Menk Garqag Tig', 'Menk Har_a Tig', 'Menk Scnin Tig', 'Mongolian Baiti', 'Mongol Usug', 'MongolianScript', 'Code2000', 'Menksoft Qagan', 'STFangsong', 'SimSun-18030'\">".substr($FiveSymbolName, 0, -3)."</span>";
		}

	}
	// appel de la fonction dateCompleteV2

	/**
	 * Permettre l'affichage des dates Ã  programmer selon les exigences des CDC au niveau des blocs Ã  afficher (jour, mois, annÃ©e) ou la forme d'Ã©criture (anglaise, francaise)
	 * @param int $dt : nombre de jours
	 * @param int $m  : nombre de mois
	 * @param string $type_date : sd/de
	 * @param string $lang :	Langue
	 * @param boolean $day : bloc du jour
	 * @param boolean $months : bloc de mois
	 * @param boolean $year : bloc de l'annÃ©e
	 *
	 * @return string
	 */
	 public function getDateCompleteV2($dt=null,$m=null,$type_date=null,$lang=null,$day=null,$months=null,$year=null){
		\Yii::import( 'ext.DateHelper2' );
		$this->updateInitialShootDateAnaconda();
		return DateHelper2::completDateV2($dt,$m,$type_date,$lang,$day,$months,$year);


	}
	/**
	 * Retourne variable masculin/feminin du porteur 
	 */

	public function persoVariablePorteur($varPorteur)
	{
		$porteur = \Yii::app()->params['porteur'];
		
		$var  =  explode('/', $varPorteur);
		
		$varMasculin  =  $var[0]; 
		$varFeminin =  $var[1];
		
		if(substr_count($porteur,"rucker")!=0){
			{return $varMasculin;}	
		}else{
			{return $varFeminin;}
		}
		
	}
	/*fin fct persovariableporteur*/

	// By Ahmed LAM.
	public function getPG() {
		$email = $prod = $port = '';

		if (\Yii::app()->request->getParam( 'm' ) !== NULL) {
			$email = \Yii::app()->request->getParam( 'm' );
		}

		if (\Yii::app()->request->getParam( 'prod' ) !== NULL) {
			$prod = \Yii::app()->request->getParam( 'prod' );
		}

		if (\Yii::app()->request->getParam( 'port' ) !== NULL) {
			$port = \Yii::app()->request->getParam( 'port' );
		} else {
			$port = \Yii::app()->params['porteur'];
		}
		
		return $this->Context->GetPricingGrid($email, $prod, $port);
	}

	

	public function updateInitialShootDateAnaconda(){
	if (isset ( $this->getUser()->id ) && isset ( $this->getCampaign()->id )) 
	{
		if (($this->getUser ()->bannReason == 2 || is_null ( $this->getUser ()->bannReason ))
				&& ! is_null ( $this->getCampaign ()->isAnaconda )) {
	
					$campaignHistory = new \Business\CampaignHistory ();
					$campaignHistorys = $campaignHistory->seachByIdUSerIdSubCampaign ( $this->getUser ()->id, $this->getSubCampaign ()->id );
					$listCampaignsHistorys = $campaignHistorys->getData (); 
	
					if (isset ( $listCampaignsHistorys [0]->initialShootDate )) {
						$sessionSD = Yii::app()->session;
						if(!isset($sessionSD['SD']))
						{
							$sessionSD['SD']= DateTime::createFromFormat('Y-m-d',$listCampaignsHistorys [0]->initialShootDate)->format('m/d/Y');;
						}
						
					}
				}
	}
	
}

	
}

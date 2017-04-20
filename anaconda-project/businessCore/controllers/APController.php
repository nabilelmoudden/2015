<?php
/**
 * Description of AP
 *
 * @author JulienL
 * @package Controllers
 */
class APController extends AdminController
{
	const SESSION_NAME	= 'affiliationTestSession';

	public $layout	= '//ap/menu';

	/**
	 * Initialisation du controleur
	 */
	public function init()
	{
		parent::init();

		// Url de la page de login ( pour les redirections faites par les Rules ) :
		Yii::app()->user->loginUrl = array( '/AP/login' );

		// Default page title :
		$this->setPageTitle( 'Affiliate Platform Administration' );
	}

	// ************************** RULES / FILTER ************************** //
	public function filters()
    {
        return array( 'accessControl' );
    }

	public function accessRules()
    {
        return array(
			array(
				'allow',
				'roles' => array( 'ADMIN', 'AFFILIATION_SERVICE' )
            ),
			array(
				'allow',
				'actions' => array( 'login', 'logout', 'CronHSBounce', 'createSession', 'subID' ),
				'users' => array('*')
			),
            array('deny'),
        );
    }

	// ************************** ACTION ************************** //
	public function actionIndex()
	{
		$this->redirect( Yii::app()->baseUrl.'/index.php/AP/affiliatePlatform' );
	}

	// ************************** Affiliate Platform ************************** //
	public function actionAffiliatePlatform()
	{
		$this->includeJS( 'affiliatePlatform.js' );

		$AP = new \Business\AffiliatePlatform( 'search' );

		if( Yii::app()->request->getParam('delete') )
		{
			// Log l'action courante :
			$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );

			$toDelete	= \Business\AffiliatePlatform::load( Yii::app()->request->getParam('id') );
			if( $toDelete->delete() )
				Yii::app()->user->setFlash( "success", Yii::t( 'common', 'deleteOK' ) );
			else
				Yii::app()->user->setFlash( "error", Yii::t( 'common', 'updateNOK' ) );
		}
		
		print_r($_GET);

		// Filtre recherche :
		if( Yii::app()->request->getParam( 'Business\AffiliatePlatform' ) !== NULL )
			$AP->attributes = Yii::app()->request->getParam( 'Business\AffiliatePlatform' );

		$this->render( '//ap/affiliatePlatform', array( 'AP' => $AP ) );
	}

	public function actionAffiliatePlatformShow()
	{
		// Update
		if( Yii::app()->request->getParam( 'id' ) !== NULL )
		{
			if( !($AP = \Business\AffiliatePlatform::load( Yii::app()->request->getParam( 'id' ) )) )
				return false;
		}
		// Create
		else
			$AP = new \Business\AffiliatePlatform();

		// POST :
		if( Yii::app()->request->getParam( 'Business\AffiliatePlatform' ) !== NULL )
		{
			// Log l'action courante :
			$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );

			$P_AP	= Yii::app()->request->getParam( 'Business\AffiliatePlatform' );
			$P_TC	= Yii::app()->request->getParam( 'Business\TrackingCode' );
			$P_PS	= Yii::app()->request->getParam( 'Business\PromoSite' );
			$idM_S	= Yii::app()->request->getParam( 'Business\idManagerS' );
			$idM_O	= Yii::app()->request->getParam( 'Business\idManagerO' );

			$AP->attributes = $P_AP;

			if( $idM_S > 0 && !$AP->addNewManager( $idM_S, \Business\Manager::TYPE_STRATEGIQUE ) )
				Yii::app()->user->setFlash( "error", Yii::t( 'common', 'updateNOK' ) );

			if( $idM_O > 0 && !$AP->addNewManager( $idM_O, \Business\Manager::TYPE_OPERATIONNEL ) )
				Yii::app()->user->setFlash( "error", Yii::t( 'common', 'updateNOK' ) );

			if( $AP->save() && $AP->updateTrackingCode($P_TC ) && $AP->updatePromoSite( $P_PS['id'] ) )
				Yii::app()->user->setFlash( "success", Yii::t( 'common', 'updateOK' ) );
			else
				Yii::app()->user->setFlash( "error", Yii::t( 'common', 'updateNOK' ) );
		}

		$PromoSite		= new \Business\PromoSite( 'search' );
		$listPromoSite	= $PromoSite->findAll();

		$Site			= new \Business\Site( 'search' );
		$listSite		= $Site->findAll();

		$TC				= $AP->getTrackingCode();
		$PS				= $AP->getPromoSite();

		$Admin			= new \Business\User('search');
		$lAdmin			= $Admin->searchAdmin()->getData();

		$ManagerS		= $AP->getActualManager( \Business\Manager::TYPE_STRATEGIQUE );
		$ManagerO		= $AP->getActualManager( \Business\Manager::TYPE_OPERATIONNEL );

		if( Yii::app()->request->getParam('partialRender') !== NULL )
			$this->renderPartial( '//ap/affiliatePlatformShow', array( 'AP' => $AP, 'lPromoSite' => $listPromoSite, 'lSite' => $listSite, 'TC' => $TC, 'PS' => $PS, 'lAdmin' => $lAdmin, 'ManagerS' => $ManagerS, 'ManagerO' => $ManagerO ) );
		else
			$this->render( '//ap/affiliatePlatformShow', array( 'AP' => $AP, 'lPromoSite' => $listPromoSite, 'lSite' => $listSite, 'TC' => $TC, 'PS' => $PS, 'lAdmin' => $lAdmin ) );
	}

	public function actionAffiliatePlatformSubId()
	{
		if( !($AP = \Business\AffiliatePlatform::load( Yii::app()->request->getParam( 'id' ) )) )
			return false;

		$insertScript					= Yii::app()->request->getParam( 'insertScript' );
		$Search							= new \Business\AffiliatePlatformSubId( 'search' );
		$Search->idAffiliatePlatform	= $AP->id;

		// Filtre recherche :
		if( Yii::app()->request->getParam( 'Business\AffiliatePlatformSubId' ) !== NULL )
			$Search->attributes = Yii::app()->request->getParam( 'Business\AffiliatePlatformSubId' );

		if( Yii::app()->request->getParam('partialRender') !== NULL )
			$this->renderPartial( '//ap/affiliatePlatformSubId', array( 'AP' => $AP, 'Search' => $Search, 'insertScript' => $insertScript ) );
		else
			$this->render( '//ap/affiliatePlatformSubId', array( 'AP' => $AP, 'Search' => $Search ) );
	}

	public function actionAffiliatePlatformSubIdShow()
	{
		if( ($idAP = Yii::app()->request->getParam( 'idAP' )) === NULL )
			return false;

		if( Yii::app()->request->getParam( 'id' ) !== NULL )
		{
			if( !($SubID = \Business\AffiliatePlatformSubId::load( Yii::app()->request->getParam( 'id' ) )) )
				return false;
		}
		else
			$SubID = new \Business\AffiliatePlatformSubId();

		// POST :
		if( Yii::app()->request->getParam( 'delete' ) !== NULL )
		{
			// Log l'action courante :
			$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );

			echo $SubID->delete() ? 'true' : 'false';
			\Yii::app()->end();
		}
		else if( Yii::app()->request->getParam( 'Business\AffiliatePlatformSubId' ) !== NULL )
		{
			// Log l'action courante :
			$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );

			$P_SI	= Yii::app()->request->getParam( 'Business\AffiliatePlatformSubId' );
			$P_TC	= Yii::app()->request->getParam( 'Business\TrackingCode' );
			$P_PS	= Yii::app()->request->getParam( 'Business\PromoSite' );

			$SubID->attributes	= $P_SI;

			if( $SubID->save() && $SubID->updateTrackingCode($P_TC) && $SubID->updatePromoSite( $P_PS['id'] )  )
				Yii::app()->user->setFlash( "success", Yii::t( 'common', 'updateOK' ) );
			else
				Yii::app()->user->setFlash( "error", Yii::t( 'common', 'updateNOK' ) );
		}

		$PromoSite		= new \Business\PromoSite( 'search' );
		$listPromoSite	= $PromoSite->findAll();

		$TC				= $SubID->getTrackingCode();
		$PS				= $SubID->getPromoSite();

		if( Yii::app()->request->getParam('partialRender') !== NULL )
			$this->renderPartial( '//ap/affiliatePlatformSubIdShow', array( 'SubID' => $SubID, 'lPromoSite' => $listPromoSite, 'idAP' => $idAP, 'TC' => $TC, 'PS' => $PS ) );
		else
			$this->render( '//ap/affiliatePlatformSubIdShow', array( 'SubID' => $SubID, 'lPromoSite' => $listPromoSite, 'idAP' => $idAP, 'TC' => $TC, 'PS' => $PS ) );
	}

	public function actionAffiliatePlatformManager()
	{
		if( !($AP = \Business\AffiliatePlatform::load( Yii::app()->request->getParam( 'id' ) )) )
			return false;

		
		$Search							= new \Business\Manager( 'search' );
		$Search->idAffiliatePlatform	= $AP->id;

		// Filtre recherche :
		if( Yii::app()->request->getParam( 'Business\Manager' ) !== NULL )
			$Search->attributes = Yii::app()->request->getParam( 'Business\Manager' );

		if( Yii::app()->request->getParam('partialRender') !== NULL )
			$this->renderPartial( '//ap/affiliatePlatformManager', array( 'AP' => $AP, 'Search' => $Search ) );
		else
			$this->render( '//ap/affiliatePlatformManager', array( 'AP' => $AP, 'Search' => $Search ) );
	}

	// ************************** HARD SOFT BOUNCE ************************** //
	public function actionHSBounce()
	{
		$this->includeJS( 'hsBounce.js' );

		$Emv = false;
		if( !isset(\Yii::app()->params['EMV_ACQ']['login']) || empty(\Yii::app()->params['EMV_ACQ']['login']) )
		{
			Yii::app()->user->setFlash( "warning", Yii::t( 'AP', 'emvError1' ) );
		}
		else
		{
			$Emv = new \Business\EmvExport('search');

			// Filtre recherche :
			if( Yii::app()->request->getParam( 'Business\EmvExport' ) !== NULL )
				$Emv->attributes = Yii::app()->request->getParam( 'Business\EmvExport' );

			// Export CSV :
			if( \Yii::app()->request->getParam( 'export' ) !== NULL )
			{
				// Log l'action courante :
				$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );

				\Yii::import( 'ext.CSVHelper' );

				if( \Yii::app()->request->getParam( 'export' ) > 0 )
				{
					$Emv->type	= \Yii::app()->request->getParam( 'export' );
					$type		= ( $Emv->type == \Business\EmvExport::TYPE_HB ) ? 'Hard' : ( $Emv->type == \Business\EmvExport::TYPE_DESABO ? 'Desabonnes' : 'Soft' );
				}
				else
					$type = 'Hard & Soft & desabonnes';

				CSVHelper::createWithModel( $Emv, \Yii::app()->params['porteur'].' '.$type.' Bounce - '.date( 'd-m-Y' ).'.csv' );
			}
		}

		$this->render( '//ap/HSBounce', array( 'Emv' => $Emv ) );
	}

	public function actionCronHSBounce()
	{
		$isConf = false;
		if( isset(\Yii::app()->params['EMV_ACQ']['login']) && !empty(\Yii::app()->params['EMV_ACQ']['login']) )
		{
			// Log l'action courante :
			$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );

			$HB = new \EmvExportTreatment( \Yii::app()->params['EMV_ACQ'] );

			// Export Hard Bounce :
			if( $HB->insertHardBounce() == false )
				echo 'probleme durant la recuperation des hard bounces ( ACQ ) : '.$HB->getError().'<br />';

			// Export Soft Bounce :
			if( $HB->insertSoftBounce() == false )
				echo 'probleme durant la recuperation des soft bounces ( ACQ ) : '.$HB->getError().'<br />';

			// Export desabonne
			if( $HB->insertDesabonne() == false )
				echo 'probleme durant la recuperation des desabonnes ( ACQ ) : '.$HB->getError().'<br />';

			$isConf = true;
		}

		if( isset(\Yii::app()->params['EMV_FID']['login']) && !empty(\Yii::app()->params['EMV_FID']['login']) )
		{
			// Log l'action courante :
			$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );

			$HB = new \EmvExportTreatment( \Yii::app()->params['EMV_FID'] );

			// Export Hard Bounce :
			if( $HB->insertHardBounce() == false )
				echo 'probleme durant la recuperation des hard bounces ( FID ) : '.$HB->getError().'<br />';

			// Export Soft Bounce :
			if( $HB->insertSoftBounce() == false )
				echo 'probleme durant la recuperation des soft bounces ( FID ) : '.$HB->getError().'<br />';

			// Export desabonne
			if( $HB->insertDesabonne() == false )
				echo 'probleme durant la recuperation des desabonnes ( FID ) : '.$HB->getError().'<br />';

			$isConf = true;
		}

		if( !$isConf )
			echo 'Aucune configuration de l\'API EMV';

		\Yii::app()->end();
	}

	// ************************** Promo Site ************************** //
	public function actionPromoSite()
	{
		$this->includeJS( 'promoSite.js' );

		$PS = new \Business\PromoSite( 'search' );

		if( Yii::app()->request->getParam('delete') )
		{
			// Log l'action courante :
			$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );

			$toDelete	= \Business\PromoSite::load( Yii::app()->request->getParam('id') );
			if( $toDelete->delete() )
				Yii::app()->user->setFlash( "success", Yii::t( 'common', 'deleteOK' ) );
			else
				Yii::app()->user->setFlash( "error", Yii::t( 'common', 'updateNOK' ) );
		}

		$ConfDNS = \Business\Config::loadByKey( 'DNS' );

		// Filtre recherche :
		if( Yii::app()->request->getParam( 'Business\PromoSite' ) !== NULL )
			$PS->attributes = Yii::app()->request->getParam( 'Business\PromoSite' );

		$this->render( '//ap/promoSite', array( 'PS' => $PS, 'DNS' => $ConfDNS ) );
	}

	public function actionPromoSiteShow()
	{
		// Update
		if( Yii::app()->request->getParam( 'id' ) !== NULL )
		{
			if( !($PS = \Business\PromoSite::load( Yii::app()->request->getParam( 'id' ) )) )
				return false;
		}
		// Create
		else
			$PS = new \Business\PromoSite();

		// POST :
		if( Yii::app()->request->getParam( 'Business\PromoSite' ) !== NULL )
		{
			// Log l'action courante :
			$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );

			$PS->attributes = Yii::app()->request->getParam( 'Business\PromoSite' );

			if( $PS->save() )
				Yii::app()->user->setFlash( "success", Yii::t( 'common', 'updateOK' ) );
			else
				Yii::app()->user->setFlash( "error", Yii::t( 'common', 'updateNOK' ) );
		}

		$ConfDNS = \Business\Config::loadByKey( 'DNS' );

		if( Yii::app()->request->getParam('partialRender') !== NULL )
			$this->renderPartial( '//ap/promoSiteShow', array( 'PS' => $PS, 'DNS' => $ConfDNS ) );
		else
			$this->render( '//ap/promoSiteShow', array( 'PS' => $PS, 'DNS' => $ConfDNS ) );
	}

	// ************************** Campaign ************************** //
	public function actionCampaign()
	{
		$this->includeJS( '../../js/ckeditor/ckeditor.js' );
		$this->includeJS( 'campaign.js' );

		$AC = new \Business\AffiliateCampaign( 'search' );

		//Suppression d'une campagne :
		if( Yii::app()->request->getParam('delete') )
		{
			// Log l'action courante :
			$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );

			$toDelete	= \Business\AffiliateCampaign::load( Yii::app()->request->getParam('id') );
			if( $toDelete->delete() )
				Yii::app()->user->setFlash( "success", Yii::t( 'common', 'deleteOK' ) );
			else
				Yii::app()->user->setFlash( "error", Yii::t( 'common', 'updateNOK' ) );
		}
		// Telechargement des fichiers generés pour une campagne :
		else if( Yii::app()->request->getParam('dlCamp') !== NULL )
		{
			\Yii::import( 'ext.Zip' );

			$Zip	= new Zip();
			$GenAC	= \Business\AffiliateCampaign::load( Yii::app()->request->getParam('dlCamp') );

			$Zip->setComment( "Campaign '".$GenAC->label."'\nCreated on ".date('d/m/Y h:i:s') );

			foreach( $GenAC->getGenerated() as $file )
				$Zip->addFile( file_get_contents($file), basename($file) );

			$Zip->sendZip( \Yii::app()->params['porteur'].' '.$GenAC->label.'_'.date( 'd-m-Y' ).'.zip' );
			\Yii::app()->end();
		}

		// Filtre recherche :
		if( Yii::app()->request->getParam( 'Business\AffiliateCampaign' ) !== NULL )
			$AC->attributes = Yii::app()->request->getParam( 'Business\AffiliateCampaign' );

		$this->render( '//ap/campaign', array( 'AC' => $AC ) );
	}

	public function actionCampaignShow()
	{
		// Update
		if( Yii::app()->request->getParam( 'id' ) !== NULL )
		{
			if( !($AC = \Business\AffiliateCampaign::load( Yii::app()->request->getParam( 'id' ) )) )
				return false;
		}
		// Create
		else
			$AC = new \Business\AffiliateCampaign();

		// POST :
		if( Yii::app()->request->getParam( 'Business\AffiliateCampaign' ) !== NULL )
		{
			// Log l'action courante :
			$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );

			$P_AC = Yii::app()->request->getParam( 'Business\AffiliateCampaign' );
			$P_PS = Yii::app()->request->getParam( 'Business\PromoSite' );

			$AC->attributes = $P_AC;

			if( $AC->save() && $AC->updatePromoSite( $P_PS['id'] ) )
				Yii::app()->user->setFlash( "success", Yii::t( 'common', 'updateOK' ) );
			else
				Yii::app()->user->setFlash( "error", Yii::t( 'common', 'updateNOK' ) );
		}

		$PromoSite		= new \Business\PromoSite( 'search' );
		$listPromoSite	= $PromoSite->findAll();

		
		$PS				= ( $AC->id > 0 ) ? $AC->getPromoSite() : 0;

		if( Yii::app()->request->getParam('partialRender') !== NULL )
			$this->renderPartial( '//ap/campaignShow', array( 'AC' => $AC, 'template' => $AC->template, 'lPromoSite' => $listPromoSite, 'PS' => $PS ) );
		else
			$this->render( '//ap/campaignShow', array( 'AC' => $AC, 'template' => $AC->template, 'lPromoSite' => $listPromoSite, 'PS' => $PS ) );
	}

	public function actionCampaignGenerate()
	{
		if( !($AC = \Business\AffiliateCampaign::load( Yii::app()->request->getParam( 'id' ) )) )
			return false;

		if( empty($AC->template) )
			Yii::app()->user->setFlash( "warning", Yii::t( 'AP', 'templaceACempty' ) );

		if( Yii::app()->request->getParam( 'selAF' ) !== NULL )
		{
			// Log l'action courante :
			$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );

			$selAF = Yii::app()->request->getParam( 'selAF' );
			if( $AC->generateCampaignForAffiliatePlatform($selAF) )
				Yii::app()->user->setFlash( "success", Yii::t( 'common', 'updateOK' ) );
			else
				Yii::app()->user->setFlash( "error", Yii::t( 'common', 'updateNOK' ) );
		}

		$generatedUrl	= Yii::App()->getBaseUrl(true)."/campaignTemplate/".Yii::App()->params['porteur']."/".$AC->id."/";
		$insertScript	= Yii::app()->request->getParam( 'insertScript' );
		$AP				= new \Business\AffiliatePlatform( 'search' );

		if( Yii::app()->request->getParam('partialRender') !== NULL )
			$this->renderPartial( '//ap/campaignGenerate', array( 'AC' => $AC, 'AP' => $AP, 'insertScript' => $insertScript, 'generatedUrl' => $generatedUrl ) );
		else
			$this->render( '//ap/campaignGenerate', array( 'AC' => $AC, 'AP' => $AP, 'generatedUrl' => $generatedUrl ) );
	}

	public function actionCampaignValidate()
	{
		\Yii::import( 'ext.W3CHelper' );

		if( !($AC = \Business\AffiliateCampaign::load( Yii::app()->request->getParam( 'idCampaign' ) )) )
			return false;

		if( !($AF = \Business\AffiliatePlatform::load( Yii::app()->request->getParam( 'id' ) )) )
			return false;

		if( ($url = $AC->isGenerated($AF)) )
		{
			$url		= Yii::App()->getBaseUrl(true)."/campaignTemplate/".Yii::App()->params['porteur']."/".$AC->id."/".$url;
			$Validator	= new W3CHelper( $url );
			$res		= $Validator->validate();

			if( $res['status'] == 'valid' )
				Yii::app()->user->setFlash( "success", 'Document valide W3C !' );
			else
			{
				$msg = '<h2>Document non valide W3C : '.$res['err_num'].' erreurs</h2>';

				foreach( $res['errors'] as $error )
					$msg .= '<u>Ligne '.$error['line'].', colonne '.$error['col'].'</u> : '.$error['message'].'<br />'.$error['explanation'].'<hr>';

				Yii::app()->user->setFlash( "error", $msg );
			}

			if( count($res['warnings']) > 0 )
			{
				$msg = '<h2>Document non valide W3C : '.$res['warn_num'].' warnings</h2>';

				foreach( $res['warnings'] as $error )
					$msg .= $error['message'].'<hr>';

				Yii::app()->user->setFlash( "warning", $msg );
			}
		}

		if( Yii::app()->request->getParam('partialRender') !== NULL )
			$this->renderPartial( '//ap/campaignValidate', array( 'AC' => $AC, 'AF' => $AF ) );
		else
			$this->render( '//ap/campaignValidate', array( 'AC' => $AC, 'AF' => $AF ) );
	}

	public function actionCampaignLink()
	{
		if( !($AC = \Business\AffiliateCampaign::load( Yii::app()->request->getParam( 'id' ) )) )
			return false;

		$APSearch	= new \Business\AffiliatePlatform( 'search' );
		$ResAP		= $APSearch->search();
		$tree		= array();
		foreach( $ResAP->getData() as $k => $AP )
		{
			$tree[$k] = array(
				'AP'		=> $AP,
				'SubIDs'	=> array()
			);

			$SubSearch						= new \Business\AffiliatePlatformSubId( 'search' );
			$SubSearch->idAffiliatePlatform	= $AP->id;
			$ResSub							= $SubSearch->search();
			foreach( $ResSub->getData() as $Sub )
				$tree[$k]['SubIDs'][] = $Sub;
		}

		if( Yii::app()->request->getParam('partialRender') !== NULL )
			$this->renderPartial( '//ap/campaignLink', array( 'AC' => $AC, 'AP' => $AP, 'tree' => $tree ) );
		else
			$this->render( '//ap/campaignLink', array( 'AC' => $AC, 'AP' => $AP, 'tree' => $tree ) );
	}

	public function actionCampaignLinkShow()
	{
		if( !($AC = \Business\AffiliateCampaign::load( Yii::app()->request->getParam( 'idAC' ) )) )
			return false;

		$AP = $SubID = false;
		// Association Campagne + Plateforme :
		if( Yii::app()->request->getParam( 'idAP' ) > 0 )
		{
			$AP		= \Business\AffiliatePlatform::load( Yii::app()->request->getParam( 'idAP' ) );

			// POST :
			if( Yii::app()->request->getParam( 'Business\TrackingCode' ) !== NULL && Yii::app()->request->getParam( 'Business\PromoSite' ) !== NULL )
			{
				// Log l'action courante :
				$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );

				$P_TC	= Yii::app()->request->getParam( 'Business\TrackingCode' );
				$P_PS	= Yii::app()->request->getParam( 'Business\PromoSite' );

				if( $AC->updateTrackingCode( $P_TC, $AP->id ) && $AC->updatePromoSite( $P_PS['id'], $AP->id ) )
					Yii::app()->user->setFlash( "success", Yii::t( 'common', 'updateOK' ) );
				else
					Yii::app()->user->setFlash( "error", Yii::t( 'common', 'updateNOK' ) );
			}

			$PS		= $AC->getPromoSite( $AP->id );
			$TC		= $AC->getTrackingCode( $AP->id );
		}
		// Association Campagne + Plateforme + SubID :
		else if( Yii::app()->request->getParam( 'idSubId' ) > 0 )
		{
			$SubID	= \Business\AffiliatePlatformSubId::load( Yii::app()->request->getParam( 'idSubId' ) );

			// POST :
			if( Yii::app()->request->getParam( 'Business\TrackingCode' ) !== NULL && Yii::app()->request->getParam( 'Business\PromoSite' ) !== NULL )
			{
				// Log l'action courante :
				$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );

				$P_TC	= Yii::app()->request->getParam( 'Business\TrackingCode' );
				$P_PS	= Yii::app()->request->getParam( 'Business\PromoSite' );

				if( $AC->updateTrackingCode( $P_TC, $SubID->idAffiliatePlatform, $SubID->id ) && $AC->updatePromoSite( $P_PS['id'], $SubID->idAffiliatePlatform, $SubID->id ) )
					Yii::app()->user->setFlash( "success", Yii::t( 'common', 'updateOK' ) );
				else
					Yii::app()->user->setFlash( "error", Yii::t( 'common', 'updateNOK' ) );
			}

			$PS		= $AC->getPromoSite( $SubID->idAffiliatePlatform, $SubID->id );
			$TC		= $AC->getTrackingCode( $SubID->idAffiliatePlatform, $SubID->id );
		}
		else
			return false;

		$PromoSite		= new \Business\PromoSite( 'search' );
		$listPromoSite	= $PromoSite->findAll();

		if( Yii::app()->request->getParam('partialRender') !== NULL )
			$this->renderPartial( '//ap/campaignLinkShow', array( 'AC' => $AC, 'AP' => $AP, 'SubID' => $SubID, 'lPromoSite' => $listPromoSite, 'PS' => $PS, 'TC' => $TC ) );
		else
			$this->render( '//ap/campaignLinkShow', array( 'AC' => $AC, 'AP' => $AP, 'SubID' => $SubID, 'lPromoSite' => $listPromoSite, 'PS' => $PS, 'TC' => $TC ) );
	}

	// ************************** Campaign ************************** //
	public function actionConfigPorteur()
	{
		if( !($ConfDNS = \Business\Config::loadByKey( 'DNS' )) )
			$ConfDNS = new \Business\Config('insert');

		if( !($ConfError = \Business\Config::loadByKey( 'errorPage' )) )
			$ConfError = new \Business\Config('insert');

		if( !($PS = \Business\RouterPS::loadByAP()) )
			$PS = array();

		if( Yii::app()->request->getParam( 'update' ) !== NULL )
		{
			if( Yii::app()->request->getParam( 'idPromoSite' ) > 0 )
			{
				// Log l'action courante :
				$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );

				$ConfDNS->value	= Yii::app()->request->getParam( 'confDNS' );
				$ConfDNS->key	= 'DNS';

				if( !empty($ConfDNS->value) && strpos( $ConfDNS->value, 'http' ) === false )
					$ConfDNS->value = 'http://'.$ConfDNS->value;

				$ConfError->value	= Yii::app()->request->getParam( 'confError' );
				$ConfError->key		= 'errorPage';

				if( !empty($ConfError->value) && strpos( $ConfError->value, 'http' ) === false )
					$ConfError->value = 'http://'.$ConfError->value;

				// Sans split test :
				if( Yii::app()->request->getParam( 'splitTest' ) == 0 )
				{
					$PS1				= ( isset($PS[0]) ) ? $PS[0] : new \Business\RouterPS('insert');
					$PS1->idPromoSite	= Yii::app()->request->getParam( 'idPromoSite' );

					if( isset($PS[1]) )
						$PS[1]->delete();

					$PS					= array( $PS1 );

					if( $ConfDNS->save() && $PS1->save() && $ConfError->save() )
						Yii::app()->user->setFlash( "success", Yii::t( 'common', 'updateOK' ) );
					else
						Yii::app()->user->setFlash( "error", Yii::t( 'common', 'updateNOK' ) );
				}
				// Avec split tesst
				else
				{
					$PS1				= ( isset($PS[0]) ) ? $PS[0] : new \Business\RouterPS('insert');
					$PS1->idPromoSite	= Yii::app()->request->getParam( 'idPromoSiteSplit1' );

					$PS2				= ( isset($PS[1]) ) ? $PS[1] : new \Business\RouterPS('insert');
					$PS2->idPromoSite	= Yii::app()->request->getParam( 'idPromoSiteSplit2' );

					$PS					= array( $PS1, $PS2 );

					if( $ConfDNS->save() && $PS1->save() && $PS2->save() && $ConfError->save() )
						Yii::app()->user->setFlash( "success", Yii::t( 'common', 'updateOK' ) );
					else
						Yii::app()->user->setFlash( "error", Yii::t( 'common', 'updateNOK' ) );
				}
			}
			else
				Yii::app()->user->setFlash( "error", Yii::t( 'AP', 'txtDefPSError' ) );
		}

		$PromoSite		= new \Business\PromoSite( 'search' );
		$listPromoSite	= $PromoSite->findAll();

		$this->render( '//ap/configPorteur', array( 'ConfDNS' => $ConfDNS, 'ConfError' => $ConfError, 'lPromoSite' => $listPromoSite, 'PS' => $PS ) );
	}

	// ************************** Session Test ************************** //
	public function actionSetTestMode()
	{
		$Config = \Business\Config::loadByKey( 'DNS' );

		//Rendu du contenu
		$this->render( '//ap/setTestMode', array( 'Url' => $Config->value.$this->createUrl( 'AP/CreateSession' ) ) );
	}

	public function actionCreateSession()
	{
		$this->layout = '//ap/noMenu';

		// Traitement des post :
		if( \Yii::app()->request->getParam('start') !== NULL || \Yii::app()->request->getParam('reset') !== NULL )
		{
			Yii::app()->session->destroy();
			Yii::app()->session->open();

			Yii::app()->session[ self::SESSION_NAME ]	= time();
		}
		else if( \Yii::app()->request->getParam('stop') !== NULL )
		{
			Yii::app()->session->destroy();
			Yii::app()->session->open();
		}

		// Affichage de l'etat de la session de test :
		if( Yii::app()->session[ self::SESSION_NAME ] > 0 )
			Yii::app()->user->setFlash( 'success', 'Session de test demarr&eacute; le '.date( 'd-m-Y H:i:s', $_SESSION[ self::SESSION_NAME ] ) );
		else
			Yii::app()->user->setFlash( 'error', 'Session de test stopp&eacute;' );

		//Rendu du contenu
		$this->render( '//ap/createSession', array( 'session' => Yii::app()->session[ self::SESSION_NAME ] ) );
	}

	// ************************** AffiliatePlateform gestion SUBID ************************** //
	public function actionSubID(){
		$this->layout	= '//ap/noMenu';
		$newLink		= false;

		if( \Yii::app()->request->getParam('generateTemp') !== NULL || \Yii::app()->request->getParam('generateLink') !== NULL ){
			if( \Yii::app()->request->getParam('generateTemp') !== NULL )
			{
				$Temp		= \CUploadedFile::getInstanceByName( 'template' );

				if( !is_object($Temp) )
					Yii::app()->user->setFlash( 'error', 'Aucun fichier n\'a été reçu !' );
				else
				{
					$subID		= \Yii::app()->request->getParam('tempSubID');
					$content	= file_get_contents( $Temp->tempName );
				}
			}
			else if( \Yii::app()->request->getParam('generateLink') !== NULL ){
				$content	= trim( \Yii::app()->request->getParam('link') );
				if( empty($content) )
					Yii::app()->user->setFlash( 'error', 'Le lien est vide !' );
				else
					$subID		= \Yii::app()->request->getParam('linkSubID');
			}

			if(isset($subID)){
				$content = preg_replace( '/si=__0__/mi', 'si='.$subID, $content, -1, $count );
				if( $count <= 0 )
					Yii::app()->user->setFlash( 'error', 'Aucun subID n\'a été inseré !' );
				else{
					Yii::app()->user->setFlash( 'success', 'Les liens ont été modifié ! ( '.$count.' liens modifiés )' );

					if( \Yii::app()->request->getParam('generateTemp') !== NULL )
						\Yii::app()->request->sendFile( $Temp->name, $content );
					else
						$newLink = $content;
				}
			}
		}

		//Rendu du contenu
		$this->render( '//ap/subID', array( 'newLink' => $newLink ) );
	}
}

?>

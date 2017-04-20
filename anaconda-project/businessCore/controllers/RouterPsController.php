<?php

// http://www.rucker-voyance.com/voyance/voyance_router.php?ap=3&ac=1
// http://www.rucker-voyance.com/voyance/voyance_router.php?ap=msru2

/**
 * Description of RouterPs
 *
 * @author JulienL
 */
class RouterPsController extends Controller
{
	/**
	 * Contexte
	 * @var \Business\ContextRouterPs
	 */
	protected $Context	= false;

	public function init()
	{
		parent::init();

		// Chargement du context :
		$this->Context = new \Business\ContextRouterPs();
		if( !$this->Context->loadContext() )
		{
			// Si une page d'erreur est configuré, redirection vers cet page
			if( ($ConfError = \Business\Config::loadByKey( 'errorPage' )) )
			{$this->redirect( $ConfError->value );}
			else
			{throw new \EsoterException( 900, \Yii::t( 'error', 900 ) );}
		}

		// Raise log events :
		$this->onLogAction = array( $this, 'logAction' );

		\Yii::app()->session->open();
	}

	public function actionIndex(){
		// Si le SubID n'existe pas, on le créé a la volé :
		if( !$this->getAffiliatePlateformSubId() )
		{$this->createSubId();}

		$this->onAfterAction = array( $this, 'searchUrl' );
	}

	public function actionTestMode()
	{
		$this->layout = '//AP/noMenu';

		// Si le SubID n'existe pas, on le créé a la volé :
		if( !$this->getAffiliatePlateformSubId() )
		{$this->createSubId();}

		$PA			= $this->getAffiliatePlateform();
		$SubId		= $this->getAffiliatePlateformSubId();
		$AC			= $this->getAffiliateCampaign();
		$PS			= \Business\RouterPS::searchRouterForAP( $PA->id, $AC->id, $SubId->id );
		$TC			= \Business\ChoiceTrackingCode::searchTrackingCodeForAP( $PA->id, $AC->id, $SubId->id );

		// Config session old version :
		$_SESSION['IDAffiliatePlatform']		= $PA->id;		// ID platform old version
		$_SESSION['RefAffiliatePlatform']		= $PA->id.'_'.$AC->id.'_'.$SubId->subID;		// Login platform old version
		$_SESSION['SiteAffiliatePlatform']		= $PA->Site->code;			// Site old platform
		// For new version
		$_SESSION['idTrackingCodeV2']			= $TC->id;			// ID TrackingCode new version
		$_SESSION['idAffiliateCampaign']		= $AC->id;			// ID campaign new version
		$_SESSION['idAffiliatePlatformSubId']	= $SubId->id;		// SubID new version
		$_SESSION['affiliatePlatformDualOptin']	= $PA->isDualOptin;	// Dual OPTIN ?

		//Rendu du contenu
		$this->render( '//AP/testMode', array
			(
				'DN' => $_SERVER['SERVER_NAME'],
				'urlPS' => $PS->url,
				'platform' => $PA->label.' ( '.$PA->id.' )',
				'subID' => $SubId->subID.' ( '.$SubId->id.' )',
				'campaign' => $AC->label.' ( '.$AC->id.' )',
				'dualOptin' => $PA->isDualOptin > 0 ? 'Oui' : 'Non',
				'TCLeadPromo' => htmlentities( $TC->TCLeadPromo ),
				'TCLandingPagePromo' => htmlentities( $TC->TCLandingPagePromo ),
				'TCPrePurchase' => htmlentities( $TC->TCPrePurchase ),
				'TCPurchase' => htmlentities( $TC->TCPurchase ),
				'TCLead' => htmlentities( $TC->TCLead ),
				'TCLandingPage' => htmlentities( $TC->TCLandingPage ),
			) );
	}

	/**
	 * Insere le subId dans la DB s'il n'existe pas
	 * @return type
	 * @throws EsoterException
	 */
	protected function createSubId()
	{
		$id		= $this->Context->getSubIdAffiliatePlateformSubId();
		$AP		= $this->getAffiliatePlateform();
		$SubId	= new \Business\AffiliatePlatformSubId();

		$SubId->idAffiliatePlatform = $AP->id;
		$SubId->subID				= $id;
		$SubId->label				= ( $id === DEFAULT_SUBID ) ? '' : '';
		$SubId->description			= ( $id === DEFAULT_SUBID ) ? 'Valeur par defaut' : 'Inseré automatiquement';

		if( $SubId->save() )
		{return $this->Context->loadContext();}
		else
		{throw new EsoterException( 901, \Yii::t( 'error', 901 ) );}
	}

	/**
	 * Retourne l'URL du site de promo, ou Redirige vers le site de promo
	 * La recherche se fais en fonction des GET recuperé au travers de l'objet context ( Plateform, Campaign et SubID )
	 * @param bool $redirect Si true alors redirige, sinon retourne l'Url
	 * @return	string	Url
	 */
	protected function searchUrl( $redirect = true )
	{
		$PA			= $this->getAffiliatePlateform();
		$SubId		= $this->getAffiliatePlateformSubId();
		$AC			= $this->getAffiliateCampaign();
		$PS			= \Business\RouterPS::searchRouterForAP( $PA->id, $AC->id, $SubId->id );
		$TC			= \Business\ChoiceTrackingCode::searchTrackingCodeForAP( $PA->id, $AC->id, $SubId->id );

		// Config session old version :
		$_SESSION['IDAffiliatePlatform']		= $PA->id;		// ID platform old version
		$_SESSION['RefAffiliatePlatform']		= $PA->id.'_'.$AC->id.'_'.$SubId->subID;		// Login platform old version
		$_SESSION['SiteAffiliatePlatform']		= $PA->Site->code;			// Site old platform
		// For new version
		$_SESSION['idTrackingCodeV2']			= $TC->id;			// ID TrackingCode new version
		$_SESSION['idAffiliateCampaign']		= $AC->id;			// ID campaign new version
		$_SESSION['idAffiliatePlatformSubId']	= $SubId->id;		// SubID new version
		$_SESSION['affiliatePlatformDualOptin']	= $PA->isDualOptin;	// Dual OPTIN ?

		// Log l'action courante :
		$this->logAction( new CEvent( $this, array(
			'action' => \Business\Log::ACTION_ROUTER,
			'idAffiliatePlatform' => $this->getAffiliatePlateform()->id,
			'idAffiliateCampaign' => $this->getAffiliateCampaign()->id,
			'idAffiliatePlatformSubId' => $this->getAffiliatePlateformSubId()->id,
			'idPromoSite' => $PS->id
		) ) );

		if( $redirect )
		{
			if( strpos( $PS->url, 'http' ) !== false )
			{$this->redirect( $PS->url );}
			else
			{$this->redirect( '/voyance/'.$PS->url );}
		}
		else
		{return $PS->url;}
	}

	// ****************************** GETTER ****************************** //
	/**
	 * Retourne la plateforme courante
	 * @return \Business\AffiliatePlatform
	 */
	public function getAffiliatePlateform()
	{
		return ( is_object($this->Context) ) ? $this->Context->getAffiliatePlateform() : false;
	}
	/**
	 * Retourne le sub ID courant
	 * @return \Business\AffiliatePlatformSubId
	 */
	public function getAffiliatePlateformSubId()
	{
		return ( is_object($this->Context) ) ? $this->Context->getAffiliatePlateformSubId() : false;
	}
	/**
	 * Retourne la campagne courant
	 * @return \Business\AffiliateCampaign
	 */
	public function getAffiliateCampaign()
	{
		return ( is_object($this->Context) ) ? $this->Context->getAffiliateCampaign() : false;
	}
}

?>
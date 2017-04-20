<?php
/**
 * Description of SiteController
 *
 * @author JulienL
 * @package Controllers
 */
class Formation1Controller extends Controller
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
		$this->Context = new \Business\ContextFormation1();
		if( ($res = $this->Context->loadContext()) !== true )
		{throw new EsoterException( 10, \Yii::t( 'error', 10 ).'<br />Param GET : '.implode( ', ', $_GET ).'<br>Param POST : '.implode( ', ', $_POST ) );}
	}

	

	/*
	 * Action generique pour l'affichage d'une LDV : /site/ldv?m=test@test.com&...
	 */
	public function actionIndex()
	{
		// Log l'action courante :
		$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_OPEN ) ) );

		// Campagne du produit :

		// Titre de la page :
		

		// Rendu de la page :																		
		$this->render( '//fr_rinalda/testview/teste', array( 'Invoice' => $this->Context->getFirstInvoice() ) );
	}

	

	

	

	/**
	 * Action transformant les GET en POST
	 * attend au moins un parametre GET['url'] ( URL ver laquel redirigé )
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
	

	// ****************************** GETTER ****************************** //
	/**
	 * Retourne l'utilisateur courant
	 * @return \Business\User
	 */
	public function getUser()
	{
		return ( is_object($this->Context) ) ? $this->Context->getUser() : false;
	}
	/**
	 * Retourne le produit courant
	 * @return \Business\Product
	 */
	
	/**
	 * Retourne le context
	 * @return \Business\Context
	 */
	public function getContext()
	{
		return ( is_object($this->Context) ) ? $this->Context : false;
	}

	
	
}

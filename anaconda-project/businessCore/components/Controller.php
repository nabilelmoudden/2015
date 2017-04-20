<?php
/**
 * Class Controller
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 *
 * @author Julien L
 * @package Controllers
 */
abstract class Controller extends CController
{
	/**
	 * view's Directory ( html, css, js, img )
	 * @var string
	 */
	protected $portViewDir;
	protected $adminViewDir;

	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout = '';

	/**
	 * Initialisation du controller
	 */
	public function init()
	{
		parent::init();

		$this->portViewDir	= \Yii::app()->baseUrl.'/views/';
		$this->adminViewDir	= \Yii::app()->baseUrl.'/businessCore/views/';

		//  Defini l'action pour l'affichage des erreurs dans le controleur courant :
		

		// Creation de variable JS dans le header ( si la requete n'est pas une requete Ajax ) :
		if( \Yii::app()->request->getParam('partialRender') == NULL )
			Yii::app()->clientScript->registerScript( 'varJS', 'var baseUrl = "'.Yii::app()->getBaseUrl().'";', CClientScript::POS_HEAD );
	}

	/**
	 * Permet d'inclure les JS / CSS pour jquery
	 * @param boolean $withUI Avec ou sans JQuery-UI
	 */
	public function includeJQuerySCript( $withUI = false, $withJTree = false )
	{
		\Yii::app()->clientScript->registerCoreScript( 'jquery' );

		if( $withUI || $withJTree )
		{
			\Yii::app()->clientScript->registerCoreScript( 'jquery.ui' );
			\Yii::app()->clientScript->registerCssFile( \Yii::app()->clientScript->getCoreScriptUrl().'/jui/css/base/jquery-ui.css' );
		}

		if( $withJTree )
		{
			\Yii::app()->clientScript->registerCoreScript( 'treeview' );
			\Yii::app()->clientScript->registerCssFile( \Yii::app()->clientScript->getCoreScriptUrl().'/treeview/jquery.treeview.css' );
		}
	}

	/**
	 *
	 * @param string $fileName
	 * @return type
	 */
	public function includeJS( $fileName )
	{
		return \Yii::app()->clientScript->registerScriptFile( $this->portViewDir.'/js/'.$fileName );
	}

	/**
	 *
	 * @param string $fileName
	 * @return type
	 */
	public function includeCSS( $fileName )
	{
		return \Yii::app()->clientScript->registerCssFile( $this->portViewDir.'/css/'.$fileName );
	}

	/**
	 * Charge la configuration d'un porteur et remplace celle actuel
	 * ( Charge la config "DB", et "Params" )
	 * @param string $porteur
	 */
	public static function loadConfigForPorteur( $porteur )
	{
		static $lastLoadedConfig = false;

		if( $lastLoadedConfig == $porteur )
			return true;
		else
			$lastLoadedConfig = $porteur;

		$porteur	= isset($GLOBALS['porteurMap'][$porteur]) ? $GLOBALS['porteurMap'][$porteur] : $porteur;
		$confFile	= SERVER_ROOT.'/'.$porteur.'/'.DIR_CONF_PORTEUR.'/'.( IS_DEV_VERSION ? FILE_CONF_PORTEUR_DEV : FILE_CONF_PORTEUR );

		if( is_file($confFile) )
		{
			$esoConf	= require( __DIR__.'/../config/esoter.php' );
			$esoComp	= Yii::createComponent( $esoConf['components']['commonDb'] );

			$globConf	= require( $confFile );
			$dbComp		= Yii::createComponent( $globConf['components']['db'] );

			// Vide les params avant d'ajouter les nouveaux :
			Yii::app()->getParams()->clear();

			// Defini les nouveaux params :
			Yii::app()->setComponent( 'commonDb' , $esoComp );
			Yii::app()->setComponent( 'db' , $dbComp );
			Yii::app()->setParams( array_merge( $esoConf['params'], $globConf['params'] ) );

			if( isset($_SESSION) && \Yii::app()->user->getState('User') && !\Yii::app()->user->relogin() )
				\Yii::app()->user->logout();

			return true;
		}
		else
			throw new \EsoterException( 0, \Yii::t( 'error', 0 ).' : '.$porteur );
	}

	/**
	 * Charge la configuration d'un porteur et remplace celle actuel --> sans authentification <--
	 * ( Charge la config "DB", et "Params" )
	 * @param string $porteur
	 */
	public static function loadConfigForPorteur2( $porteur )
	{
		static $lastLoadedConfig = false;

		if( $lastLoadedConfig == $porteur )
			return true;
		else
			$lastLoadedConfig = $porteur;

		$_SESSION['porteur'] = $porteur;

		$porteur	= isset($GLOBALS['porteurMap'][$porteur]) ? $GLOBALS['porteurMap'][$porteur] : $porteur;
		$confFile	= SERVER_ROOT.'/'.$porteur.'/'.DIR_CONF_PORTEUR.'/'.( IS_DEV_VERSION ? FILE_CONF_PORTEUR_DEV : FILE_CONF_PORTEUR );

		if( is_file($confFile) )
		{
			$esoConf	= require( __DIR__.'/../config/esoter.php' );
			$esoComp	= Yii::createComponent( $esoConf['components']['commonDb'] );

			$globConf	= require( $confFile );
			$dbComp		= Yii::createComponent( $globConf['components']['db'] );

			// Vide les params avant d'ajouter les nouveaux :
			Yii::app()->getParams()->clear();

			// Defini les nouveaux params :
			Yii::app()->setComponent( 'commonDb' , $esoComp );
			Yii::app()->setComponent( 'db' , $dbComp );
			Yii::app()->setParams( array_merge( $esoConf['params'], $globConf['params'] ) );

			
				

			return true;
		}
		else
			throw new \EsoterException( 0, \Yii::t( 'error', 0 ).' : '.$porteur );
	}
	

	/**
	 * Insere dans la table log l'action effectué par l'utilisateur
	 * @param	object	$Event	Instance de la classe CEvent, contient les params User et Action
	 */
	public function logAction( $Event )
	{

		$GLOBALS['GETSerializedLog'] = '';
		$GLOBALS['POSTSerializedLog'] = '';
		$callbackGET = function($value, $key){
			$GLOBALS['GETSerializedLog'].=$key. '='. str_replace(',', '|||', $value).'&';
		};
		array_walk_recursive($_GET,$callbackGET);

		$callbackPOST = function($value, $key){
			$GLOBALS['POSTSerializedLog'].=$key. '='. str_replace(',', '|||', $value).'&';
		};
		array_walk_recursive($_POST,$callbackPOST);

		$log =  '';
		$log .= ( method_exists($this, 'getUser') && $this->getUser() ) ? 'idUser='.$this->getUser()->id."&" : '';
		$log .= ( method_exists($this, 'getProduct') && $this->getProduct() ) ? 'idProduct='.$this->getProduct()->id."&" : '';
		$log .= ( isset($Event->params['action']) ) ? 'actionType='.$Event->params['action'] ."&" : 'actionType='.\Business\Log::ACTION_DEFAULT;
		$log .= ( method_exists($this, 'getUser') && $this->getUser()) ? 'email='.$this->getUser()->email."&" : '';
		$log .= isset($_SERVER['SERVER_ADDR']) ? 'ip='. $_SERVER['SERVER_ADDR']."&" : '';
		$log .= isset($_SERVER['HTTP_USER_AGENT']) ? 'userAgent='.$_SERVER['HTTP_USER_AGENT']."&" : '';
		$log .= 'actionDate='.date( Yii::app()->params['dbDateTime'] );
		$log .= ( isset($Event->params['supportRef']) ) ? 'supportRef='.$Event->params['supportRef']."&" : '';
		$log .= ( isset($Event->params['supportDate']) ) ? 'supportDate='.$Event->params['supportDate']."&" : '';
		$log .= ( isset($Event->params['idAffiliatePlatform']) ) ? 'idAffiliatePlatform='.$Event->params['idAffiliatePlatform']."&" : '';
		$log .= ( isset($Event->params['idAffiliateCampaign']) ) ? 'idAffiliateCampaign='.$Event->params['idAffiliateCampaign']."&" : '';
		$log .= ( isset($Event->params['idAffiliatePlatformSubId']) ) ? 'idAffiliatePlatformSubId='.$Event->params['idAffiliatePlatformSubId']."&" : '';
		$log .= ( isset($Event->params['idPromoSite']) ) ? 'idPromoSite='.$Event->params['idPromoSite'] : '';

		$response = array(
			"short_message" => "test",
			"host" => "Preprod2",
			"facility" => "test123",
			"_Path" => 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
			"_LOG" => isset($log) ? $log : '',
			"_GET" => isset($GLOBALS['GETSerializedLog']) ? $GLOBALS['GETSerializedLog'] : "",
			"_POST" => isset($GLOBALS['POSTSerializedLog']) ? $GLOBALS['POSTSerializedLog'] : '',
			"_REFERER" => isset($_SERVER['HTTP_REFERER'])  ? $_SERVER['HTTP_REFERER'] : '' ,
			"_REQUEST_URI" => isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '',
			"_REQUEST_URI2" => isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : "",
			"_PHP_AUTH_USER" => isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : NULL,
		);

		$ch = curl_init('http://37.25.92.112:12201/gelf');
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($response));
		curl_exec($ch);
		curl_close($ch);

		$Log							= new \Business\Log();
		$Log->idUser					= ( method_exists($this, 'getUser') && $this->getUser() ) ? $this->getUser()->id : NULL;
		$Log->idProduct					= ( method_exists($this, 'getProduct') && $this->getProduct() ) ? $this->getProduct()->id : NULL;
		$Log->actionType				= ( isset($Event->params['action']) ) ? $Event->params['action'] : \Business\Log::ACTION_DEFAULT;;
		$Log->email						= ( method_exists($this, 'getUser') && $this->getUser() ) ? $this->getUser()->email : '';
		$Log->ip						= isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : false;
		$Log->userAgent					= isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : false;
		$Log->queryString				= json_encode( array_merge( array( $_SERVER['PATH_INFO'] ), array( 'GET' => $_GET, 'POST' => $_POST ) ) );
		$Log->actionDate				= date( Yii::app()->params['dbDateTime'] );

		$Log->supportRef				= ( isset($Event->params['supportRef']) ) ? $Event->params['supportRef'] : NULL;
		$Log->supportDate				= ( isset($Event->params['supportDate']) ) ? $Event->params['supportDate'] : NULL;
		$Log->idAffiliatePlatform		= ( isset($Event->params['idAffiliatePlatform']) ) ? $Event->params['idAffiliatePlatform'] : NULL;
		$Log->idAffiliateCampaign		= ( isset($Event->params['idAffiliateCampaign']) ) ? $Event->params['idAffiliateCampaign'] : NULL;
		$Log->idAffiliatePlatformSubId	= ( isset($Event->params['idAffiliatePlatformSubId']) ) ? $Event->params['idAffiliatePlatformSubId'] : NULL;
		$Log->idPromoSite				= ( isset($Event->params['idPromoSite']) ) ? $Event->params['idPromoSite'] : NULL;
		if( !$Log->save() )
		{
			$error = false;
			foreach( $Log->getErrors() as $field => $tabError )
				$error .= $field.' : '.implode( ',', $tabError )."\r\n";

			
		}
	}

	/**
	 * Test si une vue existe ou non
	 * @param string $viewName Chemin de la vue
	 * @return boolean
	 */
	public function isView( $viewName )
	{
		$base	= \Yii::app()->getViewPath();
		$ext	= ( ($Renderer=Yii::app()->getViewRenderer())!==null ) ? $Renderer->fileExtension : '.php';

		return is_file( $base.$viewName.$ext );
	}

	// ****************************** ACTIONS ****************************** //

	/**
	 * This is the action to handle external exceptions.
	 * Appeler en cas d'erreur 404, ou par l'url /site/error
	 */
	public function actionError()
	{
		if( ($error = Yii::app()->errorHandler->error) )
		{
			if( Yii::app()->request->isAjaxRequest )
				echo $error['message'];
			else
				$this->render( 'error', $error );
		}
	}

	// ****************************** EVENTS ****************************** //

	/**
	 *  Fire events : Evenement appelé avant chaque action :
	 */
	protected function beforeAction( $Action )
	{
		$e = new CEvent( $this, array() );
		$this->onBeforeAction( $e );

		return parent::beforeAction( $Action );
	}
	/**
	 *  Fire events : Evenement appelé apres chaque action :
	 */
	protected function afterAction( $Action )
	{
		$e = new CEvent( $this, array() );
		$this->onAfterAction( $e );

		return parent::afterAction( $Action );
	}

	/**
	 *  Raise Events :
	 */
	public function onBeforeAction( $event )
	{
		$this->raiseEvent( "onBeforeAction", $event );
	}

	public function onAfterAction( $event )
	{
		$this->raiseEvent( "onAfterAction", $event );
	}
	public function onLogAction( $event )
	{
		$this->raiseEvent( "onLogAction", $event );
	}

	// ****************************** GETTER ****************************** //
	public function portViewDir( $absolutePath = false )
	{
		return ( $absolutePath ) ? SERVER_ROOT.$this->portViewDir : $this->portViewDir;
	}
	public function portDir( $absolutePath = false )
	{
		return $this->portViewDir( $absolutePath ).\Yii::app()->params['porteur'].'/';
	}
	public function adminViewDir( $absolutePath = false )
	{
		return ( $absolutePath ) ? SERVER_ROOT.$this->adminViewDir : $this->adminViewDir;
	}
	public function adminDir( $absolutePath = false )
	{
		return $this->adminViewDir( $absolutePath ).strtolower($this->id).'/';
	}
	public function EsoterAccess($role){
		if(\Yii::App()->User->checkAccess($role))
			return true;
		else
			return false;
	}
}

?>

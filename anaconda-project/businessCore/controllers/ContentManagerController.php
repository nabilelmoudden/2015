<?php
/**
 * Description of ContentManager
 *
 * @author JulienL
 * @package Controllers
 */
class ContentManagerController extends AdminController
{
	public $layout = '//contentManager/menu';

	private $tempList	= array(
		'Rucker FR' => array(
			'StratCentrale - VGLDV' => 'fr_rucker/StrateCentrale/vg/vgldv.html',

			'CampFid1 - EDCLDV' => 'fr_rucker/CampFid1/edc/edcldv.html',
			'CampFid1 - EDCR1' => 'fr_rucker/CampFid1/edc/edcr1.html',

			//'CampFid1 - GPCLDV' => 'fr_rucker/CampFid1/gpc/gpcldv.html',
			//'CampFid1 - GPCR1' => 'fr_rucker/CampFid1/gpc/gpcr1.html',
		),
	);

	/**
	 * Initialisation du controleur
	 */
	public function init()
	{
		parent::init();

		// Url de la page de login ( pour les redirections faites par les Rules ) :
		Yii::app()->user->loginUrl = array( '/AP/login' );

		// Default page title :
		$this->setPageTitle( 'Content Manager' );

		// Css
		Yii::app()->clientScript->registerCssFile( Yii::app()->baseUrl.'/css/contentManager/main.css' );
	}

	public function actionIndex()
	{
		\Yii::import( 'ext.CKEditorHelper' );

		// Log l'action courante :
		$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );

		Yii::app()->clientScript->registerScriptFile( Yii::app()->baseUrl.'/js/ckeditor/ckeditor.js' );
		Yii::app()->clientScript->registerScriptFile( Yii::app()->baseUrl.'/js/contentManager/main.FCK.js' );

		

		// Chargement ajax du template a editer ds le wysiwyg :
		if( isset($_GET['template']) )
		{
			echo CKEditorHelper::getConfigForContentManager( $_GET['template'] );
			Yii::app()->end();
		}
		else if( isset($_POST['template']) && isset($_POST['html']) )
		{
			if( CKEditorHelper::saveView( $_POST['template'], $_POST['html'] ) )
			{Yii::app()->user->setFlash( "success", Yii::t( 'SAV', 'updateOK' ) );}
			else
			{Yii::app()->user->setFlash( "error", Yii::t( 'SAV', 'updateNOK' ) );}

			echo json_encode( Yii::app()->user->getFlashes() );
			Yii::app()->end();
		}

		$this->render( 'index', array( 'tempList' => $this->tempList ) );
	}
}

?>
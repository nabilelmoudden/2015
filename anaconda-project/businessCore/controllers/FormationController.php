<?php
/**
 * Description of SiteController
 *
 * @author JulienL
 * @package Controllers
 */
class FormationController extends Controller
{
	/**
	 * Contexte
	 * @var \Business\ContextSite
	 */
        /////FORMATION ///////////////////////////////////////
        protected $ContextFormation	= false;
        //////////////////////////////////////////////////////

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
		$this->layout	= '//'.\Yii::app()->params['porteur'].'/porteur';

		// Chargement du context :
                /////FORMATION////////////////////////////////////
                $this->ContextFormation = new \Business\ContextFormation();
                if( ($res = $this->ContextFormation->loadContext()) !== true )
	       		{throw new EsoterException( 10, \Yii::t( 'error', 10 ).'<br />Param GET : '.implode( ', ', $_GET ).'<br>Param POST : '.implode( ', ', $_POST ) );}
                ///////////////////////////////////////////////////
                 
	}

        public function actionPage()
	{
		// Log l'action courante :
		$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_OPEN ) ) );

		// Campagne du produit :
		$porteur    = \Yii::app()->params['porteur'];
		$campName   = $this->getCampaign()->ref;
                $view	    = 'frmldv'; 
		$refProduct = $this->getProduct()->ref;

                
		// Titre de la page :
		$this->pageTitle = 'Test Formation';

		// Rendu de la page :
		
               
                $this->render( '//'.$porteur.'/'.$campName.'/'.$refProduct.'/'.$view, array( ) );
	}
        
        
        ////////////////////////////////////////////
        public function getProduct()
	{
		return ( is_object($this->ContextFormation) ) ? $this->ContextFormation->getProduct() : false;
	}
        
        public function getCampaign()
	{
		return ( is_object($this->ContextFormation) ) ? $this->ContextFormation->getCampaign() : false;
	}
        
        public function getContextFormation()
	{
		return ( is_object($this->ContextFormation) ) ? $this->ContextFormation : false;
	}
        ////////////////////////////////////////////
	
	
	
	
}

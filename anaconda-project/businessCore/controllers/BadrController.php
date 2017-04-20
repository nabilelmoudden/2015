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


class BadrController extends AdminController
{
	public $layout	= '';
 	
	/**
	 * Initialisation du controleur
	 */
	/*public function init(){
		parent::init();
		// Url de la page de login ( pour les redirections faites par les Rules ) :
		Yii::app()->user->loginUrl = array( '/Login/login' );
		// Default page title :
		$this->setPageTitle( 'Login Administration' );
	} 

	// ************************** RULES / FILTER ************************** //
	public function filters(){
		return array( 'accessControl', 'postOnly + delete' );
    }

	public function accessRules(){
        return array(
			array(
				'allow',
                'users' => array('@'),
				'roles' => array( 'ADMIN' )
            ),
			array(
				'allow',
				'actions' => array( 'login' ),
				'users' => array('*')
			),
            array('deny'),
        );
    }

	// ************************** ACTION ************************** //
        */
    public function actionIndex()
    {
	\Business\MediaAlert::joinMedia('test', 'fichier', '/AnacondaData',1);
		     
    //	$ab= new AnacondaBehavior();
    //	$ab->setQuarantaine(); 
 
    	//\Business\CommentAlert::createComment(13,"zzzzzzzzz");
    
    }
    
    public function actionTest(){ 
    	
    	if(\Yii::app()->request->getParam('submitForm') !== NULL)
    	{
    		
    	echo 1;	
    	\Business\MediaAlert::joinMedia('test', 'fichier', '../',1); 
    	
    	
    	} 
    	$this->render( '//TestEngine/badr', array());
    }
        

    
    //////////////////////////////////////////////////////////////////////////////////////////////////

    
    
}

?>
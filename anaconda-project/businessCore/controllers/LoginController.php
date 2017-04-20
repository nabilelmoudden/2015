<?php
/**
 * Description of Login controller
 *
 * @author JulienL
 * @package Controllers
 */
class LoginController extends AdminController
{
	public $layout	= '//login/menu';

	/**
	 * Initialisation du controleur
	 */
	public function init(){
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
	public function actionIndex(){
		$this->includeJS('login.js');
		$User		= new \Business\User('search');

		// Filtre recherche :
		if( Yii::app()->request->getParam( 'Business\User' ) !== NULL )
		{$User->attributes = Yii::app()->request->getParam( 'Business\User' );}

		// Post, delete :
		if( Yii::app()->request->getParam('delete') && Yii::app()->user->checkAccess('ADMIN') ){
			// Log l'action courante :
			$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );

			$toDelete = \Business\User::load( \Yii::app()->request->getParam('id') );
			$toDelete->setAdmin( false );
		}

		//Rendu du contenu
		$this->render( '//login/index', array( 'User' => $User ) );
	}

	


	//---------------------------------------------------------
	//action UserCreate pour charger le fomulaire vide
	public function actionUserCreate(){
		$listSite		= array();
		foreach($GLOBALS['porteurAC2'] as $k => $v){
				$listSite[]=$k;
		}
		$Role	= new \Business\Role( 'search' );
		$lRole	= $Role->findAll();
		$User = new \Business\User();
		$this->renderPartial( '//login/userShow', array('User' => $User,'lSite' => $listSite, 'lRole' => $lRole) );

		$show  = "<script>";
		$show .= "$('#selectSite').html(\"<select name='Business\\\User[Site][]' id='selectSiteInput' multiple style='width:145px;height:180px;'>";
		foreach ($listSite as $key => $value){
		    $show .= "<option value='$value'>$value</option>";
		}
		$show .= "</select>\");";
		$show .= "</script>";

		echo $show;	  
	}
	
	//action UserUpdate pour charger le fomulaire rempli et le préparer à la modification
	public function actionUserUpdate(){	
		$listSite = array();
		$followed = array();

		$Role	= new \Business\Role( 'search' );
		$lRole	= $Role->findAll();
		$currentUser = \Business\User::load( Yii::app()->request->getParam( 'id' ));

		if($currentUser != null){
			$this->renderPartial( '//login/userShow', array('User' => $currentUser,'lSite' => $listSite, 'lRole' => $lRole) );
		}

		foreach($GLOBALS['porteurAC2'] as $k => $v){
			$listSite[]=$k;
			
			$User = \Business\User::model()->find(array(
				'condition'=>'email=:postEmail',
				'params'=>array(':postEmail'=>$_GET['email']),
			));
			

			//To Remove
			$followed[$k]= false;
		}


		$show  = "<script>";
		$show .= "$('#selectSite').html(\"<select name='Business\\\User[Site][]' id='selectSiteInput' multiple style='width:145px;height:180px;'>";
		foreach ($followed as $key => $value){
		    if($value == true) {$show .= "<option value='$key' selected>$key</option>";}
		    else {$show .= "<option value='$key'>$key</option>";}
		}
		$show .= "</select>\");";
		$show .= "$('#selectSiteInput option').on('mousedown', function (e) {";
		$show .= "this.selected = !this.selected;e.preventDefault();";
		$show .= "});";
		$show .= "</script>";

		echo $show;
	}
	
	//action UserInsertData pour appliquer les modifications
	public function actionUserInsertData(){
		if(Yii::app()->request->getParam('Business\User') != NULL && Yii::app()->user->checkAccess('ADMIN')){
			$listSite		= array();
			foreach($GLOBALS['porteurAC2'] as $k => $v){
				$listSite[]=$k;	
			}
			$userParam = Yii::app()->request->getParam('Business\User');
			$explodedPasswordParam = explode("|", $userParam['password']);
			//la variable mail va etre utile pour envoye un mail de confirmation à la personne ajouter une seul fois meme si elle est ajouté dans plusieurs BD
			$mailCp = 0;
			foreach($userParam['Site'] as $listSite){

				//le conrolleur ci-dessou va nous permetre de changer la bd lor de la maj sur plusieurs bd
				\Controller::loadConfigForPorteur($listSite);
				$User = \Business\User::model()->find(array(
					'select'=>'email',
					'condition'=>'email=:postEmail',
					'params'=>array(':postEmail'=>$userParam['email']),
				));
				//si l'utilisateur ne se trouve pas on l'ajoute
				if(!$User)
				{
					$User = new \Business\User();	
					$User->attributes   = $userParam;
					if($explodedPasswordParam[0] == "DoNotSetPassword" &&  isset($explodedPasswordParam [1])) {$User->password = $explodedPasswordParam[1];}
					else {$User->password = md5($explodedPasswordParam[0]);}	
					$User->AuthorizedIP = $userParam['AuthorizedIP'];				
					if($User->save())
					{
						$idUser = \Business\User::model()->find(array(
						'select'=>'id',
						'condition'=>'email=:postEmail',
						'params'=>array(':postEmail'=>$userParam['email']),
						));
						$User->setAdmin2( Yii::app()->request->getParam('Business\User')['roles'], $idUser['id']);
						if($mailCp == 0) {$User->sendMail($userParam);}
					}					

				}
				//si l'utilisateur est touvé alors en fait un update
				else
				{
					$User = \Business\User::model()->find(array(
						'condition'=>'email=:postEmail',
						'params'=>array(':postEmail'=>$userParam['email']),
					));
					$User->setAdmin2( Yii::app()->request->getParam('Business\User')['roles'], $User['id']);
					if($explodedPasswordParam[0] == 'DoNotSetPassword' &&  isset($explodedPasswordParam [1])){
						$User->attributes = $userParam;
						$User->password = $explodedPasswordParam[1];
					}else{
						$User->attributes = $userParam;
						$User->password = md5($explodedPasswordParam[0]);
					}
					$User->AuthorizedIP = $userParam['AuthorizedIP'];				
					$User->save();
				}	
			$mailCp++;
			}	
		}
	}
	//---------------------------------------------------------

}

?>
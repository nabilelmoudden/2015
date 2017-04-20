<?php
/**
 * Class AdminController
 * AdminController is the customized base controller class for backoffice.
 * All controller classes for backoffice application should extend from this base class.
 *
 * @author Julien L
 * @package Controllers
 */
abstract class AdminController extends Controller
{
	/**
	 * Porteur courant :
	 * @var string
	 */
	private $porteur;

	/**
	 * Initialisation du controller
	 */
	public function init()
	{
		parent::init();

		// Choix du porteur :
		if( Yii::app()->request->getParam('p') !== NULL )
			Yii::app()->session['porteur'] = Yii::app()->request->getParam('p');
		else if( empty(Yii::app()->session['porteur']) )
			Yii::app()->session['porteur'] = Yii::app()->params['porteur'];

		$leporteur = Yii::app()->session['porteur'];
		$this->setPorteur( $leporteur );
		$this->setPorteurAdmContr( $leporteur );
		
		//**************changement d'URL, By Youssef HARRATI
		
		$this->checkSession( );
		
		//*************fin changement d'URL

		// Include JQuery + JQuery-UI
		$this->includeJQuerySCript( true, true );

		// Admin JS
		$this->includeJS( '../../js/adminTool.js' );

		// AdminMenu Css
		$this->includeCSS( '../../css/backofficeMenu.css' );
	}
	
	
	// ************************** Check user Session ************************** //
	public function checkSession(  )
	{
		$url = $_SERVER['HTTP_HOST'];
		$req = $_SERVER['SCRIPT_NAME'].$_SERVER['PATH_INFO'];
		$params = $_SERVER['QUERY_STRING'];
		
		//direct or www
		list($subDomain) = explode('.',$url);
		
		list(,$domain,$ext) = explode('.',$GLOBALS['porteurDNS'][$this->porteur]);
		
		$dns = $subDomain.'.'.$domain.'.'.$ext;
		$token = md5(mt_rand());
		
		
		
		if(Yii::app()->request->getParam('p') !== NULL /* && !Yii::app()->user->isGuest*/){
			
			$email = Yii::app()->user->getState('User') != NULL ? Yii::app()->user->getState('User')->email : "" ;
			$password = Yii::app()->user->getState('User') != NULL ? Yii::app()->user->getState('User')->password : "" ;
			
			$this->loadConfigForPorteur( 'fr_rinalda' );
			
			if(Yii::app()->request->getParam( 'tocken' ) !== NULL){
				$token = Yii::app()->request->getParam( 'tocken' );
				
				$TempLogin = \Business\TempLogin::model()->findByAttributes( array( 'token' => $token ) );
				if($TempLogin != NULL){
					$email = $TempLogin->login;
					$pass = $TempLogin->password;
					
					
					$identity = new UserIdentity($email, $pass);
					
					$identity->authenticate();
					
					
					Yii::app()->user->login($identity, 0);
					
					$TempLogin->delete();
					
					$get = "";
					foreach($_GET AS $key => $value){
						if($key != 'tocken' && $key != 'token'){
							$get .= $key."=".$value.'&';
						}
					}
					
					header("Location: http://".$dns.$req.'?'.$get.'token='.$token);
					
					$this->loadConfigForPorteur( $this->porteur );
				}
			}else{
				$TempLogin = new \Business\TempLogin();
				
				$TempLogin->login = $email;
				$TempLogin->password = $password;
				$TempLogin->token = $token;
				$TempLogin->time = time();
				
				$TempLogin->save();
				
				if( $url != $dns){
					$ext = strlen($params)>0?'?'.$params.'&tocken='.$token:'?tocken='.$token;
					header("Location: http://".$dns.$req.$ext);
				}
			}
			
			$this->loadConfigForPorteur( $this->porteur );
		}
		
	}

	/**
	 *
	 * @param string $fileName
	 * @return type
	 */
	public function includeJS( $fileName )
	{
		return \Yii::app()->clientScript->registerScriptFile( $this->adminDir().'js/'.$fileName );
	}

	/**
	 *
	 * @param string $fileName
	 * @return type
	 */
	public function includeCSS( $fileName )
	{
		return \Yii::app()->clientScript->registerCssFile( $this->adminDir().'css/'.$fileName );
	}

	// ************************** LOGIN ************************** //
	public function actionLogin()
	{
		$this->pageTitle = 'Login';

		$model = new LoginForm;

		// if it is ajax validation request
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if (isset($_POST['LoginForm']))
		{
			$model->attributes = $_POST['LoginForm'];

			// validate user input and redirect to the previous page if valid
			if( $model->validate() && $model->login() )
			{
				// Log l'action courante :
				$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_LOGIN ) ) );

				$this->redirect( Yii::app()->baseUrl.'/index.php/'.$this->id.'/index' );
			}
		}

		// Methode appeler pour afficher une vue
		$this->render( '//login', array('model' => $model) );
	}

	public function actionLogout()
	{
		// Log l'action courante :
		$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_LOGOUT ) ) );

		Yii::app()->user->logout();
		$this->redirect( Yii::app()->baseUrl.'/index.php/'.$this->id.'/index' );
	}

	// ************************** SETTER ************************** //
	public function setPorteur( $porteur )
	{
		$this->porteur = $porteur;
		return $this->loadConfigForPorteur( $porteur );
	}
	
	public function setPorteurAdmContr( $porteur )
	{
		$this->porteur = $porteur;
		
		return $this->loadConfigForPorteur( $porteur );
	}

	// ************************** GETTER ************************** //
	public function porteur()
	{
		return $this->porteur;
	}

	/**
	 * Retourne l'utilisateur courant
	 * @return \Business\User
	 */
	public function getUser()
	{
		$User = \Yii::app()->user->getState('User');
		return ( is_object($User) ) ? $User : false;
	}
}

?>
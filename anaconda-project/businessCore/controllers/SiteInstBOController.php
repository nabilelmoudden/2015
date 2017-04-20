<?php



/**
 * Description of SiteInstBOController
 *
 * @author Youssef HARRATI
 * @package Controllers
 */
class SiteInstBOController extends AdminController {

	public $layout	= '//product/menu';
	protected $Context	= false;
	
    /**
     * Initialise le controller generique des site
     * Instancie l'Objet Context
     * @throws EsoterException	Si l'instanciation du Context a posé probleme
     */
    public function init() {
        
		// Choix du porteur :
		if( Yii::app()->request->getParam('p') !== NULL )
			Yii::app()->session['porteur'] = Yii::app()->request->getParam('p');
		else if( empty(Yii::app()->session['porteur']) )
			Yii::app()->session['porteur'] = Yii::app()->params['porteur'];
		
		parent::init();
		$this->pageTitle = 'Home page';
		

		// Defini le dossier dans lequel sont les vues :
		\Yii::app()->setViewPath( $this->adminViewDir(true) );

		// Defini la langue :
		\Yii::app()->setLanguage( \Yii::app()->params['lang'] );

		// Insertion de JQuery :
		$this->includeJQuerySCript( true );

		// Chargement du context :
		$this->Context = new \Business\ContextSite();
		$res = $this->Context->loadContext();
		Yii::app()->user->loginUrl = array( '/Product/login' );
    }
    
    public function filters(){
    	return array( 'accessControl' );
    }
    
    public function accessRules(){
        return array(
			array(
				'allow',
                'users' => array('@'),
				'roles' => array( 'ADMIN', 'ADMIN_PRODUCT'  )
            ),
			array(
				'allow',
				'actions' => array( 'login', 'logout' ),
				'users' => array('*')
			),
			array('deny'),
        );
    }
    
    public function actionIndex() {
    	
		$this->redirect( 'siteInstBO/pageList');
    }
	
	public function actionPageList() {
		$site = "";
		if(Yii::app()->request->getParam( 'site' ) !== "")
			$site = "_".Yii::app()->request->getParam( 'site' );
		if($site === "_")
			$site = "";
		$this->verifFiles($site);
		if(Yii::app()->request->getParam( 'site' ) !== "")
			$site = Yii::app()->request->getParam( 'site' );
		
		$porteur			= \Yii::app()->params['porteur'];
		$viewDir = $this->adminViewDir.'/siteInstBO/';
		
		
    	$this->render( '//siteInstBO/pageList', array('porteur' => $porteur, 'viewDir' => $viewDir, 'baseDir' => \Yii::app()->baseUrl, 'site' => $site) );
    }
	
	public function actionPageEdit() {
		
		$site = "";
		if(Yii::app()->request->getParam( 'site' ) !== "")
			$site = "_".Yii::app()->request->getParam( 'site' );
		
		if($site === "_")
			$site = "";
		
		$this->verifFiles($site);
		
		
		$porteur			= \Yii::app()->params['porteur'];
		$viewDir = SERVER_ROOT.$this->portViewDir.$porteur.'/siteInst/';
		$adminViewDir = $this->adminViewDir.'/siteInstBO/';
		
		$file = "index$site.html";
		$page = "";
		$title = "Présentation";
		if(Yii::app()->request->getParam( 'id' ) !== NULL){
			$page = Yii::app()->request->getParam( 'id' );
			
			switch($page){
				case "index":
					$file = "index$site.html";
					$title = "Présentation";
					break;
				case "store":
					$file = "store$site.html";
					$title = "Boutique";
					break;
				case "testimonial":
					$file = "testimonial$site.html";
					$title = "Témoignages";
					break;
				case "terms":
					$file = "terms$site.html";
					$title = "Conditions";
					break;
				case "thanks":
					$file = "thanks$site.html";
					$title = "Confirmation";
					break;
				case "form":
					$file = "form$site.html";
					$title = "Voyance Gratuite";
					break;
				case "faq":
					$file = "faq$site.html";
					$title = "FAQ";
					break;
				case "privacy":
					$file = "privacy$site.html";
					$title = "Vie Privée";
					break;
				case "consultation":
					$file = "consultation$site.html";
					$title = "consultation and prediction";
					break;
				default:
					$file = "index$site.html";
					$title = "Présentation";
					break;
					
			}
			
			if(Yii::app()->request->getParam( 'content' ) !== NULL){
				$handle = fopen($viewDir.$file, "w+");
				fwrite($handle, Yii::app()->request->getParam( 'content' ));
				fclose($handle);
				Yii::app()->user->setFlash( "success", Yii::t( 'common', 'la modification est enregistrée dans le fichier: <b>'.$this->portViewDir.$porteur.'/siteInst/'.$file.'</b>' ) );
			}
		}
		
		$filePath = $this->portViewDir.$porteur.'/siteInst/'.$file;
		$file = $viewDir.$file;
		$contents = "";
		if(is_file($file)){
			$handle = fopen($file, "r");
			$contents = fread($handle, filesize($file));
			fclose($handle);
		}
		
		if(Yii::app()->request->getParam( 'site' ) !== "")
			$site = Yii::app()->request->getParam( 'site' );
		
		if(Yii::app()->request->getParam( 'popup' ) == 1){
			if(Yii::app()->request->getParam( 'content' ) !== NULL){
				$this->redirect(array('SiteInstBO/pageList', 'site' => $site));
			}
			$this->renderPartial( '//siteInstBO/pageEditPartial', array('file' => $filePath, 'page' => $page, 'porteur' => $porteur, 'title' => $title, 'viewDir' => $viewDir, 'adminViewDir' => $adminViewDir, 'baseDir' => \Yii::app()->baseUrl, 'contents' => htmlspecialchars($contents), 'site' => $site) );
			
		}else
			$this->render( '//siteInstBO/pageEdit', array('file' => $filePath, 'page' => $page, 'porteur' => $porteur, 'title' => $title, 'viewDir' => $viewDir, 'adminViewDir' => $adminViewDir, 'baseDir' => \Yii::app()->baseUrl, 'contents' => htmlspecialchars($contents), 'site' => $site) );
    }
	
	private function verifFiles($site = "") {
		$porteur			= \Yii::app()->params['porteur'];
		$viewDir = SERVER_ROOT.$this->portViewDir.$porteur.'/siteInst';
		
		$folders = ['images','css','lib'];
		$files = ['index'.$site.'.html','store'.$site.'.html','testimonial'.$site.'.html','terms'.$site.'.html','thanks'.$site.'.html','form'.$site.'.html','faq'.$site.'.html','privacy'.$site.'.html','consultation'.$site.'.html'];
		$str = "<html>\n\r\t<head>\n\r\t</head>\n\r\t<body>\n\r\t\tview file\n\r\t</body>\n\r</html>";
		
		
		if (!file_exists($viewDir)) {
			mkdir($viewDir, 0777);
			chmod($viewDir,0777);
			foreach($folders AS $folder){
				mkdir($viewDir.'/'.$folder, 0777);
				chmod($viewDir.'/'.$folder,0777);
			}
			
			foreach($files AS $file){
				$handle = fopen($viewDir.'/'.$file,"w+");
				fwrite($handle, $str);
				fclose($handle);
				chmod($viewDir.'/'.$file,0777);
			}
		}else{
			foreach($folders AS $folder){
				if (!file_exists($viewDir.'/'.$folder)) {
					mkdir($viewDir.'/'.$folder, 0777);
					chmod($viewDir.'/'.$folder,0777);
				}
			}
			
			foreach($files AS $file){
				if (!file_exists($viewDir.'/'.$file)) {
					$handle = fopen($viewDir.'/'.$file,"w+");
					fwrite($handle, $str);
					fclose($handle);
					chmod($viewDir.'/'.$file,0777);
				}
			}
		}
		
	}
	
}

<?php


class EsoterDefineController extends AdminController {
	
	public $layout	= '//product/menu';

	public function init(){
		parent::init();

	   if(Yii::app()->user->isGuest){
			$this->redirect(array('Product/login'));
		}
		
		$this->setPageTitle( 'EsoterDefine Administration' );
	}

	public function filters(){
		return array( 'accessControl' );
	}
/**/

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
			array('allow'),
		);
	}
	

	public function actionIndex()
	{
            
            	
           	  
         
            
		    $porteurMap       = Yii::app()->session['porteur']; 
	   	    $ConfigPorteurMap = __DIR__.'/../config/Config_porteurMap/Config_'.$porteurMap.'/Config_'.$porteurMap.'.php';
			@require($ConfigPorteurMap);
			  		
			$rawData = array( array('id'              => 2, 
			                        'NomPorteur'       => $_GLOBALS2["listPorteur"][$porteurMap]["port-name"],  
			                        'Nomdedomaine'     => $GLOBALS["porteurDNS"][$porteurMap] ,
			                        'compteEMVactif'   => $GLOBALS["porteurCompteEMVactif"][$porteurMap] ,
			                        'Dossier'          => $_GLOBALS2["porteurFolder"][$porteurMap],
			                        'porteur'          => $porteurMap,  


		                     ),);


		$arrayDataProvider=new CArrayDataProvider($rawData, array(
			 'id'=>'id',
			/* 'sort'=>array(
				'attributes'=>array(
					'NomPorteur', 'Nomdedomaine','compteEMVactif'
				),
			),*/
			'pagination'=>array(
				'pageSize'=>10,
			),
		));
	
		$params =array(
			'arrayDataProvider'=>$arrayDataProvider,
		);
	
		if(!isset($_GET['ajax'])) 
		{$this->render('index', $params);}
		else  
		{$this->renderPartial('index', $params);}
	}

	
	public function actionUpdatePorteur()
	{
		$esoterForm = new EsoterDefineForm();		
        $porteurMap = Yii::app()->session['porteur'];
        $UseFile    =  __DIR__.'/../config/Config_Opened/useConf_'.$porteurMap.'.php';
        $ConfigFile =  __DIR__.'/../config/Config_porteurMap/Config_'.$porteurMap.'/Config_'.$porteurMap.'.php';  

      
       if( Yii::app()->request->getParam( 'EsoterDefineForm' ) !== NULL ) {
         	$esoterForm->attributes = Yii::app()->request->getParam( 'EsoterDefineForm' );

			$bakConfigFile = __DIR__.'/../config/Config_Backup/Config_'.$porteurMap.'_'.date("Y-m-d-H-i-s").'.php'; 
			copy($ConfigFile, $bakConfigFile);
           
			if($esoterForm->validate(array('porteurFolder','porteurAlias','porteurMap','porteurDNS')) && in_array($esoterForm->porteurFolder, $this->checkFolder()) && file_exists(SERVER_ROOT.$esoterForm->porteurFolder.'/confi.php') ){
					// Log l'action courante :
					 $this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );
		       	     $this->writeConfigFile($ConfigFile,Yii::app()->request->getParam( 'EsoterDefineForm' ));	       		 		
					 $this->writeUseFile($UseFile,$porteurMap);	
					 $this->renderPartial('//esoterDefine/UpdatePorteur',array('EsotreDefine'=>$esoterForm)); 
			 }else{
             		 echo('KO');
             		 Yii::app()->end();
		     }
			 		 				
		}else{

			  @require($UseFile);
			  if($_USE[$porteurMap]['email']  == '' || $_USE[$porteurMap]["email"] == Yii::app()->user->getState('User')->email ){

				 	$this->writeUseFile($UseFile, $porteurMap, Yii::app()->user->getState('User')->email);    
         
	                
	                @require( __DIR__.'/../config/Config_porteurMap/Config_'.$porteurMap.'/Config_'.$porteurMap.'.php' );
							
	
					$esoterForm->porteurMap       =  $_GLOBALS2['porteurMap'][$porteurMap];
					$esoterForm->porteurFolder    =  $_GLOBALS2['porteurFolder'][$porteurMap];
					$esoterForm->imageFolder      =  $GLOBALS['porteurPathPhoto'][$porteurMap];
					$esoterForm->porteurDNS       =  $GLOBALS['porteurDNS'][$porteurMap];
					$esoterForm->porteurMail      =  $GLOBALS['porteurMail'][$porteurMap];
					$esoterForm->compteEMVactif   =  $GLOBALS['porteurCompteEMVactif'][$porteurMap];

					if($GLOBALS["porteurWithTwoSFAccounts"][$porteurMap]){
						$esoterForm->SFAccountsMap    =  $GLOBALS['SFAccountsMap'][$esoterForm->compteEMVactif];
					}	
					
					$esoterForm->porteurAlias     =  $_GLOBALS2['listPorteur'][$porteurMap]['alias'];
					$esoterForm->porteurName      =  $_GLOBALS2['listPorteur'][$porteurMap]['port-name'];
					$esoterForm->porteurPere      =  $_GLOBALS2['porteurPere'][$porteurMap];
					

					if($_GLOBALS2["porteurMultiSite"][$porteurMap] != 0){
						$esoterForm->porteurMultiSite = $_GLOBALS2["porteurMultiSite"][$porteurMap];  
						$esoterForm->DefaultSite      = $GLOBALS['DefaultSite'][$porteurMap];
					}
					
					
					$esoterForm->porteurWithTwoSFAccounts = $GLOBALS['porteurWithTwoSFAccounts'][$porteurMap];
					$esoterForm->porteurRedirectV1        = $GLOBALS['porteurRedirectV1'][$porteurMap];
					
					
					if($esoterForm->SFAccountsMap){
							$mapEMV = $esoterForm->SFAccountsMap;
					}else{
						    $mapEMV = $porteurMap;
					}
						
					$esoterForm->porteurWebformUpdateClient     = $GLOBALS['porteurWebformUpdateClient'][$mapEMV];
					$esoterForm->porteurWebformInscrirClient    = $GLOBALS['porteurWebformInscrirClient'][$mapEMV];
					$esoterForm->porteurWebformDesinscrirClient = $GLOBALS['porteurWebformDesinscrirClient'][$mapEMV];
					
					$esoterForm->UrlRefundDone      = $GLOBALS['UrlRefundDone'][$mapEMV];
					$esoterForm->UrlRefundReceived  = $GLOBALS['UrlRefundReceived'][$mapEMV];
					$esoterForm->UrlResendProduct   = $GLOBALS['UrlResendProduct'][$mapEMV];
					$esoterForm->UrlResendProductV1 = $GLOBALS['UrlResendProductV1'][$mapEMV];
					
					if(isset($_GLOBALS2["isoter_file_mapping"][$porteurMap])){
								$esoterForm->isoterFileMapping  = $_GLOBALS2["isoter_file_mapping"][$porteurMap];

					}
					
					if(isset($GLOBALS["SendMailSAV"][$porteurMap])){
								$esoterForm->SendMailSAVkey  = $GLOBALS["SendMailSAV"][$mapEMV];

					}
					
				    	
					
					
										
					$this->renderPartial('UpdatePorteur',array('EsotreDefine'=>$esoterForm));                            
               
			 }else{
			 		$this->renderPartial('//esoterDefine/FileUse',array('conf'=>'Porteur', 'user'=>$_USE[$porteurMap]['email']));
			 }
			 
		}
		
 	
	}

		/**
		 * @method actionAddPorteur()
		 * Afficher la configuration du  Porteur
		 * @View ViewPorteur
		 */
		 
		public function actionViewPorteur() {
			
				$esoterForm = new EsoterDefineForm();		
		        $porteurMap = Yii::app()->session['porteur'];
		
		        $ConfigFile =  __DIR__.'/../config/Config_porteurMap/Config_'.$porteurMap.'/Config_'.$porteurMap.'.php';  
		
                @require($ConfigFile);
							
				$esoterForm->porteurMap       =  $_GLOBALS2['porteurMap'][$porteurMap];
				$esoterForm->porteurFolder    =  $_GLOBALS2['porteurFolder'][$porteurMap];
				$esoterForm->imageFolder      =  $GLOBALS['porteurPathPhoto'][$porteurMap];
				$esoterForm->porteurDNS       =  $GLOBALS['porteurDNS'][$porteurMap];
				$esoterForm->porteurMail      =  $GLOBALS['porteurMail'][$porteurMap];
				$esoterForm->compteEMVactif   =  $GLOBALS['porteurCompteEMVactif'][$porteurMap];

				if($GLOBALS["porteurWithTwoSFAccounts"][$porteurMap]){
					$esoterForm->SFAccountsMap    =  $GLOBALS['SFAccountsMap'][$esoterForm->compteEMVactif];
				}	
				
				$esoterForm->porteurAlias     =  $_GLOBALS2['listPorteur'][$porteurMap]['alias'];
				$esoterForm->porteurName      =  $_GLOBALS2['listPorteur'][$porteurMap]['port-name'];
				$esoterForm->porteurPere      =  $_GLOBALS2['porteurPere'][$porteurMap];
				

				if($_GLOBALS2["porteurMultiSite"][$porteurMap] != 0){
					$esoterForm->porteurMultiSite = $_GLOBALS2["porteurMultiSite"][$porteurMap];  
					$esoterForm->DefaultSite      = $GLOBALS['DefaultSite'][$porteurMap];
				}
				
				
				$esoterForm->porteurWithTwoSFAccounts = $GLOBALS['porteurWithTwoSFAccounts'][$porteurMap];
				$esoterForm->porteurRedirectV1        = $GLOBALS['porteurRedirectV1'][$porteurMap];
				
				$esoterForm->porteurWebformUpdateClient     = $GLOBALS['porteurWebformUpdateClient'][$porteurMap];
				$esoterForm->porteurWebformInscrirClient    = $GLOBALS['porteurWebformInscrirClient'][$porteurMap];
				$esoterForm->porteurWebformDesinscrirClient = $GLOBALS['porteurWebformDesinscrirClient'][$porteurMap];
				
				$esoterForm->UrlRefundDone      = $GLOBALS['UrlRefundDone'][$porteurMap];
				$esoterForm->UrlRefundReceived  = $GLOBALS['UrlRefundReceived'][$porteurMap];
				$esoterForm->UrlResendProduct   = $GLOBALS['UrlResendProduct'][$porteurMap];
				$esoterForm->UrlResendProductV1 = $GLOBALS['UrlResendProductV1'][$porteurMap];
				
				if(isset($GLOBALS["SendMailSAV"][$porteurMap])){
								$esoterForm->SendMailSAVkey  = $GLOBALS["SendMailSAV"][$porteurMap];

					}
				
				if(isset($_GLOBALS2["isoter_file_mapping"][$porteurMap]) != 0){
					$esoterForm->isoterFileMapping  = $_GLOBALS2["isoter_file_mapping"][$porteurMap];
				}
								
													
				$this->renderPartial('ViewPorteur',array('EsotreDefine'=>$esoterForm));                            
		               	
			}

	
		/**
		 * @method actionAddPorteur()
		 * Creation D'un nouveau Porteur
		 * @View addPorteur
		 */

		public function actionAddPorteur() {
			
			   $esoterForm = new EsoterDefineForm();
			   			   
		       if( Yii::app()->request->getParam( 'EsoterDefineForm' ) !== NULL ) {		              
		   
		       	     $esoterForm->attributes = Yii::app()->request->getParam( 'EsoterDefineForm' );
		             $porteurMap = Yii::app()->request->getParam( 'EsoterDefineForm' )['porteurMap'];
		             $UseFile    =  __DIR__.'/../config/Config_Opened/useConf_'.$porteurMap.'.php';
		             
		             if($esoterForm->validate(array('porteurFolder','porteurAlias','porteurMap','porteurDNS')) && in_array($esoterForm->porteurFolder, $this->checkFolder() ) && file_exists(SERVER_ROOT.$esoterForm->porteurFolder.'/confi.php')){
		             	if (@mkdir(__DIR__.'/../config/Config_porteurMap/Config_'.$porteurMap)) {

		             		chmod(__DIR__.'/../config/Config_porteurMap/Config_'.$porteurMap, 0777);
		             		$ConfigFile =  __DIR__.'/../config/Config_porteurMap/Config_'.$porteurMap.'/Config_'.$porteurMap.'.php';
		             		// Log l'action courante :
					        $this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );
		             		$this->writeUseFile($UseFile,$porteurMap);	
		             		$this->writeConfigFile($ConfigFile,Yii::app()->request->getParam( 'EsoterDefineForm' ));
		             	}
		             	
		             }else{
		             		echo('KO');
		             		Yii::app()->end();
		             }
		            	       		 							 					 		 				
				}
					 
				$this->renderPartial('addPorteur',array('EsotreDefine'=>$esoterForm));  		 	
		}
  


		
		/**
		 * @method actionAddCompte()
		 * Create SF Account
		 */
    
    public function actionAddCompte(){
    	
    	$porteurMap = Yii::app()->session['porteur'];
    	$esoterForm     = new EsoterDefineForm(); 
    
    	if(Yii::app()->request->getParam('EsoterDefineForm') != NULL){
    		$esoterForm->attributes = Yii::app()->request->getParam('EsoterDefineForm') ;

			if($esoterForm->validate(array('compteEMVactif','SFAccountsMap'))){
    		    $compte     = Yii::app()->request->getParam('EsoterDefineForm')['SFAccountsMap'];   		    
    			$UseFile    =  __DIR__.'/../config/Config_Opened/useCompte_'.$compte.'.php';

    			$configCompteFile = __DIR__.'/../config/Config_porteurMap/Config_'.$porteurMap.'/Compte-'.$compte.'.php'; 

				// Log l'action courante :
				$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );
    			$this->writeUseFile($UseFile,$compte);
    			$this->writeCompteFile($configCompteFile,Yii::app()->request->getParam('EsoterDefineForm'),$compte); 
    		 }else{
             		 echo('KO');
             		 Yii::app()->end();
		     }
    	}
    	 
    	$this->renderPartial( 'AddCompte', array('EsotreDefine' => $esoterForm));
    	 
    }
    
     /**
	   * @method actionListComptes()
	   * Afficher la liste des comptes SF
	   */

	public function actionListComptes(){
	   	
	   	    $porteurMap       = Yii::app()->session['porteur']; 
	   	    $ConfigPorteurMap = __DIR__.'/../config/Config_porteurMap/Config_'.$porteurMap;
	   	    
		    $configFileComptes  = preg_grep('/^Compte.*/', scandir($ConfigPorteurMap));
	     	$rawData = array();
			foreach($configFileComptes as $configFileCompteKey => $configFilecompte){
			  	    $porteur = explode('.',explode('-',$configFilecompte)[1])[0];
			  		@require( $ConfigPorteurMap.'/'. $configFilecompte );
			  		
					$rawData[] = array( 'id'           => $configFileCompteKey, 
		                                'NomPorteur'   => $_GLOBALS2['porteurName'][$porteur] , 
		                                'CompteSF'    => $_GLOBALS2['SFAccountName'][$porteur], 
		                                'porteur'      => $_GLOBALS2['porteur'][$porteur]);
					
		    }

			$arrayDataProvider=new CArrayDataProvider($rawData, array(
				 'id'=>'id',
				 'sort'=>array(
					'attributes'=>array(
						'NomPorteur', 'CompteSF'
					),
				),
				'pagination'=>array(
					'pageSize'=>10,
				),
			));
		
			$params =array(
				'arrayDataProvider'=>$arrayDataProvider,
			);
		
			if(!isset($_GET['ajax'])) 
			{$this->render('ListComptes', $params);}
			else  
			{$this->renderPartial('ListComptes', $params);}
	   }

	  /**
	   * @method actionRemoveCompte
	   * Supprimer un compte SF
	   */
		public function actionRemoveCompte(){
		    	 // Log l'action courante :
				 $this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );
		    	 $porteur           = Yii::app()->request->getParam('compte');
		    	 $porteurMap        = Yii::app()->session['porteur'];
		    	 $ConfigPorteurMap  = __DIR__.'/../config/Config_porteurMap/Config_'.$porteurMap.'/Compte-'.$porteur.'.php';
	
		    	 $bakCompteFile = __DIR__.'/../config/Removed/Site-'.$porteur.'_'.date("Y-m-d-H-i-s").'.php'; 
				 copy($ConfigPorteurMap, $bakCompteFile);
	
				 try{
				 	unlink($ConfigPorteurMap);
				 }catch(Exception $e){
				 	echo("KO");
				 }    	
		}

	/**
     * @method  actionViewCompte()
     * 
	 * Afficher Les information d'un compte
	 *
	 */
    public function actionViewCompte(){
    	
    	 $porteur    = Yii::app()->request->getParam('compte');
    	 $porteurMap = Yii::app()->session['porteur'];

    	 @require(__DIR__.'/../config/Config_porteurMap/Config_'.$porteurMap.'/Compte-'.$porteur.'.php');
    	   
    	 $esoterForm = new EsoterDefineForm(); 

    	 $compteSF = $_GLOBALS2['SFAccountName'][$porteur];

    	 $esoterForm->compte               =  $_GLOBALS2['porteur'][$porteur];
    	 $esoterForm->compteEMVactif       =  $compteSF;
    	 $esoterForm->SFAccountsMap        =  $GLOBALS['SFAccountsMap'][$compteSF];
    	 $esoterForm->porteurName          =  $_GLOBALS2['porteurName'][$porteur];

		$esoterForm->porteurWebformUpdateClient     = $GLOBALS["porteurWebformUpdateClient"][$porteur];
		$esoterForm->porteurWebformInscrirClient    = $GLOBALS["porteurWebformInscrirClient"][$porteur];
		$esoterForm->porteurWebformDesinscrirClient = $GLOBALS["porteurWebformDesinscrirClient"][$porteur];
		$esoterForm->UrlRefundDone                  = $GLOBALS["UrlRefundDone"][$porteur];
		$esoterForm->UrlRefundReceived              = $GLOBALS["UrlRefundReceived"][$porteur];
		$esoterForm->UrlResendProduct               = $GLOBALS["UrlResendProduct"][$porteur];
		$esoterForm->UrlResendProductV1             = $GLOBALS["UrlResendProductV1"][$porteur];
		
        if(isset($GLOBALS["SendMailSAV"][$porteur])){
				$esoterForm->SendMailSAVkey  = $GLOBALS["SendMailSAV"][$porteur];

		} 
    	 $this->renderPartial( 'ViewCompte', array('EsotreDefine' => $esoterForm));
    	
    }



   /**
     * @method  ViewCompte()
     * 
	 * Modifier la configuration d'un Compte
	 *
	 */
    public function actionUpdateCompte(){
    	
    	 $porteur    = Yii::app()->request->getParam('compte');
    	 $porteurMap = Yii::app()->session['porteur'];
		 $esoterForm = new EsoterDefineForm();

    	 $configCompteFile = __DIR__.'/../config/Config_porteurMap/Config_'.$porteurMap.'/Compte-'.$porteur.'.php';   	  
		 $UseFile          = __DIR__.'/../config/Config_Opened/useCompte_'.$porteur.'.php';
         if(Yii::app()->request->getParam('EsoterDefineForm') != NULL){      
        	 $esoterForm->attributes = Yii::app()->request->getParam( 'EsoterDefineForm' );   	
          	 $compte     = Yii::app()->request->getParam('EsoterDefineForm')['SFAccountsMap'];  
          	 
          	    $bakCompteFile = __DIR__.'/../config/Config_Backup/Compte-'.$compte.'_'.date("Y-m-d-H-i-s").'.php'; 
				copy($configCompteFile , $bakCompteFile);  

				if($esoterForm->validate(array('compteEMVactif','SFAccountsMap'))){
						 // Log l'action courante :
					      $this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );
			       	      $this->writeCompteFile($configCompteFile,Yii::app()->request->getParam('EsoterDefineForm'));	       		 		
						  $this->writeUseFile($UseFile,$compte);	
				 }else{
	             		 echo('KO');
	             		 Yii::app()->end();
			     }	      
         }else{

			  @require($UseFile);
			  if($_USE[$porteur]['email']  == '' || $_USE[$porteur]["email"] == Yii::app()->user->getState('User')->email ){

       			 $this->writeUseFile($UseFile, $porteur, Yii::app()->user->getState('User')->email);               
	                   
       			  @require($configCompteFile);
    	   
    	 

    	 $compteSF = $_GLOBALS2['SFAccountName'][$porteur];

    	 $esoterForm->compte               =  $_GLOBALS2['porteur'][$porteur];
    	 $esoterForm->compteEMVactif       =  $compteSF;
    	 $esoterForm->SFAccountsMap        =  $GLOBALS['SFAccountsMap'][$compteSF];
		 $esoterForm->porteurName          =  $_GLOBALS2['porteurName'][$porteur];
		 $esoterForm->porteurWebformUpdateClient     = $GLOBALS["porteurWebformUpdateClient"][$porteur];
		 $esoterForm->porteurWebformInscrirClient    = $GLOBALS["porteurWebformInscrirClient"][$porteur];
		 $esoterForm->porteurWebformDesinscrirClient = $GLOBALS["porteurWebformDesinscrirClient"][$porteur];
		 $esoterForm->UrlRefundDone                  = $GLOBALS["UrlRefundDone"][$porteur];
		 $esoterForm->UrlRefundReceived              = $GLOBALS["UrlRefundReceived"][$porteur];
		 $esoterForm->UrlResendProduct               = $GLOBALS["UrlResendProduct"][$porteur];
		 $esoterForm->UrlResendProductV1             = $GLOBALS["UrlResendProductV1"][$porteur];
		 
		if(isset($GLOBALS["SendMailSAV"][$porteur])){
					$esoterForm->SendMailSAVkey  = $GLOBALS["SendMailSAV"][$porteur];
	
		}
       
        
    	 $this->renderPartial( 'UpdateCompte', array('EsotreDefine' => $esoterForm));
    	}else{
					 		$this->renderPartial('//esoterDefine/FileUse',array('conf'=>'Site', 'user'=>$_USE[$porteur]['email']));
			    }
	  	}
    }
  




	  /**
	   * @method actionListSites()
	   * Afficher la liste des sites de ce porteur
	   * Les fichier erronés sont ignorés
	   */
	   
	   public function actionListSites(){
	   	
	   	    $porteurMap       = Yii::app()->session['porteur']; 
	   	    $ConfigPorteurMap = __DIR__.'/../config/Config_porteurMap/Config_'.$porteurMap;
	   	    
		    $configFileSites  = preg_grep('/^Site.*/', scandir($ConfigPorteurMap));
			$rawData = array();
			foreach($configFileSites as $configFileSiteKey => $configFileSite){

			  	    $porteur = explode('.',explode('-',$configFileSite)[1])[0];
			  	    
			  			$FileConf = shell_exec('php -l '.$ConfigPorteurMap.'/'. $configFileSite);
						if(substr($FileConf, 0, 16) == 'No syntax errors') {
						    @require($ConfigPorteurMap.'/'. $configFileSite);
						} else {
						    continue;
						}
			  		try{
						$rawData[] = array( 'id'         => $configFileSiteKey, 
			                                'NomPorteur'   => $_GLOBALS2['listPorteur'][$porteur]['port-name'], 
			                                'Site'         => $_GLOBALS2[$porteur]['site'], 
			                                'Nomdedomaine' => $GLOBALS['porteurDNS'][$porteur] ,
			                                'porteur'      => $_GLOBALS2[$porteur]['porteur']);
			  		}catch(Exception $e) {  
			  
				 		 continue;
				  
	  				 }
		    }

			$arrayDataProvider=new CArrayDataProvider($rawData, array(
				 'id'=>'id',
				 'sort'=>array(
					'attributes'=>array(
						'NomPorteur', 'Site','Nomdedomaine'
					),
				),
				'pagination'=>array(
					'pageSize'=>10,
				),
			));
		
			$params =array(
				'arrayDataProvider'=>$arrayDataProvider,
			);
		
			if(!isset($_GET['ajax'])) 
			{$this->render('ListSites', $params);}
			else  
			{$this->renderPartial('ListSites', $params);}
   }



    /**
     * @method  actionViewSite()
     * 
	 * Afficher Les information d'un site
	 *
	 */
    public function actionViewSite(){
    	
    	 $porteur    = Yii::app()->request->getParam('site');
    	 $porteurMap = Yii::app()->session['porteur'];
    	 
    	 @require(__DIR__.'/../config/Config_porteurMap/Config_'.$porteurMap.'/Site-'.$porteur.'.php');
    	   
    	 $esoterForm = new EsoterDefineForm();  
    	
    	 $esoterForm->porteurDNS           =  $GLOBALS['porteurDNS'][$porteur];   	
    	 $esoterForm->porteurAlias         =  $_GLOBALS2['listPorteur'][$porteur]['alias'];
		 $esoterForm->porteurName          =  $_GLOBALS2['listPorteur'][$porteur]['port-name'];			
		 $esoterForm->site                 =  $_GLOBALS2[$porteur]['site'];
		 $esoterForm->porteurFolder        =  $_GLOBALS2['listPorteur'][$porteur]['folder'];
		 
          if(isset($_GLOBALS2["isoter_file_mapping"][$porteur])){
								$esoterForm->isoterFileMapping  = $_GLOBALS2["isoter_file_mapping"][$porteur];

		}
    	 $this->renderPartial( 'ViewSite', array('EsotreDefine' => $esoterForm));
    	
    }
   
    
  
   /**
     * @method  UpdateSite()
     * 
	 * Modifier la configuration d'un site
	 *
	 */
    public function actionUpdateSite(){
    	
    	 $porteur        = Yii::app()->request->getParam('site');
    	 $porteurMap     = Yii::app()->session['porteur'];
    	 $esoterForm = new EsoterDefineForm(); 

    	 $configSiteFile = __DIR__.'/../config/Config_porteurMap/Config_'.$porteurMap.'/Site-'.$porteur.'.php';   	  
 		 $UseFile        = __DIR__.'/../config/Config_Opened/useSite_'.$porteur.'.php';
         
         if(Yii::app()->request->getParam('EsoterDefineForm') != NULL){
	        	$esoterForm->attributes = Yii::app()->request->getParam( 'EsoterDefineForm' );
				
				$porteur = explode('.',explode('-',$configSiteFile)[1])[0];
				
				
				$bakSiteFile = __DIR__.'/../config/Config_Backup/Site-'.$porteur.'_'.date("Y-m-d-H-i-s").'.php'; 
				copy($configSiteFile , $bakSiteFile);  

				if($esoterForm->validate(array('site','porteurAlias','porteurDNS'))){
							// Log l'action courante :
						 $this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );
			       	      $this->writeSiteFile($configSiteFile,Yii::app()->request->getParam('EsoterDefineForm'),$porteur);	       		 		
						  $this->writeUseFile($UseFile,$porteur);	
				 }else{
	             		 echo('KO');
	             		 Yii::app()->end();
			     }
	          	
          	 	      
         }else{

			  @require($UseFile);
			  if($_USE[$porteur]['email']  == '' || $_USE[$porteur]["email"] == Yii::app()->user->getState('User')->email ){

				 	$this->writeUseFile($UseFile, $porteur, Yii::app()->user->getState('User')->email);               
	                           	 
					@require(__DIR__.'/../config/Config_porteurMap/Config_'.$porteurMap.'/Site-'.$porteur.'.php');  
					 	
			    	 $esoterForm->porteurDNS           =  $GLOBALS['porteurDNS'][$porteur];   	
			    	 $esoterForm->porteurAlias         =  $_GLOBALS2['listPorteur'][$porteur]['alias'];
					 $esoterForm->porteurName          =  $_GLOBALS2['listPorteur'][$porteur]['port-name'];			
					 $esoterForm->site                 =  $_GLOBALS2[$porteur]['site'];
					 $esoterForm->porteur              = explode('_', $porteurMap)[1];
			    	 $esoterForm->porteurFolder        = $GLOBALS['porteurMap'][$porteurMap];
					 
					 if(isset($_GLOBALS2["isoter_file_mapping"][$porteur])){
											$esoterForm->isoterFileMapping  = $_GLOBALS2["isoter_file_mapping"][$porteur];

					}
			               
			    	 $this->renderPartial( 'UpdateSite', array('EsotreDefine' => $esoterForm));
			    	
		    	}else{
					 		$this->renderPartial('//esoterDefine/FileUse',array('conf'=>'Site', 'user'=>$_USE[$porteur]['email']));
			    }
	  	}
    }
    
    /**
     * @method  AddSite()
     * 
	 * Ajouter un site 
	 *
	 */
    public function actionAddSite(){
    	
    	 $porteurMap   = Yii::app()->session['porteur'];
    	 $esoterForm   = new EsoterDefineForm(); 
    	 
    	 $esoterForm->porteur = explode('_', $porteurMap)[1];
    	 $esoterForm->porteurFolder = $GLOBALS['porteurMap'][$porteurMap];	  

        if(Yii::app()->request->getParam('EsoterDefineForm') != NULL){ 
        	$esoterForm->attributes = Yii::app()->request->getParam('EsoterDefineForm') ;


        	if($esoterForm->validate(array('porteurDNS','site','porteurAlias'))){
        		$porteur        = Yii::app()->request->getParam('EsoterDefineForm')['site'].'_'.Yii::app()->request->getParam('EsoterDefineForm')['porteur'];     	
          	 	$configSiteFile = __DIR__.'/../config/Config_porteurMap/Config_'.$porteurMap.'/Site-'.$porteur.'.php';  
          	 	$UseFile        = __DIR__.'/../config/Config_Opened/useSite_'.$porteur.'.php';
				// Log l'action courante :
				$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );
				$this->writeUseFile($UseFile,$porteur);
          	 	$this->writeSiteFile($configSiteFile,Yii::app()->request->getParam('EsoterDefineForm'),$porteur);

        	}else{
        		echo('KO');	
          	 	Yii::app()->end();
        	}       	 
          	 	      
         }
                 
    	 $this->renderPartial( 'AddSite', array('EsotreDefine' => $esoterForm));
    	
    }
    
  
  

  /**
   * @method actionRemoveSite
   * Supprimer un site
   */
	public function actionRemoveSite(){
	    	
	    	 $porteur           = Yii::app()->request->getParam('site');
	    	 $porteurMap        = Yii::app()->session['porteur'];
	    	 $ConfigPorteurMap  = __DIR__.'/../config/Config_porteurMap/Config_'.$porteurMap.'/Site-'.$porteur.'.php';

			 // Log l'action courante :
			 $this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );
	    	 $bakSiteFile = __DIR__.'/../config/Removed/Site-'.$porteur.'_'.date("Y-m-d-H-i-s").'.php'; 
			 copy($ConfigPorteurMap, $bakSiteFile);

			 try{
			 	unlink($ConfigPorteurMap);
			 }catch(Exception $e){
			 	echo("KO");
			 }    	
	}
	






	public function writeCompteFile($ConfigFile,$EsoterDefineForm, $compte=NULL){
		
		if(isset($compte)){
			$EsoterDefineForm["compte"] =  $this->FormatStr($compte);
		}	
			 
	   	$updatedContents ='<?php $_GLOBALS2["SFAccountName"]["'.$this->FormatStr($EsoterDefineForm["compte"]).'"]   = "'.$this->FormatStr($EsoterDefineForm["compteEMVactif"]).'";
							$_GLOBALS2["porteur"]["'.$this->FormatStr($EsoterDefineForm["compte"]).'"]              = "'.$this->FormatStr($EsoterDefineForm["compte"]).'";
	
							$_GLOBALS2["porteurName"]["'.$this->FormatStr($EsoterDefineForm["compte"]).'"]          = "'.$this->FormatStr($EsoterDefineForm["porteurName"]).'";
	
	
							$GLOBALS["SFAccountsMap"]["'.$this->FormatStr($EsoterDefineForm["compteEMVactif"]).'"] = "'.$this->FormatStr($EsoterDefineForm["SFAccountsMap"]).'";
	
							$GLOBALS["porteurWebformUpdateClient"]["'.$this->FormatStr($EsoterDefineForm["compte"]).'"]     = "'.$this->FormatStr($EsoterDefineForm["porteurWebformUpdateClient"]).'";
							$GLOBALS["porteurWebformInscrirClient"]["'.$this->FormatStr($EsoterDefineForm["compte"]).'"]    = "'.$this->FormatStr($EsoterDefineForm["porteurWebformInscrirClient"]).'";
							$GLOBALS["porteurWebformDesinscrirClient"]["'.$this->FormatStr($EsoterDefineForm["compte"]).'"] = "'.$this->FormatStr($EsoterDefineForm["porteurWebformDesinscrirClient"]).'";
	
							$GLOBALS["UrlRefundDone"]["'.$this->FormatStr($EsoterDefineForm["compte"]).'"]                  = "'.$this->FormatStr($EsoterDefineForm["UrlRefundDone"]).'";
							$GLOBALS["UrlRefundReceived"]["'.$this->FormatStr($EsoterDefineForm["compte"]).'"]              = "'.$this->FormatStr($EsoterDefineForm["UrlRefundReceived"]).'";
							$GLOBALS["UrlResendProduct"]["'.$this->FormatStr($EsoterDefineForm["compte"]).'"]               = "'.$this->FormatStr($EsoterDefineForm["UrlResendProduct"]).'";
							$GLOBALS["UrlResendProductV1"]["'.$this->FormatStr($EsoterDefineForm["compte"]).'"]             = "'.$this->FormatStr($EsoterDefineForm["UrlResendProductV1"]).'";';
							
		           if(isset($_POST['SendMailSAVKey'])){
					  	foreach ($_POST['SendMailSAVKey'] as $key => $mailSav){
								$updatedContents .= '$GLOBALS["SendMailSAV"]["'.$this->FormatStr($EsoterDefineForm["compte"]).'"]["'.$this->FormatStr($key).'"] = "'.$this->FormatStr($mailSav).'";';
		
					  	}
					}
					
		 $updatedContents .= '?>';								
	     $fs = fopen( $ConfigFile, "w+" );
	     fwrite($fs, $updatedContents);
	     chmod($ConfigFile, 0777);
	     fclose($fs);
	
	   }
  
  
   public function writeSiteFile($ConfigFile,$EsoterDefineForm, $porteur = NULL){
   	
   		if(isset($porteur)){
   		   $EsoterDefineForm["porteur"] =  $this->FormatStr($porteur);
  	 	}	
   				 
	   	$updatedContents ='<?php
						   	$_GLOBALS2["listPorteur"]["'.$this->FormatStr($EsoterDefineForm["porteur"]).'"]["alias"]      = "'.$this->FormatStr($EsoterDefineForm["porteurAlias"]).'";
						    $_GLOBALS2["listPorteur"]["'.$this->FormatStr($EsoterDefineForm["porteur"]).'"]["port-name"]  = "'.$this->FormatStr($EsoterDefineForm["porteurName"]).'";
							$_GLOBALS2["listPorteur"]["'.$this->FormatStr($EsoterDefineForm["porteur"]).'"]["folder"]     = "'.$this->FormatStr($EsoterDefineForm["porteurFolder"]).'";		
							$_GLOBALS2["'.$this->FormatStr($EsoterDefineForm["porteur"]).'"]["site"]          = "'.$this->FormatStr($EsoterDefineForm["site"]).'";
							$_GLOBALS2["'.$this->FormatStr($EsoterDefineForm["porteur"]).'"]["porteur"]       = "'.$this->FormatStr($EsoterDefineForm["porteur"]).'";';
	
		$updatedContents .= '$GLOBALS["porteurDNS"]["'.$this->FormatStr($EsoterDefineForm["porteur"]).'"]     =  "'.$this->FormatStr($EsoterDefineForm["porteurDNS"]).'";';
	
	    if(trim($EsoterDefineForm["porteurAlias"]) != '' && trim($EsoterDefineForm["porteurName"]) != ''){
	    	$updatedContents .= '$GLOBALS["listPorteur"]["'.$this->FormatStr($EsoterDefineForm["porteurAlias"]).'"]     =  array("port-name" => "'.$this->FormatStr($EsoterDefineForm["porteurName"]).'", "folder" => "'.$this->FormatStr($EsoterDefineForm["porteurFolder"]).'");';
			
	    }
	    
	    if(isset($_POST['isoterFileMapping'])){
		  	foreach ($_POST['isoterFileMapping'] as $key => $file){
		  		if(trim($file) != ''){
		  			$updatedContents .= '$GLOBALS["isoter_file_mapping"]["'.$this->FormatStr($file).'"] = "'.$this->FormatStr($EsoterDefineForm["porteurFolder"]).'";';
		  		    $updatedContents .= '$_GLOBALS2["isoter_file_mapping"]["'.$this->FormatStr($EsoterDefineForm["porteur"]).'"][]  = "'.$file.'";';
		  		} 		
		  	}
	    }
			
		 $updatedContents.='?>';
		 							
	     $fs = fopen( $ConfigFile, "w+" );	     
	     fwrite($fs, $updatedContents);
	     chmod($ConfigFile, 0777);
	     fclose($fs);

   }
  
  

	
	public function writeConfigFile($ConfigFile,$EsoterDefineForm){
	      $Begin = '<?php ';
	      $End   = '?>';
		  $porteurMap      =    $this->FormatStr($EsoterDefineForm["porteurMap"]); 		   
          $updatedContents =   '  $_GLOBALS2["porteurFolder"]["'.$this->FormatStr($porteurMap).'"]            = "'.$this->FormatStr($EsoterDefineForm["porteurFolder"]).'";
								$_GLOBALS2["listPorteur"]["'.$this->FormatStr($porteurMap).'"]["alias"]     = "'.$this->FormatStr($EsoterDefineForm["porteurAlias"]).'";
								$_GLOBALS2["listPorteur"]["'.$this->FormatStr($porteurMap).'"]["port-name"] = "'.$this->FormatStr($EsoterDefineForm["porteurName"]).'";
								$_GLOBALS2["porteurMultiSite"]["'.$this->FormatStr($porteurMap).'"]         =  "'.$this->FormatStr($EsoterDefineForm["porteurMultiSite"]).'";
								$_GLOBALS2["porteurWithTwoSFAccounts"]["'.$this->FormatStr($porteurMap).'"]  = "'.$this->FormatStr($EsoterDefineForm["porteurWithTwoSFAccounts"]).'";
								$_GLOBALS2["porteurPere"]["'.$this->FormatStr($porteurMap).'"]              = "'.$this->FormatStr($EsoterDefineForm["porteurPere"]).'";
								$_GLOBALS2["porteurMap"]["'.$this->FormatStr($porteurMap).'"]              = "'.$this->FormatStr($porteurMap).'";
								
								$GLOBALS["porteurMap"]["'.$this->FormatStr($porteurMap).'"]        = "'.$this->FormatStr($EsoterDefineForm["porteurFolder"]).'";
								$GLOBALS["porteurDNS"]["'.$this->FormatStr($porteurMap).'"]        = "'.$this->FormatStr($EsoterDefineForm["porteurDNS"]).'";
								$GLOBALS["porteurPathPhoto"]["'.$this->FormatStr($porteurMap).'"]  = "'.$this->FormatStr($EsoterDefineForm["imageFolder"]).'";
								$GLOBALS["porteurMail"]["'.$this->FormatStr($porteurMap).'"]       = "'.$this->FormatStr($EsoterDefineForm["porteurMail"]).'";
								$GLOBALS["porteurCompteEMVactif"]["'.$this->FormatStr($porteurMap).'"]    = "'.$this->FormatStr($EsoterDefineForm["compteEMVactif"]).'";';
								
								$EsoterDefineForm["porteurWithTwoSFAccounts"] = ($EsoterDefineForm["porteurWithTwoSFAccounts"]=="0") ? "false":"true";
								$EsoterDefineForm["porteurRedirectV1"]        = ($EsoterDefineForm["porteurRedirectV1"]=="0") ? "false":"true";
								
			 $updatedContents .= '$GLOBALS["porteurWithTwoSFAccounts"]["'.$this->FormatStr($porteurMap).'"] = '.$this->FormatStr($EsoterDefineForm["porteurWithTwoSFAccounts"]).';
								  $GLOBALS["porteurRedirectV1"]["'.$this->FormatStr($porteurMap).'"]        = '.$this->FormatStr($EsoterDefineForm["porteurRedirectV1"]).';';
								  
						if($EsoterDefineForm["porteurWithTwoSFAccounts"]=="true" && trim($EsoterDefineForm["compteEMVactif"]) !=''){
							$updatedContents .=' $GLOBALS["SFAccountsMap"]["'.$this->FormatStr($EsoterDefineForm["compteEMVactif"]).'"] = "'.$this->FormatStr($EsoterDefineForm["SFAccountsMap"]).'";';
							
							$mapEMV = $this->FormatStr($EsoterDefineForm["SFAccountsMap"]);
							
						}else{
							$mapEMV = $this->FormatStr($EsoterDefineForm["porteurMap"]);
						}
		
						
							
							 
			$updatedContents .='$GLOBALS["porteurWebformUpdateClient"]["'.$mapEMV.'"]     = "'.$this->FormatStr($EsoterDefineForm["porteurWebformUpdateClient"]).'";
								$GLOBALS["porteurWebformInscrirClient"]["'.$mapEMV.'"]    = "'.$this->FormatStr($EsoterDefineForm["porteurWebformInscrirClient"]).'";
								$GLOBALS["porteurWebformDesinscrirClient"]["'.$mapEMV.'"] = "'.$this->FormatStr($EsoterDefineForm["porteurWebformDesinscrirClient"]).'";
								$GLOBALS["UrlRefundDone"]["'.$mapEMV.'"]                  = "'.$this->FormatStr($EsoterDefineForm["UrlRefundDone"]).'";
								$GLOBALS["UrlRefundReceived"]["'.$mapEMV.'"]              = "'.$this->FormatStr($EsoterDefineForm["UrlRefundReceived"]).'";
								$GLOBALS["UrlResendProduct"]["'.$mapEMV.'"]               = "'.$this->FormatStr($EsoterDefineForm["UrlResendProduct"]).'";
								$GLOBALS["UrlResendProductV1"]["'.$mapEMV.'"]             = "'.$this->FormatStr($EsoterDefineForm["UrlResendProductV1"]).'";
								$GLOBALS["porteurAC2"]["'.$porteurMap.'"]                     = "'.$this->FormatStr($EsoterDefineForm["porteurFolder"]).'";
										
								$GLOBALS["porteurPere"]["'.$this->FormatStr($EsoterDefineForm["porteurPere"]).'"][]   = array("'.str_replace("-"," ",$this->FormatStr($EsoterDefineForm["porteurAlias"])).'","'.$this->FormatStr($EsoterDefineForm["porteurMap"]).'");
								$GLOBALS["listPorteur"]["'.$this->FormatStr($EsoterDefineForm["porteurAlias"]).'"]    =  array("port-name" => "'.$this->FormatStr($EsoterDefineForm["porteurName"]).'", "folder" => "'.$this->FormatStr($EsoterDefineForm["porteurFolder"]).'");';
								
								if($EsoterDefineForm["porteurMultiSite"] =="1"){
									$updatedContents .='$GLOBALS["DefaultSite"]["'.$this->FormatStr($porteurMap).'"] ="'.$this->FormatStr($EsoterDefineForm["DefaultSite"]).'";';
									$updatedContents .='$GLOBALS["porteurMultiSite"][] ="'.$this->FormatStr($EsoterDefineForm["porteurMap"]).'";';
								}
			 			
								  if(isset($_POST['isoterFileMapping'])){
									  	foreach ($_POST['isoterFileMapping'] as $key => $file){
									  		if(trim($file) != ''){
									  			$updatedContents .= '$GLOBALS["isoter_file_mapping"]["'.$this->FormatStr($file).'"] = "'.$this->FormatStr($porteurMap).'";';
									  			$updatedContents .= '$_GLOBALS2["isoter_file_mapping"]["'.$this->FormatStr($porteurMap).'"][]  = "'.$this->FormatStr($file).'";';
									  		} 		
									  	}
								  }
		
		                         if(isset($_POST['SendMailSAVKey'])){
									  	foreach ($_POST['SendMailSAVKey'] as $key => $mailSav){
									  		
									  			$updatedContents .= '$GLOBALS["SendMailSAV"]["'.$mapEMV.'"]["'.$this->FormatStr($key).'"] = "'.$this->FormatStr($mailSav).'";';
									  	 		
									  	}
								  }
								  
          	  	 $updatedContents = $Begin.$updatedContents.$End;	
		  	
				 $fs = fopen( $ConfigFile, "w+" );
			     fwrite($fs, $updatedContents);
			     chmod($ConfigFile, 0777);
			     fclose($fs);
	}
	
	
	
	public function writeUseFile($UseFile,$porteurMap, $email = NULL){		
		     $updatedContents = '<?php $_USE["'.$porteurMap.'"]["email"] = "'.$email.'";?>';
			 $fs = fopen( $UseFile, "w+" );
			 fwrite($fs, $updatedContents);
			 if($email == NULL){
			 	chmod($UseFile, 0777);
			 } 				
		     fclose($fs);
		     
	}
	
	public function actionFreeUseFile(){	
	         $porteurMap        = Yii::app()->session['porteur'];	
		     $updatedContents = '<?php $_USE["'.$porteurMap.'"]["email"] = "";?>';
		     $UseFile = __DIR__.'/../config/Config_Opened/useConf_'.$porteurMap.'.php';
			 $fs = fopen( $UseFile, "w+" );
			 fwrite($fs, $updatedContents);
		     fclose($fs);
		     
	}
	
	public function actionFreeUseSiteFile(){	
			 $porteur =	Yii::app()->request->getParam('site');
		     $updatedContents = '<?php $_USE["'.$porteur.'"]["email"] = "";?>';
		     $UseFile = __DIR__.'/../config/Config_Opened/useSite_'.$porteur.'.php';
			 $fs = fopen( $UseFile, "w+" );
			 fwrite($fs, $updatedContents);
		     fclose($fs);		     
	}
	
	public function actionFreeUsecompteFile(){	
			 $porteur =	Yii::app()->request->getParam('compte');
		     $updatedContents = '<?php $_USE["'.$porteur.'"]["email"] = "";?>';
		     $UseFile = __DIR__.'/../config/Config_Opened/useCompte_'.$porteur.'.php';
			 $fs = fopen( $UseFile, "w+" );
			 fwrite($fs, $updatedContents);
		     fclose($fs);		     
	}
	
	public function actionFileIsOpened(){
		     $porteurMap = Yii::app()->session['porteur'];
		     @require(__DIR__.'/../config/Config_Opened/use_'.$porteurMap.'.php');
			 return $_USE[$porteurMap]['email'] ;
			 
	}
	
	public function EsoterAccess($role){
		if(\Yii::App()->User->checkAccess($role))
		{return true;}
		else
		{return false;}
	}
	
	public function checkFolder(){
		return array_diff(scandir(SERVER_ROOT), array('..', '.'));
	}
	
	public function  FormatStr($str){
		return str_replace(array('\\' , '"' ,  '\'' ),'', $str); 
	}
	
	
	public function actiontestEsoter(){
		/**/    echo('<pre>');
            	print_r($GLOBALS);
           	 echo('</pre>'); 
         
	}
}


?>
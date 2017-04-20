<?php
/**
 * Description of AdminController
 *
 * @author JulienL
 */
\Yii::import( 'ext.MailHelper' );

class ProductController extends AdminController
{
	public $layout	= '//product/menu';

	public function init(){
		parent::init();
		$action = Yii::app()->getUrlManager()->parseUrl(Yii::app()->getRequest());
		if( $action=='Product/login' || $action=='Product/index' ||  $action == 'Product/campaign' ){
			// Url de la page de login ( pour les redirections faites par les Rules ) :
			Yii::app()->user->loginUrl = array( '/Product/login' );
		}

		// Default page title :
		$this->setPageTitle( 'Product Administration' );
	}
	//*****************************actions
	public function actions()
	{
		return array(
			'coco'=>array(
				'class'=>'CocoAction',
			),
		);
	}
	// ************************** RULES / FILTER ************************** //
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
			array('allow'),
		);
	}

	// ************************** ACTION ************************** //
	public function actionIndex(){

		/* added by Mounir 03/02/2016 */
		$user = \Business\User::load( Yii::app()->user->getId() );
		!$user  ? $this->redirect('login') : $this->render( '//product/index' );
	}
	
	
	/*************************** CSS Management (by: Youssef HARRATI) 13/01/2016 ********************************/
	
	public function actionEditCss(){
		$idCamp = Yii::app()->request->getParam( 'idcamp' );
		$id = Yii::app()->request->getParam( 'idProd' );
		
		//load product
		if( !($prod = \Business\Product::load( $id )) )
			return false;
		
		//load campaign
		if( !($camp = \Business\Campaign::load( $idCamp )) )
			return false;
		
		//views directory
		$porteur	= \Yii::app()->params['porteur'];
		$viewDir 	= SERVER_ROOT.$this->portViewDir.$porteur;
		$path 		= $this->portViewDir.$porteur;
		
		if( Yii::app()->request->getParam( 'content' ) !== NULL ){
			$cssFile = $viewDir.'/'.$camp->ref.'/'.$prod->ref.'/css/product.css';
			$date = date("Y-m-d-H");
			$bakFile = $viewDir.'/'.$camp->ref.'/'.$prod->ref.'/css/product_'.$date.'.css.bak';
			copy($cssFile, $bakFile);
			$f = fopen($cssFile, "w+");
			fwrite($f, $_POST['content']);
			fclose($f);
			Yii::app()->user->setFlash( "success", Yii::t( 'common', 'updateOK' ) );
		}
		
		// read file
		$cssFile = $viewDir.'/'.$camp->ref.'/'.$prod->ref.'/css/product.css';
		$path = $path.'/'.$camp->ref.'/'.$prod->ref.'/css/product.css';
		if(!is_file($cssFile)){
			$f = fopen($cssFile, "w+");
			fwrite($f, " ");
			fclose($f);
			chmod($cssFile, 0777);
		}
		$handle = fopen($cssFile, "r+");
		$fileContent = fread($handle, filesize($cssFile));
		fclose($handle);
		
		$this->renderPartial( '//product/EditCss', array( 'prod'=>$prod, 'camp'=>$camp, 'cssFile' => $path, 'fileContent' => $fileContent, 'baseDir' => \Yii::app()->baseUrl ) );
	}
	
	public function actionEditCssPort(){
		$porteur	= \Yii::app()->params['porteur'];
		$viewDir 	= SERVER_ROOT.$this->portViewDir.$porteur;
		$path 		= $this->portViewDir.$porteur;
		
		if( Yii::app()->request->getParam( 'content' ) !== NULL ){
			$cssFile = $viewDir.'/css/porteur.css';
			$date = date("Y-m-d-H");
			$bakFile = $viewDir.'/css/porteur_'.$date.'.css.bak';
			copy($cssFile, $bakFile);
			$f = fopen($cssFile, "w+");
			fwrite($f, $_POST['content']);
			fclose($f);
			Yii::app()->user->setFlash( "success", Yii::t( 'common', 'updateOK' ) );
			// Yii::app()->user->setFlash( "success", Yii::t( 'siteInst', 'txtEmailEmpty' ) );
		}
		
		// read file
		$cssFile = $viewDir.'/css/porteur.css';
		$path = $path.'/css/porteur.css';
		if(!is_file($cssFile)){
			$f = fopen($cssFile, "w+");
			fwrite($f, " ");
			fclose($f);
			chmod($cssFile, 0777);
		}
		$handle = fopen($cssFile, "r+");
		$fileContent = fread($handle, filesize($cssFile));
		fclose($handle);
		
		$this->renderPartial( '//product/EditCssPort', array( 'cssFile' => $path, 'fileContent' => $fileContent, 'baseDir' => \Yii::app()->baseUrl ) );
	}
	
	public function actionViewEditionAce(){
		if( !($Reflation = \Business\SubCampaignReflation::load( Yii::app()->request->getParam( 'id' ) )) )
			return false;

		if( !($Type = Yii::app()->request->getParam( 'type' ) ) )
			return false;
		
		if( $Type == 'view' )
			$viewFile = $Reflation->getPathView(true);
		else
			$viewFile = $Reflation->getPathTemplateProd(true);
		
		if( Yii::app()->request->getParam( 'content' ) !== NULL ){
			$srcFile = $viewFile;
			$date = date("Y-m-d-H");
			$bakFile = $viewFile.'_'.$date.'.bak';
			copy($srcFile, $bakFile);
			$f = fopen($srcFile, "w+");
			fwrite($f, Yii::app()->request->getParam( 'content' ));
			fclose($f);
			
			Yii::app()->user->setFlash( "success", Yii::t( 'common', 'updateOK' ) );
			
			if( !($Sub = \Business\SubCampaign::load( $Reflation->idSubCampaign )) )
				return false;
			
			$Ref				= new \Business\SubCampaignReflation( 'search' );
			$Ref->idSubCampaign	= $Sub->id;

			$this->renderPartial( '//product/subCampaignReflation', array( 'Sub' => $Sub, 'Ref' => $Ref ) );
			die;
		}
		
		if(!is_file($viewFile)){
			$f = fopen($viewFile, "w+");
			fwrite($f, " ");
			fclose($f);
			chmod($viewFile, 0777);
		}
		
		$handle = fopen($viewFile, "r+");
		$Data = fread($handle, filesize($viewFile));
		fclose($handle);


		$this->renderPartial( '//product/viewEdition', array( 'Ref' => $Reflation, 'viewFile' => $viewFile, 'Data' => $Data,'Type' => $Type, 'baseDir' => \Yii::app()->baseUrl ) );
	}
	
	public function actionViewEdition_old(){
		\Yii::import( 'ext.CKEditorHelper' );
		
		if( !($Reflation = \Business\SubCampaignReflation::load( Yii::app()->request->getParam( 'id' ) )) )
			return false;

		if( !($Type = Yii::app()->request->getParam( 'type' ) ) )
			return false;

		if( $Type == 'view' )
			$Data = CKEditorHelper::getConfigForContentManager( $Reflation->getPathView(true) );
		else
			$Data = CKEditorHelper::getConfigForContentManager( $Reflation->getPathTemplateProd(true) );

		$this->renderPartial( '//product/viewEdition', array( 'Ref' => $Reflation, 'Data' => $Data,'Type' => $Type ) );
	}
	
	
	/*************************** END CSS Management ********************************/


	public function actionCampaign(){
		//$this->includeJS( '../../js/ckeditor/ckeditor.js' );
		$this->includeJS( 'campaign.js' );

		//Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/businessCore/views/css/reset.css');
		//Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/businessCore/views/css/style.css');


		// Generation CDC ====== MOUJJAB ABDELILAH

		/****** Recuperer Droit Utilisateur (Utilisateur Marketing) */

		$usr_mark = "ko";
		$page = "/Product/campaignShow";
		$user = \Business\User::load( Yii::app()->user->getId() );

		/* added by Mounir 03/02/2016 */
		!$user  ? $this->redirect('login') : '';

		if( $user->isRole(12) ){
			$usr_mark = "ok";
			$page = "/Product/campaignShowMark";
		}
		if( $user->isRole(13) ){
			$usr_mark = "ok";
			$page = "/Product/campaignShowQualt";
		}
		// Fin Generation CDC


		/********************MY SECOND CODE ************************/

		$Camp= new \Business\Campaign('search');

		if( Yii::app()->request->getParam('delete') )
		{
			// Log l'action courante :
			$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );

			$toDelete	= \Business\Campaign::load( Yii::app()->request->getParam('id') );
			if( $toDelete->delete() )
				Yii::app()->user->setFlash( "success", Yii::t( 'product', 'deleteOK' ) );
			else
				Yii::app()->user->setFlash( "error", Yii::t( 'product', 'updateNOK' ) );
		}

		//  Valider la création du CDC

		if( Yii::app()->request->getParam('valide') )
		{
			// Log l'action courante :
			$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );
			$toValide = \Business\Campaign::load( Yii::app()->request->getParam('idCamp') );
			if( $toValide !== NULL )
			{
				$toValide->projectStatus = \Business\Campaign::PROJECT_CREATE;
				$toValide->date_creation_cdc = date( Yii::app()->params['dbDateTime'] );

				if( $toValide->save() )
				{
					$suiviPlanification  = $toValide->SuiviPlanification($toValide->id);
					$porteur    = Yii::app()->session['porteur'];
					$msg  = '<div style=" font-size:15px;"><u>'.$porteur.'('.$toValide->num.')</u> : le service <b>Marketing</b> a effectué la livraison du CDC de la campagne(<b>'.$toValide->num.'</b>). </div>';
					$msg .= $suiviPlanification;
					$expediteur = "Suivi Planification(".$porteur.")";
					$sendAlert  = \MailHelper::sendMail( $toValide->adminMails[$porteur], $porteur, 'Maj Suivi de Planification', $msg );

					Yii::app()->user->setFlash( "success", Yii::t( 'common', 'updateOK' ) );
				}else{
					Yii::app()->user->setFlash( "error", Yii::t( 'product', 'updateNOK' ) );
				}
			}
		}

		//  Valider le contrôle du CDC

		if( Yii::app()->request->getParam('controle') )
		{
			// Log l'action courante :
			$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );
			$toValide = \Business\Campaign::load( Yii::app()->request->getParam('idCamp') );
			if( $toValide !== NULL )
			{
				$toValide->projectStatus = \Business\Campaign::PROJECT_CONTROL;
				$toValide->date_control_cdc = date( Yii::app()->params['dbDateTime'] );

				if( $toValide->save() )
				{
					$suiviPlanification  = $toValide->SuiviPlanification($toValide->id);
					$porteur    = Yii::app()->session['porteur'];
					$msg  = '<div style=" font-size:15px;"><u>'.$porteur.'('.$toValide->num.')</u> : le service <b>qualité</b> a effectué le contrôle CDC de la campagne(<b>'.$toValide->num.'</b>). </div>';
					$msg .= $suiviPlanification;
					$expediteur = "Suivi Planification(".$porteur.")";
					$sendAlert  = \MailHelper::sendMail( $toValide->adminMails[$porteur], $porteur, 'Maj Suivi de Planification', $msg );

					Yii::app()->user->setFlash( "success", Yii::t( 'common', 'updateOK' ) );
				}else{
					Yii::app()->user->setFlash( "error", Yii::t( 'product', 'updateNOK' ) );
				}
			}
		}

		//  Valider le contrôle du Projet

		if( Yii::app()->request->getParam('controle_project') )
		{
			// Log l'action courante :
			$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );
			$toValide = \Business\Campaign::load( Yii::app()->request->getParam('idCamp') );
			if( $toValide !== NULL )
			{
				$toValide->projectStatus = \Business\Campaign::PROJECT_CONTROL_FINAL;
				$toValide->date_control_project = date( Yii::app()->params['dbDateTime'] );

				if( $toValide->save() )
				{
					$suiviPlanification  = $toValide->SuiviPlanification($toValide->id);
					$porteur    = Yii::app()->session['porteur'];
					$msg  = '<div style=" font-size:15px;"><u>'.$porteur.'('.$toValide->num.')</u> : le service <b>qualité</b> a effectué le contrôle de la campagne(<b>'.$toValide->num.'</b>). </div>';
					$msg .= $suiviPlanification;
					$expediteur = "Suivi Planification(".$porteur.")";
					$sendAlert  = \MailHelper::sendMail( $toValide->adminMails[$porteur], $porteur, 'Maj Suivi de Planification', $msg );

					Yii::app()->user->setFlash( "success", Yii::t( 'common', 'updateOK' ) );
				}else{
					Yii::app()->user->setFlash( "error", Yii::t( 'product', 'updateNOK' ) );
				}
			}
		}

		//  Valider le dev du Projet

		if( Yii::app()->request->getParam('developper') )
		{
			// Log l'action courante :
			$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );
			$toValide = \Business\Campaign::load( Yii::app()->request->getParam('idCamp') );
			if( $toValide !== NULL )
			{
				$toValide->projectStatus = \Business\Campaign::PROJECT_DEVELOP;
				$toValide->date_dev_it   = date( Yii::app()->params['dbDateTime'] );

				if( $toValide->save() )
				{
					$suiviPlanification  = $toValide->SuiviPlanification($toValide->id);
					$porteur    = Yii::app()->session['porteur'];
					$msg  = '<div style=" font-size:15px;"><u>'.$porteur.'('.$toValide->num.')</u> : le service <b>IT</b> a effectué la création du campagne (<b>'.$toValide->num.'</b>).</div>';
					$msg .= $suiviPlanification;
					$expediteur = "Suivi Planification(".$porteur.")";
					$sendAlert  = \MailHelper::sendMail( $toValide->adminMails[$porteur], $porteur, 'Maj Suivi de Planification', $msg );

					Yii::app()->user->setFlash( "success", Yii::t( 'common', 'updateOK' ) );
				}else{
					Yii::app()->user->setFlash( "error", Yii::t( 'product', 'updateNOK' ) );
				}
			}
		}

		//  Valider le Projet par Marcketing

		if( Yii::app()->request->getParam('valide_project') )
		{
			// Log l'action courante :
			$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );
			$toValide = \Business\Campaign::load( Yii::app()->request->getParam('idCamp') );
			if( $toValide !== NULL )
			{
				$toValide->projectStatus = \Business\Campaign::PROJECT_VALID_FINAL;
				$toValide->date_valid_project   = date( Yii::app()->params['dbDateTime'] );

				if( $toValide->save() )
				{
					$suiviPlanification  = $toValide->SuiviPlanification($toValide->id);
					$porteur    = Yii::app()->session['porteur'];
					$msg  = '<div style=" font-size:15px;"><u>'.$porteur.'('.$toValide->num.')</u> : le service <b>Marcketing</b> a effectué la validation du campagne (<b>'.$toValide->num.'</b>).</div>';
					$msg .= $suiviPlanification;
					$expediteur = "Suivi Planification(".$porteur.")";
					$sendAlert  = \MailHelper::sendMail( $toValide->adminMails[$porteur], $porteur, 'Maj Suivi de Planification', $msg );

					Yii::app()->user->setFlash( "success", Yii::t( 'common', 'updateOK' ) );
				}else{
					Yii::app()->user->setFlash( "error", Yii::t( 'product', 'updateNOK' ) );
				}
			}
		}
		// Filtre recherche :
		if( Yii::app()->request->getParam( 'Business\Campaign' ) !== NULL )
			$Camp->attributes = Yii::app()->request->getParam( 'Business\Campaign' );

		$this->render( '//product/campaign', array( 'Camp' => $Camp, 'page' => $page, 'user_mark' => $usr_mark) );
		Yii::app()->end();
	}

	/*********************************** EXPORT Excel SUIVI DE PLANIFICATION ***********************************************************************************/

	//********************  Action Export Excel //
	public function actionExcel()
	{
		$porteur = Yii::app()->session['porteur'];
		$Camp= new \Business\Campaign('search');

		$suivi_plan = $Camp->SuiviPlanification();
		//echo $suivi_plan;exit;

		if(isset($_POST['tblexcel'])){

			$tblexcel = $suivi_plan;
			$name_file = iconv('UTF-8', 'greek//TRANSLIT//IGNORE', "Suivi Planification Projets(".$porteur.").xls");
			Yii::app()->request->sendFile($name_file,$tblexcel);
		}
		else
			Yii::app()->user->setFlash( "error", Yii::t( 'common', 'Erreur lors de la récupération du tableau à exporter' ) );
	}
	public function actionExcel_v2()
	{
		$porteur = Yii::app()->session['porteur'];
		$Camp= new \Business\Campaign('search');

		$suivi_plan_site = $Camp->SuiviPlanification_v2();

		$CampaignAdaptSuivi  = new \Business\CampaignAdapt;
		$suivi_plan_multi  = $CampaignAdaptSuivi->SuiviPlanification_v2();

		$suivi_plan = array_merge($suivi_plan_site,$suivi_plan_multi);
		uasort($suivi_plan, function($line1,$line2){
			$a = $line1[0];
			$b = $line2[0];
			if ($a == $b) {
				return 0;
			}
			return ($a < $b) ? -1 : 1;
		});
		//echo "<pre>"; print_r($suivi_plan); echo "</pre>"; die("");

		if(isset($_POST['tblexcel']) OR 1){

			/*$tblexcel = $suivi_plan;
			$name_file = iconv('UTF-8', 'greek//TRANSLIT//IGNORE', "Suivi Planification(".$porteur.").xls");
			Yii::app()->request->sendFile($name_file,$tblexcel);*/
			 \Yii::import('ext.PHPExcel');
			 Yii::import('application.vendors.PHPExcel',true);

			   //$phpExcel = XPHPExcel::createPHPExcel();
			 $objPHPExcel = new PHPExcel();
			 $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B4', $porteur);
			 $objPHPExcel->getActiveSheet()->getStyle("B4")->getFont()->setBold(true)
			 					->setSize(24)
                                ->getColor()
								->setRGB('6F6F6F');
			$objPHPExcel->getActiveSheet()->getStyle("B4")->applyFromArray(array("font" => array( "bold" => true)));
			$objPHPExcel->getActiveSheet()->getStyle("B8:K8")->applyFromArray(array("font" => array( "bold" => true)));
			$objPHPExcel->getActiveSheet()->getStyle("B8:K8")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

			$objPHPExcel->getActiveSheet()
			->getStyle( $objPHPExcel->getActiveSheet()->calculateWorksheetDimension() )
			->getAlignment()
			->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('B8', 'Pays')
			->setCellValue('C8', 'N°Fid')
			->setCellValue('D8', 'Label')
			->setCellValue('E8', 'Date Shoot')
			->setCellValue('F8', 'Date réception CDC')
			->setCellValue('G8', 'Validation du CDC')
			->setCellValue('H8', 'Lancement IT')
			->setCellValue('I8', 'Livraison Qualité')
			->setCellValue('J8', 'Livraison Marketing')
			->setCellValue('K8', 'Commentaire');
			foreach(range('B','K') as $columnID)
			{
				$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
			}

			$objPHPExcel->getActiveSheet()->setTitle($porteur);
			$objPHPExcel->setActiveSheetIndex(0);

			$sheet = $suivi_plan;

			$rowInitID = 9;
			$rowID = $rowInitID;
			$track  = '___init___';
			$merge = array();
			foreach($sheet as $rowArray) {
			   $columnID = 'B';
			   //if ($track == '___init___') {
				   $track  = $rowArray[0];
			   //}
			   if ($track  === $rowArray[0]) {
				   //$rowID == 69 ? die('here') : '';
				   if (!isset($merge[$rowArray[0]])) {
				   		$merge[$rowArray[0]] = array();
				   }
				   array_push($merge[$rowArray[0]], $rowID);
			   }
			   foreach($rowArray as $columnValue) {
					 $track  = $rowArray[0];

				  $tab_value = explode("|__#__|",$columnValue);
				  $objPHPExcel->getActiveSheet()->setCellValueExplicit($columnID.$rowID, $tab_value[0], PHPExcel_Cell_DataType::TYPE_STRING);
				  $objPHPExcel->getActiveSheet()->getStyle($columnID.$rowID)
							  ->getAlignment()
							  ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				 $objPHPExcel->getActiveSheet()->getStyle($columnID.$rowID)
							  ->getAlignment()
							  ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				  $objPHPExcel->getActiveSheet()->getRowDimension($rowID)->setRowHeight(30);
				//   $objPHPExcel->setActiveSheetIndex()->mergeCells('B9:B'.$rowID);
				  $objPHPExcel->getActiveSheet()->getStyle('B9:B'.$rowID)->getFont()->setBold(true)
			 					->setSize(24)
                                ->getColor()
								->setRGB('&H0');


				  if(isset($tab_value[1]) && $tab_value[1]!='')
				  {
				    $objPHPExcel->getActiveSheet()
					->getStyle($columnID.$rowID)
					->getFill()
					->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
					->getStartColor()
					->setARGB($tab_value[1]);
				  }

				  $objPHPExcel->getActiveSheet()->getStyle($columnID.$rowID)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

				  $columnID++;
			   }
			   $rowID++;
			}
			//echo '<pre>'; print_r($merge); die;
			foreach ($merge as $elts) {
				$eltInit = $elts[0];
				$eltEnd = end($elts);
				$objPHPExcel->getActiveSheet()->mergeCells("B{$eltInit}:B{$eltEnd}");
			}

			$objPHPExcel->createSheet();
			$objPHPExcel->setActiveSheetIndex(1)->setCellValue('A8', 'Codification');
			$objPHPExcel->getActiveSheet()->getStyle("A4")->getFont()->setBold(true)
			 					->setSize(24)
                                ->getColor()
								->setRGB('6F6F6F');
			$objPHPExcel->getActiveSheet()
						->getStyle("A8")
						->getFill()
						->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
						->getStartColor()
						->setARGB('ccffff');

			$objPHPExcel->getActiveSheet()
			->getStyle("A8")
			->getAlignment()
			->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn('A')->setAutoSize(false);
			$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn('A')->setWidth('30');
			$objPHPExcel->getActiveSheet()->getStyle("A8")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

			$objPHPExcel->getActiveSheet()->getStyle('B10')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('&H50d092');
			$objPHPExcel->getActiveSheet()->getStyle('B11')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('&Hff');
			$objPHPExcel->getActiveSheet()->getStyle('B12')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('&Hd9d9d9');
			$objPHPExcel->getActiveSheet()->getStyle('B13')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('&Hffff');

			$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('C10', 'Réception dans les délais ')
			->setCellValue('C11', 'Réception en retard')
			->setCellValue('C12', 'Réception prévue')
			->setCellValue('C13', 'Réception avancée');

			$objPHPExcel->getActiveSheet()->setTitle('Template');
			$objPHPExcel->setActiveSheetIndex(0);

			ob_end_clean();
			ob_start();

			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="Suivi Planification Projets('.date('d_m_Y').').xls"');
			header('Cache-Control: max-age=0');
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$objWriter->save('php://output');



		}
		else
			Yii::app()->user->setFlash( "error", Yii::t( 'common', 'Erreur lors de la rÃ©cupÃ©ration du tableau Ã  exporter' ) );
	}

	/*********************************** FIN Action EXPORT Excel SUIVI DE PLANIFICATION ***********************************************************************************/

	public function actionCampaignShow(){
		// Update
		if( Yii::app()->request->getParam( 'id' ) !== NULL )
		{
			if( !($Camp = \Business\Campaign::load( Yii::app()->request->getParam( 'id' ) )) )
				return false;

			echo $this->portDir( true ).'/'.$Camp->ref;
		}
		// Create
		else
			$Camp = new \Business\Campaign();

		// POST :
		if( Yii::app()->request->getParam( 'Business\Campaign' ) !== NULL )
		{
			// Log l'action courante :
			$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );

			$Camp->attributes = Yii::app()->request->getParam( 'Business\Campaign' );

			if( empty($Camp->ref) )
				$Camp->ref = $Camp->label;
				
			// date prévisionnelle de lancement de dev de L'IT

			$dateProject  = Yii::app()->request->getParam( 'Business\Campaign' )['date_dev_it_prev'];
			$heureProject = Yii::app()->request->getParam( 'Business\Campaign' )['time_dev_it_prev'];

			$date_dev_it_prev = $dateProject . " " . $heureProject;

			if($Camp->date_dev_it_prev != $date_dev_it_prev){
					$suiviPlanification = true;
			}

			$Camp->date_dev_it_prev = $date_dev_it_prev;
			$Camp->commentaire_palanification = Yii::app()->request->getParam( 'Business\Campaign' )['commentaire_palanification'];
			
			if( $Camp->save() )
			{
				if(isset($suiviPlanification)){
					$suiviPlanification  = $Camp->SuiviPlanification($Camp->id);
					$porteur    = Yii::app()->session['porteur'];
					$msg  = '<div style="font-size: 15px;"><b><u>'.$porteur.'('.$Camp->num.')</u></b> : Le service <b>IT</b> a mis à jour la date de livraison prévisionnelle de projet (<b>'.$Camp->num.'<b>).</div>';
					$msg .= $suiviPlanification;
					$expediteur = "Suivi Planification(".$porteur.")";
					$sendAlert  = \MailHelper::sendMail( $Camp->adminMails[$porteur], $porteur, 'Maj Suivi de Planification', $msg );
				}
				Yii::app()->user->setFlash( "success", Yii::t( 'common', 'updateOK' ) );
			}else{
				Yii::app()->user->setFlash( "error", 'Reference existe deja !' );

				//Yii::app()->user->setFlash( "error", Yii::t( 'common', 'updateNOK' ) );

			}
		}

		$this->renderPartial( '//product/campaignShow', array( 'Camp' => $Camp ) );
	}

	public function actionGoogleAnalytics(){

		// Update
		$GG	= new \Business\GoogleAnalytics('search');

		$post= $GG->find('id=:id', array(':id'=>1));

		if($post)
			$id_table = 1;
		else
			$id_table = Yii::app()->request->getParam( 'id' );

		if( $id_table !== NULL )
		{
			if( !($GoogleAnalytics = \Business\GoogleAnalytics::load( $id_table )) )
				return false;
		}
		// Create
		else
			$GoogleAnalytics = new \Business\GoogleAnalytics();

		// POST :
		if( Yii::app()->request->getParam( 'Business\GoogleAnalytics' ) !== NULL )
		{
			// Log l'action courante :
			$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );

			$GoogleAnalytics->attributes = Yii::app()->request->getParam( 'Business\GoogleAnalytics' );

			if( empty($GoogleAnalytics->code) )
				$GoogleAnalytics->code = $GoogleAnalytics->label;

			if( $GoogleAnalytics->save() )
				Yii::app()->user->setFlash( "success", Yii::t( 'common', 'updateOK' ) );
			else
				Yii::app()->user->setFlash( "error", Yii::t( 'common', 'updateNOK' ) );
		}

		$this->render( '//product/googleAnalytics', array( 'GoogleAnalytics' => $GoogleAnalytics ) );

	}


	public function actionSubCampaign(){

		if(Yii::App()->User->checkAccess("QUALITE_SERVICE"))
			$role_user = 'QUALITE_SERVICE';
		else
			$role_user = 'AUTRE';

		if( !($Camp = \Business\Campaign::load( Yii::app()->request->getParam( 'id' ) )) )
			return false;

		$Search				= new \Business\SubCampaign( 'search' );
		$Search->idCampaign	= $Camp->id;

		// Filtre recherche :
		if( Yii::app()->request->getParam( 'Business\SubCampaign' ) !== NULL )
			$Search->attributes = Yii::app()->request->getParam( 'Business\SubCampaign' );

		$this->renderPartial( '//product/subCampaign', array( 'Camp' => $Camp, 'Search' => $Search, 'RoleUser' => $role_user ) );
	}
	/*  Code ajouté par Samir: 23/09/2014 */

	public function actionSubCampaign_V1(){
		$UrlMapping =  array(
			//Name			=> directory name on server
			'fr_rucker/'		=> 'rucker/',
			'fr_laetizia/'   => 'laetizia/',
			'fr_rmay/'		=> 'fr_rmay/',
			'fr_rinalda/'	=> 'rinalda/',

			'br_rucker/'		=> 'br_rucker/',
			'br_laetizia/'	=> 'pt_laetizia/',
			'br_rmay/'		=> 'pt_rmay/',

			'es_laetizia/'	=> 'es_laetizia/',
			'es_rmay/'		=> 'es_rmay/',

			'en_aasha/'		=> 'en_aasha/',
			'en_alisha/'		=> 'en_alisha/',

			'de_rmay/'		=> 'de_rmay/',
			'de_theodor/'	=> 'de_theodor/'
		);
		$Camp_V1 = \Business\Product_V1::load( Yii::app()->request->getParam( 'id' ) );
		$link =  $this->portDir( true );
		$link = substr( $link, 43, strlen($link) );
		$link=$UrlMapping[$link];
		$link='http://www.chancepure.com/'.$link.'index.php?c=';
		$escaped_link = htmlspecialchars($link, ENT_QUOTES, 'UTF-8');

		$this->renderPartial( '//product/subCampaign_V1', array( 'Camp_V1' =>$Camp_V1,'escaped_link'=>$escaped_link ) );

	}

	/*************************************/



	public function actionSubCampaignShow(){
		if( ($idCamp = Yii::app()->request->getParam( 'idCamp' )) === NULL )
			return false;

		if( Yii::app()->request->getParam( 'id' ) !== NULL )
		{
			if( !($Sub = \Business\SubCampaign::load( Yii::app()->request->getParam( 'id' ) )) )
				return false;
		}
		else
			$Sub = new \Business\SubCampaign();

		// POST :
		if( Yii::app()->request->getParam( 'delete' ) !== NULL )
		{
			// Log l'action courante :
			$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );

			echo $Sub->Product->delete() && $Sub->delete() ? 'true' : 'false';
			\Yii::app()->end();
		}
		else if( Yii::app()->request->getParam( 'Business\SubCampaign' ) !== NULL )
		{
			// Log l'action courante :
			$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );

			$Sub->attributes	= Yii::app()->request->getParam( 'Business\SubCampaign' );

			if( $Sub->save() )
				Yii::app()->user->setFlash( "success", Yii::t( 'common', 'updateOK' ) );
			else
				Yii::app()->user->setFlash( "error", Yii::t( 'common', 'updateNOK' ) );
		}

		$this->renderPartial( '//product/subCampaignShow', array( 'Sub' => $Sub ) );
	}


//Badr Yassine -- passer a la lecture du fichier
	public function actionPricingGrid(){


		if( !($Sub = \Business\SubCampaign::load( Yii::app()->request->getParam( 'id' ) )) )
			return false;

		//if (isset($_FILES['myFileInput']) && ($_FILES['myFileInput']['name'] != null) ){

		//Badr Yassine -- Lecture fichier csv
		//	$this->actionGetPricingGridExcel($Sub);

		// }else{

		// Mise a jour de la grille de prix :
		if( Yii::app()->request->getParam( 'GP' ) !== NULL )
		{
			$GP		= Yii::app()->request->getParam( 'GP' );
			$idSite	= Yii::app()->request->getParam( 'idSite' );
			$PTH	= Yii::app()->request->getParam( 'PTH' );





			$error	= false;

			foreach( $GP as $bs => $tabBs )
			{
				foreach( $tabBs as $ps => $tabPs )
				{
					foreach( $tabPs as $pg => $price )
					{
						$Pricing = \Business\PricingGrid::get( $Sub->id, $bs, $ps, $pg, $idSite );
						//echo $ps;
						$prixTh = $PTH[$bs][1][$pg];
						if( $price > 0 )
						{
							if( $Pricing == NULL )
							{
								$Pricing					= new \Business\PricingGrid();
								$Pricing->refBatchSelling	= $bs;
								$Pricing->priceStep			= $ps;
								$Pricing->refPricingGrid	= $pg;
								$Pricing->idSubCampaign		= $Sub->id;
								$Pricing->idSite			= $idSite;
								$Pricing->prixTheorique		= $prixTh;
							}

							$Pricing->priceATI			= $price;
							$Pricing->priceVAT			= 0;
							$Pricing->prixTheorique		= $prixTh;



							if( !$Pricing->save() )
							{
								$error = true;
								break;
							}
						}
						else if( is_object($Pricing) )
						{
							if( !$Pricing->delete() )
							{
								$error = true;
								break;
							}
						}
					}
				}
			}

			if( !$error )
				Yii::app()->user->setFlash( "success", Yii::t( 'common', 'updateOK' ) );
			else
				Yii::app()->user->setFlash( "error", Yii::t( 'common', 'updateNOK' ) );
		}

		$Site	= new \Business\Site('search');
		$PariteInvoice	= new \Business\PariteInvoice('search');

		$tab	= array();
		foreach( $Site->findAll() as $S )
		{
			$parite = $PariteInvoice->loadByDevise($S->codeDevise);
			$tab[ $S->country ] = array(
				'id'	=> $S->id,
				'ajax'	=> $this->createAbsoluteUrl( '//product/getPricingGrid', array( 'idSite' => $S->id, 'idSub' => $Sub->id, 'parite' => $parite->id_parite ) )
			);
		}

		$this->renderPartial( '//product/pricingGrid', array( 'Sub' => $Sub, 'tab' => $tab ) );

		//}

	}

	/*
	public function actionPGThird(){
		$this->renderPartial( '//product/getPricingGrid_v3' );
	}
	*/
	public function actionGetPricingGridExcel($Sub){
		if( ($idSite = Yii::app()->request->getParam( 'idSite' )) === NULL )
			return false;

		//Lecture du fichier
		move_uploaded_file($_FILES['myFileInput']['tmp_name'],$this->portDir( true ).'/'.$_FILES['myFileInput']['name']);

		$nbRelance = $Sub->countDistinctOffsetPriceStep();

		if( $nbRelance <= 0 )
			Yii::app()->user->setFlash( "warning", Yii::t( 'product', 'noReflation' ) );

		$Data=array();

		//Commenté par Badr
		//$this->renderPartial( '//product/getPricingGrid', array( 'Sub' => $Sub, 'idSite' => $idSite, 'data' => $data, 'nbRelance' => $nbRelance, 'nbGP' => MAX_GP, 'nbBS' => MAX_BS ) );
		$this->render( '//product/getPricingGrid_v2', array( 'Sub' => $Sub, 'idSite' => $idSite, 'data' => $Data, 'nbRelance' => $nbRelance, 'nbGP' => MAX_GP, 'nbBS' => MAX_BS ) );

	}


	public function actionGetPricingGrid(){

		if( !($Sub = \Business\SubCampaign::load( Yii::app()->request->getParam( 'idSub' ) )) )
			return false;

		if( ($idSite = Yii::app()->request->getParam( 'idSite' )) === NULL )
			return false;

		if( !($parite = \Business\PariteInvoice::load( Yii::app()->request->getParam( 'parite' ))) )
			return false;

		$nbRelance = $Sub->countDistinctOffsetPriceStep();

		if( $nbRelance <= 0 )
			Yii::app()->user->setFlash( "warning", Yii::t( 'product', 'noReflation' ) );

		$data = array();
		foreach( $Sub->PricingGrid as $GP )
		{
			$data[ $GP->priceStep ][] = array(
				'bs' => $GP->refBatchSelling,
				'gp' => $GP->refPricingGrid,
				'price' => $GP->priceATI
			);
		}

		//Commenté par Badr
		//$this->renderPartial( '//product/getPricingGrid', array( 'Sub' => $Sub, 'idSite' => $idSite, 'data' => $data, 'nbRelance' => $nbRelance, 'nbGP' => MAX_GP, 'nbBS' => MAX_BS ) );
		$this->renderPartial( '//product/getPricingGrid_v2', array( 'Sub' => $Sub, 'idSite' => $idSite, 'parite' => $parite->parite, 'data' => $data, 'nbRelance' => $nbRelance, 'nbGP' => MAX_GP, 'nbBS' => MAX_BS ) );

	}

	public function actionSubCampaignReflation(){
		if( !($Sub = \Business\SubCampaign::load( Yii::app()->request->getParam( 'id' ) )) )
			return false;

		$Ref				= new \Business\SubCampaignReflation( 'search' );
		$Ref->idSubCampaign	= $Sub->id;

		// Filtre recherche :
		if( Yii::app()->request->getParam( 'Business\SubCampaignReflation' ) !== NULL )
			$Ref->attributes = Yii::app()->request->getParam( 'Business\SubCampaignReflation' );

		$this->renderPartial( '//product/subCampaignReflation', array( 'Sub' => $Sub, 'Ref' => $Ref ) );
	}

	public function actionSubCampaignReflationShow(){
		if( ($idSubCamp = Yii::app()->request->getParam( 'idSubCamp' )) === NULL )
			return false;

		if( Yii::app()->request->getParam( 'id' ) !== NULL )
		{
			if( !($Ref = \Business\SubCampaignReflation::load( Yii::app()->request->getParam( 'id' ) )) )
				return false;
		}
		else
			$Ref = new \Business\SubCampaignReflation();

		// POST :
		if( Yii::app()->request->getParam( 'delete' ) !== NULL )
		{
			// Log l'action courante :
			$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );

			echo $Ref->delete() ? 'true' : 'false';
			\Yii::app()->end();
		}
		else if( Yii::app()->request->getParam( 'Business\SubCampaignReflation' ) !== NULL )
		{
			// Log l'action courante :
			$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );

			$Ref->attributes	  = Yii::app()->request->getParam( 'Business\SubCampaignReflation' );
			//$Ref->attributes['offsetPriceStep'] = Yii::app()->request->getParam( 'Business\SubCampaignReflation' )['offsetPriceStep'];
			
			
			if( $Ref->save() )
				Yii::app()->user->setFlash( "success", Yii::t( 'common', 'updateOK' ) );
			else
				Yii::app()->user->setFlash( "error", Yii::t( 'common', 'updateNOK' ) );
		}

		$this->renderPartial( '//product/subCampaignReflationShow', array( 'Ref' => $Ref, 'idSubCamp' => $idSubCamp ) );
	}

	public function actionProductShow(){

		if(Yii::App()->User->checkAccess("QUALITE_SERVICE"))
			$role_user = 'QUALITE_SERVICE';
		else
			$role_user = 'AUTRE';

		// Update
		if( Yii::app()->request->getParam( 'id' ) > 0 ){
			if( !($Sub = \Business\SubCampaign::load( Yii::app()->request->getParam( 'id' ) )) )
				return false;
			$Prod	= $Sub->Product;
		}
		// Create
		elseif( Yii::app()->request->getParam( 'idCamp' ) !== NULL ){
			$Sub				= new \Business\SubCampaign();
			$Sub->idCampaign	= Yii::app()->request->getParam( 'idCamp' );
			$Sub->position      = \Business\SubCampaign::GetLastPositionByCampaign($Sub->idCampaign);

			$Prod				= new \Business\Product();
			$Prod->ref   		= $Sub->Campaign->ref.'_'.$Sub->position;
		}
		else
			return false;
		$PPSet	= new \Business\PaymentProcessorSet('search');
		$lSub	= $Sub->Campaign->SubCampaign;
		// POST :
		if( Yii::app()->request->getParam( 'Business\Product' ) !== NULL )
		{
			// Log l'action courante :
			$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );

			$Sub->attributes			= Yii::app()->request->getParam( 'Business\SubCampaign' );
			$Prod->attributes			= Yii::app()->request->getParam( 'Business\Product' );
			$Prod->bdcFields			= Yii::app()->request->getParam( 'bdcFields' );

			$paramPriceModel			= Yii::app()->request->getParam( 'paramPriceModel' );
			$priceModel_Prod = ($Prod->priceModel == 'prevBasedAsile2' || $Prod->priceModel == 'prevBasedAsile3' || $Prod->priceModel == 'prevBasedAsile4' )? 'prevBased' : $Prod->priceModel;
			$Prod->paramPriceModel		= isset($paramPriceModel[$priceModel_Prod]) ? $paramPriceModel[$priceModel_Prod] : NULL;
			if( $Sub->idProduct > 0 )
			{
				if( $Sub->save() && $Prod->save() )
					Yii::app()->user->setFlash( "success", Yii::t( 'common', 'updateOK' ) );
				else
					Yii::app()->user->setFlash( "error", Yii::t( 'common', 'updateNOK' ) );
			}
			else if( $Sub->idProduct <= 0 )
			{
				if( $Prod->save() )
				{
					$Sub->idProduct		= $Prod->id;
					$Sub->attributes	= Yii::app()->request->getParam( 'Business\SubCampaign' );

					if( $Sub->save() )
						Yii::app()->user->setFlash( "success", Yii::t( 'common', 'updateOK' ) );
					else
						Yii::app()->user->setFlash( "error", Yii::t( 'common', 'updateNOK' ) );
				}
				else
					Yii::app()->user->setFlash( "error", Yii::t( 'common', 'updateNOK' ) );
			}
		}
		$this->renderPartial( '//product/productShow', array( 'Sub' => $Sub, 'Prod' => $Prod, 'lPPSet' => $PPSet->findAll(), 'lSub' => $lSub, 'RoleUser' => $role_user ) );
	}

	public function actionAjaxGetBdcFields(){
		if( !($Set = \Business\PaymentProcessorSet::load( Yii::app()->request->getParam( 'idPP' ) )) )
			return false;
		if( !($Prod = \Business\Product::load( Yii::app()->request->getParam( 'idProd' ) )) )
			$Prod = new \Business\Product();
		$availBdcFields = include( \Yii::app()->basePath.'/views/common/bdc.php' );
		$availBdcFields	= $availBdcFields['elements'];
		unset( $availBdcFields['paymentType'] );

		$this->renderPartial( '//product/ajaxBdcFields', array( 'lPP' => $Set->PaymentProcessorType, 'availBdcFields' => $availBdcFields, 'Prod' => $Prod ) );
	}

	public function actionRouterEMV(){
		if( !($Sub = \Business\SubCampaign::load( Yii::app()->request->getParam( 'id' ) )) )
			return false;

		$Router				= new \Business\RouterEMV('search');
		$Router->idProduct	= $Sub->Product->id;
		$idCamp = Yii::app()->request->getParam( 'idCamp' );

		if( Yii::app()->request->getParam( 'delete' ) !== NULL )
		{
			// Log l'action courante :
			$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );

			$Delete = \Business\RouterEMV::load( Yii::app()->request->getParam( 'idDelete' ) );
			echo $Delete->delete() ? 'true' : 'false';
			\Yii::app()->end();
		}

		// Filtre recherche :
		if( Yii::app()->request->getParam( 'Business\RouterEMV' ) !== NULL )
			$Router->attributes = Yii::app()->request->getParam( 'Business\RouterEMV' );

		$this->renderPartial( '//product/routerEMV', array( 'Sub' => $Sub, 'Router' => $Router, 'idCamp' => $idCamp ) );
	}

	public function actionRouterEMVShow(){
		
		if( ( $Sub = \Business\SubCampaign::load( Yii::app()->request->getParam( 'idSub' )) ) === NULL )
			return false;

		if( Yii::app()->request->getParam( 'id' ) !== NULL ){
			if( !($Router = \Business\RouterEMV::load( Yii::app()->request->getParam( 'id' ) )) )
				return false;
		}
		else
			$Router = new \Business\RouterEMV();

		// POST :
		if( Yii::app()->request->getParam( 'Business\RouterEMV' ) !== NULL )
		{
			// Log l'action courante :
			$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );

			$Router->attributes = Yii::app()->request->getParam( 'Business\RouterEMV' );
			$Router->idProduct	= $Sub->Product->id;

			if( $Router->save() )
				Yii::app()->user->setFlash( "success", Yii::t( 'common', 'updateOK' ) );
			else
				Yii::app()->user->setFlash( "error", Yii::t( 'common', 'updateNOK' ) );	
		}
		$this->renderPartial( '//product/routerEMVShow', array( 'Router' => $Router, 'routerType' => \Business\RouterEMV::$tabType, 'listCompteEmv' => \Yii::app()->params['compteEMV'], 'tokenHelp' => \WebForm::getTokenHelp() ) );

	}

	/***** Insertion Automatique Webform Yii [Hania EL HAINANI + Safae BENAISSA] *****/

	public function actionRouterEMVSH(){

		if( ( $Sub = \Business\SubCampaign::load( Yii::app()->request->getParam( 'idSub' )) ) === NULL )
			return false;

		if( Yii::app()->request->getParam( 'id' ) !== NULL ){
			if( !($Router = \Business\RouterEMV::load( Yii::app()->request->getParam( 'id' ) )) )
				return false;
		}
		else
			$Router = new \Business\RouterEMV('search');

		$idCamp = Yii::app()->request->getParam( 'idCamp' );


		if( Yii::app()->request->getParam( 'Business\RouterEMV' ) !== NULL ){

			\Yii::import( 'ext.PHPExcel' );
			Yii::import('application.vendors.PHPExcel',true);
			$excelfile = CUploadedFile::getInstance($Router,'file');

			$path = $excelfile->getTempName();

			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
			$objPHPExcel = $objReader->load($path);

			$objWorksheet = $objPHPExcel->getActiveSheet();
			$highestRow = $objWorksheet->getHighestRow(); // e.g. 10
			$highestColumn = $objWorksheet->getHighestColumn(); // e.g 'F'
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); // e.g '6' (=F)

			// Récupération de champs de formulaire
			$de=Yii::app()->request->getParam('emvDE');
			$dect=Yii::app()->request->getParam('emvDECT');
			$s=Yii::app()->request->getParam('emvS');
			$datect=Yii::app()->request->getParam('emvDATECT');
			$gp=Yii::app()->request->getParam('emvGP');
			$sd=Yii::app()->request->getParam('emvSD');
			$modeAsyn=Yii::app()->request->getParam('asyn');
			$produitM=Yii::app()->request->getParam('prodM');

			$porteur = \Yii::app()->params['porteur'];


			$emvref = \Business\Campaign::load( Yii::app()->request->getParam('idCamp'));
			$position				= substr($Sub->Product->ref,-1);
			$RefComp				= $emvref->ref;
			$RefProduct				= $Sub->Product->ref;

			// Création de fichier excel avec les nouveau Urls
			$tblexcel = '
								<html>
									<head>
									</head>
									<body>
									<table width="600" cellspacing="0" cellpadding="0" border="2px">
									  <tr>
										<td width="200" align="center" style="background-color:#093;"><b>TYPE</b></td>
										<td width="400" align="center" style="background-color:#093;"><b>URL</b></td>
										<td width="400" align="center" style="background-color:#80D22E;"><b>Ancien URL</b></td>
									  </tr>';
			for ($row = 2; $row <= $highestRow; ++$row){

				$type = preg_replace('/\s+/', '',$objWorksheet->getCellByColumnAndRow(0, $row)->getValue()); // e.g 'UrlPaiement'
				$url_ = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
				$url = preg_replace('/\s+/', '', $url_); // remove all whitespace
			// Traitement seulement pour les lignes non vide			
			if(!empty($type) and !empty($url))
			{
				if($modeAsyn==1)
				{
					if($position==1 || $produitM==1)
					{  switch($type)
					{
						case "UrlPaiement":				$valS=$RefComp."_2";break;
						case "UrlIntentionCheque":		$valS=$RefComp."_1";break;
						case "UrlIntentionMultibanco":	$valS=$RefComp."_1";break;
						case "UrlIntentionBoleto":		$valS=$RefComp."_1";break;
						case "UrlPaiementDm":			$valS=$RefComp."_1";break;

					}
					}
					else
					{
						switch($type)
						{
							case "UrlPaiement":				$valS=$RefComp."_4";break;
							case "UrlIntentionCheque":		$valS=$RefComp."_3";break;
							case "UrlIntentionMultibanco":	$valS=$RefComp."_3";break;
							case "UrlIntentionBoleto":		$valS=$RefComp."_3";break;
							case "UrlPaiementDm":			$valS=$RefComp."_3";break;
						}
					}
				}
				else
				{
					if($position==1 || $produitM==1)
					{
						$valS=$RefComp."_1";
					}
					else
					{
						$valS=$RefComp."_2";
					}
				}

				$urlelements = array("EMAIL_FIELD=XXXXXX",
					"CLIENTURN_FIELD=XXXXXX",
					"EMVADMIN9_FIELD=XXXXXX",
					"EMVADMIN10_FIELD=XXXXXX",
					"EMVADMIN11_FIELD=XXXXXX",
					"LASTNAME_FIELD=XXXXXX",
					"FIRSTNAME_FIELD=XXXXXX",
					"TITLE_FIELD=XXXXXX",
					"DATEOFBIRTH_FIELD=XXXXXX",
					"EMV_POS_FIELD=XXXXXX",
					"EMV_REF_FIELD=XXXXXX",
					"SITE_FIELD=XXXXXX",
					"EMVADMIN13_FIELD=XXXXXX",
					"EMVADMIN113_FIELD=XXXXXX",
					"EMVADMIN112_FIELD=XXXXXX",
					"EMVADMIN".$de."_FIELD=XXXXXX",
					"EMVADMIN".$dect."_FIELD=XXXXXX",
					"EMVADMIN".$s."_FIELD=XXXXXX",
					"EMVADMIN14_FIELD=XXXXXX",
					"EMVADMIN17_FIELD=XXXXXX",
					"EMVADMIN3_FIELD=XXXXXX",
					"EMVADMIN82_FIELD=XXXXXX"
				);

				$newurlelements = array("EMAIL_FIELD=__m__",
					"CLIENTURN_FIELD=0",
					"EMVADMIN9_FIELD=__pc__",
					"EMVADMIN10_FIELD=__cc__",
					"EMVADMIN11_FIELD=__rf__",
					"LASTNAME_FIELD=__n__",
					"FIRSTNAME_FIELD=__p__",
					"TITLE_FIELD=__x__",
					"DATEOFBIRTH_FIELD=__b__",
					"EMV_POS_FIELD=".$position."",
					"EMV_REF_FIELD=".$RefComp."",
					"SITE_FIELD=__site__",
					"EMVADMIN13_FIELD=".$RefProduct."",
					"EMVADMIN113_FIELD=".$position."",
					"EMVADMIN112_FIELD=".$RefComp."",
					"EMVADMIN".$de."_FIELD=__date__",
					"EMVADMIN".$dect."_FIELD=__date__",
					"EMVADMIN".$s."_FIELD=".$valS."",
					"EMVADMIN14_FIELD=".$position."",
					"EMVADMIN17_FIELD=".$position."",
					"EMVADMIN3_FIELD=".$RefComp."",
					"EMVADMIN82_FIELD=".$RefComp.""
				);



				/***********DATE CT*************/
				for($i=0;$i<$datect;$i++)
				{
					array_push($urlelements,'EMVADMIN'.($i+18).'_FIELD=XXXXXX');
					array_push($newurlelements,'EMVADMIN'.($i+18).'_FIELD=__EMVADMIN'.($i+18).'__');
				}
				/*******************************/

				// création des url avec XXXXX remplacé
				$newurl = str_replace($urlelements, $newurlelements, $url);

				//Save to BDD
				$Router2 = new \Business\RouterEMV('search');
				$Router2->idProduct	= $Sub->Product->id;
				$Router2->type=$type;
				$Router2->url=$newurl;
				$Router2->compteEMV=Yii::app()->request->getParam( 'Business\RouterEMV' )['compteEMV'];

				if( $Router2->save() )
					Yii::app()->user->setFlash( "success", Yii::t( 'common', 'updateOK' ) );
				else
					Yii::app()->user->setFlash( "error", Yii::t( 'common', 'updateNOK' ) );


				//********************

				$tblexcel .= '<tr>
									<td width="91" valign="top"><b>'.$type.'</b></td>
									<td width="309"><b>'.$newurl.'</b></td>
									<td width="309"><b>'.$url.'</b></td>
								  </tr>';

			}
		} // fin teste sur ligne vides
			$tblexcel .= '</table>
			<br><br>
			<table  border="1px">
				<tr>
					<td colspan="2" align="center" style="background-color:#093;"><b>Champs EMV</b></td>
			  	</tr>
				<tr>
					<td width="40" align="center" valign="top"><b> DE </b></td>
					<td width="80" align="center"><b>'.$de.'</b></td>
			  	</tr>
			  	<tr>
					<td width="40" align="center" valign="top"><b> DECT </b></td>
					<td width="80" align="center"><b>'.$dect.'</b></td>
			  	</tr>
			  	<tr>
					<td width="40" align="center" valign="top"><b> S </b></td>
					<td width="80" align="center"><b>'.$s.'</b></td>
			  	</tr>
			  	<tr>
					<td width="40" align="center" valign="top"><b> GP </b></td>
					<td width="80" align="center"><b>'.$gp.'</b></td>
			  	</tr>
			  	<tr>
					<td width="40" align="center" valign="top"><b> SD </b></td>
					<td width="80" align="center"><b>'.$sd.'</b></td>
			  	</tr>
			  </table>
									</body>
									</html>	';

			$name_file = "url_webform_".$RefProduct.".xls";
			Yii::app()->request->sendFile($name_file,$tblexcel);/**/
		}


		$this->renderPartial( '//product/routerEMVSH', array( 'Router' => $Router, 'routerType' => \Business\RouterEMV::$tabType, 'listCompteEmv' => \Yii::app()->params['compteEMV'], 'idCamp'=> $idCamp , 'tokenHelp' => \WebForm::getTokenHelp(), 'porteur' => \Yii::app()->params['porteur'] ));

	}
	/***** END -- Insertion Automatique Webform Yii [Hania EL HAINANI + Safae BENAISSA] *****/
	/************ START 12 Signes Ajouter par Othmane ********************/
	public function actionSignes(){
		if( !($Sub = \Business\SubCampaign::load( Yii::app()->request->getParam( 'id' ) )) )
			return false;

		$Signes				= new \Business\Signes('search');
		$Signes->idProduct	= $Sub->Product->id;

		if( Yii::app()->request->getParam( 'delete' ) !== NULL )
		{
			// Log l'action courante :
			$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );

			$Delete = \Business\Signes::load( Yii::app()->request->getParam( 'idDelete' ) );
			echo $Delete->delete() ? 'true' : 'false';
			\Yii::app()->end();
		}

		// Filtre recherche :
		if( Yii::app()->request->getParam( 'Business\Signes' ) !== NULL )
			$Signes->attributes = Yii::app()->request->getParam( 'Business\Signes' );

		$RecordCount = $Signes::model()->findByAttributes(array('idProduct'=>$Sub->idProduct));

		$this->renderPartial( '//product/Signes', array( 'Sub' => $Sub, 'Signes' => $Signes,'RecordCount'=>count($RecordCount) ) );
	}
	public function actionsignesShow(){
		if( ( $Sub = \Business\SubCampaign::load( Yii::app()->request->getParam( 'idSub' )) ) === NULL )
			return false;

		if( Yii::app()->request->getParam( 'id' ) !== NULL ){
			if( !($Signes = \Business\Signes::load( Yii::app()->request->getParam( 'id' ) )) )
				return false;
		}
		else
			$Signes = new \Business\Signes();

		// POST :
		if( Yii::app()->request->getParam( 'Business\Signes' ) !== NULL )
		{
			// Log l'action courante :
			$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );

			$Signes->attributes = Yii::app()->request->getParam( 'Business\Signes' );
			$Signes->idProduct	= $Sub->Product->id;

			if( $Signes->save() )
				Yii::app()->user->setFlash( "success", Yii::t( 'common', 'updateOK' ) );
			else
				Yii::app()->user->setFlash( "error", Yii::t( 'common', 'updateNOK' ) );
		}		$this->renderPartial( '//product/signesShow', array( 'Signes' => $Signes ));
	}
	/************ END 12 Signes Ajouter par Othmane ********************/
	/************ START 12 Signes Variables Ajouter par Othmane ********************/
	public function actionSignesVariables(){
		if( Yii::app()->request->getParam( 'delete' ) !== NULL )
		{
			// Log l'action courante :
			$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );

			$Delete = \Business\SignesVariables::load( Yii::app()->request->getParam( 'idDelete' ) );
			echo $Delete->delete() ? 'true' : 'false';
			\Yii::app()->end();exit;
		}

		if( !($Signe = \Business\Signes::load( Yii::app()->request->getParam( 'id' ) )) )
			return false;

		$Variables				= new \Business\SignesVariables('search');
		$Variables->id_signe	= $Signe->id;



		// Filtre recherche :
		if( Yii::app()->request->getParam( 'Business\SignesVariables' ) !== NULL )
			$Variables->attributes = Yii::app()->request->getParam( 'Business\SignesVariables' );

		$this->renderPartial( '//product/SignesVariables', array( 'Signe' => $Signe, 'Variables' => $Variables ) );
	}
	public function actionSignesVariablesShow(){
		if( ( $Signe = \Business\Signes::load( Yii::app()->request->getParam( 'idSub' )) ) === NULL )
			return false;

		if( Yii::app()->request->getParam( 'id' ) !== NULL ){
			if( !($SignesVariables = \Business\SignesVariables::load( Yii::app()->request->getParam( 'id' ) )) )
				return false;
		}
		else
			$SignesVariables = new \Business\SignesVariables();

		// POST :
		if( Yii::app()->request->getParam( 'Business\SignesVariables' ) !== NULL )
		{
			// Log l'action courante :
			$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );

			$SignesVariables->attributes = Yii::app()->request->getParam( 'Business\SignesVariables' );
			$SignesVariables->id_signe	= $Signe->id;

			if( $SignesVariables->save() )
				Yii::app()->user->setFlash( "success", Yii::t( 'common', 'updateOK' ) );
			else
				Yii::app()->user->setFlash( "error", Yii::t( 'common', 'updateNOK' ) );
		}		$this->renderPartial( '//product/SignesVariablesShow', array( 'SignesVariables' => $SignesVariables,'Signe' => $Signe ));
	}
	public function actionSaveView2(){

		$SignesVariable = new \Business\SignesVariables();
		$SignesVariable->attributes						= Yii::app()->request->getParam( 'Business\SignesVariables' );
		if((Yii::app()->request->getParam( 'id' )!== NULL) && (Yii::app()->request->getParam( 'id' )!=''))
		{
			//////Update
			$SignesVariable = $SignesVariable->findByPk(Yii::app()->request->getParam( 'id' ));
			$SignesVariable->id_signe = Yii::app()->request->getParam( 'idSigne' );
			$SignesVariable->name = Yii::app()->request->getParam( 'name' );
			$SignesVariable->value = Yii::app()->request->getParam( 'value' );
			if( $SignesVariable->save() )
				Yii::app()->user->setFlash( "success", Yii::t( 'common', 'updateOK' ) );
			else
				Yii::app()->user->setFlash( "error", Yii::t( 'common', 'updateNOK' ) );
		}
		else
		{
			//////Insert
			$SignesVariable->id_signe = Yii::app()->request->getParam( 'idSigne' );
			$SignesVariable->name = Yii::app()->request->getParam( 'name' );
			$SignesVariable->value = Yii::app()->request->getParam( 'value' );

			if( $SignesVariable->save() )
				Yii::app()->user->setFlash( "success", Yii::t( 'common', 'updateOK' ) );
			else
				Yii::app()->user->setFlash( "error", Yii::t( 'common', 'updateNOK' ) );
		}


	}
	/************ END 12 Signes Variables Ajouter par Othmane ********************/

	public function actionViewEdition(){
		\Yii::import( 'ext.CKEditorHelper' );

		if( !($Reflation = \Business\SubCampaignReflation::load( Yii::app()->request->getParam( 'id' ) )) )
			return false;

		if( !($Type = Yii::app()->request->getParam( 'type' ) ) )
			return false;

		if( $Type == 'view' )
			$Data = CKEditorHelper::getConfigForContentManager( $Reflation->getPathView(true) );
		else
			$Data = CKEditorHelper::getConfigForContentManager( $Reflation->getPathTemplateProd(true) );

		$this->renderPartial( '//product/viewEdition', array( 'Ref' => $Reflation, 'Data' => $Data,'Type' => $Type ) );
	}

	// *********************************************************************** //
	public function actionPaymentProcessorSet(){
		$this->includeJS( 'paymentProcessor.js' );

		$PpSet = new \Business\PaymentProcessorSet( 'search' );

		if( Yii::app()->request->getParam('delete') ){
			// Log l'action courante :
			$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );

			$toDelete	= \Business\PaymentProcessorSet::load( Yii::app()->request->getParam('id') );
			if( $toDelete->delete() )
				Yii::app()->user->setFlash( "success", Yii::t( 'product', 'deleteOK' ) );
			else
				Yii::app()->user->setFlash( "error", Yii::t( 'product', 'updateNOK' ) );
		}

		// Filtre recherche :
		if( Yii::app()->request->getParam( 'Business\PaymentProcessorSet' ) !== NULL )
			$PpSet->attributes = Yii::app()->request->getParam( 'Business\PaymentProcessorSet' );

		$this->render( '//product/paymentProcessorSet', array( 'PpSet' => $PpSet ) );
	}

	public function actionPaymentProcessorSetShow(){
		// Update
		if( Yii::app()->request->getParam( 'id' ) !== NULL )
		{
			if( !($PpSet = \Business\PaymentProcessorSet::load( Yii::app()->request->getParam( 'id' ) )) )
				return false;
		}
		// Create
		else
			$PpSet = new \Business\PaymentProcessorSet();

		// POST :
		if( Yii::app()->request->getParam( 'Business\PaymentProcessorSet' ) !== NULL )
		{
			// Log l'action courante :
			$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );

			$PpSet->attributes = Yii::app()->request->getParam( 'Business\PaymentProcessorSet' );

			if( $PpSet->save() )
				Yii::app()->user->setFlash( "success", Yii::t( 'common', 'updateOK' ) );
			else
				Yii::app()->user->setFlash( "error", Yii::t( 'common', 'updateNOK' ) );
		}

		$this->renderPartial( '//product/paymentProcessorSetShow', array( 'PpSet' => $PpSet ) );
	}

	public function actionPaymentProcessor(){
		if( !($PpSet = \Business\PaymentProcessorSet::load( Yii::app()->request->getParam( 'id' ) )) )
			return false;

		if( Yii::app()->request->getParam('delete') )
		{
			// Log l'action courante :
			$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );

			$toDelete	= \Business\PaymentProcessorSetPaymentProcessor::load( $PpSet->id, Yii::app()->request->getParam('delete') );
			if( $toDelete->delete() )
				Yii::app()->user->setFlash( "success", Yii::t( 'product', 'deleteOK' ) );
			else
				Yii::app()->user->setFlash( "error", Yii::t( 'product', 'updateNOK' ) );
		}

		$PpSetPp						= new \Business\PaymentProcessorSetPaymentProcessor('search');
		$PpSetPp->idPaymentProcessorSet	= $PpSet->id;

		$this->renderPartial( '//product/paymentProcessor', array( 'PpSet' => $PpSet, 'PpSetPp' => $PpSetPp ) );
	}

	public function actionPaymentProcessorTypeShow(){
		// Update
		if( Yii::app()->request->getParam( 'id' ) !== NULL )
		{
			if( !($PP = \Business\PaymentProcessorType::load( Yii::app()->request->getParam( 'id' ) )) )
				return false;

			if( !($PpSetPp = \Business\PaymentProcessorSetPaymentProcessor::load( Yii::app()->request->getParam( 'idPpSet' ), $PP->id )) )
				return false;
		}
		// Create
		else
		{
			$PP			= new \Business\PaymentProcessorType();
			$PpSetPp	= new \Business\PaymentProcessorSetPaymentProcessor();
		}

		$Site = new \Business\Site('search');

		// POST :
		if( Yii::app()->request->getParam( 'Business\PaymentProcessorType' ) !== NULL )
		{
			// Log l'action courante :
			$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );

			$PP->attributes						= Yii::app()->request->getParam( 'Business\PaymentProcessorType' );
			$PpSetPp->attributes				= Yii::app()->request->getParam( 'Business\PaymentProcessorSetPaymentProcessor' );
			$PpSetPp->idPaymentProcessorSet		= Yii::app()->request->getParam( 'idPpSet' );

			if( $PP->save() )
			{
				$PpSetPp->idPaymentProcessorType	= $PP->id;

				if( $PpSetPp->save() )
					Yii::app()->user->setFlash( "success", Yii::t( 'common', 'updateOK' ) );
				else
					Yii::app()->user->setFlash( "error", Yii::t( 'common', 'updateNOK' ) );
			}
			else
				Yii::app()->user->setFlash( "error", Yii::t( 'common', 'updateNOK' ) );
		}

		$this->renderPartial( '//product/paymentProcessorTypeShow', array( 'PP' => $PP, 'Site' => $Site, 'PpSetPp' => $PpSetPp,'merchantLocal'=>$GLOBALS['merchantLocale'] ) );
	}

	public function actionPaymentProcessorTypeAdd(){
		if( !($PpSet = \Business\PaymentProcessorSet::load( Yii::app()->request->getParam( 'id' ) )) )
			return false;

		$PpSetPp	= new \Business\PaymentProcessorSetPaymentProcessor();
		$PP			= new \Business\PaymentProcessorType('search');
		$listPP		= CHtml::listData( $PP->findAll(), 'id', function( $PP ) { return $PP->name.' ( '.$PP->Site->code.' )'; } );

		// POST :
		if( Yii::app()->request->getParam( 'Business\PaymentProcessorSetPaymentProcessor' ) !== NULL )
		{
			// Log l'action courante :
			$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );

			$PpSetPp->attributes			= Yii::app()->request->getParam( 'Business\PaymentProcessorSetPaymentProcessor' );
			$PpSetPp->idPaymentProcessorSet	= $PpSet->id;

			if( $PpSetPp->save() )
				Yii::app()->user->setFlash( "success", Yii::t( 'common', 'updateOK' ) );
			else
				Yii::app()->user->setFlash( "error", Yii::t( 'common', 'updateNOK' ) );
		}

		$this->renderPartial( '//product/paymentProcessorTypeAdd', array( 'PpSet' => $PpSet, 'PpSetPp' => $PpSetPp, 'PP' => $PP, 'listPP' => $listPP ) );
	}

	public function actionSaveView(){
		\Yii::import( 'ext.CKEditorHelper' );
		if( !($Type = Yii::app()->request->getParam( 'type' ) ) )
			return false;

		if( !($Reflation = \Business\SubCampaignReflation::load( Yii::app()->request->getParam( 'idsub' ) )) )
			return false;

		/*echo '<script>alert("'.$Reflation->getPathView(true).'");</script>';*/
		if( $Type == 'view' )
			CKEditorHelper::saveView( $Reflation->getPathView(true), Yii::app()->request->getParam( 'data' ));
		else
			CKEditorHelper::saveView( $Reflation->getPathTemplateProd(true), Yii::app()->request->getParam( 'data' ));

	}

	public function actionVariablesperso(){

		// Recuperation des variablesperso depuis la BDD
		$this->includeJS( 'variablesperso.js' );
		$VPE	= new \Business\Variablesperso('search');
		if( Yii::app()->request->getParam('delete') ){
			// Log l'action courante :
			$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );

			$toDelete	= \Business\Variablesperso::load( Yii::app()->request->getParam('id') );
			if( $toDelete->delete() )
				Yii::app()->user->setFlash( "success", Yii::t( 'product', 'deleteOK' ) );
			else
				Yii::app()->user->setFlash( "error", Yii::t( 'product', 'updateNOK' ) );
		}

		// Filtre recherche :
		if( Yii::app()->request->getParam( 'Business\Variablesperso' ) !== NULL )
			$VPE->attributes = Yii::app()->request->getParam( 'Business\Variablesperso' );

		$this->render( '//product/variablesperso', array( 'variablesperso' => $VPE ) );

	}

	public function actionVariablesPersoShow(){
		// Update
		if( Yii::app()->request->getParam( 'id' ) !== NULL )
		{
			if( !($Variap = \Business\Variablesperso::load( Yii::app()->request->getParam( 'id' ) )) )
				return false;

		}
		// Create
		else
			$Variap = new \Business\Variablesperso();

		// POST :
		if( Yii::app()->request->getParam( 'Business\Variablesperso' ) !== NULL )
		{
			// Log l'action courante :
			$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );

			$Variap->attributes = Yii::app()->request->getParam( 'Business\Variablesperso' );

			if( $Variap->save() )
				Yii::app()->user->setFlash( "success", Yii::t( 'common', 'updateOK' ) );
			else
				Yii::app()->user->setFlash( "error", Yii::t( 'common', 'updateNOK' ) );
		}

		$this->renderPartial( '//product/variablesPersoShow', array( 'Variap' => $Variap ) );
	}


	public function actionCampaignShowQualt(){
		// Update
		if( Yii::app()->request->getParam( 'id' ) !== NULL )
		{
			if( !($Camp = \Business\Campaign::load( Yii::app()->request->getParam( 'id' ) )) )
				return false;

			//echo $this->portDir( true ).'/'.$Camp->ref;
		}
		// Create
		else
			$Camp = new \Business\Campaign();

		// POST :
		if( Yii::app()->request->getParam( 'Business\Campaign' ) !== NULL )
		{
			// Log l'action courante :
			$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );

			$Camp->attributes = Yii::app()->request->getParam( 'Business\Campaign' );

			if( isset(Yii::app()->request->getParam( 'Business\Campaign' )['date_control_cdc_prev']) )
			{

				$dateProject  = Yii::app()->request->getParam( 'Business\Campaign' )['date_control_cdc_prev'];
				$heureProject = Yii::app()->request->getParam( 'Business\Campaign' )['time_control_cdc_prev'];
				$date_control_cdc_prev = $dateProject . " " . $heureProject;

				if($Camp->date_control_cdc_prev != $date_control_cdc_prev){
					$suiviPlanification = true;
				}
				$Camp->date_control_cdc_prev = $date_control_cdc_prev;
				$Camp->commentaire_palanification = Yii::app()->request->getParam( 'Business\Campaign' )['commentaire_palanification'];

			}else if( isset(Yii::app()->request->getParam( 'Business\Campaign' )['date_control_project_prev']) ){

				$dateProject  = Yii::app()->request->getParam( 'Business\Campaign' )['date_control_project_prev'];
				$heureProject = Yii::app()->request->getParam( 'Business\Campaign' )['time_control_project_prev'];
				$date_control_project_prev = $dateProject . " " . $heureProject;

				if($Camp->date_control_project_prev != $date_control_project_prev){
					$suiviPlanification = true;
				}
				$Camp->date_control_project_prev = $date_control_project_prev;
				$Camp->commentaire_palanification = Yii::app()->request->getParam( 'Business\Campaign' )['commentaire_palanification'];

			}
			if( $Camp->save() ){
				if(isset($suiviPlanification)){
					$suiviPlanification  = $Camp->SuiviPlanification($Camp->id);
					$porteur    = Yii::app()->session['porteur'];
					$msg  = '<div style="font-size: 15px;"><b><u>'.$porteur.'('.$Camp->num.')</u></b> : le service qualité a mis à jour la date de livraison prévisionnelle de projet (<b>'.$Camp->num.'<b>).</div>';
					$msg .= $suiviPlanification;
					$expediteur = "Suivi Planification(".$porteur.")";
					$sendAlert  = \MailHelper::sendMail( $Camp->adminMails[$porteur], $porteur, 'Maj Suivi de Planification', $msg );
				}

				Yii::app()->user->setFlash( "success", Yii::t( 'common', 'updateOK' ) );
			}else{
				Yii::app()->user->setFlash( "error", 'Reference existe deja !' );

				//Yii::app()->user->setFlash( "error", Yii::t( 'common', 'updateNOK' ) );

			}
		}

		$this->renderPartial( '//product/campaignShowQualit', array( 'Camp' => $Camp ) );
	}



	/*************************** CDC V3 (by: Youssef HARRATI) ********************************/



	public function actionCampaignShowAdaptMark(){
		$id = Yii::app()->request->getParam( 'idcamp' );

		if( !($camp = \Business\Campaign::load( $id )) )
			return false;

		$CDCadapts = new \Business\CDCAdapt( );

		if( Yii::app()->request->getParam( 'Business\CDCAdapt' ) !== NULL ){
			$CDCadapts->id = Yii::app()->request->getParam( 'Business\CDCAdapt' )['id'];
			$CDCadapts->ref = Yii::app()->request->getParam( 'Business\CDCAdapt' )['ref'];
			$CDCadapts->label = Yii::app()->request->getParam( 'Business\CDCAdapt' )['label'];
		}


		if( Yii::app()->request->getParam('delete') ) :

			$CDCId =  Yii::app()->request->getParam('id');

			$CDCAdapt             = new \Business\CDCAdapt;
			$CampaignAdapt        = new \Business\CampaignAdapt;
			$CampaignAdaptShoot   = new \Business\CampaignAdaptShoot;
			$SubCampaignAdapt     = new \Business\SubCampaignAdapt;
			$SubCampaignComponent = new \Business\SubCampaign;
			$Pricinggrid          = new \Business\PricingGrid;
			$SiteComponent        = new \Business\Site;

			$CDCAdapt_data = $CDCAdapt->load($CDCId);
			foreach ($CDCAdapt_data->CampaignAdapt as $key => $ca) :

				$site_id     = $SiteComponent->loadByCode($ca->site)->id ;
				$campaign_id = $CDCAdapt_data->idCampaign;

				foreach ($ca->SubCampaignAdapt as $subkey => $subca)  :

					$product_id  =  $subca->idProduct;
					$SubCampain = $SubCampaignComponent->loadByCampaignAndProduct( $campaign_id, $product_id );
					$pgs = $Pricinggrid->getBySubCampaignAndSite( $SubCampain->id, $site_id );
					foreach ($pgs as $pg):
						if(method_exists($pg, 'delete'))
							$pg->delete() ;
					endforeach;
					$subca->delete();

				endforeach;

				$ca_adapte_shoot =  $CampaignAdaptShoot->loadByCampaign($ca->id);

				foreach ($ca_adapte_shoot as $key_shoot => $ca_shoot):
					$ca_shoot->delete();
				endforeach;

				$ca->delete();

			endforeach;
			$CDCAdapt_data->delete();

		endif ;

		$this->renderPartial( '//product/campaignShowAdaptMark', array( 'id' => $id, 'CDCadapts' => $CDCadapts, 'camp' => $camp ) );
	}

	public function actionCampaignCreateAdaptMark(){

		$sites      = \Business\Site::model()->findAll();
		$listSites  = [];
		$listSitesCrees = [];

		$idcamp = Yii::app()->request->getParam( 'idcamp' );

		$CDCAdapt	= new \Business\CDCAdapt();
		$cdcs = $CDCAdapt->loadByCampaign($idcamp);

		$lacamp = \Business\Campaign::load( $idcamp );

		foreach($cdcs as $cdc) {
			foreach($cdc->CampaignAdapt as $cp)
				$listSitesCrees[] = $cp->site;
		}
		$p = $GLOBALS['DefaultSite'][Yii::app()->params['porteur']];
		foreach($sites as $site) {
			if($site->code != $p && !in_array($site->code, $listSitesCrees))
				$listSites[] = $site->code;
		}

		// Update
		if( Yii::app()->request->getParam( 'id' ) !== NULL )
		{
			if( !($leCDCAdapt	= \Business\CDCAdapt::load( Yii::app()->request->getParam( 'id' ) )) )
				return false;

		}
		else{
			$leCDCAdapt	= new \Business\CDCAdapt();
		}
		$update = false;

		if( Yii::app()->request->getParam( 'Business\Campaign' ) !== NULL ){

			if( Yii::app()->request->getParam( 'id' ) !== NULL )
			{
				$update = true;
				$post = Yii::app()->request->getParam( 'Business\Campaign' );
				$postcdc = Yii::app()->request->getParam( 'Business\CDCAdapt' );
				$id = Yii::app()->request->getParam( 'id' );

				if( !($leCDCAdapt	= \Business\CDCAdapt::load( $id )) )
					return false;

				$leCDCAdapt->ref = $postcdc['ref'];
				$leCDCAdapt->label = $postcdc['label'];
				$leCDCAdapt->save();

				$checkedSites = [];
				foreach($leCDCAdapt->CampaignAdapt as $ca){
					if(!isset($post['sitesupdate']) || !in_array($ca->site, $post['sitesupdate'])){

						$SiteComponent        = new \Business\Site;
						$CampaignAdaptShoot   = new \Business\CampaignAdaptShoot;
						$SubCampaignAdapt     = new \Business\SubCampaignAdapt;
						$SubCampaignComponent = new \Business\SubCampaign;
						$Pricinggrid          = new \Business\PricingGrid;

						$site_id     = $SiteComponent->loadByCode($ca->site)->id ;
						$campaign_id = $leCDCAdapt->idCampaign;

						foreach ($ca->SubCampaignAdapt as $subkey => $subca)  :
							$product_id  =  $subca->idProduct;
							$SubCampain = $SubCampaignComponent->loadByCampaignAndProduct( $campaign_id, $product_id );
							$pgs = $Pricinggrid->getBySubCampaignAndSite( $SubCampain->id, $site_id );
							foreach ($pgs as $pg):
								if(method_exists($pg, 'delete'))
									$pg->delete() ;
							endforeach;
							$subca->delete();
						endforeach;
						$ca_adapte_shoot =  $CampaignAdaptShoot->loadByCampaign($ca->id);
						foreach ($ca_adapte_shoot as $key_shoot => $ca_shoot):
							$ca_shoot->delete();
						endforeach;
						$ca->delete();
					}
					else{
						$checkedSites[] = $ca->site;
						$post[$ca->site]['date_shoot'] = $post[$ca->site]['date'].' '.$post[$ca->site]['time'];
						$post[$ca->site]['date_shoot2'] = $post[$ca->site]['date2'].' '.$post[$ca->site]['time2'];
						$ca->num = $post[$ca->site]['num'];
						$ca->date_shoot = $post[$ca->site]['date_shoot'];
						$ca->date_shoot2 = $post[$ca->site]['date_shoot2'];
						$ca->save();
					}
				}

				if(isset($post['sites'])){
					$newsites = $post['sites'];
					foreach($newsites AS $site){
						$checkedSites[] = $site;
						$campaignAdapt	= new \Business\CampaignAdapt();
						$post[$site]['date_shoot'] = $post[$site]['date'].' '.$post[$site]['time'];
						$post[$site]['date_shoot2'] = $post[$site]['date2'].' '.$post[$site]['time2'];
						$campaignAdapt->num = $post[$site]['num'];
						$campaignAdapt->date_shoot = $post[$site]['date_shoot'];
						$campaignAdapt->date_shoot2 = $post[$site]['date_shoot2'];
						
						$campaignAdapt->site = $site;
						$campaignAdapt->idCDCAdapt = $leCDCAdapt->id;
						$campaignAdapt->save();
						unset($campaignAdapt);
					}
				}
			}else{
				$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );
				$post = Yii::app()->request->getParam( 'Business\Campaign' );
				$postcdc = Yii::app()->request->getParam( 'Business\CDCAdapt' );
				$leCDCAdapt	= new \Business\CDCAdapt();
				$leCDCAdapt->idCampaign = $post['idCampaign'];
				$leCDCAdapt->ref = $postcdc['ref'];
				$leCDCAdapt->label = $postcdc['label'];
				$leCDCAdapt->save();

				$checkedSites = [];

				foreach($post['sites'] AS $site){
					$checkedSites[] = $site;
					$campaignAdapt	= new \Business\CampaignAdapt();
					$post[$site]['date_shoot'] = $post[$site]['date'].' '.$post[$site]['time'];
					$post[$site]['date_shoot2'] = $post[$site]['date2'].' '.$post[$site]['time2'];

					$campaignAdapt->num = $post[$site]['num'];
					$campaignAdapt->date_shoot = $post[$site]['date_shoot'];
					$campaignAdapt->date_shoot2 = $post[$site]['date_shoot2'];
					$campaignAdapt->site = $site;
					$campaignAdapt->idCDCAdapt = $leCDCAdapt->id;

					$campaignAdapt->save();
					unset($campaignAdapt);
				}
			}

			$this->renderPartial( '//product/campaignCreateAdapt2Mark', array( 'sites' => $checkedSites, 'update' => $update, 'page' => 'campaignCreateAdapt2Mark', 'id' => $leCDCAdapt->id ,'idCamp'=> $post['idCampaign'] ) );

		}else{
			$this->renderPartial( '//product/campaignCreateAdaptMark', array( 'sites' => $listSites, 'id' => $idcamp, 'lecdcadapt' => $leCDCAdapt, 'lacamp' => $lacamp ) );
		}
	}

	public function actionCampaignCreateAdapt2Mark(){
		$id = Yii::app()->request->getParam( 'id' );
		$idCamp = Yii::app()->request->getParam( 'idCamp' );
		$update = Yii::app()->request->getParam( 'update' )==1? true: false;

		if( Yii::app()->request->getParam( 'Business\Campaign' ) !== NULL ){
			$post = Yii::app()->request->getParam( 'Business\Campaign' );
			if($update){
				foreach($post['sites'] AS $site){
					$checkedSites[] = $site;
					foreach($post[$site] AS $product){
						$campaignAdapt	= new \Business\CampaignAdapt();
						$camp = $campaignAdapt->loadByCampaignAndSite($id,$site);

						if( !($SubCampaignAdapt	= \Business\SubCampaignAdapt::model()->findByAttributes( array( 'idProduct' => $product['prod'],'idCampaignAdapt' => $camp->id  ) )) )
							$SubCampaignAdapt	= new \Business\SubCampaignAdapt();

						$SubCampaignAdapt->link_mail = $product['mail'];
						$SubCampaignAdapt->page = $product['page'];
						$SubCampaignAdapt->nb = $product['nb'];
						$SubCampaignAdapt->idProduct = $product['prod'];
						$SubCampaignAdapt->idCampaignAdapt = $camp->id;
						$SubCampaignAdapt->save();
						unset($SubCampaignAdapt);
						unset($campaignAdapt);
					}
				}
			}else{
				foreach($post['sites'] AS $site){
					$checkedSites[] = $site;
					foreach($post[$site] AS $product){
						$campaignAdapt	= new \Business\CampaignAdapt();
						$camp = $campaignAdapt->loadByCampaignAndSite($id,$site);

						$SubCampaignAdapt	= new \Business\SubCampaignAdapt();
						$SubCampaignAdapt->link_mail = $product['mail'];
						$SubCampaignAdapt->page = $product['page'];
						$SubCampaignAdapt->nb = $product['nb'];
						$SubCampaignAdapt->idProduct = $product['prod'];
						$SubCampaignAdapt->idCampaignAdapt = $camp->id;
						$SubCampaignAdapt->save();
						unset($SubCampaignAdapt);
						unset($campaignAdapt);
					}
				}
			}


			$this->renderPartial( '//product/campaignCreateAdapt3Mark', array( 'sites' => $checkedSites, 'update' => $update, 'page' => 'campaignCreateAdapt3Mark', 'id' =>$id, 'idCamp' =>$idCamp ) );

		}else{
			$this->renderPartial( '//product/campaignCreateAdapt2Mark', array( 'sites' => $listSites ) );
		}
	}


	public function actionCampaignCreateAdapt3Mark(){
		$id = Yii::app()->request->getParam( 'id' );
		$idCamp = Yii::app()->request->getParam( 'idCamp' );
		$update = Yii::app()->request->getParam( 'update' )==1? true: false;
		if( Yii::app()->request->getParam( 'Business\Campaign' ) !== NULL ){
			$post = Yii::app()->request->getParam( 'Business\Campaign' );
			$checkedSites = [];
			if( !$update ){
				foreach($post['sites'] AS $site){
					$campaignAdapt = new \Business\CampaignAdapt();
					$camp = $campaignAdapt->loadByCampaignAndSite($id,$site);
					$checkedSites[] = $site;
					foreach($post[$site][1] AS $population => $shoot){
						if($population=='population' || $population=='comment')
							continue;

						$CampaignAdaptShoot = new \Business\CampaignAdaptShoot();
						$CampaignAdaptShoot->idCampaignAdapt = $camp->id;
						$CampaignAdaptShoot->population = $population;
						$CampaignAdaptShoot->groupe_prix = $shoot['group'];
						$CampaignAdaptShoot->selection = $shoot['select'];
						$CampaignAdaptShoot->date_prem_shoot = $shoot['date'];
						$CampaignAdaptShoot->jours_shoot = $shoot['jour'];
						$CampaignAdaptShoot->comptage = $shoot['count'];
						$CampaignAdaptShoot->compte = 'Acq';

						$CampaignAdaptShoot->save();
						unset($CampaignAdaptShoot);

					}
					$camp->commentaire_shoot_acq = $post[$site][1]['comment'];
					$camp->save();

					if(isset($post[$site][2])){
						foreach($post[$site][2] AS $population => $shoot){
							if($population=='population' || $population=='comment')
								continue;
							$campaignAdapt = new \Business\CampaignAdapt();
							$camp = $campaignAdapt->loadByCampaignAndSite($id,$site);

							$CampaignAdaptShoot = new \Business\CampaignAdaptShoot();
							$CampaignAdaptShoot->idCampaignAdapt = $camp->id;
							$CampaignAdaptShoot->population = $population;
							$CampaignAdaptShoot->groupe_prix = $shoot['group'];
							$CampaignAdaptShoot->selection = $shoot['select'];
							$CampaignAdaptShoot->date_prem_shoot = $shoot['date'];
							$CampaignAdaptShoot->jours_shoot = $shoot['jour'];
							$CampaignAdaptShoot->comptage = $shoot['count'];
							$CampaignAdaptShoot->compte = 'Fid';

							$CampaignAdaptShoot->save();
							unset($CampaignAdaptShoot);
							unset($campaignAdapt);
						}
						$camp->commentaire_shoot_fid = $post[$site][2]['comment'];
						$camp->save();
					}
				}
			}else{
				foreach($post['sites'] AS $site){
					$campaignAdapt = new \Business\CampaignAdapt();
					$camp = $campaignAdapt->loadByCampaignAndSite($id,$site);
					$checkedSites[] = $site;
					foreach($post[$site][1] AS $population => $shoot){
						if($population=='population' || $population=='comment')
							continue;

						$CampaignAdaptShoot =\Business\CampaignAdaptShoot::loadByCampaignAndPopulation( $population, $camp->id, 'Acq' );
						if(!$CampaignAdaptShoot)
							$CampaignAdaptShoot = new \Business\CampaignAdaptShoot();

						$CampaignAdaptShoot->idCampaignAdapt = $camp->id;
						$CampaignAdaptShoot->population = $population;
						$CampaignAdaptShoot->groupe_prix = $shoot['group'];
						$CampaignAdaptShoot->selection = $shoot['select'];
						$CampaignAdaptShoot->date_prem_shoot = $shoot['date'];
						$CampaignAdaptShoot->jours_shoot = $shoot['jour'];
						$CampaignAdaptShoot->comptage = $shoot['count'];
						$CampaignAdaptShoot->compte = 'Acq';

						$CampaignAdaptShoot->save();
						unset($CampaignAdaptShoot);

					}
					$camp->commentaire_shoot_acq = $post[$site][1]['comment'];
					$camp->save();

					if(isset($post[$site][2])){
						foreach($post[$site][2] AS $population => $shoot){
							if($population=='population' || $population=='comment')
								continue;
							$campaignAdapt = new \Business\CampaignAdapt();
							$camp = $campaignAdapt->loadByCampaignAndSite($id,$site);

							$CampaignAdaptShoot =\Business\CampaignAdaptShoot::loadByCampaignAndPopulation( $population, $camp->id, 'Fid' );
							if(!$CampaignAdaptShoot)
								$CampaignAdaptShoot = new \Business\CampaignAdaptShoot();
							$CampaignAdaptShoot->idCampaignAdapt = $camp->id;
							$CampaignAdaptShoot->population = $population;
							$CampaignAdaptShoot->groupe_prix = $shoot['group'];
							$CampaignAdaptShoot->selection = $shoot['select'];
							$CampaignAdaptShoot->date_prem_shoot = $shoot['date'];
							$CampaignAdaptShoot->jours_shoot = $shoot['jour'];
							$CampaignAdaptShoot->comptage = $shoot['count'];
							$CampaignAdaptShoot->compte = 'Fid';

							$CampaignAdaptShoot->save();
							unset($CampaignAdaptShoot);
							unset($campaignAdapt);
						}
						$camp->commentaire_shoot_fid = $post[$site][2]['comment'];
						$camp->save();
					}
				}
			}
			Yii::app()->session['lessites'] = $checkedSites;
			Yii::app()->session['nbsite']   = count($checkedSites);
			$this->redirect(array('Product/PricingGridAdaptMark', 'sites' => $checkedSites, 'update' => $update, 'page' => 'campaignCreateAdapt4Mark', 'idAdapt' =>$id, 'idCamp' =>$idCamp));

		}else{
			$this->renderPartial( '//product/campaignCreateAdapt3Mark', array( 'sites' => $listSites ) );
		}
	}

	public function actionPricingGridAdaptMark(){

		//die('ok');
		$lessites  = Yii::app()->request->getParam( 'sites' );
		$id 	   = Yii::app()->request->getParam( 'idAdapt' );
		$idCamp    = Yii::app()->request->getParam( 'idCamp' );
		$page      = Yii::app()->request->getParam( 'page' );
		$nbprod    = Yii::app()->request->getParam( 'nbprod' );
		//$ls = Yii::app()->session['lessites']=$lessites ;


		// Mise a jour de la grille de prix :
		if( Yii::app()->request->getParam( 'GP' ) !== NULL )
		{
			$GP		= Yii::app()->request->getParam( 'GP' );
			$idSite	= Yii::app()->request->getParam( 'idSite' );
			$PT	    = Yii::app()->request->getParam( 'PT' );




			$error	= false;
			foreach( $GP as $SubCam => $tabBss )
			{
				foreach( $tabBss as $bs => $tabBs )  {
					foreach( $tabBs as $ps => $tabPs )
					{
						foreach( $tabPs as $pg => $price )
						{
							$Pricing = \Business\PricingGrid::get( $SubCam, $bs, $ps, $pg, $idSite );
							//echo $ps;
							$prixTh = $PT[$SubCam][$bs][1][$pg];
							/*echo('<pre>');
								print_r($prixTh);
							exit;*/
							if( $price > 0 )
							{
								if( $Pricing == NULL )
								{
									$Pricing					= new \Business\PricingGrid();
									$Pricing->refBatchSelling	= $bs;
									$Pricing->priceStep			= $ps;
									$Pricing->refPricingGrid	= $pg;
									$Pricing->idSubCampaign		= $SubCam;
									$Pricing->idSite			= $idSite;
									$Pricing->prixTheorique		= $prixTh;
								}

								$Pricing->priceATI			= $price;
								$Pricing->priceVAT			= 0;
								$Pricing->prixTheorique		= $prixTh;

								if( !$Pricing->save() )
								{
									Yii::app()->session['nbsite'] = Yii::app()->session['nbsite']-1;
									$error = true;
									break;
								}
							}
							else if( is_object($Pricing) )
							{
								if( !$Pricing->delete() )
								{
									$error = true;
									break;
								}
							}
						}
					}
				}
			}
			if( !$error ){
				Yii::app()->user->setFlash( "success", Yii::t( 'common', 'updateOK' ) );
			}else
				Yii::app()->user->setFlash( "error", Yii::t( 'common', 'updateNOK' ) );
		}


		$Site	= new \Business\Site('search');
		$PariteInvoice	= new \Business\PariteInvoice('search');
		$sites = $Site->findAll();
		$tab = array();
		$nbsiterest = 0;

		foreach( $sites as $S )
		{
			if(!in_array($S->code,Yii::app()->session['lessites'])){
				continue;
			}

			$lessitesrestants = Yii::app()->session['lessites'];
			unset($lessitesrestants[array_search($S->code, $lessitesrestants)]);
			Yii::app()->session['lessites'] = $lessitesrestants;
			$nbsiterest = count($lessitesrestants);

			$parite = $PariteInvoice->loadByDevise($S->codeDevise);
			$tab[ $S->country ] = array(
				'id'	=> $S->id,
				'ajax'	=> $this->createAbsoluteUrl( '//product/getPricingGridAdapt_Mark', array( 'idSite' => $S->id, 'parite' => $parite->id_parite, 'priceStep' => 2 /* $priceStep */, 'idCamp'=>$idCamp, 'idAdapt'=>$id, 'nbprod'=>count($this->nbProducts($idCamp)), 'nbsiterest' => $nbsiterest /*$nbprod*/ ) )
			);

			break;
		}


		$this->renderPartial( '//product/campaignCreateAdapt4Mark', array( 'tab' => $tab, 'idCamp'=>$idCamp, 'idAdapt'=>$id, 'nbprod'=>count($this->nbProducts($idCamp)) ) );

	}


	public function actionGetPricingGridAdapt_Mark(){
		$Site = new \Business\Site('search');
		$porteur = Yii::app()->params['porteur'];
		$sitedefaut = $Site::loadByCode($GLOBALS['DefaultSite'][$porteur]);
		$nbsiterest  = Yii::app()->request->getParam( 'nbsiterest' );


		$idCamp = Yii::app()->request->getParam( 'idCamp' );
		$id = Yii::app()->request->getParam( 'idAdapt' );

		if( !($Sub = \Business\SubCampaign::model()->findAllByAttributes( array( 'idCampaign' => $idCamp )))){
			return false;
		}


		foreach( $Sub as $s )
		{
			$sub[$s->id]['sub']  = $s->id;
			$sub[$s->id]['step'] = $s->Product->price_step;
			$sub[$s->id]['idproduct'] = $s->idProduct;
			$sub[$s->id]['refproduct'] = $s->Product->ref;

		}
		$nbprod = count($sub);

		if( ($idSite = Yii::app()->request->getParam( 'idSite' )) === NULL ){
			return false;
		}
		$nbRelance = Yii::app()->request->getParam( 'priceStep' );

		if( $nbRelance <= 0 ){
			Yii::app()->user->setFlash( "warning", Yii::t( 'product', 'noReflation' ) );
		}



		if( !($parite = \Business\PariteInvoice::load( Yii::app()->request->getParam( 'parite' ))) ){
			return false;
		}

		$this->renderPartial( '//product/getPricingGridAdapt_Mark', array( 'SubC' => $sub, 'idSite' => $idSite, 'parite' => $parite->parite /*, 'data' => $data*/, 'nbRelance' => $nbRelance, 'nbGP' => MAX_GP, 'nbBS' => MAX_BS, 'idAdapt'=>$id, 'idCamp'=>$idCamp, 'nbprod'=>$nbprod, 'sitedefaut' => $sitedefaut, 'nbsiterest' => $nbsiterest ) );

	}

	public function actionControleAdapt(){
		$id = Yii::app()->request->getParam( 'id' );
		$status = true;
		$shoot_status = true;
		$controle = [];
		$camps = \Business\CampaignAdapt::loadByCDC($id);

		foreach($camps AS $camp){
			$controle[$camp->site] = [];
			$population = ['Clients','VG','Prospects','Reac'];
			if(empty($camp->num)){
				$status = false;
				$controle[$camp->site]['camp'] = 'Le numéro de la campagne d\'adaptation est vide.';
			}
			$subs = \Business\SubCampaignAdapt::model()->findAllByAttributes( array( 'idCampaignAdapt' => $camp->id  ) );
			$i = 0;
			foreach($subs AS $sub){
				if(empty($sub->link_mail)){
					$status = false;
					$controle[$camp->site]['prod'][$i]['link_mail'] = 'Aucune adaptation de texte pour les Links Mail n\'a été mentionnée.';
				}

				if(empty($sub->page)){
					$status = false;
					$controle[$camp->site]['prod'][$i]['page'] = 'Aucune adaptation de texte pour les pages web n\'a été mentionnée.';
				}

				if(empty($sub->nb)){
					$status = false;
					$controle[$camp->site]['prod'][$i]['nb'] = 'Aucune note (N.B.) d\'adaptation de texte n\'a été mentionnée.';
				}
				$i++;
			}

			foreach($population AS $pop){
				$shoot = \Business\CampaignAdaptShoot::loadByCampaignAndPopulation( $pop, $camp->id, 'Acq'  );

				if(empty($shoot->groupe_prix) || empty($shoot->selection)|| empty($shoot->date_prem_shoot)|| empty($shoot->jours_shoot)|| empty($shoot->comptage)){
					$shoot_status = false;
				}

				if($this->isTwoSFAccounts()){
					$shoot = \Business\CampaignAdaptShoot::loadByCampaignAndPopulation( $pop, $camp->id, 'Fid' );

					if(empty($shoot->groupe_prix) || empty($shoot->selection)|| empty($shoot->date_prem_shoot)|| empty($shoot->jours_shoot)|| empty($shoot->comptage)){
						$shoot_status = false;
					}
				}
			}

			if(!$shoot_status)
				$controle[$camp->site]['shoot'] = "Le planning de Shoot est incomplet.";

		}
		//print_r($controle);die;
		$this->renderPartial( '//product/controleAdapt', array( 'status' => $status, 'controle' => $controle) );
	}


	public function actionGenerateCDCAdapt(){

		spl_autoload_unregister(array('YiiBase', 'autoload'));
		try {
			Yii::import('ext.PHPWord', true);
		} catch (Exception $e) {
			spl_autoload_register(array('YiiBase', 'autoload'));
			throw $e;
		}

		// New Word Document
		$PHPWord = new PHPWord();
		spl_autoload_register(array('YiiBase', 'autoload'));
		$document = $PHPWord->loadTemplate(\Yii::app()->basePath . '/vendors/Template2P2C.docx');

		$id = Yii::app()->request->getParam( 'id' );
		//echo $id;
		$leCDCAdapt	= \Business\CDCAdapt::load( $id );
		//print_r($leCDCAdapt->attributes);die("ok");
		$campaignAdapt	= new \Business\CampaignAdapt();
		$campAdapt = \Business\CampaignAdapt::loadByCDC($id);
		$sites = [];
		//$porteur = "Alisha ";
		$porteur = Yii::app()->params['porteur'];
		$porteur = ucfirst(explode('_',$porteur)[1])." ";
		foreach($campAdapt AS $cmp){
			$sites[] = $cmp->site;
		}
		$ports = $porteur.implode("-",$sites);
		$ports = str_replace(' ', '_', $ports);
		$porteur .= implode("/",$sites);
		//print_r($leCDCAdapt);die("ok");
		$idcpn = $leCDCAdapt->idCampaign ;
		//echo($idcpn);die;
		$Camp = \Business\Campaign::load( $idcpn );


		$document->setValue('leporteur', $porteur);
		$document->setValue('idcampgsource', $leCDCAdapt->idCampaign);
		//$document->setValue('porteurSource', 'Alisha IN');
		$Camp->porteur_source = ($Camp->porteur_source == '') ? '' : '('.$Camp->porteur_source.')';
		$document->setValue('porteurSource', $Camp->porteur_source);
		
		$document->setValue('nomenclature', $Camp->label);
		$document->setValue('nomcampg', $Camp->ref);

		$document->cloneRow('idcampg', count($sites));
		$document->cloneRow('dateshoot', count($sites));
		$document->cloneRow('test', count($sites));
		$document->cloneRow('prodPG', count($sites));
		$document->cloneRow('shoot', count($sites));


		$i=1;
		foreach($campAdapt AS $cmp){
			$document->setValue('idcampg#'.$i, ' - ['.$cmp->site.'] - '.$cmp->num);
			$document->setValue('site#'.$i, $cmp->site);
			
			if(isset($cmp->date_shoot2) && $cmp->date_shoot2 != '0000-00-00 00:00:00')
				$document->setValue('dateshoot#'.$i, ''.date($cmp->date_shoot).'<w:br/> Et '.date($cmp->date_shoot2));
			else
				$document->setValue('dateshoot#'.$i, $cmp->date_shoot);
			
			$document->setValue('test#'.$i, $cmp->site);

			$subCampAdapt = \Business\SubCampaignAdapt::loadAllByCampaign($cmp->id);
			$Site = \Business\Site::loadByCode($cmp->site);
			$subcs = \Business\SubCampaign::loadByCampaign($leCDCAdapt->idCampaign);

			$document->cloneRow('prod#'.$i, count($subCampAdapt));
			$document->cloneRow('prodPG#'.$i, count($subCampAdapt));

			$j = 1;
			foreach($subCampAdapt AS $subCmp){

				$document->setValue('prod#'.$i.'#'.$j, $j);
				$document->setValue('page#'.$i.'#'.$j, str_replace("\n","<w:br/>", $subCmp->page) );
				$document->setValue('mail#'.$i.'#'.$j, str_replace("\n","<w:br/>", $subCmp->link_mail));
				$document->setValue('nb#'.$i.'#'.$j, str_replace("\n","<w:br/>", $subCmp->nb));

				$document->setValue('prodPG#'.$i.'#'.$j, $j);
				$document->setValue('site#'.$i.'#'.$j, $cmp->site);


				/**** Pricing Grid ***/
				$table = $this->getGridPrice($PHPWord, $subcs[$j-1], $Site);


				if($table != "La grille Tarifaire est vide !!"){
					$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
					$sTableText = $objWriter->getWriterPart('document')->getObjectAsText($table);
				}
				else{
					$sTableText = $table;
				}
				$document->setValue('priceGrid#'.$i.'#'.$j, $sTableText);
				/***************** End Pricing Grid ******************/

				$j++;
			}

			/**** Shoot ***/
			if($this->isTwoSFAccounts()){
				$document->cloneRow('shoot#'.$i, 2);
				$table = $this->getShoot($PHPWord,$cmp->id);

				$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
				$sTableText = $objWriter->getWriterPart('document')->getObjectAsText($table);
				$document->setValue('sitePS#'.$i.'#1', $cmp->site);
				$document->setValue('comptePS#'.$i.'#1', 'Compte Acq');
				$document->setValue('shoot#'.$i.'#1', $sTableText);

				$table = $this->getShoot($PHPWord,$cmp->id,'Fid');

				$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
				$sTableText = $objWriter->getWriterPart('document')->getObjectAsText($table);
				$document->setValue('sitePS#'.$i.'#2', $cmp->site);
				$document->setValue('comptePS#'.$i.'#2', 'Compte Fid');
				$document->setValue('shoot#'.$i.'#2', $sTableText);
			}else{
				$table = $this->getShoot($PHPWord,$cmp->id);

				$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
				$sTableText = $objWriter->getWriterPart('document')->getObjectAsText($table);
				$document->setValue('sitePS#'.$i, $cmp->site);
				$document->setValue('comptePS#'.$i, '');
				$document->setValue('shoot#'.$i, $sTableText);
			}
			/***************** End Shoot ******************/

			$i++;
		}


		$document->save(\Yii::app()->basePath . '/vendors/CDCV3.docx');
		$path = \Yii::app()->basePath . '/vendors/CDCV3.docx';
		$file = 'CDC_Adaptation_'.$ports.'_'.$id.'.docx';
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream; charset=utf-8');
		header("Content-Disposition: attachment; filename=".$file);
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Content-Length: ' . filesize($path));
		readfile($path);
		unlink($path);
		die("ok: ".$id.", p: ".$porteur);
	}





	private function getShoot($PHPWord, $id, $compte = 'Acq'){
		/****** shoot *************/
		$fStyle = array('bold' => true, 'size' => 12);
		$bgTop = array('bgColor'=>'F2DBDB','borderColor' => '000000', 'borderSize' => 2);
		$bgLeft = array('bgColor'=>'D99594','borderColor' => '000000', 'borderSize' => 2);
		$cellStyle = array('borderColor' => '000000', 'borderSize' => 2);

		$section = $PHPWord->createSection();
		$table = $section->addTable('myTable');

		$Shoot = \Business\CampaignAdaptShoot::loadByCampaign($id, $compte);
		$shoot_headers = array('Population' => 'population', 'Groupe de Prix' => 'groupe_prix', 'Selection' => 'selection', 'Date 1er Shoot' => 'date_prem_shoot', 'Jours de Shoot' => 'jours_shoot', 'Comptage' => 'comptage' );

		$table->addRow();
		foreach($shoot_headers AS $key => $value){
			$table->addCell(1750, $bgTop)->addText($key,$fStyle);
		}

		foreach($Shoot AS $value){
			$table->addRow();
			$i = 0;
			foreach($shoot_headers AS $res){
				if($i==0)
					$table->addCell(1750, $bgLeft)->addText($value->$res,$fStyle);
				else if($res=='date_prem_shoot'){
					if($value->$res != '0000-00-00'){
						$tmp = explode('-',$value->$res);
						$table->addCell(1750, $cellStyle)->addText($tmp[2]."/".$tmp[1]."/".$tmp[0]);
					}else{
						$table->addCell(1750, $cellStyle)->addText(" ");
					}

				}else
					$table->addCell(1750, $cellStyle)->addText($value->$res);
				$i++;
			}
		}


		$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
		$sTableText = $objWriter->getWriterPart('document')->getObjectAsText($table);
		return $table;
	}

	private function getGridPrice($PHPWord, $subcs, $Site){
		/***** Price Grid ***/
		$PG1s = \Business\PricingGrid::model()->findAllByAttributes( array( 'idSubCampaign' => $subcs->id, 'idSite' => $Site->id ) );
		$price_step1 = $subcs->Product->price_step;
		$section = $PHPWord->createSection();
		$table   = $section->addTable();

		if(!is_array($PG1s))
			continue;
		foreach ($PG1s as $x){
			$PricingGridProduit1[$x['refBatchSelling']][$x['refPricingGrid']][$x['priceStep']] = $x['priceATI'];
			$PricingGridProduit1[$x['refBatchSelling']][$x['refPricingGrid']][$price_step1+1] = $x['prixTheorique'];
		}
		if(!empty($PricingGridProduit1)){
			//print_r($PricingGridProduit1);die;
			if(!isset($PricingGridProduit1) || !is_array($PricingGridProduit1))
				return $table;
			$ty = array();

			@ksort($PricingGridProduit1);

			foreach($PricingGridProduit1 as $key => $refPricingGrid) {
				@ksort($refPricingGrid);
				$ty[$key]=$refPricingGrid;
			}

			foreach($ty as $key => $refPricingGrid) {
				foreach($refPricingGrid as $key1 => $refPricingGrid) {
					@ksort($refPricingGrid);
					$ty[$key][$key1]=$refPricingGrid;
				}
			}

			/** BEGIN **/
			$tableStyle = array(
				'borderColor' => '000000',
				'borderSize'  => 15,
				'bold' => true,
				'align' => 'center'
			);

			$tableStyle1 = array(
				'borderColor' => '000000',
				'borderSize'  => 15,
				'bold' => true,
				'bgColor' => 'CE8D8C'
			);
			$tableStyle2 = array(
				'borderColor' => '000000',
				'borderSize'  => 15,
				'bold' => true,
				'bgColor' => 'DBE5F1'
			);

			$styleCell = array('gridSpan' => $price_step1,
				'borderColor' => '000000',
				'borderSize'  => 15,
				'bgColor' => '809BBD',
				'bold' => true);
			$table->addRow();
			$table->addCell(150)->addText("");
			$table->addCell(150)->addText("");
			$table->addCell(1500, $styleCell)->addText("Price Step",$tableStyle1,array('bold' => true, 'align' => 'center'));
			$table->addCell(150)->addText("");

			$table->addRow();
			$table->addCell(150,$tableStyle)->addText("Batch Selling",$tableStyle1,array('bold' => true, 'align' => 'center'));
			$table->addCell(150,$tableStyle)->addText("Pricing Grid",$tableStyle1,array('bold' => true, 'align' => 'center'));

			for($step = 1; $step <= $price_step1 ; $step++) {
				$table->addCell((int)1500/$price_step1,$tableStyle2)->addText("$step",null,array('bold' => true, 'align' => 'center'));
			}
			$table->addCell(1950,$tableStyle)->addText("Pricing theorique",$tableStyle1,array('bold' => true, 'align' => 'center'));

			foreach($ty as $key => $refPricingGrid1) {
				$table->addRow();
				$table->addCell(1500,array('vMerge' => 'restart',
					'borderColor' => '000000',
					'borderSize'  => 15,
					'valign' => 'center',
					'bgColor' => 'F2DBDB'))
					->addText($key,null,array('bold' => true,'align' => 'center'));
				foreach($refPricingGrid1 as $key1 => $refPricingGrid) {
					if($key1 == 1){
						$table->addCell(1750,$tableStyle1)->addText($key1,array('borderSize'=>15),array('bold' => true,'align' => 'center'));
					}else{
						$table->addRow();
						$table->addCell(1500,array('vMerge' => 'fusion','borderSize'=>15));
						$table->addCell(1500,$tableStyle1)->addText($key1,null,array('bold' => true,'align' => 'center'));
					}

					/*foreach($refPricingGrid as $key2 => $priceStep) {
                        if($key2 == $price_step1+2)
                            continue;
                        $table->addCell(1500,$tableStyle)->addText($priceStep,null,array('bold' => true,'align' => 'center'));
                    }*/

					for($step = 1; $step <= $price_step1+1 ; $step++) {
						if(isset($refPricingGrid[$step])){
							$table->addCell(1500,$tableStyle)->addText($refPricingGrid[$step],null,array('bold' => true,'align' => 'center'));
						}else{
							$table->addCell(1500,$tableStyle)->addText('',null,array('bold' => true,'align' => 'center'));
						}
					}
				}
			}

			return $table;
		}else{
			return 'La grille Tarifaire est vide !!';
		}
	}


	public function isTwoSFAccounts(){
		if($GLOBALS['porteurWithTwoSFAccounts'][$_SESSION['porteur']])
			return true;
		else
			return false;
	}

	public function nbProducts($id){
		$sub = new \Business\SubCampaign( );
		$subs = $sub->loadByCampaign($id);
		return $subs;
	}

	public function getSubAdapt($idProd, $idCDC, $site){
		$camp = \Business\CampaignAdapt::loadByCampaignAndSite($idCDC, $site);
		$sub = \Business\SubCampaignAdapt::model()->findByAttributes( array( 'idProduct' => $idProd,'idCampaignAdapt' => $camp->id  ) );;

		return $sub;
	}

	public function getCamp($idCDC, $site){
		$camp = \Business\CampaignAdapt::loadByCampaignAndSite($idCDC, $site);

		return $camp;
	}

	public function getShootAdapt($population, $idCDC, $site, $compte = 'Acq'){
		$camp = \Business\CampaignAdapt::loadByCampaignAndSite($idCDC, $site);
		$shoot = \Business\CampaignAdaptShoot::loadByCampaignAndPopulation( $population, $camp->id, $compte );

		return $shoot;
	}

	public function isMultiSite(){
		if(in_array(Yii::app()->params['porteur'],$GLOBALS['porteurMultiSite']) AND \Yii::App()->User->checkAccess('MARKETING_SERVICE'))
			return true;
		else
			return false;
	}

	/*************************** END CDC V3 ********************************/


// Generation CDC ========== MOUJAB ABDELILAH
// ====================================================================================================

	public function actionCampaignShowMark(){

		// ******************************************** Nombre Comptes * //
		$porteur = Yii::app()->params['porteur'];

		$nbcpt   = 1;
		if($GLOBALS['porteurWithTwoSFAccounts'][$porteur] == true){
			$nbcpt = 2;
		}

		Yii::app()->session['nbcomptes'] = $nbcpt;

		$CampShoot = array();

		// ******************************************** Nombre Produits Restants * //

		//POST
		if( Yii::app()->request->getParam('Business\Campaign')['nb_produit'] !== NULL ){
			$nbprod = Yii::app()->request->getParam( 'Business\Campaign' )['nb_produit'];
			Yii::app()->session['maxprod'] = $nbprod;
		}

		// ******************************************** Planning Shoot * //
		for ($l = 0; $l < 4; $l++){
			$CampShoot['Acq'][$l] = new \Business\CampaignShoot();
			$CampShoot['Fid'][$l] = new \Business\CampaignShoot();
		}

		// ******************************************** Modification Campagne (id!=null) * //
		$id     = Yii::app()->request->getParam( 'id' );
		$idpro  = 0;
		if( $id !== NULL && ! empty($id) )
		{
			if(!($Camp = \Business\Campaign::load( Yii::app()->request->getParam( 'id' )))){
				die('erreur: Impossible de charger la campaign');
				return false;
			}

			/** Model de Shoot */
			$CSs = \Business\CampaignShoot::model()->findAllByAttributes( array( 'id_campaign' => $id ), array( 'order'=>'population' ) );

			foreach ($CSs as $CS){

				$cmpt = $CS['compte'];
				switch ($CS['population']){
					case 'Clients':
						$CampShoot[$cmpt][0] = $CS;
						break;
					case 'VG':
						$CampShoot[$cmpt][1] = $CS;
						break;
					case 'Prospects':
						$CampShoot[$cmpt][2] = $CS;
						break;
					case 'Reac':
						$CampShoot[$cmpt][3] = $CS;
						break;
				}
			}

			// ******************************************** Sub Campagns * //
			$SubC = \Business\SubCampaign::model()->findAllByAttributes( array( 'idCampaign' => $id ), array( 'order'=>'position' ) );
			
			// ******************************************** Récupéré le premier produit s'il est créé * //
			if(isset($SubC[0]['idProduct'])){
				if($SubC[0]['idProduct'] > 0){
					$idpro = $SubC[0]['idProduct'];
					$idsubcamp = $SubC[0]['id'];
				}
			}

		// ******************************************** Création Campagne * //

		}else{ /** Create campagne :: GET */

			$Camp = new \Business\Campaign();
		}

		// ******************************************** Mise a jour ou création de la campagne -- récupération des elements saisis * ///
		if( Yii::app()->request->getParam( 'Business\Campaign' ) !== NULL ){
			// Log l'action courante
			$this->logAction( new CEvent($this, array( 'action' => \Business\Log::ACTION_ADMIN)));

			/** Affectation des champs Campaign */
			$Camp->attributes  = Yii::app()->request->getParam( 'Business\Campaign' );
			$Camp->ref         = strtolower($Camp->ref);
			$Camp->num         = Yii::app()->request->getParam( 'Business\Campaign' )['num'];
			$Camp->duree_shoot = Yii::app()->request->getParam( 'Business\Campaign' )['duree_shoot'];
			$Camp->titre_stat  = Yii::app()->request->getParam( 'Business\Campaign' )['titre_stat'];
			$Camp->asile       = Yii::app()->request->getParam( 'Business\Campaign' )['asile'];
			$Camp->chainable   = Yii::app()->request->getParam( 'Business\Campaign' )['chainable'];
			$Camp->split       = Yii::app()->request->getParam( 'Business\Campaign' )['split'];
			$Camp->numSource       = Yii::app()->request->getParam( 'Business\Campaign' )['numSource'];
			$Camp->porteur_source  = Yii::app()->request->getParam( 'Business\Campaign' )['porteur_source'];
			$Camp->model_shoot_acq = Yii::app()->request->getParam( 'Business\Campaign' )['model_shoot_acq'];
			$Camp->nb_produit      = Yii::app()->request->getParam( 'Business\Campaign' )['nb_produit'];
			$Camp->commentaire_shoot_acq      = Yii::app()->request->getParam( 'Business\Campaign' )['commentaire_shoot_acq'];
			$Camp->commentaire_palanification = Yii::app()->request->getParam( 'Business\Campaign' )['commentaire_palanification'];
			$Camp->etape_envoi                = Yii::app()->request->getParam( 'Business\Campaign' )['etape_envoi'];
			if($nbcpt == 2){
				$Camp->commentaire_shoot_fid = Yii::app()->request->getParam( 'Business\Campaign' )['commentaire_shoot_fid'];
			}
				$datej = Yii::app()->request->getParam( 'Business\Campaign' )['date_shoot'];
				$heurej = Yii::app()->request->getParam( 'Business\Campaign' )['time_shoot'];
			$Camp->date_shoot = $datej . " " . $heurej;
				$datej2 = Yii::app()->request->getParam( 'Business\Campaign' )['date_shoot2'];
				$heurej2 = Yii::app()->request->getParam( 'Business\Campaign' )['time_shoot2'];
			$Camp->date_shoot2 = $datej2 . " " . $heurej2;
			$nbprod = $Camp->nb_produit;
	
			// ******************************************** Suivi de planification * //
			if( isset(Yii::app()->request->getParam( 'Business\Campaign' )['date_creation_cdc_prev']) )
			{
				$dateProject  = Yii::app()->request->getParam( 'Business\Campaign' )['date_creation_cdc_prev'];
				$heureProject = Yii::app()->request->getParam( 'Business\Campaign' )['time_creation_cdc_prev'];
				$date_creation_cdc_prev = $dateProject . " " . $heureProject;
				if($Camp->date_creation_cdc_prev != $date_creation_cdc_prev){
					$suiviPlanification = true;
				}

				$Camp->date_creation_cdc_prev = $date_creation_cdc_prev;
				$date_prev	 = DateTime::createFromFormat( 'Y-m-d', Yii::app()->request->getParam( 'Business\Campaign' )['date_creation_cdc_prev'] );

				$Camp->date_control_cdc_prev     = $date_prev->add( new \DateInterval('P1D') )->format( 'Y-m-d H:i:s' );
				$Camp->date_valid_cdc_it_prev    = $date_prev->add( new \DateInterval('P2D') )->format( 'Y-m-d H:i:s' );
				$Camp->date_dev_it_prev          = $date_prev->add( new \DateInterval('P5D') )->format( 'Y-m-d H:i:s' );
				$Camp->date_control_project_prev = $date_prev->add( new \DateInterval('P7D') )->format( 'Y-m-d H:i:s' );
				$Camp->date_valid_project_prev   = $date_prev->add( new \DateInterval('P9D') )->format( 'Y-m-d H:i:s' );

				if(empty($Camp->projectStatus)){
					$Camp->projectStatus = \Business\Campaign::PROJECT_IN_PROGRESS;
				}


			}else if(isset(Yii::app()->request->getParam( 'Business\Campaign' )['date_valid_project_prev'])){

				$dateProject  = Yii::app()->request->getParam( 'Business\Campaign' )['date_valid_project_prev'];
				$heureProject = Yii::app()->request->getParam( 'Business\Campaign' )['time_valid_project_prev'];
				$date_valid_project_prev = $dateProject . " " . $heureProject;

				if($Camp->date_valid_project_prev != $date_valid_project_prev){
					$suiviPlanification = true;
				}

				$Camp->date_valid_project_prev = $date_valid_project_prev;
			}

			// ******************************************** Controle des champs AJAX * //
			if( empty($Camp->label) )
				die('erreur: Le label de la Campagne est obligatoire !');
			if( empty($Camp->ref) )
				die('erreur: La référence de la Campagne est obligatoire !');
			if( empty($Camp->titre_stat) )
				die('erreur: Le Titre Stats de la Campagne est obligatoire');

			// ******************************************** Enregistrement des données de la Campagne * //
			if( $Camp->save() ){

				$msgerr = "";

				// ******************************************** Enregistrement des Models de Shoot * //
				for ($i=0; $i<4; $i++){

					$CampSh = new \Business\CampaignShoot();

					if(Yii::app()->request->getParam( 'BusinessCampaignShoot' )["Acq"][$i]["id"] > 0){
						if( !($CampSh = \Business\CampaignShoot::load( Yii::app()->request->getParam( 'BusinessCampaignShoot' )["Acq"][$i]["id"] )) ){
							die('erreur: Impossible de charger le planning de shoot');
							return false;
						}
					}

					$dt = Yii::app()->request->getParam( 'BusinessCampaignShoot' )["Acq"][$i]["date_prem_shoot"];
					if(empty($dt) || $dt == "" || $dt == " ")
						$dt = "0000-00-00";
					// ******************************************** Récupération des elements saisis - 1er Compte * //
					$CampSh->population      = Yii::app()->request->getParam( 'BusinessCampaignShoot' )["Acq"][$i]["population"];
					$CampSh->groupe_prix     = Yii::app()->request->getParam( 'BusinessCampaignShoot' )["Acq"][$i]["groupe_prix"];
					$CampSh->selection       = Yii::app()->request->getParam( 'BusinessCampaignShoot' )["Acq"][$i]["selection"];
					$CampSh->date_prem_shoot = $dt;
					$CampSh->jours_shoot     = Yii::app()->request->getParam( 'BusinessCampaignShoot' )["Acq"][$i]["jours_shoot"];
					$CampSh->comptage        = Yii::app()->request->getParam( 'BusinessCampaignShoot' )["Acq"][$i]["comptage"];
					$CampSh->id_campaign     = $Camp->id;

					if(! $CampSh->save())
						$msgerr = $msgerr.$CampSh->population."(ACQ),  ";

					// ******************************************** Model de Shoot Compte FID * //
					if($nbcpt == 2){

						$CampSh = new \Business\CampaignShoot();

						if(Yii::app()->request->getParam( 'BusinessCampaignShoot' )["Fid"][$i]["id"] > 0){
							if( !($CampSh = \Business\CampaignShoot::load( Yii::app()->request->getParam( 'BusinessCampaignShoot' )["Fid"][$i]["id"] )) ){
								die('erreur: Impossible de charger le planning de shoot - FID');
								return false;
							}
						}

						$dt = Yii::app()->request->getParam( 'BusinessCampaignShoot' )["Fid"][$i]["date_prem_shoot"];
						if(empty($dt) || $dt == "" || $dt == " ")
							$dt = "0000-00-00";
						// ******************************************** Récupération des elements saisis - FID* //
						$CampSh->population = Yii::app()->request->getParam( 'BusinessCampaignShoot' )["Fid"][$i]["population"];
						$CampSh->groupe_prix = Yii::app()->request->getParam( 'BusinessCampaignShoot' )["Fid"][$i]["groupe_prix"];
						$CampSh->selection = Yii::app()->request->getParam( 'BusinessCampaignShoot' )["Fid"][$i]["selection"];
						$CampSh->date_prem_shoot = $dt;
						$CampSh->jours_shoot = Yii::app()->request->getParam( 'BusinessCampaignShoot' )["Fid"][$i]["jours_shoot"];
						$CampSh->comptage = Yii::app()->request->getParam( 'BusinessCampaignShoot' )["Fid"][$i]["comptage"];
						$CampSh->id_campaign = $Camp->id;
						$CampSh->compte = 'Fid';

						if(! $CampSh->save())
							$msgerr = $msgerr.$CampSh->population."(FID),  ";
					}

				}

				if($msgerr == ""){
					if(isset($suiviPlanification)){
						$suiviPlanification  = $Camp->SuiviPlanification($Camp->id);
						$porteur    = Yii::app()->session['porteur'];
						$msg  = '<div style=" font-size:15px;"><u>'.$porteur.'('.$Camp->num.')</u> : le service <b>Marketing</b> a effectué une mise a jour du date de livraison prévisionnelle du CDC de la campagne(<b>'.$Camp->num.'</b>). </div>';
						$msg .= $suiviPlanification;
						$expediteur = "Suivi Planification(".$porteur.")";
						$sendAlert  = \MailHelper::sendMail( $Camp->adminMails[$porteur], $porteur, 'Maj Suivi de Planification', $msg );
					}
					Yii::app()->user->setFlash( "success", Yii::t( 'common', 'updateOK' ) );
				}else{
					Yii::app()->user->setFlash( "error", 'Erreur lors de l\'enregistrement des "CampaignShoot" suivants '.$msgerr );
					Yii::app()->user->setFlash( "success", Yii::t( 'common', 'Campagne enregistrée' ) );
				}
			}else{
				$searchLabel = \Business\Campaign::model()->findAllByAttributes(array('label'=>$Camp->label));
				$searchRef   = \Business\Campaign::model()->findAllByAttributes(array('ref'=>$Camp->ref));
				if(!empty($searchLabel))
					die('erreur: Label existe déjà, veuillez choisir un autre label svp!');
				elseif(!empty($searchRef))
					die('erreur: Référence existe déjà, veuillez choisir une autre référence svp!');
				else
					die('erreur: Impossible d\'enregistrer la Campaign');
			}
		}

		// ******************************************** Redirection vers le Produit - PoPup Product * //
		if( Yii::app()->request->getParam( 'step' ) == '1' ){
			if($idpro > 0){
				$this->redirect( array('product/productShowMark', 'idpro'=>$idpro, 'id' => $idsubcamp, 'idCamp'=>$Camp->id, 'nbprodrest'=>$nbprod) );
			}else{
				$this->redirect( array('product/productShowMark', 'idCamp'=>$Camp->id, 'nbprodrest'=>$nbprod) );
			}

		}
		// ******************************************** Affichage Campagne - PoPup Campagne * //
		else{
			// ******************************************** Liste des porteurs FR - Porteur Origine * //
			$lalistedesporteursfr = array();
			foreach($GLOBALS['listPorteur'] as $glport){
				if (strpos($glport['port-name'], ' FR'))
					$lalistedesporteursfr[] = $glport;
			}

			$this->renderPartial( '//product/campaignShowMark', array( 'Camp' => $Camp, 'CampShoot' => $CampShoot, 'nbcpt' => $nbcpt, 'porteurs'=>$lalistedesporteursfr) );
		}
	}

	public function actionProductShowMark(){

		$idcampm   = Yii::app()->request->getParam( 'idCamp' );
		$nbprodrest    = Yii::app()->request->getParam( 'nbprodrest' );
		
		if( Yii::app()->request->getParam( 'idCamp' ) !== NULL ){
			
			if(!($Camp = \Business\Campaign::load( Yii::app()->request->getParam( 'idCamp' )))){
				die('erreur: Impossible de charger la campaign du produit.');
				return false;
			}
			
			$nbrprods = $Camp->nb_produit;
			$SubC = \Business\SubCampaign::model()->findAllByAttributes( array( 'idCampaign' => Yii::app()->request->getParam( 'idCamp' ) ), array( 'order'=>'position' ) );
	
			// ******************************************** Récupéré le produit à modifier s il est deja créé * //
			$idpro = 0;
			if( isset($SubC[$nbrprods - $nbprodrest]['idProduct']) ){
				if($SubC[$nbrprods - $nbprodrest]['idProduct'] > 0){
					$idpro = $SubC[$nbrprods - $nbprodrest]['idProduct'];
					$idsubcamp = $SubC[$nbrprods - $nbprodrest]['id'];
				}
			}
			
			if($idpro > 0){
				if( !($Sub = \Business\SubCampaign::load( $idsubcamp )) ){
					die('erreur: Impossible de charger la sub campaign.');
					return false;
				}
				$Prod	= $Sub->Product;
			}
			// ******************************************** Nouveau Produit - SubCamp * //
			else{
				$Sub				= new \Business\SubCampaign();
				$Sub->idCampaign	= $idcampm;
				$Sub->position      = \Business\SubCampaign::GetLastPositionByCampaign($idcampm);
	
				$Prod				= new \Business\Product();
				$Prod->ref   		= $Sub->Campaign->ref.'_'.$Sub->position;
			}
		}else{
			die('erreur: ID de la Campaign est invalide.');
			return false;
		}
	
	
		$PPSet	= new \Business\PaymentProcessorSet('search');
		$lSub	= $Sub->Campaign->SubCampaign;

		// ******************************************** POST * //
		if( Yii::app()->request->getParam( 'Business\Product' ) !== NULL )
		{
			// Log l'action courante :
			$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );
	
			// ******************************************** Récupération des champs du formulaire * //
			$Sub->attributes	= Yii::app()->request->getParam( 'Business\SubCampaign' );
			$Prod->attributes	= Yii::app()->request->getParam( 'Business\Product' );
			
			$Prod->ref	        = strtolower(Yii::app()->request->getParam( 'Business\Product' )['ref']);
			$Prod->description  = str_replace(' ','_',Yii::app()->request->getParam( 'Business\Product' )['description']);
			
			$Prod->price_step = Yii::app()->request->getParam( 'Business\Product' )['price_step'];
			$Prod->description_marketing = Yii::app()->request->getParam( 'Business\Product' )['description_marketing'];
			$Prod->particularite_it = Yii::app()->request->getParam( 'Business\Product' )['particularite_it'];
			$Prod->kdo = Yii::app()->request->getParam( 'Business\Product' )['kdo'];
			$Prod->num_c = Yii::app()->request->getParam( 'Business\Product' )['num_c'];
			$Prod->date_a = Yii::app()->request->getParam( 'Business\Product' )['date_a'];
			$Prod->variable_page = Yii::app()->request->getParam( 'Business\Product' )['variable_page'];
			$Prod->variable_mail = Yii::app()->request->getParam( 'Business\Product' )['variable_mail'];
			$Prod->productType2 = Yii::app()->request->getParam( 'Business\Product' )['productType2'];
			$priceStep = Yii::app()->request->getParam( 'Business\Product' )['price_step'];
			$tblpro = Yii::app()->request->getParam( 'bdcFields' );
			
			if( empty($Prod->price_step) )
				die('erreur: Il faut créer au moins une "price step" !');
	
			if(isset($tblpro['global']))
			{
				foreach( $tblpro['global'] as $refr => $contn ){
					if($contn == 'Message'){
						$lemsg = $tblpro['global'][$refr] . ":" . Yii::app()->request->getParam( 'lemessage' );
						$tblpro['global'][$refr] = $lemsg;
						break;
					}
				}
			}
	
			$Prod->bdcFields = $tblpro;
	
			$paramPriceModel			= Yii::app()->request->getParam( 'paramPriceModel' );
			$priceModel_Prod = ($Prod->priceModel == 'prevBasedAsile2' || $Prod->priceModel == 'prevBasedAsile3' || $Prod->priceModel == 'prevBasedAsile4' )? 'prevBased' : $Prod->priceModel;
			$Prod->paramPriceModel		= isset($paramPriceModel[$priceModel_Prod]) ? $paramPriceModel[$priceModel_Prod] : NULL;
	
			// ******************************************** Enregistrer le Produit et SubCamp * //
			if( $Sub->idProduct > 0 ) {
				if( $Sub->save() && $Prod->save() )
					Yii::app()->user->setFlash( "success", Yii::t( 'common', 'updateOK' ) );
				else{
					/*print_r($Sub->getErrors());
					print_r($Prod->getErrors());
					die;*/
					die('erreur: Impossible de Créer la sub campaign et le produit !');
					Yii::app()->user->setFlash( "error", Yii::t( 'common', 'updateNOK' ) );
				}
			}else{
			
				if( $Prod->save() ) {
					$Sub->idProduct		= $Prod->id;
					$Sub->attributes	= Yii::app()->request->getParam( 'Business\SubCampaign' );
			
					if( $Sub->save() )
						Yii::app()->user->setFlash( "success", Yii::t( 'common', 'updateOK' ) );
					else{
						die('erreur: Impossible de Créer la sub campaign');
						Yii::app()->user->setFlash( "error", 'Erreur lors de la création du SubID. Le produit a été supprimé. Veuillez réessayer' );
						return false;
					}
				}else{
					print_r($Prod->attributes);
					die('erreur: Impossible de Créer le produit');
					Yii::app()->user->setFlash( "error", "Erreur Lors de la création du Produit. Veuillez réessayer ou contacter l'administrateur" );
					return false;
				}
			}
		}else{
			//converting to array...
			$Prod->productType2 = explode(',',$Prod->productType2);
		}
	
		if( Yii::app()->request->getParam( 'step' ) != NULL){
			$this->redirect( array('product/pricingGridMark', 'id'=>$Sub->id, 'idCamp'=>$idcampm, 'nbprodrest'=>$nbprodrest, 'priceStep'=>$priceStep) );
		}else{
			$this->renderPartial( '//product/productShowMark', array( 'Sub' => $Sub, 'Prod' => $Prod, 'Camp'=>$Camp, 'lPPSet' => $PPSet->findAll(), 'lSub' => $lSub ) );
		}

	}


	public function actionPricingGridMark(){

			$priceStep     = Yii::app()->request->getParam( 'priceStep' );
		$porteur       = Yii::app()->params['porteur'];
		$idCamp        = Yii::app()->request->getParam( 'idCamp' );
		$nbprodrest    = Yii::app()->request->getParam( 'nbprodrest' );

		// ******************************************** Récupérer SubCamp * //
		if( Yii::app()->request->getParam( 'id' ) !== NULL ){
			if( !($Sub = \Business\SubCampaign::load( Yii::app()->request->getParam( 'id' ) )) )
				return false;
		}
		else{
			Yii::app()->user->setFlash( "error", 'Erreur lors de la récupération du SubCampaign !' );
			return false;
		}
	

			
		// ******************************************** Mise a jour Grille Tarifaire * //
		if( Yii::app()->request->getParam( 'GP' ) !== NULL )
		{
			$GP		= Yii::app()->request->getParam( 'GP' );
			$idSite	= Yii::app()->request->getParam( 'idSite' );
			$PT	    = Yii::app()->request->getParam( 'PT' );
					
			$error	= false;
	
			##############
			
			$priceStep    = count($GP[1]); 			
			$batchSelling = count($GP);
			$Nbs = 0;
			
			for($j=1;$j<=$batchSelling;$j++){
				if(array_sum($_POST['GP'][$j][1])){
					$Nbs++;
				}
			}
			$site = \Business\Site::load($idSite);
			$pariteInvoice	= new \Business\PariteInvoice('search');
			$parite = $pariteInvoice->loadByDevise($site->codeDevise);
			for($j=1;$j<=$Nbs;$j++){
				for($i=1; $i<5; $i++){
					$GP[$j][$priceStep+1][$i] = intval(1.50/($parite->parite));
					$GP[$j][$priceStep+2][$i] = intval(1.50/($parite->parite));
				}
			}
			#############
			
			foreach( $GP as $bs => $tabBs )
			{
				foreach( $tabBs as $ps => $tabPs )
				{
					foreach( $tabPs as $pg => $price )
					{
						$Pricing = \Business\PricingGrid::get( $Sub->id, $bs, $ps, $pg, $idSite );
						$prixTh = $PT[$bs][1][$pg];
						if( $price > 0 )
						{
							if( $Pricing == NULL )
							{
								$Pricing					= new \Business\PricingGrid();
								$Pricing->refBatchSelling	= $bs;
								$Pricing->priceStep			= $ps;
								$Pricing->refPricingGrid	= $pg;
								$Pricing->idSubCampaign		= $Sub->id;
								$Pricing->idSite			= $idSite;
								$Pricing->prixTheorique		= $prixTh;
							}
	
							$Pricing->priceATI			= $price;
							$Pricing->priceVAT			= 0;
							$Pricing->prixTheorique		= $prixTh;
	
							if( !$Pricing->save() )
							{
								$error = true;
								break;
							}
						}
						else if( is_object($Pricing) )
						{
							if( !$Pricing->delete() )
							{
								$error = true;
								break;
							}
						}
					}
				}
			}
	
			if( !$error ){
				
				Yii::app()->user->setFlash( "success", Yii::t( 'common', 'updateOK' ) );
			}else
				Yii::app()->user->setFlash( "error", Yii::t( 'common', 'updateNOK' ) );
		}

		$Site	        = new \Business\Site('search');
		$PariteInvoice	= new \Business\PariteInvoice('search');
		$tab		    = array();
	
		if(isset($GLOBALS['DefaultSite'][$porteur])){
			$sites = $Site::loadByCode($GLOBALS['DefaultSite'][$porteur]);
		}else{
			$sites = $Site::model()->find(array('order'=>'id ASC'));
		}
	
		// ******************************************** Afficher les tableaux de la Grille Tarifaire - Ajax * //
		
		$parite = $PariteInvoice->loadByDevise($sites->codeDevise);
		$tab[ $sites->country ] = array(
				'id'	=> $sites->id,
				'ajax'	=> $this->createAbsoluteUrl( '//product/getPricingGridMark', array( 'idSite' =>  $sites->id, 'idSub' => $Sub->id, 'parite' => $parite->id_parite, 'priceStep' => $priceStep, 'idCamp'=>$idCamp, 'nbprodrest'=>$nbprodrest, 'nbsites'=>1 ) )
		);
	
		Yii::app()->session['nbsite'] = 1;
	
		//$nbprodrest = $nbprodrest - 1;
		
		// ******************************************** Redirection création 2eme produit * //
		if( Yii::app()->request->getParam( 'step' ) == '3' && $nbprodrest > 0 )
			$this->redirect( array('product/productShowMark', 'idCamp'=>$idCamp, 'nbprodrest'=>$nbprodrest) );
		else{
			$this->renderPartial( '//product/pricingGridMark', array( 'Sub' => $Sub, 'tab' => $tab, 'idCamp'=>$idCamp, 'nbprodrest'=>$nbprodrest, 'nbsites'=>1 ) );
		}

	}


	public function actionGetPricingGridMark(){

		//$ls = Yii::app()->session['lessites'];
		//print_r($ls); die;
		//Yii::app()->session['indiceSite'] = Yii::app()->session['indiceSite'] + 1;
		//$nbsites = Yii::app()->request->getParam( 'nbsites' );
		$nbprodrest  = Yii::app()->request->getParam( 'nbprodrest' );
		$idCamp  = Yii::app()->request->getParam( 'idCamp' );

		if( !($Sub = \Business\SubCampaign::load( Yii::app()->request->getParam( 'idSub' ) )) )
			return false;

		if( ($idSite = Yii::app()->request->getParam( 'idSite' )) === NULL )
			return false;

		if( !($parite = \Business\PariteInvoice::load( Yii::app()->request->getParam( 'parite' ))) )
			return false;

		$nbRelance = Yii::app()->request->getParam( 'priceStep' );

		if( $nbRelance <= 0 )
			Yii::app()->user->setFlash( "warning", Yii::t( 'product', 'noReflation' ) );

		$data = array();
		$data2 = array();
		foreach( $Sub->PricingGrid as $GP )
		{

			$data[ $GP->priceStep ][] = array(
				'bs' => $GP->refBatchSelling,
				'gp' => $GP->refPricingGrid,
				'price' => 0,
				'prix'=> $GP->prixTheorique
			);
			$data2[$GP->refBatchSelling][$GP->refPricingGrid][] =  array(
				'prix'=> $GP->prixTheorique
			);
		}
		$nbprodrest = $nbprodrest - 1;

		$this->renderPartial( '//product/getPricingGrid_Mark', array( 'Sub' => $Sub, 'idSite' => $idSite, 'parite' => $parite->parite, 'data' => $data, 'data2' => $data2, 'nbRelance' => $nbRelance, 'nbGP' => MAX_GP, 'nbBS' => MAX_BS, 'idCamp'=>$idCamp, 'nbprod'=>$nbprodrest, 'nbsites'=>1) );

	}


	// ******************************************** Action Controle CDC * //
	public function actionControleCDC(){

		$controle = array();

		// **************** Controle de la campagne */

		$idCamp = Yii::app()->request->getParam( 'idCamp' );
		if( !($Camp = \Business\Campaign::load( $idCamp )) )
			return false;

		$ercamp = $Camp->controle();
		if(count($ercamp) > 0)
			$controle['camp'] = $ercamp;

		$porteur = Yii::app()->params['porteur'];
		$nbcpt = 1;
		//if(true == true){
		if($GLOBALS['porteurWithTwoSFAccounts'][$porteur] == true){

			$nbcpt = 2;

			// **************** Controle du planning de Shoot FID */

			$CSsf = \Business\CampaignShoot::model()->findAllByAttributes( array( 'id_campaign' => $idCamp, 'compte' => "Fid" ), array( 'order'=>'population' ) );
			$err = 0;
			foreach ($CSsf as $CS){
				switch ($CS['population']){
					case 'Clients':
						if($CS->controle() === false){
							$err = 1;
							break 2;
						}
						break;
					case 'VG':
						if($CS->controle() === false){
							$err = 1;
							break 2;
						}
						break;
					case 'Prospects':
						if($CS->controle() === false){
							$err = 1;
							break 2;
						}
						break;
				}
			}
			if($err == 1)
				$controle['shoot_fid'] = "Le planning de Shoot (Compte FID) est incomplet.";
		}

		// **************** Controle du planning de Shoot ACQ */
		$CSs = \Business\CampaignShoot::model()->findAllByAttributes( array( 'id_campaign' => $idCamp, 'compte' => "Acq" ), array( 'order'=>'population' ) );

		$err = 0;
		foreach ($CSs as $CS){
			switch ($CS['population']){
				case 'Clients':
					if($CS->controle() === false){
						$err = 1;
						break 2;
					}
					break;
				case 'VG':
					if($CS->controle() === false){
						$err = 1;
						break 2;
					}
					break;
				case 'Prospects':
					if($CS->controle() === false){
						$err = 1;
						break 2;
					}
					break;
			}
		}
		if($err == 1){
			if($nbcpt == 1)
				$controle['shoot_acq'] = "Le planning de Shoot est incomplet.";
			else
				$controle['shoot_acq'] = "Le planning de Shoot (Compte ACQ) est incomplet.";
		}


		$subc = new \Business\SubCampaign();
		$subcs = $subc->loadByCampaign($idCamp);

		$sites = \Business\Site::model()->findAll(array('order'=>'id ASC'));
		//print_r($site[1]->attributes);die;

		// **************** Controle du Produit 1 */
		if(isset($subcs[0]->Product)){
			$erp1 = $subcs[0]->Product->controle();
			if(count($erp1) > 0)
				$controle['p1'] = $erp1;

			// **************** Controle Grille Produit 1 */
			foreach ($sites as $site){
				$PG1s = \Business\PricingGrid::model()->findAllByAttributes( array( 'idSubCampaign' => $subcs[0]->id, 'idSite' => $site->id ), array( 'order'=>'idSite' ) );
				if(count($PG1s) < 1){
					if(isset($controle['p1']))
						array_push($controle['p1'],"La Grille tarifaire du Produit 1 (" . $site->country . ") est vide.");
					else
						$controle['p1'][0] = "La Grille tarifaire du Produit 1 (" . $site->country . ") est vide.";
				}
			}

		}

		// **************** Controle du Produit 2 */
		if(isset($subcs[1]->Product)){
			$erp2 = $subcs[1]->Product->controle();
			if(count($erp2) > 0)
				$controle['p2'] = $erp2;

			// **************** Controle Grille Produit 2 */
			foreach ($sites as $site){
				$PG2s = \Business\PricingGrid::model()->findAllByAttributes( array( 'idSubCampaign' => $subcs[1]->id, 'idSite' => $site->id ), array( 'order'=>'idSite' ) );
				if(count($PG2s) < 1){
					if(isset($controle['p2']))
						array_push($controle['p2'],"La Grille tarifaire du Produit 2 (" . $site->country . ") est vide.");
					else
						$controle['p2'][0] = "La Grille tarifaire du Produit 2 (" . $site->country . ") est vide.";
				}
			}
		}

		$this->renderPartial( '//product/controle', array( 'Camp' => $Camp, 'controle' => $controle) );
	}

// ******************************************** Action Generation CDC * //
	public function actionGetCDCdMark() {

		$tbchq = array(
			//Name			=> directory name on server
			'de_theodor'	=> true,
			'es_laetizia'	=> true,
			'es_rmay'		=> true,
			'fr_rmay'		=> true,
			'fr_laetizia'	=> true,
			'fr_rinalda'	=> true,
			'fr_rucker'		=> true,
			'de_laetizia'   => true,
			//'en_alisha'		=> true,
		);

		$idCamp = Yii::app()->request->getParam( 'idCamp' );

		// ******************************************** Nombre Comptes * //
		$porteur   = Yii::app()->params['porteur'];
		$leporteur = $porteur;
		$nbcpt     = 1;
		if($GLOBALS['porteurWithTwoSFAccounts'][$porteur] == true){
			$nbcpt = 2;
			$CSsf  = \Business\CampaignShoot::model()->findAllByAttributes( array( 'id_campaign' => $idCamp, 'compte' => "Fid" ), array( 'order' => 'population' ) );
		}
		if( !($Camp = \Business\Campaign::load( $idCamp )) ){
			return false;
		}


		// ******************************************** Planning Shoot 1er Compte * //
		$CSs     = \Business\CampaignShoot::model()->findAllByAttributes( array( 'id_campaign' => $idCamp, 'compte' => "Acq" ), array( 'order'=>'population' ) );
		$subc    = new \Business\SubCampaign();
		$subcs   = $subc->loadByCampaign($idCamp);


		$porteur	= isset($GLOBALS['porteurMap'][\Yii::app()->params['porteur']]) ? $GLOBALS['porteurMap'][\Yii::app()->params['porteur']] : \Yii::app()->params['porteur'];
		$confFile	= SERVER_ROOT.'/'.$porteur.'/'.DIR_CONF_PORTEUR.'/'.( IS_DEV_VERSION ? FILE_CONF_PORTEUR_DEV : FILE_CONF_PORTEUR );
//print_r($confFile);
//exit;
		if( is_file($confFile) ) {
			$globConf	= require( $confFile );
			if(isset($GLOBALS['DefaultSite'][\Yii::app()->params['porteur']])){
				$porteurTemp = explode(' ',$globConf['name']);
				if(count($porteurTemp) == 3){
					$porteur = strtoupper($GLOBALS['DefaultSite'][$porteur]).' '.$porteurTemp[0].' '.$porteurTemp[1];
				}else{
					$porteur = strtoupper($GLOBALS['DefaultSite'][$porteur]).' '.$porteurTemp[0];
				}
			}else{
				$porteurTemp = explode(' ',$globConf['name']);
				$porteurTemp1 = explode('_',\Yii::app()->params['porteur']);
				if(count($porteurTemp) == 3){
					$porteur = strtoupper($porteurTemp1[0]).' '.$porteurTemp[0].' '.$porteurTemp[1];
				}else{
					$porteur = strtoupper($porteurTemp1[0]).' '.$porteurTemp[0];
				}

			}
		}

		//$porteurTemp = explode('_',ucfirst(\Yii::app()->params['porteur']));
		//$porteur =  $GLOBALS['DefaultSite'][].' '.$porteurTemp[1];


		$g1 = $g2 = $g3 = $g4 = "";
		$s1 = $s2 = $s3 = $s4 = "";
		$d1 = $d2 = $d3 = $d4 = "";
		$j1 = $j2 = $j3 = $j4 = "";
		$c1 = $c2 = $c3 = $c4 = "";

		$g1f = $g2f = $g3f = $g4f = "";
		$s1f = $s2f = $s3f = $s4f = "";
		$d1f = $d2f = $d3f = $d4f = "";
		$j1f = $j2f = $j3f = $j4f = "";
		$c1f = $c2f = $c3f = $c4f = "";

		$split   = "Non";
		$chain   = "Non";
		$type1   = "Virtual";
		$type2   = "Virtual";
		$nbprods = 1;

		/**Vérifier si au moins un des produits est créé */

		if(isset($subcs[0]->Product)){

			$type1      = $subcs[0]->Product->productType2;
			$price_step1 = $subcs[0]->Product->price_step;
			if(isset($subcs[1]->Product)){
				$price_step2 = $subcs[1]->Product->price_step;
				$type2      = $subcs[0]->Product->productType2;
				$nbprods    = 2;
			}
			if($Camp->split == 1){
				$split = "Oui";
			}
			if($Camp->chainable == 1){
				$chain = "Oui";
			}


			$site = \Business\Site::model()->find(array('order'=>'id ASC'));
			$PG1s = \Business\PricingGrid::model()->findAllByAttributes( array( 'idSubCampaign' => $subcs[0]->id, 'idSite' => $site->id ), array( 'order'=>'idSite' ) );


			for($ii=1; $ii<=4; $ii++){
				for($jj=1; $jj<=4; $jj++){
					for($kk=1; $kk<=4; $kk++){
						for($l=1; $l<3; $l++){
							$c = '$var'.$l.$ii.$jj.$kk.' = "";';
							eval($c);
						}
					}
				}
			}

			foreach ($PG1s as $x){
				${'var1'.$x['refBatchSelling'].$x['refPricingGrid'].$x['priceStep']} = $x['priceATI'];

			}




			// ******************************************** Planning Shoot 1er Compte * //
			foreach ($CSs as $CS){
				switch ($CS['population']){
					case 'Clients':
						$g1 = $CS['groupe_prix'];
						$s1 = $CS['selection'];
						if($CS['date_prem_shoot'] != "0000-00-00")
							$d1 = $CS['date_prem_shoot'];
						$j1 = $CS['jours_shoot'];
						$c1 = $CS['comptage'];
						break;
					case 'VG':
						$g2 = $CS['groupe_prix'];
						$s2 = $CS['selection'];
						if($CS['date_prem_shoot'] != "0000-00-00")
							$d2 = $CS['date_prem_shoot'];
						$j2 = $CS['jours_shoot'];
						$c2 = $CS['comptage'];
						break;
					case 'Prospects':
						$g3 = $CS['groupe_prix'];
						$s3 = $CS['selection'];
						if($CS['date_prem_shoot'] != "0000-00-00")
							$d3 = $CS['date_prem_shoot'];
						$j3 = $CS['jours_shoot'];
						$c3 = $CS['comptage'];
						break;
					case 'Reac':
						$g4 = $CS['groupe_prix'];
						$s4 = $CS['selection'];
						if($CS['date_prem_shoot'] != "0000-00-00")
							$d4 = $CS['date_prem_shoot'];
						$j4 = $CS['jours_shoot'];
						$c4 = $CS['comptage'];
						break;
				}
			}

			// ******************************************** Planning Shoot 2eme Compte * //
			if($nbcpt == 2){
				foreach ($CSsf as $CSf){
					switch ($CSf['population']){
						case 'Clients':
							$g1f = $CSf['groupe_prix'];
							$s1f = $CSf['selection'];
							if($CSf['date_prem_shoot'] != "0000-00-00")
								$d1f = $CSf['date_prem_shoot'];
							$j1f = $CSf['jours_shoot'];
							$c1f = $CSf['comptage'];
							break;
						case 'VG':
							$g2f = $CSf['groupe_prix'];
							$s2f = $CSf['selection'];
							if($CSf['date_prem_shoot'] != "0000-00-00")
								$d2f = $CSf['date_prem_shoot'];
							$j2f = $CSf['jours_shoot'];
							$c2f = $CSf['comptage'];
							break;
						case 'Prospects':
							$g3f = $CSf['groupe_prix'];
							$s3f = $CSf['selection'];
							if($CSf['date_prem_shoot'] != "0000-00-00")
								$d3f = $CSf['date_prem_shoot'];
							$j3f = $CSf['jours_shoot'];
							$c3f = $CSf['comptage'];
							break;
						case 'Reac':
							$g4f = $CSf['groupe_prix'];
							$s4f = $CSf['selection'];
							if($CSf['date_prem_shoot'] != "0000-00-00")
								$d4f = $CSf['date_prem_shoot'];
							$j4f = $CSf['jours_shoot'];
							$c4f = $CSf['comptage'];
							break;
					}
				}
			}

			// ******************************************** 2 Produits * //
			if($nbprods == 2){
				$type2 = $subcs[1]->Product->productType2;
				$PG2s = \Business\PricingGrid::model()->findAllByAttributes( array( 'idSubCampaign' => $subcs[1]->id, 'idSite' => $site->id ), array( 'order'=>'idSite' ) );
				foreach ($PG2s as $x){
					${'var2'.$x['refBatchSelling'].$x['refPricingGrid'].$x['priceStep']} = $x['priceATI'];
				}

			}


			/**
			 * @Import PHPWord
			 */
			spl_autoload_unregister(array('YiiBase', 'autoload'));
			try {
				Yii::import('ext.PHPWord', true);
			}catch (Exception $e){
				spl_autoload_register(array('YiiBase', 'autoload'));
				throw $e;
			}
			// New Word Document
			$PHPWord = new PHPWord();
			spl_autoload_register(array('YiiBase', 'autoload'));

			$nbl = 2;

			// ******************************************** Selection du Template * //
			if($nbprods == 1){
				if(isset($tbchq[$leporteur])){
					if($nbcpt == 1){
						$document = $PHPWord->loadTemplate(\Yii::app()->basePath . '/vendors/Template1P1C-AC.docx');
						$d= '/vendors/Template1P1C-AC.docx';
					}
					else{
						$document = $PHPWord->loadTemplate(\Yii::app()->basePath . '/vendors/Template1P2C-AC.docx');
						$d= '/vendors/Template1P2C-AC.docx';
					}

				}else{
					if($nbcpt == 1){
						$document = $PHPWord->loadTemplate(\Yii::app()->basePath . '/vendors/Template1P1C-SC.docx');
						$d= '/vendors/Template1P1C-SC.docx';
					}else{
						$document = $PHPWord->loadTemplate(\Yii::app()->basePath . '/vendors/Template1P2C-SC.docx');
						$d= '/vendors/Template1P2C-SC.docx';
					}

				}

			}
			else{
				// ******************************************** Templates Asile * //
				if($Camp->asile == 1){
					if(isset($tbchq[$leporteur])){
						if($nbcpt == 1){
							$document = $PHPWord->loadTemplate(\Yii::app()->basePath . '/vendors/Template2P1C-AC-Asile.docx');
							$d= '/vendors/Template2P1C-AC-Asile.docx';
						}else{
							$document = $PHPWord->loadTemplate(\Yii::app()->basePath . '/vendors/Template2P2C-AC-Asile.docx');
							$d= '/vendors/Template2P2C-AC-Asile.docx';
						}

					}else{
						if($nbcpt == 1){
							$document = $PHPWord->loadTemplate(\Yii::app()->basePath . '/vendors/Template2P1C-SC-Asile.docx');
							$d= '/vendors/Template2P1C-SC-Asile.docx';

						}else{
							$document = $PHPWord->loadTemplate(\Yii::app()->basePath . '/vendors/Template2P2C-SC-Asile.docx');
							$d= '/vendors/Template2P2C-SC-Asile.docx';
						}

					}
				}else{	/**Template Inter */
					if(isset($tbchq[$leporteur])){
						if($nbcpt == 1){
							$document = $PHPWord->loadTemplate(\Yii::app()->basePath . '/vendors/Template2P1C-AC-Inter.docx');
							$d= '/vendors/Template2P1C-AC-Inter.docx';
						}else{
							$document = $PHPWord->loadTemplate(\Yii::app()->basePath . '/vendors/Template2P2C-AC-Inter.docx');
							$d= '/vendors/Template2P2C-AC-Inter.docx';
						}

					}
					else{
						if($nbcpt == 1){
							$document = $PHPWord->loadTemplate(\Yii::app()->basePath . '/vendors/Template2P1C-SC-Inter.docx');
							$d= '/vendors/Template2P1C-SC-Inter.docx';
						}else{
							$document = $PHPWord->loadTemplate(\Yii::app()->basePath . '/vendors/Template2P2C-SC-Inter.docx');
							$d= '/vendors/Template2P2C-SC-Inter.docx';
						}

					}
				}
				$nbl = 3;
			}

			for($ii=1; $ii<=4; $ii++){
				for($jj=1; $jj<=4; $jj++){
					for($kk=1; $kk<=4; $kk++){
						for($l=1; $l<$nbl; $l++){
							$document->setValue('p'.$l.$ii.$jj.$kk, ${'var'.$l.$ii.$jj.$kk});
						}
					}
				}
			}
			/*echo('<pre>');
            print_r($d);
            exit;*/

			$section = $PHPWord->createSection();
			$table   = $section->addTable();

			foreach ($PG1s as $x){
				$PricingGridProduit1[$x['refBatchSelling']][$x['refPricingGrid']][$x['priceStep']] = $x['priceATI'];
				$PricingGridProduit1[$x['refBatchSelling']][$x['refPricingGrid']][$price_step1+1] = $x['prixTheorique'];
			}



			$ty = array();

			/**
			 * Style Cell
			 */
			$tableStyle = array(
				'borderColor' => '000000',
				'borderSize'  => 15,
				'bold' => true,
				'align' => 'center'
			);

			$tableStyle1 = array(
				'borderColor' => '000000',
				'borderSize'  => 15,
				'bold' => true,
				'bgColor' => 'CE8D8C'
			);

			$tableStyle2 = array(
				'borderColor' => '000000',
				'borderSize'  => 15,
				'bold' => true,
				'bgColor' => 'DBE5F1'
			);





			/**
			 * Test si $PricingGridProduit1 est vide
			 * @Code for Debug
			echo('<pre>');
			if(empty($PricingGridProduit1))
			print_r('La Grille Tarifaire est vide !!');
			else
			print_r($PricingGridProduit1);
			exit;
			 */
			if(!empty($PricingGridProduit1)) {

				ksort($PricingGridProduit1);

				foreach($PricingGridProduit1 as $key => $refPricingGrid) {
					ksort($refPricingGrid);
					$ty[$key]=$refPricingGrid;
				}

				foreach($ty as $key => $refPricingGrid) {
					foreach($refPricingGrid as $key1 => $refPricingGrid) {
						ksort($refPricingGrid);
						$ty[$key][$key1]=$refPricingGrid;
					}
				}

				/*foreach($PricingGridProduit1 as $key => $refPricingGrid) {
                    foreach($refPricingGrid as $key1 => $refPricingGrid) {
                        ksort($refPricingGrid);
                        $ty[$key][$key1]=$refPricingGrid;
                    }
                }*/

				//ksort($PricingGridProduit1);
				//print_r($ty);
				//die;
				/** BEGIN **/
				$styleCell = array('gridSpan' => $price_step1,
					'borderColor' => '000000',
					'borderSize'  => 15,
					'bgColor' => '809BBD',
					'bold' => true);
				$table->addRow();
				$table->addCell(150)->addText("");
				$table->addCell(150)->addText("");
				$table->addCell(1500, $styleCell)->addText("Price Step",$tableStyle1,array('bold' => true, 'align' => 'center'));
				$table->addCell(150)->addText("");

				$table->addRow();
				$table->addCell(150,$tableStyle)->addText("Batch Selling",$tableStyle1,array('bold' => true, 'align' => 'center'));
				$table->addCell(150,$tableStyle)->addText("Pricing Grid",$tableStyle1,array('bold' => true, 'align' => 'center'));

				for($step = 1; $step <= $price_step1 ; $step++) {
					//for($step = 1; $step <= count($ty) ; $step++) {
					$table->addCell((int)1500/$price_step1,$tableStyle2)->addText("$step",null,array('bold' => true, 'align' => 'center'));
				}
				$table->addCell(1950,$tableStyle)->addText("Pricing theorique",$tableStyle1,array('bold' => true, 'align' => 'center'));



				foreach($ty as $key => $refPricingGrid1) {
					$table->addRow();
					$table->addCell(1500,array('vMerge' => 'restart',
						'borderColor' => '000000',
						'borderSize'  => 15,
						'valign' => 'center',
						'bgColor' => 'F2DBDB'))->addText($key,null,array('bold' => true,'align' => 'center'));

					foreach($refPricingGrid1 as $key1 => $refPricingGrid) {

						if($key1 == 1){
							$table->addCell(1750,$tableStyle1)->addText($key1,array('borderSize'=>15),array('bold' => true,'align' => 'center'));
						}else{
							$table->addRow();
							$table->addCell(1500,array('vMerge' => 'fusion','borderSize'=>15));
							$table->addCell(1500,$tableStyle1)->addText($key1,null,array('bold' => true,'align' => 'center'));
						}
						/*echo('<pre>');
                            print_r($refPricingGrid);
                        exit;*/
						/*foreach($refPricingGrid as $key2 => $priceStep) {
                                $table->addCell(1500,$tableStyle)->addText($priceStep,null,array('bold' => true,'align' => 'center'));
                        }*/
						for($step = 1; $step <= $price_step1+1 ; $step++) {
							if(isset($refPricingGrid[$step])){
								$table->addCell(1500,$tableStyle)->addText($refPricingGrid[$step],null,array('bold' => true,'align' => 'center'));
							}else{
								$table->addCell(1500,$tableStyle)->addText('',null,array('bold' => true,'align' => 'center'));
							}

						}

					}

				}


				$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
				$sTableText = $objWriter->getWriterPart('document')->getObjectAsText($table);
				$document->setValue('grille1', $sTableText);
			}else{
				$document->setValue('grille1', 'La Grille Tarifaire est vide !!');
			}
			/** END **/

			//}
			if($nbprods == 2){

				$section2 = $PHPWord->createSection();
				$table2   = $section2->addTable();

				foreach ($PG2s as $x){
					$PricingGridProduit2[$x['refBatchSelling']][$x['refPricingGrid']][$x['priceStep']] = $x['priceATI'];
					$PricingGridProduit2[$x['refBatchSelling']][$x['refPricingGrid']][$price_step2+1]  = $x['prixTheorique'];
				}


				$ty2 = array();
				if(!empty($PricingGridProduit2)) {

					ksort($PricingGridProduit2);

					foreach($PricingGridProduit2 as $key => $refPricingGrid2) {
						ksort($refPricingGrid2);
						$ty2[$key]=$refPricingGrid2;
					}

					foreach($ty2 as $key => $refPricingGrid) {
						foreach($refPricingGrid as $key1 => $refPricingGrid) {
							ksort($refPricingGrid);
							$ty2[$key][$key1]=$refPricingGrid;
						}
					}

					/** BEGIN **/

					$styleCell2 = array('gridSpan' => $price_step2,
						'borderColor' => '000000',
						'borderSize'  => 15,
						'bgColor' => '809BBD',
						'bold' => true);

					$table2->addRow();
					$table2->addCell(150)->addText("");
					$table2->addCell(150)->addText("");
					$table2->addCell(1500, $styleCell2)->addText("Price Step",$tableStyle1,array('bold' => true, 'align' => 'center'));
					$table2->addCell(150)->addText("");

					$table2->addRow();
					$table2->addCell(150,$tableStyle)->addText("Batch Selling",$tableStyle1,array('bold' => true, 'align' => 'center'));
					$table2->addCell(150,$tableStyle)->addText("Pricing Grid",$tableStyle1,array('bold' => true, 'align' => 'center'));

					for($step = 1; $step <= $price_step2; $step++) {
						$table2->addCell((int)1500/$price_step2,$tableStyle2)->addText("$step",null,array('bold' => true, 'align' => 'center'));
					}
					$table2->addCell(1950,$tableStyle)->addText("Pricing theorique",$tableStyle1,array('bold' => true, 'align' => 'center'));



					foreach($ty2 as $key => $refPricingGrid2) {
						$table2->addRow();
						$table2->addCell(1500,array('vMerge' => 'restart',
							'borderColor' => '000000',
							'borderSize'  => 15,
							'valign' => 'center',
							'bgColor' => 'F2DBDB'))->addText($key,null,array('bold' => true,'align' => 'center'));
						foreach($refPricingGrid2 as $key2 => $refPricingGrid) {

							if($key2 == 1){
								$table2->addCell(1750,$tableStyle1)->addText($key2,null,array('bold' => true,'align' => 'center'));
							}else{
								$table2->addRow();
								$table2->addCell(1500,array('vMerge' => 'fusion','borderSize'=>15));
								$table2->addCell(1500,$tableStyle1)->addText($key2,null,array('bold' => true,'align' => 'center'));
							}

							/*	foreach($refPricingGrid as $key2 => $priceStep) {
                                      $table2->addCell(1500,$tableStyle)->addText($priceStep,null,array('bold' => true,'align' => 'center'));
                              }*/

							/*	echo('<pre>');
                                    print_r($refPricingGrid);
                                exit;*/
							for($step = 1; $step <= $price_step2+1 ; $step++) {
								if(isset($refPricingGrid[$step])){
									$table2->addCell(1500,$tableStyle)->addText($refPricingGrid[$step],null,array('bold' => true,'align' => 'center'));
								}else{
									$table2->addCell(1500,$tableStyle)->addText('',null,array('bold' => true,'align' => 'center'));
								}

							}
						}

					}



					$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
					$sTableText = $objWriter->getWriterPart('document')->getObjectAsText($table2);
					$document->setValue('grille2', $sTableText);
				}else{
					$document->setValue('grille2', 'La Grille Tarifaire est vide !!');
				}

				/** END **/

			}


			// ****** Set values template produit 1 * /

			$document->setValue('nm', htmlspecialchars($Camp->ref));
			$document->setValue('nbj', '2');
			$document->setValue('nbjas', '1');

			//$document->setValue('idcampg', $Camp->id);
			$document->setValue('idcampg', $Camp->num);
			$document->setValue('nomcampg', htmlspecialchars($Camp->label));
			$document->setValue('nomenclature', htmlspecialchars($Camp->ref));
			$document->setValue('leporteur', htmlspecialchars($porteur));
			
			if(isset($Camp->date_shoot2) && $Camp->date_shoot2 != '0000-00-00 00:00:00')
				$document->setValue('dateshoot', ''.date($Camp->date_shoot).'<w:br/> Et le '.date($Camp->date_shoot2));
			else
				$document->setValue('dateshoot', date($Camp->date_shoot));

			$desc1 = $subcs[0]->Product->description;
			//$document->setbloc('desc1', $desc1, 40);
			$document->setValue('desc1', str_replace("\n","<w:br/>", htmlspecialchars($desc1)), 40);
			$document->setValue('nomp1', htmlspecialchars($desc1));

			$tstat = $Camp->titre_stat;
			$document->setbloc('titrestat', htmlspecialchars($tstat), 40);

			$descp1 = $subcs[0]->Product->description_marketing;
			//$document->setbloc('descriptionp1', $descp1, 92);
			$document->setValue('descriptionp1', str_replace("\n","<w:br/>", htmlspecialchars($descp1)), 92);

			//$document->setbloc('nom1', $subcs[0]->Product->description, 92);
			$document->setValue('nom1', str_replace("\n","<w:br/>", htmlspecialchars($subcs[0]->Product->description)), 92);
			$document->setbloc('type1', htmlspecialchars($type1), 76);
			//$document->setbloc('it1', $subcs[0]->Product->particularite_it, 92);
			$document->setValue('it1', str_replace("\n","<w:br/>", htmlspecialchars($subcs[0]->Product->particularite_it)), 92);
			$document->setbloc('kdo1', htmlspecialchars($subcs[0]->Product->kdo), 92);

			//$document->setbloc('pagep1', $subcs[0]->Product->variable_page, 40);
			$document->setValue('pagep1', str_replace("\n","<w:br/>",htmlspecialchars($subcs[0]->Product->variable_page)), 40);
			$document->setValue('mailp1', str_replace("\n","<w:br/>",htmlspecialchars($subcs[0]->Product->variable_mail)), 40);

			//$document->setbloc('numero1', $subcs[0]->Product->num_c, 40);
			$document->setValue('numero1', str_replace("\n","<w:br/>",htmlspecialchars($subcs[0]->Product->num_c)), 40);
			//$document->setbloc('date1', $subcs[0]->Product->date_a, 40);
			$document->setValue('date1', str_replace("\n","<w:br/>",htmlspecialchars($subcs[0]->Product->date_a)), 40);

			$document->setValue('chainable', htmlspecialchars($chain));

			$document->setValue('split', htmlspecialchars($split));
			$document->setValue('dureeshoot', htmlspecialchars($Camp->duree_shoot.' Jours'));

			//$document->setbloc('commentaire', $Camp->commentaire_shoot_acq, 92);
			$document->setValue('commentaire', str_replace("\n","<w:br/>",htmlspecialchars($Camp->commentaire_shoot_acq)), 92);
			/** BEGIN **/
			$Camp->numSource = ($Camp->numSource == 0) ? '' : 'FID '.$Camp->numSource.' ';
			$document->setValue('idcampgsource', htmlspecialchars($Camp->numSource), 40);
			$Camp->porteur_source = ($Camp->porteur_source == '') ? '' : '('.$Camp->porteur_source.')';
			$document->setValue('porteurSource', htmlspecialchars($Camp->porteur_source), 40);
			$document->setValue('etape_envoi', str_replace("\n","<w:br/>",htmlspecialchars($Camp->etape_envoi)), 92);
			/** END **/

			$document->setValue('model', htmlspecialchars($Camp->model_shoot_acq.' Jours'));

			//$document->setValue('pr1', $subcs[0]->Product->theoPricePros);
			//$document->setValue('vg1', $subcs[0]->Product->theoPriceVg);
			//$document->setValue('vp1', $subcs[0]->Product->theoPriceVp);
			//$document->setValue('ct1', $subcs[0]->Product->theoPriceCt);

			$document->setValue('gp1', htmlspecialchars($g1));
			$document->setValue('gp2', htmlspecialchars($g2));
			$document->setValue('gp3', htmlspecialchars($g3));
			$document->setValue('gp4', htmlspecialchars($g4));

			$document->setValue('s1', htmlspecialchars($s1));
			$document->setValue('s2', htmlspecialchars($s2));
			$document->setValue('s3', htmlspecialchars($s3));
			$document->setValue('s4', htmlspecialchars($s4));

			$document->setValue('d1', htmlspecialchars($d1));
			$document->setValue('d2', htmlspecialchars($d2));
			$document->setValue('d3', htmlspecialchars($d3));
			$document->setValue('d4', htmlspecialchars($d4));

			$document->setValue('j1', htmlspecialchars($j1));
			$document->setValue('j2', htmlspecialchars($j2));
			$document->setValue('j3', htmlspecialchars($j3));
			$document->setValue('j4', htmlspecialchars($j4));

			$document->setValue('c1', htmlspecialchars($c1));
			$document->setValue('c2', htmlspecialchars($c2));
			$document->setValue('c3', htmlspecialchars($c3));
			$document->setValue('c4', htmlspecialchars($c4));


			// *******************   2 Comptes   *********************
			if($nbcpt == 2){
				$document->setValue('gp1f', htmlspecialchars($g1f));
				$document->setValue('gp2f', htmlspecialchars($g2f));
				$document->setValue('gp3f', htmlspecialchars($g3f));
				$document->setValue('gp4f', htmlspecialchars($g4f));

				$document->setValue('s1f', htmlspecialchars($s1f));
				$document->setValue('s2f', htmlspecialchars($s2f));
				$document->setValue('s3f', htmlspecialchars($s3f));
				$document->setValue('s4f', htmlspecialchars($s4f));

				$document->setValue('d1f', htmlspecialchars($d1f));
				$document->setValue('d2f', htmlspecialchars($d2f));
				$document->setValue('d3f', htmlspecialchars($d3f));
				$document->setValue('d4f', htmlspecialchars($d4f));

				$document->setValue('j1f', htmlspecialchars($j1f));
				$document->setValue('j2f', htmlspecialchars($j2f));
				$document->setValue('j3f', htmlspecialchars($j3f));
				$document->setValue('j4f', htmlspecialchars($j4f));

				$document->setValue('c1f', htmlspecialchars($c1f));
				$document->setValue('c2f', htmlspecialchars($c2f));
				$document->setValue('c3f', htmlspecialchars($c3f));
				$document->setValue('c4f', htmlspecialchars($c4f));

				$document->setbloc('commentairef', htmlspecialchars($Camp->commentaire_shoot_fid), 92);
			}
			// *******************     FIN     *********************


			// ****** Set values template produit 2 * /

			if($nbprods == 2){

				$desc2 = $subcs[1]->Product->titleStat;
				//$document->setbloc('desc2', $desc2, 40);
				$document->setValue('desc2', str_replace("\n","<w:br/>",htmlspecialchars($desc2)), 40);
				$document->setValue('nomp2', htmlspecialchars($desc2));
				//$document->setValue('desc2', str_replace("\n","<w:br/>",$desc2));
				$descp2 = $subcs[1]->Product->description_marketing;
				//	$document->setbloc('descriptionp2', $descp2, 92);
				$document->setValue('descriptionp2', str_replace("\n","<w:br/>",htmlspecialchars($descp2)), 92);
				//	$document->setbloc('nom2', $subcs[1]->Product->description, 92);
				$document->setValue('nom2', str_replace("\n","<w:br/>",htmlspecialchars($subcs[1]->Product->description)), 92);
				$document->setbloc('type2', htmlspecialchars($type2), 92);
				//	$document->setbloc('it2', $subcs[1]->Product->particularite_it, 92);
				$document->setValue('it2', str_replace("\n","<w:br/>",htmlspecialchars($subcs[1]->Product->particularite_it)), 92);
				$document->setbloc('kdo2', htmlspecialchars($subcs[1]->Product->kdo), 92);

				//$document->setbloc('pagep2', $subcs[1]->Product->variable_page, 40);
				$document->setValue('pagep2', str_replace("\n","<w:br/>",htmlspecialchars($subcs[1]->Product->variable_page)), 92);
				//$document->setbloc('mailp2', $subcs[1]->Product->variable_mail, 40);
				$document->setValue('mailp2', str_replace("\n","<w:br/>",htmlspecialchars($subcs[1]->Product->variable_mail)), 40);
				//	$document->setbloc('numero2', $subcs[1]->Product->num_c, 40);
				$document->setbloc('numero2', htmlspecialchars($subcs[1]->Product->num_c), 40);

				//	$document->setValue('pr2', $subcs[1]->Product->theoPricePros);
				//	$document->setValue('vg2', $subcs[1]->Product->theoPriceVg);
				//	$document->setValue('vp2', $subcs[1]->Product->theoPriceVp);
				//	$document->setValue('ct2', $subcs[1]->Product->theoPriceCt);
				$document->setValue('date2', str_replace("\n","<w:br/>",htmlspecialchars($subcs[1]->Product->date_a)), 40);
			}

			// ******************************************** Save File * //
			$temp_file = tempnam(sys_get_temp_dir(), 'PHPWord');
			$document->save($temp_file);
			$porteurnom = str_replace(" ","_",$porteur);
			// regardless of what it's named on the server
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream; charset=utf-8');
			header("Content-Disposition: attachment; filename=CDC-".$Camp->id."-".$porteurnom."-(".$Camp->ref.").docx");
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			header('Content-Length: ' . filesize($temp_file));
			readfile($temp_file); // or echo file_get_contents($temp_file);
			unlink($temp_file);  // remove temp file
			die;
		}

		// ****** Si aucun produit n'est encore créé * /
		else
			Yii::app()->user->setFlash( "error", Yii::t( 'common', "Vous devez créer d'abord créer les produits de cette Campagne !" ) );

		$this->redirect( array('product/campaign') );
	}

	// Fin Generation CDC


// Fin Generation CDC ========== MOUJAB ABDELILAH
// ====================================================================================================
//////////////////////////Code
////////code html pour macro
	public function actionGetHtmlCode($fid_border_color, $nomenclature,$porteur,$prod){


		//variables
		$html = '';
		$text = '';
		$error = '';
		$allparatext='';
		$header = array();
		$afterObject ='';
		$footertext ='';
		$inc= "[EMV INCLUDE]";
		$finc= "[EMV /INCLUDE]";


		$headerHtml ='';
		$afterHeaderHtml='';
		$footerHtml ='';
		// la couleur du bordure
		//la nomenclature
		if ($prod =="product_1") {
			# code...
			$imgHeader ='header';
			$imgFooter ='footer';
		}elseif ($prod =="product_2"){
			$imgHeader ='headerct';
			$imgFooter ='footerct';

		}


		$allparahtml ='';
		$signature = "<img src='".$inc.$GLOBALS['porteurUrl'][$porteur]['emvImage'].$finc.$GLOBALS['porteurUrl'][$porteur]['imgname']."' alt='".$GLOBALS['porteurUrl'][$porteur]['nom']."' />";

		//text header du fichier text
		$header['uLabel'] = "\nURL Page :\n";
		$header['uLabel'] .= '----------'."\n";
		$header['fLabel']= "\n\n\nURL FAQ :\n";
		$header['fLabel'] .= '----------'."\n";
		$header['lr'] = "\n\n\n\n\n";

		///text apres l'objet
		$afterObject .= '-------------------------------------------------------------------------------------------';
		$afterObject .= "\n";
		$afterObject .= '-------------------------------------------------------------------------------------------';
		$afterObject .= "\n\n\n\n";
		$afterObject .= "[EMV TEXTPART]\n\n\t";
		if (isset($GLOBALS['porteurUrl'][$porteur]['emvsendertxt']) && $GLOBALS['porteurUrl'][$porteur]['emvsendertxt'] !=="") {
			$afterObject .= $inc.$GLOBALS['porteurUrl'][$porteur]['emvsendertxt'].$finc;
		}
		if (isset($GLOBALS['porteurUrl'][$porteur]['emvsendertxt2']) && $GLOBALS['porteurUrl'][$porteur]['emvsendertxt2'] !=="") {
			$afterObject .= "\n\n\t".$inc.$GLOBALS['porteurUrl'][$porteur]['emvsendertxt2'].$finc;
		}
		if (isset($GLOBALS['porteurUrl'][$porteur]['emvsendertxt3']) && $GLOBALS['porteurUrl'][$porteur]['emvsendertxt3'] !=="") {
			$afterObject .= "\n\n\t".$inc.$GLOBALS['porteurUrl'][$porteur]['emvsendertxt3'].$finc;
		}



		//text footer partie text
		$footertext = $GLOBALS['porteurUrl'][$porteur]['ptext'];
		if (isset($GLOBALS['porteurUrl'][$porteur]['ratioText']) && $GLOBALS['porteurUrl'][$porteur]['ratioText'] !=='') {
			$footertext .= "\t[EMV INCLUDE]".$GLOBALS['porteurUrl'][$porteur]['ratioText']."[EMV /INCLUDE]\n \n ";
		}
		if (isset($GLOBALS['porteurUrl'][$porteur]['emvDesFaqtxt']) && $GLOBALS['porteurUrl'][$porteur]['emvDesFaqtxt'] !=='') {
			$footertext .= "\t[EMV INCLUDE]".$GLOBALS['porteurUrl'][$porteur]['emvDesFaqtxt']."[EMV /INCLUDE]\n \n ";
		}
		if (isset($GLOBALS['porteurUrl'][$porteur]['emvDesFaqtxt2']) && $GLOBALS['porteurUrl'][$porteur]['emvDesFaqtxt2'] !=='') {
			$footertext .= "\t[EMV INCLUDE]".$GLOBALS['porteurUrl'][$porteur]['emvDesFaqtxt2']."[EMV /INCLUDE]\n \n ";
		}		
		if (isset($GLOBALS['porteurUrl'][$porteur]['emvDesFaqtxt3']) && $GLOBALS['porteurUrl'][$porteur]['emvDesFaqtxt3'] !=='') {
			$footertext .= "\t[EMV INCLUDE]".$GLOBALS['porteurUrl'][$porteur]['emvDesFaqtxt3']."[EMV /INCLUDE]\n \n ";
		}
		$footertext .= "\n[EMV HTMLPART]\n\n\n";



		$headerHtml .= "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN'";
		$headerHtml .= "\n\n'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>";
		$headerHtml .= "\n\n<html xmlns='http://www.w3.org/1999/xhtml'>";
		$headerHtml .= "\n\n\t<head>";
		$headerHtml .= "\n\t\t<meta http-equiv='Content-Type' content='text/html; charset=ISO-8859-1' />";
		$headerHtml .= "\n\t\t<title>\n";


		// <!-- la partie apres le titre de la partie html -->
		$afterHeaderHtml .= "\t\t</title>\n";
		$afterHeaderHtml .=   "\t</head>  \n  \n";
		$afterHeaderHtml .=   "<body> \n  \n";
		$afterHeaderHtml .=   "<table style='width: 456px; margin-right: auto;margin-left: auto; border: 0px solid white; border-style: none; border-spacing: 0px; padding: 0px; font-family: Tahoma, Geneva, sans-serif;'>  \n  \n";
		if (isset($GLOBALS['porteurUrl'][$porteur]['emvSenderhtml']) &&  $GLOBALS['porteurUrl'][$porteur]['emvSenderhtml'] !='') {
			$afterHeaderHtml .=   "<tr> <td style='padding:0px; text-align: center;'>[EMV INCLUDE]".$GLOBALS['porteurUrl'][$porteur]['emvSenderhtml']."[EMV /INCLUDE]</td> </tr>\n  \n";
		}
		if (isset($GLOBALS['porteurUrl'][$porteur]['emvSenderhtml2']) &&  $GLOBALS['porteurUrl'][$porteur]['emvSenderhtml2'] !='') {
			$afterHeaderHtml .=   "<tr> <td style='padding:0px; text-align: center;'>[EMV INCLUDE]".$GLOBALS['porteurUrl'][$porteur]['emvSenderhtml2']."[EMV /INCLUDE]</td> </tr>\n  \n";
		}
		if (isset($GLOBALS['porteurUrl'][$porteur]['emvSenderhtml3']) &&  $GLOBALS['porteurUrl'][$porteur]['emvSenderhtml3'] !='') {
			$afterHeaderHtml .=   "<tr> <td style='padding:0px; text-align: center;'>[EMV INCLUDE]".$GLOBALS['porteurUrl'][$porteur]['emvSenderhtml3']."[EMV /INCLUDE]</td> </tr>\n  \n";
		}
		if (isset($GLOBALS['porteurUrl'][$porteur]['emvSenderhtml4']) &&  $GLOBALS['porteurUrl'][$porteur]['emvSenderhtml4'] !='') {
			$afterHeaderHtml .=   "<tr> <td style='padding:0px; text-align: center;'>[EMV INCLUDE]".$GLOBALS['porteurUrl'][$porteur]['emvSenderhtml4']."[EMV /INCLUDE]</td> </tr>\n  \n";
		}
		$afterHeaderHtml .=   "<tr> <td style='padding:0px; text-align: center; height: 10px; width: 456px;'>&nbsp;</td> </tr>\n  \n";
		$afterHeaderHtml .=   "<tr>\n";
		$afterHeaderHtml .=     "\t<td style='padding:0px; width: 456px; height: 186px; text-align: center; border-left: 8px solid "  .$fid_border_color.  "; border-right: 8px solid "  .$fid_border_color.  "; border-top: 0px solid "  .$fid_border_color.  ";'>  \n";
		$afterHeaderHtml .=     "\t  \t<table cellpadding=\"0\" cellspacing=\"0\"  style=\"border-collapse:collapse;\"> \n";
		$afterHeaderHtml .=     "\t  \t<tr>\n";
		$afterHeaderHtml .=     "\t  \t<td><img src='[EMV INCLUDE]".$GLOBALS['porteurUrl'][$porteur]['emvImage']."[EMV /INCLUDE]".$nomenclature."_images/".$imgHeader."_A.jpg' alt='".$GLOBALS['porteurUrl'][$porteur]['nom']."' style='border: 0px solid white; border-style: none; ' /></td>\n";
		$afterHeaderHtml .=     "\t  \t<td><img src='[EMV INCLUDE]".$GLOBALS['porteurUrl'][$porteur]['emvImage']."[EMV /INCLUDE]".$nomenclature."_images/".$imgHeader."_B.jpg' alt='".$GLOBALS['porteurUrl'][$porteur]['nom']."' style='border: 0px solid white; border-style: none; ' /></td>\n";
		$afterHeaderHtml .=     "\t  \t<td><img src='[EMV INCLUDE]".$GLOBALS['porteurUrl'][$porteur]['emvImage']."[EMV /INCLUDE]".$nomenclature."_images/".$imgHeader."_C.jpg' alt='".$GLOBALS['porteurUrl'][$porteur]['nom']."' style='border: 0px solid white; border-style: none; ' /></td>\n";
		$afterHeaderHtml .=     "\t  \t<td><img src='[EMV INCLUDE]".$GLOBALS['porteurUrl'][$porteur]['emvImage']."[EMV /INCLUDE]".$nomenclature."_images/".$imgHeader."_D.jpg' alt='".$GLOBALS['porteurUrl'][$porteur]['nom']."' style='border: 0px solid white; border-style: none; ' /></td>\n";
		$afterHeaderHtml .=     "\t  \t<td><img src='[EMV INCLUDE]".$GLOBALS['porteurUrl'][$porteur]['emvImage']."[EMV /INCLUDE]".$nomenclature."_images/".$imgHeader."_E.jpg' alt='".$GLOBALS['porteurUrl'][$porteur]['nom']."' style='border: 0px solid white; border-style: none; ' /></td>\n";
		$afterHeaderHtml .=     "\t  \t</tr>\n";
		$afterHeaderHtml .=     "\t  \t</table>\n";

		$afterHeaderHtml .=    "\t </td>\n";
		$afterHeaderHtml .=   "</tr>\n  \n";
		$afterHeaderHtml .=   "<tr>\n  \n";
		$afterHeaderHtml .=     "\t<td style='padding:0px; text-align: center; width: 456px; border-left: 8px solid "  .$fid_border_color.  "; border-right: 8px solid "  .$fid_border_color.  ";'>\n  \n";
		$afterHeaderHtml .=     "\t  \t<table style='border: 0px solid white; border-style: none; border-spacing: 0px; padding: 0px; width: 580px; font-family: Tahoma, Geneva, sans-serif;'>\n  \n";
		$afterHeaderHtml .=     "\t  \t<tr>\n  \n" ;
		$afterHeaderHtml .=     "\t  \t  \t<td style='padding:0px; width: 70px;'>&nbsp;</td> \n  \n";
		$afterHeaderHtml .=    "\t  \t  \t <td style='padding:0px; width: 540px; text-align: left; font-size: 18px; font-family: Tahoma, Geneva, sans-serif;'>  \n  \n";
		$afterHeaderHtml .=     "\t  \t  \t<br />\n  \n  \n" ;




		// <!-- foooter de la partie html -->
		$footerHtml .= "\t\t\t<br/>  \n \n";
		$footerHtml .= "\t\t\t<div style='font-size: 12px; margin: auto; text-align: center;'>\n" ;
		$footerHtml .= $GLOBALS['porteurUrl'][$porteur]['phtml'];
		$footerHtml .= "\t\t\t</div>\n \n \n" ;
		$footerHtml .= "\t \t \t </td>\n \n" ;
		$footerHtml .= "\t \t \t <td style='padding:0px; width: 68px'>&nbsp;</td>\n \n" ;
		$footerHtml .= "\t \t</tr>\n" ;
		$footerHtml .= "\t \t</table>\n" ;
		$footerHtml .= "\t</td>\n" ;
		$footerHtml .= "</tr>\n \n" ;
		$footerHtml .= "<tr>\n \n" ;
		$footerHtml .= "\t<td style='padding:0px; width: 600px; height: 50px; text-align: center; border-left: 8px solid " .$fid_border_color. "; border-right: 8px solid " .$fid_border_color. "; border-bottom: 0px solid " .$fid_border_color. "; line-height: 0px;'>\n" ;


		$footerHtml .=  "\t \t<table cellpadding=\"0\" cellspacing=\"0\"  style=\"border-collapse:collapse;\"> \n";
		$footerHtml .=  "\t \t<tr> \n";

		$footerHtml .=  "\t \t<td><img src='[EMV INCLUDE]".$GLOBALS['porteurUrl'][$porteur]['emvImage']."[EMV /INCLUDE]" .$nomenclature. "_images/" .$imgFooter. "_A.jpg' alt='".$GLOBALS['porteurUrl'][$porteur]['nom']."' /> </td>\n";
		$footerHtml .=  "\t \t<td><img src='[EMV INCLUDE]".$GLOBALS['porteurUrl'][$porteur]['emvImage']."[EMV /INCLUDE]" .$nomenclature. "_images/" .$imgFooter. "_B.jpg' alt='".$GLOBALS['porteurUrl'][$porteur]['nom']."' /> </td>\n";
		$footerHtml .=  "\t \t<td><img src='[EMV INCLUDE]".$GLOBALS['porteurUrl'][$porteur]['emvImage']."[EMV /INCLUDE]" .$nomenclature. "_images/" .$imgFooter. "_C.jpg' alt='".$GLOBALS['porteurUrl'][$porteur]['nom']."' /></td> \n";
		$footerHtml .=  "\t \t<td><img src='[EMV INCLUDE]".$GLOBALS['porteurUrl'][$porteur]['emvImage']."[EMV /INCLUDE]" .$nomenclature. "_images/" .$imgFooter. "_D.jpg' alt='".$GLOBALS['porteurUrl'][$porteur]['nom']."' /> </td>\n";
		$footerHtml .=  "\t \t<td><img src='[EMV INCLUDE]".$GLOBALS['porteurUrl'][$porteur]['emvImage']."[EMV /INCLUDE]" .$nomenclature. "_images/" .$imgFooter. "_E.jpg' alt='".$GLOBALS['porteurUrl'][$porteur]['nom']."' /></td> \n";
		$footerHtml .=  "\t \t</tr> \n";
		$footerHtml .=  "\t \t</table> \n";




		$footerHtml .=  "\t</td> \n \n";
		$footerHtml .=  "</tr>\n \n";
		$footerHtml .= "<tr> <td style='padding:0px; width: 456px; height: 7px;'>&nbsp;</td> </tr>\n \n";
		
		if (isset($GLOBALS['porteurUrl'][$porteur]['ratioHtml'])  && $GLOBALS['porteurUrl'][$porteur]['ratioHtml'] !='') {
			$footerHtml .=  "<tr> \n";
			$footerHtml .=  "\t <td style='padding:0px; text-align: center;'> \n";
			$footerHtml .=  "\t \t [EMV INCLUDE]".$GLOBALS['porteurUrl'][$porteur]['ratioHtml']."[EMV /INCLUDE]\n";
			$footerHtml .=  "\t </td>\n";
			$footerHtml .=  "</tr> \n \n";
		} else{
			$error .= "le champ 'ratioHtml' n'existe pas dans la configuration du porteur<br>";
		}	
		if (isset($GLOBALS['porteurUrl'][$porteur]['emvDesFaqhtml'])  && $GLOBALS['porteurUrl'][$porteur]['emvDesFaqhtml'] !='') {
			$footerHtml .=  "<tr> \n";
			$footerHtml .=  "\t <td style='padding:0px; height: 2px; text-align: center; font-size: 9pt; font-family: Verdana; color:".$fid_border_color.";'> \n";
			$footerHtml .=  "\t \t [EMV INCLUDE]".$GLOBALS['porteurUrl'][$porteur]['emvDesFaqhtml']."[EMV /INCLUDE]\n";
			$footerHtml .=  "\t </td>\n";
			$footerHtml .=  "</tr> \n \n";
		} else{
			$error .= "le champ 'emvDesFaqhtml' n'existe pas dans la configuration du porteur<br>";
		}
		if (array_key_exists('emvDesFaqhtml2',$GLOBALS['porteurUrl'][$porteur])  && $GLOBALS['porteurUrl'][$porteur]['emvDesFaqhtml2'] !='') {
			$footerHtml .=  "<tr> \n";
			$footerHtml .=  "\t <td style='padding:0px; height: 2px; text-align: center; font-size: 9pt; font-family: Verdana; color:".$fid_border_color.";'> \n";
			$footerHtml .=  "\t \t [EMV INCLUDE]".$GLOBALS['porteurUrl'][$porteur]['emvDesFaqhtml2']."[EMV /INCLUDE]\n";
			$footerHtml .=  "\t </td>\n";
			$footerHtml .=  "</tr> \n \n";
		} else {
			$error .= "le champ 'emvDesFaqhtml2' n'existe pas dans la configuration du porteur<br>";
		}
		$footerHtml .=  "</table> \n \n";
		$footerHtml .=  "</body> \n \n";
		$footerHtml .=  "</html> \n \n";


		$return = array();
		$return['html'] =  $html;
		$return['text'] =  $text;
		$return['allparatext'] =  $allparatext;
		$return['header'] =  $header;
		$return['afterObject'] =  $afterObject;
		$return['footertext'] =  $footertext;


		$return['headerHtml'] =  $headerHtml;
		$return['afterHeaderHtml'] =  $afterHeaderHtml;
		$return['footerHtml'] =  $footerHtml;
		$return['imgHeader'] =  $imgHeader;
		$return['imgFooter'] =  $imgFooter;
		$return['allparahtml'] =  $allparahtml;
		$return['imgsignature'] =  $signature;
		$return['error'] =  $error;
		return $return;
	}
	//--------------------------------------

	public function actionMacro(){



		// echo "<pre>"; print_r($_FILES); print_r($_POST); die;
		Yii::import( 'ext.MacroTabs', true );
		$Router = new \Business\RouterEMV('search');
		if(Yii::app()->request->getParam('submitForm') !== NULL){
			$isacq = '';
			//Récupration des variables

			$porteur = Yii::app()->session['porteur'];
			$fid_acq = Yii::app()->request->getParam('fid_acq');
			$fid_site = Yii::app()->request->getParam('fid_site');
			//test pour les sites fid et acq
			if(isset($porteur) && !empty($porteur) && isset($fid_site) && !empty($fid_site)){
				$porteur = explode('_',$porteur);
				$porteur = $fid_site.'_'.$porteur[1];

			}
			if ($porteur == 'fr_rucker' || $porteur == 'en_aasha') {
				if ($fid_acq == 'acq') {
					$isacq = '_acq';
				}
			}
			$porteur.=$isacq;


			//Récupration des variables
			$fid_name = Yii::app()->request->getParam('fid_name');
			$fid_border_color = Yii::app()->request->getParam('fid_border_color');
			$fid_product = Yii::app()->request->getParam('fid_product');
			$fid_type = Yii::app()->request->getParam('fid_type');
			$fid_asile_inter = Yii::app()->request->getParam('fid_asile_inter');
			$fid_gp = Yii::app()->request->getParam('fid_gp');
			$fid_s = Yii::app()->request->getParam('fid_s');
			$fid_de = Yii::app()->request->getParam('fid_de');
			$fid_sd = Yii::app()->request->getParam('fid_sd');
			$fid_dest = Yii::app()->request->getParam('fid_dest');
			$fid_bs = Yii::app()->request->getParam('fid_bs');

			//importation des extension
			Yii::import( 'ext.PHPExcel' );
			Yii::import('application.vendors.PHPExcel',true);

			// si le porteur est defini dans la config


			if (!array_key_exists($porteur,$GLOBALS['porteurUrl'])) {
				Yii::app()->user->setFlash( "error", "le porteur  $porteur n'existe pas dans le fichier configuration." );
				$this->render( '//product/macro', array('Router' => $Router, 'porteur' => $_SESSION['porteur']));
				die();
			}
			$macroErrors = "";
			$required = "";
			$ct ='';
			if  ($fid_product =="product_2" && $fid_asile_inter !=='asile'){
				$ct = 'ct';

			}




			//tester si un des n'existe pas la config : generer errur avec le nom du champs
			if (!array_key_exists('url',$GLOBALS['porteurUrl'][$porteur])) {
				$macroErrors .= "le champ 'url' n'existe pas dans la configuration du porteur<br>";
			}
			if (!array_key_exists('nom',$GLOBALS['porteurUrl'][$porteur])) {
				$macroErrors .= "le champ 'nom' n'existe pas dans la configuration du porteur<br>";
			}
			if (!array_key_exists('folder',$GLOBALS['porteurUrl'][$porteur])) {
				$macroErrors .= "le champ 'folder' n'existe pas dans la configuration du porteur<br>";
			}
			if (!array_key_exists('emvsendertxt',$GLOBALS['porteurUrl'][$porteur])) {
				$macroErrors .= "le champ 'emvsendertxt' n'existe pas dans la configuration du porteur<br>";
			}
			if (!array_key_exists('emvsendertxt2',$GLOBALS['porteurUrl'][$porteur])) {
				$macroErrors .= "le champ 'emvsendertxt2' n'existe pas dans la configuration du porteur<br>";
			}
			if (!array_key_exists('emvSenderhtml',$GLOBALS['porteurUrl'][$porteur])) {
				$macroErrors .= "le champ 'emvSenderhtml' n'existe pas dans la configuration du porteur<br>";
			}
			if (!array_key_exists('emvSenderhtml2',$GLOBALS['porteurUrl'][$porteur])) {
				$macroErrors .= "le champ 'emvSenderhtml2' n'existe pas dans la configuration du porteur<br>";
			}
			if (!array_key_exists('emvImage',$GLOBALS['porteurUrl'][$porteur])) {
				$macroErrors .= "le champ 'emvImage' n'existe pas dans la configuration du porteur<br>";
			}
			if (!array_key_exists('emvDesFaqtxt',$GLOBALS['porteurUrl'][$porteur])) {
				$macroErrors .= "le champ 'emvDesFaqtxt' n'existe pas dans la configuration du porteur<br>";
			}
			if (!array_key_exists('emvDesFaqtxt2',$GLOBALS['porteurUrl'][$porteur])) {
				$macroErrors .= "le champ 'emvDesFaqtxt2' n'existe pas dans la configuration du porteur<br>";
			}
			if (!array_key_exists('emvDesFaqhtml',$GLOBALS['porteurUrl'][$porteur])) {
				$macroErrors .= "le champ 'emvDesFaqhtml' n'existe pas dans la configuration du porteur<br>";
			}
			if (!array_key_exists('emvDesFaqhtml2',$GLOBALS['porteurUrl'][$porteur])) {
				$macroErrors .= "le champ 'emvDesFaqhtml2' n'existe pas dans la configuration du porteur<br>";
			}
			if (!array_key_exists('emvsignature',$GLOBALS['porteurUrl'][$porteur])) {
				$macroErrors .= "le champ 'emvsignature' n'existe pas dans la configuration du porteur<br>";
			}
			if (!array_key_exists('imgname',$GLOBALS['porteurUrl'][$porteur])) {
				$macroErrors .= "le champ 'imgname' n'existe pas dans la configuration du porteur<br>";
			}
			if (!array_key_exists('emvchq',$GLOBALS['porteurUrl'][$porteur])) {
				$macroErrors .= "le champ 'emvchq' n'existe pas dans la configuration du porteur<br>";
			}
			if (!array_key_exists('emvchqhtml',$GLOBALS['porteurUrl'][$porteur])) {
				$macroErrors .= "le champ 'emvchqhtml' n'existe pas dans la configuration du porteur<br>";
			}
			if (!array_key_exists('phtml',$GLOBALS['porteurUrl'][$porteur])) {
				$macroErrors .= "le champ 'phtml' n'existe pas dans la configuration du porteur<br>";
			}
			if (!array_key_exists('ptext',$GLOBALS['porteurUrl'][$porteur])) {
				$macroErrors .= "le champ 'ptext' n'existe pas dans la configuration du porteur<br>";
			}

			//si un des champs n'existe pas dans la config generer une erreur
			if ($macroErrors !=="") {
				Yii::app()->user->setFlash( "error", $macroErrors );
				$this->render( '//product/macro', array('Router' => $Router, 'porteur' => $_SESSION['porteur']));
				die();
			}

			if ($GLOBALS['porteurUrl'][$porteur]['url'] =='') {
				$required .= "le champ 'url' n'est pas rempli dans la config du porteur <br>";
			}
			if ($GLOBALS['porteurUrl'][$porteur]['url'] =='') {
				$required .= "le champ 'nom' n'est pas rempli dans la config du porteur  <br>";
			}
			if ($GLOBALS['porteurUrl'][$porteur]['url'] =='') {
				$required .= "le champ 'folder' n'est pas rempli dans la config du porteur  <br>";
			}

			if ($required !=="") {
				Yii::app()->user->setFlash( "error", $required );
				$this->render( '//product/macro', array('Router' => $Router, 'porteur' => $_SESSION['porteur']));
				die();
			}
			//appelle fonction vue html
			$htmlData = $this ->actionGetHtmlCode($fid_border_color, $fid_name,$porteur,$fid_product);
			$macroErrors = $htmlData['error'];
			// Lecture des fichiers uploader
			$excelfiles = CUploadedFile::getInstancesByName('macroFiles');
			foreach($excelfiles as $macroFile => $pic){
				if (!$pic->saveAs(dirname(__FILE__).'/../../views/macro/macrosdocs/'.$pic->name)) {
					echo "Probléme lors de l'enregistrement du fichier";exit;
				}
				$filename = dirname(__FILE__).'/../../views/macro/macrosdocs/'.$pic->name;
				chmod($filename, 0777);

				$fileNameZip = explode('.', $pic->name);
				$filenamezip = dirname(__FILE__).'/../../views/macro/macrosdocszipped/'.$fileNameZip[0].'.zip';
				//chmod($filenamezip, 0777);

				//definition du path du ficier avec le nom du fichier telechargé

				//le fichier text avec extension TXT

				if (!copy(dirname(__FILE__).'/../../views/macro/macrosdocs/'.$pic->name, $filenamezip)){
					die("La copie $file du fichier a échoué...\n");
				}

				//Extraction du ZIP
				$zip = new ZipArchive;
				if ($zip->open($filenamezip) === TRUE){
					$zip->extractTo(dirname(__FILE__).'/../../views/macro/macrosdocszipped/');
					chmod(dirname(__FILE__).'/../../views/macro/macrosdocszipped/[Content_Types].xml',0777);
					$zip->close();
				} else {
					die("Probléme lors de l'extraction du fichier.");
				}

				//Traitement de la Macro: Création de l'url
				$macro = new \Business\Macro;
				$macroURL = $macro->getUrlMacro($pic->name, $porteur, $fid_name, $fid_product, $fid_type, $fid_asile_inter, $fid_gp, $fid_s, $fid_de, $fid_sd, $fid_dest, $fid_bs);
				$FAQURL = $macro->getUrlFAQ($porteur, $fid_name, $fid_product, $fid_type,$fid_asile_inter,$pic->name);

				// echo $macroURL;
				// echo "<br><br>------------------------<br>";
				// echo "<br>".$FAQURL."<br>";
				// die();
				////creer dossier avec nom du porteur
				$genFileTxt = '';
				$genFileHtml = '';
				$eVision='';
				$serveur='';
				$folder = dirname(__FILE__).'/../../views/generated/'.$porteur;
				$fileNameZip[0] = strtolower($fileNameZip[0]);
				$eVision=$folder.'/EmailVision/';
				// die($eVision);

				//creation du dossier du porteur
				if (!file_exists($folder)) {
					if (mkdir($folder)) {
						chmod($folder, 0777);
					}
				}

				//creation du dosiier du FID
				$folder = $folder.'/'.$fid_name.$ct;
				if (!file_exists($folder)) {
					if (mkdir($folder)) {
						chmod($folder, 0777);
					}
				}

				// creation du dossier serveur
				$serveur = $folder.'/Serveur';
				if (!file_exists($serveur)) {
					if (mkdir($serveur)) {
						chmod($serveur, 0777);
					}
				}
				$serveur .='/';
				if (!file_exists($serveur.$fileNameZip[0])) {
					if (mkdir($serveur.$fileNameZip[0])) {
						$serveur=$serveur.$fileNameZip[0].'/';
						chmod($serveur, 0777);
					}
				}
				if (file_exists($serveur.$fileNameZip[0])) {
					$serveur=$serveur.$fileNameZip[0].'/';
				}
				$genFileHtml = $serveur.'html.html';
				//die($genFileHtml);

				// creation du dosssier email vision
				$eVision = $folder.'/EmailVision';
				if (!file_exists($eVision)) {
					if (mkdir($eVision)) {
						chmod($eVision, 0777);
					}
				}

				$eVision .='/';
				if (file_exists($eVision)) {
					$genFileTxt = $eVision.strtolower($fileNameZip[0]).'.txt';
				}

				//Lecture du fichier
				$striped_content = '';
				$content = '';
				$emvinc = "[EMV INCLUDE]";
				$finemvinc = "[EMV /INCLUDE]";

				if(!$filenamezip || !file_exists($filenamezip)) return false;
				$zip = zip_open($filenamezip);
				if (!$zip || is_numeric($zip)) return false;
				while ($zip_entry = zip_read($zip)) {
					if (zip_entry_open($zip, $zip_entry) == FALSE) continue;
					if (zip_entry_name($zip_entry) != "word/document.xml"){ continue;}
					$content .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
					zip_entry_close($zip_entry);
				}
				//traitement du fichier xml
				$tpl =  simplexml_load_file(dirname(__FILE__).'/../../views/macro/macrosdocszipped/'.'word/document.xml');
				$namespaces = $tpl->getNamespaces(true);
				$array_xml = $tpl->children($namespaces["w"]);
				$body = $array_xml->body;
				$paragraphes = $body->p;



				$text='';
				$html='';
				$allparatext='';
				$allparahtml = '';
				$header ='';
				$isobjet = 0;
				//$islink = 0;
				$header .= 'URL Page :'."\n";
				$header .= '----------'."\n";
				$header .= $macroURL."\n\n";
				$header .= 'URL FAQ :'."\n";
				$header .= '----------'."\n";
				$header .= $FAQURL."\n\n\n\n";


				$textObjet=[];
				$emvFName="[EMV FIELD]FIRSTNAME[EMV /FIELD]";
				//a voir comme variable
				$emvLink="\t\t: [EMV LINK]1[EMV /LINK]";
				// $text .= $Objet."\n\n";
				//affichage du contenu et l'enregistrement dans le fichier text
				$counter =0;
				$paratext ='';
				$parastext ='';
				$paratexthtml ='';
				$paracounter =0;
				$countpara=0;
				$perso;
				$bcentred = "";
				$ecentred = "";
				foreach($paragraphes as $para){
					$bcentred = "";
					$ecentred = "";
					$words = $para->r;
					$wordp = $para->pPr;
					foreach ($wordp as $wordd) {
						if ($wordd->jc['val']=='center') {
							$bcentred = "<div style='margin: auto; text-align: center;'>";
							$ecentred = "</div>";
						}
					}
					$paratext='';
					$paratexthtml='';
					foreach ($words as $word) {
						if ($word->t !='' && $word->t!=' ') {
							if ($word->rPr) {
								$rPr =$word->rPr;
								$perso ="";
								foreach ($rPr as $rp) {
									if ($rp->u) {
										if ($rp->u['val']=='single') {
											$word->t = "<lien>".$word->t."</lien>";
										}
									}
									if ($rp->color) {
										switch ($rp->color['val']) {
											case '00B050':
													$word->t = "<pn>".$word->t."</pn>";

												break;
											case 'FFC000':
												$word->t = "<pr>".$word->t."</pr>";
												break;
											case 'FF0000':
												$word->t = "<vp>".$word->t."</vp>";
												break;
											case 'C0504D':
												$word->t = "<vs>".$word->t."</vs>";
												break;
											case '0070C0':
												if ($word->t=='[Pavé chèque]' || $word->t=='[Pavé check]' || $word->t=='[Pavé cheque]') {
													$word->t = '[EMV INCLUDE]'.$GLOBALS['porteurUrl'][$porteur]['emvchq'].'[EMV /INCLUDE]';
												}
												break;
											default:
												# code...
												break;
										}
									}
								}
							}
						}

						$paratext .= $word->t;
						$paratexthtml .= $word->t;
						$paratext = str_replace('}0{>','',$paratext);
						$paratexthtml = str_replace('}0{>','',$paratexthtml);

					}
					if ($paratext !='' && $paratext !=' ') {
						$countpara ++;
					}
					if ($paratexthtml !='' &&$paratexthtml !=' ') {
						$paratexthtml = $bcentred.$paratexthtml.$ecentred;
					}
					//traitement de la partie text ****************************************************
					$paratext = preg_replace('@\[(.*)ch(.*)\]@', '[EMV INCLUDE]'.$GLOBALS['porteurUrl'][$porteur]['emvchq'].'[EMV /INCLUDE]', $paratext);
					$paratext = str_replace("</pn><pn>", '', $paratext);
					$paratext = str_replace("</pn> <pn>", '', $paratext);
					$paratext = str_replace("</vp><vp>", '', $paratext);
					$paratext = str_replace("</vp> <vp>", '', $paratext);
					$paratext = str_replace("</pr><pr>", '', $paratext);
					$paratext = str_replace("</pr> <pr>", '', $paratext);
					$paratext = str_replace("</vs><vs>", '', $paratext);
					$paratext = str_replace("</vs> <vs>", '', $paratext);
					$paratext = str_replace("</lien><lien>", '', $paratext);
					if ($porteur == 'in_laetizia' || $porteur =='en_aasha' || $porteur =='en_aasha_acq' || $porteur =='ie_laetizia'  || $porteur =='nl_laetizia' ) {
						$paratext = str_replace("#ddn", "[EMV FIELD]DATEOFBIRTH[EMV /FIELD]", $paratext);
					}
					//ajout de la phrase de magie
					if(isset($GLOBALS['porteurUrl'][$porteur]['magie']) && !empty($GLOBALS['porteurUrl'][$porteur]['magie'])){
						if(strpos($paratext,'signature') !== false){
							$paratext .= "\n\n\t<br><br> \n\n \t".$GLOBALS['porteurUrl'][$porteur]['magie'];
						}
					}
					$paratext = str_replace("#ddn", "[EMV FIELD]DATEOFBIRTH,dd/MM/yyyy,fr[EMV /FIELD]", $paratext);
					$paratext = str_replace("</lien>".$GLOBALS['porteurUrl'][$porteur]['nom']."<lien>", $GLOBALS['porteurUrl'][$porteur]['nom'], $paratext);
					$paratext = preg_replace("@<pr><lien>([^<>]+)</lien></pr>@", "<pr>$1</pr>", $paratext);

					$paratext = str_replace("<pr><lien>([^<>]+)</lien></pr>", "<pr>$1</pr>", $paratext);
					$paratext = preg_replace('@<pn>(\s*)#(\s*)([^\.;,/\s]+)(\s*)/(\s*)([^\.,;/\s]+)(\s*)([^\/]*)</pn>@', "$1$2[EMV IF] (TITLE = \"1\") [EMV THEN]$3[EMV /THEN] [EMV ELSE]$6[EMV /ELSE][EMV /IF]$7$8" , $paratext);
					$paratext = preg_replace('@<pn>(\s*)#([^\.;,\s]+)/([^\.,;\s]+)(\s*)#([^\.;,\s]+)/([^\.,;\s]+)(\s*)</pn>@', "$1[EMV IF] (TITLE = \"1\") [EMV THEN]$2[EMV /THEN] [EMV ELSE]$3[EMV /ELSE] [EMV /IF]$4[EMV IF] (TITLE = \"1\") [EMV THEN]$5[EMV /THEN] [EMV ELSE]$6[EMV /ELSE] [EMV /IF]$7" , $paratext);
					$paratext = preg_replace('@<pn>(\s*)#([^\.;,/\s]+)/([^\.,;/\s]+)(\s*)</pn>@', "$1[EMV IF] (TITLE = \"1\") [EMV THEN]$2[EMV /THEN] [EMV ELSE]$3[EMV /ELSE] [EMV /IF]$4" , $paratext);
					//changement pour prise en compte de lespace
					$paratext = preg_replace('@<pn>(\s*)#([^\.;,]+)/([^\.,;]+)(\s*)</pn>@', "$1[EMV IF] (TITLE = \"1\") [EMV THEN]$2[EMV /THEN] [EMV ELSE]$3[EMV /ELSE] [EMV /IF]$4" , $paratext);
					$paratext = preg_replace('@<pn>(\s*)#([^\.;,/s]+)/([^\.,;/s]+)(\s*)</pn>@', "$1[EMV IF] (TITLE = \"1\") [EMV THEN]$2[EMV /THEN] [EMV ELSE]$3[EMV /ELSE] [EMV /IF]$4" , $paratext);
					$paratext = str_replace("# Prénom", $emvFName, $paratext);
					$paratext = str_replace("#Prénom", $emvFName, $paratext);
					$paratext = str_replace("# prénom", $emvFName, $paratext);
					$paratext = str_replace("#prénom", $emvFName, $paratext);

					//variable msc fem porteur
					if($porteur == 'fr_rucker' || $porteur == 'fr_rucker_acq'|| $porteur == 'de_rucker'){
						$paratext = preg_replace('@<vp>(\s*)#([^\.;,\s]+)(\s*)/(\s*)([^\.,;\s]+)(\s*)#(\s*)([^\.;,\s]+)(\s*)/(\s*)([^\.,;\s]+)(\s*)</vp>@', "$1$2$6$8$12" , $paratext);
						$paratext = preg_replace('@<vp>(\s*)#([^\.;,/\s]+)/([^\.,;/\s]+)(\s*)</vp>@', "$1$2$4" , $paratext);
						$paratext = preg_replace('@<vp>(\s*)#([^\.;,/\s]+)/([^\.,;/\s]+)(\s*)</vp>@', "$1$2$4" , $paratext);
						$paratext = preg_replace('@<vp>(\s*)#([^\.;,/s]+)/([^\.,;/s]+)(\s*)</vp>@', "$1$2$4" , $paratext);
					}else{
						$paratext = preg_replace('@<vp>(\s*)#([^\.;,\s]+)(\s*)/(\s*)([^\.,;\s]+)(\s*)#(\s*)([^\.;,\s]+)(\s*)/(\s*)([^\.,;\s]+)(\s*)</vp>@', "$1$5$6$11$12" , $paratext);
						$paratext = preg_replace('@<vp>(\s*)#([^\.;,/\s]+)/([^\.,;/\s]+)(\s*)</vp>@', "$1$3$4" , $paratext);
						$paratext = preg_replace('@<vp>(\s*)#([^\.;,/\s]+)/([^\.,;/\s]+)(\s*)</vp>@', "$1$3$4" , $paratext);
						$paratext = preg_replace('@<vp>(\s*)#([^\.;,/s]+)/([^\.,;/s]+)(\s*)</vp>@', "$1$3$4" , $paratext);
					}
					//variable site porteur
//					if($porteur == 'es_laetizia' || $porteur == 'es_rmay'){
					$paratext = preg_replace('@<vs>(\s*)#([^\.;,/s]+)/([^\.,;/s]+)(\s*)#([^\.;,/s]+)/([^\.,;/s]+)(\s*)</vs>@', "$1[EMV IF] (SITE = \"ES\") [EMV THEN] $2[EMV /THEN] [EMV ELSE]$3[EMV /ELSE] [EMV /IF]$4[EMV IF] (SITE = \"ES\") [EMV THEN] $5[EMV /THEN] [EMV ELSE]$6[EMV /ELSE] [EMV /IF]$7" , $paratext);
					$paratext = preg_replace('@<vs>(\s*)#([^\.;,/\s]+)/([^\.,;/\s]+)(\s*)</vs>@', "$1[EMV IF] (SITE = \"ES\") [EMV THEN]$2[EMV /THEN] [EMV ELSE]$3[EMV /ELSE] [EMV /IF]$4" , $paratext);
					$paratext = preg_replace('@<vs>(\s*)#([^\.;,/\s]+)/([^\.,;/\s]+)(\s*)</vs>@', "$1[EMV IF] (SITE = \"ES\") [EMV THEN]$2[EMV /THEN] [EMV ELSE]$3[EMV /ELSE] [EMV /IF]$4" , $paratext);
					$paratext = preg_replace('@<vs>(\s*)#([^\.;,/s]+)/([^\.,;/s]+)(\s*)</vs>@', "$1[EMV IF] (SITE = \"ES\") [EMV THEN]$2[EMV /THEN] [EMV ELSE]$3[EMV /ELSE] [EMV /IF]$4" , $paratext);
//					}else{
//						$paratext = preg_replace('@<vs>(\s*)#([^\.;,/s]+)/([^\.,;/s]+)(\s*)#([^\.;,/s]+)/([^\.,;/s]+)(\s*)</vs>@', "$1$3$7" , $paratext);
//						$paratext = preg_replace('@<vs>(\s*)#([^\.;,/\s]+)/([^\.,;/\s]+)(\s*)</vs>@', "$1$3$4" , $paratext);
//						$paratext = preg_replace('@<vs>(\s*)#([^\.;,/\s]+)/([^\.,;/\s]+)(\s*)</vs>@', "$1$3$4" , $paratext);
//						$paratext = preg_replace('@<vs>(\s*)#([^\.;,/s]+)/([^\.,;/s]+)(\s*)</vs>@', "$1$3$4" , $paratext);
//					}

					$paratext = preg_replace('@<pr>(\s*)#([^\.;,/s]+)/([^\.,;/s]+)(\s*)</pr>@', $GLOBALS['porteurUrl'][$porteur]['nom'] , $paratext);
					$paratext = preg_replace('@<lien>(.+)</lien>@', "$1 \n\t: [EMV LINK]1[EMV /LINK]", $paratext);
					$paratext = preg_replace('@<pr>(.*)#(.+)</pr>@', $GLOBALS['porteurUrl'][$porteur]['nom'], $paratext);
					//supprimer phrases aprés signature pour les les porteur ici
					if($porteur =='fr_rmay' || $porteur =='de_rmay' || $porteur =='es_rmay' || $porteur =='nl_rmay' || $porteur =='br_rmay' || $porteur =='pt_rmay' || $porteur =='pt_rmay' || $porteur =='pt_rmay' || $porteur =='tr_rmay' || $porteur =='dk_rmay' || $porteur =='no_ml' || $porteur =='ie_ml' || $porteur =='ie_ml'){
						$paratext = preg_replace('@<pr>(.*)</pr>@', '', $paratext);
					}

					//$paratext = str_replace(array('…','’' ,'<pn>', '</pn>', '<vs>', '</vs>', '<vp>', '</vp>', '<pr>', '</pr>', '<lien>', '</lien>'), array('...','\'','','','','','','','','',''), $paratext);

					//traitement partie html ******************************************************
					$paratexthtml = preg_replace('@\[(.*)ch(.*)\]@i', '[EMV INCLUDE]'.$GLOBALS['porteurUrl'][$porteur]['emvchq'].'[EMV /INCLUDE]', $paratexthtml);
					//ajout de la phrase de magie
					if(isset($GLOBALS['porteurUrl'][$porteur]['magie']) && !empty($GLOBALS['porteurUrl'][$porteur]['magie'])){
						if(strpos($paratexthtml,'signature') !== false){
							$paratexthtml .= "\n\n\t\t\t<br><br> \n\n \t\t\t".$GLOBALS['porteurUrl'][$porteur]['magie'];
						}
					}

					$paratexthtml = str_replace("</pn><pn>", '', $paratexthtml);
					$paratexthtml = str_replace("</pn> <pn>", '', $paratexthtml);
					$paratexthtml = str_replace("</pr><pr>", '', $paratexthtml);
					$paratexthtml = str_replace("</pr> <pr>", '', $paratexthtml);
					$paratexthtml = str_replace("</vp><vp>", '', $paratexthtml);
					$paratexthtml = str_replace("</vp> <vp>", '', $paratexthtml);
					$paratexthtml = str_replace("</vs><vs>", '', $paratexthtml);
					$paratexthtml = str_replace("</vs> <vs>", '', $paratexthtml);

					if ($porteur == 'in_laetizia' || $porteur =='en_aasha' || $porteur =='en_aasha_acq' || $porteur =='ie_laetizia'  || $porteur =='nl_laetizia' ) {
						$paratexthtml = str_replace("#ddn", "[EMV FIELD]DATEOFBIRTH[EMV /FIELD]", $paratexthtml);

					}
					//variable fem mac porteur
					if($porteur == 'fr_rucker' || $porteur == 'fr_rucker_acq'|| $porteur == 'de_rucker'){
						$paratexthtml = preg_replace('@<vp>(\s*)#([^\.;,/s]+)/([^\.,;/s]+)(\s*)#([^\.;,/s]+)/([^\.,;/s]+)(\s*)</vp>@', "$1$2$7" , $paratexthtml);
						$paratexthtml = preg_replace('@<vp>(\s*)#([^\.;,/\s]+)/([^\.,;/\s]+)(\s*)</vp>@', "$1$2$4" , $paratexthtml);
						$paratexthtml = preg_replace('@<vp>(\s*)#([^\.;,/\s]+)/([^\.,;/\s]+)(\s*)</vp>@', "$1$2$4" , $paratexthtml);
						$paratexthtml = preg_replace('@<vp>(\s*)#([^\.;,/s]+)/([^\.,;/s]+)(\s*)</vp>@', "$1$2$4" , $paratexthtml);
					}else{
						$paratexthtml = preg_replace('@<vp>(\s*)#([^\.;,/s]+)/([^\.,;/s]+)(\s*)#([^\.;,/s]+)/([^\.,;/s]+)(\s*)</vp>@', "$1$3$7" , $paratexthtml);
						$paratexthtml = preg_replace('@<vp>(\s*)#([^\.;,/\s]+)/([^\.,;/\s]+)(\s*)</vp>@', "$1$3$4" , $paratexthtml);
						$paratexthtml = preg_replace('@<vp>(\s*)#([^\.;,/\s]+)/([^\.,;/\s]+)(\s*)</vp>@', "$1$3$4" , $paratexthtml);
						$paratexthtml = preg_replace('@<vp>(\s*)#([^\.;,/s]+)/([^\.,;/s]+)(\s*)</vp>@', "$1$3$4" , $paratexthtml);
					}
					//variable site porteur
					$paratexthtml = preg_replace('@<vs>(\s*)#([^\.;,/s]+)/([^\.,;/s]+)(\s*)#([^\.;,/s]+)/([^\.,;/s]+)(\s*)</vs>@', "$1[EMV IF] (SITE = \"ES\") [EMV THEN] $2[EMV /THEN] [EMV ELSE]$3[EMV /ELSE] [EMV /IF]$4[EMV IF] (SITE = \"ES\") [EMV THEN] $5[EMV /THEN] [EMV ELSE]$6[EMV /ELSE] [EMV /IF]$7" , $paratexthtml);
					$paratexthtml = preg_replace('@<vs>(\s*)#([^\.;,/\s]+)/([^\.,;/\s]+)(\s*)</vs>@', "$1[EMV IF] (SITE = \"ES\") [EMV THEN]$2[EMV /THEN] [EMV ELSE]$3[EMV /ELSE] [EMV /IF]$4" , $paratexthtml);
					//
					$paratexthtml = preg_replace('@<vs>(\s*)#([^\.;,/\s]+)/([^\.,;/\s]+)(\s*)</vs>@', "$1[EMV IF] (SITE = \"ES\") [EMV THEN]$2[EMV /THEN] [EMV ELSE]$3[EMV /ELSE] [EMV /IF]$4" , $paratexthtml);
					$paratexthtml = preg_replace('@<vs>(\s*)#([^\.;,/s]+)/([^\.,;/s]+)(\s*)</vs>@', "$1[EMV IF] (SITE = \"ES\") [EMV THEN]$2[EMV /THEN] [EMV ELSE]$3[EMV /ELSE] [EMV /IF]$4" , $paratexthtml);

//					if($porteur == 'es_laetizia' || $porteur == 'es_rmay'){
//						$paratexthtml = preg_replace('@<vs>(\s*)#([^\.;,/s]+)/([^\.,;/s]+)(\s*)#([^\.;,/s]+)/([^\.,;/s]+)(\s*)</vs>@', "$1$2$7" , $paratexthtml);
//						$paratexthtml = preg_replace('@<vs>(\s*)#([^\.;,/\s]+)/([^\.,;/\s]+)(\s*)</vs>@', "$1$2$4" , $paratexthtml);
//						$paratexthtml = preg_replace('@<vs>(\s*)#([^\.;,/\s]+)/([^\.,;/\s]+)(\s*)</vs>@', "$1$2$4" , $paratexthtml);
//						$paratexthtml = preg_replace('@<vs>(\s*)#([^\.;,/s]+)/([^\.,;/s]+)(\s*)</vs>@', "$1$2$4" , $paratexthtml);
//					}else{
//						$paratexthtml = preg_replace('@<vs>(\s*)#([^\.;,/s]+)/([^\.,;/s]+)(\s*)#([^\.;,/s]+)/([^\.,;/s]+)(\s*)</vs>@', "$1$3$7" , $paratexthtml);
//						$paratexthtml = preg_replace('@<vs>(\s*)#([^\.;,/\s]+)/([^\.,;/\s]+)(\s*)</vs>@', "$1$3$4" , $paratexthtml);
//						$paratexthtml = preg_replace('@<vs>(\s*)#([^\.;,/\s]+)/([^\.,;/\s]+)(\s*)</vs>@', "$1$3$4" , $paratexthtml);
//						$paratexthtml = preg_replace('@<vs>(\s*)#([^\.;,/s]+)/([^\.,;/s]+)(\s*)</vs>@', "$1$3$4" , $paratexthtml);
//					}
					$paratexthtml = str_replace("#ddn", "[EMV FIELD]DATEOFBIRTH,dd/MM/yyyy,fr[EMV /FIELD]", $paratexthtml);
					$paratexthtml = str_replace("</lien><lien>", '', $paratexthtml);
					$paratexthtml = preg_replace("@<pr><lien>([^<>]+)</lien></pr>@", "<pr>$1</pr>", $paratexthtml);

					//variables perso
					$paratexthtml = preg_replace('@<pn>(\s*)#(\s*)([^\.;,/\s]+)(\s*)/(\s*)([^\.,;/\s]+)(\s*)([^\/]*)</pn>@', "$1$2[EMV IF] (TITLE = \"1\") [EMV THEN]$3[EMV /THEN] [EMV ELSE]$6[EMV /ELSE][EMV /IF]$7$8" , $paratexthtml);
					$paratexthtml = preg_replace('@<pn>(\s*)#([^\.;/,\s]+)/([^\.;/,\s]+)(\s*)#([^\.;/,\s]+)/([^\.;/,\s]+)(\s*)</pn>@', "$1[EMV IF] (TITLE = \"1\") [EMV THEN]$2[EMV /THEN] [EMV ELSE]$3[EMV /ELSE] [EMV /IF]$4[EMV IF] (TITLE = \"1\") [EMV THEN]$5[EMV /THEN] [EMV ELSE]$6[EMV /ELSE] [EMV /IF]$7" , $paratexthtml);
					$paratexthtml = preg_replace('@<pn>(\s*)#([^\.;,/\s]+)/([^\.,;/\s]+)(\s*)</pn>@', "$1[EMV IF] (TITLE = \"1\") [EMV THEN]$2[EMV /THEN] [EMV ELSE]$3[EMV /ELSE] [EMV /IF]$4" , $paratexthtml);
					//changement pour prise en compte de lespace
					$paratexthtml = preg_replace('@<pn>(\s*)#([^\.;,]+)/([^\.,;]+)(\s*)</pn>@', "$1[EMV IF] (TITLE = \"1\") [EMV THEN]$2[EMV /THEN] [EMV ELSE]$3[EMV /ELSE] [EMV /IF]$4" , $paratexthtml);
					$paratexthtml = preg_replace('@<pn>(\s*)#([^\.;,/s]+)/([^\.,;/s]+)(\s*)</pn>@', "$1[EMV IF] (TITLE = \"1\") [EMV THEN]$2[EMV /THEN] [EMV ELSE]$3[EMV /ELSE] [EMV /IF]$4" , $paratexthtml);
					$paratexthtml = str_replace("#Prénom", $emvFName, $paratexthtml);
					$paratexthtml = str_replace("# Prénom", $emvFName, $paratexthtml);
					$paratexthtml = str_replace("#prénom", $emvFName, $paratexthtml);
					$paratexthtml = str_replace("# prénom", $emvFName, $paratexthtml);

					//$paratexthtml = preg_replace('@<pr>#([^\.;,/s]+)/([^\.,;/s]+)</pr>@', $GLOBALS['porteurUrl'][$porteur]['nom'] , $paratexthtml);
					$paratexthtml = str_replace("</lien>".$GLOBALS['porteurUrl'][$porteur]['nom']."<lien>", $GLOBALS['porteurUrl'][$porteur]['nom'], $paratexthtml);
					$paratexthtml = preg_replace('@<lien>(.+)</lien>@', "<a href='[EMV LINK]1[EMV /LINK]'><b>$1</b></a>", $paratexthtml);

					//$paratexthtml = preg_replace('@<pr>#([^\.;,/s]+)/([^\.,;/s]+)</pr>@', $GLOBALS['porteurUrl'][$porteur]['nom'] , $paratexthtml);

					//signature au debut
					if (isset($GLOBALS['porteurUrl'][$porteur]['emvsignature']) && $GLOBALS['porteurUrl'][$porteur]['emvsignature'] !== '') {
						$paratexthtml = preg_replace('@<pr>(.*)#(.*)signature(.*)</pr>@i', "[EMV /INCLUDE]".$GLOBALS['porteurUrl'][$porteur]['emvsignature']."[EMV /INCLUDE]", $paratexthtml);
					} else {
						$paratexthtml = preg_replace('@<pr>(.*)#(.*)signature(.*)</pr>@i', $htmlData['imgsignature'], $paratexthtml);
					}
					//variable cheque
					//variable porteur
					$paratexthtml = preg_replace('@<pr>(.*)#(.*)</pr>@', $GLOBALS['porteurUrl'][$porteur]['nom'] , $paratexthtml);

					//supprimer phrases aprés signature pour les les porteur ici
					if($porteur =='fr_rmay' || $porteur =='de_rmay' || $porteur =='es_rmay' || $porteur =='nl_rmay' || $porteur =='br_rmay' || $porteur =='pt_rmay' || $porteur =='pt_rmay' || $porteur =='pt_rmay' || $porteur =='tr_rmay' || $porteur =='dk_rmay' || $porteur =='no_ml' || $porteur =='ie_ml' || $porteur =='ie_ml'){
						$paratexthtml = preg_replace('@<pr>(.*)</pr>@', '', $paratexthtml);
					}
					//remplacer les caracters

					//traitement du paragraph qui contient l'objet ou Asunto
					if (strpos($paratext,'Objet') !== false || strpos($paratext,'Asunto') !== false || strpos($paratext,'Oggetto') !== false) {
						$parastext .= 'Objet :'."\n";
						$parastext .='-------'."\n";
						$textObjets = explode(':', $paratext);
						$parastext .= $textObjets[1]."\n\n\n\n\n";
						$parastext .= $htmlData['afterObject'];
						// $parastext .= $emvinc.$GLOBALS['porteurUrl'][$porteur]['emvsendertxt'].$finemvinc;
						$parastext .= "\n\n\n\n";
						$isobjet = 1;
						//a voir comme variable
					} else {
						if ($paratext != '' && $paratext != ' ' && $countpara >1) {
							$allparatext .= "\t".$paratext."\n\n\n";
						}
						if ($paratexthtml != '' && $paratexthtml !== ' ' && $countpara >1 ) {
							$allparahtml .= "\t\t\t".$paratexthtml."\n\n \t\t\t<br/><br/> \n\n";
						}
					}
				}



				$parastext = preg_replace('@<pn>(\s*)#(\s*)([^\.;,/\s]+)(\s*)/(\s*)([^\.,;/\s]+)(\s*)([^\/]*)</pn>@', "$1$2[EMV IF] (TITLE = \"1\") [EMV THEN]$3[EMV /THEN] [EMV ELSE]$6[EMV /ELSE] $7$8" , $parastext);
				$parastext = preg_replace('@<pn>(\s*)#([^\.;/,\s]+)/([^\.;/,\s]+)(\s*)#([^\.;/,\s]+)/([^\.;/,\s]+)(\s*)</pn>@', "$1[EMV IF] (TITLE = \"1\") [EMV THEN]$2[EMV /THEN] [EMV ELSE]$3[EMV /ELSE] [EMV /IF]$4[EMV IF] (TITLE = \"1\") [EMV THEN]$5[EMV /THEN] [EMV ELSE]$6[EMV /ELSE] [EMV /IF]$7" , $parastext);
				$parastext = preg_replace('@<pn>(\s*)#([^\.;,/\s]+)/([^\.,;/\s]+)(\s*)</pn>@', "$1[EMV IF] (TITLE = \"1\") [EMV THEN]$2[EMV /THEN] [EMV ELSE]$3[EMV /ELSE] [EMV /IF]$4" , $parastext);
				$parastext = preg_replace('@<pn>(\s*)#([^\.;,/\s]+)/([^\.,;/\s]+)(\s*)</pn>@', "$1[EMV IF] (TITLE = \"1\") [EMV THEN]$2[EMV /THEN] [EMV ELSE]$3[EMV /ELSE] [EMV /IF]$4" , $parastext);
				$parastext = preg_replace('@<pn>(\s*)#([^\.;,/s]+)/([^\.,;/s]+)(\s*)</pn>@', "$1[EMV IF] (TITLE = \"1\") [EMV THEN]$2[EMV /THEN] [EMV ELSE]$3[EMV /ELSE] [EMV /IF]$4" , $parastext);
				$textObjets[1] = preg_replace('@<pn>(\s*)#(\s*)([^\.;,/\s]+)(\s*)/(\s*)([^\.,;/\s]+)(\s*)([^\/]*)</pn>@', "$1$2[EMV IF] (TITLE = \"1\") [EMV THEN]$3[EMV /THEN] [EMV ELSE]$6[EMV /ELSE] $7$8" , $textObjets[1]);
				$textObjets[1] = preg_replace('@<pn>(\s*)#([^\.;,\s]+)/([^\.,;/s]+)(\s*)#([^\.;,\s]+)/([^\.,;\s]+)(\s*)</pn>@', "$1[EMV IF] (TITLE = \"1\") [EMV THEN]$2[EMV /THEN] [EMV ELSE]$3[EMV /ELSE] [EMV /IF]$4[EMV IF] (TITLE = \"1\") [EMV THEN]$5[EMV /THEN] [EMV ELSE]$6[EMV /ELSE] [EMV /IF]$7" , $textObjets[1]);
				$textObjets[1] = preg_replace('@<pn>(\s*)#([^\.;,/\s]+)/([^\.,;/\s]+)(\s*)</pn>@', "$1[EMV IF] (TITLE = \"1\") [EMV THEN]$2[EMV /THEN] [EMV ELSE]$3[EMV /ELSE] [EMV /IF]$4" , $textObjets[1]);
				$textObjets[1] = preg_replace('@<pn>(\s*)#([^\.;,/\s]+)/([^\.,;/\s]+)(\s*)</pn>@', "$1[EMV IF] (TITLE = \"1\") [EMV THEN]$2[EMV /THEN] [EMV ELSE]$3[EMV /ELSE] [EMV /IF]$4" , $textObjets[1]);
				$textObjets[1] = preg_replace('@<pn>(\s*)#([^\.;,/s]+)/([^\.,;/s]+)(\s*)</pn>@', "$1[EMV IF] (TITLE = \"1\") [EMV THEN]$2[EMV /THEN] [EMV ELSE]$3[EMV /ELSE] [EMV /IF]$4" , $textObjets[1]);

				if (isset($GLOBALS['porteurUrl'][$porteur]['emvchq']) && $GLOBALS['porteurUrl'][$porteur]['emvchq'] !=="" && isset($GLOBALS['porteurUrl'][$porteur]['emvchqhtml']) && $GLOBALS['porteurUrl'][$porteur]['emvchqhtml'] !=="") {
					$allparahtml = str_replace($GLOBALS['porteurUrl'][$porteur]['emvchq'], $GLOBALS['porteurUrl'][$porteur]['emvchqhtml'], $allparahtml);
				}
				$allparahtml = str_replace(array('…','’' ,'<pn>', '</pn>', '<vs>', '</vs>', '<vp>', '</vp>', '<pr>', '</pr>', '<lien>', '</lien>'), array('...','\'','','','','','','','','',''), $allparahtml);
				$allparatext = str_replace(array('…','’' ,'<pn>', '</pn>', '<vs>', '</vs>', '<vp>', '</vp>', '<pr>', '</pr>', '<lien>', '</lien>'), array('...','\'','','','','','','','','',''), $allparatext);
				$textObjets[1] = str_replace(array('…','’' ,'<pn>', '</pn>', '<vs>', '</vs>', '<vp>', '</vp>', '<pr>', '</pr>', '<lien>', '</lien>'), array('...','\'','','','','','','','','',''), $textObjets[1]);
				$parastext = str_replace(array('…','’' ,'<pn>', '</pn>', '<vs>', '</vs>', '<vp>', '</vp>', '<pr>', '</pr>', '<lien>', '</lien>'), array('...','\'','','','','','','','','',''), $parastext);


				// echo "<xmp>".$parastext."</xmp>";
				// echo "<xmp>".$allparatext."</xmp>";

				// echo "<br>".$error."<br>";
				// $text .= "<br><br><br><br>";
//					echo '<xmp>';
//					die($allparatext);
				$text .= $htmlData['header']['uLabel'];
				if($fileNameZip[0] == $fid_name .$ct.'lmar'){
					$text .= 'Ce mail n\'a pas de lien';
				}else{
					$text .=$macroURL;
				}
				$text .= $htmlData['header']['fLabel'];
				$text .= $FAQURL;
				$text .= $htmlData['header']['lr'];

				$text .= $parastext;
				$text .= wordwrap($allparatext, 80, "\n\t");
				$text .= $htmlData['footertext'];
				$html .= $htmlData['headerHtml'];
				if ($isobjet == 1) {
					$html .= "\t\t\t".htmlentities($textObjets[1])."\n";
				} else {
					die('Ce fichier ne contient pas d\'objet veuillez Choisier le bon fichier. ');
				}
				//if ($islink == 0) {
				//	die('Ce fichier ne contient pas de lien veuillez Choisier le bon fichier. ');
				//}
				$html .= $htmlData['afterHeaderHtml'];

				// echo (wordwrap($allparatext, 80, "<br><br>\n\t\t\t"));
				// echo '<br><br>-----------------------------------------<br><br>';
				// echo '<br><br>-----------------------------------------<br><br>';
				// echo "<xmp>".(wordwrap($allparahtml, 80, "\n\t\t\t"))."</xmp>";
				// die();
				$html .= $allparahtml;
				$html .= $htmlData['footerHtml'];
				$html = preg_replace("@(\s*<br\s*/?>\s*){3,}@","<br/><br/>",$html);
				$html = str_replace("/><br/><br/><div","/>\n\n\t\t\t<br/><br/>\n\n\t\t\t<div",$html);

				// echo "<xmp>".$text."</xmp>";
				// echo "<xmp>".$html."</xmp>";

				// die();

				// creation ouverture du fichier txt et html
				$openedGenFileHtml = fopen($genFileHtml, 'w+') or die("can't create the file.");
				$openedGenFile = fopen($genFileTxt, 'w+') or die("can't create the file.");
				//encodage des caracteres
				$html = htmlentities($html);
				$html = htmlspecialchars_decode($html);
				// $text = htmlentities($text);
				// $text = htmlspecialchars_decode($text);
				//replace spechial charactere for polish and turkish
				// $text = str_replace(array('ż','Ż' ,'ź', 'Ź', 'ś', 'Ś', 'ń', 'Ń', 'ł', 'Ł', 'ć', 'Ć','ó','Ó','ę','Ę','ą','Ą'), 
				// 				    array('&#380;','&#379;','&#378;','&#377;','&#347;','&#346;','&#324;','&#323;','&#322;','&#321;','&#263;','&#262;','&#243;','&#211;','&#281;','&#280;','&#261;','&#260;')
				// 		, $text);	
				$html = str_replace(array('ż','Ż' ,'ź', 'Ź', 'ś', 'Ś', 'ń', 'Ń', 'ł', 'Ł', 'ć', 'Ć','ó','Ó','ę','Ę','ą'), 
								    array('&#380;','&#379;','&#378;','&#377;','&#347;','&#346;','&#324;','&#323;','&#322;','&#321;','&#263;','&#262;','&#243;','&#211;','&#281;','&#280;','&#261;','&#260;')
						, $html);

				fwrite($openedGenFile, $text);
				fwrite($openedGenFile, $html);
				fwrite($openedGenFileHtml, $html);
				fclose($openedGenFile);
				fclose($openedGenFileHtml);

				// echo "<xmp>".$html."</xmp>";
				// die();

				zip_close($zip);

				// echo $striped_content;exit;
			}//fin boocle fichiers
			// echo 'vous pouvez telecharger vos fichiers sur : <b>/_dev_yii_0929/businessCore/tests/'.$porteur.'/</b>';

			Yii::app()->user->setFlash( "success", "<b>Les fichiers sont générés dans le dossier : /voyances/views/generated/$porteur/$fid_name$ct</b>" );
			$this->render( '//product/macro', array('Router' => $Router, 'porteur' => $_SESSION['porteur']));


		}else{
			$this->render( '//product/macro', array('Router' => $Router, 'porteur' => $_SESSION['porteur']));
			Yii::app()->end();
		}

	}
	/****************************Fonction Code html*****/
	/************ Upload des images du produit ********************/
	public function actionUploadImages(){
		$idCamp = Yii::app()->request->getParam( 'idcamp' );
		$id = Yii::app()->request->getParam( 'idSub' );
		$imageToDelete = Yii::app()->request->getParam( 'image_name' );
		if(isset($imageToDelete) && !empty($imageToDelete)){
			$msg ='';
			if (file_exists($imageToDelete)) {
				if (unlink($imageToDelete)) {
					$msg = "L'image $imageToDelete à été supprimé.";
				} else {
					$msg = "L'image $imageToDelete n'à pas été supprimé.";
				}
			}else{
				$msg = "L'image $imageToDelete n'existe pas";
			}
			Yii::app()->user->setFlash( "success", Yii::t( 'common', $msg ) );
		}

		//load product
		if( !($prod = \Business\Product::load( $id )) )
			return false;
		
		//load campaign
		if( !($camp = \Business\Campaign::load( $idCamp )) )
			return false;

		$porteur	= \Yii::app()->params['porteur'];

		$imgDir = Yii::app()->baseUrl.'/views/'.$porteur.'/'.$camp->ref.'/'.$prod->ref.'/images';

		$viewDir 	= SERVER_ROOT.$this->portViewDir.$porteur;
		$dir    = $viewDir.'/'.$camp->ref.'/'.$prod->ref.'/images';

		if (!file_exists($dir)) {
			if (mkdir($dir)) {
				chmod($dir, 0777);
			}
		}
		$images = scandir($dir);
		$this->renderPartial( '//product/UploadImages', array( 'prod'=>$prod,'idcamp'=>$idCamp,'idSub'=>$id,'images'=>$images, 'camp'=>$camp, 'viewDir' => $viewDir, 'imgDir' => $imgDir ) );
	}

		
/*************** Traitement "9 Anges" ********************/	
	public function actionAnges(){
		if( !($Sub = \Business\SubCampaign::load( Yii::app()->request->getParam( 'id' ) )) ) //get id du produit
			return false;

		$Anges				= new \Business\Anges('search');
		$Anges->idProduct	= $Sub->Product->id;

		if( Yii::app()->request->getParam( 'delete' ) !== NULL )
		{
			// Log l'action courante :
			$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );

			$Delete = \Business\Anges::load( Yii::app()->request->getParam( 'idDelete' ) );
			echo $Delete->delete() ? 'true' : 'false';
			\Yii::app()->end();
		}

		// Filtre recherche :
		if( Yii::app()->request->getParam( 'Business\Anges' ) !== NULL )
			$Anges->attributes = Yii::app()->request->getParam( 'Business\Anges' );

		$RecordCount = $Anges::model()->findByAttributes(array('idProduct'=>$Sub->idProduct));

		$this->renderPartial( '//product/Anges', array( 'Sub' => $Sub, 'Anges' => $Anges,'RecordCount'=>count($RecordCount) ) );
	}
	
	
public function actionAngesShow(){
		if( ( $Sub = \Business\SubCampaign::load( Yii::app()->request->getParam( 'idSub' )) ) === NULL )// get id du product
			return false;

		if( Yii::app()->request->getParam( 'id' ) !== NULL ){
			if( !($Anges = \Business\Anges::load( Yii::app()->request->getParam( 'id' ) )) )// get id de la variable Ange
				return false;
		}
		else
			$Anges = new \Business\Anges();

		// POST :
		if( Yii::app()->request->getParam( 'Business\Anges' ) !== NULL )
		{
			// Log l'action courante :
			$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );

			$Anges->attributes = Yii::app()->request->getParam( 'Business\Anges' );
			$Anges->idProduct	= $Sub->Product->id;

			if( $Anges->save() )
				Yii::app()->user->setFlash( "success", Yii::t( 'common', 'updateOK' ) );
			else
				Yii::app()->user->setFlash( "error", Yii::t( 'common', 'updateNOK' ) );
		}		$this->renderPartial( '//product/AngesShow', array( 'Anges' => $Anges ));
	}
	
/*************** Fin de Traitement "9 Anges" ********************/


/*************** Traitement "9 Anges" Varaibles ********************/

	public function actionAngesVariables(){
		if( Yii::app()->request->getParam( 'delete' ) !== NULL )
		{
			// Log l'action courante :
			$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );

			$Delete = \Business\AngesVariables::load( Yii::app()->request->getParam( 'idDelete' ) );
			echo $Delete->delete() ? 'true' : 'false';
			\Yii::app()->end();exit;
		}

		if( !($Ange = \Business\Anges::load( Yii::app()->request->getParam( 'id' ) )) )
			return false;

		$Variables				= new \Business\AngesVariables('search');
		$Variables->id_ange	= $Ange->id;



		// Filtre recherche :
		if( Yii::app()->request->getParam( 'Business\AngesVariables' ) !== NULL )
			$Variables->attributes = Yii::app()->request->getParam( 'Business\AngesVariables' );

		$this->renderPartial( '//product/AngesVariables', array( 'Ange' => $Ange, 'Variables' => $Variables ) );
	}

		public function actionAngesVariablesShow(){
		if( ( $Ange = \Business\Anges::load( Yii::app()->request->getParam( 'idSub' )) ) === NULL )
			return false;

		if( Yii::app()->request->getParam( 'id' ) !== NULL ){
			if( !($AngesVariables = \Business\AngesVariables::load( Yii::app()->request->getParam( 'id' ) )) )
				return false;
		}
		else
			$AngesVariables = new \Business\AngesVariables();

		// POST :
		if( Yii::app()->request->getParam( 'Business\AngesVariables' ) !== NULL )
		{
			// Log l'action courante :
			$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );

			$AngesVariables->attributes = Yii::app()->request->getParam( 'Business\AngesVariables' );
			$AngesVariables->id_Ange	= $Ange->id;

			if( $AngesVariables->save() )
				Yii::app()->user->setFlash( "success", Yii::t( 'common', 'updateOK' ) );
			else
				Yii::app()->user->setFlash( "error", Yii::t( 'common', 'updateNOK' ) );
		}		$this->renderPartial( '//product/AngesVariablesShow', array( 'AngesVariables' => $AngesVariables,'Ange' => $Ange ));
	}
	
public function actionSaveViewAnge(){

		$AngesVariable = new \Business\AngesVariables();
		$AngesVariable->attributes						= Yii::app()->request->getParam( 'Business\AngesVariables' );
		if((Yii::app()->request->getParam( 'id' )!== NULL) && (Yii::app()->request->getParam( 'id' )!=''))
		{
			//////Update
			$AngesVariable = $AngesVariable->findByPk(Yii::app()->request->getParam( 'id' ));
			$AngesVariable->id_ange = Yii::app()->request->getParam( 'idAnge' );
			$AngesVariable->name = Yii::app()->request->getParam( 'name' );
			$AngesVariable->value = Yii::app()->request->getParam( 'value' );
			if( $AngesVariable->save() )
				Yii::app()->user->setFlash( "success", Yii::t( 'common', 'updateOK' ) );
			else
				Yii::app()->user->setFlash( "error", Yii::t( 'common', 'updateNOK' ) );
		}
		else
		{
			//////Insert
			$AngesVariable->id_ange = Yii::app()->request->getParam( 'idAnge' );
			$AngesVariable->name = Yii::app()->request->getParam( 'name' );
			$AngesVariable->value = Yii::app()->request->getParam( 'value' );

			if( $AngesVariable->save() )
				Yii::app()->user->setFlash( "success", Yii::t( 'common', 'updateOK' ) );
			else
				Yii::app()->user->setFlash( "error", Yii::t( 'common', 'updateNOK' ) );
		}


	}		

}

?>
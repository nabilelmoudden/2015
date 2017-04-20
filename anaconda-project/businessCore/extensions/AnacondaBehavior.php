<?php

/**
 * Classe Anaconda pour definir les traitements relatifs au comportement d'un lead Anaconda.
 *
 * @author YacineR le : 17/10/2016
 */
class AnacondaBehavior {
	private $PorteurMap;
	
	/**
	 * ********************************************* Shoot Plannification **************************************************************
	 */
	/**
	 * Creation d'un fichier d'import pour initialiser la fiche Client des nouveaux leads anaconda au niveau de Smart Focus.
	 * 
	 * @author Yacine RAMI
	 * @param array $list
	 *        	emails a integrer
	 * @param string $campaign
	 *        	la premiere FID Anaconda
	 * @param string $shootDate
	 *        	shoot
	 * @param integer $gp
	 *        	prix
	 * @param string $dir
	 *        	"../../" => execution depuis le temrinal || "../" Execution de puis une action d'un controller.
	 * @return string du fichier d'import cree
	 *        
	 */
	static public function ShootPlanification($list, $campaign, $shootDate, $gp, $dir) {
		
		// Recuperer le site
		$porteur = Yii::app ()->params ['porteur'];
		
		// traitement de l'arborescence du fichier d'import
		if (! file_exists ( $dir . "AnacondaData/Imports/" . $porteur )) {
			mkdir ( $dir . "AnacondaData/Imports/" . $porteur, 0777 );
		}
		
		if (! file_exists ( $dir . "AnacondaData/Imports/" . $porteur . "/" . $campaign )) {
			mkdir ( $dir . "AnacondaData/Imports/" . $porteur . "/" . $campaign, 0777 );
		}
		
		if (! file_exists ( $dir . "AnacondaData/Imports/" . $porteur . "/" . $campaign . "/" . date ( "F_j_Y" ) )) {
			mkdir ( $dir . "AnacondaData/Imports/" . $porteur . "/" . $campaign . "/" . date ( "F_j_Y" ), 0777 );
		}
		
		$fileName = $dir . "AnacondaData/Imports/" . $porteur . "/" . $campaign . "/" . date ( "F_j_Y" ) . "/new_leads_anaconda_" . date ( "F_j_Y_G_i_s" ) . ".txt";
		
		// creation du fichier d'import
		fopen ( $fileName, "w" );
		
		if (sizeof ( $list ) <= 501) {
			
			// traitement d'un fichier ayant un nombre de leads inferieur e 500 pour les integrer e Anaconda.
			for($counter = 0; $counter < sizeof ( $list ); $counter ++) {
				
				if ($counter == 0) {
					file_put_contents ( $fileName, "EMAIL|GP_ANACONDA|DE_ANACONDA|SD_ANACONDA|STATUT_ANACONDA|ACTIVITY_HOUR\n", FILE_APPEND | LOCK_EX );
				} else {
					file_put_contents ( $fileName, $list [$counter] . "|" . $gp . "|" . $shootDate . "|" . $shootDate . "|" . $campaign . "_0|" . "8\n", FILE_APPEND | LOCK_EX );
				}
			}
		} else if (sizeof ( $list ) > 501 && sizeof ( $list ) <= 1001) {
			
			// traitement d'un fichier ayant un nombre de leads entre 500 et 1000 pour les integrer e Anaconda.
			for($counter = 0; $counter < round ( sizeof ( $list ) / 2 ); $counter ++) {
				
				if ($counter == 0) {
					file_put_contents ( $fileName, "EMAIL|GP_ANACONDA|DE_ANACONDA|SD_ANACONDA|STATUT_ANACONDA|ACTIVITY_HOUR\n", FILE_APPEND | LOCK_EX );
				} else {
					file_put_contents ( $fileName, $list [$counter] . "|" . $gp . "|" . $shootDate . "|" . $shootDate . "|" . $campaign . "_0|" . "5\n", FILE_APPEND | LOCK_EX );
				}
			}
			
			for($counter = round ( sizeof ( $list ) / 2 ); $counter < sizeof ( $list ); $counter ++) {
				
				file_put_contents ( $fileName, $list [$counter] . "|" . $gp . "|" . $shootDate . "|" . $shootDate . "|" . $campaign . "_0|" . "9\n", FILE_APPEND | LOCK_EX );
			}
		} else if (sizeof ( $list ) > 1001 && sizeof ( $list ) <= 2001) {
			
			// traitement d'un fichier ayant un nombre de leads entre 500 et 1000 pour les integrer e Anaconda.
			for($counter = 0; $counter < round ( sizeof ( $list ) / 4 ); $counter ++) {
				
				if ($counter == 0) {
					file_put_contents ( $fileName, "EMAIL|GP_ANACONDA|DE_ANACONDA|SD_ANACONDA|STATUT_ANACONDA|ACTIVITY_HOUR\n", FILE_APPEND | LOCK_EX );
				} else {
					file_put_contents ( $fileName, $list [$counter] . "|" . $gp . "|" . $shootDate . "|" . $shootDate . "|" . $campaign . "_0|" . "6\n", FILE_APPEND | LOCK_EX );
				}
			}
			
			for($counter = round ( sizeof ( $list ) / 4 ); $counter < round ( sizeof ( $list ) / 2 ); $counter ++) {
				
				file_put_contents ( $fileName, $list [$counter] . "|" . $gp . "|" . $shootDate . "|" . $shootDate . "|" . $campaign . "_0|" . "10\n", FILE_APPEND | LOCK_EX );
			}
			
			for($counter = round ( sizeof ( $list ) / 2 ); $counter < round ( (sizeof ( $list ) * 3) / 4 ); $counter ++) {
				
				file_put_contents ( $fileName, $list [$counter] . "|" . $gp . "|" . $shootDate . "|" . $shootDate . "|" . $campaign . "_0|" . "12\n", FILE_APPEND | LOCK_EX );
			}
			
			for($counter = round ( (sizeof ( $list ) * 3) / 4 ); $counter < sizeof ( $list ); $counter ++) {
				
				file_put_contents ( $fileName, $list [$counter] . "|" . $gp . "|" . $shootDate . "|" . $shootDate . "|" . $campaign . "_0|" . "16\n", FILE_APPEND | LOCK_EX );
			}
		} else if (sizeof ( $list ) > 2001) {
			
			// traitement d'un fichier ayant un nombre de leads superieur e 2000 pour les integrer e Anaconda.
			for($counter = 0; $counter < round ( sizeof ( $list ) / 6 ); $counter ++) {
				
				if ($counter == 0) {
					file_put_contents ( $fileName, "EMAIL|GP_ANACONDA|DE_ANACONDA|SD_ANACONDA|STATUT_ANACONDA|ACTIVITY_HOUR\n", FILE_APPEND | LOCK_EX );
				} else {
					file_put_contents ( $fileName, $list [$counter] . "|" . $gp . "|" . $shootDate . "|" . $shootDate . "|" . $campaign . "_0|" . "6\n", FILE_APPEND | LOCK_EX );
				}
			}
			
			for($counter = round ( sizeof ( $list ) / 6 ); $counter < round ( (sizeof ( $list ) * 2) / 6 ); $counter ++) {
				
				file_put_contents ( $fileName, $list [$counter] . "|" . $gp . "|" . $shootDate . "|" . $shootDate . "|" . $campaign . "_0|" . "10\n", FILE_APPEND | LOCK_EX );
			}
			
			for($counter = round ( (sizeof ( $list ) * 2) / 6 ); $counter < round ( sizeof ( $list ) / 2 ); $counter ++) {
				
				file_put_contents ( $fileName, $list [$counter] . "|" . $gp . "|" . $shootDate . "|" . $shootDate . "|" . $campaign . "_0|" . "12\n", FILE_APPEND | LOCK_EX );
			}
			
			for($counter = round ( sizeof ( $list ) / 2 ); $counter < round ( (sizeof ( $list ) * 4) / 6 ); $counter ++) {
				
				file_put_contents ( $fileName, $list [$counter] . "|" . $gp . "|" . $shootDate . "|" . $shootDate . "|" . $campaign . "_0|" . "16\n", FILE_APPEND | LOCK_EX );
			}
			
			for($counter = round ( (sizeof ( $list ) * 4) / 6 ); $counter < round ( (sizeof ( $list ) * 5) / 6 ); $counter ++) {
				
				file_put_contents ( $fileName, $list [$counter] . "|" . $gp . "|" . $shootDate . "|" . $shootDate . "|" . $campaign . "_0|" . "20\n", FILE_APPEND | LOCK_EX );
			}
			
			for($counter = round ( (sizeof ( $list ) * 5) / 6 ); $counter < sizeof ( $list ); $counter ++) {
				
				file_put_contents ( $fileName, $list [$counter] . "|" . $gp . "|" . $shootDate . "|" . $shootDate . "|" . $campaign . "_0|" . "23\n", FILE_APPEND | LOCK_EX );
			}
		}
		
		// retourner le chemin du fichier d'import cree
		return $fileName;
	}
	/**
	 * ******************************************************************* /Shoot Plannficiation ********************************************************************************************
	 */
	
	/**
	 * ********************************* Debut Export **********************************************************************
	 */
	
	/**
	 * Exporter un segment via son id, mettre le contenu dans un fichier et retourner le chemin dans le serveur.
	 * 
	 * @author Zakaria CHNIBER
	 * @return fileName
	 */
	static public function exportSegment($idSegment, $type, $dir) {
		
		// appel de la classe api
		Yii::import ( 'ext.Class_API', true );
		
		$porteurMapp = Yii::app ()->params ['porteur'];
		\Controller::loadConfigForPorteur ( $porteurMapp );
		
		// rï¿½cupï¿½ration des parametres de l'API
		$mkt_wdsl = \Yii::app ()->params ['MKT_EMV_ACQ'] ['wdsl'];
		$mkt_login = \Yii::app ()->params ['MKT_EMV_ACQ'] ['login'];
		$mkt_pwd = \Yii::app ()->params ['MKT_EMV_ACQ'] ['pwd'];
		$mkt_key = \Yii::app ()->params ['MKT_EMV_ACQ'] ['key'];
		
		$class_api = new Class_API ( $mkt_wdsl, $mkt_login, $mkt_pwd, $mkt_key );
		
		// token de connexion
		$token = $class_api->connexion ();
		
		// contenu du fichier d'export
		$fileContent = $class_api->export ( $token, $idSegment );
		
		// Recuperer le site
		$porteur = Yii::app ()->params ['porteur'];
		
		// traitement de l'arborescence du fichier d'export
		if (! file_exists ( $dir . "AnacondaData/Exports/" . $porteur )) {
			mkdir ( $dir . "AnacondaData/Exports/" . $porteur, 0777 );
		}
		
		if (! file_exists ( $dir . "AnacondaData/Exports/" . $porteur . "/AnacondaInit" )) {
			mkdir ( $dir . "AnacondaData/Exports/" . $porteur . "/AnacondaInit", 0777 );
		}
		
		if (! file_exists ( $dir . "AnacondaData/Exports/" . $porteur . "/AnacondaInit/" . $type )) {
			mkdir ( $dir . "AnacondaData/Exports/" . $porteur . "/AnacondaInit/" . $type, 0777 );
		}
		
		if (! file_exists ( $dir . "AnacondaData/Exports/" . $porteur . "/AnacondaInit/" . $type . "/" . date ( "F_j_Y" ) )) {
			mkdir ( $dir . "AnacondaData/Exports/" . $porteur . "/AnacondaInit/" . $type . "/" . date ( "F_j_Y" ), 0777 );
		}
		
		$fileName = $dir . "AnacondaData/Exports/" . $porteur . "/AnacondaInit/" . $type . "/" . date ( "F_j_Y" ) . "/export_leads_anaconda_" . $type . "_" . date ( "F_j_Y_G_i_s" ) . ".txt";
		
		// creation du fichier d'export
		$handleFile = fopen ( $fileName, "w" ) or die ( "Probleme de connexion au serveur, impossible d\'ouvrir le fichier! Merci de reessayer e nouveau." );
		fputs ( $handleFile, $fileContent );
		fclose ( $handleFile );
		
		return $fileName;
	}
	/**
	 * ********************************* Fin Export **********************************************************************
	 */
	static public function exportSegmentQuanrantaine($idSegment, $type, $dir) {
		
		// appel de la classe api
		Yii::import ( 'ext.Class_API', true );
		
		$porteurMapp = Yii::app ()->params ['porteur'];
		\Controller::loadConfigForPorteur ( $porteurMapp );
		
		// rï¿½cupï¿½ration des parametres de l'API
		$mkt_wdsl = \Yii::app ()->params ['MKT_EMV_ACQ'] ['wdsl'];
		$mkt_login = \Yii::app ()->params ['MKT_EMV_ACQ'] ['login'];
		$mkt_pwd = \Yii::app ()->params ['MKT_EMV_ACQ'] ['pwd'];
		$mkt_key = \Yii::app ()->params ['MKT_EMV_ACQ'] ['key'];
		
		$class_api = new Class_API ( $mkt_wdsl, $mkt_login, $mkt_pwd, $mkt_key );
		
		// token de connexion
		$token = $class_api->connexion ();
		
		// contenu du fichier d'export
		$fileContent = $class_api->exportQuarantaine ( $token, $idSegment );
		
		// Recuperer le site
		$porteur = Yii::app ()->params ['porteur'];
		
		// traitement de l'arborescence du fichier d'export
		if (! file_exists ( $dir . "AnacondaData/Exports/" . $porteur )) {
			mkdir ( $dir . "AnacondaData/Exports/" . $porteur, 0777 );
		}
		
		if (! file_exists ( $dir . "AnacondaData/Exports/" . $porteur . "/AnacondaInit" )) {
			mkdir ( $dir . "AnacondaData/Exports/" . $porteur . "/AnacondaInit", 0777 );
		}
		
		if (! file_exists ( $dir . "AnacondaData/Exports/" . $porteur . "/AnacondaInit/" . $type )) {
			mkdir ( $dir . "AnacondaData/Exports/" . $porteur . "/AnacondaInit/" . $type, 0777 );
		}
		
		if (! file_exists ( $dir . "AnacondaData/Exports/" . $porteur . "/AnacondaInit/" . $type . "/" . date ( "F_j_Y" ) )) {
			mkdir ( $dir . "AnacondaData/Exports/" . $porteur . "/AnacondaInit/" . $type . "/" . date ( "F_j_Y" ), 0777 );
		}
		
		$fileName = $dir . "AnacondaData/Exports/" . $porteur . "/AnacondaInit/" . $type . "/" . date ( "F_j_Y" ) . "/export_leads_anaconda_" . $type . "_" . date ( "F_j_Y_G_i_s" ) . ".txt";
		
		// creation du fichier d'export
		$handleFile = fopen ( $fileName, "w" ) or die ( "Probleme de connexion au serveur, impossible d\'ouvrir le fichier! Merci de reessayer e nouveau." );
		fputs ( $handleFile, $fileContent );
		fclose ( $handleFile );
		
		return $fileName;
	}
	
	/**
	 * ********************************* Import **********************************************************************
	 */
	/**
	 * importer via API un fichier
	 * 
	 * @author Saad HDIDOU
	 * @param string $path        	
	 * @return
	 *
	 */
	static public function import($path) {
		// appel de la classe api
		Yii::import ( 'ext.Class_API', true );
		
		// recuperer le dossier porteur
		
		$porteurMapp = Yii::app ()->params ['porteur'];
		\Controller::loadConfigForPorteur ( $porteurMapp );
		
		// connexion API
		
		$cmd_wdsl = \Yii::app ()->params ['CMD_EMV_ACQ'] ['wdsl_batchMember'];
		$cmd_login = \Yii::app ()->params ['CMD_EMV_ACQ'] ['login'];
		$cmd_pwd = \Yii::app ()->params ['CMD_EMV_ACQ'] ['pwd'];
		$cmd_key = \Yii::app ()->params ['CMD_EMV_ACQ'] ['key'];
		$class_api = new Class_API ( $cmd_wdsl, $cmd_login, $cmd_pwd, $cmd_key );
		$token = $class_api->connexion2 ();
		
		$open = fopen ( $path, "r" );
		
		// recuperation des champs a mettre a jour dans la fiche client
		$ligne1 = fgets ( $open );
		$ligne1 = explode ( '|', $ligne1 );
		$j = 1;
		$t = array (
				'colNum' => 1,
				'fieldName' => 'EMAIL',
				'toReplace' => false 
		);
		$mapping = array (
				$t 
		);
		while ( $j < count ( $ligne1 ) ) {
			if ($j == count ( $ligne1 ) - 1) {
				$ligne1 [$j] = substr ( $ligne1 [$j], 0, - 1 );
			}
			$t = array (
					'colNum' => $j + 1,
					'fieldName' => trim ( $ligne1 [$j] ),
					'toReplace' => true 
			);
			array_push ( $mapping, $t );
			$j ++;
		}
		
		// import
		$imp = $class_api->importFile ( $token, $path, $mapping );
		if ($imp) {
			echo "<br/>import done<br/>";
			return true;
		} else
			return false;
	}
	
	/**
	 * ********************************* /Import **********************************************************************
	 */
	
	/**
	 * ********************************* fileToArray ******************************************************************
	 */
	
	/**
	 * convertir un fichier en un tableau
	 * 
	 * @author Saad HDIDOU
	 * @param string $path        	
	 * @return array
	 */
	static public function fileToArray($path) {
		$handle = fopen ( $path, "r" );
		$array = array ();
		if ($handle) {
			while ( ($line = fgets ( $handle )) !== false ) {
				// elliminer les retours a la ligne
				$line = str_replace ( "\n", '', $line );
				$line = str_replace ( "\r", '', $line );
				// inserer dans le tableau
				$array [] = $line;
			}
			
			fclose ( $handle );
			return $array;
		} else {
			echo "error";
		}
	}
	
	/**
	 * ********************************* /fileToArray ******************************************************************
	 */
	
	/**
	 * ********************************* firstCampaign ****************************************************************
	 */
	
	/**
	 * recuperer la premiere fid anaconda
	 * 
	 * @author Saad HDIDOU
	 * @param        	
	 *
	 * @return campaign
	 */
	static public function getFirstCampaign() {
		// recuperer tous les campaigns du premier lot
		$tablecampaigns = null;
		if (! ($tablecampaigns = \Business\Campaign::loadByNumLot ( 1 ))) {
			return false;
		}
		$first = 0;
		$i = 0;
		// recuperer la campaign sans predecesseur
		while ( $i != count ( $tablecampaigns ) && $first == 0 ) {
			$first = 1;
			for($j = 0; $j < count ( $tablecampaigns ); $j ++) {
				if ($tablecampaigns [$i] ['id'] == $tablecampaigns [$j] ['idNextCampaign'])
					$first = 0;
			}
			$i ++;
		}
		return $tablecampaigns [$i - 1];
	}
	
	/**
	 * ********************************* /firstCampaign ****************************************************************
	 */
	
	/**
	 * ********************************* nextCampaign ****************************************************************
	 */
	
	/**
	 * recuperer la campaign suivante
	 * 
	 * @author Saad HDIDOU
	 * @param
	 *        	$idUser
	 * @return campaign
	 */
	static public function getNextCampaign($idUser) {
		$campaign = self::getFirstCampaign ();
		$subCampaign = \Business\SubCampaign::loadByCampaignAndPosition ( $campaign->id, 1 );
		$camph = \Business\CampaignHistory::loadByUserAndSubCampaign ( $idUser, $subCampaign->id );
		
		while ( isset ( $camph ) ) {
			$campaign = $campaign->getNextCampaign ();
			if (! isset ( $campaign )) {
				return false;
			}
			$subCampaign = \Business\SubCampaign::loadByCampaignAndPosition ( $campaign->id, 1 );
			$camph = \Business\CampaignHistory::loadByUserAndSubCampaign ( $idUser, $subCampaign->id );
		}
		return $campaign;
	}
	
	/**
	 * ********************************* /nextCampaign ****************************************************************
	 */
	
	/**
	 * ******************************************* GetPorteur MAP by soufiane ********************************************
	 */
	public function getPorteurMap() {
		$porteur = Yii::app ()->session ['porteur'];
		
		if (isset ( explode ( '_', $porteur )[2] )) {
			$this->PorteurMap = $GLOBALS ['porteurMap'] [explode ( '_', $porteur )[0] . '_' . explode ( '_', $porteur )[1] . '_' . explode ( '_', $porteur )[2]];
		} else {
			$this->PorteurMap = $GLOBALS ['porteurMap'] [explode ( '_', $porteur )[0] . '_' . explode ( '_', $porteur )[1]];
		}
	}
	
	/**
	 * ******************************************* Get list o connexion api (acqui / fid ) by soufiane ********************************************
	 */
	/**
	 * return	un tebleau de type classAPI
	 */
	public function getParamApiCMD() {
		$this->getPorteurMap ();
		Yii::import ( 'ext.Class_API', true );
		\Controller::loadConfigForPorteur ( $this->PorteurMap );
		
		$cmd_wdsl = \Yii::app ()->params ['CMD_EMV_ACQ'] ['wdsl'];
		$cmd_login = \Yii::app ()->params ['CMD_EMV_ACQ'] ['login'];
		$cmd_pwd = \Yii::app ()->params ['CMD_EMV_ACQ'] ['pwd'];
		$cmd_key = \Yii::app ()->params ['CMD_EMV_ACQ'] ['key'];
		
		$class_api [0] = new Class_API ( $cmd_wdsl, $cmd_login, $cmd_pwd, $cmd_key );
		if (isset ( \Yii::app ()->params ['CMD_EMV_FID'] ['wdsl'] )) {
			
			$cmd_wdsl_fid = \Yii::app ()->params ['CMD_EMV_FID'] ['wdsl'];
			$cmd_login_fid = \Yii::app ()->params ['CMD_EMV_FID'] ['login'];
			$cmd_pwd_fid = \Yii::app ()->params ['CMD_EMV_FID'] ['pwd'];
			$cmd_key_fid = \Yii::app ()->params ['CMD_EMV_FID'] ['key'];
			$class_api [1] = new Class_API ( $cmd_wdsl_fid, $cmd_login_fid, $cmd_pwd_fid, $cmd_key_fid );
		}
		return $class_api;
	}
	
	/**
	 * ************************ Create Segment(Shoot/Livraison) by Campaign **********************
	 */
	
	/**
	 *
	 * @param
	 *        	intger	idCampaign	l'id de la campaign
	 * @param
	 *        	string	site site du proteur
	 * @param
	 *        	integer	nbrInterval nombre d'intervalle de shoot et du livraisonv2_c
	 */
	public function CreateSegmentByCampagin($idCampaign, $nbrInterval) {
		// tester s'il existe un produit a la position une
		if (isset ( \Business\SubCampaign::loadByCampaignAndPosition ( $idCampaign, 1 )->id )) {
			
			$this->getPorteurMap ();
			// retourner le compte emv du proteur
			$compteEMV = $GLOBALS ["porteurCompteEMVactif"] [$this->PorteurMap];
			
			$sub_campaing = \Business\SubCampaign::loadByCampaignAndPosition ( $idCampaign, 1 );
			$anaconda_segment = new \Business\AnacondaSegment ();
			
			// eviter les boucles infinis
			$nbrTourne = 0;
			
			foreach ( $this->getParamApiCMD () as $class_api ) {
				
				if ($nbrTourne > 40) {
					die ();
				}
				// recuperer le token couurant
				$token = $class_api->connexion ();
				
				// somme interval courant + pas
				$j = 0;
				// nbre interval par jour
				$pas = (24 / $nbrInterval - 1);
				for($i = 1; $i <= $nbrInterval; $i ++) {
					
					if ($nbrTourne > 40) {
						die ();
					}
					// segments to_shoot du produit 1
					if (count ( \Business\AnacondaSegment::loadByNameSegmentAndIdSubCampaign ( $sub_campaing->Campaign->ref . '_toshoot_vague' . $i . '[ANACONDA]', $sub_campaing->id ) ) == 0) {
						// creation de segement
						$idsegment = $class_api->createSegment ( $sub_campaing->Campaign->ref . '_toshoot_vague' . $i . '[ANACONDA]' );
						$class_api->critereDateAbsoluteSansInterval ( 'SD_ANACONDA', 'IS_NOT_EMPTY', '', '' );
						$class_api->critereString ( 'GP_ANACONDA', 'IS_NOT_EMPTY', '', '', '' );
						$class_api->critereDateAbsoluteSansInterval ( 'DE_ANACONDA', 'IS_NOT_EMPTY', '', '' );
						$class_api->critereString ( 'STATUT_ANACONDA', 'EQUALS', $sub_campaing->Campaign->ref . '_0', '', '' );
						$class_api->critereNumeric ( 'ACTIVITY_HOUR', 'IS_BETWEEN', $j, $j += $pas, '', '' );
						$anaconda_segment->createSegment ( $idsegment, $sub_campaing->Campaign->ref . '_toshoot_vague' . $i . '[ANACONDA]', 'Shoot', $sub_campaing->id, $compteEMV );
					} else {
						$j += $pas;
					}
					$j ++;
					$nbrTourne ++;
				}
				$j = 0;
				for($i = 1; $i <= $nbrInterval; $i ++) {
					if ($nbrTourne > 40) {
						die ();
					}
					// segments to_deliver du produit 1
					if (count ( \Business\AnacondaSegment::loadByNameSegmentAndIdSubCampaign ( $sub_campaing->Campaign->ref . '_todeliver_vague' . $i . '[ANACONDA]', $sub_campaing->id ) ) == 0) {
						// creation de segement
						$idsegment = $class_api->createSegment ( $sub_campaing->Campaign->ref . '_todeliver_vague' . $i . '[ANACONDA]' );
						$class_api->critereString ( 'GP_ANACONDA', 'IS_NOT_EMPTY', '', '', '' );
						$class_api->critereDateAbsoluteSansInterval ( 'DL_ANACONDA', 'IS_NOT_EMPTY', '', '' );
						$class_api->critereString ( 'LIVRAISON_ANACONDA', 'CONTAINS', $sub_campaing->Campaign->ref . '_1', '', '' );
						$class_api->critereNumeric ( 'ACTIVITY_HOUR', 'IS_BETWEEN', $j, $j += $pas, '', '' );
						$anaconda_segment->createSegment ( $idsegment, $sub_campaing->Campaign->ref . '_todeliver_vague' . $i . '[ANACONDA]', 'Livraison', $sub_campaing->id, $compteEMV );
					} else {
						$j += $pas;
					}
					$nbrTourne ++;
					$j ++;
				}
				// tester s'il existe un produit a la deuxieme position
				if (isset ( \Business\SubCampaign::loadByCampaignAndPosition ( $idCampaign, 2 )->id )) {
					$sub_campaing = \Business\SubCampaign::loadByCampaignAndPosition ( $idCampaign, 2 );
					$j = 0;
					for($i = 1; $i <= $nbrInterval; $i ++) {
						if ($nbrTourne > 40) {
							die ();
						}
						// segments to_shoot du produit 2
						if (count ( \Business\AnacondaSegment::loadByNameSegmentAndIdSubCampaign ( $sub_campaing->Campaign->ref . 'ct_toshoot_vague' . $i . '[ANACONDA]', $sub_campaing->id ) ) == 0) {
							// creation de segement
							$idsegment = $class_api->createSegment ( $sub_campaing->Campaign->ref . 'ct_toshoot_vague' . $i . '[ANACONDA]' );
							$class_api->critereString ( 'GP_ANACONDA', 'IS_NOT_EMPTY', '', '', '' );
							$class_api->critereDateAbsoluteSansInterval ( 'SD_ANACONDA', 'IS_NOT_EMPTY', '', '' );
							$class_api->critereDateAbsoluteSansInterval ( 'DE_ANACONDA', 'IS_NOT_EMPTY', '', '' );
							$class_api->critereString ( 'STATUT_ANACONDA', 'EQUALS', $sub_campaing->Campaign->ref . '_1', '', '' );
							$class_api->critereNumeric ( 'ACTIVITY_HOUR', 'IS_BETWEEN', $j, $j += $pas, '', '' );
							$anaconda_segment->createSegment ( $idsegment, $sub_campaing->Campaign->ref . 'ct_toshoot_vague' . $i . '[ANACONDA]', 'Shoot', $sub_campaing->id, $compteEMV );
						} else {
							$j += $pas;
						}
						$j ++;
						$nbrTourne ++;
					}
					$j = 0;
					for($i = 1; $i <= $nbrInterval; $i ++) {
						if ($nbrTourne > 40) {
							die ();
						}
						// segments to_deliver du produit 2
						if (count ( \Business\AnacondaSegment::loadByNameSegmentAndIdSubCampaign ( $sub_campaing->Campaign->ref . 'ct_todeliver_vague' . $i . '[ANACONDA]', $sub_campaing->id ) ) == 0) {
							// creation de segement
							$idsegment = $class_api->createSegment ( $sub_campaing->Campaign->ref . 'ct_todeliver_vague' . $i . '[ANACONDA]' );
							$class_api->critereString ( 'GP_ANACONDA', 'IS_NOT_EMPTY', '', '', '' );
							$class_api->critereDateAbsoluteSansInterval ( 'DL_ANACONDA', 'IS_NOT_EMPTY', '', '' );
							$class_api->critereString ( 'LIVRAISON_ANACONDA', 'CONTAINS', $sub_campaing->Campaign->ref . '_2', '', '' );
							$class_api->critereNumeric ( 'ACTIVITY_HOUR', 'IS_BETWEEN', $j, $j += $pas, '', '' );
							
							$anaconda_segment->createSegment ( $idsegment, $sub_campaing->Campaign->ref . 'ct_todeliver_vague' . $i . '[ANACONDA]', 'Livraison', $sub_campaing->id, $compteEMV );
						} else {
							$j += $pas;
						}
						$j ++;
						$nbrTourne ++;
					}
				}
				// fermer le token
				$class_api->closeConnection ( $token );
				$nbrTourne ++;
			}
		}
	}
	/**
	 * ************************ /Create Segment(Shoot/Livraison) by Campaign **********************
	 */
	
	/**
	 * ****************************************************** Get SB Banned List (SB >= 10) *****************************************************************
	 */
	/**
	 * Recuperer la liste des utilisateur ayant atteint un nombre de soft bounce egal e 10.
	 * 
	 * @author Yacine RAMI
	 * @return $string[] liste des emails.
	 */
	static public function GetSBBannList() {
		$listUser = null;
		$listmails = null;
		if (! ($listUser = \Business\User::loadBySBBann ( 10 ))) {
			return false;
		} else {
			for($count = 0; $count < sizeof ( $listUser ); $count ++) {
				$listmails [$count] = $listUser [$count]->email;
			}
			
			return $listmails;
		}
	}
	
	/**
	 * *************************************************** / Get SB Banned List (SB >= 10) *********************************************************************
	 */
	
	/**
	 * **************************** getReceivedR3 ***********************************************************
	 */
	
	/**
	 * recuperer les clients qui ont recu la relance 3 de la chaine VP
	 * 
	 * @author Saad HDIDOU
	 * @param string $dir
	 *        	"../../" => execution depuis le temrinal || "../" Execution de puis une action d'un controller.
	 * @return array
	 */
	static public function getReceivedR3($dir) {
		
		// appel du fichier /fv2_YII/conf_porteur_dev.php
		$porteurMapp = Yii::app ()->params ['porteur'];
		\Controller::loadConfigForPorteur ( $porteurMapp );
		
		$id_emv_seg_recievedR3 = \Yii::app ()->params ['emv_seg_recievedR3'];
		$path = self::exportSegment ( $id_emv_seg_recievedR3, "ReceivedR3", $dir );
		return self::fileToArray ( $path );
	}
	
	/**
	 * **************************** /getReceivedR3 **********************************************************
	 */
	
	/**
	 * **************************** getPayedVP **********************************************************
	 */
	
	/**
	 * recuperer les clients qui ont achete le produit VP
	 * 
	 * @author Saad HDIDOU
	 * @param string $dir
	 *        	"../../" => execution depuis le temrinal || "../" Execution de puis une action d'un controller.
	 * @return array
	 */
	static public function getPayedVP($dir) {
		
		// appel du fichier /fv2_YII/conf_porteur_dev.php
		$porteurMapp = Yii::app ()->params ['porteur'];
		\Controller::loadConfigForPorteur ( $porteurMapp );
		
		$id_emv_seg_payedVP = \Yii::app ()->params ['emv_seg_payedVP'];
		$path = self::exportSegment ( $id_emv_seg_payedVP, "PayedVP", $dir );
		return self::fileToArray ( $path );
	}
	
	/**
	 * **************************** /getPayedVP **********************************************************
	 */
	static public function getQuarantaine($dir) {
		
		// appel du fichier /fv2_YII/conf_porteur_dev.php
		$porteurMapp = Yii::app ()->params ['porteur'];
		\Controller::loadConfigForPorteur ( $porteurMapp );
		
		$id_emv_seg_quarantaine = \Yii::app ()->params ['emv_quarantaine'];
		$path = self::exportSegmentQuanrantaine ( $id_emv_seg_quarantaine, "Quarantaine", $dir );
		return self::fileToArray ( $path );
	}
	/****************************************************************/
	static public function setQuarantaine($dir) {
		$users = self::getQuarantaine ( $dir );
		 
		foreach ( $users as $user ) {
			
			$us = \Business\User::loadByEmail($user);
			
			if ($us) {				
				$us->quarantaine = 1;
				$us->save();
			}		
		}
	}
	/**
	 * **************************** segmentation **********************************************************
	 */
	
	/**
	 * segmentation des lead a initialiser dans anaconda, import dans SF et insertion dans la table campaginHistory
	 * 
	 * @author Saad HDIDOU
	 * @param array $list
	 *        	emails a integrer
	 * @param string $campaign
	 *        	la premiere FID Anaconda
	 * @param string $shootDate
	 *        	shoot
	 * @param int $gp
	 *        	Groupe de prix
	 * @param string $dir
	 *        	"../../" => execution depuis le temrinal || "../" Execution de puis une action d'un controller.
	 * @return
	 *
	 */
	static public function segmentation($list, $shootDate, $gp, $dir) {
		// first campaign
		$campaign = self::getFirstCampaign ();
		// shoot planification
		$pathImport = self::ShootPlanification ( $list, $campaign->ref, $shootDate, $gp, $dir );
		// import du fichier
		if (! (self::import ( $pathImport )))
			return 2;
			// id subCampaign
		if (! ($subCampaign = \Business\SubCampaign::loadByCampaignAndPosition ( $campaign->id, 1 )))
			return 3;
		$idSubCampaign = $subCampaign->id;
		// convertir le fichier en tableau
		$list = self::fileToArray ( $pathImport );
		// eliminer la premiere ligne qui contient les noms des champs
		unset ( $list [0] );
		// list des email introuvables
		$unfounded = array ();
		// parcourir le tableau afin d inserer chaque ligne dans la table campaignHistory
		foreach ( $list as $ligne ) {
			$ligne = explode ( '|', $ligne );
			// EMAIL|GP_ANACONDA|DE_ANACONDA|SD_ANACONDA|STATUT_ANACONDA|ACTIVITY_HOUR
			$de = DateTime::createFromFormat ( 'd/m/Y', $ligne [2] );
			$sd = DateTime::createFromFormat ( 'd/m/Y', $ligne [3] );
			$s = explode ( '_', $ligne [4] );
			if (($user = \Business\User::loadByEmail ( $ligne [0] ))) {
				
				$campaignHistory = new \Business\CampaignHistory ();
				$campaignHistory->modifiedShootDate = $de->format ( 'Y-m-d' );
				$campaignHistory->initialShootDate = $sd->format ( 'Y-m-d' );
				$campaignHistory->groupPrice = $ligne [1];
				$campaignHistory->status = 0;
				$campaignHistory->behaviorHour = $ligne [5];
				$campaignHistory->idUser = $user->id;
				$campaignHistory->idSubCampaign = $idSubCampaign;
				$campaignHistory->save ();
				
				$user->dateGpChange = date ( 'Y-m-d H:i:s' );
				$user->intialDate = date ( 'Y-m-d H:i:s' );
				$user->save ();
			} else {
				$unfounded [] = $ligne [0];
			}
		}
		if (empty ( $unfounded ))
			return 1;
		else
			return $unfounded;
	}
	
	/**
	 * **************************** /segmentation **********************************************************
	 */
	
	/**
	 * ********************************************************* get Inactive Users ****************************************************************
	 */
	/**
	 * Recuperer les Utilisateurs inactifs de Smart Focus.
	 * 
	 * @author Yacine RAMI
	 * @param
	 *        	array liste des dates
	 * @return number $period nombre de mois d'inactivite
	 */
	static public function getInactiveUsers($dir) {
		// appel du fichier /fv2_YII/conf_porteur_dev.php
		$porteurMapp = Yii::app ()->params ['porteur'];
		\Controller::loadConfigForPorteur ( $porteurMapp );
		
		$id_emv_seg_payedVP = \Yii::app ()->params ['emv_inactive_users_anaconda'];
		$path = self::exportSegment ( $id_emv_seg_payedVP, "Inactive_Users_Anaconda", $dir );
		
		return self::fileToArray ( $path );
	}
	
	static public function getInactiveUsers2($dir) {
		// appel du fichier /fv2_YII/conf_porteur_dev.php
		$porteurMapp = Yii::app ()->params ['porteur'];
		\Controller::loadConfigForPorteur ( $porteurMapp );
		
		Yii::import ( 'ext.Class_API', true );
		 
		$porteurMapp = Yii::app ()->params ['porteur'];
		\Controller::loadConfigForPorteur ( $porteurMapp );
		 
		// rï¿½cupï¿½ration des parametres de l'API
		$mkt_wdsl = \Yii::app ()->params ['CMD_EMV_ACQ'] ['wdsl'];
		$mkt_login = \Yii::app ()->params ['CMD_EMV_ACQ'] ['login'];
		$mkt_pwd = \Yii::app ()->params ['CMD_EMV_ACQ'] ['pwd'];
		$mkt_key = \Yii::app ()->params ['CMD_EMV_ACQ'] ['key'];
		 
		$class_api = new Class_API ( $mkt_wdsl, $mkt_login, $mkt_pwd, $mkt_key );
		 
		/////////////////////////////////////////////////// Dates
		 
		//J
		$dateToday = new Datetime ( date ( 'd-m-Y' ) );
		 
		$dateJ = $dateToday->format('Y-m-d')."T00:00:00";
		 
		// J - 8 mois
		$dateSub8Months = new Datetime ( date ( 'd-m-Y' ) );
		$dateSub8Months = $dateSub8Months->sub ( new DateInterval ( 'P8M' ) );
		$date8= $dateSub8Months->format('Y-m-d')."T00:00:00";
		 
		$token = $class_api->connexion ();
	    $id_segment = \Yii::app()->params['Inactive_Users_Anaconda_8_mois'];
		$column_name = "LAST_DATE_OPEN";
		$operator = "ISBETWEEN_STATIC";
		$first_value = $date8;
		$second_value = $dateJ;
		$groupNumber = 1;
		$orderFrag= 1 ;
		
		$class_api->update_segment_criteria_recent($token,$id_segment,$column_name,$operator,$first_value,$second_value,$groupNumber,$orderFrag);
		
		$class_api->closeConnection ( $token );
		
	
		$id_emv_seg_payedVP = \Yii::app ()->params ['emv_inactive_users_anaconda'];
		$path = self::exportSegment ( $id_emv_seg_payedVP, "Inactive_Users_Anaconda_2_mois", $dir );
		$path1 = self::exportSegment ( $id_emv_seg_payedVP, "Inactive_Users_Anaconda_1_mois_et_demi", $dir );
		$path2 = self::exportSegment ( $id_emv_seg_payedVP, "Inactive_Users_Anaconda_8_mois", $dir );
		

		$r= array
        (
 		self::fileToArray ( $path ),
 		self::fileToArray ( $path1 ),
  		self::fileToArray ( $path2 )

  		);

		print_r(self::fileToArray ( $path ));
		
		$users=self::fileToArray ( $path1 );
		foreach ($users as $us)
	    {
	    	if($cussus=	\Business\AnacondaSubdivision::loadByEmail($us))
	    	if ($cussus->subdivised==0)
	    		unset($users[$us]);
	    	
	    		
	    	
			
		}
		
		$r= array_merge_recursive(self::fileToArray ( $path ),self::fileToArray ( $path1 ),self::fileToArray ( $path2 ));
         
		print_r($users);
		die();
	
		return $r;	}
		
		
		///////////////////////////
		
		
		static public function getInactiveUsersSB($dir) {
			// appel du fichier /fv2_YII/conf_porteur_dev.php
			$porteurMapp = Yii::app ()->params ['porteur'];
			\Controller::loadConfigForPorteur ( $porteurMapp );
		
			Yii::import ( 'ext.Class_API', true );
				
			$porteurMapp = Yii::app ()->params ['porteur'];
			\Controller::loadConfigForPorteur ( $porteurMapp );
				
			// rï¿½cupï¿½ration des parametres de l'API
			$mkt_wdsl = \Yii::app ()->params ['CMD_EMV_ACQ'] ['wdsl'];
			$mkt_login = \Yii::app ()->params ['CMD_EMV_ACQ'] ['login'];
			$mkt_pwd = \Yii::app ()->params ['CMD_EMV_ACQ'] ['pwd'];
			$mkt_key = \Yii::app ()->params ['CMD_EMV_ACQ'] ['key'];
				
			$class_api = new Class_API ( $mkt_wdsl, $mkt_login, $mkt_pwd, $mkt_key );
				
			/////////////////////////////////////////////////// Dates
				
			//J
			$dateToday = new Datetime ( date ( 'd-m-Y' ) );
				
			$dateJ = $dateToday->format('Y-m-d')."T00:00:00";
				
			$dateSub4Months = new Datetime ( date ( 'd-m-Y' ) );
			$dateSub4Months = $dateSub4Months->sub ( new DateInterval ( 'P135D' ) );
			 
			$date4= $dateSub4Months->format('Y-m-d')."T00:00:00";
				
			$token = $class_api->connexion ();
			$id_segment = \Yii::app()->params['Inactive_Users_Anaconda_4_mois_et_demi'];
			$column_name = "LAST_DATE_OPEN";
			$operator = "ISNOTBETWEEN_STATIC";
			$first_value = $date4;
			$second_value = $dateJ;
			$groupNumber = 1;
			$orderFrag= 1 ;
		
			$class_api->update_segment_criteria_recent($token,$id_segment,$column_name,$operator,$first_value,$second_value,$groupNumber,$orderFrag);
			
			$column_name = "LAST_DATE_CLICK";
			$operator = "ISNOTBETWEEN_STATIC";
			$first_value = $date4;
			$second_value = $dateJ;
			$groupNumber = 2;
			$orderFrag= 2 ;
			
			$class_api->update_segment_criteria_recent($token,$id_segment,$column_name,$operator,$first_value,$second_value,$groupNumber,$orderFrag);
		
			$class_api->closeConnection ( $token );
			
			echo "done ".$id_segment;
		
		
			
			$path = self::exportSegment ( $id_segment, "Inactive_Users_Anaconda_4_mois_et_demi", $dir );
		
			print_r(self::fileToArray ( $path ));	
		}
	
	/**
	 * ******************************************* / get Inactive Users ******************************************************************
	 */
	
	/**
	 * *********************************************** Update EMV DE ********************************************************************
	 */
	/**
	 * Mise ï¿½ jour du champs DE (modifiedShootDate) au niveau de la BDD et Smart Focus.
	 * 
	 * @author Yacine RAMI
	 * @param array $list
	 *        	Liste des emails des ouvreurs + subcampaignID
	 * @param $de Date
	 *        	de shoot Anaconda Extraite de la BDD => format : yyyy-mm-dd
	 * @param string $dir
	 *        	"../../" => execution depuis le temrinal || "../" Execution depuis une action d'un controller.
	 * @return number $period nombre de mois d'inactivite
	 */
	static public function updateEMVDE($list, $dir) {
		$de = null;
		// Recuperer le site
		$porteur = Yii::app ()->params ['porteur'];
		
		// traitement de l'arborescence du fichier d'import
		if (! file_exists ( $dir . "AnacondaData/Imports/" . $porteur )) {
			mkdir ( $dir . "AnacondaData/Imports/" . $porteur, 0777 );
		}
		
		if (! file_exists ( $dir . "AnacondaData/Imports/" . $porteur . "/" . "UpdateEMVDE" )) {
			mkdir ( $dir . "AnacondaData/Imports/" . $porteur . "/" . "UpdateEMVDE", 0777 );
		}
		
		if (! file_exists ( $dir . "AnacondaData/Imports/" . $porteur . "/" . "UpdateEMVDE" . "/" . date ( "F_j_Y" ) )) {
			mkdir ( $dir . "AnacondaData/Imports/" . $porteur . "/" . "UpdateEMVDE" . "/" . date ( "F_j_Y" ), 0777 );
		}
		
		$fileName = $dir . "AnacondaData/Imports/" . $porteur . "/" . "UpdateEMVDE" . "/" . date ( "F_j_Y" ) . "/mise_a_jour_emv_DE_" . date ( "F_j_Y_G_i_s" ) . ".txt";
		
		// creation du fichier d'import
		if (! fopen ( $fileName, "w" )) {
			return false;
		}
		
		// entete du fichier d'import
		file_put_contents ( $fileName, "EMAIL|DE_ANACONDA\n", FILE_APPEND | LOCK_EX );
		
		foreach ( $list as $key => $value ) {
			
			$user = null;
			if ($user = \Business\User::loadByEmail ( $key )) {
				// Alimentation du fichier d'import si l'utilisateur existe dans la BDD
				$campaignHistory = new \Business\CampaignHistory ();
				$campaignHistorys = $campaignHistory->seachByIdUSerIdSubCampaign ( $user->id, $value );
				$listCampaignsHistorys = $campaignHistorys->getData ();
				
				if (isset ( $listCampaignsHistorys [0]->modifiedShootDate )) {
					$de = $listCampaignsHistorys [0]->modifiedShootDate;
					$dateSubOne = new DateTime ( date ( $de ) );
					$dateSubOne->sub ( new DateInterval ( 'P1D' ) );
					$dateSubOne = $dateSubOne->format ( 'd/m/Y' );
					file_put_contents ( $fileName, "$key|$dateSubOne\n", FILE_APPEND | LOCK_EX );
				}
			}
		}
		// Si l'import est fait proceder au traitement de la BDD
		if (self::import ( $fileName )) {
			foreach ( $list as $key => $value ) {
				
				$user = null;
				if ($user = \Business\User::loadByEmail ( $key )) {
					$campaignHistory = new \Business\CampaignHistory ();
					$campaignHistorys = $campaignHistory->seachByIdUSerIdSubCampaign ( $user->id, $value );
					$listCampaignsHistorys = $campaignHistorys->getData ();
					
					if (isset ( $listCampaignsHistorys [0]->modifiedShootDate )) {
						$de = $listCampaignsHistorys [0]->modifiedShootDate;
						$dateSubOne = new DateTime ( date ( $de ) );
						$dateSubOne->sub ( new DateInterval ( 'P1D' ) );
						$listCampaignsHistorys [0]->modifiedShootDate = $dateSubOne->format ( 'Y-m-d' );
						$listCampaignsHistorys [0]->save ();
					}
				}
			}
			
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * ************************************ / Update EMV DE **********************************************************
	 */
	/**
	 * Rï¿½cupï¿½re la date de shoot du produit suivant pour les personnes qui ont reï¿½u R4 du P1 ou R2 du P2 sans acheter.
	 * 
	 * @author Zakaria CHNIBER
	 * @return Date sous format Y-m-d
	 */
	static public function getDateNextShootR4R2($idUser, $currentSubCampaign) {
		
		// récupération des objets de la subCampaign
		$campaignHistory = new \Business\CampaignHistory ();
		$mycampaignHistory = $campaignHistory->seachByIdUSerIdSubCampaign ( $idUser, $currentSubCampaign );
		$mycampaignHistory = $mycampaignHistory->getData ();
		
		$modifiedShootDate = $mycampaignHistory [0]->modifiedShootDate;
		
		$subCampaign = new \Business\SubCampaign ();
		$reflation = new \Business\SubCampaignReflation ();
		$openedlinkmail = new \Business\Openedlinkmail ();
		$positionSubCampaign = $subCampaign->getPositionBySubCampaign ( $currentSubCampaign );
		$myreflation = $reflation->getSubCampaignReflationBySubCampaign ( $currentSubCampaign );
		
		$countOpenedLinkmail = 0;
		
		// rï¿½cupï¿½ration du compteur des ouvertures de la fid currentSubCampaign qui ont effectuï¿½ des dï¿½calages de DE
		for($i = 0; $i < sizeof ( $myreflation ); $i ++) {
			// récupération des ouvertures selon les reflations
			$myopenedlinkmail [$i] = $openedlinkmail->LoadopenedlinkmailBySubCampaignReflationAndUser ( $myreflation [$i]->id, $idUser );
			
			if (($myreflation [$i]->number !== 5 && $positionSubCampaign == 1) || ($myreflation [$i]->number !== 3 && $positionSubCampaign == 2)) {
				if (is_object ( $myopenedlinkmail [$i] ) && $myopenedlinkmail [$i]->shiftDe == 1) {
					
					$countOpenedLinkmail ++;
				}
			}
		}
		
		// P1 ou P2
		if ($positionSubCampaign == 1) {
			
			if ($countOpenedLinkmail != 0) {
				
				return date ( 'Y-m-d', strtotime ( $modifiedShootDate . ' + 10 days' ) );
			} else {
				
				return date ( 'Y-m-d', strtotime ( $modifiedShootDate . ' + 13 days' ) );
			}
		} else {
			
			return date ( 'Y-m-d', strtotime ( $modifiedShootDate . ' + 7 days' ) );
		}
	}
	
	/**
	 * ********************************************************** get R4 and R2 List *******************************************************
	 */
	/**
	 * Recuperer les campaign histories des leads ayant recu la R4 P1 ou R2 P2 sans acheter.
	 * 
	 * @author Yacine RAMI
	 * @return CampaignHistory[] liste des Campaigns Histories
	 */
	static public function getR4R2List() {
		$listR4R2 = null;
		$compt = 0;
		// ======================================== Traitement R4 ========================================================//
		
		// J - 8
		$DateMin8 = new DateTime ( date ( 'Y-m-d' ) );
		$DateMin8->sub ( new DateInterval ( 'P8D' ) );
		$DateMin8 = $DateMin8->format ( 'Y-m-d' );
		
		// Recuperer les leads qui n'ont pas achete (status = 0) et qui ont recu la 4eme relance le jour même
		$listDateMin8 = \Business\CampaignHistory::loadByStatusAndModifiedShootDate ( 0, $DateMin8 );
		
		// Pour chaque Campaign History s'il s'agit du P1 ou M1, alimenter la liste
		foreach ( $listDateMin8 as $row9 ) {
			if ($row9->SubCampaign->position == 1) {
				$listR4R2 [$compt] = $row9;
				$compt ++;
			}
		}
		
		// ======================================== Traitement R2 ========================================================//
		
		// J - 4
		$DateMin4 = new DateTime ( date ( 'Y-m-d' ) );
		$DateMin4->sub ( new DateInterval ( 'P4D' ) );
		$DateMin4 = $DateMin4->format ( 'Y-m-d' );
		
		// Recuperer les leads qui n'ont pas achete (status = 0) et qui ont recu la 2eme relance le jour même
		$listDateMin4 = \Business\CampaignHistory::loadByStatusAndModifiedShootDate ( 0, $DateMin4 );
		
		// Pour chaque Campaign History s'il s'agit du P2, alimenter la liste
		foreach ( $listDateMin4 as $row5 ) {
			
			if ($row5->SubCampaign->position == 2) {
				$listR4R2 [$compt] = $row5;
				$compt ++;
			}
		}
		
		// retourner la liste
		return $listR4R2;
	}
	
	/**
	 * ************************************ / get R4 and R2 List **********************************************************
	 */
	
	/**
	 * @Author Soufiane Balkaid
	 * @Desc augmenter la grille tarifaire du client Update total indice et indice d'implication par 0
	 * 
	 * @return Boolean si le nombre d'indice d'implication = nombre nextStepSum de la table settingsGP else false
	 */
	static public function nextGridByUser($campaignHistory) {
		// recuperer le dernier gp
		if (isset ( $campaignHistory )) {
			$LastGpForUser = $campaignHistory->groupPrice;
			// recuperer settings gp
			$gpSettings = \Business\AnacondaSettings::getSettingsByGp ( $LastGpForUser );
			
			if (isset ( end ( $gpSettings )->nextStepSum )) {
				// verifier si le gp est superieur de 1 et indice d'implication = next step sum de la table gp settigns du gp courrant
				if (($campaignHistory->User->indiceImplication >= end ( $gpSettings )->nextStepSum) && $LastGpForUser > 1) {
					// update indice d'implication par 0 et augmenter le nombre des points
					
					$campaignHistory->User->updateTotalIndice ();
					return true;
				}
			}
		}
		
		return false;
	}
	
	/**
	 * @Author Soufiane Balkaid
	 * @Desc ription diminuer la grille tarifaire du client Update total indice et indice d'implication par 0
	 *
	 * @return Boolean si le nombre clickBDC = nombre nextStepSum de la table settingsGP true else false
	 */
	static public function priviousgridByUser($campaignHistory) {
		// recuperer le dernier gp
		if (isset ( $campaignHistory )) {
			
			$LastGpForUser = $campaignHistory->groupPrice;
			// recuperer le max gp de la table anacondaSettings
			$maxGP = \Business\AnacondaSettings::getMaxGp ();
			// recuperer settings gp
			$gpSettings = \Business\AnacondaSettings::getSettingsByGp ( $LastGpForUser );
			
			if (isset ( end ( $gpSettings )->durationPrevious )) {
				
				$userBehaviors = new \Business\UserBehavior ();
				// recuperer le nbr des bdc click dans la periode convenable
				$userBehaviorBdcClick = $userBehaviors->getNbrBdcClickByIdUserPeriod ( $campaignHistory->User->id, end ( $gpSettings )->durationPrevious );
				// recuperer le nbr d'achat durant la periode
				$campaignHistorys = new \Business\CampaignHistory ();
				
				$userPurchased = $campaignHistorys->getPurchasedByUserPerdiod ( $campaignHistory->User->id, end ( $gpSettings )->durationPrevious );
				
				// verifier si le gp est inferieur de max gp et nombre des bdc click = previous step click de la table gp settigns du gp courrant et nombre d'achat = 0
				if (count ( $userBehaviorBdcClick ) >= end ( $gpSettings )->previousStepClicks && $LastGpForUser < $maxGP [0]->MaxGroupPrice && count ( $userPurchased ) === 0) {
					
					$campaignHistory->User->updateTotalIndice ();
					return true;
				}
			}
		}
		
		return false;
	}
	/**
	 * **************************** PassInterCampaignR4R2 **********************************************************
	 */
	
	/**
	 * effectuer le passage ï¿½ la fid suivante pour les personnes qui ont reï¿½u la R4 de P1 ou R2 de P2
	 * 
	 * @author Zakaria CHNIBER
	 * @param CampaignHistory $campaignHistory        	
	 */
	static public function passInterCampaignR4R2($listCampaignHistory) {
		\Yii::import ( 'ext.CurlHelper' );
		
		foreach ( $listCampaignHistory as $myCampaignHistory ) {
			
			$toExecute = false;
			
			// passage effectu� pour les clients non bann�s
			if ($myCampaignHistory->User->bannReason != 1 && $myCampaignHistory->User->bannReason != 2) {
				
				$token = array ();
				// recuperer la premi�re fid en stand by.
				$stb = \Business\CampaignHistory::getLastStbByUser ( $myCampaignHistory->idUser );
				
				if ($stb != false && $stb->status != - 5) {
					// traitement fid stand by
					$token ['__m__'] = $myCampaignHistory->User->email;
					$shootDate = new \DateTime ();
					if ($stb->SubCampaign->position == 1) {
						switch ($stb->status) {
							case "-1" :
								$shootDate->sub ( new DateInterval ( 'P1D' ) );
								break;
							case "-2" :
								$shootDate->sub ( new DateInterval ( 'P3D' ) );
								break;
							case "-3" :
								$shootDate->sub ( new DateInterval ( 'P5D' ) );
								break;
							case "-4" :
								$shootDate->sub ( new DateInterval ( 'P7D' ) );
								break;
							default :
								break;
						}
					} else if ($stb->SubCampaign->position == 2) {
						switch ($stb->status) {
							case "-1" :
								break;
							case "-2" :
								$shootDate->sub ( new DateInterval ( 'P2D' ) );
								break;
							case "-3" :
								$shootDate->sub ( new DateInterval ( 'P4D' ) );
								break;
							default :
								break;
						}
					}
					
					$token ['__date__'] = $shootDate->format ( 'm/d/Y' );
					
					$token ['__h__'] = $myCampaignHistory->behaviorHour;
					
					$token ['__gp__'] = $stb->groupPrice;
					$campaign = $stb->SubCampaign->Campaign;
					
					// remplissage du statut selon le produit 1 ou 2
					if ($stb->SubCampaign->position == 1) {
						$token ['__s__'] = $campaign->ref . "_0";
					} else {
						$token ['__s__'] = $campaign->ref . "_1";
					}
					
					// enregistrement des attributs dans la BDD
					$stb->modifiedShootDate = $shootDate->format ( 'Y-m-d' );
					$stb->behaviorHour = $myCampaignHistory->behaviorHour;
					$stb->status = 0;
					$stb->save ();
					$toExecute = true;
				} else {
					
					if ($stb) {
						$stb->status = 0;
					}
					// r�cup�ration de la compagne suivante non encore re�u
					
					$nextCampaign = self::getNextCampaign ( $myCampaignHistory->idUser );
					
					if ($nextCampaign) {
						
						if (! ($subCampaign = \Business\SubCampaign::loadByCampaignAndPosition ( $nextCampaign->id, 1 )))
							return false;
							// r�cup�ration de la campaignhistory si elle existe
						$newCampaignHistory = \Business\CampaignHistory::loadByUserAndSubCampaign ( $myCampaignHistory->idUser, $subCampaign->id );
						
						// sinon creer une nouvelle
						if (! isset ( $newCampaignHistory ))
							$newCampaignHistory = new \Business\CampaignHistory ();
						
						$token ['__m__'] = $myCampaignHistory->User->email;
						
						// date de shoot de la campign suivante
						$shootDate = self::getDateNextShootR4R2 ( $myCampaignHistory->idUser, $myCampaignHistory->SubCampaign->id );
						
						$shootDateFormat = new \DateTime ( $shootDate );
						
						// dates de shoot de la campaign suivante
						$token ['__date__'] = $shootDateFormat->format ( 'm/d/Y' );
						$newCampaignHistory->modifiedShootDate = $shootDate;
						$newCampaignHistory->initialShootDate = $shootDate;
						
						// gp eventuellement changeable
						$gp = ($myCampaignHistory->groupPrice);
						$token ['__gp__'] = $gp;
						if (self::nextGridByUser ( $myCampaignHistory )) {
							$gp -= 1;
							$token ['__gp__'] = $gp;
						} else if (self::priviousgridByUser ( $myCampaignHistory )) {
							$gp += 1;
							$token ['__gp__'] = $gp;
						}
						
						$newCampaignHistory->groupPrice = $gp;
						$newCampaignHistory->status = 0;
						
						$newCampaignHistory->idUser = $myCampaignHistory->idUser;
						
						// changement de l'heure d'activit� si un click est existant
						$behaviorHour = \Business\UserBehavior::searchByIdCampaingHistory ( $myCampaignHistory->id );
						if ($behaviorHour) {
							
							$Datehour = new \DateTime ( $behaviorHour->lastDateClick );
							$newCampaignHistory->behaviorHour = $Datehour->format ( 'H' );
							$token ['__h__'] = $Datehour->format ( 'H' );
						} else {
							
							$newCampaignHistory->behaviorHour = $myCampaignHistory->behaviorHour;
							$token ['__h__'] = $myCampaignHistory->behaviorHour;
						}
						
						$newCampaignHistory->idSubCampaign = $subCampaign->id;
						$token ['__s__'] = $nextCampaign->ref . "_0";
						
						$newCampaignHistory->save ();
						$toExecute = true;
					}
				}
				if ($toExecute) {
					// recupereation du webform de passage inter fid
					$wf_interfid = \Yii::app ()->params ['wf_interfid'];
					$webForm = str_replace ( array_keys ( $token ), $token, $wf_interfid );
					
					// execution du webform
					$Curl = new \CurlHelper ();
					$Curl->setTimeout ( CURL_TIMEOUT );
					$Curl->sendRequest ( $webForm );
				}
			}
		}
		
		return true;
	}
	
	/**
	 * **************************** passInterFidPayed **********************************************************
	 */
	
	/**
	 * effectuer le passage ï¿½ la fid suivante ou ï¿½ la premiï¿½re fid en stand by, suite ï¿½ une action d'achat
	 * 
	 * @author Saad HDIDOU
	 * @param CampaignHistory $campaignHistory        	
	 */
	static public function passInterFidPayed($campaignHistory) {
		\Yii::import ( 'ext.CurlHelper' );
		
		$token = array ();
		// recuperer la premi�re fid en stand by.
		$stb = \Business\CampaignHistory::getLastStbByUser ( $campaignHistory->idUser );
		
		if ($stb && $stb->status != - 5) {
			// traitement fid stand by
			$token ['__m__'] = $campaignHistory->User->email;
			$shootDate = new \DateTime ( $campaignHistory->deliveryDate );
			if ($stb->SubCampaign->position == 1) {
				switch ($stb->status) {
					case "-1" :
						$shootDate->sub ( new DateInterval ( 'P1D' ) );
						break;
					case "-2" :
						$shootDate->sub ( new DateInterval ( 'P3D' ) );
						break;
					case "-3" :
						$shootDate->sub ( new DateInterval ( 'P5D' ) );
						break;
					case "-4" :
						$shootDate->sub ( new DateInterval ( 'P7D' ) );
						break;
					default :
						break;
				}
			} else if ($stb->SubCampaign->position == 2) {
				switch ($stb->status) {
					case "-1" :
						break;
					case "-2" :
						$shootDate->sub ( new DateInterval ( 'P2D' ) );
						break;
					case "-3" :
						$shootDate->sub ( new DateInterval ( 'P4D' ) );
						break;
					default :
						break;
				}
			}
			$token ['__date__'] = $shootDate->format ( 'm/d/Y' );
			
			$token ['__h__'] = $campaignHistory->behaviorHour;
			
			$token ['__gp__'] = $stb->groupPrice;
			$campaign = $stb->SubCampaign->Campaign;
			if ($stb->SubCampaign->position == 1) {
				$token ['__s__'] = $campaign->ref . "_0";
			} else {
				$token ['__s__'] = $campaign->ref . "_1";
			}
			
			$stb->modifiedShootDate = $shootDate->format ( 'Y-m-d' );
			$stb->status = 0;
			$stb->behaviorHour = $campaignHistory->behaviorHour;
			$stb->save ();
		} else {
			if ($stb) {
				$stb->status = 0;
			}
			
			// taritement fid suivante
			
			$nextCampaign = self::getNextCampaign ( $campaignHistory->idUser );
			if (! $nextCampaign) {
				return false;
			}
			
			$token ['__m__'] = $campaignHistory->User->email;
			$shootDate = new \DateTime ( $campaignHistory->deliveryDate );
			$shootDate->add ( new DateInterval ( 'P1D' ) );
			$token ['__date__'] = $shootDate->format ( 'm/d/Y' );
			$token ['__h__'] = $campaignHistory->behaviorHour;
			
			if (self::nextGridByUser ( $campaignHistory )) {
				$gp = ($campaignHistory->groupPrice) - 1;
				$token ['__gp__'] = $gp;
			} else {
				$gp = ($campaignHistory->groupPrice);
				$token ['__gp__'] = $gp;
			}
			
			$token ['__s__'] = $nextCampaign->ref . "_0";
			
			if (! ($subCampaign = \Business\SubCampaign::loadByCampaignAndPosition ( $nextCampaign->id, 1 )))
				return false;
			
			$newCampaignHistory = \Business\CampaignHistory::loadByUserAndSubCampaign ( $campaignHistory->idUser, $subCampaign->id );
			
			if (! isset ( $newCampaignHistory ))
				$newCampaignHistory = new \Business\CampaignHistory ();
			
			$newCampaignHistory->modifiedShootDate = $shootDate->format ( 'Y-m-d' );
			$newCampaignHistory->initialShootDate = $shootDate->format ( 'Y-m-d' );
			$newCampaignHistory->groupPrice = $gp;
			$newCampaignHistory->status = 0;
			$newCampaignHistory->behaviorHour = $campaignHistory->behaviorHour;
			$newCampaignHistory->idUser = $campaignHistory->idUser;
			$newCampaignHistory->idSubCampaign = $subCampaign->id;
			$newCampaignHistory->save ();
		}
		
		// recupereation du webform de passage inter fid
		$wf_interfid = \Yii::app ()->params ['wf_interfid'];
		$webForm = str_replace ( array_keys ( $token ), $token, $wf_interfid );
		
		// execution du webform
		$Curl = new \CurlHelper ();
		$Curl->setTimeout ( CURL_TIMEOUT );
		return $Curl->sendRequest ( $webForm );
	}
	
	/**
	 * **************************** /passInterFidPayed **********************************************************
	 */
	
	/**
	 * **************************** passNextProduct **********************************************************
	 */
	/**
	 * effectuer le passage au produit suivant
	 * 
	 * @author Saad HDIDOU
	 * @param CampaignHistory $campaignHistory        	
	 */
	static public function passNextProduct($campaignHistory) {
		$subCampaign = \Business\SubCampaign::loadByCampaignAndPosition ( $campaignHistory->SubCampaign->idCampaign, 2 );
		$dateToday = new \DateTime ();
		$newCampaignHistory = new \Business\CampaignHistory ();
		$newCampaignHistory->modifiedShootDate = $campaignHistory->modifiedShootDate;
		$newCampaignHistory->initialShootDate = $campaignHistory->initialShootDate;
		$newCampaignHistory->groupPrice = $campaignHistory->groupPrice;
		$newCampaignHistory->status = 0;
		$newCampaignHistory->behaviorHour = $campaignHistory->behaviorHour;
		$newCampaignHistory->idUser = $campaignHistory->idUser;
		$newCampaignHistory->idSubCampaign = $subCampaign->id;
		$newCampaignHistory->save ();
	}
	
	/**
	 * **************************** /passNextProduct **********************************************************
	 */
	
	/**
	 * **************************** pauseLastFid **********************************************************
	 */
	
	/**
	 * mettre en stand by la fid en shoot si le client a effectuï¿½ un achat d'une fid ancienne, en sauvegardant l'etat de la derniere reflation envoyï¿½
	 * 
	 * @author Saad HDIDOU
	 * @param CampaignHistory $campaignHistory        	
	 */
	static public function pauseLastFid($campaignHistory) {
		$lastCH = \Business\CampaignHistory::getLastCampaignHistorybyIdUSer ( $campaignHistory->idUser );
		$dateToday = new \DateTime ();
		if ($lastCH->SubCampaign->position == 2 && $lastCH->SubCampaign->Product->asile_type == 'inter' && $lastCH->modifiedShootDate == $dateToday->format ( 'Y-m-d' )) {
			return;
		}
		if ($campaignHistory->id != $lastCH->id && $lastCH->status == 0) {
			$modifiedShootDate = new DateTime ( $lastCH->modifiedShootDate );
			$interval = $dateToday->diff ( $modifiedShootDate )->format ( '%a' );
			$lastCH->SetDateStbByCampaignHistory($dateToday);
			if ($campaignHistory->SubCampaign->position == 1 && $interval < 9) {
				switch ($interval) {
					case 0 :
					case 1 :
						$lastCH->status = - 1;
						$lastCH->save ();
						break;
					
					case 2 :
					case 3 :
						$lastCH->status = - 2;
						$lastCH->save ();
						break;
					
					case 4 :
					case 5 :
						$lastCH->status = - 3;
						$lastCH->save ();
						break;
					
					case 6 :
					case 7 :
						$lastCH->status = - 4;
						$lastCH->save ();
						break;
					
					case 8 :
						$lastCH->status = - 5;
						$lastCH->save ();
						break;
					
					default :
						break;
				}
			} else if ($campaignHistory->SubCampaign->position == 2 && $interval < 6) {
				switch ($interval) {
					case 0 :
						$lastCH->status = - 1;
						$lastCH->save ();
						break;
					
					case 1 :
					case 2 :
						$lastCH->status = - 2;
						$lastCH->save ();
						break;
					
					case 3 :
					case 4 :
						$lastCH->status = - 3;
						$lastCH->save ();
						break;
					
					case 5 :
						$lastCH->status = - 5;
						$lastCH->save ();
						break;
					
					default :
						break;
				}
			}
		}
	}
	
	/**
	 * **************************** /pauseLastFid **********************************************************
	 */
	
	/**
	 * **************************** Create web form **********************************************************
	 */
	function createWebFormByCampaign($idCampaign) {
		$this->getPorteurMap ();
		
		$replyTo = $GLOBALS ["porteurMail"] [$this->PorteurMap];
		
		// recuperer le nom domaine du proeteur
		$porteurDNS = str_replace ( 'direct', 'www', $GLOBALS ["porteurDNS"] [$this->PorteurMap] );
		
		// url de la page de confirmation
		$confirmationPageURL = 'http://' . $porteurDNS . '/' . $this->PorteurMap . '/index.php?c=emv';
		
		// url de la page d'erreur
		$errorPageURL = 'http://' . $porteurDNS . '/' . $this->PorteurMap . '/erreur.php';
		
		$campaign = new \Business\Campaign ();
		
		$campaign = \Business\Campaign::load ( $idCampaign );
		
		$sub_campaing = new \Business\SubCampaign ();
		
		$sub_campaing = \Business\SubCampaign::loadByCampaignAndPosition ( $idCampaign, 1 );
		
		// eviter les boucles infinis
		$nbreTourne = 0;
		
		foreach ( $this->getParamApiCMD () as $class_api ) {
			
			if ($nbreTourne >= 20) {
				die ();
			}
			$token = $class_api->connexion ();
			// tester si les message AP/AR sont deja crees du produit 1
			if (isset ( $class_api->getIdMessage ( $campaign->ref . 'ar', 1 )->return )) {
				$contenue ['AR'] = 1;
				$idMessageAR = $class_api->getIdMessage ( $campaign->ref . 'ar', 1 )->return;
			} else {
				$contenue ['AR'] = 0;
			}
			if (isset ( $class_api->getIdMessage ( $campaign->ref . 'ap', 1 )->return )) {
				$contenue ['AP'] = 1;
				$idMessageAP = $class_api->getIdMessage ( $campaign->ref . 'ap', 1 )->return;
			} else {
				$contenue ['AP'] = 0;
			}
			
			$message ['product1'] = $contenue;
			// detruire la valeur $contenue
			unset ( $contenue );
			// tester si les message AP/AR sont deja crees du produit 2
			if (isset ( $campaign->SubCampaign [1]->Product->id )) {
				if (isset ( $class_api->getIdMessage ( $campaign->ref . 'ctar', 1 )->return )) {
					$contenue ['AR'] = 1;
					$idMessageCTAR = $class_api->getIdMessage ( $campaign->ref . 'ctar', 1 )->return;
				} else {
					$contenue ['AR'] = 0;
				}
				if (isset ( $class_api->getIdMessage ( $campaign->ref . 'ctap', 1 )->return )) {
					$contenue ['AP'] = 1;
					$idMessageCTAP = $class_api->getIdMessage ( $campaign->ref . 'ctap', 1 )->return;
				} else {
					$contenue ['AP'] = 0;
				}
				
				$message ['product2'] = $contenue;
				// tester si les messages du produit 2 sont deja crees
				if (! isset ( $idMessageCTAR ) || ! isset ( $idMessageCTAP )) {
					return $message;
				}
			}
			
			// tester si les messages du produit 1 sont deja crees
			if (! isset ( $idMessageAR ) || ! isset ( $idMessageAP )) {
				return $message;
			}
			// ajout des champs au webform
			// tester si la campaign se compose de deux produits
			if (isset ( $campaign->SubCampaign [1]->Product->id )) {
				// si il 'a deux produit il faut preparer le shoot du deuxieme
				$fields = array (
						'field' => array (
								array (
										'fieldName' => 'EMAIL',
										'required' => true,
										'displayName' => 'email',
										'inputType' => 'TEXT',
										'validationType' => 'NONE',
										'textLength' => '255' 
								),
								array (
										'fieldName' => 'DE_ANACONDA',
										'inputType' => 'HIDDEN',
										'displayName' => '' 
								),
								
								array (
										'fieldName' => 'DL_ANACONDA',
										'inputType' => 'HIDDEN',
										'displayName' => '' 
								),
								array (
										'fieldName' => 'LIVRAISON_ANACONDA',
										'inputType' => 'HIDDEN',
										'displayName' => '' 
								),
								array (
										'fieldName' => 'STATUT_ANACONDA',
										'inputType' => 'HIDDEN',
										'displayName' => '' 
								),
								array (
										'fieldName' => 'ACTIVITY_HOUR',
										'inputType' => 'HIDDEN',
										'displayName' => '' 
								) 
						) 
				);
			} else {
				$fields = array (
						'field' => array (
								array (
										'fieldName' => 'EMAIL',
										'required' => true,
										'displayName' => 'email',
										'inputType' => 'TEXT',
										'validationType' => 'NONE',
										'textLength' => '255' 
								),
								
								array (
										'fieldName' => 'DL_ANACONDA',
										'inputType' => 'HIDDEN',
										'displayName' => '' 
								),
								array (
										'fieldName' => 'LIVRAISON_ANACONDA',
										'inputType' => 'HIDDEN',
										'displayName' => '' 
								),
								
								array (
										'fieldName' => 'ACTIVITY_HOUR',
										'inputType' => 'HIDDEN',
										'displayName' => '' 
								) 
						) 
				);
			}
			// creation du webform commande du produit 1
			
			if (count ( \Business\RouterEMV::loadByTypeAndIdProductAndCompteEMV ( $campaign->SubCampaign [0]->Product->id, 'UrlPaiement', $GLOBALS ["porteurCompteEMVactif"] [$this->PorteurMap] ) ) == 0) 

			{
				$idWebForm = $class_api->CreateWebForm ( 'Commande ' . $campaign->ref, $idMessageAR, $replyTo, $fields, $confirmationPageURL, $errorPageURL );
				
				$url = $class_api->getUrlWebForm ( $idWebForm );
				
				$type = 'UrlPaiement';
				
				$this->RouterEMVSH ( $url, $type, $idCampaign, $campaign->SubCampaign [0]->Product->id );
			}
			
			$fields = array (
					'field' => array (
							array (
									'fieldName' => 'EMAIL',
									'required' => true,
									'displayName' => 'email',
									'inputType' => 'TEXT',
									'validationType' => 'NONE',
									'textLength' => '255' 
							),
							array (
									'fieldName' => 'CLIENTURN',
									'inputType' => 'HIDDEN',
									'displayName' => '' 
							) 
					) 
			);
			// creation du webform ap du produit 1
			if (count ( \Business\RouterEMV::loadByTypeAndIdProductAndCompteEMV ( $campaign->SubCampaign [0]->Product->id, 'UrlAbandonPanier', $GLOBALS ["porteurCompteEMVactif"] [$this->PorteurMap] ) ) == 0) {
				$idWebForm = $class_api->CreateWebForm ( 'Abandon panier ' . $campaign->ref, $idMessageAP, $replyTo, $fields, $confirmationPageURL, $errorPageURL );
				
				$url = $class_api->getUrlWebForm ( $idWebForm );
				
				$type = 'UrlAbandonPanier';
				
				$this->RouterEMVSH ( $url, $type, $idCampaign, $campaign->SubCampaign [0]->Product->id );
			}
			
			if (isset ( $campaign->SubCampaign [1]->Product->id )) {
				
				// creation du webform ap du produit 2
				if (count ( \Business\RouterEMV::loadByTypeAndIdProductAndCompteEMV ( $campaign->SubCampaign [1]->Product->id, 'UrlAbandonPanier', $GLOBALS ["porteurCompteEMVactif"] [$this->PorteurMap] ) ) == 0) {
					$idWebForm = $class_api->CreateWebForm ( 'Abandon panier ' . $campaign->ref . ' CT', $idMessageCTAP, $replyTo, $fields, $confirmationPageURL, $errorPageURL );
					
					$url = $class_api->getUrlWebForm ( $idWebForm );
					
					$type = 'UrlAbandonPanier CT';
					
					$this->RouterEMVSH ( $url, $type, $idCampaign, $campaign->SubCampaign [1]->Product->id );
				}
				
				$fields = array (
						'field' => array (
								array (
										'fieldName' => 'EMAIL',
										'required' => true,
										'displayName' => 'email',
										'inputType' => 'TEXT',
										'validationType' => 'NONE',
										'textLength' => '255' 
								),
								
								array (
										'fieldName' => 'DL_ANACONDA',
										'inputType' => 'HIDDEN',
										'displayName' => '' 
								),
								array (
										'fieldName' => 'LIVRAISON_ANACONDA',
										'inputType' => 'HIDDEN',
										'displayName' => '' 
								),
								
								array (
										'fieldName' => 'ACTIVITY_HOUR',
										'inputType' => 'HIDDEN',
										'displayName' => '' 
								) 
						) 
				);
				// creation du webform commande du produit 2
				if (count ( \Business\RouterEMV::loadByTypeAndIdProductAndCompteEMV ( $campaign->SubCampaign [1]->Product->id, 'UrlPaiement', $GLOBALS ["porteurCompteEMVactif"] [$this->PorteurMap] ) ) == 0) {
					$idWebForm = $class_api->CreateWebForm ( 'Commande ' . $campaign->ref . ' CT', $idMessageCTAR, $replyTo, $fields, $confirmationPageURL, $errorPageURL );
					
					$url = $class_api->getUrlWebForm ( $idWebForm );
					
					$type = 'UrlPaiement CT';
					
					$this->RouterEMVSH ( $url, $type, $idCampaign, $campaign->SubCampaign [1]->Product->id );
				}
			}
			
			$class_api->closeConnection ( $token );
			
			$nbreTourne ++;
		}
	}
	
	/**
	 * ************************************ /Create web form **********************************************************
	 */
	
	/**
	 * **************************** programmer les web form **********************************************************
	 */
	public function RouterEMVSH($url, $types, $idCamp, $IdProduct) {
		$this->getPorteurMap ();
		
		// RÃƒÆ’Ã‚Â©cupÃƒÆ’Ã‚Â©ration de champs de formulaire
		$de = 'DE_ANACONDA';
		
		$dect = 'DECT_ANACONDA';
		
		$s = 'STATUT_ANACONDA';
		
		$sl = 'LIVRAISON_ANACONDA';
		
		$dl = 'DL_ANACONDA';
		
		$Ah = 'ACTIVITY_HOUR';
		// CrÃƒÆ’Ã‚Â©ation de fichier excel avec les nouveau Urls
		
		$productM = \Business\Product::load ( $IdProduct );
		
		$emvref = \Business\Campaign::load ( $idCamp );
		
		$position = substr ( $productM->ref, - 1 );
		
		$RefComp = $emvref->ref;
		
		$RefProduct = $productM->ref;
		
		if ($types == 'UrlPaiement CT') {
			$type = 'UrlPaiement';
		} 

		elseif ($types == 'UrlAbandonPanier CT') {
			$type = 'UrlAbandonPanier';
		} else {
			$type = $types; // e.g 'UrlPaiement'
		}
		
		$url_ = $url;
		
		$url = preg_replace ( '/\s+/', '', $url_ ); // remove all whitespace
		
		if (! empty ( $type ) and ! empty ( $url )) { // Traitement seulement pour les lignes non vide
			
			$urlelements = array (
					"EMAIL_FIELD=XXXXXX",
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
					$de . "_FIELD=XXXXXX",
					$dect . "_FIELD=XXXXXX",
					$s . "_FIELD=XXXXXX",
					$dl . "_FIELD=XXXXXX",
					$Ah . "_FIELD=XXXXXX",
					$sl . "_FIELD=XXXXXX",
					"EMVADMIN14_FIELD=XXXXXX",
					"EMVADMIN17_FIELD=XXXXXX",
					"EMVADMIN3_FIELD=XXXXXX",
					"EMVADMIN82_FIELD=XXXXXX" 
			);
			
			$newurlelements = array (
					"EMAIL_FIELD=__m__",
					"CLIENTURN_FIELD=0",
					"EMVADMIN9_FIELD=__pc__",
					"EMVADMIN10_FIELD=__cc__",
					"EMVADMIN11_FIELD=__rf__",
					"LASTNAME_FIELD=__n__",
					"FIRSTNAME_FIELD=__p__",
					"TITLE_FIELD=__x__",
					"DATEOFBIRTH_FIELD=__b__",
					"EMV_POS_FIELD=" . $position . "",
					"EMV_REF_FIELD=" . $RefComp . "",
					"SITE_FIELD=__site__",
					"EMVADMIN13_FIELD=" . $RefProduct . "",
					"EMVADMIN113_FIELD=" . $position . "",
					"EMVADMIN112_FIELD=" . $RefComp . "",
					$de . "_FIELD=__date__",
					$dect . "_FIELD=__date__",
					$s . "_FIELD=__s__",
					$dl . "_FIELD=__date__",
					$Ah . "_FIELD=__h__",
					$sl . "_FIELD=__l__",
					"EMVADMIN14_FIELD=" . $position . "",
					"EMVADMIN17_FIELD=" . $position . "",
					"EMVADMIN3_FIELD=" . $RefComp . "",
					"EMVADMIN82_FIELD=" . $RefComp . "" 
			);
			
			// crÃƒÆ’Ã‚Â©ation des url avec XXXXX remplacÃƒÆ’Ã‚Â©
			$newurl = str_replace ( $urlelements, $newurlelements, $url );
			
			// Save to BDD
			$Router2 = new \Business\RouterEMV ( 'search' );
			
			$Router2->idProduct = $productM->id;
			
			$Router2->type = $type;
			
			$Router2->url = $newurl;
			
			$Router2->compteEMV = $GLOBALS ["porteurCompteEMVactif"] [$this->PorteurMap];
			
			$Router2->save ();
			
			// ********************
		}
	}
	
	/**
	 * ***************************************** /programmer les web form **********************************************************
	 */
	
	/**
	 * ****************************************************** get Openers List **************************************************************************
	 */
	/**
	 * Retourne la liste des ouvreurs pour leur dï¿½caler la Date de Shoot
	 * 
	 * @author Yacine RAMI
	 * @param int $shoot
	 *        	vague de shoot
	 */
	static public function getOpenersList($shoot) {
		// Recuperation de la date systeme
		$dateToday = date ( 'Y-m-d' );
		
		// calcul de la date d'hier
		$dateYesterday = new DateTime ( $dateToday );
		$dateYesterday->sub ( new DateInterval ( 'P1D' ) );
		$dateYesterday = $dateYesterday->format ( 'Y-m-d' );
		
		$interval = null;
		$listOpen = null;
		$list = null;
		$listDayAfter = null;
		
		if ($shoot == 1 || $shoot == 2) {
			// Recuperation des ouvertures faites le jour J (pour les vagues de shoot 1 et 2)
			$list = \Business\Openedlinkmail::LoadByDateAndShoot ( $dateToday, $shoot );
		} else {
			// Recuperation des ouvertures faites le jour J -1 (pour les vagues de shoot 3..8)
			$list = \Business\Openedlinkmail::LoadByDateAndShoot ( $dateYesterday, $shoot );
			// Recuperation des ouvertures faites le jour J (pour les vagues de shoot 3..8)
			$listDayAfter = \Business\Openedlinkmail::LoadByDateAndShoot ( $dateToday, $shoot );
		}
		
		// traitement des ouvertures faite le meme jour de la reception du linkmail dans les 20 heures
		if ($list) {
			foreach ( $list as $olm ) {
				
				// Recuperation de la date DE le moment d'ouverture
				$dateDE = new DateTime ( $olm->modifiedShootDate );
				// Recuperation de la date d'ouverture
				$dateOpen = new DateTime ( explode ( ' ', $olm->openedDate )[0] );
				
				// calcul de l'interval entre l'ouverture et la date DE
				$interval = $dateDE->diff ( $dateOpen )->format ( '%a' );
				$reflatioNumber = $olm->Subcampaignreflation->number;
				
				// traitement P1
				if ($olm->Subcampaignreflation->SubCampaign->position == 1) {
					// Ajouter l'ouverture ï¿½ la liste s'elle est effectuï¿½ le jour de l'envoi de l'email pour P1
					if (($interval == '0' && $reflatioNumber == 1) || ($interval == '2' && $reflatioNumber == 2) || ($interval == '4' && $reflatioNumber == 3) || ($interval == '6' && $reflatioNumber == 4)) {
						$campaignHistory = new \Business\CampaignHistory ();
						$campaignHistorys = $campaignHistory->seachByIdUSerIdSubCampaign ( $olm->User->id, $olm->Subcampaignreflation->SubCampaign->id );
						$listCampaignsHistorys = $campaignHistorys->getData ();
						
						if (isset ( $listCampaignsHistorys [0] ) && $listCampaignsHistorys [0]->status == 0) {
							
							$olm->shiftDe = 1;
							$olm->save ();
							$listOpen [$olm->User->email] = $olm->Subcampaignreflation->SubCampaign->id;
						}
					}
				} 				

				// traitement P2
				else if ($olm->Subcampaignreflation->SubCampaign->position == 2) {
					// Ajouter l'ouverture ï¿½ la liste s'elle est effectuï¿½ le jour de l'envoi de l'email pour P2
					if (($interval == '1' && $reflatioNumber == 1) || ($interval == '3' && $reflatioNumber == 2) || ($interval == '5' && $reflatioNumber == 3)) {
						$campaignHistory = new \Business\CampaignHistory ();
						$campaignHistorys = $campaignHistory->seachByIdUSerIdSubCampaign ( $olm->User->id, $olm->Subcampaignreflation->SubCampaign->id );
						$listCampaignsHistorys = $campaignHistorys->getData ();
						
						if (isset ( $listCampaignsHistorys [0] ) && $listCampaignsHistorys [0]->status == 0) {
							$olm->shiftDe = 1;
							$olm->save ();
							$listOpen [$olm->User->email] = $olm->Subcampaignreflation->SubCampaign->id;
						}
					}
				}
			}
		}
		
		// traitement des ouvertures faite le jour J+1 après la reception du linkmail dans les 20 heures
		if ($listDayAfter) {
			foreach ( $listDayAfter as $olm ) {
				
				// Recuperation de la date DE le moment d'ouverture
				$dateDE = new DateTime ( $olm->modifiedShootDate );
				// Recuperation de la date d'ouverture
				$dateOpen = new DateTime ( explode ( ' ', $olm->openedDate )[0] );
				
				// calcul de l'interval entre l'ouverture et la date DE
				$interval = $dateDE->diff ( $dateOpen )->format ( '%a' );
				$reflatioNumber = $olm->Subcampaignreflation->number;
				
				// traitement P1
				if ($olm->Subcampaignreflation->SubCampaign->position == 1) {
					// Ajouter l'ouverture ï¿½ la liste s'elle est effectuï¿½ le jour de l'envoi de l'email pour P1
					if (($interval == '1' && $reflatioNumber == 1) || ($interval == '3' && $reflatioNumber == 2) || ($interval == '5' && $reflatioNumber == 3) || ($interval == '7' && $reflatioNumber == 4)) {
						$campaignHistory = new \Business\CampaignHistory ();
						$campaignHistorys = $campaignHistory->seachByIdUSerIdSubCampaign ( $olm->User->id, $olm->Subcampaignreflation->SubCampaign->id );
						$listCampaignsHistorys = $campaignHistorys->getData ();
						
						if (isset ( $listCampaignsHistorys [0] ) && $listCampaignsHistorys [0]->status == 0) {
							
							$olm->shiftDe = 1;
							$olm->save ();
							$listOpen [$olm->User->email] = $olm->Subcampaignreflation->SubCampaign->id;
						}
					}
				} 				

				// traitement P2
				else if ($olm->Subcampaignreflation->SubCampaign->position == 2) {
					// Ajouter l'ouverture ï¿½ la liste s'elle est effectuï¿½ le jour de l'envoi de l'email pour P2
					if (($interval == '2' && $reflatioNumber == 1) || ($interval == '4' && $reflatioNumber == 2) || ($interval == '6' && $reflatioNumber == 3)) {
						$campaignHistory = new \Business\CampaignHistory ();
						$campaignHistorys = $campaignHistory->seachByIdUSerIdSubCampaign ( $olm->User->id, $olm->Subcampaignreflation->SubCampaign->id );
						$listCampaignsHistorys = $campaignHistorys->getData ();
						
						if (isset ( $listCampaignsHistorys [0] ) && $listCampaignsHistorys [0]->status == 0) {
							$olm->shiftDe = 1;
							$olm->save ();
							$listOpen [$olm->User->email] = $olm->Subcampaignreflation->SubCampaign->id;
						}
					}
				}
			}
		}
		
		// Retourner la liste des ouvertures pour appliquer le decalage de la date DE par la suite
		return $listOpen;
	}
	/**
	 * ***************************************************** / get Openers List **************************************************************************
	 */
	/**
	 * ****************************************************** Subdivision *********************************************************************************
	 */
	/**
	 * Recuperation des leads a integrer dans Anaconda via la Subdivision
	 * 
	 * @author Yacine RAMI
	 * @return Boolean Subdivision faite ou deja faite.
	 */
	public function Subdivision()
    {
		self::UpdateSubdivisionSegment();

        $anacoSubdivision = new \Business\AnacondaSubdivision();

        if (count($anacoSubdivision->findByAttributes( array( 'subdivised' => 0 ))) == 0) {
            //Recuperation des ids des segments VG et Client Subdivisions
            $subdivision_inactifs = \Yii::app()->params['subdivision_inactifs'];
            $subdivision_open_click = \Yii::app()->params['subdivision_open_click'];
            $subdivision_leads_doubles = \Yii::app()->params['subdivision_leads_doubles'];
            $subdivision_clients = \Yii::app()->params['subdivision_clients'];
            
            /////////////////////////// Traitement Clients ////////////////////////////////////////////////////////////
            //exporter le segment
            $fileVG = AnacondaBehavior::exportSegment($subdivision_clients, "subdivision_clients", '../');
            
            //Convertir le fichier vers des tableaux
            $list_sub = AnacondaBehavior::fileToArray($fileVG);
            
            //Supression de la ligne EMAIL
            unset($list_sub[0]);
            
            foreach ($list_sub as $mail) {
            	if ($user = \Business\User::loadByEmail($mail)) {
            		//Alimenta la table V2_anacondaSubdivision
            		$anacoSubdivision = new \Business\AnacondaSubdivision();
            		$anacoSubdivision->emailUser = $mail;
            		$anacoSubdivision->purchasedOldAnaconda = 1;
            		$anacoSubdivision->save();
            	}
            }
            
            /////////////////////////// Traitement Ouvreurs Cliqueurs ////////////////////////////////////////////////////////////
            //exporter le segment
            $fileVG = AnacondaBehavior::exportSegment($subdivision_open_click, "subdivision_open_click", '../');
            
            //Convertir le fichier vers des tableaux
            $list_sub = AnacondaBehavior::fileToArray($fileVG);
            
            //Supression de la ligne EMAIL
            unset($list_sub[0]);
            
            foreach ($list_sub as $mail) {
            	if ($user = \Business\User::loadByEmail($mail)) {
            		//Alimenta la table V2_anacondaSubdivision
            		$anacoSubdivision = new \Business\AnacondaSubdivision();
            		$anacoSubdivision->emailUser = $mail;
            		$anacoSubdivision->purchasedOldAnaconda = 2;
            		$anacoSubdivision->save();
            	}
            }
            
            /////////////////////////// Traitement Leads Doubles ////////////////////////////////////////////////////////////
            //exporter le segment
            $fileVG = AnacondaBehavior::exportSegment($subdivision_leads_doubles, "subdivision_leads_doubles", '../');
            
            //Convertir le fichier vers des tableaux
            $list_sub = AnacondaBehavior::fileToArray($fileVG);
            
            //Supression de la ligne EMAIL
            unset($list_sub[0]);
            
            foreach ($list_sub as $mail) {
            	if ($user = \Business\User::loadByEmail($mail)) {
            		//Alimenta la table V2_anacondaSubdivision
            		$anacoSubdivision = new \Business\AnacondaSubdivision();
            		$anacoSubdivision->emailUser = $mail;
            		$anacoSubdivision->purchasedOldAnaconda = 3;
            		$anacoSubdivision->save();
            	}
            }
            
            /////////////////////////// Traitement Inactifs ////////////////////////////////////////////////////////////
            //exporter le segment
            $fileVG = AnacondaBehavior::exportSegment($subdivision_inactifs, "subdivision_inactifs", '../');
            
            //Convertir le fichier vers des tableaux
            $list_sub = AnacondaBehavior::fileToArray($fileVG);
            
            //Supression de la ligne EMAIL
            unset($list_sub[0]);
            
            foreach ($list_sub as $mail) {
            	if ($user = \Business\User::loadByEmail($mail)) {
            		//Alimenta la table V2_anacondaSubdivision
            		$anacoSubdivision = new \Business\AnacondaSubdivision();
            		$anacoSubdivision->emailUser = $mail;
            		$anacoSubdivision->purchasedOldAnaconda = 4;
            		$anacoSubdivision->save();
            	}
            }		
            
            //success
            return true;
            
        } else {
        	
        	//error
            return false;
        }
    }
	/**
	 * *************************** Is Subdivised *************************************************
	 */
	/**
	 * cette fonction retourne true si la subdivision est faite, sinon : false
	 * 
	 * @author Yacine RAMI
	 * @return boolean
	 */
	public function isSubdivised() {
		$anacoSubdivision = new \Business\AnacondaSubdivision ();
		
		if (count ( $anacoSubdivision->findAll () ) == 0)
			return false;
		return true;
	}
	/**
	 * *************************** / Is Subdivised *************************************************
	 */
	/**
	 * ***************************************************** / Subdivision ********************************************************************************
	 */
	public function GetListWebFormByIdCampaign($idCampaign) {
		$campaign = new \Business\Campaign ();
		$campaign = \Business\Campaign::load ( $idCampaign );
		
		$ListWebForm ['Product1'] = \Business\RouterEMV::loadByIdProduct ( $campaign->SubCampaign [0]->Product->id );
		if (isset ( $campaign->SubCampaign [1]->Product->id )) {
			
			$ListWebForm ['Product2'] = \Business\RouterEMV::loadByIdProduct ( $campaign->SubCampaign [1]->Product->id );
		}
		return $ListWebForm;
	}
	public function GetListSegmentByIdCampaign($idCampaign) {
		$campaign = new \Business\Campaign ();
		$campaign = \Business\Campaign::load ( $idCampaign );
		
		$ListSegment ['Product1'] = \Business\AnacondaSegment::loadByIdProduct ( $campaign->SubCampaign [0]->Product->id );
		
		if (isset ( $campaign->SubCampaign [1]->Product->id )) {
			$ListSegment ['Product2'] = \Business\AnacondaSegment::loadByIdProduct ( $campaign->SubCampaign [1]->Product->id );
		}
		return $ListSegment;
	}
	public function deleteSegmentById($idSegment) {
		// $this->getPorteurMap();
		$nbrtourneeee = 0;
		// foreach ( $this->getParamApiCMD () as $class_api ) {
		$anacondaSegment = new \Business\AnacondaSegment ();
		$anacondaSegment->deleteSegment ( $idSegment );
		// $token = $class_api->connexion();
		// $class_api->deleteSegment($idSegment);
		// $class_api->closeConnection($token);
		// $nbrtourneeee++;
		// if($nbrtourneeee>10)
		// // die;
		// }
	}
	public function CreateSbSegment() {
		$this->getPorteurMap ();
		$compteEMV = $GLOBALS ["porteurCompteEMVactif"] [$this->PorteurMap];
		$anaconda_segment = new \Business\AnacondaSegment ();
		$firstSC = \Business\SubCampaign::loadfirst ();
		
		foreach ( $this->getParamApiCMD () as $class_api ) {
			$token = $class_api->connexion ();
			// P1
			$idsegment_ldv = $class_api->createSegment ( 'segment_api_sb_ldv' );
			$anaconda_segment->createSegment ( $idsegment_ldv, 'segment_api_sb_ldv', 'Soft Bounce P1', $firstSC->id, $compteEMV );
			$class_api->critereDateAbsoluteSB ( $idsegment_ldv, 0, '', '' );
			
			$idsegment_r1 = $class_api->createSegment ( 'segment_api_sb_r1' );
			$anaconda_segment->createSegment ( $idsegment_r1, 'segment_api_sb_r1', 'Soft Bounce P1', $firstSC->id, $compteEMV );
			$class_api->critereDateAbsoluteSB ( $idsegment_r1, 2, '', '' );
			
			$idsegment_r2 = $class_api->createSegment ( 'segment_api_sb_r2' );
			$anaconda_segment->createSegment ( $idsegment_r2, 'segment_api_sb_r2', 'Soft Bounce P1', $firstSC->id, $compteEMV );
			$class_api->critereDateAbsoluteSB ( $idsegment_r2, 4, '', '' );
			
			$idsegment_r3 = $class_api->createSegment ( 'segment_api_sb_r3' );
			$anaconda_segment->createSegment ( $idsegment_r3, 'segment_api_sb_r3', 'Soft Bounce P1', $firstSC->id, $compteEMV );
			$class_api->critereDateAbsoluteSB ( $idsegment_r3, 6, '', '' );
			
			$idsegment_r4 = $class_api->createSegment ( 'segment_api_sb_r4' );
			$anaconda_segment->createSegment ( $idsegment_r4, 'segment_api_sb_r4', 'Soft Bounce P1', $firstSC->id, $compteEMV );
			$class_api->critereDateAbsoluteSB ( $idsegment_r4, 8, '', '' );
			
			// P2
			$idsegment_ctldv = $class_api->createSegment ( 'segment_api_sb_ctldv' );
			$anaconda_segment->createSegment ( $idsegment_ctldv, 'segment_api_sb_ctldv', 'Soft Bounce P2', $firstSC->id, $compteEMV );
			$class_api->critereDateAbsoluteSB ( $idsegment_ctldv, 1, '', '' );
			
			$idsegment_ctr1 = $class_api->createSegment ( 'segment_api_sb_ctr1' );
			$anaconda_segment->createSegment ( $idsegment_ctr1, 'segment_api_sb_ctr1', 'Soft Bounce P2', $firstSC->id, $compteEMV );
			$class_api->critereDateAbsoluteSB ( $idsegment_ctr1, 3, '', '' );
			
			$idsegment_ctr2 = $class_api->createSegment ( 'segment_api_sb_ctr2' );
			$anaconda_segment->createSegment ( $idsegment_ctr2, 'segment_api_sb_ctr2', 'Soft Bounce P2', $firstSC->id, $compteEMV );
			$class_api->critereDateAbsoluteSB ( $idsegment_ctr2, 5, '', '' );
			
			$class_api->closeConnection ( $token );
		}
	}
	public function updateSbSegment($posProduct, $idTrigger, $inter) {
		$this->getPorteurMap ();
		
		foreach ( $this->getParamApiCMD () as $class_api ) {
			$token = $class_api->connexion ();
			
			if ($posProduct == 1) {
				echo "pos 1<br/>";
				$SbSegments = \Business\AnacondaSegment::loadSbSegments ( 'Soft Bounce P1' );
				if (empty ( $SbSegments )) {
					$this->CreateSbSegment ();
					$SbSegments = \Business\AnacondaSegment::loadSbSegments ( 'Soft Bounce P1' );
				}
				foreach ( $SbSegments as $SbSegment ) {
					if (strpos ( $SbSegment->nameSegment, '_ldv' )) {
						$class_api->critereSerieAction ( $SbSegment->idSegment, $idTrigger, 1, '', 1 );
					} else if (strpos ( $SbSegment->nameSegment, '_r1' )) {
						$class_api->critereSerieAction ( $SbSegment->idSegment, $idTrigger, 2, '', 1 );
					} else if (strpos ( $SbSegment->nameSegment, '_r2' )) {
						$class_api->critereSerieAction ( $SbSegment->idSegment, $idTrigger, 3, '', 1 );
					} else if (strpos ( $SbSegment->nameSegment, '_r3' )) {
						$class_api->critereSerieAction ( $SbSegment->idSegment, $idTrigger, 4, '', 1 );
					} else if (strpos ( $SbSegment->nameSegment, '_r4' )) {
						$class_api->critereSerieAction ( $SbSegment->idSegment, $idTrigger, 5, '', 1 );
					}
				}
			} 

			else if ($posProduct == 2) {
				$SbSegments = \Business\AnacondaSegment::loadSbSegments ( 'Soft Bounce P2' );
				if (empty ( $SbSegments )) {
					$this->CreateSbSegment ();
					$SbSegments = \Business\AnacondaSegment::loadSbSegments ( 'Soft Bounce P2' );
				}
				foreach ( $SbSegments as $SbSegment ) {
					if ($inter == 1) {
						if (strpos ( $SbSegment->nameSegment, '_ctldv' )) {
							$class_api->critereSerieAction ( $SbSegment->idSegment, $idTrigger, 1, '', 1 );
						} else if (strpos ( $SbSegment->nameSegment, '_ctr1' )) {
							$class_api->critereSerieAction ( $SbSegment->idSegment, $idTrigger, 2, '', 1 );
						} else if (strpos ( $SbSegment->nameSegment, '_ctr2' )) {
							$class_api->critereSerieAction ( $SbSegment->idSegment, $idTrigger, 3, '', 1 );
						}
					} else {
						if (strpos ( $SbSegment->nameSegment, '_ctr1' )) {
							$class_api->critereSerieAction ( $SbSegment->idSegment, $idTrigger, 1, '', 1 );
						} else if (strpos ( $SbSegment->nameSegment, '_ctr2' )) {
							$class_api->critereSerieAction ( $SbSegment->idSegment, $idTrigger, 2, '', 1 );
						}
					}
				}
			}
			$class_api->closeConnection ( $token );
		}
	}
	public function setSb($dir) {
		$segmentsP1 = \Business\AnacondaSegment::loadSbSegments ( 'Soft Bounce P1' );
		$segmentsP2 = \Business\AnacondaSegment::loadSbSegments ( 'Soft Bounce P2' );
		$segments = array_merge ( $segmentsP1, $segmentsP2 );
		
		$listSB = array ();
		
		foreach ( $segments as $segment ) {
			$path = self::exportSegment ( $segment->idSegment, "SoftBounce", $dir );
			$list = self::fileToArray ( $path );
			unset ( $list [0] );
			$listSB = array_merge ( $listSB, $list );
		}
		
		// $dateToday = date('Y-m-d');
		$dateToday = '2016-07-05';
		$shooted = \Business\Reflationuser::loadByShootDate ( $dateToday );
		
		foreach ( $listSB as $email ) {
			$user = \Business\User::loadByEmail ( $email );
			if (isset ( $user )) {
				$user->countSoftBounce ++;
				$user->save ();
			}
		}
		
		foreach ( $shooted as $shoot ) {
			if (! in_array ( $shoot->User->email, $listSB )) {
				$shoot->User->countSoftBounce = 0;
				$shoot->User->save ();
			}
		}
	}
	
	/**
	 * ********************************************* Shoot Plannification Subdivision**************************************************************
	 */
	/**
	 * Creation d'un fichier d'import pour initialiser la fiche des leads subdivision Anaconda au niveau de Smart Focus.
	 * 
	 * @author Yacine RAMI
	 * @param array $list
	 *        	emails a integrer
	 * @param string $campaign
	 *        	la premiere FID Anaconda
	 * @param string $shootDate
	 *        	shoot
	 * @param string $dir
	 *        	"../../" => execution depuis le temrinal || "../" Execution de puis une action d'un controller.
	 * @return string du fichier d'import cree
	 */
	static public function ShootPlanSubdivision($list, $campaign, $shootDate) {
		$base_directory = "../AnacondaData/Imports/";
		// Recuperer le site
		$porteur = Yii::app ()->params ['porteur'];
		
		// traitement de l'arborescence du fichier d'import
		if (! file_exists ( $base_directory . $porteur )) {
			mkdir ( $base_directory . $porteur, 0777 );
		}
		if (! file_exists ( $base_directory . $porteur . "/Subdivision/" )) {
			mkdir ( $base_directory . $porteur . "/Subdivision/", 0777 );
		}
		
		if (! file_exists ( $base_directory . $porteur . "/Subdivision/" . $campaign )) {
			mkdir ( $base_directory . $porteur . "/Subdivision/" . $campaign, 0777 );
		}
		
		if (! file_exists ( $base_directory . $porteur . "/Subdivision/" . $campaign . "/" . date ( "F_j_Y" ) )) {
			mkdir ( $base_directory . $porteur . "/Subdivision/" . $campaign . "/" . date ( "F_j_Y" ), 0777 );
		}
		
		$fileName = $base_directory . $porteur . "/Subdivision/" . $campaign . "/" . date ( "F_j_Y" ) . "/subdivision_anaconda_" . date ( "F_j_Y_G_i_s" ) . ".txt";
		
		// creation du fichier d'import
		fopen ( $fileName, "w" );
		
		if (sizeof ( $list ) <= 501) {
			
			// traitement d'un fichier ayant un nombre de leads inferieur e 500 pour les integrer dans Anaconda.
			for($counter = 0; $counter < sizeof ( $list ); $counter ++) {
				
				if ($counter == 0) {
					file_put_contents ( $fileName, "EMAIL|GP_ANACONDA|DE_ANACONDA|SD_ANACONDA|STATUT_ANACONDA|ACTIVITY_HOUR\n", FILE_APPEND | LOCK_EX );
				} else {
					file_put_contents ( $fileName, $list [$counter] . "|" . self::getSubdivisionGPByMail ( $list [$counter] ) . "|" . $shootDate . "|" . $shootDate . "|" . $campaign . "_0|" . "8\n", FILE_APPEND | LOCK_EX );
				}
			}
		} else if (sizeof ( $list ) > 501 && sizeof ( $list ) <= 1001) {
			
			// traitement d'un fichier ayant un nombre de leads entre 500 et 1000 pour les integrer dans Anaconda.
			for($counter = 0; $counter < round ( sizeof ( $list ) / 2 ); $counter ++) {
				
				if ($counter == 0) {
					file_put_contents ( $fileName, "EMAIL|GP_ANACONDA|DE_ANACONDA|SD_ANACONDA|STATUT_ANACONDA|ACTIVITY_HOUR\n", FILE_APPEND | LOCK_EX );
				} else {
					file_put_contents ( $fileName, $list [$counter] . "|" . self::getSubdivisionGPByMail ( $list [$counter] ) . "|" . $shootDate . "|" . $shootDate . "|" . $campaign . "_0|" . "5\n", FILE_APPEND | LOCK_EX );
				}
			}
			
			for($counter = round ( sizeof ( $list ) / 2 ); $counter < sizeof ( $list ); $counter ++) {
				
				file_put_contents ( $fileName, $list [$counter] . "|" . self::getSubdivisionGPByMail ( $list [$counter] ) . "|" . $shootDate . "|" . $shootDate . "|" . $campaign . "_0|" . "9\n", FILE_APPEND | LOCK_EX );
			}
		} else if (sizeof ( $list ) > 1001 && sizeof ( $list ) <= 2001) {
			
			// traitement d'un fichier ayant un nombre de leads entre 500 et 1000 pour les integrer dans Anaconda.
			for($counter = 0; $counter < round ( sizeof ( $list ) / 4 ); $counter ++) {
				
				if ($counter == 0) {
					file_put_contents ( $fileName, "EMAIL|GP_ANACONDA|DE_ANACONDA|SD_ANACONDA|STATUT_ANACONDA|ACTIVITY_HOUR\n", FILE_APPEND | LOCK_EX );
				} else {
					file_put_contents ( $fileName, $list [$counter] . "|" . self::getSubdivisionGPByMail ( $list [$counter] ) . "|" . $shootDate . "|" . $shootDate . "|" . $campaign . "_0|" . "6\n", FILE_APPEND | LOCK_EX );
				}
			}
			
			for($counter = round ( sizeof ( $list ) / 4 ); $counter < round ( sizeof ( $list ) / 2 ); $counter ++) {
				
				file_put_contents ( $fileName, $list [$counter] . "|" . self::getSubdivisionGPByMail ( $list [$counter] ) . "|" . $shootDate . "|" . $shootDate . "|" . $campaign . "_0|" . "10\n", FILE_APPEND | LOCK_EX );
			}
			
			for($counter = round ( sizeof ( $list ) / 2 ); $counter < round ( (sizeof ( $list ) * 3) / 4 ); $counter ++) {
				
				file_put_contents ( $fileName, $list [$counter] . "|" . self::getSubdivisionGPByMail ( $list [$counter] ) . "|" . $shootDate . "|" . $shootDate . "|" . $campaign . "_0|" . "12\n", FILE_APPEND | LOCK_EX );
			}
			
			for($counter = round ( (sizeof ( $list ) * 3) / 4 ); $counter < sizeof ( $list ); $counter ++) {
				
				file_put_contents ( $fileName, $list [$counter] . "|" . self::getSubdivisionGPByMail ( $list [$counter] ) . "|" . $shootDate . "|" . $shootDate . "|" . $campaign . "_0|" . "16\n", FILE_APPEND | LOCK_EX );
			}
		} else if (sizeof ( $list ) > 2001) {
			
			// traitement d'un fichier ayant un nombre de leads superieur e 2000 pour les integrer dans Anaconda.
			for($counter = 0; $counter < round ( sizeof ( $list ) / 6 ); $counter ++) {
				
				if ($counter == 0) {
					file_put_contents ( $fileName, "EMAIL|GP_ANACONDA|DE_ANACONDA|SD_ANACONDA|STATUT_ANACONDA|ACTIVITY_HOUR\n", FILE_APPEND | LOCK_EX );
				} else {
					file_put_contents ( $fileName, $list [$counter] . "|" . self::getSubdivisionGPByMail ( $list [$counter] ) . "|" . $shootDate . "|" . $shootDate . "|" . $campaign . "_0|" . "6\n", FILE_APPEND | LOCK_EX );
				}
			}
			
			for($counter = round ( sizeof ( $list ) / 6 ); $counter < round ( (sizeof ( $list ) * 2) / 6 ); $counter ++) {
				
				file_put_contents ( $fileName, $list [$counter] . "|" . self::getSubdivisionGPByMail ( $list [$counter] ) . "|" . $shootDate . "|" . $shootDate . "|" . $campaign . "_0|" . "10\n", FILE_APPEND | LOCK_EX );
			}
			
			for($counter = round ( (sizeof ( $list ) * 2) / 6 ); $counter < round ( sizeof ( $list ) / 2 ); $counter ++) {
				
				file_put_contents ( $fileName, $list [$counter] . "|" . self::getSubdivisionGPByMail ( $list [$counter] ) . "|" . $shootDate . "|" . $shootDate . "|" . $campaign . "_0|" . "12\n", FILE_APPEND | LOCK_EX );
			}
			
			for($counter = round ( sizeof ( $list ) / 2 ); $counter < round ( (sizeof ( $list ) * 4) / 6 ); $counter ++) {
				
				file_put_contents ( $fileName, $list [$counter] . "|" . self::getSubdivisionGPByMail ( $list [$counter] ) . "|" . $shootDate . "|" . $shootDate . "|" . $campaign . "_0|" . "16\n", FILE_APPEND | LOCK_EX );
			}
			
			for($counter = round ( (sizeof ( $list ) * 4) / 6 ); $counter < round ( (sizeof ( $list ) * 5) / 6 ); $counter ++) {
				
				file_put_contents ( $fileName, $list [$counter] . "|" . self::getSubdivisionGPByMail ( $list [$counter] ) . "|" . $shootDate . "|" . $shootDate . "|" . $campaign . "_0|" . "20\n", FILE_APPEND | LOCK_EX );
			}
			
			for($counter = round ( (sizeof ( $list ) * 5) / 6 ); $counter < sizeof ( $list ); $counter ++) {
				
				file_put_contents ( $fileName, $list [$counter] . "|" . self::getSubdivisionGPByMail ( $list [$counter] ) . "|" . $shootDate . "|" . $shootDate . "|" . $campaign . "_0|" . "23\n", FILE_APPEND | LOCK_EX );
			}
		}
		
		// retourner le chemin du fichier d'import cree
		return $fileName;
	}
	
	/**
	 * ******************************************************************* /Shoot Plannficiation Subdivision ********************************************************************************************
	 */
	
	/**
	 * ****************************************************************** Segmentation Subdivision ***********************************************************************************
	 */
	
	/**
	 * segmentation des lead subdivison, import dans SF et insertion dans la table campaginHistory
	 * 
	 * @author Yacine RAMI
	 * @param array $list
	 *        	emails a integrer
	 * @param string $campaign
	 *        	la premiere FID Anaconda
	 * @param string $shootDate
	 *        	shoot
	 * @return
	 *
	 */
	static public function SegmentationSubdivision($list, $shootDate) {
		// first campaign
		$campaign = self::getFirstCampaign ();
		// shoot planification
		$pathImport = self::ShootPlanSubdivision ( $list, $campaign->ref, $shootDate );
		
		// import du fichier
		if (! (self::import ( $pathImport )))
			return 2;
			// id subCampaign
		if (! ($subCampaign = \Business\SubCampaign::loadByCampaignAndPosition ( $campaign->id, 1 )))
			return 3;
		$idSubCampaign = $subCampaign->id;
		// convertir le fichier en tableau
		$list = self::fileToArray ( $pathImport );
		// eliminer la premiere ligne qui contient les noms des champs
		unset ( $list [0] );
		// list des email introuvables
		$unfounded = array ();
		// parcourir le tableau afin d inserer chaque ligne dans la table campaignHistory
		foreach ( $list as $ligne ) {
			$ligne = explode ( '|', $ligne );
			// EMAIL|GP_ANACONDA|DE_ANACONDA|SD_ANACONDA|STATUT_ANACONDA|ACTIVITY_HOUR
			$de = DateTime::createFromFormat ( 'd/m/Y', $ligne [2] );
			$sd = DateTime::createFromFormat ( 'd/m/Y', $ligne [3] );
			$s = explode ( '_', $ligne [4] );
			if (($user = \Business\User::loadByEmail ( $ligne [0] ))) {
				
				$campaignHistory = new \Business\CampaignHistory ();
				$campaignHistory->modifiedShootDate = $de->format ( 'Y-m-d' );
				$campaignHistory->initialShootDate = $sd->format ( 'Y-m-d' );
				$campaignHistory->groupPrice = $ligne [1];
				$campaignHistory->status = 0;
				$campaignHistory->behaviorHour = $ligne [5];
				$campaignHistory->idUser = $user->id;
				$campaignHistory->idSubCampaign = $idSubCampaign;
				$campaignHistory->save ();
			} else {
				$unfounded [] = $ligne [0];
			}
		}
		if (empty ( $unfounded ))
			return 1;
		else
			return $unfounded;
	}
	
	/**
	 * ************************************************************************ / Segmentation Subdivision ************************************************************************
	 */
	
	/**
	 * ********************************************************************** Subdivise By Number ******************************************************************************
	 */
	/**
	 * Segmenter un nombre de leads recuperes de la tete de file subdivision (V2_anacondaSubdivision)
	 * 
	 * @author Yacine RAMI
	 * @param integer $number        	
	 * @return boolean
	 */
	public function SubdiviseByNumber($number) {
		
		$listEmails = array ();
		$listEmails [0] = "EMAIL";
		
		// Recuperer un nombre de lead a subdiviser
		$AnacondaSubs = \Business\AnacondaSubdivision::loadFirstNbr ( $number );
		
		// Plannifier le shoot pour les leads recupereres (si la liste n'est pas vide)
		if (count ( $AnacondaSubs ) != 0) {
			// J+1
			$shootDate = new Datetime ( date ( 'd-m-Y' ) );
			$shootDate = $shootDate->add ( new DateInterval ( 'P1D' ) );
			
			// Alimenter une liste des emails
			foreach ( $AnacondaSubs as $row ) {
				$listEmails [] = $row->emailUser;
			}
			
			$shootDate = $shootDate->format ( 'd/m/Y' );
			
			// Segmentation des leads subdivision
			$unfound = \AnacondaBehavior::SegmentationSubdivision ( $listEmails, $shootDate );
			$shootDate = new Datetime ( date ( 'd-m-Y' ) );
			$shootDate = $shootDate->add ( new DateInterval ( 'P1D' ) );
			foreach ( $AnacondaSubs as $row ) 
			{				
				$row->subdivised = 1;
				$row->save ();
					
				// mise a jour de la date d'initiation Anaconda
				if ($user = \Business\User::loadByEmail ( $row->emailUser )) {
					$user->intialDate = $shootDate->format ( \Yii::app ()->params ['dbDateTime'] );
					$user->dateGpChange = $shootDate->format ( \Yii::app ()->params ['dbDateTime'] );
					$user->setOriginByUser(1);
					$user->save ();
				}
			}
				
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * ********************************************************************** / Subdivse By Number ******************************************************************************
	 */
	static public function getSubdivisionGPByMail($email) {
			/*
		 * $invoice = new \Business\Invoice ();
		 * $nbrPurchasedduring4 = $invoice->getNbrPurchased ( $email, 1 );
		 * $nbrPurchasedbefore4 = $invoice->getNbrPurchased ( $email, 2 );
		 *
		 * if ($nbrPurchasedduring4 >= 2) {
		 * return 1;
		 * }
		 * if ($nbrPurchasedduring4 == 1 || ($nbrPurchasedduring4 == 0 && $nbrPurchasedbefore4 >= 2)) {
		 *
		 * return 2;
		 * }
		 * if ($nbrPurchasedduring4 == 0 || $nbrPurchasedbefore4 <= 1) {
		 *
		 * return 3;
		 * }
		 * return 3;
		 */
		$invoice = new \Business\Invoice ();
		if ($tag = \Business\AnacondaSubdivision::loadTagByEmail ( $email )) {
			$nbrPurchasedduring4 = $invoice->getNbrPurchased ( $email, 1 );
			$nbrPurchasedbefore4 = $invoice->getNbrPurchased ( $email, 2 );
			switch ($tag) {
				case 4 :
				case 2 :
					return 2;
				case 3 :
					return 3;
				case 1 :
					if ($nbrPurchasedduring4 >= 2 || $nbrPurchasedduring4 == 1 && $nbrPurchasedbefore4 >= 1) {
						return 1;
					} elseif ($nbrPurchasedbefore4 >= 2 || $nbrPurchasedduring4 == 1) {
						return 2;
					} elseif ($nbrPurchasedbefore4 == 1) {
						return 3;
					}
				default :
					break;
			}
		}
		return 1;	
	}
	
	/**
	 * retourne le gp correspondant pour la reactivation
	 * 
	 * @param unknown $email        	
	 * @return number GP
	 */
	static public function getGridPriceReactivationByUser($email) {
		switch (\Business\Invoice::getPurchasedAnaconda ( $email )) {
			case 0 :
				$GP = 3;
				break;
			case 1 :
				$GP = 2;
				break;
			
			default :
				$GP = 1;
				break;
		}
		return $GP;
	}

//************************************************************************** REACTIVATION ***********************************************************//	
	
	/**

	 * @author Anas HILAMA
	 * @param Id User
	 * @return BehaviourHour de la derniere action 
	 */
	
	static public function getBehaviourHourLastActionByUser($id) {
		
		$behaviourHour = 0 ; 
		$user = new \Business\User() ;
		
		$email = $user->getEmailByIdUser($id) ;
		
		
		echo $email ; 
		
		//*************************************** Les achats ***************************************************//
		// Les achats depuis V2
		$achat = \Business\Invoice::LoadByEmailPayedInv($email) ;
		// Les achats depuis V1
		$achat += \Business\Invoice_V1::LoadByEmailPayedInv($email) ;
		
		 
		
		if(count($achat) != 0) 
		{ 			 
					$behaviourHour = new \DateTime(reset($achat)) ; 	
					$behaviourHour = $behaviourHour->format('H');
					return  'Achats'.$behaviourHour ;  		}			
		
		
		unset($achat) ;	
		//*************************************** Les BdcClicks ************************************************//
		
		$clicks = \Business\UserBehavior::LoadBdcClickDateByID($id) ; 			
		
		if(count($clicks) != 0)
				{
					$behaviourHour = new \DateTime(reset($clicks)) ;   
					$behaviourHour = $behaviourHour->format('H');
					return 'Clicks'. $behaviourHour ;
		}
		
		unset($clicks) ;
		//*************************************** Les Ouvertures ***********************************************//
		
		$ouvertures = \Business\Openedlinkmail::LoadOuvertureDateByID($id) ;
		
		if(count($ouvertures) != 0)
		
		{  
			$behaviourHour = new \DateTime(reset($ouvertures)) ;   
			$behaviourHour = $behaviourHour->format('H');
			return  'Ouvertures'.$behaviourHour ; 
		}
		
		return $behaviourHour ; 
	}
	
	//************************************************************************** FIN  REACTIVATION ***********************************************************//	
	
	/**
	 * ********************************************* Stress Testing Functions**************************************************************
	 */
	/**
	 * Generation des leads � importer au niveau de smartfocus .
	 * 
	 * @author Badre Boussouni
	 * @param
	 *        	integer le nombre de leads � importer
	 * @return string du fichier d'import cree
	 */
	static public function generationLeads($nbrsEmails) {
		$url = "../AnacondaData/generated";
		is_dir ( $url ) || mkdir ( $url, 0777, true );
		
		$urlFileVp = $url . "/generatedLeadsVp.txt";
		$urlFileR3 = $url . "/generatedLeadsR3.txt";
		file_put_contents ( $urlFileVp, "EMAIL|EMVADMIN4|EMVADMIN49\n" );
		file_put_contents ( $urlFileR3, "EMAIL|EMVADMIN4|EMVADMIN49\n" );
		$nbrR3 = $nbrsEmails - ($nbrsEmails * 1 / 4);
		$nbrVp = $nbrsEmails - $nbrR3;
		
		for($i = 0; $i < $nbrR3; $i ++) {
			$email = "anaconda_stress_" . $i . "@gmail.com";
			$EMVADMIN4 = 1;
			$EMVADMIN49 = date ( 'Y-m-d', strtotime ( "-5 days" ) );
			file_put_contents ( $urlFileR3, $email . "|" . $EMVADMIN4 . "|" . $EMVADMIN49 . "\n", FILE_APPEND );
		}
		for($j = 0; $j < $nbrVp; $j ++) {
			$email = "anaconda_stress_" . $i . "@gmail.com";
			$EMVADMIN4 = 2;
			$EMVADMIN49 = date ( 'Y-m-d', strtotime ( "-1 day" ) );
			file_put_contents ( $urlFileVp, $email . "|" . $EMVADMIN4 . "|" . $EMVADMIN49 . "\n", FILE_APPEND );
			$i ++;
		}
		
		return $urlFileVp;
	}
	static public function InscriptionLeads($nbrEmails) {
		ini_set ( 'display_errors', 1 );
		ini_set ( 'display_startup_errors', 1 );
		error_reporting ( E_ALL );
		$nbrIn = $nbrEmails - ($nbrEmails * 1 / 4);
		
		for($i = 0; $i < $nbrIn; $i ++) {
			$user = new \Business\User ();
			$userI = new \Business\Internaute ();
			$userL = new \Business\LeadAffiliatePlatform ();
			// $userInvoice = new Business\Invoice();
			// $userRinvoice = new Business\RecordInvoice();
			
			$userInvoice1 = new Business\Invoice_V1 ();
			
			$user->email = "anaconda_stress_" . $i . "@gmail.com";
			$user->firstName = "test" . $i;
			$user->validPhoneNumber = - 1;
			$user->dateValidPhone = date ( '0000-00-00 00:00' );
			$user->addressComp = " ";
			$user->state = " ";
			$user->visibleDesinscrire = 0;
			
			$userI->Email = "anaconda_stress_" . $i . "@gmail.com";
			$userI->Address = "test" . $i;
			$userI->CP = '';
			$userI->Country = '';
			$userI->Phone = " ";
			$userI->CreationDate = date ( 'Y-m-d' );
			$userI->UpdateTS = date ( 'Y-m-d' );
			$userI->visibleDesinscrire = 0;
			$userI->CompteEMVactif = "R_MAY";
			$userI->Comment = '';
			$userI->DateValidationCheck = date ( 'Y-m-d' );
			$userI->countSend = 0;
			
			$userI->save ();
			
			$userL->idUser = $user->id;
			$userL->idAffiliatePlatfom = 25;
			$userL->idAffiliatePlatformSubId = 108;
			$userL->idAffiliateCampaign = 12;
			$userL->idSite = 1;
			$userL->creationDate = date ( 'Y-m-d', strtotime ( "-8 days" ) );
			$userL->promo = "ps_spevmfrv1";
			$userL->isDouble = 0;
			$userL->doubleOptin = 0;
			$userL->idTrackingCodeV2 = 808;
			$userL->Payed = 0;
			$userL->device = "computer";
			if (! $userL->save ())
				return "erreur lead !!";
			
			/*
			 * $userInvoice->emailUser = "test".$i."@gmail.com"; $userInvoice->creationDate = date('Y-m-d'); $userInvoice->refInterne= "v2_vp".$i; $userInvoice->codeSite="fr"; $userInvoice->chrono=' '; $userInvoice->date_ouverture=date('Y-m-d'); $userInvoice->countSend=0; $userInvoice->lastSend=date('Y-m-d'); if(!$userInvoice->save())return "erreur invoice !!"; $userRinvoice->idInvoice=$userInvoice->id; $userRinvoice->refProduct='VP'; $userRinvoice->qty=1; $userRinvoice->priceATI=0.00; if(!$userRinvoice->save()) return "erreur record invoice !!" ;
			 */
		}
		$date = new \DateTime ();
		$date->sub ( new \DateInterval ( 'P1D' ) );
		
		for($j = $i; $j < $nbrEmails * 1 / 4; $j ++) {
			
			$user = new \Business\User ();
			$userI = new \Business\Internaute ();
			
			$user->email = "anaconda_stress_" . $i . "@gmail.com";
			$user->firstName = "test" . $i;
			$user->validPhoneNumber = - 1;
			$user->dateValidPhone = date ( '0000-00-00 00:00' );
			$user->addressComp = " ";
			$user->state = " ";
			$user->visibleDesinscrire = 0;
			if (! $user->save ())
				return "erreur  !!";
			
			$userI->Email = "anaconda_stress_" . $i . "@gmail.com";
			$userI->Address = "test" . $i;
			$userI->CP = '';
			$userI->Country = '';
			$userI->Phone = " ";
			$userI->CreationDate = date ( 'Y-m-d' );
			$userI->UpdateTS = new DateTime ();
			$userI->visibleDesinscrire = 0;
			$userI->CompteEMVactif = "R_MAY";
			$userI->Comment = '';
			$userI->DateValidationCheck = date ( 'Y-m-d' );
			$userI->countSend = 0;
			if (! $userI->save ())
				return "erreur  !!";
			
			$userInvoice1->IDInternaute = $userI->ID;
			$userInvoice1->Chrono = '';
			$userInvoice1->RefProduct = 'fr_rmay_voypayante';
			$userInvoice1->Qty = 1;
			$userInvoice1->CreationDate = $date->format ( 'Y-m-d' );
			$userInvoice1->Ref1Transaction = 'anaconda-' . $i;
			$userInvoice1->InvoiceStatus = 2;
			$userInvoice1->Ref2Transaction = 'anaconda-' . $i;
			$userInvoice1->Origine = '';
			$userInvoice1->UnitPrice = 1;
			$userInvoice1->PricePaid = 0;
			$userInvoice1->NumCheck = 0;
			$userInvoice1->DateExportLivraison = date ( '0000-00-00 00:00' );
			$userInvoice1->DateRemiseTransporteur = date ( '0000-00-00 00:00' );
			$userInvoice1->ModificationDate = $date->format ( 'Y-m-d' );
			$userInvoice1->date_ouverture = date ( '0000-00-00 00:00' );
			$userInvoice1->countSend = 0;
			$userInvoice1->lastSend = date ( '0000-00-00' );
			if (! $userInvoice1->save ())
				return "erreur invoice 1 !!";
			
			echo 'hello-------------' . $i . '<br>';
		}
		
		return "done !!";
	}
	/**
	 * Simulation d'ouvertuve des leads.
	 * 
	 * @author Fouad Dani
	 * @param
	 *        	date date d'execution du cron
	 * @return string
	 */
	static public function SimulationOuvertureLeads($nbrEmails, $activityHour) {
		$nbr_update = 1;
		$nbr_no_update = 1;
		for($i = 0; $i < $nbrEmails; $i ++) {
			$email = "anaconda_stress_" . $i . "@gmail.com";
			$user = \Business\User::loadByEmail ( $email );
			if ($user) {
				$id_user = $user->id;
				
				$campaign = self::getFirstCampaign ();
				$subCampaign = \Business\SubCampaign::loadByCampaignAndPosition ( $campaign->id, 1 );
				$subCampainReflations = \Business\SubCampaignReflation::getSubCampaignReflationBySubCampaign ( $subCampaign->id );
				foreach ( $subCampainReflations as $sub ) {
					if ($sub->number == 1) {
						$subCampainReflationId = $sub->id;
					}
				}
				
				$openedDate = date ( 'Y-m-d H:i' );
				$modifiedShootDate = date ( 'Y-m-d' );
				$openedLinkmail = new \Business\Openedlinkmail ();
				$openedLinkmail->openedDate = $openedDate;
				$openedLinkmail->idUser = $id_user;
				$openedLinkmail->idSubCampaignReflation = $subCampainReflationId;
				$openedLinkmail->activityHour = $activityHour;
				$openedLinkmail->modifiedShootDate = $modifiedShootDate;
				
				if (! $openedLinkmail->save ()) {
					$nbr_no_update ++;
				} else {
					$nbr_update ++;
				}
			}
		}
		return "Success : " . $nbr_update . " leads sont à jour, par contre  " . $nbr_no_update . " ne sont pas à jour";
	}
	
	/**
	 * d�calage date de pour recevoir la campaign suivante.
	 * 
	 * @author Mohamed Meski
	 * @param
	 *        	date date d'execution du cron
	 * @return string
	 */
	static public function decalageDeLeads($nbrEmails) {
		for($i = 0; $i < $nbrEmails; $i ++) {
			$email = "anaconda_stress_" . $i . "@gmail.com";
			$user = \Business\User::loadByEmail ( $email );
			if ($user) {
				$id_user = $user->id;
				$tmp = new \Business\CampaignHistory ();
				$campaignHistory = $tmp->getCampaignHistoryByUserId ( $id_user );
				$camphistories = $campaignHistory->getData ();
				foreach ( $camphistories as $camphistory ) {
					$camphistory = \Business\CampaignHistory::load ( $camphistory->id );
					if ($camphistory->SubCampaign->position == 1) {
						$dateSubOne = new DateTime ( date ( 'Y-m-d', strtotime ( $camphistory->modifiedShootDate ) ) );
						$dateSubOne->sub ( new DateInterval ( 'P8D' ) );
						$dateSubOne = $dateSubOne->format ( 'Y-m-d' );
						$camphistory->modifiedShootDate = $dateSubOne;
						$camphistory->save ();
					}
				}
			}
		}
		return "success";
	}
	
	/**
	 * executer les webforms apres une action d achat
	 * 
	 * @author Saad HDIDOU
	 * @param unknown $campaignHistory        	
	 */
	static public function execWebFormPayment($campaignHistory) {
		\Yii::import ( 'ext.CurlHelper' );
		$token = array ();
		
		// EMAIL_FIELD=__m__&STATUT_ANACONDA_FIELD=__s__&DE_ANACONDA_FIELD=__date__&ACTIVITY_HOUR_FIELD=__h__&DL_ANACONDA_FIELD=__date1__&LIVRAISON_ANACONDA_FIELD=__l__
		$token ['__m__'] = $campaignHistory->User->email;
		$deliveryDate = new \DateTime ( $campaignHistory->deliveryDate );
		$token ['__date1__'] = $deliveryDate->format ( 'm/d/Y' );
		$shootDate = new \DateTime ( $campaignHistory->modifiedShootDate );
		$token ['__date__'] = $shootDate->format ( 'm/d/Y' );
		$token ['__h__'] = $campaignHistory->behaviorHour;
		$campaign = $campaignHistory->SubCampaign->Campaign;
		
		$user = \Business\User::loadByEmail ( $campaignHistory->User->email );
		$arrayStatus = \Business\CampaignHistory::getAllProductsRef ( $user->id, $campaignHistory->deliveryDate, $campaignHistory->behaviorHour );
		if ($campaignHistory->SubCampaign->position == 1) {
			$status = $campaign->ref . "_1";
		} else {
			$status = $campaign->ref . "_2";
		}
		$statusLivraison = $status;
		if (! empty ( $arrayStatus )) {
			foreach ( $arrayStatus as $s ) {
				if ($s != $status) {
					$statusLivraison .= ',' . $s;
				}
			}
		}
		
		$token ['__s__'] = $status;
		$token ['__l__'] = $statusLivraison;
		
		if ($campaignHistory->SubCampaign->position == 1 && $campaign->hasCT ()) {
			$subCampCt=\Business\SubCampaign::loadByCampaignAndPosition( $campaignHistory->SubCampaign->idCampaign, 2 );
			$camphCt=\Business\CampaignHistory::loadByUserAndSubCampaign( $campaignHistory->idUser, $subCampCt->id );
			if(isset($camphCt)&&$camphCt->status>0)
			{
				$wf_payment = \Yii::app ()->params ['wf_paymentP2M1'];
			}
			else 
			{
				$wf_payment = \Yii::app ()->params ['wf_paymentP1'];
			}
		} else {
			$wf_payment = \Yii::app ()->params ['wf_paymentP2M1'];
		}
		
		$webForm = str_replace ( array_keys ( $token ), $token, $wf_payment );
		
		echo $webForm;
		
		// execution du webform
		$Curl = new \CurlHelper ();
		$Curl->setTimeout ( CURL_TIMEOUT );
		$Curl->sendRequest ( $webForm );
	}
	
	/**
	 * **************************** pauseLastInterFid **********************************************************
	 */
	
	/**
	 * mettre en stand by la fid en shoot si le client a effectuï¿½ un achat d'une fid ancienne, en sauvegardant l'etat de la derniere reflation envoyï¿½
	 * 
	 * @author Saad HDIDOU
	 * @param CampaignHistory $campaignHistory        	
	 */
	static public function pauseLastInterFid($campaignHistory) {
		$lastCH = $campaignHistory->getLastInterCampaignHistory ();
		$dateToday = new \DateTime ();
		if (! $lastCH) {
			return;
		}
		if ($lastCH->SubCampaign->position == 2 && $lastCH->SubCampaign->Product->asile_type == 'inter' && $lastCH->modifiedShootDate == $dateToday->format ( 'Y-m-d' )) {
			return;
		}
		if ($campaignHistory->id != $lastCH->id && $lastCH->status == 0) {
			$modifiedShootDate = new DateTime ( $lastCH->modifiedShootDate );
			$interval = $dateToday->diff ( $modifiedShootDate )->format ( '%a' );
			if ($campaignHistory->SubCampaign->position == 1 && $interval < 9) {
				switch ($interval) {
					case 0 :
					case 1 :
						$lastCH->status = - 1;
						$lastCH->save ();
						break;
					
					case 2 :
					case 3 :
						$lastCH->status = - 2;
						$lastCH->save ();
						break;
					
					case 4 :
					case 5 :
						$lastCH->status = - 3;
						$lastCH->save ();
						break;
					
					case 6 :
					case 7 :
						$lastCH->status = - 4;
						$lastCH->save ();
						break;
					
					case 8 :
						$lastCH->status = - 5;
						$lastCH->save ();
						break;
					
					default :
						break;
				}
			} else if ($campaignHistory->SubCampaign->position == 2 && $interval < 6) {
				switch ($interval) {
					case 0 :
						$lastCH->status = - 1;
						$lastCH->save ();
						break;
					
					case 1 :
					case 2 :
						$lastCH->status = - 2;
						$lastCH->save ();
						break;
					
					case 3 :
					case 4 :
						$lastCH->status = - 3;
						$lastCH->save ();
						break;
					
					case 5 :
						$lastCH->status = - 5;
						$lastCH->save ();
						break;
					
					default :
						break;
				}
			}
		}
	}
	
	/**
	 * **************************** /pauseLastInterFid **********************************************************
	 */
	public function updateWebForm($name) {
		$this->getPorteurMap ();
		$nbreTourne = 0;
		
		foreach ( $this->getParamApiCMD () as $class_api ) {
			$token = $class_api->connexion ();
			if ($nbreTourne >= 20) {
				die ();
			}
			
			$listWebForms = $class_api->getAllWebForm ( $name );
			
			echo 'soufiane' . count ( $listWebForms->webformSummary ) . '<br>';
			
			foreach ( $listWebForms->webformSummary as $element ) {
				echo $element->webformId . '------> ' . $element->name . '<br>';
				
				$class_api->updateWebForm ( $element->webformId );
				
				$nbreTourne ++;
				
				if ($nbreTourne >= 40) {
					die ();
				}
			}
			$nbreTourne ++;
			$class_api->closeConnection ( $token );
		}
	}
	
///////////////////////////////////////////////////////////////// Moteur de test ////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////// Ecart Livraison //////////////////////////////////////////////////////////////////////////
/************************************************************** Get Predeliveries By Date***********************************************************************/
 
 /**
  * @author Yacine RAMI
  * @desc cette methode va retourner les Campaigns histories des leads qui doivent �tre livres le jour passe en parametre, 
  * en les regroupant par provenance de la commande.
  * @param Date de livraison $dateLiv
  */
	 static public function preDeliveriesByDate($dateLiv)
	 {
	 	//Recupartion des livraions a livrer le jour passe en parametre
	 	$list = \Business\CampaignHistory::loadPreDeliveriesByDate($dateLiv);
	 	
	 	//Calcul J-1
	 	$dateSubOne = new DateTime($dateLiv);
	 	$dateSubOne->sub(new DateInterval('P1D'));
	 	
	 	//Grouper les livraison par provenance (J, J-1, J-2)
	 	
	 	$ind=0;
	 	
	 	//Groupe By Provenance
	 	foreach($list as $ch)
	 	{
	 		if($ch->deliveryDate == $dateLiv)
	 		{
	 			if($ch->SubCampaign->isInter())
	 			{
	 				$ch->provenance = 1;
	 			}
	 			else
	 			{
	 				$ch->provenance = 0;
	 			}
	 		}
	 		
	 		else if($ch->deliveryDate == $dateSubOne->format('Y-m-d'))
	 		{
	 			if($ch->SubCampaign->isInter())
	 			{
	 				$ch->provenance = 2;
	 			}
	 			else
	 			{
	 				$ch->provenance = 1;
	 			}
	 		}
	 		
			$ind++;
	 	}
	 	
	 	//retourner la litse mise a jour
	 	return $list;
	 
	
	 }
 /************************************************************** /Get Predeliveries By Date *******************************************************************/
 
 /*************************************************************** group Deliveries By Step ********************************************************************/
 /**
  * @author Yacine RAMI
  * @desc cette methode va retourner les Campaigns histories livr�es par etape d'achat .
  * @param List des campaigns histories � regrouper  $list
  */
	 static public function groupDeliveriesByStep($list)
	 {
	 	
	 	//pour chaque element de la list
	 	foreach($list as $ch)
	 	{
	 		// Recupere l'invoice par email et ref de produit
	 		$invoice = \Business\Invoice::loadByEmailAndProductPayed($ch->User->email,$ch->SubCampaign->Product->ref);
			
	 		// Eliminer les steps de test 1503/1504
	 		if($invoice)
	 		{
	 			if($invoice->priceStep != 1503 && $invoice->priceStep != 1504 && $invoice->priceStep != null)
	 			{
	 				$ch->step = $invoice->priceStep;
		 		}
		 		else 
		 		{
		 			$ch->step = NULL; 
		 		}
	 		}
	 	}

	 	return $list;
	 
 	}
 /*************************************************************** / group Deliveries By Step ******************************************************************/
 /****************************************************** Merge Deliveries by SubCampaign and Step ****************************************************************************/// /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 	/**
 	 * @author Yacine RAMI
 	 * @desc cette methode remplie la table Ecart Delivery par des campaign histories et alimente le comptage par step et provenance 
 	 * @param list des campaign histories regroupee par step et provenance $listFilteredByStep
 	 * @param date de livraison theorique $date
 	 */
	 static public function mergeDeliveriesBySubcampaignAndStep($listFilteredByStep,$date)
	 {
	 	foreach($listFilteredByStep as $ch)
	 	{
	 		if($ch->step!=null)
	 		{
	 			//tester si l'enregistrement existe pour la meme date, subcampaign, step
		 		$ed = \Business\EcartDelivery::loadBySubcampaignAndStepAndDate($ch->SubCampaign->id,$ch->step,$date);
		 		
		 		//si oui mettre a jour les comptages
		 		if($ed!=NULL)
		 		{
			 	
		 			switch($ch->provenance){
		 				case 0:
		 					$ed->buyerdJ += 1;
		 					break;
		 				case 1:
		 					$ed->buyerdJ1 += 1;
		 					break;
		 				case 2:
		 					$ed->buyerdJ2 += 1;
		 					break;
		 				default:
		 			}
		 	
		 			$ed->save();
		 		}
		 		//sinon creer l'enregistrement
		 		else if($ch->step!=NULL)
		 		{
		 			$ec=new \Business\Ecart();
				  	$ec->type = 2 ;
				  	$ec->creationDate = $date.' 00:00:00' ;
				  	$ec->save();
	
		 			$ed = new \Business\EcartDelivery();
     	 			if(self::getPositionByCampaign($ch->SubCampaign->Campaign))
    	 			{
   		 				$ed->fidPosition=self::getPositionByCampaign($ch->SubCampaign->Campaign);
    	 			}
		 			
		 			$ed->idSubCampaign=$ch->SubCampaign->id;
		 			$ed->idEcart=$ec->id;
		 			$ed->step=$ch->step;
		 	
		 			switch($ch->provenance){
		 				case 0:
		 					$ed->buyerdJ += 1;
		 					$ed->buyerdJ1 += 0;
		 					$ed->buyerdJ2+= 0;
		 					break;
		 				case 1:
		 					$ed->buyerdJ += 0;
		 					$ed->buyerdJ1 += 1;
		 					$ed->buyerdJ2 += 0;
		 					break;
		 				case 2:
		 					$ed->buyerdJ += 0;
		 					$ed->buyerdJ1 += 0;
		 					$ed->buyerdJ2 += 0;
		 					break;
		 				default:
		 			}
		 	
		 			$ed->save();
		 		
		 		}
	 		}
	 	
 		}
 		//done
 		return true;
	}
 
 /************************************************************ / Merge Deliveries by SubCampaign and Step*********************************************************************/
/**************************************************** deliveries By SubcampRef Date Step ******************************************************************/
	/**
	 * 
	 * @param unknown $idSubcampaignReflation
	 * @param unknown $date
	 * @param etape d'achat $step
	 */
	static public function deliveriesBySubcampDateStep($idSubcampaign,$date, $step)
	{
		$countAll=0;
		$countTest=0;
		$subcamp=null;
		$scr=null;
		$subcampCT=null;
		$list=null;
		
		
		// Recuperer la subcampaign
		$subcamp=\Business\SubCampaign::load($idSubcampaign);
		
		// Recuperer la subcampaignreflation
		if($subcamp)
		{
			if($subcamp->isAsile())
			{		
				$subcampCT=\Business\SubCampaign::loadByCampaignAndPosition( $subcamp->idCampaign, 2 );
				if($subcampCT)
				{
					$scr = \Business\SubCampaignReflation::loadByCampStep($subcampCT->id,1);
				}
			}
			else 
			{
				$scr = \Business\SubCampaignReflation::loadByCampStep($subcamp->id,111);
			}
		}
		

		
		// Recuperer la Ref du Produit
		if($scr)
		{
			$list = \Business\Reflationuser::loadBySubcampaignReflationAndDate($scr->id,$date);
		}
		
		if($list)
		{
			foreach ($list as $el)
			{
				$invoice = \Business\Invoice::loadByEmailAndProductPayed($el->email,$scr->SubCampaign->Product->ref);
					
				if($invoice && $invoice->priceStep == $step)
				{
					$countAll++;					
					if(strpos($invoice->ref1Transaction,"test"))
					{
						$countTest++;
					}
					
				}
							
			}
		}
		
		$ecarts= array();
		$ecarts[] = $countAll;
		$ecarts[] = $countTest;
		
		return $ecarts;

	}
/************************************************* / deliveries By SubcampRef Date Step ******************************************************************/	
///////////////////////////////////////////////////// / Ecart Livraison //////////////////////////////////////////////////////////////////////////		
	
	public static function SetReflationStbByCampaignHistory($idSubCampaignReflation, $idCampaignHistory) {
		$ch = \Business\CampaignHistory::model ()->findByPk ( $idCampaignHistory );
		$cussus = \Business\SubCampaignReflation::model ()->findByPk ( $idSubCampaignReflation );
		if($cussus && $ch)
		{
			$ch->status = - $cussus->number;
		
			if ($ch->save ())
				return true;
			else
				return false;
		}
		
		return false;
		
	}

	/***********************************   uploadSummaryList ***********************************************************************/

	/**
	 * @author Fouad DANI
	 * @desc Retrieves a list of uploads and their details.
	 * @return	list of uploads and their details
	 * @param int token and array listOptions
	 */
	static public function getUploadSummaryList($listOptions){
		//appel de la classe api
		Yii::import('ext.Class_API', true);

		//recuperer le dossier porteur

		$porteurMapp = Yii::app()->params['porteur'];
		\Controller::loadConfigForPorteur($porteurMapp);

		//connexion API

		$cmd_wdsl = \Yii::app()->params['CMD_EMV_ACQ']['wdsl_batchMember'];
		$cmd_login = \Yii::app()->params['CMD_EMV_ACQ']['login'];
		$cmd_pwd = \Yii::app()->params['CMD_EMV_ACQ']['pwd'];
		$cmd_key = \Yii::app()->params['CMD_EMV_ACQ']['key'];
		$class_api = new Class_API($cmd_wdsl, $cmd_login, $cmd_pwd, $cmd_key);
		$token = $class_api->connexion2();
		// Retrieves a list of uploads and their details.
		$imp = $class_api->getUploadSummaryList($token, $listOptions);
		return $imp;


	}
	/***********************************   /uploadSummaryList ***********************************************************************/


 
 ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 public function addEmailCondition($id)
 {
 	
 	$this->getPorteurMap();
 	$compteEMV=$GLOBALS ["porteurCompteEMVactif"] [$this->PorteurMap];
 	foreach ( $this->getParamApiCMD () as $class_api ) {
 		
 		$token = $class_api->connexion ();
 		$class_api->critereString ( 'EMAIL', 'DOES_NOT_CONTAINS', 'anaconda_stress','','' );
 	}
 }
 
 /************************************** Resubdivise ******************************************************/
 /**
  * @author Yacine RAMI
  * @desc Cette methode vide la table de subdivsion et relance la subdivison
  */
 public function ReSubdivise()
 {
 	\Business\AnacondaSubdivision::model()->deleteAllByAttributes(array("subdivised"=>0));
 	return self::Subdivision();
 }
 
 
 static public function UpdateSubdivisionSegment()
 {
	   	// appel de la classe api
	   	Yii::import ( 'ext.Class_API', true );
	   	
	   	$porteurMapp = Yii::app ()->params ['porteur'];
	   	\Controller::loadConfigForPorteur ( $porteurMapp );
	   	
	   	// rï¿½cupï¿½ration des parametres de l'API
	   	$mkt_wdsl = \Yii::app ()->params ['CMD_EMV_ACQ'] ['wdsl'];
	   	$mkt_login = \Yii::app ()->params ['CMD_EMV_ACQ'] ['login'];
	   	$mkt_pwd = \Yii::app ()->params ['CMD_EMV_ACQ'] ['pwd'];
	   	$mkt_key = \Yii::app ()->params ['CMD_EMV_ACQ'] ['key'];
	   	
	   	$class_api = new Class_API ( $mkt_wdsl, $mkt_login, $mkt_pwd, $mkt_key );
	   	
	   	/////////////////////////////////////////////////// Dates
	   	
	   	//J
	   	$dateToday = new Datetime ( date ( 'd-m-Y' ) );
	   	
	   	$dateJ = $dateToday->format('Y-m-d')."T00:00:00";
	   	
	   	// J - 8 mois
	   	$dateSub8Months = new Datetime ( date ( 'd-m-Y' ) );
	   	$dateSub8Months = $dateSub8Months->sub ( new DateInterval ( 'P8M' ) );
	   	
	   	$date8= $dateSub8Months->format('Y-m-d')."T00:00:00";
	   	// J - 4 mois
	   	$dateSub4Months = new Datetime ( date ( 'd-m-Y' ) );
	   	$dateSub4Months = $dateSub4Months->sub ( new DateInterval ( 'P4M' ) );
	   	
	   	
	   	$date4 = $dateSub4Months->format('Y-m-d')."T00:00:00";
	   	
	   	
	   	/////////////////////////////////////////////////// token de connexion
	   	$token = $class_api->connexion ();
	   	
	   	////////////////////////////////////////////////////// Clients //////////////////////////////////////
	   	$id_segment = \Yii::app()->params['subdivision_clients'];
	   	$operator = "ISBETWEEN_STATIC";
	   	$first_value = $date8;
	   	$second_value = $dateJ;
	   	$groupNumber = 1;
	   	
	   	//Segment Dernier Click
	   	$column_name = "LAST_DATE_CLICK";
	   	$orderFrag= 0;	   	
	   	$class_api->update_segment_criteria_recent($token,$id_segment,$column_name,$operator,$first_value,$second_value,$groupNumber,$orderFrag);
	   	
	   	// Segment Derniere ouverture
	   	$column_name = "LAST_DATE_OPEN";
	   	$orderFrag= 1 ;	   	 
	   	$class_api->update_segment_criteria_recent($token,$id_segment,$column_name,$operator,$first_value,$second_value,$groupNumber,$orderFrag);	   	
	   	
	   	//////////////////////////////////////////////// Leads Doubles //////////////////////////////////////////////////////////////////////
	   	$id_segment = \Yii::app()->params['subdivision_leads_doubles'];
	   	$operator = "ISBETWEEN_STATIC";
	   	$first_value = $date8;
	   	$second_value = $date4;
	   	$groupNumber = 1;
	   	
	   	// Dernier Clic
	   	$column_name = "LAST_DATE_CLICK";
	   	$orderFrag= 0 ;	 
	   	$class_api->update_segment_criteria_recent($token,$id_segment,$column_name,$operator,$first_value,$second_value,$groupNumber,$orderFrag);
	   	
	   	//Derniere Ouverture
	   	$column_name = "LAST_DATE_OPEN";
	   	$orderFrag= 1 ;
	   	$class_api->update_segment_criteria_recent($token,$id_segment,$column_name,$operator,$first_value,$second_value,$groupNumber,$orderFrag);	   	
	   	
	   	// fermer la connexion de l'API
	   	$class_api->closeConnection ( $token );
 	
 }
 
 /*********************************** / Resubdivise *******************************************************/

	/***********************************   ImportErrorList ***********************************************************************/

	/**
	 * @author Fouad DANI
	 * @desc Retrieves a number of uploads errors.
	 * @return	list of uploads and their details
	 */
	static public function getImportErrorList(){

		// declaration de la date d'execution du cron
		$datetime1 = new DateTime("now");
		// listes des options d'appelle de web service
		$listOptions = array('page' => 1,
			'pageSize' => 20,
			'search' => array('source' => '', 'status' => ''),
			'sortOptions' => array('column' => $datetime1, 'order' => 'ASC'));
		// initialisation du counteur des imports errones
		$countImport = 0;
		// variable pour tester la page ou on s'arrete
		$nextPage = true; $i = 1;
		try{
			while ($nextPage) {
				$listOptions['page'] = $i;
				$uploadSummaryLists = \AnacondaBehavior::getUploadSummaryList($listOptions);
				foreach ($uploadSummaryLists->uploadSummaries->uploadSummaryEntity as $summaryEntity) {

					$delimiters = array("-", "T", ":", "+");
					$ready = str_replace($delimiters, $delimiters[0], $summaryEntity->date);
					$date_explode = explode($delimiters[0], $ready);


					$datetime2 = new DateTime($date_explode[0] . "-" . $date_explode[1] . "-" . $date_explode[2]);
					$interval = $datetime2->diff($datetime1);

					// On prend le nombre des status errones dans le jour courant
					if (($summaryEntity->status == "error" || $summaryEntity->status == "failure" || $summaryEntity->status == "queued") && $interval->d == 1) {
						$countImport++;
					} elseif ($interval->d != 1) {
						$nextPage = false;
					}
					// Tester si il y a d'autre page
					if ($uploadSummaryLists->nextPage) {
						$i++;
					}
				}
			}
		}catch(Exception $e){
			var_dump($e);
		}

		return $countImport;



	}
	/***********************************   /ImportErrorList ***********************************************************************/
 
	/**
	 * ********************************* getCampaignByPosition ****************************************************************
	 */
	
	/**
	 * recuperer la fid qui correspond 
	 *
	 * @author Saad HDIDOU
	 * @param
	 *
	 * @return campaign
	 */
	static public function getCampaignByPosition($position) 
	{
		$campaign = self::getFirstCampaign ();
		if($position!=1)
		{
			for($i=1;$i<$position;$i++)
			{
				$campaign = $campaign->getNextCampaign ();
			}
		}
		return $campaign;
	}
	
	static public function getPositionByCampaign($campaign)
	{
		$Firstcampaign = self::getFirstCampaign ();
		$position=1;
		$isNull=0;
		$nbrTourne=0;
		if($Firstcampaign->id!=$campaign->id && ($campaign->isAnaconda!=1 || $Firstcampaign->idNextCampaign==NULL))
		{
			return false;
		}
		while ($Firstcampaign->id!=$campaign->id && $isNull==0 && $nbrTourne<100)
		{
			$Firstcampaign = $Firstcampaign->getNextCampaign ();
			$position++;
			$nbrTourne++;
			if($Firstcampaign->idNextCampaign==NULL)
				$isNull=1;
		}
		if($isNull==1)
			return false;
		return $position;
	}
	/**  /getCampaignByPosition ***********************************/



	/**  /SupposedGp ***********************************/
	public function SupposedGp($idUser,$idCampaignHistory2)
	{
		
		$cussusHistroy = new \Business\CampaignHistory ();
		$user = new \Business\User ();

		//si la subCampaign envoiee est du p2 en verifie seulement si le GP envoye == au supposed GP du produit 1
		if (substr ( \Business\CampaignHistory::load ( $idCampaignHistory2 )->SubCampaign->Product->ref, - 1 ) == 2) {
			$idProduct1 = \Business\Product::loadByRef ( substr ( \Business\CampaignHistory::load ( $idCampaignHistory2 )->SubCampaign->Product->ref, 0, - 1 ) . '1' )->id;
			$idSubCamapingProduct1 = \Business\SubCampaign::loadByProduct ( $idProduct1 )->id;
			return \Business\CampaignHistory::loadByUserAndSubCampaign ( $idUser, $idSubCamapingProduct1 )->supposedGp;
		}
		//recuperer la campaign history avant 6jours
		$cussusCampaignHistoryBefor6day = $cussusHistroy->getLastCampaignHistoryByIdUserBeforeSixDay ( $idUser ) [0];
	
		//$dateLivraison = $cussusCampaignHistoryBefor6day->deliveryDate;
	
		//parcourir la liste des campagins history entre la campaign actuelle et la campaign avant 6 jours
		foreach ( $cussusHistroy->getCampaignHistoryBetween2CmapaignHistory ( $idUser, $cussusCampaignHistoryBefor6day->id, $idCampaignHistory2 ) as $element ) {
			//comparer la campaign history avec campaign history +1
			if ($element->id != $cussusCampaignHistoryBefor6day->id) {
	
				//verifier si la subCampaign envoee et de 2eme produit
				if (substr ( \Business\CampaignHistory::load ( $cussusCampaignHistoryBefor6day->id )->SubCampaign->Product->ref, - 1 ) == 2) {
					continue;
				}
	
				//recuperer idSubCamapaignreflation de la ldv de la campaignHistory -6
				if (count ( \Business\SubCampaignReflation::loadByCampStep ( $cussusCampaignHistoryBefor6day->idSubCampaign, 1 ) ) > 0) {
					//recuperer l'indice d implication de la LDV
					if (count ( \Business\Reflationuser::loadByIdUserAndIdSubCamapaignReflation ( $idUser, \Business\SubCampaignReflation::loadByCampStep ( $cussusCampaignHistoryBefor6day->idSubCampaign, 1 )->id ) ) > 0) {
						echo 'indice d\'implication est ===>' . \Business\Reflationuser::loadByIdUserAndIdSubCamapaignReflation
						( $idUser, \Business\SubCampaignReflation::loadByCampStep ( $cussusCampaignHistoryBefor6day->idSubCampaign, 1 )->id ) [0]->indiceImplication . '<br>';
					}
				}
	
				$count = 0;
				echo '///////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\C//////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\<br>';
				echo $cussusCampaignHistoryBefor6day->id . '=>' . $element->id . '<br>';
				echo $cussusCampaignHistoryBefor6day->initialShootDate . '=>' . $element->initialShootDate . '<br>';
	
				echo '**************Parti STC V1***************<br><br>';
				//recuperer les STC acheter entre les deux campaign history V1
				foreach ( \Business\PaymentTransaction::getPurchasedSTCByInterval ( $cussusCampaignHistoryBefor6day->initialShootDate, $element->initialShootDate, $idUser ) as $elements ) {
					echo $elements->id . '==============>' . $elements->refDiscount . '========>' . $elements->productRef . '=========>' .
							self::getPointStcV1 ( $elements->refDiscount, $elements->productRef ) . '<br>';
							$count += self::getPointStcV1 ( $elements->refDiscount, $elements->productRef );
				}
	
				echo '**************Parti STC V2***************<br><br>';
				//recuperer les STC acheter entre les deux campaign history V2
				foreach ( \Business\Invoice::getPurchasedSTCByInterval ( $cussusCampaignHistoryBefor6day->initialShootDate, $element->initialShootDate, \Business\User::load ( $idUser )->email ) as $elements ) {
					echo $elements->id . '===============>' . $elements->priceStep . '=======>' . $elements->RecordInvoice [0]->refProduct . '==========>' .
							self::getPointStcV2 ( $elements->priceStep, $elements->RecordInvoice [0]->refProduct ) . '<br>';
							$count += self::getPointStcV2 ( $elements->priceStep, $elements->RecordInvoice [0]->refProduct );
				}
				echo '**************Parti FID ANACONDA***************<br><br>';
				//recuperer les fid anconda acheter entre les deux campaign history V1
				foreach ( \Business\Invoice::getPurchasedAnacondaFid ( $cussusCampaignHistoryBefor6day->initialShootDate, $element->initialShootDate, \Business\User::load ( $idUser )->email ) as $elements ) {
					echo $elements->id . '===============>' . $elements->priceStep . '=======>' . $elements->RecordInvoice [0]->refProduct . '==========>' .
							self::getPointStcV2 ( $elements->priceStep, $elements->RecordInvoice [0]->refProduct ) . '<br>';
							$count += $user->indiceImplication($elements->Invoice->priceStep, substr ( $elements->RecordInvoice [0]->refProduct, - 1 ) );
				}
	
				//
	
				echo $count . '<br>';
			}
			$cussusCampaignHistoryBefor6day = $element;
		}
	}
	public static function getPointStcV1($step,$productRef)
	{
	
		$porteurMapp = Yii::app ()->params ['porteur'];
		$type = explode ( '_', $porteurMapp );
		$cont = count ( $type ) - 1;
		$type = $type [$cont];
		\Controller::loadConfigForPorteur ( $porteurMapp );
		if ($step == 1503) {
			return 3;
		}
		if (preg_match ( '/asil/', $productRef ) || preg_match ( '/inter/', $productRef ) || preg_match ( '/conttele/', $productRef )) {
			return 3;
		}
	
		else {
			switch ($step) {
	
				case 1 :
				case 2 :
				case 3 :
					return 3;
	
				case 4 :
				case 5 :
				case 6 :
					return 2;
	
				case 7 :
				case 8 :
				case 9 :
					if ($type == "Rinalda") {
						return 2;
					} else {
						return 1;
					}
	
				case 10 :
				case 11 :
				case 12 :
				case 13 :
					return 1;
	
				default :
					break;
			}
		}
	}
	
	public static function getPointStcV2($step,$refProduct)
	{
		$porteur = Yii::app ()->params ['porteur'];
	
		switch ($refProduct) {
			case 'stg_3' :
			case 'stg_4' :
			case 'stg_5' :
			case 'stg_6' :
			case 'stg_7' :
			case 'INTER' :
			case 'as' :
			case 'as2' :
			case 'as3' :
			case 'as4' :
			case 'stc_3' :
			case 'stc_4' :
			case 'stc_5' :
			case 'stc_6' :
			case 'stc_7' :
				return 3;
					
			case 'stc_2' :
			case 'vp' :
			case 'stg_2' :
				if ($GLOBALS ['porteurMap'] [$porteur] == 'se_rmay') {
						
					return 3;
				} else {
					switch ($step) {
						case 1 :
						case 2 :
						case 3 :
							return 3;
	
						case 4 :
						case 5 :
						case 6 :
							return 2;
	
						case 7 :
						case 8 :
						case 9 :
							return 1;
	
						default :
							break;
					}
				}
				break;
			case 'VP' :
				switch ($step) {
					case 1 :
					case 2 :
					case 3 :
						return 3;
							
					case 4 :
					case 5 :
					case 6 :
					case 7 :
					case 8 :
						return 2;
							
					case 9 :
					case 10 :
					case 11 :
					case 12 :
					case 13 :
						return 1;
							
					default :
						break;
				}
				break;
			case 'stc_1' :
				if ($GLOBALS ['porteurMap'] [$porteur] == 'se_rmay') {
					switch ($step) {
						case 1 :
						case 2 :
						case 3 :
							return 3;
	
						case 4 :
						case 5 :
						case 6 :
							return 2;
	
						case 7 :
						case 8 :
						case 9 :
							return 1;
	
						default :
							break;
					}
				}
				break;
			default :
				break;
		}
	}


	/** / getNextShoot By campaignHistory ***********************************************************/

	/**  get Next IndexImplication By campaignHistory  ********************************************************
	 * @author Fouad DANI
	 * @desc Next Index Implication by campaignHistory.
	 * @return	Next Index Implication
	 * @param int Index Implication
	 */
	static public function getNextIndexImplication($currentCampaignHistory, $dateRef) {
		$count = 0;
		$user = new \Business\User();
		$user = $user::load ( $currentCampaignHistory->idUser );

		//si la subCampaign envoiee est du p2 en verifie seulement si le GP envoye == au supposed GP du produit 1
		if (substr ( $currentCampaignHistory->SubCampaign->Product->ref, - 1 ) == 2) {
			$idProduct1 = \Business\Product::loadByRef ( substr ( $currentCampaignHistory->SubCampaign->Product->ref, 0, - 1 ) . '1' )->id;
			$idSubCampaignProduct1 = \Business\SubCampaign::loadByProduct ( $idProduct1 )->id;
			return \Business\CampaignHistory::loadByUserAndSubCampaign ( $currentCampaignHistory->idUser, $idSubCampaignProduct1 )->supposedGp;
		}

		// return last reference campaignHistory  by reference date and idUser
		$campaignHistoryRef = \Business\CampaignHistory::getListCampaignHistoryByIdUserAndDateRef( $currentCampaignHistory->idUser, $dateRef )[0];

		/** find next campaignHistory */
		$nextCampaignHistory = \Business\CampaignHistory::getNextShoot($currentCampaignHistory);
		/** STC V1  */

		/** STC V2  */

		/** Fid Anaconda  */

		$ListPurchasedAnacondaFid = \Business\Invoice::getPurchasedAnacondaFid ( $campaignHistoryRef->initialShootDate, $currentCampaignHistory->initialShootDate, $user->email );

		$json = \CJSON::encode($ListPurchasedAnacondaFid);
		print_r($json);


		//find Anaconda Fid  Purchased between  currentCampaignHistory and nextCampaignHistory
		foreach ($ListPurchasedAnacondaFid as $elements ) {
			echo $elements->id . ': ' . $elements->priceStep . ': ' . $elements->RecordInvoice [0]->refProduct . ': ' .
				self::getPointStcV2 ( $elements->priceStep, $elements->RecordInvoice [0]->refProduct ) . '<br>';
			$count += $user->indiceImplication($elements->Invoice->priceStep, substr ( $elements->RecordInvoice [0]->refProduct, - 1 ) );
		}

		echo "<br>";
		return $count;

		die();


		//$dateLivraison = $cussusCampaignHistoryBefor6day->deliveryDate;

		//parcourir la liste des campagins history entre la campaign actuelle et la campaign avant 6 jours
		foreach ( $cussusHistroy->getCampaignHistoryBetween2CmapaignHistory ( $idUser, $cussusCampaignHistoryBefor6day->id, $idCampaignHistory2 ) as $element ) {
			//comparer la campaign history avec campaign history +1
			if ($element->id != $cussusCampaignHistoryBefor6day->id) {

				//verifier si la subCampaign envoee et de 2eme produit
				if (substr ( \Business\CampaignHistory::load ( $cussusCampaignHistoryBefor6day->id )->SubCampaign->Product->ref, - 1 ) == 2) {
					continue;
				}

				//recuperer idSubCamapaignreflation de la ldv de la campaignHistory -6
				if (count ( \Business\SubCampaignReflation::loadByCampStep ( $cussusCampaignHistoryBefor6day->idSubCampaign, 1 ) ) > 0) {
					//recuperer l'indice d implication de la LDV
					if (count ( \Business\Reflationuser::loadByIdUserAndIdSubCamapaignReflation ( $idUser, \Business\SubCampaignReflation::loadByCampStep ( $cussusCampaignHistoryBefor6day->idSubCampaign, 1 )->id ) ) > 0) {
						echo 'indice d\'implication est ===>' . \Business\Reflationuser::loadByIdUserAndIdSubCamapaignReflation
							( $idUser, \Business\SubCampaignReflation::loadByCampStep ( $cussusCampaignHistoryBefor6day->idSubCampaign, 1 )->id ) [0]->indiceImplication . '<br>';
					}
				}

				$count = 0;
				echo '///////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\C//////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\<br>';
				echo $cussusCampaignHistoryBefor6day->id . '=>' . $element->id . '<br>';
				echo $cussusCampaignHistoryBefor6day->initialShootDate . '=>' . $element->initialShootDate . '<br>';

				echo '**************Parti STC V1***************<br><br>';
				//recuperer les STC acheter entre les deux campaign history V1
				foreach ( \Business\PaymentTransaction::getPurchasedSTCByInterval ( $cussusCampaignHistoryBefor6day->initialShootDate, $element->initialShootDate, $idUser ) as $elements ) {
					echo $elements->id . '==============>' . $elements->refDiscount . '========>' . $elements->productRef . '=========>' .
						self::getPointStcV1 ( $elements->refDiscount, $elements->productRef ) . '<br>';
					$count += self::getPointStcV1 ( $elements->refDiscount, $elements->productRef );
				}

				echo '**************Parti STC V2***************<br><br>';
				//recuperer les STC acheter entre les deux campaign history V2
				foreach ( \Business\Invoice::getPurchasedSTCByInterval ( $cussusCampaignHistoryBefor6day->initialShootDate, $element->initialShootDate, \Business\User::load ( $idUser )->email ) as $elements ) {
					echo $elements->id . '===============>' . $elements->priceStep . '=======>' . $elements->RecordInvoice [0]->refProduct . '==========>' .
						self::getPointStcV2 ( $elements->priceStep, $elements->RecordInvoice [0]->refProduct ) . '<br>';
					$count += self::getPointStcV2 ( $elements->priceStep, $elements->RecordInvoice [0]->refProduct );
				}
				echo '**************Parti FID ANACONDA***************<br><br>';
				//recuperer les fid anconda acheter entre les deux campaign history V1
				foreach ( \Business\Invoice::getPurchasedAnacondaFid ( $cussusCampaignHistoryBefor6day->initialShootDate, $element->initialShootDate, \Business\User::load ( $idUser )->email ) as $elements ) {
					echo $elements->id . '===============>' . $elements->priceStep . '=======>' . $elements->RecordInvoice [0]->refProduct . '==========>' .
						self::getPointStcV2 ( $elements->priceStep, $elements->RecordInvoice [0]->refProduct ) . '<br>';
					$count += $user->indiceImplication($elements->Invoice->priceStep, substr ( $elements->RecordInvoice [0]->refProduct, - 1 ) );
				}

				//

				echo $count . '<br>';
			}
			$cussusCampaignHistoryBefor6day = $element;
		}

	}
	/** / get Next IndexImplication By campaignHistory ***********************************************************/
	
	public static function getSentMessagesByReflationAndDate($date,$idSubCampaignReflation, $vague)
	{
		$countSubdivision=0;
		$countPayedVP=0;
		$countR8=0;
		$countP2Ldv=0;
		$countOpener=0;
		$countNotOpener=0;
		$countLdvP1Opener=0;
		$countLdvNotOpener=0;
		$countLdvP2Opener=0;
		$countBuyer=0;
		$countReactivation=0;
		$countStb=0;
		
		echo "debut <br/>";
	
		
		$list=\Business\Reflationuser::getSentMessagesByReflationAndDate($date,$idSubCampaignReflation, $vague);
		$subCampaignReflation=\Business\SubCampaignReflation::load($idSubCampaignReflation);
	
	
		if($subCampaignReflation->number==1 && $subCampaignReflation->Subcampaign->position==2)
		{
			$countP2Ldv=count($list);
		}
		else if($subCampaignReflation->number==1 && $subCampaignReflation->Subcampaign->position==1)
		{
			foreach ($list as $ligne)
			{
				$user=\Business\User::load($ligne->idUser);
				$dateReactivation=new \DateTime ( $user->reactivationDate );
				$campaignHistory=\Business\CampaignHistory::loadByUserAndSubCampaign( $ligne->idUser, $subCampaignReflation->idSubCampaign );
				if ($ligne->isNewLead==1)
				{
					switch ($user->origin)
					{
						case 1:
							$countSubdivision++;
							break;
						case 2:
							$countPayedVP++;
							break;
						case 3:
							$countR8++;
							break;
					}
				}
				else if($campaignHistory->dateStb == $date->format ( 'Y-m-d' ))
				{
					$countStb++;
				}
				else if ($ligne->buyerJ2==1)
				{
					$countBuyer++;
				}
				else if($dateReactivation->format ( 'Y-m-d' ) == $date->format ( 'Y-m-d' ))
				{
					$countReactivation++;
				}
				else 
				{
					$subCampaign=$campaignHistory->getLastShootedSubCampaign();
					if($subCampaign->position==2)
					{
						$countLdvP2Opener++;
					}
					else 
					{
						if(\Business\CampaignHistory::LoadopenedlinkmailBySubCampaignAndUser($subCampaign->id, $user->id))
						{
							$countLdvP1Opener++;
						}
						else
						{
							$countLdvNotOpener++;
						}
					}
				}
	
			}
		}
		else
		{
			foreach ($list as $ligne)
			{
				if ($ligne->openerJ1==1)
				{
					$countOpener++;
				}
				else if ($ligne->notOpenerJ2==1)
				{
					$countNotOpener++;
				}
			}
		}
		
		echo "fin <br/>";
	
		echo "countSubdivision ".$countSubdivision."<br/>";
		echo "countPayedVP ".$countPayedVP."<br/>";
		echo "countR8 ".$countR8."<br/>";
		echo "countP2Ldv ".$countP2Ldv."<br/>";
		echo "countOpener ".$countOpener."<br/>";
		echo "countNotOpener ".$countNotOpener."<br/>";
		echo "countBuyer ".$countBuyer."<br/>";
		echo "countReactivation ".$countReactivation."<br/>";
		echo "countLdvP1Opener ".$countLdvP1Opener."<br/>";
		echo "countLdvNotOpener ".$countLdvNotOpener."<br/>";
		echo "countLdvP2Opener ".$countLdvP2Opener."<br/>";
		echo "countStb ".$countStb."<br/>";
	}
	/********************************************************* Manage History **********************************************/
	/**
	 * @author Yacine RAMI
	 * @desc Gestion d'une alerte
	 * @param unknown $idAlert
	 */
	static public function manageHistory($idAlert,$commentContent)
	{
		$commentID =  \Business\CommentAlert::createComment($idAlert,$commentContent);
			if($commentID!=false)
			{
				echo "saved";
			}
			else echo "not saved";
		
		
	}
	/******************************************************* / Manage History **********************************************/

 
 }
 
?>	
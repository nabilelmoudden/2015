<?php

/**
 * Created by PhpStorm.
 * User: sbalkaid
 * Date: 10/10/2016
 * Time: 16:41
 */
namespace Business;

class CampaignHistory extends \CampaignHistory {

	public $provenance;
	public $step;

	/**
	 * @author soufiane balkaid
	 * @param $idUser
	 * @param $idSubCampaign 
	 * @return CampaignHistory[]
	 */
	
	public function seachByIdUSerIdSubCampaign($idUser, $idSubCampaign) {
		$campaignHistory = $this->search ( 'id' );
		$campaignHistory->criteria->order = 'id DESC';
		$campaignHistory->criteria->compare ( 'idUser', $idUser );
		$campaignHistory->criteria->compare ( 'idSubCampaign', $idSubCampaign );
		return $campaignHistory;
	}
	
	
	/**
	 * @author soufiane balkaid
	 * @desc creer un nouveau enregistrement dans la table V2_CampaignHistory
	 * @param $CampaignHistory
	 */
	public function saveCampaignHistory($campaignHistory) {
		try {
			$campaignHistory->save ();
		} catch ( Exception $e ) {
			var_dump ( $e );
		}
	}

	
	
	public function getStatus(){
		return $this->status;
	}
	public function getId() {
		return $this->id;
	}

	/**
	 * @author soufiane balkaid
	 * @param $idSubCampaign
	 * @return CampaignHistory[]
	 */
	public function searchUsersByIdSubCampaign($idsubcamp) {
		$campaignHistory = $this->search ( 'id DESC' );
		$campaignHistory->criteria->compare ( 'idSubCampaign', $idsubcamp );
		return $campaignHistory;
	}
	
	/**************************************** Get CampaignHistory By User Id ***********************************************************************/
	/**
	 * @author Yacine RAMI
	 * @desc Recuperer des campaigns histories par idUser.
	 * @return	CampaignHistory[] liste des Campaigns Histories
	 * @param int $idUser id de l'utilisateur
	 */
	public function getCampaignHistoryByUserId($idUser) {
		$campaignHistory = $this->search ( 'id  DESC' );
		$campaignHistory->criteria->order = 'id DESC';
		$campaignHistory->criteria->compare ( 'idUser', $idUser );
		return $campaignHistory;
	}
	
	/************************************ / Get CampaignHistory By User Id **************************************************************************/
	
	/**
	 * @author Saad HDIDOU
	 * @desc renvoie du dernier enregistrement campaignHistory d'un client
	 * @param $idUser
	 * @return CampaignHistory
	 */
	 static public function getLastCampaignHistorybyIdUSer($idUser){
		
	 	$listeCampaignHistory=self::model()->findAllByAttributes( array( 'idUser' => $idUser ) );
	 	if(isset($listeCampaignHistory))
	 		return end($listeCampaignHistory) ;
	}
	
	
	/**************************************** / Get CampaignHistory By User Id ***********************************************************************/
	
	/**************************************** Load Stand By Campaigns *********************************************************/
	/**
	 * @author Yacine RAMI
	 * @desc Recuperer les campaigns histories en mode Stand By.
	 * @return CampaignHistory[] liste des Campaigns Histories
	 * @param integer $numCron numero de cron execute
	 */
	static public function loadStandByCampaigns($numCron)
	{
		switch($numCron)
		{		
			case 3 :
				return self::model()->findAll( array('condition'=>'status = 1 AND behaviorHour IN (0,1,2)'));
				break;
			case 4 :
				return self::model()->findAll( array('condition'=>'status = 1 AND behaviorHour IN (3,4,5)'));
				break;
			case 5 :
				return self::model()->findAll( array('condition'=>'status = 1 AND behaviorHour IN (6,7,8)'));
				break;
			case 6 :
				return self::model()->findAll( array('condition'=>'status = 1 AND behaviorHour IN (9,10,11)'));
				break;
			case 7 :
				return self::model()->findAll( array('condition'=>'status = 1 AND behaviorHour IN (12,13,14)'));
				break;
			case 8 :
				return self::model()->findAll( array('condition'=>'status = 1 AND behaviorHour IN (15,16,17)'));
				break;
			case 1 :
				return self::model()->findAll( array('condition'=>'status = 1 AND behaviorHour IN (18,19,20)'));
				break;
			case 2 :
				return self::model()->findAll( array('condition'=>'status = 1 AND behaviorHour IN (21,22,23)'));
				break;
			default:
				return false;
			}
	}
	
	/******************************************** / Load Stand By Campaigns *************************************************************************/
	/******************************************  Load By Status And ModifiedShootDate ***********************************************************/
	/**
	 * @author Yacine RAMI
	 * @desc Recupere les campaigns histories par status et Date de shoot Modifiable DE.
	 * @param integer $status
	 * @param string $modifiedShootDate
	 * @return CampaignHistory[] liste des Campaigns Histories
	 */
	
	static public function loadByStatusAndModifiedShootDate($status,$modifiedShootDate)
	{
		return self::model()->findAllByAttributes( array( 'status' => $status,'modifiedShootDate'=> $modifiedShootDate) );
	}
	
	
	/****************************************** / Load By Status And ModifiedShootDate ***********************************************************/
	
	static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * 
	 * @param integer $iduser
	 * @param integer $idsubcampaign
	 * @return CampaignHistory
	 */
	static public function loadByUserAndSubCampaign( $iduser, $idsubcampaign )
	{
		return self::model()->findByAttributes( array( 'idUser' => $iduser, 'idSubCampaign' => $idsubcampaign ) );
	}
	
	/**
	 * @desc verifier si le client a effectue un achat le jour avant
	 * @param date $date
	 * @return boolean
	 */
	public function hasPurshasedDayBefore( $date )
	{
		$dateSubOne = new \DateTime($date);
		$dateSubOne->sub(new \DateInterval('P1D'));
		$result = self::model()->findAllByAttributes( array( 'deliveryDate' => $dateSubOne->format('Y-m-d'), 'idUser' => $this->idUser, 'status' => 2 ) );
		if (!empty($result))
			return true;
		else
			return false;
	}
	
	public function hasPurshasedSixHour( $date )
	{
		$hour = $date->format( 'H' );
	
		if($hour>=0 && $hour<=2)
		{
			$inf=18;
			$sup=23;
			$date->sub(new \DateInterval('P1D'));
			
			$criteria = new \CDbCriteria;
			$criteria->condition = 'behaviorHour BETWEEN :inf AND :sup AND idUser = :idUser AND deliveryDate = :deliveryDate AND status = 2';
			$criteria->params = array (
					':inf' => $inf,
					':sup' => $sup,
					':idUser' => $this->idUser,
					':deliveryDate' => $date->format('Y-m-d')
			);
			
			$result = self::model()->findAll($criteria);
		}
		else if($hour>=3 && $hour<=5)
		{
			$inf=0;
			$sup=2;
				
			$criteria = new \CDbCriteria;
			$criteria->condition = 'behaviorHour BETWEEN :inf AND :sup AND idUser = :idUser AND deliveryDate = :deliveryDate AND status = 2';
			$criteria->params = array (
					':inf' => $inf,
					':sup' => $sup,
					':idUser' => $this->idUser,
					':deliveryDate' => $date->format('Y-m-d')
			);
				
			$result1 = self::model()->findAll($criteria);
			
			$inf=21;
			$sup=23;
			$date->sub(new \DateInterval('P1D'));
			
			$criteria->condition = 'behaviorHour BETWEEN :inf AND :sup AND idUser = :idUser AND deliveryDate = :deliveryDate AND status = 2';
			$criteria->params = array (
					':inf' => $inf,
					':sup' => $sup,
					':idUser' => $this->idUser,
					':deliveryDate' => $date->format('Y-m-d')
			);
			
			$result2 = self::model()->findAll($criteria);
			$result = array_merge($result1, $result2);
		}
		else if($hour>=6 && $hour<=8)
		{
			$inf=0;
			$sup=5;
				
			$criteria = new \CDbCriteria;
			$criteria->condition = 'behaviorHour BETWEEN :inf AND :sup AND idUser = :idUser AND deliveryDate = :deliveryDate AND status = 2';
			$criteria->params = array (
					':inf' => $inf,
					':sup' => $sup,
					':idUser' => $this->idUser,
					':deliveryDate' => $date->format('Y-m-d')
			);
				
			$result = self::model()->findAll($criteria);
		}
		else if($hour>=9 && $hour<=11)
		{
			$inf=3;
			$sup=8;
				
			$criteria = new \CDbCriteria;
			$criteria->condition = 'behaviorHour BETWEEN :inf AND :sup AND idUser = :idUser AND deliveryDate = :deliveryDate AND status = 2';
			$criteria->params = array (
					':inf' => $inf,
					':sup' => $sup,
					':idUser' => $this->idUser,
					':deliveryDate' => $date->format('Y-m-d')
			);
				
			$result = self::model()->findAll($criteria);
		}
		else if($hour>=12 && $hour<=14)
		{
			$inf=6;
			$sup=11;
				
			$criteria = new \CDbCriteria;
			$criteria->condition = 'behaviorHour BETWEEN :inf AND :sup AND idUser = :idUser AND deliveryDate = :deliveryDate AND status = 2';
			$criteria->params = array (
					':inf' => $inf,
					':sup' => $sup,
					':idUser' => $this->idUser,
					':deliveryDate' => $date->format('Y-m-d')
			);
				
			$result = self::model()->findAll($criteria);
		}
		else if($hour>=15 && $hour<=17)
		{
			$inf=9;
			$sup=14;
				
			$criteria = new \CDbCriteria;
			$criteria->condition = 'behaviorHour BETWEEN :inf AND :sup AND idUser = :idUser AND deliveryDate = :deliveryDate AND status = 2';
			$criteria->params = array (
					':inf' => $inf,
					':sup' => $sup,
					':idUser' => $this->idUser,
					':deliveryDate' => $date->format('Y-m-d')
			);
				
			$result = self::model()->findAll($criteria);
		}
		else if($hour>=18 && $hour<=20)
		{
			$inf=12;
			$sup=17;
				
			$criteria = new \CDbCriteria;
			$criteria->condition = 'behaviorHour BETWEEN :inf AND :sup AND idUser = :idUser AND deliveryDate = :deliveryDate AND status = 2';
			$criteria->params = array (
					':inf' => $inf,
					':sup' => $sup,
					':idUser' => $this->idUser,
					':deliveryDate' => $date->format('Y-m-d')
			);
				
			$result = self::model()->findAll($criteria);
		}
		else if($hour>=21 && $hour<=23)
		{
			$inf=15;
			$sup=20;
				
			$criteria = new \CDbCriteria;
			$criteria->condition = 'behaviorHour BETWEEN :inf AND :sup AND idUser = :idUser AND deliveryDate = :deliveryDate AND status = 2';
			$criteria->params = array (
					':inf' => $inf,
					':sup' => $sup,
					':idUser' => $this->idUser,
					':deliveryDate' => $date->format('Y-m-d')
			);
				
			$result = self::model()->findAll($criteria);
		}
	
		if (!empty($result))
			return true;
		else
			return false;
	}
	
	/**
	 * 
	 * @param integer $idUser
	 * @param date $dateToday
	 * @return array[] list des references de produit achete le jour avant
	 */
	static public function getAllProductsRef($idUser, $dateToday, $hour)
	{
        $array=array();
        if($hour>=0 && $hour<=2)
        {
        	$inf=0;
        	$sup=2;
        }
        else if($hour>=3 && $hour<=5)
        {
        	$inf=3;
        	$sup=5;
        }
        else if($hour>=6 && $hour<=8)
        {
        	$inf=6;
        	$sup=8;
        }
        else if($hour>=9 && $hour<=11)
        {
        	$inf=9;
        	$sup=11;
        }
        else if($hour>=12 && $hour<=14)
        {
        	$inf=12;
        	$sup=14;
        }
        else if($hour>=15 && $hour<=17)
        {
        	$inf=15;
        	$sup=17;
        }
        else if($hour>=18 && $hour<=20)
        {
        	$inf=18;
        	$sup=20;
        }
        else if($hour>=21 && $hour<=23)
        {
        	$inf=21;
        	$sup=23;
        }
        
        $criteria = new \CDbCriteria;
        $criteria->condition = 'deliveryDate = :dateToday AND idUser = :idUser AND status = 2 AND behaviorHour BETWEEN :inf AND :sup';
        $criteria->params = array (
        		':inf' => $inf,
        		':sup' => $sup,
        		':idUser' => $idUser,
        		':dateToday' => $dateToday
        );
        
        $result = self::model()->findAll($criteria);
        
        if(!empty($result))
        {
        	foreach ($result as $camph)
        	{
        		$product=$camph->SubCampaign->Product;
        		$array[] = $product->ref;
        	}
        }
        return $array;
        
	}
	
	/**
	 *
	 * @param type $id
	 * @return \Business\CampaignHistory
	 */
	static public function load( $id )
	{ 
		return self::model()->findByPk( $id );
	}
	
	/**
	 * @author Saad HDIDOU
	 * @desc recuperer la premi�re fid en stand by d'un client
	 * @param User $idUser
	 */
	static public function getLastStbByUser( $idUser )
	{
		$criteria = new \CDbCriteria;
		$criteria->condition = 'status IN ("-1",  "-2",  "-3",  "-4",  "-5") AND idUser = '.$idUser;
		
		$data = self::model()->findAll($criteria);
		return end($data);
	}
	
	/**
	 * @author Saad HDIDOU
	 * @desc verifie s'il s'agit de la fid courrante
	 * @return bool
	 */
	public function isCurrent()
	{
		$lastCH=self::getLastCampaignHistorybyIdUSer($this->idUser);
		if($this->id == $lastCH->id)
		{
			return true;
		}
		else 
		{
			return false;
		}
	}
	
	static public function getShooted( $startDate, $endDate, $idSubCampaign )
	{
		$criteria = new \CDbCriteria;
		$criteria->condition = 'modifiedShootDate BETWEEN "'.$startDate.'" AND "'.$endDate.'" AND idSubCampaign = '.$idSubCampaign;
	
		$data = self::model()->findAll($criteria);
		return $data;
	}
	
	public function getPurchasedByUserPerdiod($iduser,$period)
	{
	
		return self::model()->findAll(array(
				'condition'=>'deliveryDate between
		cast(ADDDATE(CURRENT_DATE,INTERVAL -:d DAY) as datetime)
 and cast(NOW() as datetime) and  idUser = :user and status  between 1 and 2 ',
				'params'=>array(
						':user'=>$iduser,
						':d'=>$period
				),
		));
	
	
		
	}
	
	//a supprimer
	public function hasPurshasedInter()
	{
		$date = new \DateTime();
		$date->add( new \DateInterval('P1D') );
		$criteria = new \CDbCriteria;
		$criteria->condition ='idUser = '. $this->idUser .' AND status IN (1, 2) AND deliveryDate = \'' . $date->format('Y-m-d') .'\'';
		$list = self::model()->findAll($criteria);
		foreach ($list as $l)
		{
			if ($l->SubCampaign->isInter())
				return true;
		}
		return false;
	}
	
	/**
	 * @author Saad HDIDOU
	 * @desc recuperer la derniere fid inter achete
	 */
	public function getLastPurshasedInter()
	{
		$date = new \DateTime();
		$criteria = new \CDbCriteria;
		$criteria->condition ='idUser = '. $this->idUser .' AND status IN (1, 2) AND deliveryDate >= \'' . $date->format('Y-m-d') .'\'';
		$list = self::model()->findAll($criteria);
		
		$highDL=$date->format('Y-m-d');
		$highCH=NULL;
		foreach ($list as $l)
		{
			if ($l->SubCampaign->isInter())
			{
				if($l->deliveryDate > $highDL)
				{
					$highDL=$l->deliveryDate;
					$highCH=$l;
				}
			}
		}
		return $highCH;
	}
	
	/**
	 * @author Saad HDIDOU
	 * @desc verifier si le client a effectue un achat d'une fid inter qui sera livre dans six heures
	 * @param date $date
	 * @return boolean
	 */
	public function hasPurshasedInterInSixHour( $date )
	{
		$hour = $date->format( 'H' );
	
		if($hour>=0 && $hour<=2)
		{
			$inf=9;
			$sup=11;
		}
		else if($hour>=3 && $hour<=5)
		{
			$inf=12;
			$sup=14;
		}
		else if($hour>=6 && $hour<=8)
		{
			$inf=15;
			$sup=17;
		}
		else if($hour>=9 && $hour<=11)
		{
			$inf=18;
			$sup=20;
		}
		else if($hour>=12 && $hour<=14)
		{
			$inf=21;
			$sup=23;
		}
		else if($hour>=15 && $hour<=17)
		{
			$inf=0;
			$sup=2;
			$date->add(new \DateInterval('P1D'));
		}
		else if($hour>=18 && $hour<=20)
		{
			$inf=3;
			$sup=5;
			$date->add(new \DateInterval('P1D'));
		}
		else if($hour>=21 && $hour<=23)
		{
			$inf=6;
			$sup=8;
			$date->add(new \DateInterval('P1D'));
		}
		 
	
	
		$criteria = new \CDbCriteria;
		$criteria->condition = 'behaviorHour BETWEEN :inf AND :sup AND idUser = :idUser AND deliveryDate = :deliveryDate AND status IN (1,2) ';
		$criteria->params = array (
				':inf' => $inf,
				':sup' => $sup,
				':idUser' => $this->idUser,
				':deliveryDate' => $date->format('Y-m-d')
		);
	
		$result = self::model()->findAll($criteria);
	
		if (!empty($result))
			return true;
			else
				return false;
	}
	
	/**************************************** Load Stand By Campaigns Inter *********************************************************/
	/**
	 * @author Saad HDIDOU
	 * @desc Recuperer les campaigns histories en mode Stand By dans le cas INTER.
	 * @return CampaignHistory[] liste des Campaigns Histories
	 * @param integer $numCron numero de cron execute
	 */
	static public function loadInterStandByCampaigns($numCron)
	{
		$dateToday = new \DateTime();
		switch($numCron)
		{
			case 1 :
				$dateToday->add( new \DateInterval('P1D') );
				return self::model()->findAll( array('condition'=>'status IN (1, 2) AND behaviorHour IN (0,1,2) AND deliveryDate LIKE \''.$dateToday->format('Y-m-d').'\''));
				break;
			case 2 :
				return self::model()->findAll( array('condition'=>'status IN (1, 2) AND behaviorHour IN (3,4,5) AND deliveryDate LIKE \''.$dateToday->format('Y-m-d').'\''));
				break;
			case 3 :
				return self::model()->findAll( array('condition'=>'status IN (1, 2) AND behaviorHour IN (6,7,8) AND deliveryDate LIKE \''.$dateToday->format('Y-m-d').'\''));
				break;
			case 4 :
				return self::model()->findAll( array('condition'=>'status IN (1, 2) AND behaviorHour IN (9,10,11) AND deliveryDate LIKE \''.$dateToday->format('Y-m-d').'\''));
				break;
			case 5 :
				return self::model()->findAll( array('condition'=>'status IN (1, 2) AND behaviorHour IN (12,13,14) AND deliveryDate LIKE \''.$dateToday->format('Y-m-d').'\''));
				break;
			case 6 :
				return self::model()->findAll( array('condition'=>'status IN (1, 2) AND behaviorHour IN (15,16,17) AND deliveryDate LIKE \''.$dateToday->format('Y-m-d').'\''));
				break;
			case 7 :
				return self::model()->findAll( array('condition'=>'status IN (1, 2) AND behaviorHour IN (18,19,20) AND deliveryDate LIKE \''.$dateToday->format('Y-m-d').'\''));
				break;
			case 8 :
				return self::model()->findAll( array('condition'=>'status IN (1, 2) AND behaviorHour IN (21,22,23) AND deliveryDate LIKE \''.$dateToday->format('Y-m-d').'\''));
				break;
			default:
				return false;
		}
	}
	
	/******************************************** / Load Stand By Campaigns *************************************************************************/
	
	/**
	 * @author Saad HDIDOU
	 * @desc renvoie du dernier enregistrement campaignHistory d'un client dans le cas inter
	 * @param $idUser
	 * @return CampaignHistory
	 */
	 public function getLastInterCampaignHistory(){
	
		$criteria = new \CDbCriteria;
		$criteria->alias = 'CampaignHistory';
		$criteria->join='LEFT JOIN V2_subcampaign SubCampaign ON CampaignHistory.idSubCampaign=SubCampaign.id';
		$criteria->condition = 'CampaignHistory.idUser = '.$this->idUser.' and SubCampaign.idCampaign != '.$this->SubCampaign->idCampaign.' and status = 0';
		$listeCampaignHistory = self::model()->findAll($criteria);
		if(isset($listeCampaignHistory))
			return end($listeCampaignHistory) ;
	}
	
	/******************************************  load By Behavior Hour And Delivery Date ***********************************************************/
	/**
	 * @author Yacine RAMI
	 * @desc Recupere les campaigns histories par Heure d'activite et date de livraison.
	 * @param integer $behavior
	 * @param string $deliverDate
	 * @return CampaignHistory[] liste des Campaigns Histories
	 */
	
	static public function loadPreDeliveriesByDate($date)
	{
		//traitement des dates
		$deliverDate = new \DateTime($date);
		$dateSubOne = new \DateTime($date);
		$dateSubOne->sub(new \DateInterval('P1D'));
	
		//recupartion des pre-deliveries
		$criteria = new \CDbCriteria;
		$criteria->condition = '(behaviorHour >= 18  AND deliveryDate = :dateSubOne) OR (behaviorHour < 18  AND deliveryDate = :deliveryDate)';
		$criteria->params = array (
				':deliveryDate' => $deliverDate->format('Y-m-d'),
				':dateSubOne' => $dateSubOne->format('Y-m-d')
		);
	
		//Retourner le resultat de la requete
		return self::model()->findAll($criteria);
	
	}
	/**************************************** Get last CampaignHistory By User Id and date constat ********************************************************/

	/**
	 * @author Fouad DANI
	 * @desc getListCampaignHistoryByIdUserBetweenDate return list des Campaigns Histories entre deux date et par idUser.
	 * @return	CampaignHistory[] list des Campaigns Histories
	 * @param int $idUser id de l'utilisateur
	 * @param date  date Constat modification date
	 * @param date  date Ref modification date
	 */
	static public function getListCampaignHistoryByIdUserBetweenDate($idUser, $dateRef, $dateConstat) {
		//recupartion des pre-deliveries
		$criteria = new \CDbCriteria;
		$criteria->alias = 'CampaignHistory';
		$criteria->addBetweenCondition('initialShootDate',$dateConstat,$dateRef);
		$criteria->condition= 'idUser ='.$idUser;
		$criteria->order = 'id ASC';

		// Récupérer and return la list campaignHistory
		return self::model()->findAll($criteria);
	}

	static public function getListCampaignHistoryByIdUserAndDateRef($idUser, $dateRef) {
		//recupartion des pre-deliveries
		$criteria = new \CDbCriteria;
		$criteria->alias = 'CampaignHistory';
		$criteria->condition = 'initialShootDate >= \''. $dateRef .'\'AND idUser='.$idUser;
		$criteria->order = 'id ASC';

		// Récupérer and return la list campaignHistory
		return self::model()->findAll($criteria);
	}
	
	public function SetDateStbByCampaignHistory($date)
	
	{
		$this->dateStb = $date->format ( 'Y-m-d' );
		$this->update();
	
	
	}
	
	public static function getOutStbByReflationAndDate($idSubCampaign,$date)
	{
		return self::model()->findByAttributes(array('idSubCampaign'=>$idSubCampaign,'dateStb'=>$date));
	}

	/**  getNextShoot By campaignHistory  ********************************************************
	 * @author Fouad DANI
	 * @desc Return next shoot by campaignHistory.
	 * @return	next Shoot
	 * @param object campaignHistory
	 */
	static public function getNextShoot($currentCampaignHistory) {
		if($currentCampaignHistory){
			//return des pre-deliveries
			$criteria = new \CDbCriteria;
			$criteria->alias = 'CampaignHistory';
			$criteria->condition= 'idUser ='.$currentCampaignHistory->idUser;
			$criteria->order = 'id ASC';
			// return la list campaignHistory
			$campaignHistoryList = self::model()->findAll($criteria);
			// Parcourir list campaignHistory et retourner la nextCampaignHistory
			$countList = count($campaignHistoryList);
			for($i = 0; $i < $countList; $i++){
				if($campaignHistoryList[$i]->id == $currentCampaignHistory->id){
					if($i >= $countList - 1){
						return null;
					}else{
						return $campaignHistoryList[$i+1];
					}
				}
			}
		}else{
			return null;
		}
	}

	/** / getNextShoot By campaignHistory ***********************************************************/

	/**  get Next IndexImplication By campaignHistory  ********************************************************
	 * @author Fouad DANI
	 * @desc Next Index Implication by campaignHistory.
	 * @return	Next Index Implication
	 * @param int Index Implication
	 */
	static public function getNextIndexImplication($campaignHistory) {

		return null;

	}

	/** / get Next IndexImplication By campaignHistory ***********************************************************/

	public function getLastShootedSubCampaign()
	{
		$date = new \DateTime();
		$criteria = new \CDbCriteria;
		$criteria->condition ='idUser = '. $this->idUser .' AND status = 0 AND id != '.$this->id.' AND modifiedShootDate <= \'' . $date->format('Y-m-d') .'\'';
		$criteria->order = 'modifiedShootDate DESC';
		$criteria->limit = '1';
		return self::model()->findAll($criteria);
	}
	
}
?>
<?php



namespace Business;



/**
 * Description of AnacondaSubdivision
 *
 * @author Yacine RAMI 
 * @package Business.AnacondaSubdivision
 */

class AnacondaSubdivision extends \AnacondaSubdivision

{
	const  PENDING_SUBDIVISED =  "subdivised=0 and emailUser NOT IN (SELECT email FROM V2_user as u	INNER JOIN V2_campaignHistory as ch ON u.id=ch.idUser)";
	
	// Define Tags 
	
	const CLIENTS  = 1;
	const OPENERS_CLICKERS  = 2;
	const LEADS_DOUBLES  = 3;
	const INACTIFS  = 4;

	
	
	/**
	 * Retourne l'Anaconda Setting correspondant
	 * @param integer $id
	 * @return \Business\AnacondaSubdivision
	 */

	static public function load( $id )
	{
		return self::model()->findByPk( $id );
	}
	/**
	 * Rechercher par emailUser
	 * @param string $mail
	 * @return @return \Business\AnacondaSubdivision 
	 */
	static public function loadByEmail( $mail )
	{
		return self::model()->findByAttributes(array( 'emailUser' => $mail ));
	}
	
	/**
	 * @desc Recuperation d'un nombre de leads de la tete de la file subdivision 
	 * @param integer $Number nombre de leads à recuperer
	 */
	static public function loadFirstNbr_old($number)
	{
		$count = self::model()->countByAttributes(array(
				'subdivised'=> 0
		));
		
		if($number>$count)
		{
			$number = $count;
		}
		
		$half = (int)$number/2;
		
		if($number%2==0)
		{
			$numb1 = intval($half);
			$numb2 = intval($half);
		}
		else
		{
			$numb1 = intval($half)+1;
			$numb2 = intval($half);
		
		}
		
		$sql='SELECT * FROM V2_anacondaSubdivision 
				WHERE subdivised = 0 and 
				emailUser NOT IN 
				(SELECT email
				FROM V2_user as u 
				INNER JOIN V2_campaignHistory 
				as 
				ch ON u.id=ch.idUser) 
				ORDER BY purchasedOldAnaconda DESC LIMIT :number';
		$params=array('number'=>(int)$numb1);
		$listTop =  self::model()->findAllBySql($sql,$params);
		
		$sql='SELECT * FROM V2_anacondaSubdivision
				WHERE subdivised = 0 and
				emailUser NOT IN
				(SELECT email
				FROM V2_user as u
				INNER JOIN V2_campaignHistory
				as
				ch ON u.id=ch.idUser)
				ORDER BY purchasedOldAnaconda ASC LIMIT :number';
		$params=array('number'=>(int)$numb2);
		$listBottom =  self::model()->findAllBySql($sql,$params);
		
		return array_merge($listTop,$listBottom);
	}
	
	/**
	 * @desc Recuperation d'un nombre de leads de la tete de la file subdivision 
	 * @param integer $Number nombre de leads à recuperer
	 */
static public function loadFirstNbr($number)
	{
		$criteria = new \CDbCriteria;
		
		// calcul  des comptages des leads par Tag ( inactifs, lead double , ouvreur/cliqueurs, clients)
		$inactifs = self::countInactifs();
		$openClick = self::countOpenClick();
		$leadsDoubles = self::countLeadsDoubles();
		$clients = self::countClients();
		$all = self::countAll();
		
		if($all > 0)
		{
			// Calcul des pourcentages de chaque type de lead subdivisé 
			$inacPerc =$inactifs/$all;
			$openPerc = $openClick/$all;
			$leadDoublePerc = $leadsDoubles/$all;
			$clientsPerc = $clients/$all;
			
			
			//Calcul des nombre à recuprer pour chaque type de leads
	    	$inacNumber = intval($inacPerc* $number);
	    	$openNumber = intval($openPerc * $number);
	    	$leadDoubleNumber = intval($leadDoublePerc * $number);
	    	$clientsNumber = intval($clientsPerc * $number);
			
			$list = array();
			////////////////Recuperation  Clients///////////
			$criteria->condition = "purchasedOldAnaconda = ".self::CLIENTS." and ".self::PENDING_SUBDIVISED;
			$criteria->limit=$clientsNumber;
			$list = array_merge(self::model()->findAll($criteria),$list);
			
			////////////////Recuperation  Ouvreurs CLiqueurs///////////
			$criteria->condition = "purchasedOldAnaconda = ".self::OPENERS_CLICKERS." and ".self::PENDING_SUBDIVISED;
			$criteria->limit=$openNumber;
			$list = array_merge(self::model()->findAll($criteria),$list);
			
			////////////////Recuperation  Leads Doubles///////////
			$criteria->condition = "purchasedOldAnaconda =".self::LEADS_DOUBLES." and ".self::PENDING_SUBDIVISED;
			$criteria->limit=$leadDoubleNumber;
			$list = array_merge(self::model()->findAll($criteria),$list);
			
			////////////////Recuperation  Inactifs///////////
			$criteria->condition = "purchasedOldAnaconda = ".self::INACTIFS." and ".self::PENDING_SUBDIVISED;
			$criteria->limit=$inacNumber;
			
			return array_merge(self::model()->findAll($criteria),$list);
		}
		
	}
	
	/////////////////////////////////////////////////////////////////////// Count by Tag for Non Subdivised leads /////////////////////////////////////////////////////////////////////
	/******** Clients ***************/
	
	static public function countClients()
	{
		$criteria = new \CDbCriteria;
		$criteria->condition = "purchasedOldAnaconda = ".self::CLIENTS." and ".self::PENDING_SUBDIVISED;
		return self::model()->count($criteria);
	}
	/******** Ouvreurs Cliqueurs ***************/
	
	static public function countOpenClick()
	{
		$criteria = new \CDbCriteria;
		$criteria->condition = "purchasedOldAnaconda = ".self::OPENERS_CLICKERS." and ".self::PENDING_SUBDIVISED;
		return self::model()->count($criteria);
	}
	
	/******** Leads Doubles ***************/
	
	static public function countLeadsDoubles()
	{
		$criteria = new \CDbCriteria;
		$criteria->condition = "purchasedOldAnaconda =".self::LEADS_DOUBLES." and ".self::PENDING_SUBDIVISED;
		return self::model()->count($criteria);
	}
	
	/******** Inactifs ***************/
	
	static public function countInactifs()
	{
		$criteria = new \CDbCriteria;
		$criteria->condition = "purchasedOldAnaconda = ".self::INACTIFS." and ".self::PENDING_SUBDIVISED;
		return self::model()->count($criteria);
	}
			
	/**************** ALL ****************/
	
	static public function countAll()
	{
		$criteria = new \CDbCriteria;
		$criteria->condition = self::PENDING_SUBDIVISED;
		return self::model()->count($criteria);
	}
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    static public function loadTagByEmail($email)
    {
    	return self::model()->findByAttributes( array( 'emailUser' => $email) )->purchasedOldAnaconda;
    }
} 
 
 

?>
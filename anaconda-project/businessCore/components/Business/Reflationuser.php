<?php



namespace Business;



/**
 * Description of reflationuser
 *
 * @author YacineR 
 * @package Business.Reflationuser
 */

class Reflationuser extends \Reflationuser

{
	public $origin=0;
    public $maxID;

	static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * Retourne l'Reflationuser correspondant
	 * @param type $id
	 * @return \Business\Reflationuser
	 */
	static public function load( $id )
	{
		return self::model()->findByPk( $id );
	}
	
	/**
	 * Retourne les Reflationusers correspondants
	 * @param unknown $shootDate
	 * @return \Business\Reflationuser
	 */
	static public function loadByShootDate( $shootDate)
	{
		$criteria = new \CDbCriteria;
		$criteria->condition = 'shootDate LIKE \'%'.$shootDate.'%\'';
		
		return self::model()->findAll($criteria); 
	}
	
	/**
	 * 
	 */
	static public function loadEmptyUsers()
	{
		$criteria = new \CDbCriteria;
		$criteria->condition = 'idUser IS NULL';
	
		return self::model()->findAll($criteria);
	}
	/////////////////////////////////////Moteur de test///////////////////////////////////////////////
	static public function loadByIdUserAndIdSubCamapaign($idUser,$idSubCampaign)
	{
		return self::model()->findAll( array('condition'=>'idUser = $idUser AND idSubCampaign = $idSubCampaign',
				'select'=>'indiceImplication','limit'=>1,'order'=>'DESC'));
	}

	static public function loadByIdUserAndIdSubCamapaignReflation($idUser,$idSubCampaignReflation)
	{

		return self::model()->findAll( array('condition'=>"idUser = ".$idUser." AND idSubCampaignReflation = ".$idSubCampaignReflation,
				'select'=>'indiceImplication'));
	}

	/**
	 * @desc cette m�thode retourne les subcampaignrelfations envoyees pour une date donnee
	 * @author Yacine RAMI
	 * @param id de la subcampaignreflation $idSubcampaignReflation
	 * @param date d'envoi du linkmail $date
	 */
	static public function loadBySubcampaignReflationAndDate($idSubcampaignReflation,$date)
	{
		return self::model()->findAll(array('condition'=>"idSubCampaignReflation = ".$idSubcampaignReflation." AND shootDate LIKE '%".$date."%'",
											'select'=>'id,email'));
	}

	static public function getSentMessagesByReflationAndDate($date, $idSubCampaignReflation, $vague)
	{
		switch($vague)
		{
			case 1:
				$min=0;
				$max=2;
				break;
			case 2:
				$min=3;
				$max=5;
				break;
			case 3:
				$min=6;
				$max=8;
				break;
			case 4:
				$min=9;
				$max=11;
				break;
			case 5:
				$min=12;
				$max=14;
				break;
			case 6:
				$min=15;
				$max=17;
				break;
			case 7:
				$min=18;
				$max=20;
				break;
			case 8:
				$min=21;
				$max=23;
				break;

		}
		$criteria = new \CDbCriteria;
		$criteria->condition = 'shootDate BETWEEN  \''.$date->format('Y-m-d').' '.$min.':00:00\' AND  \''.$date->format('Y-m-d').' '.$max.':59:59\' AND idSubCampaignReflation = '.$idSubCampaignReflation;
		return self::model()->findAll($criteria);
	}
//chercher toutes les reflation avec iduser,date shoot et la subcampaign
    public static function findByDatesAndUser($idUser,$dateRef,$dateConstat,$idSubcampRef)
    {
        $criteria = new \CDbCriteria;
        $criteria->condition = 'shootDate BETWEEN \'%'.$dateRef.'%\' AND \'%'.$dateConstat.'%\'';
        $criteria->condition = 'idSubCampaignReflation = '.$idSubcampRef;
        $criteria->condition = 'idUser = '.$idUser;
        return self::model()->findAll($criteria);

	}
    public static function findByDateAndIndice($dateRef,$dateConstat,$idSubcampRef,$indice)
    {
        $criteria = new \CDbCriteria;
        $criteria->condition = 'shootDate BETWEEN \'%'.$dateRef.'%\' AND \'%'.$dateConstat.'%\'';
        $criteria->condition = 'idSubCampaignReflation = '.$idSubcampRef;
        $criteria->condition = 'indiceImplication = '.$indice;
        return self::model()->findAll($criteria);

    }


    /**
     * return la dernire reflation envoyé à un client avant la date donnée
     * @param $idUser
     * @param $dateRef
     * @return int
     */

    public static function lastReceivedReflation($idUser,$dateRef) {
        $criteria = new \CDbCriteria;
       $criteria->condition = 'date (shootDate) <= \''.$dateRef.'\' AND idUser='.$idUser;
       $criteria->order='shootDate DESC';
       $criteria->limit ='1';
        return self::model()->find($criteria);
    }



	
}



?>
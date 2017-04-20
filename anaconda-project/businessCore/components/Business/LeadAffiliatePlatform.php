<?php

namespace Business;

/**
 * Description of LeadAffiliatePlatform
 *
 * @author JulienL
 * @package Business.AffiliatePlatform
 */
class LeadAffiliatePlatform extends \Leadaffiliateplatform
{
	/**
	 * @return array relational rules.
	 * Surcharge pour que la relation soit sur la classe Business
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'User' => array(self::BELONGS_TO, '\Business\User', 'idUser'),
			'AffiliatePlatform' => array(self::BELONGS_TO, '\Business\AffiliatePlatform', 'idAffiliatePlatform'),
		);
	}

	// *********************** STATIC *********************** //
	static function model($className=__CLASS__)
	{
        return parent::model($className);
    }

	/**
	 *
	 * @param type $id
	 * @return \Business\LeadAffiliatePlatform
	 */
	static public function load( $id )
	{
		return self::model()->findByPk( $id );
	}
	
	/**
	 * @author Saad HDIDOU
	 * @desc: recuperer les clients qui ont recu la relance 3 de la chaine VP
	 */
	static public function getReceivedR3()
	{
		$setting=\Business\PorteurSettings::getAllSettings();
		$period=$setting[0]->periodAnaconda;
		$date = new \DateTime();
		$date->sub( new \DateInterval('P'.$period.'D') );
		$criteria = new \CDbCriteria;
		$criteria->alias = 'LeadAffiliatePlatform';
		$criteria->join='LEFT JOIN V2_user User ON User.id=LeadAffiliatePlatform.idUser';
		$criteria->condition = 'LeadAffiliatePlatform.creationDate LIKE \'%'.$date->format('Y-m-d').'%\' AND User.intialDate IS NULL AND (User.visibleDesinscrire IS NULL OR User.visibleDesinscrire = 0) ';
		
		$list = self::model()->findAll($criteria); 
		return array_map(function($element) { return $element->User->email;}, $list);
	}
}

?>
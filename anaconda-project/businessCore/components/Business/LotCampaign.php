<?php



namespace Business;



/**
 * Description of AnacondaSettings
 *
 * @author YacineR 
 * @package Business.LotCampaign
 */

class LotCampaign extends \LotCampaign

{

	/**
	 * Classe qui hérite du Model LotCampaign. 
	 */
	
	/**
	 * Retourne all Lots added by mohamed meski
	 * @return \Business\AnacondaSettings
	 */
	static public function getAllLots()
	{
		return self::model()->findAll();
	}
	
	/**
	 * Retourne l'Anaconda Setting correspondant
	 * @param type $id
	 * @return \Business\LotCampaign
	 */
	static public function load( $id )
	{
		return self::model()->findByPk( $id );
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
				'Campaigns' => array(self::HAS_MANY, '\Business\Campaign', 'idLotCampaign'),
		);
	}

}



?>
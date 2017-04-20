<?php



namespace Business;



/**
 * Description of AnacondaTrigger
 *
 * @author SaadH 
 * @package Business.AnacondaTrigger
 */

class AnacondaTrigger extends \AnacondaTrigger

{
	
	static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * Retourne l'AnacondaTrigger correspondant
	 * @param type $id
	 * @return \Business\AnacondaTrigger
	 */
	static public function load( $id )
	{
		return self::model()->findByPk( $id );
	}
	
	/**
	 * 
	 * @param unknown $name
	 * @param unknown $idsubcampaign
	 */
	static public function loadByIdTrigger($idTrigger) {
		return self::model ()->findByAttributes ( array (
				'idTrigger' => $idTrigger,
		) );
	}
	
	
	
	
}



?>
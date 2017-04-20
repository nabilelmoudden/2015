<?php



namespace Business;



/**
 * Description of AnacondaSettings
 *
 * @author YacineR 
 * @package Business.AnacondaSettings
 */

class AnacondaSettings extends \AnacondaSettings

{

	/**
	 * Retourne l'Anaconda Setting correspondant
	 * @param integer $id
	 * @return \Business\AnacondaSettings
	 */
	static public function load( $id )
	{
		return self::model()->findByPk( $id );
	}
	
	/**
	 *
	 * @author soufiane balkaid
	 * @param $groupePrice groupPrice
	 *        	
	 * @return AnacondaSettings[]
	 */
	static public function getSettingsByGp($groupePrice) {
		return self::model ()->findAllByAttributes ( array (
				'groupPrice' => $groupePrice 
		) );
	}
	/**
	 *
	 * @author Anas Hilama
	 * @param $groupePrice groupPrice
	 *
	 * @return StepSum[]
	 */
	
	static public function getStepSumByGp($groupeprice) {
	
		return self::model ()->findAllBySql ('select nextStepSum from V2_anacondaSettings where groupPrice = '.$groupeprice );
	
	}
	/**
	 * Retourne all anaconda settings added by mohamed meski
	 * @return \Business\AnacondaSettings
	 */
	static public function getAllSettings()
	{
		return self::model()->findAll();
	} 
	
	/**
	 * @author soufiane balkaid
	 * @return int  max(groupPrice)
	 */
	static public function getMaxGp() {
		return self::model ()->findAllBySql ('select max(groupPrice) as MaxGroupPrice from V2_anacondaSettings' );
	}
	
      
} 
 
 

?>
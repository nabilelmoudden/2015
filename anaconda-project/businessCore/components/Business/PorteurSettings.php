<?php



namespace Business;



/**
 * Description of PorteurSettings
 *
 * @author MeskiM
 * @package Business.PorteurSettings
 */

class PorteurSettings extends \PorteurSettings

{

	/**
	 * Retourne l'Anaconda Setting correspondant 
	 * @param integer $id
	 * @return \Business\PorteurSettings
	 */
	static public function load( $id )
	{
		return self::model()->findByPk( $id );
	}
	
	/**
	 * Retourne all anaconda settings added by mohamed meski
	 * @return \Business\PorteurSettings
	 */
	static public function getAllSettings()
	{
		return self::model()->findAll();
	} 
	
	
      
} 
 
 

?>
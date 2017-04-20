<?php

namespace Business;

/**
 * Description of Config
 *
 * @author JulienL
 * @package Business.Config
 */
class Config extends \Config
{


	// *********************** STATIC *********************** //
	static function model($className=__CLASS__)
	{
        return parent::model($className);
    }

	/**
	 *
	 * @param int $id
	 * @return \Business\Config
	 */
	static public function load( $id )
	{
		return self::model()->findByPk( $id );
	}

	/**
	 *
	 * @param string $key
	 * @return \Business\Config
	 */
	static public function loadByKey( $key )
	{
		return self::model()->findByAttributes( array( 'key' => $key ) );
	}
	
	/**
	 *
	 * @param string $key
	 * @return \Business\Config
	 */
	static public function loadByKeyAndSite( $key )
	{
		$site	= substr(\Yii::app()->params['porteur'],0,2);
		return self::model()->findByAttributes( array( 'key' => $key,'idSite' => $site ) );
	}
}

?>
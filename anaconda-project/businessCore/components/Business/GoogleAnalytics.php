<?php

namespace Business;

/**
 * Description of GoogleAnalytics
 *
 * @author JalalB
 * @package Business.GoogleAnalytics
 */
class GoogleAnalytics extends \GoogleAnalytics
{

	// *********************** STATIC *********************** //
	static function model($className=__CLASS__)
	{
        return parent::model($className);
    }

	/**
	 *
	 * @param int $id
	 * @return \Business\GoogleAnalytics
	 */
	static public function load( $id )
	{
		return self::model()->findByPk( $id );
	}

	/**
	 *
	 * @param string $key
	 * @return \Business\GoogleAnalytics
	 */
	static public function loadByKey( $key )
	{
		return self::model()->findByAttributes( array( 'code' => $key ) );
	}
}

?>
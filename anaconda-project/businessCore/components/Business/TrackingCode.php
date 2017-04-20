<?php

namespace Business;

/**
 * Description of TrackingCode
 *
 * @author JulienL
 * @package Business.AffiliatePlatform
 */
class TrackingCode extends \Trackingcode
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
			'ChoiceTrackingCode' => array(self::HAS_MANY, '\Business\ChoiceTrackingCode', 'idTrackingCode'),
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
	 * @return \Business\TrackingCode
	 */
	static public function load( $id )
	{
		return self::model()->findByPk( $id );
	}
}

?>
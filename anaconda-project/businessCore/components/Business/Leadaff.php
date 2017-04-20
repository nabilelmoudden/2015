<?php

namespace Business;

/**
 * Description of LeadAffiliatePlatform
 *
 * @author JulienL
 * @package Business.AffiliatePlatform
 */
class Leadaff extends \Leadaff
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
			'User' => array(self::BELONGS_TO, '\Business\User', 'idInternaute'),
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
}

?>
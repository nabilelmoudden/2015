<?php

namespace Business;

/**
 * Description of AffiliatePlatform
 *
 * @author JulienL
 * @package Business.AffiliatePlatform
 */
class Segmentation extends \Segmentation
{
	public $codeSite;

	private $ManagerS	= false;
	private $ManagerO	= false;

	/**
	 * @return array relational rules.
	 * Surcharge pour que la relation soit sur la classe Business
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			
		);
	}

	public function search(  )
	{
		return true;
	}

	// *********************** STATIC *********************** //
	static function model($className=__CLASS__)
	{
        return parent::model($className);
    }

	/**
	 *
	 * @param type $id
	 * @return \Business\AffiliatePlatform
	 */
	static public function load( $id )
	{
		return self::model()->findByPk( $id );
	}
}

?>

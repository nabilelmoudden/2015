<?php

namespace Business;

/**
 * Description of UserRole
 *
 * @author JulienL
 * @package Business.User
 */
class UserRole extends \Userrole
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
	 * @return \Business\UserRole
	 */
	static public function load( $id )
	{
		return self::model()->findByPk( $id );
	}


    public static function loadByUser( $idUser )
    {
        return self::model()->findAllByAttributes(['idUser' => $idUser]);
    }
}

?>
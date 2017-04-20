<?php

namespace Business;

/**
 * Description of Role
 *
 * @author JulienL
 * @package Business.User
 */
class Role extends \Role
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
			'Authorization' => array(self::MANY_MANY, '\Business\Authorization', $this->tableNamePrefix().'roleauthorization(idRole, idAuthorization)'),
			'User' => array(self::MANY_MANY, '\Business\User', $this->tableNamePrefix().'userrole(idRole, idUser)'),
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
	 * @return \Business\Role
	 */
	static public function load( $id )
	{
		return self::model()->findByPk( $id );
	}
}

?>
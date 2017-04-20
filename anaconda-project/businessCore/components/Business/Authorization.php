<?php

namespace Business;

/**
 * Description of Authorization
 *
 * @author JulienL
 * @package Business.User
 */
class Authorization extends \Authorization
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
			'Role' => array(self::MANY_MANY, '\Business\Role', $this->tableNamePrefix().'roleauthorization(idAuthorization, idRole)'),
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
	 * @return \Business\Authorization
	 */
	static public function load( $id )
	{
		return self::model()->findByPk( $id );
	}
}

?>
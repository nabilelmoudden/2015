<?php

namespace Business;

/**
 * Description of Alert
 *
 * @author AL.
 * @package Business.Alert
 */
class UserPorteur extends \UserPorteur
{

	public function init()
	{
		parent::init();

		//$this->onBeforeDelete	= array( $this, 'deleteAlert' );
		//$this->onBeforeSave		= array( $this, 'createAlert' );
	}

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
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

	
	/**
	 * Recherche
	 * @param string $order Ordre
	 * @param int $pageSize	Nb de result par page
	 * @return CActiveDataProvider	CActiveDataProvider
	 */
	public function search( $order = false, $pageSize = 0 )
	{
		$Provider = parent::search();

		if( $pageSize == false )
			$Provider->setPagination( false );
		else
			$Provider->pagination->pageSize = $pageSize;

		if( $order != false )
			$Provider->criteria->order = $order;
		else
			$Provider->criteria->order ='id DESC';

		return $Provider;
	}

    public static function loadById($id)
    {
        return self::model()->findByPk($id);
    }

    public static function loadByUserAndPorteur($idUser, $idPorteur)
    {
        return self::model()->findAllByAttributes(['user' => $idUser, 'porteur' => $idPorteur]);
    }
	
}

?>
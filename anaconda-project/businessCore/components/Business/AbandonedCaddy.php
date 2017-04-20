<?php

namespace Business;

/**
 * Class AbandonedCaddy
 *
 * @author JulienL
 * @package Business.Invoice
 */
class AbandonedCaddy extends \Abandonedcaddy
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
			'User' => array(self::BELONGS_TO, '\Business\User', 'idAdmin'),
			'Invoice' => array(self::BELONGS_TO, '\Business\Invoice', 'idInvoice'),
		);
	}
	
	/**
	 * Recherche
	 * @param string $order Ordre
	 * @param int $pageSize	Nb de result par page
	 * @return \CActiveDataProvider	CActiveDataProvider
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

		return $Provider;
	}

	// *********************** STATIC *********************** //
	static function model($className=__CLASS__)
	{
        return parent::model($className);
    }

	/**
	 *
	 * @param type $id
	 * @return \Business\AbandonedCaddy
	 */
	static public function load( $id )
	{
		return self::model()->findByPk( $id );
	}
}

?>
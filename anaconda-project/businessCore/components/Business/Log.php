<?php

namespace Business;

/**
 * Description of Log
 *
 * @author JulienL
 * @package Business.Log
 */
class Log extends \Log
{
	// Types d'action :
	const ACTION_DEFAULT		= 0;
    const ACTION_OPEN			= 1;	// Affichage d'une page du site
	const ACTION_LOGIN			= 2;	// Login backoffice
	const ACTION_LOGOUT			= 3;	// Logout backoffice
	const ACTION_ROUTER			= 4;	// Routage vers un site promo
	const ACTION_ADMIN			= 5;	// Action d'adminstration ( Insert, update, ...  )
	const ACTION_REDIRECT_PP	= 6;	// Redirection vers un processeur de paiement
	const ACTION_TY_CHECK		= 7;	// Affichage d'une page de remerciement paiement cheque
	const ACTION_TY_CB			= 8;	// Affichage d'une page de remerciement paiement CB
	const ACTION_TY_FAILED		= 9;	// Affichage de la page de paiement refusé
	const ACTION_TRANS_OK		= 10;	// Payment accepté par le paymentProcessor
	const ACTION_TRANS_NOK		= 11;	// Payment refusé par le paymentProcessor

	/**
	 * @return array relational rules.
	 * Surcharge pour que la relation soit sur la classe Business
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'Product' => array(self::BELONGS_TO, '\Business\Product', 'idProduct'),
			'User' => array(self::BELONGS_TO, '\Business\User', 'idUser'),
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
	 * @return \Business\Log
	 */
	static public function load( $id )
	{
		return self::model()->findByPk( $id );
	}
}

?>
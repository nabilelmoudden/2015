<?php
namespace Business;

namespace Business;

/**
 * Description of Invoice
 *
 * @author YoussefB
 * @package Business.Logcallbackpacnet
 */
 
class Logcallbackpacnet extends \Logcallbackpacnet
{
	const MD_STATUS  = 'APPROVED';
	const PPP_STATUS = 'OK';

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array();
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

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Logcallbackpacnet the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * Recupere un User par son adresse mail
	 * @param type $mail
	 * @return \Business\User
	
	static public function loadByEmail( $mail )
	{
		
		return self::model()->findByAttributes(array( 'Email' => $mail ));
	}
	 */
		
	
}

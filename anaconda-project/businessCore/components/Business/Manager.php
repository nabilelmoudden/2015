<?php

namespace Business;

/**
 * Description of Log
 *
 * @author JulienL
 * @package Business.Log
 */
class Manager extends \Manager
{
	const TYPE_STRATEGIQUE	= 0;
	const TYPE_OPERATIONNEL	= 1;

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'User' => array(self::BELONGS_TO, '\Business\User', 'idUser'),
			'AffiliatePlatform' => array(self::BELONGS_TO, '\Business\AffiliatePlatform', 'idAffiliatePlatform'),
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
	
	/**
	 * Retourne la date de debut
	 * @return \DateTime 
	 */
	public function getDateStart()
	{
		$Date = \DateTime::createFromFormat( 'Y-m-d H:i:s', $this->dateStart );
		return $Date;
	}
	
	/**
	 * Retourne la date de fin
	 * @return \DateTime 
	 */
	public function getDateEnd()
	{
		$Date = \DateTime::createFromFormat( 'Y-m-d H:i:s', $this->dateEnd );
		return $Date;
	}

	// *********************** STATIC *********************** //
	static function model($className=__CLASS__)
	{
        return parent::model($className);
    }

	/**
	 *
	 * @param int $id
	 * @return \Business\Manager
	 */
	static public function load( $id )
	{
		return self::model()->findByPk( $id );
	}

	/**
	 * Retourne les Manager associé a une plateforme
	 * @param int $idAP	ID Affiliate platform
	 * @param int $type	Type de Manager ( cf constante de la classe )
	 * @return array[\Business\Manager]
	 */
	static public function getByPlatformAndType( $idAP, $type )
	{
		return self::model()->findAllByAttributes( array( 
			'idAffiliatePlatform' => $idAP, 
			'type' => $type 
		),
		array(
			'order' => 'dateStart DESC, dateEnd ASC'
		) );
	}
}

?>

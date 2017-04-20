<?php

namespace Business;

/**
 * Description of TrackingCode
 *
 * @author JulienL
 * @package Business.AffiliatePlatform
 */
class PromoSite extends \PromoSite
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
			'RouterPS' => array(self::HAS_MANY, '\Business\RouterPS', 'idPromoSite'),
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
	 * Retourne l'URL pour acceder au site promo
	 * @return string
	 */
	public function getUrl()
	{
		$ConfDNS = \Business\Config::loadByKey( 'DNS' );
		return ( strpos( $this->url, 'http' ) !== false ) ? $this->url : $ConfDNS->value.'/voyance/'.$this->url;
	}

	// *********************** STATIC *********************** //
	static function model($className=__CLASS__)
	{
        return parent::model($className);
    }

	/**
	 *
	 * @param type $id
	 * @return \Business\PromoSite
	 */
	static public function load( $id )
	{
		return self::model()->findByPk( $id );
	}

	/**
	 * Retourne un tableau de site promo disponible
	 * @return array Tableau de site promo
	 */
	static public function getAvailablePromoSite()
	{
		return ( isset(\Yii::app()->params['listPromoSite']) && is_array(\Yii::app()->params['listPromoSite']) ) ? \Yii::app()->params['listPromoSite'] : array();
	}
}

?>
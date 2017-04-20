<?php

namespace Business;

/**
 * Description of AffiliatePlatformSubId
 *
 * @author JulienL
 * @package Business.AffiliatePlatform
 */
class AffiliatePlatformSubId extends \Affiliateplatformsubid
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
			'AffiliatePlatform' => array(self::BELONGS_TO, '\Business\AffiliatePlatform', 'idAffiliatePlatform'),
			'ChoiceTrackingCode' => array(self::HAS_MANY, '\Business\ChoiceTrackingCode', 'idAffiliatePlatformSubId'),
			'RouterPS' => array(self::HAS_MANY, '\Business\RouterPS', 'idAffiliatePlatformSubId'),
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
	 * Met a jour / Créé les TrackingCodes lié a cet SubID
	 * @param array $newTrackingCode Tableau contenant les trackingcodes
	 * @return boolean
	 */
	public function updateTrackingCode( $newTrackingCode )
	{
		\Yii::import( 'ext.ArrayHelper' );

		if( ( $TC = $this->getTrackingCode() ) )
		{
			if( $this->forceEmpty == false && \ArrayHelper::isEmpty($newTrackingCode) )
				return $TC->delete();

			$TC->attributes = $newTrackingCode;
			return $TC->save();
		}
		else
		{
			if( $this->forceEmpty == false && \ArrayHelper::isEmpty($newTrackingCode) )
				return true;

			$CTC							= new \Business\ChoiceTrackingCode();
			$CTC->idAffiliatePlatformSubId	= $this->id;
			$CTC->idAffiliatePlatform		= $this->idAffiliatePlatform;
			if( !$CTC->addTrackingCode( $newTrackingCode ) )
				return false;

			return $CTC->save();
		}
	}

	/**
	 * Met a jour le PromoSite lié a cet plateforme
	 * @param int $idPromoSite ID du PromoSite
	 * @return boolean
	 */
	public function updatePromoSite( $idPromoSite )
	{
		$Router = $this->RouterPS( array( 'condition' => 'idAffiliateCampaign IS NULL AND idAffiliatePlatform = "'.$this->idAffiliatePlatform.'"' ) );
		if( is_array($Router) && count($Router) > 0 )
		{
			$Router	= $Router[0];

			// Si $idPromoSite == 0 alors SitePromo par defaut pour le porteur, donc on supprime l'entré dans la DB
			if( $idPromoSite <= 0 )
				return $Router->delete();
		}
		else
		{
			// Si $idPromoSite == 0 alors SitePromo par defaut pour le porteur, donc on ne créé aucune entré ds la DB
			if( $idPromoSite <= 0 )
				return true;

			$Router								= new \Business\RouterPS();
			$Router->idAffiliatePlatformSubId	= $this->id;
			$Router->idAffiliatePlatform		= $this->idAffiliatePlatform;
		}

		$Router->idPromoSite = $idPromoSite;
		return $Router->save();
	}

	/**
	 * Retourne les TrackingCodes lié a la SubID courante
	 * @return \Business\TrackingCode
	 */
	public function getTrackingCode()
	{
		if( $this->id <= 0 ) return false;

		$CTC = \Business\ChoiceTrackingCode::loadByAP( $this->idAffiliatePlatform, NULL, $this->id );
		return ( is_object($CTC) ) ? $CTC->TrackingCode : false;
	}

	/**
	 * Retourne le PromoSite lié a la plateforme courante
	 * @return \Business\PromoSite
	 */
	public function getPromoSite()
	{
		if( $this->id <= 0 ) return false;

		$Router = \Business\RouterPS::loadByAP( $this->idAffiliatePlatform, NULL, $this->id );
		return ( is_array($Router) && count($Router) > 0 ) ? $Router[0]->PromoSite : false;
	}

	// *********************** STATIC *********************** //
	static function model($className=__CLASS__)
	{
        return parent::model($className);
    }

	/**
	 * Retourne l'instance correspondante a l'ID
	 * @param int $id
	 * @return \Business\AffiliatePlatformSubId
	 */
	static public function load( $id )
	{
		return self::model()->findByPk( $id );
	}

	/**
	 * Retourne l'instance correspondante au SubID
	 * @param int $id
	 * @return \Business\AffiliatePlatformSubId
	 */
	static public function loadBySubId( $idAffiliatePlatform, $subId )
	{
		return self::model()->findByAttributes( array( 'idAffiliatePlatform' => $idAffiliatePlatform, 'subID' => $subId ) );
	}
}

?>
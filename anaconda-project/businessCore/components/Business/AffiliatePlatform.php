<?php

namespace Business;

/**
 * Description of AffiliatePlatform
 *
 * @author JulienL
 * @package Business.AffiliatePlatform
 */
class AffiliatePlatform extends \Affiliateplatform
{
	public $codeSite;

	private $ManagerS	= false;
	private $ManagerO	= false;

	/**
	 * @return array relational rules.
	 * Surcharge pour que la relation soit sur la classe Business
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'AffiliatePlatformSubId' => array(self::HAS_MANY, '\Business\AffiliatePlatformSubId', 'idAffiliatePlatform'),
			'ChoiceTrackingCode' => array(self::HAS_MANY, '\Business\ChoiceTrackingCode', 'idAffiliatePlatform'),
			'LeadAffiliatePlatfom' => array(self::HAS_MANY, '\Business\LeadAffiliatePlatfom', 'idAffiliatePlatfom'),
			'RouterPS' => array(self::HAS_MANY, '\Business\RouterPS', 'idAffiliatePlatform'),
            'Site' => array(self::BELONGS_TO, '\Business\Site', 'idSite'),
            'Manager' => array(self::HAS_MANY, '\Business\Manager', 'idAffiliatePlatform')
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

		$Provider->criteria->with = array( 'Site' );
		$Provider->criteria->compare( 'Site.code', $this->codeSite, true );

		if( $pageSize == false )
			$Provider->setPagination( false );
		else
			$Provider->pagination->pageSize = $pageSize;

		if( $order != false )
			$Provider->criteria->order = $order;

		return $Provider;
	}

	/**
	 * Met a jour / Créé les TrackingCodes lié a cet plateforme
	 * @param array $newTrackingCode Tableau contenant les trackingcodes
	 * @return boolean
	 */
	public function updateTrackingCode( $newTrackingCode )
	{
		\Yii::import( 'ext.ArrayHelper' );

		if( ( $TC = $this->getTrackingCode() ) )
		{
			
			

			$TC->attributes = $newTrackingCode;
			return $TC->save();
		}
		else
		{
			
			

			$CTC						= new \Business\ChoiceTrackingCode();
			$CTC->idAffiliatePlatform	= $this->id;
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
		$Router = $this->RouterPS( array( 'condition' => 'idAffiliateCampaign IS NULL AND idAffiliatePlatformSubId IS NULL' ) );
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

			$Router							= new \Business\RouterPS();
			$Router->idAffiliatePlatform	= $this->id;
		}

		$Router->idPromoSite = $idPromoSite;
		return $Router->save();
	}

	/**
	 * Retourne les TrackingCodes lié a la plateforme courante
	 * @return \Business\TrackingCode
	 */
	public function getTrackingCode()
	{
		if( $this->id <= 0 ) return false;

		$CTC = \Business\ChoiceTrackingCode::loadByAP( $this->id );
		return ( is_object($CTC) ) ? $CTC->TrackingCode : false;
	}

	/**
	 * Retourne le PromoSite lié a la plateforme courante
	 * @return \Business\PromoSite
	 */
	public function getPromoSite()
	{
		if( $this->id <= 0 ) return false;

		$Router = \Business\RouterPS::loadByAP( $this->id );
		return ( is_array($Router) && count($Router) > 0 ) ? $Router[0]->PromoSite : false;
	}

	public function addNewManager( $idUser, $type )
	{
		$Actual = $this->getActualManager( $type );
		if( $Actual != false && $Actual->idUser == $idUser )
			return true;
		else if( $Actual != false )
		{
			$Actual->dateEnd	= date( 'Y-m-d H:i:s' );

			if( !$Actual->save() )
				return false;
		}

		$Manager 						= new \Business\Manager();
		$Manager->type					= $type;
		$Manager->idAffiliatePlatform	= $this->id;
		$Manager->idUser				= $idUser;
		$Manager->dateStart				= date( 'Y-m-d H:i:s' );

		return $Manager->save() && $this->getActualManager( $type, true );
	}

	/**
	 * Retourne le manager actuel pour un type donné
	 * @param type $type
	 * @return \Business\Manager
	 */
	public function getActualManager( $type, $forceReload = false )
	{
		if( $forceReload == false && $this->ManagerS !== false && $type === \Business\Manager::TYPE_STRATEGIQUE )
			return $this->ManagerS;
		else if( $forceReload == false && $this->ManagerO !== false && $type === \Business\Manager::TYPE_OPERATIONNEL )
			return $this->ManagerO;

		$list = \Business\Manager::getByPlatformAndType( $this->id, $type );

		if( count($list) > 0 )
		{
			if( $type === \Business\Manager::TYPE_STRATEGIQUE )
				$this->ManagerS = $list[0];
			else if( $type === \Business\Manager::TYPE_OPERATIONNEL )
				$this->ManagerO = $list[0];

			return $list[0];
		}

		return false;
	}

	// *********************** STATIC *********************** //
	static function model($className=__CLASS__)
	{
        return parent::model($className);
    }

	/**
	 *
	 * @param type $id
	 * @return \Business\AffiliatePlatform
	 */
	static public function load( $id )
	{
		return self::model()->findByPk( $id );
	}
}

?>

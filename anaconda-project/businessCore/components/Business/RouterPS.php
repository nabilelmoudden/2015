<?php

namespace Business;

/**
 * Description of RouterPS
 *
 * @author JulienL
 * @package Business.AffiliatePlatform
 */
class RouterPS extends \RouterPS
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
			'PromoSite' => array(self::BELONGS_TO, '\Business\PromoSite', 'idPromoSite'),
			'AffiliatePlatform' => array(self::BELONGS_TO, '\Business\AffiliatePlatform', 'idAffiliatePlatform'),
			'AffiliateCampaign' => array(self::BELONGS_TO, '\Business\AffiliateCampaign', 'idAffiliateCampaign'),
			'AffiliatePlatformSubId' => array(self::BELONGS_TO, '\Business\AffiliatePlatformSubId', 'idAffiliatePlatformSubId'),
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
	 * @return \Business\RouterPS
	 */
	static public function load( $id )
	{
		return self::model()->findByPk( $id );
	}

	/**
	 * Retourne un router en fonction des parametres transmis
	 * @param int $idAffiliatePlatform
	 * @param int $idAffiliateCampaign
	 * @param int $idAffiliatePlatformSubId
	 * @return array[\Business\RouterPS]
	 */
	static public function loadByAP( $idAffiliatePlatform = NULL, $idAffiliateCampaign = NULL, $idAffiliatePlatformSubId = NULL )
	{
		$Router	= new \Business\RouterPS( 'search' );

		if( $idAffiliatePlatform > 0 )
			$Router->idAffiliatePlatform = new \CDbExpression( $idAffiliatePlatform );
		else
			$Router->getDbCriteria()->addCondition( 'idAffiliatePlatform IS NULL' );

		if( $idAffiliateCampaign > 0 )
			$Router->idAffiliateCampaign = new \CDbExpression( $idAffiliateCampaign );
		else
			$Router->getDbCriteria()->addCondition( 'idAffiliateCampaign IS NULL' );

		if( $idAffiliatePlatformSubId > 0 )
			$Router->idAffiliatePlatformSubId = new \CDbExpression( $idAffiliatePlatformSubId );
		else
			$Router->getDbCriteria()->addCondition( 'idAffiliatePlatformSubId IS NULL' );

		$DataProvider = $Router->search();
		if( $DataProvider->getTotalItemCount() > 0 )
			return $DataProvider->getData();

		return false;
	}

	/**
	 * Recherche l'url vers laquel effectué le routage en fonction de priorité sur les parametres transmis
	 * @param int $idAffiliatePlatform
	 * @param int $idAffiliateCampaign
	 * @param int $idAffiliatePlatformSubId
	 * @return array[\Business\PromoSite]
	 */
	static public function searchRouterForAP( $idAffiliatePlatform = NULL, $idAffiliateCampaign = NULL, $idAffiliatePlatformSubId = NULL )
	{
		//Priority 1 :
		$Res = \Business\RouterPS::loadByAP( $idAffiliatePlatform, $idAffiliateCampaign, $idAffiliatePlatformSubId );
		if( !is_array($Res) || count($Res) == 0 )
		{
			//Priority 2 :
			$Res = \Business\RouterPS::loadByAP( $idAffiliatePlatform, NULL, $idAffiliatePlatformSubId );
			if( !is_array($Res) || count($Res) == 0 )
			{
				//Priority 3 :
				$Res = \Business\RouterPS::loadByAP( $idAffiliatePlatform, $idAffiliateCampaign );
				if( !is_array($Res) || count($Res) == 0 )
				{
					//Priority 4 :
					$Res = \Business\RouterPS::loadByAP( NULL, $idAffiliateCampaign );
					if( !is_array($Res) || count($Res) == 0 )
					{
						//Priority 5 :
						$Res = \Business\RouterPS::loadByAP( $idAffiliatePlatform );
						if( !is_array($Res) || count($Res) == 0 )
						{
							//Priority 6 :
							$Res = \Business\RouterPS::loadByAP();
						}
					}
				}
			}
		}

		if( count($Res) == 1 )
			return $Res[0]->PromoSite;
		else
			return ( rand(0,9)%2 ) ? $Res[0]->PromoSite : $Res[1]->PromoSite;
	}
}

?>
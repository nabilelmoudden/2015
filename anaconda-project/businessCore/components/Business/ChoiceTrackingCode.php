<?php

namespace Business;

/**
 * Description of ChoiceTrackingCode
 *
 * @author JulienL
 * @package Business.AffiliatePlatform
 */
class ChoiceTrackingCode extends \ChoiceTrackingCode
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
            'AffiliateCampaign' => array(self::BELONGS_TO, '\Business\AffiliateCampaign', 'idAffiliateCampaign'),
            'AffiliatePlatformSubId' => array(self::BELONGS_TO, '\Business\AffiliatePlatformSubId', 'idAffiliatePlatformSubId'),
            'TrackingCode' => array(self::BELONGS_TO, '\Business\TrackingCode', 'idTrackingCode'),
		);
	}

	/**
	 * Crée les TrackingCodes et les lie au ChoiceTrackingCode courant
	 * @param array $newTrackingCode Tableau contenant les TrackingCode
	 * @return boolean
	 */
	public function addTrackingCode( $newTrackingCode )
	{
		$TC				= new \Business\TrackingCode();
		$TC->attributes	= $newTrackingCode;
		if( !$TC->save() )
			return false;

		$this->idTrackingCode = $TC->id;
		return true;
	}

	// *********************** STATIC *********************** //
	static function model($className=__CLASS__)
	{
        return parent::model($className);
    }

	/**
	 *
	 * @param type $id
	 * @return \Business\ChoiceTrackingCode
	 */
	static public function load( $id )
	{
		return self::model()->findByPk( $id );
	}

	/**
	 * Retourne un ChoiceTrackingCode en fonction des parametres transmis
	 * @param int $idAffiliatePlatform
	 * @param int $idAffiliateCampaign
	 * @param int $idAffiliatePlatformSubId
	 * @return \Business\ChoiceTrackingCode
	 */
	static public function loadByAP( $idAffiliatePlatform = NULL, $idAffiliateCampaign = NULL, $idAffiliatePlatformSubId = NULL )
	{
		$Router	= new \Business\ChoiceTrackingCode( 'search' );

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
		if( $DataProvider->getTotalItemCount() == 1 )
		{
			$Res = $DataProvider->getData();
			return $Res[0];
		}

		return false;
	}

	/**
	 * Recherche le TrackingCode a utiliser en fonction de priorité sur les parametres transmis
	 * @param int $idAffiliatePlatform
	 * @param int $idAffiliateCampaign
	 * @param int $idAffiliatePlatformSubId
	 * @return \Business\TrackingCode TrackingCode
	 */
	static public function searchTrackingCodeForAP( $idAffiliatePlatform = NULL, $idAffiliateCampaign = NULL, $idAffiliatePlatformSubId = NULL )
	{
		//Priority 1 :
		$Res = \Business\ChoiceTrackingCode::loadByAP( $idAffiliatePlatform, $idAffiliateCampaign, $idAffiliatePlatformSubId );
		if( $Res )
			$TC = $Res->TrackingCode;
		else
		{
			//Priority 2 :
			$Res = \Business\ChoiceTrackingCode::loadByAP( $idAffiliatePlatform, NULL, $idAffiliatePlatformSubId );
			if( $Res )
				$TC = $Res->TrackingCode;
			else
			{
				//Priority 3 :
				$Res = \Business\ChoiceTrackingCode::loadByAP( $idAffiliatePlatform, $idAffiliateCampaign );
				if( $Res )
					$TC = $Res->TrackingCode;
				else
				{
					//Priority 4 :
					$Res = \Business\ChoiceTrackingCode::loadByAP( $idAffiliatePlatform );
					if( $Res )
						$TC = $Res->TrackingCode;
					else
						throw new \EsoterException( 902 );
				}
			}
		}

		return $TC;
	}
}

?>
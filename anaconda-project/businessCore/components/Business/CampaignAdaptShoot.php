<?php
namespace Business;
/**
 * This is the model class for table "campaign". 
 *
 * The followings are the available columns in table 'campaign':
 * @property integer $id
 * @property string $label
 * @property integer $type
 * @property string $ref
 *
 * The followings are the available model relations:
 * @property Subcampaign[] $SubCampaign
 *
 * @package Models.Campaign
 */
class CampaignAdaptShoot extends \CampaignAdaptShoot
{
	static public function loadByCampaign( $idCampaignAdapt, $compte = 'Acq' )
	{
		return self::model()->findAllByAttributes( array( 'idCampaignAdapt' => $idCampaignAdapt, 'compte' => $compte  ) );
	}
	
	static public function loadByCampaignAndPopulation( $population, $idCampaignAdapt, $compte = 'Acq' )
	{
		return self::model()->findByAttributes( array( 'population' => $population, 'idCampaignAdapt' => $idCampaignAdapt, 'compte' => $compte  ) );
	}
}

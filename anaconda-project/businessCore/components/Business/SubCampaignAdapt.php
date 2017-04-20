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
class SubCampaignAdapt extends \SubCampaignAdapt
{
	static public function loadAllByCampaign( $idCamp )
	{
		return self::model()->findAllByAttributes( array( 'idCampaignAdapt' => $idCamp ) );
	}
}

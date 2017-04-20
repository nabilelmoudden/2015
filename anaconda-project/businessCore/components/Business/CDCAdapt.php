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
class CDCAdapt extends \CDCAdapt
{
	
	static public function loadByCampaign ( $idCampaign )
	{
		return self::model()->with('CampaignAdapt')->findAllByAttributes( array( 'idCampaign' => $idCampaign ) );
	}
	

	public function getsites( )
	{
		$lessites = '';
		foreach($this->CampaignAdapt as $cp)
				$lessites = $lessites.$cp->site.', ';
	
		return $lessites;
	}
	
	public function getCamp( $site )
	{
		foreach($this->CampaignAdapt as $cp)
			if($cp->site == $site)
				return $cp->SubCampaignAdapt;
	}
	
	
	public function search( $camp = 0 )
	{
		$Provider = parent::search();
		
		$Provider->criteria->with = array( 'CampaignAdapt' );
		
		if($camp > 0)
			$Provider->criteria->addCondition('t.idCampaign = '.$camp);

		return $Provider;
	}
	
	static public function load( $id )
	{
		return self::model()->findByPk( $id );
	}
	
}

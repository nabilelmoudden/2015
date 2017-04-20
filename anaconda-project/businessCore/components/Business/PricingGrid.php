<?php

namespace Business;

/**
 * Description of PricingGrid
 *
 * @author JulienL
 * @package Business.Campaign
 */
class PricingGrid extends \Pricinggrid
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
			'SubCampaign' => array(self::BELONGS_TO, '\Business\SubCampaign', 'idSubCampaign'),
			'Site' => array(self::BELONGS_TO, '\Business\Site', 'idSite'),
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
	 * @return \Business\PricingGrid
	 */
	static public function load( $id )
	{
		return self::model()->findByPk( $id );
	}

	/**
	 *	Retourne le pricing grid
	 * @param int $idProduct
	 * @param int $refBatchSelling
	 * @param int $priceStep
	 * @param int $refPricingGrid
	 * @return \Business\PricingGrid
	 */
	static public function get( $idSubCampaign, $refBatchSelling, $priceStep, $refPricingGrid, $idSite )
	{
		return self::model()->findByAttributes( array(
					'idSubCampaign'		=> $idSubCampaign,
					'refBatchSelling'	=> $refBatchSelling,
					'priceStep'			=> $priceStep,
					'refPricingGrid'	=> $refPricingGrid,
					'idSite'			=> $idSite
				) );
	}
	
	/**
	 *	Retourne le pricing grid
	 * @param int $idSubCampaign
	 * @param int $idSite
	 * @return \Business\PricingGrid
	 */
	static public function getBySubCampaignAndSite( $idSubCampaign, $idSite )
	{
		return self::model()->findAllByAttributes( array(
			'idSubCampaign'		=> $idSubCampaign,
			'idSite'			=> $idSite
		) );
	}
}

?>

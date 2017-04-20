<?php

namespace Business;

/**
 * Description of StandardPriceEngine
 *
 * @author JulienL
 * @package Business.PriceEngine
 */
class StandardPriceEngine extends \Business\PriceEngine
{
	public function __construct( $paramPriceModel, $User, $refBatchSelling, $priceStep, $refPricingGrid, $idSite = false )
	{
		parent::__construct( $paramPriceModel, $User, $refBatchSelling, $priceStep, $refPricingGrid, $idSite );
	}

	/**
	 * Retourne le prix du produit associé a la SubCampaign
	 * @param \Business\SubCampaignReflation $SubCampaignReflation SubCampaignReflation
	 * @return \Business\PricingGrid
	 */
	public function getPrice( $SubCampaignReflation )
	{
		$priceStep	= 1 + $SubCampaignReflation->offsetPriceStep;
		
		//die( $SubCampaignReflation->idSubCampaign.' - '.$this->refBatchSelling.' - '.$priceStep.' - '.$this->refPricingGrid.' - '.$this->idSite );
		
		$PG			= \Business\PricingGrid::get( $SubCampaignReflation->idSubCampaign, $this->refBatchSelling, $priceStep, $this->refPricingGrid, $this->idSite );
		if(is_object($PG) )
			return $PG;
		else
			throw new \EsoterException( 106, \Yii::t( 'error', '106' ) );
	}
}

?>

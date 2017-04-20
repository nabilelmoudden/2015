<?php

namespace Business;

/**
 * Description of FreePriceEngine
 *
 * @author JulienL
 * @package Business.PriceEngine
 */
class FreePriceEngine extends \Business\PriceEngine
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
	public function getPrice( $SubCampaignReflation = NULL )
	{
		$PG						= new \Business\PricingGrid();
		$PG->idSubCampaign		= $SubCampaignReflation->idSubCampaign;
		$PG->idSite				= $this->idSite;
		$PG->refBatchSelling	= $this->refBatchSelling;
		$PG->priceStep			= $this->priceStep;
		$PG->refPricingGrid		= $this->refPricingGrid;
		$PG->priceATI			= 0;
		$PG->priceVAT			= 0;

		return $PG;
	}
}

?>
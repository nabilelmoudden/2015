<?php

namespace Business;

/**
 * Description of AbandonPanierPriceEngine
 *
 * @author Jalal
 * @package Business.PriceEngine
 */
class AbandonPanierPriceEngine extends \Business\PriceEngine
{
	public function __construct( $paramPriceModel, $User, $refBatchSelling, $priceStep, $refPricingGrid, $idSite = false, $ap = false)
	{
		parent::__construct( $paramPriceModel, $User, $refBatchSelling, $priceStep, $refPricingGrid, $idSite );
	}

	/**
	 * Retourne le prix du produit en abandon panier. 
	 * @param \Business\SubCampaignReflation $SubCampaignReflation SubCampaignReflation
	 * @return \Business\PricingGrid
	 */
	public function getPrice( $SubCampaignReflation )
	{
		if( !is_object($this->User) )
			throw new \EsoterException( 106, 'Error AbandonPanierPriceEngine' );

		$searchInvoice	= new \Business\Invoice( 'search' );
		$lastInvoice	= $searchInvoice->searchInvoiceProgressForUserAndProduct( $this->User->email, $SubCampaignReflation->idSubCampaign,'ASC');
			
		if( $lastInvoice->getItemCount() > 0 )
		{
			$lastInvoice	= $lastInvoice->getData();
			$lastInvoice	= end( $lastInvoice );
			
			$searchSubCampaignReflation	= new \Business\SubCampaignReflation( 'search' );
			$SubCmpReflation            = $searchSubCampaignReflation->GetOffsetPriceStep($SubCampaignReflation->idSubCampaign, $lastInvoice->priceStep);
			$SubCmpReflation	        = $SubCmpReflation->getData();
			$lastSubCmpReflation		= end($SubCmpReflation);
			
			$priceStep = 1 + $lastSubCmpReflation->offsetPriceStep;
			
			
			
			//$priceStep	= 1 + $SubCampaignReflation->offsetPriceStep;
			$PG				= \Business\PricingGrid::get( $SubCampaignReflation->idSubCampaign, $lastInvoice->refBatchSelling, $priceStep, $lastInvoice->refPricingGrid, $this->idSite );

			if(is_object($PG) )
				return $PG;
			else
				throw new \EsoterException( 106, \Yii::t( 'error', '106' ) );
		}
		else
		{
			$priceStep	= 1 + $SubCampaignReflation->offsetPriceStep;
			
			$PG			= \Business\PricingGrid::get( $SubCampaignReflation->idSubCampaign, $this->refBatchSelling, $priceStep, $this->refPricingGrid, $this->idSite );

			if(is_object($PG) )
				return $PG;
			else
				throw new \EsoterException( 106, \Yii::t( 'error', '106' ) );
		}
	}
}

?>
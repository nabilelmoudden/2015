<?php

namespace Business;

/**
 * Description of PrevBasedPriceEngine1
 *
 * @author JulienL
 * @package Business.PriceEngine
 */

class PrevBasedPriceEngineAsile2 extends \Business\PriceEngine
{
	public function __construct( $paramPriceModel, $User, $refBatchSelling, $priceStep, $refPricingGrid, $idSite = false )
	{
		parent::__construct( $paramPriceModel, $User, $refBatchSelling, $priceStep, $refPricingGrid, $idSite );
	}

	/**
	 * Retourne le prix du produit associé a la SubCampaign, en fonction du produit precedement vendu contenu dans les parametres du PriceEngine
	 * @param \Business\SubCampaignReflation $SubCampaignReflation SubCampaignReflation
	 * @return \Business\PricingGrid
	 */
	public function getPrice( $SubCampaignReflation )
	{
		
		if( empty($this->params->prevRefProduct) || !is_object($this->User) )
			throw new \EsoterException( 106, 'Error PrevBasedPriceEngine1' );
		
		$searchInvoice	= new \Business\Invoice( 'search' );
		$lastInvoice	= $searchInvoice->searchInvoiceForUserAndProduct( $this->User->email, $this->params->prevRefProduct );
		
		
		if( $lastInvoice->getItemCount() > 0 )
		{
				$SubCampaign  =  \Business\SubCampaign::load($SubCampaignReflation->idSubCampaign);
				$lastInvoice  = $lastInvoice->getData();
				$lastInvoice	= end( $lastInvoice );
				
				$SearchAllInvoice	= new \Business\Invoice( 'search' );
				$AllInvoiceUser	= $SearchAllInvoice->searchAllInvoiceUser( $this->User->email, $lastInvoice->campaign, "asile" );
				$CountInvoice = $AllInvoiceUser->getItemCount();
				$AllInvoice  = $AllInvoiceUser->getData();
				$currentStep = $SubCampaignReflation->offsetPriceStep + 1;
				$steepAsile1    = $AllInvoice[0]->priceStep;
				$steepAsile2    = ($CountInvoice - 1 > 0)?$AllInvoice[1]->priceStep: 0 ;
				$lastStep = $AllInvoice[0]->priceStep ;
				$priceStep	    = $lastInvoice->lastStepInvoice($lastStep, "asile2", $currentStep, $steepAsile1, $steepAsile2);
 
			$PG				= \Business\PricingGrid::get( $SubCampaignReflation->idSubCampaign, $this->refBatchSelling, $priceStep , $this->refPricingGrid, $this->idSite );

			if(is_object($PG) )
				return $PG;
			else
				throw new \EsoterException( 106, \Yii::t( 'error', '106' ) );
		}
		else
		{
			$param = $_GET;
			$url = \Yii::app()->baseUrl.'/index.php/site/DependP?ref='.$param['ref'].'&tr='.$param['tr'].'&gp='.$param['gp'].'&sp='.$param['sp'].'&bs='.$param['bs'].'&m='.$param['m'];
			\Yii::app()->request->redirect( $url );
		}
	}
}

?>
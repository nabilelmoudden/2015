<?php

namespace Business;

/**
 * Description of PricingEngine
 *
 * @author JulienL
 * @package Business.PriceEngine
 */
abstract class PriceEngine
{
	/**
	 * Price Engine
	 */
	const PRICE_ENGINE_FREE			= 'free';
	const PRICE_ENGINE_STANDARD		= 'standard';
	const PRICE_ENGINE_PREV_BASED	= 'prevBased';
	const PRICE_ENGINE_PREV_BASED_ASILE2	= 'prevBasedAsile2';
	const PRICE_ENGINE_PREV_BASED_ASILE3	= 'prevBasedAsile3';
	const PRICE_ENGINE_PREV_BASED_ASILE4	= 'prevBasedAsile4';
	const PRICE_ENGINE_PREV_BASED_ASILE2_ALTHEA	= 'prevBasedAsile2Althea';
	const PRICE_ENGINE_PREV_BASED_ALTHEA	= 'prevBasedAlthea';
	const PRICE_ENGINE_AP			= 'abandonpanier';

	/**
	 * User
	 * @var \Business\User
	 */
	protected $User;
	/**
	 * Tableau de parametre propre au PriceEngine
	 * @var array
	 */
	protected $params			= false;
	/**
	 * $refBatchSelling
	 * @var int
	 */
	protected $refBatchSelling	= false;
	/**
	 * $refPricingGrid
	 * @var int
	 */
	protected $refPricingGrid	= false;
	/**
	 * $priceStep
	 * @var int
	 */
	protected $priceStep		= false;
	/**
	 * Site
	 * @var int	ID Site
	 */
	protected $idSite			= false;

	/**
	 * Constructeur du Price Engine
	 * @param int $refBatchSelling
	 * @param int $priceStep
	 * @param int $refPricingGrid
	 */
	public function __construct( $paramPriceModel, $User, $refBatchSelling, $priceStep, $refPricingGrid, $idSite = false )
	{
		$this->User				= $User;
		$this->params			= $paramPriceModel;
		$this->refBatchSelling	= $refBatchSelling;
		$this->refPricingGrid	= $refPricingGrid;
		$this->priceStep		= $priceStep;
		$this->idSite			= $idSite;
	}

	/**
	 * This method returns the product price depending on the parameters passed and on the database datas.
	 * Example : price can differ with the history purchase ofthe customer
	 * @param \Business\SubCampaignReflation $SubCampaignReflation SubCampaignReflation
	 * @return \Business\PricingGrid
	 */
	abstract public function getPrice( $SubCampaignReflation );

	// *************************** GETTER *************************** //
	public function getRefBatchSelling()
	{
		return $this->refBatchSelling;
	}
	public function getRefPricingGrid()
	{
		return $this->refPricingGrid;
	}
	public function getPriceStep()
	{
		return $this->priceStep; 
	}
	
	/**
	 * @author soufiane balkaid
	 * @param $gp
	 * @desc modifier refPricingGrid du l'objet courrant
	 */
	public function setRefPricingGrid($gp)
	{
		 $this->refPricingGrid=$gp; 
	}

	// *************************** STATIC *************************** //
	/**
	 * Factory : Retourne l'instance du PriceEngince a utiliser
	 * @param string $priceModel Nom du PriceEngine
	 * @param string $paramPriceModel Parametre du PriceEngine
	 * @param \Business\User $User Utilisateur
	 * @param int $refBatchSelling
	 * @param int $priceStep
	 * @param int $refPricingGrid
	 * @return \Business\StandardPriceEngine|\Business\PrevBasedPriceEngine|boolean
	 */
	static public function get( $priceModel, $paramPriceModel, $User, $refBatchSelling, $priceStep, $refPricingGrid, $idSite = false, $ap = false )
	{
		
		if($priceModel == self::PRICE_ENGINE_PREV_BASED){
			$searchInvoice	= new \Business\Invoice( 'search' );
			$lastInvoice	= $searchInvoice->searchInvoiceForUserAndProduct( $User->email, $paramPriceModel->prevRefProduct );
			if( $lastInvoice->getItemCount() > 0 )
			{
				$lastInvoice	= $lastInvoice->getData();
				$lastInvoice	= end( $lastInvoice );
			
				if($priceStep < $lastInvoice->priceStep && $priceStep < 100 && $priceStep > 1000)
					$priceStep		= $lastInvoice->priceStep;
					
			}
			else
			{				
				return new \Business\PrevBasedPriceEngine( $paramPriceModel, $User, $refBatchSelling, $priceStep, $refPricingGrid, $idSite );
			}
		}
		
		if( $ap == 1){
			
			  switch( $priceModel )
			  {
				default :
				case self::PRICE_ENGINE_STANDARD :
					return new \Business\AbandonPanierPriceEngine( $paramPriceModel, $User, $refBatchSelling, $priceStep, $refPricingGrid, $idSite );
					break;
			
				case self::PRICE_ENGINE_PREV_BASED :
					return new \Business\AbandonPanierPriceEngine( $paramPriceModel, $User, $refBatchSelling, $priceStep, $refPricingGrid, $idSite );
					break;
					
				case self::PRICE_ENGINE_PREV_BASED_ASILE2 :
					return new \Business\PrevBasedPriceEngineAsile2( $paramPriceModel, $User, $refBatchSelling, $priceStep, $refPricingGrid, $idSite );
					break;
					
				case self::PRICE_ENGINE_PREV_BASED_ASILE3 :
					return new \Business\PrevBasedPriceEngineAsile3( $paramPriceModel, $User, $refBatchSelling, $priceStep, $refPricingGrid, $idSite );
					break;	
					
				case self::PRICE_ENGINE_PREV_BASED_ASILE4 :
					return new \Business\PrevBasedPriceEngineAsile4( $paramPriceModel, $User, $refBatchSelling, $priceStep, $refPricingGrid, $idSite );
					break;	
					
				case self::PRICE_ENGINE_PREV_BASED_ASILE2_ALTHEA :
					return new \Business\PrevBasedPriceEngineAsile2Althea( $paramPriceModel, $User, $refBatchSelling, $priceStep, $refPricingGrid, $idSite );
					break;

				case self::PRICE_ENGINE_PREV_BASED_ALTHEA :
					return new \Business\PrevBasedPriceEngineAlthea( $paramPriceModel, $User, $refBatchSelling, $priceStep, $refPricingGrid, $idSite );
					break;	
			
			  }
		
		}else{
		
			switch( $priceModel )
			{
				default :
				case self::PRICE_ENGINE_STANDARD :
					return new \Business\StandardPriceEngine( $paramPriceModel, $User, $refBatchSelling, $priceStep, $refPricingGrid, $idSite );
					break;
	
				case self::PRICE_ENGINE_PREV_BASED :
					return new \Business\PrevBasedPriceEngine( $paramPriceModel, $User, $refBatchSelling, $priceStep, $refPricingGrid, $idSite );
					break;
					
				case self::PRICE_ENGINE_PREV_BASED_ASILE2 :
					return new \Business\PrevBasedPriceEngineAsile2( $paramPriceModel, $User, $refBatchSelling, $priceStep, $refPricingGrid, $idSite );
					break;
					
				case self::PRICE_ENGINE_PREV_BASED_ASILE3 :
					return new \Business\PrevBasedPriceEngineAsile3( $paramPriceModel, $User, $refBatchSelling, $priceStep, $refPricingGrid, $idSite );
					break;	
					
				case self::PRICE_ENGINE_PREV_BASED_ASILE4 :
					return new \Business\PrevBasedPriceEngineAsile4( $paramPriceModel, $User, $refBatchSelling, $priceStep, $refPricingGrid, $idSite );
					break;	
	
				case self::PRICE_ENGINE_FREE :
					return new \Business\FreePriceEngine( $paramPriceModel, $User, $refBatchSelling, $priceStep, $refPricingGrid, $idSite );
					break;
					
				case self::PRICE_ENGINE_PREV_BASED_ASILE2_ALTHEA :
					return new \Business\PrevBasedPriceEngineAsile2Althea( $paramPriceModel, $User, $refBatchSelling, $priceStep, $refPricingGrid, $idSite );
					break;

				case self::PRICE_ENGINE_PREV_BASED_ALTHEA :
					return new \Business\PrevBasedPriceEngineAlthea( $paramPriceModel, $User, $refBatchSelling, $priceStep, $refPricingGrid, $idSite );
					break;	
			}
	
			return false;
		
		}
		
		
		
	}
}

?>
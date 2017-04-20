<?php

namespace Business;

/**
 * Description of DefaultPricingGrid
 *
 * @author JulienL
 * @package Business.Campaign
 */
class DefaultPricingGrid extends \DefaultPricingGrid
{

	// *********************** STATIC *********************** //
	static function model($className=__CLASS__)
	{
        return parent::model($className);
    }

	/**
	 *
	 * @param type $id
	 * @return \Business\DefaultPricingGrid
	 */
	static public function load( $nbrProduct )
	{
		return self::model()->findByPk( $id );
	}

	/**
	 *	Return default Pricing Grid
	 * @param int $refBatchSelling
	 * @param int $priceStep
	 * @param int $refPricingGrid
	 * @param int $nbrProduct
	 * @return \Business\DefaultPricingGrid
	 */
	static public function get($refBatchSelling, $priceStep, $refPricingGrid,$nbrProduct)
	{
		return self::model()->findByAttributes( array(
					'refBatchSelling'	=> $refBatchSelling,
					'priceStep'			=> $priceStep,
					'refPricingGrid'	=> $refPricingGrid,
					'nbrProduct'		=> $nbrProduct
				) );
	}

	/**
	 *	Return default pricing grid
	 * @param int $nbrProduct
	 * @return \Business\DefaultPricingGrid
	 */
	static public function loadByNbrProduct( $nbrProduct )
	{
		return self::model()->findAllByAttributes( array(
			'nbrProduct'		=> $nbrProduct
		) );
	}


}

?>

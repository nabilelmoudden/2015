<?php



namespace Business;



/**
 * Description of EcartDelivery
 *
 * @author Yacine RAMI 
 * @package Business.EcartDelivery
 */

class EcartDelivery extends \EcartDelivery

{
	public $date;
	public $ecartCount;
	
	const DELIVERY  = 2;
	/**
	 * Retourne l'Anaconda Setting correspondant
	 * @param integer $id
	 * @return \Business\AnacondaSubdivision
	 */

	static public function load( $id )
	{
		return self::model()->findByPk( $id );
	}
	
	
	/**
	 * @author Yacine RAMI
	 * Load by Id Subcampaign and Step and Date
 	 * @param date de livraison theorique $date
	 */
	static public function loadBySubcampaignAndStepAndDate($idSubCampaign,$step,$date)
	{
		return self::model()->with(array('Ecart' => array( 'condition' => 'creationDate="'.$date.' 00:00:00" AND type='.self::DELIVERY )))->findByAttributes(array( 'idSubCampaign' => $idSubCampaign, 'step' =>  $step));
	}
	
	/**
	 * Load ALL
	 */
	static public function loadAll()
	{
		return self::model()->with('Ecart')->findAll();
	}
	
	/**
	 * @author Yacine RAMI
	 * @return la liste des ecart dont les livraisons réelles sont vides
	 */
	static public function loadNonUpdateDeliveries()
	{
		$criteria = new \CDbCriteria;
		$criteria->condition = 'testDeliveries IS NULL';
		return self::model()->findAll($criteria);
	}
	
	/**
	 * @author Yacine RAMI
	 * @desc cette méthode retourne la liste des ecarts de livraisons par periode
	 * @param date from $dateFrom
	 * @param date to $dateTo
	 * @return list des ecarts EcartDelivery[] 
	 */
	static public function loadByPeriod($dateFrom,$dateTo)
	{	 
		return self::model()->with(array('Ecart' => array( 'condition' => 'creationDate BETWEEN "'.$dateFrom.'" AND "'.$dateTo.'" AND type='.self::DELIVERY )))->findAll();
	}
	
	/**
	 * @author Yacine RAMI
	 * @desc cette méthode retourne la liste des ecarts de livraisons par periode
	 * @param date from $dateFrom
	 * @param date to $dateTo
	 * @return list des ecarts EcartDelivery[]
	 */
	static public function loadEcartDeliveriesByPeriodCampaignProduct($dateFrom,$dateTo,$idCampaign,$position)
	{
		$subCamp= \Business\SubCampaign::loadByCampaignAndPosition($idCampaign,$position);
		
		$list=null;
		if($subCamp)
		{
			$criteriaEcartDelivery = new \CDbCriteria;
			$criteriaEcartDelivery->alias="e";
			$criteriaEcartDelivery->condition="idSubCampaign = ".$subCamp->id;
			
			$list = self::model()->with(array('Ecart' => array('condition' => 'creationDate BETWEEN "'.$dateFrom.'" AND "'.$dateTo.'" AND type='.self::DELIVERY )))
			->findAll($criteriaEcartDelivery);
		}
		
		
		$data=null;
		
		foreach($list as $elt)
		{
			$ecartD = new self();
			$dateDelivs = explode(" ",$elt->Ecart->creationDate )[0];
			$dateDelivs = new \DateTime(date($dateDelivs));
			$ecartD->date = $dateDelivs->format ( 'd/m/Y' );
			$ecartD->id=$elt->id;
			$ecartD->step=$elt->step;
			$ecartD->fidPosition=$elt->fidPosition;
			$ecartD->buyerdJ=$elt->buyerdJ;
			$ecartD->buyerdJ1=$elt->buyerdJ1;
			$ecartD->buyerdJ2=$elt->buyerdJ2;
			$ecartD->delivered=$elt->delivered;
			$ecartD->testDeliveries=$elt->testDeliveries;
			$ecartD->idEcart=$elt->idEcart;
			$ecartD->idSubCampaign=$elt->idSubCampaign;
			$ecartD->ecartCount = $elt->delivered - $elt->testDeliveries - ($elt->buyerdJ+$elt->buyerdJ1+$elt->buyerdJ2) ;
			$data[]=$ecartD;
		}
		
		return json_encode($data,JSON_UNESCAPED_SLASHES);
	}
	
}
<?php

namespace Business;

/**
 * Description of Restriction_paiement
 *
 * @author JulienL
 * @package Business.Campaign
 *
 *
 * The followings are the available model relations:
 * @property \Business\Evtemv[] $EvtEMV
 * @property \Business\Log[] $Log
 * @property \Business\Routeremv[] $RouterEMV
 * @property \Business\Subcampaign $SubCampaign
 * @property \Business\Paymentprocessorset $PaymentProcessorSet
 */
class Restriction_paiement extends \Restriction_paiement
{
	public function init()
	{
		parent::init();

		
		
		

		
		
	}

	/**
	 * Decode les valeurs additionnels apres recuperation en DB
	 * @return boolean
	 */
	protected function loadAdditionnalValues()
	{
		$this->bdcFields		= !empty($this->bdcFields) ? json_decode( $this->bdcFields ) : new \StdClass();
		$this->paramPriceModel	= !empty($this->paramPriceModel) ? json_decode( $this->paramPriceModel ) : new \StdClass();
		return true;
	}

	/**
	 * Encode les valeurs additionnels avant sauvegarde en DB
	 * @return boolean
	 */
	protected function saveAdditionnalValues()
	{
		$this->bdcFields		= !empty($this->bdcFields) ? json_encode( $this->bdcFields ) : NULL;
		$this->paramPriceModel	= !empty($this->paramPriceModel) ? json_encode( $this->paramPriceModel ) : NULL;
		return true;
	}

	

	/**
	 * Recherche
	 * @param string $order Ordre
	 * @param int $pageSize	Nb de result par page
	 * @return CActiveDataProvider	CActiveDataProvider
	 */
	public function search( $order = false, $pageSize = 0 )
	{
		$Provider = parent::search();

		if( $pageSize == false )
			$Provider->setPagination( false );
		else
			$Provider->pagination->pageSize = $pageSize;

		if( $order != false )
			$Provider->criteria->order = $order;

		return $Provider;
	}

	
	/**
	 * Verifie qu'un type de PaymentProcessor est disponible pour le produit
	 * @param string $refPP
	 * @return boolean	true / false
	 */
	public function isPaymentProcessorAvailable( $refPP )
	{
		/*
			
   traitement
		
		*/
	}

	/**
	 * Retourne le PaymentProcessorSet disponible pour le site passé en argument
	 * @param int $idSite
	 * @return array[\Business\PaymentProcessorSet]
	 */
	public function getPaymentProcessorTypeForSite( $idSite )
	{
		$Res = $this->PaymentProcessorSet->PaymentProcessorType( array( 'condition' => '`PaymentProcessorType`.idSite='.$idSite ) );
		if( is_array($Res) && count($Res) > 0 )
			return $Res;
		else
			return false;
	}

	/**
	 * Test si un champs du BDC est disponible pour ce produit
	 * @param string $type
	 * @param string $name
	 * @return boolean
	 */
	public function isBdcFields( $type, $name )
	{
		if( !isset($this->bdcFields->$type) )
			return false;
		if($name == 'Message'){
			$taille = count($this->bdcFields->$type);
			$tabl = $this->bdcFields->$type;
			$contenu = $tabl[$taille-1];
			return (preg_match("#Message:#", $contenu));
		}
		else
			return in_array( $name, $this->bdcFields->$type );
	}

	/**
	 * Retourne un parametre du priceModel
	 * @param string $name
	 * @return mixed
	 */
	public function getParamPriceModel( $name )
	{
		return isset($this->paramPriceModel->$name) ? $this->paramPriceModel->$name : false;
	}
	
	/**
	 *
	 * @param id product
	 * @return les chaamps des dates CT de produit
	 */
	 

	
	/**
	 * Retourne un parametre du PaymentProcessorSet
	 * @param string $name
	 * @return mixed
	 */

	// *********************** STATIC *********************** //
	static function model($className=__CLASS__)
	{
        return parent::model($className);
    }

	/**
	 *
	 * @param type $id
	 * @return \Business\Restriction_paiement
	 */
	static public function load( $id )
	{
		return self::model()->findByPk( $id );
	}

	static public function loadP( $id_product )
	{
		return self::model()->findByProperty( $id_product );
	}


static public function loadpp( $id_product,$type_transaction )
	{
		$id_product = (int) $id_product; 
			
		return self::model()->findAllByAttributes(array('id_product'=>$id_product,'type_transaction'=>$type_transaction));
	}
	

public function date_fin($Restriction_paiement)
{

$date_fin ="10/10/2012";

return $date_fin;
}
	




}

?>

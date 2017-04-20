<?php

namespace Business;

/**
 * Description of Product_V1
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
class Product_V1 extends \Product_V1  //implements Interface_Camp
{
	static public $Version;
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
		
	}

	/**
	 * Encode les valeurs additionnels avant sauvegarde en DB
	 * @return boolean
	 */
	protected function saveAdditionnalValues()
	{

	}

	/**
	 * @return array relational rules.
	 * Surcharge pour que la relation soit sur la classe Business
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'Log' => array(self::HAS_MANY, '\Business\Log', 'idProduct'),
			'RouterEMV' => array(self::HAS_MANY, '\Business\RouterEMV', 'idProduct'),
			'RecordInvoice' => array(self::HAS_MANY, '\Business\Recordinvoice', array( 'Ref' => 'refProduct' ) ),
		);
	}

	/**
	 * Recherche
	 * @param string $order Ordre
	 * @param int $pageSize	Nb de result par page
	 * @return CActiveDataProvider	CActiveDataProvider 
	 */
	public function search( $order = false, $pageSize = 20 )
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
		/*if( empty($this->paymentType) )
			return false;

		$paymentType = explode( ',', $this->paymentType );
		return in_array( $refPP, $paymentType );*/
	}

	/**
	 * Retourne le PaymentProcessorSet disponible pour le site passé en argument
	 * @param int $idSite
	 * @return array[\Business\PaymentProcessorSet]
	 */
	public function getPaymentProcessorTypeForSite( $idSite )
	{

	}

	/**
	 * Test si un champs du BDC est disponible pour ce produit
	 * @param string $type
	 * @param string $name
	 * @return boolean
	 */
	public function isBdcFields( $type, $name )
	{
		
	}

	/**
	 * Retourne un parametre du priceModel
	 * @param string $name
	 * @return mixed
	 */
	public function getParamPriceModel( $name )
	{
		}
	
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
	 * @return \Business\Product_V1
	 */
	static public function load( $id )
	{
		return self::model()->findByPk( $id );
	}

	/**
	 *
	 * @param type $ref
	 * @return \Business\Product_V1
	 */
	static public function loadByRef( $ref ){
		return self::model()->findByAttributes( array( 'Ref' => $ref ) );
	}

	static public function getVersion( ){	
		$Version='V1';
		return $Version;
	}
	
	static public function loadByAP( $id = NULL){
		$Product_V1	= new \Business\Product_V1( 'search' );
		
		
		
		
	
		$DataProvider = $Product_V1->search();
		if( $DataProvider->getTotalItemCount() > 0 )
			return $DataProvider->getData();
		return false;
	}
	
}

?>

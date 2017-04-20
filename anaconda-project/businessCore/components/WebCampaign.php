<?php

/**
 * Description of WebProduct
 *
 * @author JulienL
 */
abstract class WebCampaign
{
	/**
	 * User
	 * @var \Business\User
	 */
	protected $User;
	/**
	 * Product
	 * @var \Business\SubCampaign
	 */
	protected $SubCampaign;

	/**
	 * Constructeur
	 * @param \Business\User $User
	 * @param \Business\Product $Product
	 */
	public function __construct( $SubCampaign, $User )
	{
		$this->SubCampaign	= $SubCampaign;
		$this->User			= $User;
	}

	/**
	 * Methode appelé au moment de l'enregistrement de l'invoice
	 * @param \Business\Invoice	Commande precedement créé
	 */
	abstract function onSaveInvoice( $Invoice );

	/**
	 * Retourne l'User
	 * @return \Business\User
	 */
	public function getUser()
	{
		return $this->User;
	}
	/**
	 *
	 * @return \Business\Campaign
	 */
	public function getCampaign()
	{
		return $this->SubCampaign->Campaign;
	}
	/**
	 *
	 * @return \Business\Product
	 */
	public function getProduct()
	{
		return $this->SubCampaign->Product;
	}
	/**
	 * Retourne le produit precedent le produit actuel dans la campagne
	 * @return \Business\Product|boolean
	 */
	public function getPrevProduct()
	{
		if( $this->SubCampaign->position > 1 )
		{
			$SubCampaign	= \Business\SubCampaign::loadByCampaignAndPosition( $this->SubCampaign->Campaign->id, $this->SubCampaign->position - 1 );
			return $SubCampaign->Product;
		}
		else
			return false;
	}
	/**
	 * Retourne la derniere commande du produit precedent le produit actuel dans la campagne
	 * @return \Business\Invoice|boolean
	 */
	public function getPrevInvoice()
	{
		if( ($Product = $this->getPrevProduct()) )
		{
			$Invoices = \Business\Invoice::loadByEmailAndProduct( $this->getUser()->email, $Product->ref );
			return end($Invoices);
		}
		else
			return false;
	}

	// *************************** FACTORY *************************** //
	/**
	 *
	 * @param \Business\SubCampaign $SubCampaign
	 * @param \Business\Product $Product
	 * @param \Business\User $User
	 * @return \WebProduct|boolean
	 */
	public static function getWebCampaign( $SubCampaign, $User )
	{
		$classUtil	= $SubCampaign->Campaign->ref;
		if( is_file(SERVER_ROOT.\Yii::app()->controller->portDir().'/'.$classUtil.'/'.$classUtil.'.php') )
		{
			include_once( SERVER_ROOT.\Yii::app()->controller->portDir().'/'.$classUtil.'/'.$classUtil.'.php' );
			return new $classUtil( $SubCampaign, $User );
		}

		return false;
	}
}

?>

<?php

namespace Business;

/**
 * Description of RecordInvoiceAnnexe
 *
 * @author JulienL
 * @package Business.Invoice
 */
class RecordInvoiceAnnexe extends \Recordinvoiceannexe
{

	public function init()
	{
		parent::init();

		$this->onAfterFind		= array( $this, 'loadProductExt' );
		$this->onBeforeSave		= array( $this, 'saveProductExt' );
		$this->onAfterSave		= array( $this, 'loadProductExt' );
	}

	/**
	 * Decode les valeurs additionnels apres recuperation en DB
	 * @return boolean
	 */
	protected function loadProductExt()
	{
		$this->productExt = !empty($this->productExt) ? json_decode( $this->productExt ) : new \StdClass();
		return true;
	}

	/**
	 * Encode les valeurs additionnels avant sauvegarde en DB
	 * @return boolean
	 */
	protected function saveProductExt()
	{
		$this->productExt = !empty($this->productExt) ? json_encode( $this->productExt ) : NULL;
		return true;
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
			'RecordInvoice' => array(self::HAS_ONE, '\Business\RecordInvoice', 'id'),
		);
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

	// *********************** STATIC *********************** //
	static function model($className=__CLASS__)
	{
        return parent::model($className);
    }

	/**
	 *
	 * @param type $id
	 * @return \Business\RecordInvoiceAnnexe
	 */
	static public function load( $id )
	{
		return self::model()->findByPk( $id );
	}
}

?>
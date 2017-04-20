<?php

namespace Business;

/**
 * Description of PaymentProcessorSet
 *
 * The followings are the available model relations:
 * @property \Business\Site $Site
 * @property \Business\PaymentProcessorType[] $PaymentProcessorType
 * @property \Business\Product[] $Product
 *
 * @author JulienL
 * @package Business.PaymentProcessor
 */
class PaymentProcessorSet extends \Paymentprocessorset
{
	/**
	 * Recherche
	 * @param string $order Ordre
	 * @param int $pageSize	Nb de result par page
	 * @return CActiveDataProvider	CActiveDataProvider
	 */
	public function search( $order = false, $pageSize = 100 )
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
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'PaymentProcessorType' => array(self::MANY_MANY, '\Business\PaymentProcessorType', $this->tableNamePrefix().'paymentprocessorsetpaymentprocessor(idPaymentProcessorSet, idPaymentProcessorType)', 'order' => 'position ASC' ),
			'Product' => array(self::HAS_MANY, '\Business\Product', 'idPaymentProcessorSet'),
		);
	}

	// *********************** STATIC *********************** //
	static function model($className=__CLASS__)
	{
        return parent::model($className);
    }

	/**
	 *
	 * @param int $id
	 * @return \Business\PaymentProcessorSet
	 */
	static public function load( $id )
	{
		return self::model()->findByPk( $id );
	}
}

?>
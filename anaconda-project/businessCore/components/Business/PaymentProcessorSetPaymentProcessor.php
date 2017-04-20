<?php

namespace Business;

/**
 * Description of PaymentProcessorSetPaymentProcessor
 *
 * The followings are the available columns in table 'Paymentprocessorsetpaymentprocessor':
 * @property integer $idPaymentProcessorSet
 * @property integer $idPaymentProcessorType
 * @property integer $position
 *
 * @author JulienL
 * @package Business.PaymentProcessor
 */
class PaymentProcessorSetPaymentProcessor extends \Paymentprocessorsetpaymentprocessor
{
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'PaymentProcessorType' => array(self::BELONGS_TO, '\Business\PaymentProcessorType', 'idPaymentProcessorType'),
		);
	}

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

		$Provider->criteria->with = 'PaymentProcessorType';

		return $Provider;
	}

	// *********************** STATIC *********************** //
	static function model($className=__CLASS__)
	{
        return parent::model($className);
    }

	/**
	 *
	 * @param int $idSet
	 * @param int $idType
	 * @return \Business\PaymentProcessorSetPaymentProcessor
	 */
	static public function load( $idSet, $idType )
	{
		return self::model()->findByPk( array( 'idPaymentProcessorSet' => $idSet, 'idPaymentProcessorType' => $idType ) );
	}
}

?>
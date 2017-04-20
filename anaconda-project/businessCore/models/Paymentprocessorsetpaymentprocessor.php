<?php

/**
 * This is the model class for table "Paymentprocessorsetpaymentprocessor".
 *
 * The followings are the available columns in table 'Paymentprocessorsetpaymentprocessor':
 * @property integer $idPaymentProcessorSet
 * @property integer $idPaymentProcessorType
 * @property integer $position
 *
 * @package Models.PaymentProcessor
 */
class Paymentprocessorsetpaymentprocessor extends ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Paymentprocessorsetpaymentprocessor the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function rawTableName()
	{
		return 'paymentprocessorsetpaymentprocessor';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('idPaymentProcessorSet, idPaymentProcessorType, position', 'required'),
			array('idPaymentProcessorSet, idPaymentProcessorType, position', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('idPaymentProcessorSet, idPaymentProcessorType, position', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'idPaymentProcessorSet' => 'Id Payment Processor Set',
			'idPaymentProcessorType' => 'Id Payment Processor Type',
			'position' => 'Position',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('idPaymentProcessorSet',$this->idPaymentProcessorSet);
		$criteria->compare('idPaymentProcessorType',$this->idPaymentProcessorType);
		$criteria->compare('position',$this->position);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
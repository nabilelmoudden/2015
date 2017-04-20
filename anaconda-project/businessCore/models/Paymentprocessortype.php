<?php

/**
 * This is the model class for table "paymentprocessortype".
 *
 * The followings are the available columns in table 'paymentprocessortype':
 * @property integer $id
 * @property integer $idSite
 * @property string $name
 * @property integer $type
 * @property string $className
 * @property string $ref
 * @property string $description
 * @property string $param
 *
 * The followings are the available model relations:
 * @property \Business\PaymentProcessorSet[] $PaymentProcessorSet
 *
 * @package Models.PaymentProcessor
 */
class Paymentprocessortype extends ActiveRecord
{
	public  $Nbr ;
	public $code;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Paymentprocessor the static model class
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
		return 'paymentprocessortype';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('idSite, name, type, className, ref, param', 'required'),
			array('type, idSite', 'numerical', 'integerOnly'=>true),
			array('name, className', 'length', 'max'=>50),
			array('ref', 'length', 'max'=>100),
			array('description', 'length', 'max'=>250),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, type, className, ref, description, param', 'safe', 'on'=>'search'),
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
			'Site' => array(self::BELONGS_TO, 'Site', 'idSite'),
			'PaymentProcessorSet' => array(self::MANY_MANY, 'Paymentprocessorset', $this->tableNamePrefix().'paymentprocessorsetpaymentprocessor(idPaymentProcessorType, idPaymentProcessorSet)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'idSite' => 'Id Site',
			'name' => 'Name',
			'type' => 'Type',
			'className' => 'Class Name',
			'ref' => 'Ref',
			'description' => 'Description',
			'param' => 'Param',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('idSite',$this->idSite,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('className',$this->className,true);
		$criteria->compare('ref',$this->ref,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('param',$this->param,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
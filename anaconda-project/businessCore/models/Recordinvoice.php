<?php

/**
 * This is the model class for table "recordinvoice".
 *
 * The followings are the available columns in table 'recordinvoice':
 * @property integer $id
 * @property integer $idInvoice
 * @property string $refProduct
 * @property integer $qty
 * @property string $priceATI
 * @property string $priceVAT
 *
 * The followings are the available model relations:
 * @property Invoice $Invoice
 * @property Recordinvoiceannexe $RecordInvoiceAnnexe
 * @property Product $Product
 *
 * @package Models.Invoice
 */
class Recordinvoice extends ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Recordinvoice the static model class
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
		return 'recordinvoice';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('idInvoice, qty', 'numerical', 'integerOnly'=>true),
			array('refProduct', 'length', 'max'=>20),
			array('priceATI, priceVAT', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, idInvoice, refProduct, qty, priceATI, priceVAT', 'safe', 'on'=>'search'),
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
			'Invoice' => array(self::BELONGS_TO, 'Invoice', 'idInvoice'),
			'RecordInvoiceAnnexe' => array(self::BELONGS_TO, 'Recordinvoiceannexe', 'id'),
			'Product' => array(self::BELONGS_TO, 'Product', array( 'refProduct' => 'ref' ) ),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'idInvoice' => 'Id Invoice',
			'refProduct' => 'Ref Product',
			'qty' => 'Qty',
			'priceATI' => 'Price Ati',
			'priceVAT' => 'Price Vat',
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
		$criteria->compare('idInvoice',$this->idInvoice);
		$criteria->compare('refProduct',$this->refProduct,true);
		$criteria->compare('qty',$this->qty);
		$criteria->compare('priceATI',$this->priceATI,true);
		$criteria->compare('priceVAT',$this->priceVAT,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
<?php

/**
 * This is the model class for table "product".
 *
 * The followings are the available columns in table 'product':
 * @property integer $id
 * @property integer $productType
 * @property string $Ref
 * @property integer $qty
 * @property string $priceATI
 * @property string $priceVAT
 * @property string $amountBif
 * @property int $AbrStat
 * @property string $description
 * @property string $webSiteProductCode
 */
class Product_V1 extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Product the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function TableName()
	{
		return 'product';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('AbrStat', 'length', 'max'=>50),
			array('Ref', 'ext.RefValidator'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ID, WebSiteProductCode, ProductType, Ref, AbrStat', 'safe', 'on'=>'search'),
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
			'Log' => array(self::HAS_MANY, 'Log', 'idProduct'),
			'RouterEMV' => array(self::HAS_MANY, 'Routeremv', 'idProduct'),
			'RecordInvoice' => array(self::HAS_MANY, 'Recordinvoice', array( 'Ref' => 'refProduct' )),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ID' 	=> 'ID',
			'WebSiteProductCode' => 'Label',
			'ProductType'  => 'Type',
			'Ref'	=> 'Reference'
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

		$criteria->compare('ID',$this->ID);
		$criteria->compare('Ref',$this->Ref);
		$criteria->compare('ProductType',$this->ProductType);
		$criteria->compare('WebSiteProductCode',$this->WebSiteProductCode);
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}

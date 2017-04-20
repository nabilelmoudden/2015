<?php

/**
 * This is the model class for table "product".
 *
 * The followings are the available columns in table 'product':
 * @property integer $id
 * @property integer $productType
 * @property string $ref
 * @property integer $qty
 * @property string $priceATI
 * @property string $priceVAT
 * @property string $amountBif
 * @property int $idPaymentProcessorSet
 * @property string $description
 * @property string $webSiteProductCode
 * @property integer $isCT
 * @property string $titleStat
 * @property string $abrStat
 * @property integer $boutique
 * @property string $webSiteProductCodeAC
 * @property string $commercialText
 * @property string $bdcFields
 * @property string $priceModel
 * @property string $paramPriceModel
 * @property integer $ctdate
 * @property string $theoPricePros
 * @property string $theoPriceVg
 * @property string $theoPriceVp
 * @property string $theoPriceCt
 * @property string $asile_type
 *
 * The followings are the available model relations:
 * @property Evtemv[] $EvtEMV
 * @property Log[] $Log
 * @property Routeremv[] $RouterEMV
 * @property Subcampaign $SubCampaign
 * @property Paymentprocessorset $PaymentProcessorSet
 *
 * @package Models.Campaign
 */
class restriction_paiement extends ActiveRecord
{

	var $id ;
	var $id_product ;
	var $date_fin ;
	var $type_transaction ;
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
	public function rawTableName()
	{
		return 'restriction_paiement';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
		/*	array('ref, titleStat, abrStat, priceModel', 'required'),

			array('productType, ctdate, qty, isCT, boutique, idPaymentProcessorSet ', 'numerical', 'integerOnly'=>true),
			array('ref, webSiteProductCodeAC , asile_type', 'length', 'max'=>20),
			array('priceATI, priceVAT, amountBif, theoPricePros, theoPriceVg, theoPriceVp, theoPriceCt, abrStat', 'length', 'max'=>10),
			array('description', 'length', 'max'=>100),
			array('webSiteProductCode, titleStat, priceModel', 'length', 'max'=>50),
			array('ref', 'ext.RefValidator'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, productType, ctdate, ref, qty, priceATI, priceVAT, amountBif, asile_type , idPaymentProcessorSet, description, webSiteProductCode, isCT, titleStat, abrStat, boutique, webSiteProductCodeAC, commercialText, bdcFields, priceModel, paramPriceModel', 'safe', 'on'=>'search'),*/
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
			'Restriction_paiement' => array(self::HAS_MANY, 'Restriction_paiement', 'idProduct')
			
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_product' => 'Id product',
			'type_transaction' => 'Type transaction',
			'date_fin' => 'date fin'
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
		$criteria->compare('id_product',$this->id_product);
		$criteria->compare('type_transaction',$this->type_transaction);
		$criteria->compare('date_fin',$this->date_fin);
	

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria
		));
	}
}
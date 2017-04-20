<?php

/**
 * This is the model class for table "pricinggrid".
 *
 * The followings are the available columns in table 'pricinggrid':
 * @property integer $id
 * @property integer $idSubCampaign
 * @property integer $idSite
 * @property string $refBatchSelling
 * @property integer $priceStep
 * @property string $refPricingGrid
 * @property string $priceATI
 * @property integer $priceVAT
 * @property integer $prixTheorique
 
 *
 * The followings are the available model relations:
 * @property Subcampaign $SubCampaign
 *
 * @package Models.Campaign
 */
class Pricinggrid extends ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Pricinggrid the static model class
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
		return 'pricinggrid';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('idSubCampaign, idSite, refBatchSelling, priceStep, refPricingGrid', 'required'),
			array('idSubCampaign, idSite, priceStep, priceVAT', 'numerical', 'integerOnly'=>true),
			array('refBatchSelling, refPricingGrid', 'length', 'max'=>5),
			array('priceATI', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, idSubCampaign, idSite, refBatchSelling, priceStep, refPricingGrid, priceATI, priceVAT, prixTheorique', 'safe', 'on'=>'search'),
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
			'SubCampaign' => array(self::BELONGS_TO, 'Subcampaign', 'idSubCampaign'),
			'Site' => array(self::BELONGS_TO, 'Site', 'idSite'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'idSubCampaign' => 'Id Sub Campaign',
			'refBatchSelling' => 'Ref Batch Selling',
			'priceStep' => 'Price Step',
			'refPricingGrid' => 'Ref Pricing Grid',
			'priceATI' => 'Price Ati',
			'priceVAT' => 'Price Vat',
			'prixTheorique' => 'prix Theorique'
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
		$criteria->compare('idSubCampaign',$this->idSubCampaign);
		$criteria->compare('refBatchSelling',$this->refBatchSelling,true);
		$criteria->compare('priceStep',$this->priceStep);
		$criteria->compare('refPricingGrid',$this->refPricingGrid,true);
		$criteria->compare('priceATI',$this->priceATI,true);
		$criteria->compare('priceVAT',$this->priceVAT);
		$criteria->compare('prixTheorique',$this->prixTheorique);
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}

<?php

/**
 * This is the model class for table "subcampaign".
 *
 * The followings are the available columns in table 'subcampaign':
 * @property integer $id
 * @property integer $idCampaign
 * @property integer $position
 * @property integer $idProduct
 *
 * The followings are the available model relations:
 * @property Pricinggrid[] $PricingGrid
 * @property Product $Product
 * @property Campaign $Campaign
 * @property Subcampaignreflation[] $SubCampaignReflation
 *
 * @package Models.Campaign
 */
class Subcampaign extends ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Subcampaign the static model class
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
		return 'subcampaign';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('idCampaign, position, idProduct', 'required'),
			array('idCampaign, position, idProduct', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, idCampaign, position, idProduct, prodRef, prodDesc', 'safe', 'on'=>'search'),
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
			'PricingGrid' => array(self::HAS_MANY, 'Pricinggrid', 'idSubCampaign'),
			'AnacondaSegment' => array(self::HAS_MANY, 'Anacondasegment', 'idSubCampaign'),
			'AnacondaTrigger' => array(self::HAS_MANY, 'AnacondaTrigger', 'idSubCampaign'),
			'Product' => array(self::BELONGS_TO, 'Product', 'idProduct'),
			'Campaign' => array(self::BELONGS_TO, 'Campaign', 'idCampaign'),
			'SubCampaignReflation' => array(self::HAS_MANY, 'Subcampaignreflation', 'idSubCampaign'),
			'CampaingHistory' => array(self::HAS_MANY, 'CampaingHistory', 'idSubCampaign'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'idCampaign' => 'Id Campaign',
			'position' => 'Position',
			'idProduct' => 'Id Product',
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
		$criteria->compare('idCampaign',$this->idCampaign);
		$criteria->compare('position',$this->position);
		$criteria->compare('idProduct',$this->idProduct);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}

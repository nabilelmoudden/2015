<?php

/**
 * This is the model class for table "routerPS".
 *
 * The followings are the available columns in table 'routerPS':
 * @property integer $id
 * @property integer $idAffiliatePlatform
 * @property integer $idAffiliateCampaign
 * @property integer $idAffiliatePlatformSubId
 * @property integer $idPromoSite
 *
 * The followings are the available model relations:
 * @property PromoSite $PromoSite
 * @property Affiliateplatform $AffiliatePlatform
 * @property AffiliateCampaign $AffiliateCampaign
 * @property Affiliateplatformsubid $AffiliatePlatformSubId
 *
 * @package Models.AffiliatePlatform
 */
class RouterPS extends ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return RouterPS the static model class
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
		return 'routerPS';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('idPromoSite', 'required'),
			array('idAffiliatePlatform, idAffiliateCampaign, idAffiliatePlatformSubId, idPromoSite', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, idAffiliatePlatform, idAffiliateCampaign, idAffiliatePlatformSubId, idPromoSite', 'safe', 'on'=>'search'),
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
			'PromoSite' => array(self::BELONGS_TO, 'PromoSite', 'idPromoSite'),
			'AffiliatePlatform' => array(self::BELONGS_TO, 'Affiliateplatform', 'idAffiliatePlatform'),
			'AffiliateCampaign' => array(self::BELONGS_TO, 'AffiliateCampaign', 'idAffiliateCampaign'),
			'AffiliatePlatformSubId' => array(self::BELONGS_TO, 'Affiliateplatformsubid', 'idAffiliatePlatformSubId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'idAffiliatePlatform' => 'Id Affiliate Platform',
			'idAffiliateCampaign' => 'Id Affiliate Campaign',
			'idAffiliatePlatformSubId' => 'Id Affiliate Platform Sub',
			'idPromoSite' => 'Id Promo Site',
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

		$criteria->compare('idAffiliatePlatform',$this->idAffiliatePlatform);
		$criteria->compare('idAffiliateCampaign',$this->idAffiliateCampaign);
		$criteria->compare('idAffiliatePlatformSubId',$this->idAffiliatePlatformSubId);
		$criteria->compare('idPromoSite',$this->idPromoSite);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
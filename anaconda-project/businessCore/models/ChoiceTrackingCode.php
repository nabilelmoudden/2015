<?php

/**
 * This is the model class for table "choiceTrackingCode".
 *
 * The followings are the available columns in table 'choiceTrackingCode':
 * @property integer $id
 * @property integer $idAffiliatePlatform
 * @property integer $idAffiliateCampaign
 * @property integer $idAffiliatePlatformSubId
 * @property integer $idTrackingCode
 *
 * The followings are the available model relations:
 * @property Affiliateplatform $AffiliatePlatform
 * @property AffiliateCampaign $AffiliateCampaign
 * @property Affiliateplatformsubid $AffiliatePlatformSubId
 * @property Trackingcode $TrackingCode
 *
 * @package Models.AffiliatePlatform
 */
class ChoiceTrackingCode extends ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ChoiceTrackingCode the static model class
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
		return 'choiceTrackingCode';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('idTrackingCode', 'required'),
			array('idAffiliatePlatform, idAffiliateCampaign, idAffiliatePlatformSubId, idTrackingCode', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, idAffiliatePlatform, idAffiliateCampaign, idAffiliatePlatformSubId, idTrackingCode', 'safe', 'on'=>'search'),
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
            'AffiliatePlatform' => array(self::BELONGS_TO, 'Affiliateplatform', 'idAffiliatePlatform'),
            'AffiliateCampaign' => array(self::BELONGS_TO, 'AffiliateCampaign', 'idAffiliateCampaign'),
            'AffiliatePlatformSubId' => array(self::BELONGS_TO, 'Affiliateplatformsubid', 'idAffiliatePlatformSubId'),
            'TrackingCode' => array(self::BELONGS_TO, 'Trackingcode', 'idTrackingCode'),
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
			'idTrackingCode' => 'Id Tracking Code',
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
		$criteria->compare('idTrackingCode',$this->idTrackingCode);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
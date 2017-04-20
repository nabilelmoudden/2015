<?php

/**
 * This is the model class for table "affiliateplatformsubid".
 *
 * The followings are the available columns in table 'affiliateplatformsubid':
 * @property integer $id
 * @property integer $idAffiliatePlatform
 * @property string $subID
 * @property string $label
 * @property string $description
 * @property boolean $forceEmpty
 *
 * The followings are the available model relations:
 * @property Affiliateplatform $AffiliatePlatform
 * @property ChoiceTrackingCode[] $ChoiceTrackingCode
 * @property RouterPS[] $RouterPS
 *
 * @package Models.AffiliatePlatform
 */
class Affiliateplatformsubid extends ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Affiliateplatformsubid the static model class
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
		return 'affiliateplatformsubid';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('idAffiliatePlatform', 'required' ),
			array('idAffiliatePlatform', 'numerical', 'integerOnly'=>true),
			array('label', 'length', 'max'=>50),
			array('subID', 'ext.RefValidator'),
			array('description', 'length', 'max'=>100),
			array('forceEmpty', 'boolean'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, idAffiliatePlatform, subID, label, description, forceEmpty', 'safe', 'on'=>'search'),
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
			'ChoiceTrackingCode' => array(self::HAS_MANY, 'ChoiceTrackingCode', 'idAffiliatePlatformSubId'),
			'RouterPS' => array(self::HAS_MANY, 'RouterPS', 'idAffiliatePlatformSubId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'idAffiliatePlatform' => 'Id Affiliate Platform',
			'subID' => 'Sub ID',
			'label' => 'Label',
			'description' => 'Description',
			'forceEmpty' => 'Force Empty',
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
		$criteria->compare('idAffiliatePlatform',$this->idAffiliatePlatform);
		$criteria->compare('subID',$this->subID,true);
		$criteria->compare('label',$this->label,true);
		$criteria->compare('description',$this->description,true);
		$criteria->order = 'subID ASC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
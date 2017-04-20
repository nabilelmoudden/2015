<?php

/**
 * This is the model class for table "leadaffiliateplatform".
 *
 * The followings are the available columns in table 'leadaffiliateplatform':
 * @property integer $id
 * @property integer $idInternaute
 * @property integer $idAffiliatePlatform
 * @property string $creationDate
 * @property string $promo
 * @property integer $isDouble
 *
 * The followings are the available model relations:
 * @property User $idInternaute0
 * @property Affiliateplatform $idAffiliatePlatfom0
 *
 * @package Models.AffiliatePlatform
 */
class Leadaff extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Leadaffiliateplatfom the static model class
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
		return 'lead_affiliateplatform';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('idInternaute, idAffiliatePlatform, isDouble', 'numerical', 'integerOnly'=>true),
			array('promo', 'length', 'max'=>50),
			array('creationDate', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, idInternaute, idAffiliatePlatform, creationDate, promo, isDouble', 'safe', 'on'=>'search'),
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
			'User' => array(self::BELONGS_TO, 'User', 'idInternaute'),
			'AffiliatePlatform' => array(self::BELONGS_TO, 'Affiliateplatform', 'idAffiliatePlatform'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'idInternaute' => 'Id User',
			'idAffiliatePlatform' => 'Id Affiliate Platform',
			'creationDate' => 'Creation Date',
			'promo' => 'Promo',
			'isDouble' => 'Is Double',
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
		$criteria->compare('idInternaute',$this->idInternaute);
		$criteria->compare('idAffiliatePlatform',$this->idAffiliatePlatform);
		$criteria->compare('creationDate',$this->creationDate,true);
		$criteria->compare('promo',$this->promo,true);
		$criteria->compare('isDouble',$this->isDouble);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
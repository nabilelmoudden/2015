<?php

/**
 * This is the model class for table "lotcampaign".
 *
 * The followings are the available columns in table 'lotcampaign':
 * @property integer $id
 * @property integer $numLot
 * @property string $creationDate
 *
 * The followings are the available model relations:
 * @property Campaign[] $Campaign
 * @package Models.LotCampaign
 */
class LotCampaign extends ActiveRecord
{
	public $id;
	public $numLot;
	public $creationDate;
	public static $master_db;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return LotCampaign the static model class
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
		return 'lotcampaign';
	}
	
	public function getDbConnection() 
	{
        self::$master_db = Yii::app()->db;
        if (self::$master_db instanceof CDbConnection) {
            self::$master_db->setActive(true);
            return self::$master_db;
        }
        else
		{throw new CDbException(Yii::t('yii', 'Active Record requires a "db" CDbConnection application component.'));}
    }

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('numLot', 'numerical', 'integerOnly'=>true),
			array('creationDate', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('numLot', 'safe', 'on'=>'search'),
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
			'Campaigns' => array(self::HAS_MANY, 'Campaign', 'idLotCampaign'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id Lot',
			'numLot' => 'Numero lot',
			'creationDate' => 'Date de creation' 
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
		$criteria->select = 'id, numLot, creationDate';
		$criteria->compare('id',$this->id,true);
		$criteria->compare('numLot',$this->numLot,true);
		$criteria->compare('creationDate',$this->creationDate,true);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	} 
}
<?php

/**
 * This is the model class for table "manager".
 *
 * The followings are the available columns in table 'manager':
 * @property integer $id
 * @property integer $idAffiliatePlatform
 * @property integer $idUser
 * @property integer $type
 * @property string $dateStart
 * @property string $dateEnd
 *
 * The followings are the available model relations:
 * @property User $User
 * @property Affiliateplatform $AffiliatePlatform
 */
class Manager extends ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Manager the static model class
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
		return 'manager';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('idAffiliatePlatform, idUser, type, dateStart', 'required'),
			array('idAffiliatePlatform, idUser, type', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, idAffiliatePlatform, idUser, type, dateStart, dateEnd', 'safe', 'on'=>'search'),
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
			'User' => array(self::BELONGS_TO, 'User', 'idUser'),
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
			'idAffiliatePlatform' => 'Id Affiliate Platform',
			'idUser' => 'Id User',
			'type' => 'Type',
			'dateStart' => 'Date Start',
			'dateEnd' => 'Date End',
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
		$criteria->compare('idUser',$this->idUser);
		$criteria->compare('type',$this->type);
		$criteria->compare('dateStart',$this->dateStart,true);
		$criteria->compare('dateEnd',$this->dateEnd,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}

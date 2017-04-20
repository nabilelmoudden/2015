<?php

/**
 * This is the model class for table "userrole".
 *
 * The followings are the available columns in table 'userrole':
 * @property integer $idUser
 * @property integer $idRole
 *
 * @package Models.User
 */
class Userrole extends ActiveRecord
{
	public static $master_db;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Userrole the static model class
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
		return 'userrole';
	}
	public function getDbConnection() {
        self::$master_db = Yii::app()->db;
        if (self::$master_db instanceof CDbConnection) {
            self::$master_db->setActive(true);
            return self::$master_db;
        }
        else
            throw new CDbException(Yii::t('yii', 'Active Record requires a "db" CDbConnection application component.'));
    }
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('idUser', 'required'),
			array('idUser, idRole', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('idUser, idRole', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'idUser' => 'Id User',
			'idRole' => 'Id Role',
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

		$criteria->compare('idUser',$this->idUser);
		$criteria->compare('idRole',$this->idRole);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
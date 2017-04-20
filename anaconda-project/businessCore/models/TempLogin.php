<?php

/**
 * This is the model class for table "V2_temp_login".
 *
 * The followings are the available columns in table 'V2_temp_login':
 * @property integer $id
 * @property string $login
 * @property string $password
 * @property string $token
 * @property integer $time
 */
class TempLogin extends ActiveRecord
{
	public static $master_db;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return TempLogin the static model class
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
		return 'temp_login';
	}
	
	public function getDbConnection() {
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
			array('login, password, token, time', 'required'),
			array('time', 'numerical', 'integerOnly'=>true),
			array('login, password, token', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, login, password, token, time', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'login' => 'Login',
			'password' => 'Password',
			'token' => 'Token',
			'time' => 'Time',
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
		$criteria->compare('login',$this->login,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('token',$this->token,true);
		$criteria->compare('time',$this->time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
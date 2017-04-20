<?php

/**
 * This is the model class for table "role".
 *
 * The followings are the available columns in table 'role':
 * @property integer $id
 * @property string $name
 *
 * The followings are the available model relations:
 * @property Authorization[] $Authorization
 * @property User[] $User
 *
 * @package Models.User
 */
class Role extends ActiveRecord
{
	public static $master_db;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Role the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
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
	 * @return string the associated database table name
	 */
	public function rawTableName()
	{
		return 'role';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name', 'safe', 'on'=>'search'),
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
			'Authorization' => array(self::MANY_MANY, 'Authorization', 'roleauthorization(idRole, idAuthorization)'),
			'User' => array(self::MANY_MANY, 'User', 'userrole(idRole, idUser)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
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
		$criteria->compare('name',$this->name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}

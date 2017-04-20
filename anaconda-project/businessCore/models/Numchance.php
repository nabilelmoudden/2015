<?php

/**
 * This is the model class for table "numchance".
 *
 * The followings are the available columns in table 'numchance':
 * @property integer $id_user
 * @property integer $id_product
 * @property string $numChance
 *
 * @package Models.Numchance
 */
class Numchance extends ActiveRecord
{
	public $id_user;
	public $id_product;
	public $numChance;
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
		return 'numchance';
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
			array('id_user, id_product', 'numerical', 'integerOnly'=>true),
			array('numChance', 'length', 'max'=>200),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id_user, id_product, numChance', 'safe', 'on'=>'search'),
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
			'id_user' => 'Id User',
			'id_product' => 'Id Product',
			'numChance' => 'Numero Chance'
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

		$criteria->compare('id_user',$this->id_user);
		$criteria->compare('id_product',$this->id_product);
		$criteria->compare('numChance',$this->numChance);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	} 
}
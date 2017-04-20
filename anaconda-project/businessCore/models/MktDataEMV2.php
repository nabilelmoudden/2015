<?php

/**  
 * This is the model class for table "mkt_data_EMV".
 * @package Models.MktDataEMV
 */
class MktDataEMV2 extends CActiveRecord
{
	
	
	public static $master_db;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Invoice the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	
	/**
	 * @return string the associated database table name
	 */
	public function rawTableName(){
		return 'mkt_data_EMV2';
	}

	/**
	 * @return string the associated database table name
	 */
	public function TableName(){
		return 'mkt_data_EMV2';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array();
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array();
	}
	
    public function getDbConnection() {
        self::$master_db = Yii::app()->db;
        if (self::$master_db instanceof CDbConnection) {
            self::$master_db->setActive(true);
            return self::$master_db;
        }
        else
		{throw new CDbException(Yii::t('yii', 'Active Record requires a "db" CDbConnection application component.'));}
    }/**/
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array();
	}
}
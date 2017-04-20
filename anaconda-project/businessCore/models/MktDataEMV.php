<?php

/**  
 * This is the model class for table "mkt_data_EMV".
 * @package Models.MktDataEMV
 */
class MktDataEMV extends CActiveRecord
{
	
	
	
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
		return 'mkt_data_EMV';
	}

	/**
	 * @return string the associated database table name
	 */
	public function TableName(){
		return 'mkt_data_EMV';
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

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array();
	}
}
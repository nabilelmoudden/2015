<?php

/**
 * This is the model class for table "roleauthorization".
 *
 * The followings are the available columns in table 'roleauthorization':
 * @property integer $idRole
 * @property integer $idAuthorization
 *
 * @package Models.User
 */
class Roleauthorization extends ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Roleauthorization the static model class
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
		return 'roleauthorization';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('idRole, idAuthorization', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('idRole, idAuthorization', 'safe', 'on'=>'search'),
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
			'idRole' => 'Id Role',
			'idAuthorization' => 'Id Authorization',
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

		$criteria->compare('idRole',$this->idRole);
		$criteria->compare('idAuthorization',$this->idAuthorization);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
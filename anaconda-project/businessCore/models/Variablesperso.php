<?php

class Variablesperso extends ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Variablesperso the static model class
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
		return 'variablesperso';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nom, valueM, valueF', 'required'),
			array('nom, valueM, valueF', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, nom, valueM, valueF', 'safe', 'on'=>'search'),
			array('nom', 'unique', 'message'=>'cette variable personnalisée existe déjà, veuillez choisir un autre nom svp!'),
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
			'nom' => 'Nom',
			'valueM' => 'Value M',
			'valueF' => 'Value F',
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
		$criteria->compare('nom',$this->nom,true);
		$criteria->compare('valueM',$this->valueM,true);
		$criteria->compare('valueF',$this->valueF,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
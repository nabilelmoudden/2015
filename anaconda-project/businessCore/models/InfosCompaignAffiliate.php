<?php

/**
 * This is the model class for table "infos_compaign_affiliate".
 *
 */
class InfosCompaignAffiliate extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Internaute the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'infos_compaign_affiliate';
	}


	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
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
		return array(
			'Id' => 'ID',
			'idAffiliatePlatform' => 'idAffiliatePlatform',
			'subID' => 'subID',
			'date_jour' => 'date_jour',
			'Click' => 'Click',
			'Achattotal'=>'Achattotal',
			'Achat' => 'Achat',
			'total' => 'total',
			'porteur'=> 'porteur',
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

		$criteria->compare('Id',$this->Id,true);
		$criteria->compare('subID',$this->subID,true);
		$criteria->compare('date_jour',$this->date_jour,true);
		$criteria->compare('Click',$this->Click,true);
		$criteria->compare('Achat',$this->Achat,true);
		$criteria->compare('total',$this->total,true);
		$criteria->compare('porteur',$this->porteur,true);
		$criteria->compare('Achattotal',$this->Achattotal,true);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
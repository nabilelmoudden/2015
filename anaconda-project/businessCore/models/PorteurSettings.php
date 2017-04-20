<?php

/**
 * This is the model class for table "porteursettings".
 *
 * The followings are the available columns in table 'porteursettings':
 * @property integer $id
 * @property integer $display
 * @property integer $idFirstCampaign
 *
 * @package Models.PorteurSettings
 */
class PorteurSettings extends ActiveRecord
{
	public $id;
	public $display;
	public $idFirstCampaign;
	public $periodAnaconda;
	public $countImport;

	public static $master_db;


	public function getDbConnection()
	{
		self::$master_db = Yii::app()->db;
		if (self::$master_db instanceof CDbConnection) {
			self::$master_db->setActive(true);
			return self::$master_db;
		}
		else
		{throw new CDbException(Yii::t('yii', 'Active Record requires a "db" CDbConnection application component.'));}
	}
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AnacondaSettings the static model class
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
		return 'porteurSettings';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('display', 'numerical', 'integerOnly'=>true),
		);
	}



	/**
	 * @return array customized attribute labels (name=>label)
	 */

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'display' => 'permission à la modification du graph anaconda',
			'idFirstCampaign' => 'First Campaign ID',
			'periodAnaconda' => 'duree d entree au processus anaconda',
			'countImport' => 'compteur des imports erones'
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search(){
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;
		$criteria->select = 'id, display, idFirstCampaign, periodAnaconda';
		$criteria->compare('id',$this->id);
		$criteria->compare('display',$this->display,true);
		$criteria->compare('idFirstCampaign',$this->idFirstCampaign,true);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));;
	}
}
<?php

/**
 * This is the model class for table "V2_ispcompaign".
 *
 * The followings are the available columns in table 'V2_ispcompaign':
 * @property integer $id
 * @property integer $idcompaign_sf
 * @property string $triggername
 * @property integer $idmessage
 * @property string $messagename
 * @property string $senddate
 * @property string $porteur
 * @property string $site
 */
class IspCompaign extends ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return IspCompaign the static model class
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
		return 'ispcompaign';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('idcompaign_sf, triggername, idmessage, messagename, senddate, porteur, site', 'required'),
			array('idcompaign_sf, idmessage', 'numerical', 'integerOnly'=>true),
			array('triggername', 'length', 'max'=>100),
			array('messagename', 'length', 'max'=>50),
			array('porteur', 'length', 'max'=>20),
			array('site', 'length', 'max'=>2),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, idcompaign_sf, triggername, idmessage, messagename, senddate, porteur, site', 'safe', 'on'=>'search'),
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
			'IspReport' => array(self::HAS_MANY, 'IspReport', 'idispcompaign'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'idcompaign_sf' => 'Idcompaign Sf',
			'triggername' => 'Triggername',
			'idmessage' => 'Idmessage',
			'messagename' => 'Messagename',
			'senddate' => 'Senddate',
			'porteur' => 'Porteur',
			'site' => 'Site',
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
		$criteria->compare('idcompaign_sf',$this->idcompaign_sf);
		$criteria->compare('triggername',$this->triggername,true);
		$criteria->compare('idmessage',$this->idmessage);
		$criteria->compare('messagename',$this->messagename,true);
		$criteria->compare('senddate',$this->senddate,true);
		$criteria->compare('porteur',$this->porteur,true);
		$criteria->compare('site',$this->site,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
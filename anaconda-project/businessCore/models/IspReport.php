<?php

/**
 * This is the model class for table "V2_ispreport".
 *
 * The followings are the available columns in table 'V2_ispreport':
 * @property integer $id
 * @property integer $idispcompaign
 * @property integer $idreport_sf
 * @property string $namereport
 * @property string $creationdate
 * @property string $personne
 * @property string $chemin
 * @property integer $isdownloaded
 */
class IspReport extends ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return IspReport the static model class
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
		return 'ispreport';
	}

	/**
	 * @return array validation rules for model attributes.
	 */


	/**
	 * @return array relational rules.
	 */

	
		public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('idispcompaign, idreport_sf, namereport, creationdate, personne, chemin, isdownloaded', 'required','message'=>"tttttt"),
			array('idispcompaign, idreport_sf, isdownloaded', 'numerical', 'integerOnly'=>true),
			array('namereport', 'length', 'max'=>100),
			array('personne', 'length', 'max'=>25),
			array('chemin', 'length', 'max'=>200),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, idispcompaign, idreport_sf, namereport, creationdate, personne, chemin, isdownloaded,porteur,site,triggername,messagename,senddate', 'safe', 'on'=>'search1'),
			array('id, idispcompaign, idreport_sf, namereport, creationdate, personne, chemin, isdownloaded', 'safe', 'on'=>'search'),
		);
	}
	


		public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'IspCompaign' => array(self::BELONGS_TO, 'IspCompaign','', 'foreignKey' => array('idispcompaign'=>'id')),
		);
	}


	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'idispcompaign' => 'Idispcompaign',
			'idreport_sf' => 'Idreport Sf',
			'namereport' => 'Namereport',
			'creationdate' => 'Creationdate',
			'personne' => 'Personne',
			'chemin' => 'Chemin',
			'isdownloaded' => 'Isdownloaded',
			
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
		$criteria->compare('idispcompaign',$this->idispcompaign);
		$criteria->compare('idreport_sf',$this->idreport_sf);
		$criteria->compare('namereport',$this->namereport,true);
		$criteria->compare('creationdate',$this->creationdate,true);
		$criteria->compare('personne',$this->personne,true);
		$criteria->compare('chemin',$this->chemin,true);
		$criteria->compare('isdownloaded',$this->isdownloaded);
        
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
<?php

/**
 * @author AL.
 * This is the model class for table "alert".
 *
 * The followings are the available columns in table 'alert':
 * @property integer $id
 * @property string $creationDate
 * @property integer $statut
 * @property integer $idSubCampaign
 * @property integer $idEcart
 *
 *
 * @package Models.Alert
 */
class MediaAlert extends ActiveRecord
{
    public $id;
    public $name;
    public $extention;
    public $url;
    public $idComment;


	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Campaign the static model class
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
		return 'mediaAlert';
	}

	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		array('name', 'file', 'types'=>'jpg, gif, png,pdf,docx', 'safe' => false);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			
			'idComment' => array(self::BELONGS_TO, 'commentAlert', 'id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
            'name' => 'Nom',
            'url' => 'URL',
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
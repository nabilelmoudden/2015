<?php 

/**
 * This is the model class for table "user_porteur".
 *
 * The followings are the available columns in table 'user_porteur':
 * @property integer $id
 * @property integer $user
 * @property integer $porteur
 *
 * @package Models.UserPorteur
 */
class UserPorteur extends ActiveRecord
{
	public $id;
	public $user;
	public $porteur;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
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
		return 'user_porteur';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(

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
		$criteria->select = 'id, user, porteur, "V2" AS version';
		$criteria->compare('id',$this->id);
		$criteria->compare('user',$this->user,false);
		$criteria->compare('porteur',$this->porteur,true);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
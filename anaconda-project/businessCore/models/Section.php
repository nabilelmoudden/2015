<?php
/**  
 * This is the model class for table "invoice".
 *
 *
 * The followings are the available model relations:
 * @property User_V1 $User_V1
 *
 * @package Models.Invoice
 */

class Section extends CActiveRecord{
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Invoice the static model class
	 */
	 
	public static function model($className=__CLASS__){
		return parent::model($className);
	}



	/**
	 * @return string the associated database table name
	 */
	public function rawTableName(){
		return 'section';
	}

	/**
	 * @return string the associated database table name
	 */
	public function TableName(){
		return 'section';
	}

	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_section, section', 'safe', 'on'=>'search'),
		);
	}



	/**
	 * @return array relational rules.
	 */
	public function relations(){
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'Question' => array(self::HAS_MANY, 'Question', array( 'id_section' => 'id_sec_FK' ) ),
		);
	}



	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels(){
		return array();
	}
	
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search(){
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.
		$criteria = new CDbCriteria;
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

}
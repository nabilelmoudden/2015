<?php
/**
 * This is the model class for table "router".
 *
 * The followings are the available columns in table 'router':
 * @property integer $ID
 * @property string $ProductRef
 * @property string $CompteEMV
 * @property string $Type
 * @property string $Url
 * @property string $updateTS
 */
class Router_V1 extends CActiveRecord{
	
	public $ID;
	public $ProductRef;
	public $CompteEMV;
	public $Type;
	public $Url;
	public $updateTS;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Router_V1 the static model class
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
		return 'router';
	}
	
	/**
	 * @return string the associated database table name
	 */
	public function rawTableName(){
		return 'router';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ProductRef, CompteEMV, Type, Url, updateTS', 'required'),
			array('ProductRef', 'length', 'max'=>50),
			array('CompteEMV, Type', 'length', 'max'=>24),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ID, ProductRef, CompteEMV, Type, Url, updateTS', 'safe', 'on'=>'search'),
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
			'ID' => 'ID',
			'ProductRef' => 'Product Ref',
			'CompteEMV' => 'Compte Emv',
			'Type' => 'Type',
			'Url' => 'Url',
			'updateTS' => 'Update Ts',
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

		$criteria->compare('ID',$this->ID);
		$criteria->compare('ProductRef',$this->ProductRef,true);
		$criteria->compare('CompteEMV',$this->CompteEMV,true);
		$criteria->compare('Type',$this->Type,true);
		$criteria->compare('Url',$this->Url,true);
		$criteria->compare('updateTS',$this->updateTS,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	
	/**
	 * @param int $id
	 */
	 
	public static function loadByRef($RefProduct, $type){
		
		$criteria = new CDbCriteria();
		$criteria->compare('ProductRef','='. $RefProduct);
		$criteria->compare('Type', '='. $type);
		
		return self::model()->findAll($criteria);
	}
}
<?php

/**
 * This is the model class for table "recordinvoiceannexe".
 *
 * The followings are the available columns in table 'recordinvoiceannexe':
 * @property integer $idPoste
 * @property string $productExt
 *
 * The followings are the available model relations:
 * @property Recordinvoice $recordinvoice
 *
 * @package Models.Invoice
 */
class Recordinvoiceannexe extends ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Recordinvoiceannexe the static model class
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
		return 'recordinvoiceannexe';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('idPoste', 'required'),
			array('idPoste', 'numerical', 'integerOnly'=>true),
			array('productExt', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('idPoste, productExt', 'safe', 'on'=>'search'),
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
			'RecordInvoice' => array(self::HAS_ONE, 'Recordinvoice', 'id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'idPoste' => 'Id Poste',
			'productExt' => 'Product Ext',
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

		$criteria->compare('idPoste',$this->idPoste);
		$criteria->compare('productExt',$this->productExt,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
<?php

/**
 * This is the model class for table "abandonedcaddy".
 *
 * The followings are the available columns in table 'abandonedcaddy':
 * @property integer $id
 * @property integer $type
 * @property integer $idInvoice
 * @property integer $idAdmin
 * @property string $creationDate
 * @property string $alertDate
 * @property integer $status
 * @property string $journal
 *
 * The followings are the available model relations:
 * @property User $idAdmin0
 * @property Invoice $idInvoice0
 * @property Invoice[] $invoices
 *
 * @package Models.Invoice
 */
class Abandonedcaddy extends ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Abandonedcaddy the static model class
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
		return 'abandonedcaddy';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('idInvoice, idAdmin', 'required'),
			array('type, idInvoice, idAdmin, status', 'numerical', 'integerOnly'=>true),
			array('creationDate, alertDate, journal', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, type, idInvoice, idAdmin, creationDate, alertDate, status, journal', 'safe', 'on'=>'search'),
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
			'User' => array(self::BELONGS_TO, 'User', 'idAdmin'),
			'Invoice' => array(self::BELONGS_TO, 'Invoice', 'idInvoice'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'type' => 'Type',
			'idInvoice' => 'Id Invoice',
			'idAdmin' => 'Id Admin',
			'creationDate' => 'Creation Date',
			'alertDate' => 'Alert Date',
			'status' => 'Status',
			'journal' => 'Journal',
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
		$criteria->compare('type',$this->type);
		$criteria->compare('idInvoice',$this->idInvoice);
		$criteria->compare('idAdmin',$this->idAdmin);
		$criteria->compare('creationDate',$this->creationDate,true);
		$criteria->compare('alertDate',$this->alertDate,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('journal',$this->journal,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
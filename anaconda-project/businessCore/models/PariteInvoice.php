<?php
/**
 * This is the model class for table "parite_invoice".
 *
 * The followings are the available columns in table 'parite_invoice':
 * @property integer $id_parite
 * @property string $parite
 * @property string $devise
 * @property string $updateDate
 */
class PariteInvoice extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return PariteInvoice the static model class
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
		return 'parite_invoice';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('updateDate', 'required'),
			array('parite', 'length', 'max'=>13),
			array('devise', 'length', 'max'=>3),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id_parite, parite, devise, updateDate', 'safe', 'on'=>'search'),
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
			'id_parite' => 'Id Parite',
			'parite' => 'Parite',
			'devise' => 'Devise',
			'updateDate' => 'Update Date',
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

		$criteria->compare('id_parite',$this->id_parite);
		$criteria->compare('parite',$this->parite,true);
		$criteria->compare('devise',$this->devise,true);
		$criteria->compare('updateDate',$this->updateDate,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
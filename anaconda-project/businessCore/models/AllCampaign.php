<?php

class AllCampaign extends ActiveRecord
{
	

	/**
	 * @return string the associated database table name
	 */
	public function rawTableName()
	{
		return 'campaign';
	}
	public $porteur;
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('type', 'numerical', 'integerOnly'=>true),
			array('label', 'length', 'max'=>50),
			array( 'ref', 'ext.RefValidator' ),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, label, type, ref', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'label' => 'Label',
			'type' => 'Type',
			'ref' => 'Reference'
		);
	}

	public function getProductsFROMV1AndV2(){
		$criteria = new CDbCriteria();
		$criteria->select = ' id, type, ref, label, "V2" as version  ';
		$criteria->mergeWith(array('join'=>' UNION  SELECT ID as id, ProductType as type, Ref as ref, WebSiteProductCode as label, "V1" as version FROM product '));
		
		
		return new CActiveDataProvider($this, array(
			'criteria' => ($criteria),
		));
		
		
		
		
			
				
					
				
	}
	
	// ************************** SETTER ************************** //
	public function setPorteur( $porteur )
	{
		$this->porteur = $porteur;
		return \Controller::loadConfigForPorteur( $porteur );
	}

	
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.
		$criteria = new CDbCriteria();
		$criteria->select = ' id, type, ref, label, "V2" as version  ';
		$criteria->join = ' UNION  SELECT ID as id, ProductType as type, Ref as ref, WebSiteProductCode as label, "V1" as version FROM product ';
		print_r($criteria);exit;

		$criteria->compare('id',$this->id);
		$criteria->compare('label',$this->label,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('ref',$this->ref);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}

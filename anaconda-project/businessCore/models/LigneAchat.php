<?php

/**
 * This is the model class for table "campaign".
 *
 * The followings are the available columns in table 'campaign':
 * @property integer $id
 * @property string $label
 * @property integer $type
 *
 * The followings are the available model relations:
 * @property Subcampaign[] $SubCampaign
 *
 * @package Models.Campaign
 */
class LigneAchat extends ActiveRecord
{
	
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
		return 'ligne_achat';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('article, demandeur, motif, code, quantite, reference', 'required', 'message'=>'Ce champ est obligatoire.'),
			//array('dateInvoice', 'date', 'format'=>'yyyy-M-d', 'message'=>'Format date: YYYY-MM-DD'),
			array('id, idAchat, article, demandeur, motif, reference, code, quantite, fournisseur', 'safe', 'on'=>'search'),
		);
	}
 
	/** 
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
				'Achat' => array(self::BELONGS_TO, 'Achat', 'idAchat')
		);
	}
	
	/** 
	 * @return array relational rules.
	 */
	public function getSubLignes()
	{
		
		return unserialize($this->subLignes);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'article' => 'Article',
			'demandeur' => 'Demandeur',
			'code' => 'Code',
			'motif' => 'Motif pour achat',
			'fournisseur' => 'Fournisseur',
			'reference' => 'Reference'
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria=new CDbCriteria;
		
		
				
		$criteria->compare('id',$this->id);
		$criteria->compare('article',$this->article, true);
		$criteria->compare('demandeur',$this->demandeur, true);
		$criteria->compare('code',$this->code, true);
		$criteria->compare('motif',$this->motif, true);
		$criteria->compare('fournisseur',$this->fournisseur, true);
		$criteria->compare('reference',$this->reference, true);
		$criteria->compare('idAchat',$this->idAchat, true);
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function searchByAchat($id)
	{
		$criteria=new CDbCriteria;
		$criteria->with = array( 'Achat');
		$criteria->addCondition(" idAchat = ".$id);
		
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}

<?php



class TemplateInvoice extends ActiveRecord{
	
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Role the static model class
	 */
	
	/**
	 * @return string the associated database table name
	 */
	public function rawTableName(){
		return 'TemplateInvoice';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{     
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('IdTemplate', 'IdInvoice', 'safe', 'on'=>'search'),
		);
	}



	/**
	 * @return array relational rules.
	 */
	public function relations(){
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		   return array(
            'Template'=>array(self::BELONGS_TO, 'Template', 'IdTemplate'),
        );
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels(){
		return array(
			'IdTemplate' => 'IdTemplate',
			'IdInvoice' => 'IdInvoice'
		);
	}


	public function findbyIdInvoice( $id ,$categorie=null)
	{
		$criteria = new CDbCriteria();
		if($categorie!=null){
		$criteria->compare('Title', $categorie);	
		}
		$criteria->compare('IdInvoice', ' = '. $id);
		$criteria->join=' JOIN V2_TemplateInvoice ON t.Id=V2_TemplateInvoice.IdTemplate';
		return new CActiveDataProvider('Template', array(
			'criteria'=>$criteria,
		));
	}
		public function CreateListeTemplate( $id )
	{
			//**  selection de l'introduction **//
			$Template = new Template('Getliste');
			$Introductions = $Template->Getliste('Introduction')->getData();
			$cpt=0;
			
			foreach($Introductions as $Introduction){
				Yii::app()->db->createCommand()->insert('V2_TemplateInvoice', array('IdInvoice' => $id,'IdTemplate' =>$Introduction->Id));
				$cpt++;
				if($cpt==1) {break; }
			}
			//**  Fin de l'introduction **//
		
			//** selection du texte d'admour **//
			$cpt=0;
			$amours = $Template->Getliste('amour')->getData();
			foreach($amours as $amour){
				Yii::app()->db->createCommand()->insert('V2_TemplateInvoice', array('IdInvoice' => $id,'IdTemplate' =>$amour->Id));
				$cpt++;
				if($cpt==10){ break;}
			}
			//** Fin du texte d'admour **//
			
			//** selection du texte CGV **//
			$cpt=0;
			$amours = $Template->Getliste('CGV')->getData();
			foreach($amours as $amour){
				Yii::app()->db->createCommand()->insert('V2_TemplateInvoice', array('IdInvoice' => $id,'IdTemplate' =>$amour->Id));
				$cpt++;
				if($cpt==10) {break;}
			}
			//** Fin du texte CGV **//
			
			//** selection du texte CGV **//
			$cpt=0;
			$amours = $Template->Getliste('VRS')->getData();
			foreach($amours as $amour){
				Yii::app()->db->createCommand()->insert('V2_TemplateInvoice', array('IdInvoice' => $id,'IdTemplate' =>$amour->Id));
				$cpt++;
				if($cpt==10) {break;}
			}
			//** Fin du texte VRS **//
			
			//** selection du texte conclusion **//
			$cpt=0;
			$amours = $Template->Getliste('conclusion')->getData();
			foreach($amours as $amour){
				Yii::app()->db->createCommand()->insert('V2_TemplateInvoice', array('IdInvoice' => $id,'IdTemplate' =>$amour->Id));
				$cpt++;
				if($cpt==1) {break;}
			}
			//** Fin du texte conclusion **//
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
		$criteria->compare('IdTemplate',$this->IdTemplate);
		$criteria->compare('IdInvoice',$this->IdInvoice);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}


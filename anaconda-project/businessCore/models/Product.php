<?php

/**
 * This is the model class for table "product".
 *
 * The followings are the available columns in table 'product':
 * @property integer $id
 * @property integer $productType
 * @property string $ref
 * @property integer $qty
 * @property string $priceATI
 * @property string $priceVAT
 * @property string $amountBif
 * @property int $idPaymentProcessorSet
 * @property string $description
 * @property string $webSiteProductCode
 * @property integer $isCT
 * @property string $titleStat
 * @property string $abrStat
 * @property integer $boutique
 * @property string $webSiteProductCodeAC
 * @property string $commercialText
 * @property string $bdcFields
 * @property string $priceModel
 * @property string $paramPriceModel
 * @property integer $ctdate
 * @property string $theoPricePros
 * @property string $theoPriceVg
 * @property string $theoPriceVp
 * @property string $theoPriceCt
 * @property string $asile_type
 *
 * The followings are the available model relations:
 * @property Evtemv[] $EvtEMV
 * @property Log[] $Log
 * @property Routeremv[] $RouterEMV
 * @property Subcampaign $SubCampaign
 * @property Paymentprocessorset $PaymentProcessorSet
 *
 * @package Models.Campaign
 */
class Product extends ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Product the static model class
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
		return 'product';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ref, titleStat, abrStat, priceModel', 'required'),

			array('productType, ctdate, qty, isCT, boutique, idPaymentProcessorSet', 'numerical', 'integerOnly'=>true),
			array('ref, webSiteProductCodeAC, asile_type', 'length', 'max'=>20),
			array('priceATI, priceVAT, amountBif, theoPricePros, theoPriceVg, theoPriceVp, theoPriceCt, abrStat', 'length', 'max'=>10),
			array('description', 'length', 'max'=>100),
			array('webSiteProductCode, titleStat, priceModel', 'length', 'max'=>50),
			array('ref', 'unique', 'message'=>'Référence existe déjà, veuillez choisir une autre référence svp!'),
			array('ref', 'ext.RefValidator'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, productType, ctdate, ref, qty, priceATI, priceVAT, amountBif, idPaymentProcessorSet, description, webSiteProductCode, isCT, titleStat, abrStat, boutique, webSiteProductCodeAC, commercialText, bdcFields, priceModel, paramPriceModel', 'safe', 'on'=>'search'),
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
			'Log' => array(self::HAS_MANY, 'Log', 'idProduct'),
			'RouterEMV' => array(self::HAS_MANY, 'Routeremv', 'idProduct'),
			'SubCampaign' => array(self::HAS_ONE, 'Subcampaign', 'idProduct'),
			'PaymentProcessorSet' => array(self::BELONGS_TO, 'Paymentprocessorset', 'idPaymentProcessorSet'),
			'RecordInvoice' => array(self::HAS_MANY, 'Recordinvoice', array( 'ref' => 'refProduct' )),
			'Restriction_paiement' => array(self::HAS_MANY, 'Restriction_paiement', 'idProduct'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'productType' => 'Product Type',
			'ref' => 'Ref',
			'qty' => 'Qty',
			'priceATI' => 'Price Ati',
			'priceVAT' => 'Price Vat',
			'amountBif' => 'Amount Bif',
			'idPaymentProcessorSet' => 'idPaymentProcessorSet',
			'description' => 'Description',
			'webSiteProductCode' => 'Web Site Product Code',
			'isCT' => 'Is Ct',
			'titleStat' => 'Title Stat',
			'abrStat' => 'Abr Stat',
			'boutique' => 'Boutique',
			'webSiteProductCodeAC' => 'Web Site Product Code Ac',
			'commercialText' => 'Commercial Text',
			'bdcFields' => 'Bdc Fields',
			'priceModel' => 'Price Model',
			'paramPriceModel' => 'Param Price Model',
			'ctdate' => 'Nbr Date CT',
			'theoPricePros' => 'Prix théorique Prospect',
			'theoPriceVg' => 'Prix théorique VG',
			'theoPriceVp' => 'Prix théorique VP',
			'theoPriceCt' => 'Prix théorique CT',
			'asile_type' => 'asile type',
			'produit_expirabe' => 'produit expirable',
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
		$criteria->compare('productType',$this->productType);
		$criteria->compare('ref',$this->ref,true);
		$criteria->compare('qty',$this->qty);
		$criteria->compare('priceATI',$this->priceATI,true);
		$criteria->compare('priceVAT',$this->priceVAT,true);
		$criteria->compare('amountBif',$this->amountBif,true);
		$criteria->compare('idPaymentProcessorSet',$this->idPaymentProcessorSet,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('webSiteProductCode',$this->webSiteProductCode,true);
		$criteria->compare('isCT',$this->isCT);
		$criteria->compare('titleStat',$this->titleStat,true);
		$criteria->compare('abrStat',$this->abrStat,true);
		$criteria->compare('boutique',$this->boutique);
		$criteria->compare('webSiteProductCodeAC',$this->webSiteProductCodeAC,true);
		$criteria->compare('commercialText',$this->commercialText,true);
		
		$criteria->compare('priceModel',$this->priceModel,true);
		$criteria->compare('ctdate',$this->ctdate,true);
			
		$criteria->compare('theoPricePros',$this->theoPricePros,true);
		$criteria->compare('theoPriceVg',$this->theoPriceVg);
		$criteria->compare('theoPriceVp',$this->theoPriceVp,true);
		$criteria->compare('theoPriceCt',$this->theoPriceCt,true);
		$criteria->compare('asile_type',$this->asile_type,true);
		$criteria->compare('produit_expirable',$this->produit_expirable,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	protected function beforeSave() {
	    parent::beforeSave();
	    if(!empty($this->productType2)){
	    	if(is_array($this->productType2))
	    	{$this->productType2 = implode(',', $this->productType2);}
	    }
	  	  
	    return $this;
	}/**/
}
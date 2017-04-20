<?php

/**
 * This is the model class for table "payment_transaction".
 *
 * The followings are the available columns in table 'payment_transaction':
 * @property integer $id
 * @property string $description
 * @property string $totalAtiPrice
 * @property string $currency
 * @property integer $orderID
 * @property integer $internauteID
 * @property string $Chrono
 * @property string $email
 * @property string $treatmentUrl
 * @property string $paymentProcessor
 * @property integer $modeLivraison
 * @property integer $vueTypCheck
 * @property integer $status
 * @property integer $errorNumber
 * @property string $errorMessage
 * @property string $dateCreation
 * @property string $refundDate
 * @property string $dateFinalAnswer
 * @property string $productRef
 * @property integer $productQty
 * @property string $productAtiPrice
 * @property string $externId
 * @property string $EMVADMIN1
 * @property string $EMVADMIN2
 * @property string $EMVADMIN3
 * @property string $EMVADMIN4
 * @property string $EMVADMIN5
 * @property string $EMVADMIN6
 * @property string $EMVADMIN7
 * @property string $EMVADMIN8
 * @property string $EMVADMIN9
 * @property string $EMVADMIN10
 * @property string $EMVADMIN11
 * @property string $EMVADMIN12
 * @property string $EMVADMIN13
 * @property string $EMVADMIN14
 * @property string $EMVADMIN15
 * @property string $EMVADMIN16
 * @property string $EMVADMIN17
 * @property string $EMVADMIN18
 * @property string $EMVADMIN19
 * @property string $EMVADMIN20
 * @property string $EMVADMIN21
 * @property string $EMVADMIN22
 * @property string $EMVADMIN23
 * @property string $EMVADMIN24
 * @property string $EMVADMIN25
 * @property string $EMVADMIN26
 * @property string $EMVADMIN27
 * @property string $EMVADMIN28
 * @property string $EMVADMIN29
 * @property string $EMVADMIN30
 * @property string $EMVADMIN31
 * @property string $EMVADMIN32
 * @property string $EMVADMIN33
 * @property string $EMVADMIN34
 * @property string $EMVADMIN35
 * @property string $EMVADMIN36
 * @property string $EMVADMIN37
 * @property string $EMVADMIN38
 * @property string $EMVADMIN39
 * @property string $EMVADMIN40
 * @property string $EMVADMIN41
 * @property string $EMVADMIN42
 * @property string $EMVADMIN43
 * @property string $EMVADMIN44
 * @property string $EMVADMIN45
 * @property string $EMVADMIN46
 * @property string $EMVADMIN47
 * @property string $EMVADMIN48
 * @property string $EMVADMIN49
 * @property string $EMVADMIN50
 * @property string $EMVADMIN51
 * @property string $EMVADMIN52
 * @property string $EMVADMIN53
 * @property string $EMVADMIN54
 * @property string $EMVADMIN55
 * @property string $EMVADMIN56
 * @property string $EMVADMIN57
 * @property string $EMVADMIN58
 * @property string $EMVADMIN59
 * @property string $EMVADMIN60
 * @property string $EMVADMIN61
 * @property string $EMVADMIN62
 * @property string $EMVADMIN63
 * @property string $EMVADMIN64
 * @property string $EMVADMIN65
 * @property string $EMVADMIN66
 * @property string $EMVADMIN67
 * @property string $EMVADMIN68
 * @property string $EMVADMIN69
 * @property string $SOURCE
 * @property string $CAMPAIGN
 * @property string $SupportRef
 * @property string $SupportDate
 * @property string $subPaymentProcessor
 * @property string $refBatchSelling
 * @property string $refDiscount
 * @property string $refPricingGrid
 * @property string $isAP
 * @property string $RefAP
 * @property string $externOption
 * @property integer $RefundStatus
 * @property string $TrackingNumber
 * @property string $ModificationDate
 * @property string $Site
 * @property integer $PRN
 * @property string $updateTS
 * @property integer $annuleCommande
 * @property string $annulationDate
 * @property string $amountProposed
 * @property integer $RefundParcial
 * @property string $AmountPartialRefund
 * @property integer $commCheckRembCB
 * @property string $datesd
 */
class PaymentTransaction extends CActiveRecord
{
	public $id;
	public $emailUser;
	public $creationDate;
	public $refundDate;
	public $pricePaid ;
	public $unitPrice ;
	public $numCheck ;
	public $invoiceStatus ;
	public $version;

	public  $description ;
	public  $totalAtiPrice ;
	public  $currency ;
	public  $orderID ;
	public  $internauteID ;
	public  $chrono ;
	public  $email ;
	public  $treatmentUrl ;
	public  $paymentProcessor ;
	public  $modeLivraison ;
	public  $vueTypCheck ;
	public  $status;
	public  $errorNumber ;
	public  $errorMessage ;
	public  $dateCreation ;
	public  $dateFinalAnswer ;
	public  $productRef ;
	public  $productQty ;
	public  $productAtiPrice ;
	public  $externId ;
	
	public  $SOURCE ;
	public  $CAMPAIGN ;
	public  $SupportRef ;
	public  $SupportDate ;
	public  $subPaymentProcessor ;
	public  $refBatchSelling ;
	public  $refDiscount ;
	public  $refPricingGrid ;
	public  $isAP ;
	public  $RefAP ;
	public  $externOption ;
	public  $RefundStatus ;
	public  $TrackingNumber ;
	public  $ModificationDate ;
	public  $Site ;
	public  $PRN ;
	public  $updateTS ;
	public  $annuleCommande ;
	public  $annulationDate ;
	public  $amountProposed ;
	public  $RefundParcial ;
	public  $AmountPartialRefund ;
	public  $commCheckRembCB ;
	public  $datesd ;
	public  $Nbr ;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return PaymentTransaction the static model class
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
		return 'payment_transaction';
	}

	/**
	 * @return array validation rules for model attributes.
	 */

	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
		/*	array('Chrono, ModificationDate, updateTS, commCheckRembCB', 'required'),
			array('orderID, internauteID, modeLivraison, vueTypCheck, status, errorNumber, productQty, RefundStatus, PRN, annuleCommande, RefundParcial, commCheckRembCB', 'numerical', 'integerOnly'=>true),
			array('description, errorMessage, externId, EMVADMIN1, EMVADMIN2, EMVADMIN3, EMVADMIN4, EMVADMIN5, EMVADMIN6, EMVADMIN7, EMVADMIN8, EMVADMIN9, EMVADMIN10, EMVADMIN11, EMVADMIN12, EMVADMIN13, EMVADMIN14, EMVADMIN15, EMVADMIN16, EMVADMIN17', 'length', 'max'=>255),
			array('totalAtiPrice, productAtiPrice, amountProposed, AmountPartialRefund', 'length', 'max'=>13),
			array('currency', 'length', 'max'=>3),
			array('Chrono', 'length', 'max'=>60),
			array('email, paymentProcessor', 'length', 'max'=>100),
			array('productRef, EMVADMIN18, EMVADMIN19, EMVADMIN20, EMVADMIN21, EMVADMIN22, EMVADMIN23, EMVADMIN24, EMVADMIN25, EMVADMIN26, EMVADMIN27, EMVADMIN28, EMVADMIN29, EMVADMIN30, EMVADMIN31, EMVADMIN32, EMVADMIN33, EMVADMIN34, EMVADMIN35, EMVADMIN36, EMVADMIN37, EMVADMIN38, EMVADMIN39, EMVADMIN40, EMVADMIN41, EMVADMIN42, EMVADMIN43, EMVADMIN44, EMVADMIN45, EMVADMIN46, EMVADMIN47, EMVADMIN48, EMVADMIN49, EMVADMIN50, EMVADMIN51, EMVADMIN52, EMVADMIN53, EMVADMIN54, EMVADMIN55, EMVADMIN56, EMVADMIN57, EMVADMIN58, EMVADMIN59, EMVADMIN60, EMVADMIN61, EMVADMIN62, EMVADMIN63, EMVADMIN64, EMVADMIN65, EMVADMIN66, EMVADMIN67, EMVADMIN68, EMVADMIN69, SOURCE, CAMPAIGN, refBatchSelling, refDiscount, refPricingGrid, isAP, RefAP, externOption, TrackingNumber', 'length', 'max'=>50),
			array('SupportRef, subPaymentProcessor', 'length', 'max'=>20),
			array('Site', 'length', 'max'=>5),
			array('treatmentUrl, dateCreation, dateFinalAnswer, SupportDate, annulationDate, datesd', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, description, totalAtiPrice, currency, orderID, internauteID, Chrono, email, treatmentUrl, paymentProcessor, modeLivraison, vueTypCheck, status, errorNumber, errorMessage, dateCreation, dateFinalAnswer, productRef, productQty, productAtiPrice, externId, EMVADMIN1, EMVADMIN2, EMVADMIN3, EMVADMIN4, EMVADMIN5, EMVADMIN6, EMVADMIN7, EMVADMIN8, EMVADMIN9, EMVADMIN10, EMVADMIN11, EMVADMIN12, EMVADMIN13, EMVADMIN14, EMVADMIN15, EMVADMIN16, EMVADMIN17, EMVADMIN18, EMVADMIN19, EMVADMIN20, EMVADMIN21, EMVADMIN22, EMVADMIN23, EMVADMIN24, EMVADMIN25, EMVADMIN26, EMVADMIN27, EMVADMIN28, EMVADMIN29, EMVADMIN30, EMVADMIN31, EMVADMIN32, EMVADMIN33, EMVADMIN34, EMVADMIN35, EMVADMIN36, EMVADMIN37, EMVADMIN38, EMVADMIN39, EMVADMIN40, EMVADMIN41, EMVADMIN42, EMVADMIN43, EMVADMIN44, EMVADMIN45, EMVADMIN46, EMVADMIN47, EMVADMIN48, EMVADMIN49, EMVADMIN50, EMVADMIN51, EMVADMIN52, EMVADMIN53, EMVADMIN54, EMVADMIN55, EMVADMIN56, EMVADMIN57, EMVADMIN58, EMVADMIN59, EMVADMIN60, EMVADMIN61, EMVADMIN62, EMVADMIN63, EMVADMIN64, EMVADMIN65, EMVADMIN66, EMVADMIN67, EMVADMIN68, EMVADMIN69, SOURCE, CAMPAIGN, SupportRef, SupportDate, subPaymentProcessor, refBatchSelling, refDiscount, refPricingGrid, isAP, RefAP, externOption, RefundStatus, TrackingNumber, ModificationDate, Site, PRN, updateTS, annuleCommande, annulationDate, amountProposed, RefundParcial, AmountPartialRefund, commCheckRembCB, datesd', 'safe', 'on'=>'search'),
		*/
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
			'description' => 'Description',
			'totalAtiPrice' => 'Total Ati Price',
			'currency' => 'Currency',
			'orderID' => 'Order',
			'internauteID' => 'Internaute',
			'chrono' => 'Chrono',
			'email' => 'Email',
			'treatmentUrl' => 'Treatment Url',
			'paymentProcessor' => 'Payment Processor',
			'modeLivraison' => 'Mode Livraison',
			'vueTypCheck' => 'Vue Typ Check',
			'status' => 'Status',
			'errorNumber' => 'Error Number',
			'errorMessage' => 'Error Message',
			'dateCreation' => 'Date Creation',
			'dateFinalAnswer' => 'Date Final Answer',
			'productRef' => 'Product Ref',
			'productQty' => 'Product Qty',
			'productAtiPrice' => 'Product Ati Price',
			'externId' => 'Extern',
			/*
			'EMVADMIN1' => 'Emvadmin1',
			'EMVADMIN2' => 'Emvadmin2',
			'EMVADMIN3' => 'Emvadmin3',
			'EMVADMIN4' => 'Emvadmin4',
			'EMVADMIN5' => 'Emvadmin5',
			'EMVADMIN6' => 'Emvadmin6',
			'EMVADMIN7' => 'Emvadmin7',
			'EMVADMIN8' => 'Emvadmin8',
			'EMVADMIN9' => 'Emvadmin9',
			'EMVADMIN10' => 'Emvadmin10',
			'EMVADMIN11' => 'Emvadmin11',
			'EMVADMIN12' => 'Emvadmin12',
			'EMVADMIN13' => 'Emvadmin13',
			'EMVADMIN14' => 'Emvadmin14',
			'EMVADMIN15' => 'Emvadmin15',
			'EMVADMIN16' => 'Emvadmin16',
			'EMVADMIN17' => 'Emvadmin17',
			'EMVADMIN18' => 'Emvadmin18',
			'EMVADMIN19' => 'Emvadmin19',
			'EMVADMIN20' => 'Emvadmin20',
			'EMVADMIN21' => 'Emvadmin21',
			'EMVADMIN22' => 'Emvadmin22',
			'EMVADMIN23' => 'Emvadmin23',
			'EMVADMIN24' => 'Emvadmin24',
			'EMVADMIN25' => 'Emvadmin25',
			'EMVADMIN26' => 'Emvadmin26',
			'EMVADMIN27' => 'Emvadmin27',
			'EMVADMIN28' => 'Emvadmin28',
			'EMVADMIN29' => 'Emvadmin29',
			'EMVADMIN30' => 'Emvadmin30',
			'EMVADMIN31' => 'Emvadmin31',
			'EMVADMIN32' => 'Emvadmin32',
			'EMVADMIN33' => 'Emvadmin33',
			'EMVADMIN34' => 'Emvadmin34',
			'EMVADMIN35' => 'Emvadmin35',
			'EMVADMIN36' => 'Emvadmin36',
			'EMVADMIN37' => 'Emvadmin37',
			'EMVADMIN38' => 'Emvadmin38',
			'EMVADMIN39' => 'Emvadmin39',
			'EMVADMIN40' => 'Emvadmin40',
			'EMVADMIN41' => 'Emvadmin41',
			'EMVADMIN42' => 'Emvadmin42',
			'EMVADMIN43' => 'Emvadmin43',
			'EMVADMIN44' => 'Emvadmin44',
			'EMVADMIN45' => 'Emvadmin45',
			'EMVADMIN46' => 'Emvadmin46',
			'EMVADMIN47' => 'Emvadmin47',
			'EMVADMIN48' => 'Emvadmin48',
			'EMVADMIN49' => 'Emvadmin49',
			'EMVADMIN50' => 'Emvadmin50',
			'EMVADMIN51' => 'Emvadmin51',
			'EMVADMIN52' => 'Emvadmin52',
			'EMVADMIN53' => 'Emvadmin53',
			'EMVADMIN54' => 'Emvadmin54',
			'EMVADMIN55' => 'Emvadmin55',
			'EMVADMIN56' => 'Emvadmin56',
			'EMVADMIN57' => 'Emvadmin57',
			'EMVADMIN58' => 'Emvadmin58',
			'EMVADMIN59' => 'Emvadmin59',
			'EMVADMIN60' => 'Emvadmin60',
			'EMVADMIN61' => 'Emvadmin61',
			'EMVADMIN62' => 'Emvadmin62',
			'EMVADMIN63' => 'Emvadmin63',
			'EMVADMIN64' => 'Emvadmin64',
			'EMVADMIN65' => 'Emvadmin65',
			'EMVADMIN66' => 'Emvadmin66',
			'EMVADMIN67' => 'Emvadmin67',
			'EMVADMIN68' => 'Emvadmin68',
			'EMVADMIN69' => 'Emvadmin69',
		    */
			'SOURCE' => 'Source',
			'CAMPAIGN' => 'Campaign',
			'SupportRef' => 'Support Ref',
			'SupportDate' => 'Support Date',
			'subPaymentProcessor' => 'Sub Payment Processor',
			'refBatchSelling' => 'Ref Batch Selling',
			'refDiscount' => 'Ref Discount',
			'refPricingGrid' => 'Ref Pricing Grid',
			'isAP' => 'Is Ap',
			'RefAP' => 'Ref Ap',
			'externOption' => 'Extern Option',
			'RefundStatus' => 'Refund Status',
			'TrackingNumber' => 'Tracking Number',
			'ModificationDate' => 'Modification Date',
			'Site' => 'Site',
			'PRN' => 'Prn',
			'updateTS' => 'Update Ts',
			'annuleCommande' => 'Annule Commande',
			'annulationDate' => 'Annulation Date',
			'amountProposed' => 'Amount Proposed',
			'RefundParcial' => 'Refund Parcial',
			'AmountPartialRefund' => 'Amount Partial Refund',
			'commCheckRembCB' => 'Comm Check Remb Cb',
			'datesd' => 'Datesd',
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
		$criteria->select = ' id ,internauteID, email AS emailUser, dateCreation AS creationDate, status AS invoiceStatus, externId AS chrono, productRef AS productRef, currency AS currency, paymentProcessor, totalAtiPrice, vueTypCheck, "V1" AS version';
		$criteria->compare('id',$this->id);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('totalAtiPrice',$this->totalAtiPrice,true);
		$criteria->compare('currency',$this->currency,true);
		$criteria->compare('orderID',$this->orderID);
		$criteria->compare('internauteID',$this->internauteID);
		$criteria->compare('chrono',$this->chrono,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('treatmentUrl',$this->treatmentUrl,true);
		$criteria->compare('paymentProcessor',$this->paymentProcessor,true);
		$criteria->compare('modeLivraison',$this->modeLivraison);
		$criteria->compare('vueTypCheck',$this->vueTypCheck);
		$criteria->compare('status',$this->status);
		$criteria->compare('errorNumber',$this->errorNumber);
		$criteria->compare('errorMessage',$this->errorMessage,true);
		$criteria->compare('dateCreation',$this->dateCreation,true);
		$criteria->compare('refundDate',$this->refundDate,true);
		$criteria->compare('dateFinalAnswer',$this->dateFinalAnswer,true);
		$criteria->compare('productRef',$this->productRef,true);
		$criteria->compare('productQty',$this->productQty);
		$criteria->compare('productAtiPrice',$this->productAtiPrice,true);
		$criteria->compare('externId',$this->externId,true);
		
		$criteria->compare('SOURCE',$this->SOURCE,true);
		$criteria->compare('CAMPAIGN',$this->CAMPAIGN,true);
		$criteria->compare('SupportRef',$this->SupportRef,true);
		$criteria->compare('SupportDate',$this->SupportDate,true);
		$criteria->compare('subPaymentProcessor',$this->subPaymentProcessor,true);
		$criteria->compare('refBatchSelling',$this->refBatchSelling,true);
		$criteria->compare('refDiscount',$this->refDiscount,true);
		$criteria->compare('refPricingGrid',$this->refPricingGrid,true);
		$criteria->compare('isAP',$this->isAP,true);
		$criteria->compare('RefAP',$this->RefAP,true);
		$criteria->compare('externOption',$this->externOption,true);
		$criteria->compare('RefundStatus',$this->RefundStatus);
		$criteria->compare('TrackingNumber',$this->TrackingNumber,true);
		$criteria->compare('ModificationDate',$this->ModificationDate,true);
		$criteria->compare('Site',$this->Site,true);
		$criteria->compare('PRN',$this->PRN);
		$criteria->compare('updateTS',$this->updateTS,true);
		$criteria->compare('annuleCommande',$this->annuleCommande);
		$criteria->compare('annulationDate',$this->annulationDate,true);
		$criteria->compare('amountProposed',$this->amountProposed,true);
		$criteria->compare('RefundParcial',$this->RefundParcial);
		$criteria->compare('AmountPartialRefund',$this->AmountPartialRefund,true);
		$criteria->compare('commCheckRembCB',$this->commCheckRembCB);
		$criteria->compare('datesd',$this->datesd,true);


		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

		/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function searchChrono($chrono)
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;
		$criteria->select = ' id ,internauteID, email AS emailUser, dateCreation AS creationDate, status AS invoiceStatus, externId, externId AS chrono, productRef AS productRef, currency AS currency, paymentProcessor, totalAtiPrice, vueTypCheck, PRN, "V1" AS version';
		$criteria->addCondition(' externId  LIKE "%'.$chrono.'%" ');
		$criteria->compare('id',$this->id);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('totalAtiPrice',$this->totalAtiPrice,true);
		$criteria->compare('currency',$this->currency,true);
		$criteria->compare('orderID',$this->orderID);
		$criteria->compare('internauteID',$this->internauteID);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('treatmentUrl',$this->treatmentUrl,true);
		$criteria->compare('paymentProcessor',$this->paymentProcessor,true);
		$criteria->compare('modeLivraison',$this->modeLivraison);
		$criteria->compare('vueTypCheck',$this->vueTypCheck);
		$criteria->compare('status',$this->status);
		$criteria->compare('errorNumber',$this->errorNumber);
		$criteria->compare('errorMessage',$this->errorMessage,true);
		$criteria->compare('dateCreation',$this->dateCreation,true);
		$criteria->compare('refundDate',$this->refundDate,true);
		$criteria->compare('dateFinalAnswer',$this->dateFinalAnswer,true);
		$criteria->compare('productRef',$this->productRef,true);
		$criteria->compare('productQty',$this->productQty);
		$criteria->compare('productAtiPrice',$this->productAtiPrice,true);
		$criteria->compare('externId',$this->externId,true);
		$criteria->compare('SOURCE',$this->SOURCE,true);
		$criteria->compare('CAMPAIGN',$this->CAMPAIGN,true);
		$criteria->compare('SupportRef',$this->SupportRef,true);
		$criteria->compare('SupportDate',$this->SupportDate,true);
		$criteria->compare('subPaymentProcessor',$this->subPaymentProcessor,true);
		$criteria->compare('refBatchSelling',$this->refBatchSelling,true);
		$criteria->compare('refDiscount',$this->refDiscount,true);
		$criteria->compare('refPricingGrid',$this->refPricingGrid,true);
		$criteria->compare('isAP',$this->isAP,true);
		$criteria->compare('RefAP',$this->RefAP,true);
		$criteria->compare('externOption',$this->externOption,true);
		$criteria->compare('RefundStatus',$this->RefundStatus);
		$criteria->compare('TrackingNumber',$this->TrackingNumber,true);
		$criteria->compare('ModificationDate',$this->ModificationDate,true);
		$criteria->compare('Site',$this->Site,true);
		$criteria->compare('PRN',$this->PRN);
		$criteria->compare('updateTS',$this->updateTS,true);
		$criteria->compare('annuleCommande',$this->annuleCommande);
		$criteria->compare('annulationDate',$this->annulationDate,true);
		$criteria->compare('amountProposed',$this->amountProposed,true);
		$criteria->compare('RefundParcial',$this->RefundParcial);
		$criteria->compare('AmountPartialRefund',$this->AmountPartialRefund,true);
		$criteria->compare('commCheckRembCB',$this->commCheckRembCB);
		$criteria->compare('datesd',$this->datesd,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	public function searchPaymentsByMB(){
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;
		$criteria->select = ' id ,internauteID, email AS emailUser, dateCreation AS creationDate, status AS invoiceStatus, externId, externId AS chrono, productRef AS productRef, currency AS currency, paymentProcessor, totalAtiPrice, vueTypCheck, PRN, "V1" AS version';
		$criteria->addCondition(' paymentProcessor  = "MB_1"');
		$criteria->compare('id',$this->id);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('totalAtiPrice',$this->totalAtiPrice,true);
		$criteria->compare('currency',$this->currency,true);
		$criteria->compare('orderID',$this->orderID);
		$criteria->compare('internauteID',$this->internauteID);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('treatmentUrl',$this->treatmentUrl,true);
		$criteria->compare('paymentProcessor',$this->paymentProcessor,true);
		$criteria->compare('modeLivraison',$this->modeLivraison);
		$criteria->compare('vueTypCheck',$this->vueTypCheck);
		$criteria->compare('status',$this->status);
		$criteria->compare('errorNumber',$this->errorNumber);
		$criteria->compare('errorMessage',$this->errorMessage,true);
		$criteria->compare('dateCreation',$this->dateCreation,true);
		$criteria->compare('refundDate',$this->refundDate,true);
		$criteria->compare('dateFinalAnswer',$this->dateFinalAnswer,true);
		$criteria->compare('productRef',$this->productRef,true);
		$criteria->compare('productQty',$this->productQty);
		$criteria->compare('productAtiPrice',$this->productAtiPrice,true);
		$criteria->compare('externId',$this->externId,true);
		$criteria->compare('SOURCE',$this->SOURCE,true);
		$criteria->compare('CAMPAIGN',$this->CAMPAIGN,true);
		$criteria->compare('SupportRef',$this->SupportRef,true);
		$criteria->compare('SupportDate',$this->SupportDate,true);
		$criteria->compare('subPaymentProcessor',$this->subPaymentProcessor,true);
		$criteria->compare('refBatchSelling',$this->refBatchSelling,true);
		$criteria->compare('refDiscount',$this->refDiscount,true);
		$criteria->compare('refPricingGrid',$this->refPricingGrid,true);
		$criteria->compare('isAP',$this->isAP,true);
		$criteria->compare('RefAP',$this->RefAP,true);
		$criteria->compare('externOption',$this->externOption,true);
		$criteria->compare('RefundStatus',$this->RefundStatus);
		$criteria->compare('TrackingNumber',$this->TrackingNumber,true);
		$criteria->compare('ModificationDate',$this->ModificationDate,true);
		$criteria->compare('Site',$this->Site,true);
		$criteria->compare('PRN',$this->PRN);
		$criteria->compare('updateTS',$this->updateTS,true);
		$criteria->compare('annuleCommande',$this->annuleCommande);
		$criteria->compare('annulationDate',$this->annulationDate,true);
		$criteria->compare('amountProposed',$this->amountProposed,true);
		$criteria->compare('RefundParcial',$this->RefundParcial);
		$criteria->compare('AmountPartialRefund',$this->AmountPartialRefund,true);
		$criteria->compare('commCheckRembCB',$this->commCheckRembCB);
		$criteria->compare('datesd',$this->datesd,true);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * @param int $id
	 */
	public static function loadByInternauteId($internauteId){
		$criteria = new CDbCriteria();
		$criteria->compare('internauteID', ' = '. $internauteId);
		return self::model()->findAll($criteria);
	}

}

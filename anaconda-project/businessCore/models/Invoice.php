<?php

/**  
 * This is the model class for table "invoice".
 *
 * The followings are the available columns in table 'invoice':
 * @property integer $id
 * @property string $emailUser
 * @property string $creationDate
 * @property string $refInterne
 * @property string $ref1Transaction
 * @property string $ref2Transaction 
 * @property integer $invoiceStatus
 * @property string $origine
 * @property integer $refundStatus
 * @property integer $refundDate
 * @property string $modificationDate
 * @property string $parity
 * @property string $currency
 * @property string $dateDeliveryExport
 * @property string $dateTransfertToTransporter
 * @property string $paymentProcessor
 * @property string $codeSite
 * @property integer $deliveryMode
 * @property integer $thyCheckView
 * @property integer $errorNumber
 * @property string $errorMessage
 * @property string $dateFinalAnswer
 * @property string $campaign
 * @property string $supportRef
 * @property string $supportDate
 * @property string $subPaymentProcessor
 * @property string $trackingNumber
 * @property string $refBatchSelling
 * @property integer $priceStep
 * @property string $refPricingGrid
 * @property integer $idAbandonedCaddy
 * @property string $pricePaid
 * @property integer $numCheck
 * @property string $chrono
 * @property string $date_ouverture
 * @property integer $countSend
 * @property string $lastSend
 * @property string $commentary
 *
 * The followings are the available model relations:
 * @property User $User
 * @property Abandonedcaddy
 * @property Recordinvoice[] $RecordInvoice
 * @property Site $Site
 * @property Campaign $Campaign
 *
 * @package Models.Invoice
 */
class Invoice extends ActiveRecord
{
	
	public $id;
	public $emailUser;
	public $creationDate;
	public $refInterne;
	public $ref1Transaction;
	public $ref2Transaction;
	public $invoiceStatus;
	public $origine;
	public $refundStatus;
	public $refundDate;
	public $modificationDate;
	public $parity;
	public $currency;
	public $deviseinformativecheque;
	public $dateDeliveryExport;
	public $dateTransfertToTransporter;
	public $paymentProcessor;
	public $codeSite;
	public $deliveryMode;
	public $thyCheckView;
	public $errorNumber;
	public $errorMessage;
	public $dateFinalAnswer;
	public $campaign;
	public $supportRef;
	public $supportDate;
	public $subPaymentProcessor;
	public $trackingNumber;
	public $refBatchSelling;
	public $priceStep;
	public $refPricingGrid;
	public $idAbandonedCaddy;
	public $pricePaid;
	public $numCheck;
	public $chrono;
	public $date_ouverture;
	public $countSend;
	public $lastSend;	
	public $version;
	public $agent;
	public $commentary;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Invoice the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function rawTableName(){
		return 'invoice';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('invoiceStatus, refundStatus, deliveryMode, thyCheckView, errorNumber, priceStep, idAbandonedCaddy, numCheck, countSend', 'numerical', 'integerOnly'=>true),
			array('emailUser', 'length', 'max'=>100),
			array('refInterne, ref1Transaction, ref2Transaction, campaign, trackingNumber, paymentProcessor', 'length', 'max'=>50),
			array('origine, supportRef, subPaymentProcessor', 'length', 'max'=>20),
			array('parity, pricePaid', 'length', 'max'=>10),
			array('currency', 'length', 'max'=>3),
			array('deviseinformativecheque', 'length', 'max'=>3),
			array('codeSite', 'length', 'max'=>2),
			array('errorMessage', 'length', 'max'=>255),
			array('refBatchSelling, refPricingGrid', 'length', 'max'=>5),
			array('creationDate,refundDate, modificationDate, dateDeliveryExport, dateTransfertToTransporter, dateFinalAnswer, supportDate, chrono, countSend, lastSend', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, emailUser, creationDate, refundDate, refInterne, ref1Transaction, ref2Transaction, pricePaid, invoiceStatus, origine, codeSite, refundStatus, modificationDate, parity, currency, deviseinformativecheque, dateDeliveryExport, dateTransfertToTransporter, paymentProcessor, deliveryMode, thyCheckView, errorNumber, errorMessage, dateFinalAnswer, campaign, supportRef, supportDate, subPaymentProcessor, trackingNumber, refBatchSelling, priceStep, refPricingGrid, idAbandonedCaddy, numCheck, chrono, date_ouverture, countSend, lastSend', 'safe', 'on'=>'search'),
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
			'User' => array(self::BELONGS_TO, 'User', array( 'emailUser' => 'email' ) ),
			'AbandonedCaddy' => array(self::BELONGS_TO, 'Abandonedcaddy', 'idAbandonedCaddy'),
			'RecordInvoice' => array(self::HAS_MANY, 'Recordinvoice', 'idInvoice'),
			'Site' => array(self::BELONGS_TO, 'Site', 'codeSite'),
			'Campaign' => array(self::BELONGS_TO, 'Campaign', array( 'campaign' => 'ref' ) )
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'emailUser' => 'Email User',
			'creationDate' => 'Creation Date',
			'refundDate' => 'Refund Date',
			'refInterne' => 'Ref1 Interne',
			'ref1Transaction' => 'Ref1 Transaction',
			'ref2Transaction' => 'Ref2 Transaction',
			'invoiceStatus' => 'Invoice Status',
			'origine' => 'Origine',
			'refundStatus' => 'Refund Status',
			'modificationDate' => 'Modification Date',
			'parity' => 'Parity',
			'currency' => 'Currency',
			'deviseinformativecheque' => 'Devise Informative Cheque',
			'dateDeliveryExport' => 'Date Delivery Export',
			'dateTransfertToTransporter' => 'Date Transfert To Transporter',
			'paymentProcessor' => 'Payment Processor',
			'codeSite' => 'Code Site',
			'deliveryMode' => 'Delivery Mode',
			'thyCheckView' => 'Thy Check View',
			'errorNumber' => 'Error Number',
			'errorMessage' => 'Error Message',
			'dateFinalAnswer' => 'Date Final Answer',
			'campaign' => 'Campaign',
			'supportRef' => 'Support Ref',
			'supportDate' => 'Support Date',
			'subPaymentProcessor' => 'Sub Payment Processor',
			'trackingNumber' => 'Tracking Number',
			'refBatchSelling' => 'Ref Batch Selling',
			'priceStep' => 'Price Step',
			'refPricingGrid' => 'Ref Pricing Grid',
			'idAbandonedCaddy' => 'Id Abandoned Caddy',
			'pricePaid' => 'Amount',
			'numCheck' => 'Num Check',
			'chrono' => 'Chrono',
			'date_ouverture' => 'Opening Date',
			'lastSend' => 'Last Send',
			'refundDate' => 'Refund Date',
			'countSend' => 'Count Send',
			'version' => 'version',
			'agent' => 'agent',
			'commentary' => 'commentary',
		);
	}
	
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search($pageSize=false)
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;
		$criteria->select = 'id, emailUser, creationDate, refInterne, ref1Transaction, ref2Transaction, invoiceStatus, origine, refundStatus, refundDate, modificationDate, parity, currency, deviseinformativecheque, dateDeliveryExport, dateTransfertToTransporter, dateTransfertToTransporter, paymentProcessor, codeSite, deliveryMode, thyCheckView, errorNumber, errorMessage, dateFinalAnswer, campaign, supportRef, supportDate, subPaymentProcessor, trackingNumber, refBatchSelling, priceStep, refPricingGrid, idAbandonedCaddy, pricePaid, numCheck, chrono, date_ouverture, countSend, refundDate, lastSend,agent, "V2" AS version';
		$criteria->compare('id',$this->id);
		$criteria->compare('emailUser',$this->emailUser,true);
		$criteria->compare('creationDate',$this->creationDate,true);
		$criteria->compare('refundDate',$this->refundDate,true);
		$criteria->compare('refInterne',$this->refInterne,true);
		$criteria->compare('ref1Transaction',$this->ref1Transaction,true);
		$criteria->compare('ref2Transaction',$this->ref2Transaction,true);
		$criteria->compare('invoiceStatus',$this->invoiceStatus);
		$criteria->compare('origine',$this->origine,true);
		$criteria->compare('refundStatus', $this->refundStatus);
		$criteria->compare('modificationDate',$this->modificationDate,true);
		$criteria->compare('parity',$this->parity,true);
		$criteria->compare('currency',$this->currency,true);
		$criteria->compare('deviseinformativecheque',$this->deviseinformativecheque,true);
		$criteria->compare('dateDeliveryExport',$this->dateDeliveryExport,true);
		$criteria->compare('dateTransfertToTransporter',$this->dateTransfertToTransporter,true);
		$criteria->compare('paymentProcessor',$this->paymentProcessor,true);
		$criteria->compare('codeSite',$this->codeSite,true);
		$criteria->compare('deliveryMode',$this->deliveryMode);
		$criteria->compare('thyCheckView',$this->thyCheckView);
		$criteria->compare('errorNumber',$this->errorNumber);
		$criteria->compare('errorMessage',$this->errorMessage,true);
		$criteria->compare('dateFinalAnswer',$this->dateFinalAnswer,true);
		$criteria->compare('campaign',$this->campaign,true);
		$criteria->compare('supportRef',$this->supportRef,true);
		$criteria->compare('supportDate',$this->supportDate,true);
		$criteria->compare('subPaymentProcessor',$this->subPaymentProcessor,true);
		$criteria->compare('trackingNumber',$this->trackingNumber,true);
		$criteria->compare('refBatchSelling',$this->refBatchSelling,true);
		$criteria->compare('priceStep',$this->priceStep);
		$criteria->compare('refPricingGrid',$this->refPricingGrid,true);
		$criteria->compare('idAbandonedCaddy',$this->idAbandonedCaddy);
		$criteria->compare('pricePaid',$this->pricePaid);
		$criteria->compare('numCheck',$this->numCheck);
		$criteria->compare('chrono',$this->chrono);
		$criteria->compare('date_ouverture',$this->date_ouverture);
		$criteria->compare('countSend',$this->countSend);
		$criteria->compare('lastSend',$this->lastSend);
		$criteria->compare('refundDate',$this->refundDate);
		$criteria->compare('version',$this->version);
		$criteria->compare('agent',$this->agent);
		$criteria->compare('commentary',$this->commentary);
		
		if($pageSize!=false)
		{$criteria->limit = $pageSize;}

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
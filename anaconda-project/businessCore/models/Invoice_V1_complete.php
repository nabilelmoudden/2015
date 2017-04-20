<?php 
/**
 * This is the model class for table "invoice".
 *
 * The followings are the available columns in table 'invoice':
 * @property integer $id
 * @property string $IDInternaute
 * @property string $Chrono
 * @property string $RefProduct
 * @property integer $Qty
 * @property string $CreationDate
 * @property string $ref1Transaction
 * @property integer $invoiceStatus
 * @property string $Ref2Transaction
 * @property string $Origine
 * @property string $UnitPrice
 * @property string $PricePaid
 * @property integer $NumCheck
 * @property string $RefBatchSelling
 * @property integer $RefDiscount
 * @property string $RefPricingGrid
 * @property string $ExternOption
 * @property integer $refundStatus
 * @property string $DeliveryDate
 * @property string $DateExportLivraison
 * @property string $DateRemiseTransporteur
 * @property integer $IDPaymentTransaction
 * @property string $ModificationDate
 * @property string $Site
 * @property string $Parite
 * @property string $Devise
 * @property string $deviseinformativecheque
 * @property string $updateTS
 * @property integer $InvoiceDeliveryStatus
 * @property integer $ProductType
 * @property integer $RefundParcial
 * @property string $AmountPartialRefund
 * @property string $date_ouverture
 * @property integer $countSend
 * @property string $lastSend
 * @property string $version
 * @property string $commentary
 */
class Invoice_V1_complete extends CActiveRecord
{
	public $id;
	public $pricePaid ;
	public $numCheck ;
	public $emailUser ;
	public $totalAtiPrice ;	
	public $productRef;
	public $internauteID;
	public $status;
	public $creationDate;
	public $currency;
	public $modificationDate;
	
	
	public $ID;
	public $IDInternaute;
	public $Chrono;
	public $RefProduct;
	public $Qty;
	public $CreationDate;
	public $ref1Transaction;
	public $invoiceStatus;
	public $ref2Transaction;
	public $Origine;
	public $UnitPrice;
	public $PricePaid;
	public $NumCheck;
	public $RefBatchSelling;
	public $RefDiscount;
	public $RefPricingGrid;
	public $ExternOption;
	public $refundStatus;
	public $DeliveryDate;
	public $DateExportLivraison;
	public $DateRemiseTransporteur;
	public $IDPaymentTransaction;
	public $ModificationDate;
	public $Site;
	public $Parite;
	public $Devise;
	public $deviseinformativecheque;
	public $updateTS;
	public $InvoiceDeliveryStatus;
	public $ProductType;
	public $RefundParcial;
	public $AmountPartialRefund;
	public $date_ouverture;
	public $countSend;
	public $lastSend;
	public $version;
	public $refundDate;
	public $commentary;
	
	public $paymentProcessor;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Invoice_V1_complete the static model class
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
		return 'invoice';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
	/*		array('IDInternaute, Chrono, RefProduct, ref2Transaction, Origine, UnitPrice, NumCheck, DateExportLivraison, DateRemiseTransporteur, ModificationDate, updateTS, date_ouverture, countSend, lastSend', 'required'),
			array('Qty, invoiceStatus, NumCheck, RefDiscount, refundStatus, IDPaymentTransaction, InvoiceDeliveryStatus, ProductType, RefundParcial, countSend', 'numerical', 'integerOnly'=>true),
			array('IDInternaute, RefProduct, Origine', 'length', 'max'=>20),
			array('Chrono', 'length', 'max'=>60),
			array('ref1Transaction, ref2Transaction', 'length', 'max'=>50),
			array('UnitPrice, PricePaid, Parite, AmountPartialRefund', 'length', 'max'=>13),
			array('RefBatchSelling, RefPricingGrid, Site', 'length', 'max'=>5),
			array('ExternOption', 'length', 'max'=>100),
			array('Devise, deviseinformativecheque', 'length', 'max'=>3),
			array('CreationDate, DeliveryDate', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ID, IDInternaute, emailUser, Chrono, RefProduct, Qty, CreationDate, ref1Transaction, invoiceStatus, ref2Transaction, Origine, UnitPrice, PricePaid, NumCheck, RefBatchSelling, RefDiscount, RefPricingGrid, ExternOption, refundStatus, DeliveryDate, DateExportLivraison, DateRemiseTransporteur, IDPaymentTransaction, ModificationDate, Site, Parite, Devise, deviseinformativecheque, updateTS, InvoiceDeliveryStatus, ProductType, RefundParcial, AmountPartialRefund, date_ouverture, countSend, lastSend', 'safe', 'on'=>'search'),
	*/	);
	
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
			'id' => 'id',
			'IDInternaute' => 'Idinternaute',
			'emailUser' => 'Email User',
			'Chrono' => 'Chrono',
			'RefProduct' => 'Ref Product',
			'Qty' => 'Qty',
			'CreationDate' => 'Creation Date',
			'ref1Transaction' => 'Ref1 Transaction',
			'InvoiceStatus' => 'Invoice Status',
			'ref2Transaction' => 'Ref2 Transaction',
			'Origine' => 'Origine',
			'UnitPrice' => 'Unit Price',
			'PricePaid' => 'Price Paid',
			'NumCheck' => 'Num Check',
			'RefBatchSelling' => 'Ref Batch Selling',
			'RefDiscount' => 'Ref Discount',
			'RefPricingGrid' => 'Ref Pricing Grid',
			'ExternOption' => 'Extern Option',
			'refundStatus' => 'Refund Status',
			'DeliveryDate' => 'Delivery Date',
			'DateExportLivraison' => 'Date Export Livraison',
			'DateRemiseTransporteur' => 'Date Remise Transporteur',
			'IDPaymentTransaction' => 'Idpayment Transaction',
			'ModificationDate' => 'Modification Date',
			'Site' => 'Site',
			'Parite' => 'Parite',
			'Devise' => 'Devise',
			'deviseinformativecheque' => 'Deviseinformativecheque',
			'updateTS' => 'Update Ts',
			'InvoiceDeliveryStatus' => 'Invoice Delivery Status',
			'ProductType' => 'Product Type',
			'RefundParcial' => 'Refund Parcial',
			'AmountPartialRefund' => 'Amount Partial Refund',
			'date_ouverture' => 'Date Ouverture',
			'countSend' => 'Count Send',
			'lastSend' => 'Last Send',
			'version' => 'Version',
			'commentary' => 'commentary',
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
		
		$criteria->select = ' ID AS id, IDInternaute AS internauteID, IDInternaute as emailUser , CreationDate AS creationDate, ModificationDate AS modificationDate, InvoiceStatus AS invoiceStatus, RefundStatus as refundStatus, IDPaymentTransaction, RefProduct AS productRef, Devise AS currency, deviseinformativecheque, NumCheck AS numCheck, Ref1Transaction as ref1Transaction, Ref2Transaction as ref2Transaction, UnitPrice AS totalAtiPrice, UnitPrice, PricePaid AS pricePaid, "" AS refundDate, "V1" AS version';
		
		$criteria->compare('id',$this->id);
		$criteria->compare('IDInternaute',$this->IDInternaute,true);
		$criteria->compare('emailUser',$this->IDInternaute,true);
		$criteria->compare('Chrono',$this->Chrono,true);
		$criteria->compare('RefProduct',$this->RefProduct,true);
		$criteria->compare('Qty',$this->Qty);
		$criteria->compare('CreationDate',$this->CreationDate,true);
		$criteria->compare('ref1Transaction',$this->ref1Transaction,true);
		$criteria->compare('invoiceStatus',$this->invoiceStatus);
		$criteria->compare('ref2Transaction',$this->ref2Transaction,true);
		$criteria->compare('Origine',$this->Origine,true);
		$criteria->compare('UnitPrice',$this->UnitPrice,true);
		$criteria->compare('PricePaid',$this->PricePaid,true);
		$criteria->compare('NumCheck',$this->NumCheck);
		$criteria->compare('RefBatchSelling',$this->RefBatchSelling,true);
		$criteria->compare('RefDiscount',$this->RefDiscount);
		$criteria->compare('RefPricingGrid',$this->RefPricingGrid,true);
		$criteria->compare('ExternOption',$this->ExternOption,true);
		$criteria->compare('refundStatus',$this->refundStatus);
		$criteria->compare('DeliveryDate',$this->DeliveryDate,true);
		$criteria->compare('DateExportLivraison',$this->DateExportLivraison,true);
		$criteria->compare('DateRemiseTransporteur',$this->DateRemiseTransporteur,true);
		$criteria->compare('IDPaymentTransaction',$this->IDPaymentTransaction);
		$criteria->compare('ModificationDate',$this->ModificationDate,true);
		$criteria->compare('Site',$this->Site,true);
		$criteria->compare('Parite',$this->Parite,true);
		$criteria->compare('Devise',$this->Devise,true);
		$criteria->compare('deviseinformativecheque',$this->deviseinformativecheque,true);
		$criteria->compare('updateTS',$this->updateTS,true);
		$criteria->compare('InvoiceDeliveryStatus',$this->InvoiceDeliveryStatus);
		$criteria->compare('ProductType',$this->ProductType);
		$criteria->compare('RefundParcial',$this->RefundParcial);
		$criteria->compare('AmountPartialRefund',$this->AmountPartialRefund,true);
		$criteria->compare('date_ouverture',$this->date_ouverture,true);
		$criteria->compare('countSend',$this->countSend);
		$criteria->compare('lastSend',$this->lastSend,true);
		$criteria->compare('version',$this->version,true);
		$criteria->compare('commentary',$this->commentary,true);

		$crit = new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
		
		return $crit;
	}
}
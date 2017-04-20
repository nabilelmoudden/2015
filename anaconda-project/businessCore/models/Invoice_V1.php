<?php
/**  
 * This is the model class for table "invoice".
 *
 * The followings are the available columns in table 'invoice':
 * @property integer $ID
 * @property string $IDInternaute
 * @property string $CreationDate
 * @property string $RefProduct
 * @property integer $Qty
 * @property string $Ref1Transaction
 * @property string $Ref2Transaction 
 * @property integer $InvoiceStatus
 * @property string $Origine
 * @property string $UnitPrice
 * @property string $PricePaid
 * @property integer $RefundStatus
 * @property string $ModificationDate
 * @property string $Parite
 * @property string $Devise
 * @property string $DateExportLivraison
 * @property string $DateRemiseTransporteur
 * @property string $Site
 * @property integer $InvoiceDeliveryStatus
 * @property string $RefBatchSelling
 * @property string $RefPricingGrid
 * @property string $PricePaid
 * @property integer $NumCheck
 * @property string $Chrono
 * @property integer $countSend
 * @property string $lastSend
 * @property string $date_ouverture
 * @property string $commentary
 *
 * The followings are the available model relations:
 * @property User_V1 $User_V1
 *
 * @package Models.Invoice
 */

class Invoice_V1 extends CActiveRecord{
	public $ID;
	public $IDInternaute;
	public $CreationDate;
	public $RefProduct;
	public $Qty;
	public $Ref1Transaction;
	public $Ref2Transaction;
	public $InvoiceStatus;
	public $Origine;
	public $UnitPrice;
	public $PricePaid;
	public $RefundStatus;
	public $ModificationDate;
	public $Parite;
	public $Devise;
	public $DateExportLivraison;
	public $DateRemiseTransporteur;
	public $Site;
	public $InvoiceDeliveryStatus;
	public $RefBatchSelling;
	public $RefPricingGrid;
	public $NumCheck;
	public $Chrono;
	public $countSend;
	public $lastSend;
	public $date_ouverture;
	public $commentary;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Invoice the static model class
	 */
	 
	public static function model($className=__CLASS__){
		return parent::model($className);
	}



	/**
	 * @return string the associated database table name
	 */
	public function rawTableName(){
		return 'invoice';
	}

	/**
	 * @return string the associated database table name
	 */
	public function TableName(){
		return 'invoice';
	}

	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('IDInternaute, InvoiceStatus, RefundStatus, NumCheck, countSend', 'numerical', 'integerOnly'=>true),
			array('Ref1Transaction, Ref2Transaction', 'length', 'max'=>50),
			array('Origine', 'length', 'max'=>20),
			array('Parite, UnitPrice, PricePaid', 'length', 'max'=>10),
			array('Devise', 'length', 'max'=>3),
			array('Site', 'length', 'max'=>2),
			array('RefBatchSelling, RefPricingGrid', 'length', 'max'=>5),
			array('CreationDate, ModificationDate, DateExportLivraison, DateRemiseTransporteur, Chrono, countSend, lastSend, date_ouverture', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, IDInternaute, CreationDate, Qty, RefProduct, ref1Transaction, Ref2Transaction, UnitPrice, PricePaid, InvoiceStatus, Origine, Site, RefundStatus, ModificationDate, Parite, Devise, DateExportLivraison, DateRemiseTransporteur, RefBatchSelling, RefPricingGrid, NumCheck, Chrono, countSend, lastSend, date_ouverture', 'safe', 'on'=>'search'),
		);
	}



	/**
	 * @return array relational rules.
	 */
	public function relations(){
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'User_V1' => array(self::BELONGS_TO, 'User_V1', array( 'IDInternaute' => 'id' ) )
		);
	}



	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels(){
		return array(
			'id' => 'ID',
			'IDInternaute' => 'ID USER',
			'CreationDate' => 'Creation Date',
			'RefProduct' => 'Ref. Product',
			'Qty' => 'Quantité',
			'Ref1Transaction' => 'Ref1 Transaction',
			'Ref2Transaction' => 'Ref2 Transaction',
			'InvoiceStatus' => 'Invoice Status',
			'Origine' => 'Origine',
			'UnitPrice' => 'Unit Price',
			'PricePaid' => 'Amount',
			'RefundStatus' => 'Refund Status',
			'ModificationDate' => 'Modification Date',
			'Parite' => 'Parity',
			'Devise' => 'Currency',
			'DateExportLivraison' => 'Date Delivery Export',
			'DateRemiseTransporteur' => 'Date Transfert To Transporter',
			'Site' => 'Code Site',
			'RefBatchSelling' => 'Ref Batch Selling',
			'RefPricingGrid' => 'Ref Pricing Grid',
			'NumCheck' => 'Num Check',
			'Chrono' => 'Chrono',
			'lastSend' => 'Last Send',
			'date_ouverture' => 'Opening Date',
			'countSend' => 'Count Send',
			'commentary' => 'commentary',
		);
	}
	
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search(){
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.
		$criteria = new CDbCriteria;
		$criteria->select = ' "" as refundDate, date_ouverture';
		$criteria->compare('ID',$this->ID);
		$criteria->compare('IDInternaute', $this->IDInternaute,true);
		$criteria->compare('CreationDate',$this->CreationDate,true);
		$criteria->compare('RefProduct',$this->RefProduct,true);
		$criteria->compare('Qty',$this->Qty,true);
		$criteria->compare('Ref1Transaction',$this->Ref1Transaction,true);
		$criteria->compare('Ref2Transaction',$this->Ref2Transaction,true);
		$criteria->compare('InvoiceStatus',$this->InvoiceStatus);
		$criteria->compare('Origine',$this->Origine,true);
		$criteria->compare('UnitPrice',$this->UnitPrice,true);
		$criteria->compare('PricePaid',$this->PricePaid);
		$criteria->compare('RefundStatus',$this->RefundStatus);
		$criteria->compare('ModificationDate',$this->ModificationDate,true);
		$criteria->compare('Parite',$this->Parite,true);
		$criteria->compare('Devise',$this->Devise,true);
		$criteria->compare('DateExportLivraison',$this->DateExportLivraison,true);
		$criteria->compare('DateRemiseTransporteur',$this->DateRemiseTransporteur,true);
		$criteria->compare('Site',$this->Site,true);
		$criteria->compare('RefBatchSelling',$this->RefBatchSelling,true);
		$criteria->compare('RefPricingGrid',$this->RefPricingGrid,true);
		$criteria->compare('NumCheck',$this->NumCheck);
		$criteria->compare('Chrono',$this->Chrono);
		$criteria->compare('countSend',$this->countSend);
		$criteria->compare('lastSend',$this->lastSend);
		$criteria->compare('date_ouverture',$this->date_ouverture);
		$criteria->compare('commentary',$this->commentary);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * @param int $id
	 */
	public static function loadByInternauteId($internauteId){
		$criteria = new CDbCriteria();
		$criteria->compare('IDInternaute', ' = '. $internauteId);
		return self::model()->findAll($criteria);
	}

}
<?php


class AllInvoices extends ActiveRecord{
	/**	 * @return string the associated database table name	 */	
	
	public $f_name;
	public $l_name;
	
	public function rawTableName(){		
	
			return 'invoice';
			
	}
	public $porteur;
	
	/**	 * @return array validation rules for model attributes.	 */
	public function rules(){
		
		return array(
		    
			array('invoiceStatus, refundStatus, deliveryMode, thyCheckView, errorNumber, priceStep, idAbandonedCaddy, numCheck, countSend', 'numerical', 'integerOnly'=>true),			
			array('emailUser', 'length', 'max'=>100),			array('refInterne, ref1Transaction, ref2Transaction, campaign, trackingNumber, paymentProcessor', 'length', 'max'=>50),			
			array('origine, supportRef, subPaymentProcessor', 'length', 'max'=>20),			array('parity, pricePaid', 'length', 'max'=>10),			array('currency', 'length', 'max'=>3),			
			array('codeSite', 'length', 'max'=>2),			
			array('errorMessage', 'length', 'max'=>255),			
			array('refBatchSelling, refPricingGrid', 'length', 'max'=>5),			
			array('creationDate, modificationDate, dateDeliveryExport, dateTransfertToTransporter, dateFinalAnswer, supportDate, chrono, countSend, lastSend', 'safe'),			
			
			// The following rule is used by search().			
			// Please remove those attributes that should not be searched.			
			array('id, emailUser, creationDate, refInterne, ref1Transaction, ref2Transaction, pricePaid, invoiceStatus, origine, codeSite, refundStatus, modificationDate, parity, currency, dateDeliveryExport, dateTransfertToTransporter, paymentProcessor, deliveryMode, thyCheckView, errorNumber, errorMessage, dateFinalAnswer, campaign, supportRef, supportDate, subPaymentProcessor, trackingNumber, refBatchSelling, priceStep, refPricingGrid, idAbandonedCaddy, numCheck, chrono, countSend, lastSend', 'safe', 'on'=>'search'),		
			
			);	
			
	}
	
	
	/**	 * @return array relational rules.	 */
	public function relations()	{		
	
	// NOTE: you may need to adjust the relation name and the related		
	// class name for the relations automatically generated below.		
	return array();
	
	}
	/**	 * @return array customized attribute labels (name=>label)	 */
	public function attributeLabels()	{
		return array(			
		'id' => 'ID',		
		'emailUser' => 'Email User',			
		'creationDate' => 'Creation Date',			
		'refInterne' => 'Ref1 Interne',			
		'ref1Transaction' => 'Ref1 Transaction',			
		'ref2Transaction' => 'Ref2 Transaction',			
		'invoiceStatus' => 'Invoice Status',			
		'origine' => 'Origine',			
		'refundStatus' => 'Refund Status',			
		'modificationDate' => 'Modification Date',			
		'parity' => 'Parity',			
		'currency' => 'Currency',			
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
		'lastSend' => 'Last Send',			
		'countSend' => 'Count Send',
		'commentary' => 'commentary',

		);	
		
	}
	// ************************** SETTER ************************** //
	
	public function setPorteur( $porteur )	{		
	
		$this->porteur = $porteur;		
		return \Controller::loadConfigForPorteur( $porteur );	
	
	}
	
	/**	 * Retrieves a list of models based on the current search/filter conditions.	
	     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.	 
		 
	*/
	public function search($emailUser = ''){
		if( Yii::app()->request->getParam('complete') === NULL && Yii::app()->request->getParam('mb') === NULL && Yii::app()->request->getParam('refund') === NULL && !Yii::app()->params['mb']){
			$allInvoices_v1_All     = new \Business\Invoice;		
			$allInvoices_v2_All     = new \Business\PaymentTransaction;
			$pending                = true;
		}else{
			$allInvoices_v1_All     = new \Business\Invoice;		
			$allInvoices_v2_All     = new \Business\Invoice_V1_complete;
			$pending                = false;		
		}
		if( Yii::app()->request->getParam('Business\AllInvoices') == NULL && Yii::app()->request->getParam('refund') == NULL && Yii::app()->request->getParam('mb') == NULL && !Yii::app()->params['mb']){
			$allInvoices_v1 = $allInvoices_v1_All->searchInvoiceCheck($pending, 'creationDate DESC','', 50);
			$allInvoices_v2 = $allInvoices_v2_All->searchInvoiceCheck($pending, 'creationDate DESC', 50);
		}elseif(Yii::app()->request->getParam('refund') != NULL){
			$allInvoices_v1 = $allInvoices_v1_All->searchRefundInvoice('creationDate DESC', Yii::app()->request->getParam('Business\AllInvoices')['emailUser'],Yii::app()->request->getParam('Business\AllInvoices')['paymentProcessor'], Yii::app()->request->getParam('Business\AllInvoices')['refundStatus'], 50);
			$allInvoices_v2 = $allInvoices_v2_All->searchRefundInvoice('creationDate DESC', Yii::app()->request->getParam('Business\AllInvoices')['emailUser'],Yii::app()->request->getParam('Business\AllInvoices')['paymentProcessor'], Yii::app()->request->getParam('Business\AllInvoices')['refundStatus'], 50);
		} elseif(Yii::app()->request->getParam('mb') != NULL || Yii::app()->params['mb']){
			$allInvoices_v1 = $allInvoices_v1_All->searchInvoiceCheckMB('creationDate DESC', 50, Yii::app()->request->getParam('Business\AllInvoices')['emailUser'], Yii::app()->request->getParam('Business\AllInvoices')['chrono'], Yii::app()->request->getParam('Business\AllInvoices')['f_name'], Yii::app()->request->getParam('Business\AllInvoices')['l_name']);
			$allInvoices_v2 = $allInvoices_v2_All->searchInvoiceCheckMB('creationDate DESC', 50, Yii::app()->request->getParam('Business\AllInvoices')['emailUser'], Yii::app()->request->getParam('Business\AllInvoices')['chrono'], Yii::app()->request->getParam('Business\AllInvoices')['f_name'], Yii::app()->request->getParam('Business\AllInvoices')['l_name']);
		} else {
			$allInvoices_v1 = $allInvoices_v1_All->searchInvoiceCheck($pending, 'creationDate DESC', '', 50, Yii::app()->request->getParam('Business\AllInvoices')['emailUser'], Yii::app()->request->getParam('Business\AllInvoices')['chrono'], Yii::app()->request->getParam('Business\AllInvoices')['f_name'], Yii::app()->request->getParam('Business\AllInvoices')['l_name']);
			$allInvoices_v2 = $allInvoices_v2_All->searchInvoiceCheck($pending, 'creationDate DESC', 50, Yii::app()->request->getParam('Business\AllInvoices')['emailUser'], Yii::app()->request->getParam('Business\AllInvoices')['chrono'], Yii::app()->request->getParam('Business\AllInvoices')['f_name'], Yii::app()->request->getParam('Business\AllInvoices')['l_name']);
		}
		
		// Create filter model and set properties
		Yii::import("application.classes.Filtersform"); 		
		$filtersForm  = new FiltersForm;					
		$records      = array_merge($allInvoices_v1->data, $allInvoices_v2->data); 
		$filteredData = $filtersForm->filter($records);    	    
		$provAll = new CArrayDataProvider($filteredData,                        
			array(                           
					'keyField'=>false,                           
					'sort' => array( //optional and sortring                                
									'attributes' => array('emailUser','currency'),),       
									   
					'pagination' => array('pageSize' => 50) 
					//optional add a pagination                       
					 ));                       		
		     return $provAll;	

	}
	
	public function searchNoProgress($emailUser = ''){
		if( Yii::app()->request->getParam('complete') === NULL && Yii::app()->request->getParam('mb') === NULL && Yii::app()->request->getParam('refund') === NULL && !Yii::app()->params['mb']){
			$allInvoices_v1_All     = new \Business\Invoice;
			$allInvoices_v2_All     = new \Business\PaymentTransaction;
			$pending                = true;
		}else{
			$allInvoices_v1_All     = new \Business\Invoice;
			$allInvoices_v2_All     = new \Business\Invoice_V1_complete;
			$pending                = false;
		}
		if( Yii::app()->request->getParam('Business\AllInvoices') == NULL && Yii::app()->request->getParam('refund') == NULL && Yii::app()->request->getParam('mb') == NULL && !Yii::app()->params['mb']){
			$allInvoices_v1 = $allInvoices_v1_All->searchInvoiceCheck($pending, 'creationDate DESC','', 50);
			$allInvoices_v2 = $allInvoices_v2_All->searchInvoiceCheck($pending, 'creationDate DESC', 50);
		} elseif(Yii::app()->request->getParam('refund') != NULL && Yii::app()->request->getParam('Business\allInvoice_TYPE_REFUND') !== 'TREATED'){
            $allInvoices_v1 = $allInvoices_v1_All->searchRefundInvoiceNoProgress('creationDate DESC', Yii::app()->request->getParam('Business\AllInvoices')['emailUser'],Yii::app()->request->getParam('Business\AllInvoices')['paymentProcessor'], Yii::app()->request->getParam('Business\AllInvoices')['refundStatus'], 100);
			$allInvoices_v2 = $allInvoices_v2_All->searchRefundInvoiceNoProgress('creationDate DESC', Yii::app()->request->getParam('Business\AllInvoices')['emailUser'],Yii::app()->request->getParam('Business\AllInvoices')['paymentProcessor'], Yii::app()->request->getParam('Business\AllInvoices')['refundStatus'], 100);
		    
		} elseif(Yii::app()->request->getParam('refund') != NULL && Yii::app()->request->getParam('Business\allInvoice_TYPE_REFUND') === 'TREATED'){
            $allInvoices_v1 = $allInvoices_v1_All->searchRefundInvoiceNoProgress('modificationDate DESC', Yii::app()->request->getParam('Business\AllInvoices')['emailUser'],Yii::app()->request->getParam('Business\AllInvoices')['paymentProcessor'], 'TREATED', 100);
			$allInvoices_v2 = $allInvoices_v2_All->searchRefundInvoiceNoProgress('modificationDate DESC', Yii::app()->request->getParam('Business\AllInvoices')['emailUser'],Yii::app()->request->getParam('Business\AllInvoices')['paymentProcessor'], 'TREATED', 100);
		    
		} elseif(Yii::app()->request->getParam('mb') != NULL || Yii::app()->params['mb']){
			$allInvoices_v1 = $allInvoices_v1_All->searchInvoiceCheckMB('creationDate DESC', 50, Yii::app()->request->getParam('Business\AllInvoices')['emailUser'], Yii::app()->request->getParam('Business\AllInvoices')['chrono'], Yii::app()->request->getParam('Business\AllInvoices')['f_name'], Yii::app()->request->getParam('Business\AllInvoices')['l_name']);
			$allInvoices_v2 = $allInvoices_v2_All->searchInvoiceCheckMB('creationDate DESC', 50, Yii::app()->request->getParam('Business\AllInvoices')['emailUser'], Yii::app()->request->getParam('Business\AllInvoices')['chrono'], Yii::app()->request->getParam('Business\AllInvoices')['f_name'], Yii::app()->request->getParam('Business\AllInvoices')['l_name']);
		} else {
			$allInvoices_v1 = $allInvoices_v1_All->searchInvoiceCheck($pending, 'creationDate DESC', '', 50, Yii::app()->request->getParam('Business\AllInvoices')['emailUser'], Yii::app()->request->getParam('Business\AllInvoices')['chrono'], Yii::app()->request->getParam('Business\AllInvoices')['f_name'], Yii::app()->request->getParam('Business\AllInvoices')['l_name']);
			$allInvoices_v2 = $allInvoices_v2_All->searchInvoiceCheck($pending, 'creationDate DESC', 50, Yii::app()->request->getParam('Business\AllInvoices')['emailUser'], Yii::app()->request->getParam('Business\AllInvoices')['chrono'], Yii::app()->request->getParam('Business\AllInvoices')['f_name'], Yii::app()->request->getParam('Business\AllInvoices')['l_name']);
		}
	
		// Create filter model and set properties
		Yii::import("application.classes.Filtersform");
		$filtersForm  = new FiltersForm;
		$records      = array_merge($allInvoices_v1->data, $allInvoices_v2->data);
		$filteredData = $filtersForm->filter($records);
		$provAll = new CArrayDataProvider($filteredData,
				array(
						'keyField'=>false,
						'sort' => array( //optional and sortring
								'attributes' => array('emailUser','currency'),),
	
						'pagination' => array('pageSize' => 600)
						//optional add a pagination
				));
		return $provAll;
	
	}
}
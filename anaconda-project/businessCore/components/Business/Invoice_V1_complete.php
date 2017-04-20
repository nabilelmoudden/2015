<?php
namespace Business;
/**
 * Description of Invoice
 *
 * @author BENSAAD Jalal
 * @package Business.Invoice
 */
class Invoice_V1_complete extends \Invoice_V1_complete{
	/** 
	* PaymentTransaction Status
	*/
	const INVOICE_CADDIE		= 0;	
	const INVOICE_IN_PROGRESS	= 1;	
	const INVOICE_PAYED			= 2;	
	const INVOICE_ERROR			= -1;	
	const INVOICE_CANCEL		= 3;
	
	/**	* Refund Status	*/
	const INVOICE_REFUND_NOT_ASKED		= 0;	
	const INVOICE_REFUND_IN_PROGRESS	= 11;
	const INVOICE_REFUNDED				= 12;
	const INVOICE_REFUND_REFUSED		= 22;
	
	public function init(){
		parent::init();
	}	
	/**	 * @return array relational rules.	 * Surcharge pour que la relation soit sur la classe Business	 */
	public function relations()	{
		// NOTE: you may need to adjust the relation name and the related		// class name for the relations automatically generated below.
		return array('User_V1' => array(self::BELONGS_TO, '\Business\User_V1', array( 'internauteID' => 'id' ) ));	
	
	}

	/**
	 * Rajoute une fonctionnalité specifique a la classe	 * @return array
	 */
	public function behaviors(){
		
		return array_merge(
					parent::behaviors(),array( 'ActiveRecordBehavior' => 'application.components.ActiveRecordBehavior' )
				);	
	}
	
	/**	 * Recherche	 
	* @param string $order Ordre	 
	* @param int $pageSize	Nb de result par page	 
	* @return CActiveDataProvider	CActiveDataProvider	 
	*/
	
	public function search( $order = false, $pageSize = 20){
		
		$Provider = parent::search();
		
		if( $pageSize == false )
			$Provider->setPagination( false );
		else
			$Provider->pagination->pageSize = $pageSize;
			
		if( $order != false )
			$Provider->criteria->order = $order;
			
		return $Provider;	
	}
	
	/**
	 * @param int $id
	 */
	static public function loadByInternauteId($internauteId){
		return self::model()->findByAttributes( array( 'IDInternaute' => $internauteId ) );
	}
	
	/**	 * Finalise l'invoice	 * @return \Business\ConfigPaymentProcessor	 */
	public function finalize(){
		
		$this->invoiceStatus = self::INVOICE_IN_PROGRESS;
		foreach( $this->RecordInvoice as $RI )
			if( $RI->RecordInvoiceAnnexe != NULL && !$RI->RecordInvoiceAnnexe->save() )				
				return false;
		// Met a jour l'invoice :
		if( $this->User->save() && $this->save() )			
			return $this->engagePayment();		
		else		
			return false;	
	
	}
	
	
	public function getId(){
		return $this->id; 
	}
	
	/**
	 * Excute web form Update les infos clients EMV
	 * @return string|false Retour de la requete, false en cas de probleme
	 */
	Public function SendToEMV($type = 'UrlRefundDone'){
		$porteur = \Yii::app()->params['porteur'];
		$user = \Business\User_V1::load($this->IDInternaute);

		
		
		
			
				
		
		
		
			
		
		   
		
			
		
			
		
		
		if($GLOBALS['porteurWithTwoSFAccounts'][$porteur] && $user->CompteEMVactif != ''){
			$porteur = $GLOBALS['SFAccountsMap'][$user->CompteEMVactif] ;
		}
		
		switch ($type){
			case "UrlRefundDone":
				$url_WebForm = $GLOBALS['UrlRefundDone'][$porteur];
				break;
			case "UrlRefundReceived":
				$url_WebForm = $GLOBALS['UrlRefundReceived'][$porteur];
				break;
			case "UrlResendProduct":
				$url_WebForm = $GLOBALS['UrlResendProductV1'][$porteur];
				break;
			case "UrlPaiementCheque":
				$Router_V1   = \Business\Router_V1::loadByRef($this->RefProduct,$type);
				$url_WebForm = $Router_V1[0]['Url'];
				break;
		}
		
		$WF = new \WebForm($url_WebForm);
		$WF->setTokenWithInvoice_V1($this);
		return $WF->execute( true );
	}

	/**	 * Envoi une requete a EMV	 * @param string $type	Voir \Business\RouterEMV::$tabType	 * @return boolean	 * @throws \EsoterException	 */
	public function sendRequestToEMV( $type ){
					
		if( !in_array( $type, \Business\RouterEMV::$tabType ) )			
			throw new \EsoterException( 109, \Yii::t( 'error', '109' ).' : '.$type );		
		if( count($this->RecordInvoice) <= 0 )			
			return false;
		for( $i=0; $i<count($this->RecordInvoice); $i++ ){
			$Product	= $this->RecordInvoice[ $i ]->Product;			
			$Router		= $Product->RouterEMV( array( 'condition' => 'type = "'.$type.'"' ) );
			
			for( $j=0; $j<count($Router); $j++ ){			    
			
				if($type == \Business\RouterEMV::URL_INTENTION_CHEQUE ){					
						if( !$Router[$j]->sendRequest( $this ,false ,true ) )						
							return false;					    
				}else{		    				    
						if( !$Router[$j]->sendRequest( $this ) )						
							return false;			    
				}
							
			}		
		}		
		return true;	
	}
	
	
	/**	 * Retourne les refs des produits de l'invoice	 * @return array	Refs Products	 */
	public function getProductsRef(){
		$ret = array();
			$ret[] = $this->productRef;		
		return $ret;
	}
	
	/**	 
	* Retourne l'Url des produits de l'invoice	 
	* @return array	Url Products	 
	*/
	public function getUrlProducts($invoice){
		
		
		$Myurl =array();
		$ConfDNS      = \Business\Config::loadByKey( 'DNS' );
		$porteur      = \Yii::app()->params['porteur'];
		$j =1;
		for( $i=0; $i<count($invoice); $i++ ){
			
			$date = date_create($invoice[$i]->CreationDate);
			$PaymentTransaction = \Business\PaymentTransaction::load($invoice[$i]->IDPaymentTransaction);
			
			$user = \Business\User_V1::load($invoice[$i]->IDInternaute);	
			$date_naissance = date_create($user->Birthday);
			
			$Product  = \Business\Product_V1::loadByRef($invoice[$i]->RefProduct);
			$WebSiteProductCode = $Product->WebSiteProductCode;
			
			if($WebSiteProductCode=='vgl'){
				$WebSiteProductCode='vgldv';
				$param_url='bs=1&tr=1&gp=1';
			}else{
				$PaymentTransaction = \Business\PaymentTransaction::load($invoice[$i]->IDPaymentTransaction);
			    $param_url='bs='.$PaymentTransaction->refBatchSelling.'&tr='.$PaymentTransaction->refDiscount.'&gp='.$PaymentTransaction->refPricingGrid;
				
			}
			
			if ( $porteur == 'fr_laetizia' && ((strlen($invoice[$i]->RefProduct) == 11) or (strlen($invoice[$i]->RefProduct) == 13)) )
			{
				$prod_chain = '&pdt=ch';
				
			}else{
				
				if ( $porteur == 'fr_rinalda' && ((strlen($invoice[$i]->RefProduct) == 8) or (strlen($invoice[$i]->RefProduct) == 10)) )
				{
					$prod_chain = '&pdt=ch';
					
				}else{
					$prod_chain = '';
				}
				
			}
		
		     $Myurl[$j] = $ConfDNS->value.'/'.$GLOBALS['porteurMap'][$porteur].'/index.php?c='.$WebSiteProductCode.'&'.$param_url.'&p='.$user->Lastname.'&n='.$user->Firstname.'&d='.date_format($date_naissance, 'd/m/Y').'&m='.$user->Email.'&x='.$user->Civility.'&de='.date_format($date, 'm/d/Y').'&site='.$invoice[0]->Site.'&sd='.date_format($date, 'm/d/Y').''.$prod_chain;
			 $j = $j + 1;
		}
		return $Myurl;
	}
	
	/**	 
	* Retourne le montant total de l'invoice	 * @return	float	Total	 */
	public function getTotalInvoice(){		$total = $this->totalAtiPrice;
		return $total;
	}



	/**
	 * Retourne un tableau contenant la description de chaque produit de la commande	 * @return array Tableau de description
	 */

	public function getDescription(){
		
		if( count($this->RecordInvoice) <= 0 )			
			return false;
			
			$desc = array();
			for( $i=0; $i<count($this->RecordInvoice); $i++ ){			
					$desc[] = $this->RecordInvoice[$i]->Product->description;		
			}
			
		return $desc;	
	}



	/**
	 * Recherche les invoices avec le refundStatus = 11 OU 12
	 * @param int $pageSize	Nb de result par page
	 * @return \CActiveDataProvider	CActiveDataProvider
	 */

	public function searchRefundInvoice($order = false, $emailUser='',$paymentProcessor = '', $refundStatus='' , $pageSize = 0 , $type = ''){
		$Provider = $this->search( $order, false );
		$Provider->criteria->alias   = 'inv';
		$Provider->criteria->select = ' inv.ID AS id, inv.IDInternaute AS internauteID, inv.IDInternaute as emailUser , inv.CreationDate AS creationDate, inv.ModificationDate AS modificationDate, inv.InvoiceStatus AS invoiceStatus, inv.RefundStatus as refundStatus, inv.IDPaymentTransaction, inv.RefProduct AS productRef, inv.Ref1Transaction as ref1Transaction, inv.Ref2Transaction as ref2Transaction, inv.Devise AS currency, inv.NumCheck AS numCheck, inv.UnitPrice AS totalAtiPrice, inv.UnitPrice,( SELECT CASE pt.paymentProcessor WHEN  "PACNETCHECK_1" THEN inv.PricePaid ELSE inv.UnitPrice END ) AS pricePaid, "" AS refundDate, "V1" AS version';
		$Provider->criteria->join   = ' RIGHT JOIN payment_transaction pt ON inv.IDPaymentTransaction = pt.ID ';
		
		$Provider->criteria->addCondition(' (pt.RefundStatus = 11 || inv.RefundStatus = 11 || pt.RefundStatus = 22 || inv.RefundStatus = 22) AND inv.RefundStatus != 12 AND pt.RefundStatus != 12 AND pt.RefundStatus != 0 AND inv.RefundStatus != 0 AND pt.dateCreation >=  "2011-03-01 00:00:01" 
							AND inv.IDPaymentTransaction IS NOT NULL AND pt.email != "" AND inv.CreationDate > "2011-03-01 00:00:01" ');					
		
		if($type == 'Check')
			$Provider->criteria->addCondition('inv.numCheck not like 0');
		if($type == 'CB')
			$Provider->criteria->addCondition('inv.numCheck = 0');
		
		if($emailUser != ''){
			$user_v1 = new \Business\User_V1('search');		
			$users = $user_v1->searchMail(false, 50, $emailUser);
			if(!empty($users->data)){	
				$ids = array();
				foreach($users->data as $user)
					$ids[] = $user->id;	
				if(!empty($ids))
					$Provider->criteria->addInCondition('inv.IDInternaute', $ids);	
			}else{	
				$Provider->criteria->compare('inv.NumCheck', '');
			}		
		}
		if($paymentProcessor != ''){
			$Provider->criteria->addCondition(' paymentProcessor LIKE "%'.$paymentProcessor.'%" ');	
		}
		
		if($refundStatus != ''){
			if(strpos('Information incomplète',$refundStatus) !== false){
				$Provider->criteria->addCondition(' pt.RefundStatus = 22 || inv.RefundStatus = 22 ');
			}
			elseif(strpos('In progress',$refundStatus) !== false){
				$Provider->criteria->addCondition(' pt.RefundStatus = 11 || inv.RefundStatus = 11 ');
			}
		}
		
		$Provider->criteria->group   = ' inv.IDPaymentTransaction ';					
		$Provider->criteria->order   = ' inv.ModificationDate DESC ';
		
		return $Provider;
	}
	
	
	public function searchRefundInvoiceNoProgress($order = false, $emailUser='',$paymentProcessor = '', $refundStatus='' , $pageSize = 0 , $type = ''){
		
		$dateDebut = $_SESSION['dateDebut'] ;
		$dateFin   = $_SESSION['dateFin'];
		
		$Provider = $this->search( $order, false );
		$Provider->criteria->alias   = 'inv';
		$Provider->criteria->select = ' inv.ID AS id, inv.IDInternaute AS internauteID, inv.IDInternaute as emailUser , inv.CreationDate AS creationDate, inv.ModificationDate AS modificationDate, inv.InvoiceStatus AS invoiceStatus, inv.RefundStatus as refundStatus, inv.IDPaymentTransaction, inv.RefProduct AS productRef, inv.Ref1Transaction as ref1Transaction, inv.Ref2Transaction as ref2Transaction, inv.Devise AS currency, inv.NumCheck AS numCheck, inv.UnitPrice AS totalAtiPrice, inv.UnitPrice,( SELECT CASE pt.paymentProcessor WHEN  "PACNETCHECK_1" THEN inv.PricePaid ELSE inv.UnitPrice END ) AS pricePaid, "" AS refundDate, "V1" AS version';
		$Provider->criteria->join   = ' RIGHT JOIN payment_transaction pt ON inv.IDPaymentTransaction = pt.ID ';
		
		$Provider->criteria->addCondition(' (pt.RefundStatus = 11 || inv.RefundStatus = 11 || pt.RefundStatus = 22 || inv.RefundStatus = 22) AND inv.RefundStatus != 12 AND pt.RefundStatus != 12 AND pt.RefundStatus != 0 AND inv.RefundStatus != 0 AND pt.dateCreation >=  "2011-03-01 00:00:01"
							AND inv.IDPaymentTransaction IS NOT NULL AND pt.email != "" AND inv.CreationDate > "2011-03-01 00:00:01" ');
	
		if($type == 'Check')
			$Provider->criteria->addCondition('inv.numCheck not like 0');
		if($type == 'CB')
			$Provider->criteria->addCondition('inv.numCheck = 0');
	
		if($emailUser != ''){
			$user_v1 = new \Business\User_V1('search');
			$users = $user_v1->searchMail(false, 50, $emailUser);
			if(!empty($users->data)){
				$ids = array();
				foreach($users->data as $user)
					$ids[] = $user->id;
				if(!empty($ids))
					$Provider->criteria->addInCondition('inv.IDInternaute', $ids);
			}else{
				$Provider->criteria->compare('inv.NumCheck', '');
			}
		}
		if($paymentProcessor != ''){
			$Provider->criteria->addCondition(' paymentProcessor LIKE "%'.$paymentProcessor.'%" ');
		}
	
		if($refundStatus != ''){
			if(strpos('Information incomplète',$refundStatus) !== false){
				$Provider->criteria->addCondition(' pt.RefundStatus = 22 || inv.RefundStatus = 22 ');
			}
			elseif(strpos('In progress',$refundStatus) !== false){
				$Provider->criteria->addCondition(' pt.RefundStatus = 11 || inv.RefundStatus = 11 ');
			}
		}
	
		$Provider->criteria->group   = ' inv.IDPaymentTransaction ';
		$Provider->criteria->order   = ' inv.ModificationDate DESC ';
		$Provider->criteria->addCondition('inv.invoiceStatus != 1');
		
		if ('TREATED' === $refundStatus ) :
			$Provider->criteria->addCondition("inv.ModificationDate BETWEEN '".$dateDebut."' AND '".$dateFin."'");
		else:
			
			$Provider->criteria->addCondition("creationDate BETWEEN '".$dateDebut."' AND '".$dateFin."'");
		endif;

		return $Provider;
	}

	/**	 
	* Recherche les invoices check en attente / complete	 
	* @param	bool	$pending	Seulement les invoices check en attente ( numCheck == NULL )	 
	* @return \CActiveDataProvider	 
	*/
	public function searchInvoiceCheck( $pending = true, $order = false, $pageSize = 0 , $email = '', $chrono = '', $f_name = '', $l_name = ''){
						
		
		$Provider = $this->search( $order, $pageSize );
		$Provider->criteria->addCondition('numCheck IS NOT NULL' );
		$Provider->criteria->addCondition('numCheck != 0' );			
			
		
		if($email !== ''){
		
		
		
		$user_v1 = new \Business\User_V1('search');		
		$users = $user_v1->searchMail(false,50,$email);
			if(!empty($users->data)){	
				$ids = array();
				foreach($users->data as $user)
					$ids[] = $user->id;
					
				if( !empty($ids) )	
					$Provider->criteria->addInCondition('IDInternaute', $ids);	
			}else{
				
				    $Provider->criteria->compare('NumCheck', $email);
			}
		}
		
		if($chrono !== ''){
			
			$PaymentTransaction_v1 = new \Business\PaymentTransaction('search');	
		    $PaymentTransactions   = $PaymentTransaction_v1->searchChrono(false,50,$chrono);
			
			if(!empty($PaymentTransactions->data)){	
				$ids = array();
				foreach($PaymentTransactions->data as $PaymentTransaction)
					$ids[] = $PaymentTransaction->id;
				
				if( !empty($ids) )
					$Provider->criteria->addInCondition('IDPaymentTransaction', $ids);	
			}else{
				
				    $Provider->criteria->compare('NumCheck', $chrono);
			}
			
		}
		
		if($f_name !== ''){
			
			$user_v1 = new \Business\User_V1;		
			$users = $user_v1->search( false, 20000, '', '', '', $f_name);
			
			if(!empty($users->data)){	
			
				$ids = array();
				foreach($users->data as $user)
					$ids[] = $user->id;
			
				if(!empty($ids))
					$Provider->criteria->addInCondition('IDInternaute', $ids);
				
					
			}else{
			
				$Provider->criteria->compare('NumCheck', $f_name);
			}
		}
		
		if($l_name !== ''){
			
			$user_v1 = new \Business\User_V1;		
			$users = $user_v1->search( false, 20000, '', '', $l_name, '');
			
			if(!empty($users->data)){	
			
				$ids = array();
				foreach($users->data as $user)
					$ids[] = $user->id;
					
				
				if(!empty($ids))
					$Provider->criteria->addInCondition('IDInternaute', $ids);
				
					
			}else{
			
				$Provider->criteria->compare('NumCheck', $l_name);
			}
		}
		
		
		return $Provider;		
	}

	/**
	 * Recherche l'invoice pour un utilisateur precis, ayant acheté un produit precis
	 * @param string $email
	 * @param string $refProduct
	 * @return \CActiveDataProvider
	 */
	public function searchInvoiceForUserAndProduct($email, $refProduct){
		$Provider = $this->search( 'creationDate DESC' );
		$Provider->criteria->compare( 'invoiceStatus', self::INVOICE_PAYED );
		$Provider->criteria->with = 'RecordInvoice';
		$Provider->criteria->addCondition( '( RecordInvoice.refProduct = "'.$refProduct.'" )' );
		return $Provider;
	}

	/**
	 * Retourne le status de l'invoice sous la forme d'un texte
	 * @return string|boolean status
	 */
	public function humanInvoiceStatus(){
		switch( $this->invoiceStatus ){
			default :
			case self::INVOICE_CADDIE :
				return 'Caddie';
			case self::INVOICE_IN_PROGRESS :
				return 'In progress'; 
			case self::INVOICE_PAYED :
				return 'Payed';
			case self::INVOICE_ERROR :
				return 'Error';
			case self::INVOICE_CANCEL :
				return 'Canceled';
		}
		return false;
	}
	
	
	/**
	 * Retourne le status du remboursement sous la forme d'un texte
	 * @return string|boolean status
	 */
	public function humanRefundStatus(){
		switch( $this->refundStatus ){
			default :
			case self::INVOICE_REFUND_NOT_ASKED :
				return '-';
			case self::INVOICE_REFUND_IN_PROGRESS :
				return 'In progress';
			case self::INVOICE_REFUND_REFUSED :
				return 'Information incomplète';
			case self::INVOICE_REFUNDED :
				return 'Refunded';
		}
		return false;
	}
	/**	 
	* Recherche les invoices check en attente / complete	 
	* @param	bool	$pending	Seulement les invoices check en attente ( numCheck == NULL )	 
	* @return \CActiveDataProvider	 
	*/
	public function searchInvoiceCheckMB($order = false, $pageSize = 100 , $email = '', $chrono = '', $f_name = '', $l_name = ''){
		$Provider = $this->search( $order, $pageSize );
		//On veut pas de cheque
		$Provider->criteria->addCondition('numCheck = 0 OR numCheck = "" OR numCheck = NULL');
		//Nous cherchons les paymentsTransactionIds de ceux qui ont payé par Multibanco
		$PaymentTransaction_v1 = new \Business\PaymentTransaction('search');	
		$PaymentTransactions   = $PaymentTransaction_v1->searchPaymentsByMB('creationDate DESC',2000);
		
		
			
			
			
				
			
					
		
			
		
		
		if($email != ''){
			$user_v1 = new \Business\User_V1('search');		
			$users = $user_v1->searchMail(false,50,$email);
			if(!empty($users->data)){	
				$ids = array();
				foreach($users->data as $user)
					$ids[] = $user->id;
					
				if( !empty($ids) )	
					$Provider->criteria->addInCondition('IDInternaute', $ids);	
			}else{
				
				    $Provider->criteria->compare('NumCheck', $email);
			}
		}
		
		if($chrono != ''){
			$PaymentTransaction_v1 = new \Business\PaymentTransaction('search');	
		    $PaymentTransactions   = $PaymentTransaction_v1->searchChrono(false,50,$chrono);
			if(!empty($PaymentTransactions->data)){	
				$ids = array();
				foreach($PaymentTransactions->data as $PaymentTransaction)
					$ids[] = $PaymentTransaction->id;
				if( !empty($ids) )
					$Provider->criteria->addInCondition('IDPaymentTransaction', $ids);	
			}else{
				  $Provider->criteria->compare('NumCheck', $chrono);
			}
		}
		
		if($f_name != ''){
			$user_v1 = new \Business\User_V1;		
			$users = $user_v1->search( false, 20000, '', '', '', $f_name);
			if(!empty($users->data)){	
				$ids = array();
				foreach($users->data as $user)
					$ids[] = $user->id;
				if(!empty($ids))
					$Provider->criteria->addInCondition('IDInternaute', $ids);
			}else{
				$Provider->criteria->compare('NumCheck', $f_name);
			}
		}
		
		if($l_name != ''){
			$user_v1 = new \Business\User_V1;		
			$users = $user_v1->search( false, 20000, '', '', $l_name, '');
			if(!empty($users->data)){	
				$ids = array();
				foreach($users->data as $user)
					$ids[] = $user->id;
				if(!empty($ids))
					$Provider->criteria->addInCondition('IDInternaute', $ids);			
			}else{
				$Provider->criteria->compare('NumCheck', $l_name);
			}
		}
		
		return $Provider;		
	}
	
	
	// *************************** ASCESSEUR *************************** //
	/**
	 * @return \Business\User
	 */
	 public function getUser(){
		return $this->User_V1;
	}



	// *************************** STATIC *************************** //
	static function model($className=__CLASS__){
        return parent::model($className);
    }



	/**
	 * @param type $id
	 * @return \Business\Invoice
	 */
	 
	static public function load( $id ){
		return self::model()->findByPk( $id );
	}



	/*
	 * Charge l'instance Invoice correspondant a la refInterne
	 * @param	int	$ref	RefInterne
	 * @return	\Business\Invoice		Instance Invoice
	 */
	 
	static public function loadByRefInterne( $ref ){
		return self::model()->findByAttributes( array( 'refInterne' => $ref ) );
	}	  
	
	
	static public function loadByIdPaymentTransaction( $id ){
		return self::model()->findByAttributes( array( 'IDPaymentTransaction' => $id ) ); 
	}	  
	
	
	 /**	 
	 * Retourne le montant total de l'invoice	 
	 * @return	float	Total	
	 */
	 
		
	public function getUserByMail(){	
			$user = \Business\User_V1::load($this->internauteID);		
			if( $user != NULL )			
				return $user->Firstname.' '.$user->Lastname;		
			return ''; 			
				
	}
	
	public function getFirstnameByMail(){	
			$user = \Business\User_V1::load($this->internauteID);		
			if( $user != NULL )			
				return $user->Firstname;		
			return ''; 			
				
	}
	
	public function getLastnameByMail(){	
			$user = \Business\User_V1::load($this->internauteID);		
			if( $user != NULL )			
				return $user->Lastname;		
			return ''; 			
				
	}
     
	public function getMailById($refund = ''){		
		$user = \Business\User_V1::load($this->internauteID);		
		if( $user != NULL )			
			return $user->Email;		
		return ''; 				
	}
	
	public function getChronoById(){
		
		$PaymentTransaction = \Business\PaymentTransaction::load($this->IDPaymentTransaction);		
			
		if( $PaymentTransaction != NULL )			
			return $PaymentTransaction->externId;		
		return '';
	}
/**	
	public static function getChronoById($idPaymentTransaction = 0){
		if($idPaymentTransaction != 0)
			$PaymentTransaction = \Business\PaymentTransaction::load($idPaymentTransaction);		
		else
			$PaymentTransaction = \Business\PaymentTransaction::load($this->IDPaymentTransaction);		
			
		if( $PaymentTransaction != NULL )			
			return $PaymentTransaction->externId;		
		return '';
	}
**/	
	public function getRefInterneById(){  
	  
		$PaymentTransaction = \Business\PaymentTransaction::load($this->IDPaymentTransaction);		
			
		if( $PaymentTransaction != NULL )			
			return $PaymentTransaction->externId;		
		return '';
	 }
	
	public function getPriceATI(){
		// return $this->id;
		$invoice = \Business\Invoice_V1_complete::load($this->id);
		return $invoice->UnitPrice;
	}
	public function getPricePaid(){
		return $this->pricePaid;
	}
	
	public static function getPaymentProcessor($idPaymentTransaction){
		$PaymentTransaction = \Business\PaymentTransaction::load($idPaymentTransaction);		
		if( $PaymentTransaction != NULL )			
			return $PaymentTransaction->paymentProcessor;
		return '';
	}
	
	public function getPRN($idPaymentTransaction = 0){
		if($idPaymentTransaction != 0)
			$PaymentTransaction = \Business\PaymentTransaction::load($idPaymentTransaction);		
		else
			$PaymentTransaction = \Business\PaymentTransaction::load($this->IDPaymentTransaction);
			
		if( $PaymentTransaction != NULL )
			return $PaymentTransaction->PRN;
		return '';
	}
	
	public function getRefInterne(){
		
			return $this->ref2Transaction;	
			
	}
	
	public function getRefTransaction(){
		
			return $this->ref1Transaction;	
			
	}
	
	public function GetDeviseinformativecheque(){
		
		if( $this->deviseinformativecheque != NULL ){				
			return $this->deviseinformativecheque;		
		}else{	
			return $this->currency;
		}
		
	}
	
	public function GetDateRefund(){
				
			return $this->modificationDate;
		
	}
	
	
	public function GetPaymentProcessorRefunded(){  
	  
		$PaymentTransaction = \Business\PaymentTransaction::load($this->IDPaymentTransaction);	
		if( $PaymentTransaction != NULL ){
			
			$cmp = $PaymentTransaction->company;
			if(strpos($PaymentTransaction->paymentProcessor,'Internationnal') ||strpos($PaymentTransaction->paymentProcessor,'National'))
				$company = explode(' ',$cmp)[0];
			else
				$company = $cmp;
			$company = !empty($company)?' - '.$company:'';
			return $PaymentTransaction->paymentProcessor.$company;
		}
		return '';
	}
}
?>
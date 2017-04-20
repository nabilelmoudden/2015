<?php
namespace Business;
/**
 * Description of Invoice
 *
 * @author BENSAAD Jalal
 * @package Business.Invoice
 */
class PaymentTransaction extends \PaymentTransaction
{
	/**
	* PaymentTransaction Status
	*/
	/**
	* PaymentTransaction Status
	*/
	//const INVOICE_CADDIE		= 0;
	const INVOICE_IN_PROGRESS	= 0;
	const INVOICE_PAYED			= 1;
	const INVOICE_ERROR			= 2;
	const INVOICE_CANCEL		= 3;

	//const INVOICE_CADDIE		= 0;	const INVOICE_IN_PROGRESS	= 0;	const INVOICE_PAYED			= 1;	const INVOICE_ERROR			= 2;	const INVOICE_CANCEL		= 3;
	/**	* Refund Status	*/
	const INVOICE_REFUND_NOT_ASKED		= 0;
	const INVOICE_REFUND_IN_PROGRESS	= 11;
	const INVOICE_REFUNDED				= 12;

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
		return parent::loadByInternauteId($internauteId);
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

	public function searchChrono( $order = false, $pageSize = 50, $chrono = ''){
		$Provider = parent::searchChrono($chrono);

		if( $pageSize == false )
			$Provider->setPagination( false );
		else
			$Provider->pagination->pageSize = $pageSize;
		if( $order != false )
			$Provider->criteria->order = $order;

		return $Provider;

	}

	public function searchPaymentsByMB( $order = false, $pageSize = 50){
		$Provider = parent::searchPaymentsByMB();
		if( $pageSize == false )
			$Provider->setPagination( false );
		else
			$Provider->pagination->pageSize = $pageSize;
		if( $order != false )
			$Provider->criteria->order = $order;
		return $Provider;
	}

	/**
	 * Excute web form Update les infos clients EMV
	 * @return string|false Retour de la requete, false en cas de probleme
	 */
	Public function SendToEMV($type = 'UrlRefundDone'){
		$porteur = \Yii::app()->params['porteur'];
		if($type == 'UrlRefundDone'){
			$url_WebForm = $GLOBALS['UrlRefundDone'][$porteur];
		}elseif($type == 'UrlRefundReceived'){
			$url_WebForm = $GLOBALS['UrlRefundReceived'][$porteur];
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


	/**	 * Retourne le montant total de l'invoice	 * @return	float	Total	 */
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

					$desc[] = $this->RecordInvoice[$i]->Product->description.' ( Qty : '.$this->RecordInvoice[$i]->qty.' )';
			}

		return $desc;
	}



	/**
	 * Recherche les invoices avec le refundStatus = 11 OU 12
	 * @param int $pageSize	Nb de result par page
	 * @return \CActiveDataProvider	CActiveDataProvider
	 */

	public function searchRefundInvoice( $order = false, $emailUser = '',$paymentProcessor = '', $pageSize = 0 ){
		$Provider = $this->search( $order, 20 );
		$Provider->criteria->compare('status', array( self::INVOICE_REFUND_IN_PROGRESS, self::INVOICE_REFUNDED ) );
		return $Provider;
	}

	public function searchRefundInvoiceNoProgress( $order = false, $emailUser = '',$paymentProcessor = '', $pageSize = 0 ){

		$dateDebut = $_SESSION['dateDebut'] ;
		$dateFin   = $_SESSION['dateFin'];

		$Provider = $this->search( $order, 20 );
		$Provider->criteria->compare('status', array( self::INVOICE_REFUND_IN_PROGRESS, self::INVOICE_REFUNDED ) );
		$Provider->criteria->addCondition('status != 0');

		$Provider->criteria->addCondition(' DATE(dateCreation) >= "'.$dateDebut.'" AND DATE(dateCreation) <= DATE_ADD("'.$dateFin.'", INTERVAL 1 DAY)');

		return $Provider;
	}

	/**
	* Recherche les invoices check en attente / complete
	* @param  bool $pending	Seulement les invoices check en attente ( numCheck == NULL )
	* @return \CActiveDataProvider
	*/
	public function searchInvoiceCheck( $pending = true, $order = false, $pageSize = 0 , $email = '', $chrono = '', $f_name = '', $l_name = ''){

		$Provider = $this->search( $order, $pageSize);
		$Provider->criteria->compare('status', 0);
		$Provider->criteria->compare('paymentProcessor', 'PACNETCHECK_1');

		if($email !== ''){
			$Provider->criteria->addCondition(' email LIKE "%'.$email.'%" ');
		}
		if($chrono !== ''){
			$Provider->criteria->addCondition(' externId  LIKE "%'.$chrono.'%" ');
		}

		if($f_name !== ''){

			$user_v1 = new \Business\User_V1;
			$users = $user_v1->search( false, 20000, '', '', '', $f_name);

			if(!empty($users->data)){

				$ids = array();
				foreach($users->data as $user)
					$ids[] = $user->id;

				if(!empty($ids))
					$Provider->criteria->addInCondition('internauteID', $ids);


			}else{

				$Provider->criteria->compare('externId', $f_name);
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
					$Provider->criteria->addInCondition('internauteID', $ids);


			}else{

				$Provider->criteria->compare('externId', $l_name);
			}
		}
		//print_r($Provider);exit;
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
			case self::INVOICE_REFUNDED :
				return 'Refunded';
		}
		return false;
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

	static public function loadByMail( $email ){
		return self::model()->findAllByAttributes( array( 'email' => $email, 'paymentProcessor' => 'MB_1' ) );
	}

	 /**
	 * Retourne le montant total de l'invoice
	 * @return	float	Total
	 */


	public function getUserByMail(){
			$user = \Business\User_V1::loadByEmail($this->emailUser);
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

	public function getMailById(){

		return $this->emailUser;

	}

	public function getChronoById(){
		return $this->chrono;
	}

	/*public function getDistinctPRNs(){
		$prns = self::model()->findAll(array(
			'select' => 'PRN, paymentProcessor,count(DISTINCT(i.IDPaymentTransaction)) AS Nbr',
			'group' => 'PRN, paymentProcessor',
			'distinct' => true,
			'join'	=> ' INNER JOIN invoice i ON i.IDPaymentTransaction = t.ID ',
			'condition' => '  (t.RefundStatus = 11 || i.RefundStatus = 11) AND i.RefundStatus != 12 AND t.RefundStatus != 12 AND t.dateCreation >=  "2011-03-01 00:00:01" AND i.IDPaymentTransaction IS NOT NULL AND t.email != "" AND i.CreationDate > "2011-03-01 00:00:01" '
		));
		$arr = array();
		foreach($prns as $p){
			if($p['PRN'] == '') continue;
			$arr[] =  array('prn' => $p['PRN'], 'PP' => $p['paymentProcessor'], 'Nbr' => $p['Nbr']);
		}
		return $arr;
	}*/

	public function getDistinctPRNs(){
		$prns = self::model()->findAll(array(
				'select' => 'PRN, paymentProcessor, company, t.Site, count(DISTINCT(i.IDPaymentTransaction)) AS Nbr',
				'group' => 'PRN, paymentProcessor, t.Site',
				'distinct' => true,
				'join'	=> ' INNER JOIN invoice i ON i.IDPaymentTransaction = t.ID ',
				'condition' => '  (t.RefundStatus = 11 || i.RefundStatus = 11) AND i.RefundStatus != 12 AND t.RefundStatus != 12 AND t.dateCreation >=  "2011-03-01 00:00:01" AND i.IDPaymentTransaction IS NOT NULL AND t.email != "" AND i.CreationDate > "2011-03-01 00:00:01" '
		));
		$arr = array();
		foreach($prns as $p){
			$company = '';
			if(isset($p['company']))
				$company = $p['company'];
			if($p['PRN'] == '') continue;
			$arr[] =  array('prn' => $p['PRN'], 'PP' => $p['paymentProcessor'], 'Nbr' => $p['Nbr'], 'site' => $p['Site'], 'version' => 'V1', 'company' => $company);
		}
		return $arr;
	}

	public function getInvoicesByPRN($prn, $pp, $type){

		if($type == 'Check')
			$cnd = 'AND (t.paymentProcessor LIKE "%CHECK%" OR  t.paymentProcessor LIKE "%BOLETOS%" )';
		else
			$cnd = 'AND (t.paymentProcessor NOT LIKE "%CHECK%" and t.paymentProcessor NOT LIKE "%BOLETOS%" )';

		//$condition = ($type == 'all') ? ' ' : ' AND t.paymentProcessor = "'.$pp.'" AND t.PRN = "'.$prn.'" ';
		$condition = ($type == 'all') ? ' AND t.PRN = "'.$prn.'" ' : $cnd.' AND t.PRN = "'.$prn.'" ';
		// PACNETCHECK_1|PACNETCHECK_2|PACNETBOLETOS_1|PACNETBOLETOS_2

		$invoices = self::model()->findAll(array(
			'select' => ' ( CASE t.paymentProcessor WHEN "PACNETCHECK_1" THEN inv.PricePaid ELSE inv.UnitPrice END ) AS unitPrice , inv.ID AS id,
							t.currency AS currency, inv.Ref1Transaction AS Ref1Transaction, inv.Ref2Transaction AS Ref2Transaction, inv.IDPaymentTransaction  as id, t.paymentProcessor, t.PRN',
			'join'	=> ' RIGHT JOIN invoice inv ON inv.IDPaymentTransaction = t.ID ',
			'condition' => '(t.RefundStatus = 11 || inv.RefundStatus = 11) AND inv.RefundStatus != 12 AND t.RefundStatus != 12 AND t.dateCreation >=  "2011-03-01 00:00:01"
							AND inv.IDPaymentTransaction IS NOT NULL  AND inv.CreationDate > "2011-03-01 00:00:01" '.$condition,
			'group' => ' inv.IDPaymentTransaction ',
			'order' => ' inv.ModificationDate '
		));
		$arr = array();
		foreach($invoices as $invoice){
			$invoice_V1 = \Business\Invoice_V1_complete::loadByIdPaymentTransaction($invoice['id']);
			$arr[] = array(
				'rpn'	=>	$invoice['PRN'],
				'paymentProcessor'	=>	$invoice['paymentProcessor'],
				'pricePaid'	=>	$invoice['unitPrice'],
				'currency'	=>	$invoice['currency'],
				'Ref1Transaction'	=>	$invoice_V1->Ref1Transaction,
			);
		}

		return $arr;
	}

	/**
	 * @author YSF
	 * @method getInvoicesByPRNandSite
	 * @param  $prn
	 * @param  $site
	 * @param  $pp
	 * @param  $type
	 */
	 public function getInvoicesByPRNAndSite($prn, $pp, $site, $type){

		if($type == 'Check')
			$cnd = 'AND (t.paymentProcessor LIKE "%CHECK%" OR  t.paymentProcessor LIKE "%BOLETOS%" )';
		else
			$cnd = 'AND (t.paymentProcessor NOT LIKE "%CHECK%" and t.paymentProcessor NOT LIKE "%BOLETOS%" )';

		//$condition = ($type == 'all') ? ' ' : ' AND t.paymentProcessor = "'.$pp.'" AND t.PRN = "'.$prn.'" ';
		$condition = ($type == 'all') ? ' AND t.PRN = "'.$prn.'" AND inv.Site ="'.$site.'" ' : $cnd.' AND t.PRN = "'.$prn.'" AND inv.Site ="'.$site.'"';
		// PACNETCHECK_1|PACNETCHECK_2|PACNETBOLETOS_1|PACNETBOLETOS_2

		$invoices = self::model()->findAll(array(
			'select' => ' ( CASE t.paymentProcessor WHEN "PACNETCHECK_1" THEN inv.PricePaid ELSE inv.UnitPrice END ) AS unitPrice , inv.ID AS id,
							t.currency AS currency, inv.Ref1Transaction AS Ref1Transaction, inv.Ref2Transaction AS Ref2Transaction, inv.IDPaymentTransaction  as id, t.paymentProcessor, t.PRN',
			'join'	=> ' RIGHT JOIN invoice inv ON inv.IDPaymentTransaction = t.ID ',
			'condition' => '(t.RefundStatus = 11 || inv.RefundStatus = 11) AND inv.RefundStatus != 12 AND t.RefundStatus != 12 AND t.dateCreation >=  "2011-03-01 00:00:01"
							AND inv.IDPaymentTransaction IS NOT NULL  AND inv.CreationDate > "2011-03-01 00:00:01" '.$condition,
			'group' => ' inv.IDPaymentTransaction ',
			'order' => ' inv.ModificationDate '
		));

		$arr = array();
		foreach($invoices as $invoice){
			$invoice_V1 = \Business\Invoice_V1_complete::loadByIdPaymentTransaction($invoice['id']);
			$arr[] = array(
				'rpn'				=>	$invoice['PRN'],
				'paymentProcessor'	=>	$invoice['paymentProcessor'],
				'pricePaid'			=>	$invoice['unitPrice'],
				'currency'			=>	$invoice['currency'],
				'Ref1Transaction'	=>	$invoice_V1->Ref1Transaction,
			);
		}

		return $arr;
	}

	public function GetDeviseinformativecheque(){


		return $this->currency;


	}
	/*public function GetPaymentProcessorRefunded(){
			return $this->paymentProcessor;
	 }*/
	public function GetPaymentProcessorRefunded(){
		$cmp = $this->company;
		$company = (strpos($this->paymentProcessor,'Internationnal') ||strpos($this->paymentProcessor,'National'))?explode(' ',$cmp)[0]:$cmp;
		$company = !empty($company)?' - '.$company:'';
		return $this->paymentProcessor.$company;
	}
	
	public function GetDateRefund(){

			return $this->ModificationDate;

	}

	/**
	 * load Invoices based on email and invoice Type
	 *
	 * @param $email
	 * @param $invoices Default : INVOICE_PAYED
	 * @param $orderBy (Default : DESC)
	 * @param $condition : 1 => last 4 month , 2 => before 4 month
	 * @return Array of \Invoice objects List 
	 **/
	public static function getByEmail($email, $invoice = self::INVOICE_PAYED, $orderBy = 'DESC',$condition=false)
	{
		
		$requette = 'RefDiscount not in (1504, 1503)';
		if ($condition == 1)
		{
			$requette .= "AND dateCreation between cast(ADDDATE( CURRENT_DATE, INTERVAL -4 MONTH ) as datetime) and cast(CURRENT_DATE as datetime) ";
		}
		elseif ($condition == 2)
		{
			$requette .= 'AND dateCreation between cast(ADDDATE( CURRENT_DATE, INTERVAL -8 MONTH ) as datetime) and cast(ADDDATE( CURRENT_DATE, INTERVAL -4 MONTH ) as datetime)';
		}
		return self::model ()->findAllByAttributes ( [ 
				'email' => $email,
				'status' => $invoice 
		], array (
				'order' => "dateCreation $orderBy",
				'condition' => $requette 
		) );
	} 

	public function getStcName() {
	
		$porteur = \Yii::app()->params['porteur'];
		$type = explode('_',$porteur);
		$count = count($type) -1 ;
		$type = $type[$count] ;
	
	
		$ref = "'%voypayante' " ;
	
		if($type == 'rinalda' )   return $ref    ;
			
		elseif($type == 'rmay' || $type == 'ml' ) return $ref."OR productRef LIKE '%inter%' OR productRef LIKE '%asil%'"  ;
	
		else  return $ref."OR productRef LIKE '%conttele%'" ; 	 // Laetizia , Aasha , Rucker
	}
	
	
	/**
	 * @author Anass Hilama
	 * @desc Retourne les paiement effectue de la chaine STC achete hier
	 */


	public function getPurchasedStcOfBeforOneDay() {
	
		$dateNow = new \DateTime();
		$criteria = new \CDbCriteria;
		$ref = self::getStcName() ;
		$criteria->condition = "dateCreation LIKE '%" .  date("Y-m-d", strtotime("- 1 day"))  . "%' AND ( productRef LIKE".$ref.") AND status = " . self::INVOICE_PAYED ." AND refDiscount NOT IN (1504 , 1503)";
		return self::model()->findAll( $criteria );
	
	}
	
	
	/////////////////////////////////////////////////////// Moteur de test ///////////////////////////////////////////////////////////////////
	
	/**
	 * @author Soufiane BALKAID
	 * @param string $email
	 * @param date $date
	 */
	public static function getPurchasedSTCByInterval($date1,$date2,$email)
	{
	
		$criteria = new \CDbCriteria;
		$criteria->condition = "dateCreation between  '" .  $date1  . " 00:00:00' and '".  $date2  . " 23:59:59' AND  email like '".$email."' AND status = " . self::INVOICE_PAYED ." AND refDiscount NOT IN (1504 , 1503)";
		
		return self::model()->findAll( $criteria );
	}
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
}

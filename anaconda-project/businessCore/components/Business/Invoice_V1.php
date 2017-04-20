<?php
namespace Business;
/**
 * Description of Invoice
 *
 * @author JulienL
 * @package Business.Invoice
 */

class Invoice_V1 extends \Invoice_V1{
	/**
	* Invoice Status
	*/

	const INVOICE_CADDIE		= 0;
	const INVOICE_IN_PROGRESS	= 1;
	const INVOICE_PAYED			= 2;
	const INVOICE_ERROR			= 3;
	const INVOICE_CANCEL		= 4;


	/**
	* Refund Status
	*/

	const INVOICE_REFUND_NOT_ASKED		= 0;
	const INVOICE_REFUND_IN_PROGRESS	= 11;
	const INVOICE_REFUNDED				= 12;

	public function init(){
		parent::init();
	}





	/**
	 * @return array relational rules.
	 * Surcharge pour que la relation soit sur la classe Business
	 */



	public function relations(){
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'User_V1' => array(self::BELONGS_TO, '\Business\User_V1', array( 'IDInternaute' => 'id' ) )
		);
	}





	/**
	 * Rajoute une fonctionnalité specifique a la classe
	 * @return array
	 */

	public function behaviors(){
		return array_merge(
					parent::behaviors(),
					array( 'ActiveRecordBehavior' => 'application.components.ActiveRecordBehavior' )
				);
	}



	/**
	 * Recherche
	 * @param string $order Ordre
	 * @param int $pageSize	Nb de result par page
	 * @return CActiveDataProvider	CActiveDataProvider
	 */



	public function search( $order = false, $pageSize = 1000, $IDInternaute = 0){
		$Provider = parent::search();
		if( $pageSize == false )
			$Provider->setPagination( false );
		else
			$Provider->pagination->pageSize = $pageSize;
		if($IDInternaute !== 0)
			$Provider->criteria->compare('IDInternaute', $IDInternaute, true);
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

	/**
	 * Finalise l'invoice
	 * @return \Business\ConfigPaymentProcessor
	 */

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



	/**
	 * Permet de mettre a jour l'utilisateur en DB par rapport a BDC
	 * @param \Bdc $bdc Bon de commande
	 * @return bool	true / false
	 */

	private function updateUserWithBdcInfo( $Bdc , $email){
		if( !is_object($this->User)){
			$User              = new \Business\User_V1();
			$map	= array(
								'civility' => 'Civility',
								'firstName' => 'First Name',
								'lastName' => 'Last Name',
								'birthday' => 'Birthday',
								'email' => 'Email'
							);
			foreach( $map as $key => $value ){
				if(!empty($value)){
					if( $key == 'birthday' ){
						$User->birthday = $Bdc->getBirthday( \Yii::app()->params['dbDateTime'] );
					}else{
					    $User->$key = $Bdc->$key;
					}
				}
			}
		    $this->User_V1 = $User;
			return true;
		}else{
		  foreach( $Bdc as $k => $v ){
				if( !empty($v) && isset($this->User_V1->$k) && $this->User_V1->$k != $v ){
					if( $k == 'birthday' )
						$this->User_V1->birthday = $Bdc->getBirthday( \Yii::app()->params['dbDateTime'] );
					else
						$this->User_V1->$k = $v;
				}
			}
		   return true;
		}
	}

	public function getMailById(){
		$user = \Business\User_V1::load($this->IDInternaute);
		if( $user != NULL )
			return $user->Email;
		return '';
	}

	public function getId(){
		return $this->ID;
	}





	/**

	 * Envoi une requete a EMV

	 * @param string $type	Voir \Business\RouterEMV::$tabType

	 * @return boolean

	 * @throws \EsoterException

	 */



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


	/**
	 * Retourne les refs des produits de l'invoice
	 * @return array	Refs Products
	 */
	public function getProductsRef(){
		if( count($this->RecordInvoice) <= 0 )
			return false;
		$ret = array();
		for( $i=0; $i<count($this->RecordInvoice); $i++ )
			$ret[] = $this->RecordInvoice[$i]->refProduct;
		return $ret;

	}


   /**
	 * Retourne l'Url de produit de l'invoice
	 * @return string Url Products
	 */
	public function getUrlProducts($invoice){


		$Myurl =array();
		$porteur = \Yii::app()->params['porteur'];
		if($porteur!='en_aasha' && $porteur!='en_alisha'){
			$ConfDNS      = \Business\Config::loadByKeyAndSite( 'DNS' );
		}else{
			$ConfDNS      = \Business\Config::loadByKey( 'DNS' );
		}

		$porteur      = \Yii::app()->params['porteur'];
		$j =1;
		for( $i=0; $i<count($invoice); $i++ ){

			$date = date_create($invoice[$i]->CreationDate);

			$user = \Business\User_V1::load($invoice[$i]->IDInternaute);
			$date_naissance = date_create($user->Birthday);

			if($invoice[$i]->RefProduct=="en_alisha_voygratuit"){
				$WebSiteProductCode = "vgl";
			}else{
				$Product  = \Business\Product_V1::loadByRef($invoice[$i]->RefProduct);
				
				if(is_object($Product)){
					$WebSiteProductCode = $Product->WebSiteProductCode;
				}else{
					$WebSiteProductCode = '';
				}
			}

			if($WebSiteProductCode=='vgl'){
				$WebSiteProductCode='vgldv';
				$param_url='bs=1&tr=1&gp=1';
			}else{
				$PaymentTransaction = \Business\PaymentTransaction::load($invoice[$i]->IDPaymentTransaction);
			    if(is_object($PaymentTransaction)){
			   		 $param_url='bs='.$PaymentTransaction->refBatchSelling.'&tr='.$PaymentTransaction->refDiscount.'&gp='.$PaymentTransaction->refPricingGrid;
				}else{
					$param_url='bs=1&tr=1&gp=1';
				}

			}
			$prod_chain = '';
			if ( (((strpos($invoice[$i]->RefProduct, 'rmay') == true) or ($porteur == 'pt_rmay')) && ((strlen($invoice[$i]->RefProduct) == 12) or (strlen($invoice[$i]->RefProduct) == 14))) or (($invoice[$i]->RefProduct == 'br_rmay_pta' ) or ($invoice[$i]->RefProduct == 'br_rmay_ptact' )) )
			{
				$prod_chain = '&pdt=ch';
			}else{
				if ( (($porteur == 'fr_laetizia') or ($porteur == 'de_theodor') or ($porteur == 'es_laetizia')) && ((strlen($invoice[$i]->RefProduct) == 11) or (strlen($invoice[$i]->RefProduct) == 13)) )
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
			}

		     $Myurl[$j] = $ConfDNS->value.'/'.$GLOBALS['porteurMap'][$porteur].'/index.php?c='.$WebSiteProductCode.'&'.$param_url.'&p='.$user->Firstname.'&n='.$user->Lastname.'&d='.date_format($date_naissance, 'd/m/Y').'&m='.$user->Email.'&x='.$user->Civility.'&de='.date_format($date, 'm/d/Y').'&site='.$invoice[0]->Site.'&sd='.date_format($date, 'm/d/Y').''.$prod_chain;
			 $j = $j + 1;
		}
		return $Myurl;
	}


	/**
	 * get The Product url
	 *
	 **/
	public function getUrlProduct()
	{
		$invoice =  $this;
		$ConfDNS      = \Business\Config::loadByKey( 'DNS' );
		$porteur      = \Yii::app()->params['porteur'];
		$date = date_create($invoice->CreationDate);
		$user = \Business\User_V1::load($invoice->IDInternaute);
		$date_naissance = date_create($user->Birthday);

		if($invoice->RefProduct == "en_alisha_voygratuit"){
			$WebSiteProductCode = "vgl";
		} else {
			$Product  = \Business\Product_V1::loadByRef($invoice->RefProduct);
			$WebSiteProductCode = $Product->WebSiteProductCode;
		}

		if($WebSiteProductCode == 'vgl'){
			$WebSiteProductCode = 'vgldv';
			$param_url='bs=1&tr=1&gp=1';
		} else {
			$PaymentTransaction = \Business\PaymentTransaction::load($invoice->IDPaymentTransaction);
			if(is_object($PaymentTransaction)){
				 $param_url='bs='.$PaymentTransaction->refBatchSelling.'&tr='.$PaymentTransaction->refDiscount.'&gp='.$PaymentTransaction->refPricingGrid;
			}else{
				$param_url='bs=1&tr=1&gp=1';
			}
		}

		$prod_chain = '';

		if ( strpos($invoice->RefProduct, 'rmay') == true && ((strlen($invoice->RefProduct) == 12) or (strlen($invoice->RefProduct) == 14)) )
		{
			$prod_chain = '&pdt=ch';
		} else {
			if ( (($porteur == 'fr_laetizia') or ($porteur == 'de_theodor'))  && ((strlen($invoice->RefProduct) == 11) or (strlen($invoice->RefProduct) == 13)) ){
				$prod_chain = '&pdt=ch';
			} else {
				if ( $porteur == 'fr_rinalda' && ((strlen($invoice->RefProduct) == 8) or (strlen($invoice->RefProduct) == 10)) ) {
					$prod_chain = '&pdt=ch';

				} else {
					$prod_chain = '';
				}
			}
		}

		$url = $ConfDNS->value.'/'.$GLOBALS['porteurMap'][$porteur].'/index.php?c='.$WebSiteProductCode.'&'.$param_url.'&p='.$user->Lastname.'&n='.$user->Firstname.'&d='.date_format($date_naissance, 'd/m/Y').'&m='.$user->Email.'&x='.$user->Civility.'&de='.date_format($date, 'm/d/Y').'&site='.$invoice->Site.'&sd='.date_format($date, 'm/d/Y').''.$prod_chain;

		return $url;
	}



	/**
	 * Retourne le montant total de l'invoice
	 * @return	float	Total
	 */
	public function getTotalInvoice(){
		return $this->UnitPrice * $this->Qty;
	}







	/**

	 * Retourne un tableau contenant la description de chaque produit de la commande

	 * @return array Tableau de description

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



	public function searchRefundInvoice( $order = false, $emailUser = '', $pageSize = 0, $type = ""){
		$Provider = $this->search($order, 2000);
		if($type == 'Check')
			$Provider->criteria->addCondition(' paymentProcessor like "%Check%"');
		if($type == 'CB')
			$Provider->criteria->addCondition(' paymentProcessor not like "%Check%"');

		$Provider->criteria->compare('refundStatus', array( self::INVOICE_REFUND_IN_PROGRESS, self::INVOICE_REFUNDED ) );
		if($emailUser !== ''){
			$Provider->criteria->addCondition(' emailUser LIKE "%'.$emailUser.'%" ');
		}
		return $Provider;
	}

	/**
	 * Recherche les invoices check en attente / complete
	 * @param	bool	$pending	Seulement les invoices check en attente ( numCheck == NULL )
	 * @return \CActiveDataProvider
	 */

	public function searchInvoiceCheck( $pending = true, $order = false, $pageSize = 0, $email = '', $currency = '' ){

		$Provider	= $this->searchRefundInvoice( $order, $pageSize );
		$Provider->criteria->addCondition('numCheck IS NOT NULL' );
		$Provider->criteria->compare( 'IDInternaute', $idInternaute, true, 'AND' );

		if($email !== ''){
			$Provider->criteria->compare('email', $email);
		}
		if($currency !== ''){
			$Provider->criteria->compare('currency', $currency);
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

	static public function loadByInternauteId2( $id ){
		return self::model()->findByAttributes( array( 'IDInternaute' => $id ) );
	}


	/**
	 * Retourne le Nom et le Prenom de client @internaute
	 * @return	chaine
	 */


	public function getUserByMail(){
		/*
				traitement
		*/
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

	/**
	 * load Invoices based on email and invoice Type
	 *
	 * @param $email
	 * @param $invoices Default : INVOICE_PAYED
	 * @param $orderBy (Default : DESC)
	 * @return Array of \Invoice objects List
	 **/
	public static function getByEmail($IDInternaute, $invoice = self::INVOICE_PAYED, $orderBy = 'DESC')
	{
		// INVOICE_IN_PROGRESS
		// INVOICE_PAYED
		return self::model()->findAllByAttributes([
			'IDInternaute' => $IDInternaute,
			'InvoiceStatus' => $invoice,
		], array(
			'order' => "creationDate $orderBy",
			'condition' => 'RefDiscount not in (1504, 1503) '
		));
	}
	
	/**
	 * @author Saad HDIDOU
	 * @desc: recuperer les clients qui ont achete le produit VP
	 */
	static public function getPayedVPV1()
	{
		$date = new \DateTime();
		$date->sub( new \DateInterval('P1D') );
		$criteria = new \CDbCriteria;
		$criteria->alias = 'Invoice_V1';
		$criteria->join='LEFT JOIN internaute Internaute ON Internaute.id=Invoice_V1.IDInternaute LEFT JOIN V2_user User ON User.email=Internaute.Email';
		$criteria->condition = 'Invoice_V1.CreationDate LIKE \'%'.$date->format('Y-m-d').'%\' AND Invoice_V1.RefProduct LIKE \'%voypayant%\' AND Invoice_V1.InvoiceStatus=2 AND User.intialDate IS NULL AND (User.visibleDesinscrire IS NULL OR User.visibleDesinscrire = 0)';
		$list = self::model()->findAll($criteria);
		return array_map(function($element) { return $element->User_V1->Email;}, $list);
	}
	/***************************************************** get Nbr Purshased Old Anaconda By Email *************************************************/
	/**
	 * @author Soufiane balkaid
	 * @desc Retourne le nombre de fid Anaconda ayant ete acheter avant le cycle Anaconda
	 * @param string $email
	 */
	public static function getNbrPurshasedOldAnacondaByEmail($email)
	{
		$criteria = new \CDbCriteria;
		$criteria->alias = 'Invoice_V1';
		$criteria->join='LEFT JOIN internaute Internaute ON Internaute.id=Invoice_V1.IDInternaute LEFT JOIN V2_user User ON User.email=Internaute.Email';
		$criteria->condition="Internaute.Email = '".$email."' AND invoiceStatus = ".self::INVOICE_PAYED." AND DATEDIFF( NOW(),Invoice_V1.CreationDate) < 365";
		$anacondaOldRefs=\Yii::app()->params['anaconda_old_refs'];
		$criteria->addInCondition('Invoice_V1.RefProduct',$anacondaOldRefs,'AND');
		return count(self::model()->findAll( $criteria ));
	}
	
	/**************************************************  / get Nbr Purshased Old Anaconda By Email **************************************************/
	
	/**************************************************  / Reactivation  **************************************************/
	/**
	 * @author Anas HILAMA
	 * @param string $emaiL
	 * @desc date de validation d'achat
	 *
	 */
	static public function LoadByEmailPayedInv($email) {
	
		$BornSup = date("Y-m-d H:i:s") ;
		$BornInf = date("Y-m-d H:i:s", strtotime("- 1 day")) ;
		$criteria = new \CDbCriteria;
		$criteria->alias = "Invoice_V1" ;
		$criteria->join = "INNER JOIN internaute Internaute ON Internaute.id=Invoice_V1.IDInternaute " ; 		
		$criteria->condition = "Internaute.Email ='".$email."' AND Invoice_V1.invoiceStatus = 2 AND ( Invoice_V1.ModificationDate BETWEEN '".$BornInf."' AND'".$BornSup."')"  ;
		$list =  self::model()->findAll($criteria);
		return array_map(function($element) { return $element['ModificationDate'];}, $list);
		
	}
	
	/**************************************************  / FIN Reactivation  **************************************************/
	

}	
?>

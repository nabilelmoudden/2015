<?php

namespace Business;

/**
 * Description of PaymentProcessorType
 *
 * @author JulienL
 * @package Business.PaymentProcessor
 */
class PaymentProcessorType extends \Paymentprocessortype
{
	/**
	 * Defini les types possibles :
	 */
	const TYPE_FREE		= 0;
	const TYPE_CB		= 1;
	const TYPE_CHEQUE	= 2;
	const TYPE_DINEROMAIL	= 3;
	const TYPE_MB		= 4;
	const TYPE_PAYPAL				= 5;
	const TYPE_SOFORT				= 6;
	const TYPE_CBNATIONAL			= 7;
	const TYPE_CBINTERNATIONAL		= 8;
	const TYPE_BOLETOS	= 9;
	const TYPE_AMEX	= 10;

	/**
	 * Defini les parametres
	 */
	const PARAM_PREFIX			= 'prefix';
	const PARAM_MERCANTID		= 'mercantId';
	const PARAM_COMPANY			= 'company';
	const PARAM_SUBMERCANTID	= 'subMercantId';
	const PARAM_LANGPP			= 'langPP';
	const PARAM_DATAINTEGRITY	= 'dataIntegrityCode';
	const PARAM_LABEL			= 'labelPaymentProcessorPage';
	const PARAM_SUBMITTER		= 'submitter';
	
	/**
	 * Tableau definissant les processeurs de paiement gratuite
	 * @var array
	 */
	static public $typefree	= array(
		self::TYPE_FREE
	);

	/**
	 * Tableau definissant les processeurs de paiement synchrone
	 * @var array
	 */
	static public $typeSync	= array(
		self::TYPE_CB,self::TYPE_CBNATIONAL,self::TYPE_CBINTERNATIONAL,self::TYPE_PAYPAL,self::TYPE_SOFORT,self::TYPE_AMEX
	);

	/**
	 * Tableau definissant les processeurs de paiement asynchrone
	 * @var array
	 */
	static public $typeAsync = array(
		self::TYPE_CHEQUE,
		self::TYPE_DINEROMAIL,
		self::TYPE_MB,
		self::TYPE_BOLETOS
	);

	public function init()
	{
		parent::init();

		$this->onAfterFind	= array( $this, 'loadParam' );
		$this->onBeforeSave	= array( $this, 'saveParam' );
		$this->onAfterSave	= array( $this, 'loadParam' );
	}

	protected function loadParam()
	{
		if( !empty($this->param) )
			$this->param = json_decode ( $this->param );
	}

	protected function saveParam()
	{
		if( !empty($this->param) )
			$this->param = json_encode ( $this->param );
	}

	/**
	 * Recherche
	 * @param string $order Ordre
	 * @param int $pageSize	Nb de result par page
	 * @return CActiveDataProvider	CActiveDataProvider
	 */
	public function search( $order = false, $pageSize = 100 )
	{
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
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'PaymentProcessorSet' => array(self::MANY_MANY, '\Business\PaymentProcessorSet', $this->tableNamePrefix().'paymentprocessorsetpaymentprocessor(idPaymentProcessorType, idPaymentProcessorSet)'),
			'Site' => array(self::BELONGS_TO, '\Business\Site', 'idSite'),
		);
	}

	/**
	 * Test si le processeur est un processeur gratuit
	 * @return boolean
	 */
	public function isFreePayment()
	{
		return ( $this->type == self::TYPE_FREE );
	}

	/**
	 * Retourne les parametres du processeur de paiement
	 * @param string $name
	 * @return \StdClass
	 */
	public function getParam( $name )
	{
		//return ( is_object($this->param) && isset($this->param->$name) ) ? $this->param->$name : false;
		if($name == "langPP")
			return ( is_object($this->param) && isset($this->param->$name) ) ? $this->param->$name : "";
		else
			return ( is_object($this->param) && isset($this->param->$name) ) ? $this->param->$name : false;
	}

	/**
	 * Retourne le type du processeur
	 * @return string|boolean
	 */
	public function getHumanType()
	{
		switch( $this->type )
		{
			case self::TYPE_FREE :
				return 'Gratuit';
			case self::TYPE_CB :
				return 'CB';
			case self::TYPE_CBNATIONAL :
				return 'CB_NATIONAL';
			case self::TYPE_CBINTERNATIONAL :
				return 'CB_INTERNATIONAL';
			case self::TYPE_CHEQUE :
				return 'Cheque';
			case self::TYPE_BOLETOS :
				return 'Boletos';
			case self::TYPE_MB :
				return 'MultiBanco';
			case self::TYPE_DINEROMAIL :
				return 'DINEROMAIL';
			case self::TYPE_AMEX :
				return 'AMEX';
		}

		return false;
	}

	/**
	 * Retourne la position du processeur dans un set donné
	 * @param int $idPaymentProcessorSet
	 * @return boolean
	 */
	public function getPosition( $idPaymentProcessorSet )
	{
		if( !($PpSetPp = \Business\PaymentProcessorSetPaymentProcessor::load( $idPaymentProcessorSet, $this->id )) )
			return false;

		return $PpSetPp->position;
	}
	
	public function isAsync()
	{
		return in_array( $this->type, \Business\PaymentProcessorType::$typeAsync );
	}
	
	public function isSync()
	{
		return in_array( $this->type, \Business\PaymentProcessorType::$typeSync );
	}

	// *********************** STATIC *********************** //
	static function model($className=__CLASS__)
	{
        return parent::model($className);
    }

	/**
	 *
	 * @param int $id
	 * @return \Business\PaymentProcessorType
	 */
	static public function load( $id )
	{
		return self::model()->findByPk( $id );
	}

	/**
	 *
	 * @param string $name
	 * @return \Business\PaymentProcessorType
	 */
	static public function loadByRef( $ref )
	{
		return self::model()->findByAttributes( array( 'ref' => $ref ) );
	}
	
	/**
	 * Retourne un tableau des noms des payment processor par type
	 * @param int $type	Type of payment processor
	 * @return array	Array of name payment processor
	 */
	static public function getNamePaymentProcessorByType( $type, $addQuote = false )
	{
		$ret = array();
		$res = self::getPaymentProcessorByType( $type );

		foreach( $res as $P )
			$ret[] = ( $addQuote ) ? '"'.$P->name.'"' : $P->name;

		return $ret;
	}

	/**
	 * Retourne tous les paymentProcessor en fonction du type passé en argument
	 * @staticvar array $ppByType Tableau de paymentProcessor
	 * @param int $type Type de paymentProcessor
	 * @return array Tableau de paymentProcessor
	 */
	
	static public function getPaymentProcessorByType( $type )
	{
		static $ppByType = array();

		if( isset($ppByType[ $type ]) )
			return $ppByType[ $type ];

		$PP					= new self( 'search' );
		$PP->type			= $type;
		$ppByType[ $type ]	= $PP->search()->getData();
		return $ppByType[ $type ];
	}
	
	/*public function getDistinctPRNs(){
		$prns =  self::model()->findAll(array(
			'select' => 'param, ref,count(distinct(i.id)) AS  Nbr',
			'group' => 'param,ref',
			'distinct' => true,
			'join'	=> ' INNER JOIN V2_invoice i ON t.ref LIKE  i.paymentProcessor',
			'condition' => '  i.refundStatus =11'
		));
		$params = array();
		// if(!empty($prns)){
			foreach($prns as $prn){
				if(!empty($prn['param'])){
					if($prn['param']->mercantId == '') $prn['param']->mercantId = 0;
					$params[] = array('prn' => $prn['param']->mercantId, 'PP' => $prn['ref'], 'Nbr' => $prn['Nbr']);
				}
			}
		// }
		return $params;
	}*/
	
	public function getDistinctPRNs(){
		$prns =  self::model()->findAll(array(
				'select' => 'param, ref,s.code,count(distinct(i.id)) AS  Nbr',
				'group' => 'param,ref',
				'distinct' => true,
				'join'	=> ' INNER JOIN V2_invoice i ON t.ref LIKE  i.paymentProcessor LEFT JOIN V2_site s on t.idSite = s.id',
				'condition' => '  i.refundStatus =11 AND i.invoiceStatus =2 '
		));
		$params = array();
	
		foreach($prns as $prn){
			if(!empty($prn['param'])){
				$company = '';
				if(isset($prn['param']->company))
					$company = $prn['param']->company;
				if($prn['param']->mercantId == '') $prn['param']->mercantId = 0;
				$params[] = array('prn' => $prn['param']->mercantId, 'PP' => $prn['ref'], 'Nbr' => $prn['Nbr'], 'site' => $prn['code'], 'version' => 'V2', 'company' => $company);
			}
		}

		return $params;
	}
	
	public function getInvoicesByPRN($prn, $pp, $type){
		//$condition = $prn == 0 ? ' ' : ' AND `param` like "%'.$prn.'%"  ';
		if($type == 'Check')
			$condition = $prn == 0 ? ' ' : ' AND `param` like "%'.$prn.'%" AND i.paymentProcessor like  "%'.$type.'%"';
		else{
			if($type == 'CB' && $pp =='Check')
		 	   $condition = ' AND `param` like "%'.$prn.'%" AND i.paymentProcessor like  "%'.$type.'%"';
			 else   
			   $condition = $prn == 0 ? ' ' : ' AND `param` like "%'.$prn.'%" AND i.paymentProcessor Not like  "%'.$type.'%"'; 
		}
		$invoices =  self::model()->findAll( array (
			'select' => ' i.id',
			'join'	=> ' INNER JOIN V2_invoice i ON t.ref = i.paymentProcessor',
			'condition'  =>  ' i.refundStatus = 11 AND i.paymentProcessor like  "%'.$pp.'%" '.$condition
		));
		$invoicesId = $arr = array();
		foreach($invoices as $invoice){
			$invoicesId[] = $invoice['id'];
		}
		
		foreach($invoicesId as $invoiceId){
			$invoice = \Business\Invoice::load($invoiceId);
			$arr[] = array(
				'rpn'	=>	$prn,
				'paymentProcessor'	=>	$invoice['paymentProcessor'],
				'pricePaid'	=>	$invoice->getPriceATI(),
				'currency'	=>	$invoice['currency'],
				'Ref1Transaction'	=>	$invoice['ref1Transaction']
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
		
		/*echo $pp;
		if(strpos("Check_Canada",'Check') === false)
		die('nn');
		else
		die('oui');
		print_r(strpos("Check_Canada",'Check'));exit;	*/	
		if($type == 'Check')
			$condition = $prn == 0 ? ' ' : ' AND `param` like "%'.$prn.'%" AND i.paymentProcessor like  "%'.$type.'%" AND i.codeSite="'.$site.'"';
		else{
			/*if($type == 'CB' && strpos($pp,'Check') == true)
			{
		 	   $condition = ' AND `param` like "%'.$prn.'%" AND i.paymentProcessor like  "%'.$type.'%"';
			   $pp = 'Check';
			}else   
			   $condition = $prn == 0 ? ' ' : ' AND `param` like "%'.$prn.'%" AND i.paymentProcessor Not like  "%'.$type.'%"';*/
			   
			if($type != 'CB' || strpos($pp,'Check') === false)
			{
		 	   $condition = $prn == 0 ? ' ' : ' AND `param` like "%'.$prn.'%" AND i.paymentProcessor Not like  "%'.$type.'%" AND i.codeSite="'.$site.'"'; 
			}else {
				$condition = ' AND `param` like "%'.$prn.'%" AND i.paymentProcessor like  "%'.$type.'%" AND i.codeSite="'.$site.'"';
			    $pp = 'Check';
			}
			   
		}
		
		$invoices =  self::model()->findAll( array (
			'select' => ' i.id',
			'join'	=> ' INNER JOIN V2_invoice i ON t.ref = i.paymentProcessor',
			'condition'  =>  ' i.refundStatus = 11 AND i.paymentProcessor like  "%'.$pp.'%" '.$condition
		));
		$invoicesId = $arr = array();
		foreach($invoices as $invoice){
			$invoicesId[] = $invoice['id'];
		}
		
		foreach($invoicesId as $invoiceId){
			$invoice = \Business\Invoice::load($invoiceId);
			$arr[] = array(
				'rpn'	=>	$prn,
				'paymentProcessor'	=>	$invoice['paymentProcessor'],
				'pricePaid'	=>	$invoice->getPriceATI(),
				'currency'	=>	$invoice['currency'],
				'Ref1Transaction'	=>	$invoice['ref1Transaction']
			);
		}
		return $arr;
	}
}
?>
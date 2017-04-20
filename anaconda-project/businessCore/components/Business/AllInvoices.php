<?php 

namespace Business;

class AllInvoices extends \AllInvoices //AbstractProduct  //AbstractProduct

    //implements Interface_Camp   
	
{ 
	public $id;	
	public $emailUser;	
	public $creationDate;	
	public $refInterne;	
	public $Datecreation;	
	public $currency;
	public $chrono;
	public $numCheck;	
	public $pricePaid;	
	public $version;
	
	
	public function init(){
		parent::search();
	}
	
	
	/**	 * Decode les valeurs additionnels apres recuperation en DB	 * @return boolean	 */
	public function setInvoice_V1(Invoice_V1 $invoice_V1)
	{        
	
		$this->invoice_V1 = $invoice_V1;   
	
	} 
	
	 
    public function getInvoice_V1(){
        return $this->Invoice_V1;
    }  
	
	public function setInvoice(Invoice $invoice){
		
    	$this->invoice = $invoice;
    }
	
    public function getInvoice(){
		
    	return $this->invoice;
    }	
	
	public function search( $order = false, $pageSize = 100, $emailUser = ''){
		$Provider = parent::search($emailUser);
		if( $pageSize == false )
			$Provider->setPagination( false );
		else
			$Provider->pagination->pageSize = $Provider->pagination->pageSize;			
		if( $order != false )
			$Provider->criteria->order = $order;	
		return $Provider;	
	}   

	public function searchNoProgress( $order = false, $pageSize = 100, $emailUser = ''){
		$Provider = parent::searchNoProgress($emailUser);
		if( $pageSize == false )
			$Provider->setPagination( false );
		else
			$Provider->pagination->pageSize = $Provider->pagination->pageSize;
		if( $order != false )
			$Provider->criteria->order = $order;
		return $Provider;
	}
	
}
?>  
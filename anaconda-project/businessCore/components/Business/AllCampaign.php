<?php  
namespace Business;
class AllCampaign extends \AllCampaign //AbstractProduct  //AbstractProduct 
    //implements Interface_Camp
{
	
	public $produit_V1;
	public $campaign;
	
	public $id;
	public $version;
	public $label;
	public $ref;
	public $type;
	
	public function init()
	{
		parent::init();
	}
	
	/**
	 * Decode les valeurs additionnels apres recuperation en DB
	 * @return boolean
	 */
 public function setProduct_V1(Product_V1 $produit_V1)
    {
        $this->produit_V1 = $produit_V1;
    }
    
    public function getProduct_V1()
    {
        return $this->produit_V1;
    }
    
    public function setCampaign(Campaign $campaign)
    {
    	$this->campaign = $campaign;
    }
    
    public function getCampaign()
    {
    	return $this->campaign;
    }
    
    public function getAllCampaign(){
		
		$allProduct = \AllCampaign::getProductsFROMV1AndV2();
		return $allProduct;
    }
    
    
    
    	
    
    
    static public function getVersion()
    {
    	/*traitement  */
    
    }

}
?>
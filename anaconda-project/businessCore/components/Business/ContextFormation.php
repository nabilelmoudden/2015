<?php

namespace Business;

\Yii::import('ext.DateHelper');

/**
 * Description of ContextSite
 *
 * @author 
 * @package Business.Context
 */
class ContextFormation extends \Business\Context {

    // Parametre GET attendu :
    const FMAIL         = 'fm';
    const FPRODUCT      = 'fpr';
    const SUB_POSITION	= 'sp';
    const CAMPAIGN	= 'cp';

    // Params contexte 
    protected $fmail        = false;
    protected $fproduct     = false;
    
    protected $Campaign  = false;
    protected $Product      = false;
    

    public function loadContext($context = self::TYPE_GET) {
        $this->fmail        = $this->getContextVar(self::FMAIL, $context);
        $this->fproduct     = $this->getContextVar(self::FPRODUCT, $context);

        
	$idCamp             = $this->getContextVar( self::CAMPAIGN, $context );
        
        //$this->Product = $this->SubCampaign->Product;

        $this->Product  = \Business\Product::loadByRef( $this->fproduct );
        $this->Campaign = $this->Product->SubCampaign->Campaign;
       
        
        
       
         

        // Mise a jours des parametres propre au l'utilisateur :
      
        return true;
    }

   // ************************************ GETTER ************************************ //

    public function getFormationMail() {
        return $this->fmail;
    }

    public function getFormationProduct() {
        return $this->fproduct;
    }
    
    public function getCampaign()
    {
            return ( is_object($this->Campaign) ) ? $this->Campaign : false;
    }
    
    
    
            
        
                

            
    
    
    public function getProduct()
    {
            return $this->Product;
    }

    
}

?>
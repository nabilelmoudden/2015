<?php

namespace Business;

\Yii::import( 'ext.DateHelper' );

/**
 * Description of ContextSite
 *
 * @author JulienL
 * @package Business.Context 
 */
class ContextFormation1 extends \Business\Context
{
	 // Parametre GET attendu :
	const MAIL		= 'm';
	const SUB_POSITION	= 'sp';
      

        /**
         * 
         */
        
        
	/**
	 * User
	 * @var \Business\User
	 */
	protected $User				= false;
	/**
	 * Product
	 * @var \Business\Product
	 */
	protected $Product			= false;
	/**
	 * Campaign
	 * @var \Business\SubCampaign
	 */
	protected $SubCampaign		= false;
	/**
	 * Site
	 * @var \Business\Site
	 */
	protected $Site				= false;

	/**
	 * Charge le contexte pour les pages Lettre de vente, pages produits, ...
	 * Transforme les parametres, cherche l'user, la subCampaign, le produit et le priceEngine en fct des parametres
	 * @param	int		$context	Context de chargement ( GET, POST, ... )
	 * @return	bool	True / False
	 */
	public function loadContext( $context = self::TYPE_GET )
	{
		$mail		= $this->getContextVar( self::MAIL, $context );

		$this->User		= \Business\User::loadByEmail( $mail );
		
		if( !is_object($this->User) )
			return false;
			
		// Mise a jours des parametres propre au l'utilisateur :
		return true;
	}


	/**
	 *
	 * @return \Business\User
	 */
	public function getUser()
	{
		return $this->User;
	}
	
	public function getFirstInvoice( )
	{
		return $this->User->Invoice[0];
	}
}

?>

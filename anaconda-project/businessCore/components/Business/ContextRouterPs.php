<?php

namespace Business;

/**
 * Description of ContextRouterPs
 *
 * @author JulienL
 * @package Business.Context
 */
class ContextRouterPs extends \Business\Context
{
	// Parametre GET attendu :
	const GET_AP	= 'ap';
	const GET_SI	= 'si';
	const GET_AC	= 'ac';

	/**
	 * Affiliate Platform
	 * @var \Business\AffiliatePlatform
	 */
	protected $AffiliatePlateform		= false;
	/**
	 * Affiliate Platform Sub ID
	 * @var \Business\AffiliatePlatformSubId
	 */
	protected $AffiliatePlateformSubId	= false;
	/**
	 * ID Affiliate Platform Sub ID
	 * @var int
	 */
	protected $subIdAffiliatePlateformSubId	= false;
	/**
	 * Affiliate Campaign
	 * @var \Business\AffiliateCampaign
	 */
	protected $AffiliateCampaign		= false;

	/**
	 * Charge le contexte pour le routage vers les sites promos
	 * Transforme les parametres, cherche l'user, la plateforme, le subID et la campagne en fct des parametres
	 * @param	int		$context	Context de chargement ( GET, POST, ... )
	 * @return	bool	True / False
	 */
	public function loadContext( $context = self::TYPE_GET )
	{
		\Yii::import( 'ext.RefValidator' );

		$ap			= $this->getContextVar( self::GET_AP, $context );
		$si			= $this->getContextVar( self::GET_SI, $context );
		$ac			= $this->getContextVar( self::GET_AC, $context );

		$si			= \RefValidator::transform( $si );

		$this->AffiliatePlateform = \Business\AffiliatePlatform::load( $ap );
		if( !is_object($this->AffiliatePlateform) )
			return false;

		$this->subIdAffiliatePlateformSubId	= ( $si == false ) ? DEFAULT_SUBID : $si;
		$this->AffiliatePlateformSubId		= \Business\AffiliatePlatformSubId::loadBySubId( $ap, $this->subIdAffiliatePlateformSubId );

		$this->AffiliateCampaign		= \Business\AffiliateCampaign::load( $ac );
		if( !is_object($this->AffiliateCampaign) )
			return false;

		return true;
	}

	// ************************************ GETTER ************************************ //
	/**
	 *
	 * @return \Business\AffiliatePlateform
	 */
	public function getAffiliatePlateform()
	{
		return $this->AffiliatePlateform;
	}
	/**
	 *
	 * @return \Business\AffiliatePlateformSubId
	 */
	public function getAffiliatePlateformSubId()
	{
		return $this->AffiliatePlateformSubId;
	}
	/**
	 *
	 * @return \Business\AffiliateCampaign
	 */
	public function getAffiliateCampaign()
	{
		return $this->AffiliateCampaign;
	}
	/**
	 *
	 * @return int
	 */
	public function getSubIdAffiliatePlateformSubId()
	{
		return $this->subIdAffiliatePlateformSubId;
	}
}

?>

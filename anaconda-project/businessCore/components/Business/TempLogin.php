<?php

namespace Business;

/**
 * Description of LeadAffiliatePlatform
 *
 * @author JulienL
 * @package Business.AffiliatePlatform
 */
class TempLogin extends \TempLogin
{
	
	/**
	 *
	 * @param type $id
	 * @return \Business\LeadAffiliatePlatform
	 */
	static public function load( $id )
	{
		return self::model()->findByPk( $id );
	}
}

?>
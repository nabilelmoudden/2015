<?php

namespace Business;

/**
 * Description of Site
 *
 * @author JulienL
 * @package Business.Site
 */
class Site extends \Site
{


	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
            'AffiliatePlatform' => array(self::HAS_MANY, '\Business\Affiliateplatform', 'idSite'),
			'Invoice' => array(self::HAS_MANY, '\Business\Invoice', 'codeSite'),
			'PricingGrid' => array(self::HAS_MANY, '\Business\PricingGrid', 'idSite'),
			'PaymentProcessorType' => array(self::HAS_MANY, '\Business\PaymentProcessorType', 'idSite'),
		);
	}
	
	public function DeviseSite($codeSite)
	{
		$selected_fr = ($codeSite==="fr")?' selected ':'';
		$selected_ca = ($codeSite==="ca")?' selected ':'';
		$outputDeviseSite = '<center>
							  <form> 
								'.\Yii::t('BDC', 'lbl_devise').'
								<select name="devise" id="devise" style="width: 40%;">
									  <option value="d_euro" '.$selected_fr.'>Euro</option>
									  <option value="d_canada" '.$selected_ca.'>Dollar Canadien</option>
								</select>
							  </form>          
							</center>';
			
		return $outputDeviseSite;
	 }

	// *********************** STATIC *********************** //
	static function model($className=__CLASS__)
	{
        return parent::model($className);
    }

	/**
	 *
	 * @param int $id
	 * @return \Business\Config
	 */
	static public function load( $id )
	{
		return self::model()->findByPk( $id );
	}
	/**
	 *
	 * 
	 * @return \la devise du site
	 */
public function GetDevise()
  {    
   return $this->devise;
  }
	/**
	 *
	 * @param string code
	 * @return \Business\Site
	 */
	static public function loadByCode( $code )
	{
		return self::model()->findByAttributes( array( 'code' => $code ) );
	}
	
}

?>
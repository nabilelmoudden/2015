<?php

namespace Business;

/**
 * Description of CampaignReflation
 *
 * @author JulienL
 * @package Business.Campaign
 */
class SubCampaignReflation extends \Subcampaignreflation
{
	public function init()
	{
		parent::init();

		//$this->onAfterSave		= array( $this, 'createReflation' );
		$this->onAfterDelete	= array( $this, 'deleteReflation' );

		// Valeur par defaut du champs templateProd si scenario d'insertion
		if( $this->getScenario() == 'insert' )
			$this->templateProd		= DEFAULT_TEMPLATE_PROD;
	}

	/**
	 * @return array relational rules.
	 * Surcharge pour que la relation soit sur la classe Business
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'SubCampaign' => array(self::BELONGS_TO, '\Business\SubCampaign', 'idSubCampaign'),
		);
	}

	/**
	 * Creation de la vue associé a la relance
	 * @return boolean
	 */
	public function beforeSave()
	{	
		if(defined('EVENT_SAVE_ESCAPE') && EVENT_SAVE_ESCAPE){
			return true;
		}
		
		umask(0);
	// Defini le dossier contenant le contenu de Template Produit :
	 	$path_templateprod = \Yii::app()->controller->portViewDir( true ).'templateprod/'.\Yii::app()->params['lang'].'/default_templateProduct.html';
		$data_templateprod = file_get_contents($path_templateprod);
		$productPath = explode('/default_templateProduct.html',$this->getPathTemplateProd( true ));

		if( !empty($this->templateProd) )
		{
			
			$view	= $this->getPathTemplateProd( true );
			if(!file_exists($view)){
				if(!is_writable($productPath[0]))
				{
					return false;
				}
				if( !is_file($view) && !touch($view) )
					return false;
					
				$fptemp = fopen( $view, 'r+' );
				fwrite( $fptemp, $data_templateprod );
				fclose( $fptemp );
			}
			
		}
		
	// Defini le dossier contenant le contenu initial d'une view
	// 111 pour la page pro
	    if($this->number == 111)
		{
			$path_vieweprod = \Yii::app()->controller->portViewDir( true ).'templateprod/'.\Yii::app()->params['lang'].'/default_viewepro.html';
		}else{
			$path_vieweprod = \Yii::app()->controller->portViewDir( true ).'templateprod/'.\Yii::app()->params['lang'].'/default_vieweprod.html';
		}
		
		$data_vieweprod = file_get_contents($path_vieweprod);

		if( !empty($this->view) )                                    
		{
			$view	= $this->getPathView( true );
			if( !is_file($view) )
			{
				if(!is_writable($productPath[0]))
					return false;
				
				if( !touch($view) )
					return false;

				$fp = fopen( $view, 'r+' );
				fwrite( $fp, $data_vieweprod );
				fclose( $fp );
			}
		}

		return true;
	}
	static public function loadByCampStep( $idCampaign , $step )
	{
		return self::model()->findByAttributes( array( 'number' => $step , 'idSubCampaign' => $idCampaign ) );
	}
	/**
	 * Suppression de la vue associé a la relance
	 * @return boolean
	 */
	public function deleteReflation()
	{
		if( !empty($this->view) )
		{
			$view = $this->getPathView( true );
			if( is_file($view) && !unlink($view) )
				return false;
		}

		// if( !empty($this->templateProd) )
		// {
			// $view = $this->getPathTemplateProd( true );
			// if( is_file($view) && !unlink($view) )
				// return false;
		// }

		return true;
	}
	
	/**
	 * getUrl
	 * @return l'URL de la page
	 */
	public function getUrl(){
		$DNS = "";
		$ConfDNS = \Business\Config::loadByKeyAndSite( 'DNS' );
		if(is_object($ConfDNS)){
			$DNS = $ConfDNS->value;
		}else{
			$DNS = "http://__domaine__";
		}
		
		$ref = $this->SubCampaign->Campaign->ref;
		$tr = $this->number;
		$sp = $this->SubCampaign->position;
		$site	= substr(\Yii::app()->params['porteur'],0,2);
		$url = $DNS.'/voyances/index.php/site/index?ref='.$ref.'&tr='.$tr.'&sp='.$sp.'&bs=1&gp=1&site='.$site;
		
		return $url;
	}

	/**
	 * Recherche
	 * @param string $order Ordre
	 * @param int $pageSize	Nb de result par page 
	 * @return CActiveDataProvider	CActiveDataProvider
	 */
	public function search( $order = false, $pageSize = 0 )
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
	 * Retourne le chemin vers la vue
	 * @return string
	 */
	public function getPathView( $withRoot = false )
	{
		$refC	= $this->SubCampaign->Campaign->ref;
		$refP	= $this->SubCampaign->Product->ref;

		return \Yii::app()->controller->portDir( $withRoot ).'/'.$refC.'/'.$refP.'/'.$this->view.'.html';
	}

	/**
	 * Retourne le chemin vers la template produit
	 * @return string
	 */
	public function getPathTemplateProd( $withRoot = false )
	{
		$refC	= $this->SubCampaign->Campaign->ref;
		$refP	= $this->SubCampaign->Product->ref;

		return \Yii::app()->controller->portDir( $withRoot ).'/'.$refC.'/'.$refP.'/'.$this->templateProd.'.html';
	}
	
	/**
	 * Recherche OffsetPriceStep d'une relance d'un campaine
	 */

	public function GetOffsetPriceStep($idSubCampaign, $number ){
		
		$Provider = $this->search();
		$Provider->criteria->compare( 'idSubCampaign', $idSubCampaign );
		$Provider->criteria->compare( 'number', $number );

		return $Provider;

	}

	// *********************** STATIC *********************** //
	static function model($className=__CLASS__)
	{
        return parent::model($className);
    }

	/**
	 *
	 * @param type $id
	 * @return \Business\SubCampaignReflation
	 */
	static public function load( $id )
	{
		return self::model()->findByPk( $id );
	}
	
	static public function getSubCampaignReflationBySubCampaign( $idSubCampaign )
	{
		
		return self::model()->findAllByAttributes( array( 'idSubCampaign' => $idSubCampaign ) );	
		
		
	}

    /**
     * @author AL.
     * @param $idSubCampaign
     * @return mixed
     */
    public static function loadByIdSubCamp( $idSubCampaign, $number )
    {
        return self::model()->findByAttributes( array( 'idSubCampaign' => $idSubCampaign, 'number' => $number ) );
    }

    /**
     * @author FIKRI.
     * @param $idSubCampaign
     * @return mixed
     */
    public static function loadByIdSubCampAndNumber( $idSubCampaign, $number )
    {
        return self::model()->findByAttributes( array( 'idSubCampaign' => $idSubCampaign, 'number' => $number ) );
    }

    /**
     * @author AL.
     * @param $idSubCampaign
     * @return mixed
     */
    public static function loadByIdSubCampaign($idSubCampaign)
    {
        return self::model()->findAllByAttributes(['idSubCampaign' => $idSubCampaign]);
    }

    /**
     * @author AL.
     * @param $idSubCampaign
     * @param $idSubCampRefl
     * @return mixed
     */
    public static function loadByIdSubCampaignAndIdSubCampRefl($idSubCampaign, $idSubCampRefl)
    {
        return self::model()->findByAttributes(['idSubCampaign' => $idSubCampaign, 'id' => $idSubCampRefl]);
    }
}
?>

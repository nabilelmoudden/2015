<?php
/**
 * Description of TwigEsoterExt
 * Extension de fonctions perso pour Twig
 *
 * @author JulienL
 * @package Twig
 */
class TwigEsoterExt extends \Twig_Extension
{
    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
	public function getName()
    {
        return 'TwigEsoterExt';
    }

    /**
     * Returns a list of globals to add to the existing list.
     *
     * @return array An array of globals
     */
    public function getGlobals()
	{
		return array(
			'CHtml'		=> new CHtml(),
		);
	}

    /**
     * Returns a list of filters to add to the existing list.
     *
     * @return array An array of filters
     */
    public function getFilters()
	{
		return array(
			new Twig_SimpleFilter( 'rot13', 'str_rot13' ),
			new Twig_SimpleFilter( 'jencode', 'CJSON::encode' ),
			new Twig_SimpleFilter( 'ucfirst', 'ucfirst' ),
			new Twig_SimpleFilter( 'uppercase', 'strtoupper' ),
			new Twig_SimpleFilter( 'lowercase', 'strtolower' ),
			 'evaluate' => new \Twig_Filter_Method( $this, 'evaluate', array(
                'needs_environment' => true,
                'needs_context' => true,
                'is_safe' => array(
                    'evaluate' => true
                )
            ))
		);
	}
public function evaluate( \Twig_Environment $environment, $context, $string ) {
        $loader = $environment->getLoader( );

        $parsed = $this->parseString( $environment, $context, $string );

        $environment->setLoader( $loader );
        return $parsed;
    }
   protected function parseString( \Twig_Environment $environment, $context, $string ) {
        $environment->setLoader( new \Twig_Loader_String( ) );
        return $environment->render( $string, $context );
    }
    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
	public function getFunctions()
	{
		return array(
			'adminViewDir'				=> new Twig_Function_Method( $this, 'adminViewDir' ),
			'portViewDir'				=> new Twig_Function_Method( $this, 'portViewDir' ),
			'portDir'					=> new Twig_Function_Method( $this, 'portDir' ),
			'campaignDir'				=> new Twig_Function_Method( $this, 'campaignDir' ),
			'productDir'				=> new Twig_Function_Method( $this, 'productDir' ),
			'getProductTemplate'		=> new Twig_Function_Method( $this, 'getProductTemplate' ),

			'pageTitle'					=> new Twig_Function_Method( $this, 'pageTitle' ),
			'staticCall'				=> new Twig_Function_Method( $this, 'staticCall' ),
			't'							=> new Twig_Function_Function( 'Yii::t' ),
			'FM'						=> new Twig_Function_Method( $this, 'FM' ),
			'isString'					=> new Twig_Function_Function( 'is_string' ),
			'rand'						=> new Twig_Function_Function( 'rand' ),
			'loadJS'					=> new Twig_Function_Method( $this, 'loadJS' ),
			'loadCoreJS'				=> new Twig_Function_Method( $this, 'loadCoreJS' ),
			'loadCSS'					=> new Twig_Function_Method( $this, 'loadCSS' ),
			'date'						=> new Twig_Function_Method( $this, 'date' ),
			'printFlashes'				=> new Twig_Function_Method( $this, 'printFlashes' ),
			'currentPrice'				=> new Twig_Function_Method( $this, 'currentPrice' ),
			'getPrice'					=> new Twig_Function_Method( $this, 'getPrice' ),
			'getPriceParite'			=> new Twig_Function_Method( $this, 'getPriceParite' ),
			'getPriceTheorique'			=> new Twig_Function_Method( $this, 'getPriceTheorique' ),
			'getPrice2'					=> new Twig_Function_Method( $this, 'getPrice2' ),
			'insertRegisteredScript'	=> new Twig_Function_Method( $this, 'insertRegisteredScript' ),
			
			'menuWithPorteur'			=> new Twig_Function_Method( $this, 'menuWithPorteur' ),

			'debug'						=> new Twig_Function_Method( $this, 'debug' ),
			'datet'						=> new Twig_Function_Method( $this, 'mois' ),
			'SD'						=> new Twig_Function_Method( $this, 'getSD' ),
				
			'impor'					=> new Twig_Function_Method( $this, 'impor' ),
			
			// ROI
			'updatearray'				=> new Twig_Function_Method( $this, 'updatearray' ),
				
			// Generation CDC
			'getsession'				=> new Twig_Function_Method( $this, 'getsession' ),
				
			'getPricePariteMS'			=> new Twig_Function_Method( $this, 'getPricePariteMS' ),
			'getPriceTheoriquePariteMS'			=> new Twig_Function_Method( $this, 'getPriceTheoriquePariteMS' ),
			'getPriceTH'					=> new Twig_Function_Method( $this, 'getPriceTH' ),
			
			'chainageSD'					=> new Twig_Function_Method( $this, 'chainageSD' ),
		);
	}

	// ************************** FUNCTION FOR TWIG ************************** //

	/*
	 * Fonction TWIG pour modifier le titre de la page
	 * Usage : {{ pageTitle( Titre ) }}
	 * @param	string	$title	Titre de la page
	 */
	public function pageTitle( $title )
	{
		if( !is_object(Yii::app()->controller) )
			return false;

		Yii::app()->controller->pageTitle = $title;
	}

	public function adminViewDir()
	{
		return \Yii::app()->controller->adminViewDir();
	}

	public function adminDir()
	{
		return \Yii::app()->controller->adminDir();
	}

	public function portViewDir()
	{
		return \Yii::app()->controller->portViewDir();
	}

	public function portDir()
	{
		return \Yii::app()->controller->portDir();
	}

	public function productDir()
	{
		return $this->campaignDir().'/'.\Yii::app()->controller->getProduct()->ref;
	}

	public function campaignDir()
	{
		return $this->portDir().'/'.\Yii::app()->controller->getCampaign()->ref;
	}

	public function getProductTemplate()
	{
		return \Yii::app()->params['porteur'].'/'.\Yii::app()->controller->getCampaign()->ref.'/'.\Yii::app()->controller->getProduct()->ref.'/'.\Yii::app()->controller->getSubCampaignReflation()->templateProd.'.html';
	}

	/*
	 * Fonction TWIG pour appel� une fonction statique d'un objet
	 * Usage : {{ staticCall( 'className', 'function', [ arg ] ) }}
	 * @param	string	$class		Class Name
	 * @param	string	$function	Function Name
	 * @param	array	$args		Arguments
	 */
	public function staticCall( $class, $function, $args = array() )
	{
		if (class_exists($class) && method_exists($class, $function))
			return call_user_func_array(array($class, $function), $args);
		return null;
	}

	/*
	 * Fonction TWIG pour ger� le feminin/masculin
	 * Usage : {{ FM( 'mot masculin', 'mot feminin' ) }}
	 * @param	string	$masculin	Mot masculin
	 * @param	string	$feminin	Mot feminin ( optionnel s'il y a juste 'e' a rajout� au masculin )
	 */
	public function FM( $masculin, $feminin = NULL )
	{
		if( !is_object(Yii::app()->controller) || !Yii::app()->controller->getUser() )
			return $masculin;

		if( $feminin == NULL )
			$feminin = $masculin.'e';

		//Yii::t( 'app', 'n==M#'.$masculin.'|n!=M#'.$feminin, Yii::app()->controller->getUser()->civility )

		return '<span style="color:red;">'.( strtolower(Yii::app()->controller->getUser()->civility) == 'm' ? $masculin : $feminin ).'</span>';
		//return ( strtolower(Yii::app()->controller->getUser()->civility) == 'm' ? $masculin : $feminin );
	}

	/**
	 * Permet de charger un fichier JS
	 * @param string $fileName	Fichier JS a charger
	 * @return string
	 */
	public function loadJS( $fileName )
	{
		\Yii::app()->controller->includeJS( $fileName );
		return "";
	}
	
	
	/**
	 * Permet de charger un fichier JS
	 * @param string $fileName	Fichier JS a charger
	 * @return string
	 */
	public function impor( $fileName )
	{
		Yii::import( $fileName );
		//return "";
	}

	/**
	 * Permet de charger un fichier core JS
	 * @param string $fileName	Fichier core JS a charger
	 * @return string
	 */
	public function loadCoreJS( $fileName )
	{
		\Yii::app()->controller->includeCSS( $fileName );
		return "";
	}

	/**
	 * Permet de charger un fichier CSS
	 * @param string $fileName Fichier CSS a charger
	 * @return string
	 */
	public function loadCSS( $fileName )
	{
		Yii::app()->clientScript->registerCssFile( $fileName );
		return "";
	}

	/**
	 * Retourne la date uniquement ( sans les heures )
	 * @param string $date
	 * @return string $date
	 */
	public function date( $date )
	{
		Yii::import( 'ext.DateHelper' );

		return \DateHelper::dateOnly( $date );
	}

	/**
	 * Permet d'afficher les Flashes ET / OU les erreurs sur un model de l'utilisateur
	 * @param	object	$Model	Optionnel
	 * @return	string	code HTML
	 */
	public function printFlashes()
	{
		$ret = NULL;

		foreach( Yii::app()->user->getFlashes() as $k => $v )
			$ret .= '<div class="alert alert-'.$k.'">'.$v.'</div>';

		$errorModel = ActiveRecord::getAllErrors();
		if( count($errorModel) > 0 )
		{
			$ret .= '<div class="alert alert-error">';
			foreach( $errorModel as $field => $err )
			{
				if( is_array($err) )
				{
					foreach( $err as $errMsg )
						$ret .= $field.' : '.$errMsg.'<br />';
				}
				else if( is_string($err) )
					$ret .= $err.'<br/>';
			}
			$ret .= '</div>';
		}

		return $ret;
	}

	/**
	 * Retourne le prix du produit courant
	 * @return string Prix formatt�
	 */
	public function currentPrice( $format = true )
	{
		\Yii::import( 'ext.PriceHelper' );

		$PriceHelp	= new PriceHelper();
		$price		= \Yii::app()->controller->getCurrentPrice();

		return ( $format ) ? $PriceHelp->formatPrice( $price ) : $price;
	}
	
	public function getPrice( $idSubCampaign, $refBatchSelling, $priceStep, $refPricingGrid, $idSite, $parite, $format = true )
	{
		\Yii::import( 'ext.PriceHelper' );

		$PriceHelp	= new PriceHelper();
		$PG			= \Business\PricingGrid::get( $idSubCampaign, $refBatchSelling, $priceStep, $refPricingGrid, $idSite );

		return (is_object($PG) ) ? ( $format ? $PriceHelp->formatPrice( $PG->priceATI ) : $PG->priceATI ) : false;
	}
	
	public function getPriceTH( $idSubCampaign, $refBatchSelling, $priceStep, $refPricingGrid, $idSite, $parite, $format = true )
	{
		\Yii::import( 'ext.PriceHelper' );
	
		$PriceHelp	= new PriceHelper();
		$PG			= \Business\PricingGrid::get( $idSubCampaign, $refBatchSelling, $priceStep, $refPricingGrid, $idSite );
	
		return (is_object($PG) ) ? ( $format ? $PriceHelp->formatPrice( $PG->prixTheorique ) : $PG->prixTheorique ) : false;
	}
	
	public function getPriceParite( $idSubCampaign, $refBatchSelling, $priceStep, $refPricingGrid, $idSite, $parite, $format = true )
	{
		\Yii::import( 'ext.PriceHelper' );

		$PriceHelp	= new PriceHelper();
		
		$Site	= new \Business\Site('search');
		$incr = 0;
		foreach( $Site->findAll() as $S )
		{
			if($incr == 0){
				
				$id_site = $S->id;
				$Devise_site = $S->codeDevise;
			}
			$incr++;
		}
		
		$PG_1		= \Business\PricingGrid::get( $idSubCampaign, $refBatchSelling, $priceStep, $refPricingGrid, $id_site);		
		$PG			= \Business\PricingGrid::get( $idSubCampaign, $refBatchSelling, $priceStep, $refPricingGrid, $idSite );
		
			
		if(is_object($PG))
		{
			$PGPrice =  $PG->priceATI;
			
		}else{
			
			if(is_object($PG_1))
			{
				if($Devise_site != 'EUR'){
					$PariteInvoice	= new \Business\PariteInvoice('search');
					$parite_Invoice = $PariteInvoice->loadByDevise($Devise_site);
					
					$PGPrice =  intval((($PG_1->priceATI)*$parite_Invoice->parite)/$parite);
				}else{
					$PGPrice =  intval(($PG_1->priceATI)/$parite);
				}
				//$PGPrice =  number_format($PGPrice, 2, '.', '');
				
			}else{
				
				false;
				
			}
			
		}
			
		
		return (is_object($PG) || is_object($PG_1) ) ? ( $format ? $PriceHelp->formatPrice( $PGPrice ) : $PGPrice ) : false;
	}
	
	public function getPricePariteMS( $idSubCampaign, $refBatchSelling, $priceStep, $refPricingGrid, $idSite, $parite, $format = true, $idSiteS )
	{
		\Yii::import( 'ext.PriceHelper' );
	
		$PriceHelp	= new PriceHelper();
	
		$Site	= new \Business\Site('search');
		$incr = 0;
		$S	= $Site->findByPk($idSiteS);

				$id_site = $S->id;
				$Devise_site = $S->codeDevise;

	
		$PG_1		= \Business\PricingGrid::get( $idSubCampaign, $refBatchSelling, $priceStep, $refPricingGrid, $id_site);
		$PG			= \Business\PricingGrid::get( $idSubCampaign, $refBatchSelling, $priceStep, $refPricingGrid, $idSite );
	
			
		if(is_object($PG))
		{
			$PGPrice =  $PG->priceATI;
				
		}else{
				
			if(is_object($PG_1))
			{
				if($Devise_site != 'EUR'){
					$PariteInvoice	= new \Business\PariteInvoice('search');
					$parite_Invoice = $PariteInvoice->loadByDevise($Devise_site);
						
					$PGPrice =  intval((($PG_1->priceATI)*$parite_Invoice->parite)/$parite);
				}else{
					$PGPrice =  intval(($PG_1->priceATI)/$parite);
				}
				//$PGPrice =  number_format($PGPrice, 2, '.', '');
	
			}else{
	
				false;
	
			}
				
		}
			
	
		return (is_object($PG) || is_object($PG_1) ) ? ( $format ? $PriceHelp->formatPrice( $PGPrice ) : $PGPrice ) : false;
	}
	
	public function getPriceTheoriquePariteMS( $idSubCampaign, $refBatchSelling, $priceStep, $refPricingGrid, $idSite, $parite, $format = true, $idSiteS ){
		\Yii::import( 'ext.PriceHelper' );
	
		$PriceHelp	= new PriceHelper();
	
		$Site	= new \Business\Site('search');
		$S	= $Site->findByPk($idSiteS);

				$id_site = $S->id;
				$Devise_site = $S->codeDevise;
	
		$PG_1		= \Business\PricingGrid::get( $idSubCampaign, $refBatchSelling, $priceStep, $refPricingGrid, $id_site);
		$PG			= \Business\PricingGrid::get( $idSubCampaign, $refBatchSelling, $priceStep, $refPricingGrid, $idSite );
	
			
		if(is_object($PG))
		{
			$PGPrice =  $PG->prixTheorique;
				
		}else{
				
			if(is_object($PG_1))
			{
				if($Devise_site != 'EUR'){
					$PariteInvoice	= new \Business\PariteInvoice('search');
					$parite_Invoice = $PariteInvoice->loadByDevise($Devise_site);
						
					$PGPrice =  intval((($PG_1->prixTheorique)*$parite_Invoice->parite)/$parite);
				}else{
					$PGPrice =  intval(($PG_1->prixTheorique)/$parite);
				}
				//$PGPrice =  number_format($PGPrice, 2, '.', '');
	
			}else{
	
				false;
	
			}
				
		}
			
	
		return (is_object($PG) || is_object($PG_1) ) ? ( $format ? $PriceHelp->formatPrice( $PGPrice ) : $PGPrice ) : false;
	}
	
	
	public function getPriceTheorique( $idSubCampaign, $refBatchSelling, $priceStep, $refPricingGrid, $idSite, $parite, $format = true ){
		\Yii::import( 'ext.PriceHelper' );

		$PriceHelp	= new PriceHelper();
		
		$Site	= new \Business\Site('search');
		$incr = 0;
		foreach( $Site->findAll() as $S )
		{
			if($incr == 0){
				
				$id_site = $S->id;
				$Devise_site = $S->codeDevise;
			}
			$incr++;
		}
		
		$PG_1		= \Business\PricingGrid::get( $idSubCampaign, $refBatchSelling, $priceStep, $refPricingGrid, $id_site);		
		$PG			= \Business\PricingGrid::get( $idSubCampaign, $refBatchSelling, $priceStep, $refPricingGrid, $idSite );
		
			
		if(is_object($PG))
		{
			$PGPrice =  $PG->prixTheorique;
			
		}else{
			
			if(is_object($PG_1))
			{
				if($Devise_site != 'EUR'){
					$PariteInvoice	= new \Business\PariteInvoice('search');
					$parite_Invoice = $PariteInvoice->loadByDevise($Devise_site);
					
					$PGPrice =  intval((($PG_1->prixTheorique)*$parite_Invoice->parite)/$parite);
				}else{
					$PGPrice =  intval(($PG_1->prixTheorique)/$parite);
				}
				//$PGPrice =  number_format($PGPrice, 2, '.', '');
				
			}else{
				
				false;
				
			}
			
		}
			
		
		return (is_object($PG) || is_object($PG_1) ) ? ( $format ? $PriceHelp->formatPrice( $PGPrice ) : $PGPrice ) : false;
	}
	
	public function getPrice2( $idSubCampaign, $refBatchSelling, $priceStep, $refPricingGrid, $idSite, $format = true )
	{

	if (isset($_FILES['myFileInput']) && ($_FILES['myFileInput']['name'] != null)){

	echo $_FILES['myFileInput']['name'];
	
	$handle = fopen('/var/www/www.spirit2010.com/'.$this->portDir( true ).$_FILES['myFileInput']['name'], "r");
	$row=1;
	$prix='';
	    
	    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE)
	    {
		    $num = count($data);
		    
			for ($c=0; $c < $num; $c++)
			{		
				if ( $c + 1 ==$priceStep && $row==$refPricingGrid && $refBatchSelling == 1)
				{
					$prix  = $data[$c];
					
				}
			}
			$row++;
        }
        return $prix;
	//return $prix;
	fclose($handle);
	unlink($handle);
	
	}else{
		\Yii::import( 'ext.PriceHelper' );
		
		$PriceHelp	= new PriceHelper();
		$PG			= \Business\PricingGrid::get( $idSubCampaign, $refBatchSelling, $priceStep, $refPricingGrid, $idSite );

		return (is_object($PG) ) ? ( $format ? $PriceHelp->formatPrice( $PG->priceATI ) : $PG->priceATI ) : false;
	}
	
	}
	

	/**
	 * Insert les scripts enregistr� depuis le php dans la vue
	 * @return string script
	 */
	public function insertRegisteredScript()
	{
		$scripts	= Yii::app()->getClientScript()->scripts;
		$toInsert	= NULL;
		foreach( $scripts as $scriptAtPos )
			$toInsert .= CHtml::script( implode("\n", $scriptAtPos) )."\n";

		return $toInsert;
	}

	/**
	 * Permet l'affichage d'un menu gerant les differents porteurs, le login/logout ainsi que les differents items transmit en argument
	 * @param array $items
	 */
	/*public function menuWithPorteur( $items = array() )
	{
		$itemPorteur	= array();
		$lPorteurs		= $GLOBALS['porteurMap'];
		$lastCountry	= NULL;

		asort ( $lPorteurs, SORT_STRING );

		foreach( $lPorteurs as $port => $dirPort )
		{
			$tmp	= explode( '_', $port );
			$label	= strtoupper($tmp[0]).' '.ucfirst( $tmp[1] );

			$itemPorteur[] = array(
				'itemOptions'	=> array( 'class' => ( \Yii::app()->controller->porteur() == $port ) ? 'activeSousMenu' : NULL ),
				'label'			=> $label,
				'url'			=> array( 0 => '', 'p' => $port ),
				'template'		=> ( $lastCountry != NULL && $lastCountry != $tmp[0] ? '<hr>' : NULL ).'<img src="'.\Yii::app()->controller->portViewDir().$port.'/images/80.jpg" style="width:20px;" />{menu}'
			);

			if( $lastCountry != $tmp[0] )
				$lastCountry = $tmp[0];
		}

		$globalPorteur	= array
		(
			array
			(
				'label'		=> 'Porteur',
				'url'		=> '#',
				'visible'	=> !\Yii::app()->user->isGuest,
				'items'		=> $itemPorteur
			)
		);

		$login = array
		(
			array
			(
				'label'		=> 'Login',
				'url'		=> array( '/'.\Yii::app()->controller->id.'/login' ),
				'visible'	=> \Yii::app()->user->isGuest,
			),
			array
			(
				'label'		=> 'Logout',
				'url'		=> array( '/'.Yii::app()->controller->id.'/logout' ),
				'visible'	=> !\Yii::app()->user->isGuest,
			)
		);

		$items = array_merge( $globalPorteur, $items, $login );

		Yii::app()->controller->widget( 'zii.widgets.CMenu', array
		(
			'activateParents'	=> true,
			'items'				=> $items
		));
	}*/
	
	
	public function menuWithPorteur( $items = array() )
	{
		$itemPorteur	= array();
		$lPorteurs		= $GLOBALS['porteurPere'];
		$lastCountry	= NULL;
	
		@asort ( $lPorteurs, SORT_STRING );
	
		foreach( $lPorteurs as $portP => $ports )
		{
			$ports = array_unique($ports,SORT_REGULAR);
			
			$tmp	= explode( '_', $portP );
			@$label	= @strtoupper($tmp[0]).' '.@ucfirst( $tmp[1] );
			if($label == 'BR Rucker')
				$label = 'BR Theodor';
			elseif($label == 'DE Rmay')
			$label = 'DE Monaluisa';
			elseif($label == 'ES Rmay')
			$label = 'ES Monaluisa';
			elseif($label == 'FR Rmay')
			$label = 'FR Monaluisa';
			elseif($label == 'BR Rmay')
			$label = 'BR Monaluisa';
			elseif($label == 'FR Rucker')
			$label = 'FR Theodor';
	
			$get = $_GET;
			unset($get['p']);
					
			foreach( $ports as $port ){
				
				$result = array_merge(array( 0 => '', 'p' => $port[1] ), $get);
				
				$itemPort[] = array(
						'itemOptions'	=> array( 'class' => ( \Yii::app()->controller->porteur() == $port[1] ) ? 'activeSousMenu' : NULL ),
						'label'			=> $port[0],
						//'url'			=> array( 0 => '', 'p' => $port[1] ),
						'url'			=> $result,
						'template'		=> '<img src="'.\Yii::app()->controller->portViewDir().$port[1].'/images/80.jpg" style="width:20px;" />{menu}'
				);
			}
	
			if( $lastCountry != $tmp[0] )
				$lastCountry = $tmp[0];
	
			$itemPorteur[] = array(
					'itemOptions'	=> array( 'class' => 'laclass' ),
					'activateParents'	=> true,
					'url'		=> '#',
					'label'			=> $portP,
					'items'			=> $itemPort,
					'template'		=> '<hr style="width:93%">'.$portP
			);
	
			unset($itemPort);
	
	
		}
	
		$globalPorteur	= array
		(
				array
				(
						'activateParents'	=> true,
						'label'		=> 'Porteur',
						'url'		=> '#',
						'visible'	=> !\Yii::app()->user->isGuest,
						'items'		=> $itemPorteur
				)
		);
	
		$login = array
		(
				array
				(
						'label'		=> 'Login',
						'url'		=> array( '/'.\Yii::app()->controller->id.'/login' ),
						'visible'	=> \Yii::app()->user->isGuest,
				),
				array
				(
						'label'		=> 'Logout',
						'url'		=> array( '/'.Yii::app()->controller->id.'/logout' ),
						'visible'	=> !\Yii::app()->user->isGuest,
				)
		);
		// print_r($globalPorteur);exit;
		$items = array_merge( $globalPorteur, $items, $login );
			
		Yii::app()->controller->widget( 'zii.widgets.CMenu', array
		(
		'activateParents'	=> true,
		'items'				=> $items
		));
	}

	/**
	 * Print_r de la variable
	 * @param array $var
	 */
	public function debug( $var )
	{
		echo '<pre>';
		print_r( $var );
		echo '</pre>';
	}
	
	
	//////////////////
	public function getSD(){
		 return $_GET['sd'];
	}
	
	 public function swi_month($i,$language = 'en'){
	
		switch ($i){
			case(1):
				$month = "January";
				break;
			case(2):
				$month = "February";
				break;
			case(3):
				$month = "March";
				break;
			case(4):
				$month = "April";
				break;
			case(5):
				$month = "May";
				break;
			case(6):
				$month = "June";
				break;
			case(7):
				$month = "July";
				break;
			case(8):
				$month = "August";
				break;
			case(9):
				$month = "September";
				break;
			case(10):
				$month = "October";
				break;
			case(11):
				$month = "November";
				break;
			case(12):
				$month = "December";
				break;
	}
	return $month;
}
	function mois($date_us,$more_day){
	$date = $date_us;
	$date_format = 'm/d';
	$array_date = date_parse_from_format($date_format, $date);
	$next_date = date($date_format, mktime(0, 0, 0,$array_date['month'],$array_date['day']+$more_day));
	return $this->swi_month($next_date,"en");
	}
	
	// ROI
	public function updatearray($tabl, $ind1, $ind2, $value){
		$tabl[$ind1][$ind2] += $value;
		return $tabl;
	}
	
	// Generation CDC (Fonction retournant le contenu d'une variable session
	public function getsession( $param ){
		return Yii::app()->session[$param];
	}
	
	
	// Calcul Date Chainage
	public function chainageSD ($interval) {
	
		if(isset($_REQUEST['sd'])){
			$Date = DateTime::createFromFormat( 'm/d/Y', $_REQUEST['sd'] );
			$Date->add( new DateInterval('P15D') );
			if(isset($_GET['sd']))
				$_GET['sd'] = $Date->format('m/d/Y');
			if(isset($_POST['sd']))
				$_POST['sd'] = $Date->format('m/d/Y');
		}
	
	}
		
}

?>

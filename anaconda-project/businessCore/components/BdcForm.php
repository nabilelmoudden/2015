<?php

/**
 * Description of BdcForm
 *
 * @author JulienL
 *
 * @property SiteController $parent
 * @property Bdc $model
 *
 * @package BDC
 */
class BdcForm extends CForm
{
	/**
	 * Payment Processor Type
	 * @var array[\Business\PaymentProcessorSet]
	 */
	protected $PaymentType	= false;
	/**
	 * Tableau contenant les champs du BDC
	 * @var array $bdcFields
	 */
	protected $bdcFields	= array();

	public function __construct( $Model )
	{
		parent::__construct( include(\Yii::app()->controller->adminViewDir( true ).'/common/bdc.php'), $Model );

		if(isset($_GET['v']) && $_GET['v']=='in'){
        	Yii::app()->language = 'in';
        }
        
		\Yii::app()->controller->includeJS( 'bdc.js' );
		\Yii::app()->controller->includeCSS( 'bdc.css' );

		// Valeur par defaut :
		foreach( $this->model as $k => $v )
			if( isset($this->parent->getUser()->$k) )
				$this->model->$k = $this->parent->getUser()->$k;

		// Cas speciaux :
		$this->model->birthday		= $this->parent->getUser()->getBirthday( \Yii::app()->params['formatDate'] );
		$this->model->birthdayHour	= $this->parent->getUser()->getBirthday('H:i');

		// Recuperation des posts et modification du model :
		if( Yii::app()->request->getParam( 'Bdc' ) != NULL )
			$this->model->attributes	= Yii::app()->request->getParam( 'Bdc' );

		// Recuperation du PaymentProcessorSet affecté au produit
		if( $this->parent->getProduct()->idPaymentProcessorSet <= 0 || !($this->PaymentType = $this->parent->getProduct()->getPaymentProcessorTypeForSite( $this->model->idSite )) )
			throw new \EsoterException( 104, \Yii::t('error', '104') );

		$this->setFieldsForProduct();
	}

	/**
	 * Defini les champs visibles et obligatoires pour le produit courant
	 * Ainsi que les modes de paiement disponible pour le produit courant
	 */
	private function setFieldsForProduct()
	{
		foreach( $this->parent->getProduct()->bdcFields as $type => $tab )
		{
			foreach( $tab as $attr )
			{
				$this->bdcFields[ $attr ][] = $type;

				if( !($Elt = $this->elements->itemAt( $attr )) )
					throw new \EsoterException( 105, \Yii::t('error', '105') );

				if( in_array( 'require', $Elt->attributes ) && $Elt->attributes['require'] )
					$this->model->addRequired( $Elt->name );

				if( $Elt->name == 'birthday' )
				{
					$Elt->attributes['class']		= 'DatePicker';
					$Elt->attributes['datePicker']	= ( json_encode( array( 'lang' => \Yii::app()->params['lang'], 'format' => \Yii::app()->params['formatDate'] ) ) );
				}
			}
		}

	    $PP		 = $this->elements->itemAt('paymentType');
		$Invoice = new \Business\Invoice();

		// Traitement de loadBalancing ajouter par Jalal le 05/03/2016

		$porteur = \Yii::app()->params['porteur'];
		$site    = \Business\Site::load($this->model->idSite);

		if(isset($GLOBALS['loadBalancing'][$porteur]))
		{
			if(isset($GLOBALS['loadBalancing'][$porteur]['site']) == $site->code)
			{
				\Yii::import( 'ext.LBElement' );

				$TabLB = $GLOBALS['loadBalancing'][$porteur];
				$tabBalancing= array();

				foreach ($TabLB AS $Processeur => $val)
				{
					if($Processeur != 'site')
					{
						$tabBalancing[]= new LBElement($val, $Processeur);
					}
				}
				$order_storeName = lb_paymentprocessor($tabBalancing);
			}
		}

		foreach( $this->PaymentType as $PaymentType )
		{
		$nbInvoiceAsynch = $Invoice->checkCanPayByAsynch( $this->parent->getUser()->email, $this->parent->getProduct()->ref );

			if($PaymentType->type==2 && $nbInvoiceAsynch > 0){
			    
			    $PP->msg_pp = Yii::t( 'BDC', 'paymentType_Asynch');

			}else{
				// Porteur en cours :
				$porteur = \Yii::app()->params['porteur'];
				if(isset($order_storeName))
				{
					if(array_key_exists($PaymentType->name, $GLOBALS['loadBalancing'][$porteur]))
					{
						if($PaymentType->name == $order_storeName)
						{

							$PP->items[ $PaymentType->id ] = Yii::t( 'BDC', 'paymentType_'.$PaymentType->type );
							$PP->msg_pp = '';
							// Si c'est le 1er, alors ce sera celui par defaut :
							if( count($PP->items) == 1 )
								$this->model->paymentType = $PaymentType->id;
						}
					}else{

						$PP->items[ $PaymentType->id ] = Yii::t( 'BDC', 'paymentType_'.$PaymentType->type );
							$PP->msg_pp = '';
							// Si c'est le 1er, alors ce sera celui par defaut :
							if( count($PP->items) == 1 )
								$this->model->paymentType = $PaymentType->id;
					}

				}else{

					$PP->items[ $PaymentType->id ] = Yii::t( 'BDC', 'paymentType_'.$PaymentType->type );
						$PP->msg_pp = '';
						// Si c'est le 1er, alors ce sera celui par defaut :
						if( count($PP->items) == 1 )
							$this->model->paymentType = $PaymentType->id;

				}
			}
		}
	}

	/**
	 * Crée le rendu HTML du BDC
	 * @return string	Rendu HTML du BDC
	 */
	public function render()
	{
		// PaymentProcessor selectionné / par defaut :
		$PaymentProcessorType = $this->model->getPaymentProcessorType();

		// Affichage d'info si e nversion de DEV
		if( IS_DEV_VERSION )
		{
			

		}

		$output	= '<a name="BDC"></a>';
		$output	.= $this->renderBegin();
		$output	.= '<table cellspacing="4" class="bdc">';

		$output .= '<tr>';
		$output .= '	<td align="right" valign="top">&nbsp;</td>';
		$output .= '	<td align="left"><input type="hidden" id="Bdc_StringCTdate" name="Bdc[StringCTdate]" value="" /></td>';
		$output .= '</tr>';

		// BDC Fields :
		foreach( $this->bdcFields as $name => $type )
		{
			$Elt		= $this->elements->itemAt( $name );
			$className	= ( $this->model->getError( $name ) ) ? 'bdc_error ' : NULL;
			$style		= in_array( 'global', $type ) || in_array( $PaymentProcessorType->id, $type ) ? NULL : 'display:none;';

			// Classe utilisé par du JS pour afficher/masquer les champs en fct du PP
			foreach( $type as $isSpecialForPP )
			{
				if( $isSpecialForPP != 'global' )
					$className .= 'specialForPP_'.$isSpecialForPP.' ';
			}

			$output .= '<tr style="'.$style.'" class="bdc_input '.$className.'">';
			$output .= '	<td align="right" valign="top">'.$Elt->label.':</td>';
			$output .= '	<td align="left">'.$Elt->renderInput().'</td>';
			$output .= '</tr>';
		}

		// Payment Type :
		if( !$PaymentProcessorType->isFreePayment() && ($PP = $this->elements->itemAt('paymentType')) )
		{

			$output .= '<tr style="" class="bdc_input bdc_input_payment">';
			$output .= '	<td colspan="2" align="left">'.$PP->renderInput().'<br /><br />';
			$output .= (empty($PP->msg_pp))?$PP->msg_pp:''.'</td>';
			$output .= '</tr>';

		}

		// Buttons :
		foreach( $this->getButtons() as $name => $Elt )
		{
			$output .= '<tr>';
			$output .= '<td colspan="2" style="text-align: center;" align="center" class="'.\Yii::t('BDC', 'bdc_buttons').'">'.$Elt->render().'</td>';
			$output .= '</tr>';
		}

		$output .= '</table>';
        $output .= $this->renderEnd();

        return $output;
	}

	public function getStringCTdate(){

			return json_encode($this->model->StringCTdate);
	}
}

?>

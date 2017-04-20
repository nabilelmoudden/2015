<?php
/**
 * Description of SAV
 *
 * @author JulienL
 * @package Controllers
 */
class SAVController extends AdminController{
    public $layout	= '//sav/menu';

    /**
     * Initialisation du controleur
     */
    public function init(){
        DEFINE('INVOICE_CADDIE', 0);
        parent::init();
        // Url de la page de login ( pour les redirections faites par les Rules ) :
        $action = Yii::app()->getUrlManager()->parseUrl(Yii::app()->getRequest());
        if( $action=='SAV/login' || $action=='SAV/refundTool' ||  $action == 'SAV/validateOrder' || $action=='SAV/customerProfile' ){

		    Yii::app()->user->loginUrl = array('/SAV/login');
        }

        // Default page title :
        $this->setPageTitle('SAV Administration');
    }

    // ************************** RULES / FILTER ************************** //
    public function filters(){
        return array('accessControl', 'postOnly + delete' );
    }

    public function accessRules(){
        return array(
            array(
                'allow',
                'users' => array('@'),
                'roles' => array('ADMIN', 'REFUND_SERVICE'),
            ),
            array(
                'allow',
                'actions' => array( 'index', 'login' , 'logout'),
                'users' => array('*')
            ),
            array(
                'allow',
                'actions' => array('refundTool'),
                'roles' => array('ADMIN', 'REFUND_SERVICE', 'REFUND_TOOL', 'REFUND_TOOL_ADMIN')
            ),
            array(
                'allow',
                'actions' => array('validateOrder'),
                'roles' => array('ADMIN', 'REFUND_SERVICE', 'VALIDATE_ORDERS', 'VALIDATE_ORDERS_ADMIN')
            ),
            array(
                'allow',
                'actions' => array('customerProfile'),
                'roles' => array('ADMIN', 'REFUND_SERVICE', 'CUSTOMER_PROFILE', 'CUSTOMER_PROFILE_ADMIN')
            ),
            array(
                'allow',
                'actions' => array('customerInformation'),
                'roles' => array('ADMIN', 'REFUND_SERVICE', 'CUSTOMER_PROFILE', 'CUSTOMER_PROFILE_ADMIN')
            ),
            array(
                'allow',
                'actions' => array('customerTransactions'),
                'roles' => array('ADMIN', 'REFUND_SERVICE', 'CUSTOMER_PROFILE', 'CUSTOMER_PROFILE_ADMIN')
            ),
            array(
                'allow',
                'actions' => array('customerProfileUpdate', 'customerProfileShow', 'customerEmailShow'),
                'roles' => array('ADMIN', 'VALIDATE_ORDERS_ADMIN', 'CUSTOMER_PROFILE', 'CUSTOMER_PROFILE_ADMIN')
            ),
            array(
                'allow',
                'actions' => array('customerProfile'),
                'roles' => array('ADMIN', 'REFUND_SERVICE', 'CUSTOMER_PROFILE')
            ),array(
        				'allow',
        				'actions' => array('Ask'),
        				'roles' => array('ADMIN', 'REFUND_SERVICE', 'CUSTOMER_PROFILE')
        		),
        	array(
    				'allow',
    				'actions' => array('GetG2SSubPayment'),
    				'roles' => array('ADMIN', 'REFUND_SERVICE', 'CUSTOMER_PROFILE')
    		),
            array('allow'),
			//array('deny', 'deniedCallback' => array($this, 'actionRedirectUnothorizied'))
        );
    }

    // ************************** ACTION ************************** //
   

    public function actionIndex(){
        // Log l'action courante :
        $this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );
        //Rendu du contenu
        $user = \Business\User::load( Yii::app()->user->getId() );
        !$user  ? $this->redirect('login') : $this->render( '//sav/index' );
        
    }

    



    public function actionRedirectUnothorizied(){
        $this->render('//sav/404');
    }
    // ************************** Ask to refund ************************** //
    public function actionAsk(){

    	Yii::app()->clientScript->registerScriptFile( Yii::app()->baseUrl.'/js/ROI/ROI.js' );
    	Yii::app()->clientScript->registerScriptFile( Yii::app()->baseUrl.'/js/ROI/RoiRecherche.js' );
    	Yii::app()->clientScript->registerScriptFile( Yii::app()->baseUrl.'/js/ROI/jquery.mtz.monthpicker.js' );

    	Yii::import('ext.DateHelper');
    	$dateDebut = Yii::app()->request->getParam( 'Business\ROI' )['date'];
    	$dateFin = Yii::app()->request->getParam( 'Business\ROI' )['datefin'];
    	$periode = $dateDebut." à ".$dateFin;


    	$Invoices = new \Business\Invoice();




    	$Invoices2 = $Invoices->search3($dateDebut,$dateFin);




    	
    	//Rendu du contenu

    	$this->render( '//sav/Ask', array( 'Invoices' => $Invoices2 , 'periode'=>$periode ));






    }
    // ************************** REFUND TOOL ************************** //
    public function actionRefundTool(){
        // Log l'action courante :
        $this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );
        if(Yii::app()->request->getParam('prn') !== NULL){
			$this->exportRefunds(Yii::app()->request->getParam('prn'), Yii::app()->request->getParam('type'),Yii::app()->request->getParam('site'), Yii::app()->request->getParam('PP'));
		}
		$InvoiceUpdate = false;
        if(Yii::app()->request->getParam('id') !== NULL || Yii::app()->request->getParam('email') !== NULL){
		
				if(Yii::app()->request->getParam('version') == 'V1'){
					$User_V1 = new \Business\User_V1;
					$User = $User_V1::loadByEmail(Yii::app()->request->getParam('email'));

					if(!($InvoiceUpdate = \Business\Invoice_V1::load( Yii::app()->request->getParam('idInvoice') )))
					{return false;}

					if( Yii::app()->request->getParam('action') == "rembourser" ){
						$InvoiceUpdate->RefundStatus	   = 12;
						$InvoiceUpdate->ModificationDate   = date( Yii::app()->params['dbDateTime'] );
					}
					elseif( Yii::app()->request->getParam('action') == "reactivation" )
					{$InvoiceUpdate->RefundStatus	   = 11;}
					else
					{$InvoiceUpdate->RefundStatus	   = 22;}



					echo($InvoiceUpdate->save()) ? 'true' : 'false';

					if( Yii::app()->request->getParam('action') == "rembourser" ){
						// Excute web form of refund
						$invoices = \Business\Invoice_V1::loadByInternauteId2( $InvoiceUpdate->IDInternaute );
						$invoices->SendToEMV('UrlRefundDone');
					}
				}else{
					if(!($InvoiceUpdate = \Business\Invoice::load( Yii::app()->request->getParam('id'))))
					{return false;}

					if( Yii::app()->request->getParam('action') == "rembourser" ){
						$InvoiceUpdate->refundStatus	= \Business\Invoice::INVOICE_REFUNDED;
						$InvoiceUpdate->refundDate		= date( Yii::app()->params['dbDateTime'] );
					}
					elseif( Yii::app()->request->getParam('action') == "reactivation" )
						$InvoiceUpdate->refundStatus	= 11;
					else
						$InvoiceUpdate->refundStatus	= \Business\Invoice::INVOICE_REFUND_REFUSED;


					echo($InvoiceUpdate->save()) ? 'true' : 'false';

					if( Yii::app()->request->getParam('action') == "rembourser" ){
						// Excute web form of refund
						$invoices = \Business\Invoice::load(Yii::app()->request->getParam('id'));
						if( count($invoices->RecordInvoice) <= 0 )
						{echo ' no recordInvoice !';}
						for( $i=0; $i<count($invoices->RecordInvoice); $i++ ){
							if(Yii::app()->request->getParam('UrlRefundDone')){
								$invoices->SendToEMV('UrlRefundDone');
							}else{
								$invoices->SendToEMV('UrlRefundReceived');
							}
						}
					}
				}
				Yii::app()->end();
         
        }
        $Invoices = new \Business\AllInvoices;

        $sessionSearch = new CHttpSession;
        $sessionSearch->open();

        // Recherche toutes les invoices dont les coord users sont incomplete
        if( Yii::app()->request->getParam( 'incomplete' ) !== NULL ){
            $Provider	= $Invoices->searchIncompleteClientData( 'refundDate DESC' );
            $show		= 1;
        }else{

			Yii::app()->params['refund'] = $refund  =true; // c'est affectation au params pas une variable non utilisée
			if( !isset($_GET['Business\AllInvoices']) ) {
				if( Yii::app()->request->getParam( 'Business\allInvoiceDate' ) !== NULL ){
					$sessionSearch['dateDebut']    = Yii::app()->request->getParam( 'Business\allInvoiceDate' )['datedebut'];
					$sessionSearch['dateFin']      = (Yii::app()->request->getParam( 'Business\allInvoiceDate' )['datefin'] == '') ?  date("Y-m-d") : Yii::app()->request->getParam( 'Business\allInvoiceDate' )['datefin'] ;
				}else{
					$sessionSearch['dateDebut'] = date("Y-m-d",strtotime("-1 year", strtotime(date("Y-01-01"))));
					$sessionSearch['dateFin']   = date("Y-m-d");
				}
			}

			list($y1, $m1, $d1) = explode("-", $sessionSearch['dateDebut']);
			list($y2, $m2, $d2) = explode("-", $sessionSearch['dateFin']);

			if($sessionSearch['dateDebut'] == date("Y-m-d",strtotime("-1 year", strtotime(date("Y-01-01")))) && $sessionSearch['dateFin']   == date("Y-m-d")){
				$periode = $y1." et ".$y2;
			}
			else{
				$periode = "du ".$d1."/".$m1."/".$y1." au ".$d2."/".$m2."/".$y2;
			}
			$Provider = $Invoices = new \Business\AllInvoices;
            $show		= 0;
        }

        //Filtre recherche:
        if( Yii::app()->request->getParam( 'Business\Invoice' ) !== NULL ){
            $Invoices->attributes = Yii::app()->request->getParam( 'Business\Invoice' );
            if( Yii::app()->request->getParam( 'incomplete' ) !== NULL ){
				$Provider	= $Invoices->searchIncompleteClientData( 'refundDate DESC' , $Invoices->attributes['emailUser']);
                $show		= 1;
            }else{
				$Provider = $Invoices->searchRefundInvoice('refundDate DESC', $Invoices->attributes['emailUser']);
                $show	  = 0;
            }
        }

        // EXPORT EXCEL REFUND TOOL ALL
        if(Yii::app()->request->getParam('exportAll') !== NULL || Yii::app()->request->getParam('export') !== NULL || Yii::app()->request->getParam('export2') !== NULL ) {
			$Provider = $inv = new \Business\allInvoices;
			$data = 'RavenPaymentFile_v1.0,,,,'."\n";
			$data .= 'PaymentRoutingNumber, PaymentType,Amount,CurrencyCode,TemplateNumber'."\n";
            $invoices = $Provider;
            $vals_v1 = $vals_v2 = array();
            foreach($Provider->search()->getData() as $datom){
                if($datom->version == 'V2')
				{$vals_v2[] = $datom->getAttributes();}
				else
				{$vals_v1[] = array(
						'id'	=> 	$datom->id,
						'pricePaid'	=> 	$datom->pricePaid,
						'IDPaymentTransaction'	=> 	$datom->IDPaymentTransaction,
						'paymentProcessor'	=> 	\Business\Invoice_V1_complete::getPaymentProcessor($datom->IDPaymentTransaction),
						'currency'	=> 	$datom->currency,
						'ref1Transaction'	=> 	$datom->Ref1Transaction,
						'version'   => 'V1'
					);}
            }

			$vals = array_merge($vals_v1, $vals_v2);
			$i = $totalAmount = $prn = 0;
            foreach($vals as $invoice){
                if(Yii::app()->request->getParam('export') !== NULL && !strstr($invoice['paymentProcessor'], 'Check')) {continue;}
                if(Yii::app()->request->getParam('export2') !== NULL && strstr($invoice['paymentProcessor'], 'Check')) {continue;}
				if(!isset($invoice['id'])) {continue;}
				$i++;
				$totalAmount +=  $invoice['pricePaid'] * 100;
				if(isset($invoice['version']) && $invoice['version'] == 'V1'){
					$INV   = new \Business\Invoice_V1_complete('search');
					$prn   = $INV->getPRN($invoice['IDPaymentTransaction']);
				}else{
					$INV   = new \Business\Invoice('search');
					$prn   = $INV->getPRN($invoice['paymentProcessor']);
				}
				$data .= $prn.',';
                $data .= 'cc_refund,';
                $data .= ($invoice['pricePaid']*100).',';
                $data .= $invoice['currency'].',';
                $data .= $invoice['ref1Transaction'].',';
                $data .= "\n";
            }
			$data .= 'RavenFooter,'.$i.','.$totalAmount.',,';
            $porteur = \Yii::app()->params['porteur'];
			$name = substr($porteur, 0, 6).'_refunds_';
			if(Yii::app()->request->getParam('export') !== NULL)
			{$name .= 'cb_';}
			if(Yii::app()->request->getParam('export2') !== NULL)
			{$name .= 'cheks_';}
			$name .= date("Ymd").'112409_'.$i.'_'.$totalAmount.'_'.$prn.'.csv';
			header('Content-Disposition: attachment; filename="'.$name.'"');
			header("Content-type: application/x-msdownload; charset=iso-8859-15; encoding=iso-8859-15");
            header("Pragma: no-cache");
            header("Expires: 0");
			echo $data;
            exit;
        }

		//Get PRNsLists
		$prnTransa_V1   = new \Business\PaymentTransaction;
		$prnTransa_V2   = new \Business\PaymentProcessorType;
		$prn_V1   = $prnTransa_V1->getDistinctPRNs();
		$prn_V2   = $prnTransa_V2->getDistinctPRNs();
		$prns = array_merge($prn_V1, $prn_V2);

		//Rendu du contenu
        $this->render( '//sav/refundTool', array( 'Invoices' => $Invoices, 
        	'Provider' => $Provider , 
	        'show' => $show , 
	        'prns' => $prns, 
	        'dateDebut'=>$sessionSearch['dateDebut'],
	        'TYPE_REFUND' =>Yii::app()->request->getParam('Business\allInvoice_TYPE_REFUND'),
	        'dateFin'=>$sessionSearch['dateFin'], 
	        'periode' => $periode
        ));
    }

    /**
     * manage Invoice commentary Crud
     *
     * @author Mounir Mouih
     * @return view
     **/
    public function actionInvoiceCommentary(){
    	
        $version  = Yii::app()->request->getParam("version");
        $id  = Yii::app()->request->getParam("id");
        $invoice  = $version == "V2" ? \Business\Invoice::load($id) : \Business\Invoice_V1::load($id) ;
        if($invoice) :
            if ( Yii::app()->request->isPostRequest && Yii::app()->request->getParam("commentary") !== NULL) : #update
                $invoice->commentary = Yii::app()->request->getParam("commentary");
                $invoice->save();
                
            else :
                $this->renderPartial('//sav/invoiceCommentary',array(
                    'invoice'=> $invoice,
                    'version'=> $version
                ));
            endif;
        endif;
        Yii::app()->end();

    }

	//************************** EXPORT REFUNDS ***************************//
	public function exportRefunds($prn, $type, $site, $paymentProcessor){
		$prnTransa_V1   = new \Business\PaymentTransaction;
		$prnTransa_V2   = new \Business\PaymentProcessorType;

	
	    $prn_V1   = $prnTransa_V1->getInvoicesByPRNAndSite($prn, $paymentProcessor, $site, $type);
		$prn_V2   = $prnTransa_V2->getInvoicesByPRNAndSite($prn, $paymentProcessor, $site, $type);

		$data = 'RavenPaymentFile_v1.0,,,,'."\n";
		$data .= 'PaymentRoutingNumber, PaymentType,Amount,CurrencyCode,TemplateNumber'."\n";
		header('Content-Disposition: attachment; filename="RefundExport.csv"');
		header("Content-type: application/x-msdownload; charset=iso-8859-15; encoding=iso-8859-15");
		header("Pragma: no-cache");
		header("Expires: 0");
		
		if(strpos($paymentProcessor, '_1') == true)
		{
			$vals = $prn_V1;
		}else{
			$vals = $prn_V2;
		}
		$i = $totalAmount = 0;
		foreach($vals as $invoice){
			
			$i++;
			$totalAmount +=  $invoice['pricePaid'] * 100;
			$data .= $invoice['rpn'].',';
			$data .= 'cc_refund,';
			$data .= ($invoice['pricePaid'] * 100).',';
			$data .= $invoice['currency'].',';
			$data .= $invoice['Ref1Transaction'].',';
			$data .= "\n";
		}
		$data .= 'RavenFooter,'.$i.','.$totalAmount.',,';
		$porteur = \Yii::app()->params['porteur'];
		$name = substr($porteur, 0, 6).'_refunds_';
		if(Yii::app()->request->getParam('export') !== NULL)
			$name .= 'cb_';
		if(Yii::app()->request->getParam('export2') !== NULL)
			$name .= 'cheks_';
		$name .= date("Ymd").'112409_'.$i.'_'.$totalAmount.'_'.$prn.'.csv';
		header('Content-Disposition: attachment; filename="'.$name.'"');
		header("Content-type: application/x-msdownload; charset=iso-8859-15; encoding=iso-8859-15");
		header("Pragma: no-cache");
		header("Expires: 0");
		echo $data;
		exit;
	}

    // ************************** VALIDATE ORDER ************************** //
    public function actionValidateOrder(){
        // Log l'action courante :
        $this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN )));
        // Sauvegarde des order en attente :
        if( Yii::app()->request->getParam( 'save' ) !== NULL && Yii::app()->request->getParam( 'check' ) !== NULL ){

			if(!(Yii::app()->request->getParam( 'invoiceId' ))){
				$idInvoice	= Yii::app()->request->getParam( 'invoiceId' );
			}else{
				$idInvoice	= Yii::app()->request->getParam( 'ID' );
			}
            if(Yii::app()->request->getParam( 'version' ) == 'V1'){
				if(Yii::app()->request->getParam( 'complete' ) != 2){

					if( !($PayementTransaction = \Business\PaymentTransaction::load( $idInvoice ))){
						return false;
					}

					$saveStatus	= true;
					$PayementTransaction->status = 1;
					$PayementTransaction->ModificationDate = date( Yii::app()->params['dbDateTime'] );

					$Invoice	= new \Business\Invoice_V1( 'search' );

					$Invoice->IDInternaute 	             = $PayementTransaction->internauteID;
					$Invoice->RefProduct  			     = $PayementTransaction->productRef;
					$Invoice->Qty  			             = $PayementTransaction->productQty;
					$Invoice->CreationDate  		     = date( Yii::app()->params['dbDateTime'] );
					$Invoice->Ref1Transaction			 = $PayementTransaction->externId;
					$Invoice->InvoiceStatus  			 = 2;
					$Invoice->UnitPrice      			 = $PayementTransaction->productAtiPrice;
					$Invoice->PricePaid       			 = Yii::app()->request->getParam('checkAmount');
					$Invoice->NumCheck        			 = Yii::app()->request->getParam('checkNum');
					$Invoice->RefBatchSelling 			 = $PayementTransaction->refBatchSelling;
					$Invoice->RefDiscount     			 = $PayementTransaction->refDiscount;
					$Invoice->RefPricingGrid  			 = $PayementTransaction->refPricingGrid;
					$Invoice->RefundStatus   	         = 0;
					$Invoice->IDPaymentTransaction       = $PayementTransaction->id;
					$Invoice->Site      	             = $PayementTransaction->Site;
					$Invoice->Devise    	  			 = $PayementTransaction->currency;
					$Invoice->deviseinformativecheque    = Yii::app()->request->getParam('checkCurrency');

					$PariteInvoice						 = new \Business\PariteInvoice('search');
					$parite 							 = $PariteInvoice->loadByDevise($PayementTransaction->currency);

					$Invoice->Parite 	 				 = $parite->parite;

				}else{

					if( !($Invoice = \Business\Invoice_V1_complete::load( $idInvoice ))){
                    	return false;
              	    }

					$saveStatus	= true;
					$Invoice->NumCheck	= Yii::app()->request->getParam('checkNum');
					$Invoice->PricePaid = Yii::app()->request->getParam('checkAmount');
					$Invoice->deviseinformativecheque    = Yii::app()->request->getParam('checkCurrency');
					$Invoice->invoiceStatus = \Business\Invoice::INVOICE_PAYED;

					if(!$Invoice->save()){
                    	$saveStatus = false;
                    }else{

						echo 'La commande a été validé avec succès !';
						Yii::app()->user->setFlash( "success", Yii::t( 'SAV', 'updateOK' ) );
						Yii::app()->end();
					}

				}

				if(!$PayementTransaction->save()){
                    $saveStatus = false;
                }else{
					if(!$Invoice->save()){
                    	$saveStatus = false;
                    }else{
						$type	    = 'UrlPaiementCheque';
						if(Yii::app()->request->getParam( 'complete' ) != 2){

							if($Invoice->SendToEMV($type) === false){
								 $saveStatus = false;
							}else{

								 echo 'La commande a été validé avec succès !';
								 Yii::app()->user->setFlash( "success", Yii::t( 'SAV', 'updateOK' ) );
								 Yii::app()->end();

							}
						}else{

							 echo 'La commande a été validé avec succès !';
							 Yii::app()->user->setFlash( "success", Yii::t( 'SAV', 'updateOK' ) );
							 Yii::app()->end();

						}

					}
				}

            }else{

                if( !($Invoice = \Business\Invoice::load( $idInvoice ))){
                    return false;
                }

                $saveStatus	= true;
                $Invoice->numCheck	= Yii::app()->request->getParam('checkNum');
                $Invoice->pricePaid = Yii::app()->request->getParam('checkAmount');
                $Invoice->invoiceStatus = \Business\Invoice::INVOICE_PAYED;
				$Invoice->modificationDate = date( Yii::app()->params['dbDateTime'] );
				$Invoice->deviseinformativecheque = Yii::app()->request->getParam('checkCurrency');

                if(!$Invoice->save()){
                    $saveStatus = false;
                }else{
					if(Yii::app()->request->getParam( 'complete' ) != 2){
						$type	    = 'UrlPaiementCheque';
						$Invoice->sendRequestToEMV($type);

						for($i=0; $i < count($Invoice->RecordInvoice); $i++ ){
							$Product	= $Invoice->RecordInvoice[$i]->Product;
							$Router		= $Product->RouterEMV( array('condition' => 'type = "'.$type.'"'));
							for($j=0; $j<count($Router); $j++){
								$Router[$j]->sendRequest($Invoice, false, true);
							}
						}

					}

                    echo 'La commande a été validé avec succès !';
                    Yii::app()->user->setFlash( "success", Yii::t( 'SAV', 'updateOK' ) );
                    Yii::app()->end();
                }
            }

        }

        // Invoice check en attente/Complete :
        $Provider = $Invoices = new \Business\AllInvoices;

        

        // Filtre recherche :
        if( Yii::app()->request->getParam( 'Business\Invoice' ) !== NULL ){
            $Invoices->attributes = Yii::app()->request->getParam( 'Business\Invoice' );
           
        }
        $showButtons = 1;
        

        $this->render( '//sav/validateOrder', array( 'Invoices' => $Invoices, 'showButtons' => $showButtons, 'complete' => Yii::app()->request->getParam( 'complete' ) ) );
    }

	// ************************** VALIDATE ORDER MULTIBANCO ************************** //
    public function actionValidateOrderMB(){
		Yii::app()->params['mb'] = true;
		// Log l'action courante :
        $this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN )));

		
	   // Invoice check en attente/Complete :
        $Provider = $Invoices = new \Business\AllInvoices;

        // Filtre recherche :
        if( Yii::app()->request->getParam( 'Business\Invoice' ) !== NULL ){
            $Invoices->attributes = Yii::app()->request->getParam( 'Business\Invoice' );
        }
        $showButtons = 1;
        if(Yii::app()->user->getState("User")->Role[0]->name !== 'VALIDATE_ORDERS'){
            $showButtons = 0;
        }

        $this->render( '//sav/validateOrderMB', array( 'Invoices' => $Invoices, 'showButtons' => $showButtons, 'complete' => Yii::app()->request->getParam( 'complete' ) ) );
    }


    // ************************** CUSTOMER PROFILE ************************** //
    public function actionCustomerProfile(){
       
        // Log l'action courante :
        $this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );

        // Ajax :
        if( Yii::app()->request->getParam( 'id' ) !== NULL && Yii::app()->request->getParam( 'status' ) !== NULL && Yii::app()->request->getParam( 'version' ) !== NULL ){
            if(Yii::app()->user->getState("User")->Role[0]->name !== 'CUSTOMER_PROFILE'){
                if(Yii::app()->request->getParam( 'version' ) == 'V1'){
                    if( !($User = \Business\User_V1::load( Yii::app()->request->getParam('id') )) ){
                        if( !($User = \Business\User_V1::load( Yii::app()->request->getParam('id') )) )
                            return false;
                    }
                }else{
                    if( !($User = \Business\User::load( Yii::app()->request->getParam('id') )) ){
                    	 return false;
                    }
                }
                $User->visibleDesinscrire = Yii::app()->request->getParam('status');
                if((int) $User->visibleDesinscrire == 0){
                    $User->SendToEMV('inscrire');
                }else{
                    $User->SendToEMV('desincrire');
                }
                if($User->save())
                    echo 'true';
                else
                    Yii::app()->user->setFlash( "error", Yii::t( 'SAV', 'updateNOK' ) );
                Yii::app()->end();
            }else{
                echo "Vous n'avez pas le droit d'accéder à cette fonctionnalité !";
                Yii::app()->end();
            }
        }


        $User		= new \Business\AllUsers;
        // Recherche tous clients OU tous les clients a surveiller
        

        // Filtre recherche :
        if( Yii::app()->request->getParam( 'Business\AllUsers' ) !== NULL )
            $User->attributes = Yii::app()->request->getParam('Business\AllUsers');

        //Rendu du contenu
        $this->render( '//sav/customerProfile', array( 'User' => $User/*, 'monitor' => $monitor */) );
    }

    public function actionCustomerProfileUpdate(){
        // Log l'action courante :
        $this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );

        if( Yii::app()->request->getParam('id') !== NULL ){
            if(Yii::app()->request->getParam('version') == 'V1'){
                $User_V1 = new \Business\User_V1;
                $User = $User_V1::getUserById(Yii::app()->request->getParam('id'));
				$User->id = $User->ID;
                $User->civility = $User->Civility;
                $User->zipCode = $User->CP;
                $User->savComments = $User->Comment;
                $User->firstName = $User->Firstname;
                $User->lastName = $User->Lastname;
                $User->email = $User->Email;
                $User->address = $User->Address;
                $User->country = $User->Country;
                $User->city = $User->City;
                $User->phone = $User->Phone;
                $User->birthday = $User->Birthday;
            }else{
                if( !($User = \Business\User::load( Yii::app()->request->getParam('id') )) )
                    return false;
            }
        } else if( Yii::app()->request->getParam( 'email' ) !== NULL ){
            if(Yii::app()->request->getParam('version') == 'V1'){
                $User_V1 = new \Business\User_V1;
                $User = $User_V1::loadByEmail(Yii::app()->request->getParam('email'));
				$User->id = $User->ID;
                $User->civility = $User->Civility;
                $User->zipCode = $User->CP;
                $User->savComments = $User->Comment;
                $User->firstName = $User->Firstname;
                $User->lastName = $User->Lastname;
                $User->email = $User->Email;
                $User->address = $User->Address;
                $User->country = $User->Country;
                $User->city = $User->City;
                $User->phone = $User->Phone;
                $User->birthday = $User->Birthday;
            } elseif( !($User = \Business\User::loadByEmail( Yii::app()->request->getParam( 'email' ) )) )
                return false;
        }
        else
            return false;

        // Affiche une notice si le client a le status 'savToMonitor' != 0
        

        // Met a jours les infos clients, et sauvegarde en bdd :
        if( ($form = Yii::app()->request->getParam( 'Business\User' )) !== NULL  || ($form = Yii::app()->request->getParam( 'Business\User_V1' )) !== NULL ){
            if(Yii::app()->request->getParam('version') == 'V2'){
                $User->attributes = $form;
                if($User->save() && $User->SendToEMV())
                    Yii::app()->user->setFlash( "success", Yii::t( 'SAV', 'updateOK' ) );
                else
                    Yii::app()->user->setFlash( "error", Yii::t( 'SAV', 'updateNOK' ) );
            }elseif(Yii::app()->request->getParam('version') == 'V1'){
                $User_V1 = new \Business\User_V1;
                if(Yii::app()->request->getParam('email') !== NULL){
					$User = $User_V1::loadByEmail(Yii::app()->request->getParam('email'));
				}else{
					$User = $User_V1::getUserById(Yii::app()->request->getParam('id'));
				}

                $User->attributes = $form;
                $User->civility = $User->Civility = $form['civility'];
                $User->firstName = $User->Firstname = $form['firstName'];
                $User->lastName  = $User->Lastname  = $form['lastName'];
                $User->email 	 = $User->Email = $form['email'];
                $User->address 	 = $User->Address = $form['address'];
                $User->country 	 = $User->Country = $form['country'];
                $User->city 	 = $User->City = $form['city'];
                $User->phone 	 = $User->Phone = $form['phone'];
                $User->birthday  = $User->Birthday = $form['birthday'];
                $User->savComments  = $User->Comment = $form['savComments'];
                $User->zipCode   = $User->CP = $form['zipCode'];

                if($User->save() && $User->SendToEMV('updateClient'))
                    Yii::app()->user->setFlash( "success", Yii::t( 'SAV', 'updateOK' ) );
                else
                    Yii::app()->user->setFlash( "error", Yii::t( 'SAV', 'updateNOK' ) );
            }
        }

        //Rendu du contenu
        if( Yii::app()->request->getParam('partialRender') !== NULL )
            $this->renderPartial( '//sav/customerProfileUpdate', array( 'User' => $User ) );
        else
            $this->render( '//sav/customerProfileUpdate', array( 'User' => $User ) );
    }

    public function actionCustomerProfileShow(){
		$productDescription = array();
		$CtDates = array();

		if( Yii::app()->request->getParam('changedateCt') !== NULL && Yii::app()->request->getParam('changedateCt') !=='' ){

		    $version = Yii::app()->request->getParam('version');

			if( Yii::app()->request->getParam('tableCTdate') !== NULL ){

				$tableCTdate = Yii::app()->request->getParam('tableCTdate');

				if($version == 'V2'){

					$IdRecordInvoiceAnnexe = Yii::app()->request->getParam('IdRecordInvoiceAnnexe');
					$RecordInvoiceAnnexe = \Business\RecordInvoiceAnnexe::load($IdRecordInvoiceAnnexe);

					if( is_object($RecordInvoiceAnnexe ))
					{
						$RecordInvoiceAnnexe->productExt	= new \StdClass();

						$additionnalValues					= $RecordInvoiceAnnexe->productExt;
						$additionnalValues->CTdate			= json_decode(json_decode(json_encode((string)$tableCTdate)));
						$RecordInvoiceAnnexe->productExt	= $additionnalValues;

						if($RecordInvoiceAnnexe->save()){
							Yii::app()->user->setFlash( "success", Yii::t( 'SAV', 'updateOK' ) );
						}
					}
				}

			}else{
				$version = Yii::app()->request->getParam('version');
				if($version == 'V2'){

					$version = Yii::app()->request->getParam('version');
					$invoices = \Business\Invoice::load(Yii::app()->request->getParam('IdInvoice'));

					if( !count($invoices->RecordInvoice) <= 0 )
					{
					$refProduct   		 = $invoices->RecordInvoice[0]->refProduct;
					$RecordInvoiceAnnexe = $invoices->RecordInvoice[0]->RecordInvoiceAnnexe;

						if(is_object($RecordInvoiceAnnexe)){

							$CtDates = $RecordInvoiceAnnexe->productExt->CTdate;

						}

					}
					$CtDates = (array) $CtDates;

				}else{

					$invoice = \Business\Invoice_V1_complete::load(Yii::app()->request->getParam('IdInvoice'));
					if( !is_object($invoice )){
						return false;
					}

					if( !($PayementTransaction = \Business\PaymentTransaction::load( $invoice->IDPaymentTransaction ))){
						return false;
					}

					$refProduct   		 = $invoice->productRef;
					$RecordInvoiceAnnexe = $PayementTransaction;

					for( $int=18; $int<=27; $int++ ){
						$intEMVADMIN = 'EMVADMIN'.$int;
						if($RecordInvoiceAnnexe->$intEMVADMIN != NULL){
							$CtDates[$intEMVADMIN] = $RecordInvoiceAnnexe->$intEMVADMIN;
						}
					}
				}

				$this->renderPartial('//sav/customerChangeDateCTShow', array('RecordInvoiceAnnexe' => $RecordInvoiceAnnexe, 'refProduct' => $refProduct ,'CtDates' => $CtDates,'version' => $version));
				exit;
			}
        }

		if( Yii::app()->request->getParam('resendProduct') !== NULL ){
            if(Yii::app()->request->getParam('id') == NULL){
				if(Yii::app()->request->getParam('version') == 'V1'){
					$User = \Business\User_V1::loadByEmail( Yii::app()->request->getParam('email'));
					$userId = $User->ID;
				} else {
					$User = \Business\User::loadByEmail(Yii::app()->request->getParam('email'));
					$userId = $User->id;
				}
			} else {
				$userId = Yii::app()->request->getParam('id');
			}
			$this->resendProduct($userId, Yii::app()->request->getParam('updateIdInvoice'), Yii::app()->request->getParam('updateRefundStatus'), Yii::app()->request->getParam( 'email' ));
            exit;
        }

        // Log l'action courante :
        $this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );
        if( Yii::app()->request->getParam('version') == NULL ){
            return false;
        } else {
            if(Yii::app()->request->getParam('version') == 'V1'){
                if( Yii::app()->request->getParam( 'id' ) !== NULL ){
                    if( !($User = \Business\User_V1::load( Yii::app()->request->getParam('id'))) )
                        return false;
                }elseif( Yii::app()->request->getParam('email') !== NULL ){
                    $User_V1 = new \Business\User_V1;
					$User = $User_V1::loadByEmail(Yii::app()->request->getParam('email'));
					if(!$User)
						return false;
                }else{
                    return false;
                }
            } else {
                if( Yii::app()->request->getParam( 'id' ) !== NULL ){
                    if( !($User = \Business\User::load( Yii::app()->request->getParam('id') )) ){
                        return false;
                    }
                } else if( Yii::app()->request->getParam( 'email' ) !== NULL ){
                    if( !($User = \Business\User::loadByEmail( Yii::app()->request->getParam( 'email' ) )) )
                        return false;
                } else
                    return false;
            }
        }

        // Mise a jour du refundStatus :
        if( Yii::app()->request->getParam( 'updateIdInvoice' ) !== NULL && Yii::app()->request->getParam( 'updateRefundStatus' ) !== NULL ){
            // Excute web form of validation
            if(Yii::app()->request->getParam('version') == 'V1'){
                $invoices = \Business\Invoice_V1::load( Yii::app()->request->getParam( 'updateIdInvoice' ) );
            }else{
                $invoices = \Business\Invoice::load( Yii::app()->request->getParam( 'updateIdInvoice' ) );
            }

            if(Yii::app()->request->getParam( 'updateRefundStatus' ) == 11 ){
                $invoices->SendToEMV('UrlRefundReceived');
            }

            /************Fin Global EMV *****************************/

            if(Yii::app()->request->getParam('version') == 'V2' && !($InvoiceUpdate = \Business\Invoice::load( Yii::app()->request->getParam( 'updateIdInvoice' ) )) )
                return false;

            if(Yii::app()->request->getParam('version') == 'V1' && !($InvoiceUpdate = \Business\Invoice_V1::load( Yii::app()->request->getParam( 'updateIdInvoice' ) )) )
                return false;

            if(Yii::app()->request->getParam('version') == 'V1'){
                $InvoiceUpdate->RefundStatus	= Yii::app()->request->getParam( 'updateRefundStatus' );
                $InvoiceUpdate->ModificationDate		= date( Yii::app()->params['dbDateTime'] );

                // Enregistrer RefundStatus dans PaymentTransaction
                $PayementTransaction1 = \Business\PaymentTransaction::load($InvoiceUpdate->IDPaymentTransaction);
                $PayementTransaction1->RefundStatus = Yii::app()->request->getParam('updateRefundStatus');
                $PayementTransaction1->save();
            }else{
                $InvoiceUpdate->refundStatus	= Yii::app()->request->getParam( 'updateRefundStatus' );
                $InvoiceUpdate->refundDate		= date( Yii::app()->params['dbDateTime'] );
                $InvoiceUpdate->agent  			=  ($_SESSION[Yii::app()->user->getStateKeyPrefix().'User']["email"]);
            }
            if($InvoiceUpdate->save())
                Yii::app()->user->setFlash( "success", Yii::t( 'SAV', 'updateOK' ) );
            else
                Yii::app()->user->setFlash( "error", Yii::t( 'SAV', 'updateNOK' ) );
        }

       if(Yii::app()->request->getParam('version') == 'V1'){
            $Invoice			= new \Business\Invoice_V1;
            if(Yii::app()->request->getParam('email') !== NULL){
				if(Yii::app()->request->getParam('mb')){
					$Results_MB = \Business\PaymentTransaction::loadByMail(Yii::app()->request->getParam('email'));
					$Results = $Invoice->loadByInternauteId($User->ID);
				} else {
					$Results = $Invoice->loadByInternauteId($User->ID);
				}
			}else{
				$Results = $Invoice->loadByInternauteId(Yii::app()->request->getParam('userId'));
				for( $i=0; $i<count($Results); $i++ ){
					if(isset($Results[$i]->RefProduct)){
						/* Youssef HARRATI le: 09/09/2015 */
						if($Results[$i]->RefProduct=="en_alisha_voygratuit"){
							$productDescription[] = "en_alisha_voygratuit";
						}else{
							$productV1 = \Business\Product_V1::loadByRef($Results[$i]->RefProduct);
						
						     if(is_object($productV1) )
							     $productDescription[] = $productV1->getAttributes()['Description'];
						}
					}
				}
			}
			$url_product 		= $Invoice->getUrlProducts($Results);
        } else {
            $Invoice			= new \Business\Invoice('search');
            $Invoice->emailUser = $User->email;
            $Results			= $Invoice->search()->getData();
			$url_product		= '';
        }
        //Rendu du contenu
        if( Yii::app()->request->getParam('partialRender') !== NULL ){
            $rendredFile = (Yii::app()->request->getParam('version') == 'V1' ) ? '//sav/customerProfileShowV1' : '//sav/customerProfileShow';
			if(Yii::app()->request->getParam('mb')){
				if(Yii::app()->request->getParam('version') == 'V1')
					$this->renderPartial('//sav/customerProfileShowV1_mb', array( 'User' => $User, 'Invoices' => $Results_MB, 'productDescription' => $productDescription, 'url_product' => $url_product));
				else
					$this->renderPartial($rendredFile, array( 'User' => $User, 'Invoices' => $Results, 'productDescription' => $productDescription, 'url_product' => $url_product));
			}
			else
				$this->renderPartial($rendredFile, array( 'User' => $User, 'Invoices' => $Results, 'productDescription' => $productDescription, 'url_product' => $url_product));
        }else
		{$this->render('//sav/customerProfileShow', array('User' => $User, 'Invoices' => $Results));}
    }

	public function actionCustomerEmailShow(){
		$porteur = \Yii::app()->params['porteur'];
		$tabLinkEMV = $GLOBALS['SendMailSAV'][$porteur];

		if(Yii::app()->request->getParam('sendmailVG') !== NULL ){

			$url_webForm = $tabLinkEMV[Yii::app()->request->getParam('url_web_form')];

			if(Yii::app()->request->getParam('iduser') != NULL){

					if(Yii::app()->request->getParam('version') == 'V1'){
						$User = \Business\User_V1::load(Yii::app()->request->getParam('iduser'));

						$WF = new \WebForm($url_webForm);
						$WF->setTokenWithUser_V1( $User );
						return $WF->execute( true );
					} else {
						$User = \Business\User::load(Yii::app()->request->getParam('iduser'));

						$WF = new \WebForm($url_webForm);
						$WF->setTokenWithUser( $User );
						return $WF->execute( true );
					}
			 }

		}else{

			// Log l'action courante :
			$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_ADMIN ) ) );

			$version = Yii::app()->request->getParam('version');

			if(Yii::app()->request->getParam('id') != NULL){

					if(Yii::app()->request->getParam('version') == 'V1'){
						$User = \Business\User_V1::load(Yii::app()->request->getParam('id'));
						$userId = $User->ID;
					} else {
						$User = \Business\User::load(Yii::app()->request->getParam('id'));
						$userId = $User->id;
					}
			 }

			$this->renderPartial('//sav/customerSendMailShow', array('User' => $User,'userId' =>$userId, 'tabLinkEMV'=> $tabLinkEMV, 'version'=> $version));

		}
    }

    public function resendProduct($userId, $invoiceId, $refundStatus, $emailUser){
		if(Yii::app()->request->getParam('version') == 'V2'){
            if( $userId !== NULL ){
                if( !($User = \Business\User::load( $userId )) )
				{ return false;}
            }else if( $emailUser !== NULL ){
                if( !($User = \Business\User::loadByEmail($emailUser)) )
				{return false;}
            }
            else
			{return false;}

            if($invoiceId !== NULL && $refundStatus !== NULL)
			{$invoices = \Business\Invoice::load( $invoiceId );}

            if( Yii::app()->request->getParam('resendProduct') !== NULL ){
                $invoices->SendToEMV('UrlResendProduct');
            }

            if( !($InvoiceUpdate = \Business\Invoice::load( $invoiceId)) )
			{return false;}
        }else{
            if( $userId !== NULL ){
                $User_V1 = new \Business\User_V1;
                $User = $User_V1::getUserById($userId);
            }else
			{return false;}

            if($invoiceId !== NULL){
                $Invoice			= new \Business\Invoice_V1;
                $InvoiceUpdate = $invoices = \Business\Invoice_V1::load($invoiceId);
            }

            if( Yii::app()->request->getParam('resendProduct') !== NULL ){
                $invoices->SendToEMV('UrlResendProduct');
            }
        }

        $InvoiceUpdate->lastSend		= date( Yii::app()->params['dbDateTime'] );;
        $InvoiceUpdate->countSend		= $InvoiceUpdate->countSend + 1;

        if( $InvoiceUpdate->save() )
		{Yii::app()->user->setFlash( "success", Yii::t( 'SAV', 'updateOK' ) );}
        else
		{Yii::app()->user->setFlash( "error", Yii::t( 'SAV', 'updateNOK' ) );}


        if(Yii::app()->request->getParam('version') == 'V1'){
			$Invoice			= new \Business\Invoice_V1;
            $Results = $Invoice->loadByInternauteId($userId);
        }else{
            $Invoice			= new \Business\Invoice( 'search' );
            $Invoice->emailUser = $User->email;
            $Results			= $Invoice->search()->getData();
        }

        //Rendu du contenu

        if( Yii::app()->request->getParam('partialRender') !== NULL ){
            $rendredFile = (Yii::app()->request->getParam('version') == 'V1' ) ? '//sav/customerProfileShowV1' : '//sav/customerProfileShow';
            $this->renderPartial($rendredFile, array( 'User' => $User, 'Invoices' => $Results));
        }else
		{$this->render('//sav/customerProfileShow', array('User' => $User, 'Invoices' => $Results));}
    }

    // ************************** LOGIN ************************** //
    public function actionLogin(){
        $this->pageTitle = 'Login';
        $model = new LoginForm;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form'){
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['LoginForm'])){
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if( $model->validate() && $model->login() ){
                // Log l'action courante :
                $this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_LOGIN ) ) );
                $this->redirect( Yii::app()->baseUrl.'/index.php/SAV/index' );
            }
        }

        // Methode appeler pour afficher une vue
        // 1er argument => nom de la vue
        // 2eme argument => tableau contenant les variables a transmettre a la vue
        $this->render( '//sav/login', array('model' => $model) );
    }

    public function actionLogout(){
        // Log l'action courante :
        $this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_LOGOUT ) ) );

        Yii::app()->user->logout();
        $this->redirect( Yii::app()->baseUrl.'/index.php/SAV/index' );
    }

    // ************************** SETTER ************************** //
    public function setPorteur( $porteur ){
        $this->porteur = $porteur;
        $this->loadConfigForPorteur( $porteur );
    }

    // ************************** GETTER ************************** //
    public function porteur(){
        return $this->porteur;
    }

    public function statut(){

    	for ($i = 0; $i < count(Yii::app()->user->getState("User")->Role); $i++)
    	{



    		if (Yii::app()->user->getState("User")->Role[$i]->name =="ASK_TO_REFUND")
			{return  Yii::app()->user->getState("User")->Role[$i]->name;}




    	}

    	$i--;

    	return  Yii::app()->user->getState("User")->Role[$i]->name;



    }


    /**
     * @author Mounir MOUIH
     * @return customer information based on a given email
     */

    function actionCustomerInformation(){

    	global $isoter_file_mapping;

    	if(isset($_GET['pr']) && !isset($_GET['p'])){
    		$isoter_file = strip_tags($_GET['pr']);
    		$porteur = isset($isoter_file_mapping[$isoter_file]) ? $isoter_file_mapping[$isoter_file] : 'fr_rinalda';
    		$this->redirect( Yii::app()->baseUrl.'/index.php/SAV/customerInformation?'.$_SERVER['QUERY_STRING'].'&p='.$porteur );
    	}
    	// Log l'action courante :
		$this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_LOGIN ) ) );

    	if(isset($_GET['customerID'])) :
	    	if( Yii::app()->user->isGuest ) :
	    		/* if user is not logged in */
	    		$model = new LoginForm;
	    		if (isset($_POST['LoginForm'])) :
	    			/* process Login request */
		            $model->attributes =  Yii::app()->request->getpost('LoginForm');

		            if( $model->validate() && $model->login() ) :
		                $this->redirect( $_SERVER['HTTP_REFERER'] );  /* redirection  */
		            endif;

		        endif;
		       /* Login form */
	    		$this->render( '//sav/login', array('model' => $model) );
	    		die ;
	    	else :
	    		$email = strip_tags( Yii::app()->request->getParam('customerID') );

				$model = new \Business\User;  //V2_user table
				$model->email =  $email;
				/*  Load users from V2_user table */
				$user = $model->loadByEmail($email);
				if($user):
					$vars =  get_object_vars($user);
					$this->render('/sav/customerInformation', array('data' => $vars));
				else :
					/* if not found in V2_user table, check internaut table */
					$model = new \Business\Internaute;  //internaute
					$model->Email =  $email;

					$internaute = $model->loadByEmail($email); // check internaute table

					if($internaute):
						$vars =  get_object_vars($internaute);
						$this->render('/sav/customerInformation', array('data' => $vars));
					else :
						header("HTTP/1.0 404 Not Found");
		    			die("<h3>No customer has been found</h3>");
		    		endif;
				endif;

			endif;
	  	else :
    		header("HTTP/1.0 404 Not Found");
    		$this->render('//sav/404');
    	endif;
    }


    /**
     * @author Mounir MOUIH
     * @return customer Transactions based on a given email
     */
    function actionCustomerTransactions(){

        global $isoter_file_mapping;

        if(isset($_GET['pr']) && !isset($_GET['p'])){
            $isoter_file = strip_tags($_GET['pr']);
            $porteur = isset($isoter_file_mapping[$isoter_file]) ? $isoter_file_mapping[$isoter_file] : 'fr_rinalda';
            $this->redirect( Yii::app()->baseUrl.'/index.php/SAV/customerTransactions?'.$_SERVER['QUERY_STRING'].'&p='.$porteur );
        }
        // Log l'action courante :
        $this->logAction( new CEvent( $this, array( 'action' => \Business\Log::ACTION_LOGIN ) ) );

        if(isset($_GET['customerID'])) :
            if( Yii::app()->user->isGuest ) :
                /* if user is not logged in */
                $model = new LoginForm;
                if (isset($_POST['LoginForm'])) :
                    /* process Login request */
                    $model->attributes =  Yii::app()->request->getpost('LoginForm');

                    if( $model->validate() && $model->login() ) :
                        $this->redirect( $_SERVER['HTTP_REFERER'] );  /* redirection  */
                    endif;

                endif;
               /* Login form */
                $this->render( '//sav/login', array('model' => $model) );
                die ;
            else :
                $email = strip_tags( Yii::app()->request->getParam('customerID') );

                $model = new \Business\User;  //V2_user table
                $model->email =  $email;
                /*  Load users from V2_user table */
                $user = $model->loadByEmail($email);
                $version  = 'V2';
                $invoices = [];
                $invoices['V2']['PAYED'] = \Business\Invoice::getByEmail($email);
                $invoices['V2']['IN_PROGRESS'] = \Business\Invoice::getByEmail($email, \Business\Invoice::INVOICE_IN_PROGRESS);

                /* V1 */
                $internaute = \Business\Internaute::loadByEmail($email); // check internaute table
                if($internaute) :
                    if( !$user ):
                        $user =  $internaute;
                        $version  = 'V1';
                    endif;
                    $user_id = $internaute->ID;
                elseif(!$user) :
                    header("HTTP/1.0 404 Not Found");
                    die("<h3>No customer has been found</h3>");
                endif;
                $invoices_1  = \Business\PaymentTransaction::getByEmail($email, \Business\PaymentTransaction::INVOICE_IN_PROGRESS);
                $invoices_1_PAYED  = \Business\PaymentTransaction::getByEmail($email);
                foreach ($invoices_1 as $key => $value) {
                    $invoices['V1']['PaymentTransaction']['IN_PROGRESS'][$value->id] = $value ;
                }
                foreach ($invoices_1_PAYED as $key => $value) {
                    $invoices_1_PAYED[$value->id] = $value ;
                }
                if ( isset($user_id) ) :
                    $invoices_IN_PROGRESS  = \Business\Invoice_V1::getByEmail($user_id, \Business\Invoice_V1::INVOICE_IN_PROGRESS);
                else :
                    $invoices_IN_PROGRESS  = [];
                endif;
                foreach ($invoices_IN_PROGRESS as $key => $value) {
                    if( !isset($invoices['V1']['IN_PROGRESS'][$value->IDPaymentTransaction]) && $value->IDPaymentTransaction !== NULL  ) :

                       if(isset($invoices['V1']['PaymentTransaction']['IN_PROGRESS'][$value->IDPaymentTransaction])) :
                            $value->deviseinformativecheque = $invoices['V1']['PaymentTransaction']['IN_PROGRESS'][$value->IDPaymentTransaction];
                             /* be sure we have just one invoice, avoid suprises */
                             unset($invoices['V1']['PaymentTransaction']['IN_PROGRESS'][$value->IDPaymentTransaction]);
                        else :
                            $value->deviseinformativecheque = "";
                        endif;
                        $invoices['V1']['IN_PROGRESS'][$value->IDPaymentTransaction] = $value;
                    else :
                        $invoices['V1']['IN_PROGRESS'][] = $value;
                    endif;
                }
                if ( isset($user_id) ) :
                    $invoices['V1']['PAYED']  = \Business\Invoice_V1::getByEmail($user_id);
                else :
                    $invoices['V1']['PAYED']  = [];
                endif;
               
                foreach ($invoices['V1']['PAYED'] as $key => $value) {
                     if(isset($invoices['V1']['PaymentTransaction']['IN_PROGRESS'][$value->IDPaymentTransaction])) :
                         /* be sure we have just one invoice, avoid suprises */
                         unset($invoices['V1']['PaymentTransaction']['IN_PROGRESS'][$value->IDPaymentTransaction]);
                    endif;
                    if ( isset( $invoices_1_PAYED[$value->IDPaymentTransaction] ) ) :
                        /* deviseinformativecheque it's chosen because i won't need  it later */
                        $invoices['V1']['PAYED'][$key]->deviseinformativecheque  =  $invoices_1_PAYED[$value->IDPaymentTransaction];
                    else :
                        $invoices['V1']['PAYED'][$key]->deviseinformativecheque  =  '';
                    endif;
                }

                
                if(isset($user) && $user):
                    return $this->render('/sav/customerTransactions', array('invoices' => $invoices, 'data' => $user, 'version' => $version));
                else :
                    header("HTTP/1.0 404 Not Found");
                    die("<h3>No customer has been found</h3>");
                endif;

            endif;
        else :
            header("HTTP/1.0 404 Not Found");
            $this->render('//sav/404');
        endif;
    }


    /**
     * Excute web form Update les infos clients EMV
     * @return string|false Retour de la requete, false en cas de probleme
     */

    public function actionTestSendToEMV(){
    	$porteur = \Yii::app()->params['porteur'];
    	$email   = Yii::app()->request->getParam('m');
    	$type    = Yii::app()->request->getParam('type');
    	$user    = \Business\User_V1::load($email);
	  if($GLOBALS['porteurWithTwoSFAccounts'][$porteur] && $user->CompteEMVActif != ''){
    		$porteur = $GLOBALS['SFAccountsMap'][$user->CompteEMVActif];

    	}


		switch ($type){
			case "updateClient":
				$url_WebForm =  $GLOBALS['porteurWebformUpdateClient'][$porteur];
				break;
			case "inscrire":
				$url_WebForm = $GLOBALS['porteurWebformInscrirClient'][$porteur];
				break;
			case "desincrire":
				$url_WebForm = $GLOBALS['porteurWebformDesinscrirClient'][$porteur];
				break;
		}

    	$output_array= array( 'type'         => $type,
    			'compteEMV'    => $user->CompteEMVactif,
    			'webformIndex' => $porteur,
    			'email'        => $email,
    			'url_WebForm'  => $url_WebForm,
    	);
    	echo('<pre>');
    	print_r($output_array);
    	echo('</pre>');
    	Yii::app()->end();

    }

     public function actionGetG2SSubPayment(){

    	$row   = \Business\Logcallbackpacnet::model()->find(array('condition'=>'CallingUrl LIKE "%'.Yii::app()->request->getParam('refInterneINV').'&%" AND CallingUrl LIKE "%Status=APPROVED%" '));
    	if(count($row)>0){
    		preg_match('/&payment_method(.*?)&/', $row->attributes['CallingUrl'], $match);
    		$paymentMethod = explode('_',$match[1])[1];
    	}else{
    		$paymentMethod = NULL;
    	}
		
    	$this->renderPartial('//sav/G2SSubPayment',array('paymentMethod'=>$paymentMethod));
    }

}

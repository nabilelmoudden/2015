<?php

\Yii::import( 'ext.MailHelper' );

/**
 * Description of TestController
 *
 * @author JulienL
 */
class TestController extends AdminController{		

public function actionIndex()
	{		
	
	$Req			    = new \Business\RequestRouterEMV();				
	$Req->executed	    = \Business\RequestRouterEMV::PENDING;	
	$Res				= $Req->search()->getData();
	
	$msg	= NULL;	
		if( is_array($Res) && count($Res) > 0 ){			
		
				for( $i=0; $i<count($Res); $i++ ){
				
					  $Invoice = \Business\Invoice::load($Res[$i]->idInvoice);
					  if($Invoice->invoiceStatus  == \Business\Invoice::INVOICE_IN_PROGRESS){		
					      if( ($response = $Res[$i]->sendRequest()) !== \WebForm::RES_OK ){				      
						       $msg .= '<div style="color:red"><h1> RINALDA Fr, ID '.$Res[$i]->id.'</h1><u>Url :</u> '.$Res[$i]->url.'<br /><u>Response :</u> '.$response.'<br /><u>WebForm :</u> '.$Res[$i]->type.'</div>';		           
					      }
					  }
				      				
				}
		
				return \MailHelper::sendMail( 'jalal.bensaad.ki@gmail.com', 'Cron', 'Maj DB', $msg );
		}	
	
	}

public function actionExecuteRequestEmvPending( $porteur = false, $sendMail = true )
	{
	
		if( !empty($porteur) )
		{
			$msg = NULL;

			if( !isset($GLOBALS['porteurMap'][$porteur]) || !\Controller::loadConfigForPorteur( $porteur ) )
				$msg = '<div style="color:red"><u>'.$porteur.'</u> : Le porteur est introuvable</div>';
			else
			{
				$Req			= new \Business\RequestRouterEMV();
				$Req->executed	= \Business\RequestRouterEMV::PENDING;
				$Res			= $Req->search()->getData();

				if( is_array($Res) && count($Res) > 0 )
				{
					for( $i=0; $i<count($Res); $i++ )
					{
						if( ($response = $Res[$i]->sendRequest()) !== \WebForm::RES_OK )
							$msg .= '<div style="color:red"><h1>'.$porteur.', ID '.$Res[$i]->id.'</h1><u>Url :</u> '.$Res[$i]->url.'<br /><u>Response :</u> '.$response.'</div>';
					}
				}
			}

			if( $sendMail && $msg != NULL )
				return \MailHelper::sendMail( 'jalal.bensaad@kindyinfomaroc.com', 'Cron', 'Execute pending request EMV', $msg );
			else
				return $msg;
		}
		else
		{
			$msg = NULL;
			foreach( $GLOBALS['porteurMap'] as $portRef => $portName )
			{
			echo $portRef.'<br>';
				$msg .= $this->actionExecuteRequestEmvPending( $portRef, false );
			echo $msg.'<br>';
			}

			if( $msg != NULL )
				return \MailHelper::sendMail( 'jalal.bensaad@kindyinfomaroc.com', 'Cron', 'Execute pending request EMV', $msg );
			else
				return true;
		}
	}

    public function actionTestML1()
    {

        $pp = \Business\User::testML();
        echo $pp;
    }

    public function loadBySubcampaignAndMessageNumber()
    {
        $user = new \Business\User;
        $users = $user->loadBySubcampaignAndMessageNumber();
        var_dump($users);
    }

}?>
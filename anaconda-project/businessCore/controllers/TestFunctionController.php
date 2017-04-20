<?php
/**
 * Description of Controller controller
 *
 * @author YacineR
 * @package Controllers
 */


// importer l'extension AnacondaBehavior

\Yii::import( 'ext.AnacondaBehavior' );


class TestFunctionController extends Controller
{
	
 	public function actionIndiceImplication()
	    {
	    	$email = \Yii::app()->request->getQuery('m', false);
		        $user= new \Business\User();
		       echo $user->getIndiceImplicationByUser($email); 
		    }
		    
		    public function actionCurrentGP()
		    {
		    	$gp = \Yii::app()->request->getQuery('gp', false);
		    	$settingGP= new \Business\AnacondaSettings();
		    	$settingsGP = $settingGP->getSettingsByGp($gp);
		    	if(isset($settingsGP[0]))
		    	echo $settingsGP[0]->durationNext; 
		  		    	
		    }	 
		    
		    public function actionCreateListOfSegmeent()
		    {
		    	$idCampaign = \Yii::app()->request->getQuery('idcampaign', false);
		    	$ab= new AnacondaBehavior();
		    	$ab->CreateSegmentByCampagin($idCampaign,8);
		    }

		    public function actionTestMaxGp(){
		    	$ab= new AnacondaBehavior();
		    	$ab->nextGridByUser('yacine.3@kindyinfomaroc.com');
		    	
		    }
			public function actionGetListWebFormByIdCampaign() {
				$idCampaign = \Yii::app ()->request->getQuery ( 'idCampaign', false );
				$campaign = new \Business\Campaign ();
				$campaign = \Business\Campaign::load ( $idCampaign );
				
				$ListWebForm ['Product1'] = \Business\RouterEMV::loadByIdProduct ( $campaign->SubCampaign [0]->Product->id );
				if (isset ( $campaign->SubCampaign [1]->Product->id )) {
					
					$ListWebForm ['Product2'] = \Business\RouterEMV::loadByIdProduct ( $campaign->SubCampaign [1]->Product->id );
				}
				return $ListWebForm;
			}
			public function actionGetListSegmentByIdCampaign() {
				$idCampaign = \Yii::app ()->request->getQuery ( 'idCampaign', false );
				$campaign = new \Business\Campaign ();
				$campaign = \Business\Campaign::load ( $idCampaign );
				
				$ListSegment ['Product1'] = \Business\AnacondaSegment::loadByIdProduct ( $campaign->SubCampaign [0]->Product->id );
				
				if (isset ( $campaign->SubCampaign [1]->Product->id )) {
					$ListSegment ['Product2'] = \Business\AnacondaSegment::loadByIdProduct ( $campaign->SubCampaign [1]->Product->id );
				}
				return $ListSegment;
			}
		    	

}

?>
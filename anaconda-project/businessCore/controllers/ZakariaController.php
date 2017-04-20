<?php
/**
 * Description of Controller controller
 *
 * @author ZakariaC
 * @package Controllers
 */


// importer l'extension AnacondaBehavior
\Yii::import( 'ext.AnacondaBehavior' );



class ZakariaController extends AdminController
{
	public $layout	= '//login/menu';

	/**
	 * Initialisation du controleur
	 */
	public function init(){
		parent::init();
		// Url de la page de login ( pour les redirections faites par les Rules ) :
		Yii::app()->user->loginUrl = array( '/Login/login' );
		// Default page title :
		$this->setPageTitle( 'Login Administration' );
	}

	// ************************** RULES / FILTER ************************** //
	public function filters(){
		return array( 'accessControl', 'postOnly + delete' );
    }

	public function accessRules(){
        return array(
			array(
				'allow',
                'users' => array('@'),
				'roles' => array( 'ADMIN' )
            ),
			array(
				'allow',
				'actions' => array( 'login' ),
				'users' => array('*')
			),
            array('deny'),
        );
    }

	// ************************** ACTION ************************** //
        
    public function actionIndex()
    {
    	
    	
    	
    }
    
    public function actionTestdate(){
    	
    
    $behaviorHour=\Business\UserBehavior::searchByIdCampaingHistory(214);
 		if($behaviorHour){
 			$Datehour=new \DateTime($behaviorHour->lastDateClick);
 			echo $Datehour->format('H');
 			
 		}else{
 			$newCampaignHistory->behaviorHour=$myCampaignHistory->behaviorHour;
 		}
    	
    }
    
    public function actionExport()
    {
    	
    	$test= new AnacondaBehavior();
    	$result=$test->exportSegment("1330487","VP");
    	var_dump($result);
    	

    	
    	
    }
        
	public function actionAnacondaSetting(){
		    
		
		    	
		    	$anaset=null;
		    	if( !($anaset = \Business\AnacondaSettings::load( 20 )) )
		    		return false;
		    	
		    	echo "<br/>".$anaset->groupPrice;
		    	echo "<br/>".$anaset->nextStepSum;
		    	echo "<br/>".$anaset->previousStepClicks;
		    	echo "<br/>".$anaset->durationNext;
		    	echo "<br/>".$anaset->durationPrevious;
		    	echo "<br/>".$anaset->idFirstCampaign;
		    	
		    	
		    	echo "<br/><br/><br/>";
		    	
		    	$analist=null;
		    	
		    	if( !($analist = \Business\AnacondaSettings::loadByGroupPrice( 3 )) )
		    		return false;
		    	
		    	var_dump($analist[0]->durationNext);
		    	echo "<table border=2>
		    			<th>Group Price</th>
		    			<th>Next Step Sum</th>
		    			<th>Previous Step Clicks</th>
		    			<th>Duration Next</th>
		    			<th>Duration Previous</th>
		    			<th>id First Campaign</th>";
		    	for($i=0;$i<sizeof($analist);$i++){
		    	        
		    			echo "<tr>";
		    		  	echo "<td>".$analist[$i]->groupPrice."</td>";
				    	echo "<td>".$analist[$i]->nextStepSum."</td>";
				    	echo "<td>".$analist[$i]->previousStepClicks."</td>";
				    	echo "<td>".$analist[$i]->durationNext."</td>";
				    	echo "<td>".$analist[$i]->durationPrevious."</td>";
				    	echo "<td>".$analist[$i]->idFirstCampaign."</td>";
				    	echo "</tr>";
		    	}
		    	echo "</table>";
	}

    
    public function actionLotCampaign(){
    
    	echo "create lotCampaign";
    	$LotCamp= new \Business\LotCampaign();
    	$LotCamp->numLot=1;
    	$LotCamp->creationDate=date( \Yii::app()->params['dbDateTime']);
    	$LotCamp->save();
    	 
    	
    }
    
    
	//---------------------------------------------------------
	
    public function actionSetUserDateBan()
    {
    	$ab= new AnacondaBehavior();
    	$email="NN@GMAIL.COM";
    	if($ab->setUserDateBanningByUser($email))
    	{
    		echo "mail inserted : ".date( \Yii::app()->params['dbDateTime']);
    	}
    	else 
    		echo "mail not found";
    }
    
    public function actionImport(){

    	
    	$ab= new AnacondaBehavior();

    	$ab->import("../Anaconda Data/test_import.txt"); 
    	
    	
    }
    
    
    //---------------------------------------------------------
    
    public function actionFileToArray(){
    
    	 
    	$ab= new AnacondaBehavior();
    
    	$listEmails=$ab->fileToArray("../Anaconda Data/test_import.txt");
    	foreach ($listEmails as $email) {
    		echo $email;
    		echo "<br>";
    	} 
    	 
    	 
    }
    
    //----------------------------------------------------------
    
	public function actionFirstCampaign(){ 
    	
		$firstCampaign = new \Business\Campaign();
		$firstCampaign->getFirstCampaign();
		echo $firstCampaign['ref'];
    } 
    
    
    //test de la méthode shoot planification
    public function actionShootPlanification()
    {
    	
    	$ab= new AnacondaBehavior();
    	 
    	$listEmails=$ab->fileToArray("../Anaconda Data/test_import.txt");
    	$gp=3;
    	$ab->ShootPlanification($listEmails,"egg","10/10/2017",$gp);
    	
    }

}

?>
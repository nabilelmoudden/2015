<?php
/**
 * le controlleur d'alimentation de la base de données .
 *
 * il contient les methodes permettant l'alimentation journalière de la base données par les informations nécéssaires à la génération des rapports.
 *
 *@package businessCore\controllers.
 *@var array $adminMails
 */

define("porteur","Aasha acq");
use \Business\IspCompaign ;
\Yii::import('ext.MailHelper');


class IsploaddbController extends Controller
{

     private $IspadminMails = array(
       'assia.najm.ki@gmail.com',
      /* 'zakaria.elouafi.ki@gmail.com',*/
    ); 




   /**
   * @return view action declenchant l'interface graphique qui permet d'alimenter la base de données
   */
    public function actionLoaddb()
    {
        
        $model = new IspCompaign();

        $this->render('//loaddb/loaddb',array('model'=>$model));

        


      
    }

    /**
    * action permettent d'alimenter la base de données par les ids de compagnes, nom des triggers,la date d'envoi et le porteur associés à chaque id campaign.
    * @return void 
    */

     public function actionLoaddb1()
    {
    	echo '1';
     
       $i=1;
       $token = Yii::app()->ispAlimenterBD->gettoken(porteur);  
    	
       $one=Yii::app()->ispAlimenterBD->getcompaigns($token,1,porteur);
       $xmlone=(object)$one;
       $nextPage=$xmlone->nextPage;
	   $xmlobject=$xmlone->campaigns;

         foreach($xmlobject->campaign as $node){
                        $data[] = $node;
         }
          $i++;
          while ($nextPage) {
                     
                    $one2=Yii::app()->ispAlimenterBD->getcompaigns($token,$i,porteur); 
                    $xmlone2=(object)$one2;
                      
                    $xmlobject2=$xmlone2->campaigns;
                    $nextPage=$xmlone2->nextPage;

                    foreach($xmlobject2->campaign as $node){
                        $data[] = $node;
                    }
                    
                     $i++;

         }

    

       $nbTotalItems =$one['nbTotalItems'];

		for($ligne=0;$ligne<$nbTotalItems ;$ligne++){
            $tree=$data[$ligne];
            
			 $compaignid=(string)$tree->campaignId;
			 $triggername=(string)$tree->name;
			 $sendDate=(string)$tree->sendDate;
			
             Yii::app()->ispCrudBD->insertcompaign($compaignid,$triggername,"0","null",$sendDate,porteur);
           
         }
    }

    /**
    * action permettent d'alimenter la base de données par les ids des messages associés à chaque id campaign.
    * @return void 
    */
    public function actionLoaddb2()
    {
    	echo '2';
       
    	 Yii::app()->ispCrudBD->updateidmessage(porteur);

    }
    /**
    * action permettent d'alimenter la base de données par les noms de messages associés à chaque id campaign.
    * @return void 
    */
     public function actionLoaddb3()
    {
    	echo '3';
   
    	 Yii::app()->ispCrudBD->updatenamemessage(porteur);

    }
    /**
    * @return view action permettent d'alimenter la base de données par les noms de sites associés à chaque id campaign
    */
     public function actionLoaddb4()
    {
        echo '4';
       
         Yii::app()->ispCrudBD->updatesite(porteur);

    }

    /**
    * action permettent d'alimenter la base de données par toutes les informations nécéssaires à la génération des rapports.
    * @return void 
    */
     public function actionTestCron()
    {
 

   
    \Yii::import( 'ext.IspGlobalMAP' ); 
    $IspGlobalMAP=new \IspGlobalMAP();
    
    $ispCrudBD=new \IspCrudBD();
    $msg="";
    
    $start=time();

    foreach($GLOBALS['porteurMap'] AS $key=>$value){
     
       
        $lng=explode('_', $key);
        $site=$lng[0];
        $_GET['site']=$site;
       \Controller::loadConfigForPorteur($key);
       

         $msg.=$key.": ".$ispCrudBD->conloaddb1($key,$value);
         $msg .=$key.": ".$ispCrudBD->updateidmessage($key,$value);
         $msg .=$key.": ".$ispCrudBD->updatenamemessage($key,$value);
         $msg .=$key.": ".$ispCrudBD->updatesite($value);
   
         echo '</br>';
       

      
     

       
     }
       $msg .= '<br /><br /><div><b>Total Recuperation effectue en ' . ( time() - $start ) . ' secondes</b></div><br /><br />';
           echo $msg;
             \MailHelper::sendMail($this->IspadminMails, 'Cron', 'Export MKT compaign ( tous les porteurs )', $msg);
       
    }

}

<?php
/**
 * le controlleur de génération des rapports FAIs.
 *
 * il contient les methodes permettant la génération des rapports FAIs.
 *
 *@package businessCore\controllers
 */

define('model', 'model');
define('model1', 'model1');
define('return1', 'return');
use \Business\IspCompaign ;
\Yii::import('ext.MailHelper');


     


class SeuilrunController extends Controller
{

      private $IspadminMails = array(
           'assia.najm.ki@gmail.com',
           //'zakaria.elouafi.ki@gmail.com',
        ); 

  public function actionStoreinfos()

    { 



       \Yii::import( 'ext.SeuilGlobalMAP' ); 
     
        $date_actuel_cron='2016-09-16';
        $date_premiere_cron='2016-09-01';
          

        $msg="";
           

        foreach($GLOBALS['porteurMap'] AS $key=>$value){
               $msg="";
               $msg .=$key.'<br/>';
              
               
               
               
               
               
             
               
                $lng=explode('_', $key);
                $site=$lng[0];
                $_GET['site']=$site;
               \Controller::loadConfigForPorteur($key);
               
                $seuilCronFunctions=new \SeuilCronFunctions();
              /*-----------ccreate segment *-------------*/
                $msg .=$seuilCronFunctions-> createsegment($key,$value);
              /*-----------change SOURCE by EMVSOURCE *-------------*/
              
             
                 /*-----------ADD EMVADMIN43 avec EMVADMIN 5 *-------------*/
             

              /*-----------delete EMVADMIN43  *-------------*/
           


              /*----------- CLICK ET ACHAT------------------------------------------------------------------*/

             
              

              
         }

          

  
       
    
   }
  
  
}

  
      

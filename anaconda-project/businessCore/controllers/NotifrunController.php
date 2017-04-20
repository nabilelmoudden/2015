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


     


class NotifrunController extends Controller
{

      private $IspadminMails = array(
           'assia.najm.ki@gmail.com',
           //'zakaria.elouafi.ki@gmail.com',
        ); 

  public function actionStoreinfos()

    { 

   \Yii::import( 'ext.SeuilGlobalMAP' ); 
      $compte = 'acq';
      $porteur = 'fr_laetizia';
   
          
         $y = 0  ;
        $msg="";
           
          $start=time();
        $MEMBER_ID = null ;
     
             $msg="";
         
        
                $lng=explode('_', $porteur);
                $site=$lng[0];
                $_GET['site']=$site;
               \Controller::loadConfigForPorteur($porteur);
               
                $seuilDB=new \SeuilDB();
                $seuilSF=new \SeuilSF();
              /*----------- get clients------------------------------------------------------------------*/
              $infos = $seuilDB->GetLeads();
                for ($i=0; $i <count($infos) ; $i++) { 

                   $email=$infos[$i]['adresse'];

                 $y++ ;
              /*----------- get sent message------------------------------------------------------------------*/
                \Controller::loadConfigForPorteur($porteur);
                 $token=$seuilSF->gettoken($porteur,$compte); 
                
                
  $resultat_membere = $seuilSF->GetNbreClick($email,$porteur,$compte);
                


}   

                /*-----------FIN ALERTE------------------------------------------------------------------*/

         


              
     $tempsecoule = time() - $start ;

     echo $tempsecoule ;  
     echo '</br>' ;
     echo $y;
          

  
       
    
   }
  
  
}

  
      

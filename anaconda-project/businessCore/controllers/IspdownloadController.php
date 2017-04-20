<?php
/**
 * le controlleur de téléchargement des rapports FAIs .
 *
 * il contient les methodes permettant le téléchargement  des rapports FAIs depuis le serveur de production.
 *
 *@package businessCore\controllers
 */
include('isperror_handle.php');
use \Business\IspCompaign ;
use \Business\IspReport ;
use \Business\Reportsearch1;

class IspdownloadController extends Controller
{



    /**
   * cette methode retourne une vue.
   * @return view action declenchant l'interface graphique qui permet le retéléchargement des rapports depuis le serveur de production
   */
    public function actionDownload()
    {




         $model = new IspReport();
        $AC = new \Business\IspReport( 'search1' );
      
        if( Yii::app()->request->getParam( 'Business\IspReport' ) !== NULL ){
          $AC->attributes = Yii::app()->request->getParam( 'Business\IspReport' );

        }
           return $this->render('//download/download', array('model' => $model,'AC'=>$AC));

    
    }

 /**
   * cette methode était utilisée dans l'ancienne version 
  */
    public function actionDownload1()
    {


      $request = Yii::$app->request;
   
      $filename =$request->get('filename');

        $searchModel = new Reportsearch1();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


         $model = new Report();
           return $this->render('download1', [
             'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
              'model' => $model,
              'filename'=>$filename,
          ]);

    
    }

     /**
   * cette action declenchant  le retéléchargement des rapports depuis le serveur de production.
   * @return void 
   */
    public function actionDownloadreport1()
    {


       


          

    
   
      $reportId =Yii::app()->request->getParam( 'id' );
      
      //get chemin from database
      \Controller::loadConfigForPorteur('fr_rinalda');
      $query=IspReport::model()->find(array(
                      
                      'condition' => 'idreport_sf = :idreport_sf ',
                      'params'    => array(':idreport_sf' => $reportId  )
                  ));
      
      
      $filename=$query->chemin;


    

      if($filename != "" && file_exists('./'.$filename) ){

         $this->redirect(Yii::App()->getBaseUrl(true).$filename);
      }
     else{
      echo "SORRY : the requested file doesn't exist on the server TRY to generate it";
     }
    

 

        

    }

  /**
   * cette methode declenchant l'extraction des rapports FAIs depuis Smartfocus et leur stockage ainsi que leur téléchargement depuis  le serveur de production.
   * @return void  
   */
    public function actionDownloadreport()
    {
       
define("ACTION_1", 'return');
      
     \Yii::import( 'ext.IspGlobalMAP' ); 
    $IspGlobalMAP=new \IspGlobalMAP();



      
      $i=1;
      
             $ispExportReport=new \IspExportReport();
             $ispGenererReport=new \IspGenererReport();
             $ispAlimenterBD=new \IspAlimenterBD();
             
             $reportId =Yii::app()->request->getParam( 'id' );
             $porteur =Yii::app()->request->getParam( 'porteur' );
             $site =Yii::app()->request->getParam( 'site' );
             $divporteur = explode('_', $porteur);
             if(empty($divporteur[1]) ||  ( empty($divporteur[2]) && $divporteur[1]=="fid") ){
            $porteur_map=strtolower ($site).'_'.$divporteur[0];
             $porteur_site=strtolower ($site).'_'.$divporteur[0];
             }
             else{
             $porteur_map=strtolower ($site).'_'.$divporteur[1];
             $porteur_site=strtolower ($site).'_'.$divporteur[1];
           }
             if(!empty($divporteur[2]) ||(!empty($divporteur[1]) && $divporteur[1]=="fid")  )
              {$compte="fid";} 
            else {$compte="acq";}


  
          Yii::app()->session['porteur'] = $porteur_site;
        
           \Controller::loadConfigForPorteur($porteur_map);
             
            $token=$ispAlimenterBD->gettoken($porteur_map,$compte);
 
            
     
            \Controller::loadConfigForPorteur($porteur_map);
            
            $infosreport=$ispGenererReport->infosreport($token,$reportId,$porteur_map,$compte); 
            $status=(string)$infosreport['return']->status;
            if($status==2){

              
              echo "<script type='text/javascript'>window.confirm('le rapport non encore pret!');</script>";

                echo "<script type='text/javascript'>window.location='".Yii::App()->getBaseUrl(true)."/index.php/ispgenerate/generate';</script>";
              
                die();
            }
            else{
                $page="1";
                \Controller::loadConfigForPorteur($porteur_map);
               
                  $report=$ispExportReport->exportereport($token,$reportId,$page,$porteur_map,$compte); 
                 
                  $xmlobject=$report[ACTION_1] ;
               
                  $nbPages=(int)$report[ACTION_1]->nbPages;
                  $nbTotalItems=(int)$report[ACTION_1]->nbTotalItems;
                if($nbTotalItems==1){ 
                  foreach($xmlobject->list as $node){
                      $data[] = $xmlobject->list;
                     
                       

                    
                    }
                  $i++;
                 while ($i <= $nbPages) {
                   \Controller::loadConfigForPorteur($porteur_map);
                  $report2=$ispExportReport->exportereport($token,$reportId,$i,$porteur_map,$compte); 

                  $xmlobject=$report2[ACTION_1];
                  
                  foreach($xmlobject->list as $node){
                      $data[] = $xmlobject->list;
                    }
                  
                    $i++;

                  }

                }
                elseif($nbTotalItems>1){
                   foreach($xmlobject->list as $node){
                      $data[] = $node;
                     
                       

                    
                    }
                  $i++;
                 while ($i <= $nbPages) {
                   \Controller::loadConfigForPorteur($porteur_map);
                  $report2=$ispExportReport->exportereport($token,$reportId,$i,$porteur_map,$compte); 

                  $xmlobject=$report2[ACTION_1];
                  
                  foreach($xmlobject->list as $node){
                      $data[] = $node;
                    }
                  
                    $i++;

                  }
                  
                }
              
                include('Ispfileexport.php');

 

          
    }       


    }

}


<?php
/**
 * le controlleur de génération des rapports FAIs.
 *
 * il contient les methodes permettant la génération des rapports FAIs.
 *
 *@package businessCore\controllers
 */
include('isperror_handle.php');
define('model', 'model');
define('model1', 'model1');
define('return1', 'return');
use \Business\IspCompaign ;
use \Business\IspReport ;
use \Business\Reportsearch;

class IspgenerateController extends Controller
{

    


   /**
   * cette methode retourne une vue.
   * @return  view declenchant l'interface graphique qui permet la génération des rapports 
   */


    public function actionGenerate()
    {
       
      

        $model= new IspReport();
        $model1= new IspCompaign();
           
      


             
      $AC = new \Business\IspReport( 'search' );
   
    if( Yii::app()->request->getParam( 'Business\IspReport' ) !== NULL ){

      $AC->attributes = Yii::app()->request->getParam( 'Business\IspReport' );
     }
   
    $this->render('//generate/generate', array(model1=>$model1,model=>$model,'AC'=>$AC)); 
      
        
    }

    /**
   * cette methode  retourne une vue.
   * @return view action permet d'afficher les erreurs liés au formulaire de génération des rapports
   */

     public function actionGenerate_error()
    {
       
      

        $model= new IspReport();
        $model1= new IspCompaign();
           
      


             
      $AC = new \Business\IspReport( 'search' );

    if( Yii::app()->request->getParam( 'Business\IspReport' ) !== NULL ){

      $AC->attributes = Yii::app()->request->getParam( 'Business\IspReport' );
    }
   
    $this->render('//generate/generate_error', array(model1=>$model1,model=>$model,'AC'=>$AC)); 
      
        
    }

  /**
   * cette methode  retourne une vue.
   * @return view action permet  de lancer le téléchargement des rapport FAIs et l'actualisation de l'interface de  génération des rapports
   */

     public function actionGenerate1()
    {
       
 
     
   
      $filename =Yii::app()->request->getParam('filename');
       

        $model= new IspReport();
        $model1= new IspCompaign();
    
             
      $AC = new \Business\IspReport( 'search' );
    
    if( Yii::app()->request->getParam( 'Business\IspReport' ) !== NULL ){
      $AC->attributes = Yii::app()->request->getParam( 'Business\IspReport' );
    }
   
    
        
      $this->render('//generate/generate1', array(model1=>$model1,model=>$model,'AC'=>$AC,'filename'=>$filename));
        
    }

  /**
   * cette action permet  la génération des rapports FAIs suivant les informations du formulaire.
   * @return void 
   */
public function actionTest()
    {
      $token=Yii::app()->ispAlimenterBD->gettoken('fr_rucker','fid');
        $infosreport=Yii::app()->ispGenererReport->infosreport($token,'27028','fr_rucker','fid');
        $campaignId=(string) $infosreport['return']->campaignId;
      var_dump($infosreport) ;
      echo $campaignId;
    }
    public function actionGeneratereport()
    {


     
     \Yii::import( 'ext.IspGlobalMAP' ); 
    $IspGlobalMAP=new \IspGlobalMAP();
    
    
      $reportname= Yii::app()->request->getPost('reportname');  
      $triggername =(string) Yii::app()->request->getPost('triggername'); 
      $messagename =(string)Yii::app()->request->getPost('messagename'); 
      $senddate = (string)Yii::app()->request->getPost('senddate'); 

      $porteur=(string) Yii::app()->request->getPost('porteur'); 
      $site=(string) Yii::app()->request->getPost('site');
     
    if(empty($porteur) || empty($site) || empty($triggername) || empty($messagename) || empty($senddate) || empty($messagename) ){
      

     
       $this->redirect(Yii::App()->getBaseUrl(true).'/index.php/ispgenerate/generate_error');
     
   
    }

    else{

       $dates = explode('/', $senddate);
       $senddatee=$dates[2].'-'.$dates[1].'-'.$dates[0];
       $divporteur = explode('_', $porteur);
      if(empty($divporteur[1]) || ( empty($divporteur[2]) && $divporteur[1]=="fid") ){
          $porteur_map=strtolower ($site).'_'.$divporteur[0];
       $porteur_site=strtolower ($site).'_'.$divporteur[0];
       }
       else{
       $porteur_map=strtolower ($site).'_'.$divporteur[1];
       $porteur_site=strtolower ($site).'_'.$divporteur[1];
     }
       if(!empty($divporteur[2]) ||(!empty($divporteur[1]) && $divporteur[1]=="fid") ){$compte="fid";} else {$compte="acq";}

   
          Yii::app()->session['porteur'] = $porteur_site;
       

       
        

       $ispCrudBD=new \IspCrudBD();
      $campaignId_array=$ispCrudBD->getcompaignid($triggername,$messagename,$senddatee,$porteur,$site);
      $campaignId=$campaignId_array[0];
      $id_tablecompaign=$campaignId_array[1];
     

         if ($campaignId != "0" ){

              \Controller::loadConfigForPorteur($porteur_map);
              $ispAlimenterBD=new \IspAlimenterBD();
              $token=$ispAlimenterBD->gettoken($porteur_map,$compte); 
            
           
          
              $ispGenererReport=new \IspGenererReport();
              $idreport=$ispGenererReport->generatereport($token,$reportname,$campaignId,$porteur_map,$compte); 
            
              
             $infosreport=$ispGenererReport->infosreport($token,$idreport,$porteur_map,$compte);
             $campaignId=(string) $infosreport[return1]->campaignId;
             $creationDate= (string)$infosreport[return1]->creationDate;
             $managerName= (string)$infosreport[return1]->managerName;
             $name= (string)$infosreport[return1]->name;
             $reportId= (string)$infosreport[return1]->reportId;
             

     

             $ispCrudBD->insertreport($id_tablecompaign,$reportId,$name,$creationDate,$managerName,"null",0);

             echo "the report has been successfully generated" ;

             $this->redirect(Yii::App()->getBaseUrl(true).'/index.php/ispgenerate/generate');
           }
           else{

                 echo "the report with these fields doesn't exist " ;
             }
     

         
         
 
       }

     
            
    
           
        }

  /**
   * cette action permet  de lister les sites associés à un porteur.
   * @return void 
   */
public function actionSubcat3() {
 
   $data1=IspCompaign::model()->findAll(array(
                      
                      'condition' => 'porteur= :porteur AND site!="0"',
                      'params'    => array(':porteur' => $_POST['porteur']) ,

                     
    ));

 
   $data=array_unique(CHtml::listData($data1,'id','site'),SORT_REGULAR);

   echo "<option value=''>Séléctionner un site</option>";
   foreach($data as $site){
   echo CHtml::tag('option', array('value'=>$site),CHtml::encode($site),true);

   }
    }

  /**
   * cette action permet  de lister les triggers associés à un porteur et à site.
   * @return void 
   */
public function actionSubcat() {




  $data1=IspCompaign::model()->findAll(array(
                      
                      'condition' => 'site = :site AND porteur= :porteur AND triggername!="null" AND messagename!="null" AND triggername NOT LIKE :triggername AND triggername NOT LIKE :triggernamee AND triggername NOT LIKE :triggernameee',
                      'params'    => array(':site' => $_POST['site'] , ':porteur' => $_POST['porteur'] ,':triggername'=>'%AUTO%',':triggernamee'=>'%test%',':triggernameee'=>'%auto%')
                  ));


 
   $data=array_unique(CHtml::listData($data1,'id','triggername'),SORT_REGULAR);
 
   echo "<option value=''>Séléctionner un trigger</option>";
   foreach($data as $triggername){
   echo CHtml::tag('option', array('value'=>$triggername),CHtml::encode($triggername),true);
  }

    }

  /**
   * cette action permet  de lister les messages associés à un porteur , à site et à un trigger.
   * @return void 
   */
    public function actionSubcat2() {

   $data1=IspCompaign::model()->findAll(array(
                      
                      'condition' => 'triggername = :triggername  AND messagename!="null" AND site = :site AND porteur= :porteur',
                      'params'    => array(':triggername' => $_POST['triggername'] ,':site' => $_POST['site'] , ':porteur' => $_POST['porteur'] )
                  ));
 
   $data=array_unique(CHtml::listData($data1,'id','messagename'),SORT_REGULAR);
 
   echo "<option value=''>Séléctionner un message</option>";
   foreach($data as $messagename){
   echo CHtml::tag('option', array('value'=>$messagename),CHtml::encode($messagename),true);
 }

    }
  /**
   * cette action permet  de lister les dates associés à un porteur , à un site ,  à un trigger et à un message.
   * @return void 
   */
     public function actionSubcat4() {

   $data1=IspCompaign::model()->findAll(array(
                     
                      'condition' => 'triggername = :triggername AND site = :site AND porteur= :porteur AND messagename= :messagename',
                      'params'    => array(':triggername' => $_POST['triggername'] ,':site' => $_POST['site'] , ':porteur' => $_POST['porteur'] ,':messagename' => $_POST['messagename'])
                  ));
 
   $data=CHtml::listData($data1,'id','senddate');

   echo "<option value=''>Séléctionner une date</option>";
   foreach($data as $senddate){
   $dates = explode(' ', $senddate);
   $senddatee = $dates[0];
   $dates2 = explode('-', $senddatee);
   $senddatee2=$dates2[2].'/'.$dates2[1].'/'.$dates2[0];
   echo CHtml::tag('option', array('value'=>$senddatee2),CHtml::encode($senddatee2),true);
  }


    }
 /**
   * cette action permet  de lancer le téléchargement d'un rapport FAIs.
   * @return void 
   */

 public function actionDownloadreport()
    {
          
           
            $reportId =Yii::app()->request->getParam( 'id' );
            $porteur =Yii::app()->request->getParam( 'porteur' );
            $site =Yii::app()->request->getParam( 'site' );
           
     
        $this->redirect(Yii::App()->getBaseUrl(true).'/index.php/ispdownload/downloadreport?id='.$reportId.'&porteur='.$porteur.'&site='.$site);
 

          
           


    }


      
}
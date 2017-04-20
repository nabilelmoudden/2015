<?php



/**
 * le composant CRUD.
 *
 * il contient les methodes permettant l'insertion et la mise à jour des données des deux tables  V2_ispreport et V2_ispcompaign .
 *
 *@package businessCore\components\EMV\IspExportReport
 */





use \Business\InfosCompaignAffiliate ;



ini_set('display_errors', 1);

 ini_set('log_errors', 1);

 error_reporting(E_ALL);





class SeuilCronFunctions  {



  private $IspadminMails = array(

           'assia.najm.ki@gmail.com',

           'zakaria.elouafi.ki@gmail.com',
           'hmossaddek.ki@gmail.com',

        ); 

  /**
   * cette methode permet de recuperer les informations des adresses avec leur subID ,total d'acheteur pour une date précise et la date depuis la dase de données.
   * @return void 
   */

  public function StoreInfosinDB($date_actuel_cron,$date_premiere_cron,$porteur){



      $un="true";
      $msg="";
      $msg1="";
         

        $seuilDB=new SeuilDB();

        //acquerir les infos adresses 

        $EMVACTIF= Yii::app()->params['compteEMV'];



  

    foreach ($EMVACTIF as $key => $value) {

     



        $infos=$seuilDB->GetAdresseInfos($date_actuel_cron,$date_premiere_cron,$porteur,$EMVACTIF[$key]);



        for ($i=0; $i <count($infos) ; $i++) { 

        $idAffiliatePlatform=$infos[$i]['idAffiliatePlatform'];

        $subID=$infos[$i]['subID'];

        $date_achat_actuel=date("Y-m-d", strtotime("-1 day", strtotime($date_actuel_cron)));

        $date_jour=$date_achat_actuel;

        $Click=0;

        $Achattotal=$infos[$i]['Achattotal'];

       /* $Achat=$infos[$i]['Achatjour'];*/

        $total=$infos[$i]['total'];

        $porteur_DB=$infos[$i]['porteur'];



      $msg .="total:  <b>".$total.'</b><br /><br />';



        //store infos in dababase

        if($total>20){


        $msg .=$seuilDB->StoreinDB($subID,$idAffiliatePlatform,$date_jour,$Click,$Achattotal,'0',$total,$porteur_DB);
      
        
     
          

      }



        }



        

      



      }

       return $msg; 

  }



 public function createsegment($porteur,$v){

      $seuilSF=new SeuilSF();

      $EMVACTIF= Yii::app()->params['compteEMV'];

      $nombre=Yii::app()->params['isp_porteur_name'][$v]['nombre'];

    foreach ($EMVACTIF as $key => $value) {



        $comptes=explode('_', $EMVACTIF[$key]);

        if(isset($comptes[2])  && $comptes[2]=='FID'){$compte='fid';} else {$compte='acq'; }



            /*******----------------------------------------Créer segment-------------------------------------------------------------------******/

             $token= $seuilSF->gettoken($porteur,$compte);

              $date= date("Y-m-d");

              $name= 'Click_Aff_Seuil'.$date;

              $description= 'Seuil des affilies clicks';

              $sampleType= 'ALL';

   

              $SegmentId=$seuilSF->creatSegment($token, $name, $description, $sampleType,$compte);

              echo $porteur.'_'.$compte.':'.$SegmentId ;

              echo "</br>";



          /**----------debut creation criteres-----------*/



            

              $columnEMV5='EMVADMIN5';

              $columnSource='EMVSOURCE';

              $columnSite= 'SITE' ;

              $columnClick=  'LAST_DATE_CLICK';  



              $operatorEMV5='ABSOLUTE_IS_BETWEEN';

              $operatorSource='ENDS_WITH';

              $operatorSite='EQUALS';

              $operatorClick='ON_STATIC';

             





              $valueEMV5_1='2016-01-01';

              $valueEMV5_2='2016-01-02';

              $valueSource='t';

              $valueSite='test';

              $valueClick='2015-12-30';



              $groupSource='Groupe1';

              $NumberSource='1';

              $orderFragSource='1';



              $groupSite='Groupe4';

              $NumberSite='4';

              $orderFragSite='4';

                // EMVADMin5

                $seuilSF->criteriadateSegment($token, $SegmentId, $columnEMV5, $operatorEMV5,$valueEMV5_1,$valueEMV5_2 ,$compte);

                // SOURCE

                $Critersegment=$seuilSF->criteriaSegment($token, $SegmentId, $columnSource,$groupSource,$NumberSource,$orderFragSource, $operatorSource,$valueSource,$compte);



                //SITE

                if($nombre>1){

                $Critersegment=$seuilSF->criteriaSegment($token, $SegmentId, $columnSite, $groupSite,$NumberSite,$orderFragSite,$operatorSite,$valueSite,$compte);

                }

                //CLICK

                 $Critersegment=$seuilSF->criteriaRecencySegment($token, $SegmentId, $columnClick, $operatorClick,$valueClick,$compte);

                

                





                /*---------------fin creation criteres--------------*/



    }



    }

public function deleteEMVSOURCE43($porteur,$value,$date_actuel_cron,$date_premiere_cron){



       \Controller::loadConfigForPorteur($porteur);

        $seuilSF=new SeuilSF();

      $nombre=Yii::app()->params['isp_porteur_name'][$value]['nombre'];
        $EMVACTIF= Yii::app()->params['compteEMV'];

    foreach ($EMVACTIF as $key => $value) {


        \Controller::loadConfigForPorteur($porteur); 



        $comptes=explode('_', $EMVACTIF[$key]);

         if(isset($comptes[2])  && $comptes[2]=='FID'){$compte='fid' ;$site_porteur=$porteur.'_'.$compte; } else {$compte='acq'; $site_porteur=$porteur; }

        

         $token= $seuilSF->gettoken($porteur,$compte);

        

          \Yii::import( 'ext.Seuilswitch' ); 

          $Seuilswitch=new \Seuilswitch();

              $SegmentId= $Seuilswitch->Seuilswi($site_porteur);

            /*----- debut udelete EMV43 -----*/

             if($nombre>1){ 

                    $orderFrag = 6 ; 

                   
                    $seuilSF->deletecriteriaSegment($token, $SegmentId, $orderFrag ,$compte) ;

            } else{ $orderFrag = 5 ;
              $o =1 ;
                 while($o<3){
                    $seuilSF->deletecriteriaSegment($token, $SegmentId, $orderFrag ,$compte) ;

                    $o ++ ;

               }

             }

}

}

public function UpdateEMVSOURCE($porteur,$value,$date_actuel_cron,$date_premiere_cron){



       \Controller::loadConfigForPorteur($porteur);

        $seuilSF=new SeuilSF();

      $nombre=Yii::app()->params['isp_porteur_name'][$value]['nombre'];
        $EMVACTIF= Yii::app()->params['compteEMV'];

    foreach ($EMVACTIF as $key => $value) {


        \Controller::loadConfigForPorteur($porteur); 



        $comptes=explode('_', $EMVACTIF[$key]);

         if(isset($comptes[2])  && $comptes[2]=='FID'){$compte='fid' ;$site_porteur=$porteur.'_'.$compte; } else {$compte='acq'; $site_porteur=$porteur; }

        

         $token= $seuilSF->gettoken($porteur,$compte);

        

          \Yii::import( 'ext.Seuilswitch' ); 

          $Seuilswitch=new \Seuilswitch();

              $SegmentId= $Seuilswitch->Seuilswi($site_porteur);

            /*----- debut update EMV5 et SITE et Click -----*/


              $columnSource='EMVSOURCE';
              $operatorSource='ENDS_WITH';
              $valueSource='t';
              $groupSource='Groupe7';

              $NumberSource='7';

              $orderFragSource='7';

            $Critersegment=$seuilSF->criteriaSegment($token, $SegmentId, $columnSource,$groupSource,$NumberSource,$orderFragSource, $operatorSource,$valueSource,$compte);

            
          
            if($Critersegment == ""){

              echo '</br>';
              echo $porteur.":".$compte." le champs n'existe pas" ;
              echo '</br>';
            }
            else {
               echo '</br>';
               echo $porteur.":".$compte." le champs existe" ;
               echo '</br>';
              //delete le champs source1 et source2  
              $seuilSF->deletecriteriaSegment($token, $SegmentId, 1 ,$compte) ;

              if($nombre>1){ $seuilSF->deletecriteriaSegment($token, $SegmentId, 3 ,$compte) ; } else{ $seuilSF->deletecriteriaSegment($token, $SegmentId, 2 ,$compte) ; }

              if($nombre>1){ $seuilSF->deletecriteriaSegment($token, $SegmentId, 3 ,$compte) ; } else{ $seuilSF->deletecriteriaSegment($token, $SegmentId, 2 ,$compte) ; }

              $columnSource='EMVSOURCE';

              $operatorSource='ENDS_WITH';

              $valueSource="test";

              $groupName='Groupe1';

              $groupNumber='1';

              $orderFrag='1';





              $columnSource2='EMVSOURCE';

              $operatorSource2='BEGINS_WITH';

             

              $valueSource2="test";

              $groupName2='Groupe6';

              $groupNumber2='6';

              if($nombre>1){$orderFrag2='4';}else{$orderFrag2='3';}
               $Critersegment=$seuilSF->criteriaSegment($token, $SegmentId, $columnSource,$groupName,$groupNumber,$orderFrag, $operatorSource,$valueSource,$compte);
               $Critersegment=$seuilSF->criteriaSegment($token, $SegmentId, $columnSource2,$groupName2,$groupNumber2,$orderFrag2, $operatorSource2,$valueSource2,$compte);

            }
            

      }


 }
public function ADDEMVADMIN43($porteur,$value,$date_actuel_cron,$date_premiere_cron){

     $date_creation_initiale=date("Y-m-d", strtotime("-2 day", strtotime($date_premiere_cron)));
      $date_creation_actuel=date("Y-m-d", strtotime("-2 day", strtotime($date_actuel_cron)));

       \Controller::loadConfigForPorteur($porteur);

        $seuilSF=new SeuilSF();

      $nombre=Yii::app()->params['isp_porteur_name'][$value]['nombre'];
        $EMVACTIF= Yii::app()->params['compteEMV'];

    foreach ($EMVACTIF as $key => $value) {


        \Controller::loadConfigForPorteur($porteur); 



        $comptes=explode('_', $EMVACTIF[$key]);

         if(isset($comptes[2])  && $comptes[2]=='FID'){$compte='fid' ;$site_porteur=$porteur.'_'.$compte; } else {$compte='acq'; $site_porteur=$porteur; }

        

         $token= $seuilSF->gettoken($porteur,$compte);

        

          \Yii::import( 'ext.Seuilswitch' ); 

          $Seuilswitch=new \Seuilswitch();

              $SegmentId= $Seuilswitch->Seuilswi($site_porteur);

            /*----- debut update EMV5 et SITE et Click -----*/


               $columnEMV5='EMVADMIN43';

               $operatorEMV5='ABSOLUTE_IS_BETWEEN';

              $valueEMV5_1=$date_creation_initiale;

              $valueEMV5_2=$date_creation_actuel;

            

             $Critersegment=  $seuilSF->criteriadateSegment($token, $SegmentId, $columnEMV5, $operatorEMV5,$valueEMV5_1,$valueEMV5_2 ,$compte);


            if($Critersegment == ""){

              echo '</br>';
              echo $porteur.":".$compte." le champs n'existe pas" ;
              echo '</br>';
            }
            else {
               echo '</br>';
               echo $porteur.":".$compte." le champs existe" ;
               echo '</br>';

             }
          
            

      }


 }
public function UpdateDBClick($porteur,$value,$date_actuel_cron,$date_premiere_cron){

$msg="";

       \Controller::loadConfigForPorteur($porteur);

        $seuilSF=new SeuilSF();

        $seuilDB=new SeuilDB();

       $nombre=Yii::app()->params['isp_porteur_name'][$value]['nombre'];

         $divporteur = explode('_', $porteur);

         $site= $divporteur[0];

        $EMVACTIF= Yii::app()->params['compteEMV'];

        $countemv=count($EMVACTIF);



        $date_click_actuel=date("Y-m-d", strtotime("-1 day", strtotime($date_actuel_cron)));

        $date_creation_actuel=date("Y-m-d", strtotime("-2 day", strtotime($date_actuel_cron)));

         $date_creation_initiale=date("Y-m-d", strtotime("-2 day", strtotime($date_premiere_cron)));





    foreach ($EMVACTIF as $key => $value) {

         $Clickstotal=0;

         $Clicks2=0;

         $nbreclick=0;



        //acquerir les infos adresses 

        $DistinctAllAdresses=$seuilSF->GetAddress($date_actuel_cron,$date_premiere_cron,$porteur,$EMVACTIF[$key]);





       



        \Controller::loadConfigForPorteur($porteur); 



        $comptes=explode('_', $EMVACTIF[$key]);

         if(isset($comptes[2])  && $comptes[2]=='FID'){$compte='fid' ;$site_porteur=$porteur.'_'.$compte; } else {$compte='acq'; $site_porteur=$porteur; }

        

         $token= $seuilSF->gettoken($porteur,$compte);

        

          \Yii::import( 'ext.Seuilswitch' ); 

       $Seuilswitch=new \Seuilswitch();

      $SegmentId= $Seuilswitch->Seuilswi($site_porteur);

    



         /*----- debut update EMV5 et SITE et Click -----*/

          $columnEMV5='EMVADMIN5';

          $operatorEMV5='ABSOLUTE_IS_BETWEEN';

          $valueEMV5_1=$date_creation_initiale;

          $valueEMV5_2=$date_creation_actuel;



           $columnEMV52='EMVADMIN43';

          $operatorEMV52='ABSOLUTE_IS_BETWEEN';

          $valueEMV5_12=$date_creation_initiale;

          $valueEMV5_22=$date_creation_actuel;



          $columnSite= 'SITE' ;

          $operatorSite='EQUALS';

          $valueSite=$site;

          $groupName='Groupe4';

          $groupNumber='4';

          $orderFrag='2';



          



           if($nombre>1){$orderFragclick='3';}else{$orderFragclick='2';}



          $columnClick=  'LAST_DATE_CLICK';  

          $operatorClick='ON_STATIC';

          $valueClick=$date_click_actuel;



          



          $seuilSF->updatedatesegment($token, $SegmentId, $columnEMV5, $valueEMV5_1, $operatorEMV5,$valueEMV5_2,$compte);
             if($nombre>1){$orderFrag43='5';}else{$orderFrag43='4';}
          $seuilSF->updatedatesegment43($token, $SegmentId, $columnEMV52, $valueEMV5_12, $operatorEMV52,$valueEMV5_22,$orderFrag43,$compte);



          $seuilSF->updatecriteriaSegment($token, $SegmentId, $columnSite,$groupNumber,$groupName,$orderFrag ,$operatorSite,$valueSite,$compte);

          $seuilSF->updatecriteriaRecencySegment($token, $SegmentId, $columnClick, $operatorClick,$valueClick,$orderFragclick,$compte );





        

         /* -------------fin update ------------------*/

      



            

         

        // click total

        for ($l=0; $l <count($DistinctAllAdresses) ; $l++) 

        

          { 

        

             /*******----------------------------------------update segment Source------------------------------------------------------------------******/

             \Controller::loadConfigForPorteur($porteur);

              $columnSource='EMVSOURCE';

              $operatorSource='ENDS_WITH';

              $valueSource=$DistinctAllAdresses[$l]['subID'];

              $groupName='Groupe1';

              $groupNumber='1';

              $orderFrag='1';





              $columnSource2='EMVSOURCE';

              $operatorSource2='BEGINS_WITH';

              $idplateforme=$DistinctAllAdresses[$l]['idAffiliatePlatform'];

              $valueSource2='pr_'.$DistinctAllAdresses[$l]['idAffiliatePlatform'].'_';

              $groupName2='Groupe6';

              $groupNumber2='6';

              if($nombre>1){$orderFrag2='4';}else{$orderFrag2='3';}

              

            //update champs :   source

            $seuilSF->updatecriteriaSegment($token, $SegmentId, $columnSource,$groupNumber,$groupName,$orderFrag ,$operatorSource,$valueSource,$compte);



            $seuilSF->updatecriteriaSegment($token, $SegmentId, $columnSource2,$groupNumber2,$groupName2,$orderFrag2 ,$operatorSource2,$valueSource2,$compte);

             //lancement du comptage

             $Countsegment=$seuilSF->CountSegment($token,$SegmentId,$compte,$porteur);

          

            echo'</br>';

              

              echo $valueSource.'_       '.$site_porteur.':'.$Countsegment;

              echo'</br>';

              echo $SegmentId ;

              echo $compte;

              echo $porteur;

              

              $msg .=$seuilDB->UpdateDB($valueSource,$idplateforme,$Countsegment,$nbreclick,$date_click_actuel,$EMVACTIF[$key],$porteur);





         }

        

         // click

       /* for ($l=0; $l<count($DistinctAdressJ_2) ; $l++) { 

         # code...



             

              for ($i=0; $i <count($AdressJ_2) ; $i++) { 

                if($AdressJ_2[$i]['subID']==$DistinctAdressJ_2[$l]['subID']){



                  $Click2= $Clicks2+($seuilSF->GetNbreClick($AdressJ_2[$i]['adresse'],$date_click_actuel,$porteur,$compte));

                }

             



              }



              //update array1_1

              $DistinctAdressJ_2[$l]['Click']=$Click2;

              $nbreclick=$DistinctAdressJ_2[$l]['Click'];

              $subID=$DistinctAdressJ_2[$l]['SubID'];

              // à faire not coded yet

              $seuilDB->UpdateDB($subID,$nbreclicktotal,$nbreclick,$date_actuel_cron,$EMVACTIF[$key]);



         }*/



      





      }

        
return $msg;
  }

 



    

  
public function UpdateClicksegment($porteur,$value,$date_actuel_cron,$date_premiere_cron,$EMVACTIFkey,$idaffiliateplateforme, $subID){
     echo '</br>';
     echo "11111111ooooooh".$date_actuel_cron ; 
      echo '</br>';
     echo "22222222ooooooh".$date_premiere_cron ; 
      echo '</br>';

      $msg="";

       \Controller::loadConfigForPorteur($porteur);

        $seuilSF=new SeuilSF();

        $seuilDB=new SeuilDB();

       $nombre=Yii::app()->params['isp_porteur_name'][$value]['nombre'];

         $divporteur = explode('_', $porteur);

         $site= $divporteur[0];

     



        $date_click_actuel=date("Y-m-d", strtotime("-1 day", strtotime($date_actuel_cron)));
        $date_click_initiale=date("Y-m-d", strtotime("-1 day", strtotime($date_premiere_cron)));

        $date_creation_actuel=date("Y-m-d", strtotime("-2 day", strtotime($date_actuel_cron)));

         $date_creation_initiale=date("Y-m-d", strtotime("-2 day", strtotime($date_premiere_cron)));


         $Clickstotal=0;

         $Clicks2=0;

         $nbreclick=0;


        \Controller::loadConfigForPorteur($porteur); 



        $comptes=explode('_', $EMVACTIFkey);

         if(isset($comptes[2])  && $comptes[2]=='FID'){$compte='fid' ;$site_porteur=$porteur.'_'.$compte; } else {$compte='acq'; $site_porteur=$porteur; }

        

         $token= $seuilSF->gettoken($porteur,$compte);

        

          \Yii::import( 'ext.Seuilswitch' ); 

       $Seuilswitch=new \Seuilswitch();

      $SegmentId= $Seuilswitch->Seuilswi($site_porteur);

    



         /*----- debut update EMV5 et SITE et Click -----*/

          $columnEMV5='EMVADMIN5';

          $operatorEMV5='ABSOLUTE_IS_BETWEEN';

          $valueEMV5_1=$date_creation_initiale;

          $valueEMV5_2=$date_creation_actuel;



           $columnEMV52='EMVADMIN43';

          $operatorEMV52='ABSOLUTE_IS_BETWEEN';

          $valueEMV5_12=$date_creation_initiale;

          $valueEMV5_22=$date_creation_actuel;



          $columnSite= 'SITE' ;

          $operatorSite='EQUALS';

          $valueSite=$site;

          $groupName='Groupe4';

          $groupNumber='4';

          $orderFrag='2';



          



           if($nombre>1){$orderFragclick='3';}else{$orderFragclick='2';}



          $columnClick=  'LAST_DATE_CLICK';  

          $operatorClick='ISBETWEEN_STATIC';

          
          $valueClick1=$date_click_actuel;
          $valueClick2=$date_click_initiale;



          



          $seuilSF->updatedatesegment($token, $SegmentId, $columnEMV5, $valueEMV5_1, $operatorEMV5,$valueEMV5_2,$compte);

            if($nombre>1){$orderFrag43='5';}else{$orderFrag43='4';}
          $seuilSF->updatedatesegment43($token, $SegmentId, $columnEMV52, $valueEMV5_12, $operatorEMV52,$valueEMV5_22,$orderFrag43,$compte);


 if($nombre>1){
          $seuilSF->updatecriteriaSegment($token, $SegmentId, $columnSite,$groupNumber,$groupName,$orderFrag ,$operatorSite,$valueSite,$compte);
}


          $a = $seuilSF->updatecriteriaRecencySegment2($token, $SegmentId, $columnClick, $operatorClick,$valueClick2,$valueClick1,$orderFragclick,$compte );
          echo 'aaaaa</br>';
          var_dump($a);
          echo '</br>';
          echo $operatorClick;
          echo '</br>';
           echo $valueClick2;
          echo '</br>';
          echo $valueClick1;
          echo '</br>';
          echo $orderFragclick;
          echo '</br>';

             /*******----------------------------------------update segment Source------------------------------------------------------------------******/

             \Controller::loadConfigForPorteur($porteur);

              $columnSource='EMVSOURCE';

              $operatorSource='ENDS_WITH';

              $valueSource=$subID;

              $groupName='Groupe1';

              $groupNumber='1';

              $orderFrag='1';





              $columnSource2='EMVSOURCE';

              $operatorSource2='BEGINS_WITH';

              $idplateforme=$idaffiliateplateforme;

              $valueSource2='pr_'.$idaffiliateplateforme.'_';

              $groupName2='Groupe6';

              $groupNumber2='6';

              if($nombre>1){$orderFrag2='4';}else{$orderFrag2='3';}

              

            //update champs :   source

            $seuilSF->updatecriteriaSegment($token, $SegmentId, $columnSource,$groupNumber,$groupName,$orderFrag ,$operatorSource,$valueSource,$compte);



            $seuilSF->updatecriteriaSegment($token, $SegmentId, $columnSource2,$groupNumber2,$groupName2,$orderFrag2 ,$operatorSource2,$valueSource2,$compte);

             //lancement du comptage

             $Countsegment=$seuilSF->CountSegment($token,$SegmentId,$compte,$porteur);
   
        
return $Countsegment;
  }

 


      

  

 public function SendMailAchat($date_premiere_cron,$date_actuel_cron,$porteur,$porteurD,$porte){



    // test if 

          \Controller::loadConfigForPorteur($porteur);

             //calculer le %

          $seuilDB=new SeuilDB();

          $texte='';

          $txt='';

          $compte='';

          $EMVACTIF= Yii::app()->params['compteEMV'];


          $today=strtotime(date("Y-m-d", strtotime("-1 day", strtotime($date_actuel_cron))));

          $firstdatecron =strtotime(date("Y-m-d", strtotime("-1 day", strtotime($date_premiere_cron))));

          //utile pour acquerir les subdi de 1ere date de la semaine 

          $firstdatein3week=date("Y-m-d", strtotime("-22 day", strtotime($date_actuel_cron)));
          $firstdatein3week1=date("d/m/Y", strtotime("-23 day", strtotime($date_actuel_cron)));


          $nbrejour=round(abs($today-$firstdatecron)/60/60/24);
          echo  "nbrejoues".$nbrejour;
          if($nbrejour>=21){
            echo "212121";

          

      

            




                     $texte='';

                      $sitee=explode('_', $porteur);

                      $site=strtoupper($sitee[0]);

                    


        

                       $result=$seuilDB->GetprctAchat($date_actuel_cron,$firstdatein3week,$porteur);



                       $headermail=' <br /> <h3> Acquisition date :'.$firstdatein3week1.' </h3> 

                                       <h3> Thank you for reviewing the following Affiliate Platform SubId :</h3>  <br />  <table border="1"><tr style="background: green;">

                                        <th scope="col">Plateforme</th>

                                        <th scope="col">SubID</th>

                                        <th scope="col">Leads(nb)</th>

                                        <th scope="col">Achats(%)</th></tr>';

                     $headers  = "From: Analyseur Achat<alerts@kindyinfomaroc.com> \r\nReply-To: alerts@kindyinfomaroc.com ";
                     $headers .= 'MIME-Version: 1.0' . "\r\n";
                     $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

                                   



                       $footermail='</table><br /> <h3 style="color:#274E9C"><i>Regards, </i><br /> RMDD</h3>';

                      for ($l=0; $l <count($result) ; $l++) 

          

                       {  

                      

                   

                                 $Achattotal=0;



                                 $total=$result[$l]['total'];

                                 $idaffiliateplateforme=$result[$l]['idAffiliatePlatform'];

                                 $subID=$result[$l]['subID'];
                                 echo '</br>';
                                 echo $subID;
                                 echo '</br>';
                                 echo  $idaffiliateplateforme;
                                 echo '</br>';

                                foreach ($EMVACTIF as $key => $value) {
                                /*-----get click total -update emvadmin5  and emvsource  mv43 , and e*/
                                   $all= $seuilDB->GetAdresseInfos2($date_actuel_cron,$date_premiere_cron,$porteur,$EMVACTIF[$key],$idaffiliateplateforme,$subID);
                                    echo '</br>';
                                     var_dump($all);
                                     echo '</br>';

                                     if(isset($all[0])){

                                      $Achattotal += $all[0]['totalAchat'];
                                      }


                                }
                               echo "achattotal".$EMVACTIF[$key].' '.$Achattotal;

                                

                                  \Controller::loadConfigForPorteur($porteur);
                                   $connection=Yii::app()->db; 

                                    $oo=$connection->createCommand("select label from  V2_affiliateplatform where id= '".$idaffiliateplateforme."' ")->query();


                                    $affiliate=$oo->readAll();

                                    $labelplateforme=$affiliate['0']['label'];
                                    
                           

                                 

                                 $prct=($Achattotal*100)/$total;

                                
                                  echo "prct".$prct;


                                 if($prct<=1){



                                  //envoyer l'alerte



     //  $texte .= '<br /><br /><div>Le subID:  <b>' .$subID. '</b> du porteur  <b>'.$site_porteur.'</b> du compte SF  <b>'.$EMVACTIF[$key].'</b> ne convertit pas  a poucentage des achats <b>'.$prct.'</b></div><br /><br /> ';

      



                               $texte .="<tr>  <td> ".$labelplateforme."  </td>

                                          <td> ".$subID."  </td>

                                            <td> ".$total."  </td>                                          

                                          <td style='background:red' >".round($prct,2)." %</td> 

                                          </tr>";

                                  }

                                  /* else{

                                    $texte  .='<br /><br /><div><b>Le subID:  ' .$subID. ' du compte '.$EMVACTIF[$key].' a poucentage'.$prct.' </b></div><br /><br /> ';

                                    

                                    }*/

                        }   



                      



                        if($texte!=''){

                           echo '</br>' ;

                        echo 'Alerte taux de transformation click - '.$porte.' '.$site ;

                        echo  $headermail.$texte.$footermail ;

                        echo '</br>' ;
                           $subject=   'Alerte taux de transformation achat - '.$porte.' '.$site;
                          \MailHelper::sendMail($this->IspadminMails, 'Analyseur Achat<alerts@kindyinfomaroc.com>', 'Alerte taux de transformation achat - '.$porte.' '.$site, $headermail.$texte.$footermail);
                         /* $mail_sent=    @mail('assia.najm.ki@gmail.com', $subject, $headermail.$texte.$footermail, $headers );
                          $mail_sent=    @mail('assia.najm.ki@gmail.com', $subject, $headermail.$texte.$footermail, $headers );
*/
                        }





                  



          }   



          return $texte;       





}

public function SendMailClick($date_premiere_cron,$date_actuel_cron,$porteur,$porteurD,$porte){



    // test if 

          \Controller::loadConfigForPorteur($porteur);

             //calculer le %

         $seuilDB=new SeuilDB();

          $texte="";

          $txt='';

          $compte='';

          $EMVACTIF= Yii::app()->params['compteEMV'];

          $today1=date("d/m/Y", strtotime("-1 day", strtotime($date_actuel_cron)));

          $today=strtotime(date("Y-m-d", strtotime("-1 day", strtotime($date_actuel_cron))));

          $firstdatecron =strtotime(date("Y-m-d", strtotime("-1 day", strtotime($date_premiere_cron))));

          //utile pour acquerir les subdi de 1ere date de la semaine 
          $firstdateinweekcron=date("Y-m-d", strtotime("-7 day", strtotime($date_actuel_cron)));

          $firstdateinweek=date("Y-m-d", strtotime("-8 day", strtotime($date_actuel_cron)));
          $firstdateinweek1=date("d/m/Y", strtotime("-9 day", strtotime($date_actuel_cron)));
         

          $nbrejour=round(abs($today-$firstdatecron)/60/60/24);

          echo $nbrejour ;
          echo "hhhh".$firstdateinweekcron ;
          if($nbrejour>=7){

            echo "777";

          

      

         

                  



                      $texte='';

                      $sitee=explode('_', $porteur);

                      $site=strtoupper($sitee[0]);

                     


                     $headermail=' <br /> <h3> Acquisition Date :'.$firstdateinweek1.' </h3> 

                                          <h3> Thank you for reviewing the following Affiliate Platform SubId :</h3>  <br />  <table border="1"><tr style="background: green;">

                                                        <th scope="col">Plateforme</th>

                                                        <th scope="col">SubID</th>

                                                         <th scope="col">Leads(nb)</th>

                                                         <th scope="col">Clicks(%)</th></tr>';



                     $footermail='</table><br /> <h3 style="color:#274E9C"><i>Regards, </i><br /> RMDD</h3>';



                     $result=$seuilDB->GetprctAchat($date_actuel_cron,$firstdateinweek,$porteur);
                     echo  "eeeee" ;
                     var_dump($result);
                      

                      for ($l=0; $l <count($result) ; $l++) 

          

                       {  

                      

                   
                                $Clicktotal = 0 ;
                              




                                 $total=$result[$l]['total'];



                                
                                 $idaffiliateplateforme=$result[$l]['idAffiliatePlatform'];
                                  $subID=$result[$l]['subID'];

                                   /* $Clicktotal=$result[$l]['Clicktotal'];*/
                                foreach ($EMVACTIF as $key => $value) {
                                /*-----get click total -update emvadmin5  and emvsource  mv43 , and e*/

                                $Clicktotal += $this->UpdateClicksegment($porteur,$porteurD,$date_actuel_cron,$firstdateinweekcron,$EMVACTIF[$key],$idaffiliateplateforme, $subID);

                                }
                               

                                  \Controller::loadConfigForPorteur($porteur);
                                   $connection=Yii::app()->db; 

                                    $oo=$connection->createCommand("select label from  V2_affiliateplatform where id= '".$idaffiliateplateforme."' ")->query();


                                    $affiliate=$oo->readAll();

                                    $labelplateforme=$affiliate['0']['label'];



                                

                               

                                 $prct=($Clicktotal*100)/$total;

                                echo '</br>';
                                echo $subID;
                                echo $Clicktotal;
                                echo $total;
                                 echo $prct;
                                echo '</br>';



                                 if($prct<=1){



                                  //envoyer l'alerte



                             //      $texte .= '<br /><br /><div>Le subID:  <b>' .$subID. '</b> du porteur  <b>'.$site_porteur.'</b> du compte SF  <b>'.$EMVACTIF[$key].'</b> ne convertit pas  a poucentage de click <b>'.$prct.'</b></div><br /><br /> ';

                                  

                        

      

                               $texte .="<tr>  <td> ".$labelplateforme."  </td>

                                                 <td> ".$subID."  </td>

                                                 <td> ".$total."  </td> 

                                            <td style='background:red' >".round($prct,2)." %</td> 

                                             </tr>";

       

                           

                                       



                                  

                                  }

                                   /*else{

                                    $texte  .='<br /><br /><div><b>Le subID:  ' .$subID. 'du porteur  '.$site_porteur.' du compte  '.$EMVACTIF[$key].' a poucentage de click : '.$prct.' </b></div><br /><br /> ';

                                  

                                    }*/

                        }   



                      

                      if($texte!=''){

                          echo '</br>' ;

                        echo 'Alerte taux de transformation click - '.$porte.' '.$site ;

                        echo  $headermail.$texte.$footermail ;

                        echo '</br>' ;

                          \MailHelper::sendMail($this->IspadminMails, 'Analyseur Click<alerts@kindyinfomaroc.com>', 'Alerte taux de transformation click - '.$porte.' '.$site, $headermail.$texte.$footermail);

  

                        }











                    



          }   



        

          return $texte;       





}





}

 

?>
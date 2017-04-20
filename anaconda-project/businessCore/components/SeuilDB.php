<?php

/**
 * le composant CRUD.
 *
 * il contient les methodes permettant l'insertion et la mise à jour des données des deux tables  V2_ispreport et V2_ispcompaign .
 *
 *@package businessCore\components\EMV\IspExportReport
 */


use \Business\InfosCompaignAffiliate ;



class SeuilDB  {


	public function GetAdresseInfos($date_actuel_cron,$date_premiere_cron,$porteur,$CompteEMVactif){

		\Controller::loadConfigForPorteur($porteur);
         $date_creation_actuel=date("Y-m-d", strtotime("-2 day", strtotime($date_actuel_cron)));
         $date_creation_initiale=date("Y-m-d", strtotime("-2 day", strtotime($date_premiere_cron)));
         $date_achat_actuel=date("Y-m-d", strtotime("-1 day", strtotime($date_actuel_cron)));
         $divporteur = explode('_', $porteur);
         $site= $divporteur[0];
         $comptes=explode('_', $CompteEMVactif);
         if(isset($comptes[2])  && $comptes[2]=='FID'){$compte='fid' ;$site_porteur=$porteur.'_'.$compte;	} else {$compte=''; $site_porteur=$porteur;	}
   		 
		
          $connection=Yii::app()->db; 
          //Sum(if(f.status=2 and  f.DateLead='".$date_creation_actuel."' and f.DateInvoice BETWEEN '".$date_achat_actuel."00:00:00' and '".$date_achat_actuel." 23:59:59' , 1, 0)) AS Achatjour, 
          $dataReader1=$connection->createCommand("
			        Select f.subID,'0' as Achattotal,'".$site_porteur."' as porteur,f.idAffiliatePlatform,					
					sum(if( f.DateLead ='".$date_creation_actuel."' , 1, 0)) as total
					from (

					SELECT DISTINCT i.id,i.email as adresse, Date(l.creationDate) as DateLead,sub.id as idAffiliatePlatformSubId,sub.idAffiliatePlatform as idAffiliatePlatform,sub.subID as subID ,inv.InvoiceStatus as status, Date(inv.CreationDate)  as DateInvoice,inv.site as site,i.CompteEMVactif as CompteEMVactif
					 FROM internaute i
					inner join lead_affiliateplatform l
					on i.ID=l.idInternaute
					inner join invoice inv
					on i.ID=inv.IDInternaute
					inner join V2_affiliateplatformsubid sub
                    on l.idAffiliatePlatformSubId=sub.id
					WHERE Date(l.creationDate) BETWEEN '".$date_creation_initiale."' and '".$date_creation_actuel."'  and inv.site='".$site."' and i.CompteEMVactif='".$CompteEMVactif."'
					) as f

					GROUP BY f.idAffiliatePlatformSubId")->query();

          $dataReader2=$connection->createCommand("
          	select d.subID, d.totalAchat ,d.idAffiliatePlatform
			from (SELECT  COALESCE(sub.id,'total') as idAffiliatePlatformSubId,COALESCE(i.id,'total')as total ,COUNT(DISTINCT i.id) AS totalAchat,
				sub.idAffiliatePlatform idAffiliatePlatform,sub.subID as subID, i.email as adresse, Date(l.creationDate) as DateLead ,
				inv.InvoiceStatus as status, Date(inv.CreationDate)  as DateInvoice
			 FROM `internaute` i
			inner join lead_affiliateplatform l
			on i.ID=l.idInternaute
			inner join invoice inv
			on i.ID=inv.IDInternaute
			inner join V2_affiliateplatformsubid sub
            on l.idAffiliatePlatformSubId=sub.id
			WHERE Date(l.creationDate) BETWEEN '".$date_creation_initiale."' and '".$date_creation_actuel."' and  Date(inv.CreationDate)='".$date_achat_actuel."' and inv.InvoiceStatus=2 and inv.site='".$site."' and i.CompteEMVactif='".$CompteEMVactif."'
			GROUP BY sub.id ,i.id with ROLLUP) as d
			where d.idAffiliatePlatformSubId!='total' and d.total='total'")->query();



       
				// récupération de toutes les lignes d'un coup dans un tableau
		$array1=$dataReader1->readAll();
		$array2=$dataReader2->readAll();


		
    
				// concat values array 2 with the first one
				for ($i=0; $i <count($array2) ; $i++) { 
				 
					$idAffiliatePlatform2=$array2[$i]['idAffiliatePlatform'];
					$subid2=$array2[$i]['subID'];
					$totalAchat=$array2[$i]['totalAchat'];

					//search if true 
						for ($j=0; $j <count($array1) ; $j++) { 
				 
				         
							$subid1=$array1[$j]['subID'];
							$idAffiliatePlatform1=$array1[$j]['idAffiliatePlatform'];

				
						
								if ($idAffiliatePlatform1==$idAffiliatePlatform2 && $subid1==$subid2)
										  {
										  //concat 
										  	$array1[$j]['Achattotal']=$totalAchat;

										  }
								  
										
							
					}
				
				}	
		
	   
		return $array1;
		}
	
	

	public function GetAdresseInfos2($date_actuel_cron,$date_premiere_cron,$porteur,$CompteEMVactif,$idAffiliatePlatform,$subID){

		\Controller::loadConfigForPorteur($porteur);
         $date_creation_actuel=date("Y-m-d", strtotime("-2 day", strtotime($date_actuel_cron)));
         $date_creation_initiale=date("Y-m-d", strtotime("-23 day", strtotime($date_actuel_cron)));

         $date_achat_initiale=date("Y-m-d", strtotime("-22 day", strtotime($date_actuel_cron)));
         $date_achat_actuel=date("Y-m-d", strtotime("-1 day", strtotime($date_actuel_cron)));

         $divporteur = explode('_', $porteur);
         $site= $divporteur[0];
         $comptes=explode('_', $CompteEMVactif);
         if(isset($comptes[2])  && $comptes[2]=='FID'){$compte='fid' ;$site_porteur=$porteur.'_'.$compte;	} else {$compte=''; $site_porteur=$porteur;	}
   		 
		
          $connection=Yii::app()->db; 

          $dataReader2=$connection->createCommand("
          	select d.subID, d.totalAchat ,d.idAffiliatePlatform,d.DateInvoice
			from (SELECT  COALESCE(sub.id,'total') as idAffiliatePlatformSubId,COALESCE(i.id,'total')as total ,COUNT(DISTINCT i.id) AS totalAchat,
				sub.idAffiliatePlatform idAffiliatePlatform,sub.subID as subID, i.email as adresse, Date(l.creationDate) as DateLead ,
				inv.InvoiceStatus as status, Date(inv.CreationDate)  as DateInvoice
			 FROM `internaute` i
			inner join lead_affiliateplatform l
			on i.ID=l.idInternaute
			inner join invoice inv
			on i.ID=inv.IDInternaute
			inner join V2_affiliateplatformsubid sub
            on l.idAffiliatePlatformSubId=sub.id
			WHERE  sub.idAffiliatePlatform ='".$idAffiliatePlatform."' and sub.subID = '".$subID."' and Date(l.creationDate) BETWEEN '".$date_creation_initiale."' and '".$date_creation_actuel."' and  Date(inv.CreationDate) BETWEEN '".$date_achat_initiale."'  and '".$date_achat_actuel."'  and inv.InvoiceStatus=2 and inv.site='".$site."' and i.CompteEMVactif='".$CompteEMVactif."'
			GROUP BY sub.id ,i.id with ROLLUP) as d
			where d.idAffiliatePlatformSubId!='total' and d.total='total'  ")->query();



       

		$array2=$dataReader2->readAll();


		
    
				
	   
		return $array2;
		}
	
	

  /**
   * cette methode permet d'inserer les données d'une compagne dans la dase de données.
   * @return void 
   */
public function StoreinDB($subID,$idAffiliatePlatform,$date_jour,$Click,$Achattotal,$Achat,$total,$porteur){
	
$msg="";
	  try { 
	 \Controller::loadConfigForPorteur('fr_rinalda');

		/*
	  	$data1=InfosCompaignAffiliate::model()->findAll(array(
                      
                      'condition' => 'idAffiliatePlatform = :idAffiliatePlatform AND subID = :subID AND date_jour = :date_jour   AND porteur= :porteur' ,
                      'params'    => array(':idAffiliatePlatform' => $idAffiliatePlatform ,':subID' => $subID ,':date_jour' => $date_jour , ':porteur' => $porteur)
                  ));*/

	  	     $connection=Yii::app()->db; 

          $dataReader1=$connection->createCommand("

          	select *
          	from infos_compaign_affiliate 
            WHERE idAffiliatePlatform = '".$idAffiliatePlatform."'  and subID = '".$subID."' and date_jour = '".$date_jour."' and porteur = '".$porteur."'

           ")->query();


		$array1=$dataReader1->readAll();


	  } catch (exception $e) {

		 \Controller::loadConfigForPorteur('fr_rinalda');
		  	$connection=Yii::app()->db; 
       

        	 $dataReader1=$connection->createCommand("

          	select *
          	from infos_compaign_affiliate 
            WHERE idAffiliatePlatform = '".$idAffiliatePlatform."'  and subID = '".$subID."' and date_jour = '".$date_jour."' and porteur = '".$porteur."'

           ")->query();


		$array1=$dataReader1->readAll();

		 echo " Assia : got exception -- ".$e->getMessage()."\n";

        	}

        	echo "	aray1";
        	
        var_dump($array1);	

        
	if(count($array1) != 0){
		 echo "insertion non effectuée : Deja existant dans la BD" ;
		
	}
	else{

		
		 
           $connection=Yii::app()->db; 
       

        	 $dataReader1=$connection->createCommand("

          	INSERT INTO infos_compaign_affiliate (idAffiliatePlatform,subID,date_jour,Click,Achattotal,Achat,total,porteur)
          	VALUES ('".$idAffiliatePlatform."' ,'".$subID."','".$date_jour."','".$Click."','".$Achattotal."','".$Achat."','".$total."','".$porteur."')
          
           

           ")->execute();

		
		 //    $model=new InfosCompaignAffiliate;
		 //    $model->idAffiliatePlatform=$idAffiliatePlatform;
			// $model->subID=$subID;
			
			// $model->date_jour=$date_jour;
			// $model->Click=$Click;
			// $model->Achattotal=$Achattotal;
			// $model->Achat=$Achat;
	  //       $model->total=$total;
	  //       $model->porteur=$porteur;
	 
	       

			if ($dataReader1 > 0){
		  
		    $msg = 'insertion effectuée avec succes';

		    echo $msg;
			}
			else{

				 echo "error occured while insertion";
			}

	
	}


	  
	return $msg;


}

public function UpdateDB($subID,$idAffiliatePlatform,$nbreclicktotal,$nbreclick,$date_jour,$CompteEMVactif,$porteur){

$msg="";

echo "clicktotal:".$nbreclicktotal;
	

     $comptes=explode('_', $CompteEMVactif);
    if(isset($comptes[2])  && $comptes[2]=='FID'){$compte='fid' ;$site_porteur=$porteur.'_'.$compte;	} else {$compte=''; $site_porteur=$porteur;	}
	  try{

	  	
/*	  	$data1=InfosCompaignAffiliate::model()->findAll(array(
                      
                      'condition' => 'idAffiliatePlatform = :idAffiliatePlatform AND subID = :subID AND date_jour = :date_jour   AND porteur= :porteur' ,
                      'params'    => array(':idAffiliatePlatform' => $idAffiliatePlatform ,':subID' => $subID ,':date_jour' => $date_jour , ':porteur' => $site_porteur)
                  ));*/
         \Controller::loadConfigForPorteur('fr_rinalda');
        	$connection=Yii::app()->db; 
       

        	 $dataReader1=$connection->createCommand("

          	UPDATE  infos_compaign_affiliate
          	SET Clicktotal = '".$nbreclicktotal."' , Click = '".$nbreclick."'
            WHERE idAffiliatePlatform = '".$idAffiliatePlatform."'  and subID = '".$subID."' and date_jour = '".$date_jour."' and porteur = '".$site_porteur."'

           ")->execute();


		

	  	}	catch (exception $e) {

		 \Controller::loadConfigForPorteur('fr_rinalda');
		  	$connection=Yii::app()->db; 

        	 $dataReader1=$connection->createCommand("

          	UPDATE  infos_compaign_affiliate
          	SET Clicktotal = '".$nbreclicktotal."' , Click = '".$nbreclick."'
            WHERE idAffiliatePlatform = '".$idAffiliatePlatform."'  and subID = '".$subID."' and date_jour = '".$date_jour."' and porteur = '".$site_porteur."'

           ")->execute();


		
}




	if($dataReader1 > 0){
		
		    $msg .="<div> the idAffiliatePlatform ".$idAffiliatePlatform." for the porteur ".$site_porteur." at this date ".$date_jour."  has been updated</div><br/>";
		   echo  "<div> the idAffiliatePlatform ".$idAffiliatePlatform." for the porteur ".$site_porteur." at this date ".$date_jour."  has been updated</div><br/>";
		
	}
	else{

		
		echo " the idAffiliatePlatform ".$idAffiliatePlatform." for the porteur ".$site_porteur." at this date ".$date_jour." doesn't exist in database";
			

	
	}
return $msg;
	
}


public function GetprctAchat($date_actuel_cron,$six_vightjour,$porteur){

		\Controller::loadConfigForPorteur('fr_rinalda');
        
         $date_achatclick_actuel=date("Y-m-d", strtotime("-1 day", strtotime($date_actuel_cron)));
        // $date_achat_ancien=date("Y-m-d", strtotime("-1 day", strtotime($date_premiere_cron)));

         
		
          $connection=Yii::app()->db; 

          $dataReader1=$connection->createCommand("

          	select sum(d.Achattotal) as Achattotal,sum(d.total) as total,sum(d.Clicktotal) as Clicktotal, d.subID,d.porteur,d.idAffiliatePlatform

          	from 
          	(select info.Achattotal , info.total, info.subID, info.Clicktotal,info.porteur ,info.idAffiliatePlatform

          		from infos_compaign_affiliate info

          	

            WHERE info.date_jour BETWEEN '".$six_vightjour."'  and '".$date_achatclick_actuel."' and info.porteur like '%".$porteur."%' and info.subID in (SELECT subID
				FROM  `infos_compaign_affiliate` 
				WHERE date_jour =  '".$six_vightjour."'
				AND porteur like '%".$porteur."%')

            ) as d
         
			group by idAffiliatePlatform ")->query();


		$array1=$dataReader1->readAll();


		
    
		
	   
		return $array1;
		}
	
	public function GetLeads(){

		\Controller::loadConfigForPorteur("fr_laetizia");
         $date_entry=date("Y-m-d", strtotime("-1 day", strtotime(date("Y-m-d"))));
      
        echo ($date_entry);
         $compte = "acq";
         
   		 
		
          $connection=Yii::app()->db; 
          //Sum(if(f.status=2 and  f.DateLead='".$date_creation_actuel."' and f.DateInvoice BETWEEN '".$date_achat_actuel."00:00:00' and '".$date_achat_actuel." 23:59:59' , 1, 0)) AS Achatjour, 
          $dataReader1=$connection->createCommand("
			  

					SELECT DISTINCT i.id,i.email as adresse, Date(l.creationDate) as DateLead
					FROM internaute i
					inner join lead_affiliateplatform l
					on i.ID=l.idInternaute
					
					WHERE Date(l.creationDate) BETWEEN  '2016-07-27' and '".$date_entry."'
					")->query();

        

       
				// récupération de toutes les lignes d'un coup dans un tableau
		$array1=$dataReader1->readAll();
		


		
    	
		
	   
		return $array1;
		}
	
	

 
}
?>
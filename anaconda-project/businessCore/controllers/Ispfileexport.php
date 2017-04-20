<?php
    $filename= $ispGenererReport-> createfolders($reportId); 

     $phpExcelPath = Yii::getPathOfAlias('ext.PHPExcel2');
 
    
     spl_autoload_unregister(array('YiiBase','autoload'));        
 
    
    include($phpExcelPath . DIRECTORY_SEPARATOR . 'PHPExcel.php');
 
     spl_autoload_register(array('YiiBase','autoload'));

     
      $objPHPExcel = new PHPExcel();
          


    
      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A1', 'ispName')
                  ->setCellValue('B1', 'Sent (nb)')
                  ->setCellValue('C1', 'Delivered (nb)')
                  ->setCellValue('D1', 'Opens (nb)')
                  ->setCellValue('E1', 'Opened (%)')
                  ->setCellValue('F1', 'Click throughs (nb)')
                  ->setCellValue('G1', 'Click Throughs (%)')
                  ->setCellValue('H1', 'Unsubscribed (nb)')
                  ->setCellValue('I1', 'Unsubscribed (%)')
                  ->setCellValue('J1', 'Complaints (nb)')
                  ->setCellValue('K1', 'Complaints (%)')
                  ->setCellValue('L1', 'Soft bounces (nb)')
                  ->setCellValue('M1', 'Soft bounces (%)')
                  ->setCellValue('N1', 'Hard bounces (nb)')
                  ->setCellValue('O1', 'Hard bounces (nb)');
      $pointdepart=1;
   
        for ($i=$nbTotalItems-1; $i >=0 ; $i--) { 
        $books=$data[$i];
       
      

         
        $pointdepart=$pointdepart+1;
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$pointdepart, $books->ispName)
            ->setCellValue('B'.$pointdepart, $books->nbSent)
            ->setCellValue('C'.$pointdepart, $books->nbDelivered)
            ->setCellValue('D'.$pointdepart, $books->nbOpeners)
            ->setCellValue('E'.$pointdepart, round($books->pctOpeners,2).'%')
            ->setCellValue('F'.$pointdepart, $books->nbClickers)
            ->setCellValue('G'.$pointdepart, round($books->pctClickers,2).'%')
            ->setCellValue('H'.$pointdepart, $books->nbUnjoined)
            ->setCellValue('I'.$pointdepart, round($books->pctUnjoined,2).'%')
            ->setCellValue('J'.$pointdepart, $books->nbComplaints)
            ->setCellValue('K'.$pointdepart, round($books->pctComplaints,2).'%')
            ->setCellValue('L'.$pointdepart, $books->nbSoftBounces)
            ->setCellValue('M'.$pointdepart, round($books->pctSoftBounces,2).'%')
            ->setCellValue('N'.$pointdepart, $books->nbHardBounces)
            ->setCellValue('O'.$pointdepart, round($books->pctHardBounces,2).'%');

      }
     
       
     
      $objPHPExcel->getActiveSheet()->setTitle('Simple');


     
      $objPHPExcel->setActiveSheetIndex(0);



      $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
      $objWriter->save('./'.$filename);



 if(file_exists('./'.$filename)){
   
\Controller::loadConfigForPorteur('fr_rinalda');
    $query=IspReport::model()->find(array(
                      
                      'condition' => 'idreport_sf = :idreport_sf ',
                      'params'    => array(':idreport_sf' => $reportId  )
                  ));

    $query->isdownloaded=1;
    $query->chemin=$filename;
    $query->save(); 

  
         $this->redirect(Yii::App()->getBaseUrl(true).'/index.php/ispgenerate/generate1?filename='.str_replace('+', '%2B',$filename));

              
     }
     else{
     echo "AN ERROR WAS ACCURED : the report is not générated .PLZ TRY AGAIN !!";
     $this->redirect(Yii::App()->getBaseUrl(true).'/index.php/ispgenerate/generate1?filename='.str_replace('+', '%2B',$filename));

     }

           

   
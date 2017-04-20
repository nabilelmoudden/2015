<?php
/**
 * le controlleur de génération des rapports FAIs.
 *
 * il contient les methodes permettant la génération des rapports FAIs.
 *
 *@package businessCore\controllers
 */


use \Business\IspCompaign ;
use \Business\IspReport ;
use \Business\Reportsearch;

class IspauthController extends Controller
{

 

public function actionLogin()
{
    $form=new \LoginForm;

    if(isset($_POST['LoginForm']))
    {
        $form->attributes=$_POST['LoginForm'];

        if($form->validate()  && $form->login())
         {$this->redirect(Yii::App()->getBaseUrl(true).'/index.php/ispgenerate/generate');}
    }

    $this->render('//login/isplogin',array('form'=>$form));
}



public function actionLogout()
{

    Yii::app()->user->logout();
  
  $this->redirect(Yii::App()->getBaseUrl(true).'/index.php/ispauth/login');

}


}
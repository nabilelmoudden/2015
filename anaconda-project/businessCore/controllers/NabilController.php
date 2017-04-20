<?php
/**
 * Description of Controller controller
 *
 * @author YacineR
 * @package Controllers
 */


// importer l'extension AnacondaBehavior
\Yii::import('ext.AnacondaBehavior');

class NabilController extends AdminController
{
	
	public $layout	= '//login/menu';


	// ************************** ACTION ************************** //

    public function actionIndex()
    {
        $this->layout = "";
        $this->renderPartial('//MoteurTestDev/index'); 

    }
}
?>
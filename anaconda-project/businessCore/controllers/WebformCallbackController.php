<?php
/**
 * Description of WebformCallbackController
 *
 * @author JulienL
 */
class WebformCallbackController extends Controller
{

	public function actionOK()
	{
		echo \WebForm::RES_OK;
		\Yii::app()->end();
	}

	public function actionNOK()
	{
		echo \WebForm::RES_NOK;
		\Yii::app()->end();
	}
}

?>
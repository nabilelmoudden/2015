<?php
 /**
 * CocoAction class file.
 *
 *
 *	@example:
 *		public function actions() { return array('coco'=>array('class'=>'CocoAction')); }
 *
 * @author Christian Salazar <christiansalazarh@gmail.com>
 * @license http://opensource.org/licenses/bsd-license.php
 */
class CocoAction extends CAction {
	/**
	* this action invokes the appropiated handler referenced by a 'classname' url attribute.
	* the specified classname must implements: EYuiActionRunnable.php
	*/
	public function run(){
		//Yii::log('ACTION CALLED','info');
		$inst = new CocoWidget();
		$inst->runAction($_GET['action'],$_GET['data']);
	}
 }


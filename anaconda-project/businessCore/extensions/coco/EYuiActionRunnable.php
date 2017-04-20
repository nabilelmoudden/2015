<?php
/**
 * EYuiActionRunnable class file.
 *
 *	Allow every based EYuiWidget-Based widget to respond a EYuiAction call.
 *
 *	@see
 *		EYuiAction
 *
 * @author Christian Salazar <christiansalazarh@gmail.com>
 * @link https://bitbucket.org/christiansalazarh/eyui
 * @license http://opensource.org/licenses/bsd-license.php
 */
interface EYuiActionRunnable {

	/**
	* Method called whenever an EYui widget is invoked from an action int order to start query model.
	*
	*/
	public function runAction($action,$data);
}
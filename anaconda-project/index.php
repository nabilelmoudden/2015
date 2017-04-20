<?php
ini_set( 'display_errors', '1' );
error_reporting( E_ALL );
//exec( 'chmod 777 -R '. dirname(__FILE__).'/../../' );
header('Content-Type: text/html; charset=UTF-8');


// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once( 'framew0rk/framework/yii.php' );
require_once( dirname(__FILE__).'/businessCore/config/esoterDefine.php' );

$config = CMap::mergeArray
	(
		require dirname(__FILE__).'/businessCore/config/main.php',
		require dirname(__FILE__).'/businessCore/config/esoter.php',
		( IS_DEV_VERSION ) ? require DIR_CONF_PORTEUR.'/'.FILE_CONF_PORTEUR_DEV : require DIR_CONF_PORTEUR.'/'.FILE_CONF_PORTEUR
	);

Yii::createWebApplication($config)->run();
?>
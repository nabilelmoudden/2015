<?php
ini_set( 'display_errors', '1' );
error_reporting( E_ALL );
//exec( 'chmod 777 -R '. dirname(__FILE__).'/../../' );
header('Content-Type: text/html; charset=UTF-8');

// change the following paths if necessary
$yiic	= dirname(__FILE__).'/../../framew0rk/framework/yiic.php';
require_once( dirname(__FILE__).'/config/esoterDefine.php' );
require_once( dirname(__FILE__).'/../../framew0rk/framework/yii.php' );

//$config	= dirname(__FILE__).'/config/console.php';
$config = CMap::mergeArray
	(
		require dirname(__FILE__).'/config/console.php',
		require dirname(__FILE__).'/config/esoter.php'
	);

require_once($yiic);

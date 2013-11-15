<?php

ini_set('display_errors', 'on');
ini_set('log_errors', 'on');
error_reporting(E_ALL | E_STRICT);
//error_reporting(0);

$yii = '/usr/share/php/yii/yii.php';
$config = dirname(dirname(__FILE__)).'/protected/config/main.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG', true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);

require_once($yii);
Yii::createWebApplication($config)->run();

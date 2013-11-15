<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'language' => 'es',
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Bng5',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'xxxx',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),

	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		'urlManager'=>array(
			'urlFormat'=>'path',
            'showScriptName'=>false,
			'rules'=>array(
                'http://pablo.bng5.net/' => 'pablo/',
                'http://pablo.bng5.net/<action:\w+>' => 'pablo/<action>',
                'http://pablo.bng5.net/<action:\w+>/<id:[_.\w]+>' => 'pablo/<action>',
//                'bliki<formato:\.[a-z]+>' => 'bliki/index',
                '<controller:\w+>\.<formato:[a-z]+>' => '<controller>/index',
				'blogroll/<feed:[-_.\w]+>' => 'blogroll/index',
				'bliki/<item:[_.\w]+>' => 'bliki/post',
				'bliki/<item:[_.\w]+>/<action:\w+>' => 'bliki/<action>',
				'tags/<item:[_.\w]+>' => 'tags/tag',
				'<controller:\w+>/<action:\w+>/<id:[_.\w]+>' => '<controller>/<action>',
		/*
                'multilevelpiechart/js/<format:\w+>' => array('multilevelpiechart/js', 'urlSuffix'=>'.js', 'caseSensitive'=>false),
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
		*/
			),
		),
        'cache'=>array(
            'class'=>'system.caching.CFileCache',
        ),
		//'db'=>array(
		//	'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		//),
		// uncomment the following to use a MySQL database
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=bng5_net',
			'emulatePrepare' => true,
			'username' => 'xxxx',
			'password' => 'xxxx',
			'charset' => 'utf8',
		),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning, info',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
	),

    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params'=>array(
        // this is used in contact page
        'adminEmail'=>'xxxx',
        'siteSalt' => 'xxxx',
    ),
);
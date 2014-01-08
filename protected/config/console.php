<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
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
	// application components
	'components'=>array(
		// uncomment the following to use a MySQL database
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=bng5_net',
			'emulatePrepare' => true,
			'username' => 'bng5_net',
			'password' => 'R?G{%rDyfvT-',
			'charset' => 'utf8',
		),
        'couchdb'=>array(
            'class' => 'Couchdb',
//            'autoConnect' => false,
//            'emulatePrepare' => true,
//            'connectionString' => 'mysql:host=10.163.1.15;dbname=gbg',
            'host' => 'localhost',
            'port' => '5984',
//            'charset' => 'utf8',
        ),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
			),
		),
	),
);
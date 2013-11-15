<?php

class NsController extends Controller {

	/**
	 * @return array action filters
	 */
	public function filters() {
		return array(
			'accessControl', // perform access control for CRUD operations
			//'postOnly + delete', // we only allow deletion via POST request
		);
	}
    
    /**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules() {
		return array(
//			array('allow',  // allow all users to perform 'index' and 'view' actions
//				'actions' => array(
//                    'index',
//                    'post',
//                    'comentar',
//                ),
//				'users'=>array('*'),
//			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions' => array(
                    'editar',
                ),
				'users'=>array('@'),
			),
//    //			array('allow', // allow admin user to perform 'admin' and 'delete' actions
//    //				'actions'=>array('admin','delete'),
//    //				'users'=>array('admin'),
//    //			),
			array('deny',  // deny all users
				'actions' => array(
                    'editar',
                ),
				'users'=>array('*'),
			),
		);
	}
    
    public function actionIndex() {
        $this->breadcrumbs = array(
            'Namespace',
        );
        $namespaces = array(
            'post' => array(
                'Post',
            ),
        );
        $this->render('index', array(
            'namespaces' => $namespaces,
        ));
	}

    public function actionPost() {
        $this->breadcrumbs = array(
            'Namespace' => array('ns/'),
            'post',
        );
        $view = $this->render('post');
    }
}
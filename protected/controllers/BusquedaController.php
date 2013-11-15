<?php

class BusquedaController extends Controller {
   
    public function actionIndex() {
        $this->pageTitle = 'Búsqueda - Bng5.net';
        $this->actionGoogle();
	}

    public function actionGoogle() {
        Yii::app()->clientScript->registerScriptFile('https://www.google.com/jsapi', CClientScript::POS_HEAD);
        Yii::app()->clientScript->registerScriptFile('/js/busqueda.js', CClientScript::POS_HEAD);
        $query['cx'] = '000505282032523587963:plblebphvbg';
        $query['q'] = $_GET['q'];
        $query['nojs'] = 1;
		$this->render('index', array(
            'google_query' => $query,
        ));
	}
}
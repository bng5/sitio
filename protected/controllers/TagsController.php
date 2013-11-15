<?php

class TagsController extends Controller {

    
    public function __construct($path = null) {
        parent::__construct($path);
        array_unshift($this->pageTitle, 'Tags');
    }
    
    public function actionIndex() {

		$dataProvider = new CActiveDataProvider('Tag', array(
            'criteria'=>array(
//                'condition' => 'estado = 1',
                'order' => 'tag',
                'with' => array('posts_count'),
            ),
//            'pagination'=>array(
//                'pageSize'=>6,
//                //'currentPage' => --$pag,
//                'pageVar' => 'pagina',
//                'route' => '/bliki',//$request['path'],
//                'params'=> $params,
//            ),
        ));
        
//        $model = new Suscriptions('search');
//        $model->unsetAttributes();  // clear any default values
//        if (isset($_GET['Suscriptions']))
//            $model->attributes = $_GET['Suscriptions'];
		$this->render('index',array(
			'dataProvider'=>$dataProvider->data,
		));
	}

    public function actionTag() {
        $tag = $this->loadModel($this->actionParams['item']);
		$this->render('tag', array(
			'tag' => $tag,
		));
    }


    public function loadModel($item) {

        $model = Tag::model()->find(array(
            'condition' => 'tag=:tag',
            'params' => array(':tag' => $item),
        ));
//        if ($model === null)
//            throw new CHttpException(404, 'no está');
        return $model;
    }
    


}
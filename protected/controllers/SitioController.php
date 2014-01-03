<?php

class SitioController extends Controller {
	/**
	 * Declares class-based actions.
	 */
	public function actions() {
		return array(
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex() {
        $this->layout = '//layouts/column2';
        $list = Post::model()->find('created');
//		$dataProvider = new CActiveDataProvider('Post', array(
//            'criteria'=>array(
//                'condition'=>'estado = 1',
////                'order'=>'create_time DESC',
////                'with'=>array('author'),
//            ),
//            'pagination'=>array(
//                'pageSize'=>6,
//                //'currentPage' => --$pag,
//                'pageVar' => 'pagina',
//                'route' => '/bliki',//$request['path'],
////                'params'=> $params,
//            ),
//        ));
        
		$this->render('index', array(
//            'dataProvider'=>$dataProvider,
            'model' => $list,
        ));
	}
    
    public function actionMapa() {
        $this->render('mapa');
    }

    public function actionPowered_by() {
        $this->render('powered_by');
    }

	/**
	 * Displays the contact page
	 */
	public function actionContact() {
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

    public function actionCookies() {
        var_dump($_COOKIE);
    }
}
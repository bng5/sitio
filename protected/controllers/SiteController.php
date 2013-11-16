<?php

class SiteController extends Controller {
	/**
	 * Declares class-based actions.
	 */
	public function actions() {
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
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

    public function actionStatus() {
        if(array_key_exists('id', $this->actionParams) && is_numeric($this->actionParams['id'])) {
            throw new CHttpException($this->actionParams['id']);
        }
    }

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError() {
        $error = Yii::app()->errorHandler->error;
//        'type' => string 'CHttpException'

        try {
            $this->render('error_'.$error['code'], $error);
        } catch (Exception $exc) {
//            echo $exc->getTraceAsString();
            $this->render('error', $error);
        }

//		if($error=Yii::app()->errorHandler->error) {
//			if(Yii::app()->request->isAjaxRequest)
//				echo $error['message'];
//			else {
//				$this->render('error', $error);
//            }
//		}
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

	/**
	 * Displays the login page
	 */
	public function actionLogin() {
		$model = new LoginForm;
        $error = false;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm'])) {
			$model->attributes = $_POST['LoginForm'];
            //try {
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
            //} catch (CDbException $exc) {
            //    $this->showCDbException($exc->getCode());
            //    //echo $exc->getTraceAsString();
            //    var_dump($exc);
            //}
		}
        // display the login form
        $this->render('login', array(
            'model' => $model,
            'error' => $error,
        ));
    }

    /**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout() {
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}
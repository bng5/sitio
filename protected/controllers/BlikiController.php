<?php

class BlikiController extends Controller {

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
    
    public function actionIndex($pagina = 1, $id = null) {
        if($id && is_numeric($id)) {
            $post = Post::model()->findByPk($id);
            $this->redirect('/bliki/'.$post->path);
            exit;
        }
        
//        $request = parse_url($_SERVER["REQUEST_URI"]);
//        parse_str($request['query'], $params);
        $params = $this->actionParams;

        $list = Post::model()->find('created');
        
        $this->render('index',array(
            'model' => $list,
//            'dataProvider' => $dataProvider,
        ));
        return;
//		$dataProvider = new CActiveDataProvider('Post', array(
//            'criteria'=>array(
//                'condition' => 'estado = 1',
////                'order'=>'create_time DESC',
////                'with'=>array('author'),
//            ),
//            'pagination'=>array(
//                'pageSize'=>6,
//                //'currentPage' => --$pag,
//                'pageVar' => 'pagina',
//                'route' => '/bliki',//$request['path'],
//                'params'=> $params,
//            ),
//        ));
        
//        $model = new Suscriptions('search');
//        $model->unsetAttributes();  // clear any default values
//        if (isset($_GET['Suscriptions']))
//            $model->attributes = $_GET['Suscriptions'];
        
        $formato = array_key_exists('formato', $this->actionParams) ? substr($this->actionParams['formato'], 1) : 'xhtml';
        switch($formato) {
            case 'json':
                $data = array();
                foreach ($dataProvider->data AS $v) {
                    $attributes = array(
                        'id' => $v->id,
                        'path' => $v->path,
                        'titulo' => $v->titulo,
                        'fecha_creado' => $v->fecha_creado,
                        'fecha_modificado' => $v->fecha_modificado,
                        'resumen' => $v->resumen,
                        'comentarios_habilitados' => $v->comentarios_habilitados,
                    );
                    $data[] = $attributes;
                }
                echo json_encode($data);
                break;
            case 'rss':
                echo 'TODO
                    RSS';
                break;
            case 'xhtml':
                $this->render('index',array(
                    'dataProvider'=>$dataProvider,
                ));
                break;
            default:
                // múltiples opciones
                throw new CHttpException(404, sprintf('No existe la representación %s.', $formato));
                break;
        }
	}

    public function actionPost() {
        $path = $this->actionParams['item'];
        $model = Post::model()->get($path);
        if(!$model) {
            throw new CHttpException(404, "No existe el artículo {$path}.");
        }
        
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        
//        if ($model->isNewRecord) {
//            $model->status = 1;
//        }

        $use_cache = false;
        array_unshift($this->pageTitle, $model->title);
        $this->breadcrumbs = array(
            'Bliki' => array('bliki/'),
            $model->title,
        );
        Yii::app()->clientScript->registerLinkTag('alternate', 'application/xml', "/bliki/{$path}/source");
        Yii::app()->clientScript->registerCssFile('/css/avisos.css');

        $renderer = $this->___renderer($model->tokens);

        $new_comment = property_exists($model, 'comentarios_habilitados') ? new Comment() : false;

        $view = $this->render('post', array(
            'path' => $path,
            'titulo' => $model->title,
            'html' => $renderer->__toString(),
            'post' => $model,//->post,
            'new_comment' => $new_comment,
//            'comments' => $model->comments,
            'cache' => $use_cache,
        ), true);
        echo $view;
    }
    
    private function ___renderer($arr) {
        $renderer = new BlikiRenderer();
        foreach($arr AS $instruction) {
            $renderer->append((array) $instruction);
        }
        return $renderer;
    }

    
    public function actionSource() {
        header("Content-Type: application/json");
        $model = $this->loadModel($this->actionParams['item']);
        echo json_encode($model);
//        $model = $this->loadModel($this->actionParams['item']);
//        header("Content-Type: application/xml");
//        readfile("../data/post/{$model->id}.{$model->rev}.xml");
    }
    
    public function actionEditar() {
        $error = false;
        $path = $this->actionParams['item'];
        $model = $this->loadModel($path);
        $this->render('edit', array(
            'path' => $path,
            'estado' => $model->estado,
            'model' => $model->post,
            'error' => $error,
        ));
return;
        if($model) {
            $model->loadSource();
//var_dump($model);
//var_dump($model->post);
//exit;
        }
        else {
            $model = new Post();
            $model->setPost(new PostContent());
            $model->post->path = $this->actionParams['item'];
            $model->post->titulo = ucfirst(str_replace('_', ' ', $this->actionParams['item']));
            $model->post->fecha_creado = time();
        }
        
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
//            if($model->isNewRecord || array_key_exists('publicar', $_POST)) {
                $path = $_POST['PostContent']['path'];
                $model->attributes = $_POST['PostContent'];
//                $model->toc_habilitado = (array_key_exists('toc_habilitado', $_POST['PostContent']) && $_POST['PostContent']['toc_habilitado']);
                $model->comentarios_habilitados = (array_key_exists('comentarios_habilitados', $_POST['PostContent']) && $_POST['PostContent']['comentarios_habilitados']);
//                $model->estado = array_key_exists('publicar', $_POST) ? 1 : 0;
                $model->save();
//            }
            $model->post->attributes = $_POST['PostContent'];
            $model->post->toc_habilitado = (array_key_exists('toc_habilitado', $_POST['PostContent']) && $_POST['PostContent']['toc_habilitado']);;
            $model->post->comentarios_habilitados = $model->comentarios_habilitados;
            
            PostTags::model()->deleteAll('post_id = :post_id', array(
                ':post_id' => $model->id,
            ));
            foreach($_POST['PostContent']['tags'] AS $t) {
                $tag = new Tag;
                $tag->tag = $t;
                $dataProvider = $tag->search();
                if($dataProvider->itemCount) {
                    $id = $dataProvider->data[0]->id;
                }
                else {
                    $tag->titulo = $t;
                    $tag->descripcion = $t;
                    if(!$tag->save()) {
            //var_dump($tag->getErrors());
                    }
                    $id = $tag->id;
                }
                $postTag = new PostTags;
                $postTag->post_id = $model->id;
                $postTag->tag_id = $id;
                $postTag->save();
            }
            
            
            try {
                $model->post->save();
                Yii::app()->user->setFlash('success', true);
                $this->redirect(array("bliki/{$path}/editar"));
            } catch (Exception $exc) {
                $error = $exc->getMessage();
            }
        }
        
        $this->render('edit', array(
            'path' => $path,
            'estado' => $model->estado,
            'model' => $model->post,
            'error' => $error,
        ));
return;
        try {
            $model->loadSource();
        } catch (Exception $exc) {
            $error = $exc->getMessage();
            $this->render('recuperacion', array(
                'model' => $model,
                'error' => $error,
            ));
            return;
        }

    }
    
    public function actionHistorial() {
        $model = $this->loadModel($this->actionParams['item']);
        $this->render('historial', array(
            'model' => $model,
        ));

    }

    public function actionComentarios() {
        $model = $this->loadModel($this->actionParams['item']);
        if ($model === null)
            throw new CHttpException(404, 'no está');
        
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $comment = new Comment();
            
            $comment->post_id = $model->id;
            $comment->texto = $_POST['Comentario']['comentario'];
            $comment->remote_addr = sprintf("%u", ip2long($_SERVER['REMOTE_ADDR']));
            $comment->status = 0;
            $comment->fecha_creado = time();
            $comment->validate();
            
            if(!in_array($_POST['Auth']['type'], array(
                'persona',
                'twitter',
                'linkedin',
                'openid',
            ))) {
                $comment->addError('auth', 'Debe especificar el método de identificación.');
            }
            
            if($comment->hasErrors()) {
                $this->render('comentar', array(
                    'post' => $model,
                    'comentario' => $comment,
                ));
                return;
            }
            $auth = false;
            switch($_POST['Auth']['type']) {
                case 'persona':
                    $auth = $this->persona($_POST['Auth']['persona_assertion'], $comment);
                    break;
                case 'twitter':
                case 'linkedin':
                    $comment->save();
                    $this->actionOauth($_POST['Auth']['type'], $comment->id, $this->actionParams['item']);
                    break;
                case 'openid':
                    $comment->save();
                    $this->actionOpenid($_POST['Auth']['openid_identifier'], $comment->id, $model->id);
                    break;
//                default:
//                    echo CHtml::link('redirect', array('post', 'item' => $this->actionParams['item']));
//                    break;
            }
            
            if($auth) {
                Yii::app()->cache->delete('come:'.$model->id);
                $this->redirect("/bliki/{$model->path}#comentario-{$comment->id}");
            }
            else {
                $this->render('comentar', array(
                    'post' => $model,
                    'comentario' => $comment,
                ));
            }
            
        }
    }
    
    public function loadModel($item) {
        $model = Post::model()->get($item);
        return $model;
        
        $model = Post::model()->find(array(
            'condition' => 'path=:path',
            'params' => array(':path' => $item),
        ));
//        if ($model === null)
//            throw new CHttpException(404, 'no está');
        return $model;
    }
    
    public function loadAuthor($provider, $id) {
        $model = Author::model()->find(array(
            'condition' => 'tipo = :provider AND provider_id = :id',
            'params' => array(
                ':provider' =>  $provider,
                ':id' => $id,
            ),
        ));
        if(!$model) {
            $model = new Author();
            $model->tipo = $provider;
            $model->provider_id = $id;
        }
        return $model;
    }
    
    public function getAuthor($email) {

        $author = Author::model()->find('email = :email', array(':email' => $email));
        Yii::log("Buscando autor: {$email}");
        if(!$author) {

            Yii::log("No existe: {$email}");
            
            $author = new Author();
            $author->email = $email;
            if($author->validate()) {
                if($this->gravatarValidation($email)) {
                    Yii::log('Verificado por Gravatar');
                    $author->verificado = Author::VERIFICACION_GRAVATAR;
                }
                else {
                    Yii::log('No verificado por Gravatar');
                    $author->hostValidation();
                }
            }
            if(!$author->hasErrors()) {
                $author->save();
            }
        }
        return $author;
    }
            
    public function gravatarValidation($email) {
        $ch = curl_init('http://www.gravatar.com/avatar/'.md5($email).'?d=404');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return ($status != 404);
    }

//    protected function twitterLogin($auth) {
//        $provider = array(
//            'key' => 'PocyRN5tfzTOJDYyBUPXsA',
//            'secret' => 'sjlBXQfJLoqWoWN1gzqTHfk7c5sqBcDH8ugIWzd3h6s',
//            'provider'=>array(
//                'request'=>'https://api.twitter.com/oauth/request_token',
//                'authorize'=>'https://api.twitter.com/oauth/authorize',
//                'access'=>'https://api.twitter.com/oauth/access_token',
//            )
//        );
//        $this->oauthLogin('twitter', $provider);
//    }
    
    protected function oauth_provider_info($provider) {
        $providers = array(
            'linkedin' => array(
                'key' => 'b446jl32ux9g',
                'secret' => 'Bk3Th4lLSIEWmMXG',
                'provider'=>array(
                    'request' => 'https://api.linkedin.com/uas/oauth/requestToken',
                    'authorize' => 'https://api.linkedin.com/uas/oauth/authenticate',
                    'access' => 'https://api.linkedin.com/uas/oauth/accessToken',
                )
            ),
            'twitter' => array(
                'key' => 'PocyRN5tfzTOJDYyBUPXsA',
                'secret' => 'sjlBXQfJLoqWoWN1gzqTHfk7c5sqBcDH8ugIWzd3h6s',
                'provider'=>array(
                    'request'=>'https://api.twitter.com/oauth/request_token',
                    'authorize'=>'https://api.twitter.com/oauth/authorize',
                    'access'=>'https://api.twitter.com/oauth/access_token',
                )
            ),
        );
        return $providers[$provider];
    }
    
    protected function oauth_api_info($provider) {
        $profiles = array(
            'linkedin' => array(
                'url' => 'http://api.linkedin.com/v1/people/~:(id,firstName,lastName,siteStandardProfileRequest,picture-url)',
                'params' => array('format' => 'json'),
            ),
            'twitter' => array(
                'url' => 'https://api.twitter.com/1/account/verify_credentials.json',
                'params' => null,
            ),
        );
        return $profiles[$provider];
    }
    
    public function actionOauth($prov, $c, $i) {
        Yii::import('ext.eoauth.*');

        $provider = $this->oauth_provider_info($prov);
        $ui = new EOAuthUserIdentity($provider);
        $ui->path = "/bliki/oauth/prov/{$prov}/c/{$c}/i/{$i}";

        if($ui->authenticate()) {
            $profile = $this->oauth_api_info($prov);

            $url = $profile['url'];
            $params = $profile['params'];
            $signatureMethod = new OAuthSignatureMethod_HMAC_SHA1();
            $request = OAuthRequest::from_consumer_and_token($ui->provider->consumer, $ui->provider->token, 'GET', $url, $params);
            $request->sign_request($signatureMethod, $ui->provider->consumer, $ui->provider->token);

            $url .= $params ? '?'.http_build_query($params, null, '&') : '';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            $head[] = $request->to_header();
            curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
            $response = curl_exec($ch);
            $headers = curl_getinfo($ch);
            curl_close($ch);

            $response_obj = json_decode($response);
            if(!$response_obj) {
                var_dump($response,
                    $headers);
                exit;
            }
            
            
            switch($prov) {
                case 'twitter':
                    $author = $this->loadAuthor(Author::TIPO_TWITTER, $response_obj->id);
                    $author->nombre = "{$response_obj->name} (@{$response_obj->screen_name})";
                    $author->avatar = str_replace('_normal.', '_bigger.', $response_obj->profile_image_url);
                    $author->link = "https://twitter.com/{$response_obj->screen_name}";
            //        screen_name {$response_obj->screen_name}
            //        echo "
            //        profile_image_url_https <img src=\"{$response_obj->profile_image_url_https}\" alt=\"\" />
            //        profile_image_url bigger <img src=\"".str_replace('_normal.', '_bigger.', $response_obj->profile_image_url)."\" alt=\"\" />
            //        profile_image_url mini <img src=\"".str_replace('_normal.', '_mini.', $response_obj->profile_image_url)."\" alt=\"\" />
            //        ";
                    break;
                case 'linkedin':
                    $author = $this->loadAuthor(Author::TIPO_LINKEDIN, $response_obj->id);
                    $author->nombre = "{$response_obj->firstName} {$response_obj->lastName}";
                    $author->avatar = $response_obj->pictureUrl;
                    $author->link = $response_obj->siteStandardProfileRequest->url;
                    break;
            }

            $author->data = serialize(array(
                'access_token' => $ui->provider->token->key,
                'secret_token' => $ui->provider->token->secret,
            ));
            $author->save();
            $comment = Comment::model()->findByPk($c);
            $comment->status = 1;
            //$comment->status = $author->whitelist;
            $comment->author_id = $author->id;
            $comment->parseMessage();
            $comment->save();
            Yii::app()->cache->delete('come:'.$comment->post_id);
            $this->redirect("/bliki/{$i}#comentario-{$c}");
        }
        else {
var_dump($ui);
//            throw new CHttpException(401, $ui->error);
        }
    }
    
    public function actionOpenid($openid_identifier = null, $c = null, $i = null) {
        Yii::import('ext.eopenid.*');
        $openid = new EOpenID;
        if(!isset($this->actionParams['openid_mode'])) {
            if(isset($openid_identifier)) {
                $trustRoot = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
                $openid->returnUrl = $trustRoot."/bliki/openid/c/{$c}/i/{$i}";//$_SERVER['REQUEST_URI'];
                $openid->authenticate($openid_identifier);
            }
        }
        else {
            $comment = Comment::model()->findByPk($c);
            if($openid->validate()) {
                $identity = $openid->getIdentity();
                $author = $this->loadAuthor(Author::TIPO_OPENID, $identity);

                $author->nombre = preg_replace('/(^https?:\/\/)|(\/$)/', '', $identity);
                $author->avatar = '/img/avatar/openid';
                $author->link = $identity;
                $data = $openid->getData();
                $author->data = serialize(array(
                    'ns' => $data->openid_ns,
                    'mode' => $data->openid_mode,
                    'op_endpoint' => $data->openid_op_endpoint,
                    'identity' => $data->openid_identity,
                    'claimed_id' => $data->openid_claimed_id,
                ));
                $author->save();

                $comment->status = 1;
                //$comment->status = $author->whitelist;
                $comment->author_id = $author->id;
                $comment->parseMessage();
                $comment->save();
                Yii::app()->cache->delete('come:'.$comment->post_id);
                $this->redirect("/bliki/{$comment->post->path}#comentario-{$c}");
            }
            else {
//$data = $openid->getData();
//var_dump($data);

                $comment->addError('openid', 'No fue posible validar tu cuenta OpenID.');
                $this->render('comentar', array(
                    'post' => $comment->post,
                    'comentario' => $comment,
                ));
exit;
                throw new CHttpException(404, 'No fue posible autenticar la cuenta OpenID.');
            }

        }
        //$this->render('openIDLogin',array('openid'=>$openid));
    }

    protected function persona($persona_assertion, $comment) {
                   
        $data = array(
            'assertion' => $persona_assertion,
            'audience' => "{$_SERVER['HTTP_HOST']}:80",
        );
        $ch = curl_init('https://verifier.login.persona.org/verify');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data, null, '&'));
        $response = curl_exec($ch);
//        $headers = curl_getinfo($ch);
        curl_close($ch);

        $response_obj = json_decode($response);
        //stdClass Object (
        //    [status] => okay
        //    [email] => pablobngs@gmail.com
        //    [audience] => localhost.bng5.net:80
        //    [expires] => 1360211751966
        //    [issuer] => login.persona.org
        //)

        if($response_obj && $response_obj->status == 'okay') {
            $author = $this->loadAuthor(Author::TIPO_PERSONA, $response_obj->email);
            $author->nombre = preg_replace('/@(.*)$/', '…', $response_obj->email);
            $author->avatar = md5($response_obj->email);
            //$author->link = $response_obj->url;

            $author->save();
            $comment->status = 1;
            $comment->author_id = $author->id;
            $comment->parseMessage();
            $comment->save();
            return true;
        }
        return false;
    }
}
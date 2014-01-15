<?php

class BlikiController extends Controller {

    public $path;
    
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
                    'status_comentario',
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
                    'status_comentario',
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

        array_unshift($this->pageTitle, $model->title);
        $this->breadcrumbs = array(
            'Bliki' => array('bliki/'),
            $model->title,
        );
        
        header("X-Pingback: http://localhost.bng5.net/recibe");
        Yii::app()->clientScript->registerLinkTag('pingback', null, "http://localhost.bng5.net/recibe");
//        header("X-Pingback: http://{$_SERVER['SERVER_NAME']}/pingback/xmlrpc");
//        Yii::app()->clientScript->registerLinkTag('pingback', null, "http://{$_SERVER['SERVER_NAME']}/pingback/xmlrpc");
        Yii::app()->clientScript->registerLinkTag('alternate', 'application/xml', "/bliki/{$path}/source");
        Yii::app()->clientScript->registerCssFile('/css/avisos.css');

        $use_cache = true;
        $cache = '';
        if(!Yii::app()->user->isGuest && array_key_exists('cache', $_GET)) {
            $cache = $_GET['cache'];
            switch($cache) {
                case 'clear':
//                    Yii::app()->cache->delete($cache_id_post);
                case 'no':
                    $use_cache = false;
                    break;
            }
        }

   
        $new_comment = $model->nocomments ? false : new Comment();

        $comments = Comment::model()->find('comments/_view/by_post', array(
            'startkey' => '["'.$model->_id.'", 0]',
            'endkey' => '["'.$model->_id.'", 2147483647]',
        ));
        
        $view = $this->render('post', array(
            'path' => $path,
            'titulo' => $model->title,
            'post' => $model,//->post,
            'new_comment' => $new_comment,
            'comments' => $comments,
//            'comments' => $model->comments,
            'cache' => $cache,
        ));/*, true);
        echo $view;*/
    }
    
    protected function _renderer($arr) {
        $renderer = new BlikiRenderer();
        foreach($arr AS $instruction) {
            $renderer->append((array) $instruction);
        }
        return (string) $renderer;
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
        $html = null;
        
        $model = Post::model()->get($path);//$this->loadModel($path);
        if(!$model) {
            $model = new Post;
            $model->_id = $path;
            $model->path = $path;
        }
        
        $preview = false;
        if(array_key_exists('preview', $_REQUEST)) {
//var_dump(Yii::app()->user);
//exit;
            $preview = true;
            $parser = new BlikiParser;
            try {
                $parser->parse($model->content);
                $html = $this->_renderer($parser->getTokens());
            } catch (Exception $exc) {
                $error = $exc->getMessage();
            }
            Yii::app()->clientScript->registerCssFile('/css/avisos.css');
        }
            
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $post = $_POST['Post'];
            $post['tags'] = array_unique(array_filter($post['tags']));
            $model->setAttributes($post);


            if(!$preview) {
                

//            if($model->_rev) {// || array_key_exists('publicar', $_POST)) {
//                $path = $_POST['PostContent']['path'];
//                $model->attributes = $_POST['PostContent'];
////                $model->toc_habilitado = (array_key_exists('toc_habilitado', $_POST['PostContent']) && $_POST['PostContent']['toc_habilitado']);
//                $model->comentarios_habilitados = (array_key_exists('comentarios_habilitados', $_POST['PostContent']) && $_POST['PostContent']['comentarios_habilitados']);
////                $model->estado = array_key_exists('publicar', $_POST) ? 1 : 0;
//                $model->save();
////            }
//            $model->post->attributes = $_POST['PostContent'];
//            $model->post->toc_habilitado = (array_key_exists('toc_habilitado', $_POST['PostContent']) && $_POST['PostContent']['toc_habilitado']);;
//            $model->post->comentarios_habilitados = $model->comentarios_habilitados;
//            
            
                try {
                    $model->save();
                    Yii::app()->user->setFlash('success', true);
                    $this->redirect(array("bliki/{$path}/editar?preview"));
                } catch (Exception $exc) {
                    $error = $exc->getMessage();
                }
            }
        }
        else {
//            $model = Post::model()->get($path);//$this->loadModel($path);
        }
        
//var_dump($model);
//exit;
//        if($model) {
//        }
//        else {
//            $model = new Post();
//            $model->setPost(new PostContent());
//            $model->post->path = $this->actionParams['item'];
//            $model->post->titulo = ucfirst(str_replace('_', ' ', $this->actionParams['item']));
//            $model->post->fecha_creado = time();
//        }
        $this->render('edit', array(
            'path' => $path,
//            'estado' => $model->estado,
            'model' => $model,
            'error' => $error,
            'preview' => $html,
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
        if ($model === null) {
            throw new CHttpException(404, 'no está');
        }
        
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $comment = new Comment();
            $comment->post_id = $model->_id;
            if($_POST['Comentario']['reply_to']) {
                $comment->reply_to = $_POST['Comentario']['reply_to'];
            }
            $comment->message = $_POST['Comentario']['comentario'];
            $comment->remote_addr = sprintf("%u", ip2long($_SERVER['REMOTE_ADDR']));
            $comment->status = 0;
            $comment->created_at = time();
            if(array_key_exists('HTTP_USER_AGENT', $_SERVER)) {
                $user_agent = new WpUserAgent();
                $user_agent->useragent = $_SERVER['HTTP_USER_AGENT'];
                $comment->user_agent = array(
                    $_SERVER['HTTP_USER_AGENT'],
                    array(
                        'browser' => $user_agent->detect_webbrowser(),
                        'platform' => $user_agent->detect_platform(),
                    ),
                );
            }
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
                    'post_id' => $model->_id,
                    'comentario' => $comment,
                ));
                return;
            }
            
            $comment->html = $comment->message;
            
            $auth = false;
            switch($_POST['Auth']['type']) {
                case 'persona':
                    $auth = $this->persona($_POST['Auth']['persona_assertion'], $comment);
                    break;
                case 'twitter':
                case 'linkedin':
                    $comment->auth = $_POST['Auth'];
                    $comment->save();
                    $this->actionOauth($_POST['Auth']['type'], $comment->_id, $this->actionParams['item']);
                    break;
                case 'openid':
                    $comment->save();
                    $this->actionOpenid($_POST['Auth']['openid_identifier'], $comment->_id, $model->_id);
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
        elseif($_SERVER['REQUEST_METHOD'] == 'DELETE') {
            
        }
    }
    
    public function actionStatus_comentario() {
        $response = array(
            'success' => false,
        );
        header("Content-Type: application/json; charset=UTF-8");
        $model = Comment::model()->get($_POST['id']);
        $model->status = (int) $_POST['status'];
        if($model->save()) {
            $response['success'] = true;
            $response['id'] = $model->_id;
            $response['status'] = $model->status;
            echo json_encode($response);
        }
        else {
            echo json_encode($response);
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
print_r(array(
    ':provider' =>  $provider,
    ':id' => $id,
));
exit;
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
    
    protected function oauth_provider_info($providerId) {
        $providers = array(
            'linkedin' => array(
                'request' => 'https://api.linkedin.com/uas/oauth/requestToken',
                'authorize' => 'https://api.linkedin.com/uas/oauth/authenticate',
                'access' => 'https://api.linkedin.com/uas/oauth/accessToken',
            ),
            'twitter' => array(
                'request'=>'https://api.twitter.com/oauth/request_token',
                'authorize'=>'https://api.twitter.com/oauth/authorize',
                'access'=>'https://api.twitter.com/oauth/access_token',
            ),
        );
        $provider = Yii::app()->params['oauth_providers'][$providerId];
        $provider['provider'] = $providers[$providerId];
        return $provider;
    }
    
    protected function oauth_api_info($provider) {
        $profiles = array(
            'linkedin' => array(
                'url' => 'http://api.linkedin.com/v1/people/~:(id,firstName,lastName,siteStandardProfileRequest,picture-url)',
                'params' => array('format' => 'json'),
            ),
            'twitter' => array(
                'url' => 'https://api.twitter.com/1.1/account/verify_credentials.json',
                'params' => null,
            ),
        );
        return $profiles[$provider];
    }
    
    public function actionOauth($prov, $c, $i) {
        $id_comentario = $c;
        $id_post = $i;
        
        Yii::import('ext.eoauth.*');

        $provider = $this->oauth_provider_info($prov);
        $provider = Yii::app()->params['oauth_providers'][$prov];
        
        
        $ui = new EOAuthUserIdentity($provider);
        $ui->path = "/bliki/oauth/prov/{$prov}/c/{$id_comentario}/i/{$id_post}";

        if($ui->authenticate()) {
            $profile = $this->oauth_api_info($prov);

            $url = $profile['url'];
            $params = $profile['params'];
            $signatureMethod = new OAuthSignatureMethod_HMAC_SHA1();
            $request = OAuthRequest::from_consumer_and_token($ui->provider->consumer, $ui->provider->token, 'GET', $url, $params);
            $request->sign_request($signatureMethod, $ui->provider->consumer, $ui->provider->token);

            $url .= $params ? '?'.http_build_query($params, null, '&') : '';
            $http_client = new Http_Client();
            $response = $http_client->get($url, array('authorization' => $request->to_header()));
//            $ch = curl_init($url);
//            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
//            curl_setopt($ch, CURLOPT_HEADER, 0);
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
//            $head[] = $request->to_header();
//            curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
//            $response = curl_exec($ch);
//            $headers = curl_getinfo($ch);
//            curl_close($ch);

            $response_obj = json_decode($response);
            if(!$response_obj) {
                var_dump($response,
                    $headers);
                exit;
            }
            
            $comment = Comment::model()->get($id_comentario);
            
            switch($prov) {
                case 'twitter':
                    if($response->status == 200) {
                        
//                    if($response_obj->errors) {
//                        ["_status":"Http_Response":private]=>int(410)
//                        ["body":"Http_Response":private]=>string(160) "{"errors": [{"message": "The Twitter REST API v1 is no longer active. Please migrate to API v1.1. https://dev.twitter.com/docs/api/1.1/overview.", "code": 68}]}"
//                        throw new CHttpException(500, $response_obj->errors[0]->message);
//                    }
                    //$author = $this->loadAuthor(Author::TIPO_TWITTER, $response_obj->id);
                    //$author->nombre = "{$response_obj->name} (@{$response_obj->screen_name})";
                    //$author->avatar = str_replace('_normal.', '_bigger.', $response_obj->profile_image_url);
                    //$author->link = "https://twitter.com/{$response_obj->screen_name}";
                        $comment->author = "{$response_obj->name} (@{$response_obj->screen_name})";
                        $comment->author_avatar = str_replace('_normal.', '_bigger.', $response_obj->profile_image_url);
                        $comment->author_website = "https://twitter.com/{$response_obj->screen_name}";
                    }
                    
                    
            //        screen_name {$response_obj->screen_name}
            //        echo "
            //        profile_image_url_https <img src=\"{$response_obj->profile_image_url_https}\" alt=\"\" />
            //        profile_image_url bigger <img src=\"".str_replace('_normal.', '_bigger.', $response_obj->profile_image_url)."\" alt=\"\" />
            //        profile_image_url mini <img src=\"".str_replace('_normal.', '_mini.', $response_obj->profile_image_url)."\" alt=\"\" />
            //        ";
                    break;
                case 'linkedin':
//                    $author = $this->loadAuthor(Author::TIPO_LINKEDIN, $response_obj->id);
                    $comment->author = "{$response_obj->firstName} {$response_obj->lastName}";
                    $comment->author_avatar = $response_obj->pictureUrl;
                    $comment->author_website = $response_obj->siteStandardProfileRequest->url;
                    break;
            }

            
            $comment->author_data = $response_obj;
            $comment->auth->access_token = $ui->provider->token->key;
            $comment->auth->secret_token = $ui->provider->token->secret;
//            $comment->author_data = serialize(array(
//                'access_token' => $ui->provider->token->key,
//                'secret_token' => $ui->provider->token->secret,
//            ));
            //$author->save();
            $comment->status = 1;
            //$comment->status = $author->whitelist;
//            $comment->author_id = $author->id;
//            $comment->parseMessage();
            $comment->save();
            Yii::app()->cache->delete('come:'.$comment->post_id);
            $this->redirect("/bliki/{$id_post}#comentario-{$id_comentario}");
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
            $comment = Comment::model()->get($c);

            if($openid->validate()) {
                /*
                $openid = object(EOpenID)#36 (19) {
                  ["returnUrl"]=>  string(748) "http://ddex.bng5.net/bliki/openid/c/dd44f0c92a09ae783c5e77b1f00030a3/i/estilos?openid.ns=http%3A%2F%2Fspecs.openid.net%2Fauth%2F2.0&openid.mode=id_res&openid.op_endpoint=https%3A%2F%2Fwww.google.com%2Faccounts%2Fo8%2Fud%3Fsource%3Dprofiles&openid.response_nonce=2014-01-13T00%3A15%3A14ZmmVNPE_bCbILYg&openid.return_to=http%3A%2F%2Fddex.bng5.net%2Fbliki%2Fopenid%2Fc%2Fdd44f0c92a09ae783c5e77b1f00030a3%2Fi%2Festilos&openid.assoc_handle=1.AMlYA9XluJPQydBvlvENU9lOeEJmYFD6CI44sqNr1rmwtJ0qu0GA9WvbkxrEyrA2&openid.signed=op_endpoint%2Cclaimed_id%2Cidentity%2Creturn_to%2Cresponse_nonce%2Cassoc_handle&openid.sig=ktExb90y4dmImQDJOUdpSvrBbhg%3D&openid.identity=https%3A%2F%2Fprofiles.google.com%2Fpablobngs&openid.claimed_id=http%3A%2F%2Fpablo.bng5.net%2F"
                  ["required"]=>  array(0) {
                  }
                  ["optional"]=>  array(0) {
                  }
                  ["identity":"EOpenID":private]=>  string(37) "https://profiles.google.com/pablobngs"
                  ["claimed_id":"EOpenID":private]=>  string(22) "http://pablo.bng5.net/"
                  ["isAuthenticated":"EOpenID":private]=>  bool(true)
                  ["server":protected]=>  string(53) "https://www.google.com/accounts/o8/ud?source=profiles"
                  ["version":protected]=>  int(2)
                  ["trustRoot":protected]=>  string(20) "http://ddex.bng5.net"
                  ["aliases":protected]=>  NULL
                  ["identifier_select":protected]=>  bool(false)
                  ["ax":protected]=>  bool(false)
                  ["sreg":protected]=>  bool(false)
                  ["data":protected]=>  array(12) {
                    ["openid_ns"]=>    string(32) "http://specs.openid.net/auth/2.0"
                    ["openid_mode"]=>    string(6) "id_res"
                    ["openid_op_endpoint"]=>    string(53) "https://www.google.com/accounts/o8/ud?source=profiles"
                    ["openid_response_nonce"]=>    string(34) "2014-01-13T00:15:14ZmmVNPE_bCbILYg"
                    ["openid_return_to"]=>    string(78) "http://ddex.bng5.net/bliki/openid/c/dd44f0c92a09ae783c5e77b1f00030a3/i/estilos"
                    ["openid_assoc_handle"]=>    string(66) "1.AMlYA9XluJPQydBvlvENU9lOeEJmYFD6CI44sqNr1rmwtJ0qu0GA9WvbkxrEyrA2"
                    ["openid_signed"]=>    string(69) "op_endpoint,claimed_id,identity,return_to,response_nonce,assoc_handle"
                    ["openid_sig"]=>    string(28) "ktExb90y4dmImQDJOUdpSvrBbhg="
                    ["openid_identity"]=>    string(37) "https://profiles.google.com/pablobngs"
                    ["openid_claimed_id"]=>    string(22) "http://pablo.bng5.net/"
                    ["c"]=>    string(32) "dd44f0c92a09ae783c5e77b1f00030a3"
                    ["i"]=>    string(7) "estilos"
                  }
                  ["errorCode"]=>  int(100)
                  ["errorMessage"]=>  string(0) ""
                  ["_state":"CBaseUserIdentity":private]=>  array(0) {
                  }
                  ["_e":"CComponent":private]=>  NULL
                  ["_m":"CComponent":private]=>  NULL
                }
                 */

                $identity = $openid->getIdentity();
//                $author = $this->loadAuthor(Author::TIPO_OPENID, $identity);

                $comment->author = preg_replace('/(^https?:\/\/)|(\/$)/', '', $identity);
                $comment->author_avatar = '/img/avatar/openid';
                $comment->author_website = $identity;
                $comment->author_data = $openid->getData();
//                $data = $openid->getData();
//                $comment->author_data = serialize(array(
//                    'ns' => $data->openid_ns,
//                    'mode' => $data->openid_mode,
//                    'op_endpoint' => $data->openid_op_endpoint,
//                    'identity' => $data->openid_identity,
//                    'claimed_id' => $data->openid_claimed_id,
//                ));
//                $author->save();

                $comment->status = 1;
                //$comment->status = $author->whitelist;
//                $comment->author_id = $author->id;
                $comment->parseMessage();
                $comment->save();
                Yii::app()->cache->delete('come:'.$comment->post_id);
                $this->redirect("/bliki/{$comment->post->path}#comentario-{$c}");
            }
            else {
                
/*
$openid = object(EOpenID)#36 (19) {
  ["returnUrl"]=>  string(750) "http://ddex.bng5.net/bliki/openid/c/dd44f0c92a09ae783c5e77b1f00019a0/i/estilos?openid.ns=http%3A%2F%2Fspecs.openid.net%2Fauth%2F2.0&openid.mode=id_res&openid.op_endpoint=https%3A%2F%2Fwww.google.com%2Faccounts%2Fo8%2Fud%3Fsource%3Dprofiles&openid.response_nonce=2014-01-12T23%3A54%3A29Z3Qfakld1n16yPA&openid.return_to=http%3A%2F%2Fddex.bng5.net%2Fbliki%2Fopenid%2Fc%2Fdd44f0c92a09ae783c5e77b1f00019a0%2Fi%2Festilos&openid.assoc_handle=1.AMlYA9XoIl9B6hF34VlDpbY6MEt2Uqn66SQO7hioEebeBTIpsZrAu1kMUvMArxyt&openid.signed=op_endpoint%2Cclaimed_id%2Cidentity%2Creturn_to%2Cresponse_nonce%2Cassoc_handle&openid.sig=yr%2Frp3Bn1xoMIKN7hkBhKw9vLF4%3D&openid.identity=https%3A%2F%2Fprofiles.google.com%2Fpablobngs&openid.claimed_id=http%3A%2F%2Fpablo.bng5.net%2F"
  ["required"]=>  array(0) {
  }
  ["optional"]=>  array(0) {
  }
  ["identity":"EOpenID":private]=>  string(37) "https://profiles.google.com/pablobngs"
  ["claimed_id":"EOpenID":private]=>  string(22) "http://pablo.bng5.net/"
  ["isAuthenticated":"EOpenID":private]=>  bool(false)
  ["server":protected]=>  string(53) "https://www.google.com/accounts/o8/ud?source=profiles"
  ["version":protected]=>  int(2)
  ["trustRoot":protected]=>  string(20) "http://ddex.bng5.net"
  ["aliases":protected]=>  NULL
  ["identifier_select":protected]=>  bool(false)
  ["ax":protected]=>  bool(false)
  ["sreg":protected]=>  bool(false)
  ["data":protected]=>  array(12) {
    ["openid_ns"]=>    string(32) "http://specs.openid.net/auth/2.0"
    ["openid_mode"]=>    string(6) "id_res"
    ["openid_op_endpoint"]=>    string(53) "https://www.google.com/accounts/o8/ud?source=profiles"
    ["openid_response_nonce"]=>    string(34) "2014-01-12T23:54:29Z3Qfakld1n16yPA"
    ["openid_return_to"]=>    string(78) "http://ddex.bng5.net/bliki/openid/c/dd44f0c92a09ae783c5e77b1f00019a0/i/estilos"
    ["openid_assoc_handle"]=>    string(66) "1.AMlYA9XoIl9B6hF34VlDpbY6MEt2Uqn66SQO7hioEebeBTIpsZrAu1kMUvMArxyt"
    ["openid_signed"]=>    string(69) "op_endpoint,claimed_id,identity,return_to,response_nonce,assoc_handle"
    ["openid_sig"]=>    string(28) "yr/rp3Bn1xoMIKN7hkBhKw9vLF4="
    ["openid_identity"]=>    string(37) "https://profiles.google.com/pablobngs"
    ["openid_claimed_id"]=>    string(22) "http://pablo.bng5.net/"
    ["c"]=>    string(32) "dd44f0c92a09ae783c5e77b1f00019a0"
    ["i"]=>    string(7) "estilos"
  }
  ["errorCode"]=>  int(100)
  ["errorMessage"]=>  string(0) ""
  ["_state":"CBaseUserIdentity":private]=>  array(0) {
  }
  ["_e":"CComponent":private]=>  NULL
  ["_m":"CComponent":private]=>  NULL
}

$data = object(stdClass)#41 (12) {
  ["openid_ns"]=>  string(32) "http://specs.openid.net/auth/2.0"
  ["openid_mode"]=>  string(6) "id_res"
  ["openid_op_endpoint"]=>  string(53) "https://www.google.com/accounts/o8/ud?source=profiles"
  ["openid_response_nonce"]=>  string(34) "2014-01-12T23:54:29Z3Qfakld1n16yPA"
  ["openid_return_to"]=>  string(78) "http://ddex.bng5.net/bliki/openid/c/dd44f0c92a09ae783c5e77b1f00019a0/i/estilos"
  ["openid_assoc_handle"]=>  string(66) "1.AMlYA9XoIl9B6hF34VlDpbY6MEt2Uqn66SQO7hioEebeBTIpsZrAu1kMUvMArxyt"
  ["openid_signed"]=>  string(69) "op_endpoint,claimed_id,identity,return_to,response_nonce,assoc_handle"
  ["openid_sig"]=>  string(28) "yr/rp3Bn1xoMIKN7hkBhKw9vLF4="
  ["openid_identity"]=>  string(37) "https://profiles.google.com/pablobngs"
  ["openid_claimed_id"]=>  string(22) "http://pablo.bng5.net/"
  ["c"]=>  string(32) "dd44f0c92a09ae783c5e77b1f00019a0"
  ["i"]=>  string(7) "estilos"
}
 */
$data = $openid->getData();
var_dump($openid, $data);
exit;
                $comment->addError('openid', 'No fue posible validar tu cuenta OpenID.');
                $this->render('comentar', array(
                    'post_id' => $i,
                    'comentario' => $comment,
                ));
//                throw new CHttpException(404, 'No fue posible autenticar la cuenta OpenID.');
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
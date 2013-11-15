<?php

class OauthController extends Controller {
    
    public function actionIndex() {
 
        Yii::import('ext.eoauth.*');
        
        var_dump(Yii::app()->user);
    }
    
    public function actionComentario($prov) {
 
        Yii::import('ext.eoauth.*');
//var_dump($prov);
//exit;
        $providers = array(
            'twitter' => array(
                'key' => 'PocyRN5tfzTOJDYyBUPXsA',
                'secret' => 'sjlBXQfJLoqWoWN1gzqTHfk7c5sqBcDH8ugIWzd3h6s',
                'provider'=>array(
                    'request'=>'https://api.twitter.com/oauth/request_token',
                    'authorize'=>'https://api.twitter.com/oauth/authorize',
                    'access'=>'https://api.twitter.com/oauth/access_token',
                )
            ),
            'linkedin' => array(
                'key' => 'b446jl32ux9g',
                'secret' => 'Bk3Th4lLSIEWmMXG',
                'provider'=>array(
                    'request' => 'https://api.linkedin.com/uas/oauth/requestToken',
                    'authorize' => 'https://api.linkedin.com/uas/oauth/authenticate',
                    'access' => 'https://api.linkedin.com/uas/oauth/accessToken',
                )
            ),
            'github' => array(
                'key' => '7d5b0f8ac57b793162ba',
                'secret' => '3125515f0e3a2a5202c47cd44be83ac3947c69e6',
                'provider'=>array(
                    'request'=>'https://github.com/login/oauth/request_token',
                    'authorize'=>'https://github.com/login/oauth/authorize',
                    'access'=>'https://github.com/login/oauth/access_token',
                )
            ),
        );
        $ui = new EOAuthUserIdentity($providers[$prov]);
 
        if($ui->authenticate()) {
            $user=Yii::app()->user;
//            $user->login($ui);

$profiles = array(
    'linkedin' => array(
        'url' => 'http://api.linkedin.com/v1/people/~:(firstName,lastName,siteStandardProfileRequest,picture-url)',
        'params' => array('format' => 'json'),
    ),
    'twitter' => array(
        'url' => 'https://api.twitter.com/1/account/verify_credentials.json',
        'params' => array(),
    ),
);
$url = $profiles[$prov]['url'];
$params = count($profiles[$prov]['params']) ? $profiles[$prov]['params'] : null;
$signatureMethod = new OAuthSignatureMethod_HMAC_SHA1();
$request = OAuthRequest::from_consumer_and_token($ui->provider->consumer, $ui->provider->token, 'GET', $url, $params);
//$request = new OAuthRequest('GET', $url);
$request->sign_request($signatureMethod, $ui->provider->consumer, $ui->provider->token);
echo '<hr />';
var_dump($request->to_header(),
    $request->to_url()
);
//$url = $request->to_url();

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

            $author = new Author();
switch($prov) {
    case 'twitter':
        echo "
        id {$response_obj->id}
        screen_name {$response_obj->screen_name}
        profile_image_url_https <img src=\"{$response_obj->profile_image_url_https}\" alt=\"\" />
        profile_image_url <img src=\"{$response_obj->profile_image_url}\" alt=\"\" />
        profile_image_url bigger <img src=\"".str_replace('_normal.', '_bigger.', $response_obj->profile_image_url)."\" alt=\"\" />
        profile_image_url mini <img src=\"".str_replace('_normal.', '_mini.', $response_obj->profile_image_url)."\" alt=\"\" />
        url {$response_obj->url}
        name {$response_obj->name}
        ";
        break;
    case 'linkedin':
        $tipo = 1;
        $nombre = "{$response_obj->firstName} {$response_obj->lastName}";
        $link = $response_obj->siteStandardProfileRequest->url;
        $avatar = $response_obj->pictureUrl;
        break;
}

//echo htmlspecialchars($response);
echo "</pre>";
echo '<hr />';
//var_dump(
//        $request->to_header(),
//        $request->to_url(),
//        $user, $ui);
//var_dump(
//    $response,
//    $headers);
//echo '<hr />';


exit;
//            $this->redirect($user->returnUrl);
            $this->redirect('http://localhost.bng5.net/oauth/info/prov/twitter');
        }
        else
            throw new CHttpException(401, $ui->error);
    }

    
    
//    public function actionLogout() {
// 
//        Yii::app()->user->logout();
// 
//        // Redirect to application home page.
//        $this->redirect(Yii::app()->homeUrl);
//    }
    

    public function actionOpenid() {
        echo '
            <div class="form">
                <form method="post" action="/oauth/openidlogin">
                    <div class="row">
                        <input name="openid_identifier" value="http://bng5.myopenid.com/" />
                    </div>
                    <div class="row buttons">
                        <input type="submit" />
                    </div>
                </form>
            </div>';
    }
    
    public function actionOpenidlogin() {
        Yii::import('ext.eopenid.*');
        $openid = new EOpenID;
        if(!isset($_GET['openid_mode'])) {
            Yii::log("Redireccionar a {$_POST['openid_identifier']}");
            if(isset($_POST['openid_identifier'])) {
                $openid->authenticate($_POST['openid_identifier']);
            }
        }
        else {
            Yii::log("Validar");
var_dump(
            $openid->validate()
);
//            Yii::app()->user->login($openid);
        }
 
        //$this->render('openIDLogin',array('openid'=>$openid));
    }
    
    public function actionPersona() {
        Yii::app()->clientScript->registerScriptFile('https://login.persona.org/include.js', CClientScript::POS_HEAD);
        $this->render('//site/persona', array());
    }
}

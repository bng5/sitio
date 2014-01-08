<?php

class XmlrpcController extends Controller {

    /*
If an error condition occurs, then the appropriate fault code from the following list should be used. Clients can quickly determine the kind of error from bits 5-8. 0×001x fault codes are used for problems with the source URI, 0×002x codes are for problems with the target URI, and 0×003x codes are used when the URIs are fine but the pingback cannot be acknowledged for some other reaon.

0
    A generic fault code. Servers MAY use this error code instead of any of the others if they do not have a way of determining the correct fault code.
0x0010 (16)
    The source URI does not exist.
0x0011 (17)
    The source URI does not contain a link to the target URI, and so cannot be used as a source.
0x0020 (32)
    The specified target URI does not exist. This MUST only be used when the target definitely does not exist, rather than when the target may exist but is not recognised. See the next error.
0x0021 (33)
    The specified target URI cannot be used as a target. It either doesn't exist, or it is not a pingback-enabled resource. For example, on a blog, typically only permalinks are pingback-enabled, and trying to pingback the home page, or a set of posts, will fail with this error.
0x0030 (48)
    The pingback has already been registered.
0x0031 (49)
    Access denied.
0x0032 (50)
    The server could not communicate with an upstream server, or received an error from an upstream server, and therefore could not complete the request. This is similar to HTTP's 402 Bad Gateway error. This error SHOULD be used by pingback proxies when propagating errors.
     */
    public function actionIndex() {
        if($_SERVER['REQUEST_METHOD'] != 'POST') {
            throw new CHttpException(405, 'Método HTTP no aceptado.');
        }
        $server = xmlrpc_server_create();
        xmlrpc_server_register_method($server, 'pingback.ping', array($this, 'pingback_ping'));
        try {
            $response = xmlrpc_server_call_method($server, file_get_contents('php://input'), null);
        } catch (XmlrpcException $exc) {
            $response = xmlrpc_encode_request(NULL, array(
                'faultCode' => $exc->getCode(),
                'faultString' => $exc->getMessage(),
            ));
        } catch (Exception $exc) {
            $response = xmlrpc_encode_request(NULL, array(
                'faultCode' => 0,
                'faultString' => $exc->getMessage(),
            ));
        }
        header("Content-Type: application/xml");//; charset=UTF-8
        echo $response;
	}
    
    protected function pingback_ping($methodName, $args) {
        list($sourceURI, $targetURI) = $args;
//        throw new Exception('The specified target URI does not exist.', 0x0020);
        $source_components = parse_url($sourceURI);
        $target_components = parse_url($targetURI);
//var_dump($sourceURI, $source_components);


        if($target_components['scheme'] == 'http' && preg_match('/bng5\.net$/', $target_components['host']) && preg_match('/\/bliki\/([-_\w]+)/', $target_components['path'], $matches)) {
            if($post = Post::model()->get($matches[1])) {
                
                $client = new Http_Client('Bng5.net Pingback server');
                $response = $client->get($sourceURI);
                if(!$response) {
                    throw new XmlrpcException('The server could not communicate with an upstream server.', 0x0032);
                }
                if($response->status == 404) {
                    throw new XmlrpcException('The source URI does not exist.', 0x0010);
                }
                
                // TODO encontrar el link
                if(!preg_match('/<title>(.+)<\/title>/', $response->body)) {
                    $pingback->title = $matches[1];
                }

                $pingback = Pingback::model()->findOne('pingback/_view/by_post_admin', array(
                    'key' => '["'.$post->_id.'","'.$sourceURI.'"]',
                ));
                $already_registered = true;
                if(!$pingback) {
                    $pingback = new Pingback;
                    $already_registered = false;
                }
                //$pingback->_id = $sourceURI;
                $pingback->sourceURI = $sourceURI;
                $pingback->post_id = $post->_id;
                $pingback->remote_addr = $_SERVER['REMOTE_ADDR'];
                if(preg_match('/<title>(.+)<\/title>/', $response->body, $matches)) {
                    $pingback->title = $matches[1];
                }
                $pingback->save();
                if($already_registered) {
                    throw new XmlrpcException('The pingback has already been registered.', 0x0030);
                }
            }
            else {
                throw new XmlrpcException('The specified target URI does not exist.', 0x0020);
            }
        }
        else {
            throw new XmlrpcException('The specified target URI cannot be used as a target.', 0x0021);
        }
exit;
var_dump($sourceURI, $targetURI);
        return 'OK';
    }
    
}
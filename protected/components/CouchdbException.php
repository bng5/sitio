<?php

/**
 * Description of Couchdb
 *
 * @author pablo
 */
class CouchdbException extends CHttpException {
    
    protected $response;
    
    public function __construct($response, $method, $path) {
        $body = json_decode($response->body);
        $message = "{$method} {$path}, status: {$response->status}"; 
        $message .= $body ? 
            ", error: {$body->error}, reason: {$body->reason}" :
            '';
        parent::__construct(500, $message, $response->status);
        $this->response = $response;
    }
    
    public function getResponse() {
        return $this->response;
    }
}

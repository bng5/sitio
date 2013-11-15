<?php

/**
 * Description of Couchdb
 *
 * @author pablo
 */
class Couchdb {
    
    public $host;
    public $port;
    
    public $database;
    
    private $client;
    
    public function init() {
        $this->client = new Http_Client;
    }
    
    private function _exec($url, $data = null, $method = Http_Client::METHOD_GET, $requestHeaders = array()) {
        $resp = $this->client->request($url, $data, $method, $requestHeaders);
        if(!$resp) {
            throw new CHttpException(500, "No fue posible conectar con la base de datos.", 1);
        }
        return $resp;
    }
    
    public function get($database, $id) {
        $resp = $this->_exec("http://{$this->host}:{$this->port}/{$database}/{$id}");
        if($resp->status == 200) {
            return json_decode($resp->__toString());
        }
        return null;
    }
    
    public function view($database, $view) {
        $resp = $this->_exec("http://{$this->host}:{$this->port}/{$database}/_design/list/_view/{$view}");
        if($resp->status == 200) {
            return json_decode($resp->__toString());
        }
        return null;
    }
    
    public function find($id) {
        
    }
    
    public function save($id) {
        
    }

    public function delete($id) {
        
    }
}

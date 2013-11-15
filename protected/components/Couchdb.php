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
    
    public function get($database, $id) {
        $resp = $this->client->get("http://{$this->host}:{$this->port}/{$database}/{$id}");
        if($resp->status == 200) {
            return json_decode($resp->__toString());
        }
        return null;
    }
    
    public function view($database, $view) {
        $resp = $this->client->get("http://{$this->host}:{$this->port}/{$database}/_design/list/_view/{$view}");
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

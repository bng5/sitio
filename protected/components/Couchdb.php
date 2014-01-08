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
        $this->client = new Http_Client(null, array('accept' => 'Accept: application/json',));
    }
    
    private function _exec($url, $data = null, $method = Http_Client::METHOD_GET) {
        $requestHeaders = array();
        if($method == Http_Client::METHOD_PUT || $method == Http_Client::METHOD_POST) {
            $requestHeaders[] = 'Content-Type: application/json';
        }
        try {
            $resp = $this->client->request($url, $data, $method, $requestHeaders);
        } catch (Http_ClientException $exc) {
            //print_r($exc->getInfo());
            throw new CHttpException(500, "No fue posible conectar con la base de datos.", 1);
        }
        if($resp->status >= 400) {
            $parsed_url = parse_url($url);
            $url = $parsed_url['path'].(array_key_exists('query', $parsed_url) ? '?'.$parsed_url['query'] : '');
            throw new CouchdbException($resp, $method, $url);
        }
        return $resp;
    }
    
    public function getUuid() {
        $resp = $this->_exec("http://{$this->host}:{$this->port}/_uuids");
        if($resp->status == 200) {
            $json = json_decode($resp->__toString());
            return $json->uuids[0];
        }
        return null;
    }
    
    public function get($database, $id) {
        try {
            $resp = $this->_exec("http://{$this->host}:{$this->port}/{$database}/{$id}");
        } catch(CouchdbException $exc) {
            if($exc->getResponse()->status == 404) {
                return null;
            }
            throw $exc;
        }
        if($resp->status == 200) {
            return json_decode($resp->__toString());
        }
        return null;
    }
    
    public function view($database, $view, $options = array()) {
        $url = "http://{$this->host}:{$this->port}/{$database}/_design/{$view}";
        if($options && count($options)) {
            $url .= '?'.http_build_query($options, '', '&');
        }
        $resp = $this->_exec($url);
        if($resp->status == 200) {
            return json_decode($resp->__toString());
        }
        return null;
    }
    
    public function save($model) {
        $database = $model->database();
        $method = Http_Client::METHOD_PUT;
        $model_id = $model->_id ? 
            '/'.urlencode($model->_id) :
            '/';
        $attributes = $model->attributes;
        if(!$model->_rev) {
            unset($attributes['_rev']);
        }
        if(!$model->_id) {
            $method = Http_Client::METHOD_POST;
            //$model->_id = $this->getUuid();
            unset($attributes['_id']);
        }
        
        try {
            $resp = $this->_exec("http://{$this->host}:{$this->port}/{$database}{$model_id}", json_encode($attributes), $method);
        } catch (CouchdbException $exc) {
            $resp = $exc->getResponse();
//var_dump($resp);
//exit;
            if($resp && $resp->status == 409) {
                $modelClass = get_class($model);
                $newModel = $modelClass::model()->get($model->_id);
                throw new OptimisticLockException($model, $newModel);
            }
            throw $exc;
        }
        
        // $resp->status status 201
        //  {"ok":true,"id":"9cfc075e411b9c152aaa42d0e8001681","rev":"1-d82fb6af1f9cd287d6895062ff750ae7"}
        if($obj = json_decode($resp->body)) {
            $model->_id = $obj->id;
            $model->_rev = $obj->rev;
            return true;
        }
        return false;
    }

    public function delete($model) {
        if($model->database() && $model->_id && $model->_rev) {
            $resp = $this->_exec("http://{$this->host}:{$this->port}/{$model->database()}/".urlencode($model->_id)."?rev={$model->_rev}", null, Http_Client::METHOD_DELETE);
            if($resp->status == 409) {
                $modelClass = get_class($model);
                $newModel = $modelClass::model()->get($model->_id);
                throw new OptimisticLockException($model, $newModel);                
            }
            return true;
        }
        return false;
    }
}

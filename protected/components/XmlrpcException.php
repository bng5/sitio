<?php

/**
 * Description of Couchdb
 *
 * @author pablo
 */
class XmlrpcException extends Exception {
    
    public function __construct($message, $code) {
        parent::__construct($message, $code);
    }
}

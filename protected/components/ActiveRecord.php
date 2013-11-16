<?php

/**
 * Description of ActiveRecord
 *
 * @author pablo
 */
abstract class ActiveRecord extends CModel {
    
    protected $_values = array();


    public static function model($classname = null) {
        if(!$classname) {
            $classname = get_called_class();
        }
        return new $classname;
    }
 
    public function __get($property) {
        return property_exists($this->_values, $property) ? 
            $this->_values->{$property}
            : null;
    }
    
    public function save() {
        
    }

    public function get($id) {

        $obj = Yii::app()->couchdb->get($this->database(), $id);
        if(!$obj) {
            return null;
        }
        $this->_values = $obj;
        return $this;
    }
    
    public function delete() {
        
    }

    public function find($view) {
        return Yii::app()->couchdb->view($this->database(), $view);
    }
    
    public function attributeNames() {
        
    }
}

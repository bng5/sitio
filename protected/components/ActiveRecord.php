<?php

/**
 * Description of ActiveRecord
 *
 * @author pablo
 */
abstract class ActiveRecord extends CModel {
    
    public $_id;
    public $_rev;


    public static function model($classname = null) {
        if(!$classname) {
            $classname = get_called_class();
        }
        return new $classname;
    }
 
//    public function __get($property) {
//        return property_exists($this->_values, $property) ? 
//            $this->_values->{$property}
//            : null;
//    }
    
//    public function __isset($property) {
//        return property_exists($this, $property);
//    }
    
    public function getUuid() {
        $this->_id = Yii::app()->couchdb->getUuid();
        return $this;
    }
    
    public function save($runValidation = true) {
		if(!$runValidation || $this->validate()) {
            return Yii::app()->couchdb->save($this);
        }
    }

    public function get($id) {
        $obj = Yii::app()->couchdb->get($this->database(), $id);
        if(!$obj) {
            return null;
        }
        $this->setAttributes($obj, false);
        return $this;
    }
    
    public function setAttributes($values, $safeOnly = true) {
        parent::setAttributes((array) $values, $safeOnly);
        return $this;
    }
    
    public function delete() {
        Yii::app()->couchdb->delete($this);
    }

    public function group($view) {
        $list = Yii::app()->couchdb->view($this->database(), $view, array('group' => 'true'));
        return $list->rows;
    }
    
    public function find($view, $options = array()) {
        
        if(strpos($view, '/') === false) {
            $view = "list/_view/{$view}";
        }
        $list = Yii::app()->couchdb->view($this->database(), $view, $options);
//        if(property_exists($list, 'total_rows')) {
            return new ModelIterator(get_called_class(), $list);
//        } 
//        return $list->rows;
    }

    public function findOne($view, $options = array()) {
        $list = $this->find($view, $options);
        return $list->valid() ? 
            $list->current() :
            null;
    }
    
//    public function getIterator() {
//        $obj = new ArrayObject($this->_values);
//        return $obj->getIterator();
//    }
    
    public function attributeNames() {
        return array_keys(get_class_vars(get_called_class()));
    }
}

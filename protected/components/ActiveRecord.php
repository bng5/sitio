<?php

/**
 * Description of ActiveRecord
 *
 * @author pablo
 */
abstract class ActiveRecord extends CModel {
    
    public static function model($classname = null) {
        if(!$classname) {
            $classname = get_called_class();
        }
        return new $classname;
    }
    
    public function save() {
        
    }

    public function get($id) {
        return Yii::app()->couchdb->get($this->database(), $id);
    }
    
    public function delete() {
        
    }

    public function find($view) {
        return Yii::app()->couchdb->view($this->database(), $view);
    }
    
    public function attributeNames() {
        
    }
}

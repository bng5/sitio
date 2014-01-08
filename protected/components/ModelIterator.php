<?php

/**
 * Description of ModelIterator
 *
 * @author pablo
 */
class ModelIterator implements Iterator {
    private $position;
    private $className;
    private $total_rows;
    private $offset;
    private $rows;
      
    public function __construct($className, $data) {
        $this->position = 0;
        $this->className = $className;
        $this->total_rows = property_exists($data, 'total_rows') ? $data->total_rows : null;
        $this->offset = property_exists($data, 'offset') ? $data->offset : null;
        $this->rows = $data->rows;
    }

    public function __get($attr) {
        return $this->{$attr};
    }
    
    public function current() {
        $model = call_user_func(array($this->className, 'model'));
        $item = $this->rows[$this->position];
        $model->setAttributes($item->value, false);
        if(property_exists($item, 'id')) {
            $model->_id = $item->id;
        }
        return $model;
    }

    public function key() {
        return $this->position;
    }

    public function next() {
        ++$this->position;
    }

    public function rewind() {
        $this->position = 0;
    }

    public function valid() {
        return isset($this->rows[$this->position]);
    }

}

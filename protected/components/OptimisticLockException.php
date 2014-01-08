<?php

/**
 * Description of OptimisticLockException
 *
 * @author pablo
 */
class OptimisticLockException extends Exception {
    
    public $oldDoc;
    public $newDoc;
    
    public function __construct($oldModel, $newModel) {
        parent::__construct("", 0);
        $this->oldDoc = $oldModel;
        $this->newDoc = $newModel;
        
//        var_dump(array_diff((array) $this->oldDoc->attributes, (array) $this->newDoc->attributes));
//exit;
    }
}

<?php

/**
 *
 * @property string $_id
 * @property string $path
 * @property string $title
 * @property integer $estado
 * @property integer $fecha_creado
 * @property integer $fecha_modificado
 * @property integer $rev
 * @property integer $comentarios_habilitados
 * @property string $resumen
 */
class Post extends ActiveRecord { // CActiveRecord {
    
    public $type = 'post';
    
    public $path;
    public $public;
    public $notoc;
    public $nocomments;
    public $title;
    public $summary;
    public $created_at;
    public $updated_at;
    public $content;
    public $tags = array();
    

    /**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Post the static model class
	 */
//	public static function model($className = __CLASS__) {
//		return parent::model($className);
//	}

	/**
	 * @return string the associated database table name
	 */
	public function database() {
		return 'bng5_bliki';
	}
    
//    public function setAttributes($values, $safeOnly = true) {
//        $this->_post = $values['post'];
//        parent::setAttributes($values, $safeOnly);
//    }

    /**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('_id, title, path', 'required'),
			array('created_at, updated_at', 'numerical', 'integerOnly'=>true),
			array('notoc, nocomments, public', 'boolean'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('_rev, content, tags, summary, notoc, nocomments', 'safe'),
			array('id, path, titulo, fecha_creado, fecha_modificado', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'path' => 'Path',
			'title' => 'Título',
            'summary' => 'Resumen',
			'fecha_creado' => 'Fecha Creado',
			'fecha_modificado' => 'Fecha Modificado',
		);
	}

//    public function attributeNames() {
//		return array(
//			'_id',
//			'_rev',
//			'title',
//            'path',
//            'summary',
//			'created_at',
//			'updated_at',
//			'content',
//            'tags',
//		);
//    }
    
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search() {
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id, true);
		$criteria->compare('path', $this->path, true);
		$criteria->compare('titulo', $this->titulo, true);
		$criteria->compare('fecha_creado', $this->fecha_creado);
		$criteria->compare('fecha_modificado', $this->fecha_modificado);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public function setPost(PostContent $model) {
        $this->_post = $model;
        $model->setDbModel($this);
        return $this;
    }
    
    /**
     * 
     * @return PostContent
     */
    public function getPost() {
        if($this->_post === null) {
            $this->loadSource();
        }
        return $this->_post;
    }
    
    public function setToc($toc) {
        $this->_toc = $toc;
        return $this;
    }
    
    public function getTexto() {
        return 'hola';
    }
    
    /**
     * 
     * @return bool
     */
    public function getToc() {
        if($this->_toc === null) {
            $this->_toc = $this->loadSource();
        }
        return $this->_post;
    }
    
    /**
     * con simpleXML
     * 
     * @return string
     */
//    public function getPost() {
//        if($this->_post === null) {
//            $xml = new SimpleXMLElement("data/post/{$this->id}.php", 0, true);//, 'http://bng5.net/ns/post');//, 'http://www.w3.org/1999/xhtml');//
////            $xmlns = $xml->getDocNamespaces(true);
//            $xml->registerXPathNamespace('p', 'http://bng5.net/ns/post');
//            
//            
//            if($tags = $xml->xpath('//p:tags/p:tag')) {
//                $t = array();
//                foreach($tags AS $v) {
//                    $tag = new Tag();
//                    $tag->tag = $v->__toString();
//                    $t[] = $tag;
//                }
//                $this->tags= $t;
//            }
//
//var_dump(
//            $xml->xpath('//p:source')
//);
//            
//            $this->_post = $xml->body;
////            ob_start();
////            @include("data/post/{$this->id}.php");
////            $this->_post = ob_get_clean();
//        }
//        return $this->_post;
//    }
    
    public function save($runValidation = true, $attributes = null) {
        parent::save($runValidation, $attributes);
    }
    
}

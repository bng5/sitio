<?php

/**
 * This is the model class for table "post".
 *
 * The followings are the available columns in table 'post':
 * @property string $id
 * @property string $path
 * @property string $titulo
 * @property integer $estado
 * @property integer $fecha_creado
 * @property integer $fecha_modificado
 * @property integer $rev
 * @property integer $comentarios_habilitados
 * @property string $resumen
 * @property PostContent $post
 */
class Post extends ActiveRecord { // CActiveRecord {
    
    private $_post;
    private $_toc;
    
    public $rev = 1;

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
		return 'bng5_blikiposts';
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
			array('path, titulo', 'required'),
			array('estado, fecha_creado, fecha_modificado', 'numerical', 'integerOnly'=>true),
			array('path', 'length', 'max'=>50),
			array('titulo', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('post, resumen', 'safe'),
			array('id, path, titulo, fecha_creado, fecha_modificado', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		return array(
            'comments' => array(self::HAS_MANY, 'Comment', 'post_id', 'order' => 'fecha_creado',),
            'changelog' => array(self::HAS_MANY, 'PostChangelog', 'post_id'),
            'webContent' => array(self::HAS_ONE, 'PostContent', 'post_id'),// 'group' => 't.id', 'joinType' => 'RIGHT JOIN'),
            'tags' => array(self::MANY_MANY, 'Tag', 'post_tags(post_id, tag_id)',),// 'order'=>'name'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'path' => 'Path',
			'titulo' => 'Título',
			'fecha_creado' => 'Fecha Creado',
			'fecha_modificado' => 'Fecha Modificado',
		);
	}

    public function attributeNames() {
        
    }
    
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
    
    public function loadSource() {
        $source = PostContent::load($this->id, $this->rev);
        $source->setDbModel($this);
        $this->_post = $source;
        return $source;
    }
}

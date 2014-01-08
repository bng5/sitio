<?php

/**
 * 
 * @property int $id
 * @property string $url
 * @property int $type
 * @property string $title
 * @property string $link
 * @property string $description
 * @property int $lastBuildDate
 * @property int $estado
 * @property int $lastRequest
 * @property string $charset
 * @property int $HttpLastModified
 * @property string $HttpETag
 */
class Feed extends ActiveRecord {
    
    const TYPE_RSS = 1;
    const TYPE_ATOM = 2;
    
    const ESTADO_INACTIVO = 0;
    const ESTADO_ACTIVO = 1;
    const ESTADO_PRIVADO = 2;

    public $url;
    public $type = 'feed';
    public $feed_type;
    public $title;
    public $link;
    public $description;
    public $lastBuildDate;
    public $estado;
    public $lastRequest;
    public $charset;
    public $HttpLastModified;
    public $HttpETag;

    /**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return 'feed';
	}
    
    public function database() {
        return 'bng5_blogroll';
    }

    /**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		return array(
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		return array(
            'items' => array(self::HAS_MANY, 'FeedItem', 'feed_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search() {

//		$criteria=new CDbCriteria;
//
//		$criteria->compare('id',$this->id,true);
//		$criteria->compare('email',$this->email,true);
//
//		return new CActiveDataProvider($this, array(
//			'criteria'=>$criteria,
//		));
	}
    
    public function scopes() {
        return array(
            'active' => array(
                'condition'=>'estado = 1',
            ),
            'public' => array(
                'condition' => 'estado = 1',
                'order' => 'title ASC',
            ),
        );
    }
    
    public static function getFeedType($xml_str) {
        
        $xml = @simplexml_load_string($xml_str);
        /* Comentado porque no está lanzando excepciones */
        //try {
        //        $xml = new SimpleXMLElement($xml_str);
        //} catch (Exception $e) {
        //   echo '$xml_str is not a valid xml string';
        //}
        if(!$xml) {
            return false;
        }
        $root = $xml->getName();
        $namespace = $xml->getNamespaces();
        if($root == 'feed' && $namespace[''] == 'http://www.w3.org/2005/Atom') {
            $root = 'feedAtom';
        }
        $className = ucfirst($root);
        return new $className($xml);
    }
}

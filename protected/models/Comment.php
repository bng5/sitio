<?php

/**
 *
 * The followings are the available columns in table 'post':
 * @property integer $id
 * @property integer $status
 * @property integer $post_id
 * @property integer $reply_to
 * @property integer $author_id
 * @property string $nombre
 * @property string $website
 * @property integer $remote_addr
 * @property integer $fecha_creado
 * @property string $texto
 * @property string $instrucciones
 * @property string $html
 */
class Comment extends CActiveRecord {
    
    /**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Post the static model class
	 */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return 'comment';
	}

    /**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('texto', 'required', 'message' => 'El comentario no debe estar vacío.',),
//			array('nombre, post_id, author_id, texto', 'required'),
			//array('author_id', 'required', 'message' => 'No es posible validar la casilla de correos, intentá con una real.',),
			array('fecha_creado', 'numerical', 'integerOnly'=>true),
			//array('titulo', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
//			array('post', 'safe'),
//			array('id, titulo, fecha_creado, fecha_modificado', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		return array(
            'author' => array(self::BELONGS_TO, 'Author', 'author_id'),// 'group' => 't.id', 'joinType' => 'RIGHT JOIN'),
            'post' => array(self::BELONGS_TO, 'Post', 'post_id'),// 'group' => 't.id', 'joinType' => 'RIGHT JOIN'),
            //self::HAS_ONE
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

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search() {
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('path',$this->path,true);
		$criteria->compare('titulo',$this->titulo,true);
		$criteria->compare('fecha_creado',$this->fecha_creado);
		$criteria->compare('fecha_modificado',$this->fecha_modificado);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public function parseMessage() {
        
        $this->instrucciones = $this->texto;
        $this->html = $this->texto;
    }
}
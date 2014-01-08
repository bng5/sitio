<?php

/**
 *
 * The followings are the available columns in table 'post':
 * @property string $_id
 * @property integer $status
 * @property string $post_id
 * @property string $remote_addr
 * @property integer $created_at
 * @property string $sourceURI
 */
class Pingback extends ActiveRecord {
    
    public $type = 'pingback';
    
    public $post_id;
    public $sourceURI;
    public $status = 0;
    public $created_at;
    public $remote_addr;
    
    public $title;
    
    /**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Post the static model class
	 */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function database() {
		return 'bng5_bliki';
	}

    public function save($runValidation = true) {
        if(!$this->created_at) {
            $this->created_at = time();
        }
        parent::save($runValidation);
    }
    
    /**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('post_id, sourceURI', 'required', 'message' => 'El comentario no puede estar vacío.',),
//			array('nombre, post_id, author_id, texto', 'required'),
			//array('author_id', 'required', 'message' => 'No es posible validar la casilla de correos, intentá con una real.',),
			array('created_at', 'numerical', 'integerOnly'=>true),
			//array('titulo', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
//			array('post', 'safe'),
//			array('id, titulo, fecha_creado, fecha_modificado', 'safe', 'on'=>'search'),
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

}
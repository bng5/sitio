<?php

/**
 *
 * The followings are the available columns in table 'post':
 * @property integer $id
 * @property integer $status
 * @property integer $post_id
 * @property integer $reply_to
 * @property integer $author_id
 * @property string $website
 * @property integer $remote_addr
 * @property integer $fecha_creado
 * @property string $texto
 * @property string $instrucciones
 * @property string $html
 * @property string $user_agent
 */
class Comment extends ActiveRecord {
    
    const TIPO_OPENID = 1;
    const TIPO_PERSONA = 2;
    const TIPO_TWITTER = 3;
    const TIPO_LINKEDIN = 4;
    
    const AVATAR_NORMAL = 1;
    const AVATAR_MINI = 1;
    
    public $type = 'comment';
    
    public $post_id;
    public $reply_to;
    public $remote_addr;
    public $status;
    public $created_at;
    public $author;
    public $author_avatar;
    public $author_email;
    public $author_website;
    public $author_data;
    public $message;
    public $html;
    public $user_agent;

    public $auth;
    
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

    /**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('message', 'required', 'message' => 'El comentario no puede estar vacío.',),
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

    public function parseMessage() {
        return;
        $this->instrucciones = $this->message;
        $this->html = $this->message;
    }
    
    public function getAvatar($tamanyo = self::AVATAR_NORMAL) {
        if($this->author_avatar) {
            return $this->author_avatar;
        }
        if(!$this->auth) {
            return null;
        }
        switch($this->auth->type) {
            case self::TIPO_OPENID:
                $avatar = '/img/avatar/openid'.($tamanyo == self::AVATAR_NORMAL ? '' : '_mini');
                break;
            case self::TIPO_PERSONA:
                $size = $tamanyo == self::AVATAR_NORMAL ? 
                    55 : 
                    40;
                $avatar = sprintf('http://www.gravatar.com/avatar/%s?s=%d&d=mm', $this->author_avatar, $size);
                break;
//            default:
//                $avatar = $this->author_avatar;
//                break;
        }
        return $avatar;
    }
    
    
}
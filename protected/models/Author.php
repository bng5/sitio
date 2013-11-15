<?php

/**
 * This is the model class for table "author".
 *
 * The followings are the available columns in table 'author':
 * @property string $id
 * @property integer $tipo
 * @property string $provider_id
 * @property string $nombre
 * @property string $avatar
 * @property integer $verificado
 * @property integer $whitelist
 * @property string $data
 */
class Author extends CActiveRecord {

    const TIPO_OPENID = 1;
    const TIPO_PERSONA = 2;
    const TIPO_TWITTER = 3;
    const TIPO_LINKEDIN = 4;
    
    const VERIFICACION_GRAVATAR = 1;
    
    const AVATAR_NORMAL = 1;
    const AVATAR_MINI = 1;

    /**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Author the static model class
	 */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return 'author';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
//			array('email', 'required'),
//			array('email', 'length', 'max'=>512),
//			array('email', 'email'),//, 'allowEmpty' => false),
//'checkMX' => true
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, email, verificado, whitelist', 'safe', 'on'=>'search'),
//$id
//$tipo
//$provider_id
//$nombre
//$avatar
//$verificado
//$whitelist
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'email' => 'E-mail',
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
		$criteria->compare('email',$this->email,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    
    public function hostValidation() {
        $host = substr(strrchr($this->email, '@'), 1 );

        $mailhost = new KnownMailhost();
        $mailhost->mailhost = $host;
        if(!$mailhost->search()->itemCount) {

            Yii::log("Host {$host} no encontrado");
            if(!checkdnsrr($host, 'MX') && !checkdnsrr($host, 'A')) {
                //$params['{attribute}'] = $object->getAttributeLabel($attribute);
                Yii::log("No se puede chequear DNS");
                $this->addError('email', 'No es posible verificar la casilla de correos.');
                //throw new Exception('No es posible verificar el host '.$host);
                return false;
            }
            $mailhost->save();
        }
        else {
            Yii::log("Host {$host} encontrado");
        }
        return true;
    }
    
    public function getAvatar($tamanyo = self::AVATAR_NORMAL) {
        
        switch($this->tipo) {
            case self::TIPO_OPENID:
                $avatar = '/img/avatar/openid'.($tamanyo == self::AVATAR_NORMAL ? '' : '_mini');
                break;
            case self::TIPO_PERSONA:
                $size = $tamanyo == self::AVATAR_NORMAL ? 
                    55 : 
                    40;
                $avatar = sprintf('http://www.gravatar.com/avatar/%s?s=%d&d=mm', $this->avatar, $size);
                break;
            default:
                $avatar = $this->avatar;
                break;
        }
        return $avatar;
    }
}
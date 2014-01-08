<?php

/**
 *
 * $user = new User;
 * $user->username = 'kamus';
 * $user->setPassword('kamus');
 * $user->email = 'leonardo.hernandez@globalnetmobile.com';
 * $user->save();
 *
 *
 * 
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $id
 * @property string $username
 * @property integer $created_at
 * @property string $password
 * @property string $email
 *
 *
 * The followings are the available model relations:
 * @property FeedbackMessage[] $feedbackMessages
 */
class User extends ActiveRecord {

    public $id;
    public $username;
    public $created_at;
    public $password;
    public $email;
//    public $permissions = array();

//    public function  __construct($scenario = 'insert') {
//        parent::__construct($scenario);
////        $this->created_at = time();
////var_dump($this->userPermissions);
//    }

//    public function loadPermissions() {
//        foreach($this->userPermissions AS $v) {
//            $this->permissions[$v->area_id][$v->item_id] = $v->level;//attributes;
//        }
//        return $this;
//    }
    
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return User the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'user';
    }
    
	public function database() {
		return 'bng5_users';
	}

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('username, email', 'required'),
            //array('id', 'numerical', 'integerOnly' => true),
            array('username', 'length', 'max' => 45),
            array('email', 'email'),//, 'length', 'max' => 255),
            array('id, username, created_at, password, email', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        return array(
            //'userPermissions' => array(self::HAS_MANY, 'UserPermissions', 'user_id'),
            //'userAllowedIps' => array(self::HAS_MANY, 'UserAllowedIps', 'user_id'),
            //'country' => array(self::BELONGS_TO, 'Country', 'country_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'username' => 'Usuario',
            'created_at' => 'Creado',
            'password' => 'Contraseña',
            'email' => 'E-mail',
        );
    }

    public function attributesInfo() {
        return array();
    }
    
    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('username', $this->username, true);
        $criteria->compare('created_at', $this->created_at, true);
        $criteria->compare('password', $this->password, true);
        $criteria->compare('email', $this->email, true);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
    
    public function cryptPass($clean_password, $salt = null) {
        if(!$salt) {
            $salt = '$1$'.self::genAuth(8).'$';
        }
        return crypt(hash_hmac('md5', $clean_password, Yii::app()->params['siteSalt']), $salt);
    }

    public function setPassword($password) {
        $this->password = $this->cryptPass($password);
    }

//    public function getPermission($area_id, $item_id = null) {
//        if(!array_key_exists($area_id, $this->permissions)) {
//            return null;
//        }
//        if($item_id) {
//            return $this->permissions[$area_id][$item_id] ? $this->permissions[$area_id][$item_id] : false;
//        }
//        else {
//            return $this->permissions[$area_id];
//        }
//    }

    public function save($runValidation=true, $attributes=null) {
        if($this->getIsNewRecord()) {
            $this->created_at = time();
        }
		return parent::save($runValidation, $attributes);
	}

//    public function columns() {
//        return array(
//            'id',
//            'username',
//            array(
//                'name' => 'email',
//                'type'=>'raw',
//                'value'=>'CHtml::mailto($data->email, $data->email)',
//            ),
//        );
//    }
    
    public static function genAuth($length = 16) {
        $clave_caract = ".0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ_abcdefghijklmnopqrstuvwxyz";
        $cod_aut = "";
        $max = (strlen($clave_caract)-1);
        for($i=0; $i < $length; $i++)
            $cod_aut .= $clave_caract[rand(0, $max)];
        return $cod_aut;
    }

    public function attributeNames() {
        return array(
            'id',
            'username',
            'created_at',
            'password',
            'email',
        );
    }

}

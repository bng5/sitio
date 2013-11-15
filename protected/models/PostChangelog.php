<?php

/**
 * This is the model class for table "post".
 *
 * The followings are the available columns in table 'post':
 * @property integer $post_id
 * @property integer $rev
 * @property integer $type
 * @property integer $time
 * @property string $desc
 */
class PostChangelog extends CActiveRecord {
    
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
		return 'post_changelog';
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
			array('post_id, rev, time, desc', 'required'),
			array('post_id, rev, type, time', 'numerical', 'integerOnly'=>true),
			array('post_id, rev, type, time, desc', 'safe'),
			array('post_id, rev, time, desc', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		return array(
            'post' => array(self::BELONGS_TO, 'Post', 'post_id'),
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
		$criteria = new CDbCriteria;

//		$criteria->compare('id', $this->id, true);
//		$criteria->compare('path', $this->path, true);
//		$criteria->compare('titulo', $this->titulo, true);
//		$criteria->compare('fecha_creado', $this->fecha_creado);
//		$criteria->compare('fecha_modificado', $this->fecha_modificado);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}

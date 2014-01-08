<?php

/**
 * This is the model class for table "tag".
 *
 * The followings are the available columns in table 'tag':
 * @property string $id
 * @property string $tag
 * @property string $titulo
 * @property string $descripcion
 */
class Tag extends ActiveRecord {
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Tag the static model class
	 */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
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
			array('tag', 'required'),
			array('id', 'length', 'max'=>10),
			array('tag', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, tag', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		return array(
            'posts' => array(self::MANY_MANY, 'Post', 'post_tags(tag_id, post_id)'),
            'posts_count' => array(self::STAT, 'Post', 'post_tags(tag_id, post_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'tag' => 'Tag',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search() {
		$criteria = new CDbCriteria;

		// $criteria->compare('id',$this->id,true);
		$criteria->compare('tag', $this->tag, false);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public function attributeNames() {
        return array(
            
        );
    }

}
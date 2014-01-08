<?php

/**
 * This is the model class for the table
 *
 * @property int $id
 * @property string $acronym
 * @property string $desc
 */
class Acronym extends ActiveRecord {
    
    public $desc;

	public function database() {
		return 'bng5_acronyms';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('_id, desc', 'required'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('_id, desc', 'safe'),
			array('_id, desc', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'acronym' => 'Acrónimo',
            'desc' => 'Descripción',
		);
	}
    
    public function attributeNames() {
        return array(
            '_id',
            '_rev',
            'desc',
        );
    }

    /**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search() {
		$criteria = new CDbCriteria;

		// $criteria->compare('id',$this->id,true);
		$criteria->compare('acronym', $this->acronym, false);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
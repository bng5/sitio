<?php

/**
 * 
 * @property int $id
 * @property int $feed_id
 * @property string $title
 * @property string $link
 * @property string $description
 * @property int $pubDate
 * @property string $guid
 * @property int $guid_isPermaLink
 * @property string $content_encoded
 */
class FeedItem extends CActiveRecord {

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
		return 'feed_item';
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
            'feed' => array(self::BELONGS_TO, 'Feed', 'feed_id'),
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
//            'newest' => array(
//                'with' => array('feed'),
//                'order' => 'pubDate DESC',
//                'limit' => 20,
//            ),
            'oldest' => array(
                'order' => 'pubDate ASC',
            ),
//            'pending' => array(
//                'condition' => 't.user_id = :user_id AND t.status = 0',
//                'params' => array(':user_id' => Yii::app()->user->id),
//            ),
        );
    }
            
    public function newest($feed = null) {
        $criteria = array(
            'with' => array('feed'),
            'order' => 'pubDate DESC',
            'limit' => 20,
        );

        if($feed) {
            $criteria['condition'] = 't.feed_id = :feed';
            $criteria['params'] = array(':feed' => $feed->id);
        }
        $this->getDbCriteria()->mergeWith($criteria);
        return $this;
    }

//    public function tickets($user_id, $since = null) {
//        
//        $criteria = array(
//            'with' => array('organization', 'organization.tickets'),
//            'together' => true,
//            'condition' => 't.user_id = :user_id',
//            'params' => array(':user_id' => $user_id),
//        );
//        if($since) {
//            $criteria['condition'] .= ' AND tickets.created_at > :time';
//            $criteria['params'][':time'] = $since;
//        }
//        $this->getDbCriteria()->mergeWith($criteria);
//        return $this;
//    }
    
}
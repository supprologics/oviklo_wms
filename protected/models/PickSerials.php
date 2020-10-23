<?php

/**
 * This is the model class for table "pick_serials".
 *
 * The followings are the available columns in table 'pick_serials':
 * @property integer $id
 * @property integer $pick_items_id
 * @property integer $serials_id
 * @property string $created
 *
 * The followings are the available model relations:
 * @property PickItems $pickItems
 * @property Serials $serials
 */
class PickSerials extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'pick_serials';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('pick_items_id, serials_id, created', 'required'),
			array('pick_items_id, serials_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, pick_items_id, serials_id, created', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'pickItems' => array(self::BELONGS_TO, 'PickItems', 'pick_items_id'),
			'serials' => array(self::BELONGS_TO, 'Serials', 'serials_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'pick_items_id' => 'Pick Items',
			'serials_id' => 'Serials',
			'created' => 'Created',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('pick_items_id',$this->pick_items_id);
		$criteria->compare('serials_id',$this->serials_id);
		$criteria->compare('created',$this->created,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PickSerials the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

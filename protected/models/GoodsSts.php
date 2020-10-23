<?php

/**
 * This is the model class for table "goods_sts".
 *
 * The followings are the available columns in table 'goods_sts':
 * @property integer $id
 * @property string $name
 * @property string $created
 * @property integer $online
 *
 * The followings are the available model relations:
 * @property GrnItems[] $grnItems
 * @property MrItems[] $mrItems
 * @property PickItems[] $pickItems
 * @property Stock[] $stocks
 * @property Tn[] $tns
 * @property Tn[] $tns1
 */
class GoodsSts extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'goods_sts';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, created', 'required'),
			array('online', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>45),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, created, online', 'safe', 'on'=>'search'),
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
			'grnItems' => array(self::HAS_MANY, 'GrnItems', 'goods_sts_id'),
			'mrItems' => array(self::HAS_MANY, 'MrItems', 'goods_sts_id'),
			'pickItems' => array(self::HAS_MANY, 'PickItems', 'goods_sts_id'),
			'stocks' => array(self::HAS_MANY, 'Stock', 'goods_sts_id'),
			'tns' => array(self::HAS_MANY, 'Tn', 'goods_sts_from'),
			'tns1' => array(self::HAS_MANY, 'Tn', 'goods_sts_to'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'created' => 'Created',
			'online' => 'Online',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('online',$this->online);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return GoodsSts the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

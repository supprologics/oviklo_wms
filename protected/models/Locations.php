<?php

/**
 * This is the model class for table "locations".
 *
 * The followings are the available columns in table 'locations':
 * @property integer $id
 * @property integer $warehouse_id
 * @property string $code
 * @property string $name
 * @property double $max_cbm
 * @property string $created
 * @property integer $online
 *
 * The followings are the available model relations:
 * @property CategoryPutaway[] $categoryPutaways
 * @property GrnItems[] $grnItems
 * @property Warehouse $warehouse
 * @property PickItems[] $pickItems
 * @property SkuPutaway[] $skuPutaways
 * @property Stock[] $stocks
 * @property Tn[] $tns
 * @property Tn[] $tns1
 */
class Locations extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'locations';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('warehouse_id, code, name, created', 'required'),
			array('warehouse_id, online', 'numerical', 'integerOnly'=>true),
			array('max_cbm', 'numerical'),
			array('code', 'length', 'max'=>20),
			array('name', 'length', 'max'=>60),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, warehouse_id, code, name, max_cbm, created, online', 'safe', 'on'=>'search'),
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
			'categoryPutaways' => array(self::HAS_MANY, 'CategoryPutaway', 'locations_id'),
			'grnItems' => array(self::HAS_MANY, 'GrnItems', 'locations_id'),
			'warehouse' => array(self::BELONGS_TO, 'Warehouse', 'warehouse_id'),
			'pickItems' => array(self::HAS_MANY, 'PickItems', 'locations_id'),
			'skuPutaways' => array(self::HAS_MANY, 'SkuPutaway', 'locations_id'),
			'stocks' => array(self::HAS_MANY, 'Stock', 'locations_id'),
			'tns' => array(self::HAS_MANY, 'Tn', 'locations_from'),
			'tns1' => array(self::HAS_MANY, 'Tn', 'locations_to'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'warehouse_id' => 'Warehouse',
			'code' => 'Code',
			'name' => 'Name',
			'max_cbm' => 'Max Cbm',
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
		$criteria->compare('warehouse_id',$this->warehouse_id);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('max_cbm',$this->max_cbm);
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
	 * @return Locations the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

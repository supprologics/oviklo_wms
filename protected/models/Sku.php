<?php

/**
 * This is the model class for table "sku".
 *
 * The followings are the available columns in table 'sku':
 * @property integer $id
 * @property integer $customers_id
 * @property integer $category_id
 * @property integer $uom_id
 * @property string $code
 * @property string $description
 * @property string $volume
 * @property string $weight
 * @property string $cbm
 * @property string $sqft
 * @property string $rol
 * @property string $roq
 * @property string $pick_type
 * @property integer $max_stacking
 * @property integer $movement_type
 * @property string $created
 * @property integer $online
 *
 * The followings are the available model relations:
 * @property GrnItems[] $grnItems
 * @property MrItems[] $mrItems
 * @property PickItems[] $pickItems
 * @property SerialStock[] $serialStocks
 * @property Serials[] $serials
 * @property Category $category
 * @property Customers $customers
 * @property Uom $uom
 * @property SkuPutaway[] $skuPutaways
 * @property Stock[] $stocks
 * @property TnItems[] $tnItems
 */
class Sku extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sku';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customers_id, category_id, uom_id, code, description, created', 'required'),
			array('customers_id, category_id, uom_id, max_stacking, movement_type, online', 'numerical', 'integerOnly'=>true),
			array('code', 'length', 'max'=>150),
			array('description', 'length', 'max'=>250),
			array('volume, weight, cbm, sqft, rol, roq', 'length', 'max'=>10),
			array('pick_type', 'length', 'max'=>4),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, customers_id, category_id, uom_id, code, description, volume, weight, cbm, sqft, rol, roq, pick_type, max_stacking, movement_type, created, online', 'safe', 'on'=>'search'),
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
			'grnItems' => array(self::HAS_MANY, 'GrnItems', 'sku_id'),
			'mrItems' => array(self::HAS_MANY, 'MrItems', 'sku_id'),
			'pickItems' => array(self::HAS_MANY, 'PickItems', 'sku_id'),
			'serialStocks' => array(self::HAS_MANY, 'SerialStock', 'sku_id'),
			'serials' => array(self::HAS_MANY, 'Serials', 'sku_id'),
			'category' => array(self::BELONGS_TO, 'Category', 'category_id'),
			'customers' => array(self::BELONGS_TO, 'Customers', 'customers_id'),
			'uom' => array(self::BELONGS_TO, 'Uom', 'uom_id'),
			'skuPutaways' => array(self::HAS_MANY, 'SkuPutaway', 'sku_id'),
			'stocks' => array(self::HAS_MANY, 'Stock', 'sku_id'),
			'tnItems' => array(self::HAS_MANY, 'TnItems', 'sku_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'customers_id' => 'Customers',
			'category_id' => 'Category',
			'uom_id' => 'Uom',
			'code' => 'Code',
			'description' => 'Description',
			'volume' => 'Volume',
			'weight' => 'Weight',
			'cbm' => 'Cbm',
			'sqft' => 'Sqft',
			'rol' => 'Rol',
			'roq' => 'Roq',
			'pick_type' => 'Pick Type',
			'max_stacking' => 'Max Stacking',
			'movement_type' => 'Movement Type',
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
		$criteria->compare('customers_id',$this->customers_id);
		$criteria->compare('category_id',$this->category_id);
		$criteria->compare('uom_id',$this->uom_id);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('volume',$this->volume,true);
		$criteria->compare('weight',$this->weight,true);
		$criteria->compare('cbm',$this->cbm,true);
		$criteria->compare('sqft',$this->sqft,true);
		$criteria->compare('rol',$this->rol,true);
		$criteria->compare('roq',$this->roq,true);
		$criteria->compare('pick_type',$this->pick_type,true);
		$criteria->compare('max_stacking',$this->max_stacking);
		$criteria->compare('movement_type',$this->movement_type);
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
	 * @return Sku the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

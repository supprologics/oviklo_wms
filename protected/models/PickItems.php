<?php

/**
 * This is the model class for table "pick_items".
 *
 * The followings are the available columns in table 'pick_items':
 * @property integer $id
 * @property integer $sku_id
 * @property integer $mr_id
 * @property integer $mr_items_id
 * @property integer $goods_sts_id
 * @property string $batch_no
 * @property string $pkg_no
 * @property string $qty_req
 * @property string $qty
 * @property string $expire_date
 * @property string $manf_date
 * @property string $remarks
 * @property integer $locations_id
 * @property string $sub_location
 * @property string $created
 * @property string $last_update
 * @property integer $online
 *
 * The followings are the available model relations:
 * @property GoodsSts $goodsSts
 * @property Locations $locations
 * @property Mr $mr
 * @property MrItems $mrItems
 * @property Sku $sku
 * @property PickSerials[] $pickSerials
 */
class PickItems extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'pick_items';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sku_id, mr_id, mr_items_id, goods_sts_id, qty_req, qty, locations_id, created, last_update', 'required'),
			array('sku_id, mr_id, mr_items_id, goods_sts_id, locations_id, online', 'numerical', 'integerOnly'=>true),
			array('batch_no, pkg_no, sub_location', 'length', 'max'=>45),
			array('qty_req, qty', 'length', 'max'=>10),
			array('expire_date, manf_date, remarks', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, sku_id, mr_id, mr_items_id, goods_sts_id, batch_no, pkg_no, qty_req, qty, expire_date, manf_date, remarks, locations_id, sub_location, created, last_update, online', 'safe', 'on'=>'search'),
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
			'goodsSts' => array(self::BELONGS_TO, 'GoodsSts', 'goods_sts_id'),
			'locations' => array(self::BELONGS_TO, 'Locations', 'locations_id'),
			'mr' => array(self::BELONGS_TO, 'Mr', 'mr_id'),
			'mrItems' => array(self::BELONGS_TO, 'MrItems', 'mr_items_id'),
			'sku' => array(self::BELONGS_TO, 'Sku', 'sku_id'),
			'pickSerials' => array(self::HAS_MANY, 'PickSerials', 'pick_items_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'sku_id' => 'Sku',
			'mr_id' => 'Mr',
			'mr_items_id' => 'Mr Items',
			'goods_sts_id' => 'Goods Sts',
			'batch_no' => 'Batch No',
			'pkg_no' => 'Pkg No',
			'qty_req' => 'Qty Req',
			'qty' => 'Qty',
			'expire_date' => 'Expire Date',
			'manf_date' => 'Manf Date',
			'remarks' => 'Remarks',
			'locations_id' => 'Locations',
			'sub_location' => 'Sub Location',
			'created' => 'Created',
			'last_update' => 'Last Update',
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
		$criteria->compare('sku_id',$this->sku_id);
		$criteria->compare('mr_id',$this->mr_id);
		$criteria->compare('mr_items_id',$this->mr_items_id);
		$criteria->compare('goods_sts_id',$this->goods_sts_id);
		$criteria->compare('batch_no',$this->batch_no,true);
		$criteria->compare('pkg_no',$this->pkg_no,true);
		$criteria->compare('qty_req',$this->qty_req,true);
		$criteria->compare('qty',$this->qty,true);
		$criteria->compare('expire_date',$this->expire_date,true);
		$criteria->compare('manf_date',$this->manf_date,true);
		$criteria->compare('remarks',$this->remarks,true);
		$criteria->compare('locations_id',$this->locations_id);
		$criteria->compare('sub_location',$this->sub_location,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('last_update',$this->last_update,true);
		$criteria->compare('online',$this->online);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PickItems the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

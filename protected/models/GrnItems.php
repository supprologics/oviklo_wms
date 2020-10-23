<?php

/**
 * This is the model class for table "grn_items".
 *
 * The followings are the available columns in table 'grn_items':
 * @property integer $id
 * @property string $code
 * @property integer $grn_id
 * @property integer $sku_id
 * @property integer $goods_sts_id
 * @property string $batch_no
 * @property string $pkg_no
 * @property integer $locations_id
 * @property string $sub_location
 * @property string $qty
 * @property string $manf_date
 * @property string $expire_date
 * @property string $remarks
 * @property string $created
 * @property integer $online
 *
 * The followings are the available model relations:
 * @property GoodsSts $goodsSts
 * @property Grn $grn
 * @property Locations $locations
 * @property Sku $sku
 * @property GrnSerials[] $grnSerials
 */
class GrnItems extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'grn_items';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('code, grn_id, sku_id, goods_sts_id, qty', 'required'),
			array('grn_id, sku_id, goods_sts_id, locations_id, online', 'numerical', 'integerOnly'=>true),
			array('code, batch_no, pkg_no', 'length', 'max'=>60),
			array('sub_location', 'length', 'max'=>45),
			array('qty', 'length', 'max'=>10),
			array('remarks', 'length', 'max'=>250),
			array('manf_date, expire_date, created', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, code, grn_id, sku_id, goods_sts_id, batch_no, pkg_no, locations_id, sub_location, qty, manf_date, expire_date, remarks, created, online', 'safe', 'on'=>'search'),
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
			'grn' => array(self::BELONGS_TO, 'Grn', 'grn_id'),
			'locations' => array(self::BELONGS_TO, 'Locations', 'locations_id'),
			'sku' => array(self::BELONGS_TO, 'Sku', 'sku_id'),
			'grnSerials' => array(self::HAS_MANY, 'GrnSerials', 'grn_items_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'code' => 'Code',
			'grn_id' => 'Grn',
			'sku_id' => 'Sku',
			'goods_sts_id' => 'Goods Sts',
			'batch_no' => 'Batch No',
			'pkg_no' => 'Pkg No',
			'locations_id' => 'Locations',
			'sub_location' => 'Sub Location',
			'qty' => 'Qty',
			'manf_date' => 'Manf Date',
			'expire_date' => 'Expire Date',
			'remarks' => 'Remarks',
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
		$criteria->compare('code',$this->code,true);
		$criteria->compare('grn_id',$this->grn_id);
		$criteria->compare('sku_id',$this->sku_id);
		$criteria->compare('goods_sts_id',$this->goods_sts_id);
		$criteria->compare('batch_no',$this->batch_no,true);
		$criteria->compare('pkg_no',$this->pkg_no,true);
		$criteria->compare('locations_id',$this->locations_id);
		$criteria->compare('sub_location',$this->sub_location,true);
		$criteria->compare('qty',$this->qty,true);
		$criteria->compare('manf_date',$this->manf_date,true);
		$criteria->compare('expire_date',$this->expire_date,true);
		$criteria->compare('remarks',$this->remarks,true);
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
	 * @return GrnItems the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

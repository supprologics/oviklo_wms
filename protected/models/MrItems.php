<?php

/**
 * This is the model class for table "mr_items".
 *
 * The followings are the available columns in table 'mr_items':
 * @property integer $id
 * @property integer $mr_id
 * @property integer $sku_id
 * @property integer $goods_sts_id
 * @property string $batch_no
 * @property string $qty
 * @property string $expire_date
 * @property string $manf_date
 * @property string $remarks
 *
 * The followings are the available model relations:
 * @property GoodsSts $goodsSts
 * @property Mr $mr
 * @property Sku $sku
 * @property PickItems[] $pickItems
 */
class MrItems extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'mr_items';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('mr_id, sku_id, goods_sts_id, qty', 'required'),
			array('mr_id, sku_id, goods_sts_id', 'numerical', 'integerOnly'=>true),
			array('batch_no', 'length', 'max'=>45),
			array('qty', 'length', 'max'=>10),
			array('expire_date, manf_date, remarks', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, mr_id, sku_id, goods_sts_id, batch_no, qty, expire_date, manf_date, remarks', 'safe', 'on'=>'search'),
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
			'mr' => array(self::BELONGS_TO, 'Mr', 'mr_id'),
			'sku' => array(self::BELONGS_TO, 'Sku', 'sku_id'),
			'pickItems' => array(self::HAS_MANY, 'PickItems', 'mr_items_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'mr_id' => 'Mr',
			'sku_id' => 'Sku',
			'goods_sts_id' => 'Goods Sts',
			'batch_no' => 'Batch No',
			'qty' => 'Qty',
			'expire_date' => 'Expire Date',
			'manf_date' => 'Manf Date',
			'remarks' => 'Remarks',
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
		$criteria->compare('mr_id',$this->mr_id);
		$criteria->compare('sku_id',$this->sku_id);
		$criteria->compare('goods_sts_id',$this->goods_sts_id);
		$criteria->compare('batch_no',$this->batch_no,true);
		$criteria->compare('qty',$this->qty,true);
		$criteria->compare('expire_date',$this->expire_date,true);
		$criteria->compare('manf_date',$this->manf_date,true);
		$criteria->compare('remarks',$this->remarks,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return MrItems the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

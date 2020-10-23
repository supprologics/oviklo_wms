<?php

/**
 * This is the model class for table "tn".
 *
 * The followings are the available columns in table 'tn':
 * @property integer $id
 * @property integer $customers_id
 * @property string $code
 * @property integer $warehouse_from
 * @property integer $warehouse_to
 * @property integer $goods_sts_from
 * @property integer $goods_sts_to
 * @property integer $project_from
 * @property integer $project_to
 * @property integer $locations_from
 * @property integer $locations_to
 * @property string $sub_location_from
 * @property string $sub_location
 * @property string $remarks
 * @property string $created
 * @property string $last_updated
 * @property integer $online
 * @property integer $users_id
 *
 * The followings are the available model relations:
 * @property Customers $customers
 * @property GoodsSts $goodsStsFrom
 * @property GoodsSts $goodsStsTo
 * @property Locations $locationsFrom
 * @property Locations $locationsTo
 * @property Project $projectFrom
 * @property Project $projectTo
 * @property Users $users
 * @property Warehouse $warehouseFrom
 * @property Warehouse $warehouseTo
 * @property TnItems[] $tnItems
 */
class Tn extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tn';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customers_id, code, warehouse_from, warehouse_to, goods_sts_from, goods_sts_to, project_from, project_to, locations_from, locations_to, created, users_id', 'required'),
			array('customers_id, warehouse_from, warehouse_to, goods_sts_from, goods_sts_to, project_from, project_to, locations_from, locations_to, online, users_id', 'numerical', 'integerOnly'=>true),
			array('code', 'length', 'max'=>60),
			array('sub_location_from, sub_location', 'length', 'max'=>45),
			array('remarks, last_updated', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, customers_id, code, warehouse_from, warehouse_to, goods_sts_from, goods_sts_to, project_from, project_to, locations_from, locations_to, sub_location_from, sub_location, remarks, created, last_updated, online, users_id', 'safe', 'on'=>'search'),
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
			'customers' => array(self::BELONGS_TO, 'Customers', 'customers_id'),
			'goodsStsFrom' => array(self::BELONGS_TO, 'GoodsSts', 'goods_sts_from'),
			'goodsStsTo' => array(self::BELONGS_TO, 'GoodsSts', 'goods_sts_to'),
			'locationsFrom' => array(self::BELONGS_TO, 'Locations', 'locations_from'),
			'locationsTo' => array(self::BELONGS_TO, 'Locations', 'locations_to'),
			'projectFrom' => array(self::BELONGS_TO, 'Project', 'project_from'),
			'projectTo' => array(self::BELONGS_TO, 'Project', 'project_to'),
			'users' => array(self::BELONGS_TO, 'Users', 'users_id'),
			'warehouseFrom' => array(self::BELONGS_TO, 'Warehouse', 'warehouse_from'),
			'warehouseTo' => array(self::BELONGS_TO, 'Warehouse', 'warehouse_to'),
			'tnItems' => array(self::HAS_MANY, 'TnItems', 'tn_id'),
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
			'code' => 'Code',
			'warehouse_from' => 'Warehouse From',
			'warehouse_to' => 'Warehouse To',
			'goods_sts_from' => 'Goods Sts From',
			'goods_sts_to' => 'Goods Sts To',
			'project_from' => 'Project From',
			'project_to' => 'Project To',
			'locations_from' => 'Locations From',
			'locations_to' => 'Locations To',
			'sub_location_from' => 'Sub Location From',
			'sub_location' => 'Sub Location',
			'remarks' => 'Remarks',
			'created' => 'Created',
			'last_updated' => 'Last Updated',
			'online' => 'Online',
			'users_id' => 'Users',
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
		$criteria->compare('code',$this->code,true);
		$criteria->compare('warehouse_from',$this->warehouse_from);
		$criteria->compare('warehouse_to',$this->warehouse_to);
		$criteria->compare('goods_sts_from',$this->goods_sts_from);
		$criteria->compare('goods_sts_to',$this->goods_sts_to);
		$criteria->compare('project_from',$this->project_from);
		$criteria->compare('project_to',$this->project_to);
		$criteria->compare('locations_from',$this->locations_from);
		$criteria->compare('locations_to',$this->locations_to);
		$criteria->compare('sub_location_from',$this->sub_location_from,true);
		$criteria->compare('sub_location',$this->sub_location,true);
		$criteria->compare('remarks',$this->remarks,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('last_updated',$this->last_updated,true);
		$criteria->compare('online',$this->online);
		$criteria->compare('users_id',$this->users_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Tn the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

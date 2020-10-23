<?php

/**
 * This is the model class for table "mr".
 *
 * The followings are the available columns in table 'mr':
 * @property integer $id
 * @property integer $customers_id
 * @property integer $warehouse_id
 * @property integer $project_id
 * @property string $code
 * @property string $eff_date
 * @property string $delivery_date
 * @property string $col_name
 * @property string $col_nic
 * @property string $col_mobile
 * @property string $col_vehicle
 * @property string $dest1_name
 * @property string $dest2_name
 * @property string $link_name
 * @property string $remarks
 * @property string $vehicle_in
 * @property string $vehicle_out
 * @property string $start_time
 * @property string $end_time
 * @property string $created
 * @property string $last_update
 * @property integer $online
 * @property integer $users_id
 * @property integer $picked_id
 *
 * The followings are the available model relations:
 * @property Customers $customers
 * @property Users $picked
 * @property Project $project
 * @property Users $users
 * @property Warehouse $warehouse
 * @property MrItems[] $mrItems
 * @property PickItems[] $pickItems
 */
class Mr extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'mr';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customers_id, warehouse_id, project_id, code, created, last_update, users_id, picked_id', 'required'),
			array('customers_id, warehouse_id, project_id, online, users_id, picked_id', 'numerical', 'integerOnly'=>true),
			array('code', 'length', 'max'=>60),
			array('col_name, dest1_name, dest2_name, link_name', 'length', 'max'=>250),
			array('col_nic, col_mobile', 'length', 'max'=>12),
			array('col_vehicle', 'length', 'max'=>40),
			array('eff_date, delivery_date, remarks, vehicle_in, vehicle_out, start_time, end_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, customers_id, warehouse_id, project_id, code, eff_date, delivery_date, col_name, col_nic, col_mobile, col_vehicle, dest1_name, dest2_name, link_name, remarks, vehicle_in, vehicle_out, start_time, end_time, created, last_update, online, users_id, picked_id', 'safe', 'on'=>'search'),
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
			'picked' => array(self::BELONGS_TO, 'Users', 'picked_id'),
			'project' => array(self::BELONGS_TO, 'Project', 'project_id'),
			'users' => array(self::BELONGS_TO, 'Users', 'users_id'),
			'warehouse' => array(self::BELONGS_TO, 'Warehouse', 'warehouse_id'),
			'mrItems' => array(self::HAS_MANY, 'MrItems', 'mr_id'),
			'pickItems' => array(self::HAS_MANY, 'PickItems', 'mr_id'),
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
			'warehouse_id' => 'Warehouse',
			'project_id' => 'Project',
			'code' => 'Code',
			'eff_date' => 'Eff Date',
			'delivery_date' => 'Delivery Date',
			'col_name' => 'Col Name',
			'col_nic' => 'Col Nic',
			'col_mobile' => 'Col Mobile',
			'col_vehicle' => 'Col Vehicle',
			'dest1_name' => 'Dest1 Name',
			'dest2_name' => 'Dest2 Name',
			'link_name' => 'Link Name',
			'remarks' => 'Remarks',
			'vehicle_in' => 'Vehicle In',
			'vehicle_out' => 'Vehicle Out',
			'start_time' => 'Start Time',
			'end_time' => 'End Time',
			'created' => 'Created',
			'last_update' => 'Last Update',
			'online' => 'Online',
			'users_id' => 'Users',
			'picked_id' => 'Picked',
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
		$criteria->compare('warehouse_id',$this->warehouse_id);
		$criteria->compare('project_id',$this->project_id);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('eff_date',$this->eff_date,true);
		$criteria->compare('delivery_date',$this->delivery_date,true);
		$criteria->compare('col_name',$this->col_name,true);
		$criteria->compare('col_nic',$this->col_nic,true);
		$criteria->compare('col_mobile',$this->col_mobile,true);
		$criteria->compare('col_vehicle',$this->col_vehicle,true);
		$criteria->compare('dest1_name',$this->dest1_name,true);
		$criteria->compare('dest2_name',$this->dest2_name,true);
		$criteria->compare('link_name',$this->link_name,true);
		$criteria->compare('remarks',$this->remarks,true);
		$criteria->compare('vehicle_in',$this->vehicle_in,true);
		$criteria->compare('vehicle_out',$this->vehicle_out,true);
		$criteria->compare('start_time',$this->start_time,true);
		$criteria->compare('end_time',$this->end_time,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('last_update',$this->last_update,true);
		$criteria->compare('online',$this->online);
		$criteria->compare('users_id',$this->users_id);
		$criteria->compare('picked_id',$this->picked_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Mr the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

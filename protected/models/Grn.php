<?php

/**
 * This is the model class for table "grn".
 *
 * The followings are the available columns in table 'grn':
 * @property integer $id
 * @property string $code
 * @property integer $customers_id
 * @property integer $warehouse_id
 * @property integer $project_id
 * @property string $supplier
 * @property string $eff_date
 * @property string $container_no
 * @property string $vehicle_no
 * @property string $packinglist_no
 * @property string $ref_no
 * @property string $vehicle_in
 * @property string $vehicle_out
 * @property string $start_time
 * @property string $end_time
 * @property string $remarks
 * @property integer $users_id
 * @property integer $confirmed_by
 * @property string $created
 * @property string $last_update
 * @property integer $online
 *
 * The followings are the available model relations:
 * @property Users $confirmedBy
 * @property Customers $customers
 * @property Project $project
 * @property Users $users
 * @property Warehouse $warehouse
 * @property GrnItems[] $grnItems
 */
class Grn extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'grn';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('code, customers_id, warehouse_id, project_id, supplier, eff_date, users_id, created, last_update', 'required'),
			array('customers_id, warehouse_id, project_id, users_id, confirmed_by, online', 'numerical', 'integerOnly'=>true),
			array('code', 'length', 'max'=>150),
			array('supplier', 'length', 'max'=>250),
			array('container_no', 'length', 'max'=>100),
			array('vehicle_no, packinglist_no, ref_no', 'length', 'max'=>65),
			array('vehicle_in, vehicle_out, start_time, end_time, remarks', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, code, customers_id, warehouse_id, project_id, supplier, eff_date, container_no, vehicle_no, packinglist_no, ref_no, vehicle_in, vehicle_out, start_time, end_time, remarks, users_id, confirmed_by, created, last_update, online', 'safe', 'on'=>'search'),
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
			'confirmedBy' => array(self::BELONGS_TO, 'Users', 'confirmed_by'),
			'customers' => array(self::BELONGS_TO, 'Customers', 'customers_id'),
			'project' => array(self::BELONGS_TO, 'Project', 'project_id'),
			'users' => array(self::BELONGS_TO, 'Users', 'users_id'),
			'warehouse' => array(self::BELONGS_TO, 'Warehouse', 'warehouse_id'),
			'grnItems' => array(self::HAS_MANY, 'GrnItems', 'grn_id'),
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
			'customers_id' => 'Customers',
			'warehouse_id' => 'Warehouse',
			'project_id' => 'Project',
			'supplier' => 'Supplier',
			'eff_date' => 'Eff Date',
			'container_no' => 'Container No',
			'vehicle_no' => 'Vehicle No',
			'packinglist_no' => 'Packinglist No',
			'ref_no' => 'Ref No',
			'vehicle_in' => 'Vehicle In',
			'vehicle_out' => 'Vehicle Out',
			'start_time' => 'Start Time',
			'end_time' => 'End Time',
			'remarks' => 'Remarks',
			'users_id' => 'Users',
			'confirmed_by' => 'Confirmed By',
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
		$criteria->compare('code',$this->code,true);
		$criteria->compare('customers_id',$this->customers_id);
		$criteria->compare('warehouse_id',$this->warehouse_id);
		$criteria->compare('project_id',$this->project_id);
		$criteria->compare('supplier',$this->supplier,true);
		$criteria->compare('eff_date',$this->eff_date,true);
		$criteria->compare('container_no',$this->container_no,true);
		$criteria->compare('vehicle_no',$this->vehicle_no,true);
		$criteria->compare('packinglist_no',$this->packinglist_no,true);
		$criteria->compare('ref_no',$this->ref_no,true);
		$criteria->compare('vehicle_in',$this->vehicle_in,true);
		$criteria->compare('vehicle_out',$this->vehicle_out,true);
		$criteria->compare('start_time',$this->start_time,true);
		$criteria->compare('end_time',$this->end_time,true);
		$criteria->compare('remarks',$this->remarks,true);
		$criteria->compare('users_id',$this->users_id);
		$criteria->compare('confirmed_by',$this->confirmed_by);
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
	 * @return Grn the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

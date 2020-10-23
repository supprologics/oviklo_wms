<?php

/**
 * This is the model class for table "stock".
 *
 * The followings are the available columns in table 'stock':
 * @property integer $id
 * @property integer $sku_id
 * @property integer $customers_id
 * @property integer $warehouse_id
 * @property integer $category_id
 * @property integer $project_id
 * @property integer $locations_id
 * @property integer $uom_id
 * @property integer $users_id
 * @property integer $goods_sts_id
 * @property string $suppliers
 * @property string $batch_no
 * @property string $pkg_no
 * @property string $sub_location
 * @property string $qty
 * @property string $manf_date
 * @property string $expire_date
 * @property string $created
 * @property string $tbl_name
 * @property integer $grn_id
 * @property integer $p_id
 * @property integer $f_id
 * @property integer $online
 *
 * The followings are the available model relations:
 * @property Category $category
 * @property Customers $customers
 * @property GoodsSts $goodsSts
 * @property Locations $locations
 * @property Project $project
 * @property Sku $sku
 * @property Uom $uom
 * @property Users $users
 * @property Warehouse $warehouse
 */
class Stock extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'stock';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sku_id, customers_id, warehouse_id, category_id, project_id, uom_id, users_id, goods_sts_id, qty, tbl_name, grn_id, p_id, f_id', 'required'),
			array('sku_id, customers_id, warehouse_id, category_id, project_id, locations_id, uom_id, users_id, goods_sts_id, grn_id, p_id, f_id, online', 'numerical', 'integerOnly'=>true),
			array('suppliers, batch_no, pkg_no', 'length', 'max'=>150),
			array('sub_location', 'length', 'max'=>45),
			array('qty', 'length', 'max'=>10),
			array('tbl_name', 'length', 'max'=>60),
			array('manf_date, expire_date, created', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, sku_id, customers_id, warehouse_id, category_id, project_id, locations_id, uom_id, users_id, goods_sts_id, suppliers, batch_no, pkg_no, sub_location, qty, manf_date, expire_date, created, tbl_name, grn_id, p_id, f_id, online', 'safe', 'on'=>'search'),
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
			'category' => array(self::BELONGS_TO, 'Category', 'category_id'),
			'customers' => array(self::BELONGS_TO, 'Customers', 'customers_id'),
			'goodsSts' => array(self::BELONGS_TO, 'GoodsSts', 'goods_sts_id'),
			'locations' => array(self::BELONGS_TO, 'Locations', 'locations_id'),
			'project' => array(self::BELONGS_TO, 'Project', 'project_id'),
			'sku' => array(self::BELONGS_TO, 'Sku', 'sku_id'),
			'uom' => array(self::BELONGS_TO, 'Uom', 'uom_id'),
			'users' => array(self::BELONGS_TO, 'Users', 'users_id'),
			'warehouse' => array(self::BELONGS_TO, 'Warehouse', 'warehouse_id'),
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
			'customers_id' => 'Customers',
			'warehouse_id' => 'Warehouse',
			'category_id' => 'Category',
			'project_id' => 'Project',
			'locations_id' => 'Locations',
			'uom_id' => 'Uom',
			'users_id' => 'Users',
			'goods_sts_id' => 'Goods Sts',
			'suppliers' => 'Suppliers',
			'batch_no' => 'Batch No',
			'pkg_no' => 'Pkg No',
			'sub_location' => 'Sub Location',
			'qty' => 'Qty',
			'manf_date' => 'Manf Date',
			'expire_date' => 'Expire Date',
			'created' => 'Created',
			'tbl_name' => 'Tbl Name',
			'grn_id' => 'Grn',
			'p_id' => 'P',
			'f_id' => 'F',
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
		$criteria->compare('customers_id',$this->customers_id);
		$criteria->compare('warehouse_id',$this->warehouse_id);
		$criteria->compare('category_id',$this->category_id);
		$criteria->compare('project_id',$this->project_id);
		$criteria->compare('locations_id',$this->locations_id);
		$criteria->compare('uom_id',$this->uom_id);
		$criteria->compare('users_id',$this->users_id);
		$criteria->compare('goods_sts_id',$this->goods_sts_id);
		$criteria->compare('suppliers',$this->suppliers,true);
		$criteria->compare('batch_no',$this->batch_no,true);
		$criteria->compare('pkg_no',$this->pkg_no,true);
		$criteria->compare('sub_location',$this->sub_location,true);
		$criteria->compare('qty',$this->qty,true);
		$criteria->compare('manf_date',$this->manf_date,true);
		$criteria->compare('expire_date',$this->expire_date,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('tbl_name',$this->tbl_name,true);
		$criteria->compare('grn_id',$this->grn_id);
		$criteria->compare('p_id',$this->p_id);
		$criteria->compare('f_id',$this->f_id);
		$criteria->compare('online',$this->online);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Stock the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

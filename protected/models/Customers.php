<?php

/**
 * This is the model class for table "customers".
 *
 * The followings are the available columns in table 'customers':
 * @property integer $id
 * @property integer $biz_cat_id
 * @property string $code
 * @property string $name
 * @property string $address
 * @property string $tel_1
 * @property string $tel_2
 * @property string $email
 * @property integer $by_batch
 * @property integer $is_serial
 * @property string $created
 * @property integer $online
 *
 * The followings are the available model relations:
 * @property Category[] $categories
 * @property BizCat $bizCat
 * @property Grn[] $grns
 * @property Mr[] $mrs
 * @property Project[] $projects
 * @property SerialStock[] $serialStocks
 * @property Serials[] $serials
 * @property Sku[] $skus
 * @property Stock[] $stocks
 * @property Tn[] $tns
 * @property UserHasCustomers[] $userHasCustomers
 */
class Customers extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'customers';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('biz_cat_id, code, name, created', 'required'),
			array('biz_cat_id, by_batch, is_serial, online', 'numerical', 'integerOnly'=>true),
			array('code', 'length', 'max'=>6),
			array('name, email', 'length', 'max'=>150),
			array('tel_1, tel_2', 'length', 'max'=>15),
			array('address', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, biz_cat_id, code, name, address, tel_1, tel_2, email, by_batch, is_serial, created, online', 'safe', 'on'=>'search'),
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
			'categories' => array(self::HAS_MANY, 'Category', 'customers_id'),
			'bizCat' => array(self::BELONGS_TO, 'BizCat', 'biz_cat_id'),
			'grns' => array(self::HAS_MANY, 'Grn', 'customers_id'),
			'mrs' => array(self::HAS_MANY, 'Mr', 'customers_id'),
			'projects' => array(self::HAS_MANY, 'Project', 'customers_id'),
			'serialStocks' => array(self::HAS_MANY, 'SerialStock', 'customers_id'),
			'serials' => array(self::HAS_MANY, 'Serials', 'customers_id'),
			'skus' => array(self::HAS_MANY, 'Sku', 'customers_id'),
			'stocks' => array(self::HAS_MANY, 'Stock', 'customers_id'),
			'tns' => array(self::HAS_MANY, 'Tn', 'customers_id'),
			'userHasCustomers' => array(self::HAS_MANY, 'UserHasCustomers', 'customers_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'biz_cat_id' => 'Biz Cat',
			'code' => 'Code',
			'name' => 'Name',
			'address' => 'Address',
			'tel_1' => 'Tel 1',
			'tel_2' => 'Tel 2',
			'email' => 'Email',
			'by_batch' => 'By Batch',
			'is_serial' => 'Is Serial',
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
		$criteria->compare('biz_cat_id',$this->biz_cat_id);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('tel_1',$this->tel_1,true);
		$criteria->compare('tel_2',$this->tel_2,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('by_batch',$this->by_batch);
		$criteria->compare('is_serial',$this->is_serial);
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
	 * @return Customers the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

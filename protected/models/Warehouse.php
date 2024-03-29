<?php

/**
 * This is the model class for table "warehouse".
 *
 * The followings are the available columns in table 'warehouse':
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $address
 * @property string $mobile
 * @property string $email
 * @property string $created
 * @property integer $online
 *
 * The followings are the available model relations:
 * @property Grn[] $grns
 * @property Locations[] $locations
 * @property Mr[] $mrs
 * @property Stock[] $stocks
 * @property Tn[] $tns
 * @property Tn[] $tns1
 * @property UserHasWarehouse[] $userHasWarehouses
 */
class Warehouse extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'warehouse';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('code, name, created', 'required'),
			array('online', 'numerical', 'integerOnly'=>true),
			array('code', 'length', 'max'=>5),
			array('name, email', 'length', 'max'=>60),
			array('mobile', 'length', 'max'=>15),
			array('address', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, code, name, address, mobile, email, created, online', 'safe', 'on'=>'search'),
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
			'grns' => array(self::HAS_MANY, 'Grn', 'warehouse_id'),
			'locations' => array(self::HAS_MANY, 'Locations', 'warehouse_id'),
			'mrs' => array(self::HAS_MANY, 'Mr', 'warehouse_id'),
			'stocks' => array(self::HAS_MANY, 'Stock', 'warehouse_id'),
			'tns' => array(self::HAS_MANY, 'Tn', 'warehouse_from'),
			'tns1' => array(self::HAS_MANY, 'Tn', 'warehouse_to'),
			'userHasWarehouses' => array(self::HAS_MANY, 'UserHasWarehouse', 'warehouse_id'),
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
			'name' => 'Name',
			'address' => 'Address',
			'mobile' => 'Mobile',
			'email' => 'Email',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('email',$this->email,true);
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
	 * @return Warehouse the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

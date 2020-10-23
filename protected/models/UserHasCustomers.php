<?php

/**
 * This is the model class for table "user_has_customers".
 *
 * The followings are the available columns in table 'user_has_customers':
 * @property integer $id
 * @property integer $customers_id
 * @property integer $users_id
 * @property integer $online
 * @property integer $mr_
 * @property integer $grn_
 * @property integer $pick_
 * @property integer $gdn_
 *
 * The followings are the available model relations:
 * @property Customers $customers
 * @property Users $users
 */
class UserHasCustomers extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user_has_customers';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customers_id, users_id', 'required'),
			array('customers_id, users_id, online, mr_, grn_, pick_, gdn_', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, customers_id, users_id, online, mr_, grn_, pick_, gdn_', 'safe', 'on'=>'search'),
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
			'users' => array(self::BELONGS_TO, 'Users', 'users_id'),
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
			'users_id' => 'Users',
			'online' => 'Online',
			'mr_' => 'Mr',
			'grn_' => 'Grn',
			'pick_' => 'Pick',
			'gdn_' => 'Gdn',
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
		$criteria->compare('users_id',$this->users_id);
		$criteria->compare('online',$this->online);
		$criteria->compare('mr_',$this->mr_);
		$criteria->compare('grn_',$this->grn_);
		$criteria->compare('pick_',$this->pick_);
		$criteria->compare('gdn_',$this->gdn_);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserHasCustomers the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

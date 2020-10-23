<?php

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property integer $id
 * @property string $name
 * @property string $username
 * @property string $password
 * @property string $email
 * @property integer $level
 * @property string $created
 * @property integer $online
 *
 * The followings are the available model relations:
 * @property Grn[] $grns
 * @property Grn[] $grns1
 * @property Mr[] $mrs
 * @property Mr[] $mrs1
 * @property Stock[] $stocks
 * @property Tn[] $tns
 * @property UserHasCustomers[] $userHasCustomers
 * @property UserHasWarehouse[] $userHasWarehouses
 * @property UserReports[] $userReports
 * @property Useraccess[] $useraccesses
 */
class Users extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, username, password, email, created', 'required'),
			array('level, online', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>60),
			array('username', 'length', 'max'=>45),
			array('password', 'length', 'max'=>32),
			array('email', 'length', 'max'=>150),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, username, password, email, level, created, online', 'safe', 'on'=>'search'),
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
			'grns' => array(self::HAS_MANY, 'Grn', 'confirmed_by'),
			'grns1' => array(self::HAS_MANY, 'Grn', 'users_id'),
			'mrs' => array(self::HAS_MANY, 'Mr', 'picked_id'),
			'mrs1' => array(self::HAS_MANY, 'Mr', 'users_id'),
			'stocks' => array(self::HAS_MANY, 'Stock', 'users_id'),
			'tns' => array(self::HAS_MANY, 'Tn', 'users_id'),
			'userHasCustomers' => array(self::HAS_MANY, 'UserHasCustomers', 'users_id'),
			'userHasWarehouses' => array(self::HAS_MANY, 'UserHasWarehouse', 'users_id'),
			'userReports' => array(self::HAS_MANY, 'UserReports', 'users_id'),
			'useraccesses' => array(self::HAS_MANY, 'Useraccess', 'users_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'username' => 'Username',
			'password' => 'Password',
			'email' => 'Email',
			'level' => 'Level',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('level',$this->level);
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
	 * @return Users the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

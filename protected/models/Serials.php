<?php

/**
 * This is the model class for table "serials".
 *
 * The followings are the available columns in table 'serials':
 * @property integer $id
 * @property integer $customers_id
 * @property integer $project_id
 * @property integer $sku_id
 * @property string $code
 * @property string $asset
 * @property string $created
 *
 * The followings are the available model relations:
 * @property GrnSerials[] $grnSerials
 * @property PickSerials[] $pickSerials
 * @property SerialStock[] $serialStocks
 * @property Customers $customers
 * @property Project $project
 * @property Sku $sku
 */
class Serials extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'serials';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customers_id, project_id, sku_id, code, created', 'required'),
			array('customers_id, project_id, sku_id', 'numerical', 'integerOnly'=>true),
			array('code, asset', 'length', 'max'=>150),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, customers_id, project_id, sku_id, code, asset, created', 'safe', 'on'=>'search'),
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
			'grnSerials' => array(self::HAS_MANY, 'GrnSerials', 'serials_id'),
			'pickSerials' => array(self::HAS_MANY, 'PickSerials', 'serials_id'),
			'serialStocks' => array(self::HAS_MANY, 'SerialStock', 'serials_id'),
			'customers' => array(self::BELONGS_TO, 'Customers', 'customers_id'),
			'project' => array(self::BELONGS_TO, 'Project', 'project_id'),
			'sku' => array(self::BELONGS_TO, 'Sku', 'sku_id'),
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
			'project_id' => 'Project',
			'sku_id' => 'Sku',
			'code' => 'Code',
			'asset' => 'Asset',
			'created' => 'Created',
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
		$criteria->compare('project_id',$this->project_id);
		$criteria->compare('sku_id',$this->sku_id);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('asset',$this->asset,true);
		$criteria->compare('created',$this->created,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Serials the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

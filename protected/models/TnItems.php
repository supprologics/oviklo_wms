<?php

/**
 * This is the model class for table "tn_items".
 *
 * The followings are the available columns in table 'tn_items':
 * @property integer $id
 * @property integer $tn_id
 * @property integer $sku_id
 * @property string $batch_no
 * @property string $qty
 * @property string $sub_location
 * @property string $eff_date
 * @property string $expire_date
 * @property string $manf_date
 * @property string $remarks
 *
 * The followings are the available model relations:
 * @property Sku $sku
 * @property Tn $tn
 */
class TnItems extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tn_items';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tn_id, sku_id, qty', 'required'),
			array('tn_id, sku_id', 'numerical', 'integerOnly'=>true),
			array('batch_no, sub_location', 'length', 'max'=>45),
			array('qty', 'length', 'max'=>10),
			array('eff_date, expire_date, manf_date, remarks', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, tn_id, sku_id, batch_no, qty, sub_location, eff_date, expire_date, manf_date, remarks', 'safe', 'on'=>'search'),
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
			'sku' => array(self::BELONGS_TO, 'Sku', 'sku_id'),
			'tn' => array(self::BELONGS_TO, 'Tn', 'tn_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'tn_id' => 'Tn',
			'sku_id' => 'Sku',
			'batch_no' => 'Batch No',
			'qty' => 'Qty',
			'sub_location' => 'Sub Location',
			'eff_date' => 'Eff Date',
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
		$criteria->compare('tn_id',$this->tn_id);
		$criteria->compare('sku_id',$this->sku_id);
		$criteria->compare('batch_no',$this->batch_no,true);
		$criteria->compare('qty',$this->qty,true);
		$criteria->compare('sub_location',$this->sub_location,true);
		$criteria->compare('eff_date',$this->eff_date,true);
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
	 * @return TnItems the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

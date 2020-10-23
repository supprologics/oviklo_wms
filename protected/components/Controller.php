<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController {

    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout = '//layouts/default';
    public $breadcrumbs = array();

    public function returnCode($table, $pattern, $zeros = 5, $select = "code", $orderby = "id") {

        //1. Get Last Code From DB
        $lastCode = Yii::app()->db->createCommand("SELECT $select FROM $table WHERE code LIKE '$pattern%' ORDER BY $select DESC LIMIT 1 ")->queryAll();

        //2. Explode the Last Code if Existed and Assign new code
        if (count($lastCode) > 0) {
            $exploded = explode($pattern, $lastCode[0][$select]);
            if (count($exploded) <= 1) {
                $newcode = str_pad(1, $zeros, '0', STR_PAD_LEFT);
                $code = $pattern . $newcode;
            } else {
                $newcode = str_pad(intval($exploded[1]) + 1, $zeros, '0', STR_PAD_LEFT);
                $code = $pattern . $newcode;
            }
        } else {
            $newcode = str_pad(1, $zeros, '0', STR_PAD_LEFT);
            $code = $pattern . $newcode;
        }
        return $code;
    }

    public function returnCustomerList() {
        $users_id = Yii::app()->user->getId();


        //Block Customer View
        $list = UserHasCustomers::model()->findAllByAttributes(array("users_id" => $users_id, "online" => 1));
        $customer_block = "";
        foreach ($list as $value) {
            $customer_block .= $value->customers_id . ",";
        }
        return rtrim($customer_block, ",");
    }

    public function returnCustomerListForPick() {
        $users_id = Yii::app()->user->getId();


        //Block Customer View
        $list = UserHasCustomers::model()->findAllByAttributes(array("users_id" => $users_id, "online" => 1, "pick_" => 1));
        $customer_block = "";
        foreach ($list as $value) {
            $customer_block .= $value->customers_id . ",";
        }
        
        if (count($list) > 0) {
            return rtrim($customer_block, ",");
        }else{
            return '0';
        }
    }
    
    public function returnCustomerListForGdn() {
        $users_id = Yii::app()->user->getId();


        //Block Customer View
        $list = UserHasCustomers::model()->findAllByAttributes(array("users_id" => $users_id, "online" => 1, "gdn_" => 1));
        $customer_block = "";
        foreach ($list as $value) {
            $customer_block .= $value->customers_id . ",";
        }
        
        if (count($list) > 0) {
            return rtrim($customer_block, ",");
        }else{
            return '0';
        }
    }

    public function returnWarehouseList() {
        $users_id = Yii::app()->user->getId();
        //Block WH View
        $list = UserHasWarehouse::model()->findAllByAttributes(array("users_id" => $users_id, "online" => 1));
        $wh = "";
        foreach ($list as $value) {
            $wh .= $value->warehouse_id . ",";
        }
        return rtrim($wh, ",");
    }

}

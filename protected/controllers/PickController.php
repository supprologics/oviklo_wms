<?php

class PickController extends Controller {

    public $layout = '//layouts/default';

    public function filters() {
        return array(
            'accessControl - login', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    public function accessRules() {
        $deny = array();
        $accessArray = array("all");
        $denyarray = array();


        $user_id = Yii::app()->user->getState("userid");
        $access = Useraccess::model()->findByAttributes(array('users_id' => $user_id, 'access_id' => 5));

        if (isset($access)) {

            if ($access->view_ == 1) {
                $accessArray[] = 'index';
                $accessArray[] = 'view';
            }

            if ($access->create_ == 1) {
                $accessArray[] = 'create';
            }

            if ($access->update_ == 1) {
                $accessArray[] = 'update';
            }

            if ($access->delete_ == 1) {
                $accessArray[] = 'delete';
            }


            $access = array('allow',
                'actions' => $accessArray,
                'users' => array('@')
            );
        }

        return array(
            $access,
            array('deny',
                'actions' => array('create', 'update', 'delete', 'index', 'view'),
                'users' => array('*')
            )
        );
    }

    public function returnMsg($msg, $status, $hide = 0, $id = "") {
        $result = array(
            'msg' => $msg,
            'sts' => $status,
            'hide' => $hide,
            'id' => $id
        );
        echo json_encode($result);
    }

    public function actionupdateAll($id) {
        try {


            $model = Mr::model()->findByPk($id);

            $model->attributes = $_POST;
            $model->last_update = date("Y-m-d H:i:s");
            $model->save();


            foreach ($_POST['qty'] as $key => $value) {

                $poitems = PickItems::model()->findByAttributes(array("id" => $key));

                if ($value > $poitems->qty_req) {
                    throw new Exception("Invalid Action, You can't Exceed the Pick Request Limit");
                }

                $poitems->qty = $value;
                $poitems->last_update = date("Y-m-d H:i:s");


                $inQTY = Yii::app()->db->createCommand("SELECT COUNT(id) as tot FROM pick_serials WHERE pick_items_id = '$key'")->queryAll();
                if ($value < $inQTY[0]['tot']) {
                    throw new Exception("Invalid Action, Remove SERIAL NUMBERS FIRST");
                }



                if (isset($_POST["remarks_item"][$key])) {
                    $poitems->remarks = $_POST["remarks_item"][$key];
                }

                if (!$poitems->save()) {
                    $er = $poitems->getErrors();
                    $err_txt = "";
                    foreach ($er as $key => $value) {
                        $lebel = $poitems->getAttributeLabel($key);
                        $err_txt .= $lebel . " : " . $value[0] . "<br/>";
                    }
                    throw new Exception($err_txt);
                }
            }

            $this->returnMsg("Successfully Updated", 1, 0);
        } catch (Exception $exc) {
            $this->returnMsg($exc->getMessage(), 0, 1);
        }
    }

    public function loadModel($id) {
        $model = Mr::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    public function actionDelete($id) {
        try {

            $model = PickItems::model()->findByPk($id);
            $model->qty = 0;
            if (!$model->save()) {
                $er = $model->getErrors();
                $err_txt = "";
                foreach ($er as $key => $value) {
                    $lebel = $model->getAttributeLabel($key);
                    $err_txt .= $lebel . " : " . $value[0] . "<br/>";
                }
                throw new Exception($err_txt);
            }

            $this->returnMsg("Successfully Updated", 1, 0);
        } catch (Exception $exc) {
            $this->returnMsg($exc->getMessage(), 0, 1);
        }
    }

    public function actionIndex() {

        //Handle Search Values
        if (empty($_GET['val'])) {
            $searchtxt = "";
        } else {
            $searchtxt = " AND name LIKE '%" . $_GET['val'] . "%' ";
        }

        if (empty($_GET['pages'])) {
            $pages = 50;
        } else {
            $pages = $_GET['pages'];
        }

        $user_id = Yii::app()->user->getState("userid");
        $cus_keys = $this->returnCustomerListForPick();
        $wh_keys = $this->returnWarehouseList();

        $sql = "SELECT * FROM mr WHERE IF(online = 3, picked_id = '$user_id', picked_id != 0) AND online >= 2 AND online <= 3 AND customers_id IN ($cus_keys) AND warehouse_id IN ($wh_keys) $searchtxt ORDER BY id DESC ";
        $count = Yii::app()->db->createCommand($sql)->query()->rowCount;
        $dataProvider = new CSqlDataProvider($sql, array(
            'totalItemCount' => $count,
            'pagination' => array(
                'pageSize' => $pages
            ),
                )
        );

        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionPrint($id) {
        $this->renderPartial('print', array(
            'model' => $this->loadModel($id)
        ));
    }

    public function actionView($id) {
        $sql = "SELECT id FROM mr WHERE id = $id ORDER BY id DESC ";
        $count = Yii::app()->db->createCommand($sql)->query()->rowCount;
        $dataProvider = new CSqlDataProvider($sql, array(
            'totalItemCount' => $count,
            'pagination' => array(
                'pageSize' => 1
            ),
                )
        );


        if (!empty($_GET['pick_items_id'])) {
            $grnItemsId = "," . $_GET['pick_items_id'] . " as pick_items_id";
        } else {
            $grnItemsId = "";
        }

        $sql = "SELECT id $grnItemsId FROM mr WHERE id = $id ORDER BY id DESC ";
        $count = Yii::app()->db->createCommand($sql)->query()->rowCount;
        $dataProviderForSerials = new CSqlDataProvider($sql, array(
            'totalItemCount' => $count,
            'pagination' => array(
                'pageSize' => 100
            ),
                )
        );


        $this->render('view', array(
            'model' => $this->loadModel($id),
            'dataProvider' => $dataProvider,
            'dataProviderForSerials' => $dataProviderForSerials,
        ));
    }

    public function actionProcess($id) {

        Yii::app()->db->createCommand("DELETE FROM pick_items WHERE mr_id = $id")->execute();
        $model = $this->loadModel($id);

        //Item Picking
        $list = Yii::app()->db->createCommand("SELECT id FROM mr_items WHERE mr_id = $id")->queryAll();
        foreach ($list as $value) {

            $mrItems = MrItems::model()->findByPk($value['id']);

            $sku_id = $mrItems->sku_id;
            $batch_no = $mrItems->batch_no;
            $goods_sts_id = $mrItems->goods_sts_id;
            $project_id = $mrItems->mr->project_id;
            $wh_id = $mrItems->mr->warehouse_id;

            $stocklot = Yii::app()->db->createCommand("SELECT id,SUM(qty) as tot,"
                            . "pkg_no,locations_id,sub_location,batch_no,manf_date,"
                            . "goods_sts_id,expire_date FROM stock "
                            . "WHERE sku_id = '$sku_id' AND "
                            . "warehouse_id = '$wh_id' AND "
                            . "batch_no = '$batch_no' AND "
                            . "project_id = '$project_id' AND "
                            . "goods_sts_id = '$goods_sts_id' AND tbl_name IN ('grn','pick','tn','mr') AND IF(tbl_name = 'mr',online IN (1,2),online >= 1) "
                            . "GROUP BY batch_no,expire_date,locations_id,sub_location HAVING locations_id IS NOT NULL ORDER BY expire_date ASC")->queryAll();

            $balance = $mrItems->qty;
            foreach ($stocklot as $value) {

                if ($balance >= $value['tot']) {
                    $qty = $value['tot'];
                } else {
                    $qty = $balance;
                }

                if ($qty <= 0) {
                    continue;
                }

                $pick = PickItems::model()->findByAttributes(
                        array(
                            "mr_id" => $id,
                            "mr_items_id" => $mrItems->id,
                            "sku_id" => $sku_id,
                            "pkg_no" => $value['pkg_no'],
                            "batch_no" => $value['batch_no'],
                            "goods_sts_id" => $value['goods_sts_id'],
                            "expire_date" => $value['expire_date'],
                            "manf_date" => $value['manf_date'],
                            "qty" => $qty,
                            "locations_id" => $value['locations_id'],
                            "sub_location" => $value['sub_location']
                        )
                );
                if ($pick == null) {
                    $pick = new PickItems();
                }


                $pick->mr_id = $id;
                $pick->mr_items_id = $mrItems->id;
                $pick->sku_id = $sku_id;
                $pick->pkg_no = $value['pkg_no'];
                $pick->batch_no = $value['batch_no'];
                $pick->goods_sts_id = $value['goods_sts_id'];
                $pick->expire_date = $value['expire_date'];
                $pick->manf_date = $value['manf_date'];
                $pick->qty = $qty;
                $pick->qty_req = $qty;
                $pick->locations_id = $value['locations_id'];
                $pick->sub_location = $value['sub_location'];
                $pick->created = date("Y-m-d H:i:s");
                $pick->last_update = date("Y-m-d H:i:s");
                
                if(!$pick->Save()){
                    continue;
                } 
                
                $balance = $balance - $qty;
            }
        }

        $model->online = 3;
        $model->picked_id = Yii::app()->user->getId();
        $model->save();

        $this->renderPartial('print', array(
            'model' => $this->loadModel($id)
        ));
    }

}

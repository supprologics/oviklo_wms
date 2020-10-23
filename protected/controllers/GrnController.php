<?php

class GrnController extends Controller {

    public $layout = '//layouts/default';

    /**
     * @return array action filters
     */
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
        $access = Useraccess::model()->findByAttributes(array('users_id' => $user_id, 'access_id' => 3));



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

    public function actionjsondata($id) {
        $data = Grn::model()->findByPk($id);
        $output = CJSON::encode($data);
        echo $output;
    }

    public function actionloadSuppliers() {
        $term = $_GET['term'];
        $list = Yii::app()->db->createCommand("SELECT supplier FROM grn WHERE supplier LIKE '%$term%' GROUP BY supplier ")->queryAll();
        $json = array();

        $id = 1;
        foreach ($list as $value) {
            $json[] = array(
                'id' => $id,
                'value' => $value['supplier'],
            );

            $id += 1;
        }
        echo json_encode($json);
    }

    public function actionSerial($id) {

        if (!empty($_GET['grn_items_id'])) {
            $grnItemsId = ",". $_GET['grn_items_id'] ." as grn_items_id";
        } else {
            $grnItemsId = "";
        }
        $sql = "SELECT id $grnItemsId FROM grn WHERE id = $id ORDER BY id DESC ";
        $count = Yii::app()->db->createCommand($sql)->query()->rowCount;
        $dataProvider = new CSqlDataProvider($sql, array(
            'totalItemCount' => $count,
            'pagination' => array(
                'pageSize' => 10
            ),
                )
        );

        $this->render('view_serials', array(
            'model' => $this->loadModel($id),
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionView($id) {


        $sql = "SELECT id FROM grn WHERE id = $id ORDER BY id DESC ";
        $count = Yii::app()->db->createCommand($sql)->query()->rowCount;
        $dataProvider = new CSqlDataProvider($sql, array(
            'totalItemCount' => $count,
            'pagination' => array(
                'pageSize' => 1
            ),
                )
        );

        $this->render('view', array(
            'model' => $this->loadModel($id),
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionTally($id) {


        $sql = "SELECT id FROM grn WHERE id = $id ORDER BY id DESC ";
        $count = Yii::app()->db->createCommand($sql)->query()->rowCount;
        $dataProvider = new CSqlDataProvider($sql, array(
            'totalItemCount' => $count,
            'pagination' => array(
                'pageSize' => 1
            ),
                )
        );

        $this->render('view_tally', array(
            'model' => $this->loadModel($id),
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionCreate() {
        try {

            $model = new Grn;

            $model->attributes = $_POST;
            $model->created = date("Y-m-d H:i:s");
            $model->last_update = date("Y-m-d H:i:s");
            $model->users_id = Yii::app()->user->getId();
            $model->code = $this->returnCode("grn", "GRN-" . $model->customers->code . "-");


            if (!$model->save()) {

                $er = $model->getErrors();
                $err_txt = "";
                foreach ($er as $key => $value) {
                    $lebel = $model->getAttributeLabel($key);
                    $err_txt .= $lebel . " : " . $value[0] . "<br/>";
                }
                throw new Exception($err_txt);
            }

            $this->returnMsg("Successfully Updated", 1, 0, $model->id);
        } catch (Exception $exc) {
            $this->returnMsg($exc->getMessage(), 0, 1);
        }
    }

    public function actionPrint($id) {
        $this->renderPartial('print', array(
            'model' => $this->loadModel($id)
        ));
    }
    
    public function actionSerialsprint($id) {
        $this->renderPartial('print_with_serials', array(
            'model' => $this->loadModel($id)
        ));
    }

    public function actiontallyprint($id) {
        $this->renderPartial('tallyprint', array(
            'model' => $this->loadModel($id)
        ));
    }

    public function actionUpdate($id) {
        try {

            $model = $this->loadModel($id);

            $model->attributes = $_POST;
            $model->last_update = date("Y-m-d H:i:s");

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

    public function actionDelete($id) {
        try {

            $model = $this->loadModel($id);
            $model->online = 9;
            $model->last_update = date("Y-m-d H:i:s");

            if ($model->save()) {
                $this->returnMsg("Successfully Deleted", 1, 0);
            } else {
                $this->returnMsg("Error Occured", 0, 1);
            }
        } catch (Exception $exc) {
            $this->returnMsg($exc->getMessage(), 0, 1);
        }
    }

    public function actionIndex() {

        //Handle Search Values
        if (empty($_GET['val'])) {
            $searchtxt = "";
        } else {
            $searchtxt = " AND code LIKE '%" . $_GET['val'] . "%' ";
        }

        if (empty($_GET['customers_id'])) {
            $customers = "";
        } else {
            $customers = " AND customers_id = '" . $_GET['customers_id'] . "' ";
        }

        if (empty($_GET['warehouse_id'])) {
            $warehouse = "";
        } else {
            $warehouse = " AND warehouse_id = '" . $_GET['warehouse_id'] . "' ";
        }

        if (empty($_GET['project_id'])) {
            $project = "";
        } else {
            $project = " AND project_id = '" . $_GET['project_id'] . "' ";
        }

        if (empty($_GET['date_from'])) {
            $date_from = "";
        } else {
            $date_from = " AND eff_date >= '" . $_GET['date_from'] . "' ";
        }

        if (empty($_GET['date_to'])) {
            $date_to = "";
        } else {
            $date_to = " AND eff_date <= '" . $_GET['date_to'] . "' ";
        }


        if (empty($_GET['vehicle_no'])) {
            $vehicle_no = "";
        } else {
            $vehicle_no = " AND vehicle_no LIKE '%" . $_GET['vehicle_no'] . "%' ";
        }
        if (empty($_GET['packinglist_no'])) {
            $packinglist_no = "";
        } else {
            $packinglist_no = " AND packinglist_no LIKE '%" . $_GET['packinglist_no'] . "%' ";
        }

        if (empty($_GET['ref_no'])) {
            $ref_no = "";
        } else {
            $ref_no = " AND ref_no LIKE '%" . $_GET['ref_no'] . "%' ";
        }


        if (empty($_GET['pages'])) {
            $pages = 50;
        } else {
            $pages = $_GET['pages'];
        }

        $user_id = Yii::app()->user->getState("userid");
        $cus_keys = $this->returnCustomerList();
        $wh_keys = $this->returnWarehouseList();
        $sql = "SELECT * FROM grn WHERE IF(online = 1, users_id = '$user_id', users_id != 0) AND online >= 1 AND "
                . "customers_id IN ($cus_keys) AND "
                . "warehouse_id IN ($wh_keys) "
                . "$searchtxt $customers $warehouse $project $date_from $date_to $vehicle_no $packinglist_no $ref_no "
                . "ORDER BY id DESC ";

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

    public function actionTallylist() {

        //Handle Search Values
        if (empty($_GET['val'])) {
            $searchtxt = "";
        } else {
            $searchtxt = " AND code LIKE '%" . $_GET['val'] . "%' ";
        }

        if (empty($_GET['customers_id'])) {
            $customers = "";
        } else {
            $customers = " AND customers_id = '" . $_GET['customers_id'] . "' ";
        }

        if (empty($_GET['warehouse_id'])) {
            $warehouse = "";
        } else {
            $warehouse = " AND warehouse_id = '" . $_GET['warehouse_id'] . "' ";
        }

        if (empty($_GET['project_id'])) {
            $project = "";
        } else {
            $project = " AND project_id = '" . $_GET['project_id'] . "' ";
        }

        if (empty($_GET['date_from'])) {
            $date_from = "";
        } else {
            $date_from = " AND eff_date >= '" . $_GET['date_from'] . "' ";
        }

        if (empty($_GET['date_to'])) {
            $date_to = "";
        } else {
            $date_to = " AND eff_date <= '" . $_GET['date_to'] . "' ";
        }


        if (empty($_GET['vehicle_no'])) {
            $vehicle_no = "";
        } else {
            $vehicle_no = " AND vehicle_no LIKE '%" . $_GET['vehicle_no'] . "%' ";
        }
        if (empty($_GET['packinglist_no'])) {
            $packinglist_no = "";
        } else {
            $packinglist_no = " AND packinglist_no LIKE '%" . $_GET['packinglist_no'] . "%' ";
        }

        if (empty($_GET['ref_no'])) {
            $ref_no = "";
        } else {
            $ref_no = " AND ref_no LIKE '%" . $_GET['ref_no'] . "%' ";
        }


        if (empty($_GET['pages'])) {
            $pages = 50;
        } else {
            $pages = $_GET['pages'];
        }

        $user_id = Yii::app()->user->getState("userid");
        $cus_keys = $this->returnCustomerList();
        $wh_keys = $this->returnWarehouseList();
        $sql = "SELECT * FROM grn WHERE IF( online = 1, users_id = '$user_id', users_id != 0) AND online = 2 AND "
                . "customers_id IN ($cus_keys) AND "
                . "warehouse_id IN ($wh_keys) "
                . "$searchtxt $customers $warehouse $project $date_from $date_to $vehicle_no $packinglist_no $ref_no "
                . "ORDER BY id DESC ";

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

    public function loadModel($id) {
        $model = Grn::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'grn-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}

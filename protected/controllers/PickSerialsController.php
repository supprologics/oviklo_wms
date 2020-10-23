<?php

class PickSerialsController extends Controller {

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
        $access = Useraccess::model()->findByAttributes(array('users_id' => $user_id, 'access_id' => 1));

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
        $data = PickSerials::model()->findByPk($id);
        $output = CJSON::encode($data);
        echo $output;
    }

    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    function insertSerial($pick_items_id, $code) {

        $pickItems = PickItems::model()->findByPk($pick_items_id);
        
        
        //REMOVE AND project_id = '" . $pickItems->mr->project_id . "' BCZ No Need to Check Project or Batch for Erricson
        $chkStatus = Yii::app()->db->createCommand("SELECT entry FROM `serial_stock` where `code` = '$code' AND customers_id = '" . $pickItems->mr->customers_id . "'  AND sku_id = '" . $pickItems->sku_id . "' ORDER BY created DESC LIMIT 1")->queryAll();
        
        
        
        if (isset($chkStatus[0]['entry']) && $chkStatus[0]['entry'] == 'OUT') {
            throw new Exception("INVALID STATUS, YOUR SERIAL ALREADY DESPATCHED");
        }


        $serial = Serials::model()->findByAttributes(array("code" => $code, "customers_id" => $pickItems->mr->customers_id, "sku_id" => $pickItems->sku_id));
        if ($serial == null) {
            throw new Exception("Invalid SERIAL CODE !, Please check the number");
        }

        $inQTY = Yii::app()->db->createCommand("SELECT COUNT(id) as tot FROM pick_serials WHERE pick_items_id = '$pick_items_id'")->queryAll();
        if ($pickItems->qty <= $inQTY[0]['tot']) {
            throw new Exception("You have Exceeded the MAX LIMIT (" . $inQTY[0]['tot'] . ") ");
        }

        $model = new PickSerials;

        $model->pick_items_id = $pick_items_id;
        $model->serials_id = $serial->id;
        $model->created = date("Y-m-d H:i:s");

        if (!$model->save()) {

            $er = $model->getErrors();
            $err_txt = "";
            foreach ($er as $key => $value) {
                $lebel = $model->getAttributeLabel($key);
                $err_txt .= $lebel . " : " . $value[0] . "<br/>";
            }
            throw new Exception($err_txt);
        }
    }

    public function actionCreate() {
        try {
            $this->insertSerial($_POST['pick_items_id'], $_POST['code']);
            $this->returnMsg("Successfully Updated", 1, 0);
        } catch (Exception $exc) {
            $this->returnMsg($exc->getMessage(), 0, 1);
        }
    }

    public function actioncsvupload() {
        try {
            ini_set('auto_detect_line_endings', true);
            $header = 0;

            $tmp_file = $_FILES['csvfile']['tmp_name'];
            $fileHandler = fopen($tmp_file, 'r');

            $num = 0;
            $err = 0;
            $errorArray = array();

            $pickItems = PickItems::model()->findByPk($_POST['pick_items_id']);
            $inQTY = Yii::app()->db->createCommand("SELECT COUNT(id) as tot FROM pick_serials WHERE pick_items_id = '" . $_POST['pick_items_id'] . "'")->queryAll();
            $in_qty_cnt = $inQTY[0]['tot'];


            if ($pickItems->qty <= $inQTY[0]['tot']) {
                throw new Exception("You have Exceeded the MAX LIMIT (" . $inQTY[0]['tot'] . ") ");
            }

            if ($fileHandler) {
                while ($line = fgetcsv($fileHandler, 1000000, ",")) {
                    if ($header > 0) {
                        $num += 1;

                        if ($pickItems->qty < $in_qty_cnt) {
                            throw new Exception("You have Exceeded the MAX LIMIT");
                        }
                        $this->insertSerial($_POST['pick_items_id'], $line[0]);
                    }

                    $header += 1;
                    $in_qty_cnt += 1;
                }
            }

            $this->returnMsg("Successfully Updated", 1, 0);
        } catch (Exception $exc) {
            $this->returnMsg($exc->getMessage(), 0, 1);
        }
    }

    public function actionUpdate($id) {
        try {

            $model = $this->loadModel($id);

            $model->attributes = $_POST;

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
            if ($this->loadModel($id)->delete()) {
                $this->returnMsg("Successfully Deleted", 1, 0);
            } else {
                $this->returnMsg("Error Occured", 0, 1);
            }
        } catch (CDbException $exc) {
            $this->returnMsg($exc->getMessage(), 0, 1);
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


        $sql = "SELECT * FROM pick_serials WHERE online = 1 $searchtxt ORDER BY id DESC ";
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
        $model = PickSerials::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'pick-serials-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}

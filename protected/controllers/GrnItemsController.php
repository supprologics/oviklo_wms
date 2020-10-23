<?php

class GrnItemsController extends Controller {

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

    public function actioncsvupload() {
        try {
            
            ini_set('max_execution_time',0);
            ini_set('memory_limit', -1);

            ini_set('auto_detect_line_endings', true);
            $header = 0;

            $tmp_file = $_FILES['csvfile']['tmp_name'];
            $fileHandler = fopen($tmp_file, 'r');

            $num = 0;
            $err = 0;
            $errorArray = array();
            if ($fileHandler) {
                while ($line = fgetcsv($fileHandler, 1000000, ",")) {
                    if ($header > 0) {
                        $num += 1;

                        $cost = new GrnItems();

                        $cost->grn_id = $_POST['grn_id'];

                        //FIND SKU
                        $sku = Sku::model()->findByAttributes(array("code" => trim($line[0]),"customers_id" => $cost->grn->customers_id));
                        if ($sku == null) {
                            $err += 1;
                            $errorArray[] = array("code" => $line[0], "batch" => $line[1], "roll" => $line[2], "error" => "Invalid SKU CODE");
                            continue;
                        }

                        $cost->goods_sts_id = $_POST['goods_sts_id'];
                        $cost->sku_id = $sku->id;
                        $cost->code = $this->returnCode("grn_items", "B", 10);
                        $cost->batch_no = trim($line[1]);
                        $cost->pkg_no = trim($line[2]);
                        $cost->qty = trim($line[3]);
                        $cost->expire_date = trim($line[4]);
                        $cost->manf_date = trim($line[5]);
                        $cost->remarks = trim($line[6]);
                        $cost->created = date("Y-m-d H:i:s");

                        if (empty($cost->expire_date)) {
                            $cost->expire_date = null;
                        } else {
                            if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $line[4])) {
                                $err += 1;
                                $errorArray[] = array("code" => $line[0], "batch" => $line[1], "roll" => $line[2], "error" => "EXIRE DATE NOT IN FORMAT( YYYY-MM-DD )");
                                continue;
                            }
                        }
                        if (empty($cost->manf_date)) {
                            $cost->manf_date = null;
                        } else {
                            if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $line[5])) {
                                $err += 1;
                                $errorArray[] = array("code" => $line[0], "batch" => $line[1], "roll" => $line[2], "error" => "MANUFACTURE DATE NOT IN FORMAT ( YYYY-MM-DD )");
                                continue;
                            }
                        }

                        if (!$cost->save()) {
                            $err += 1;
                            $er = $cost->getErrors();
                            $err_txt = "";
                            foreach ($er as $key => $value) {
                                $lebel = $cost->getAttributeLabel($key);
                                $err_txt .= $lebel . " : " . $value[0] . "<br/>";
                            }
                            $errorArray[] = array("batch" => $line[0], "roll" => $line[1], "error" => $err_txt);
                            continue;
                        }
                    }

                    $header += 1;
                }
            }


            $success = $num - $err;

            $result = array(
                'msg' => "Successfully Uploaded,  All Records ($success), Errors ($err)",
                'sts' => 1,
                'hide' => 1,
                'er' => $errorArray
            );

            echo json_encode($result);
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    public function actionjsondata($id) {
        $data = GrnItems::model()->findByPk($id);
        $output = CJSON::encode($data);
        echo $output;
    }

    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    public function actionupdateAll($id) {
        try {

            $model = Grn::model()->findByPk($id);

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

            //Update PO Items
            foreach ($_POST['qty'] as $key => $value) {

                $poitems = GrnItems::model()->findByAttributes(array("id" => $key));
                $poitems->qty = $value;

                if (isset($_POST["batch_no"][$key])) {
                    $poitems->batch_no = $_POST["batch_no"][$key];
                }

                if (isset($_POST["pkg_no"][$key])) {
                    $poitems->pkg_no = $_POST["pkg_no"][$key];
                }

                if (isset($_POST["goods_sts_id"][$key])) {
                    $poitems->goods_sts_id = $_POST["goods_sts_id"][$key];
                }

                if (isset($_POST["expire_date"][$key])) {
                    $poitems->expire_date = $_POST["expire_date"][$key];
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

    public function actionupdateTally($id) {
        try {

            $model = Grn::model()->findByPk($id);
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

            //Update PO Items
            foreach ($_POST["locations_id"] as $key => $value) {

                $poitems = GrnItems::model()->findByAttributes(array("id" => $key));

                if (isset($_POST["locations_id"][$key])) {
                    $poitems->locations_id = $_POST["locations_id"][$key];
                }

                if (isset($_POST["sub_location"][$key])) {
                    $poitems->sub_location = $_POST["sub_location"][$key];
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

    public function actionCreate() {
        try {

            $model = new GrnItems;

            $model->attributes = $_POST;
            $model->created = date("Y-m-d H:i:s");
            $model->code = $this->returnCode("grn_items", "B", 10);

            if (empty($_POST['expire_date'])) {
                $model->expire_date = null;
            }

            if (empty($_POST['manf_date'])) {
                $model->manf_date = null;
            }


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


        $sql = "SELECT * FROM grn_items WHERE online = 1 $searchtxt ORDER BY id DESC ";
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
        $model = GrnItems::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'grn-items-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}

<?php

class SerialsController extends Controller {

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

    public function actioncsvupload() {
        try {

            ini_set('auto_detect_line_endings', true);
            $header = 0;

            $tmp_file = $_FILES['csvfile']['tmp_name'];
            $fileHandler = fopen($tmp_file, 'r');

            $num = 0;
            $err = 0;
            $errorArray = array();

            $grnItems = GrnItems::model()->findByPk($_POST['grn_items_id']);
            $inQTY = Yii::app()->db->createCommand("SELECT COUNT(id) as tot FROM grn_serials WHERE grn_items_id = '" . $_POST['grn_items_id'] . "'")->queryAll();
            $in_qty_cnt = $inQTY[0]['tot'];
            if ($grnItems->qty <= $inQTY[0]['tot']) {
                throw new Exception("You have Exceeded the MAX LIMIT (" . $inQTY[0]['tot'] . ") ");
            }


            if ($fileHandler) {
                while ($line = fgetcsv($fileHandler, 1000000, ",")) {
                    if ($header > 0) {
                        $num += 1;

                        if ($grnItems->qty < $in_qty_cnt) {
                            throw new Exception("You have Exceeded the MAX LIMIT");
                        }


                        $cost = Serials::model()->findByAttributes(array("code" => $line[0], "customers_id" => $grnItems->grn->customers_id, "sku_id" => $grnItems->sku_id));
                        if ($cost == null) {
                            $cost = new Serials();
                        }

                        $cost->customers_id = $grnItems->grn->customers_id;
                        $cost->project_id = $grnItems->grn->project_id;
                        $cost->sku_id = $grnItems->sku_id;
                        $cost->code = $line[0];
                        $cost->asset = $line[1];
                        $cost->created = date("Y-m-d H:i:s");

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

                        //UPDATE GRN IN
                        $data = GrnSerials::model()->findByAttributes(array("serials_id" => $cost->id, "grn_items_id" => $grnItems->id));
                        if ($data == null) {
                            $data = new GrnSerials();
                        }

                        $data->serials_id = $cost->id;
                        $data->grn_items_id = $grnItems->id;
                        $data->created = date("Y-m-d H:i:s");
                        if (!$data->save()) {
                            $er = $data->getErrors();
                            $err_txt = "";
                            foreach ($er as $key => $value) {
                                $lebel = $data->getAttributeLabel($key);
                                $err_txt .= $lebel . " : " . $value[0] . "<br/>";
                            }
                            $errorArray[] = array("batch" => $line[0], "roll" => $line[1], "error" => $err_txt);
                            continue;
                        }
                    }

                    $header += 1;
                    $in_qty_cnt += 1;
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
            $result = array(
                'msg' => $exc->getMessage(),
                'sts' => 1,
                'hide' => 1,
                'er' => array("batch" => "ALL", "roll" => "AA", "error" => "ERROR")
            );
            echo json_encode($result);
        }
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
        $data = Serials::model()->findByPk($id);
        $output = CJSON::encode($data);
        echo $output;
    }

    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    public function actionCreate() {
        try {

            $grnItems = GrnItems::model()->findByPk($_POST['grn_items_id']);
            
            
            $chkStatus = Yii::app()->db->createCommand("SELECT entry FROM `serial_stock` where `code` = '". $_POST['code'] ."' AND customers_id = '". $grnItems->grn->customers_id ."' AND project_id = '". $grnItems->grn->project_id ."' AND sku_id = '". $grnItems->sku_id ."' ORDER BY created DESC LIMIT 1")->queryAll();
            if(isset($chkStatus[0]['entry']) && $chkStatus[0]['entry'] == 'IN'){
                throw new Exception("INVALID STATUS, YOUR SERIAL ALREADY STOCKED IN WAREHOUSE");
            }            

            $inQTY = Yii::app()->db->createCommand("SELECT COUNT(id) as tot FROM grn_serials WHERE grn_items_id = '" . $_POST['grn_items_id'] . "'")->queryAll();
            if ($grnItems->qty <= $inQTY[0]['tot']) {
                throw new Exception("You have Exceeded the MAX LIMIT (" . $inQTY[0]['tot'] . ") ");
            }


            $model = Serials::model()->findByAttributes(array("code" => $_POST['code'], "customers_id" => $grnItems->grn->customers_id, "sku_id" => $grnItems->sku_id));
            if ($model == null) {
                $model = new Serials;
            }

            $model->attributes = $_POST;
            $model->sku_id = $grnItems->sku_id;
            $model->customers_id = $grnItems->grn->customers_id;
            $model->project_id = $grnItems->grn->project_id;
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


            //UPDATE GRN IN

            $data = GrnSerials::model()->findByAttributes(array("serials_id" => $model->id, "grn_items_id" => $grnItems->id));
            if ($data == null) {
                $data = new GrnSerials();
            }

            $data->serials_id = $model->id;
            $data->grn_items_id = $grnItems->id;
            $data->created = date("Y-m-d H:i:s");
            if (!$data->save()) {
                $er = $data->getErrors();
                $err_txt = "";
                foreach ($er as $key => $value) {
                    $lebel = $data->getAttributeLabel($key);
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

            $model = GrnSerials::model()->findByPk($id);

            if ($model->delete()) {
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


        $sql = "SELECT * FROM serials WHERE online = 1 $searchtxt ORDER BY id DESC ";
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
        $model = Serials::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'serials-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}

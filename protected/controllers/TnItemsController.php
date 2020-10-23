<?php

class TnItemsController extends Controller {

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
        $data = TnItems::model()->findByPk($id);
        $output = CJSON::encode($data);
        echo $output;
    }

    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
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
            
            $mr = Tn::model()->findByPk($_POST['tn_id']);
            
            if ($fileHandler) {
                while ($line = fgetcsv($fileHandler, 1000000, ",")) {
                    if ($header > 0) {
                        $num += 1;
                        
                        $sku = Sku::model()->findByAttributes(array("code" => trim($line[0]),"customers_id" => $mr->customers_id, ));
                        if ($sku == null) {
                            $err += 1;
                            $errorArray[] = array("code" => $line[0], "error" => "Invalid SKU CODE");
                            continue;
                        }
                        
                        $goodSts = GoodsSts::model()->findByAttributes(array("name" => trim($line[2])));
                        if ($goodSts == null) {
                            $err += 1;
                            $errorArray[] = array("code" => $line[0], "error" => "Invalid SKU STATUS NAME [". $line[2] ."]");
                            continue;
                        }
                        
                        $result = $this->bulkInventoryIn($_POST['tn_id'], $sku->id, $mr->goods_sts_from, $line[3],$line[4],$line[1]);
                        if(!empty($result)){                            
                            $err += 1;
                            $errorArray[] = array("code" => $line[0], "error" => $result);
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
    
    public function bulkInventoryIn($mr_id,$sku_id,$goods_sts_id,$qty,$remarks = '',$batch='') {
        
        $mr = Tn::model()->findByPk($mr_id);
        $sku_id = $sku_id;
        $goods_sts_id = $goods_sts_id;
        
        
        if(!empty($batch)){
            $batch_q = " AND batch_no = '$batch'";
        }else{
            $batch_q = "";
        }

        $list = Yii::app()->db->createCommand("SELECT SUM(qty) as tot,expire_date,batch_no,grn_id FROM `stock` "
                        . "WHERE sku_id = $sku_id AND "
                        . "project_id = '" . $mr->project_from . "' AND "
                        . "warehouse_id = '" . $mr->warehouse_from . "' AND "
                        . "goods_sts_id = $goods_sts_id AND IF(tbl_name = 'mr',online = 1,online >= 1) $batch_q "
                        . "GROUP BY batch_no,expire_date HAVING tot > 0  "
                        . "ORDER BY expire_date ASC ")->queryAll();
                
      
        
        
        if(count($list) <= 0){
            return "NO STOCK";
        }

        $req_qty = $qty;
        foreach ($list as $value) {

            if ($req_qty <= 0) {
                continue;
            }

            $model = new TnItems;

            $model->tn_id = $mr->id;
            $model->sku_id = $sku_id;
            $model->batch_no = $batch;
            $model->attributes = $_POST;

            if (empty($_POST['expire_date'])) {
                $model->expire_date = null;
            } else {
                $model->expire_date = $value['expire_date'];
            }

            if ($req_qty <= $value['tot']) {
                $model->qty = $req_qty;
                $req_qty = 0;
            } else {                
                return "No AVAILABLE STOCK FOR THE REQUIREMENT[$req_qty].   AVL QTY : ".$value['tot'] ." ONLY"; 
            }

            if (!$model->save()) {

                $er = $model->getErrors();
                $err_txt = "";
                foreach ($er as $key => $value) {
                    $lebel = $model->getAttributeLabel($key);
                    $err_txt .= $lebel . " : " . $value[0] . "<br/>";
                }
                return $err_txt;
            }
        }
        return 0;
    }
    
    public function bulkInventoryIn_old() {
        $tn = Tn::model()->findByPk($_POST['tn_id']);
        $sku_id = $_POST['sku_id'];
        $goods_sts_id = $tn->goods_sts_from;

        $list = Yii::app()->db->createCommand("SELECT SUM(qty) as tot,expire_date,batch_no FROM `stock` "
                        . "WHERE sku_id = $sku_id AND "
                        . "project_id = '" . $tn->project_from . "' AND "
                        . "warehouse_id = '" . $tn->warehouse_from . "' AND "
                        . "sub_location = '". $tn->sub_location_from ."' AND "
                        . "goods_sts_id = $goods_sts_id AND tbl_name IN ('grn','pick','tn') "
                        . "GROUP BY batch_no,expire_date HAVING tot > 0  "
                        . "ORDER BY expire_date ASC ")->queryAll();


        $req_qty = $_POST['qty_bulk'];
        foreach ($list as $value) {

            if ($req_qty <= 0) {
                continue;
            }

            $model = new TnItems();

            $model->tn_id = $tn->id;
            $model->sku_id = $sku_id;
            $model->batch_no = $value['batch_no'];
            $model->sub_location = $tn->sub_location;

            if (empty($_POST['expire_date'])) {
                $model->expire_date = null;
            } else {
                $model->expire_date = $value['expire_date'];
            }

            if ($req_qty <= $value['tot']) {
                $model->qty = $req_qty;
                $req_qty = 0;
            } else {
                $model->qty = $value['tot'];
                $req_qty = $req_qty - $model->qty;
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
        }
    }

    public function actionCreate() {
        try {

            if ($_POST['qty_bulk'] > 0) {
                $this->bulkInventoryIn($_POST['mr_id'],$_POST['sku_id'],$_POST['goods_sts_id'],$_POST['qty_bulk']);
            } else {
                foreach ($_POST['qty'] as $key => $value) {

                    if ($value > 0) {
                        $model = new TnItems();

                        if (empty($key)) {
                            $batch_no = "";
                        } else {
                            $batch_no = $_POST['batch_no'][$key];
                        }


                        $model->attributes = $_POST;
                        $model->qty = $value;
                        $model->batch_no = $batch_no;
                        $model->expire_date = $_POST['expire_date'][$key];
                        $model->eff_date = $_POST['eff_date'][$key];
                        $model->manf_date = $_POST['manf_date'][$key];
                        $model->sub_location = $model->tn->sub_location;


                        if (empty($_POST['expire_date'][$key])) {
                            $model->expire_date = null;
                        }
                        if (empty($_POST['manf_date'][$key])) {
                            $model->manf_date = null;
                        }
                        if (empty($_POST['eff_date'][$key])) {
                            $model->eff_date = null;
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
                        
                        
                    }
                }
            }

            $this->returnMsg("Successfully Updated", 1, 1);
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


        $sql = "SELECT * FROM tn_items WHERE online = 1 $searchtxt ORDER BY id DESC ";
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
        $model = TnItems::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'tn-items-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}

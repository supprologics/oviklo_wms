<?php

class MrController extends Controller {

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
        $access = Useraccess::model()->findByAttributes(array('users_id' => $user_id, 'access_id' => 4));

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
        $data = Mr::model()->findByPk($id);
        $output = CJSON::encode($data);
        echo $output;
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

        $this->render('view', array(
            'model' => $this->loadModel($id),
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionCreate() {
        try {

            $model = new Mr;

            $model->attributes = $_POST;
            $model->created = date("Y-m-d H:i:s");
            $model->code = $this->returnCode("mr", "MR-" . $model->customers->code . "-");
            $model->last_update = $model->created;
            $model->users_id = Yii::app()->user->getId();
            $model->picked_id = $model->users_id;
            
            
            if(empty($_POST['eff_date'])){
                $model->eff_date = date("Y-m-d");
            }
            
            if(empty($_POST['delivery_date'])){
                $model->delivery_date = date("Y-m-d");
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

            $this->returnMsg("Successfully Updated", 1, 0,$model->id);
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
    
    public function actiondeletepick($id) {
        try {
            
            $model = $this->loadModel($id);
            $model->online = 1;
            $model->last_update = date("Y-m-d H:i:s"); 
            
            if ($model->save()) {
                Yii::app()->db->createCommand("DELETE FROM pick_items WHERE mr_id = '$id'")->execute();
                $this->returnMsg("Successfully Deleted", 1, 0);
            } else {
                $this->returnMsg("Error Occured", 0, 1);
            }
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

        if (empty($_GET['pages'])) {
            $pages = 50;
        } else {
            $pages = $_GET['pages'];
        }
        
        $user_id = Yii::app()->user->getState("userid");
        $cus_keys = $this->returnCustomerList();
        $wh_keys = $this->returnWarehouseList();        
        $sql = "SELECT * FROM mr WHERE IF(online = 1, users_id = '$user_id', users_id != 0) AND online >= 1 AND customers_id IN ($cus_keys) AND warehouse_id IN ($wh_keys) $searchtxt ORDER BY id DESC ";
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
        $model = Mr::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'mr-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}

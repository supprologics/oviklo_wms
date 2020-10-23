<?php

class GdnController extends Controller {

    
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
        $access = Useraccess::model()->findByAttributes(array('users_id' => $user_id, 'access_id' => 6));

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
            $vehicle_no = " AND col_vehicle LIKE '%" . $_GET['vehicle_no'] . "%' ";
        }
        
        

        if (empty($_GET['pages'])) {
            $pages = 50;
        } else {
            $pages = $_GET['pages'];
        }

        $cus_keys = $this->returnCustomerListForGdn();
        $wh_keys = $this->returnWarehouseList();
        $sql = "SELECT * FROM mr WHERE online = 4 AND customers_id IN ($cus_keys) AND warehouse_id IN ($wh_keys) $searchtxt $customers $warehouse $project $date_from $date_to $vehicle_no ORDER BY id DESC ";
        
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
    
    public function loadModel($id) {
        $model = Mr::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

}

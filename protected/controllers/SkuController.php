<?php

class SkuController extends Controller {

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
            if ($fileHandler) {
                while ($line = fgetcsv($fileHandler, 1000000, ",")) {
                    if ($header > 0) {
                        $num += 1;

                        $cost = new Sku();

                        $cost->customers_id = $_POST['customers_id'];
                        $cost->category_id = $_POST['category_id'];
                        $cost->uom_id = $_POST['uom_id'];

                        $cost->code =  trim($line[0]);
                        $cost->description =  trim($line[1]);
                        $cost->volume =  trim($line[2]);
                        $cost->weight =  trim($line[3]);
                        $cost->cbm =  trim($line[4]);
                        $cost->sqft =  trim($line[5]);
                        $cost->rol =  trim($line[6]);
                        $cost->roq =  trim($line[7]);
                        
                        $data = array("FIFO","FEFO","LIFO");
                        if(!in_array($line[8], $data)){
                           $errorArray[] = array("code" => $line[0], "description" => $line[1], "error" => "Invalid PICKING TYPE");
                           continue; 
                        }
                        $cost->pick_type =  trim($line[8]);
                        
                        
                        $cost->max_stacking =  trim($line[9]);                        
                        $cost->created = date("Y-m-d H:i:s");

                        if (!$cost->save()) {
                            $err += 1;
                            $er = $cost->getErrors();
                            $err_txt = "";
                            foreach ($er as $key => $value) {
                                $lebel = $cost->getAttributeLabel($key);
                                $err_txt .= $lebel . " : " . $value[0] . "<br/>";
                            }
                            $errorArray[] = array("code" => $line[0], "description" => $line[1], "error" => $err_txt);
                            continue;
                        }
                    }

                    $header += 1;
                }
            }

            $result = array(
                'msg' => "Successfully Updated,  All Records ($num), Errors ($err)",
                'sts' => 1,
                'hide' => 1,
                'er' => $errorArray
            );

            echo json_encode($result);
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }
    
    public function actionloadbatchtable($id) {

        $goods_sts_id = $_POST['goods_sts_id'];
        $mr_id = $_POST['mr_id'];
        $mr = Mr::model()->findByPk($mr_id);        
        
        $list = Yii::app()->db->createCommand("SELECT SUM(qty) as tot,expire_date,batch_no,grn_id,manf_date FROM `stock` "
                . "WHERE sku_id = $id AND "
                . "project_id = '". $mr->project_id ."' AND "
                . "warehouse_id = '". $mr->warehouse_id ."' AND "
                . "goods_sts_id = $goods_sts_id AND tbl_name IN ('grn','pick','tn','mr') AND IF(tbl_name = 'mr',online IN (1,2),online >= 1) "
                . "GROUP BY batch_no,expire_date HAVING tot > 0  "
                . "ORDER BY expire_date ASC ")->queryAll();    
                
                
        $tot = 0;
        $num = 1;
        foreach ($list as $value) {
            
            $grn = Grn::model()->findByPk($value['grn_id']);
            
            if($mr->customers->by_batch == 1){
                $readonly = "readonly='true'";
            }else{
                $readonly = "";
            }
            
            
            ?>
            <tr>                
                <td><?php echo $value['batch_no']; ?></td>
                <td>
                    <input type="hidden" id="manf_date_<?php echo $num; ?>" value="<?php echo $value['manf_date']; ?>" name="manf_date[<?php echo $num; ?>]" />
                    <input type="hidden" id="batch_no_<?php echo $num; ?>" value="<?php echo $value['batch_no']; ?>" name="batch_no[<?php echo $num; ?>]" />
                    <input type="hidden" id="expire_date_<?php echo $num; ?>" value="<?php echo $value['expire_date']; ?>" name="expire_date[<?php echo $num; ?>]" />
                    <?php echo $value['expire_date']; ?>
                </td>
                <td><?php echo $value['tot']; ?></td>
                <td width="15%" class="p-0 text-right"><input <?php echo $readonly; ?> type="text" min="1" max="<?php echo $value['tot']; ?>" id="qty_<?php echo $num; ?>" name="qty[<?php echo $num; ?>]" class="form-control form-control-sm qty_input"  autocomplete="off"  placeholder="Qty" /></td>
            </tr>
            <?php            
            $tot += $value['tot'];
            $num += 1;
        }
        
        ?>
            <tr>
                <td colspan="2">Total</td>
                <td id="tot_qty" data-qty="<?php echo $tot; ?>"><?php echo $tot; ?></td>
                <td width="15%" class="p-0 text-right"><input readonly="true" type="text" min="1" max="<?php echo $tot; ?>" id="qty_all" name="qty_all" class="form-control form-control-sm"  autocomplete="off"  placeholder="Qty" /></td>
                
            </tr>
            <tr class='table-success'>
                <td colspan="3">Bulk Request</td>
                <td width="15%" class="p-0 text-right"><input type="text" min="1" max="<?php echo $tot; ?>" id="qty_bulk" name="qty_bulk" class="form-control form-control-sm"  autocomplete="off"  placeholder="Qty" /></td>
                
            </tr>
            
        <?php    
        
    }
    
    public function actionloadbatchtableForTn($id) {

        $goods_sts_id = $_POST['goods_sts_id'];
        $tn_id = $_POST['tn_id'];
        $tn = Tn::model()->findByPk($tn_id);       
                 
           
        
        
        $list = Yii::app()->db->createCommand("SELECT SUM(qty) as tot,expire_date,batch_no,grn_id,manf_date,eff_date FROM `stock` "
                . "WHERE sku_id = $id AND "
                . "project_id = '". $tn->project_from ."' AND "
                . "warehouse_id = '". $tn->warehouse_from ."' AND "
                . "goods_sts_id = $goods_sts_id AND tbl_name IN ('grn','pick','tn','mr') AND IF(tbl_name = 'mr',online IN (1,2),online >= 1) "
                . "GROUP BY batch_no,expire_date HAVING tot > 0 "
                . "ORDER BY expire_date ASC ")->queryAll();
               
        

        $tot = 0;
        $num = 1;
        foreach ($list as $value) {
            
            $grn = Grn::model()->findByPk($value['grn_id']);
            ?>
            <tr>                
                <td><?php echo $value['batch_no']; ?></td>
                <td><?php echo $grn->code; ?></td>
                <td>
                    <input type="hidden" id="manf_date_<?php echo $num; ?>" value="<?php echo $value['manf_date']; ?>" name="manf_date[<?php echo $num; ?>]" />
                    <input type="hidden" id="batch_no_<?php echo $num; ?>" value="<?php echo $value['batch_no']; ?>" name="batch_no[<?php echo $num; ?>]" />
                    <input type="hidden" id="grn_id_<?php echo $num; ?>" value="<?php echo $value['grn_id']; ?>" name="grn_id[<?php echo $num; ?>]" />
                    <input type="hidden" id="expire_date_<?php echo $num; ?>" value="<?php echo $value['expire_date']; ?>" name="expire_date[<?php echo $num; ?>]" />
                    <input type="hidden" id="eff_date_<?php echo $num; ?>" value="<?php echo $value['eff_date']; ?>" name="eff_date[<?php echo $num; ?>]" />
                    <?php echo $value['expire_date']; ?>
                </td>
                <td><?php echo $value['tot']; ?></td>
                <td width="15%" class="p-0 text-right"><input  type="text" min="1" max="<?php echo $value['tot']; ?>" id="qty_<?php echo $num; ?>" name="qty[<?php echo $num; ?>]" class="form-control form-control-sm qty_input"  autocomplete="off"  placeholder="Qty" /></td>
            </tr>
            <?php            
            $tot += $value['tot'];
            $num += 1;
        }
        
        ?>
            <tr>
                <td colspan="3">Total</td>
                <td id="tot_qty" data-qty="<?php echo $tot; ?>"><?php echo $tot; ?></td>
                <td width="15%" class="p-0 text-right"><input  type="text" min="1" max="<?php echo $tot; ?>" id="qty_all" name="qty_all" class="form-control form-control-sm"  autocomplete="off"  placeholder="Qty" /></td>
                
            </tr>
            <tr class='table-success'>
                <td colspan="4">Bulk Request</td>
                <td width="15%" class="p-0 text-right"><input type="text" min="1" max="<?php echo $tot; ?>" id="qty_bulk" name="qty_bulk" class="form-control form-control-sm"  autocomplete="off"  placeholder="Qty" /></td>
            </tr>
            
        <?php    
        
    }

    public function actionloadlist($id) {

        $term = $_GET['term'];
        $list = Yii::app()->db->createCommand("SELECT id FROM sku WHERE customers_id = '$id' AND ( sku.code LIKE '%$term%' OR sku.description LIKE '%$term%' ) ORDER BY sku.code ASC ")->queryAll();
        
        foreach ($list as $value) {
            $sku = Sku::model()->findByPk($value['id']);        
            $json[] = array(
                'id' => $sku->id,
                'value' => $sku->code,
                'description' => $sku->description,
                'volume' => $sku->volume,
                'weight' => $sku->weight,
                'max_stacking' => $sku->max_stacking,
            );
        }
        echo json_encode($json);
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
        $data = Sku::model()->findByPk($id);
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

            $model = new Sku;

            $model->attributes = $_POST;
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


        $sql = "SELECT * FROM sku WHERE online = 1 $searchtxt ORDER BY id DESC ";
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
        $model = Sku::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'sku-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}

<?php
$customer = Customers::model()->findByPk($_POST['customers_id']);
?>

<html>
    <head>

        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="language" content="en">

        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/open-iconic-bootstrap.css">
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/template.css">
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/alertify.css">
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/pace.css">


        <title>Oviklo iSpace - Cloud 3PL Management System</title>

        <?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
        <?php Yii::app()->clientScript->registerCoreScript('jquery.ui'); ?>  

        <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.bundle.min.js" ></script>
        <?php Yii::app()->clientScript->registerCssFile(Yii::app()->clientScript->getCoreScriptUrl() . '/jui/css/base/jquery-ui.css'); ?>

        <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . '/js/jquery.form.js'); ?>
        <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . '/js/jquery.validate.js'); ?>
        <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . '/js/alertify.min.js'); ?>


        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap-datepicker.css">
        <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . '/js/bootstrap-datepicker.js'); ?>
        <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . '/js/pacenew.js'); ?>

        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css">
        <title>REPORT - Stock Valuation Report</title>


        <script>

            function exportTableToExcel(tableID, filename = '') {
               var downloadLink;
               var dataType = 'application/vnd.ms-excel';
               var tableSelect = document.getElementById (tableID);
               var tableHTML = tableSelect.outerHTML.replace (/ /g, '%20');
               filename = filename ? filename + '.xls' : 'report.xls';
               downloadLink = document.createElement ("a");
               document.body.appendChild (downloadLink);

               if (navigator.msSaveOrOpenBlob) {
                  var blob = new Blob (['\ufeff', tableHTML], {
                     type: dataType
                  });
                  navigator.msSaveOrOpenBlob (blob, filename);
               } else {
                  // Create a link to the file
                  downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
                  downloadLink.download = filename;
                  downloadLink.click ();
               }
            }

        </script>


    </head>
    <body>

        <header class="d-print-none d-block fixed-top" style="margin-top: -5px;">
            <div class="row">
                <div class="col">
                    <h3>Inventory Report</h3>
                </div>
                <div class="col text-right">
                    <button class="btn btn-sm btn-default d-print-none" onclick="exportTableToExcel ('popularity_s')">Export to Excel</button>
                    <button class="btn btn-sm btn-success d-print-none" onclick="window.print()" >Print <span class="oi oi-print"></span></button>
                </div>
            </div>
        </header>

        <div class="report_body" >

            <div class="row">
                <div class="col">
                    <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/logo.jpg" width="250px" />
                    <h2 style="font-size: 18px; margin: 0px; padding: 0px; font-weight: bold;">Oviklo International ( Pvt ) Ltd.</h2>
                    <p>No.88/1F, Duwawatta, Kotikawatta</p>
                </div>
                <div class="col text-right">
                    <h2 class="report_header">Inventory Report By Batch</h2>
                </div>

            </div>

            <div id='popularity_s'>

                <div class="container-fluid">
                    <div class="row">
                        <table class="table table-sm">
                            <thead>
                                <tr >
                                    <th>Customer Code</th>
                                    <th>Customer Name</th>
                                    <th>Customer Address</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tr class="table-active">
                                <td><?php echo $customer->code; ?></td>
                                <td><?php echo $customer->name; ?></td>
                                <td><?php echo $customer->address; ?></td>
                                <td><?php echo date("Y-m-d"); ?></td>
                                <td><?php echo date("H:i:s"); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <table id="popularity" class="table data reports_table table-sm table-bordered" cellspacing="0" cellpadding="0" width="100%">

                    <thead>
                        <tr class="red">
                            <th></th>
                            <th>WH</th>  
                            <th>STATUS</th>
                            <th>SKU</th>
                            <th>DESCRIPTION</th>
                            <th>PACKAGE</th>
                            <th>BATCH</th>
                            <th>MANF DATE</th>
                            <th>EXPIRE DATE</th>
                            <th class="text-right">QTY</th>
                            <th class="text-right">UOM</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        //$sku = Sku::model()->findByPk($_POST['sku_id']);
                        $sku = Sku::model()->findByAttributes(
                                array(
                                    "code" => $_POST['sku'],
                                    "customers_id" => $customer->id
                        ));

                        if ($sku != null) {
                            $sku_q = " AND sku_id = '" . $sku->id . "' ";
                        } else {
                            $sku_q = "";
                        }

                        if (!empty($_POST['effdate'])) {
                            $effdate = " AND DATE(created) <= '" . $_POST['effdate'] . "' ";
                        } else {
                            $effdate = "";
                        }

                        if (!empty($_POST['goods_sts_id'])) {
                            $goodsSts_id = " AND goods_sts_id = '" . $_POST['goods_sts_id'] . "' ";
                        } else {
                            $goodsSts_id = "";
                        }


                        if (!empty($_POST['project_id'])) {
                            $project_id = " AND project_id = '" . $_POST['project_id'] . "' ";
                        } else {
                            $project_id = "";
                        }

                        if (!empty($_POST['warehouse_id'])) {
                            $warehouse_id = " AND warehouse_id = '" . $_POST['warehouse_id'] . "' ";
                        } else {
                            $warehouse_id = "";
                        }


                        $stocklot = Yii::app()->db->createCommand("SELECT "
                                        . "SUM(qty) as tot,"
                                        . "warehouse_id,project_id,"
                                        . "batch_no,manf_date,expire_date,"
                                        . "sku_id,"
                                        . "goods_sts_id "
                                        . "FROM stock WHERE "
                                        . "customers_id = '" . $customer->id . "' AND tbl_name IN ('grn','pick','tn') $warehouse_id $project_id $goodsSts_id $sku_q $effdate "
                                        . "GROUP BY "
                                        . "goods_sts_id,sku_id,warehouse_id,project_id,batch_no,expire_date,manf_date HAVING tot != 0 ORDER BY sku_id ASC")->queryAll();
                        
                        
                        


                        //CALCS
                        $num = 1;
                        $tot = 0;

                        foreach ($stocklot as $value) {

                            $projects = Project::model()->findByPk($value['project_id']);
                            $wh = Warehouse::model()->findByPk($value['warehouse_id']);
                            $sku = Sku::model()->findByPk($value['sku_id']);
                            $goodsSts = GoodsSts::model()->findByPk($value['goods_sts_id']);
                            ?>
                            <tr>
                                <td><?php echo $num; ?></td>
                                <td><?php echo $wh->code; ?></td>   
                                <td><?php echo $goodsSts->name; ?></td>
                                <td><?php echo $sku->code; ?></td>
                                <td><?php echo $sku->description; ?></td>
                                <td><?php echo $projects->name; ?></td>
                                <td><?php echo $value['batch_no']; ?></td>
                                <td><?php echo $value['manf_date']; ?></td>
                                <td><?php echo $value['expire_date']; ?></td>
                                <td class="text-right"><?php echo number_format($value['tot'], 2); ?></td>
                                <td class="text-right"><?php echo $sku->uom->sym; ?></td> 
                            </tr>

                            <?php
                            $num += 1;
                            $tot += $value['tot'];
                        }
                        ?>

                        <tr>
                            <td colspan="9">Total</td>
                            <td class="text-right"><?php echo number_format($tot, 2); ?></td>
                            <td></td>
                        </tr>

                    </tbody>
                </table>


            </div>

        </div>
    </body>
</html>



<?php
$customer = Customers::model()->findByPk($_POST['customers_id']);
?>

<html>
    <head>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css">
        <title>REPORT - Stock Valuation Report</title>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap.css">

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
                    <h3>Inventory Report - SOH</h3>
                </div>
                <div class="col text-right">
                    <button class="btn btn-sm btn-default d-print-none" onclick="exportTableToExcel ('popularity_s')">Export to Excel</button>
                    <button class="btn btn-sm btn-success d-print-none" onclick="window.print ()" >Print <span class="oi oi-print"></span></button>
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
                    <h2 class="report_header">Inventory Report  - SOH </h2>
                </div>

            </div>

            <div id='popularity_s'>

                <div class="container-fluid">
                    <div class="row">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Name</th>
                                </tr>
                            </thead>
                            <tr class="table-active">
                                <td><?php echo $customer->code; ?></td>
                                <td><?php echo $customer->name; ?></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <table id="popularity" class="table data reports_table table-sm table-bordered" cellspacing="0" cellpadding="0" width="100%">
                    <thead>
                        <tr class="red">
                            <th></th>
                            <th>WH</th>                        
                            <th>CATEGORY</th>
                            <th>PROJECT</th>
                            <th>SKU</th>
                            <th>DESCRIPTION</th>

                            <th>STATUS</th>
                            <th>SHIPMENT</th>
                            <th>SO NO</th>       
                            <th class="text-right">GRN QTY</th>
                            <th class="text-right">Received QTY</th>
                            <th class="text-right">QTY</th>
                            <th class="text-right">MR Pending QTY</th>
                            <th class="text-right">Pick QTY</th>
                            <th class="text-right">AVL QTY</th>
                            <th class="text-right">GRN DATE</th>
                            <th class="text-right">AGING DAYS</th>
                            <th>UOM</th>          
                        </tr>
                    </thead>
                    <tbody>

                        <?php
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
                                        . "warehouse_id,"
                                        . "id,"
                                        . "batch_no,"
                                        . "sku_id,"
                                        . "p_id,"
                                        . "project_id,"
                                        . "goods_sts_id,expire_date,eff_date, "
                                        . "DATEDIFF('" . date("Y-m-d") . "',eff_date) AS diffdays "
                                        . "FROM stock WHERE "
                                        . "customers_id = '" . $customer->id . "' AND tbl_name IN ('grn','tn') $warehouse_id $project_id $goodsSts_id $sku_q $effdate "
                                        . "GROUP BY "
                                        . "batch_no,goods_sts_id,sku_id,project_id,warehouse_id HAVING tot != 0 ORDER BY expire_date ASC")->queryAll();
                        
                        

                        $num = 1;
                        $tot = 0;

                        foreach ($stocklot as $value) {

                            $projects = Project::model()->findByPk($value['project_id']);
                            $wh = Warehouse::model()->findByPk($value['warehouse_id']);
                            $grn = Grn::model()->findByPk($value['p_id']);
                            $sku = Sku::model()->findByPk($value['sku_id']);
                            $goodsSts = GoodsSts::model()->findByPk($value['goods_sts_id']);

                            
                            $dataGRN = Yii::app()->db->createCommand("SELECT SUM(qty) as tot,packinglist_no FROM grn,grn_items WHERE grn.project_id = '" . $value['project_id'] . "' AND grn.warehouse_id = '" . $value['warehouse_id'] . "' AND grn.id = grn_items.grn_id AND sku_id = '" . $value['sku_id'] . "' AND batch_no = '" . $value['batch_no'] . "' AND goods_sts_id = '" . $value['goods_sts_id'] . "' ")->queryRow();
                            $dataTN = Yii::app()->db->createCommand("SELECT SUM(qty) as tot FROM tn,tn_items WHERE tn.project_to = '" . $value['project_id'] . "' AND tn.warehouse_to = '" . $value['warehouse_id'] . "' AND tn.id = tn_items.tn_id AND sku_id = '" . $value['sku_id'] . "' AND batch_no = '" . $value['batch_no'] . "' AND tn.goods_sts_to = '" . $value['goods_sts_id'] . "' ")->queryRow();
        
                            $totGrn = $dataGRN['tot'] + $dataTN['tot'];
                            
                            //PICKED QTY
                            $dataPicked = Yii::app()->db->createCommand("SELECT SUM(qty) as tot FROM mr,pick_items WHERE mr.project_id = '" . $value['project_id'] . "' AND mr.warehouse_id = '" . $value['warehouse_id'] . "' AND mr.id = pick_items.mr_id AND sku_id = '" . $value['sku_id'] . "' AND batch_no = '" . $value['batch_no'] . "' AND goods_sts_id = '" . $value['goods_sts_id'] . "' AND mr.online = 4 ")->queryRow();
                            //TN-OUT
                            $dataTnOut = Yii::app()->db->createCommand("SELECT SUM(qty) as tot FROM tn,tn_items WHERE tn.project_from = '" . $value['project_id'] . "' AND tn.warehouse_from = '" . $value['warehouse_id'] . "' AND tn.id = tn_items.tn_id AND sku_id = '" . $value['sku_id'] . "' AND batch_no = '" . $value['batch_no'] . "' AND tn.goods_sts_from = '" . $value['goods_sts_id'] . "' ")->queryRow();
                            $avltot = $totGrn - $dataPicked['tot'] - $dataTnOut['tot'];
                            
                            if($avltot > 0){
                            
                            ?>
                            <tr>
                                <td><?php echo $num; ?></td>
                                <td><?php echo $wh->code; ?></td>                            
                                <td><?php echo $sku->category->code; ?></td>
                                <td><?php echo $projects->name; ?></td>
                                <td><?php echo $sku->code; ?></td>
                                <td><?php echo $sku->description; ?></td>


                                <td><?php echo $goodsSts->name; ?></td>
                                <td><?php echo $dataGRN['packinglist_no']; ?> </td>
                                <td><?php echo $value['batch_no']; ?></td>
                                <td class="text-right"><?php echo $dataGRN['tot']; ?></td>
                                <td class="text-right"><?php echo $dataGRN['tot'] + $dataTN['tot']; ?></td>
                                <td class="text-right"><?php echo $avltot; ?></td>
                                <td class="text-right">
                                    <?php
                                    $dataMR = Yii::app()->db->createCommand("SELECT SUM(qty) as tot FROM mr,mr_items WHERE mr.project_id = '" . $value['project_id'] . "' AND mr.warehouse_id = '" . $value['warehouse_id'] . "' AND mr.id = mr_items.mr_id AND sku_id = '" . $value['sku_id'] . "' AND batch_no = '" . $value['batch_no'] . "' AND goods_sts_id = '" . $value['goods_sts_id'] . "' AND mr.online IN (1,2) ")->queryAll();
                                    echo $dataMR[0]['tot'];
                                    ?>
                                </td>
                                <td class="text-right">
                                    <?php
                                    $data = Yii::app()->db->createCommand("SELECT SUM(qty) as tot FROM mr,pick_items WHERE mr.project_id = '" . $value['project_id'] . "' AND mr.warehouse_id = '" . $value['warehouse_id'] . "' AND mr.id = pick_items.mr_id AND sku_id = '" . $value['sku_id'] . "' AND batch_no = '" . $value['batch_no'] . "' AND goods_sts_id = '" . $value['goods_sts_id'] . "' AND mr.online = 3 ")->queryAll();
                                    echo $data[0]['tot'];
                                    ?>
                                </td>

                                <td class="text-right">
                                    <?php
                                    echo $avltot - $dataMR[0]['tot'] - $data[0]['tot'];
                                    ?>

                                </td>

                                <td class="text-right">
                                    <?php
                                    //$data = Yii::app()->db->createCommand("SELECT grn.* FROM grn,grn_items WHERE grn.project_id = '" . $value['project_id'] . "' AND grn.warehouse_id = '" . $value['warehouse_id'] . "' AND grn.id = grn_items.grn_id AND sku_id = '" . $value['sku_id'] . "' AND batch_no = '" . $value['batch_no'] . "' AND goods_sts_id = '" . $value['goods_sts_id'] . "' ")->queryAll();
                                    //echo $data[0]['eff_date'];
                                    echo $value['eff_date'];
                                    ?>
                                </td>
                                <td class="text-right">
                                    <?php
                                    //$data = Yii::app()->db->createCommand("SELECT DATEDIFF('". date("Y-m-d") ."',grn.eff_date) as health FROM grn,grn_items WHERE grn.project_id = '" . $value['project_id'] . "' AND grn.warehouse_id = '" . $value['warehouse_id'] . "' AND grn.id = grn_items.grn_id AND sku_id = '" . $value['sku_id'] . "' AND batch_no = '" . $value['batch_no'] . "' AND goods_sts_id = '" . $value['goods_sts_id'] . "' ")->queryRow();
                                    //echo $data['health'];
                                    echo $value['diffdays'];
                                    ?>
                                </td>
                                <td><?php echo $sku->uom->sym; ?></td>          
                            </tr>

                            <?php
                            $num += 1;
                            $tot += $avltot - $dataMR[0]['tot'] - $data[0]['tot'];
                            
                            }
                        }
                        ?>


                        <tr>
                            <td colspan="14"  class="text-right"> Total</td>
                            <td class="text-right"><?php echo $tot; ?></td>
                            <td colspan="3"></td>
                        </tr>
                    </tbody>
                </table>

            </div>

        </div>
    </body>
</html>



<?php
$customer = Customers::model()->findByPk($_POST['customers_id']);

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

$isCbm = $_POST['is_cbm'];
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

        <header class="d-print-none d-block">
            <h3>Report Window</h3>
        </header>


        <div class="report_body" >

            <div class="row mt-1">
                <div class="col text-right">
                    <button class="btn btn-sm btn-default d-print-none" onclick="exportTableToExcel ('popularity_s')">Export to Excel</button>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <h2 class="report_header">Location Wise Inventory Report By Customer</h2>
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
                    <tr>
                        <th></th>
                        <th>WH</th> 
                        <th>ZONE</th>   
                        <th>CATEGORY</th>
                        <th>SKU</th>
                        <th>DESCRIPTION</th>
                        <th>UOM</th>                        
                        <th>PROJECT</th>
                        <th>STATUS</th>
                        <th>BATCH</th>   
                        <th>EXPIRE</th>    
                        <th class="text-right">AVL QTY</th>
                        <?php
                        if ($isCbm == 1) {
                            echo '<th class="text-right">CBM</th>';
                        }
                        ?>
                    </tr>

                    <?php
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

//. "customers_id = '" . $customer->id . "' AND tbl_name IN ('grn','pick','tn','mr') AND IF(tbl_name = 'mr',online = 1,online >= 1) $warehouse_id $project_id $goodsSts_id $sku_q "
//,sku_id,goods_sts_id,project_id,locations_id,sub_location
                    $stocklot = Yii::app()->db->createCommand("SELECT "
                                    . "SUM(qty) as tot,"
                                    . "warehouse_id,"
                                    . "id,"
                                    . "batch_no,"
                                    . "sku_id,"
                                    . "p_id,"
                                    . "project_id,"
                                    . "goods_sts_id,expire_date,locations_id, "
                                    . "DATEDIFF('" . date("Y-m-d") . "',created) AS diffdays "
                                    . "FROM stock WHERE "
                                    . "customers_id = '" . $customer->id . "' AND tbl_name IN ('grn','pick','tn') $warehouse_id $project_id $goodsSts_id $sku_q $effdate "
                                    . "GROUP BY "
                                    . "batch_no,goods_sts_id,sku_id,project_id,warehouse_id,locations_id HAVING tot != 0 ORDER BY sku_id,expire_date ASC")->queryAll();


                    $num = 1;
                    $tot = 0;
                    $totCBM = 0;
                    foreach ($stocklot as $value) {

                        $projects = Project::model()->findByPk($value['project_id']);
                        $wh = Warehouse::model()->findByPk($value['warehouse_id']);
                        $grn = Grn::model()->findByPk($value['p_id']);
                        $sku = Sku::model()->findByPk($value['sku_id']);
                        $goodsSts = GoodsSts::model()->findByPk($value['goods_sts_id']);
                        $locations = Locations::model()->findByPk($value['locations_id']);
                        ?>
                        <tr>
                            <td><?php echo $num; ?></td>
                            <td><?php echo $wh->code; ?></td>    
                            <td><?php echo $locations->code; ?></td>              
                            <td><?php echo $sku->category->code; ?></td>
                            <td><?php echo $sku->code; ?></td>
                            <td><?php echo $sku->description; ?></td>
                            <td><?php echo $sku->uom->sym; ?></td>                            
                            <td><?php echo $projects->name; ?></td>
                            <td><?php echo $goodsSts->name; ?></td>
                            <td><?php echo $value['batch_no']; ?></td>
                            <td><?php echo $value['expire_date']; ?></td>
                            <td class="text-right"><?php echo number_format($value['tot'],2); ?></td>
                            <?php if ($isCbm == 1) { ?>
                                <td class="text-right"><?php echo round($value['tot'] * $sku->cbm,2); ?></td>
                            <?php } ?>
                        </tr>

                        <?php
                        $num += 1;
                        $tot += $value['tot'];
                        $totCBM += round($value['tot'] * $sku->cbm,2);
                    }
                    ?>

                    <tr>
                        <td colspan="11">Total</td>
                        <td class="text-right"><?php echo number_format($tot,2); ?></td>
                        <?php if ($isCbm == 1) { ?>
                        <td class="text-right"><?php echo round($totCBM,2); ?></td>
                        <?php } ?>
                    </tr>
                </table>

            </div>

        </div>
    </body>
</html>



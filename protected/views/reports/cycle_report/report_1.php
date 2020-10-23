<?php
$customer = Customers::model()->findByPk($_POST['customers_id']);
$sku = Sku::model()->findByAttributes(array("code" => $_POST['sku']));

if($sku != null){
    $sku_q = " AND sku_id = '". $sku->id ."' ";
}else{
    $sku_q = "";
}

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
                var tableSelect = document.getElementById(tableID);
                var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
                filename = filename ? filename + '.xls' : 'report.xls';
                downloadLink = document.createElement("a");
                document.body.appendChild(downloadLink);

                if (navigator.msSaveOrOpenBlob) {
                    var blob = new Blob(['\ufeff', tableHTML], {
                        type: dataType
                    });
                    navigator.msSaveOrOpenBlob(blob, filename);
                } else {
                    // Create a link to the file
                    downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
                    downloadLink.download = filename;
                    downloadLink.click();
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
                    <button class="btn btn-sm btn-default d-print-none" onclick="exportTableToExcel('popularity_s')">Export to Excel</button>
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
                        <th>SUB/L</th>
                        <th>CATEGORY</th>
                        <th>SKU</th>
                        <th>DESC</th>
                        <th>UOM</th>
                        <th>Rec No</th>
                        <th>IN Date</th>
                        <th>Age in Days</th>
                        <th>PROJECT</th>
                        <th>STS</th>
                        <th>BATCH</th>
                        <th class="text-right">IN Qty</th>
                        <th class="text-right">Issued Qty</th>                    
                        <th class="text-right">Net Qty</th>
                    </tr>

                    <?php
                    $stocklot = Yii::app()->db->createCommand("SELECT "
                                    . "SUM(qty) as tot,"
                                    . "warehouse_id,"
                                    . "locations_id,"
                                    . "id,"
                                    . "batch_no,"
                                    . "sku_id,"
                                    . "p_id,"
                                    . "project_id,"
                                    . "goods_sts_id,sub_location, "
                                    . "DATEDIFF('" . date("Y-m-d") . "',created) AS diffdays "
                                    . "FROM stock WHERE "
                                    . "customers_id = '" . $customer->id . "' AND "
                                    . "tbl_name = 'grn' $sku_q "
                                    . "GROUP BY "
                                    . "batch_no,project_id,p_id,warehouse_id,locations_id,sku_id,goods_sts_id,sub_location,grn_id ORDER BY expire_date ASC")->queryAll();

                                        
                    $num = 1;
                    $tot = 0;
                    $totGRN = 0;
                    $totISSUED = 0;
                    foreach ($stocklot as $value) {

                        $projects = Project::model()->findByPk($value['project_id']);
                        $wh = Warehouse::model()->findByPk($value['warehouse_id']);
                        $location = Locations::model()->findByPk($value['locations_id']);
                        $grn = Grn::model()->findByPk($value['p_id']);
                        $sku = Sku::model()->findByPk($value['sku_id']);
                        $goodsSts = GoodsSts::model()->findByPk($value['goods_sts_id']);


                        $qty = Yii::app()->db->createCommand("SELECT "
                                        . "SUM(qty) as tot "
                                        . "FROM stock WHERE "
                                        . "customers_id = '" . $customer->id . "' AND "
                                        . "warehouse_id = '" . $wh->id . "' AND "
                                        . "locations_id = '" . $location->id . "' AND "
                                        . "grn_id = '" . $grn->id . "' AND "
                                        . "sku_id = '". $sku->id ."' AND "
                                        . "batch_no = '" . $value['batch_no'] . "' AND "
                                        . "tbl_name = 'pick' ")->queryAll();

                        $bal = $value['tot'] + $qty[0]['tot'];
                        
                            ?>
                            <tr>
                                <td><?php echo $num; ?></td>
                                <td><?php echo $wh->code; ?></td>
                                <td><?php echo $location->code; ?></td>
                                <td><?php echo $value['sub_location']; ?></td>
                                <td><?php echo $sku->category->code; ?></td>
                                <td><?php echo $sku->code; ?></td>
                                <td><?php echo $sku->description; ?></td>
                                <td><?php echo $sku->uom->sym; ?></td>
                                <td><?php echo $grn->code; ?></td>
                                <td><?php echo $grn->eff_date; ?></td>
                                <td><?php echo $value['diffdays']; ?></td>
                                <td><?php echo $projects->name; ?></td>
                                <td><?php echo $goodsSts->name; ?></td>
                                <td><?php echo $value['batch_no']; ?></td>
                                <td class="text-right"><?php echo $value['tot']; ?></td>
                                <td class="text-right"><?php echo abs($qty[0]['tot']); ?></td>
                                <td class="text-right"><?php echo $value['tot'] + $qty[0]['tot']; ?></td>
                            </tr>

                            <?php
                            $num += 1;
                            $totGRN += $value['tot'];
                            $totISSUED += abs($qty[0]['tot']);
                            $tot += $value['tot'] + $qty[0]['tot'];
                        
                    }
                    ?>
                            
                            <tr>
                                <td colspan="14">Total</td>
                                <td class="text-right"><?php echo $totGRN; ?></td>
                                <td class="text-right"><?php echo $totISSUED; ?></td>
                                <td class="text-right"><?php echo $tot; ?></td>
                            </tr>
                </table>

            </div>

        </div>
    </body>
</html>



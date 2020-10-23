<?php
$customer = Customers::model()->findByPk($_POST['customers_id']);
$date_from = $_POST['date_from'];
$date_to = $_POST['date_to'];
$isCbm = $_POST['is_cbm'];



$sku = Sku::model()->findByAttributes(array("code" => $_POST['sku'], "customers_id" => $customer->id));
if ($sku != null) {
    $sku_q = " AND sku_id = '" . $sku->id . "' ";
} else {
    $sku_q = "";
}

$batch = $_POST['batch'];
if (!empty($batch)) {
    $batch_q = " AND batch_no = '$batch' ";
} else {
    $batch_q = "";
}

//goods_sts_id
$goods_sts_id = $_POST['goods_sts_id'];
if (!empty($goods_sts_id)) {
    $goods_q = " AND goods_sts_id = '$goods_sts_id' ";
} else {
    $goods_q = "";
}

//goods_sts_id
$project_id = $_POST['project_id'];
if (!empty($project_id)) {
    $project_id_q = " AND project_id = '$project_id' ";
} else {
    $project_id_q = "";
}
?>

<html>
    <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
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
                    <h3>Movement Report</h3>
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
                    <h2 class="report_header">Basic Movement Report</h2>
                </div>

            </div>

            <div id='popularity_s'>

                <div class="container-fluid">
                    <div class="row">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>CODE</th>
                                    <th>NAME</th>
                                    <th>SKU</th>
                                    <th>DESCRIPTION</th>
                                    <th>UOM</th>
                                    <th class="text-right">DATE RANGE</th>
                                </tr>
                            </thead>
                            <tr class="table-active">
                                <td><?php echo $customer->code; ?></td>
                                <td><?php echo $customer->name; ?></td>
                                <td><?php echo $sku->code; ?></td>
                                <td><?php echo $sku->description; ?></td>
                                <td><?php echo $sku->uom->name; ?></td>
                                <td  class="text-right"><?php echo $date_from . " - " . $date_to; ?></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <table id="popularity" class="table data reports_table table-sm table-bordered" cellspacing="0" cellpadding="0" width="100%">
                    <thead>
                        <tr class="red">
                            <th></th>
                            <th>DATE</th>
                            <th>STOCK</th>
                            <th>GRN NO</th>
                            <th>GDN NO</th>
                            <th>TN NO</th>
                            <th>REMARKS</th>
                            <th class="text-right">IN QTY</th>
                            <th class="text-right">OUT QTY</th>
                            <th class="text-right">BALANCE</th>
                        </tr>
                    </thead>
                    <tbody>


                        <?php
                        $customer_id = $customer->id;
                        
//                        $list = Yii::app()->db->createCommand("SELECT id,eff_date FROM stock WHERE "
//                                        . "customers_id = '$customer_id' AND "
//                                        . "DATE(eff_date) >= '$date_from' AND "
//                                        . "tbl_name IN ('grn','pick','tn') AND "
//                                        . "DATE(eff_date) <= '$date_to' $sku_q $batch_q $goods_q $project_id_q ORDER BY eff_date ASC,id ASC")->queryAll();
                        
                        
                        $list = Yii::app()->db->createCommand("SELECT id,eff_date,created,project_id FROM stock WHERE "
                                        . "customers_id = '$customer_id' AND "
                                        . "DATE(created) >= '$date_from' AND "
                                        . "tbl_name IN ('grn','pick','tn') AND "
                                        . "DATE(created) <= '$date_to' $sku_q $batch_q $goods_q $project_id_q ORDER BY created ASC,id ASC")->queryAll();
                        
                        

                        //CREATED ASC


                        $num = 1;

                        $in = 0;
                        $out = 0;
                        $inCBM = 0;
                        $outCBM = 0;
                        foreach ($list as $value) {

                            $stock = Stock::model()->findByPk($value['id']);
                            ?>
                            <tr>
                                <td><?php echo $num; ?></td>
                                <td><?php echo date("Y-m-d",  strtotime($value['created'])); ?></td>
                                <td><?php echo $stock->project->name; ?></td>
                                <td>
                                    <?php
                                        if($stock->tbl_name == 'grn'){
                                           echo Grn::model()->findByPk($stock->p_id)->code; 
                                        }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                        if($stock->tbl_name == 'pick'){
                                           echo Mr::model()->findByPk($stock->p_id)->code; 
                                        }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                        if($stock->tbl_name == 'tn'){
                                           echo Tn::model()->findByPk($stock->p_id)->code; 
                                        }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    switch ($stock->tbl_name) {
                                        case 'grn':
                                            echo Grn::model()->findByPk($stock->p_id)->remarks;
                                            break;
                                        case 'pick':
                                            echo Mr::model()->findByPk($stock->p_id)->remarks;
                                            break;
                                        case 'tn':
                                            echo Tn::model()->findByPk($stock->p_id)->remarks;
                                            break;
                                        default:
                                            echo "N/A";
                                            break;
                                    }
                                    ?>
                                </td>
                                <?php
                                if ($stock->qty > 0) {
                                    ?>
                                    <td class="text-right"><?php echo round($stock->qty, 2) ?></td>
                                    <td class="text-right"></td>
                                    <?php
                                    $in += round($stock->qty, 2);
                                } else {
                                    ?>
                                    <td class="text-right"></td>
                                    <td class="text-right"><?php echo round(abs($stock->qty), 2); ?></td>
                                    <?php
                                    $out += round(abs($stock->qty), 2);
                                }
                                ?>
                                <td class="text-right"><?php echo $in - $out; ?></td>    
                            </tr>
                            <?php
                            $num += 1;
                        }
                        ?>
                            
                        <tr>
                            <td colspan="7"></td>
                            <th class="text-right">Total In</th>
                            <th class="text-right">Total Out</th>
                            <th class="text-right">Balance in Hand</th>    
                        </tr>

                        <tr>
                            <td colspan="7"></td>
                            <td class="text-right"><?php echo $in; ?></td>
                            <td class="text-right"><?php echo $out; ?></td>
                            <td class="text-right"><?php echo $in - $out; ?></td>    
                        </tr>
                        
                    </tbody>
                </table>

            </div>

        </div>
    </body>
</html>



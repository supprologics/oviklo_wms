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

$serial = $_POST['serial'];
if (!empty($serial)) {
    $serial_q = " AND code = '$serial' ";
} else {
    $serial_q = "";
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
                    <h2 class="report_header">Batch Movement Report</h2>
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
                            <th>PROJECT</th>
                            <th>SO/BATCH</th>
                            <th>SERIAL</th>
                            <th>GRN NO</th>
                            <th>GDN NO</th>
                            <th>STATUS</th>
                            <th class="text-right">TYPE</th>
                        </tr>
                    </thead>
                    <tbody>


                        <?php
                        $customer_id = $customer->id;
                        $list = Yii::app()->db->createCommand("SELECT id FROM `serial_stock` WHERE customers_id = '$customer_id' $sku_q $serial_q ")->queryAll();
                        

                        $num = 1;

                        foreach ($list as $value) { 
                            
                            $serial = SerialStock::model()->findByPk($value['id']);
                            ?>
                            <tr>
                                <td><?php echo $num; ?></td>
                                <td>
                                    <?php 
                                    
                                    if($serial->entry == 'IN'){
                                        echo Grn::model()->findByPk($serial->p_id)->eff_date;
                                    }else{
                                        echo Mr::model()->findByPk($serial->p_id)->delivery_date;
                                    }  
                                    ?>
                                
                                </td>
                                <td><?php echo $serial->project->name; ?></td>
                                <td>
                                <?php                                     
                                    if($serial->entry == 'IN'){
                                        echo GrnItems::model()->findByPk($serial->f_id)->batch_no;
                                    }else{
                                        echo PickItems::model()->findByPk($serial->f_id)->batch_no;
                                    }                                   
                                    ?>
                                </td>
                                <td><?php echo $serial->code; ?></td>
                                <td>
                                    <?php 
                                    
                                    if($serial->entry == 'IN'){
                                        echo Grn::model()->findByPk($serial->p_id)->code;
                                    }                                    
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                    
                                    if($serial->entry == 'OUT'){
                                        echo Mr::model()->findByPk($serial->p_id)->code;
                                    }                                    
                                    ?>
                                </td>
                                <td>
                                <?php                                     
                                    if($serial->entry == 'IN'){
                                        echo GrnItems::model()->findByPk($serial->f_id)->goodsSts->name;
                                    }else{
                                        echo PickItems::model()->findByPk($serial->f_id)->goodsSts->name;
                                    }                                   
                                    ?>
                                </td>
                                <td><?php echo $serial->entry; ?></td>
                            </tr>
                            <?php
                            $num += 1;
                        }
                        ?>
                    </tbody>
                </table>

            </div>

        </div>
    </body>
</html>



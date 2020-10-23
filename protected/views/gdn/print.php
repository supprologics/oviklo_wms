<?php
$mPDF1 = new mpdf('', 'A4', 0, 'Arial', 20, 5, 40, 20, 4, 4, 'P');
$model = Mr::model()->findByPk($model->id);
$barcode = "GDN-" . $model->id;
//HEADER CONTENT
ob_start();
?>



<div>
    <div style="border-bottom: 1px solid #e5e5e5; padding: 10px 0;">
        <table width="100%" cellspacing="0" cellpadding="0">
            <tr>
                <td>
                    <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/logo.jpg" width="150px" />
                </td>
                <td>
                    <h3 style="text-align: left; padding-bottom: 25px;">OVIKLO INTERNATIONAL (PVT) LTD.</h3><br/>
                    <table cellspacing="0" cellpadding="0" width="100%">
                        <tr>
                            <td style="font-size: 10px; padding-right: 30px; vertical-align: top;" >
                                <h4>Head Office</h4>
                                <p>
                                    OVIKLO International (Pvt) Ltd<br/>
                                    539/3A, New Kandy Road,<br/> 
                                    Biyagama, Sri Lanka.<br/>
                                    Tel :+94 11 2489704, Fax +94 11 2468080<br/>
                                    Email: info@oviklo.com
                                </p>
                            </td>
                            <td style="font-size: 10px; vertical-align: top;">
                                <h4>Warehouse</h4>
                                <p>
                                    <?php echo nl2br($model->warehouse->address); ?>
                                </p>
                            </td>
                            <td width="30%" style="font-size: 10px; text-align: center; vertical-align: top;">
                                
                        <barcode code="<?php echo $barcode; ?>" type="C39" text="<?php echo $barcode; ?>" height="1.2" />
                        <h2><?php echo $model->code; ?></h2>
                </td>
            </tr>
        </table>
        </td>
        </tr>
        </table>
    </div>
    <h3 style="text-align: center;">Goods Dispatch Note</h3>


</div>

<?php
$header = ob_get_contents();
ob_end_clean();
//HEADER CONTENT END--
//BODY CONTENT
ob_start();
?>

<style>
    .no-border td{
        border: none !important;
        padding: 2px;
        font-size: 11px;
    }

    .borderd{
        border-collapse: collapse;
        table-layout: fixed;
    }
    .borderdAll th{
        padding: 2px;
        font-size: 10px;
        background: #e5e5e5;
        border: 1px solid #484848;
        text-align: left;
        font-weight: bold;
    }
    .borderd td{
        padding: 2px;
        font-size: 12px;
        vertical-align: top;
    }

    .borderdAll td{
        padding: 2px;
        font-size: 12px;
        border: 1px solid #484848;
        vertical-align: top;
    }

    .right{
        text-align: right !important;
    }

    .morepad td{
        padding: 10px 5px;
    }


</style>

<div>
    <table cellspacing="0" cellpadding="0" width="100%" class="borderd">
        <tr>
            <td width="50%">

                <table cellspacing="0" cellpadding="0" width="100%" class="no-border">
                    <tr>
                        <td>Code</td>
                        <td><?php echo $model->customers->code; ?></td>
                    </tr>
                    <tr>
                        <td>Request By</td>
                        <td><?php echo $model->customers->name; ?></td>
                    </tr>
                    <tr>
                        <td>Address</td>
                        <td><?php echo nl2br($model->customers->address); ?></td>
                    </tr>

                    <tr>
                        <td>Delivery Date</td>
                        <td><?php echo $model->delivery_date; ?></td>
                    </tr>
                    <tr>
                        <td>Created</td>
                        <td><?php echo $model->created; ?></td>
                    </tr>
                    <tr>
                        <td>Last Update</td>
                        <td><?php echo $model->last_update; ?></td>
                    </tr>
                    <tr>
                        <td>Project</td>
                        <td><?php echo $model->project->name; ?></td>
                    </tr>
                </table>



            </td>
            <td>
                <table cellspacing="0" cellpadding="0" width="100%" class="no-border">

                    <tr>
                        <td>MR No#</td>
                        <td><?php echo $model->code; ?></td>
                    </tr>
                    <tr>
                        <td>Warehouse</td>
                        <td><?php echo $model->warehouse->name; ?></td>
                    </tr>
                    <tr>
                        <td>Project</td>
                        <td><?php echo $model->project->name; ?></td>
                    </tr>
                    <tr>
                        <td>Collector-name</td>
                        <td><?php echo $model->col_name; ?></td>
                    </tr>
                    <tr>
                        <td>Collector-NIC</td>
                        <td><?php echo $model->col_nic; ?></td>
                    </tr>
                    <tr>
                        <td>Vehicle No#</td>
                        <td><?php echo $model->col_vehicle; ?></td>
                    </tr>
                    <tr>
                        <td>Destination-01</td>
                        <td><?php echo $model->dest1_name; ?></td>
                    </tr>
                    <tr>
                        <td>Destination-02</td>
                        <td><?php echo $model->dest2_name; ?></td>
                    </tr>
                    <tr>
                        <td>Link #</td>
                        <td><?php echo $model->link_name; ?></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table cellspacing="0" cellpadding="0" width="100%" class="borderd">
        <tr>
            <td>Vehicle-IN</td>
            <td>Loading-Start</td>
            <td>Loading-End</td>
            <td>Vehicle-OUT</td>
        </tr>
        <tr>
            <td><?php echo $model->vehicle_in; ?></td>
            <td><?php echo $model->start_time; ?></td>
            <td><?php echo $model->end_time; ?></td>
            <td><?php echo $model->vehicle_out; ?></td>
        </tr>
    </table>
</div>

<div>
    <table cellspacing="0" cellpadding="0" width="100%" class="borderdAll" style="overflow: wrap;">

        <tr>

            <th></th>
            <th>SKU CODE</th>
            <th>DESCRIPTION</th>
            <th>BATCH</th>
            <th>STATUS</th>
            <th style="text-align: right">UOM</th>
            <th style="text-align: right">QTY</th>
        </tr>


        <?php
        $po_id = $model->id;
        $list = Yii::app()->db->createCommand("SELECT id FROM pick_items WHERE mr_id = '$po_id'")->queryAll();
        $num = 1;
        $tot = 0;
        $totamount = 0;

        foreach ($list as $value) {
            $poitems = PickItems::model()->findByPk($value['id']);
            ?>
            <tr>
                <td><?php echo $num; ?></td>
                <td><?php echo $poitems->sku->code; ?></td>
                <td><?php echo $poitems->sku->description; ?></td>
                <td><?php echo $poitems->batch_no; ?></td>
                <td><?php echo $poitems->goodsSts->name; ?></td>
                <td style="text-align: right"><?php echo $poitems->sku->uom->sym; ?></td>
                <td style="text-align: right"><?php echo $poitems->qty; ?></td>                
            </tr>
            <?php
            //ADD REMARKS
            if (!empty($poitems->remarks)) {
                ?>

                <tr>
                    <td colspan="2" style="text-align: right"><small>Remarks</small></td>
                    <td colspan="5"><?php echo $poitems->remarks; ?></td>
                </tr>

                <?php
            }

            $num += 1;
            $tot += $poitems->qty;

            $listSe = Yii::app()->db->createCommand("SELECT serials.code FROM `pick_serials`,pick_items,serials WHERE pick_items_id = pick_items.id AND pick_items.mr_id = $po_id AND serials.id = pick_serials.serials_id AND pick_items.id = '" . $poitems->id . "'")->queryAll();
            if (count($listSe) > 0) {

                echo "<tr><th colspan='6'></th><th>Serials</th></tr>";


                foreach ($listSe as $valueSerials) {
                    ?>
                    <tr>
                        <td colspan="6"></td>
                        <td><?php echo $valueSerials['code'] ?></td>
                    </tr>
                    <?php
                }
            }
        }
        ?>



    </table>

    <div style="font-size: 10px; border: 1px solid #e5e5e5; padding: 10px; margin-top: 10px;">
        <h5 style="margin: 0px; padding: 0px;">Remarks / Notes</h5>
        <p style="padding: 0px; margin: 0px;">
            <?php echo nl2br($model->remarks); ?>
        </p>
    </div>

    <div style="margin-top: 90px; font-size: 10px; margin-bottom: 55px;">
        <table cellspacing="0" cellpadding="0" width="100%" >
            <tr>

                <td style="vertical-align: top;"><?php echo $model->picked->name; ?><br/>............................................<br/>Prepared By</td>
                <td style="vertical-align: top;"><br/>............................................<br/>Authorized by By</td>
                <td style="vertical-align: top;"><br/>
                    ............................................<br/>Accepted by (Customer) <br/>
                    <small>Above goods received in good condition.</small>
                </td>
            </tr>
        </table>
    </div>

    <div style="margin-top: 50px; font-size: 10px; margin-bottom: 55px;">
        <table cellspacing="0" cellpadding="0" width="100%" class="borderd morepad" style="font-size: 10px;" >
            <tr>
                <td style="font-size: 10px;" width="25%">Name</td>
                <td></td>
            </tr>
            <tr>
                <td style="font-size: 10px;">NIC</td>
                <td></td>
            </tr>
            <tr>
                <td style="font-size: 10px;">Phone No</td>
                <td></td>
            </tr>
            <tr>
                <td style="font-size: 10px;">Time</td>
                <td></td>
            </tr>
            <tr>
                <td>Date</td>
                <td></td>
            </tr>
        </table>
    </div>
</div>

<?php
$output = ob_get_contents();
ob_end_clean();
//BODY CONTENT END
//FOOTER CONTENT
ob_start();
?>


<div style=" padding: 15px; font-size: 8px; text-align: center;">
    Page {PAGENO}/{nbpg}  ,   Printed @ <?php echo date("Y-m-d H:i:s"); ?> By iSPACE
</div>


<?php
$footer = ob_get_contents();
ob_end_clean();
//FOOTER CONTENT END


$mPDF1->SetHTMLHeader($header);
$mPDF1->SetHTMLFooter($footer);
$mPDF1->allow_output_buffering = true;


switch ($model->online) {
    case 1:
        $mPDF1->SetWatermarkText('DRAFT');
        break;
    case 9:
        $mPDF1->SetWatermarkText('CANCELED');
        break;

    default:
        break;
}

$mPDF1->showWatermarkText = true;
$mPDF1->WriteHTML($output);
$mPDF1->Output($model->code, "I");

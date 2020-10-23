<?php
$mPDF1 = new mpdf('', 'A4', 0, 'Arial', 20, 5, 102, 20, 4, 4, 'P');
$model = Grn::model()->findByPk($model->id);
$barcode = "GRN-" . $model->id;
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
    <h3 style="text-align: center;">Goods Receiving Note </h3>

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
                            <td>Client</td>
                            <td><?php echo $model->customers->name; ?></td>
                        </tr>
                        <tr>
                            <td>Address</td>
                            <td><?php echo nl2br($model->customers->address); ?></td>
                        </tr>

                        <tr>
                            <td>Pages</td>
                            <td>{PAGENO}/{nbpg}</td>
                        </tr>
                        <tr>
                            <td>Effected Date</td>
                            <td><?php echo $model->eff_date; ?></td>
                        </tr>
                        <tr>
                            <td>Created</td>
                            <td><?php echo $model->created; ?></td>
                        </tr>
                        <tr>
                            <td>Last Update</td>
                            <td><?php echo $model->last_update; ?></td>
                        </tr>
                    </table>



                </td>
                <td>
                    <table cellspacing="0" cellpadding="0" width="100%" class="no-border">

                        <tr>
                            <td>GRN No#</td>
                            <td><?php echo $model->code; ?></td>
                        </tr>
                        <tr>
                            <td>Warehouse</td>
                            <td><?php echo $model->warehouse->name; ?></td>
                        </tr>
                        <tr>
                            <td>Container</td>
                            <td><?php echo $model->container_no; ?></td>
                        </tr>
                        <tr>
                            <td>Vehicle No#</td>
                            <td><?php echo $model->vehicle_no; ?></td>
                        </tr>
                        <tr>
                            <td>Packing-List</td>
                            <td><?php echo $model->packinglist_no; ?></td>
                        </tr>
                        <tr>
                            <td>Ref#</td>
                            <td><?php echo $model->ref_no; ?></td>
                        </tr>
                        <tr>
                            <td>Project</td>
                            <td><?php echo $model->project->name; ?></td>
                        </tr>
                        <tr>
                            <td>Supplier</td>
                            <td><?php echo $model->supplier; ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
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
        padding: 3px;
    }

    .borderd{
        border-collapse: collapse;
        table-layout: fixed;
    }
    .borderd th{
        padding: 5px;
        font-size: 11px;
        background: #e5e5e5;
        text-align: left;
        font-weight: normal;
    }
    .borderd td{
        padding: 5px;
        font-size: 12px;
        border: 1px solid #e5e5e5;
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
    <table cellspacing="0" cellpadding="0" width="100%" class="borderd" style="overflow: wrap;">

        <tr>

            <th></th>
            <th>SKU CODE</th>
            <th wisth="40%">DESCRIPTION</th>
            <th style="text-align: right">UOM</th>
            <th>BATCH</th>
            <th>PKG</th>
            <th>STATUS</th>
            <th>EXPIRE</th>
            <th style="text-align: right">QTY</th>

        </tr>


        <?php
        $po_id = $model->id;
        $list = Yii::app()->db->createCommand("SELECT id FROM grn_items WHERE grn_id = '$po_id'")->queryAll();
        $num = 1;
        $tot = 0;
        $totamount = 0;

        foreach ($list as $value) {
            $poitems = GrnItems::model()->findByPk($value['id']);
            ?>
            <tr>
                <td><?php echo $num; ?></td>
                <td><?php echo $poitems->sku->code; ?></td>
                <td><?php echo $poitems->sku->description; ?></td>
                <td style="text-align: right"><?php echo $poitems->sku->uom->sym; ?></td>
                <td><?php echo $poitems->batch_no; ?></td>
                <td><?php echo $poitems->pkg_no; ?></td>
                <td><?php echo $poitems->goodsSts->name; ?></td>
                <td><?php echo!empty($poitems->expire_date) ? $poitems->expire_date : "N/A"; ?></td>
                <td style="text-align: right"><?php echo $poitems->qty; ?></td>                
            </tr>
            <tr>
                <td colspan="9" style="padding: 0px; padding-left: 60%;">

                    <table cellspacing="0" cellpadding="0" width="100%" class="borderd" style="overflow: wrap;">
                        <tr>
                            <th>NO#</th>
                            <th>SERIAL</th>
                            <th>ASSET</th>
                        </tr>
                        <?php
                        $list = Yii::app()->db->createCommand("SELECT id FROM grn_serials WHERE grn_items_id = '" . $value['id'] . "' ")->queryAll();
                        $num = 1;
                        foreach ($list as $value) {

                            $ser = GrnSerials::model()->findByPk($value['id']);
                            ?>
                            <tr>
                                <td width="15%"><?php echo $num; ?></td>
                                <td width="50%"><?php echo $ser->serials->code; ?></td>
                                <td><?php echo $ser->serials->asset; ?></td>
                            </tr>
                            <?php
                            
                            $num += 1;
                        }
                        ?>
                    </table>

                </td>
            </tr>
    <?php
    $num += 1;
    $tot += $poitems->qty;
}
?>
    </table>

    <div style="font-size: 10px; border: 1px solid #e5e5e5; padding: 10px; margin-top: 10px;">
        <h5 style="margin: 0px; padding: 0px;">Remarks / Notes</h5>
        <p style="padding: 0px; margin: 0px;">
<?php echo nl2br($model->remarks); ?>
        </p>
    </div>

    <div style="margin-top: 80px; font-size: 10px; margin-bottom: 55px;">
        <table cellspacing="0" cellpadding="0" width="100%" >
            <tr>
                <td><?php echo $model->users->name; ?><br/>............................................<br/>Prepared By</td>
                <td style="text-align: right;"><br/>............................................<br/>Authorized by By</td>
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
    Printed @ <?php echo date("Y-m-d H:i:s"); ?> By iSPACE
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

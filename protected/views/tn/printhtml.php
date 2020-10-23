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
                                    <?php echo nl2br($model->warehouseFrom->address); ?>
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
    <h3 style="text-align: center;">Goods Transfer Note </h3>

    <div>
        <table cellspacing="0" cellpadding="0" width="100%" class="borderd">
            <tr>
                <td width="50%">

                    <table cellspacing="0" cellpadding="0" width="100%" class="no-border">
                        <tr>
                            <td>Doc No#</td>
                            <td><?php echo $model->code; ?></td>
                        </tr>
                        <tr>
                            <td>Warehouse</td>
                            <td><?php echo $model->warehouseFrom->name; ?></td>
                        </tr>
                        <tr>
                            <td>Zone</td>
                            <td><?php echo $model->locationsFrom->name; ?></td>
                        </tr>
                        <tr>
                            <td>Project</td>
                            <td><?php echo $model->projectFrom->name; ?></td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td><?php echo $model->goodsStsFrom->name; ?></td>
                        </tr>
                        <tr>
                            <td>Location</td>
                            <td><?php echo $model->sub_location_from; ?></td>
                        </tr>
                    </table>



                </td>
                <td>
                    <table cellspacing="0" cellpadding="0" width="100%" class="no-border">

                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>Warehouse</td>
                            <td><?php echo $model->warehouseTo->name; ?></td>
                        </tr>
                        <tr>
                            <td>Zone</td>
                            <td><?php echo $model->locationsTo->name; ?></td>
                        </tr>
                        <tr>
                            <td>Project</td>
                            <td><?php echo $model->projectTo->name; ?></td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td><?php echo $model->goodsStsTo->name; ?></td>
                        </tr>
                        <tr>
                            <td>Location</td>
                            <td><?php echo $model->sub_location; ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</div>

<div>
    <table cellspacing="0" cellpadding="0" width="100%" class="borderd" style="overflow: wrap;">

        <tr>

            <th></th>
            <th>SKU CODE</th>
            <th wisth="40%">DESCRIPTION</th>
            <th style="text-align: right">UOM</th>
            <th>BATCH</th>
            <th>STATUS</th>
            <th>EXPIRE</th>
            <th style="text-align: right">QTY</th>

        </tr>


        <?php
        $po_id = $model->id;
        $list = Yii::app()->db->createCommand("SELECT id FROM tn_items WHERE tn_id = '$po_id'")->queryAll();
        $num = 1;
        $tot = 0;
        $totamount = 0;

        foreach ($list as $value) {
            $poitems = TnItems::model()->findByPk($value['id']);
            ?>
            <tr>
                <td><?php echo $num; ?></td>
                <td><?php echo $poitems->sku->code; ?></td>
                <td><?php echo $poitems->sku->description; ?></td>
                <td style="text-align: right"><?php echo $poitems->sku->uom->sym; ?></td>
                <td><?php echo $poitems->batch_no; ?></td>
                <td><?php echo $poitems->tn->goodsStsTo->name; ?></td>
                <td><?php echo!empty($poitems->expire_date) ? $poitems->expire_date : "N/A"; ?></td>
                <td style="text-align: right"><?php echo $poitems->qty; ?></td>                
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



<div style=" padding: 15px; font-size: 8px; text-align: center;">
    Printed @ <?php echo date("Y-m-d H:i:s"); ?> By iSPACE
</div>

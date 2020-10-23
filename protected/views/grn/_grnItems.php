<form action="#" method="post" id="inner_table">
    <table class="table table-sm table-hover table-sp" >

        <thead>
            <tr class="table-active">

                <th><input type="checkbox" id="selectall"></th>
                <th></th>
                <th>BNC</th>
                <th>CODE</th>
                <th>DESCRIPTION</th>

                <th>BATCH</th>
                <th>PACKAGE</th>
                <th>STATUS</th>
                <th>EXPIRE</th>
                <th class="text-right">UOM</th>
                <th class="text-right">Qty</th>

        </thead>
        <tbody class="lineitems" >

            <?php
            $po = Grn::model()->findByPk($data['id']);

            $total = 0;
            $num = 1;

            $list = Yii::app()->db->createCommand("SELECT id FROM grn_items WHERE grn_id = '" . $data['id'] . "'")->queryAll();
            foreach ($list as $itemArray) {

                $item = GrnItems::model()->findByPk($itemArray['id']);
                ?>
                <tr data-id="<?php echo $item->id; ?>" >

                    <td><input id="line_<?php echo $item->id; ?>" type="checkbox" class="chk" value="<?php echo $item->id; ?>" /></td>
                    <td class="selitem"><?php echo $num; ?></td>
                    <td class="selitem"><?php echo $item->code; ?></td>
                    <td class="selitem"><?php echo $item->sku->code; ?></td>
                    <td class="selitem"><?php echo $item->sku->description; ?></td>


                    <td width="10%" class=" p-0"><input type="text" name="batch_no[<?php echo $item->id ?>]" autocomplete="off" class="form-control form-control-sm" value="<?php echo $item->batch_no; ?>" /></td>
                    <td width="10%" class=" p-0"><input type="text" name="pkg_no[<?php echo $item->id ?>]" autocomplete="off" class="form-control form-control-sm" value="<?php echo $item->pkg_no; ?>" /></td>
                    <td width="10%" class=" p-0">
                        <select name="goods_sts_id[<?php echo $item->id ?>]" class="custom-select custom-select-sm">

                            <?php
                            $datalist = GoodsSts::model()->findAll();
                            foreach ($datalist as $value) {
                                if ($value->id == $item->goods_sts_id) {
                                    $option = " selected ";
                                } else {
                                    $option = "";
                                }
                                echo "<option $option value='" . $value->id . "'>" . $value->name . "</option>";
                            }
                            ?>
                        </select>
                    </td>
                    <td width="10%" class=" p-0">
                        <?php if (!empty($item->expire_date)) { ?>
                            <input type="text" name="expire_date[<?php echo $item->id ?>]" autocomplete="off" class="form-control datepicker form-control-sm" value="<?php echo $item->expire_date; ?>" />
                        <?php } ?>
                    </td>
                    <td class="text-right"><?php echo $item->sku->uom->sym; ?></td>
                    <td width="10%" class="text-right p-0"><input type="text" name="qty[<?php echo $item->id ?>]" autocomplete="off" class="form-control text-right form-control-sm" value="<?php echo $item->qty; ?>" /></td>



                </tr>
                <?php
                $num += 1;
            }
            ?>

        </tbody>
    </table>
</form>

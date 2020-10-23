<form action="#" method="post" id="inner_table">
    <table class="table table-sm table-hover table-sp" >
        <thead>
            <tr class="table-active">

                <th><input type="checkbox" id="selectall"></th>
                <th></th>
                <th>CODE</th>
                <th>DESCRIPTION</th>
                <th>BATCH</th>
                <th>EXPIRE</th>
                <th>REMARKS</th>
                <th class="text-right">UOM</th>
                <th class="text-right">Qty</th>

        </thead>
        <tbody class="lineitems" >

            <?php
            
            $total = 0;
            $num = 1;

            $list = Yii::app()->db->createCommand("SELECT id FROM tn_items WHERE tn_id = '" . $data['id'] . "'")->queryAll();
            foreach ($list as $itemArray) {

                $item = TnItems::model()->findByPk($itemArray['id']);
                ?>
                <tr data-id="<?php echo $item->id; ?>" >

                    <td><input id="line_<?php echo $item->id; ?>" type="checkbox" class="chk" value="<?php echo $item->id; ?>" /></td>
                    <td class="selitem"><?php echo $num; ?></td>
                    <td class="selitem"><?php echo $item->sku->code; ?></td>
                    <td class="selitem"><?php echo $item->sku->description; ?></td>
                    <td class="selitem"><?php echo $item->batch_no; ?></td>
                    <td class="selitem"><?php echo $item->expire_date; ?></td>
                    <td class="selitem"><?php echo $item->remarks; ?></td>
                    <td class="text-right"><?php echo $item->sku->uom->sym; ?></td>
                    <td width="10%" class="text-right"><?php echo $item->qty; ?></td>



                </tr>
                <?php
                $num += 1;
            }
            ?>

        </tbody>
    </table>
</form>
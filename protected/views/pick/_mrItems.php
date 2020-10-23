<form action="#" method="post" id="inner_table">
    <table class="table table-sm table-hover table-sp" >

        <thead>
            <tr class="table-active">

                <th><input type="checkbox" id="selectall"></th>
                <th></th>
                <th>CODE</th>
                <th>DESCRIPTION</th>
                <th>BATCH</th>
                <th>PKG</th>
                <th>EXPIRE</th>
                
                <th>LOCATION</th>
                <th>SUB/L</th>
                 <th>REMARKS</th>
               
                <th class="text-right">UOM</th>
                <th class="text-right">QTY</th>
                <th class="text-right">PICKED</th>
                
        </thead>
        <tbody class="lineitems" >

            <?php
            $po = Grn::model()->findByPk($data['id']);

            $total = 0;
            $num = 1;

            $list = Yii::app()->db->createCommand("SELECT id FROM pick_items WHERE mr_id = '" . $data['id'] . "'")->queryAll();
            foreach ($list as $itemArray) {

                $item = PickItems::model()->findByPk($itemArray['id']);
                
                if($item->qty_req != $item->qty){
                    $clss = 'table-warning';
                }else{
                    $clss = "";
                }
                
                ?>
            <tr data-id="<?php echo $item->id; ?>" class="<?php echo $clss; ?>" >

                    <td><input id="line_<?php echo $item->id; ?>" type="checkbox" class="chk" value="<?php echo $item->id; ?>" /></td>
                    <td class="selitem"><?php echo $num; ?></td>
                    <td class="selitem"><?php echo $item->sku->code; ?></td>
                    <td class="selitem"><?php echo $item->sku->description; ?></td>
                    <td class="selitem"><?php echo $item->batch_no; ?></td>
                    <td class="selitem"><?php echo $item->pkg_no; ?></td>
                    <td class="selitem"><?php echo $item->expire_date; ?></td>
                    
                    <td class="selitem"><?php echo $item->locations->name; ?></td>
                    <td class="selitem"><?php echo $item->sub_location; ?></td>
                    <td class="selitem p-0"><input type="text" name="remarks_item[<?php echo $item->id ?>]" autocomplete="off" class="form-control form-control-sm" value="<?php echo $item->remarks; ?>" /></td>
                    <td class="text-right"><?php echo $item->sku->uom->sym; ?></td>
                    <td class="text-right"><?php echo $item->qty_req; ?></td>
                    <td width="10%" class="text-right p-0">
                        <input type="text" name="qty[<?php echo $item->id ?>]" autocomplete="off" class="form-control text-right form-control-sm" value="<?php echo $item->qty; ?>" />
                    </td>
                </tr>
                <?php
                $num += 1;
            }
            ?>

        </tbody>
    </table>
</form>

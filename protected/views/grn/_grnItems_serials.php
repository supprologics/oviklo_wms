<form action="#" method="post" id="inner_table">
    <table class="table table-sm table-hover table-sp" >

        <thead>
            <tr class="table-active">

                <th><input type="checkbox" id="selectall"></th>
                <th></th>
                <th>CODE</th>
                <th>DESCRIPTION</th>
                <th>SERIAL</th>
                <th>ASSET</th>
                <th class="text-right">TIMESTAMP</th>
        </thead>
        <tbody class="lineitems" >

            <?php
            $po = Grn::model()->findByPk($data['id']);

            $total = 0;
            $num = 1;

            if(isset($data['grn_items_id'])){
                $grnItems = " AND grn_items.id = '". $data['grn_items_id'] ."' ";
            }else{
                $grnItems = "";
            }
            
            
            $list = Yii::app()->db->createCommand("SELECT grn_serials.id as grnSerialId FROM grn_serials,grn_items WHERE grn_items.grn_id = '". $data['id'] ."' AND  grn_serials.grn_items_id = grn_items.id $grnItems ")->queryAll();
            foreach ($list as $itemArray) {

                $item = GrnSerials::model()->findByPk($itemArray['grnSerialId']);
                ?>
                <tr data-id="<?php echo $item->id; ?>" >
                    <td><input id="line_<?php echo $item->id; ?>" type="checkbox" class="chk" value="<?php echo $item->id; ?>" /></td>
                    <td class="selitem"><?php echo $num; ?></td>
                    <td class="selitem"><?php echo $item->serials->sku->code; ?></td>
                    <td class="selitem"><?php echo $item->serials->sku->description; ?></td>
                    <td class="selitem"><?php echo $item->serials->code; ?></td>
                    <td class="selitem"><?php echo $item->serials->asset; ?></td>
                    <td class="selitem text-right"><?php echo $item->created; ?></td>
                </tr>
                <?php
                $num += 1;
            }
            ?>

        </tbody>
    </table>
</form>

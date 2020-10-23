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
                <th class="text-right"></th>
        </thead>
        <tbody class="lineitems" >

            <?php
            $po = Mr::model()->findByPk($data['id']);

            $total = 0;
            $num = 1;

            if(isset($data['pick_items_id'])){
                $grnItems = " AND pick_items.id = '". $data['pick_items_id'] ."' ";
            }else{
                $grnItems = "";
            }
            
            
            $list = Yii::app()->db->createCommand("SELECT pick_serials.id as pickSerialId FROM pick_serials,pick_items WHERE pick_items.mr_id = '". $data['id'] ."' AND  pick_serials.pick_items_id = pick_items.id $grnItems ")->queryAll();
            foreach ($list as $itemArray) {

                $item = PickSerials::model()->findByPk($itemArray['pickSerialId']);
                ?>
                <tr data-id="<?php echo $item->id; ?>" >
                    <td><input id="line_<?php echo $item->id; ?>" type="checkbox" class="chk" value="<?php echo $item->id; ?>" /></td>
                    <td class="selitem"><?php echo $num; ?></td>
                    <td class="selitem"><?php echo $item->serials->sku->code; ?></td>
                    <td class="selitem"><?php echo $item->serials->sku->description; ?></td>
                    <td class="selitem"><?php echo $item->serials->code; ?></td>
                    <td class="selitem"><?php echo $item->serials->asset; ?></td>
                    <td class="selitem text-right"><?php echo $item->created; ?></td>
                    <td class="selitem text-right">
                        <a class="PickSerials-delete" href="#" data-id="<?php echo $item->id; ?>" title="Delete"><span class="oi oi-x text-danger"></span></a>
        
                    </td>
                </tr>
                <?php
                $num += 1;
            }
            ?>

        </tbody>
    </table>
</form>

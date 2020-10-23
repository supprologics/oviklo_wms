<form action="#" method="post" id="inner_table">
    <table class="table table-sm table-hover table-sp" >

        <thead>
            <tr class="table-active">

                <th></th>
                <th>BNC</th>
                <th>CODE</th>
                <th>DESCRIPTION</th>
                
                <th>BATCH</th>
                <th>PACKAGE</th>
                <th>STATUS</th>
                <th>EXPIRE</th>
                
                <th>ZONE</th>
                <th>LOCATION</th>
                <th class="text-right">QTY</th>
                
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

                    <td class="selitem"><?php echo $num; ?></td>
                    <td class="selitem"><?php echo $item->code; ?></td>
                    <td class="selitem"><?php echo $item->sku->code; ?></td>
                    <td class="selitem"><?php echo $item->sku->description; ?></td>
                    

                    <td><?php echo $item->batch_no; ?></td>
                    <td><?php echo $item->pkg_no; ?></td>
                    <td><?php echo $item->goodsSts->name; ?></td>
                    <td><?php echo $item->expire_date; ?></td>
                    
                    <td width="100px" class="p-0">
                        <select name="locations_id[<?php echo $item->id ?>]" class="custom-select custom-select-sm">
                            <?php
                            
                            $list = Yii::app()->db->createCommand("SELECT * FROM locations WHERE warehouse_id = '". $po->warehouse_id ."'")->queryAll();
                            foreach ($list as $value) {
                                
                                if($item->locations_id == $value['id']){
                                    $option = "selected";
                                }else{
                                    $option = "";
                                }
                                
                                echo "<option $option value='". $value['id'] ."'>". $value['name'] ."</option>";
                            }
                            ?>
                        </select>
                    </td>
                    <td  width="100px" class="p-0">
                        <input type="text" name="sub_location[<?php echo $item->id ?>]" autocomplete="off" class="form-control form-control-sm" value="<?php echo $item->sub_location; ?>" />
                    </td>
                    <td class="text-right">
                        <?php echo $item->qty; ?> <?php echo $item->sku->uom->sym; ?>
                    </td>
                    
                    
                </tr>
                <?php
                $num += 1;
            }
            ?>

        </tbody>
    </table>
</form>

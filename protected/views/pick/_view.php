<?php
/* @var $this MrController */
/* @var $data Mr */


$dataobj = Mr::model()->findByPk($data['id']);
?>


<div class="row datarow no-gutters pick<?php echo $data['online']; ?>" data-id="<?php echo $data['id']; ?>" data-st="<?php echo $dataobj->online; ?>">

    <div class='col-1 cells px-1 clickable'>
        <?php echo $data['code']; ?>
    </div>
    <div class='col-2 cells px-1 clickable'>
        <?php echo $dataobj->customers->name; ?>
    </div>
    <div class='col-2 cells px-1 clickable'>
        <?php echo $dataobj->warehouse->name; ?>
    </div>
    
    <div class='col-1 cells px-1 clickable'>
        <?php echo $data['eff_date']; ?>
    </div>
    <div class='col-1 cells px-1 clickable'>
        <?php echo $data['delivery_date']; ?>
    </div>
    <div class='col-2 cells px-1 clickable'>
        <?php echo $data['col_name']; ?>
    </div>
    <div class='col-1 cells px-1 clickable'>
        <?php echo $data['col_nic']; ?>
    </div>
    <div class='col-1 cells px-1 clickable'>
        <?php echo $data['dest1_name']; ?>
    </div>
    <div class='col-1 cells px-1 clickable'>
        <?php echo $data['col_vehicle']; ?>
    </div>

</div>

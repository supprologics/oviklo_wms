<?php
/* @var $this WarehouseController */
/* @var $data Warehouse */


$dataobj = Warehouse::model()->findByPk($data['id']);

?>


<div class="row datarow no-gutters" data-id="<?php echo $data['id']; ?>">
    
    <div class='col cells px-1 clickable'>
	<?php echo $data['code']; ?>
</div>
<div class='col cells px-1 clickable'>
	<?php echo $data['name']; ?>
</div>
<div class='col cells px-1 clickable'>
	<?php echo $data['address']; ?>
</div>
<div class='col cells px-1 clickable'>
	<?php echo $data['mobile']; ?>
</div>
<div class='col cells px-1 clickable'>
	<?php echo $data['email']; ?>
</div>
    
    <div class='col-sm-1 cells btn-cog text-right px-1'>
        <a class="Warehouse-update" href="#" data-id="<?php echo $data['id']; ?>" model="Warehouse" controler="WarehouseController" data-toggle="tooltip" data-placement="top" title="Update"><span class="oi oi-cog"></span></a>
        <a class="Warehouse-delete" href="#" data-id="<?php echo $data['id']; ?>" model="Warehouse" controler="WarehouseController" data-toggle="tooltip" data-placement="top" title="Delete"><span class="oi oi-x"></span></a>
    </div>

    
</div>

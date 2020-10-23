<?php
/* @var $this MrItemsController */
/* @var $data MrItems */


$dataobj = MrItems::model()->findByPk($data['id']);

?>


<div class="row datarow no-gutters" data-id="<?php echo $data['id']; ?>">
    
    <div class='col cells px-1 clickable'>
	<?php echo $data['mr_id']; ?>
</div>
<div class='col cells px-1 clickable'>
	<?php echo $data['sku_id']; ?>
</div>
<div class='col cells px-1 clickable'>
	<?php echo $data['project']; ?>
</div>
<div class='col cells px-1 clickable'>
	<?php echo $data['category']; ?>
</div>
<div class='col cells px-1 clickable'>
	<?php echo $data['product_status']; ?>
</div>
<div class='col cells px-1 clickable'>
	<?php echo $data['batch']; ?>
</div>
<div class='col cells px-1 clickable'>
	<?php echo $data['qty']; ?>
</div>
<div class='col cells px-1 clickable'>
	<?php echo $data['warehouse']; ?>
</div>
<div class='col cells px-1 clickable'>
	<?php echo $data['qty_issued']; ?>
</div>
<div class='col cells px-1 clickable'>
	<?php echo $data['remarks']; ?>
</div>
    
    <div class='col-sm-1 cells btn-cog text-right px-1'>
        <a class="MrItems-update" href="#" data-id="<?php echo $data['id']; ?>" model="MrItems" controler="MrItemsController" data-toggle="tooltip" data-placement="top" title="Update"><span class="oi oi-cog"></span></a>
        <a class="MrItems-delete" href="#" data-id="<?php echo $data['id']; ?>" model="MrItems" controler="MrItemsController" data-toggle="tooltip" data-placement="top" title="Delete"><span class="oi oi-x"></span></a>
    </div>

    
</div>

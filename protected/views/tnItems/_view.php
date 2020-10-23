<?php
/* @var $this TnItemsController */
/* @var $data TnItems */


$dataobj = TnItems::model()->findByPk($data['id']);

?>


<div class="row datarow no-gutters" data-id="<?php echo $data['id']; ?>">
    
    <div class='col cells px-1 clickable'>
	<?php echo $data['tn_id']; ?>
</div>
<div class='col cells px-1 clickable'>
	<?php echo $data['sku_id']; ?>
</div>
<div class='col cells px-1 clickable'>
	<?php echo $data['batch_no']; ?>
</div>
<div class='col cells px-1 clickable'>
	<?php echo $data['qty']; ?>
</div>
<div class='col cells px-1 clickable'>
	<?php echo $data['expire_date']; ?>
</div>
<div class='col cells px-1 clickable'>
	<?php echo $data['manf_date']; ?>
</div>
<div class='col cells px-1 clickable'>
	<?php echo $data['remarks']; ?>
</div>
<div class='col cells px-1 clickable'>
	<?php echo $data['grn_id']; ?>
</div>
    
    <div class='col-sm-1 cells btn-cog text-right px-1'>
        <a class="TnItems-update" href="#" data-id="<?php echo $data['id']; ?>" model="TnItems" controler="TnItemsController" data-toggle="tooltip" data-placement="top" title="Update"><span class="oi oi-cog"></span></a>
        <a class="TnItems-delete" href="#" data-id="<?php echo $data['id']; ?>" model="TnItems" controler="TnItemsController" data-toggle="tooltip" data-placement="top" title="Delete"><span class="oi oi-x"></span></a>
    </div>

    
</div>

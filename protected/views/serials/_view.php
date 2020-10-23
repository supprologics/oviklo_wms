<?php
/* @var $this SerialsController */
/* @var $data Serials */


$dataobj = Serials::model()->findByPk($data['id']);

?>


<div class="row datarow no-gutters" data-id="<?php echo $data['id']; ?>">
    
    <div class='col cells px-1 clickable'>
	<?php echo $data['customers_id']; ?>
</div>
<div class='col cells px-1 clickable'>
	<?php echo $data['project_id']; ?>
</div>
<div class='col cells px-1 clickable'>
	<?php echo $data['sku_id']; ?>
</div>
<div class='col cells px-1 clickable'>
	<?php echo $data['code']; ?>
</div>
<div class='col cells px-1 clickable'>
	<?php echo $data['asset']; ?>
</div>
    
    <div class='col-sm-1 cells btn-cog text-right px-1'>
        <a class="Serials-update" href="#" data-id="<?php echo $data['id']; ?>" model="Serials" controler="SerialsController" data-toggle="tooltip" data-placement="top" title="Update"><span class="oi oi-cog"></span></a>
        <a class="Serials-delete" href="#" data-id="<?php echo $data['id']; ?>" model="Serials" controler="SerialsController" data-toggle="tooltip" data-placement="top" title="Delete"><span class="oi oi-x"></span></a>
    </div>

    
</div>

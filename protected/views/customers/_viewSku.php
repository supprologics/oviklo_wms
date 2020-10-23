<?php
/* @var $this SkuController */
/* @var $data Sku */


$dataobj = Sku::model()->findByPk($data['id']);
?>


<div class="row datarow no-gutters" data-id="<?php echo $data['id']; ?>">

    <div class='col-2 cells px-1'>
        <?php echo $dataobj->category->name; ?>
    </div>
    <div class='col-3 cells px-1'>
        <?php echo $data['code']; ?>
    </div>
    <div class='col-4 cells px-1'>
        <?php echo $data['description']; ?>
    </div>
    <div class='col cells px-1'>
        <?php echo $data['pick_type']; ?>
    </div>
    <div class='col-1 cells px-1'>
        <?php echo $data['cbm']; ?>
    </div>

    <div class='col-sm-1 cells btn-cog text-right px-1'>
        <a class="Sku-update" href="#" data-id="<?php echo $data['id']; ?>" model="Sku" controler="SkuController" data-toggle="tooltip" data-placement="top" title="Update"><span class="oi oi-cog"></span></a>
        <a class="Sku-delete" href="#" data-id="<?php echo $data['id']; ?>" model="Sku" controler="SkuController" data-toggle="tooltip" data-placement="top" title="Delete"><span class="oi oi-x"></span></a>
    </div>


</div>

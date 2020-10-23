<?php
/* @var $this TnController */
/* @var $data Tn */


$dataobj = Tn::model()->findByPk($data['id']);
$sts = array( 0 => "PENDING", 1 => "DRAFT", 2 => "DONE", 9 => "REJECT");
?>


<div class="row datarow no-gutters" data-id="<?php echo $data['id']; ?>" data-st="<?php echo $dataobj->online; ?>">

    <div class='col cells px-1 clickable'>
        <?php echo $data['code']; ?>
    </div>
    <div class='col cells px-1 clickable'>
        <?php echo $dataobj->warehouseFrom->name; ?>
    </div>
    <div class='col cells px-1 clickable'>
        <?php echo $dataobj->warehouseTo->name; ?>
    </div>
    <div class='col cells px-1 clickable'>
        <?php echo $dataobj->goodsStsFrom->name; ?>
    </div>
    <div class='col cells px-1 clickable'>
        <?php echo $dataobj->goodsStsTo->name; ?>
    </div>
    <div class='col cells px-1 clickable'>
        <?php echo $dataobj->projectFrom->name; ?>
    </div>
    <div class='col cells px-1 clickable'>
        <?php echo $dataobj->projectTo->name; ?>
    </div>
    <div class='col cells px-1 clickable'>
        <?php echo $data['remarks']; ?>
    </div>
    <div class='col cells px-1 clickable'>
        <?php echo $data['created']; ?>
    </div>
    <div class='col cells px-1 clickable grn_sts<?php echo $dataobj->online; ?>'>
        <?php echo $sts[$dataobj->online]; ?>
    </div>

    <div class='col-sm-1 cells btn-cog text-right px-1'>
        <a class="Tn-delete" href="#" data-id="<?php echo $data['id']; ?>" model="Tn" controler="TnController" data-toggle="tooltip" data-placement="top" title="Delete"><span class="oi oi-x"></span></a>
    </div>


</div>

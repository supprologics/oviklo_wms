<?php
/* @var $this GrnController */
/* @var $data Grn */

$dataobj = Grn::model()->findByPk($data['id']);
$sts = array( 0 => "PENDING", 1 => "DRAFT", 2 => "TALLY PENDING",3 => "GRN DONE",4 => "GRN & SERIALS DONE", 9 => "REJECT");

?>


<div class="row datarow no-gutters sts<?php echo $dataobj->online; ?>" data-id="<?php echo $data['id']; ?>" data-st="<?php echo $dataobj->online; ?>">

    <div class='col-1 cells px-1 clickable'>
        <?php echo $data['code']; ?>
    </div>
    <div class='col-2 cells px-1 clickable'>
        <?php echo $dataobj->customers->name; ?>
    </div>
    <div class='col-1 cells px-1 clickable'>
        <?php echo $dataobj->warehouse->code; ?>
    </div>
    <div class='col-2 cells px-1 clickable'>
        <?php echo $data['supplier']; ?>
    </div>
    <div class='col-1 cells px-1 clickable'>
        <?php echo $data['eff_date']; ?>
    </div>
    <div class='col-1 cells px-1 clickable'>
        <?php echo $data['vehicle_no']; ?>
    </div>
    <div class='col-1 cells px-1 clickable'>
        <?php echo $data['ref_no']; ?>
    </div>
    <div class='col-1 cells px-1 clickable'>
        <?php echo $dataobj->project->name; ?>
    </div>    
    <div class='col-1 cells px-1 clickable grn_sts<?php echo $dataobj->online; ?>'>
        <?php echo $sts[$dataobj->online]; ?>
    </div> 
    <div class="col-1 cells px-1 text-right">
        <?php if($dataobj->customers->is_serial == 1 && $dataobj->online == 3){ ?>
        <a class="grn-add" href="<?php echo Yii::app()->createUrl("grn/serial/".$data['id']); ?>" data-id="<?php echo $data['id']; ?>"data-toggle="tooltip" data-placement="top" title="Add Serials"><span class="oi oi-plus text-success"></span></a>
        <?php } ?>
        
        <?php if($dataobj->customers->is_serial == 1 && $dataobj->online == 4){ ?>
        <a class="grn-print-s" href="#" data-id="<?php echo $data['id']; ?>" model="Locations" controler="LocationsController" data-toggle="tooltip" data-placement="top" title="Serial Registry"><span class="oi oi-file "></span></a>
        <?php } ?>
        
        <a class="grn-delete" href="#" data-id="<?php echo $data['id']; ?>" model="Locations" controler="LocationsController" data-toggle="tooltip" data-placement="top" title="Delete"><span class="oi oi-x text-danger"></span></a>
        
        
    </div>

</div>

<?php
/* @var $this CustomersController */
/* @var $data Customers */


$dataobj = Customers::model()->findByPk($data['id']);
?>


<div class="row datarow no-gutters customer<?php echo $dataobj->online; ?>" data-id="<?php echo $data['id']; ?>">

    <div class='col-1 cells px-1 clickable'>
        <?php echo $data['code']; ?>
    </div>
    <div class='col-1 cells px-1 clickable'>
        <?php echo $dataobj->bizCat->name; ?>
    </div>
    <div class='col cells px-1 clickable'>
        <?php echo $data['name']; ?>
    </div>
    <div class='col-3 cells px-1 clickable'>
        <?php echo $data['address']; ?>
    </div>
    <div class='col cells px-1 clickable'>
        <?php echo $data['tel_1']; ?>
    </div>
    <div class='col cells px-1 clickable'>
        <?php echo $data['tel_2']; ?>
    </div>
    <div class='col cells px-1 clickable'>
        <?php echo $data['email']; ?>
    </div>

    <div class='col-sm-1 cells btn-cog text-right px-1'>
        <a class="Customers-update" href="#" data-id="<?php echo $data['id']; ?>" model="Customers" controler="CustomersController" data-toggle="tooltip" data-placement="top" title="Update"><span class="oi oi-cog"></span></a>
        <a class="Customers-delete" href="#" data-id="<?php echo $data['id']; ?>" model="Customers" controler="CustomersController" data-toggle="tooltip" data-placement="top" title="Delete"><span class="oi oi-x"></span></a>
    </div>


</div>

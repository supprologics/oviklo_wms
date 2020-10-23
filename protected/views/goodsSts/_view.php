<?php
/* @var $this GoodsStsController */
/* @var $data GoodsSts */


$dataobj = GoodsSts::model()->findByPk($data['id']);
?>


<div class="row datarow no-gutters" data-id="<?php echo $data['id']; ?>">

    <div class='col cells px-1 clickable'>
        <?php echo $data['name']; ?>
    </div>

    <div class='col-sm-1 cells btn-cog text-right px-1'>
        <a class="GoodsSts-update" href="#" data-id="<?php echo $data['id']; ?>" model="GoodsSts" controler="GoodsStsController" data-toggle="tooltip" data-placement="top" title="Update"><span class="oi oi-cog"></span></a>
        <a class="GoodsSts-delete" href="#" data-id="<?php echo $data['id']; ?>" model="GoodsSts" controler="GoodsStsController" data-toggle="tooltip" data-placement="top" title="Delete"><span class="oi oi-x"></span></a>
    </div>


</div>

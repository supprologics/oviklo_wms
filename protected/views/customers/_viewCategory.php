<?php
/* @var $this CategoryController */
/* @var $data Category */


$dataobj = Category::model()->findByPk($data['id']);
?>


<div class="row datarow no-gutters" data-id="<?php echo $data['id']; ?>">

    <div class='col cells px-1'>
        <?php echo $data['code']; ?>
    </div>
    <div class='col cells px-1'>
        <?php echo $data['name']; ?>
    </div>

    <div class='col-sm-1 cells btn-cog text-right px-1'>
        <a class="Category-update" href="#" data-id="<?php echo $data['id']; ?>" model="Category" controler="CategoryController" data-toggle="tooltip" data-placement="top" title="Update"><span class="oi oi-cog"></span></a>
        <a class="Category-delete" href="#" data-id="<?php echo $data['id']; ?>" model="Category" controler="CategoryController" data-toggle="tooltip" data-placement="top" title="Delete"><span class="oi oi-x"></span></a>
    </div>


</div>

<?php
/* @var $this UsersController */
/* @var $data Users */


$dataobj = Users::model()->findByPk($data['id']);
?>


<div class="row datarow no-gutters sts_<?php echo $data['online']; ?>" data-id="<?php echo $data['id']; ?>">

    <div class='col-5 cells px-1 clickable'>
        <?php echo $data['name']; ?>
    </div>
    <div class='col cells px-1 clickable'>
        <?php echo $data['email']; ?>
    </div>
    <div class='col cells px-1 clickable'>
        <?php echo $data['username']; ?>
    </div>
    <div class='col cells px-1 clickable'>
        <?php echo $data['created']; ?>
    </div>
    <div class='col cells px-1 clickable'>
        <?php echo !empty($data['online']) ? "ACTIVE" : "OFF"; ?>
    </div>

    <div class='col-sm-1 cells btn-cog text-right px-1'>
        <a class="Users-delete" href="#" data-id="<?php echo $data['id']; ?>" model="Users" controler="UsersController" data-toggle="tooltip" data-placement="top" title="Delete"><span class="oi oi-x"></span></a>
    </div>


</div>

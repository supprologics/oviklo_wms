<?php
/* @var $this ProjectController */
/* @var $data Project */


$dataobj = Project::model()->findByPk($data['id']);

?>


<div class="row datarow no-gutters" data-id="<?php echo $data['id']; ?>">
    
    <div class='col cells px-1 clickable'>
	<?php echo $data['customers_id']; ?>
</div>
<div class='col cells px-1 clickable'>
	<?php echo $data['name']; ?>
</div>
    
    <div class='col-sm-1 cells btn-cog text-right px-1'>
        <a class="Project-update" href="#" data-id="<?php echo $data['id']; ?>" model="Project" controler="ProjectController" data-toggle="tooltip" data-placement="top" title="Update"><span class="oi oi-cog"></span></a>
        <a class="Project-delete" href="#" data-id="<?php echo $data['id']; ?>" model="Project" controler="ProjectController" data-toggle="tooltip" data-placement="top" title="Delete"><span class="oi oi-x"></span></a>
    </div>

    
</div>

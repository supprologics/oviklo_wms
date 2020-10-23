<?php
/* @var $this MrController */
/* @var $data Mr */

$user_id = Yii::app()->user->getState("userid");
$access = Useraccess::model()->findByAttributes(array('users_id' => $user_id, 'access_id' => 10));
$dataobj = Mr::model()->findByPk($data['id']);

?>


<div class="row datarow no-gutters sts<?php echo $data['online']; ?>" data-id="<?php echo $data['id']; ?>" data-st="<?php echo $dataobj->online; ?>">

    <div class='col-1 cells px-1 clickable'>
        <?php echo $data['code']; ?>
    </div>
    <div class='col-2 cells px-1 clickable'>
        <?php echo $dataobj->customers->name; ?>
    </div>
    <div class='col-2 cells px-1 clickable'>
        <?php echo $dataobj->warehouse->name; ?>
    </div>

    <div class='col-1 cells px-1 clickable'>
        <?php echo $data['eff_date']; ?>
    </div>
    <div class='col-1 cells px-1 clickable'>
        <?php echo $data['delivery_date']; ?>
    </div>
    <div class='col-2 cells px-1 clickable'>
        <?php echo $data['dest1_name']; ?>
    </div>
    <div class='col cells px-1 clickable'>
        <?php echo $data['link_name']; ?>
    </div>
    <div class='col cells px-1 clickable'>
        <?php echo $data['col_vehicle']; ?>
    </div>
    <div class='col cells px-1 clickable'>
        <?php echo $data['remarks']; ?>
    </div>
    <div class='col-1 text-right cells px-1'>
        <div class="btn-group dropleft">            
            <span type="button" style="padding: 2px;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="oi oi-print"></span>
            <div class="dropdown-menu">
                <a class="dropdown-item p_mr" data-id="<?php echo $data['id']; ?>" href="#">Print MR</a>
                <a class="dropdown-item p_pick" data-id="<?php echo $data['id']; ?>" href="#">Print PICK</a>
                <a class="dropdown-item clickable" data-id="<?php echo $data['id']; ?>" href="#">Print GDN</a>
                
                <?php if (isset($access->view_) && $access->view_ == 1) { ?>
                    <a class="dropdown-item backto" data-id="<?php echo $data['id']; ?>" href="#">Reverse to PICK</a>
                <?php } ?>
                
            </div>
        </div>
    </div>

</div>

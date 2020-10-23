
<?php
$user_id = Yii::app()->user->getState("userid");
$accessTN = Useraccess::model()->findByAttributes(array('users_id' => $user_id, 'access_id' => 8));
$accessTally = Useraccess::model()->findByAttributes(array('users_id' => $user_id, 'access_id' => 9));
?>

<div style="padding: 2px; background: #c70024; z-index: 5000; width: 100%; position: fixed; height: 2px; top: 0px; left: 0px;"  ></div>

<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
    <a class="navbar-brand" href="#">MRP</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="<?php echo Yii::app()->homeUrl; ?>">Dashboard <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo Yii::app()->createUrl("customers") ?>">Customers <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Inventory
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="<?php echo Yii::app()->createUrl("grn") ?>">Goods Receiving Notes </a>
                    <?php if ($accessTally->view_ == 1) { ?>
                    <a class="dropdown-item" href="<?php echo Yii::app()->createUrl("grn/tallylist") ?>">Tally Update Registry </a>
                    <?php } ?>
                    <?php if ($accessTN->view_ == 1) { ?>
                        <a class="dropdown-item" href="<?php echo Yii::app()->createUrl("tn") ?>">Transfer Process</a>
                    <?php } ?>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo Yii::app()->createUrl("mr") ?>">MR<span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo Yii::app()->createUrl("pick") ?>">PICK <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo Yii::app()->createUrl("gdn") ?>">GDN <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Reports
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="<?php echo Yii::app()->createUrl("reports") ?>">Stock Reports</a>
                    <a class="dropdown-item" href="<?php echo Yii::app()->createUrl("reports/movements") ?>">Stock Movement Reports</a>
                </div>
            </li>
        </ul>
        <ul class="navbar-nav">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Config
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown" aria-haspopup="true" aria-expanded="false">
                    <a class="dropdown-item" href="<?php echo Yii::app()->createUrl("users") ?>">User Accounts</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?php echo Yii::app()->createUrl("uom") ?>">Units Registry</a>
                    <a class="dropdown-item" href="<?php echo Yii::app()->createUrl("goodsSts") ?>">Goods Status</a>
                    <a class="dropdown-item" href="<?php echo Yii::app()->createUrl("warehouse") ?>">Warehouse Registry</a>
                </div>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="<?php echo Yii::app()->createUrl("site/logout") ?>">Logout <span class="sr-only">(current)</span></a>
            </li>
        </ul>
    </div>
</nav>
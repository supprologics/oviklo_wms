<?php
/* @var $this SiteController */
/* @var $error array */

$this->pageTitle = "SYSTEM ERROR";
?>

<div class="container">
    <div class="row align-items-center">
        <div class="col mx-auto text-center">
            <div style="padding-top: 100px;">
                <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/err.png" class="img-fluid" width="148px" />
                <h2>CODE E#<?php echo $code; ?></h2>

                <div class="error">
                    <h3><?php echo CHtml::encode($message); ?></h3>
                    Please Contact ADMIN Department for Further Support
                </div>
            </div>
        </div>
    </div>
</div>
<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>

<?php require 'navigation.php'; ?>





<div class="container-fluid" style="margin-top: 56px;">
    <div class="row">
        <div class="col">
            <?php echo $content; ?>
        </div>
    </div>
</div>

<?php $this->endContent(); ?>
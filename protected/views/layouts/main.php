<?php /* @var $this Controller */ ?>
<!doctype html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="language" content="en">

        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/open-iconic-bootstrap.css">
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/template.css">
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/alertify.css">
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/pace.css">
        

        <title>Oviklo iSpace - Cloud 3PL Management System</title>

        <?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
        <?php Yii::app()->clientScript->registerCoreScript('jquery.ui'); ?>  

        <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.bundle.min.js" ></script>
        <?php Yii::app()->clientScript->registerCssFile(Yii::app()->clientScript->getCoreScriptUrl() . '/jui/css/base/jquery-ui.css'); ?>
            
        <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . '/js/jquery.form.js'); ?>
        <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . '/js/jquery.validate.js'); ?>
        <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . '/js/alertify.min.js'); ?>

        
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap-datepicker.css">
        <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . '/js/bootstrap-datepicker.js'); ?>
        <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . '/js/pacenew.js'); ?>
        
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
        

        <script type="text/javascript">

            
            $(function () {
                loadDatePicker();
                
                $('.select_search').select2();
                
            });
            
            
            function loadDatePicker(){
                $(".datepicker").datepicker({
                    format: "yyyy-mm-dd",
                    autoclose: true,
                    todayHighlight: true
                });
                
                var dateToday = new Date(); 
                $(".datepicker_min").datepicker({
                    format: "yyyy-mm-dd",
                    startDate: '<?php echo date("Y-m-d"); ?>',
                    autoclose: true,
                    todayHighlight: true
                });
            }
            
            
            
            function showError(txt) {
                alertify.success(txt);
            }

            function showResponse(responseText, statusText, xhr, $form) {
                $("#err").html("");

                if (responseText.status != null) {
                    alertify.success(responseText.responseText);

                    if (typeof value !== "undefined") {
                        $(".modal form").resetForm();
                        $(".modal").modal('hide');
                    }

                } else {
                    if (typeof (responseText) != 'object') {
                        var responseText = JSON.parse(responseText);
                    }
                    if (responseText.sts == '1' && responseText.hide == '0' && typeof value !== "undefined" || !responseText.hide) {
                        $(".modal form").resetForm();
                        $(".modal").modal('hide');
                    }
                    alertify.success(responseText.msg);
                }
            }
            
            

        </script>
    </head>

    <body>
        <?php echo $content; ?>
    </body>
</html>

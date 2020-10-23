<?php
/* @var $this WarehouseController */
/* @var $dataProvider CActiveDataProvider */

?>



<!--- Script -->
<script>

    $(document).ready(function () {

        $("#Warehouse-form").ajaxForm({
            beforeSend: function () {

                return $("#Warehouse-form").validate({
                    rules: {
                        name: {
                            required: true,
                        }
                    },
                    messages: {
                        name: {
                            max: "Customize Your Error"
                        }
                    }
                }).form();

            },
            success: showResponse,
            error: showResponse,
            complete: function () {
                search();
            }
        });

        $('#Warehouse-addmodel').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            if (button.attr("id") === "Warehouse-add") {
                $("#Warehouse-form").resetForm();
                $("#Warehouse-form").attr("action", "<?php echo Yii::app()->createUrl('warehouse/create') ?>/");
                $(".hideonupdate").show();
            } else {
                $(".hideonupdate").hide();
            }
        });

    });


    $(document).on("click", ".clickable", function () {
        var id = $(this).parents("div.row").attr("data-id");
        window.location.href = "<?php echo Yii::app()->createUrl('warehouse') ?>/"+id;
    });

    $(document).on("click", "#btn-submit", function () {
        $("#Warehouse-form").submit();
    });


    $(document).on("click", ".Warehouse-update", function (e) {
        e.preventDefault();
        var id = $(this).attr("data-id");
        $("#Warehouse-form").resetForm();
        //Handle JSON DATA to Update FORM
        $.getJSON("<?php echo Yii::app()->createUrl('Warehouse/jsondata') ?>/" + id).done(function (data) {
            $.each(data, function (i, item) {

                if ($("#Warehouse-form #" + i).is("[type='checkbox']")) {
                    $("#Warehouse-form #" + i).prop('checked', item);
                } else if ($("#Warehouse-form #" + i).is("[type='radio']")) {
                    $("#Warehouse-form #" + i).prop('checked', item);
                } else {
                    $("#Warehouse-form #" + i).val(item);
                }
            });
            $("#Warehouse-form").attr("action", "<?php echo Yii::app()->createUrl('Warehouse/update') ?>/" + id);
        });

        $("#Warehouse-addmodel").modal('show');
    });

    $(document).on("click", ".Warehouse-delete", function (e) {
        e.preventDefault();
        var id = $(this).attr("data-id");
        var confirmdata = confirm("Are you sure, you want to delete this record ?");
        if (confirmdata == true) {
            $.ajax({
                url: "<?php echo Yii::app()->createUrl('Warehouse/delete') ?>/" + id,
                type: "POST",
                success: showResponse,
                error: showResponse
            }).done(function (data) {
                search();
            });
        }
    });

    $(document).on("click", "#Warehouse-searchbtn", function () {
        search();
    });

    $(document).on("keyup", "#Warehouse-search", function () {
        search();
    });

    $(document).on("change", "#Warehouse-pages", function () {
        search();
    });

    function search() {
        $.fn.yiiListView.update('Warehouse-list', {
            data: {
                val: $("#Warehouse-search").val(),
                pages: $("#Warehouse-pages").val()
            },
            complete: function () {
                int();
            }
        });
    }


</script>
<!-- //END SCRIPT -->

<div id="header-sec">
    <div class="row">
        <div class="col-sm-12">
            <h1>Warehouse Registry</h1>
        </div>
    </div>
</div>

<!-- Submit Form BY model -->
<div class="modal fade" id="Warehouse-addmodel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Warehouse - FORM</h4>
            </div>
            <div class="modal-body">

                <div class="cus-form">
                    <form class="form-horizontal" action="<?php echo Yii::app()->createUrl('Warehouse/create') ?>" method="post" id="Warehouse-form">

                        <div class="form-group row">
                            <label for="code" class="col-sm-4 control-label">Code</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control required form-control-sm" id="code" name="code" placeholder="Pre-Fix Code">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-sm-4 control-label">Name</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm" id="name" name="name" placeholder="Warehouse Name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="address" class="col-sm-4 control-label">Address</label>
                            <div class="col-sm-8">
                                <textarea name="address" id="address" rows="2" class="form-control form-control-sm" placeholder="Postal Address"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="mobile" class="col-sm-4 control-label">Contact</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm" id="mobile" name="mobile" placeholder="Contact">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email" class="col-sm-4 control-label">Email</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm" id="email" name="email" placeholder="Email Address">
                            </div>
                        </div>
                    </form>
                </div>

            </div>
            <div class="modal-footer">
                <button id="btn-submit" type="button" class="btn btn-success btn-sm">Save</button>
            </div>
        </div>
    </div>
</div>
<!-- Submit Form BY model -->

<div id="title-nav" class="inputsearch">
    <div class="row justify-content-between">

        <div class="col-4">
            <div class="input-group">
                <div class="input-group-append">
                    <button id="Warehouse-add" data-toggle="modal" data-target="#Warehouse-addmodel" class="btn btn-default btn-block btn-sm" >
                        Add <span class="oi oi-plus"></span>
                    </button>
                </div>
                <input type="text" id="Warehouse-search" class="form-control form-control-sm" placeholder="Search by Name/Code etc.">
                <div class="input-group-append">
                    <button id="Warehouse-searchbtn" class="btn btn-default btn-sm" >Search <span class="glyphicon glyphicon-search"></span></button>
                </div>
            </div>
        </div> 
        <div class="col-auto">
            <div class="input-group">
                <select id="Warehouse-pages" name="pages" class="custom-select custom-select-sm">
                    <option>10 Pages</option>
                    <option selected="selected">50 Pages</option>
                    <option>100 Pages</option>
                </select>
            </div>
        </div>

    </div>
</div>




<div>
    <div class="table-box">

        <div class="row no-gutters">
            <div class='col headerdiv'>code</div>
<div class='col headerdiv'>name</div>
<div class='col headerdiv'>address</div>
<div class='col headerdiv'>mobile</div>
<div class='col headerdiv'>email</div>
            <div class="col-sm-1 headerdiv">&nbsp;</div>
        </div>


        <div class="row no-gutters">
            <?php $this->widget('zii.widgets.CListView', array(
            'dataProvider'=>$dataProvider,
            'itemView'=>'_view',
            'enablePagination' => true,
            'summaryText' => '{page}/{pages} pages',
            'id' => 'Warehouse-list',
            'emptyTagName' => 'p',
            'emptyText' => '<span class="glyphicon glyphicon-file"></span> No Records  ',
            'itemsTagName' => 'div',
            'itemsCssClass' => 'ss',
            'pagerCssClass' => 'pagination-div',
            'pager' => array(
            "header" => "",
            "htmlOptions" => array(
            "class" => "pagination pagination-sm"
            ),
            'selectedPageCssClass' => 'active',
            'nextPageLabel' => 'Next',
            'lastPageLabel' => 'Last',
            'prevPageLabel' => 'Previous',
            'firstPageLabel' => 'First',
            'maxButtonCount' => 10
            ),
            )); ?>
        </div>


    </div>
</div>

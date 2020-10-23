<?php
/* @var $this PickSerialsController */
/* @var $dataProvider CActiveDataProvider */

?>



<!--- Script -->
<script>

    $(document).ready(function () {

        $("#PickSerials-form").ajaxForm({
            beforeSend: function () {

                return $("#PickSerials-form").validate({
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

        $('#PickSerials-addmodel').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            if (button.attr("id") === "PickSerials-add") {
                $("#PickSerials-form").resetForm();
                $("#PickSerials-form").attr("action", "<?php echo Yii::app()->createUrl('PickSerials/create') ?>/");
                $(".hideonupdate").show();
            } else {
                $(".hideonupdate").hide();
            }
        });

    });


    $(document).on("click", ".clickable", function () {
        var id = $(this).parents("div.row").attr("data-id");
        window.location.href = "<?php echo Yii::app()->createUrl('PickSerials') ?>/"+id;
    });

    $(document).on("click", "#btn-submit", function () {
        $("#PickSerials-form").submit();
    });


    $(document).on("click", ".PickSerials-update", function (e) {
        e.preventDefault();
        var id = $(this).attr("data-id");
        $("#PickSerials-form").resetForm();
        //Handle JSON DATA to Update FORM
        $.getJSON("<?php echo Yii::app()->createUrl('PickSerials/jsondata') ?>/" + id).done(function (data) {
            $.each(data, function (i, item) {

                if ($("#PickSerials-form #" + i).is("[type='checkbox']")) {
                    $("#PickSerials-form #" + i).prop('checked', item);
                } else if ($("#PickSerials-form #" + i).is("[type='radio']")) {
                    $("#PickSerials-form #" + i).prop('checked', item);
                } else {
                    $("#PickSerials-form #" + i).val(item);
                }
            });
            $("#PickSerials-form").attr("action", "<?php echo Yii::app()->createUrl('PickSerials/update') ?>/" + id);
        });

        $("#PickSerials-addmodel").modal('show');
    });

    $(document).on("click", ".PickSerials-delete", function (e) {
        e.preventDefault();
        var id = $(this).attr("data-id");
        var confirmdata = confirm("Are you sure, you want to delete this record ?");
        if (confirmdata == true) {
            $.ajax({
                url: "<?php echo Yii::app()->createUrl('PickSerials/delete') ?>/" + id,
                type: "POST",
                success: showResponse,
                error: showResponse
            }).done(function (data) {
                search();
            });
        }
    });

    $(document).on("click", "#PickSerials-searchbtn", function () {
        search();
    });

    $(document).on("keyup", "#PickSerials-search", function () {
        search();
    });

    $(document).on("change", "#PickSerials-pages", function () {
        search();
    });

    function search() {
        $.fn.yiiListView.update('PickSerials-list', {
            data: {
                val: $("#PickSerials-search").val(),
                pages: $("#PickSerials-pages").val()
            },
            complete: function () {
                //CODE GOES HERE
            }
        });
    }


</script>
<!-- //END SCRIPT -->

<div id="header-sec">
    <div class="row">
        <div class="col-sm-12">
            <h1>Pick Serials</h1>
        </div>
    </div>
</div>

<!-- Submit Form BY model -->
<div class="modal fade" id="PickSerials-addmodel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"></h4>
            </div>
            <div class="modal-body">

                <div class="cus-form">
                    <form class="form-horizontal" action="<?php echo Yii::app()->createUrl('PickSerials/create') ?>" method="post" id="PickSerials-form">

                        <div class="form-group row">
                            <label for="code" class="col-sm-4 control-label">Code</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control form-control-sm" id="code" name="code" placeholder="Name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-sm-4 control-label">Name</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm" id="name" name="name" placeholder="Name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="description" class="col-sm-4 control-label">Description</label>
                            <div class="col-sm-8">
                                <textarea name="description" id="description" rows="2" class="form-control form-control-sm"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="select" class="col-sm-4 control-label">Types</label>
                            <div class="col-sm-4">
                                <select id="select" name="select" class="custom-select custom-select-sm">
                                    <option>Select the Value</option>
                                </select>
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
                    <button id="PickSerials-add" data-toggle="modal" data-target="#PickSerials-addmodel" class="btn btn-secondary btn-block btn-sm" >
                        Add <span class="oi oi-plus"></span>
                    </button>
                </div>
                <input type="text" id="PickSerials-search" class="form-control form-control-sm" placeholder="Search by Name/Code etc.">
                <div class="input-group-append">
                    <button id="PickSerials-searchbtn" class="btn btn-secondary btn-sm" >Search <span class="oi oi-magnifying-glass"></span> <span class="glyphicon glyphicon-search"></span></button>
                </div>
            </div>
        </div> 
        <div class="col-auto">
            <div class="input-group">
                <select id="PickSerials-pages" name="pages" class="custom-select custom-select-sm">
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
            <div class='col headerdiv'>pick_items_id</div>
<div class='col headerdiv'>serials_id</div>
            <div class="col-sm-1 headerdiv">&nbsp;</div>
        </div>


        <div class="row no-gutters">
            <?php $this->widget('zii.widgets.CListView', array(
            'dataProvider'=>$dataProvider,
            'itemView'=>'_view',
            'enablePagination' => true,
            'summaryText' => '{page}/{pages} pages',
            'id' => 'PickSerials-list',
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

<!--- Script -->
<script>

    $(document).ready(function () {

        $("#Category-form").ajaxForm({
            beforeSend: function () {

                return $("#Category-form").validate({
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

        $('#Category-addmodel').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            if (button.attr("id") === "Category-add") {
                $("#Category-form").resetForm();
                $("#Category-form").attr("action", "<?php echo Yii::app()->createUrl('Category/create') ?>/");
                $(".hideonupdate").show();
            } else {
                $(".hideonupdate").hide();
            }
        });

    });


    $(document).on("click", ".clickable", function () {
        var id = $(this).parents("div.row").attr("data-id");
        window.location.href = "<?php echo Yii::app()->createUrl('Category') ?>/" + id;
    });

    $(document).on("click", "#btn-submit", function () {
        $("#Category-form").submit();
    });


    $(document).on("click", ".Category-update", function (e) {
        e.preventDefault();
        var id = $(this).attr("data-id");
        $("#Category-form").resetForm();
        //Handle JSON DATA to Update FORM
        $.getJSON("<?php echo Yii::app()->createUrl('Category/jsondata') ?>/" + id).done(function (data) {
            $.each(data, function (i, item) {

                if ($("#Category-form #" + i).is("[type='checkbox']")) {
                    $("#Category-form #" + i).prop('checked', item);
                } else if ($("#Category-form #" + i).is("[type='radio']")) {
                    $("#Category-form #" + i).prop('checked', item);
                } else {
                    $("#Category-form #" + i).val(item);
                }
            });
            $("#Category-form").attr("action", "<?php echo Yii::app()->createUrl('Category/update') ?>/" + id);
        });

        $("#Category-addmodel").modal('show');
    });

    $(document).on("click", ".Category-delete", function (e) {
        e.preventDefault();
        var id = $(this).attr("data-id");
        var confirmdata = confirm("Are you sure, you want to delete this record ?");
        if (confirmdata == true) {
            $.ajax({
                url: "<?php echo Yii::app()->createUrl('Category/delete') ?>/" + id,
                type: "POST",
                success: showResponse,
                error: showResponse
            }).done(function (data) {
                search();
            });
        }
    });

    $(document).on("click", "#Category-searchbtn", function () {
        search();
    });

    $(document).on("keyup", "#Category-search", function () {
        search();
    });

    $(document).on("change", "#Category-pages", function () {
        search();
    });

    function search() {
        $.fn.yiiListView.update('Category-list', {
            data: {
                val: $("#Category-search").val(),
                pages: $("#Category-pages").val()
            },
            complete: function () {
                loadCategory();
            }
        });
    }


</script>
<!-- //END SCRIPT -->


<!-- Submit Form BY model -->
<div class="modal fade" id="Category-addmodel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Category - FORM</h4>
            </div>
            <div class="modal-body">

                <div class="cus-form">
                    <form class="form-horizontal" action="<?php echo Yii::app()->createUrl('Category/create') ?>" method="post" id="Category-form">
                        <input type="hidden" name="customers_id" id="customers_id" value="<?php echo $model->id; ?>" />
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
                    <button id="Category-add" data-toggle="modal" data-target="#Category-addmodel" class="btn btn-default btn-block btn-sm" >
                        Add <span class="oi oi-plus"></span>
                    </button>
                </div>
                <input type="text" id="Category-search" class="form-control form-control-sm" placeholder="Search by Name/Code etc.">
                <div class="input-group-append">
                    <button id="Category-searchbtn" class="btn btn-default btn-sm" >Search <span class="glyphicon glyphicon-search"></span></button>
                </div>
            </div>
        </div> 
        <div class="col-auto">
            <div class="input-group">
                <select id="Category-pages" name="pages" class="custom-select custom-select-sm">
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
            <div class="col-sm-1 headerdiv">&nbsp;</div>
        </div>


        <div class="row no-gutters">
            <?php
            $this->widget('zii.widgets.CListView', array(
                'dataProvider' => $dataProviderCategory,
                'itemView' => '_viewCategory',
                'enablePagination' => true,
                'summaryText' => '{page}/{pages} pages',
                'id' => 'Category-list',
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
            ));
            ?>
        </div>


    </div>
</div>


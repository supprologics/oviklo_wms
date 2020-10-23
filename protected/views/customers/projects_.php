<!--- Script -->
<script>

    $(document).ready(function () {

        $("#Project-form").ajaxForm({
            beforeSend: function () {

                return $("#Project-form").validate({
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
                searchProject();
            }
        });

        $('#Project-addmodel').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            if (button.attr("id") === "Project-add") {
                $("#Project-form").resetForm();
                $("#Project-form").attr("action", "<?php echo Yii::app()->createUrl('Project/create') ?>/");
                $(".hideonupdate").show();
            } else {
                $(".hideonupdate").hide();
            }
        });

    });


    $(document).on("click", ".clickable", function () {
        var id = $(this).parents("div.row").attr("data-id");
        window.location.href = "<?php echo Yii::app()->createUrl('Project') ?>/" + id;
    });

    $(document).on("click", "#btn-submit-project", function () {
        $("#Project-form").submit();
    });


    $(document).on("click", ".Project-update", function (e) {
        e.preventDefault();
        var id = $(this).attr("data-id");
        $("#Project-form").resetForm();
        //Handle JSON DATA to Update FORM
        $.getJSON("<?php echo Yii::app()->createUrl('Project/jsondata') ?>/" + id).done(function (data) {
            $.each(data, function (i, item) {

                if ($("#Project-form #" + i).is("[type='checkbox']")) {
                    $("#Project-form #" + i).prop('checked', item);
                } else if ($("#Project-form #" + i).is("[type='radio']")) {
                    $("#Project-form #" + i).prop('checked', item);
                } else {
                    $("#Project-form #" + i).val(item);
                }
            });
            $("#Project-form").attr("action", "<?php echo Yii::app()->createUrl('Project/update') ?>/" + id);
        });

        $("#Project-addmodel").modal('show');
    });

    $(document).on("click", ".Project-delete", function (e) {
        e.preventDefault();
        var id = $(this).attr("data-id");
        var confirmdata = confirm("Are you sure, you want to delete this record ?");
        if (confirmdata == true) {
            $.ajax({
                url: "<?php echo Yii::app()->createUrl('Project/delete') ?>/" + id,
                type: "POST",
                success: showResponse,
                error: showResponse
            }).done(function (data) {
                searchProject();
            });
        }
    });

    $(document).on("click", "#Project-searchProjectbtn", function () {
        searchProject();
    });

    $(document).on("keyup", "#Project-searchProject", function () {
        searchProject();
    });

    $(document).on("change", "#Project-pages", function () {
        searchProject();
    });

    function searchProject() {
        $.fn.yiiListView.update('Project-list', {
            data: {
                val: $("#Project-searchProject").val(),
                pages: $("#Project-pages").val()
            },
            complete: function () {
                loadProject();
            }
        });
    }


</script>
<!-- //END SCRIPT -->


<!-- Submit Form BY model -->
<div class="modal fade" id="Project-addmodel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Project - FORM</h4>
            </div>
            <div class="modal-body">

                <div class="cus-form">
                    <form class="form-horizontal" action="<?php echo Yii::app()->createUrl('Project/create') ?>" method="post" id="Project-form">
                        <input type="hidden" name="customers_id" id="customers_id" value="<?php echo $model->id; ?>" />
                        
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
                <button id="btn-submit-project" type="button" class="btn btn-success btn-sm">Save</button>
            </div>
        </div>
    </div>
</div>
<!-- Submit Form BY model -->

<div id="title-nav" class="inputsearchProject">
    <div class="row justify-content-between">

        <div class="col-4">
            <div class="input-group">
                <div class="input-group-append">
                    <button id="Project-add" data-toggle="modal" data-target="#Project-addmodel" class="btn btn-default btn-block btn-sm" >
                        Add <span class="oi oi-plus"></span>
                    </button>
                </div>
                <input type="text" id="Project-searchProject" class="form-control form-control-sm" placeholder="Search by Name/Code etc.">
                <div class="input-group-append">
                    <button id="Project-searchProjectbtn" class="btn btn-default btn-sm" >Search <span class="glyphicon glyphicon-searchProject"></span></button>
                </div>
            </div>
        </div> 
        <div class="col-auto">
            <div class="input-group">
                <select id="Project-pages" name="pages" class="custom-select custom-select-sm">
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
            <div class='col headerdiv'>name</div>
            <div class="col-sm-1 headerdiv">&nbsp;</div>
        </div>


        <div class="row no-gutters">
            <?php
            $this->widget('zii.widgets.CListView', array(
                'dataProvider' => $dataProviderProject,
                'itemView' => '_viewProject',
                'enablePagination' => true,
                'summaryText' => '{page}/{pages} pages',
                'id' => 'Project-list',
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


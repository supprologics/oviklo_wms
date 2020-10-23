<?php
/* @var $this GoodsStsController */
/* @var $dataProvider CActiveDataProvider */

?>



<!--- Script -->
<script>

    $(document).ready(function () {

        $("#GoodsSts-form").ajaxForm({
            beforeSend: function () {

                return $("#GoodsSts-form").validate({
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

        $('#GoodsSts-addmodel').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            if (button.attr("id") === "GoodsSts-add") {
                $("#GoodsSts-form").resetForm();
                $("#GoodsSts-form").attr("action", "<?php echo Yii::app()->createUrl('GoodsSts/create') ?>/");
                $(".hideonupdate").show();
            } else {
                $(".hideonupdate").hide();
            }
        });

    });


    $(document).on("click", ".clickable", function () {
        var id = $(this).parents("div.row").attr("data-id");
        window.location.href = "<?php echo Yii::app()->createUrl('GoodsSts') ?>/"+id;
    });

    $(document).on("click", "#btn-submit", function () {
        $("#GoodsSts-form").submit();
    });


    $(document).on("click", ".GoodsSts-update", function (e) {
        e.preventDefault();
        var id = $(this).attr("data-id");
        $("#GoodsSts-form").resetForm();
        //Handle JSON DATA to Update FORM
        $.getJSON("<?php echo Yii::app()->createUrl('GoodsSts/jsondata') ?>/" + id).done(function (data) {
            $.each(data, function (i, item) {

                if ($("#GoodsSts-form #" + i).is("[type='checkbox']")) {
                    $("#GoodsSts-form #" + i).prop('checked', item);
                } else if ($("#GoodsSts-form #" + i).is("[type='radio']")) {
                    $("#GoodsSts-form #" + i).prop('checked', item);
                } else {
                    $("#GoodsSts-form #" + i).val(item);
                }
            });
            $("#GoodsSts-form").attr("action", "<?php echo Yii::app()->createUrl('GoodsSts/update') ?>/" + id);
        });

        $("#GoodsSts-addmodel").modal('show');
    });

    $(document).on("click", ".GoodsSts-delete", function (e) {
        e.preventDefault();
        var id = $(this).attr("data-id");
        var confirmdata = confirm("Are you sure, you want to delete this record ?");
        if (confirmdata == true) {
            $.ajax({
                url: "<?php echo Yii::app()->createUrl('GoodsSts/delete') ?>/" + id,
                type: "POST",
                success: showResponse,
                error: showResponse
            }).done(function (data) {
                search();
            });
        }
    });

    $(document).on("click", "#GoodsSts-searchbtn", function () {
        search();
    });

    $(document).on("keyup", "#GoodsSts-search", function () {
        search();
    });

    $(document).on("change", "#GoodsSts-pages", function () {
        search();
    });

    function search() {
        $.fn.yiiListView.update('GoodsSts-list', {
            data: {
                val: $("#GoodsSts-search").val(),
                pages: $("#GoodsSts-pages").val()
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
            <h1>Goods Status Registry</h1>
        </div>
    </div>
</div>

<!-- Submit Form BY model -->
<div class="modal fade" id="GoodsSts-addmodel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Goods Status - FORM</h4>
            </div>
            <div class="modal-body">

                <div class="cus-form">
                    <form class="form-horizontal" action="<?php echo Yii::app()->createUrl('GoodsSts/create') ?>" method="post" id="GoodsSts-form">

                        
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
                    <button id="GoodsSts-add" data-toggle="modal" data-target="#GoodsSts-addmodel" class="btn btn-secondary btn-block btn-sm" >
                        Add <span class="oi oi-plus"></span>
                    </button>
                </div>
                <input type="text" id="GoodsSts-search" class="form-control form-control-sm" placeholder="Search by Name/Code etc.">
                <div class="input-group-append">
                    <button id="GoodsSts-searchbtn" class="btn btn-secondary btn-sm" >Search <span class="oi oi-magnifying-glass"></span> <span class="glyphicon glyphicon-search"></span></button>
                </div>
            </div>
        </div> 
        <div class="col-auto">
            <div class="input-group">
                <select id="GoodsSts-pages" name="pages" class="custom-select custom-select-sm">
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
            <?php $this->widget('zii.widgets.CListView', array(
            'dataProvider'=>$dataProvider,
            'itemView'=>'_view',
            'enablePagination' => true,
            'summaryText' => '{page}/{pages} pages',
            'id' => 'GoodsSts-list',
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

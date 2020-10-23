<?php
/* @var $this UomController */
/* @var $dataProvider CActiveDataProvider */
?>



<!--- Script -->
<script>

    $(document).ready(function () {

        $("#Uom-form").ajaxForm({
            beforeSend: function () {

                return $("#Uom-form").validate({
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

        $('#Uom-addmodel').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            if (button.attr("id") === "Uom-add") {
                $("#Uom-form").resetForm();
                $("#Uom-form").attr("action", "<?php echo Yii::app()->createUrl('Uom/create') ?>/");
                $(".hideonupdate").show();
            } else {
                $(".hideonupdate").hide();
            }
        });

    });


    $(document).on("click", ".clickable", function () {
        var id = $(this).parents("div.row").attr("data-id");
        window.location.href = "<?php echo Yii::app()->createUrl('Uom') ?>/" + id;
    });

    $(document).on("click", "#btn-submit", function () {
        $("#Uom-form").submit();
    });


    $(document).on("click", ".Uom-update", function (e) {
        e.preventDefault();
        var id = $(this).attr("data-id");
        $("#Uom-form").resetForm();
        //Handle JSON DATA to Update FORM
        $.getJSON("<?php echo Yii::app()->createUrl('Uom/jsondata') ?>/" + id).done(function (data) {
            $.each(data, function (i, item) {

                if ($("#Uom-form #" + i).is("[type='checkbox']")) {
                    $("#Uom-form #" + i).prop('checked', item);
                } else if ($("#Uom-form #" + i).is("[type='radio']")) {
                    $("#Uom-form #" + i).prop('checked', item);
                } else {
                    $("#Uom-form #" + i).val(item);
                }
            });
            $("#Uom-form").attr("action", "<?php echo Yii::app()->createUrl('Uom/update') ?>/" + id);
        });

        $("#Uom-addmodel").modal('show');
    });

    $(document).on("click", ".Uom-delete", function (e) {
        e.preventDefault();
        var id = $(this).attr("data-id");
        var confirmdata = confirm("Are you sure, you want to delete this record ?");
        if (confirmdata == true) {
            $.ajax({
                url: "<?php echo Yii::app()->createUrl('Uom/delete') ?>/" + id,
                type: "POST",
                success: showResponse,
                error: showResponse
            }).done(function (data) {
                search();
            });
        }
    });

    $(document).on("click", "#Uom-searchbtn", function () {
        search();
    });

    $(document).on("keyup", "#Uom-search", function () {
        search();
    });

    $(document).on("change", "#Uom-pages", function () {
        search();
    });

    function search() {
        $.fn.yiiListView.update('Uom-list', {
            data: {
                val: $("#Uom-search").val(),
                pages: $("#Uom-pages").val()
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
            <h1>Units of Measurements - Registry</h1>
        </div>
    </div>
</div>

<!-- Submit Form BY model -->
<div class="modal fade" id="Uom-addmodel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Units - FORM</h4>
            </div>
            <div class="modal-body">

                <div class="cus-form">
                    <form class="form-horizontal" action="<?php echo Yii::app()->createUrl('Uom/create') ?>" method="post" id="Uom-form">

                        <div class="form-group row">
                            <label for="sym" class="col-sm-4 control-label">IDN Key</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control form-control-sm" id="sym" name="sym" placeholder="Identification Character">
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
                    <button id="Uom-add" data-toggle="modal" data-target="#Uom-addmodel" class="btn btn-default btn-block btn-sm" >
                        Add <span class="oi oi-plus"></span>
                    </button>
                </div>
                <input type="text" id="Uom-search" class="form-control form-control-sm" placeholder="Search by Name/Code etc.">
                <div class="input-group-append">
                    <button id="Uom-searchbtn" class="btn btn-default btn-sm" >Search <span class="glyphicon glyphicon-search"></span></button>
                </div>
            </div>
        </div> 
        <div class="col-auto">
            <div class="input-group">
                <select id="Uom-pages" name="pages" class="custom-select custom-select-sm">
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
            <div class='col headerdiv'>IDN LETTER</div>
            <div class='col headerdiv'>NAME</div>
            <div class="col-sm-1 headerdiv">&nbsp;</div>
        </div>


        <div class="row no-gutters">
            <?php
            $this->widget('zii.widgets.CListView', array(
                'dataProvider' => $dataProvider,
                'itemView' => '_view',
                'enablePagination' => true,
                'summaryText' => '{page}/{pages} pages',
                'id' => 'Uom-list',
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

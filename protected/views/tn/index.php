<?php
/* @var $this TnController */
/* @var $dataProvider CActiveDataProvider */
?>



<!--- Script -->
<script>

    $(document).ready(function () {

        $("#Tn-form").ajaxForm({
            beforeSend: function () {

                return $("#Tn-form").validate({
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

        $('#Tn-addmodel').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            loadProjects($("#customers_id").val());
            if (button.attr("id") === "Tn-add") {
                $("#Tn-form").resetForm();
                $("#Tn-form").attr("action", "<?php echo Yii::app()->createUrl('Tn/create') ?>/");
                $(".hideonupdate").show();
            } else {
                $(".hideonupdate").hide();
            }
        });

    });


     $(document).on("click", ".clickable", function () {

        var id = $(this).parents("div.row").attr("data-id");
        var sts = $(this).parents("div.row").attr("data-st");

        if (sts == 1) {
            window.location.href = "<?php echo Yii::app()->createUrl("tn"); ?>/" + id;
        }else{
            window.open("<?php echo Yii::app()->createUrl('Tn/print/') ?>/" + id, "mywindow", "location=1,status=1,scrollbars=1, width=800,height=600").focus();
        }

    });

    $(document).on("click", "#btn-submit", function () {
        $("#Tn-form").submit();
    });


    $(document).on("click", ".Tn-update", function (e) {
        e.preventDefault();
        var id = $(this).attr("data-id");
        $("#Tn-form").resetForm();
        //Handle JSON DATA to Update FORM
        $.getJSON("<?php echo Yii::app()->createUrl('Tn/jsondata') ?>/" + id).done(function (data) {
            $.each(data, function (i, item) {

                if ($("#Tn-form #" + i).is("[type='checkbox']")) {
                    $("#Tn-form #" + i).prop('checked', item);
                } else if ($("#Tn-form #" + i).is("[type='radio']")) {
                    $("#Tn-form #" + i).prop('checked', item);
                } else {
                    $("#Tn-form #" + i).val(item);
                }
            });
            $("#Tn-form").attr("action", "<?php echo Yii::app()->createUrl('Tn/update') ?>/" + id);
        });

        $("#Tn-addmodel").modal('show');
    });

    $(document).on("click", ".Tn-delete", function (e) {
        e.preventDefault();
        var id = $(this).attr("data-id");
        var confirmdata = confirm("Are you sure, you want to delete this record ?");
        if (confirmdata == true) {
            $.ajax({
                url: "<?php echo Yii::app()->createUrl('Tn/delete') ?>/" + id,
                type: "POST",
                success: showResponse,
                error: showResponse
            }).done(function (data) {
                search();
            });
        }
    });

    $(document).on("click", "#Tn-searchbtn", function () {
        search();
    });

    $(document).on("keyup", "#Tn-search", function () {
        search();
    });

    $(document).on("change", "#Tn-pages", function () {
        search();
    });

    function search() {
        $.fn.yiiListView.update('Tn-list', {
            data: {
                val: $("#Tn-search").val(),
                pages: $("#Tn-pages").val()
            },
            complete: function () {
                //CODE GOES HERE
            }
        });
    }
    
    $(document).on("change","#customers_id",function(e){
       loadProjects($(this).val()); 
    });
    
    function loadProjects(cus_id) {
        $.ajax({
            url: "<?php echo Yii::app()->createUrl('project/loadlist') ?>/"+cus_id,
            type: "POST",
            error: showResponse,
        }).done(function (data) {
            $("#project_from").html(data);
            $("#project_to").html(data);
        });
    }


</script>
<!-- //END SCRIPT -->

<div id="header-sec">
    <div class="row">
        <div class="col-sm-12">
            <h1>Stock Transfer Process</h1>
        </div>
    </div>
</div>

<!-- Submit Form BY model -->
<div class="modal fade" id="Tn-addmodel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Stock Transfer Process</h4>
            </div>
            <div class="modal-body">

                <div class="cus-form">
                    <form class="form-horizontal" action="<?php echo Yii::app()->createUrl('Tn/create') ?>" method="post" id="Tn-form">

                        <div class="form-row mb-2">
                            <label for="code" class="col-sm-3 control-label">Customer</label>
                            <div class="col-sm-9">
                                <select id="customers_id" name="customers_id" class="custom-select custom-select-sm">
                                    <?php
                                    $list = Customers::model()->findAll();
                                    foreach ($list as $value) {
                                        echo "<option value='" . $value->id . "'>" . $value->code . " - " . $value->name . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-row mb-2">
                            <label for="code" class="col-sm-3 control-label">Remarks</label>
                            <div class="col-sm-9">
                                <textarea id="remarks" name="remarks" class="form-control form-control-sm"></textarea>
                            </div>
                        </div>
                        <div class="row no-gutters">

                            <div class="col-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Transfer From</h5>
                                        <div class="form-row mb-2">
                                            <label for="warehouse_from" class="col-sm-4 control-label">Warehouse</label>
                                            <div class="col-sm-8">
                                                <select id="warehouse_from" name="warehouse_from" class="custom-select custom-select-sm">
                                                    <?php
                                                    $list = Warehouse::model()->findAll();
                                                    foreach ($list as $value) {
                                                        echo "<option value='" . $value->id . "'>" . $value->code . " - " . $value->name . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-row mb-2">
                                            <label for="location_from" class="col-sm-4 control-label">Zone</label>
                                            <div class="col-sm-8">
                                                <select id="locations_from" name="locations_from" class="custom-select custom-select-sm">
                                                    <?php
                                                    $list = Locations::model()->findAll();
                                                    foreach ($list as $value) {
                                                        echo "<option value='" . $value->id . "'>" . $value->code . " - " . $value->name . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="form-row mb-2">
                                            <label for="sub_location_from" class="col-sm-4 control-label">Location</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="sub_location_from" id="sub_location_from" placeholder="Location From" class="form-control form-control-sm" />
                                            </div>
                                        </div>
                                        <div class="form-row mb-2">
                                            <label for="code" class="col-sm-4 control-label">Project</label>
                                            <div class="col-sm-8">
                                                <select id="project_from" name="project_from" class="custom-select custom-select-sm"></select>
                                            </div>
                                        </div>
                                        <div class="form-row mb-2">
                                            <label for="goods_sts_from" class="col-sm-4 control-label">Status</label>
                                            <div class="col-sm-8">
                                                <select id="goods_sts_from" name="goods_sts_from" class="custom-select custom-select-sm">
                                                    <?php
                                                    $list = GoodsSts::model()->findAll();
                                                    foreach ($list as $value) {
                                                        echo "<option value='" . $value->id . "'>" .  $value->name . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="card bg-warning ">
                                    <div class="card-body">
                                        <h5 class="card-title">Transfer To</h5>
                                        <div class="form-row mb-2">
                                            <label for="warehouse_to" class="col-sm-4 control-label">Warehouse</label>
                                            <div class="col-sm-8">
                                                <select id="warehouse_to" name="warehouse_to" class="custom-select custom-select-sm">
                                                    <?php
                                                    $list = Warehouse::model()->findAll();
                                                    foreach ($list as $value) {
                                                        echo "<option value='" . $value->id . "'>" . $value->code . " - " . $value->name . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-row mb-2">
                                            <label for="location_to" class="col-sm-4 control-label">Zone</label>
                                            <div class="col-sm-8">
                                                <select id="locations_to" name="locations_to" class="custom-select custom-select-sm">
                                                    <?php
                                                    $list = Locations::model()->findAll();
                                                    foreach ($list as $value) {
                                                        echo "<option value='" . $value->id . "'>" . $value->code . " - " . $value->name . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="form-row mb-2">
                                            <label for="sub_location" class="col-sm-4 control-label">Location</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="sub_location" id="sub_location" placeholder="Location"  class="form-control form-control-sm" />
                                            </div>
                                        </div>
                                      
                                        
                                        
                                        <div class="form-row mb-2">
                                            <label for="code" class="col-sm-4 control-label">Project</label>
                                            <div class="col-sm-8">
                                                <select id="project_to" name="project_to" class="custom-select custom-select-sm"></select>
                                            </div>
                                        </div>
                                        <div class="form-row mb-2">
                                            <label for="goods_sts_to" class="col-sm-4 control-label">Status</label>
                                            <div class="col-sm-8">
                                                <select id="goods_sts_to" name="goods_sts_to" class="custom-select custom-select-sm">
                                                    <?php
                                                    $list = GoodsSts::model()->findAll();
                                                    foreach ($list as $value) {
                                                        echo "<option value='" . $value->id . "'>" .  $value->name . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
                    <button id="Tn-add" data-toggle="modal" data-target="#Tn-addmodel" class="btn btn-secondary btn-block btn-sm" >
                        Add <span class="oi oi-plus"></span>
                    </button>
                </div>
                <input type="text" id="Tn-search" class="form-control form-control-sm" placeholder="Search by Name/Code etc.">
                <div class="input-group-append">
                    <button id="Tn-searchbtn" class="btn btn-secondary btn-sm" >Search <span class="oi oi-magnifying-glass"></span> <span class="glyphicon glyphicon-search"></span></button>
                </div>
            </div>
        </div> 
        <div class="col-auto">
            <div class="input-group">
                <select id="Tn-pages" name="pages" class="custom-select custom-select-sm">
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
            <div class='col headerdiv'>CODE</div>
            <div class='col headerdiv'>WH/FROM</div>
            <div class='col headerdiv'>WH/TO</div>
            <div class='col headerdiv'>STATUS/FROM</div>
            <div class='col headerdiv'>STATUS/TO</div>
            <div class='col headerdiv'>PROJECT/FROM</div>
            <div class='col headerdiv'>PROJECT/TO</div>
            <div class='col headerdiv'>REMARKS</div>
            <div class='col headerdiv'>UPDATE</div>
            <div class='col headerdiv'>STATUS</div>
            <div class="col-sm-1 headerdiv">&nbsp;</div>
        </div>


        <div class="row no-gutters">
            <?php
            $this->widget('zii.widgets.CListView', array(
                'dataProvider' => $dataProvider,
                'itemView' => '_view',
                'enablePagination' => true,
                'summaryText' => '{page}/{pages} pages',
                'id' => 'Tn-list',
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

<?php
/* @var $this SkuController */
/* @var $dataProvider CActiveDataProvider */
?>



<!--- Script -->
<script>

    $(document).ready(function () {

        $("#sku-csv").ajaxForm({
            beforeSend: function () {

                return $("#sku-csv").validate({
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
            success: function (data) {
                var jsondata = JSON.parse(data);
                $("#error_txt").html(jsondata.msg);
                $("#err_table").html("");
                var num = 1;
                $(jsondata.er).each(function (data, i) {
                    $("#err_table").append("<tr><td>" + num + "</td><td>" + i.code + "</td><td>" + i.description + "</td><td>" + i.error + "</td></tr>");
                    num += 1;
                });

                showResponse(data);
            },
            error: showResponse,
            complete: function () {
                searchSku();
            }
        });

        $("#Sku-form").ajaxForm({
            beforeSend: function () {

                return $("#Sku-form").validate({
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
                searchSku();
            }
        });

        $('#Sku-addmodel').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            loadCategory();
            if (button.attr("id") === "Sku-add") {
                $("#Sku-form").resetForm();
                $("#Sku-form").attr("action", "<?php echo Yii::app()->createUrl('Sku/create') ?>/");
                $(".hideonupdate").show();
            } else {
                $(".hideonupdate").hide();
            }
        });
        
        $('#Sku-addcsv').on('show.bs.modal', function (event) {
            loadCategory();
        });

    });


    $(document).on("click", ".clickable", function () {
        var id = $(this).parents("div.row").attr("data-id");
        window.location.href = "<?php echo Yii::app()->createUrl('Sku') ?>/" + id;
    });

    $(document).on("click", "#btn-submit-sku", function () {
        $("#Sku-form").submit();
    });
    
    $(document).on("click", "#btn-submit-csv", function () {
        $("#sku-csv").submit();
    });


    $(document).on("click", ".Sku-update", function (e) {
        e.preventDefault();
        var id = $(this).attr("data-id");
        $("#Sku-form").resetForm();
        //Handle JSON DATA to Update FORM
        $.getJSON("<?php echo Yii::app()->createUrl('Sku/jsondata') ?>/" + id).done(function (data) {
            $.each(data, function (i, item) {

                if ($("#Sku-form #" + i).is("[type='checkbox']")) {
                    $("#Sku-form #" + i).prop('checked', item);
                } else if ($("#Sku-form #" + i).is("[type='radio']")) {
                    $("#Sku-form #" + i).prop('checked', item);
                } else {
                    $("#Sku-form #" + i).val(item);
                }
            });
            $("#Sku-form").attr("action", "<?php echo Yii::app()->createUrl('Sku/update') ?>/" + id);
        });

        $("#Sku-addmodel").modal('show');
    });

    $(document).on("click", ".Sku-delete", function (e) {
        e.preventDefault();
        var id = $(this).attr("data-id");
        var confirmdata = confirm("Are you sure, you want to delete this record ?");
        if (confirmdata == true) {
            $.ajax({
                url: "<?php echo Yii::app()->createUrl('Sku/delete') ?>/" + id,
                type: "POST",
                success: showResponse,
                error: showResponse
            }).done(function (data) {
                searchSku();
            });
        }
    });

    $(document).on("click", "#Sku-searchSkubtn", function () {
        searchSku();
    });

    $(document).on("keyup", "#Sku-searchSku", function () {
        searchSku();
    });

    $(document).on("change", "#Sku-pages", function () {
        searchSku();
    });

    function searchSku() {
        $.fn.yiiListView.update('Sku-list', {
            data: {
                val: $("#Sku-searchSku").val(),
                pages: $("#Sku-pages").val()
            }
        });
    }

    function loadCategory() {
        Pace.restart();
        $.ajax({
            url: "<?php echo Yii::app()->createUrl('category/loadlist/'.$model->id) ?>/",
            type: "POST",
            async : false
        }).done(function (data) {
            $(".cat_list").html(data);
        });
    }





</script>
<!-- //END SCRIPT -->



<!-- Submit Form BY model -->
<div class="modal fade" id="Sku-addcsv" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">CSV Line Input</h4>
            </div>
            <div class="modal-body">

                <div class="cus-form">
                    <form enctype="multipart/form-data" class="form-horizontal" action="<?php echo Yii::app()->createUrl('sku/csvupload') ?>" method="post" id="sku-csv">
                        <input type="hidden" name="customers_id" id="customers_id_csv" value="<?php echo $model->id ?>" />
                        <p>Download Sample csv file <a href="<?php echo Yii::app()->request->baseUrl; ?>/sku.csv">Here</a></p>
                        <div class="form-group row">
                            <label for="category_id" class="col-sm-4 control-label">Category</label>
                            <div class="col-sm-3">
                                <select id="category_id_csv" name="category_id" class="custom-select cat_list custom-select-sm">
                                    
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="uom_id" class="col-sm-4 control-label">Unit of Measure</label>
                            <div class="col-sm-3">
                                <select id="uom_id_csv" name="uom_id" class="custom-select custom-select-sm">
                                    <?php
                                    $list = Uom::model()->findAll();
                                    foreach ($list as $value) {
                                        echo "<option value='" . $value->id . "'>" . $value->name . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="category_id" class="col-sm-2 control-label">CSV File</label>
                            <div class="col-sm-9">
                                <input type="file" name="csvfile" id="csvfile" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="category_id" class="col-sm-2 control-label"></label>
                            <div class="col-sm-9">
                                <div id="error_txt"></div>
                                <table class="table table-sm table-sf">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>CODE</th>
                                            <th>DESCRIPTION</th>
                                            <th>ERROR</th>
                                        </tr>
                                    </thead>
                                    <tbody id="err_table">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
            <div class="modal-footer">
                <button id="btn-submit-csv" type="button" class="btn btn-success btn-sm">Save</button>
            </div>
        </div>
    </div>
</div>
<!-- Submit Form BY model -->


<!-- Submit Form BY model -->
<div class="modal fade" id="Sku-addmodel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">SKU - FORM</h4>
            </div>
            <div class="modal-body">

                <div class="cus-form">
                    <form class="form-horizontal" action="<?php echo Yii::app()->createUrl('Sku/create') ?>" method="post" id="Sku-form">
                        <input type="hidden" name="customers_id" id="customers_id" value="<?php echo $model->id; ?>" />

                        <div class="form-row mb-2">
                            <label for="category_id" class="col-sm-4 control-label">Category</label>
                            <div class="col-sm-8">
                                <select id="category_id" name="category_id" class="custom-select cat_list custom-select-sm">
                                </select>
                            </div>
                        </div>
                        <div class="form-row mb-2">
                            <label for="uom_id" class="col-sm-4 control-label">Unit</label>
                            <div class="col-sm-4">
                                <select id="uom_id" name="uom_id" class="custom-select custom-select-sm">
                                    <?php
                                    $list = Uom::model()->findAll();
                                    foreach ($list as $value) {
                                        echo "<option value='" . $value->id . "'>" . $value->sym . " - " . $value->name . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-row mb-2">
                            <label for="code" class="col-sm-4 control-label">Code</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control form-control-sm" id="code" name="code" placeholder="Code">
                            </div>
                        </div>
                        <div class="form-row mb-2">
                            <label for="description" class="col-sm-4 control-label">Description</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm" id="description" name="description" placeholder="Description">
                            </div>
                        </div>
                        <div class="form-row mb-2">
                            <label for="cbm" class="col-sm-4 control-label">Spec</label>
                            <div class="col-sm-4">
                                <label>CBM</label>
                                <input type="text" class="form-control form-control-sm" id="cbm" name="cbm" placeholder="CBM">
                            </div>
                            <div class="col-sm-4">
                                <label>Sqft</label>
                                <input type="text" class="form-control form-control-sm" id="sqft" name="sqft" placeholder="SQFT">
                            </div>
                        </div>
                        <div class="form-row mb-2">
                            <label for="volume" class="col-sm-4 control-label"></label>
                            <div class="col-sm-4">
                                <label>Volume </label>
                                <input type="text" class="form-control form-control-sm" id="volume" name="volume" placeholder="Volume">
                            </div>
                            <div class="col-sm-4">
                                <label>Weight ( Kg )</label>
                                <input type="text" class="form-control form-control-sm" id="weight" name="weight" placeholder="Weight">
                            </div>
                        </div>
                        <div class="form-row mb-2">
                            <label for="rol" class="col-sm-4 control-label">Re-Ordering</label>
                            <div class="col-sm-4">
                                <label>Level</label>
                                <input type="text" class="form-control form-control-sm" id="rol" name="rol" placeholder="Re-Order Level">
                            </div>
                            <div class="col-sm-4">
                                <label>Qty</label>
                                <input type="text" class="form-control form-control-sm" id="roq" name="roq" placeholder="Re-Order Qty">
                            </div>
                        </div>
                        <div class="form-row mb-2">
                            <label for="max_stacking" class="col-sm-4 control-label">Stacking</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control form-control-sm" id="max_stacking" name="max_stacking" placeholder="Max Stacking Qty">
                            </div>
                        </div>

                        <div class="form-row mb-2">
                            <label for="pick_type" class="col-sm-4 control-label">Removal Strategy</label>
                            <div class="col-sm-6">
                                <select id="pick_type" name="pick_type" class="custom-select custom-select-sm">
                                    <option value="FIFO">First In, First Out</option>
                                    <option value="LIFO">Last In, First Out</option>
                                    <option value="FEFO">First Expire, First Out</option>
                                </select>
                            </div>
                        </div>

                    </form>
                </div>

            </div>
            <div class="modal-footer">
                <button id="btn-submit-sku" type="button" class="btn btn-success btn-sm">Save</button>
            </div>
        </div>
    </div>
</div>
<!-- Submit Form BY model -->

<div id="title-nav" class="inputsearchSku">
    <div class="row justify-content-between">

        <div class="col-4">
            <div class="input-group">
                <div class="input-group-append">

                    <button id="Sku-add" data-toggle="modal" data-target="#Sku-addmodel" class="btn btn-default btn-block btn-sm" >
                        Add <span class="oi oi-plus"></span>
                    </button>
                </div>
                <input type="text" id="Sku-searchSku" class="form-control form-control-sm" placeholder="Search by Name/Code etc.">
                <div class="input-group-append">
                    <button id="Sku-searchSkubtn" class="btn btn-default btn-sm" >Search <span class="glyphicon glyphicon-searchSku"></span></button>
                </div>
            </div>
        </div> 
        <div class="col-auto">
            <div class="input-group">
                <button id="Sku-csvadd" data-toggle="modal" data-target="#Sku-addcsv" class="btn btn-default btn-sm" >
                    CSV Upload <span class="oi oi-plus"></span>
                </button>
                <select id="Sku-pages" name="pages" class="custom-select custom-select-sm">
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
            <div class='col-2 headerdiv'>CATEGORY</div>
            <div class='col-3 headerdiv'>CODE</div>
            <div class='col-4 headerdiv'>DESCRIPTION</div>
            <div class='col headerdiv'>REMOVAL</div>
            <div class='col headerdiv'>CBM</div>
            <div class="col-sm-1 headerdiv">&nbsp;</div>
        </div>


        <div class="row no-gutters">
            <?php
            $this->widget('zii.widgets.CListView', array(
                'dataProvider' => $dataProviderSku,
                'itemView' => '_viewSku',
                'enablePagination' => true,
                'summaryText' => '{page}/{pages} pages',
                'id' => 'Sku-list',
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

<?php
/* @var $this MrController */
/* @var $dataProvider CActiveDataProvider */
?>



<!--- Script -->
<script>

    $(document).ready(function () {

        $("#Mr-form").ajaxForm({
            beforeSend: function () {

                return $("#Mr-form").validate({
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
                var result = JSON.parse(data);
                if (result.id != 0) {
                    window.location.href = "<?php echo Yii::app()->createUrl("mr"); ?>/" + result.id;
                }
                showResponse(data);
            },
            error: showResponse,
            complete: function () {
                search();
            }
        });

        $('#Mr-addmodel').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            loadProjects($("#customers_id").val());
            if (button.attr("id") === "Mr-add") {
                $("#Mr-form").resetForm();
                $("#Mr-form").attr("action", "<?php echo Yii::app()->createUrl('Mr/create') ?>/");
                $(".hideonupdate").show();
            } else {
                $(".hideonupdate").hide();
            }
        });

    });


    $(document).on("click", ".clickable", function () {

        

        var id = $(this).parents("div.row").attr("data-id");
        var sts = $(this).parents("div.row").attr("data-st");

        if (sts == 3) {
            window.location.href = "<?php echo Yii::app()->createUrl("pick"); ?>/" + id;
        } else {
            
            var conf = confirm("Are you Sure, You want to create this Pick ?");
            if (conf == false) {
                return;
            }
            
            window.open("<?php echo Yii::app()->createUrl('pick/process/') ?>/" + id, "mywindow", "location=1,status=1,scrollbars=1, width=800,height=600").focus();
            window.location.href = "<?php echo Yii::app()->createUrl("pick"); ?>/"+id;
            search();
        }
        
    });

    $(document).on("click", "#btn-submit", function () {
        $("#Mr-form").submit();
    });


    $(document).on("click", ".Mr-update", function (e) {
        e.preventDefault();
        var id = $(this).attr("data-id");
        $("#Mr-form").resetForm();
        //Handle JSON DATA to Update FORM
        $.getJSON("<?php echo Yii::app()->createUrl('Mr/jsondata') ?>/" + id).done(function (data) {
            $.each(data, function (i, item) {

                if ($("#Mr-form #" + i).is("[type='checkbox']")) {
                    $("#Mr-form #" + i).prop('checked', item);
                } else if ($("#Mr-form #" + i).is("[type='radio']")) {
                    $("#Mr-form #" + i).prop('checked', item);
                } else {
                    $("#Mr-form #" + i).val(item);
                }
            });
            $("#Mr-form").attr("action", "<?php echo Yii::app()->createUrl('Mr/update') ?>/" + id);
        });

        $("#Mr-addmodel").modal('show');
    });

    $(document).on("click", ".Mr-delete", function (e) {
        e.preventDefault();
        var id = $(this).attr("data-id");
        var confirmdata = confirm("Are you sure, you want to delete this record ?");
        if (confirmdata == true) {
            $.ajax({
                url: "<?php echo Yii::app()->createUrl('Mr/delete') ?>/" + id,
                type: "POST",
                success: showResponse,
                error: showResponse
            }).done(function (data) {
                search();
            });
        }
    });

    $(document).on("click", "#Mr-searchbtn", function () {
        search();
    });

    $(document).on("keyup", "#Mr-search", function () {
        search();
    });

    $(document).on("change", "#Mr-pages", function () {
        search();
    });

    function search() {
        $.fn.yiiListView.update('Mr-list', {
            data: {
                val: $("#Mr-search").val(),
                pages: $("#Mr-pages").val()
            },
            complete: function () {
                //CODE GOES HERE
            }
        });
    }

    $(document).on("change", "#customers_id", function (e) {
        loadProjects($(this).val());
    });

    function loadProjects(cus_id) {
        $.ajax({
            url: "<?php echo Yii::app()->createUrl('project/loadlist') ?>/" + cus_id,
            type: "POST",
            error: showResponse,
        }).done(function (data) {
            $("#project_id").html(data);
        });
    }


</script>
<!-- //END SCRIPT -->

<div id="header-sec">
    <div class="row">
        <div class="col-sm-12">
            <h1>Picklist Registry</h1>
        </div>
    </div>
</div>

<!-- Submit Form BY model -->
<div class="modal fade" id="Mr-addmodel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">MR - FORM</h4>
            </div>
            <div class="modal-body">

                <div class="cus-form">
                    <form class="form-horizontal" action="<?php echo Yii::app()->createUrl('Mr/create') ?>" method="post" id="Mr-form">


                        <div class="row">

                            <div class="col-6">
                                <div>
                                    <h4 style="font-size: 16px; border-bottom: 1px dashed #dcdcdc; padding-bottom: 8px;">Request Entry</h4>

                                    <div class="form-row mb-2">
                                        <label for="code" class="col-sm-4 control-label">Customer</label>
                                        <div class="col-sm-6">
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
                                        <label for="code" class="col-sm-4 control-label">Warehouse</label>
                                        <div class="col-sm-6">
                                            <select id="warehouse_id" name="warehouse_id" class="custom-select custom-select-sm">
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
                                        <label for="project_id" class="col-sm-4 control-label">Project</label>
                                        <div class="col-sm-6">
                                            <select id="project_id" name="project_id" class="custom-select custom-select-sm"></select>
                                        </div>
                                    </div>
                                    <div class="form-row mb-2">
                                        <label for="eff_date" class="col-sm-4 control-label">Date</label>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control form-control-sm datepicker" id="eff_date" name="eff_date" placeholder="Date">
                                        </div>
                                    </div>
                                    <div class="form-row mb-2">
                                        <label for="delivery_date" class="col-sm-4 control-label">Delivery Date</label>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control form-control-sm datepicker" id="delivery_date" name="delivery_date" placeholder="Delivery Date">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div>
                                    <h4 style="font-size: 16px; border-bottom: 1px dashed #dcdcdc; padding-bottom: 8px;">Collector Details</h4>
                                    <div class="form-row mb-2">
                                        <label for="col_name" class="col-sm-4 control-label">Name</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control form-control-sm" id="col_name" name="col_name" placeholder="Name">
                                        </div>
                                    </div>
                                    <div class="form-row mb-2">
                                        <label for="col_nic" class="col-sm-4 control-label">NIC</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control form-control-sm" id="col_nic" name="col_nic" placeholder="NIC">
                                        </div>
                                    </div>
                                    <div class="form-row mb-2">
                                        <label for="col_mobile" class="col-sm-4 control-label">Mobile</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control form-control-sm" id="col_mobile" name="col_mobile" placeholder="Mobile">
                                        </div>
                                    </div>
                                    <div class="form-row mb-2">
                                        <label for="col_vehicle" class="col-sm-4 control-label">Vehicle No#</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control form-control-sm" id="col_vehicle" name="col_vehicle" placeholder="Vehicle No#">
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h4 style="font-size: 16px; border-bottom: 1px dashed #dcdcdc; padding-bottom: 8px;">Site Details</h4>
                                    <div class="form-row mb-2">
                                        <label for="dest1_name" class="col-sm-4 control-label">Destination 01</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control form-control-sm" id="dest1_name" name="dest1_name" placeholder="Destination 01">
                                        </div>
                                    </div>
                                    <div class="form-row mb-2">
                                        <label for="dest2_name" class="col-sm-4 control-label">Destination 02</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control form-control-sm" id="dest2_name" name="dest2_name" placeholder="Destination 02">
                                        </div>
                                    </div>
                                    <div class="form-row mb-2">
                                        <label for="link_name" class="col-sm-4 control-label">Link Name</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control form-control-sm" id="link_name" name="link_name" placeholder="Link">
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
                <input type="text" id="Mr-search" class="form-control form-control-sm" placeholder="Search by Name/Code etc.">
                <div class="input-group-append">
                    <button id="Mr-searchbtn" class="btn btn-secondary btn-sm" >Search <span class="oi oi-magnifying-glass"></span> <span class="glyphicon glyphicon-search"></span></button>
                </div>
            </div>
        </div> 
        <div class="col-auto">
            <div class="input-group">
                <select id="Mr-pages" name="pages" class="custom-select custom-select-sm">
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
            <div class='col-1 headerdiv'>CODE</div>
            <div class='col-2 headerdiv'>CUSTOMER</div>
            <div class='col-2 headerdiv'>WAREHOUSE</div>
            <div class='col-1 headerdiv'>DATE</div>
            <div class='col-1 headerdiv'>DELIVERY</div>
            <div class='col-2 headerdiv'>C/NAME</div>
            <div class='col-1 headerdiv'>C/NIC</div>
            <div class='col-1 headerdiv'>DESTINATION</div>
            <div class='col-1 headerdiv'>C/VEHICLE</div>
        </div>


        <div class="row no-gutters">
            <?php
            $this->widget('zii.widgets.CListView', array(
                'dataProvider' => $dataProvider,
                'itemView' => '_view',
                'enablePagination' => true,
                'summaryText' => '{page}/{pages} pages',
                'id' => 'Mr-list',
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

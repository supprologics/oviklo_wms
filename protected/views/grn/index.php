<?php
/* @var $this GrnController */
/* @var $dataProvider CActiveDataProvider */
?>



<!--- Script -->
<script>

    $(function () {

        /* JOB ***/
        $.widget("custom.tablecomplete", $.ui.autocomplete, {
            _create: function () {
                this._super();
                this.widget().menu("option", "items", "> tr:not(.ui-autocomplete-header)");
            },
            _renderMenu(ul, items) {
                var self = this;
                //table definitions
                var $t = $("<table class='table table-sm table-sp'>", {
                    border: 0
                }).appendTo(ul);
                $t.append($("<thead>"));
                $t.find("thead").append($("<tr>"));
                var $row = $t.find("tr");
                $("<th>").html("Name").appendTo($row);
                $("<tbody>").appendTo($t);
                $.each(items, function (index, item) {
                    self._renderItemData(ul, $t.find("tbody"), item);
                });
            },
            _renderItemData(ul, table, item) {
                return this._renderItem(table, item).data("ui-autocomplete-item", item);
            },
            _renderItem(table, item) {
                var $row = $("<tr>", {
                    class: "ui-menu-item",
                    role: "presentation"
                });
                $("<td>").html(item.value).appendTo($row);
                return $row.appendTo(table);
            }
        });

        function _doFocusStuff(event, ui) {
            if (ui.item) {
                var $item = ui.item;
            }
            return false;
        }

        // create the autocomplete
        var autocomplete = $("#supplier").tablecomplete({
            minLength: 2,
            source: "<?php echo Yii::app()->createUrl('grn/loadSuppliers/'); ?>/",
            focus: _doFocusStuff,
            appendTo: "#Grn-addmodel",
            select: function (event, ui) {
                $("#supplier").val(ui.item.value);
            }
        });
    });

    $(document).ready(function () {

        $("#Grn-form").ajaxForm({
            beforeSend: function () {

                return $("#Grn-form").validate({
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
                    window.location.href = "<?php echo Yii::app()->createUrl("grn"); ?>/" + result.id;
                }
                showResponse(data);
            },
            error: showResponse,
            complete: function () {
                search();
            }
        });

        $('#Grn-addmodel').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            //loadProjects($("#customers_id").val());
            if (button.attr("id") === "Grn-add") {
                $("#Grn-form").resetForm();
                $("#Grn-form").attr("action", "<?php echo Yii::app()->createUrl('Grn/create') ?>/");
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
            window.location.href = "<?php echo Yii::app()->createUrl("grn"); ?>/" + id;
        } else if (sts == 2) {
            window.location.href = "<?php echo Yii::app()->createUrl("grn/tally"); ?>/" + id;
        } else {
            window.open("<?php echo Yii::app()->createUrl('grn/print/') ?>/" + id, "mywindow", "location=1,status=1,scrollbars=1, width=800,height=600").focus();
        }

    });
    
    $(document).on("click", ".grn-print-s", function () {
        var id = $(this).attr("data-id");
        window.open("<?php echo Yii::app()->createUrl('grn/Serialsprint/') ?>/" + id, "mywindow", "location=1,status=1,scrollbars=1, width=800,height=600").focus();
    });

    $(document).on("click", "#btn-submit", function () {
        $("#Grn-form").submit();
    });


    $(document).on("click", ".Grn-update", function (e) {
        e.preventDefault();
        var id = $(this).attr("data-id");
        $("#Grn-form").resetForm();
        //Handle JSON DATA to Update FORM
        $.getJSON("<?php echo Yii::app()->createUrl('Grn/jsondata') ?>/" + id).done(function (data) {
            $.each(data, function (i, item) {

                if ($("#Grn-form #" + i).is("[type='checkbox']")) {
                    $("#Grn-form #" + i).prop('checked', item);
                } else if ($("#Grn-form #" + i).is("[type='radio']")) {
                    $("#Grn-form #" + i).prop('checked', item);
                } else {
                    $("#Grn-form #" + i).val(item);
                }
            });
            $("#Grn-form").attr("action", "<?php echo Yii::app()->createUrl('Grn/update') ?>/" + id);
        });

        $("#Grn-addmodel").modal('show');
    });

    $(document).on("click", ".Grn-delete", function (e) {
        e.preventDefault();
        var id = $(this).attr("data-id");
        var confirmdata = confirm("Are you sure, you want to delete this record ?");
        if (confirmdata == true) {
            $.ajax({
                url: "<?php echo Yii::app()->createUrl('Grn/delete') ?>/" + id,
                type: "POST",
                success: showResponse,
                error: showResponse
            }).done(function (data) {
                search();
            });
        }
    });

    $(document).on("click", "#Grn-searchbtn", function () {
        search();
    });

    $(document).on("keyup", "#Grn-search", function () {
        search();
    });

    $(document).on("change", "#Grn-pages", function () {
        search();
    });
    
    $(document).on("click","#btn-submit-search",function(){
        $("#filters-add").addClass("btn-success");
        search();
    });

    function search() {
        $.fn.yiiListView.update('Grn-list', {
            data: {
                val: $("#Grn-search").val(),
                pages: $("#Grn-pages").val(),
                customers_id: $("#search_customers_id").val(),
                warehouse_id: $("#search_warehouse_id").val(),
                project_id: $("#search_project_id").val(),
                date_from: $("#search_datefrom").val(),
                date_to: $("#search_dateto").val(),
                vehicle_no: $("#search_vehicle_no").val(),
                packinglist_no: $("#search_packinglist_no").val(),  
                ref_no: $("#search_ref_no").val()   
            }
        });
    }

    $(document).on("change", "#customers_id", function (e) {
        loadProjects($(this).val(),"#project_id");
    });
    
    $(document).on("change", "#search_customers_id", function (e) {
        loadProjects($(this).val(),"#search_project_id");
    });
    
    $(document).on("click","#btn-clear-search",function(e){
       $("#filters-add").removeClass("btn-success");
       $("#filter-form").resetForm(); 
       search();
    });

    function loadProjects(cus_id,target) {
        $.ajax({
            url: "<?php echo Yii::app()->createUrl('project/loadlist') ?>/" + cus_id,
            type: "POST",
            error: showResponse,
        }).done(function (data) {
            $(target).html(data);
        });
    }


</script>
<!-- //END SCRIPT -->

<div id="header-sec">
    <div class="row">
        <div class="col-sm-12">
            <h1>Goods Receiving Note Registry</h1>
        </div>
    </div>
</div>

<!-- Submit Form BY model -->
<div class="modal fade" id="Grn-addmodel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">GRN - FORM</h4>
            </div>
            <div class="modal-body">

                <div class="cus-form">
                    <form class="form-horizontal" action="<?php echo Yii::app()->createUrl('Grn/create') ?>" method="post" id="Grn-form">

                        <div class="form-row mb-2">
                            <label for="code" class="col-sm-4 control-label">Customer</label>
                            <div class="col-sm-6">
                                <select id="customers_id" name="customers_id" class="custom-select custom-select-sm"> 
                                    <option value="">Select Customer</option>
                                    <?php
                                    $users_id = Yii::app()->user->getId();
                                    $list = UserHasCustomers::model()->findAllByAttributes(array("users_id" => $users_id, "online" => 1 ,"grn_" => 1));
                                    foreach ($list as $value) {
                                        echo "<option value='" . $value->customers->id . "'>" . $value->customers->code . " - " . $value->customers->name . "</option>";
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
                                    $users_id = Yii::app()->user->getId();
                                    $list = UserHasWarehouse::model()->findAllByAttributes(array("users_id" => $users_id, "online" => 1));
                                    foreach ($list as $value) {
                                        echo "<option value='" . $value->warehouse->id . "'>" . $value->warehouse->code . " - " . $value->warehouse->name . "</option>";
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
                            <label for="supplier" class="col-sm-4 control-label">Supplier Name</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm" id="supplier" name="supplier" placeholder="Supplier Name">
                            </div>
                        </div>

                        <div class="form-row mb-2">
                            <label for="eff_date" class="col-sm-4 control-label">Date</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control form-control-sm datepicker" id="eff_date" name="eff_date" placeholder="Date">
                            </div>
                        </div>

                        <div class="form-row mb-2">
                            <label for="container_no" class="col-sm-4 control-label">Container No#</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control form-control-sm" id="container_no" name="container_no" placeholder="Container No#">
                            </div>
                        </div>

                        <div class="form-row mb-2">
                            <label for="vehicle_no" class="col-sm-4 control-label">Vehicle No#</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control form-control-sm" id="vehicle_no" name="vehicle_no" placeholder="Vehicle No#">
                            </div>
                        </div>
                        <div class="form-row mb-2">
                            <label for="packinglist_no" class="col-sm-4 control-label">Packing List No#</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control form-control-sm" id="packinglist_no" name="packinglist_no" placeholder="Packing List No#">
                            </div>
                        </div>
                        <div class="form-row mb-2">
                            <label for="ref_no" class="col-sm-4 control-label">Ref No#</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control form-control-sm" id="ref_no" name="ref_no" placeholder="Referance No#">
                            </div>
                        </div>                        

                        <div class="form-row mb-2">
                            <label for="project_no" class="col-sm-4 control-label">Vehicle Entry</label>
                            <div class="col-sm-4">
                                <label>IN</label>
                                <input type="time" class="form-control form-control-sm" id="vehicle_in" name="vehicle_in" placeholder="hh:mm">
                            </div>
                            <div class="col-sm-4">
                                <label>OUT</label>
                                <input type="time" class="form-control form-control-sm" id="vehicle_out" name="vehicle_out" placeholder="hh:mm">
                            </div>
                        </div>

                        <div class="form-row mb-2">
                            <label for="project_no" class="col-sm-4 control-label">Unloading</label>
                            <div class="col-sm-4">
                                <label>START</label>
                                <input type="time" class="form-control form-control-sm" id="start_time" name="start_time" placeholder="hh:mm">
                            </div>
                            <div class="col-sm-4">
                                <label>END</label>
                                <input type="time" class="form-control form-control-sm" id="end_time" name="end_time" placeholder="hh:mm">
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

<!-- Submit Form BY model -->
<div class="modal fade" id="modal-filters" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Advanced Search</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="#" method="post" id="filter-form">

                <div class="cus-form">
                    
                        <div class="form-row mb-2">
                            <label for="code" class="col-sm-4 control-label">Customer</label>
                            <div class="col-sm-6">
                                <select id="search_customers_id" class="custom-select  custom-select-sm"> 
                                    <option value="">Select Customer</option>
                                    <?php
                                    $users_id = Yii::app()->user->getId();
                                    $list = UserHasCustomers::model()->findAllByAttributes(array("users_id" => $users_id, "online" => 1));
                                    foreach ($list as $value) {
                                        echo "<option value='" . $value->customers->id . "'>" . $value->customers->code . " - " . $value->customers->name . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>  
                        
                        <div class="form-row mb-2">
                            <label for="code" class="col-sm-4 control-label">Warehouse</label>
                            <div class="col-sm-6">
                                <select id="search_warehouse_id"  class="custom-select  custom-select-sm">
                                    <option value="">Select Warehouse</option>
                                    <?php
                                    $users_id = Yii::app()->user->getId();
                                    $list = UserHasWarehouse::model()->findAllByAttributes(array("users_id" => $users_id, "online" => 1));
                                    foreach ($list as $value) {
                                        echo "<option value='" . $value->warehouse->id . "'>" . $value->warehouse->code . " - " . $value->warehouse->name . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-row mb-2">
                            <label for="project_id" class="col-sm-4 control-label">Project</label>
                            <div class="col-sm-6">
                                <select id="search_project_id" name="search_project_id" class="custom-select  custom-select-sm"></select>
                            </div>
                        </div>
                        <div class="form-row mb-2">
                            <label for="eff_date" class="col-sm-4 control-label">Date</label>
                            <div class="col-sm-4">
                                <label>From</label>
                                <input type="text" data-date-container="#modal-filters" class="form-control  form-control-sm datepicker" id="search_datefrom" name="eff_date" placeholder="Date">
                            </div>
                            <div class="col-sm-4">
                                <label>To</label>
                                <input type="text" data-date-container="#modal-filters" class="form-control   form-control-sm datepicker" id="search_dateto" name="eff_date" placeholder="Date">
                            </div>
                        </div>
                        <div class="form-row mb-2">
                            <label for="vehicle_no" class="col-sm-4 control-label">Vehicle No#</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control  form-control-sm" id="search_vehicle_no" name="vehicle_no" placeholder="Vehicle No#">
                            </div>
                        </div>
                        <div class="form-row mb-2">
                            <label for="packinglist_no" class="col-sm-4 control-label">Packing List No#</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control  form-control-sm" id="search_packinglist_no" name="packinglist_no" placeholder="Packing List No#">
                            </div>
                        </div>
                        <div class="form-row mb-2">
                            <label for="ref_no" class="col-sm-4 control-label">Ref No#</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control  form-control-sm" id="search_ref_no" name="ref_no" placeholder="Referance No#">
                            </div>
                        </div> 

                </div>
                    
                </form>

            </div>
            <div class="modal-footer">
                <button id="btn-clear-search" type="button" class="btn btn-danger btn-sm">Clear Filters</button>
                <button id="btn-submit-search" type="button" class="btn btn-success btn-sm">Search <span class="oi oi-magnifying-glass"></span></button>
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
                    <button id="Grn-add" data-toggle="modal" data-target="#Grn-addmodel" class="btn btn-secondary btn-block btn-sm" >
                        Add <span class="oi oi-plus"></span>
                    </button>
                </div>
                <input type="text" id="Grn-search" class="form-control form-control-sm" placeholder="Search by Name/Code etc.">
                <div class="input-group-append">
                    <button id="filters-add" data-toggle="modal" data-target="#modal-filters" class="btn btn-default btn-sm" >Filters <span class="oi oi-list"></span> <span class="glyphicon glyphicon-search"></span></button>
                </div>
                <div class="input-group-append">
                    <button id="Grn-searchbtn" class="btn btn-secondary btn-sm" >Search <span class="oi oi-magnifying-glass"></span> <span class="glyphicon glyphicon-search"></span></button>
                </div>
            </div>
        </div> 
        <div class="col-auto">
            <div class="input-group">
                <select id="Grn-pages" name="pages" class="custom-select custom-select-sm">
                    <option>10 Pages</option>
                    <option selected="selected">50 Pages</option>
                    <option>100 Pages</option>
                </select>
            </div>
        </div>

    </div>
</div>




<div style="margin-bottom: 40px;">
    <div class="table-box">

        <div class="row no-gutters">
            <div class='col-1 headerdiv'>CODE</div>
            <div class='col-2 headerdiv'>CUSTOMER</div>
            <div class='col-1 headerdiv'>WAREHOUSE</div>
            <div class='col-2 headerdiv'>SUPPLIER</div>
            <div class='col-1 headerdiv'>DATE</div>
            <div class='col-1 headerdiv'>VEHICLE</div>
            <div class='col-1 headerdiv'>REF</div>
            <div class='col-1 headerdiv'>PROJECT</div>
            <div class="col-1 headerdiv">STATUS</div>
            <div class="col-1 headerdiv text-right"></div>
        </div>


        <div class="row no-gutters">
            <?php
            $this->widget('zii.widgets.CListView', array(
                'dataProvider' => $dataProvider,
                'itemView' => '_view',
                'enablePagination' => true,
                'summaryText' => '{page}/{pages} pages',
                'id' => 'Grn-list',
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

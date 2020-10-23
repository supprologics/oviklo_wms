
<script>

    var cus_id = 0;
    $(function(){
       $('.select_search').select2();
       $(".datepicker").datepicker({
            format: "yyyy-mm-dd",
                    autoclose: true,
                    todayHighlight: true
            });
       
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
                $("<th>").html("Code").appendTo($row);
                $("<th>").html("Description").appendTo($row);
                $("<th class='text-right'>").html("Volume").appendTo($row);
                $("<th class='text-right'>").html("Weight").appendTo($row);
                $("<th class='text-right'>").html("MAX Stacking").appendTo($row);
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
                })
                $("<td>").html(item.value).appendTo($row);
                $("<td>").html(item.description).appendTo($row);
                $("<td class='text-right'>").html(item.volume).appendTo($row);
                $("<td class='text-right'>").html(item.weight).appendTo($row);
                $("<td class='text-right'>").html(item.max_stacking).appendTo($row);
                return $row.appendTo(table);
            }
        });

        function _doFocusStuff(event, ui) {
            if (ui.item) {
                var item = ui.item;
                $("#sku_id").val(item.id);
            }
            jQuery(this).val(ui.item.suggestion);
            return false;
        }

        
        
    });

    $(document).on("change", "#customers_id", function (e) {
        cus_id = $(this).val();
        loadProjects($(this).val());
        
        
        // create the autocomplete
        var autocomplete = $("#sku").tablecomplete({
            minLength: 2,
            source: "<?php echo Yii::app()->createUrl('sku/loadlist'); ?>/"+cus_id,
            response: function (event, ui) {
                // ui.content is the array that's about to be sent to the response callback.
                if (ui.content.length === 0) {
                    showError("No Available Stock or Invalid SKU Code");
                }
            },
            select: function (event, ui) {
                $("#sku").val(ui.item.value);
                $("#sku_id").val(ui.item.id);
            }
        });       
        
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

<div class="row">
    <div class="col" id="dd">
        <h4 style="margin-bottom: 15px; font-size: 18px;">
            <span class="oi oi-paperclip"></span> 
            <?php echo $title; ?>
        </h4>

        <form target="_blank" class="form-horizontal" action="<?php echo Yii::app()->createUrl('reports/loadreport/') ?>" method="post" >
            <input type="hidden" name="report" value="<?php echo $report; ?>" />

            <div class="form-row mb-2">
                <label for="name" class="col-sm-2 control-label">Customer</label>
                <div class="col-sm-6">
                    <select id="customers_id" name="customers_id" class="custom-select custom-select-sm">
                         <option value="">Select All</option>
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
                <label for="name" class="col-sm-2 control-label">Warehouse</label>
                <div class="col-sm-6">
                    <select id="warehouse_id" name="warehouse_id" class="custom-select custom-select-sm">
                        <option value="">Select All</option>
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
                <label for="project_id" class="col-sm-2 control-label">Project / Brand</label>
                <div class="col-sm-4">
                    <select id="project_id" name="project_id" class="custom-select select_search custom-select-sm">
                    </select>
                </div>
            </div>

            <div class="form-row mb-2">
                <label for="name" class="col-sm-2 control-label">STOCK Status</label>
                <div class="col-sm-4">
                    <select id="goods_sts_id" name="goods_sts_id" class="custom-select custom-select-sm">
                        <option value="">Select ALL</option>
                        <?php
                        $list = GoodsSts::model()->findALl();
                        foreach ($list as $value) {
                            echo "<option value='" . $value->id . "'>" . $value->name . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="form-row mb-2">
                <label for="sku" class="col-sm-2 control-label">SKU CODE</label>
                <div class="col-sm-4">
                    <input type="hidden" class="form-control form-control-sm" id="sku_id" name="sku_id" >
                    <input type="text" class="form-control form-control-sm" id="sku" name="sku" placeholder="SKU Code">
                </div>
            </div>
            
            <div class="form-row mb-2">
                <label for="effdate" class="col-sm-2 control-label">Effected Date</label>
                <div class="col-sm-2">
                    <input type="text" data-date-container="#dd" class="form-control form-control-sm datepicker" id="effdate" value="<?php echo date("Y-m-d"); ?>" name="effdate" placeholder="Date">
                </div>
            </div>           
            

            <div class="form-row">
                <label for="plies" class="col-sm-2 control-label"></label>
                <div class="col-sm-8">
                    <button class="btn btn-primary">View Report</button>
                </div>
            </div>


        </form>
    </div>
</div>
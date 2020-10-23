<script>

    $ (function () {
        loadMainInputs ();
        $.widget ("custom.tablecomplete", $.ui.autocomplete, {
            _create: function () {
                this._super ();
                this.widget ().menu ("option", "items", "> tr:not(.ui-autocomplete-header)");
            },
            _renderMenu: function (ul, items) {
                var self = this;
                //table definitions
                var $t = $ ("<table class='table table-sm table-sp'>", {
                    border: 0
                }).appendTo (ul);
                $t.append ($ ("<thead>"));
                $t.find ("thead").append ($ ("<tr>"));
                var $row = $t.find ("tr");
                $ ("<th>").html ("Code").appendTo ($row);
                $ ("<th>").html ("Description").appendTo ($row);
                $ ("<th class='text-right'>").html ("Volume").appendTo ($row);
                $ ("<th class='text-right'>").html ("Weight").appendTo ($row);
                $ ("<th class='text-right'>").html ("MAX Stacking").appendTo ($row);
                $ ("<tbody>").appendTo ($t);
                $.each (items, function (index, item) {
                    self._renderItemData (ul, $t.find ("tbody"), item);
                });
            },
            _renderItemData: function (ul, table, item) {
                return this._renderItem (table, item).data ("ui-autocomplete-item", item);
            },
            _renderItem: function (table, item) {
                var $row = $ ("<tr>", {
                    class: "ui-menu-item",
                    role: "presentation"
                })
                $ ("<td>").html (item.value).appendTo ($row);
                $ ("<td>").html (item.description).appendTo ($row);
                $ ("<td class='text-right'>").html (item.volume).appendTo ($row);
                $ ("<td class='text-right'>").html (item.weight).appendTo ($row);
                $ ("<td class='text-right'>").html (item.max_stacking).appendTo ($row);
                return $row.appendTo (table);
            }
        });
        function _doFocusStuff (event, ui) {
            if (ui.item) {
                var item = ui.item;
                $ ("#sku_id").val (item.id);
            }
            jQuery (this).val (ui.item.suggestion);
            return false;
        }

        // create the autocomplete
        var autocomplete = $ ("#sku").tablecomplete ({
            minLength: 2,
            source: "<?php echo Yii::app()->createUrl('sku/loadlist/' . $model->customers_id); ?>",
            response: function (event, ui) {
                // ui.content is the array that's about to be sent to the response callback.
                if (ui.content.length === 0) {
                    showError ("No Available Stock or Invalid SKU Code");
                }
            },
            focus: _doFocusStuff,
            select: function (event, ui) {
                $ ("#sku").val (ui.item.value);
                $ ("#batch_no").focus ();
            }
        });
        $ (document).on ("change", "#selectall", function (e) {
            e.preventDefault ();
            $ (".chk").prop ("checked", this.checked);
        });
        $ ("#PoItems-form").ajaxForm ({
            beforeSend: function () {

                return $ ("#PoItems-form").validate ({
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
                }).form ();
            },
            success: function (data) {
                var result = JSON.parse (data);
                if (result.sts == 1) {
                    $ ("#qty").val ("");
                    $ ("#pkg_no").val ("");
                    $ ("#pkg_no").focus ();
                }
                showResponse (data);
            },
            error: showResponse,
            complete: function () {
                search ();
            }
        });
        $ (document).on ("click", "#Grn-print", function () {
            var id = $ (this).attr ("data-id");
            window.open ("<?php echo Yii::app()->createUrl('grn/serialsprint/') ?>/" + id, "mywindow", "location=1,status=1,scrollbars=1, width=800,height=600").focus ();
        });
        $ ("#Grn-csv").ajaxForm ({
            beforeSend: function () {

                return $ ("#Grn-csv").validate ({
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
                }).form ();
            },
            success: function (data) {
                var jsondata = JSON.parse(data);
                $ ("#error_txt").html (jsondata.msg);
                $ ("#err_table").html ("");
                showResponse(data);
            },
            error: showResponse,
            complete: function () {
                search ();
            }
        });
    });
    $ (document).on ("click", "#btn-submit-csv", function () {
        $ ("#Grn-csv").submit ();
    });
    $ (document).on ("click", "#save", function (e) {
        e.preventDefault ();
        save ();
    });
    function save () {

        var inner_data = $ ("form#inner_table").serializeArray ();
        //ADD Other Details        
        $.ajax ({
            url: "<?php echo Yii::app()->createUrl("grnItems/updateAll/" . $model->id) ?>",
            data: inner_data,
            type: "POST",
            success: showResponse,
            error: showResponse
        }).done (function (data) {
            search ();
        });
    }


    $ (document).on ("click", "#Grn-delete", function (e) {
        e.preventDefault ();
        var id = $ (this).attr ("data-id");
        var confirmdata = confirm ("Are you sure, you want to Revoke this record ?");
        if (confirmdata == true) {
            $.ajax ({
                url: "<?php echo Yii::app()->createUrl('Grn/delete') ?>/" + id,
                type: "POST",
                success: showResponse,
                error: showResponse
            }).done (function (data) {
                window.location.href = "<?php echo Yii::app()->createUrl('Grn') ?>";
            });
        }
    });
    
    $(document).on("change","#grn_items_id",function(){
        search($("#grn_items_id").val ());
    });
    
    $(document).on("change","#grn_items_id_csv",function(){
        search($("#grn_items_id_csv").val ());
    });
    
    
    function search (grn_items_id) {
        $.fn.yiiListView.update ('GrnItems-list', {
            data: {
                val: $("#GrnItems-search").val (),
                grn_items_id: grn_items_id
            },
            complete: function () {
                loadMainInputs ();
                loadDatePicker ();
            }
        });
    }

    function loadMainInputs () {
        var id = "<?php echo $model->id; ?>";
        $.getJSON ("<?php echo Yii::app()->createUrl('grn/jsondata') ?>/" + id).done (function (data) {
            $.each (data, function (i, item) {
                $ ("#" + i).val (item);
            });
        });
    }


    $ (document).on ("click", "#grnItems-remove", function (e) {
        e.preventDefault ();
        var getConfirmation = confirm ("Are You Sure, You want Remove Selected Lines");
        if (getConfirmation == false) {
            return;
        }

        var val = [];
        $ ('.chk:checkbox:checked').each (function (i) {
            val[i] = $ (this).val ();
        });
        var i = 0;
        do {

            $.ajax ({
                url: "<?php echo Yii::app()->createUrl('serials/delete') ?>/" + val[i],
                success: showResponse,
                type: "POST",
                async: false,
                error: showResponse,
            });
            i++;
        } while (i < val.length)
        search ();
    });
    
    $ (document).on ("change", "#sku_csv_id", function (e) {
        var po_items_id = $ ('option:selected', this).attr ('poitems_id');
        $ ("#po_items_id_csv").val (po_items_id);
    });
    
    $ (document).on ("click", "#Grn-complete", function (e) {
        e.preventDefault ();
        var id = $ (this).attr ("data-id");
        var sts = 0;
        var confirmdata = confirm ("Are you sure, you want to Complete This GRN ?");
        if (confirmdata == true) {
            $.ajax ({
                url: "<?php echo Yii::app()->createUrl('Grn/update') ?>/" + id,
                type: "POST",
                data: {
                    online: 4
                },
                async: false,
                success: function (data) {
                    var result = JSON.parse (data);
                    sts = result.sts;
                    showResponse (data);
                },
                error: showResponse
            });
            if (sts > 0) {
                window.location.href = "<?php echo Yii::app()->createUrl('Grn') ?>";
            }
        }
    });





</script>

<style>
    /***** Special Autocomplete CSS Overiden Part *********/
    .ui-autocomplete{
        width: 80% !important;
    }
</style>


<!-- Submit Form BY model -->
<div class="modal fade" id="Grn-csvmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">CSV Line Input</h4>
            </div>
            <div class="modal-body">

                <div class="cus-form">
                    <form enctype="multipart/form-data" class="form-horizontal" action="<?php echo Yii::app()->createUrl('serials/csvupload') ?>" method="post" id="Grn-csv">
                        <input type="hidden" name="grn_id" id="grn_id_csv" value="<?php echo $model->id ?>" />
                        <p>Download Sample csv file <a href="<?php echo Yii::app()->request->baseUrl; ?>/serials.csv">Here</a></p>
                        <div class="form-group row">
                            <label for="goods_sts_id" class="col-sm-2 control-label">SKU</label>
                            <div class="col-sm-5">
                               <select id="grn_items_id_csv" name="grn_items_id" class="custom-select custom-select-sm">
                                    <option value="">Select the SKU</option>
                                    <?php
                                    $list = $model->grnItems;
                                    foreach ($list as $value) {
                                        echo "<option value='" . $value->id . "'>" . $value->sku->code . " - " . $value->sku->description . "</option>";
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



<div id="form_body">
    <div class="container-fluid">

        <h2>Serial Inventory Update for GRN : <?php echo $model->code; ?></h2>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group row">
                    <label for="container_no" class="col-sm-3 control-label">Customer</label>
                    <div class="col-sm-6">
                        <?php echo $model->customers->code . " - " . $model->customers->name; ?>
                    </div>
                </div>

                <div class="form-group row">                    
                    <label for="supplier" class="col-sm-3 control-label">Supplier</label>
                    <div class="col-sm-5">
                        <?php echo $model->supplier; ?>
                    </div>
                </div>                
                <div class="form-group row">
                    <label for="eff_date" class="col-sm-3 control-label">Date</label>
                    <div class="col-sm-5">
                        <?php echo $model->eff_date; ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="project_id" class="col-sm-3 control-label">Project</label>
                    <div class="col-sm-6">
                        <?php echo $model->project->name; ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 control-label">Created</label>
                    <div class="col-sm-6">
                        <?php echo $model->created; ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group row">
                    <label for="container_no" class="col-sm-4 text-right control-label">Container No</label>
                    <div class="col-sm-6">
                        <?php echo $model->container_no; ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="vehicle_no" class="col-sm-4 text-right control-label">Vehicle No</label>
                    <div class="col-sm-6">
                        <?php echo $model->vehicle_no; ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="packinglist_no" class="col-sm-4 text-right control-label">Packing-List No</label>
                    <div class="col-sm-6">
                        <?php echo $model->packinglist_no; ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="ref_no" class="col-sm-4 text-right control-label">REF No</label>
                    <div class="col-sm-6">
                        <?php echo $model->ref_no; ?>
                    </div>
                </div>

            </div>
        </div>


        <hr />
        <div id="inline_manu">
            <div class="row">
                <div class="col-sm-11">
                    <form action="<?php echo Yii::app()->createUrl('serials/create') ?>" method="post" id="PoItems-form" >
                        <input type="hidden" name="grn_id" id="grn_id_sr" value="<?php echo $model->id; ?>" />
                        <div class="form-row">
                            <div class="col-3">  
                                <label >SKU Code</label>
                                <select id="grn_items_id" name="grn_items_id" class="custom-select custom-select-sm select_search">
                                    <option value="">Select the SKU</option>
                                    <?php
                                    $list = $model->grnItems;
                                    foreach ($list as $value) {
                                        echo "<option value='" . $value->id . "'>" . $value->sku->code . " - " . $value->sku->description . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="col-2">
                                <label>CODE</label>
                                <input type="text" id="code_sr" name="code" class="form-control form-control-sm" placeholder="SERIAL CODE">
                            </div>
                            <div class="col-2">
                                <label>ASSET</label>
                                <input type="text" id="asset_sr" name="asset" class="form-control form-control-sm" placeholder="ASSET CODE">
                            </div>
                            <div class="col">
                                <label>&nbsp;</label>
                                <button id="btn-submit-add" class="btn btn-default btn-sm">Add <span class="oi oi-plus"></span></button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-1 text-right">
                    <label>&nbsp;</label>
                    <button data-toggle="modal" data-target="#Grn-csvmodal" class="btn btn-secondary btn-block btn-sm">CSV <span class="oi oi-plus"></span></button>
                </div>
            </div>
        </div>

        <div id="quick_nav">
            <button id="grnItems-remove" class="btn btn-block btn-sm btn-danger"><span class="oi oi-x"></span></button>
            <button id="Grn-print" data-id="<?php echo $model->id; ?>" class="btn btn-block btn-sm btn-default"><span class="oi oi-print"></span></button>
        </div>

        <div style="overflow: auto;" >
            <?php
            $this->widget('zii.widgets.CListView', array(
                'dataProvider' => $dataProvider,
                'itemView' => '_grnItems_serials',
                'enablePagination' => true,
                'summaryText' => '{page}/{pages} pages',
                'id' => 'GrnItems-list',
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



        <div id="btn_bar" class="mt-2 text-right">
            <div class="row">
                <div class="col">
                   <button id="Grn-complete" data-id="<?php echo $model->id; ?>" class="btn  btn-sm btn-success">Complete GRN</button>
                </div>
            </div>
        </div>

    </div>
</div>

<script>

    $ (function () {

       $ ("#MrItems-form").ajaxForm ({
          beforeSend: function () {

             return $ ("#MrItems-form").validate ({
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
          success: showResponse,
          error: showResponse,
          complete: function () {
             loadFabricTable ();
             search ();
          }
       });


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
          appendTo: "#MrItems-addmodel",
          select: function (event, ui) {
             $ ("#sku").val (ui.item.value);
             loadFabricTable ();
          }
       });

       $ ('#MrItems-addmodel').on ('show.bs.modal', function (event) {
          $ ("#batch_table").html ("");
       });


       $ ("#pickSerial-form").ajaxForm ({
          beforeSend: function () {

             return $ ("#pickSerial-form").validate ({
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
             var jsondata = JSON.parse (data);
             $ ("#error_txt").html (jsondata.msg);
             $ ("#err_table").html ("");
             showResponse (data);
          },
          error: showResponse,
          complete: function () {
             search ();
          }
       });


    });

    $ (document).on ("click", "#btn-submit", function () {
       $ ("#MrItems-form").submit ();
    });

    $ (document).on ("click", "#save", function (e) {
       e.preventDefault ();
       save ();
    });

    $ (document).on ("click", ".PickSerials-delete", function (e) {
       e.preventDefault ();
       var id = $ (this).attr ("data-id");
       var confirmdata = confirm ("Are you sure, you want to delete this record ?");
       if (confirmdata == true) {
          $.ajax ({
             url: "<?php echo Yii::app()->createUrl('PickSerials/delete') ?>/" + id,
             type: "POST",
             success: showResponse,
             error: showResponse
          }).done (function (data) {
             search ();
          });
       }
    });


    function save () {

       var inner_data = $ ("form#inner_table").serializeArray ();
       //ADD Other Details        
       inner_data.push ({name: "col_name", value: $ ("#col_name").val ()});
       inner_data.push ({name: "col_nic", value: $ ("#col_nic").val ()});
       inner_data.push ({name: "col_mobile", value: $ ("#col_mobile").val ()});
       inner_data.push ({name: "col_vehicle", value: $ ("#col_vehicle").val ()});
       inner_data.push ({name: "dest1_name", value: $ ("#dest1_name").val ()});
       inner_data.push ({name: "dest2_name", value: $ ("#dest2_name").val ()});
       inner_data.push ({name: "link_name", value: $ ("#link_name").val ()});
       inner_data.push ({name: "remarks", value: $ ("#remarks").val ()});
       inner_data.push ({name: "vehicle_in", value: $ ("#vehicle_in").val ()});
       inner_data.push ({name: "vehicle_out", value: $ ("#vehicle_out").val ()});
       inner_data.push ({name: "end_time", value: $ ("#end_time").val ()});
       inner_data.push ({name: "start_time", value: $ ("#start_time").val ()});
       //ADD Other Details
       $.ajax ({
          url: "<?php echo Yii::app()->createUrl("pick/updateAll/" . $model->id) ?>",
          data: inner_data,
          type: "POST",
          success: showResponse,
          error: showResponse
       }).done (function (data) {
          search ();
       });
    }


    $ (document).on ("click", "#Mr-delete", function (e) {
       e.preventDefault ();
       var id = $ (this).attr ("data-id");
       var confirmdata = confirm ("Are you sure, you want to Revoke this record ?");
       if (confirmdata == true) {
          $.ajax ({
             url: "<?php echo Yii::app()->createUrl('Mr/deletepick') ?>/" + id,
             type: "POST",
             success: showResponse,
             error: showResponse
          }).done (function (data) {
             window.location.href = "<?php echo Yii::app()->createUrl('pick') ?>";
          });
       }
    });
    function search () {
       $.fn.yiiListView.update ('MrItems-list', {
          data: {
             val: $ ("#MrItems-search").val (),
          },
          complete: function () {
             loadMainInputs ();
             loadDatePicker ();
          }
       });
       search_serials ();
    }

    function search_serials () {
       $.fn.yiiListView.update ('GrnItems-list', {
          data: {
             val: $ ("#GrnItems-search").val (),
             pick_items_id: $ ("#pick_items_id").val ()
          },
          complete: function () {
             loadMainInputs ();
             loadDatePicker ();
          }
       });
    }

    $ (document).on ("change", "#pick_items_id", function (e) {
       search_serials ();
    });

    $ (document).on ("change", "#goods_sts_id", function (e) {
       e.preventDefault ();
       loadFabricTable ();
    });


    function loadFabricTable () {

       var id = $ ("#sku_id").val ();
       var goods_sts_id = $ ("#goods_sts_id").val ();
       $ ("#batch_table").html ("");
       $.ajax ({
          url: "<?php echo Yii::app()->createUrl('sku/loadbatchtable/'); ?>/" + id,
          data: {
             goods_sts_id: goods_sts_id,
             mr_id: <?php echo $model->id; ?>
          },
          type: "post",
          success: function (data) {
             $ ("#batch_table").html (data);
             var qty = $ ("#tot_qty").attr ("data-qty");
             $ ("#qty").attr ("max", qty);
          },
          error: showResponse
       });
    }

    function loadMainInputs () {
       var id = "<?php echo $model->id; ?>";
       $.getJSON ("<?php echo Yii::app()->createUrl('mr/jsondata') ?>/" + id).done (function (data) {
          $.each (data, function (i, item) {
             $ ("#" + i).val (item);
          });
       });
    }


    $ (document).on ("click", "#mrItems-remove", function (e) {
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
             url: "<?php echo Yii::app()->createUrl('pick/delete') ?>/" + val[i],
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
    $ (document).on ("click", "#Mr-complete", function (e) {
       e.preventDefault ();
       save ();
       var id = $ (this).attr ("data-id");
       var sts = 0;
       var confirmdata = confirm ("Are you sure, you want to Complete This ?");
       if (confirmdata == true) {
          $.ajax ({
             url: "<?php echo Yii::app()->createUrl('mr/update') ?>/" + id,
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
             window.location.href = "<?php echo Yii::app()->createUrl('pick') ?>";
             window.open ("<?php echo Yii::app()->createUrl('gdn/print/') ?>/" + id, "mywindow", "location=1,status=1,scrollbars=1, width=800,height=600");
          }
       }
    });
    $ (document).on ("click", "#Mr-print", function () {
       var id = $ (this).attr ("data-id");
       window.open ("<?php echo Yii::app()->createUrl('pick/print/') ?>/" + id, "mywindow", "location=1,status=1,scrollbars=1, width=800,height=600").focus ();
    });
    $ (document).on ("keyup", ".qty_input", function (e) {
       e.preventDefault ();
       var tot = 0;
       $ (".qty_input").each (function (i) {
          var qty = parseFloat ($ (this).val ());
          if (isNaN (qty)) {
             qty = 0;
          }
          tot += qty;
       });
       $ ("#qty_all").val (tot);
    });
    $ (document).on ("change", "#selectall", function (e) {
       e.preventDefault ();
       $ (".chk").prop ("checked", this.checked);
    });



    $ (document).on ("click", "#btn-submit-csv", function () {
       $ ("#Grn-csv").submit ();
    });


    $ (document).on ("click", "#reprocess", function (e) {
       e.preventDefault ();
       var id = '<?php echo $model->id; ?>'
       $.ajax ({
          url: "<?php echo Yii::app()->createUrl('pick/process') ?>/" + id,
          async: false,
          success: function (data) {
             showError("SYNC DONE");
             window.location.reload();
             search();
          },
          error: function (data) {
             showError("SYNC ERROR");
             window.location.reload();
             search();
          },
       });

    });


</script>




<!-- Submit Form BY model -->
<div class="modal fade" id="MrItems-addmodel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Add SKUs to MR Posting</h4>
            </div>
            <div class="modal-body">

                <div class="cus-form">
                    <form class="form-horizontal" action="<?php echo Yii::app()->createUrl('MrItems/create') ?>" method="post" id="MrItems-form">
                        <input type="hidden" name="mr_id" id="mr_id" value="<?php echo $model->id; ?>" />
                        <div class="form-row mb-2">
                            <label for="code" class="col-sm-2 control-label">SKU</label>
                            <div class="col-sm-6">
                                <input type="text" id="sku" class="form-control form-control-sm" placeholder="Search By SKU Code">
                                <input type="hidden" id="sku_id" name="sku_id" >
                            </div>
                        </div>
                        <div class="form-row mb-2">
                            <label for="code" class="col-sm-2 control-label">STATUS</label>
                            <div class="col-sm-4">
                                <select name="goods_sts_id" id="goods_sts_id" class="custom-select custom-select-sm">
                                    <?php
                                    $datalist = GoodsSts::model()->findAll();
                                    foreach ($datalist as $value) {
                                        echo "<option value='" . $value->id . "'>" . $value->name . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-row mb-2">
                            <label for="code" class="col-sm-2 control-label">Batch</label>
                            <div class="col-sm-10">

                                <table class="table table-sm table-sp"  >
                                    <thead>
                                        <tr>
                                            <th>BATCH</th>
                                            <th>EXPIRE</th>
                                            <th>AVL</th>
                                            <th>REQ.</th>
                                            <th>REMARKS</th>
                                        </tr>
                                    </thead>
                                    <tbody id="batch_table">

                                    </tbody>

                                </table>

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



<div id="form_body">
    <div class="container-fluid">

        <h2>PICK-LIST for <?php echo $model->code; ?></h2>
        <div class="row">
            <div class="col-sm-5">
                <div class="form-group row">
                    <label for="container_no" class="col-sm-3 control-label">Customer</label>
                    <div class="col-sm-6">
                        <?php echo $model->customers->code . " - " . $model->customers->name; ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="container_no" class="col-sm-3 control-label">Warehouse</label>
                    <div class="col-sm-6">
                        <?php echo $model->warehouse->code . " - " . $model->warehouse->name; ?>
                    </div>
                </div>   
                <div class="form-group row">
                    <label for="container_no" class="col-sm-3 control-label">Project</label>
                    <div class="col-sm-6">
                        <?php echo $model->project->name; ?>
                    </div>
                </div> 
                <div class="form-group row">
                    <label for="eff_date" class="col-sm-3 control-label">Date</label>
                    <div class="col-sm-5">
                        <?php echo $model->eff_date; ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="delivery_date" class="col-sm-3 control-label">Delivery Date</label>
                    <div class="col-sm-5">
                        <?php echo $model->delivery_date; ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 control-label">Created</label>
                    <div class="col-sm-6">
                        <?php echo $model->created; ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group row">
                    <label for="col_name" class="col-sm-4 text-right control-label"></label>
                    <div class="col-sm-6">
                        <label>Collector Details</label>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="col_name" class="col-sm-4 text-right control-label">Name</label>
                    <div class="col-sm-8">
                        <input type="text" name="col_name" id="col_name" value="<?php echo $model->col_name; ?>" class="form-control form-control-sm" />
                    </div>
                </div>
                <div class="form-group row">
                    <label for="col_nic" class="col-sm-4 text-right control-label">NIC</label>
                    <div class="col-sm-6">
                        <input type="text" name="col_nic" id="col_nic" value="<?php echo $model->col_nic; ?>" class="form-control form-control-sm" />
                    </div>
                </div>
                <div class="form-group row">
                    <label for="col_mobile" class="col-sm-4 text-right control-label">Mobile</label>
                    <div class="col-sm-6">
                        <input type="text" name="col_mobile" id="col_mobile" value="<?php echo $model->col_mobile; ?>" class="form-control form-control-sm" />
                    </div>
                </div>
                <div class="form-group row">
                    <label for="col_vehicle" class="col-sm-4 text-right control-label">Vehicle</label>
                    <div class="col-sm-6">
                        <input type="text" name="col_vehicle" id="col_vehicle" value="<?php echo $model->col_vehicle; ?>" class="form-control form-control-sm" />
                    </div>
                </div>
            </div>
            <div class="col-sm-4">                
                <div class="form-group row">
                    <label for="col_name" class="col-sm-4 text-right control-label"></label>
                    <div class="col-sm-6">
                        <label>Site Details</label>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="dest1_name" class="col-sm-4 text-right control-label">Destination #1</label>
                    <div class="col-sm-6">
                        <input type="text" name="dest1_name" id="dest1_name" value="<?php echo $model->dest1_name; ?>" class="form-control form-control-sm" />
                    </div>
                </div>
                <div class="form-group row">
                    <label for="dest2_name" class="col-sm-4 text-right control-label">Destination #2</label>
                    <div class="col-sm-6">
                        <input type="text" name="dest2_name" id="dest2_name" value="<?php echo $model->dest2_name; ?>" class="form-control form-control-sm" />
                    </div>
                </div>
                <div class="form-group row">
                    <label for="link_name" class="col-sm-4 text-right control-label">Link</label>
                    <div class="col-sm-6">
                        <input type="text" name="link_name" id="link_name" value="<?php echo $model->link_name; ?>" class="form-control form-control-sm" />
                    </div>
                </div>
            </div>
        </div>
        <div id="quick_nav">
            <button id="save" class="btn btn-block btn-sm btn-warning"><span class="oi oi-command"></span></button>
            <button id="mrItems-remove" class="btn btn-block btn-sm btn-danger"><span class="oi oi-x"></span></button>
            <button id="Mr-print" data-id="<?php echo $model->id; ?>" class="btn btn-block btn-sm btn-default"><span class="oi oi-print"></span></button>
        </div>



        <?php
        $totMR = Yii::app()->db->createCommand("SELECT SUM(qty) as qty FROM mr_items WHERE mr_id = '" . $model->id . "'")->queryRow();
        $totPick = Yii::app()->db->createCommand("SELECT SUM(qty_req) as qty FROM pick_items WHERE mr_id = '" . $model->id . "'")->queryRow();

        if ($totMR['qty'] != $totPick['qty']) {
            ?>
            <div class="alert alert-danger" role="alert">
                PLEASE CHECK THE PICK-LIST AGAIN. MR TOTAL QTY NOT MATCHING WITH PICK TOTAL QTY  <button id="reprocess" class="btn btn-danger">RE PROCESS <span class="oi oi-reload"></span></button>
            </div>        
        <?php } ?>



        <div style = "overflow: auto;" >
            <?php
            $this->widget('zii.widgets.CListView', array(
                'dataProvider' => $dataProvider,
                'itemView' => '_mrItems',
                'enablePagination' => true,
                'summaryText' => '{page}/{pages} pages',
                'id' => 'MrItems-list',
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


        <!-- SERIAL INPUT -->
        <?php if ($model->customers->is_serial == 1) { ?>



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
                                <form enctype="multipart/form-data" class="form-horizontal" action="<?php echo Yii::app()->createUrl('pickSerials/csvupload') ?>" method="post" id="Grn-csv">
                                    <input type="hidden" name="mr_id" id="mr_id_csv" value="<?php echo $model->id ?>" />
                                    <p>Download Sample csv file <a href="<?php echo Yii::app()->request->baseUrl; ?>/serials.csv">Here</a></p>
                                    <div class="form-group row">
                                        <label for="goods_sts_id" class="col-sm-2 control-label">SKU</label>
                                        <div class="col-sm-5">
                                            <select id="pick_items_id_csv" name="pick_items_id" class="custom-select custom-select-sm">
                                                <option value="">Select the SKU</option>
                                                <?php
                                                $list = $model->pickItems;
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


            <div style="margin-bottom: 25px;">

                <div id="inline_manu">
                    <div class="row">
                        <div class="col-sm-11">
                            <form action="<?php echo Yii::app()->createUrl('pickSerials/create') ?>" method="post" id="pickSerial-form" >
                                <input type="hidden" name="mr_id" id="mr_id" value="<?php echo $model->id; ?>" />
                                <div class="form-row">
                                    <div class="col-3">  
                                        <label >SKU Code</label>
                                        <select id="pick_items_id" name="pick_items_id" class="custom-select custom-select-sm select_search">
                                            <option value="">Select the SKU</option>
                                            <?php
                                            $list = $model->pickItems;
                                            foreach ($list as $value) {
                                                echo "<option value='" . $value->id . "'>" . $value->sku->code . " - " . $value->sku->description . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="col-2">
                                        <label>SERIAL</label>
                                        <input type="text" id="code_sr" name="code" class="form-control form-control-sm" placeholder="SERIAL CODE">
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


                <div style="overflow: auto;" >
                    <?php
                    $this->widget('zii.widgets.CListView', array(
                        'dataProvider' => $dataProviderForSerials,
                        'itemView' => '_pickItems_serials',
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


            </div>
        <?php } ?>




        <div>
            Additional Remarks / Notes
            <textarea class="form-control" id="remarks" rows="2" placeholder="Additional Remarks & Notes"><?php echo $model->remarks; ?></textarea>
        </div>

        <div style="margin: 10px 0; border: 2px solid #179f38; padding: 15px; background: #60d77d;">
            <div class="form-row mb-2">
                <label for="project_no" class="col-sm-4 control-label">IN/OUT</label>
                <div class="col-sm-2">
                    <label>Vehicle  IN</label>
                    <input type="time" class="form-control form-control-sm" value="<?php echo $model->vehicle_in; ?>" id="vehicle_in" name="vehicle_in" placeholder="hh:mm">
                </div>
                <div class="col-sm-2">
                    <label>Loading Start</label>
                    <input type="time" class="form-control form-control-sm" value="<?php echo $model->start_time; ?>" id="start_time" name="start_time" placeholder="hh:mm">
                </div>
                <div class="col-sm-2">
                    <label>Loading Finish</label>
                    <input type="time" class="form-control form-control-sm" value="<?php echo $model->end_time; ?>" id="end_time" name="end_time" placeholder="hh:mm">
                </div>
                <div class="col-sm-2">
                    <label>Vehicle OUT</label>
                    <input type="time" class="form-control form-control-sm" value="<?php echo $model->vehicle_out; ?>" id="vehicle_out" name="vehicle_out" placeholder="hh:mm">
                </div>
            </div>
        </div>

        <div id="btn_bar" class="mt-2 text-right">
            <div class="row">
                <div class="col">
                    <button id="Mr-delete" data-id="<?php echo $model->id; ?>" class="btn btn-sm btn-danger"> Revoke PICK <span class="oi oi-x"></span></button>

                    <?php if ($totMR['qty'] == $totPick['qty']) { ?>
                        <button id="Mr-complete" data-id="<?php echo $model->id; ?>" class="btn  btn-sm btn-success">Complete PICK-LIST</button>
                    <?php } ?>

                </div>
            </div>
        </div>

    </div>
</div>
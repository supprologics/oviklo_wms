
<script>

    $ (function () {

       $ ("#TnItems-form").ajaxForm ({
          beforeSend: function () {

             return $ ("#TnItems-form").validate ({
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
          appendTo: "#TnItems-addmodel",
          select: function (event, ui) {
             $ ("#sku").val (ui.item.value);
             loadFabricTable ();
          }
       });
       $ ('#TnItems-addmodel').on ('show.bs.modal', function (event) {
          $ ("#batch_table").html ("");
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
             var num = 1;
             $ (jsondata.er).each (function (data, i) {
                $ ("#err_table").append ("<tr><td>" + num + "</td><td>" + i.code + "</td><td>" + i.error + "</td></tr>");
                num += 1;
             });

             showResponse (data);
          },
          error: showResponse,
          complete: function () {
             search ();
          }
       });


    });
    $ (document).on ("click", "#btn-submit", function () {
       $ ("#TnItems-form").submit ();
    });
    $ (document).on ("click", "#save", function (e) {
       e.preventDefault ();
       save ();
    });
    function save () {

       var inner_data = $ ("form#inner_table").serializeArray ();
       //ADD Other Details
       inner_data.push ({name: "eff_date", value: $ ("#eff_date").val ()});
       inner_data.push ({name: "delivery_date", value: $ ("#delivery_date").val ()});
       inner_data.push ({name: "col_name", value: $ ("#col_name").val ()});
       inner_data.push ({name: "col_nic", value: $ ("#col_nic").val ()});
       inner_data.push ({name: "col_mobile", value: $ ("#col_mobile").val ()});
       inner_data.push ({name: "col_vehicle", value: $ ("#col_vehicle").val ()});
       inner_data.push ({name: "dest1_name", value: $ ("#dest1_name").val ()});
       inner_data.push ({name: "dest2_name", value: $ ("#dest2_name").val ()});
       inner_data.push ({name: "link_name", value: $ ("#link_name").val ()});
       inner_data.push ({name: "remarks", value: $ ("#remarks").val ()});
       $.ajax ({
          url: "<?php echo Yii::app()->createUrl("tnItems/updateAll/" . $model->id) ?>",
          data: inner_data,
          type: "POST",
          success: showResponse,
          error: showResponse
       }).done (function (data) {
          search ();
       });
    }


    $ (document).on ("click", "#Tn-delete", function (e) {
       e.preventDefault ();
       var id = $ (this).attr ("data-id");
       var confirmdata = confirm ("Are you sure, you want to Revoke this record ?");
       if (confirmdata == true) {
          $.ajax ({
             url: "<?php echo Yii::app()->createUrl('Tn/delete') ?>/" + id,
             type: "POST",
             success: showResponse,
             error: showResponse
          }).done (function (data) {
             window.location.href = "<?php echo Yii::app()->createUrl('Tn') ?>";
          });
       }
    });
    function search () {
       $.fn.yiiListView.update ('TnItems-list', {
          data: {
             val: $ ("#TnItems-search").val (),
          },
          complete: function () {
             loadMainInputs ();
             loadDatePicker ();
          }
       });
    }

    $ (document).on ("change", "#goods_sts_id", function (e) {
       e.preventDefault ();
       loadFabricTable ();
    });
    function loadFabricTable () {

       var id = $ ("#sku_id").val ();
       var goods_sts_id = $ ("#goods_sts_id").val ();
       $ ("#batch_table").html ("");
       $.ajax ({
          url: "<?php echo Yii::app()->createUrl('sku/loadbatchtableForTn/'); ?>/" + id,
          data: {
             goods_sts_id: goods_sts_id,
             tn_id: <?php echo $model->id; ?>
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
       $.getJSON ("<?php echo Yii::app()->createUrl('tn/jsondata') ?>/" + id).done (function (data) {
          $.each (data, function (i, item) {
             $ ("#" + i).val (item);
          });
       });
    }


    $ (document).on ("click", "#tnItems-remove", function (e) {
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
             url: "<?php echo Yii::app()->createUrl('tnItems/delete') ?>/" + val[i],
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
    $ (document).on ("click", "#Tn-complete", function (e) {
       e.preventDefault ();
       var id = $ (this).attr ("data-id");
       var sts = 0;
       var confirmdata = confirm ("Are you sure, you want to Complete This GRN ?");
       if (confirmdata == true) {
          $.ajax ({
             url: "<?php echo Yii::app()->createUrl('Tn/update') ?>/" + id,
             type: "POST",
             data: {
                online: 2
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
             window.location.href = "<?php echo Yii::app()->createUrl('Tn') ?>";
             window.open ("<?php echo Yii::app()->createUrl('Tn/print/') ?>/" + id, "mywindow", "location=1,status=1,scrollbars=1, width=800,height=600");
          }
       }
    });
    $ (document).on ("click", "#Tn-print", function () {
       var id = $ (this).attr ("data-id");
       window.open ("<?php echo Yii::app()->createUrl('tn/print/') ?>/" + id, "mywindow", "location=1,status=1,scrollbars=1, width=800,height=600").focus ();
    });
    
    $ (document).on ("click", "#Tn-print-html", function () {
       var id = $ (this).attr ("data-id");
       window.open ("<?php echo Yii::app()->createUrl('tn/printhtml/') ?>/" + id, "mywindow", "location=1,status=1,scrollbars=1, width=800,height=600").focus ();
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
    $ (document).on ("keyup", "#qty_bulk", function (e) {
       e.preventDefault ();
       $ (".qty_input").val ("");
       $ ("#qty_all").val ("");
    });
    $ (document).on ("change", "#selectall", function (e) {
       e.preventDefault ();
       $ (".chk").prop ("checked", this.checked);
    });

    $ (document).on ("click", "#btn-submit-csv", function () {
       $ ("#Grn-csv").submit ();
    });


</script>



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
                    <form enctype="multipart/form-data" class="form-horizontal" action="<?php echo Yii::app()->createUrl('tnItems/csvupload') ?>" method="post" id="Grn-csv">
                        <input type="hidden" name="tn_id" id="tn_id_csv" value="<?php echo $model->id ?>" />
                        <p>Download Sample csv file <a href="<?php echo Yii::app()->request->baseUrl; ?>/mr.csv">Here</a></p>

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
<div class="modal fade" id="TnItems-addmodel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Add SKUs to TN Posting</h4>
            </div>
            <div class="modal-body">

                <div class="cus-form">
                    <form class="form-horizontal" action="<?php echo Yii::app()->createUrl('TnItems/create') ?>" method="post" id="TnItems-form">
                        <input type="hidden" name="tn_id" id="tn_id" value="<?php echo $model->id; ?>" />
                        <input type="hidden" name="goods_sts_id" id="goods_sts_id" value="<?php echo $model->goods_sts_from; ?>" />
                        <div class="form-row mb-2">
                            <label for="code" class="col-sm-2 control-label">SKU</label>
                            <div class="col-sm-6">
                                <input type="text" id="sku" class="form-control form-control-sm" placeholder="Search By SKU Code">
                                <input type="hidden" id="sku_id" name="sku_id" >
                            </div>
                        </div>                        
                        <div class="form-row mb-2">
                            <label for="code" class="col-sm-2 control-label">Batch</label>
                            <div class="col-sm-10">

                                <table class="table table-sm table-sp"  >
                                    <thead>
                                        <tr>
                                            <th>BATCH</th>
                                            <th>GRN</th>
                                            <th>EXPIRE</th>
                                            <th>AVL</th>
                                            <th>REQ.</th>

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

<?php $model = Tn::model()->findByPk($model->id); ?>
<div id="form_body">
    <div class="container-fluid">

        <h2>Transfer Request - <?php echo $model->code; ?></h2>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-row">
                    <label for="container_no" class="col-sm-3 control-label">Customer</label>
                    <div class="col-sm-6">
                        <?php echo $model->customers->code . " - " . $model->customers->name; ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <h4 style="font-size: 14px;">Transfer From</h4>
                <div class="form-row">
                    <label for="container_no" class="col-sm-3 control-label">Warehouse</label>
                    <div class="col-sm-6">
                        <?php echo $model->warehouseFrom->name; ?>
                    </div>
                </div>
                <div class="form-row">
                    <label for="container_no" class="col-sm-3 control-label">Project</label>
                    <div class="col-sm-6">
                        <?php echo $model->projectFrom->name; ?>
                    </div>
                </div>
                <div class="form-row">
                    <label for="container_no" class="col-sm-3 control-label">Zone</label>
                    <div class="col-sm-6">
                        <?php echo $model->locationsFrom->name; ?>
                    </div>
                </div>
                <div class="form-row">
                    <label for="container_no" class="col-sm-3 control-label">Location</label>
                    <div class="col-sm-6">
                        <?php echo $model->sub_location_from; ?>
                    </div>
                </div>
                <div class="form-row">
                    <label for="container_no" class="col-sm-3 control-label">Status</label>
                    <div class="col-sm-6">
                        <?php echo $model->goodsStsFrom->name; ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <h4 style="font-size: 14px;">Transfer To</h4>
                <div class="form-row">
                    <label for="container_no" class="col-sm-3 control-label">Warehouse</label>
                    <div class="col-sm-6">
                        <?php echo $model->warehouseTo->name; ?>
                    </div>
                </div>
                <div class="form-row">
                    <label for="container_no" class="col-sm-3 control-label">Project</label>
                    <div class="col-sm-6">
                        <?php echo $model->projectTo->name; ?>
                    </div>
                </div>
                <div class="form-row">
                    <label for="container_no" class="col-sm-3 control-label">Zone</label>
                    <div class="col-sm-6">
                        <?php echo $model->locationsTo->name; ?>
                    </div>
                </div>
                <div class="form-row">
                    <label for="container_no" class="col-sm-3 control-label">Location</label>
                    <div class="col-sm-6">
                        <?php echo $model->sub_location; ?>
                    </div>
                </div>
                <div class="form-row">
                    <label for="container_no" class="col-sm-3 control-label">Status</label>
                    <div class="col-sm-6">
                        <?php echo $model->goodsStsTo->name; ?>
                    </div>
                </div>
            </div>
        </div>
        <hr />
        <div id="inline_manu">
            <button data-toggle="modal" data-target="#TnItems-addmodel" class="btn btn-secondary btn-sm">Add SKUs <span class="oi oi-plus"></span></button>
            <button data-toggle="modal" data-target="#Grn-csvmodal" class="btn btn-secondary btn-sm">CSV <span class="oi oi-plus"></span></button>
        </div>

        <div id="quick_nav">
            <button id="tnItems-remove" class="btn btn-block btn-sm btn-danger"><span class="oi oi-x"></span></button>
            <button id="Tn-print" data-id="<?php echo $model->id; ?>" class="btn btn-block btn-sm btn-default"><span class="oi oi-print"></span></button>
            <button id="Tn-print-html" data-id="<?php echo $model->id; ?>" class="btn btn-block btn-sm btn-default"><span class="oi oi-code"></span></button>
        </div>

        <div style="overflow: auto;" >
            <?php
            $this->widget('zii.widgets.CListView', array(
                'dataProvider' => $dataProvider,
                'itemView' => '_tnFrom',
                'enablePagination' => true,
                'summaryText' => '{page}/{pages} pages',
                'id' => 'TnItems-list',
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

        <div>
            Additional Remarks / Notes
            <textarea class="form-control" id="remarks" rows="2" placeholder="Additional Remarks & Notes"></textarea>
        </div>

        <div id="btn_bar" class="mt-2 text-right">
            <div class="row">
                <div class="col">
                    <button id="Tn-delete" data-id="<?php echo $model->id; ?>" class="btn btn-sm btn-danger"> Revoke TN <span class="oi oi-x"></span></button>
                    <button id="Tn-complete" data-id="<?php echo $model->id; ?>" class="btn  btn-sm btn-success">Complete TN</button>
                </div>
            </div>
        </div>


    </div>
</div>


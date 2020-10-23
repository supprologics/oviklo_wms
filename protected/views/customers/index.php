<?php
/* @var $this CustomersController */
/* @var $dataProvider CActiveDataProvider */
?>



<!--- Script -->
<script>

    $(document).ready(function () {

        $("#Customers-form").ajaxForm({
            beforeSend: function () {

                return $("#Customers-form").validate({
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

        $('#Customers-addmodel').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            if (button.attr("id") === "Customers-add") {
                $("#Customers-form").resetForm();
                $("#Customers-form").attr("action", "<?php echo Yii::app()->createUrl('Customers/create') ?>/");
                $(".hideonupdate").show();
            } else {
                $(".hideonupdate").hide();
            }
        });

    });


    $(document).on("click", ".clickable", function () {
        var id = $(this).parents("div.row").attr("data-id");
        window.location.href = "<?php echo Yii::app()->createUrl('Customers') ?>/" + id;
    });

    $(document).on("click", "#btn-submit", function () {
        $("#Customers-form").submit();
    });


    $(document).on("click", ".Customers-update", function (e) {
        e.preventDefault();
        var id = $(this).attr("data-id");
        $("#Customers-form").resetForm();
        //Handle JSON DATA to Update FORM
        $.getJSON("<?php echo Yii::app()->createUrl('Customers/jsondata') ?>/" + id).done(function (data) {
            $.each(data, function (i, item) {

                if ($("#Customers-form #" + i).is("[type='checkbox']")) {
                    $("#Customers-form #" + i).prop('checked', item);
                } else if ($("#Customers-form #" + i).is("[type='radio']")) {
                    $("#Customers-form #" + i).prop('checked', item);
                } else {
                    $("#Customers-form #" + i).val(item);
                }
            });
            $("#Customers-form").attr("action", "<?php echo Yii::app()->createUrl('Customers/update') ?>/" + id);
        });

        $("#Customers-addmodel").modal('show');
    });

    $(document).on("click", ".Customers-delete", function (e) {
        e.preventDefault();
        var id = $(this).attr("data-id");
        var confirmdata = confirm("Are you sure, you want to delete this record ?");
        if (confirmdata == true) {
            $.ajax({
                url: "<?php echo Yii::app()->createUrl('Customers/delete') ?>/" + id,
                type: "POST",
                success: showResponse,
                error: showResponse
            }).done(function (data) {
                search();
            });
        }
    });

    $(document).on("click", "#Customers-searchbtn", function () {
        search();
    });

    $(document).on("keyup", "#Customers-search", function () {
        search();
    });

    $(document).on("change", "#Customers-pages", function () {
        search();
    });

    function search() {
        $.fn.yiiListView.update('Customers-list', {
            data: {
                val: $("#Customers-search").val(),
                pages: $("#Customers-pages").val()
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
            <h1>Customers Registry</h1>
        </div>
    </div>
</div>

<!-- Submit Form BY model -->
<div class="modal fade" id="Customers-addmodel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Customers - FORM</h4>
            </div>
            <div class="modal-body">

                <div class="cus-form">
                    <form class="form-horizontal" action="<?php echo Yii::app()->createUrl('Customers/create') ?>" method="post" id="Customers-form">

                        <div class="form-group row">
                            <label for="code" class="col-sm-4 control-label">BIZ Category</label>
                            <div class="col-sm-6">
                                <select id="biz_cat_id" name="biz_cat_id" class="custom-select custom-select-sm">
                                    <?php
                                    
                                    $list = BizCat::model()->findAll();
                                    foreach ($list as $value) {
                                        echo "<option value='". $value->id ."'>". $value->name ."</option>";
                                    }
                                    
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="code" class="col-sm-4 control-label">Code</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control form-control-sm" id="code" name="code" placeholder="Name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-sm-4 control-label">Name</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm" id="name" name="name" placeholder="Name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="address" class="col-sm-4 control-label">Address</label>
                            <div class="col-sm-8">
                                <textarea name="address" id="address" rows="2" class="form-control form-control-sm" placeholder="Address"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="tel_1" class="col-sm-4 control-label">Telephone-01</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm" id="tel_1" name="tel_1" placeholder="Telephone 01">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="tel_2" class="col-sm-4 control-label">Telephone-01</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm" id="tel_2" name="tel_2" placeholder="Telephone 02">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="code" class="col-sm-4 control-label">FEFO Only ?</label>
                            <div class="col-sm-6">
                                <select id="by_batch" name="by_batch" class="custom-select custom-select-sm">
                                    <option value="1">YES</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="email" class="col-sm-4 control-label">Email</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm" id="email" name="email" placeholder="Email">
                            </div>
                        </div>
                         <div class="form-group row">
                            <label for="is_serial" class="col-sm-4 control-label">Serial Inventory ?</label>
                            <div class="col-sm-6">
                                <select id="is_serial" name="is_serial" class="custom-select custom-select-sm">
                                    <option value="1">YES</option>
                                    <option value="0">NO</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="online" class="col-sm-4 control-label">Status</label>
                            <div class="col-sm-6">
                                <select id="online" name="online" class="custom-select custom-select-sm">
                                    <option value="1">YES</option>
                                    <option value="0">TERMINATED</option>
                                </select>
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
                    <button id="Customers-add" data-toggle="modal" data-target="#Customers-addmodel" class="btn btn-default btn-block btn-sm" >
                        Add <span class="oi oi-plus"></span>
                    </button>
                </div>
                <input type="text" id="Customers-search" class="form-control form-control-sm" placeholder="Search by Name/Code etc.">
                <div class="input-group-append">
                    <button id="Customers-searchbtn" class="btn btn-default btn-sm" >Search <span class="glyphicon glyphicon-search"></span></button>
                </div>
            </div>
        </div> 
        <div class="col-auto">
            <div class="input-group">
                <select id="Customers-pages" name="pages" class="custom-select custom-select-sm">
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
            <div class='col-1 headerdiv'>BIZ</div>
            <div class='col headerdiv'>NAME</div>
            <div class='col-3 headerdiv'>ADDRESS</div>
            <div class='col headerdiv'>TEL-01</div>
            <div class='col headerdiv'>TEL-02</div>
            <div class='col headerdiv'>EMAIL</div>
            <div class="col-sm-1 headerdiv">&nbsp;</div>
        </div>


        <div class="row no-gutters">
            <?php
            $this->widget('zii.widgets.CListView', array(
                'dataProvider' => $dataProvider,
                'itemView' => '_view',
                'enablePagination' => true,
                'summaryText' => '{page}/{pages} pages',
                'id' => 'Customers-list',
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

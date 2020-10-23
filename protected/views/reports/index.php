<div id="header-sec">
    <div class="row">
        <div class="col-sm-12">
            <h1>Stock & Inventory Operation Reports</h1>
        </div>
    </div>
</div>


<script>

    $(function () {
        if (localStorage.report != null) {
            loadui(localStorage.report, localStorage.reportTitle);
        }
    });

    $(document).on("click", ".reportlink", function (e) {
        e.preventDefault();
        var report = $(this).attr("href");
        var reportTitle = $(this).html();

        localStorage.report = report;
        localStorage.reportTitle = reportTitle;

        loadui(report, reportTitle);
    });

    function loadui(page, title) {
        $.ajax({
            url: "<?php echo Yii::app()->createUrl("reports/loadui"); ?>",
            data: {
                report: page,
                title: title
            },
            type: "post",
            error: showResponse
        }).done(function (data) {
            $("#report_port").html(data);
        });

    }
</script>

<div class="report_body">
    <div class="container-fluid">

        <div class="row">
            <div class="col-sm-4">

                <div class="list-group list-group-flush">
                    <a href="inventory_report" class="reportlink list-group-item list-group-item-action">Inventory Report - BASIC</a>
                    <a href="inventory_report_batch" class="reportlink list-group-item list-group-item-action">Inventory Report with Batch</a>
                    <a href="inventory_report_soh" class="reportlink list-group-item list-group-item-action">Inventory Report - SOH</a>
                    <a href="inventory_report_location" class="reportlink list-group-item list-group-item-action">Inventory Report By Location</a>
                    <a href="serial_inventory" class="reportlink list-group-item list-group-item-action">Serial Inventory Report</a>
                    <a href="stock" class="reportlink list-group-item list-group-item-action">Inventory Report - ALL</a>
                    <a href="inventory_summery" class="reportlink list-group-item list-group-item-action">Inventory Summery Report</a>
                    <a href="cycle_report" class="reportlink list-group-item list-group-item-action">Cycle Count Report</a>
                    <a href="health_report" class="reportlink list-group-item list-group-item-action">Health Life Analysis Report</a>
                    <a href="aging_report" class="reportlink list-group-item list-group-item-action">Aging Report</a>
                </div>
            </div>
            <div class="col-sm-8">
                <div id="report_port">



                </div>
            </div>
        </div>
    </div>
</div>


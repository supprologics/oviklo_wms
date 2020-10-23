<div class="row">
    <div class="col-3">
        <div style="padding: 10px;">
            <h2 style="margin: 2px 0; border-bottom: 1px solid #bebebe; padding-bottom: 10px;"><?php echo $model->code; ?></h2>
            <h3><?php echo $model->name; ?></h3>
            <p><?php echo $model->address; ?></p>
        </div>
    </div>
    <div class="col-9">

        <div style="margin-top: 10px;">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Category Registry</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="projects-tab" data-toggle="tab" href="#projects" role="tab" aria-controls="home" aria-selected="true">Projects/Inventory Registry</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">SKU Registry</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <?php require 'category_.php'; ?>
                </div>
                <div class="tab-pane show" id="projects" role="tabpanel" aria-labelledby="projects-tab">
                    <?php require 'projects_.php'; ?>
                </div>
                <div class="tab-pane" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <?php require 'sku_.php'; ?>
                </div>
            </div>
        </div>

    </div>
</div>



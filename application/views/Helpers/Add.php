<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<form action="<?php echo AURI(isset($SaveAction) ? $SaveAction : 'Action/Add')?>" enctype="multipart/form-data" data_validate="true" method="post">
    <div class="card">
        <div class="card-header">
            <?php echo _E('{Current.Title}',true)?>
        </div>
        <div class="card-body">
            <p class="card-description"><?php echo isset($Description) ? $Description : _E('{Current.Config.Description}',true)?></p>
            <div class="row">
                <?php echo GenerateFields($Fields);?>
            </div>
            <?php echo CSRF()?>
        </div>
        <div class="card-footer text-right">
            <?php
                echo _Button('Submit',isset($SaveTitle) ? $SaveTitle : 'Save',['class'=>'btn btn-success mr-2','value'=>time()]);
                echo _A('Cancel',isset($CancelAction) ? $CancelAction : AURI('Index'),['class'=>'btn btn-primary']);
            ?>
        </div>
    </div>
</form>

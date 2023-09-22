<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<form class="row" action="" method="post">
    <div class="col-8 mx-auto">
        <div class="card">
            <div class="card-header"><i class="fa fa-user"></i> Account Settings</div>
            <div class="card-body">
                <div class="row">
                    <?php
                        echo GenerateFields([
                            [
                                'Name'=>'FirstName',
                                'Type'=>'Text',
                                'Label'=>'First Name',
                                'Config'=>[
                                    'Attributes'=>['class'=>'form-control'],
                                    'Template'=>COL6
                                ],
                                'Default'=>'{Profile.FirstName}'
                            ],[
                                'Name'=>'LastName',
                                'Type'=>'Text',
                                'Label'=>'Last Name',
                                'Config'=>[
                                    'Attributes'=>['class'=>'form-control'],
                                    'Template'=>COL6
                                ],
                                'Default'=>'{Profile.LastName}'
                            ]
                        ]);
                    ?>
                </div>
            </div>
            <div class="card-footer text-right">
                <?php echo CSRF();echo _Button('Save','Save Settings',['class'=>'btn btn-success'])?>
            </div>
        </div>
    </div>
</form>

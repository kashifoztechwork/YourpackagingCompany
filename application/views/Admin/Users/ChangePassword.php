<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<form class="row" action="" method="post">
    <div class="col-6 mx-auto">
        <div class="card">
            <div class="card-header"><i class="fa fa-key"></i> Change Your Password</div>
            <div class="card-body">
                <div class="row">
                    <?php
                        echo GenerateFields([
                            [
                                'Name'=>'OldPassword',
                                'Type'=>'Password',
                                'Label'=>'Old Password',
                                'Config'=>[
                                    'Attributes'=>['class'=>'form-control'],
                                    'Template'=>COL12
                                ]
                            ],[
                                'Name'=>'NewPassword',
                                'Type'=>'Password',
                                'Label'=>'New Password',
                                'Config'=>[
                                    'Attributes'=>['class'=>'form-control'],
                                    'Template'=>COL6
                                ]
                            ],[
                                'Name'=>'Password',
                                'Type'=>'Password',
                                'Label'=>'Retype New Password',
                                'Config'=>[
                                    'Attributes'=>['class'=>'form-control'],
                                    'Template'=>COL6
                                ]
                            ]
                        ]);
                    ?>
                </div>
            </div>
            <div class="card-footer text-right">
                <?php echo CSRF();echo _Button('Change','Change Password',['class'=>'btn btn-warning'])?>
            </div>
        </div>
    </div>
</form>

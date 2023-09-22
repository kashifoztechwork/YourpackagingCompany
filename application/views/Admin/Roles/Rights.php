<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<form action="<?php echo AURI(sprintf('Action/Rights/{Row.ID}-%s',$Type),true)?>" method="post">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title"><?php echo _E($Title)?></h4>
            <div class="row">
                <?php
                    foreach($Controllers[NULL] as $Controller):
                        $ChildControllers = isset($Controllers[$Controller->ID]) ? $Controllers[$Controller->ID] : [];
                        if(!empty($ChildControllers)):
                ?>
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header"><?php echo $Controller->Title?></div>
                        <div class="card-body">
                            <div class="row">
                                <?php foreach ($ChildControllers as $ChildController):?>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <div class="form-check form-check-flat" style="margin-top: 0px;">
                                            <label class="form-check-label">
                                                <?php
                                                    echo _Input('Rights[]',$ChildController->ID,'checkbox',['class'=>'form-check-input','AA'=>isset($Rights[$ChildController->ID]) ? ['checked'] : []]);
                                                    echo $ChildController->Title;
                                                ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach;?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                        endif;
                    endforeach;
                ?>
            </div>
        </div>
        <div class="card-footer text-right">
            <?php
                echo CSRF();
                echo _Button('Save','Update Rights',['class'=>'btn btn-success']);
                echo ' ';
                echo _A('Go Back',AURI('Index',true),['class'=>'btn btn-primary']);
            ?>
        </div>
    </div>
</form>

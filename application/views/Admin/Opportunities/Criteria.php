<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<form action="" method="post">
    <div class="card">
        <div class="card-header"><?php echo _E('{Current.Title}')?></div>
        <div class="card-body p-0 pt-2">
            <?php foreach($Items as $ID=>$Item):Def(['ID'=>$ID],'Option');?>
                <div class="card">
                    <div class="card-header bg-info text-white"><?php echo $Item['Title']?></div>
                    <div class="card-body p-2">
                        <ol>
                            <?php
                                foreach($Item['Points'] as $Point){
                                    echo _Tag('li',false,_E($Point),['class'=>'','style'=>'line-height: 50px;']);
                                }
                            ?>
                        </ol>
                    </div>
                </div>
            <?php endforeach?>
        </div>
        <div class="card-footer text-right">
            <?php echo CSRF();echo _Button('Save','Save',['class'=>'btn btn-success'])?>
        </div>
    </div>
</form>

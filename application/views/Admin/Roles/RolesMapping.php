<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="row">
    <div class="col-sm-8 mx-auto">
        <div class="card text-center">
            <div class="card-body">
                <?php
                    echo GenerateFields([[
                        'Name'=>'ProjectID',
                        'Label'=>'Please Select Project',
                        'Type'=>'DropDown',
                        'Config'=>[
                            'Attributes'=>['class'=>'form-control','onchange'=>'if(this.value != \'\')document.location = URI(\'Roles/RolesMapping/\'+(this.value))'],
                            'Template'=>'<h4>{Label_Text}</h4>{Field}',
                            'Model'=>'Project',
                            'Fields'=>['ID','Name'],
                            'Display'=>'{Name}'
                        ],
                        'Default'=>$ProjectID
                    ]])
                ?>
            </div>
        </div>
        <?php
            if($ProjectSelected):
                $Bundels->Snippet('PostScripts',sprintf('
                    <script type="text/javascript">
                        $(function(){
                            $(\'.dd\').nestable({group: 1,json: %s}).on(\'change\',UpdateData).change();
                        });
                        function UpdateData(E){
                            var E = E.length ? E : $(E.target);$(\'[name=Mapping]\').val(JSON.stringify(E.nestable(\'serialize\')).replace(/id/g,\'ID\').replace(/children/g,\'Children\'));
                        }
                    </script>',json_encode($CurrentMapping))
                );
        ?>
            <hr />
            <form action="<?php echo AURI('Action/RolesMapping',true)?>" method="post">
                <div class="card">
                    <div class="card-header">Roles Mapping For "<?php echo $Project->Name?>"</div>
                    <div class="card-body">
                        <div class="dd"></div>
                    </div>
                    <div class="card-footer text-right">
                        <?php
                            echo _Input('Mapping','','hidden');
                            echo CSRF();
                            echo _A('Cancel',AURI('RolesMapping',true),['class'=>'btn btn-primary']);
                            echo ' ';
                            echo _Button('SaveMapping','Save Mapping',['class'=>'btn btn-success']);
                        ?>
                    </div>
                </div>
            </form>
        <?php endif;?>
    </div>
</div>

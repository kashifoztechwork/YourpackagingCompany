<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
   
    //Check IF Actions Alterd
    if(!isset($Actions))$Actions = [];
    if(!isset($MultiActions))$MultiActions = [];
    if(!isset($Multi))$Multi = true;
    if(!isset($HasActions))$HasActions = true;
    if(!isset($PartialList))$PartialList = '';
    if(!isset($PartialAction))$PartialAction = '';
    if(!isset($List))$List = NULL;
    if(!isset($Headers))$Headers = [];
    if(!isset($ListMethod))$ListMethod = 'Index';
    if(!isset($Filters))$Filters = NULL;
    if(!isset($Editable))$Editable = false;
    if(!isset($EditModel))$EditModel = '';
    if(!isset($Details))$Details = false;
    //Defining Basic Actions
    if($HasActions && !isset($Actions['EDIT']))$Actions['EDIT'] = ['Title'=>'<i class="fa fa-pencil"> Edit</i>','Link'=>AURI('Edit/{Row.ID}',true),'Attributes'=>['']];
    if($HasActions && !isset($Actions['DELETE']))$Actions['DELETE'] = ['Title'=>'<i class="fa fa-trash"> Delete</i>','Link'=>AURI('Action/Delete/{Row.ID}',true),'Attributes'=>[]];

    //Defining Multiple Actions
    if($HasActions && !isset($MultiActions['ADD']))$MultiActions['ADD'] = '{(_A(\'<i class="fa fa-plus-circle"></i>\',AURI(\'Add\',true),[\'class\'=>\'btn btn-success\']))}';
    if($HasActions && !isset($MultiActions['DELETE']) && $Multi)$MultiActions['DELETE'] = '{(_Button(\'PostAction\',\'<i class="fa fa-trash"></i>\',[\'value\'=>\'DeleteAll\',\'class\'=>\'btn btn-danger\']))}';
    //Editable Action
    if($Editable){
        $Bundels->Snippet('PreScripts',sprintf('<script type="text/javascript">var SaveEditable = \'%s\';var CFN = \'%s\';var CFH = \'%s\';</script>',AURI(sprintf('SaveEditable/%s',$EditModel),true),_CSRF(),_CSRF_Hash()));
        $Headers = array();
        $DetailHeaders = array();
        foreach($this->Editables[$EditModel]['Fields'] as $Name=>$Field){
            if(is_array($Field)){
                $Field['Field'] = $Name;
                $Field['EditData'] = isset($Field['EditData']) ? $Field['EditData'] : $Field['Display'];
                $Field['Type'] = isset($Field['Type']) ? $Field['Type'] : 'Textbox';
                $Headers[$Field['Label']] = $Field;
            }else{
                $Headers[$Name] = $Field;
            }
        }
        if($Details && isset($this->Editables[$EditModel]['Details'])){
            foreach($this->Editables[$EditModel]['Details'] as $Name=>$Field){
                if(is_array($Field)){
                    $Field['Field'] = $Name;
                    $Field['EditData'] = isset($Field['EditData']) ? $Field['EditData'] : $Field['Display'];
                    $Field['Type'] = isset($Field['Type']) ? $Field['Type'] : 'Textbox';
                    $DetailHeaders[$Field['Label']] = $Field;
                }else{
                    $DetailHeaders[$Name] = $Field;
                }
            }
        }
    }
    if(!$Editable && empty($Headers)){
        foreach($this->Editables[$EditModel]['Fields'] as $Name=>$Field){
            if(is_array($Field)){
                $Headers[$Field['Label']] = $Field['Display'];
            }else{
                $Headers[$Name] = $Field;
            }
        }
        if($Details && isset($this->Editables[$EditModel]['Details'])){
            foreach($this->Editables[$EditModel]['Details'] as $Name=>$Field){
                if(is_array($Field)){
                    $DetailHeaders[$Field['Label']] = $Field['Display'];
                }else{
                    $DetailHeaders[$Name] = $Field;
                }
            }
        }
    }
    if($Filters):
?>
<form action="" method="get" class="accordion accordion-multiple-outline">
    <div class="card">
        <div class="card-header" role="tab">
            <h6 class="mb-0">
                <?php echo _A('<i class="card-icon mdi mdi-filter"></i> Filters','#Filters',['class'=>'collapsed','data-toggle'=>'collapse','aria-expanded'=>'false'])?>
            </h6>
        </div>
        <div class="card-body show" id="Filters">
            <div class="row">
                 <?php echo GenerateFields(FilterFields($Filters))?>
                <div class="col-sm-12 text-right">
                    <button class="btn btn-info" name="Search">Filter</button>
                </div>
            </div>
        </div>
    </div>
</form>
<?php endif;?>
<form action="<?php echo AURI('Action/General',true)?>" method="post">
    <div class="card">
        <div class="card-header p-2">
            <div class="row">
                <div class="col-md-8 pt-1"><?php echo isset($Title) && $Title ? _E($Title) : _E('{Current.Title}',true);echo sprintf(' (%d)',$List->Total)?></div>
                <div class="col-md-4 text-right">
                    <span class="btn-group">
                        <?php
                            if(!empty($MultiActions)){
                                foreach($MultiActions as $Access=>$Action){
                                    if($this->Access->HaveAccess($Access) && $Action){
                                        echo _E($Action);
                                    }
                                }
                            }
                        ?>
                    </span>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 table-responsive">
                    <table data-type="DataTable" class="table" style="min-width: 100%;">
                        <thead>
                            <tr>
                                <?php
                                    if($Multi){
                                        echo _Tag('th',false,_Input('RemoveMulti','','checkbox',['data-id'=>'List','data-type'=>'CheckAll']),['class'=>'text-left','valign'=>'bottom']);
                                    }
                                    if($Details){
                                        echo _Tag('th',false,'Details',['class'=>'text-center','valign'=>'bottom']);
                                    }
                                    if(!empty($Headers)){
                                        foreach($Headers as $Name=>$Value){
                                            echo _Tag('th',false,$Name,['class'=>'text-left']);
                                        }
                                    }
                                    if($HasActions){
                                        echo _Tag('th',false,'Actions',['class'=>'text-center']);
                                    }
                                ?>
                            </tr>
                        </thead>
                        <?php
                            if($List && $List->Rows > 0 && !empty($Headers)):
                                if($PartialList):
                                    $this->load->view($PartialList);
                                else:
                                    echo '<tbody>';
                                    foreach($List->Result as $Row){
                                        Def($Row,'Row');
                                        $OutCells = [];
                                        $DetailCells = [];
                                        if($Multi){
                                            $OutCells[] = _Tag('td',false,_Input('Item[]',$Row->ID,'checkbox',['data-id'=>'List']));
                                        }
                                        if($Details){
                                            $OutCells[] = _Tag('td',false,_A('Details','javascript:;',['class'=>'btn btn-info btn-xs','data-type'=>'DetailsPane','data-id'=>$Row->ID]),['class'=>'text-center']);
                                        }
                                        foreach($Headers as $Name=>$CMD){
                                            if($Editable && is_array($CMD) && isset($CMD['Type'])){
                                                if(isset($this->Editables[$EditModel]['Fields'][$CMD['Field']])){
                                                    $OutCells[] = EditableCell($CMD,$this->Editables[$EditModel]['Fields'][$CMD['Field']],$Row);
                                                }
                                            }else{
                                                $OutCells[] = _Tag('td',false,_E($CMD,true),['class'=>'text-left']);
                                            }
                                        }
                                        if($Details){
                                            foreach($DetailHeaders as $Name=>$CMD){
                                                $DetailsItem = [_Tag('th',false,isset($CMD['Label']) ? $CMD['Label'] : $Name,['width'=>'20%'])];
                                                if($Editable && is_array($CMD) && isset($CMD['Type'])){
                                                    if(isset($this->Editables[$EditModel]['Details'][$CMD['Field']])){
                                                        $DetailsItem[] = EditableCell($CMD,$this->Editables[$EditModel]['Details'][$CMD['Field']],$Row);
                                                    }
                                                }else{
                                                    $DetailsItem[] = _Tag('td',false,_E($CMD,true),['class'=>'text-left']);
                                                }
                                                $DetailCells[] = _Tag('tr',false,implode('',$DetailsItem));
                                            }
                                        }
                                        if($HasActions){
                                            if($PartialAction){
                                                $ActionContainer = $this->load->view($PartialAction,['Actions'=>$Actions,'Row'=>$Row],true);
                                            }else{
                                                $ActionContainer = [];
                                                foreach($Actions as $Access=>$Action){
                                                    if($Action && $this->Access->HaveAccess($Access)){
                                                        $Attributes = isset($Action['Attributes']) ? $Action['Attributes'] : [];
                                                        $Attributes['Class'] = 'dropdown-item';
                                                        $ActionContainer[] = _A($Action['Title'],_E($Action['Link']),$Attributes);
                                                    }
                                                }
                                                $ActionContainer = implode('',$ActionContainer);
                                            }
                                            $OutCells[] = sprintf(
                                                '<td class="text-center"><div class="dropdown">%s<div class="dropdown-menu"><h6 class="dropdown-header">Available Actions</h6>%s</div></div></td>',
                                                _Button('Actions','Actions',['class'=>'btn btn-primary dropdown-toggle','data-toggle'=>'dropdown','style'=>'padding:0.56rem 1.375rem;']),
                                                $ActionContainer
                                            );
                                        }
                                        echo _Tag('tr',false,implode('',$OutCells));
                                        if($Details){
                                            echo _Tag('tr',false,_Tag('td',false,_Tag('table',false,_Tag('tbody',false,implode('',$DetailCells)),['class'=>'table','style'=>'background-color: #212529; color: white;']),['style'=>'display: none;','data-type'=>'DetailsData','data-id'=>$Row->ID,'class'=>'p-0 border-0','colspan'=>count($Headers) + 1 + ($Multi ? 1 : 0) + ($HasActions ? 1 : 0)]));
                                            ///implode('',$DetailCells)
                                        }
                                    }
                                    echo '</tbody>';
                                endif;
                            else:
                                $TotalCells = count($Headers);
                                if($HasActions)$TotalCells++;
                                if($Multi)$TotalCells++;
                                echo sprintf('<tbody><tr><td colspan="%d"><div class="alert alert-warning">No record was found.%s</div></td></tr></tbody>',$TotalCells,GS('F') ? sprintf(' Filter map apply try to clear the filters. %s',_A('Clear Filters',AURI($this->Method,true),['class'=>'link'])) : '');
                            endif;
                        ?>
                    </table>
                </div>
            </div>
        </div>
        <?php
            if($Pages > 1){
        		//Limitize Pagination Page Numbers
        		$PagesLimit = 3;
        		$End = $Page+$PagesLimit >= $Pages ? $Pages : $Page+$PagesLimit;
        		$Start = $Page >= $PagesLimit ? $Page-$PagesLimit : 1;
        		$Start = $Start == 0 ? 1 :  $Start;
        		//Getting Query Parameters
        		$QS = isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] != '' ? sprintf('?%s',$_SERVER['QUERY_STRING']) : '';
        		$Links = array();
        		if($Page > 1){
        			$Links[] = sprintf('<li class="page-item">%s</li>',_A('<i class="mdi mdi-chevron-left"></i>',AURI(sprintf('%s/%s%s',$ListMethod,$Page-1,$QS),true),['class'=>'page-link']));
        		}
        		for($I=$Start;$I<=$End;$I++){
        			if($I == $Page){
        				$Class = ' active';
        			}else{
        				$Class = '';
        			}
        			$Links[] = sprintf('<li class="page-item%s">%s</li>',$Class,_A($I,AURI(sprintf('%s/%s%s',$ListMethod,$I,$QS),true),['class'=>'page-link']));
        		}
        		if($Page != $Pages){
        			$Links[] = sprintf('<li class="page-item">%s</li>',_A('<i class="mdi mdi-chevron-right"></i>',AURI(sprintf('%s/%s%s',$ListMethod,$Page+1,$QS),true),['class'=>'page-link']));
        		}
        		echo sprintf('<div class="card-footer"><nav class="mt-2"><ul class="pagination d-flex justify-content-center pagination-success">%s</ul></nav></div>',implode('',$Links));
        	}
        ?>
    </div>
    <?php echo CSRF();?>
</form>

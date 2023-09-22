<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Sliders extends M_Default {

	public	$Required = ['Slider'];
			

	public function __construct(){

		parent::__construct();
		$this->Model = $this->Slider;
	}

	public function Index($Page=0){
		$this->CheckAccess('LIST');
		//Page Filters
		$Data['Filters'] = [
			'Title'=>[
				'Label'=>'Name',
				'Type'=>'Text',
				'Filter'=>['SLD.Name','LIKE']
			],
			'Title'=>[
				'Label'=>'Link',
				'Type'=>'Text',
				'Filter'=>['SLD.Link','LIKE']
			]
		];

		//List Headers
		$Data['Headers'] = [
			'ID'=>'{Row.ID}',
			'Name'=>'{Row.Name}',
			'Link'=>'{Row.Link}'
		];
		// Select Fields
		$Data['SelectFields'] = 'SLD.*';
		// Prepare List
		$this->PrepareList($this->Model,$Data,$Page,true);
	}

	public function GetFields($Edit=false){

		return [
			[
				'Name'=>'Name',
				'Label'=>'Name',
				'Type'=>'Text',
				'Config'=>[
					'Attributes'=>['class'=>'form-control','AA'=>['required']],
					'Template'=>COL6
				],
				'Default'=>'{Row.Name}'
			],
			[
				'Name'=>'Link',
				'Label'=>'Link',
				'Type'=>'Text',
				'Config'=>[
					'Attributes'=>['class'=>'form-control','AA'=>['required']],
					'Template'=>COL6
				],
				'Default'=>'{Row.Link}'
			],
			[
				'Name'=>'Image',
				'Label'=>'Image',
				'Type'=>'File',
				'Config'=>[
					'Attributes'=>['class'=>'form-control'],
					'Template'=>COL12
				]
			]
		];
		if($Edit){
			$Images = _E('{Row.Image}',true);
			if(!empty($Images)){
				$Fields[] = [
					'Name'=>'',
					'Label'=>'',
					'Config'=>[
						'Template'=>_E('<div class="card col-12 border-info p-0"><div class="card-header bg-info text-white">Images</div><div class="card-body row p-2">{( TemplateItems(GetImage(\'{Row.EntryDate}\',\'{Row.Images}\',[\'width\'=>\'100%\'],true,true),sprintf(\'<div class="col-2 text-center mt-2" style="height: 200px;overflow: hidden;">{Item}%s</div>\',_A(\'Delete\',AURI(\'RemoveItem/Images/{Row.ID}/{Key}\'),[\'class\'=>\'btn btn-danger btn-xs\',\'style\'=>\'position: absolute; top:0;z-index: 9;left: 48%;\']))) )}</div></div>')
					]
				];
			}
		}
	}

	public function Action($Action,$PK=0){
		if($Action != ''){
			$Input = $this->input;
			if($Action == 'Add' || $Action == 'Edit'){

				$this->SetValidationRules([
					['Name'=>'Name','Label'=>'Name','Rule'=>'required|trim|alpha_numeric_spaces'],
					['Name'=>'Link','Label'=>'Link','Rule'=>'trim']
				]);
				if(!$this->form_validation->run()){
					return GoBack();
				}
				
				
				$this->ProcessPost([['Name'],['Link']],$Data);
			    
			    if($Action == 'Edit'){
					$Row = $this->Model->Row($PK);
					//$Config = (array)unserialize($Row->Config);
				}
				
				
				//string $Element,int $Stamp,string $Type='IMG',string $FileTypes='*',array $Config=[]
				//Uploading Images
				if($_FILES['Image']['name'][0] != ''){
				    
					$Images = Upload('Image',$Action == 'Edit' ? strtotime($Row->EntryDate) : time(),'IMG','jpg|gif|jpeg|png|bmp');
					
					if(isset($Images['Errors'])){
						SetMessage(implode('',$Images['Errors']),'Error');
						return GoBack();
					}
					if($Action == 'Edit' && isset($Row->Image)){
					    $Data['Image']  = serialize($Images);
					}else{
					    $Data['Image']  = serialize($Images);
					}
				}
			
			}

			switch($Action){
				case 'Add':
					$this->CheckAccess('ADD');
					$PK = $this->Model->Insert($Data);
					if($PK > 0){
						SetMessage('ENTRYSUCCESS');
					}else{
						SetMessage('ENTRYFAILED');
					}
				break;
				case 'Edit':
					$this->CheckAccess('EDIT');
					if($this->Model->Update($PK,$Data)){
						SetMessage('ENTRYSUCCESS');
					}else{
						SetMessage('ENTRYFAILED');
					}
				break;
				case 'Delete':
					$this->CheckAccess('DELETE');
					if($this->Model->Delete($PK)){
						SetMessage('ENTRYDELETED');
					}else{
						SetMessage('ENTRYFAILED');
					}
				break;
				case 'General':
					$PostAction = PS('PostAction');
					$Items = array_map('intval',PS('Item'));
					if(!empty($Items)){
						switch($PostAction){
							case 'DeleteAll':
								$this->CheckAccess('DELETE');
								$this->Model->DB()->where_in('ID',$Items);
								if($this->Model->Delete(['ID > '=>0])){
									SetMessage('ENTRIESDELETED');
								}else{
									SetMessage('ENTRYFAILED');
								}
							break;
						}
					}
				break;
			}
			return Forword(AURI('Index',true));
		}else{
			InvalidData();
		}
	}

	public function __destruct(){
		parent::__destruct();
	}
}

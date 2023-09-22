<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Tags extends M_Default {

	public	$Required = ['Tag'];
			

	public function __construct(){

		parent::__construct();
		$this->Model = $this->Tag;
	}

	public function Index($Page=0){
		$this->CheckAccess('LIST');
		// Filters
		$Data['Filters'] = [
			'Name'=>[
				'Label'=>'Title',
				'Type'=>'Text',
				'Filter'=>['TAG.Name','LIKE']
			],
			'Page'=>[
				'Label'=>'Type',
				'Type'=>'Text',
				'Filter'=>['TAG.Type','LIKE']
			]
		];
		//List Headers
		$Data['Headers'] = [
			'ID'=>'{Row.ID}',
			'Title'=>'{Row.Title}',
			'Keywords'=>'{Row.Keywords}',
			'Description'=>'{Row.Description}',
			'Type'=>'{Row.Type}',
		];
		// Select Fields
		$Data['SelectFields'] = 'TAG.*';
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
				'Name'=>'Title',
				'Label'=>'Title',
				'Type'=>'Text',
				'Config'=>[
					'Attributes'=>['class'=>'form-control','AA'=>['required']],
					'Template'=>COL6
				],
				'Default'=>'{Row.Title}'
			],
			[
				'Name'=>'Keywords',
				'Label'=>'Keywords',
				'Type'=>'Text',
				'Config'=>[
					'Attributes'=>['class'=>'form-control','AA'=>['required']],
					'Template'=>COL6
				],
				'Default'=>'{Row.Keywords}'
			],
			[
				'Name'=>'Type',
				'Label'=>'Type',
				'Type'=>'DropDown',
				'Config'=>[
					'Attributes'=>['class'=>'form-control','AA'=>['required']],
					'Data'=>['Category'=>'Category','Product'=>'Product','Static'=>'Static'],
					'UV'=>true,
					'Template'=>COL6
				],
				'Default'=>'{Row.Type}'
			],[
				'Name'=>'Description',
				'Label'=>'Description',
				'Type'=>'TextArea',
				'Config'=>[
					'Attributes'=>['class'=>'form-control description','rows'=>'10'],
					'Template'=>'<div class="form-group col-12">{Label}{Field}{Message}  <span id="rchars">150</span> Character(s) Remaining</div>',
				],
				'Default'=>'{Row.Description}'
			],[
				'Name'=>'IsIndex',
				'Label'=>'Is Index',
				'Type'=>'CheckBox',
				'Config'=>[
					'Attributes'=>['class'=>'form-check-input'],
					'Data'=>[1=>'Index'],
					'Template'=>COL4
				],
				'Default'=>'{Row.IsIndex}'
			]
		];
		
	}

	public function Action($Action,$PK=0){
		if($Action != ''){
			$Input = $this->input;
			if($Action == 'Add' || $Action == 'Edit'){

				$this->SetValidationRules([
					['Name'=>'Name','Label'=>'Name','Rule'=>'required|trim|alpha_numeric_spaces']
				]);
				if(!$this->form_validation->run()){
					return GoBack();
				}
				
				$this->ProcessPost([['Name'],['Title'],['Description'],['Keywords'],['Type'],['IsIndex']],$Data);
				// Config
				//$Data['Config'] = ($Data['Config']);
				if($Action == 'Edit'){
					$Row = $this->Model->Row($PK);
					$Data['TypeID'] = $Row->TypeID;
				} else {
					$Data['TypeID'] = 0;
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

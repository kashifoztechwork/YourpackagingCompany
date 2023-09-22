<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Navigations extends M_Default {

	public	$Required = ['Navigation'];
			

	public function __construct(){

		parent::__construct();
		$this->Model = $this->Navigation;
	}

	public function Index($Page=0){
		$this->CheckAccess('LIST');
		// Filters
		$Data['Filters'] = [
			'Title'=>[
				'Label'=>'Name',
				'Type'=>'Text',
				'Filter'=>['NAV.Name','LIKE']
			]
		];
		//List Headers
		$Data['Headers'] = [
			'ID'=>'{Row.ID}',
			'Name'=>'{Row.Name}',
			'Link'=>'{Row.Link}',
			'Position'=>'{Row.Position}',
			'Parent'=>'{Row.Parent}',
		];
		// Select Fields
		$Data['SelectFields'] = 'NAV.*';
		// Prepare List
		$this->PrepareList($this->Model,$Data,$Page,true);
	}

	public function GetFields($Edit=false,$Row=null){

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
				'Name'=>'Position',
				'Label'=>'Position',
				'Type'=>'Text',
				'Config'=>[
					'Attributes'=>['class'=>'form-control','AA'=>['required']],
					'Template'=>COL4
				],
				'Default'=>'{Row.Position}'
			],
			[
				'Name'=>'Parent',
				'Label'=>'Parent',
				'Type'=>'DropDown',
				'Config'=>[
					'Attributes'=>['class'=>'form-control'],
					'Model'=>'Navigation',
					'Display'=>'{Name}',
					'Fields'=>['ID','Name'],
					'UV'=>true,
					'Template'=>COL4
				],
				'Default'=>'{Row.Parent}'
			],
			[
				'Name'=>'Visible',
				'Label'=>'Status',
				'Type'=>'CheckBox',
				'Config'=>[
					'Attributes'=>['class'=>'form-check-input'],
					'Data'=>[1=>'Visible in Navigation'],
					'Template'=>COL4
				],
				'Default'=>'{Row.Visible}'
			],
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
				
				$this->ProcessPost([['Name'],['Link'],['Position'],['Visible'],['Parent']],$Data);
			
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

<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Modules extends M_Default {

	public	$Required = [];

	public function __construct(){

		parent::__construct();
		$this->Model = $this->Module;
	}

	public function Index($Page=0){
		$this->CheckAccess('LIST');
		//Page Filters
		$Data['Filters'] = [
			'Title'=>[
				'Label'=>'Module Title',
				'Type'=>'Text',
				'Filter'=>['MOD.Title','LIKE']
			],
			'Controller'=>[
				'Label'=>'Controller Name',
				'Type'=>'Text',
				'Filter'=>['MOD.Controller','LIKE']
			],
			'Method'=>[
				'Label'=>'Action Method',
				'Type'=>'Text',
				'Filter'=>['MOD.Method','LIKE']
			],
			'Date'=>[
				'Label'=>'Added Date',
				'Type'=>'Text',
				'Filter'=>['MOD.EntryDate','RANGE']
			]
		];

		//List Headers
		$Data['Headers'] = [
			'ID'=>'{Row.ID}',
			'Title'=>'{Row.Title}',
			'Link'=>'{Row.Slug}',
			'Parent'=>'{Row.ParentModule}',
			'Order'=>'{Row.Order}'
		];
		
		$this->PrepareList($this->Model,$Data,$Page,true);
	}

	public function GetFields($Edit=false,$Row=null){

		return [
			[
				'Name'=>'Title',
				'Label'=>'Module Title',
				'Type'=>'Text',
				'Config'=>[
					'Attributes'=>['class'=>'form-control','AA'=>['required']]
				],
				'Default'=>'{Row.Title}'
			],[
				'Name'=>'Controller',
				'Label'=>'Controller Name',
				'Type'=>'Text',
				'Config'=>[
					'Attributes'=>['class'=>'form-control']
				],
				'Default'=>'{Row.Controller}'
			],[
				'Name'=>'Method',
				'Label'=>'Action Name',
				'Type'=>'Text',
				'Config'=>[
					'Attributes'=>['class'=>'form-control'],
					'Template'=>COL3
				],
				'Default'=>'{Row.Method}'
			],[
				'Name'=>'Visible',
				'Label'=>'',
				'Type'=>'CheckBox',
				'Config'=>[
					'Attributes'=>['class'=>'form-check-input'],
					'Data'=>[1=>'Visible in Navigation'],
					'Template'=>COL3
				],
				'Default'=>'{Row.Visible}'
			],[
				'Name'=>'AccessModifier',
				'Label'=>'Modifier Name',
				'Type'=>'Text',
				'Config'=>[
					'Attributes'=>['class'=>'form-control']
				],
				'Default'=>'{Row.AccessModifier}'
			],[
				'Name'=>'Config[Description]',
				'Label'=>'Moduel Description',
				'Type'=>'TextArea',
				'Config'=>[
					'Attributes'=>['class'=>'form-control'],
					'Template'=>COL12
				],
				'Default'=>'{Row.Config.Description}'
			],[
				'Name'=>'Config[Icon]',
				'Label'=>'Icon Class',
				'Type'=>'Text',
				'Config'=>[
					'Attributes'=>['class'=>'form-control'],
					'Template'=>COL3,
				],
				'Default'=>'{Row.Config.Icon}'
			],[
				'Name'=>'Order',
				'Label'=>'Sort Order',
				'Type'=>'Number',
				'Config'=>[
					'Attributes'=>['class'=>'form-control','AA'=>['required']],
					'Template'=>COL3,
				],
				'Default'=>'{Row.Order}'
			],[
				'Name'=>'ModuleID',
				'Label'=>'Parent Module',
				'Type'=>'DropDown',
				'Config'=>[
					'Attributes'=>['class'=>'form-control'],
					'Model'=>'Module',
					'Clouse'=>$Edit ? ['MOD.ModuleID'=>NULL,'MOD.ID !='=>$Row->ID] : ['MOD.ModuleID'=>NULL],
					'Display'=>'{Title}',
					'Fields'=>['ID','Title'],
					'UV'=>true
				],
				'Default'=>'{Row.ModuleID}'
			]
		];
	}

	public function Action($Action,$PK=0){
		if($Action != ''){
			$Input = $this->input;
			if($Action == 'Add' || $Action == 'Edit'){

				$this->SetValidationRules([
					['Name'=>'Title','Label'=>'Title','Rule'=>'required|trim|alpha_numeric_spaces'],
					['Name'=>'Controller','Label'=>'Controller Name','Rule'=>'trim|alpha_numeric'],
					['Name'=>'Method','Label'=>'Action Name','Rule'=>'trim|alpha_numeric'],
					['Name'=>'Order','Label'=>'Sort Order','Rule'=>'required|numeric'],
				]);
				if(!$this->form_validation->run()){
					return GoBack();
				}
				$this->ProcessPost([['Title'],['Controller'],['Method'],['Visible'],['Order'],['AccessModifier'],['Config','',true,['serialize']],['ModuleID','',true,['IntOrNull']]],$Data);
				$Data['Slug'] = $Data['Controller'] ? sprintf('%s/%s',$Data['Controller'],$Data['Method']) : '';
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

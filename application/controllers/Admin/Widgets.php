<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Widgets extends M_Default {

	public	$Required = ['Widget'];
			

	public function __construct(){

		parent::__construct();
		$this->Model = $this->Widget;
	}

	public function Index($Page=0){
		$this->CheckAccess('LIST');
		// Filters
		$Data['Filters'] = [
			'Name'=>[
				'Label'=>'Name',
				'Type'=>'Text',
				'Filter'=>['WGT.Name','LIKE']
			],
			'Page'=>[
				'Label'=>'Page',
				'Type'=>'Text',
				'Filter'=>['WGT.Page','LIKE']
			],
			'Status'=>[
				'Label'=>'Status',
				'Type'=>'Text',
				'Filter'=>['WGT.Status','LIKE']
			]
		];
		//List Headers
		$Data['Headers'] = [
			'ID'=>'{Row.ID}',
			'Name'=>'{Row.Name}',
			'Page'=>'{Row.Page}',
			'Status'=>'{Row.Status}'
		];
		// Select Fields
		$Data['SelectFields'] = 'WGT.*';
		// Prepare List
		$this->PrepareList($this->Model,$Data,$Page,true);
	}

	public function GetFields($Edit=false){
		$this->Bundels->Snippet('PostScripts','
		<script>
			tinymce.init({
				selector: \'textarea.content\',
				plugins: \'code\',
				menubar: false,
				toolbar: \'code\',
				convert_fonts_to_spans : false,
				forced_root_block: false,
				force_p_newlines: false,
				valid_elements : \'*[*]\',
				
				
		
			});
		</script>
		');
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
				'Name'=>'Page',
				'Label'=>'Page',
				'Type'=>'Text',
				'Config'=>[
					'Attributes'=>['class'=>'form-control','AA'=>['required']],
					'Template'=>COL6
				],
				'Default'=>'{Row.Page}'
			],
			[
				'Name'=>'Position',
				'Label'=>'Position',
				'Type'=>'Number',
				'Config'=>[
					'Attributes'=>['class'=>'form-control','AA'=>['required']],
					'Template'=>COL6
				],
				'Default'=>'{Row.Position}'
			],
			[
				'Name'=>'Status',
				'Label'=>'Status',
				'Type'=>'CheckBox',
				'Config'=>[
					'Attributes'=>['class'=>'form-check-input'],
					'Data'=>[1=>'Visibility'],
					'Template'=>COL6
				],
				'Default'=>'{Row.Status}'
			],
			[
				'Name'=>'Config',
				'Label'=>'Content',
				'Type'=>'TextArea',
				'Config'=>[
					'Attributes'=>['class'=>'form-control content','rows'=>20],
					'Template'=>COL12
				],
				'Default'=>'{Row.Config}'
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
				
				$this->ProcessPost([['Name'],['Page'],['Position'],['Status'],['Config']],$Data);
				// Config
				//$Data['Config'] = ($Data['Config']);
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

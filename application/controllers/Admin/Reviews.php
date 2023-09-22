<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Reviews extends M_Default {

	public	$Required = ['Review','Product'];
			

	public function __construct(){

		parent::__construct();
		$this->Model = $this->Review;
	}

	public function Index($Page=0){
		$this->CheckAccess('LIST');
		// Filters
		
		$Data['Filters'] = [
			'Name'=>[
				'Label'=>'Name',
				'Type'=>'Text',
				'Filter'=>['REV.Name','LIKE']
			],
			'Email'=>[
				'Label'=>'Email',
				'Type'=>'Text',
				'Filter'=>['REV.Email','LIKE']
			],
			'Stars'=>[
				'Label'=>'Stars',
				'Type'=>'Text',
				'Filter'=>['REV.Stars','LIKE']
			],
			'Product'=>[
				'Label'=>'Product',
				'Type'=>'Text',
				'Filter'=>['REV.Product','LIKE']
			],
			'Status'=>[
				'Label'=>'Status',
				'Type'=>'Text',
				'Filter'=>['REV.Status','LIKE']
			]
		];
		//List Headers
		$Data['Headers'] = [
			'ID'=>'{Row.ID}',
			'Name'=>'{Row.Name}',
			'Email'=>'{Row.Email}',
			'Stars'=>'{Row.Stars}',
			'Product'=>'{Row.Product}',
			'Comments'=>'{Row.Comment}',
			'Status'=>'{Row.Status}',
		];
		// Select Fields
		$Data['SelectFields'] = 'REV.*';
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
				'Name'=>'Email',
				'Label'=>'Email',
				'Type'=>'Text',
				'Config'=>[
					'Attributes'=>['class'=>'form-control','AA'=>['required']],
					'Template'=>COL6
				],
				'Default'=>'{Row.Email}'
			],
			[
				'Name'=>'Product',
				'Label'=>'Product',
				'Type'=>'DropDown',
				'Config'=>[
					'Attributes'=>['class'=>'form-control'],
					'Template'=>COL4,
					'Model'=>'Product',
					'Display'=>'{Name}',
					'Fields'=>['ID','Name'],
					'UV'=>true
				],
				'Default'=>'{Row.Product}'
			],
			[
				'Name'=>'Stars',
				'Label'=>'Stars',
				'Type'=>'DropDown',
				'Config'=>[
					'Attributes'=>['class'=>'form-control'],
					'Template'=>COL4,
					'Data'=>['1','2','3','4','5'],
					'UV'=>true
				],
				'Default'=>'{Row.Stars}'
			],
			[
				'Name'=>'Status',
				'Label'=>'Status',
				'Type'=>'CheckBox',
				'Config'=>[
					'Attributes'=>['class'=>'form-check-input'],
					'Data'=>[1=>'Approve'],
					'Template'=>COL4
				],
				'Default'=>'{Row.Status}'
			],
			[
				'Name'=>'Comment',
				'Label'=>'Comment',
				'Type'=>'TextArea',
				'Config'=>[
					'Attributes'=>['class'=>'form-control','rows'=>'10'],
					'Template'=>COL12
				],
				'Default'=>'{Row.Comment}'
			],
		];
		
	}

	public function Action($Action,$PK=0){
		if($Action != ''){
			$Input = $this->input;
			if($Action == 'Add' || $Action == 'Edit'){

				$this->SetValidationRules([
					['Name'=>'Name','Label'=>'Name','Rule'=>'required|trim|alpha_numeric_spaces'],
					['Name'=>'Email','Label'=>'Email','Rule'=>'required|trim'],
					['Name'=>'Comment','Label'=>'Comment','Rule'=>'required|trim'],
				
				]);
				if(!$this->form_validation->run()){
					return GoBack();
				}
				
				$this->ProcessPost([['Name'],['Email'],['Stars'],['Comment'],['Product'],['Status']],$Data);
			
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

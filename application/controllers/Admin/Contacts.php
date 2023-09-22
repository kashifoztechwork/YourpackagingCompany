<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Contacts extends M_Default {

	public	$Required = ['Contact'];
			

	public function __construct(){

		parent::__construct();
		$this->Model = $this->Contact;
	}

	public function Index($Page=0){
		$this->CheckAccess('LIST');
		// Filters
		
		$Data['Filters'] = [
			'Name'=>[
				'Label'=>'Name',
				'Type'=>'Text',
				'Filter'=>['CONT.Name','LIKE']
			],
			'Email'=>[
				'Label'=>'Email',
				'Type'=>'Text',
				'Filter'=>['CONT.Email','LIKE']
			]
		];
		//List Headers
		$Data['Headers'] = [
			'ID'=>'{Row.ID}',
			'Name'=>'{Row.Name}',
			'Email'=>'{Row.Email}',
			'Phone'=>'{Row.Phone}',
			'Subject'=>'{Row.Subject}',
			'Message'=>'{Row.Message}'
		];
		// Select Fields
		$Data['SelectFields'] = 'CONT.*';
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
				'Name'=>'Phone',
				'Label'=>'Phone',
				'Type'=>'Text',
				'Config'=>[
					'Attributes'=>['class'=>'form-control','AA'=>['required']],
					'Template'=>COL6
				],
				'Default'=>'{Row.Phone}'
			],
			[
				'Name'=>'Subject',
				'Label'=>'Subject',
				'Type'=>'Text',
				'Config'=>[
					'Attributes'=>['class'=>'form-control','AA'=>['required']],
					'Template'=>COL12
				],
				'Default'=>'{Row.Subject}'
			],
			[
				'Name'=>'Message',
				'Label'=>'Message',
				'Type'=>'TextArea',
				'Config'=>[
					'Attributes'=>['class'=>'form-control'],
					'Template'=>COL12
				],
				'Default'=>'{Row.Message}'
			]
		];
		
	}

	public function Action($Action,$PK=0){
		if($Action != ''){
			$Input = $this->input;
			if($Action == 'Add' || $Action == 'Edit'){

				$this->SetValidationRules([
					['Name'=>'Name','Label'=>'Name','Rule'=>'required|trim|alpha_numeric_spaces'],
					['Name'=>'Email','Label'=>'Email','Rule'=>'required|trim'],
					['Name'=>'Phone','Label'=>'Phone','Rule'=>'required|trim'],
					['Name'=>'Subject','Label'=>'Subject','Rule'=>'required|trim'],
					['Name'=>'Message','Label'=>'Message','Rule'=>'required|trim'],
				
				]);
				if(!$this->form_validation->run()){
					return GoBack();
				}
				
				$this->ProcessPost([['Name'],['Email'],['Phone'],['Subject'],['Message']],$Data);
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

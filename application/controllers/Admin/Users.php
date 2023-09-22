<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Users extends M_Default {

	public	$Required = ['Role'];

	public function __construct(){

		parent::__construct();
		$this->Model = $this->Profile;
		$this->load->library('HierarchyGenerator',[]);

	}

	public function Index($Page=0){
		$this->CheckAccess('LIST');
		//Page Filters
		$Data['Filters'] = [
			'FirstName'=>[
				'Label'=>'First Name',
				'Type'=>'Text',
				'Filter'=>['PRF.FirstName','LIKE']
			],
			'LastName'=>[
				'Label'=>'Last Name',
				'Type'=>'Text',
				'Filter'=>['PRF.LastName','LIKE']
			],
			'Domain'=>[
				'Label'=>'Domain',
				'Type'=>'Text',
				'Filter'=>['PPRF.Domain','LIKE']
			],			'Status'=>[
				'Label'=>'Status',
				'Type'=>'DropDown',
				'Filter'=>['PPRF.Status','LIKE'],
				'Config'=>['Data'=>['','Active','Inactive'],'UV'=>false]
			]
		];

		//List Headers
		$Data['Headers'] = [
			'ID'=>'{Row.ID}',
			'Name'=>'{Row.FirstName} {Row.LastName}',
			'Role'=>'{Row.RoleName}',
			'Status'=>'{Row.Status}'
		];
		$this->Model->UseRelation = ['Role'];
		$Data['SelectFields'] = 'PRF.ID,PRF.FirstName,PRF.LastName,PRF.Status,ROL.Name RoleName';
		$this->PrepareList($this->Model,$Data,$Page,true);
	}

	public function ChangePassword(){

		if(PS('Change')){
			$OldPassword = PS('OldPassword');
			$NewPassword = PS('NewPassword');
			$Password = PS('Password');
			if(Encrypt($OldPassword) == _E('{Profile.Password}',true)){
				if($NewPassword == $Password){
					$this->Model->Update($this->Profile->LoggedID(),['Password'=>Encrypt($Password)]);
					SetMessage('Password has been updated successfully','Success');
				}else{
					SetMessage('Retype passwor is not matched','Error');
				}
			}else{
				SetMessage('Old password did not matched','Error');
			}
			GoBack(false);
		}
		$this->View('Admin/Users/ChangePassword',['Title'=>'Change Password']);
	}

	public function AccountSettings(){
		if(PS('Save')){
			$this->SetValidationRules([
				['Name'=>'FirstName','Label'=>'First name','Rule'=>'required|trim'],
				['Name'=>'LastName','Label'=>'Last name','Rule'=>'required|trim']/*,
				['Name'=>'Email','Label'=>'Email','Rule'=>'required|trim|valid_email'],
				['Name'=>'RoleID','Label'=>'Access Role','Rule'=>'required|trim|numeric'],
				['Name'=>'Password','Label'=>'Password','Rule'=>'required|trim|min_length[4]|max_length[20]'],
				['Name'=>'Status','Label'=>'Status','Rule'=>'required|trim|alpha_numeric_spaces']*/
			]);
			if(!$this->form_validation->run()){
				return GoBack(false);
			}
			$this->ProcessPost([['FirstName','',true,['Title']],['LastName','',true,['Title']]],$Data);
			$this->Model->Update($this->Profile->LoggedID(),$Data);
			SetMessage('Account settings saved','Success');
			return GoBack(false);
		}
		$this->View('Users/AccountSettings',[]);
	}

	public function Login(){
		if($this->Profile->LoggedIn()){
			return Forword(URI(''));
		}
		$this->PartialView('Profiles/Login',['Title'=>'Please Login To Continue']);
	}

	public function GetFields($Edit=false,$Row=null){

		$this->Bundels->Script('PostScripts',['js/custom/registeragent.js']);

		return [
			[
				'Name'=>'FirstName',
				'Label'=>'First Name',
				'Type'=>'Text',
				'Config'=>[
					'Attributes'=>['class'=>'form-control','AA'=>['required']]
				],
				'Default'=>'{Row.FirstName}'
			],[
				'Name'=>'LastName',
				'Label'=>'Last Name',
				'Type'=>'Text',
				'Config'=>[
					'Attributes'=>['class'=>'form-control','AA'=>['required']]
				],
				'Default'=>'{Row.LastName}'
			],[
				'Name'=>'Email',
				'Label'=>'Email',
				'Type'=>'Email',
				'Config'=>[
					'Attributes'=>['class'=>'form-control','AA'=>['required']]
				],
				'Default'=>'{Row.Email}'
			],[
				'Name'=>'RoleID',
				'Label'=>'Access Role',
				'Type'=>'DropDown',
				'Config'=>[
					'Model'=>'Role',
					'Fields'=>['ROL.ID','ROL.Name'],
					'Display'=>'{Name}',
					'Attributes'=>['class'=>'form-control','AA'=>['required']],
				],
				'Default'=>'{Row.RoleID}'
			],[
				'Name'=>'Password',
				'Label'=>'Password',
				'Type'=>'Password',
				'Config'=>[
					'Attributes'=>['class'=>'form-control','AA'=>['required']],
				],
				'Default'=>$Edit ? Decrypt($Row->Password) : ''
			],[
				'Name'=>'Status',
				'Label'=>'Status',
				'Type'=>'DropDown',
				'Config'=>[
					'Attributes'=>['class'=>'form-control','AA'=>['required']],
					'Data'=>['Active','Inactive'],
					'UV'=>false
				],
				'Default'=>'{Row.Status}'
			]
		];
	}

	public function Action($Action,$PK=0){
		if($Action != ''){
			$Input = $this->input;
			if($Action == 'Add' || $Action == 'Edit'){

				$this->SetValidationRules([
					['Name'=>'FirstName','Label'=>'First name','Rule'=>'required|trim'],
					['Name'=>'LastName','Label'=>'Last name','Rule'=>'required|trim'],
					['Name'=>'Email','Label'=>'Email','Rule'=>'required|trim|valid_email'],
					['Name'=>'RoleID','Label'=>'Access Role','Rule'=>'required|trim|numeric'],
					['Name'=>'Password','Label'=>'Password','Rule'=>'required|trim|min_length[4]|max_length[20]'],
					['Name'=>'Status','Label'=>'Status','Rule'=>'required|trim|alpha_numeric_spaces']
				]);
				if(!$this->form_validation->run()){
					return GoBack();
				}
				$this->ProcessPost([['FirstName','',true,['Title']],['LastName','',true,['Title']],['Email'],['Password','',true,['Encrypt']],['RoleID'],['Status']],$Data);
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
			return Forword(AURI('Index'));
		}else{
			InvalidData();
		}
	}

	public function __destruct(){
		parent::__destruct();
	}
}

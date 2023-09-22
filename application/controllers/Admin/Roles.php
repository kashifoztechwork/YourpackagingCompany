<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Roles extends M_Default {

	public	$Required = ['Role'];

	public function __construct(){

		parent::__construct();
		$this->Model = $this->Role;
		$this->load->library('HierarchyGenerator',[]);
	}

	public function Index($Page=0){
		$this->CheckAccess('LIST');
		//Page Filters
		$Data['Filters'] = [
			'Name'=>[
				'Label'=>'Role Name',
				'Type'=>'Text',
				'Filter'=>['ROL.Name','LIKE']
			]
		];

		//List Headers
		$Data['Headers'] = [
			'ID'=>'{Row.ID}',
			'Role Name'=>'{Row.Name}'
		];
		$Data['SelectFields'] = 'ROL.Name,ROL.ID';
		$Data['Actions']['RIGHTS'] = ['Title'=>'Manage Right','Link'=>AURI('Rights/{Row.ID}')];
		$this->PrepareList($this->Model,$Data,$Page);
	}

	public function RolesMapping(int $ProjectID=0){
		$this->CheckAccess('ROLESMAPPING');
		$ProjectSelected = false;
		if($Project = $this->Project->Row($ProjectID)){
			$Data['Project'] = $Project;
			$Data['Roles'] = $Roles = $this->Model->GetList(['ROL.ProjectID'=>$Project->ID]);
			$ProjectSelected = true;
			$Data['CurrentMapping'] = CreateHierarchy(
				$Roles->Result,
				function($Item,$Childrens){
					$Out = ['id'=>$Item->ID,'content'=>$Item->Name];
					if(!empty($Childrens)){
						$Out['children'] = $Childrens;
					}
					return $Out;
				},'ParentID','ID',0,false
			);
		}
		$Data['ProjectSelected'] = $ProjectSelected;
		$Data['ProjectID'] = $ProjectID;
		$this->View('Roles/RolesMapping',$Data);
	}

	public function Rights(int $PK=0,$Type='Role'){
		$this->CheckAccess('RIGHTS');
		if($Type && $PK){
			$PK = intval($PK);
			$TypeKey = 'RoleID';
			switch($Type){
				case 'Role':
					$Row = $this->Model->Row($PK);
					$Data['Title'] = 'Manage Right For Role "{Row.Name}"';
				break;
				case 'Department':
					$Row = $this->Deparment->Row($PK);
					$Data['Title'] = 'Manage Department Rights of "{Row.Name}"';
					$TypeKey = 'DepartmentID';
				break;
				case 'Profile':
					$Row = $this->Profile->Row($PK);
					$Data['Title'] = 'Manage Department Rights of "{Row.FirstName} {Row.LastName}"';
					$TypeKey = 'ProfileID';
				break;
			}
		}

		if($Row){
			$Data['Controllers'] = $this->Module->GetNestedModules(false);
			$Data['Rights'] = $this->Access->Options([$TypeKey=>$Row->ID],'',['ModuleID',$TypeKey],'{ModuleID}',[],false);
			$Data['Row'] = $Row;
			$Data['Type'] = $Type;
			Def($Row,'Row');
			$this->View('Roles/Rights',$Data);
		}else{
			InvalidData();
		}
	}

	public function GetFields($Edit=false,$Row=null){

		return [
			[
				'Name'=>'Name',
				'Label'=>'Role Name',
				'Type'=>'Text',
				'Config'=>[
					'Attributes'=>['class'=>'form-control','AA'=>['required']]
				],
				'Default'=>'{Row.Name}'
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
				$this->ProcessPost([['Name']],$Data);
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
				case 'Rights':
					$this->CheckAccess('RIGHTS');
					//Getting PK and Type
					$PK = explode('-',$PK);
					$Type = $PK[1];
					$PK = intval($PK[0]);

					//Getting Selected Modules
					$Rights = array_map('intval',PS('Rights'));
					if(!empty($Rights)){
						//Get Current Type and Database Item
						$TypeKey = 'RoleID';
						$Row = NULL;
						switch($Type){
							case 'Role':
								$Row = $this->Model->Row($PK);
								$Message = 'Rights saved successfully for "{Row.Name}"';
							break;
							case 'Department':
								$Row = $this->Department->Row($PK);
								$TypeKey = 'DepartmentID';
								$Message = 'Rights saved successfully for "{Row.Name}"';
							break;
							case 'Profile':
								$Row = $this->Profile->Row($PK);
								$TypeKey = 'ProfileID';
								$Message = 'Rights saved successfully for "{Row.FirstName} {Row.LastName}"';
							break;
						}
						if($Row){
							Def($Row,'Row');
							//Processing Modules For Specific Type
							$Assign = [];
							foreach($Rights as $ModuleID){
								if($ModuleID > 0){
									$Assign[] = [
										$TypeKey=>$PK,
										'ModuleID'=>$ModuleID
									];
								}
							}
							if(!empty($Assign)){
								$this->Access->Delete([$TypeKey=>$Row->ID],true);
								if($this->Access->Insert($Assign,true)){
									SetMessage(_E($Message),'Success');
								}else{
									SetMessage('GENERALERROR');
								}
							}else{
								SetMessage('No module was selected properly','Warning');
							}
						}else{
							InvalidData();
						}
					}else{
						SetMessage('No module was selected','Warning');
					}
					return GoBack();
				break;
				case 'RolesMapping':
					$this->CheckAccess('ROLESMAPPING');
					$HG = new HierarchyGenerator(@json_decode(PS('Mapping',false)));
					$HG->Revert();
					$ProcessedData = $HG->GetProcessedData();
					if(!empty($ProcessedData)){
						if($this->Model->Update('ID',$ProcessedData,true)){
							SetMessage('Role mapping has been updated','Success');
						}else{
							SetMessage('ENTRYFAILED');
						}
					}else{
						InvalidData();
					}
					return GoBack();
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

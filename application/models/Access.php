<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Access extends M_Model{

	public	$RunInstaller = false,
			$DBTable = DB_ACCESS,
			$DBInstance = 'ACS',
			$InsertLogs = ['Title'=>'Access Granted','Description'=>'Access has been granted to {ProfileID} / {RoleID} of {ModuleID}'],
			$DeleteLogs = ['Title'=>'Access Deleted','Description'=>'Access removed of {ProfileID}'],
			$Loaded = NULL,
			$ByAccess = [],
			$ByMethod = [];
	public function __construct(){

		$this->ResetRelations();
		$this->SetRelation('Role','RoleID','ID',DB_ROLE,'ROL','LEFT');
		$this->SetRelation('Profile','ProfileID','ID',DB_PROFILE,'PRF','LEFT');
		$this->SetRelation('Module','ModuleID','ID',DB_MODULE,'MOD');
		parent::__construct();
	}

	public function LoadAll(){
		if($this->Profile->Admin){
			$this->Module->FetchTotal(false);
			$this->Loaded = $this->Module->GetList([],0,0,'MOD.Order ASC');
		}else{
			$this->DB()->group_start()->or_where(['ACS.RoleID'=>$this->ActiveProfile->RoleID,'ACS.ProfileID'=>$this->ActiveProfile->ID])->group_end()->
			join(_E('{(DB_MODULE)} {P}MOD'),_E('MOD.ID IN(SELECT ID FROM `{P}{(DB_MODULE)}` WHERE `ModuleID` IS NULL UNION SELECT {P}ACS.ModuleID)'),'INNER')->
			group_by('MOD.ID');
			$this->Loaded = $this->GetList($this->Module->DefaultClouse,0,0,'MOD.Order ASC','MOD.*');
		}
		if($this->Loaded->Rows > 0){
			foreach($this->Loaded->Result as $Row){
				$this->ByMethod[$Row->Controller][$Row->Method] = $Row;
				$this->ByAccess[$Row->Controller][$Row->AccessModifier] = true;
			}
		}
	}

	public function HaveAccess(string $Modifiers){

		//All Granted To Admin
		if($this->Profile->Admin)return true;

		//Checking Access Further
		$Modifiers = explode(',',$Modifiers);
		if(!empty($Modifiers) && isset($this->ByAccess[$this->Controller])){
			foreach($Modifiers as $Modifier){
				if(isset($this->ByAccess[$this->Controller][$Modifier]) && $this->ByAccess[$this->Controller][$Modifier]){
					return true;
				}
			}
		}else{
			return false;
		}
	}

	public function Installation(){

		if(DEBUG){
			$this->CreateTable([
				'Table'=>$this->DBTable,
				'Fields'=>[
					[
						'Key'=>'L',
						'Name'=>'ProfileID',
						'Length'=>255,
						'Type'=>'int',
						'NULL'=>true
					],[
						'Key'=>'L',
						'Name'=>'RoleID',
						'Length'=>255,
						'Type'=>'int',
						'NULL'=>false
					],[
						'Key'=>'L',
						'Name'=>'ModuleID',
						'Length'=>255,
						'Type'=>'int',
						'NULL'=>false
					]
				]
			]);
		}
	}
}

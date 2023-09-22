<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Profile extends M_Model{

	public	$RunInstaller = false,
			$DBTable = DB_PROFILE,
			$DBInstance = 'PRF',
			$InsertLogs = ['Title'=>'New User\'s Profile','Description'=>'New user profile information has been added'],
			$UpdateLogs = ['Title'=>'User\'s Profile Updated ','Description'=>'Changes has been made to user\'s profile'],
			$DeleteLogs = ['Title'=>'Profile Removed','Description'=>'User\'s profile has been removed'],
			$CookieName = 'AGT',
			$CookieKey = 'PID',
			$Cookie = true,
			$Admin = false;
			
	public function __construct(){

		$this->ResetRelations();
		$this->SetRelation('Role','RoleID','ID',DB_ROLE,'ROL');
		parent::__construct();
	}


	public function IRow($Clouse,$Order='',$SelectFields=''){
		$this->UseRelation = ['Role'];
		return parent::IRow($Clouse,$Order,$SelectFields ? $SelectFields : 'PRF.*,ROL.Name RoleName');
	}

	public function Login($Clouse){
		$Row = $this->Row($Clouse);
		if($Row){
			$this->StoreInfo(array($this->CookieKey=>$Row->{$this->PK}));
			return $Row;
		}else{
			return false;
		}
	}

	public function GetLogged(){
		if($this->LoggedIn()){
			$Logged = $this->IRow($this->LoggedID());
			if($Logged->RoleID == 1)$this->Admin = true;
			return $Logged;
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
						'Name'=>'FirstName',
						'Length'=>50,
						'Type'=>'varchar',
						'NULL'=>false
					],[
						'Key'=>'L',
						'Name'=>'LastName',
						'Length'=>50,
						'Type'=>'varchar',
						'NULL'=>false
					],[
						'Key'=>'U',
						'Name'=>'Email',
						'Length'=>50,
						'Type'=>'varchar',
						'NULL'=>true
					],[
						'Key'=>'L',
						'Name'=>'Password',
						'Length'=>2555,
						'Type'=>'varchar',
						'NULL'=>false
					],[
						'Key'=>'L',
						'Name'=>'Phone',
						'Length'=>15,
						'Type'=>'varchar',
						'NULL'=>false
					],[
						'Key'=>'L',
						'Name'=>'Status',
						'Length'=>50,
						'Type'=>'varchar',
						'NULL'=>true
					],[
						'Key'=>'L',
						'Name'=>'RoleID',
						'Length'=>255,
						'Type'=>'int',
						'NULL'=>true
					]
				]
			]);
		}
	}
}

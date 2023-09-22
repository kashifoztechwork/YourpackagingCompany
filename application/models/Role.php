<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Role extends M_Model{

	public	$RunInstaller = false,
			$DBTable = DB_ROLE,
			$DBInstance = 'ROL',
			$InsertLogs = ['Title'=>'New Role Created','Description'=>'New role has been added in database'],
			$UpdateLogs = ['Title'=>'Role Updated','Description'=>'Role information has been updated'],
			$DeleteLogs = ['Title'=>'Role Removed','Description'=>'Role has been remove from database'];
	public function __construct(){

		$this->ResetRelations();
		$this->SetRelation('ParentRoles','ParentID','ID',DB_ROLE,'PROL');
		parent::__construct();
	}

	public function Installation(){

		if(DEBUG){
			$this->CreateTable([
				'Table'=>$this->DBTable,
				'Fields'=>[
					[
						'Key'=>'L',
						'Name'=>'Name',
						'Length'=>70,
						'Type'=>'varchar',
						'NULL'=>false
					],[
						'Key'=>'L',
						'Name'=>'ParentID',
						'Length'=>255,
						'Type'=>'int',
						'NULL'=>true
					]
				]
			]);
		}
	}
}

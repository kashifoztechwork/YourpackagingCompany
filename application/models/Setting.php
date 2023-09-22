<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Setting extends M_Model{

	public	$RunInstaller = false,
			$DBTable = DB_SETTING,
			$DBInstance = 'SET',
			$InsertLogs = ['Title'=>'New Settings','Description'=>'New Settings has been added'],
			$UpdateLogs = ['Title'=>'Settings Modified','Description'=>'Settings has been made'],
			$DeleteLogs = ['Title'=>'Settings Removed','Description'=>'Settings has been removed'];

	public function __construct(){

		$this->ResetRelations();
		parent::__construct();
	}

	public function Installation(){

		if(DEBUG){
			$this->CreateTable([
				'Table'=>$this->DBTable,
				'Fields'=>[
					[
						'Key'=>'L',
						'Name'=>'Project',
						'Length'=>70,
						'Type'=>'varchar',
						'NULL'=>false
					],[
						'Key'=>'L',
						'Name'=>'Logo',
						'Length'=>0,
						'Type'=>'text',
						'NULL'=>true
					],[
						'Key'=>'L',
						'Name'=>'Config',
						'Length'=>0,
						'Type'=>'text',
						'NULL'=>true
					]
				]
			]);
		}
	}
}

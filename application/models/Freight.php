<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Freight extends M_Model{

	public	$RunInstaller = false,
			$DBTable = DB_FREIGHT,
			$DBInstance = 'FRE',
			$InsertLogs = ['Title'=>'New Freight Forworder','Description'=>'New Freight Forworder {Name} has been added'],
			$UpdateLogs = ['Title'=>'Freight Forworder Modified','Description'=>'Changes has been made to {Name}'],
			$DeleteLogs = ['Title'=>'Freight Forworder Removed','Description'=>'Freight Forworder has been removed'];

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
						'Key'=>'U',
						'Name'=>'Name',
						'Length'=>70,
						'Type'=>'varchar',
						'NULL'=>false
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

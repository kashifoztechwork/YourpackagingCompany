<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Navigation extends M_Model{

	public	$RunInstaller = false,
			$DBTable = DB_NAVIGATION,
			$DBInstance = 'NAV',
			$InsertLogs = ['Title'=>'New Navigation','Description'=>'New Navigation {Name} has been added'],
			$UpdateLogs = ['Title'=>'Navigation Modified','Description'=>'Changes has been made to {Name}'],
			$DeleteLogs = ['Title'=>'Navigation Removed','Description'=>'Navigation has been removed'];

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
						'Name'=>'Name',
						'Length'=>70,
						'Type'=>'varchar',
						'NULL'=>false
					],
					[
						'Key'=>'L',
						'Name'=>'Link',
						'Length'=>255,
						'Type'=>'varchar',
						'NULL'=>false
					],
					[
						'Key'=>'L',
						'Name'=>'Position',
						'Length'=>11,
						'Type'=>'int',
						'NULL'=>false
					],
					[
						'Key'=>'L',
						'Name'=>'Visible',
						'Length'=>0,
						'Type'=>'bool',
						'NULL'=>true
					],
					[
						'Key'=>'L',
						'Name'=>'Parent',
						'Length'=>11,
						'Type'=>'int',
						'NULL'=>false
					]
				]
			]);
		}
	}
}

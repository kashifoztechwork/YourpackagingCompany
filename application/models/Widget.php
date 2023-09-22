<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Widget extends M_Model{

	public	$RunInstaller = false,
			$DBTable = DB_WIDGET,
			$DBInstance = 'WGT',
			$EnableLogs = false;

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
						'Length'=>120,
						'Type'=>'varchar',
						'NULL'=>false
					],
					[
						'Key'=>'L',
						'Name'=>'Page',
						'Length'=>24,
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
						'Name'=>'Status',
						'Length'=>0,
						'Type'=>'bool',
						'NULL'=>true
					],
					[
						'Key'=>'L',
						'Name'=>'Config',
						'Length'=>11,
						'Type'=>'int',
						'NULL'=>false
					]
				]
			]);
		}
	}
}

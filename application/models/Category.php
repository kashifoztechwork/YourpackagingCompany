<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Category extends M_Model{

	public	$RunInstaller = false,
			$DBTable = DB_CATEGORY,
			$DBInstance = 'CAT',
			$InsertLogs = ['Title'=>'New Category','Description'=>'New Category {Name} has been added'],
			$UpdateLogs = ['Title'=>'Category Modified','Description'=>'Changes has been made to {Name}'],
			$DeleteLogs = ['Title'=>'Category Removed','Description'=>'Category has been removed'];

	public function __construct(){

		$this->ResetRelations();
		
		$this->CustomJoins('TagDetails',[
			['Table'=>DB_TAG,'INS'=>'TAG','ForignKey'=>'TAG.TypeID','HomeKey'=>'CAT.ID','JoinType'=>'INNER']
		]);

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
					],
					[
						'Key'=>'L',
						'Name'=>'Type',
						'Length'=>20,
						'Type'=>'varchar',
						'NULL'=>false
					],[
						'Key'=>'L',
						'Name'=>'Image',
						'Length'=>0,
						'Type'=>'text',
						'NULL'=>true
					],[
						'Key'=>'L',
						'Name'=>'Config',
						'Length'=>0,
						'Type'=>'text',
						'NULL'=>true
					],
					[
						'Key'=>'L',
						'Name'=>'IsIndex',
						'Length'=>0,
						'Type'=>'bool',
						'NULL'=>true
					],
					[
						'Key'=>'L',
						'Name'=>'Featured',
						'Length'=>0,
						'Type'=>'bool',
						'NULL'=>true
					]
				]
			]);
		}
	}
}

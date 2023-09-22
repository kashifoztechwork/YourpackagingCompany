<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Review extends M_Model{

	public	$RunInstaller = false,
			$DBTable = DB_REVIEW,
			$DBInstance = 'REV',
			$InsertLogs = ['Title'=>'New Review','Description'=>'New Review {Name} has been added'],
			$UpdateLogs = ['Title'=>'Review Modified','Description'=>'Changes has been made to {Name}'],
			$DeleteLogs = ['Title'=>'Review Removed','Description'=>'Review has been removed'];

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
						'Name'=>'Email',
						'Length'=>70,
						'Type'=>'varchar',
						'NULL'=>false
					],
					[
						'Key'=>'L',
						'Name'=>'Stars',
						'Length'=>11,
						'Type'=>'int',
						'NULL'=>false
					],
					[
						'Key'=>'L',
						'Name'=>'Comment',
						'Length'=>0,
						'Type'=>'text',
						'NULL'=>true
					],
					[
						'Key'=>'L',
						'Name'=>'Product',
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
					]
				]
			]);
		}
	}
}

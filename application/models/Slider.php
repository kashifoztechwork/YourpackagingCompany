<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Slider extends M_Model{

	public	$RunInstaller = false,
			$DBTable = DB_SLIDER,
			$DBInstance = 'SLD',
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
						'Name'=>'Link',
						'Length'=>255,
						'Type'=>'varchar',
						'NULL'=>false
					],
					[
						'Key'=>'L',
						'Name'=>'Image',
						'Length'=>0,
						'Type'=>'text',
						'NULL'=>true
					]
				]
			]);
		}
	}
}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Image extends M_Model{

	public	$RunInstaller = false,
			$DBTable = DB_IMAGE,
			$DBInstance = 'IMG',
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
						'Length'=>70,
						'Type'=>'varchar',
						'NULL'=>false
					],[
						'Key'=>'L',
						'Name'=>'Image',
						'Length'=>0,
						'Type'=>'text',
						'NULL'=>false
					]
				]
			]);
		}
	}
}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Contact extends M_Model{

	public	$RunInstaller = false,
			$DBTable = DB_CONTACT,
			$DBInstance = 'CONT',
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
						'Name'=>'Email',
						'Length'=>255,
						'Type'=>'varchar',
						'NULL'=>false
					],[
						'Key'=>'L',
						'Name'=>'Phone',
						'Length'=>18,
						'Type'=>'varchar',
						'NULL'=>false
					],[
						'Key'=>'L',
						'Name'=>'Subject',
						'Length'=>100,
						'Type'=>'varchar',
						'NULL'=>false
					],[
						'Key'=>'L',
						'Name'=>'Message',
						'Length'=>0,
						'Type'=>'text',
						'NULL'=>false
					]
				]
			]);
		}
	}
}

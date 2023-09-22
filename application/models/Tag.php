<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Tag extends M_Model{

	public	$RunInstaller = false,
			$DBTable = DB_TAG,
			$DBInstance = 'TAG',
			$InsertLogs = ['Title'=>'New Tags','Description'=>'New Tags has been added'],
			$UpdateLogs = ['Title'=>'Tags Modified','Description'=>'Changes has been made'],
			$DeleteLogs = ['Title'=>'Tags Removed','Description'=>'Tags has been removed'];

	public function __construct(){

		$this->ResetRelations();
		parent::__construct();
	}

	public function SetTags(string $Type, int $TypeID, array $Data){
		$Data['Type'] = $Type;
		$Data['TypeID'] = $TypeID;
		if($ID = $this->Insert($Data)){
			return $ID;
		} else {
			return false;
		}
	}

	public function Installation(){

		if(DEBUG){
			$this->CreateTable([
				'Table'=>$this->DBTable,
				'Fields'=>[
					[
						'Key'=>'L',
						'Name'=>'Name',
						'Length'=>75,
						'Type'=>'varchar',
						'NULL'=>true
					],
					[
						'Key'=>'L',
						'Name'=>'Title',
						'Length'=>255,
						'Type'=>'varchar',
						'NULL'=>false
					],[
						'Key'=>'L',
						'Name'=>'Description',
						'Length'=>255,
						'Type'=>'varchar',
						'NULL'=>false
					],[
						'Key'=>'L',
						'Name'=>'Keywords',
						'Length'=>255,
						'Type'=>'varchar',
						'NULL'=>false
					],[
						'Key'=>'L',
						'Name'=>'Type',
						'Length'=>70,
						'Type'=>'varchar',
						'NULL'=>false
					],[
						'Key'=>'L',
						'Name'=>'TypeID',
						'Length'=>11,
						'Type'=>'int',
						'NULL'=>false					
					],[
						'Key'=>'L',
						'Name'=>'IsIndex',
						'Length'=>'0',
						'Type'=>'bool',
						'NULL'=>false
					],
					[
						'Key'=>'L',
						'Name'=>'SchemaTag',
						'Length'=>'0',
						'Type'=>'text',
						'NULL'=>true
					]
				]
			]);
		}
	}
}

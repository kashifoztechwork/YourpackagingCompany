<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class EntityData extends M_Model{

	public	$RunInstaller = false,
			$DBTable = DB_DATA,
			$DBInstance = 'OPS',
			$InsertLogs = ['Title'=>'New Data','Description'=>'New Data base been added "{Entity}"'],
			$UpdateLogs = ['Title'=>'','Description'=>''],
			$DeleteLogs = ['Title'=>'Data Deleted','Description'=>'Data deleted for "{Entity}"'];

	public function __construct(){

		$this->ResetRelations();
		parent::__construct();
	}

	public function GetData(string $Entity){
		$List = $this->GetList(['Entity'=>$Entity],0,0,'','Key,Value,PK,Entity');
		$Data = [];
		if($List->Rows > 1){
			foreach($List->Result as $Item){
				$Data[$Item->PK][$Item->Key] = $Item;
			}
		}
		return $Data;
	}

	public function Installation(){

		if(DEBUG){
			$this->CreateTable([
				'Table'=>$this->DBTable,
				'Fields'=>[
					[
						'Key'=>'L',
						'Name'=>'Entity',
						'Length'=>70,
						'Type'=>'varchar',
						'NULL'=>false
					],[
						'Key'=>'L',
						'Name'=>'Key',
						'Length'=>200,
						'Type'=>'varchar',
						'NULL'=>true
					],[
						'Key'=>'L',
						'Name'=>'Value',
						'Length'=>200,
						'Type'=>'varchar',
						'NULL'=>true
					],[
						'Key'=>'L',
						'Name'=>'PK',
						'Length'=>255,
						'Type'=>'int',
						'NULL'=>false
					]
				]
			]);
		}
	}
}

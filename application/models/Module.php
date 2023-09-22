<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Module extends M_Model{

	public	$RunInstaller = false,
			$DBTable = DB_MODULE,
			$DBInstance = 'MOD',
			$InsertLogs = ['Title'=>'New Module Controller','Description'=>'New module has been added from controller {Controller} with method {Method} and has access modifier of {AccessModifier}'],
			$UpdateLogs = ['Title'=>'Module Updated','Description'=>'Module information has been updated'],
			$DeleteLogs = ['Title'=>'Module Removed','Description'=>'Module has been deleted'],
			$Loaded = NULL;

	public function __construct(){

		$this->ResetRelations();
		$this->SetRelation('ParentModules','ModuleID','ID',DB_MODULE,'PMOD','LEFT');
		parent::__construct();
	}

	public function IGetList($Clouse=[],$Limit=0,$Offset=0,$Order='MOD.ID DESC',$SelectFields=''){

		$this->UseRelation = ['ParentModules'];
		return $this->GetList($Clouse,$Limit,$Offset,$Order,$SelectFields ? $SelectFields : 'MOD.ID,MOD.Title,MOD.Slug,MOD.ModuleID,PMOD.Title ParentModule,MOD.Order');
	}

	public function GetNestedModules($Visible = true){
		$Nested = [];
		$Ins =& get_instance();
		if($this->Access->Loaded->Rows > 0){
			foreach($this->Access->Loaded->Result as $Row){
				if(($Visible && $Row->Visible) || !$Visible){
					$Nested[$Row->ModuleID][] = $Row;
				}
			}
		}
		return $Nested;
	}

	public function Installation(){

		if(DEBUG){
			$this->CreateTable([
				'Table'=>$this->DBTable,
				'Fields'=>[
					[
						'Key'=>'L',
						'Name'=>'Title',
						'Length'=>70,
						'Type'=>'varchar',
						'NULL'=>false
					],[
						'Key'=>'L',
						'Name'=>'Controller',
						'Length'=>70,
						'Type'=>'varchar',
						'NULL'=>true
					],[
						'Key'=>'L',
						'Name'=>'Method',
						'Length'=>70,
						'Type'=>'varchar',
						'NULL'=>true
					],[
						'Key'=>'L',
						'Name'=>'Slug',
						'Length'=>70,
						'Type'=>'varchar',
						'NULL'=>true
					],[
						'Key'=>'L',
						'Name'=>'AccessModifier',
						'Length'=>70,
						'Type'=>'varchar',
						'NULL'=>true
					],[
						'Key'=>'L',
						'Name'=>'Order',
						'Length'=>5,
						'Type'=>'int',
						'NULL'=>false
					],[
						'Key'=>'L',
						'Name'=>'ModuleID',
						'Length'=>255,
						'Type'=>'int',
						'NULL'=>true
					],[
						'Key'=>'L',
						'Name'=>'Visible',
						'Length'=>0,
						'Type'=>'bool',
						'NULL'=>true
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

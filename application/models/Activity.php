<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Activity extends M_Model{

	public	$RunInstaller = false,
			$DBTable = DB_ACTIVITY,
			$DBInstance = 'ACT',
			$EnableLogs = false;
	public function __construct(){

		$this->ResetRelations();
		$this->SetRelation('Profilers','ProfilerID','ID',DB_PROFILE,'PRF','LEFT');
		parent::__construct();
	}

	public function SetActivity($Title,$Description,$Entity,$EntityID){
		//Preapring Data
		if(!empty($Title) && !empty($Description)){
			$Data = [
				'Title'=>Title($Title),
				'Description'=>trim($Description,' '),
				'Entity'=>$Entity,
				'EntityID'=>$EntityID
			];

			//If Any User is Logged in While Acitivty
			if($this->Profile->LoggedIn()){
				$Data['ProfilerID'] = $this->Profile->LoggedID();
			}
			return $this->Insert($Data);
		}else{
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
						'Name'=>'Title',
						'Length'=>70,
						'Type'=>'varchar',
						'NULL'=>false
					],[
						'Key'=>'L',
						'Name'=>'Description',
						'Length'=>0,
						'Type'=>'text',
						'NULL'=>false
					],[
						'Key'=>'L',
						'Name'=>'Entity',
						'Length'=>50,
						'Type'=>'varchar',
						'NULL'=>false
					],[
						'Key'=>'L',
						'Name'=>'EntityID',
						'Length'=>255,
						'Type'=>'int',
						'NULL'=>false
					],[
						'Key'=>'L',
						'Name'=>'ProfilerID',
						'Length'=>255,
						'Type'=>'int',
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

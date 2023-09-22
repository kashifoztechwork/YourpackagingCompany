<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Logs extends M_Model{

	public	$RunInstaller = false,
			$DBTable = DB_LOG,
			$DBInstance = 'LOG',
			$EnableLogs = false;
	public function __construct(){

		$this->ResetRelations();
		$this->SetRelation('Users','ProfileID','ID',DB_PROFILE,'PRF');
		parent::__construct();
		$this->load->library('user_agent');
	}

	public function SetLog($Dump,$Data){

		$Data += [
			'ProfileID'=>$this->Profile->LoggedIn() ? $this->Profile->LoggedID() : 0,
			'Platform'=>$this->agent->platform(),
			'Reference'=>serialize(['Refferer'=>$this->agent->is_referral() ? $this->agent->referrer() : CurrentURI(),'Current'=>CurrentURI()]),
			'Browser'=>$this->agent->browser(),
			'RawData'=>serialize($Dump)
		];
		return $this->Insert($Data);
	}

	public function Installation(){

		if(DEBUG){
			$this->CreateTable([
				'Table'=>$this->DBTable,
				'Fields'=>[
					[
						'Key'=>'L',
						'Name'=>'Title',
						'Length'=>100,
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
						'Name'=>'Reference',
						'Length'=>0,
						'Type'=>'text',
						'NULL'=>false
					],[
						'Key'=>'L',
						'Name'=>'Platform',
						'Length'=>100,
						'Type'=>'varchar',
						'NULL'=>true
					],[
						'Key'=>'L',
						'Name'=>'Browser',
						'Length'=>100,
						'Type'=>'varchar',
						'NULL'=>true
					],[
						'Key'=>'L',
						'Name'=>'ProfileID',
						'Length'=>255,
						'Type'=>'int',
						'NULL'=>true
					],[
						'Key'=>'L',
						'Name'=>'EntityType',
						'Length'=>100,
						'Type'=>'varchar',
						'NULL'=>true
					],[
						'Key'=>'L',
						'Name'=>'EntityID',
						'Length'=>255,
						'Type'=>'int',
						'NULL'=>true
					],[
						'Key'=>'L',
						'Name'=>'EntityClouse',
						'Length'=>0,
						'Type'=>'text',
						'NULL'=>true
					],[
						'Key'=>'L',
						'Name'=>'RawData',
						'Length'=>0,
						'Type'=>'text',
						'NULL'=>true
					]
				]
			]);
		}
	}
}

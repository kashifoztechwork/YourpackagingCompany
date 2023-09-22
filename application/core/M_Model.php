<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_Model extends CI_Model{

	public	$DBTable = '',
			$InsertActivityFields = [],
			$UpdateActivityFields = [],
			$DeleteActivityFields = [],
			$InsertLogs = ['Title'=>'Unknow','Description'=>'Unknown'],
			$UpdateLogs = ['Title'=>'Unknow','Description'=>'Unknown'],
			$DeleteLogs = ['Title'=>'Unknow','Description'=>'Unknown'],
			$HoldTransaction = false,
			$PK = 'ID',
			$GetTotal = false,
			$RunInstaller = false,
			$ActiveDB = NULL,
			$DatabaseEngine = 'InnoDB',
			$UseRelation = [],
			$DBInstance = '',
			$EnableLogs = true,
			$ApplyDefaultClouse = true,
			$UseJoins = [],
			$CookieName = '',
			$CookieKey = '',
			$Cookie = false,
			$DefaultClouse = [];

	private $_DBTable = '',
			$_ActiveDB = NULL,
			$LastTransactionFailed,
			$DefaultFields = [],
			$Relations = [],
			$JoinsRelations = [];

	public function __construct(){

		//parent::__construct();
		$this->ResetActiveTable();
		$this->SetActiveDB();

		//Setting Up Database Settings
		$this->DB()->trans_strict(true);

		//Setting Up Default Activity Fields
		$this->InsertActivityFields = [
			'EntryDate'=>DATETIMENOW,
			'EntryIP'=>MYIP
		];
		$this->UpdateActivityFields = [
			'EntryUpdateDate'=>DATETIMENOW,
			'EntryUpdateIP'=>MYIP
		];
		$this->DeleteActivityFields = [
			'EntryDeletedDate'=>DATETIMENOW,
			'EntryDeletedIP'=>MYIP,
			'Deleted'=>1
		];

		//Calling Install Method If It Needs To
		if($this->RunInstaller){
			//Default Fields For Database Installation
			$this->DefaultFields = [
				 [
					'Key'=>'P',
					'Name'=>'ID',
					'Length'=>255,
					'Type'=>'int',
					'NULL'=>false
				],
				[
					'Key'=>'L',
					'Name'=>'EntryDate',
					'Length'=>0,
					'Type'=>'datetime',
					'NULL'=>true
				],
				[
					'Key'=>'L',
					'Name'=>'EntryUpdateDate',
					'Length'=>0,
					'Type'=>'datetime',
					'NULL'=>true
				],
				[
					'Key'=>'L',
					'Name'=>'EntryIP',
					'Length'=>15,
					'Type'=>'varchar',
					'NULL'=>false
				],
				[
					'Key'=>'L',
					'Name'=>'EntryUpdateIP',
					'Length'=>15,
					'Type'=>'varchar',
					'NULL'=>true
				],
				[
					'Key'=>'L',
					'Name'=>'Deleted',
					'Length'=>0,
					'Type'=>'bool',
					'NULL'=>true
				],
				[
					'Key'=>'L',
					'Name'=>'EntryDeletedDate',
					'Length'=>0,
					'Type'=>'datetime',
					'NULL'=>true
				],
				[
					'Key'=>'L',
					'Name'=>'EntryDeletedIP',
					'Length'=>15,
					'Type'=>'varchar',
					'NULL'=>true
				]
			];
			$this->Installation();
		}

		$this->SetDefaultClouse([$this->Mask('Deleted')=>NULL]);
	}

	public function SetDefaultClouse($Clouse){
		$this->DefaultClouse = array_merge($this->DefaultClouse,$this->GetClouse($Clouse));
	}

	public function SetActiveDB($OtherDB = NULL){
		if($OtherDB == NULL){
			$this->_ActiveDB = $this->db;
		}else{
			if($this->ActiveDB == NULL){
				$this->ActiveDB = $this->db;
			}
			$this->_ActiveDB = $this->ActiveDB;
		}
	}

	public function DB(){

		return $this->_ActiveDB;
	}

	public function ChangeActiveTable($NewTable){

		$this->_DBTable = $NewTable;
	}

	public function ResetActiveTable(){
		$this->_DBTable = $this->DBTable;
	}

	/*
		Insert New Data Into Database
	*/
	public function Insert($Data,$Batch=false){
		//Preparing Data Before insert
		try{
			$this->Prepare($Data,ACTIVITY_INSERT,$Batch);
		
			//Initiating Database Transaction
			$this->StartTransaction();
			if($Batch){
				$this->DB()->insert_batch($this->_DBTable,$Data);
			}else{
				$this->DB()->insert($this->_DBTable,$Data);
			}
			$LastPK = $this->DB()->insert_id();
			//Ending Transaction
			$this->EndTransaction();
			$this->LogActivity($Data,ACTIVITY_INSERT,$LastPK,$Batch);
			return $LastPK;
		}catch(Exception $E){

			$this->Faliure($E,ACTIVITY_INSERT);
			return false;
		}
	}

	/*
		Update Record From Database BAse on Clouse
	*/
	public function Update($Clouse,$Data,$Batch=false,$EveryLog=true){

		//Preparing Data Before insert
		try{
			$LogClouse = $Clouse;
			$Clouse = $Batch ? $Clouse : $this->GetClouse($Clouse);
			$this->Prepare($Data,ACTIVITY_UPDATE,$Batch);
			//Initiating Database Transaction
			$this->StartTransaction();
			if($Batch){
				$Update = $this->DB()->update_batch($this->_DBTable,$Data,$Clouse);
			}else{
				$this->ApplyClouse($Clouse);
				$Update = $this->DB()->update($this->_DBTable,$Data);
			}
			//Ending Transaction
			$this->EndTransaction();
			if($EveryLog)$this->LogActivity($Data,ACTIVITY_UPDATE,$LogClouse,$Batch);
			return $Update;
		}catch(Exception $E){

			$this->Faliure($E,ACTIVITY_UPDATE);
			return false;
		}
	}

	/*
		Delete Record From Database or Update Deleted Activity
	*/
	public function Delete($Clouse,$System=false){
		if(!empty($Clouse)){
			if($System){
				try{
					//Initiating Database Transaction
					$this->StartTransaction();
					$this->ApplyClouse($Clouse);
					$this->DB()->delete($this->_DBTable/*,$this->GetClouse($Clouse)*/);
					//Ending Transaction
					$this->EndTransaction();
					$this->LogActivity([],ACTIVITY_DELETE,$Clouse,true);
					return true;
				}catch(Exception $E){

					$this->Faliure($E,ACTIVITY_DELETE);
					return false;
				}
			}else{
				if($this->Update($Clouse,$this->Prepare($Data,ACTIVITY_DELETE),false,false)){
					$this->LogActivity($Data,ACTIVITY_DELETE,$Clouse,true);
					return true;
				}else{
					return false;
				}
			}
		}else{
			return false;
		}
	}

	public function FetchTotal($Bool){
		if($Bool){
			$this->DB()->select('SQL_CALC_FOUND_ROWS \'Rows\'',false);
		}
		$this->GetTotal = $Bool;
	}

	/*
		Fetch The List of Result in Current Database Table
	*/
	public function GetList($Clouse=[],$Limit=0,$Offset=0,$Order='',$SelectFields=''){

		try{
			//Setting Up Parameters
			{
				$Clouse = $this->GetClouse($Clouse,true);
				//Merging Default Clouse
				if($this->ApplyDefaultClouse && !empty($this->DefaultClouse)){
					$Clouse = array_merge($Clouse,$this->DefaultClouse);
				}
				$this->ApplyClouse($Clouse);

				//Apply Limit
				if($Limit > 0){
					$this->DB()->limit($Limit);
				}

				//Apply Offset
				if($Offset > 0){
					$this->DB()->offset($Offset);
				}

				//Apply Order
				if(!empty($Order)){
					$this->DB()->order_by($Order);
				}

				if(!empty($SelectFields)){
					$this->DB()->select($SelectFields);
				}else if(!$this->GetTotal){
					$this->DB()->select('*');
				}
			}

			//Setting Joins With Related Tables
			if(!empty($this->UseRelation)){
				foreach($this->UseRelation as $ConstraintName){
					$ConstraintName = sprintf('%s_%s',$this->DBInstance,$ConstraintName);
					if(isset($this->Relations[$ConstraintName])){
						$Relation = $this->Relations[$ConstraintName];
						extract($Relation);
						$this->DB()->join(
							sprintf('%s {P}%s',$ForignTable,$ForignInstance),
							sprintf('%s = %s.%s',$this->Mask($FieldName),$ForignInstance,$ForignKeyName),
							$JoinType
						);
					}
				}
				$this->UseRelation = [];
			}

			//Loading Custom Joins IF Required
			if(!empty($this->UseJoins)){
				$Defaults = ['Table'=>'','INS'=>'','ForignKey'=>'','HomeKey'=>$this->Mask($this->PK),'JoinType'=>'LEFT','Clouse'=>[]];
				foreach($this->UseJoins as $Use){
					if(array_key_exists($Use,$this->JoinsRelations)){
						$Joins = $this->JoinsRelations[$Use];
						foreach($Joins as $Join){
							$Join = array_merge($Defaults,$Join);
							$JoinClouse = [sprintf('%s = %s',$Join['HomeKey'],$Join['ForignKey'])];
							//$JoinClouse['EQUAL'][$Join['HomeKey']] = $Join['ForignKey'];

							if(!empty($Join['Clouse'])){
								foreach($Join['Clouse'] as $ClouseType=>$ClouseData){
									foreach($ClouseData as $Key=>$Value){
										switch($ClouseType){
											case 'EQUAL':
												$JoinClouse[] = sprintf('%s = %s',$Key,$Value);
											break;
											case 'NOTEQUAL':
												$JoinClouse[] = sprintf('%s != %s',$Key,$Value);
											break;
											case 'NULL':
												$JoinClouse[] = sprintf('%s IS NULL',$Value);
											break;
											case 'NOTNULL':
												$JoinClouse[] = sprintf('%s IS NOT NULL',$Value);
											break;
											case 'IN':
												$JoinClouse[] = sprintf('%s IN(%s)',$Key,implode(', ',$Value));
											break;
											case 'NOTIN':
												$JoinClouse[] = sprintf('%s NOT IN(%s)',$Key,implode(', ',$Value));
											break;
										}
									}
									//$JoinClouse[] = sprintf('%s = %s',$Field,$Value);
								}
							}
							$this->DB()->join(
								sprintf('%s {P}%s',$Join['Table'],$Join['INS']),
								//sprintf('%s = %s%s',$Join['HomeKey'],$Join['ForignKey'],$JoinClouse),
								implode(' AND ',$JoinClouse),
								$Join['JoinType']
							);
						}
					}
				}
				$this->UseJoins = [];
			}
			
			$Set = $this->DB()->get(sprintf('%s %s',$this->_DBTable,$this->DB()->dbprefix($this->DBInstance)));
			$Total = 0;
			if($this->GetTotal){
				$TotalCacheName = sprintf('%s-%s',$this->router->class,$this->router->method);
				$Total = $this->Query(sprintf('SELECT FOUND_ROWS() \'%s\'',$TotalCacheName))->row()->{$TotalCacheName};
			}
			
			return (object)[
				'Rows'=>$Set->num_rows(),
				'Result'=>$Set->result(),
				'Total'=>$Total
			];
		}catch(Exception $E){
			$this->Faliure($E,ACTIVITY_SELECT);
		}
	}

	/*
		Exceptional Overrideable Method
	*/
	public function IGetList($Clouse=[],$Limit=0,$Offset=0,$Order='',$SelectFields=''){
		if($Order == ''){
			$Order = sprintf('%s DESC',$this->Mask('ID'));
		}
		return $this->GetList($Clouse,$Limit,$Offset,$Order,$SelectFields);
	}

	public function ResetRelations(){

		//self::$Relations = [];
	}

	public function SetRelation($ConstraintName,$FieldName,$ForignKeyName,$ForignTable,$ForignInstance,$JoinType='INNER'){

		$this->Relations[sprintf('%s_%s',$this->DBInstance,$ConstraintName)] = [
			'FieldName'=>$FieldName,
			'ForignKeyName'=>$ForignKeyName,
			'ForignTable'=>$ForignTable,
			'JoinType'=>$JoinType,
			'ForignInstance'=>$ForignInstance
		];
	}

	public function CustomJoins($SetName,$Definations){
		if(!empty($Definations)){
			isset($this->JoinsRelations[$SetName]) ? $this->JoinsRelations[$SetName] += $Definations : $this->JoinsRelations[$SetName] = $Definations;
		}
	}

	/*
		Count Total Result in Current Table Based on Clouse
	*/
	public function Count($Clouse){
		$this->ApplyClouse($Clouse);
		return $this->DB()->count_all_results($this->_DBTable);
	}

	/*
		Fetch Only One Record From
		Database Table Basae on Clouse
	*/
	public function Row($Clouse,$Order='',$SelectFields=''){
		$Items = $this->GetList($Clouse,1,0,$Order,$SelectFields);
		if($Items->Rows > 0){
			return $Items->Result[0];
		}else{
			return false;
		}
	}

	/*
		Instance Method Of Row
	*/
	public function IRow($Clouse,$Order='',$SelectFields=''){
		return $this->Row($Clouse,$Order,$SelectFields);
	}

	/*
		Fetch Fields For DropDown
	*/
	public function Options($Clouse=[],$Order='',$Fields=[],$Display='',$Uses=[],$FirstEmpty=true,$GroupBy='',$CallBack=''){

		//Adding Use
		if(!empty($Uses)){
			$this->UseRelation = $Uses;
			$this->UseJoins = $Uses;
		}
		//Fetching List From Database
		$List = $this->GetList($this->GetClouse($Clouse),0,0,$Order,implode(',',$Fields));

		//Processing Display
		$Out = [];
		if($FirstEmpty){
			$Out[NULL] = 'Please Select';
		}
		$FirstField = '';
		foreach($List->Result as $Item){
			$Value = $Display;
			//Getting Field Names
			foreach($Item as $FieldName=>$FieldValue){

				if($FirstField == ''){
					$FirstField = $FieldName;
				}
				$Value = str_replace(sprintf('{%s}',$FieldName),$FieldValue,$Value);
				if($CallBack && function_exists($CallBack)){
					$Value = $CallBack($Value);
				}
			}
			if($GroupBy != ''){
				$Out[$Item->{$GroupBy}][$Item->{$FirstField}] = $Value;
			}else{
				$Out[$Item->{$FirstField}] = $Value;
			}
		}
		return $Out;
	}

	/*
		Apply Clouse Process To Current DB Instance
	*/
	private function ApplyClouse($Clouse=[]){

		$Clouse = is_array($Clouse) ? $Clouse : $this->GetClouse($Clouse);
		//Apply Clouse
		if(!empty($Clouse)){
			//Processing Clouse
			foreach($Clouse as $ClouseField=>$ClouseValue){
				if(is_array($ClouseValue)){
					$this->DB()->where_in($ClouseField,$ClouseValue);
				}else{
					$this->DB()->where($ClouseField,$ClouseValue);
				}
			}
		}
	}

	/*
		Return Valid Array Clouse
		IF PK is Passed Then Clouse Changed To Array
	*/
	private function GetClouse($Clouse,$Mask=false){

		if(!is_array($Clouse)){
			return [$Mask ? $this->Mask($this->PK) : $this->PK => $Clouse];
		}
		return $Clouse;
	}

	public function Mask($Field){
		return sprintf('%s.%s',$this->DBInstance,$Field);
	}

	/*
		Run Database Query
		Return Database Set Resource
	*/
	public function Query($Query){
		return $this->DB()->query($Query);
	}

	/*
		Simple No Escape Fields Query
	*/
	public function IQuery($Query){
		return $this->DB()->simple_query($Query);
	}

	/*
		Truncate Database Table
	*/
	public function TruncateTable(){

		return $this->DB()->truncate($this->_DBTable);
	}

	/*
		Database Action Helper
		To Prepare Default Fields Data
	*/
	private function Prepare(&$Data,$Activity,$Batch=false){
		$CurrentFields = [];
		switch($Activity){
			case ACTIVITY_INSERT:
				$CurrentFields = $this->InsertActivityFields;
			break;
			case ACTIVITY_UPDATE:
				$CurrentFields = $this->UpdateActivityFields;
			break;
			case ACTIVITY_DELETE:
				$CurrentFields = $this->DeleteActivityFields;
			break;
		}
		if(!empty($CurrentFields)){
			if($Batch){
				if(!empty($Data)){
					foreach($Data as $Key=>$Value){
						$Data[$Key] = array_merge((array)$Value,$CurrentFields);
					}
				}
			}else{
				return $Data = array_merge((array)$Data,$CurrentFields);
			}
		}
	}

	/*
		Log Current Activty To Database
	*/
	private function LogActivity($DataDump,$Activity,$PK,$BulkItem){
		if(!empty($DataDump) && !$this->LastTransactionFailed && $this->EnableLogs){
			$Defination = [];
			switch($Activity){
				case ACTIVITY_INSERT:
					$Defination = $this->InsertLogs;
					break;
				case ACTIVITY_UPDATE:
					$Defination = $this->UpdateLogs;
					break;
				case ACTIVITY_DELETE:
					$Defination = $this->DeleteLogs;
					break;
			}
			if(!empty($Defination)){
				$Defination += [
					'EntityID' => is_array($PK) ? 0 : $PK,
					'EntityClouse' => is_array($PK) ? serialize($PK) : '',
					'EntityType'=>get_class($this)
				];
				$this->Logs->SetLog($DataDump,$Defination);
			}
		}
	}

	private function Faliure($Exception,$FailedActivity){

		$this->EndTransaction();
		$this->LastTransactionFailed = true;
	}

	private function StartTransaction(){

		$this->LastTransactionFailed = false;
		$this->DB()->trans_start();
	}

	private function EndTransaction(){

		if($this->DB()->trans_status() == false){
			$this->DB()->trans_rollback();
			$this->LastTransactionFailed = true;
		}else{
			$this->DB()->trans_commit();
		}
	}

	/*
		Return Boolen According To Configuration
		Only Run On Debug Mode
		@$Config->Table
		@$Config->Fields
		@$Config->Table
		@$this->DefaultFields Automaticy Merge With @Config
	*/
	public function CreateTable($Config){

		$this->ResetCache();
		extract($Config);
		$Table = $this->DB()->dbprefix($Table);
		//Load Fields Defination
		if(isset($Table) && is_array($Fields)){

			$Fetch = [];
			if(is_array($Fetch)){

				//Merge Default Fields With Given Fields
				$Fields = array_merge($this->DefaultFields,$Fields);
				$Exists = $this->TableExists($Table);
				//If Table Exists Then Update/Alter Else Create Table
				if($Exists){
					//All Fields Should Have ID, Added Date and Update Date Field
					foreach($Fields as $Field){
						if(is_array($Field) && $Field['Key'] != 'P'){

							if($this->FieldExists($Field['Name'],$Table)){
								$Fetch[] = sprintf('CHANGE %s',$this->ProcessField($Field,true));
							}else{
								$Fetch[] = sprintf('ADD (%s)',$this->ProcessField($Field));
							}
						}
					}
					//Getting Forign Relations
					$Relations = $this->Relations;
					if(!empty($Relations)){
						foreach($Relations as $ConstraintName=>$Relation){
							$this->DropForignKey($ConstraintName,$Table);
							$Fetch[] = sprintf('ADD CONSTRAINT `%s` FOREIGN KEY (`%s`) REFERENCES `%s`(`%s`)',$ConstraintName,$Relation['FieldName'],$this->DB()->dbprefix($Relation['ForignTable']),$Relation['ForignKeyName']);
						}
					}
					$Query = sprintf(
						'ALTER TABLE `%s` %s',
						$Table,
						implode(', ',$Fetch)
					);
					$this->Query($Query);
				}else{
					foreach($Fields as $Field){

						$Fetch[] = $this->ProcessField($Field);
					}

					//Getting Forign Relations
					$Relations = $this->Relations;
					if(!empty($Relations)){
						foreach($Relations as $ConstraintName=>$Relation){
							$Fetch[] = sprintf('CONSTRAINT `%s` FOREIGN KEY (`%s`) REFERENCES `%s`(`%s`)',$ConstraintName,$Relation['FieldName'],$this->DB()->dbprefix($Relation['ForignTable']),$Relation['ForignKeyName']);
						}
					}

					$Query = sprintf(
						'CREATE TABLE `%s`(%s) ENGINE=%s',
						$Table,
						@implode(',',$Fetch),
						$this->DatabaseEngine
					);
					$this->Query($Query);
				}
			}
		}
	}

	/*Private Helper Method For CreateTable()*/
	private function ProcessField($Field,$Change=false){

		extract($Field);
		$Primery = 'AUTO_INCREMENT PRIMARY KEY';
		$Name = ucwords($Name);
		if($Change){
			return sprintf(
				'`%s`%s %s%s %s %s',
				$Name,
				sprintf(' `%s`',$Name),
				$Type,
				$Length ? sprintf('(%s)',$Length) : NULL,
				$NULL ? 'NULL' : 'NOT NULL',
				$Key == 'P' ? $Primery : ($Key == 'U' ? 'UNIQUE' : NULL)
			);
		}else if(!$Change){

			return sprintf(
				'`%s`%s %s%s %s %s',
				$Name,
				NULL,
				$Type,
				$Length ? sprintf('(%s)',$Length) : NULL,
				$NULL ? 'NULL' : 'NOT NULL',
				$Key == 'P' ? $Primery : ($Key == 'U' ? 'UNIQUE' : NULL)
			);
		}
	}

	/*
		Retrun Boolen Wheather Table Exists or Not
		According To Standerd
	*/
	public function TableExists($Table){

		return $this->Query(sprintf('SHOW TABLES LIKE \'%s\'',$Table))->num_rows();
	}

	/*
		Retrun Boolen Wheather Field Exists or Not
		According To Standerd
	*/
	public function FieldExists($Field,$Table){

		$Query = sprintf('SHOW COLUMNS FROM  `%s` LIKE \'%s\'',$Table,$Field);
		$Query = $this->Query($Query);
		return $Query->num_rows();
	}

	/*
		Return Boolen
		DB Table Remove
	*/
	public function DropTable($Table){

		return $this->dbforge->drop_table($Table);
	}

	/*
		Return Boolen
		DB Field Remove
	*/
	public function DropField($Field,$Table){

		return $this->dbforge->drop_column($Table,$Field);
	}

	/*
		Remove Forign Key From Table
	*/
	public function DropForignKey($ConstraintName,$Table){
		return $this->Query(sprintf('ALTER TABLE `%s` DROP FOREIGN KEY IF EXISTS `%s`;',$Table,$ConstraintName));
	}

	/*
		Return Booen
		DB Rename Table
	*/
	public function RenameTable($OldName,$NewName){

		return $this->dbforge->rename_table($OldName,$NewName);
	}

	public function ResetCache(){
		$this->DB()->cache_delete_all();
	}

	public function __destruct(){

		$this->EndTransaction();
	}

	/*
		Return Cookey Key Value Saved
	*/
	public function LoggedID(){

		if($this->LoggedIn())return $this->GetStored($this->CookieKey);
		else return false;
	}

	/*
		Return Wheather CookieKey Set or Not
	*/
	public function LoggedIn(){

		return $this->GetStored($this->CookieKey);
	}

	/*
		Return Bool
		Store Info In Cookie
	*/
	public function StoreInfo($Info){
		if(!empty($Info)){
			if($this->Cookie){
				$Info = serialize($Info);
				set_cookie($this->CookieName,$Info,1440*1440);
			}else{
				$this->session->set_userdata($this->CookieName,$Info);
			}
			return true;
		}else{
			return false;
		}
	}

	/*
		Fetching Stored Data
	*/
	public function GetStored($Key){

		if($this->Cookie){
			$Data = unserialize(get_cookie($this->CookieName));
		}else{
			$Data = $this->session->userdata($this->CookieName);
		}
		if(!empty($Data) && isset($Data[$Key])){
			return $Data[$Key];
		}else{
			return false;
		}
	}

	/*
		Removeing Cookie Data
	*/
	public function Logout(){

		if($this->Cookie){
			delete_cookie($this->CookieName);
		}else{
			$this->session->unset_userdata($this->CookieName);
		}
	}
}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class HierarchyGenerator{

	private $Mapped = [],
			$UnMapped = [],
			$Data = [],
			$ProcessedData = [],
			$ProcessingData = [];
	public	$MappingKey,
			$ParentKey = 'ParentID',
			$PrimaryKey = 'ID';
	public function __construct(array $Data,string $MappingKey='Children'){
		$this->Data = $Data;
		$this->ProcessingData = $Data;
		$this->MappingKey = $MappingKey;
	}

	public function Generate(){}
	public function Revert($ParentValue=0){
		if(!empty($this->ProcessingData)){
			foreach($this->ProcessingData as $Item){
				$Item = !is_array($Item) ? ToArray($Item) : $Item;
				if(isset($Item[$this->MappingKey]) && !empty($Item[$this->MappingKey])){
					$this->ProcessingData = $Item[$this->MappingKey];
					unset($Item[$this->MappingKey]);
					$this->Revert($Item[$this->PrimaryKey]);
				}
				$Item[$this->ParentKey] = $ParentValue == 0 ? NULL : $ParentValue;
				$this->ProcessedData[] = $Item;
			}
		}
	}

	public function GetProcessedData(){
		return $this->ProcessedData;
	}
}

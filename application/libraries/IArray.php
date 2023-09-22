<?php
	if(!defined('BASEPATH'))exit('No direct script access allowed');
	/*
		Custom Arrays
	*/
	class IArray{
		private $Data = [];
		public	$LastElements = [];

		public function __construct(array $Data){
			$this->Data = $Data;

			$this->PrepareLastElements($Data);
		}

		private function PrepareLastElements(array $Data){

			foreach($Data as $Name=>$Value){
				if(is_array($Value)){
					$this->PrepareLastElements($Value);
				}else{
					$this->LastElements[$Name] = $Value;
				}
			}
		}
	}

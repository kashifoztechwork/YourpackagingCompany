<?php
	if(!defined('BASEPATH'))exit('No direct script access allowed');
	/*
		Messages Processor
	*/
	class Messages{

		private static	$WarningMessages = [],
						$SuccessMessages = [],
						$ErrorMessages = [],
						$InfoMessages = [],
						$Templates = [
							'ENTRYSUCCESS'=>['Entry saved successfully','Success'],
							'ENTRYFAILED'=>['An error occurred while saving entry, please try again later','Error'],
							'ENTRYDELETED'=>['Entry has been deleted successfully','Warning'],
							'ENTRIESDELETED'=>['One or more entries has been deleted successfully','Warning'],
							'GENERALERROR'=>['General error occurred while processing data, please contact administrator','Error'],
							'ITEMEXISTS'=>['Another item already existsi with same information','Warning'],
							'DATAINVALID'=>['Invald data has been passed','Warning'],
							'VALIDATIONERROR'=>['One or more validation errors has been occurred','Warning'],
							'MAXPAGES'=>['You\'v reached to maximum result pages','Warning']
						];
		private $Instance;
		public function __construct(){

			$this->Instance =& get_instance();
			$this->Instance->{get_class($this)} = $this;
		}

		public static function SetMessage($Message,$Type){
			if($Message != ''){
				//IF Template
				$Templates = self::$Templates;
				if(isset($Templates[$Message])){
					$Type = $Templates[$Message][1];
					$Message = $Templates[$Message][0];
				}

				switch($Type){
					case 'Success':
						self::$SuccessMessages[] = $Message;
					break;
					case 'Warning':
						self::$WarningMessages[] = $Message;
					break;
					case 'Error':
						self::$ErrorMessages[] = $Message;
					break;
					case 'Info':
						self::$InfoMessages[] = $Message;
					break;
				}
				return true;
			}else{
				return false;
			}
		}

		public function GetMessages($Type=''){

			$Types = ['SuccessMessages'=>'Success','WarningMessages'=>'Warning','ErrorMessages'=>'Error','InfoMessages'=>'Info'];
			switch($Type){
				case 'Success':
					$Types = ['SuccessMessages'=>'Success'];
				break;
				case 'Warning':
					$Types = ['WarningMessages'=>'Warning'];
				break;
				case 'Error':
					$Types = ['ErrorMessages'=>'Error'];
				break;
				case 'Info':
					$Types = ['InfoMessages'=>'Info'];
				break;
			}

			$Out = [];
			foreach($Types as $Container=>$FinalType){
				$Messages = self::$$Container;
				if(!empty($Messages)){
					foreach($Messages as $Message){
						$Out[] = TemplateMessage($Message,$FinalType);
					}
				}
			}
			return $Out;
		}

		public function Push(){
			foreach(['SuccessMessages','WarningMessages','ErrorMessages','InfoMessages'] as $Container){
				$Data = self::$$Container;
				if(!empty($Data)){
					$this->Instance->session->set_flashdata($Container,serialize($Data));
				}
			}
		}

		public function Fetch(){
			foreach(['SuccessMessages','WarningMessages','ErrorMessages','InfoMessages'] as $Container){
				$Data = $this->Instance->session->flashdata($Container);
				if(!empty($Data)){
					$Data = unserialize($Data);
					if(is_array($Data)){
						self::$$Container = $Data;
					}
				}
			}
			//die();
		}
	}

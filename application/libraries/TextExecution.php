<?php
	if(!defined('BASEPATH'))exit('No direct script access allowed');
	/*
		Execute Code in Text as Templateing Engine
	*/
	class TextExecution{

		//Regex For Predefined Tactics
		private	$Tactics = array(
			'Simple'=>'/\{([0-9A-Za-z._]+)\}/ix',
			'Template'=>'/\{\[([0-9A-Za-z._]+)\]\}/ix',
			'Globals'=>'/\{\%([0-9A-Za-z._]+)\%\}/ix',
			'Codes'=>'/\\{\((.*?)\)\}/ix'
		);

		public function __construct(){

			$Ins =& get_instance();
			$Ins->{get_class($this)} = $this;
		}

		/*
			Execute Given Template @CMD
		*/
		public function CMD($CMD){
			extract($this->Tactics);
			//Code Global Tactics
			if(preg_match_all($Codes,$CMD,$Code)){
				$Item = array();
				foreach($Code[1] as $Var){
					$Code = sprintf('@$Item[\'{(%s)}\'] = %s;',str_replace('\'','\\\'',$Var),$Var);
					eval($Code);
					$CMD = str_replace(array_keys($Item),array_values($Item),$CMD);
				}
			}
			//Find Text Tactic
			$Found = array();
			if(preg_match_all($Simple,$CMD,$Text)){
				foreach($Text[1] as $Var){
					if(isset($GLOBALS[$Var])){
						$Found[sprintf('{%s}',$Var)] = @$GLOBALS[$Var];
					}
				}
			}
			//Find Template Tactic
			if(preg_match_all($Template,$CMD,$Tmpl)){
				foreach($Tmpl[1] as $Var){
					$Template = preg_replace('/[.]/ix','/',$Var);
					$Found[sprintf('[%s]',$Var)] = $this->load->view($Template,array(),true);
				}
			}
			//Find Global Tactics
			if(preg_match_all($Globals,$CMD,$Global)){
				foreach($Global[1] as $Var){
					$Path = sprintf('[\'%s\']',preg_replace('/[.]/ix','\'][\'',$Var));
					$Code = sprintf('@$Found[\'%%%s%%\'] = $GLOBALS%s;',$Var,$Path);
					eval($Code);
				}
			}
			//IF has array in result
			foreach($Found as $Key=>$Value){
				if(is_array($Value)){
					return $Value;
				}
			}
			return str_replace(array_keys($Found),array_values($Found),$CMD);
		}
	}

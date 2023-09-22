<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Bundles{

	private $Styles = [],
			$Scripts = [],
			$PostSnippet = [],
			$StyleSnippet = [];

	public function Script($Section,$Srcs,$Custom=false,$Attributes=[]){

		foreach($Srcs as $Src){
			$Attributes['type'] = 'text/javascript';
			$Attributes['src'] = $Custom ? $Src : RURI($Src);
			$Item = _Tag('script',false,'',$Attributes);
			$this->Scripts[$Section][] = $Item;
		}
	}

	public function Style($Section,$Hrefs,$Custom=false,$Attributes=[]){

		foreach($Hrefs as $Href){
			$Attributes['href'] = $Custom ? $Href : RURI($Href);
			$Attributes['rel'] = 'stylesheet';
			$Attributes['type'] = 'text/css';
			$Item = _Tag('link',true,'',$Attributes);
			$this->Styles[$Section][] = $Item;
		}
	}

	public function Render($Section,$Type){
		$Out = [];
		foreach($Type == 'JS' ? ['Scripts','PostSnippet'] : ['Styles','StyleSnippet'] as $Var){
			if(isset($this->{$Var}[$Section])){
				$Out[] = implode('',$this->{$Var}[$Section]);
				$this->{$Var}[$Section] = [];
			}
		}
		return implode('',$Out);
	}

	public function Snippet($Section,$Data){
		$this->PostSnippet[$Section][] = $Data;
	}

	public function StyleSnippet($Section,$Data){
		$this->StyleSnippet[$Section][] = $Data;
	}
}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function CSRF(){
	$Ins =& get_instance();
	$Out = [];
	if($Ins->BackTo){
		$Out[] = _Input('BackTo',Reffered(),'hidden');
	}
	$Out[] = _Input(_CSRF(),_CSRF_Hash(),'hidden');
	return implode('',$Out);
}

/*
	Covert Array Attributes To
	String HTML Attributes
*/
function Attributes($Attributes){
	if(!empty($Attributes)){
		$Out = [];
		foreach($Attributes as $Name=>$Attrs){
			if(is_array($Attrs)){
				$Out[] = @implode(' ',$Attrs);
			}else{
				$Out[] = sprintf('%s="%s"',strtolower($Name),$Attrs);
			}
		}
		return sprintf(' %s',implode(' ',$Out));
	}else{
		return NULL;
	}
}
/*
	Return Formed Tag Against @Name @Data and @Attribution
	And Figure Out That Tag Is Inline Or Not
*/
function _Tag($Tag,$Inline=false,$Data='',$Attributes=[]){

	$Tag  = strtolower($Tag);
	$Close = $Inline ? '/' : sprintf('</%s',$Tag);
	$Open = $Inline ? '' : '>';
	//Before After
	$Before = '';
	$After = '';
	if(isset($Attributes['Before'])){
		$Before = $Attributes['Before'];
		unset($Attributes['Before']);
	}
	if(isset($Attributes['After'])){
		$After = $Attributes['After'];
		unset($Attributes['After']);
	}
	//If Form Field
	if(isset($Attributes['name']) && !@$Attributes['value']){
		$Attributes['value'] = PS(_E($Attributes['name']));
	}
	return sprintf('%s<%s%s%s%s%s>%s',$Before,$Tag,Attributes($Attributes),$Open,$Inline ? NULL : $Data,$Close,$After);
}

/*
	HTML Anchor Tag For Quick Use
*/
function _A($Data,$Link,$Attributes=[],$Follow=true,$Trailing=false){

	$Attributes['href'] = $Trailing ? sprintf('%s/',$Link) : sprintf('%s',$Link);
	if(!$Follow)$Attributes['rel'] = 'nofollow';
	return _Tag('A',false,$Data,$Attributes);
}

function _CA($Data,$Controller,$Action,$Attributes){

	return _A($Data,URI(sprintf('%s/%s',$Controller,$Action)),$Attributes,false);
}

/*
	Form Helper Form's Input Field Determined Agaist @Type
*/
function _Input($Name,$Value='',$Type='text',$Attributes=[],$DefaultValue=''){

	$Attributes['name'] = $Name;
	$Attributes['value'] = $Value;
	$Attributes['type'] = $Type;
	if(_E($DefaultValue) != '' && _E($DefaultValue) == _E($Value)){
		$Attributes['AA'][] = 'checked';
	}
	return _Tag('input',true,'',$Attributes);
}

function _Button($Name,$Data='',$Attributes=[]){
	$Attributes['name'] = $Name;
	if(!isset($Attributes['value']))$Attributes['value'] = time();
	if(!isset($Attributes['type']))$Attributes['type'] = 'submit';
	return _Tag('button',false,$Data,$Attributes);
}

/*
	Form Helper Form Textarea
*/
function _TextArea($Name,$Data='',$Attributes=[]){

	$Attributes['name'] = $Name;
	return _Tag('textarea',false,$Data,$Attributes);
}

/*
	Form Helper For Dropdown Form Field
*/
function _DropDown($Name,$Value='',$Options=[],$Attributes=[],$UV=true){

	$Attributes['name'] = $Name;
	$Data = GenrateOption($Options,is_array($Value) ? $Value : _E($Value),$UV);
	return _Tag('select',false,$Data,$Attributes);
}

/*
	DropDown Helper For Genrating
	Options Attributes
*/
function GenrateOption($Data,$Default,$UV){
	$Out = [];
	if(is_object($Default)){
		$Default = ToArray($Default);
	}
	foreach($Data as $Key=>$Value){
		//IF Value is an Array Then Make it Group
		if(is_array($Value)){
			$Out[] = _Tag('optgroup',false,GenrateOption($Value,$Default,$UV),['label'=>$Key]);
		}else{
			//Setting Up Option Value
			$Attributes = ['value'=>$UV ? $Key : $Value];
			if($Default){
				//Check Selected Value From @Default
				if(is_array($Default)){
					$Selected = $UV ? in_array($Key,$Default) : in_array($Value,$Default);
				}else{
					$Selected = $UV ? $Key == $Default : $Value == $Default;
				}
				$Array = ToArray(@json_decode($Default));
				if(is_array($Array) && !empty($Array)){
					$Selected = $UV ? in_array($Key,$Array) : in_array($Value,$Array);
				}
				if($Selected)$Attributes['selected'] = 'selected';
			}
			$Out[] = _Tag('option',false,$Value,$Attributes);
		}
	}
	return implode('',$Out);
}

/*
	Generate Fields With Given Template
*/
function GenerateFields($Fields){
	if(!empty($Fields)){
		$Ins =& get_instance();
		$Post = $Ins->input;
		$Out = [];
		foreach($Fields as $Field){
			if(!isset($Field['Type']))$Field['Type'] = 'Text';
			extract($Field);
			$Config = isset($Config) ? $Config : DEFAULT_FIELD_CONFIG;
			$Value = isset($Field['Default']) ? _E($Default,true) : null;
			if(PS($Name)){
				$Value = PS($Name);
			}
			$Input = null;
			$PostMessage = isset($Ins->Errors[$Name]) ? $Ins->Errors[$Name] : '';
			//Adding Plceholder
			if(!isset($Config['Attributes']['placeholder']))$Config['Attributes']['placeholder'] = $Label;
			switch($Type){
				case 'CheckBox':
				case 'Radio':
					if(isset($Config['Data'])){
						$InputFields = [];
						$SingleTemplate = isset($Config['SingleTemplate']) ? $Config['SingleTemplate'] : '';
						$SingleTemplate = $SingleTemplate == '' ? SINGLE_CHECKBOX_TEMPLATE : $SingleTemplate;
						foreach($Config['Data'] as $OptionValue=>$Option){
							$Attributes = $Config['Attributes'];
							$Attributes['class'] = 'form-check-input';
							if((is_array($Value) && in_array($OptionValue,$Value)) ||  $OptionValue == $Value){
								$Attributes['AA'] = ['checked'];
							}
							$InputFields[] = str_replace(['{SingleField}','{Label_Text}'],[_Input($Name,$OptionValue,strtolower($Type),$Attributes),$Option],$SingleTemplate);
						}
						$Input = implode('',$InputFields);
					}
				break;
				case 'TextArea':
					$Input = _TextArea($Name,$Value,$Config['Attributes']);
				break;
				case 'DropDown':
					//Prparing Data
					$Data = [];
					if(isset($Config['Data'])){
						$Data = $Config['Data'];
					}else if(isset($Config['Model'])){
						$Model  = $Config['Model'];
						//Fetching Data From Database
						$Ins->load->model($Model);
						$Data = $Ins->{$Model}->Options(isset($Config['Clouse']) ? $Config['Clouse'] : [],isset($Config['Order']) ? $Config['Order'] : '',$Config['Fields'],$Config['Display'],isset($Config['Uses']) ? $Config['Uses'] : [],isset($Config['FirstEmpty']) ? $Config['FirstEmpty'] : true,isset($Config['GroupBy']) ? $Config['GroupBy'] : '',isset($Config['CallBack']) ? $Config['CallBack'] : '');
					}
					$Input = _DropDown($Name,$Value,$Data,$Config['Attributes'],isset($Config['UV']) ? $Config['UV'] : true);
				break;
				default:
					$Input = _Input($Name,$Value,strtolower($Type),$Config['Attributes']);
				break;
			}

			//No Template for hidden
			if($Type == 'Hidden'){
				$Out[] = $Input;
			}else{
				//Preparing Default Templates
				if(!isset($Config['LabelTemplate']))$Config['LabelTemplate'] = DEFAULT_LABEL_TEMPLATE;
				if(!isset($Config['Template']))$Config['Template'] = DEFAULT_INPUT_TEMPLATE;
				if(!isset($Config['MessageTemplate']))$Config['MessageTemplate'] = DEFAULT_FIELD_MESSAGE_TEMPLATE;

				$LabelTemplate = null;
				$MessageTemplate = null;
				if($Label) $LabelTemplate = $Config['LabelTemplate'];
				if($PostMessage) $MessageTemplate = $Config['MessageTemplate'];

				$Input = str_replace(['{Field}','{Label}','{Message}'],[$Input,$LabelTemplate,$MessageTemplate],$Config['Template']);
				$Out[] = str_replace(['{Label_Text}','{Message_Text}','{Name}'],[$Label,$PostMessage,$Name],$Input);
			}
		}
		return implode('',$Out);
	}
}

function FilterFields($Filters){
	if(!empty($Filters)){
		$Fields = [];
		foreach($Filters as $Name=>$Filter){
			$Config = isset($Filter['Config']) ? $Filter['Config'] : [];
			$Config = array_merge_recursive(['Attributes'=>['class'=>'form-control']],$Config);
			!isset($Config['Template']) ? $Config['Template'] = COL4 : NULL;
			$Fields[] = [
				'Name'=>sprintf('F[%s]%s',$Name,isset($Filter['Multiple']) && $Filter['Multiple'] ? '[]' : ''),
				'Type'=>$Filter['Type'],
				'Label'=>$Filter['Label'],
				'Default'=>isset($Filter['Default']) ? $Filter['Default'] : sprintf('{Filters.%s}',$Name),
				'Config'=>$Config
			];
		}
		return $Fields;
	}
}

function LocalImage($Image,$Size='',$Attributes=[]){

	$Height = 'auto';
	$Width = 'auto';
	if($Size){
		$Size = !is_array($Size) ? explode(',',$Size) : $Size;
		if(count($Size) == 2){
			$Width = $Size[0];
			$Height = $Size[1];
		}
	}
	$Attributes['width'] = $Width;
	$Attributes['height'] = $Height;
	$Attributes['src'] = RURI(sprintf('images/%s',$Image));
	return _Tag('img',true,'',$Attributes);
}

function LocalImagePath($Image){
	return RURI(sprintf('images/%s',$Image));
}

function GetImage(string $Date,string $Config,array $Attributes=[],$Link=false,$Multiple=false){
	$Config = _E($Config,true);
	if (!is_array($Config) && !is_object($Config)) {
		$Config = unserialize($Config);
	}
	$Date = _E($Date,true);
	if(!empty($Config) && is_array($Config)){
		if(!isset($Config['file_name'])){
			if($Multiple){
				$Images = [];
				foreach($Config as $Key=>$ImageConfig){
					$Images[$Key] = _GetImage($Date,$ImageConfig,$Attributes,$Link);
				}
				return $Images;
			}else{				
				$Image = _GetImage($Date,min($Config),$Attributes,$Link);
			}
		}else{
			$Image = _GetImage($Date,$Config,$Attributes,$Link);
		}
		return $Image;
	}else{
		return '<i>No Image</i>';
	}
}

function _GetImage(string $Date,array $Config,array $Attributes=[],$Link=false){

	$Src = RURI($Config['file_name'],$Date);//URI(sprintf('%s/%s',PathStructure(strtotime($Date),'IMG',false),$Config['file_name']));
	$Attributes['src'] = $Src;
	$Image = _Tag('img',true,'',$Attributes);
	if($Link){
		$Image = _A($Image,$Src,['target'=>'_blank']);
	}
	return $Image;
}

function TemplateItems($Items,string $Template,bool $Force=false){
	if(!empty($Items)){
		$Out = [];
		if(is_array($Items) && !$Force){
			foreach($Items as $Key=>$Item){
				$Out[] = str_replace(['{Item}','{Key}'],[_E($Item),$Key],$Template);
			}
		}else{
			$Items = _E($Items,true);
			if(is_array($Items) && !empty($Items)){
				foreach($Items as $Key=>$Item){
					Def($Item,'Item');
					$Out[] = _E(str_replace(['{Key}'],[$Key],$Template),true);
				}
			}else{
				return '';
			}
		}
		return implode('',$Out);
	}
	return '';
}

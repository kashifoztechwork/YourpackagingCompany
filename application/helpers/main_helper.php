<?php
defined('BASEPATH') OR exit('No direct script access allowed');
function SetActivity($Title,$Description,$Entity,$PK){
	$Ins =& get_instance();
	$Ins->Activity->SetActivity($Title,$Description,$Entity,$PK);
}

function SetComment($Comment,$Entity,$PK){
	$Ins =& get_instance();
	$Ins->Comment->SetComment($Comment,$Entity,$PK);
}
function _E($Text,$Strict=false){
	if(is_array($Text))return $Text;
	$Ins =& get_instance();
	$Result = $Ins->TextExecution->CMD($Text);
	if($Strict){
		//If CMD
		if(preg_match('/\{(.*)\}/ix',$Text)){
			//Noting Changed
			if($Result == $Text){
				$Result = '';
			}
		}
	}
	return $Result;
}

function _EC($Object,$CMD){
	$Object = unserialize(_E($Object,true));
	if($Object){
		return isset($Object[$CMD]) && $Object[$CMD] ? $Object[$CMD] : '';
	}
	return '';
}

//Define Object's All Variables
function Def($Data,$Name){
	if(is_string($Data)){
		@$Data = unserialize($Data);
	}
	if((is_object($Data) || is_array($Data)) && !empty($Data)){
		foreach($Data as $Item=>$Value){
			$Var = sprintf('%s.%s',$Name,$Item);
			global $$Var;
			$$Var = $Value;
			Def($Value,$Var);
		}
	}else{
		return false;
	}
}

function _XSS($Data){
	$INS =& get_instance();
	return $INS->security->xss_clean($Data);
}

function _FNS($FileObject,$Reletive=false){

	$INS =& get_instance();
	return $INS->security->sanitize_filename($FileObject,$Reletive);
}

function _CSRF(){
	$INS =& get_instance();
	return $INS->security->get_csrf_token_name();
}

function _CSRF_Hash(){
	$INS =& get_instance();
	return $INS->security->get_csrf_hash();
}

function URI($Item,$IncludePrefix=false){

	$Item = ltrim($Item,'/');
	if($IncludePrefix){
		$Item = sprintf('%s/%s',SPECIALURLPREFIX,_E($Item));
	}
	return _E(base_url($Item));
}

function AURI($Action,$Special=false){
	$INS =& get_instance();
	$Controller = $INS->router->class;
	return URI(sprintf('%s/%s',$Controller,$Action),$Special);
}

function CurrentURI(){
	return current_url();
}

function JSURI($Item){
	return URI(sprintf('%s%s',JS,ltrim($Item,'/')));
}

function CSSURI($Item){
	return URI(sprintf('%s%s',CSS,ltrim($Item,'/')));
}

function RURI($Item,$Stamp='',$Type='IMG'){
	$Item = ltrim($Item,'/');
	if($Stamp){
		$Item = sprintf('%s/%s',PathStructure(strtotime(_E($Stamp)),$Type,false),$Item);
	}
	return URI($Stamp ? $Item : sprintf('%s%s',RSRC,$Item));
}

/*
	Clean Any Special Character
	And Convert @Text To A Valid URL Slug
*/
function Slug($Text,$Replace='-',$From=' '){

	preg_match_all('/([a-zA-Z0-9 ]+)/',$Text,$Matches);
	$Text = implode($From,$Matches[1]);
	return $Text = rtrim(ltrim(strtolower(preg_replace('/[ ]++/ix',$Replace,$Text)),$Replace),$Replace);
}

/*
	Convert Any @Object Into Array Like
	->Key Into [Key]
*/
function ToArray($Object){

	if(is_array($Object))return $Object;
	if(is_object($Object)){

		$Return = array();
		foreach($Object as $Key=>$Item){
			$Return[$Key] = is_object($Item) || is_array($Item) ? ToArray($Item) : $Item;
		}
		return $Return;
	}
}

/*
	Convert Any @Array Into Object Like
	[Key] Into ->Key
*/
function ToObject($Array){

	if(is_object($Array))return $Array;
	if(is_array($Array)){

		$Return = (object)array();
		foreach($Array as $Key=>$Item){

			$Return->{$Key} = is_array($Item) ? ToObject($Item) : $Item;
		}
		return $Return;
	}
}

function GoBack($SavePost=true){
	$Ins =& get_instance();
	if($SavePost)$Ins->session->set_flashdata('TempPost',serialize($Ins->input->post()));
	//Setting Validation Messages
	$Errors = $Ins->form_validation->error_array();
	$BackTo = PS('BackTo');
	if(!empty($Errors)){
		SetMessage('VALIDATIONERROR');
		$BackTo = '';
	}
	$Ins->session->set_flashdata('ValidationErrors',serialize($Errors));
	//Check If Back To Isset
	return Forword($BackTo ? $BackTo : Reffered());
}

function Reffered(){
	$Ins =& get_instance();
	return $Ins->agent->referrer();
}

function Forword($URL){
	$Ins =& get_instance();
	$Ins->Messages->Push();
	return redirect($URL);
}

function SetMessage($Message,$Type=''){
	$Ins =& get_instance();
	$Ins->Messages::SetMessage($Message,$Type);
}

function TemplateMessage($Message,$Type){

	switch($Type){
		case 'Success':
			$Template = SUCCESS_MESSAGE_TEMPLATE;
		break;
		case 'Warning':
			$Template = WARNING_MESSAGE_TEMPLATE;
		break;
		case 'Error':
			$Template = ERROR_MESSAGE_TEMPLATE;
		break;
		case 'Info':
			$Template = INFO_MESSAGE_TEMPLATE;
		break;
		default:
			$Template = INFO_MESSAGE_TEMPLATE;
		break;
	}
	return str_replace(['{Message}','{Type}'],[$Message,$Type],$Template);
}

function IntOrNull($Value){
	$Value = intval($Value);
	if(!$Value > 0)return NULL;
	return $Value;
}

function BetweenClouse($Field,$From,$To){
	$From = date(DATEFORMAT,strtotime($From));
	$To = date(DATEFORMAT,strtotime($To));
	if($To >= $From){
		return sprintf('%s BETWEEN \'%s\' AND \'%s\'',$Field,$From,$To);
	}else{
		'';
	}
}

function Title($Title){
	return ucwords(strtolower(trim($Title,' ')));
}

function ExtractObject(&$Object){
	if(!empty($Object)){
		foreach($Object as $Key=>$Value){
			if($Value){
				$UnSerialized = @unserialize($Value);
				$Value = is_array($UnSerialized) ? $UnSerialized : $Value;
			}
			is_object($Object) ? $Object->{$Key} = $Value : $Object[$Key] = $Value;
		}
	}
}

function GetAgoTime($Time,$Decode=true){

	$Time = $Decode ? strtotime($Time) : $Time;
	$Periods = array('second', 'minute', 'hour', 'day', 'week', 'month', 'year', 'decade');
	$Lengths = array('60','60','24','7','4.35','12','10');
	$Now = time();
	$Difference = $Now - $Time;
	$AS = 'ago';
	for($I=0;$Difference >= $Lengths[$I] && $I < count($Lengths)-1; $I++){
		$Difference /= $Lengths[$I];
	}
	$Difference = round($Difference);
	if($Difference != 1){
		$Periods[$I] .= 's';
	}
   return sprintf('%s %s %s',$Difference,$Periods[$I],$AS);
}

function PS(string $Name='',$XSSClean=true){
	$Ins =& get_instance();
	if($Name){
		$Value = $Ins->input->post($Name,$XSSClean);
		if(is_array($Value)){
			return ArrayMapRecursive('trim',$Value);
		}else{
			return Clean($Value);
		}
	}else{
		return $Ins->input->post();
	}
}

function GS($Name,$XSSClean=true){
	$Ins =& get_instance();
	$Value = $Ins->input->get($Name,$XSSClean);
	if(is_array($Value)){
		return ArrayMapRecursive('trim',$Value);
	}else{
		return Clean($Value);
	}
}

function ArrayMapRecursive($Function,$Value){
	if(!empty($Value)){
		$NewValues = [];
		foreach($Value as $K=>$V){
			if(is_array($V)){
				$NewValues[$K] = ArrayMapRecursive($Function,$V);
				unset($Value[$K]);
			}
		}
		array_map($Function,$Value);
		return $NewValues + $Value;
	}
}

function Ajax(){
	$Ins =& get_instance();
	return $Ins->input->is_ajax_request();
}

function JsonOut($Data,$StatusCode,$Message=''){
	$Ins =& get_instance();
	$Ins->output->set_content_type('application/json');
	$Ins->output->set_status_header($StatusCode);
	if($Message){
		$Data['Message'] = $Message;
	}
	return json_encode($Data);
}

function DoUpload($Stamp,$Element,$Type='IMG',$Types='',$Config=[],$Crop=false){
	//Verify Directory Sturcture According to Timestamp For 3 Level Structre
	$UploadPath = PathStructure($Stamp,$Type,true);
	$Config = array_merge([
		'upload_path'=>$UploadPath,
		'allowed_types'=>$Types,
		'max_size'=>100*1024,
		'file_name'=>str_shuffle(sprintf('%s%s',time(),rand(1,555)))
	],$Config);
	$Ins =& get_instance();
	$Ins->upload = NULL;
	$Ins->load->library('upload',$Config);
	if($Ins->upload->do_upload($Element)){
		$Data = $Ins->upload->data() + ['date'=>date(DATETIMEFORMAT)];
		if($Type == 'IMG'){
			//$DATA['full_path']''
		
			//$Crop['create_thumb'] = true;
			if($Crop){
			    $Crop['image_library'] = 'gd2';
		    	$Crop['source_image'] = $Data['full_path'];
				$Crop['maintain_ratio'] = true;
				$Crop['width'] = isset($Crop['width']) ? $Crop['width'] : 500;
				$Crop['height'] = isset($Crop['height']) ? $Crop['height'] : 500;
			}
			
			$Ins->image_lib = NULL;
			$Ins->load->library('image_lib',$Crop);
			$Ins->image_lib->resize();
		}
		return $Data;
	}else{
		return ['Errors'=>$Ins->upload->display_errors()];
	}
}

function Upload(string $Element,int $Stamp,string $Type='IMG',string $FileTypes='*',array $Config=[], bool $Crop = false){

	$Out = [];
	//Get From Files Data Multi or Not
	if(isset($_FILES[$Element])){
		$File = $_FILES[$Element];

		//Check if Multiple File
		if(is_array(@$File['name'])){

			$Items = array();
			foreach($File as $K=>$I)if(is_array($I))foreach($I as $Key=>$Val)$Items[$Key][$K] = $Val;

			//Processing Multiple Files
			foreach($Items as $File){
				$_FILES[$Element] = $File;
				$Upload = DoUpload($Stamp,$Element,$Type,$FileTypes,$Config,$Crop);
				if(isset($Upload['Errors'])){
					$Out['Errors'] = implode('<br />',$Upload);
				}else{
					$Out[$File['name']] = $Upload;
				}
			}
		}else{
			$Upload = DoUpload($Stamp,$Element,$Type,$FileTypes,$Config);
			if(isset($Upload['Errors'])){
				$Out['Errors'][] = implode('<br />',$Upload);
			}else{
				$Out = $Upload;
			}
		}
	}else{
		$Out['Errors'] = 'No file were uploaded';
	}
	return $Out;
}

function RemoveFile(string $Name,int $Stamp,string $Type='OTHER'){
	$Path = PathStructure($Stamp,$Type);
	@unlink(sprintf('%s%s',$Path,$Name));
}

function VerifyPath(string $Path,string $Base,bool $Verify=true){
	if($Verify){
		$Start = '';
		foreach(explode('/',$Path) as $Dir){
			if($Dir){
				$Start .= sprintf('%s/',$Dir);
				$Loc = AbsPath(sprintf('%s%s',$Base,$Start));
				is_dir($Loc) ? NULL : mkdir($Loc);
			}
		}
	}
	return sprintf('%s%s',$Base,ltrim($Path,'/'));
}

function PathStructure(int $Stamp,string $Type,bool $Verify=false){
	switch($Type){
		case 'IMG':
			$Base = IMG_UPLOAD_PATH;
			break;
		case 'DOC':
			$Base = DOC_UPLOAD_PATH;
			break;
		case 'OTHER':
			$Base = OTHER_UPLOAD_PATH;
	}
	$Path = date('Y/m/d/',$Stamp);
	$Main = VerifyPath($Path,$Base,$Verify);
	return $Verify ? AbsPath($Main) : $Main;
}

function AbsPath(string $Item=''){
	return sprintf('%s%s',ABS,ltrim($Item,'/'));
}

function ParseCSV(string $Path,bool $KeySpace=true,array &$Heads=[]){
	$Parsed = array();
	if(file_exists($Path)){
		$CSV = file_get_contents($Path);
		$CSV = substr($CSV,strlen($CSV)) == "\n" ? $CSV : $CSV."\n";
		if(preg_match_all('/(.*?)\n/',$CSV,$Matches)){
			//Process Heads
			$Heads = explode(',',$Matches[1][0]);
			unset($Matches[1][0]);

			foreach($Matches[1] as $LineNum=>$Line){
				foreach(explode(',',$Line) as $Key=>$Value){
					if(isset($Heads[$Key])){
						$Key = Clean($Heads[$Key]);
						if(!$KeySpace){
							$Key = str_replace(' ','_',$Key);
						}
						$Parsed[$LineNum][$Key] = Clean($Value);;
					}
				}
			}
		}
	}
	return $Parsed;
}

function GetPercent(double $Orignal,double $Percent){

	return $Orignal * ($Percent / 100);
}

function GetPercentOf(double $Orignal,double $Percent){

	return ($Percent / $Orignal) * 100;
}

function Avarage(array $Values){
	if(is_array($Values) && count($Values) > 0){
		return Sum($Values) / count($Values);
	}else{
		return 0;
	}
}

function Nagative(double $Value){
	return -$Value;
}

function DistributeASC(double $Days,double $Total,$Frequency=0){
	if($Total >= $Days){

		//Setting Dividation Frequency
		if($Frequency <= 0){
			$Frequency = floor($Total / $Days);
			if($Frequency > 4){
				$Frequency = 3;
			}
		}
		//Per Day Divder %
		$Divider = $Days / $Frequency;
		$Divided = [];
		$Remaining = $Total;
		foreach(range(1,$Days) as $Day){
			$Now = ceil($Remaining / $Divider);
			if($Now == 0){
				return DistributeASC($Days,$Total,$Frequency-1);
			}
			//If Last Day
			if($Day == $Days){
				$Now = $Remaining;
			}
			$Divided[] = $Now;
			$Remaining = $Total - array_sum($Divided);
		}
		sort($Divided);
		$Divided = array_reverse($Divided);
		array_unshift($Divided,'UNSET');
		unset($Divided[0]);
		return $Divided;
	}else {
		return false;
	}

}

function TrimR($Value,string $What=' '){
	if(is_array($Value)){
		foreach($Value as $K=>$V){
			$Value[$K] = TrimR($V,$What);
		}
	}else{
		$Value = trim($Value,$What);
	}
	return $Value;
}

function Encrypt(string $Text){
	if($Text){
		$Ins =& get_instance();
		return $Ins->encryption->encrypt($Text);
	}else{
		return false;
	}
}

function Decrypt(string $Text){
	if($Text){
		$Ins =& get_instance();
		return $Ins->encryption->decrypt($Text);
	}else{
		return false;
	}
}

function ClouseToString(array $Clouse,$Type=' AND '){
	if(!empty($Clouse)){
		$Out = [];
		foreach($Clouse as $Field=>$Value){
			$Out[] = sprintf('`%s` = %s',$Field,$Value);
		}
		return implode($Type,$Out);
	}
}

function ArrayISearch(array $Array, string $KeySearch='',string $ValueSearch='') {

	$IArray = new RecursiveIteratorIterator(new RecursiveArrayIterator($Array));
	foreach($IArray as $Key=>$Value) {
		if ($KeySearch == $Key && $ValueSearch == $Value){
			return true;
		}
	}
	return false;
}

function CreateHierarchy($Items,$CallBack=null,string $MasterKey='MasterID',string $SlaveKey='SlaveID',$MasterValue=0,$AutoAppend=true){

	if(!empty($Items)){
		if($MasterValue == 0 && $AutoAppend){
			foreach($Items as $Item){
				//Is Slave To Any Master
				if(!ArrayISearch($Items,$SlaveKey,is_null($Item[$MasterKey]) ? 0 : $Item[$MasterKey])){
					$Items[] = [
						$MasterKey=>0,
						$SlaveKey=>$Item[$MasterKey]
					] + $Item;
				}
			}
		}
		$Map = [];
		$SlaveIDs = [];
		foreach($Items as $Index=>$Item){
			$MasterID = is_array($Item) ? $Item[$MasterKey] : $Item->{$MasterKey};
			$SlaveID = is_array($Item) ? $Item[$SlaveKey] : $Item->{$SlaveKey};
			if($MasterID == $MasterValue){
				$Childrens = CreateHierarchy($Items,$CallBack,$MasterKey,$SlaveKey,$SlaveID);
				if($CallBack){
					$Map[] = $CallBack($Item,$Childrens);
				}else{
					$Map[] = array_merge(['ID'=>$SlaveID],!empty($Childrens) ? ['Children'=>$Childrens] : []);
				}
			}
			$SlaveIDs[] = $SlaveID;
		}
		return $Map;
	}else{
		return NULL;
	}
}

function MapIndexWithKey($Items,$Key){
	if(!empty($Items)){
		$New = (object)[];
		foreach($Items as $Item){
			if(is_object($Item)){
				$New->{$Item->{$Key}} = $Item;
			}else{
				$New[$Item[$Key]] = $Item;
			}
		}
		return $New;
	}
}

function CreateGroup($Items,$GroupBy){
	if(!empty($Items)){
		$New = (object)[];
		foreach($Items as $Item){
			if(is_object($Item)){
				$New->{$Item->{$GroupBy}}[] = $Item;
			}else{
				$New[$Item[$GroupBy]][] = $Item;
			}
		}
		return $New;
	}
}

function PostFile($Element,$Allowed=[]){
	if(isset($_FILES[$Element])){
		$File = $_FILES[$Element];
		$Name = $File['name'];
		$Extention = explode('.',$Name);
		$Extention = strtolower($Extention[count($Extention)-1]);
		if(in_array($Extention,$Allowed)){
			return $File;
		}
	}
	return false;
}

function Clean($Value,$UnicodeOnly=false){
	if($UnicodeOnly){
		if(preg_match_all('/([a-zA-Z0-9 \:\/\\\?\-\_\(\)\*\&\^\%\$\#\@\!\`\~\'\"\;\<\>\.\,\=\+])/ix',$Value,$Matches)){
			$Value = implode('',$Matches[1]);
		}else{
			$Value = '';
		}
	}
	$Value = str_ireplace(["\r","\n",'\r','\n'],'',trim($Value,' '));
	return $Value;
}

function ArrayToNamePath(array $Data,string $PrevName='',int $Level=0){
	foreach($Data as $Name=>$Value){
		$Name = sprintf('%s%s',$PrevName,$Level == 0 ? $Name : sprintf('[%s]',$Name));
		if(is_array($Value)){
			$Out[$Name] = ArrayToNamePath($Value,$Name,$Level+1);
		}else{
			$Out[$Name] = $Value;
		}
	}
	return $Out;
}

function Price($Amount,$Currency=''){

	if($Currency == '')$Currency = '{Row.Currency}';
	$Amount = _E($Amount);
	$Currency = _E($Currency);
	if(!isset(CURRENCIES[$Currency]))$Currency = 'USD';
	$Currency = CURRENCIES[$Currency];

	if($Currency['Position'] == 'L'){
		return sprintf('%s %s',$Currency['Symbol'],number_format($Amount,2));
	}else{
		return sprintf('%s %s',number_format($Amount,2),$Currency['Symbol']);
	}
}

function EditableCell(array $CMD,array $Field,object $Row){
	$FID = sprintf('%s_%d',$CMD['Field'],$Row->ID);
	$Display = _E($Field['Display'],true);
	return sprintf(
		'<td class="text-left">%s%s%s<div class="row ml-0" data-type="EditableField" data-id="%s" style="display: none;">%s</div></td>',
		isset($CMD['Before']) ? _E($CMD['Before']) : '',
		_A($Display ? $Display : '<i>N/A</i>','javascript:;',['class'=>'editable editable-click','data-type'=>'Editable','data-id'=>$FID]),
		isset($CMD['After']) ? _E($CMD['After']) : '',
		$FID,
		GenerateFields([
			[
				'Type'=>$CMD['Type'],
				'Label'=>'',
				'Name'=>$CMD['Field'],
				'Config'=>[
					'UV'=>@$CMD['UV'],
					'Data'=>@$CMD['Data'],
					'Attributes'=>(isset($CMD['Attributes']) ? $CMD['Attributes']  : []) + ['class'=>'form-control'],
					'Template'=>_E(sprintf('<div class="card col-12 p-0" style="min-width: 200px;">
						<div class="card-body p-0">{Field}</div>
						<div class="card-footer text-left p-0 pt-1">
							{(_Button(\'Save\',_Tag(\'i\',false,\'\',[\'class\'=>\'fa fa-fw fa-check\']),[\'class\'=>\'btn btn-info btn-xs\',\'type\'=>\'button\',\'data-id\'=>\'%s\',\'data-type\'=>\'SaveEditable\',\'data-pk\'=>_E(\'{Row.ID}\')]))}
							{(_Button(\'Save\',_Tag(\'i\',false,\'\',[\'class\'=>\'fa fa-fw fa-times\']),[\'class\'=>\'btn btn-danger btn-xs\',\'type\'=>\'button\',\'data-id\'=>\'%1$s\',\'data-type\'=>\'CancelEditable\']))}
						</div>
					</div>',$FID))
				],
				'Default'=>$CMD['Type'] == 'Checkbox' ? 1 : _E($CMD['EditData'],true)
			]
		])
	);
}

function OrderID(string $CMD){
	return sprintf('INV-%s-ORD',str_pad(_E($CMD),6,0,STR_PAD_LEFT));
}

function ShipmentID(string $CMD){
	return sprintf('SHP-%s',str_pad(_E($CMD),5,0,STR_PAD_LEFT));
}

function CorrectDate(string $Date){
	return strtotime($Date) ? date(DATEFORMAT,strtotime($Date)) : NULL;
}

function Sum(array $Data){
	return array_sum($Data);
}

function _Decimal($Amount){
	return number_format($Amount,2,'.','');
}

function SupplierContacts(string $Contacts,string $Template,string $GroupSeprator='////',string $Seprator='>'){
	$Contacts = explode($GroupSeprator,_E($Contacts,true));
	$Final = [];
	if($Contacts){
		foreach($Contacts as $Contact){
			$Contact = explode($Seprator,$Contact);
			if(count($Contact) == 4){
				$Final[] = [
					'Name'=>$Contact[0],
					'Email'=>$Contact[1],
					'Phone'=>$Contact[2],
					'OtherInfo'=>$Contact[3]
				];
			}
		}
		return TemplateItems($Final,$Template,true);
	}
}

function GetTime($StartTime,$EndTime,$What='',$Format=true){

	$Start = strtotime(_E($StartTime));
	$End = strtotime(_E($EndTime));
	//If Time End in Next Day
	if($Start > $End){

		/*$StartTime = date(DATETIMEFORMAT,strtotime($StartTime));
		$EndTime = date(DATETIMEFORMAT,strtotime(sprintf('+1 Day %s',$EndTime)));
		$Start = strtotime($StartTime);
		$End = strtotime($EndTime);*/
	}
	$Seconds = ($End - $Start);
	$TimeData = array(
		'Seconds'=>$Seconds,
		'Minutes'=>$Seconds / 60,
		'Hours'=>$Seconds / (60 * 60),
		'Days'=>$Seconds / (60 * 60 * 24),
		'Months'=>$Seconds / (60 * 60 * 24 * 30),
		'Years'=>$Seconds / (60 * 60 * 24 * 30 * 12),
	);

	if($Format){
		//Convert To Simple Number Format
		foreach($TimeData as $Name=>$Seconds){
			$TimeData[$Name] = number_format($Seconds,1);
		}
	}
	if($What){
		return isset($TimeData[$What]) ? $TimeData[$What] : 0;
	}else{
		return $TimeData;
	}
}

function LevelChanging($CMD,$Template=''){
	$Item = _E($CMD,true);
	if($Item){
		$Out = [];
		foreach($Item as $Log){
			$Out[] = str_replace(['{Value}','{Date}'],[$Log['Value'],$Log['Date']],$Template);
		}
		return implode('',$Out);
	}
	return '';
}

function _ExcelRange($First,$Last) {
	++$Last;
	for ($I = $First; $I !== $Last; ++$I) {
		yield $I;
	}
}

function ExcelRange($First,$Last){

	$Range = array();
	foreach(_ExcelRange($First,$Last) as $Value){
		$Range[] = $Value;
	}
	return $Range;
}
function GetLevelDate($Data,$Level){

	if(!is_array($Data))$Data = _E($Data,true);
	
	foreach($Data as $Value){
		if($Value['Value'] == $Level)return $Value['Date'];
	}
	return DATENOW;
}

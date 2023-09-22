<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_Default extends CI_Controller {

	public	$Cache = true,
			$Errors = [],
			$Controller = '',
			$Method = '',
			$Current = null,
			$LoginRequired = true,
			$Data = [],
			$BackTo = '',
			$Bundels,
			$NoProfiler,
			$ActiveProfile = null,
			$AppliedFilters = [];

	public function __construct(){

		parent::__construct();

		$this->load->helper('Main');
		//Profiler
		if(!Ajax()){
			$this->output->enable_profiler($this->NoProfiler ? false : (ENVIRONMENT != 'production'));
		}

		//Loading Encryption
		$this->load->library(['session','encryption']);
		$this->encryption->initialize(['cipher' => 'AES-256','mode' => 'ECB']);
		//Leading Internal Messages System
		$this->load->library('Messages');
		$this->Messages->Fetch();

		//Configuring Database and Base Libraries
		$this->load->database();
		$Router = $this->router;
		$this->Controller = $Router->class;
		$this->Method = $Router->method;
		$this->load->model(['Logs','Role','Tag','Profile','Blog','Activity','Widget','Contact','EntityData','Navigation','Category','Setting','Slider','QuickQuote','Product']);
		$this->load->library(['Bundles','TextExecution','form_validation','IArray'],[]);
		//Temp Post
		{
			$TempPost = $this->session->flashdata('TempPost');
			if(!empty($TempPost)){
				$TempPost = @unserialize($TempPost);
				if(is_array($TempPost)){
					$_POST = $TempPost;
				}
			}
		}

		//Post Validation Errors
		{
			$ValidationErrors = $this->session->flashdata('ValidationErrors');
			if(!empty($ValidationErrors)){
				$ValidationErrors = @unserialize($ValidationErrors);
				if(is_array($ValidationErrors)){
					$this->Errors = $ValidationErrors;
				}
			}
		}
		$this->load->helper(['HTML','Validation','form','url','cookie']);
		//$this->load->library();

		if($this->LoginRequired){
			//Loading Base Models
			$this->load->model(['Access','Module']);
			if(!$this->Profile->LoggedIn()){
				return Forword(URI('Login',true));
			}else{
				$this->ActiveProfile = $this->Profile->GetLogged();
				Def($this->ActiveProfile,'Profile');
			}
			//Setting Up Access
			$this->Access->LoadAll();

			//Getting Current Module
			if($this->Controller && $this->Method){
				$Current = isset($this->Access->ByMethod[$this->Controller][$this->Method]) ? $this->Access->ByMethod[$this->Controller][$this->Method] : NULL;
				if($Current){
					Def($Current,'Current');
				}
			}
		}

		//Loading Required Models
		if(!empty($this->Required) && is_array($this->Required)){
			$this->load->model($this->Required);
		}

		//Loading Resources Bundels
		$this->Bundels = new M_Bundels();
		$this->Bundels->Resources();

		//Globlize Filters
		$this->AppliedFilters = $this->input->get('F',true);
		Def($this->AppliedFilters,'Filters');
	}

	public function PrepareList($Model,$Data,$Page=1,$Special=false){

		//Page Settings
		{
			$PerPage = isset($Data['PerPage']) ? $Data['PerPage']  : 100;
			$Page = intval($Page);
			$Data['Page'] = $Page = $Page <= 0 ? 1 : $Page;
			$Offset = $PerPage * ($Page - 1);
		}

		$this->ApplyFilters($Model,$Data);

		//Fetching Data
		{
			/*if(isset($Data['Editable'])){
				$Data['PartialList'] = 'Helpers/EditableList';
			}*/
			$Clouse = isset($Data['Clouse']) ? $Data['Clouse'] : [];
			$Model->FetchTotal(true);
			$Method = isset($Data['Method']) ? $Data['Method'] : 'IGetList';
			
			$List = $Model->{$Method}($Clouse,$PerPage,$Offset,isset($Data['Order']) ? $Data['Order'] : '',isset($Data['SelectFields']) ? $Data['SelectFields'] : '');

			$Data['List'] = $List;
			if($List->Total == 0 && isset($Data['EmptyDataCallBack']) && $Data['EmptyDataCallBack']){
				$Data['EmptyDataCallBack']($Data);
				return;
			}
	
			//Page Validation
			$Data['Pages'] = $Pages = ceil($List->Total / $PerPage);
			if($Page > 1 && $Page > $Pages){
				SetMessage('MAXPAGES');
				return Forword(AURI(sprintf('%s/%d',$this->router->method,$Pages)));
			}
		}
		$this->View('Helpers/Index',$Data,$Special);
	}

	public function ApplyFilters($Model,$Data){
		//Applying Filters
		{
			$AppliedFilters = $this->AppliedFilters;
			if(!empty($AppliedFilters)){
				$Filters = $Data['Filters'];

				//Preparing Filter Clouse
				foreach($Filters as $Name=>$Filter){
					$FilterType = $Filter['Filter'];
					$Value = isset($AppliedFilters[$Name]) ? (is_array($AppliedFilters[$Name]) ? $AppliedFilters[$Name] : _XSS(trim($AppliedFilters[$Name],' '))) : '';
					if($Value != '' && !empty($FilterType)){
						$Field = $FilterType[0];
						switch($FilterType[1]){
							case 'LIKE':
								$Model->DB()->or_like($Field,$Value);
							break;
							case 'LIKELEFT':
								$Model->DB()->like($Field,$Value,'left');
							break;
							case 'LIKERIGHT':
								$Model->DB()->like($Field,$Value,'right');
							break;
							case 'EQUAL':
								$Model->DB()->where($Field,$Value);
							break;
							case 'HAS':
								$Model->DB()->where(sprintf('IF({P}%s = \'\' OR {P}%1$s IS NULL,0,{P}%1$s) > 0',$Field));
							break;
							case 'HASORNOT':
								$Model->DB()->where(sprintf('IF({P}%s = \'\' OR {P}%1$s IS NULL,0,{P}%1$s)%s',$Field,$Value ? ' > 0' : ' = 0'));
							break;
							case 'HASSTRING':
								$Model->DB()->where(sprintf('IFNULL({P}%s,\'\') != \'\'',$Field));
							break;
							case 'RANGE':
								$Value = explode(' - ',$Value);
								if(count($Value) == 2){
									$Between = BetweenClouse($Field,$Value[0],$Value[1]);
									if($Between){
										$Model->DB()->where($Between);
									}else{
										SetMessage(sprintf('Date of "%s" was invalid',$Filter['Label']),'Warning');
									}
								}
							break;
							case 'VOID':
							break;
						}
					}
				}
			}
		}
	}

	private function ViewProcess(&$Data = [],$Name,$Special){
		$Data += $this->Data;
		$Data['Bundels'] = $this->Bundels;
		if(!isset($Data['Title']))$Data['Title'] = '{Current.Title}';
		//Loading Given View
		$Data['Page'] = $this->load->view(sprintf('%s%s',$Special ? sprintf('%s/',SPECIALPATH) : '',$Name),$Data,true);
	}

	public function View($Name,$Data=[],$Special=false){

		$this->ViewProcess($Data,$Name,$Special);
		//Loading Tempalte Commonents
		$Template = sprintf('%sTemplate/',$Special ? sprintf('%s/',SPECIALPATH) : '');
		$Data['Header'] = $this->load->view(sprintf('%s/Header',$Template),$Data,true);
		$Data['Footer'] = $this->load->view(sprintf('%s/Footer',$Template),$Data,true);
		$Data['Sidebar'] = $this->load->view(sprintf('%s/Sidebar',$Template),$Data,true);
		$this->load->view(sprintf('%s/Index',$Template),$Data);
	}

	public function PartialView($Name,$Data=[],$PartialTemplate='Partial',$Special=false){

		$this->ViewProcess($Data,$Name,$Special);

		//Loading Tempalte Commonents
		$Template = sprintf('%sTemplate/',$Special ? sprintf('%s/',SPECIALPATH) : '');
		$this->load->view(sprintf('%s/%s',$Template,$PartialTemplate),$Data);
	}

	public function SetValidationRules($Rules=[]){

		$CustomMessages = [
			'required'=>'',
			'ValidNIC'=>''
		];
		if(!empty($Rules)){
			foreach($Rules as $Rule){
				$this->form_validation->set_rules($Rule['Name'],$Rule['Label'],$Rule['Rule']/*$CustomMessages*/);
			}
		}
	}

	public function ProcessPost($Elements,&$OutData){
		if(!empty($Elements)){
			$Input = $this->input;
			$PreparedData = [];
			foreach($Elements as $Element){
				$Name = '';
				$FieldName = '';
				$XSS = true;
				$CallBacks = [];
				if(isset($Element[0]))$Name = $Element[0];
				if(isset($Element[1]))$FieldName = $Element[1];
				if($FieldName == '')$FieldName = $Name;
				if(isset($Element[2]))$XSS = $Element[2];
				if(isset($Element[3]))$CallBacks = $Element[3];

				$Value = PS($Name,$XSS);
				//Implimenting Callbaks One by One
				if(!empty($CallBacks)){
					foreach($CallBacks as $CallBack){
						$Value = $CallBack($Value);
					}
				}else{
					$Value = TrimR($Value);
				}
				$PreparedData[$FieldName] = $Value;
			}
			$OutData = $PreparedData;
		}
	}

	public function CheckAccess($Access){
		if($this->Access->HaveAccess($Access)){
			return true;
		}else{
			AccessDenied();
		}
	}
	// Temprory Adjustment
	public function Add(){
		$this->CheckAccess('ADD');
		$Data['Fields'] = $this->GetFields();
		$this->View('Helpers/Add',$Data,true);
	}

	public function Edit(int $PK=0){
		$this->CheckAccess('EDIT');
		$Row = $this->Model->Row(intval($PK));
		if($Row){
			Def($Row,'Row');
			$Data['Fields'] = $this->GetFields(true,$Row);
			$this->View('Helpers/Edit',$Data,true);
		}else{
			InvalidData();
		}
	}

	public function Details(int $PK=0){
		$this->CheckAccess('LIST,DETAILS');
		if(!$PK)$PK = PS('PK');
		$Row = $this->Model->Row(intval($PK),'',$this->Model->Mask('*'));
		if($Row){
			$Data['Row'] = $Row;
			$Exempt = array_merge($this->Model->InsertActivityFields,$this->Model->UpdateActivityFields,$this->Model->DeleteActivityFields);
			foreach ($Exempt as $Key=>$Value){
				unset($Row->{$Key});
			}
			if(Ajax()){
				echo JsonOut($Row,200);
			}else{
				Def($Row,'Row');
				$this->View('Helpers/Details',$Data,true);
			}
		}else{
			InvalidData();
		}
	}

	public function RemoveItem(string $Type,int $PK,string $Name){
		$this->CheckAccess('EDIT');
		$Row = $this->Model->Row(intval($PK));
		$Name = urldecode($Name);
		if($Row){
			$Config = unserialize($Row->Config);
			$PathType = '';
			switch($Type){
				case 'Images':
					$Item = isset($Config['Images']) ? $Config['Images'] : [];
					$PathType = 'IMG';
				break;
				case 'Documents':
					$Item = isset($Config['Documents']) ? $Config['Documents'] : [];
					$PathType = 'DOC';
				break;
				default:
					SetMessage('DATAINVALID','Error');
					return GoBack();
				break;
			}
			if(isset($Item[$Name])){
				RemoveFile(isset($Item[$Name]['file_name']) ? $Item[$Name]['file_name'] : '',strtotime($Row->EntryDate),$PathType);
				unset($Config[$Type][$Name]);
				$this->Model->Update($Row->ID,['Config'=>serialize($Config)]);
				SetMessage('File removed successfully','Success');
			}else{
				SetMessage('File was not found','Error');
			}
		}else{
			SetMessage('DATAINVALID','Error');
		}
		return GoBack();
	}

	public function SaveEditable(string $Model=''){
		if(Ajax()){
			$Out = ['CFH'=>_CSRF_Hash()];
			if(isset($this->Editables[$Model])){
				$DataModel = $this->Editables[$Model];
				$Fields = $DataModel['Fields'];
				if(isset($DataModel['Details'])){
					$Fields += $DataModel['Details'];
				}
				$PK = intval(PS('PK'));
				$Out['Status'] = 0;
				if(PS('Save') && $PK){
					$Row = $this->{$DataModel['Model']}->Row($PK);
					if($Row){
						$Out['Status'] = 1;
						$SaveItems = PS('Save');
						$Rules = [];
						$PostList = [];
						$Display = [];
						$Post = PS();
						foreach((new IArray(ArrayToNamePath($Post)))->LastElements as $Name=>$Value){
							if(isset($Fields[$Name])){
								$Rule = $Fields[$Name];
								//Override Database Field Name
								if(isset($Rule['DB'])){
									$Field = $Rule['DB'];
									$FieldData = array_merge((array)unserialize($Row->{$Field}),$Post[$Rule['Element']]);
									$Row->{$Field} = serialize($FieldData);
									$_POST[$Field] = $FieldData;
									$PostList[] = [$Field,'',true,['serialize']];
								}else{
									$Row->{$Name} = _XSS($Value);
									$PostList[] = [$Name];
								}
								Def($Row,'Row');
								$Rules[] = $Rule;
								$Display[$Name] = _E($Rule['Display'],true);
							}
						}
						if(!empty($Rules)){
							$this->SetValidationRules($Rules);
							if(!$this->form_validation->run()){
								$Out['Error'] = isset($Out['Error']) ? $Out['Error'] : [];
								$Out['Error'] += array_values($this->form_validation->error_array());
								$Out['Status'] = 0;
							}
						}
						$this->ProcessPost($PostList,$Data);
						$Out['Data'] = $Data;
						if($Out['Status']){
							if($this->{$DataModel['Model']}->Update($PK,$Data)){
								$Out['Message'] = 'Data saved';
								$Out['Data'] = $Display;
							}else{
								$Out['Status'] = 0;
								$Out['Error'][] = 'Unable to save data this time';
							}
						}
					}else{
						$Out['Error'][] = 'Data not exists';
					}
				}
			}else{
				$Out['Error'][] = 'Model was not found';
				$Out['Status'] = 0;
			}
			echo JsonOut($Out,200);
		}else{
			InvalidData();
		}
	}

	public function ProcessUpload(string $Element,string $Type,array &$Data,string $Action,int $PK,string $AllowdTypes='*',$DataField='Config'){
		$Config = [];
		if($Action == 'Edit'){
			$Row = $this->Model->IRow($PK);
			$Config = (array)unserialize($Row->{$DataField});
		}

		if(empty($Data[$DataField])){
			$Data[$DataField] = [];
		}
		//Uploading
		if($_FILES[$Element]['name'][0] != ''){
			$Uploads = Upload($Element,$Action == 'Edit' ? strtotime($Row->EntryDate) : time(),$Type,$AllowdTypes);
			if(isset($Uploads['Errors'])){
				SetMessage(implode('',$Uploads['Errors']),'Error');
				return GoBack();
			}
			if($Action == 'Edit' && isset($Config[$Element])){
				$Data[$DataField][$Element] = array_merge($Config[$Element],$Uploads);
				//$Data[$DataField][$Element] += ;
				//$Data[$DataField] = array_merge($Data[$DataField],$Config[$Element]);
			}else{
				$Data[$DataField][$Element] = $Uploads;
			}
		}elseif($Action == 'Edit' && isset($Config[$Element])){
			$Data[$DataField][$Element] = $Config[$Element];
		}
	}

	public function __destruct(){
		$this->Messages->Push();
	}
}

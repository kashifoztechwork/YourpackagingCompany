<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class Settings extends M_Default {

		public	$Required = ['Setting'];
				

		public function __construct(){

			parent::__construct();
			$this->Model = $this->Setting;
		}

		public function Index($Page=0){
			$this->CheckAccess('LIST');
			//List Headers
			$Data['Headers'] = [
				'Project'=>'{Row.Project}',
			];
			// Select Fields
			$Data['SelectFields'] = 'SET.*';
			// Prepare List
			$this->PrepareList($this->Model,$Data,$Page,true);
		}

		public function GetFields($Edit=false){

			return [
				[
					'Name'=>'Project',
					'Label'=>'Project Name',
					'Type'=>'Text',
					'Config'=>[
						'Attributes'=>['class'=>'form-control','AA'=>['required']],
						'Template'=>COL12
					],
					'Default'=>'{Row.Project}'
				],
				[
					'Name'=>'Config[Phone]',
					'Label'=>'Phone',
					'Type'=>'Text',
					'Config'=>[
						'Attributes'=>['class'=>'form-control'],
						'Template'=>COL6
					],
					'Default'=>'{Row.Config.Phone}'
				],
				[
					'Name'=>'Config[Email]',
					'Label'=>'Email',
					'Type'=>'Text',
					'Config'=>[
						'Attributes'=>['class'=>'form-control'],
						'Template'=>COL6
					],
					'Default'=>'{Row.Config.Email}'
				],
				[
					'Name'=>'Config[Facebook]',
					'Label'=>'Facebook',
					'Type'=>'Text',
					'Config'=>[
						'Attributes'=>['class'=>'form-control'],
						'Template'=>COL6
					],
					'Default'=>'{Row.Config.Facebook}'
				],
				[
					'Name'=>'Config[Instagram]',
					'Label'=>'Instagram',
					'Type'=>'Text',
					'Config'=>[
						'Attributes'=>['class'=>'form-control'],
						'Template'=>COL6
					],
					'Default'=>'{Row.Config.Instagram}'
				],
				[
					'Name'=>'Config[Address]',
					'Label'=>'Address',
					'Type'=>'TextArea',
					'Config'=>[
						'Attributes'=>['class'=>'form-control'],
						'Template'=>COL12
					],
					'Default'=>'{Row.Config.Address}'
				],
				[
					'Name'=>'Config[Title]',
					'Label'=>'Title',
					'Type'=>'Text',
					'Config'=>[
						'Attributes'=>['class'=>'form-control'],
						'Template'=>COL6
					],
					'Default'=>'{Row.Config.Title}'
				],
				[
					'Name'=>'Config[Keywords]',
					'Label'=>'Keywords',
					'Type'=>'Text',
					'Config'=>[
						'Attributes'=>['class'=>'form-control'],
						'Template'=>COL6
					],
					'Default'=>'{Row.Config.Keywords}'
				],
				[
					'Name'=>'Config[Description]',
					'Label'=>'Description',
					'Type'=>'TextArea',
					'Config'=>[
						'Attributes'=>['class'=>'form-control'],
						'Template'=>COL12
					],
					'Default'=>'{Row.Config.Description}'
				],
				[
					'Name'=>'Config[SchemaTag]',
					'Label'=>'Schema Tag',
					'Type'=>'TextArea',
					'Config'=>[
						'Attributes'=>['class'=>'form-control','rows'=>'10'],
						'Template'=>COL12
					],
					'Default'=>'{Row.Config.SchemaTag}'
				],
				[
					'Name'=>'Logo[]',
					'Label'=>'Logo',
					'Type'=>'File',
					'Config'=>[
						'Attributes'=>['class'=>'form-control'],
						'Template'=>COL12
					]
				]
			];
			if($Edit){
				$Images = _E('{Row.Logo}',true);
				if(!empty($Images)){
					$Fields[] = [
						'Name'=>'',
						'Label'=>'',
						'Config'=>[
							'Template'=>_E('<div class="card col-12 border-info p-0"><div class="card-header bg-info text-white">Images</div><div class="card-body row p-2">{( TemplateItems(GetImage(\'{Row.EntryDate}\',\'{Row.Logo}\',[\'width\'=>\'100%\'],true,true),sprintf(\'<div class="col-2 text-center mt-2" style="height: 200px;overflow: hidden;">{Item}%s</div>\',_A(\'Delete\',AURI(\'RemoveItem/Images/{Row.ID}/{Key}\'),[\'class\'=>\'btn btn-danger btn-xs\',\'style\'=>\'position: absolute; top:0;z-index: 9;left: 48%;\']))) )}</div></div>')
						]
					];
				}
			}
		}

		public function Action($Action,$PK=0){
			if($Action != ''){
				$Input = $this->input;
				if($Action == 'Add' || $Action == 'Edit'){
               
					$this->SetValidationRules([
						['Name'=>'Project','Label'=>'Name','Rule'=>'required|trim|alpha_numeric_spaces']
					]);
					if(!$this->form_validation->run()){
						return GoBack();
					}
					
					$this->ProcessPost([['Project'],['Config']],$Data);
					
				    if($Action == 'Edit'){
					    $Row = $this->Model->Row($PK);
					    $Config = (array)unserialize($Row->Config);
			    	}
				    
					//Uploading Images
					if($_FILES['Logo']['name'][0] != ''){
						$Images = Upload('Logo',$Action == 'Edit' ? strtotime($Row->EntryDate) : time(),'IMG','jpg|gif|jpeg|png|bmp');
						if(isset($Images['Errors'])){
							SetMessage(implode('',$Images['Errors']),'Error');
							return GoBack();
						}
						if($Action == 'Edit' && isset($Row->Image)){
							$Data['Logo']  = serialize($Images);
						}else{
							$Data['Logo']  = serialize($Images);
						}
					}

					$Data['Config'] = serialize($Data['Config']);
			
				}

				switch($Action){
					case 'Add':
						$this->CheckAccess('ADD');
						$PK = $this->Model->Insert($Data);
						if($PK > 0){
							SetMessage('ENTRYSUCCESS');
						}else{
							SetMessage('ENTRYFAILED');
						}
					break;
					case 'Edit':
						$this->CheckAccess('EDIT');
						if($this->Model->Update($PK,$Data)){
							SetMessage('ENTRYSUCCESS');
						}else{
							SetMessage('ENTRYFAILED');
						}
					break;
					case 'Delete':
						$this->CheckAccess('DELETE');
						if($this->Model->Delete($PK)){
							SetMessage('ENTRYDELETED');
						}else{
							SetMessage('ENTRYFAILED');
						}
					break;
					case 'General':
						$PostAction = PS('PostAction');
						$Items = array_map('intval',PS('Item'));
						if(!empty($Items)){
							switch($PostAction){
								case 'DeleteAll':
									$this->CheckAccess('DELETE');
									$this->Model->DB()->where_in('ID',$Items);
									if($this->Model->Delete(['ID > '=>0])){
										SetMessage('ENTRIESDELETED');
									}else{
										SetMessage('ENTRYFAILED');
									}
								break;
							}
						}
					break;
				}
				return Forword(AURI('Index',true));
			}else{
				InvalidData();
			}
		}

		public function __destruct(){
			parent::__destruct();
		}
	}

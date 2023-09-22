<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Products extends M_Default {

	public	$Required = ['Product','Category','Tag'];
			

	public function __construct(){

		parent::__construct();
		$this->Model = $this->Product;
	}

	public function Index($Page=0){
		$this->CheckAccess('LIST');
		//Page Filters
		$Data['Filters'] = [
			'Title'=>[
				'Label'=>'Name',
				'Type'=>'Text',
				'Filter'=>['PRD.Name','LIKE']
			]
		];

		//List Headers
		$Data['Headers'] = [
			'ID'=>'{Row.ID}',
			'Name'=>'{Row.Name}',
			'Category'=>'{Row.Category}'
		];
		// Select Fields
		$Data['SelectFields'] = 'PRD.*';
		// Prepare List
		$this->PrepareList($this->Model,$Data,$Page,true);
	}

	public function GetFields($Edit=false,$Row=NULL){
		if($Edit){
			$ID = _E('{Row.ID}',true);
			$Tag = $this->Tag->Row(['{P}TAG.Type'=>'Product','{P}TAG.TypeID'=>$ID]);
			def($Tag,'Tag');
		}
		$this->Bundels->Snippet('PostScripts','
		<script>
			 tinymce.init({
				selector: \'textarea.content\',
				plugins: \'anchor image link lists table visualblocks wordcount\',
				menubar: false,
				toolbar: \'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image table | align lineheight | numlist bullist indent outdent | removeformat\',
				});
		</script>
		');
		return [
			[
				'Name'=>'Name',
				'Label'=>'Name',
				'Type'=>'Text',
				'Config'=>[
					'Attributes'=>['class'=>'form-control','AA'=>['required']],
					'Template'=>COL6
				],
				'Default'=>'{Row.Name}'
			],
			[
				'Name'=>'Slug',
				'Label'=>'Slug',
				'Type'=>'Text',
				'Config'=>[
					'Attributes'=>['class'=>'form-control','AA'=>['required']],
					'Template'=>COL6
				],
				'Default'=>'{Row.Slug}'
			],
			[
				'Name'=>'Category',
				'Label'=>'Category',
				'Type'=>'DropDown',
				'Config'=>[
					'Attributes'=>['class'=>'form-control'],
					'Template'=>COL6,
					'Model'=>'Category',
					'Display'=>'{Name}',
					'Fields'=>['ID','Name'],
					'UV'=>true
				],
				'Default'=>'{Row.Category}'
			],
			[
				'Name'=>'Descriptions[ShortDescription]',
				'Label'=>'Short Description',
				'Type'=>'TextArea',
				'Config'=>[
					'Attributes'=>['class'=>'form-control content','rows'=>'10'],
					'Template'=>COL12
				],
				'Default'=>'{Row.Descriptions.ShortDescription}'
			],
			[
				'Name'=>'Descriptions[Description]',
				'Label'=>'Description',
				'Type'=>'TextArea',
				'Config'=>[
					'Attributes'=>['class'=>'form-control content','rows'=>'30'],
					'Template'=>COL12
				],
				'Default'=>'{Row.Descriptions.Description}'
			],
			[
				'Name'=>'Descriptions[FAQs]',
				'Label'=>'FAQs',
				'Type'=>'TextArea',
				'Config'=>[
					'Attributes'=>['class'=>'form-control content','rows'=>'10'],
					'Template'=>COL12
				],
				'Default'=>'{Row.Descriptions.FAQs}'
			],
			[
				'Name'=>'Tag[Title]',
				'Label'=>'Title',
				'Type'=>'Text',
				'Config'=>[
					'Attributes'=>['class'=>'form-control title'],
					'Template'=>'<div class="form-group col-6">{Label}{Field}{Message}  <span id="fortitle">60</span> Character(s) Remaining</div>',
				],
				'Default'=>'{Tag.Title}'
			],
			[
				'Name'=>'Tag[Keywords]',
				'Label'=>'Keywords',
				'Type'=>'Text',
				'Config'=>[
					'Attributes'=>['class'=>'form-control'],
					'Template'=>COL6
				],
				'Default'=>'{Tag.Keywords}'
			],[
				'Name'=>'Tag[Description]',
				'Label'=>'Meta Description',
				'Type'=>'TextArea',
				'Config'=>[
					'Attributes'=>['class'=>'form-control description','rows'=>'10'],
					'Template'=>'<div class="form-group col-12">{Label}{Field}{Message}  <span id="rchars">150</span> Character(s) Remaining</div>',
				],
				'Default'=>'{Tag.Description}'
			],
			[
				'Name'=>'Tag[SchemaTag]',
				'Label'=>'SchemaTag',
				'Type'=>'TextArea',
				'Config'=>[
					'Attributes'=>['class'=>'form-control','rows'=>'10'],
					'Template'=>COL12
				],
				'Default'=>'{Tag.SchemaTag}'
			],
			[
				'Name'=>'Featured',
				'Label'=>'Featured',
				'Type'=>'CheckBox',
				'Config'=>[
					'Attributes'=>['class'=>'form-check-input'],
					'Data'=>[1=>'Featured'],
					'Template'=>COL4
				],
				'Default'=>'{Row.Featured}'
			],
			[
				'Name'=>'BestSeller',
				'Label'=>'Best Seller',
				'Type'=>'CheckBox',
				'Config'=>[
					'Attributes'=>['class'=>'form-check-input'],
					'Data'=>[1=>'Best Seller'],
					'Template'=>COL4
				],
				'Default'=>'{Row.BestSeller}'
			],
			[
				'Name'=>'Status',
				'Label'=>'Status',
				'Type'=>'CheckBox',
				'Config'=>[
					'Attributes'=>['class'=>'form-check-input'],
					'Data'=>[1=>'Approve'],
					'Template'=>COL4
				],
				'Default'=>'{Row.Status}'
			],[
				'Name'=>'Images[]',
				'Label'=>'Images',
				'Type'=>'File',
				'Config'=>[
					'Attributes'=>['class'=>'form-control','multiple'=>'true'],
					'Template'=>COL12
				]
			],[
				'Name'=>'Thumbnail',
				'Label'=>'Thumbnail',
				'Type'=>'File',
				'Config'=>[
					'Attributes'=>['class'=>'form-control'],
					'Template'=>COL12
				]
			],[
				'Name'=>'IsIndex',
				'Label'=>'Is Index',
				'Type'=>'CheckBox',
				'Config'=>[
					'Attributes'=>['class'=>'form-check-input'],
					'Data'=>[1=>'Index'],
					'Template'=>COL4
				],
				'Default'=>'{Row.IsIndex}'
			]

		];
		if($Edit){
			$Images = _E('{Row.Images}',true);
			if(!empty($Images)){
				$Fields[] = [
					'Name'=>'',
					'Label'=>'',
					'Config'=>[
						'Template'=>_E('<div class="card col-12 border-info p-0"><div class="card-header bg-info text-white">Images</div><div class="card-body row p-2">{( TemplateItems(GetImage(\'{Row.EntryDate}\',\'{Row.Images}\',[\'width\'=>\'100%\'],true,true),sprintf(\'<div class="col-2 text-center mt-2" style="height: 200px;overflow: hidden;">{Item}%s</div>\',_A(\'Delete\',AURI(\'RemoveItem/Images/{Row.ID}/{Key}\'),[\'class\'=>\'btn btn-danger btn-xs\',\'style\'=>\'position: absolute; top:0;z-index: 9;left: 48%;\']))) )}</div></div>')
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
					['Name'=>'Name','Label'=>'Name','Rule'=>'required|trim|alpha_numeric_spaces']
				]);
				if(!$this->form_validation->run()){
					return GoBack();
				}
			
				$this->ProcessPost([['Name'],['Specification'],['Descriptions'],['Category'],['Status'],['Featured'],['BestSeller'],['IsIndex'],['Slug']],$Data);
				
				if($Action == 'Edit'){
					$Row = $this->Model->Row($PK);
					$Config = (array)unserialize($Row->Config);
				}
                // Apply Slug
				$Data['Slug'] = $Data['Slug'] ? Slug($Data['Slug']) : ''; 
				//Uploading Images
				if($_FILES['Images']['name'][0] != ''){
					$Images = Upload('Images',$Action == 'Edit' ? strtotime($Row->EntryDate) : time(),'IMG','jpg|gif|jpeg|png|bmp');
					if(isset($Images['Errors'])){
						SetMessage(implode('',$Images['Errors']),'Error');
						return GoBack();
					}

					if($Action == 'Edit'){
						if(!empty($Images)){
							$Data['Images']  = serialize($Images);
						} else {
							$Data['Images'] = $Row->Images;
						}

					} else {
						$Data['Images']  = serialize($Images);
					}
				}

				// Thumbnail
				if($_FILES['Thumbnail']['name'][0] != ''){
					$Thumbnail = Upload('Thumbnail',$Action == 'Edit' ? strtotime($Row->EntryDate) : time(),'IMG','jpg|gif|jpeg|png|bmp');
					if(isset($Thumbnail['Errors'])){
						SetMessage(implode('',$Thumbnail['Errors']),'Error');
						return GoBack();
					}

					if($Action == 'Edit'){
						if(!empty($Thumbnail)){
							$Data['Thumbnail']  = serialize($Thumbnail);
						} else {
							$Data['Thumbnail'] = $Row->Thumbnail;
						}

					} else {
						$Data['Thumbnail']  = serialize($Thumbnail);
					}
				}
				//Tags
				$Tags = $Input->post('Tag');
				
				//Specifications
				$Data['Specification'] = serialize($Data['Specification']);
				//Descriptions
				$Data['Descriptions'] = serialize($Data['Descriptions']);
			}

			switch($Action){
				case 'Add':
					$this->CheckAccess('ADD');
					$PK = $this->Model->Insert($Data);
					// Insert Tags
					$this->Tag->SetTags('Product',$PK,$Tags);
					if($PK > 0){
						SetMessage('ENTRYSUCCESS');
					}else{
						SetMessage('ENTRYFAILED');
					}
				break;
				case 'Edit':
					$this->CheckAccess('EDIT');
				
					
					if($this->Model->Update($PK,$Data)){
						// Update Tags
						$this->Tag->Delete(['Type'=>'Product','TypeID'=>$PK]);
						$this->Tag->SetTags('Product',$PK,$Tags);
						SetMessage('ENTRYSUCCESS');
					}else{
						SetMessage('ENTRYFAILED');
					}
				break;
				case 'Delete':
					$this->CheckAccess('DELETE');
					if($this->Model->Delete($PK)){
						$this->Tag->Delete(['Type'=>'Product','TypeID'=>$PK]);
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

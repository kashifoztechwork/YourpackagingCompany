<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class QuickQuotes extends M_Default {

	public	$Required = ['QuickQuote','Product'];
			

	public function __construct(){

		parent::__construct();
		$this->Model = $this->QuickQuote;
	}

	public function Index($Page=0){
		$this->CheckAccess('LIST');
		// Filters
		
		$Data['Filters'] = [
			'Name'=>[
				'Label'=>'Name',
				'Type'=>'Text',
				'Filter'=>['QQ.Name','LIKE']
			],
			'Email'=>[
				'Label'=>'Email',
				'Type'=>'Text',
				'Filter'=>['QQ.Email','LIKE']
			],
			'Colors'=>[
				'Label'=>'Colors',
				'Type'=>'Text',
				'Filter'=>['QQ.Colors','LIKE']
			],
			'Phone'=>[
				'Label'=>'Phone',
				'Type'=>'Text',
				'Filter'=>['QQ.Phone','LIKE']
			],
			'Product'=>[
				'Label'=>'Product Name',
				'Type'=>'Text',
				'Filter'=>['QQ.Product','LIKE']
			]
		];
		//List Headers
		$Data['Headers'] = [
			'ID'=>'{Row.ID}',
			'Name'=>'{Row.Name}',
			'Email'=>'{Row.Email}',
			'Stars'=>'{Row.Phone}',
			'Product'=>'{Row.Product}',
			'Colors'=>'{Row.Colors}',
			'Message'=>'{Row.Config.Message}',
		];
		// Select Fields
		$Data['SelectFields'] = 'QQ.*';
		// Prepare List
		$this->PrepareList($this->Model,$Data,$Page,true);
	}

	public function GetFields($Edit=false){

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
				'Name'=>'Email',
				'Label'=>'Email',
				'Type'=>'Text',
				'Config'=>[
					'Attributes'=>['class'=>'form-control','AA'=>['required']],
					'Template'=>COL6
				],
				'Default'=>'{Row.Email}'
			],
			[
				'Name'=>'Phone',
				'Label'=>'Phone',
				'Type'=>'Text',
				'Config'=>[
					'Attributes'=>['class'=>'form-control','AA'=>['required']],
					'Template'=>COL6
				],
				'Default'=>'{Row.Phone}'
			],
			[
				'Name'=>'Quantity',
				'Label'=>'Phone',
				'Type'=>'Number',
				'Config'=>[
					'Attributes'=>['class'=>'form-control','AA'=>['required']],
					'Template'=>COL6
				],
				'Default'=>'{Row.Quantity}'
			],
			[
				'Name'=>'Colors',
				'Label'=>'Colors',
				'Type'=>'DropDown',
				'Config'=>[
					'Attributes'=>['class'=>'form-control'],
					'Template'=>COL6,
					'Data'=>['1 Color','2 Color','3 Color','4 Color','4/1 Color','4/2 Color','4/3 Color','4/4 Color'],
					'UV'=>true
				],
				'Default'=>'{Row.Colors}'
			],
			[
				'Name'=>'Product',
				'Label'=>'Product',
				'Type'=>'DropDown',
				'Config'=>[
					'Attributes'=>['class'=>'form-control'],
					'Template'=>COL6,
					'Model'=>'Product',
					'Display'=>'{Name}',
					'Fields'=>['ID','Name'],
					'UV'=>true
				],
				'Default'=>'{Row.Product}'
			],
			[
				'Name'=>'Config[Length]',
				'Label'=>'Length',
				'Type'=>'Text',
				'Config'=>[
					'Attributes'=>['class'=>'form-control'],
					'Template'=>COL3
				],
				'Default'=>'{Row.Config.Length}'
			],
			[
				'Name'=>'Config[Width]',
				'Label'=>'Width',
				'Type'=>'Text',
				'Config'=>[
					'Attributes'=>['class'=>'form-control'],
					'Template'=>COL3
				],
				'Default'=>'{Row.Config.Width}'
			],
			[
				'Name'=>'Config[Depth]',
				'Label'=>'Depth',
				'Type'=>'Text',
				'Config'=>[
					'Attributes'=>['class'=>'form-control'],
					'Template'=>COL3
				],
				'Default'=>'{Row.Config.Depth}'
			],
			[
				'Name'=>'Config[Unit]',
				'Label'=>'Unit',
				'Type'=>'DropDown',
				'Config'=>[
					'Attributes'=>['class'=>'form-control'],
					'Template'=>COL3,
					'Data'=>['CM','MM','INCH'],
					'UV'=>true
				],
				'Default'=>'{Row.Config.Unit}'
			],
			[
				'Name'=>'Config[Message]',
				'Label'=>'Message',
				'Type'=>'TextArea',
				'Config'=>[
					'Attributes'=>['class'=>'form-control'],
					'Template'=>COL12
				],
				'Default'=>'{Row.Config.Message}'
			],[
				'Name'=>'Image',
				'Label'=>'Image',
				'Type'=>'File',
				'Config'=>[
					'Attributes'=>['class'=>'form-control','multiple'=>'true'],
					'Template'=>COL12
				]
			]
		];
		
	}

	public function Action($Action,$PK=0){
		if($Action != ''){
			$Input = $this->input;
			if($Action == 'Add' || $Action == 'Edit'){

				$this->SetValidationRules([
					['Name'=>'Name','Label'=>'Name','Rule'=>'required|trim|alpha_numeric_spaces'],
					['Name'=>'Email','Label'=>'Email','Rule'=>'required|trim'],
					['Name'=>'Phone','Label'=>'Phone','Rule'=>'required|trim'],
					['Name'=>'Config[Message]','Label'=>'Message','Rule'=>'required|trim'],
				
				]);
				if(!$this->form_validation->run()){
					return GoBack();
				}
				
				$this->ProcessPost([['Name'],['Email'],['Colors'],['Quantity'],['Phone'],['Product'],['Config']],$Data);
				
				if($Action == 'Edit'){
					$Row = $this->Model->Row($PK);
					$Config = (array)unserialize($Row->Config);
				}

				//Uploading Images
				if($_FILES['Image']['name'][0] != ''){
					$Image = Upload('Image',$Action == 'Edit' ? strtotime($Row->EntryDate) : time(),'IMG','jpg|gif|jpeg|png|bmp');
					if(isset($Image['Errors'])){
						SetMessage(implode('',$Image['Errors']),'Error');
						return GoBack();
					}

					if($Action == 'Edit' && isset($Row->Image)){
						$Data['Image']  = serialize($Image);
					} else {
						$Data['Image']  = serialize($Image);
					}
				}
				// Config
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

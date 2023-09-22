<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Products extends M_Default {

	public	$Required = ['Product','Opportunity'],
			$Editables = [
				'Product'=>[
					'Model'=>'Product',
					'Fields'=>[
						'Keyword'=>'{Row.Keyword}',
						'Brand'=>'{Row.BrandName}',
						'Model'=>'{Row.ModelName}',
						'Name'=>['Name'=>'Name','Label'=>'Product Name','Rule'=>'required|trim|alpha_numeric_spaces','Display'=>'{Row.Name}'],
						'Type'=>['Name'=>'Type','Label'=>'Product Type','Rule'=>'required|trim|alpha_numeric_spaces','Display'=>'{Row.Type}','Type'=>'DropDown','UV'=>false],
						'SKU'=>['Name'=>'SKU','Label'=>'SKU','Rule'=>'required|trim','Display'=>'{Row.SKU}']
					]
				]
			];

	public function __construct(){

		parent::__construct();
		$this->Model = $this->Product;
	}

	public function Index($Page=0){
		$this->CheckAccess('LIST');
		//Page Filters
		$Data['Filters'] = [
			'OpportunityID'=>[
				'Label'=>'Keyword',
				'Type'=>'Text',
				'Filter'=>['PRD.OpportunityID','LIKE']
			],
			'Date'=>[
				'Label'=>'Added Date',
				'Type'=>'Text',
				'Filter'=>['MOD.EntryDate','RANGE']
			]
		];
		$Data['SelectFields'] = 'PRD.*,PMDL.Name ModelName,OPS.Keyword,BRD.Name BrandName';
		$this->Model->UseRelation = ['ProductModels'];
		$this->Model->UseJoins = ['Brands'];
		$Data['Editable'] = $this->Access->HaveAccess('EDIT');
		$Data['EditModel'] = 'Product';
		$this->Editables[$Data['EditModel']]['Fields']['Type']['Data'] = $this->Model->Types;
		$this->PrepareList($this->Model,$Data,$Page);
	}

	public function GetFields($Edit = false,$Row = null){
		return [
			[
				'Name'=>'Name',
				'Label'=>'Product Name',
				'Config'=>[
					'Attributes'=>['class'=>'form-control'],
					'Template'=>COL6
				],
				'Default'=>'{Row.Title}'
			],[
				'Name'=>'ProductModelID',
				'Label'=>'Select Model',
				'Type'=>'DropDown',
				'Config'=>[
					'Model'=>'ProductModel',
					'Fields'=>['PMDL.ID','PMDL.Name','BRD.Name BrandName'],
					'Display'=>'{Name}',
					'GroupBy'=>'BrandName',
					'Uses'=>['Brands'],
					'Attributes'=>['class'=>'form-control','AA'=>['required']],
					'Template'=>COL6
				],
				'Default'=>'{Row.ProductModelID}'
			],[
				'Name'=>'Type',
				'Label'=>'Product Type',
				'Type'=>'DropDown',
				'Config'=>[
					'UV'=>false,
					'Data'=>$this->Model->Types,
					'Attributes'=>['class'=>'form-control'],
					'Template'=>COL6
				],
				'Default'=>'{Row.Type}'
			],[
				'Name'=>'SKU',
				'Label'=>'Product SKU',
				'Config'=>[
					'Attributes'=>['class'=>'form-control'],
					'Template'=>COL6
				],
				'Default'=>'{Row.SKU}'
			]
		];
	}

	public function Action($Action,$PK=0){
		if($Action != ''){
			$Input = $this->input;
			if($Action == 'Add' || $Action == 'Edit'){

				$this->SetValidationRules([
					['Name'=>'Name','Label'=>'Name','Rule'=>'required|trim|alpha_numeric_spaces'],
					['Name'=>'Type','Label'=>'Product Type','Rule'=>'required|trim|alpha_numeric_spaces'],
					['Name'=>'SKU','Label'=>'Product SKU','Rule'=>'required|trim'],
					['Name'=>'ProductModelID','Label'=>'Model','Rule'=>'required|trim|numeric']
				]);
				if(!$this->form_validation->run()){
					return GoBack();
				}

				$this->ProcessPost([['Name'],['Type'],['SKU'],['ProductModelID']],$Data);
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
			return Forword(AURI('Index'));
		}else{
			InvalidData();
		}
	}

	public function __destruct(){
		parent::__destruct();
	}
}

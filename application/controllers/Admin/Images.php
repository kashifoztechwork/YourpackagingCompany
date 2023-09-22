<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Images extends M_Default {

	public	$Required = ['Image'];
			

	public function __construct(){

		parent::__construct();
		$this->Model = $this->Image;
	}

	public function Index($Page=0){
		$this->CheckAccess('LIST');
		// Filters
		
		$Data['Filters'] = [
			'Name'=>[
				'Label'=>'Name',
				'Type'=>'Text',
				'Filter'=>['IMG.Name','LIKE']
			]
		];

		
		//List Headers
		$Data['Headers'] = [
			'ID'=>'{Row.ID}',
			'Name'=>'{Row.Name}',
			'Image'=>'{(GetImage(\'{Row.EntryDate}\',\'{Row.Image}\',array(),true))}',
		];
		
		// Select Fields
		$Data['SelectFields'] = 'IMG.*';
	
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
				'Name'=>'Image',
				'Label'=>'Image',
				'Type'=>'File',
				'Config'=>[
					'Attributes'=>['class'=>'form-control'],
					'Template'=>COL6
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
				]);

				if(!$this->form_validation->run()){
					return GoBack();
				}
				
				
				$this->ProcessPost([['Name']],$Data);

				if($Action == 'Edit'){
					$Row = $this->Model->Row($PK);
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

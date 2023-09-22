<?php

defined('BASEPATH') OR exit('No direct script access allowed');
class Product extends M_Model{

	public	$RunInstaller = false,
			$DBTable = DB_PRODUCT,
			$DBInstance = 'PRD',
			$InsertLogs = ['Title'=>'New Product','Description'=>'New Product {Name} has been added'],
			$UpdateLogs = ['Title'=>'Product Modified','Description'=>'Changes has been made to {Name}'],
			$DeleteLogs = ['Title'=>'Product Removed','Description'=>'Product has been removed'];

	public function __construct(){

    	$this->ResetRelations();
		$this->CustomJoins('CategoryDetails',[
			['Table'=>DB_CATEGORY,'INS'=>'CAT','ForignKey'=>'CAT.ID','HomeKey'=>'PRD.Category','JoinType'=>'INNER']
		]);
		parent::__construct();
	}


	//ProductList
	public function ProductList($Featured=false,$BestSeller=false,$Slider=false,$Limit=0,$Offset=0,$Order='',$Where=NULL,$Col='col-lg-4 col-sm-6'){
		
		$Products = array();
		$this->Product->UseJoins = ['CategoryDetails'];
		
		$Filter = [
			'Status'=>true
		];
		
		if($Featured) $Filter['PRD.Featured'] = $Featured;
		if($BestSeller) $Filter['BestSeller'] = $BestSeller;

		if($Where){
			$this->Product->DB()->where($Where);
		}
		
		$ProductList = $this->Product->GetList($Filter,$Limit,$Offset,'','PRD.Name, PRD.Slug, PRD.Thumbnail, PRD.EntryDate, CAT.Name CategoryName');
		
		$Products['Rows'] = $ProductList->Rows;

		if($ProductList->Result){
			foreach($ProductList->Result as $Product){
					//$Thumbnail = array();
					//if(!empty($Product->Thumbnail)){
					    //$Thumbnail = array_values(unserialize($Product->Thumbnail));
					//}
				  
				    //def($Thumbnail,'Thumbnail');
					def($Product,'Product');
					$Products['Result'][] = sprintf(
									'
								%s
								<div class="product-item-two wow fadeInUp delay-0-2s">
									<div class="image">
										%s
										%s
									</div>
									<div class="content">
										<div class="title-price">
											<span class="category">%s</span>
											<h5>%s</h5>
										</div>
										%s
									</div>
								</div>
								%s
								',$Slider ? '' : sprintf('<div class="%s">',$Col),
								$Featured ? '<span class="badge bg-danger">HOT</span>' : '',
								_A(GetImage('{Product.EntryDate}','{Product.Thumbnail}'),URI(sprintf('product/%s',Slug($Product->Slug))),[],true,true),
								$Product->CategoryName,
								$Product->Name,
								_A('Shop Now <i class="fa fa-long-arrow-right"></i>',URI(sprintf('product/%s',Slug($Product->Slug))),['class'=>'theme-btn style-three'],true,true),
								$Slider ? '' : '</div>'
							);
					
				}
		}
		
		return $Products;
	}



	public function Installation(){

		if(DEBUG){
			$this->CreateTable([
				'Table'=>$this->DBTable,
				'Fields'=>[
					[
						'Key'=>'L',
						'Name'=>'Name',
						'Length'=>120,
						'Type'=>'varchar',
						'NULL'=>false
					],
					[
						'Key'=>'L',
						'Name'=>'Category',
						'Length'=>11,
						'Type'=>'int',
						'NULL'=>false
					],
					[
						'Key'=>'L',
						'Name'=>'Specification',
						'Length'=>0,
						'Type'=>'text',
						'NULL'=>true
					],
					[
						'Key'=>'L',
						'Name'=>'Descriptions',
						'Length'=>0,
						'Type'=>'text',
						'NULL'=>true
					],
					[
						'Key'=>'L',
						'Name'=>'Status',
						'Length'=>0,
						'Type'=>'bool',
						'NULL'=>true
					],
					[
						'Key'=>'L',
						'Name'=>'Featured',
						'Length'=>0,
						'Type'=>'bool',
						'NULL'=>true
					],
					[
						'Key'=>'L',
						'Name'=>'BestSeller',
						'Length'=>0,
						'Type'=>'bool',
						'NULL'=>true
					],
					[
						'Key'=>'L',
						'Name'=>'Images',
						'Length'=>0,
						'Type'=>'text',
						'NULL'=>true
					],
					[
						'Key'=>'L',
						'Name'=>'Config',
						'Length'=>0,
						'Type'=>'text',
						'NULL'=>true
					],
					[
						'Key'=>'L',
						'Name'=>'Thumbnail',
						'Length'=>0,
						'Type'=>'text',
						'NULL'=>true
					],
					[
						'Key'=>'L',
						'Name'=>'IsIndex',
						'Length'=>0,
						'Type'=>'bool',
						'NULL'=>true
					],
					[
						'Key'=>'L',
						'Name'=>'Slug',
						'Length'=>255,
						'Type'=>'varchar',
						'NULL'=>false
					]
				]
			]);
		}
	}
}

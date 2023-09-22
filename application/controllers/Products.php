<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Products extends M_Default {

	public $LoginRequired = false,
		   $Required = ['Review'];

	public function __construct(){
		parent::__construct();
		$this->Model = $this->Product;
	}
	
	public function Index($Slug=NULL) {
		$this->Bundels->Snippet('PostScripts','
			<script>
				$(document).ready(function(){
					$(\'[data-bs-toggle=tab]\').click(function(){
						let ID = parseInt($(this).attr(\'data-active\'));
						
						Parent = $("div[data-type=ID]");
						console.log(Parent);
					});
				});
			</script>
		');
	
		if(!empty($Slug)){
				// Products
				$this->Model->UseJoins = ['CategoryDetails'];
				$Product = $this->Model->Row(['PRD.Slug'=>$Slug],'','PRD.*, CAT.Name CategoryName');
				
				if($Product){
					// Reviewse
					$Reviews = $this->Review->GetList(['REV.Product'=>$Product->ID,'REV.Status'=>true]);
					// Tag
					$Tag = $this->Tag->Row(['TAG.Type'=>'Product','TAG.TypeID'=>$Product->ID]);
					// Is Index
					$IsIndex = empty($Product->IsIndex) ? '<meta name="robots" content="noindex,nofollow">' : '';
		
					$Data = [
						'Title'=>$Tag->Title ? $Tag->Title : $Product->Name,
						'Description'=>$Tag->Description,
						'Keywords'=>$Tag->Keywords,
						'SchemaTag'=>$Tag->SchemaTag,
						'Product'=>$Product,
						'Reviews'=>$Reviews,
						'RelatedSlide'=>$this->Product->ProductList(true,false,true)['Result'],
						'Widgets'=>$this->Widget->GetList(['WGT.Page'=>'All'])->Result,
						'ProductWidgets'=>$this->Widget->GetList(['WGT.Page'=>'Product'])->Result,
						'WhatWeProvide'=>$this->Widget->Row(['WGT.Name'=>'WhatWeProvide'])->Config,
						'IsIndex'=>$IsIndex,
						'ProductStaticBanner'=>$this->Widget->Row(['WGT.Name'=>'ProductStaticBanner'])->Config,
					];
					$this->View('Product/Index',$Data);	
			} else {
					SetMessage('Product not found','Error');
					Forword(URI('/'));
				}
			
		} else {
			Forword(URI('/'));
		}
	
	}
	
}

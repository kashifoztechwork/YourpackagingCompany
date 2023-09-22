<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Categories extends M_Default {

	public $LoginRequired = false;

	public function __construct(){
		parent::__construct();
		$this->Model = $this->Category;
	}
	
	public function Index($Category=NULL) {
		//Query String
		$QS = isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] != '' ? sprintf('%s',$_SERVER['QUERY_STRING']) : '';
        parse_str($QS,$QueryString);
        
        $CurrentPage = 1;
        $Offset = 0;
        $Clouse = null;

        if($QueryString){

            if(isset($QueryString['p']) && $QueryString['p'] > 1) {
    			$Offset = ($QueryString['p'] * 24) - 24;
    			$CurrentPage =  $QueryString['p'];
    		} else {
    			$Offset = 0;
    			$CurrentPage = 1;
    		}
			
			if(!empty($QueryString['search'])) {
				$this->Product->DB()->like('{P}PRD.Name',$QueryString['search']);  
				$Tag->Title = sprintf('Search: %s', $QueryString['search']);
				$Tag->Description = $QueryString['search'];
				$Tag->Keywords = $QueryString['search'];
				$CurrentCategory->IsIndex = 0;
			}
			
        }
		
	
		// Category
		if($Category){
			$Category = ucfirst(str_replace('-',' ',$Category));
			$CurrentCategory = $this->Category->Row(['CAT.Name'=>$Category]);
			if($Category === 'Custom boxes') {
				$Clouse = '';
				$TotalProduct = $this->Product->Count(['Status'=>true]);
			} else {
				$Clouse = sprintf('{P}CAT.Name = \'%s\'',$Category);
			}
			// Tag
			$Tag = $this->Tag->Row(['TAG.Type'=>'Category','TAG.TypeID'=>$CurrentCategory->ID]);
			// Total Product Count by Category ID
			$TotalProduct = $this->Product->Count(['Status'=>true,'Category'=>$CurrentCategory->ID]);
		}

		
		// Products
		$Products = $this->Product->ProductList(false,false,false,24,$Offset,'',$Clouse,'col-lg-3 col-md-4 col-sm-6 col-12');
		
		$Data = [
			'Title'=>$Category ? $Tag->Title : 'Our Boxes & Templates',
			'Description'=>$Tag->Description,
			'Keywords'=>$Tag->Keywords,
			'SchemaTag'=>$Tag->SchemaTag,
			'List'=>$Products['Result'],
			'Total'=>$TotalProduct,
			'CurrentPage'=>$CurrentPage,
			'Content'=> unserialize($CurrentCategory->Config)['Content'],
			'FrontTitle'=> !empty($QueryString['search']) ? sprintf('Search: %s', $QueryString['search']) : unserialize($CurrentCategory->Config)['FrontTitle'],
			'LongDescription'=> unserialize($CurrentCategory->Config)['LongDescription'],
			'Widgets'=>$this->Widget->GetList(['WGT.Page'=>'All'])->Result,
			'CategoryWidgets'=>$this->Widget->GetList(['WGT.Page'=>'Category'])->Result,
			'WhatWeProvide'=>$this->Widget->Row(['WGT.Name'=>'WhatWeProvide'])->Config,
			'IsIndex'=> empty($CurrentCategory->IsIndex) ? '<meta name="robots" content="noindex,nofollow">' : ''
		];
		
		$this->View('Category/Index',$Data);
	}
	
}

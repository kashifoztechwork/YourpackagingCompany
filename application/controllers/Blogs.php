<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Blogs extends M_Default {

	public $LoginRequired = false;

	public function __construct(){
		parent::__construct();
		$this->Model = $this->Blog;
	}
	
	public function Index() {
		//Query String
		$QS = isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] != '' ? sprintf('%s',$_SERVER['QUERY_STRING']) : '';
        parse_str($QS,$QueryString);
        
        $CurrentPage = 1;
        $Offset = 0;
        
        if($QueryString){
            if($QueryString['p'] > 1) {
    			$Offset = ($QueryString['p'] * 12) - 12;
    			$CurrentPage =  $QueryString['p'];
    		} else {
    			$Offset = 0;
    			$CurrentPage = 1;
    		}
        }
	
		// Blogs
		$Blogs = $this->Blog->BlogList(false,false,12,$Offset,'','','col-lg-3 col-md-4 col-sm-6 col-12');
		$IsIndex = '';
		if($Tag = $this->Tag->Row(['Type'=>'Static','Name'=>'Blog'])){
			$IsIndex = empty($Tag->IsIndex) ? '<meta name="robots" content="noindex,nofollow">' : '';
		}

		$Data = [
			'Title'=>$Tag ? $Tag->Title : 'Blog Posts',
			'Description'=>$Tag ? $Tag->Description : '',
			'Keywords'=>$Tag ? $Tag->Keywords : '',
			'SchemaTag'=>$Tag->SchemaTag,
			'List'=>$Blogs['Result'],
			'Total'=>$Blogs['Rows'],
			'CurrentPage'=>$CurrentPage,
			'Widgets'=>$this->Widget->GetList(['WGT.Page'=>'All'])->Result,
			'BlogWidgets'=>$this->Widget->GetList(['WGT.Page'=>'Product'])->Result,
			'WhatWeProvide'=>$this->Widget->Row(['WGT.Name'=>'WhatWeProvide'])->Config,
			'IsIndex'=>$IsIndex
		];
		
		$this->View('Blog/Index',$Data);
	}
	
	public function Detail($Post){
		if($Post){
			$Slug = $Post ? ucfirst(str_replace('-',' ',$Post)) : '';

			if($Post = $this->Model->Row(['Slug'=>$Slug])){
				
				// Tag
				$Tag = $this->Tag->Row(['TAG.Type'=>'Blog','TAG.TypeID'=>$Post->ID]);

				$Data = [
					'Title'=>$Tag->Title ? $Tag->Title : $Post->Name,
					'Description'=>$Tag->Description ? $Tag->Description : '',
					'Keywords'=>$Tag->Keywords ? $Tag->Keywords : '',
					'Post'=>$Post,
					'RelatedPost'=> $this->Model->BlogList(false,true)['Result'],
					'Widgets'=>$this->Widget->GetList(['WGT.Page'=>'All'])->Result,
					'BlogWidgets'=>$this->Widget->GetList(['WGT.Page'=>'Product'])->Result,
					'WhatWeProvide'=>$this->Widget->Row(['WGT.Name'=>'WhatWeProvide'])->Config
				];

				$this->View('Blog/Detail',$Data);
	
			} 

		} else {
			
		}
	}
}

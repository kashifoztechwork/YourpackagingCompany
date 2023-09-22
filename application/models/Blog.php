<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Blog extends M_Model{

	public	$RunInstaller = false,
			$DBTable = DB_BLOG,
			$DBInstance = 'BLG',
			$EnableLogs = false;

	public function __construct(){

		$this->ResetRelations();
		parent::__construct();
	}

	//ProductList
	public function BlogList($Featured=false,$Slider=false,$Limit=0,$Offset=0,$Order='',$Where=NULL,$Col='col-lg-4 col-sm-6'){
		
		$Blogs = array();
		$Filter = array();
		if($Featured) $Filter['Featured'] = $Featured;
		
		if($Where){
			$this->Blog->DB()->where($Where);
		}

		$BlogList = $this->Blog->GetList($Filter,$Limit,$Offset,'','BLG.Name, BLG.Image, BLG.EntryDate, BLG.Slug');
		$Blogs['Rows'] = $BlogList->Rows;

		if($BlogList->Result){
			foreach($BlogList->Result as $Blog){
					$Image = array();
					
					def($Blog,'Blog');
					
					if($Slider){
						$Blogs['Result'][] = sprintf('
						<div class="blog-item-two wow fadeInUp delay-0-2s">
							<div class="image">
								%s
							</div>
							<div class="content">
								<span class="date"><i class="fa fa-calendar-alt"></i> %s</span>
								<h4>%s</h4>
								%s
							</div>
						</div>
						',GetImage('{Blog.EntryDate}','{Blog.Image}'),$Blog->EntryDate,_A($Blog->Name,URI(sprintf('blog/%s',Slug($Blog->Slug))),[],true,true),_A('Read More <i class="fa fa-long-arrow-right"></i>',URI(sprintf('blog/%s',Slug($Blog->Slug))),['class'=>'read-more'],true,true));
					} else {
						$Blogs['Result'][] = sprintf('
						<div class="%s">
							<div class="blog-standard-item wow fadeInUp delay-0-2s">
								<div class="image">
									%s
								</div>
								<div class="content">
									<ul class="blog-meta">
										<li><i class="fa fa-calendar-alt"></i> %s</li>
									</ul>
									<h4>%s</h4>
									%s
								</div>
							</div>
						</div>
						',$Col,GetImage('{Blog.EntryDate}','{Blog.Image}'),$Blog->EntryDate,_A($Blog->Name,URI(sprintf('blog/%s',Slug($Blog->Slug))),[],true,true),_A('Read More <i class="fa fa-long-arrow-right"></i>',URI(Slug($Blog->Slug)),['class'=>'read-more'],true,true));
					}
				}
		}
		
		return $Blogs;
	}

	public function Installation(){

		if(DEBUG){
			$this->CreateTable([
				'Table'=>$this->DBTable,
				'Fields'=>[
					[
						'Key'=>'L',
						'Name'=>'Name',
						'Length'=>255,
						'Type'=>'varchar',
						'NULL'=>false
					],
					[
						'Key'=>'L',
						'Name'=>'Slug',
						'Length'=>255,
						'Type'=>'varchar',
						'NULL'=>false
					],[
						'Key'=>'L',
						'Name'=>'Image',
						'Length'=>0,
						'Type'=>'text',
						'NULL'=>true
					],[
						'Key'=>'L',
						'Name'=>'Content',
						'Length'=>0,
						'Type'=>'text',
						'NULL'=>true
					],[
						'Key'=>'L',
						'Name'=>'Config',
						'Length'=>0,
						'Type'=>'text',
						'NULL'=>true
					],[
						'Key'=>'L',
						'Name'=>'Featured',
						'Length'=>0,
						'Type'=>'bool',
						'NULL'=>true
					]
					
				]
			]);
		}
	}
}

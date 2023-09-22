<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Home extends M_Default {

	public $LoginRequired = false;

	public function __construct(){
		parent::__construct();
	}
	// Slides
	private function Slides(){
		$Sliders = $this->Slider->GetList();
	
		$Sliders = $Sliders->Result;
		$Slides = array();
		if($Sliders){
			$K = 1;
			foreach($Sliders as $Slider){
				$Slider->Image = unserialize($Slider->Image);
				def($Slider,'Slider');
				$Slides[$K] = sprintf('<div class="carousel-item %s">%s</div>',($K == 1 ? 'active' : ''), _A(GetImage('{Slider.EntryDate}','{Slider.Image}',['class'=>'d-block w-100']),$Slider->Link));
				$K++;
			}
		}

		return $Slides;
	}
	
	public function Index() {
		$MetaTags = $this->Setting->Row(['ID'=>1]);
		$MetaTags->Config = unserialize($MetaTags->Config);
		
		def($MetaTags,'MetaTags');
		$Data = [
			'Title'=>_E('{MetaTags.Config.Title}'),
			'Keywords'=>_E('{MetaTags.Config.Keywords}'),
			'Description'=>_E('{MetaTags.Config.Description}'),
			'SchemaTag'=>_E('{MetaTags.Config.SchemaTag}',true),
			'Slides'=> $this->Slides(),
			'Featured'=> $this->Product->ProductList(true,false,true)['Result'],
			'BestSelling'=> $this->Product->ProductList(false,true,false,12,0,'','','col-lg-3 col-md-4 col-sm-6 col-12')['Result'],
			'Blogs'=> $this->Blog->BlogList(false,true)['Result'],
			'WhatWeProvide'=>$this->Widget->Row(['WGT.Name'=>'WhatWeProvide'])->Config,
			'WhyChooseUs'=>$this->Widget->Row(['WGT.Name'=>'WhyChooseUs'])->Config,
			'FAQs'=>$this->Widget->Row(['WGT.Name'=>'FAQs'])->Config,
			'About'=>$this->Widget->Row(['WGT.Name'=>'About'])->Config
		];

		$this->View('Home/Index',$Data);
	}
	
    public function SubmitQuote(){
	
		$config = array(
			"protocol"   => "SMTP",
			"smtp_host"  => "yourpackagingcompany.com",
			"smtp_port"  => "465",
			"smtp_timeout"  => "7",
			"smtp_user"  => 'sales@yourpackagingcompany.com',
			"smtp_pass"  => "YPC@123456789#", // please take this from google app password services...
			"starttls"   => TRUE,
			"charset"    => "utf-8",
			"mailtype"   => "html", // or text/plain
			"wordwrap"   => TRUE,
			"newline"    => "\r\n",
			"validate"    => FALSE,
			"smtp_keep_alive"    => TRUE
		);
		$this->load->library('email', $config);
	
		if($this->input){
			$Input = $this->input;
		
			$this->SetValidationRules([
				['Name'=>'Name','Label'=>'Name','Rule'=>'trim|alpha_numeric_spaces'],
				['Name'=>'Email','Label'=>'Email','Rule'=>'trim'],
				['Name'=>'Phone','Label'=>'Phone','Rule'=>'trim'],
				['Name'=>'Config[Message]','Label'=>'Message','Rule'=>'trim'],
				['Name'=>'Image','Label'=>'Image','Rule'=>'trim']
			]);

			if(!$this->form_validation->run()){
				return GoBack();
			}

			$this->ProcessPost([['Name'],['Email'],['Colors'],['Quantity'],['Phone'],['Config'],['Image']],$Data);
			// Config
			$Data['Config'] = serialize($Data['Config']);

			if($Input->post('Product')){
				$Data['Product'] = $Input->post('Product');
			} else {
				$Data['Product'] = 1;
			}
			$Data['Image'] = 'No Image';
            //print_R($Data);
            //$Data['Profile'] = 0;
			$PK = $this->QuickQuote->Insert($Data);

			if($PK > 0){
				// Send Email
				$this->email->from($Data['Email'], $Data['Name']);
				$this->email->to('sales@yourpackagingcompany.com');
				$this->email->subject(sprintf('Free Quote Request by %s', $Data['Name']));
				$this->email->message(sprintf('
					Request ID: %s <br />
					Name: %s <br />
					Email: %s<br />
					Colors: %s <br />
					Quantity: %s<br />
					Phone: %s<br />
					Message: %s '
				, $PK,$Data['Name'], $Data['Email'], $Data['Colors'], $Data['Quantity'], $Data['Phone'], $Input->post('Config')['Message']));
				 
				if($this->email->send()){
					SetMessage('ENTRYSUCCESS');
				} else {
					SetMessage('ENTRYFAILED');
				}

			}else{
				SetMessage('ENTRYFAILED');
			}
		    Forword(URI(''));
		} else {
			InvalidData();
		}
	}


	public function Contact($Action=null){
		// Get Logo
		$SiteInfo = $this->Setting->Row(['ID'=>1]);
		if($Action != ''){
			if($Action = "submit"){
				$Input = $this->input;
				
				$this->SetValidationRules([
					['Name'=>'Name','Label'=>'Name','Rule'=>'trim|alpha_numeric_spaces'],
					['Name'=>'Email','Label'=>'Email','Rule'=>'trim|alpha_numeric_spaces'],
					['Name'=>'Phone','Label'=>'Phone','Rule'=>'trim|alpha_numeric_spaces'],
					['Name'=>'Subject','Label'=>'Subject','Rule'=>'trim|alpha_numeric_spaces'],
					['Name'=>'Message','Label'=>'Message','Rule'=>'trim|alpha_numeric_spaces'],
				
				]);
	
				if(!$this->form_validation->run()){
					return GoBack();
				}
	
				
				$this->ProcessPost([['Name'],['Email'],['Phone'],['Subject'],['Message']],$Data);
				if($PK = $this->Contact->Insert($Data)){
					if($PK > 0){
            		$config = array(
            			"protocol"   => "SMTP",
            			"smtp_host"  => "yourpackagingcompany.com",
            			"smtp_port"  => "465",
            			"smtp_timeout"  => "7",
            			"smtp_user"  => 'sales@yourpackagingcompany.com',
            			"smtp_pass"  => "YPC@123456789#", // please take this from google app password services...
            			"starttls"   => TRUE,
            			"charset"    => "utf-8",
            			"mailtype"   => "html", // or text/plain
            			"wordwrap"   => TRUE,
            			"newline"    => "\r\n",
            			"validate"    => FALSE,
            			"smtp_keep_alive"    => TRUE
            		);
            		
            		$this->load->library('email', $config);
						// Send Email
						$this->email->from($Data['Email'], $Data['Name']);
						$this->email->to('sales@yourpackagingcompany.com');
						$this->email->subject(sprintf('Subject %s', $Data['Subject']));
						$this->email->message(sprintf('
							Name: %s
							Email: %s
							Phone: %s
							Subject: %s
							Message: %s'
						, $Data['Name'], $Data['Email'], $Data['Phone'], $Data['Subject'], $Data['Message']));
						
						if($this->email->send()){
							SetMessage('ENTRYSUCCESS');
							Forword(URI());
						} else {
							SetMessage('ENTRYFAILED');
							Forword(URI());
						}
	
					}
				
				} else {
					SetMessage('ENTRYFAILED');
					Forword(URI());
				}
				
			} else {
				InvalidData();
			}
		}
		
		$IsIndex = '';
		if($Tag = $this->Tag->Row(['Type'=>'Static','Name'=>'Contact'])){
			$IsIndex = empty($Tag->IsIndex) ? '<meta name="robots" content="noindex,nofollow">' : '';
		}

		$Data = [
			'SiteInfo'=>$SiteInfo,
			'Title'=>$Tag ? $Tag->Title : 'Contact',
			'Description'=>$Tag ? $Tag->Description : '',
			'Keywords'=>$Tag ? $Tag->Keywords : '',
			'IsIndex'=>$IsIndex
		];

		$this->View('Home/Contact',$Data);
	}
}

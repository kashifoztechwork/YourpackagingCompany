<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Profiles extends M_Default {

	public	$Required = ['Profile'],
			$LoginRequired = false;

	public function __construct(){

		parent::__construct();
	}

	public function Login(){
		if($this->Profile->LoggedIn()){
			return Forword(URI(''));
		}
		$this->PartialView('Profiles/Login',['Title'=>'Please Login To Continue'],'Partial',true);
	}

	public function ProcessLogin(){
		if(PS('Login')){
			//Setting Validation Rules For Form
			$this->SetValidationRules([
				['Name'=>'Email','Label'=>'Email Address','Rule'=>'required|trim|valid_email'],
				['Name'=>'Password','Label'=>'Your Password','Rule'=>'required|trim']
			]);
			if(!$this->form_validation->run()){
				return GoBack();
			}

			$Email = PS('Email');
			$Password = Encrypt(PS('Password'));
			
			if($Login = $this->Profile->Login(['PRF.Email'=>$Email,'PRF.Password'=>$Password,'PRF.Status'=>'Active'])){
				return Forword(URI('',true));
			}else{
				SetMessage('Emai Address / Passowrd is Invalid');
			}
		}
		return GoBack();
	}

	public function Logout(){
		$this->Profile->Logout();
		return Forword(URI('Login',true));
	}

	public function __destruct(){
		parent::__destruct();
	}
}

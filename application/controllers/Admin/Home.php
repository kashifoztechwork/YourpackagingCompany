<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Home extends M_Default {

	public function __construct(){

		parent::__construct();
	}

	public function Index()
	{
		$this->View('Home/Index',['Title'=>'Welcome To Admin Panel'],true);
	}
}

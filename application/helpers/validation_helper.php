<?php
defined('BASEPATH') OR exit('No direct script access allowed');
function AccessDenied(){
	exit('Access denied');
}

function InvalidData(){
	if(Ajax()){
		echo JsonOut([],403,'Invalid Data');
		return;
	}else{
		exit('Invalid data');
	}
}

function ValidNIC($NIC){
	return true;
}

function ValidTime($Time){
	if(preg_match('/(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]/',$Time)){
		return $Time;
	}
	return false;
}

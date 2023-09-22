<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	$active_group = 'default';
	$query_builder = TRUE;

	$db['default'] = array(
		'dsn'	=> '',
		'hostname' => 'localhost',
		'username' => 'root',
		'password' => '',
		'database' => 'packagi1_ypc',
		// 'username' => 'washpqvk_user',
		// 'password' => 'Kingking@78622',
		// 'database' => 'washpqvk_db',
		'dbdriver' => 'mysqli',
		'dbprefix' => 'ypc_',
		'pconnect' => FALSE,
		'db_debug' => (ENVIRONMENT !== 'development'),
		'cache_on' => FALSE,
		'cachedir' => 'application/cache',
		'char_set' => 'utf8',
		'dbcollat' => 'utf8_general_ci',
		'swap_pre' => '{P}',
		'encrypt' => FALSE,
		'compress' => FALSE,
		'stricton' => FALSE,
		'failover' => array(),
		'save_queries' => TRUE
	);

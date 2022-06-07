<?php
/**
 * DorewSite Software
 * Author: Dorew
 * Email: khanh65me1@gmail.com or awginao@protonmail.com
 * Website: https://dorew.gq
 * License: license.txt
 * Copyright: (C) 2022 Dorew All Rights Reserved.
 * This file is part of the source code.
 */

defined('_DOREW') or die('Access denied');

include('database/DB.php');

use astute\CodeIgniterDB as CI;

//connection to database
require_once $_SERVER['DOCUMENT_ROOT'] . '/cms/core.php';
$db_data = array(
	'dsn'	=> '',
	'hostname' => $db_host,
	'username' => $db_user,
	'password' => $db_pass,
	'database' => $db_name,
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => TRUE,
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);
$db = &CI\DB($db_data);


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

$root = $_SERVER['DOCUMENT_ROOT'];
$http_host = $_SERVER['HTTP_HOST'];
$request_uri = $_SERVER['REQUEST_URI'];

//database config
$type_db = 'mysqli';
$url_phpmyadmin = 'http://localhost:8080/phpmyadmin';
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'dorew_upi';

//admin account infomation
$account_admin = 'admin';
$password_admin = 'dorew';
$passMd5 = md5(md5(md5($password_admin)).'dorew');
$new_password = sha1(substr($passMd5, 0, 8));

//file config
$default_index = 'index';
$default_404 = '_404';
$dir_tpl = $root.'/cms/template';
$dir_backup = $root.'/cms/backup';
$image_ext = ['png', 'jpg', 'jpeg', 'gif', 'ico', 'svg', 'bmp', 'tiff', 'tif', 'webp', 'psd'];


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
$current_version = '1.0.1';

$php_server = strtolower($_SERVER['SERVER_SOFTWARE']);
if (strpos($php_server, 'nginx') !== false) {
    die('Chỉ hỗ trợ Apache Server!');
    exit;
}

//database config
$url_phpmyadmin = 'http://127.0.0.1/phpmyadmin';
$db_host = 'localhost';
$db_user = 'dorewgs';
$db_pass = '';
$db_name = 'dorewsite';

//admin account infomation
$account_admin = 'admin';
$password_admin = 'dorew';
$passMd5 = md5(md5(md5($password_admin)).'dorew');
$new_password = sha1(substr($passMd5, 0, 8));

//file config
$image_ext = ['png', 'jpg', 'jpeg', 'gif', 'ico', 'svg', 'bmp', 'tiff', 'tif', 'webp', 'psd'];
$default_index = 'index.html';
$default_404 = '_404';
$default_login = 'dorew';
$dir_backup = $root.'/assets/template/backup';
$dir_tpl = $root.'/assets/template/get';

//recaptcha google
$g_site_key = '6LerhaYbAAAAAG5MsOgY6w7cjbvJjU61hGfPqSRU';
$g_secret_key = '6LerhaYbAAAAAF_qswE64H5DdqoukhAhKnxd6nrQ';

//sjc gold
$sjc_gold = 'https://SITE_NAME.workers.dev/?url=https://sjc.com.vn/xml/tygiavang.xml';
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

$current_version = '0.3.0';
$notify_update_version = 'display';
$code_mirror = 'on';

//database config
$type_db = 'phpSQLite3';
$config_db = [
    'QuerySQL' => [
        'pma' => 'http://localhost:8080/phpmyadmin/',
        'host' => 'localhost',
        'user' => 'root',
        'pass' => '',
        'db' => 'dorewsite',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci'
    ],
    'phpSQLite3' => [
        'directory' => $root . '/cms/database/',
        'name' => 'DorewSite',
        'path' => 'database.sqlite'
    ],
];

//admin account infomation
$account_admin = 'admin';
$password_admin = 'dorew';
$passMd5 = md5(md5(md5($password_admin)) . 'dorew');
$new_password = sha1(substr($passMd5, 0, 8));

//file config
$image_ext = ['png', 'jpg', 'jpeg', 'gif', 'ico', 'svg', 'bmp', 'tiff', 'tif', 'webp', 'psd'];
$default_index = 'index';
$default_404 = '_404';
$default_login = 'dorew';
$dir_backup = $root . '/cms/backup';
$dir_tpl = $root . '/cms/template/' . $type_db;

$uri_segments = explode('/', $request_uri);
if (!mysqli_connect(
    $config_db['QuerySQL']['host'],
    $config_db['QuerySQL']['user'],
    $config_db['QuerySQL']['pass'],
    $config_db['QuerySQL']['db']
) && $uri_segments[1] != 'cms') {
    header('Location: /cms');
    exit();
}

if ($type_db == 'phpSQLite3') {
    if (!class_exists('SQLite3')) {
        $type_db = 'QuerySQL';
    }
}

$list_type_db = ['SQLite', 'MySQL'];
$get_type_db = ['phpSQLite3', 'QuerySQL'];
$current_type_db = str_replace($get_type_db, $list_type_db, $type_db);
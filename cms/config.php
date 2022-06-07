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

error_reporting(E_ALL & ~E_NOTICE);
ini_set('default_charset', 'UTF-8');
date_default_timezone_set('Asia/Ho_Chi_Minh');

require_once $_SERVER['DOCUMENT_ROOT'] . '/cms/core.php';

//connect database
$db = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

//check connection
$uri_segments = explode('/', $request_uri);
if (!$db && $uri_segments[1] != 'cms') {
    header('Location: /cms');
    exit();
}

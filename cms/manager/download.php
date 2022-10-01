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

define('_DOREW', 1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/cms/config.php';
require_once $root . '/cms/layout/func.php';

if (!is_login()) {
    header('Location: /');
    exit;
}

$filename = htmlspecialchars(addslashes($_GET['get']));
if (file_exists($dir_backup . '/' . $filename)) {
    $real_link = $dir_backup . '/' . $filename;
    $new_filename =  $filename;
    $fp = fopen($real_link, 'rb');
    header("Content-Type: application/octet-stream");
    header("Content-Disposition: attachment; filename=$new_filename");
    header("Content-Length: " . filesize($real_link));
    fpassthru($fp);
} else {
    echo 'Template không tồn tại!';
    header('Refresh: 3; url=/cms');
    exit;
}
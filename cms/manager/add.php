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
$title = 'Template | Tạo tâp tin mới';
include $root . '/cms/layout/header.php';
require_once $root . '/cms/layout/func.php';

if (is_login()) {
    $filename = rwurl(htmlspecialchars($_POST['name']));
    if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
        file_put_contents($dir_tpl . '/' . $filename, 'Xin chào, đây là một tập tin mới');
        header('Location: /cms/manager/edit.php?file=' . $filename);
    }
    echo '
    <div class="phdr"><a href="/cms" title="Quản lý tập tin"><i class="fa fa-tachometer" aria-hidden="true"></i> Quản lý tập tin</a> | <b>Tạo mới</b></div>
    <div class="menu" style="text-align:center">
        <form method="post" action="">
            <p><b>Nhập tên tập tin:</b></p>
            <p><input type="text" name="name" value="" /></p>
            <p><button type="submit" class="button">Tạo ngay</button></p>
        </form>
    </div>
    ';
} else {
    header('Location: /cms');
}
include $root . '/cms/layout/footer.php';

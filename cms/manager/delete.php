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
$title = 'Template | Dọn dẹp';
include $root . '/cms/layout/header.php';
require_once $root . '/cms/layout/func.php';

if (is_login()) {
    if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
        remove_dir($dir_tpl);
        mkdir($dir_tpl);
        header('Location: /cms/manager');
        exit();
    }
    echo '
    <div class="phdr"><a href="/cms" title="Quản lý tập tin"><i class="fa fa-tachometer" aria-hidden="true"></i> Quản lý tập tin</a> | <b>Dọn dẹp</b></div>
    <div class="menu" style="text-align:center">
        <form method="post" action="">
            <p><b style="color:red">Bạn có thực sự muốn xoá toàn bộ tập tin hiện có không?</b></p>
            <p><button type="submit" class="button">Xoá luôn ngại gì</button></p>
            <p><span style="color:#444;font-size:14px">(Trước khi dọn sạch, bạn có thể <a href="/cms/manager/backup.php">sao lưu chúng</a>)</span></p>
        </form>
    </div>
    ';
} else {
    header('Location: /cms');
    exit();
}
include $root . '/cms/layout/footer.php';

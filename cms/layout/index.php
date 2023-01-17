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

ini_set('display_errors', 0);
require_once $_SERVER['DOCUMENT_ROOT'] . '/cms/config.php';
require_once $root . '/cms/layout/func.php';

if (is_login()) {
    include $root . '/cms/layout/header.php';
    echo '
    <div class="phdr"><b><i class="fa fa-tachometer" aria-hidden="true"></i> Quản lý tập tin</b></div>';
    $list_layout = ['mobile', 'desktop'];
    $get_layout = ['wap', 'web'];
    $description = ['Giao diện mobile', 'Giao diện web'];
    $current_layout = str_replace($get_layout, $description, $default_layout);
    if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
        $check_layout = htmlspecialchars($_POST['check_layout']);
        if (in_array($check_layout, $list_layout)) {
            echo '
            <div class="gmenu">Thay đổi thành công!</div>';
            setcookie('layout', $check_layout, date('U') + (86400 * 30),'/cms');
            header('Refresh: 3; url=/cms/layout');
        } else echo '
        <div class="rmenu">Thay đổi không thành công. Loại bố cục giao diện không hợp lệ!</div>';
    }
    echo '
    <div class="menu" style="text-align:center">
        <p><b>Bạn đang thực hiện chỉnh sửa các tập tin cho <span style="color:red">' . $current_layout . '</span>.</b><br/> Bạn có muốn thay đổi không?</p>
        <p>
            <form action="" method="post">
                <select name="check_layout">
                    ';
    foreach ($list_layout as $key => $value) {
        if ($get_layout[$key] == $default_layout) {
            echo '<option value="' . $value . '" selected>' . $description[$key] . '</option>';
        } else {
            echo '<option value="' . $value . '">' . $description[$key] . '</option>';
        }
    }
                    echo '
                </select>
                <br/><button type="submit" class="button">Thay đổi</button>
            </form>
        </p>
    </div>';
    include $root . '/cms/layout/footer.php';
} else {
    header('Location: /cms');
    exit();
}
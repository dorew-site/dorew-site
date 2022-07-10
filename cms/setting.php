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
$title = 'Cấu hình tập tin';
include $root . '/cms/layout/header.php';

if (!$db || !is_login()) {
    header('Location: /cms');
    exit();
} else {
    echo '<div class="phdr"><b><i class="fa fa-cog" aria-hidden="true"></i> Cài đặt mặc định</b></div>';
    if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
        $new_index = strtolower($_POST['new_index']) ?: 'index';
        $new_404 = strtolower($_POST['new_404']) ?: '_404';
        $new_login = strtolower($_POST['new_login']) ?: 'dorew';
        $new_notify_update_version = strtolower($_POST['new_version']) ?: 'hidden';
        //get old page
        $old_core = file_get_contents($root . '/cms/core.php');
        //replace old page with new page
        $new_core = str_replace('$default_index = \''.$default_index.'\';', '$default_index = \'' . $new_index . '\';', $old_core);
        $new_core = str_replace('$default_404 = \''.$default_404.'\';', '$default_404 = \'' . $new_404 . '\';', $new_core);
        $new_core = str_replace('$default_login = \''.$default_login.'\';', '$default_login = \'' . $new_login . '\';', $new_core);
        $new_core = str_replace('$notify_update_version = \''.$notify_update_version.'\';', '$notify_update_version = \'' . $new_notify_update_version . '\';', $new_core);
        //save new page
        file_put_contents($root . '/cms/core.php', $new_core);
        //notification
        echo '<div class="gmenu">Cài đặt thành công!</div>';
        header('Refresh: 3; url=/cms/setting.php');
    }
    if ($notify_update_version == 'display') {
        $checked_notify_version = ' checked';
    }
    echo '<div class="menu">
        <form method="post">
            <table width="100%">
                <tr>
                    <td class="left"><b>Trang lỗi:</b></td>
                    <td style="text-align:left"><input type="text" name="new_404" value="' . $default_404 . '" placeholder="_404" /></td>
                </tr>
                <tr>
                    <td class="left"><b>Trang chủ:</b></td>
                    <td style="text-align:left"><input type="text" name="new_index" value="' . $default_index . '" placeholder="index" /></td>
                </tr>
                <tr>
                    <td class="left"><b>Cookie login:</b></td>
                    <td style="text-align:left"><input type="text" name="new_login" value="' . $default_login . '" placeholder="dorew" /></td>
                </tr>
            </table>
            <table width="100%">
                <tr>
                    <td style="width:100%"><input class="w3-check" type="checkbox" name="new_version" value="display"' . $checked_notify_version . '> Cập nhật phiên bản mới</td>
                </tr>
            </table>
            <p><button type="submit">Thay đổi</button></p>
        </form>
    </div>
    <style>
        @media only screen and (min-width: 677px) {td.left{text-align:left;width:15%}}
        @media only screen and (max-width: 676px) {td.left{text-align:left;width:25%}}
        @media only screen and (max-width: 320px) {td.left{text-align:left;width:40%}}
    </style>
    ';
}
include $root . '/cms/layout/footer.php';

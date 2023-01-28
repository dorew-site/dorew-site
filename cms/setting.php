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
        $new_g_site_key = trim($_POST['g_site_key']) ?: '';
        $new_g_secret_key = trim($_POST['g_secret_key']) ?: '';
        $new_sjc_gold = trim($_POST['sjc_gold']) ?: '';
        //get old page
        $old_core = file_get_contents($root . '/cms/core.php');
        //replace old page with new page
        # admin account
        $new_core = str_replace('$default_index = \''.$default_index.'\';', '$default_index = \'' . $new_index . '\';', $old_core);
        $new_core = str_replace('$default_404 = \''.$default_404.'\';', '$default_404 = \'' . $new_404 . '\';', $new_core);
        $new_core = str_replace('$default_login = \''.$default_login.'\';', '$default_login = \'' . $new_login . '\';', $new_core);
        #tygia_sjc
        $new_core = str_replace('$sjc_gold = \''.$sjc_gold.'\';', '$sjc_gold = \'' . $new_sjc_gold . '\';', $new_core);
        #update api
        $new_core = str_replace('$g_site_key = \''.$g_site_key.'\';', '$g_site_key = \'' . $new_g_site_key . '\';', $new_core);
        $new_core = str_replace('$g_secret_key = \''.$g_secret_key.'\';', '$g_secret_key = \'' . $new_g_secret_key . '\';', $new_core);
        //save new page
        file_put_contents($root . '/cms/core.php', $new_core);
        //echo "<textarea rows='10'>$new_core</textarea>";
        //notification
        echo '<div class="gmenu">Cài đặt thành công!</div>';
        header('Refresh: 3; url=/cms/setting.php');
        //exit();
    }
    echo '<div class="menu">
        <form method="post">
            <table width="100%">
                <tr>
                    <td style="text-align:left">
                        <b>Google reCaptcha:</b><br/>
                        - Site Key: <input type="text" name="g_site_key" value="' . $g_site_key . '" placeholder="Nhập site_key của API" class="input-dr"/><br/>
                        - Secret Key: <input type="text" name="g_secret_key" value="' . $g_secret_key . '" placeholder="Nhập secret_key của API" class="input-dr"/>
                    </td>
                </tr>
                <tr>
                    <td style="text-align:left"><b>Trang lỗi:</b><br/><input type="text" name="new_404" value="' . $default_404 . '" placeholder="_404" class="input-dr"/></td>
                </tr>
                <tr>
                    <td style="text-align:left"><b>Trang chủ:</b><br/><input type="text" name="new_index" value="' . $default_index . '" placeholder="index" class="input-dr"/></td>
                </tr>
                <tr>
                    <td style="text-align:left"><b>Cookie login:</b><br/><input type="text" name="new_login" value="' . $default_login . '" placeholder="dorew" class="input-dr"/></td>
                </tr>
                <tr>
                    <td style="text-align:left"><b>Cấu hình Cloudflare (đối với tygia_sjc):</b><br/><input type="text" name="sjc_gold" value="' . $sjc_gold . '" placeholder="https://SITE_NAME.workers.dev/?url=https://sjc.com.vn/xml/tygiavang.xml" class="input-dr"/></td>
                </tr>
            </table>
            <p><button type="submit">Thay đổi</button></p>
        </form>
    </div>
    <style>
        @media only screen and (min-width: 481px) {.input-dr{width:400px;max-width:400px}}
        @media only screen and (max-width: 480px) {.input-dr{width:80%}}
        @media only screen and (min-width: 677px) {td.left{text-align:left;width:15%}}
        @media only screen and (max-width: 676px) {td.left{text-align:left;width:25%}}
        @media only screen and (max-width: 320px) {td.left{text-align:left;width:40%}}
    </style>
    ';
}
include $root . '/cms/layout/footer.php';

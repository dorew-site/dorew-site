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
$title = 'Template | Chỉnh sửa dữ liệu';
include $root . '/cms/layout/header.php';
require_once $root . '/cms/layout/func.php';

if (is_login()) {
    //get data
    $act = $_GET['act'];
    $filename = $_GET['file'];
    $url_file = $dir_tpl . '/' . $filename;
    $file = $filename ? $filename : 'ERROR';
    $checkExt = strtolower(array_pop(explode('.', $file)));
    $type = '';
    if (in_array($checkExt, array('css', 'js'))) {
        $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $http_host = $protocol . $_SERVER['HTTP_HOST'];
        $clipboard = '<div class="list1"><i class="fa fa-clipboard" aria-hidden="true"></i> <input type="text" value="' . $http_host . '/' . $file . '"></div>';
        switch ($checkExt) {
            case 'css':
                $type = ' / <a href="/cms?type=css">CSS</a>';
                break;
            case 'js':
                $type = ' / <a href="/cms?type=js">Javascript</a>';
                break;
        }
    }
    echo '<div class="phdr"><a href="/cms"><i class="fa fa-home" aria-hidden="true"></i></a>' . $type . ' / <b>' . $file . '</b></div>';
    //check file
    if (!file_exists($url_file) || !$filename) {
        echo '<div class="rmenu">Tập tin <b>' . $filename . '</b> không tồn tại</div>';
    } else {
        if ($act == 'rename') {
            //rename current file
            $new_file_name = rwurl(htmlspecialchars($_POST['rename']));
            if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
                $new_url_file = $dir_tpl . '/' . $new_file_name;
                rename($url_file, $new_url_file);
                header('Location: /cms/manager/edit.php?file=' . $new_file_name);
            }
            echo '
            <div class="menu" style="text-align:center">
                <form method="post" action="">
                    <p><b>Nhập tên mới cho tệp:</b></p>
                    <p><input type="text" name="rename" value="' . $filename . '" /></p>
                    <p><button type="submit" class="submit">Đổi tên</button></p>
                </form>
            </div>
            ';
        } elseif ($act == 'delete') {
            //remove current file
            if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
                unlink($url_file);
                header('Location: /cms/manager');
                exit();
            }
            echo '
            <div class="menu" style="text-align:center">
                <form method="post" action="">
                    <p><b style="color:red">Bạn có thực sự muốn xoá tập tin này không?</b></p>
                    <p><button type="submit" class="button">Xoá luôn ngại gì</button></p>
                </form>
            </div>
            ';
        } else {
            $data = file_get_contents($url_file);
            chmod($url_file, 0777);
            //get data from file
            $old_code = file_get_contents($url_file);
            //query
            $new_code = $_POST['contents'];
            if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
                file_put_contents($url_file, $new_code);
                header('Location: ' . $request_uri);
                exit();
            }
            //form edit
            echo '
            <div class="menu">
                <form action="" method="post">
                    <textarea id="code" name="contents" style="width: 100%; min-height: 200px" rows="35">' . htmlspecialchars($old_code) . '</textarea>
                        <p style="text-align:center">
                            <button type="submit" name="submit" class="button">Cập nhật</button>
                        </p>
                </form>
            </div>
            <div class="phdr"><b><i class="fa fa-cogs" aria-hidden="true"></i> Công cụ</b></div>
            ' . $clipboard . '
            <a href="?' . $_SERVER['QUERY_STRING'] . '&act=rename"><div class="list1"><i class="fa fa-pencil" aria-hidden="true"></i> Đổi tên tập tin</div></a>
            <a href="?' . $_SERVER['QUERY_STRING'] . '&act=delete"><div class="list1"><i class="fa fa-trash" aria-hidden="true"></i> Xóa tập tin</div></a>
            ';
        }
    }
} else {
    header('Location: /cms');
    exit();
}
include $root . '/cms/layout/footer.php';

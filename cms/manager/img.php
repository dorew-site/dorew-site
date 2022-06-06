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
$title = 'Thư viện hình ảnh';
include $root . '/cms/layout/header.php';
require_once $root . '/cms/layout/func.php';

if (is_login()) {
    $ext_allow = $image_ext;
    //show image
    $get_file = $_GET['file'];
    $url_file = $dir_tpl . '/' . $get_file;
    //kiểm tra sự tồn tại của file
    if (file_exists($url_file) && is_file($url_file)) {
        echo '<div class="phdr"><a href="/"><i class="fa fa-home" aria-hidden="true"></i></a> » <a href="./img.php">Hình ảnh</a> » <a href="?file=' . $get_file . '"><b>Thông tin</b></a></div>';
        $ext = pathinfo($url_file, PATHINFO_EXTENSION);
        if (in_array($ext, $ext_allow)) {
            $size = filesize($url_file);
            $time = date('U', filemtime($url_file));
            $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
            $url_file = $protocol . $http_host . '/' . $get_file;
            echo '
            <style>.imgShow{max-height: 500px;height: auto;width: auto;}</style>
            <center>
                <div class="menu">
                    <img class="imgShow" style="padding:2px;border:1px solid #CECECE;" src="' . $url_file . '" alt="hình ảnh"><br />
                    <div style="background:#2D3BFD;border:2px solid #2D3BFD;padding:4px;width:45%;text-align:center;border-radius:2px;">
                        <a href="' . $url_file . '"><b><font color=#ffffff><i class="fa fa-cloud-download" aria-hidden="true"></i> Download ảnh (' . file_size($size) . ')</font></b></a>
                    </div>
                </div>
            </center>
            <div class="menu">
                <i class="fa fa-calendar" aria-hidden="true"></i> Lúc: <b>' . time_ago($time) . '</b><br />
                <i class="fa fa-info-circle" aria-hidden="true"></i> Kích thước: <b>' . file_size($size) . '</b></div>
            </div>
            <div class="phdr"><i class="fa fa-share-alt-square" aria-hidden="true"></i> Chia sẻ ảnh</div>
            <div class="menu">
                <table style="border: none;">
                    <tr>
                        <td>URi</td>
                        <td><input type="text" value="/' . $get_file . '"></td>
                    </tr>
                    <tr>
                        <td>Link</td>
                        <td><input type="text" value="' . $url_file . '"></td>
                    </tr>
                    <tr>
                        <td>BBCode</td>
                        <td><input type="text" value="[img]' . $url_file . '[/img]"></td>
                    </tr>
                    <tr>
                        <td>Markdown</td>
                        <td><input type="text" value="![](' . $url_file . ')"></td>
                    </tr>
                </table>
            </div>
            ';
        } else {
            echo '<div class="rmenu">Không tìm thấy tập tin này!</div>';
        }
    } else {
        include $root . '/cms/layout/act/manager.php';
        //upload images to `images`
        echo '<div class="phdr"><i class="fa fa-upload" aria-hidden="true"></i> Tải lên hình ảnh</div>';
        $file = $_FILES['file'];
        if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
            if (!empty($file['name'])) {
                $file_name = $file['name'];
                $file_tmp = $file['tmp_name'];
                $maxSizeAllow = '5242880'; //5MB
                $file_size = $file['size'];
                $file_error = $file['error'];
                $file_ext = strtolower(end(explode('.', $file_name)));
                if (in_array($file_ext, $ext_allow) && $file_error == 0 && $file_size <= $maxSizeAllow) {
                    $file_name_new = rwurl($file_name);
                    $file_destination = $dir_tpl . '/' . $file_name_new;
                    if (move_uploaded_file($file_tmp, $file_destination)) {
                        echo '<div class="gmenu">Tải lên thành công - <b>' . file_size($file_size) . '</b></div>';
                        header('Refresh: 3; url=/cms/manager/img.php');
                    } else {
                        echo '<div class="rmenu">Lỗi tải lên</div>';
                    }
                } else {
                    echo '<div class="rmenu">Tập tin không hợp lệ</div>';
                }
            }
        }
        //form upload
        echo '
        <div class="menu" style="text-align:center">
            <form action="" method="post" enctype="multipart/form-data">
                <p><input type="file" name="file" /></p>
                <p><button class="button" type="submit">Tải lên</button></p>
            </form>
        </div>
        <div class="phdr"><i class="fa fa-list" aria-hidden="true"></i> Danh sách</div>
        ';
        //start list images in `images`
        $results_array = array();
        if (is_dir($dir_tpl)) {
            if ($handle = opendir($dir_tpl)) {
                chdir($dir_tpl);
                array_multisort(array_map('filemtime', ($files = glob("*"))), SORT_DESC, $files);
                foreach ($files as $value) {
                    $checkExt = strtolower(array_pop(explode('.', $value)));
                    if (in_array($checkExt, $ext_allow)) {
                        $results_array[] = $value;
                    }
                }
                closedir($handle);
            }
        }
        chdir($root);
        $list_file = array();
        foreach ($results_array as $value) {
            if (is_file($dir_tpl . '/' . $value)) {
                $list_file[] = $value;
            }
        }
        $total_file = count($list_file);
        if ($total_file <= 0) {
            echo '<div class="list1">Không có tập tin nào!</div>';
        }
        $per = 20;
        $namepage = 'page';
        $page_max = ceil($total_file / $per);
        $page = isset($_GET[$namepage]) ? (int) $_GET[$namepage] : 1;
        $start = ($page - 1) * $per;
        $end = $start + $per;
        if ($end >= $total_file) {
            $end = $total_file;
        }
        for ($i = $start; $i < $end; $i++) {
            $file_name = $list_file[$i];
            $file_size = filesize($dir_tpl . '/' . $file_name);
            $file_size = round($file_size / 1024, 2);
            $file_size = $file_size . ' KB';
            echo '
            <a href="?file=' . $file_name . '">
                <div class="list1">
                    <table width="100%">
                        <tr>
                            <td style="width:60%;text-align:left">
                                <b><i class="fa fa-file-image-o" aria-hidden="true"></i> ' . $file_name . '</b>
                            </td>
                            <td style="width:40%;text-align:right">
                                <b>' . $file_size . '</b>
                            </td>
                        </tr>
                    </table>
                </div>
            </a>
            ';
        }
        if ($total_file > $per) {
            echo '<center><div class="topmenu"><div class="pagination">' . paging('?' . $namepage . '=', $page, $page_max) . '</div></div></center>';
        }
    }
} else {
    header('Location: /cms');
}
include $root . '/cms/layout/footer.php';

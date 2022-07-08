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
$title = 'Sao lưu Template';
include $root . '/cms/layout/header.php';
require_once $root . '/cms/layout/func.php';

if (is_login()) {
    $act = $_GET['act'];
    if ($act == 'upload') {
        //upload the template zip to the `backup` folder
        $file = $_FILES['file'];
        echo '
        <div class="phdr"><a href="/cms" title="Quản lý tập tin"><i class="fa fa-tachometer" aria-hidden="true"></i> Quản lý tập tin</a> | <b>Tải lên template</b></div>
        ';
        if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
            if (!empty($file['name'])) {
                $file_name = $file['name'];
                $file_tmp = $file['tmp_name'];
                $maxSizeAllow = '10485760'; //10MB
                $file_size = $file['size'];
                $file_error = $file['error'];
                $ext_allow = array('zip');
                $file_ext = strtolower(end(explode('.', $file_name)));
                if (in_array($file_ext, $ext_allow) && $file_error == 0 && $file_size <= $maxSizeAllow) {
                    $file_name_new = rwurl($file_name);
                    $file_destination = $dir_backup . '/' . $file_name_new;
                    if (move_uploaded_file($file_tmp, $file_destination)) {
                        $zip = new ZipArchive;
                        //open zip
                        $zip->open($file_destination);
                        echo '<div class="gmenu">Tải lên thành công - <b>' . file_size($file_size) . '</b></div>';
                        header('Refresh: 3; url=/cms/manager/backup.php');
                    } else {
                        echo '<div class="rmenu">Lỗi tải lên</div>';
                    }
                } else {
                    echo '<div class="rmenu">Tập tin không hợp lệ</div>';
                }
            } else {
                echo '<div class="rmenu">Chưa chọn tập tin</div>';
            }
        }
        //form upload
        echo '
        <div class="menu" style="text-align:center">
            <form action="backup.php?act=upload" method="post" enctype="multipart/form-data">
                <p><input type="file" name="file" /></p>
                <p><button class="button" type="submit">Tải lên</button></p>
            </form>
        </div>
        ';
    } else {
        $filename = $_GET['file'];
        //check the existence of the file $filename, if so let's practice
        if (!file_exists($dir_backup . '/' . $filename)) {
            if ($act == 'use') {
                //use template
                remove_dir($dir_tpl);
                mkdir($dir_tpl);
                $zip_name = $dir_backup . '/' . $filename;
                $zip = new ZipArchive();
                //open zip
                $zip->open($zip_name);
                //extract the zip to folder `template`
                $zip->extractTo($dir_tpl);
                //close zip
                $zip->close();
                header('location: /cms');
            } else {
                //remove template
                unlink($dir_backup . '/' . $filename);
                header('location: /cms/manager/backup.php');
            }
        } else {
            if (in_array($act, ['use', 'del'])) {
                echo '<div class="rmenu">Tập tin <b>' . $filename . '</b> không tồn tại</div>';
            }
        }
        if (!in_array($act, ['use', 'del'])) {
            $url_file = $dir_backup . '/' . $filename;
            $zip_name = rwurl(htmlspecialchars($_POST['backup'])) . '.zip';
            if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
                $zip = new ZipArchive();
                $zip->open($dir_backup . '/' . $zip_name, ZipArchive::CREATE);
                $files = glob($dir_tpl . '/*');
                foreach ($files as $file) {
                    $zip->addFile($file, basename($file));
                }
                $zip->close();
            }
            echo '
            <div class="phdr"><a href="/cms" title="Quản lý tập tin"><i class="fa fa-tachometer" aria-hidden="true"></i> Quản lý tập tin</a> | <b>Sao lưu tệp</b></div>
            <div class="gmenu" style="font-weight:700"><a href="?act=upload"><i class="fa fa-upload" aria-hidden="true"></i> Tải lên template</a></div>
            <div class="menu" style="text-align:center">
                <form method="post" action="">
                    <p><b>Tên bản sao lưu:</b></p>
                    <p><input type="text" name="backup" value="" /></p>
                    <p><button class="button" type="submit">Sao lưu</button></p>
                </form>
            </div>
            <div class="phdr">Danh sách</div>
            ';
            //browse files contained in folder `backup`
            $results_array = array();
            if (is_dir($dir_backup)) {
                if ($handle = opendir($dir_backup)) {
                    chdir($dir_backup);
                    array_multisort(array_map('filemtime', ($files = glob("*"))), SORT_DESC, $files);
                    foreach ($files as $value) {
                        $checkExt = array_pop(explode('.', $value));
                        if ($checkExt == 'zip') {
                            $results_array[] = $value;
                        }
                    }
                    closedir($handle);
                }
            }
            //chdir($root);
            $list_file = array();
            foreach ($results_array as $value) {
                if (is_file($dir_backup . '/' . $value)) {
                    $list_file[] = $value;
                }
            }
            $total_file = count($list_file);
            if ($total_file <= 0) {
                echo '<div class="list1">Thư mục sao lưu trống!</div>';
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
                $file_size = filesize($dir_backup . '/' . $file_name);
                $file_size = round($file_size / 1024, 2);
                $file_size = $file_size . ' KB';
                $file_time = date('U', filemtime($dir_backup . '/' . $file_name));
                echo '
                <div class="list1">
                    <table width="100%">
                        <tr>
                            <td style="width:60%;text-align:left">
                                <b><i class="fa fa-file-archive-o" aria-hidden="true"></i> ' . $file_name . '</b>
                                <br/><a href="?file=' . $file_name . '&act=use" title="Sử dụng Template này"><i class="fa fa-check-circle" aria-hidden="true"></i> Sử dụng</a> | 
                                <a href="?file=' . $file_name . '&act=del" title="Xóa Template này" style="color:red"><i class="fa fa-trash" aria-hidden="true"></i> Xóa</a> | 
                                <a href="/cms/backup/' . $file_name . '" title="Tải Template này" style="color:green"><i class="fa fa-download" aria-hidden="true"></i> Tải về</a>
                            </td>
                            <td style="width:40%;text-align:right">
                                <b>' . $file_size . '</b>
                                <br/>' . time_ago($file_time) . '
                            </td>
                        </tr>
                    </table>
                </div>
                ';
            }
            if ($total_file > $per) {
                echo '<center><div class="topmenu"><div class="pagination">' . paging('?' . $namepage . '=', $page, $page_max) . '</div></div></center>';
            }
        }
    }
} else {
    header('Location: /cms');
}
include $root . '/cms/layout/footer.php';

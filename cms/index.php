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
include $root . '/cms/layout/header.php';

if (!$conn) {
    if ($_GET['act'] == 'install') {
        echo '<div class="phdr"><b><i class="fa fa-cog" aria-hidden="true"></i> Cấu hình</b></div>
        <style>input{width:60%}</style>';
        if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
            $admin_user = strtolower($_POST['admin_user']) ?: $account_admin;
            $admin_pass = $_POST['admin_pass'] ?: $password_admin;
            $new_phpmyadmin = $_POST['phpmyadmin'] ?: $url_phpmyadmin;
            $new_db_host = $_POST['host'];
            $new_db_user = $_POST['user'];
            $new_db_pass = $_POST['pass'];
            $new_db_name = $_POST['name'];
            //get old database infomation
            $old_db_info = file_get_contents($root . '/cms/core.php');
            //replace old database infomation with new database infomation
            $new_db_info = str_replace('$db_host = \'' . $db_host . '\';', '$db_host = \'' . $new_db_host . '\';', $old_db_info);
            $new_db_info = str_replace('$db_user = \'' . $db_user . '\';', '$db_user = \'' . $new_db_user . '\';', $new_db_info);
            $new_db_info = str_replace('$db_pass = \'' . $db_pass . '\';', '$db_pass = \'' . $new_db_pass . '\';', $new_db_info);
            $new_db_info = str_replace('$db_name = \'' . $db_name . '\';', '$db_name = \'' . $new_db_name . '\';', $new_db_info);
            $new_db_info = str_replace('$url_phpmyadmin = \'' . $url_phpmyadmin . '\';', '$url_phpmyadmin = \'' . $new_phpmyadmin . '\';', $new_db_info);
            $new_db_info = str_replace('$account_admin = \'' . $account_admin . '\';', '$account_admin = \'' . $admin_user . '\';', $new_db_info);
            $new_db_info = str_replace('$password_admin = \'' . $password_admin . '\';', '$password_admin = \'' . $admin_pass . '\';', $new_db_info);
            //check if database infomation is correct
            $conn_new = mysqli_connect($new_db_host, $new_db_user, $new_db_pass, $new_db_name);
            if (!$conn_new) {
                $notice = 'rmenu';
                $content = 'Thông tin cấu hình không chính xác. Không thể kết nối với cơ sở dữ liệu';
            } else {
                //save new config
                file_put_contents($root . '/cms/core.php', $new_db_info);
                //import sample data into database
                $table_name = 'ipfs';
                $sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
                    `id` int(11) UNSIGNED NULL AUTO_INCREMENT,
                    `time` int(11) NOT NULL,
                    `filename` varchar(255) NOT NULL,
                    `filesize` int(11) NOT NULL,
                    `CID` varchar(255) NOT NULL,
                    `password` varchar(255) NOT NULL,
                    `passphrase` varchar(255) NOT NULL,
                    `ip` varchar(255) NOT NULL,
                    `user_agent` varchar(255) NOT NULL,
                    PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
                $conn_new->query($sql);
                //notification
                $notice = 'gmenu';
                $content = 'Kết nối với cơ sở dữ liệu thành công!
                <br/>Tài khoản quản trị: <b>' . $admin_user . '</b>
                <br/>Mật khẩu: <b>' . $admin_pass . '</b>
                <br/>Bạn sẽ được chuyển đến trang quản trị sau 3s...';
                header('Refresh: 3; url=/cms');
            }
        }
        if (in_array($notice, ['rmenu', 'gmenu'])) {
            echo '<div class="' . $notice . '">' . $content . '</div>';
        }
        echo '
        <div style="text-align:center">
            <form method="post" action="">
                <div class="menu">
                    <center><span style="font-weight:700;font-size:16px">Cơ sở dữ liệu</b></center>
                    <input type="text" placeholder="PhpMyAdmin. Ví dụ: ' . $url_phpmyadmin . '" name="phpmyadmin"/>
                    <br/>
                    <input type="text" placeholder="Máy chủ MySQL" name="host"/>
                    <br/>
                    <input type="text" placeholder="Tên người dùng" name="user"/>
                    <br/>
                    <input type="text" placeholder="Tên cơ sở dữ liệu" name="name"/>
                    <br/>
                    <input type="text" placeholder="Mật khẩu" name="pass"/>
                </div>
                <div class="menu">
                    <center><span style="font-weight:700;font-size:16px">Quản trị</b></center>
                    <input type="text" placeholder="Tên đăng nhập" name="admin_user"/>
                    <br/>
                    <input type="password" placeholder="Mật khẩu quản trị" name="admin_pass"/>
                </div>
                <div class="menu">
                    <button type="submit" class="button">Cấu hình</button>
                </div>
            </form>
        </div>
        ';
    } else {
        //use's terms
        echo '
        <div class="phdr"><i class="fa fa-envelope-open-o" aria-hidden="true"></i> <b>Trình cài đặt DorewSite</b></div>
        <form method="get">
            <div class="menu">
                <textarea rows="15">' . file_get_contents('https://dorew-site.github.io/dorew-site/license.txt') . '</textarea>
            </div>
            <div class="menu" style="text-align:center">
                <span style="color:red;font-weight:700">Bạn đồng ý với điều khoản của Dorew chứ?</span>
                <p><button type="submit" class="button" name="act" value="install">Đồng ý và Cài đặt</button></p>
            </div>
        </form>
        ';
    }
} else {

    require_once $root . '/cms/layout/func.php';

    if (is_login()) {
        $type = strtolower($_GET['type']);
        $list_type = ['css', 'js'];
        $display_type = ['CSS', 'Javascript'];
        $icon_type = ['<i class="fa fa-file-text-o" aria-hidden="true"></i>', '<i class="fa fa-code" aria-hidden="true"></i>'];
        if ($_GET['act'] == 'logout') {
            setcookie($account_admin, '', 0);
            header('Location: /cms');
        }
        include $root . '/cms/layout/act/manager.php';
        if (!in_array($type, $list_type)) {
            echo '
            <div class="phdr"><i class="fa fa-list" aria-hidden="true"></i> <b>Danh sách</b> | <a href="/cms/use.php" title="Tài liệu tham khảo">Functions</a></div>
            <div class="topmenu" style="padding:8px"><b>Tài nguyên</b></div>
            <a href="?type=css"><div class="list1"><i class="fa fa-folder-open-o" aria-hidden="true"></i> CSS</div></a>
            <a href="?type=js"><div class="list1"><i class="fa fa-folder-open-o" aria-hidden="true"></i> Javascript</div></a>
            <a href="/cms/manager/img.php"><div class="list1"><i class="fa fa-folder-open-o" aria-hidden="true"></i> Hình ảnh</div></a>
            <div class="topmenu" style="padding:8px"><b>Tập tin code</b></div>
            ';
        } else {
            echo '
            <div class="phdr"><a href="/cms"><i class="fa fa-list" aria-hidden="true"></i> Danh sách</a> | <b>' . str_replace($list_type, $display_type, $type) . '</b></div>
            ';
        }
        //quick action
        if ($_GET['act'] == 'remove' && strtolower($_POST['option']) == 'remove' && !in_array($type, $list_type)) {
            if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
                $file = $_POST['twigfile'];
                for ($i = 0; $i < count($file); $i++) {
                    $url_file = $dir_tpl . '/' . $file[$i];
                    if (file_exists($url_file)) {
                        unlink($url_file);
                    }
                }
                header('Location: /cms');
                exit();
            }
        }
        //browse files contained in folder `template`
        $results_array = array();
        if (is_dir($dir_tpl)) {
            if ($handle = opendir($dir_tpl)) {
                chdir($dir_tpl);
                array_multisort(array_map('filemtime', ($files = glob("*"))), SORT_DESC, $files);
                foreach ($files as $value) {
                    $checkExt = strtolower(array_pop(explode('.', $value)));
                    $hidden_ext = array_merge(['zip', 'js', 'css'],$image_ext);
                    if (!in_array($checkExt, $hidden_ext) && !in_array($type, $hidden_ext) || $checkExt == 'css' && $type == 'css' || $checkExt == 'js' && $type == 'js') {
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
        sort($list_file);
        if (!in_array($type, $list_type)) {
            echo '<form method="post" action="?act=remove">';
        }
        for ($i = $start; $i < $end; $i++) {
            $file_name = $list_file[$i];
            $file_size = filesize($dir_tpl . '/' . $file_name);
            $file_size = round($file_size / 1024, 2);
            $file_size = $file_size . ' KB';
            if (!in_array($type, $list_type)) {
                $input_checkbox = '<input type="checkbox" name="twigfile[]" value="' . $file_name . '" />';
                $file_name = '<a href="/cms/manager/edit.php?file=' . $file_name . '">' . $file_name . '</a>';
            }
            $layout_list = '
            <div class="list1">
                <table width="100%">
                    <tr>
                        <td style="width:60%;text-align:left">
                            <b>' . $input_checkbox . str_replace($list_type, $icon_type, $type) . ' ' . $file_name . '</b>
                        </td>
                        <td style="width:40%;text-align:right">
                            <b>' . $file_size . '</b>
                        </td>
                    </tr>
                </table>
            </div>
            ';
            if (in_array($type, $list_type)) {
                echo '
                <a href="/cms/manager/edit.php?file=' . $file_name . '">
                    ' . $layout_list . '
                </a>
                ';
            } else {
                echo $layout_list;
            }
        }
        if ($total_file > $per) {
            if (in_array($type, $list_type)) {
                $get_type = 'type=' . $type . '&';
            }
            echo '<center><div class="topmenu"><div class="pagination">' . paging('?' . $get_type . $namepage . '=', $page, $page_max) . '</div></div></center>';
        }
        //menu quick action
        if (!in_array($type, $list_type)) {
            echo '<div class="phdr">Thao tác nhanh</div>
        <div class="menu">
            <select name="option"><option value="remove"> Xoá tệp</option></select>
            <button type="submit">Thực hiện</button>
        </div>';
            echo '</form>';
        }
    } else {
        echo '<div class="phdr">Đăng Nhập</div>';
        $user = strtolower($_POST['user']);
        $pass = $_POST['pass'];
        if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
            if ($user == $account_admin && $pass == $password_admin) {
                $div = 'gmenu';
                $result = 'Đăng nhập thành công';
                setcookie($account_admin, $new_password, time() + 31536000);
                header('Location: /cms');
            } else {
                $div = 'rmenu';
                $result = 'Thông tin đăng nhập sai cmnr!';
            }
        }
        if (isset($result)) {
            echo '<div class="' . $div . '">' . $result . '</div>';
        }
        echo '
        <div class="menu">
            <form method="post" action="">
                <p>
                    <i class="fa fa-user" aria-hidden="true"></i> Tên tài khoản:<br />
                    <input type="text" class="w3-input" name="user">
                </p>
                <p>
                    <i class="fa fa-lock" aria-hidden="true"></i> Mật khẩu:<br />
                    <input type="password" class="w3-input" name="pass">
                </p>
                <p align="center"><button style="border: 2px solid green;" class="button" type="submit">Đăng Nhập</button></p>
            </form>
        </div>
        ';
    }
}
include $root . '/cms/layout/footer.php';

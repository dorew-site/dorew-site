<?php
/**
 * DorewSite Software
 * Author: Dorew
 * Email: khanh65me1@gmail.com or awginao@protonmail.com
 * Website: https://dorew.gq
 * License: https://dorew.gq/license or read more license.txt
 * Copyright: (C) 2022 Dorew All Rights Reserved.
 * This file is part of the source code.
 */

define('_DOREW', 1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/cms/config.php';
$title = 'Functions';
include $root . '/cms/layout/header.php';
?>
<style>ul, ul li{padding-left:20px}ul::before{content:'- '}ul li::before{content:'+) '}</style>
<div class="phdr"><i class="fa fa-book" aria-hidden="true"></i> Tài liệu</div>
<div class="menu"><b>Sử dụng TWIG:</b> <a href="https://twig.symfony.com/doc/3.x/">https://twig.symfony.com</a></div>
<div class="phdr"><b>Một số function và filter bổ sung</b></div>
<div class="menu">
    <p><b style="color:red">Filter:</b></p>
    <p>
        <ul>json_decode</ul>
        <ul>json_encode</ul>
        <ul>md5</ul>
    </p>
    <p><b style="color:red">Function:</b></p>
    <ul>
        <b>Phương thức xử lý form</b>
        <li>request_method()</li>
        <li>GET: get_get(string)</li>
        <li>POST: get_post(string)</li>
    </ul>
    <ul>
        <b>Phân đoạn URI của URL</b>
        <li>get_uri_segments()</li>
    </ul>
    <ul>
        <b>Lấy IP của người dùng</b>
        <li>ip()</li>
    </ul>
    <ul>
        <b>Lấy UA của người dùng</b>
        <li>user_agent()</li>
    </ul>
    <ul>
        <b>Làm việc với Cookie</b>
        <li>get_cookie(name)</li>
        <li>set_cookie(name, value)</li>
        <li>delete_cookie(name)</li>
    </ul>
    <ul>
        <b>ReWrite URL</b>
        <li>rwurl(uri)</li>
    </ul>
    <ul>
        <b>Chuyển hướng trang</b>
        <li>redirect(url)</li>
    </ul>
    <ul>
        <b>Trang hiện tại</b>
        <li>current_url()</li>
    </ul>
    <ul>
        <b>Debug</b>
        <li>debug(string)</li>
    </ul>
    <ul>
        <b>Giải mã HTML</b>
        <li>html_decode(string)</li>
    </ul>
    <ul>
        <b>Kiểm tra sự tồn tại của người dùng</b>
        <li>is_login()</li>
        <li>Tên cookie: <b>dorew</b></li>
    </ul>
    <ul>
        <b>Làm việc với Cơ sở dữ liệu</b>
        <li>create_table(table_name)</li>
        <li>create_table_with_column(table_name, array_column)</li>
        <li>drop_table(table_name)</li>
        <li>get_table_count(table_name)</li>
        <li>create_column_table(table_name, column_name, column_type)</li>
        <li>drop_column_table(table_name, column_name)</li>
        <li>insert_row_table(table_name, column_name, column_value)</li>
        <li>insert_row_array_table(table_name, array_row)</li>
        <li>update_row_table(table_name, column_name, column_value, where_column_name, where_column_value)</li>
        <li>update_row_array_table(table_name, array_row, where_column_name, where_column_value)</li>
        <li>delete_row_table(table_name, where_column_name, where_column_value)</li>
        <li>select_table_data(table_name, order, sort)</li>
        <li>select_table_where_data(table_name, where_column_name, where_column_value, order, sort)</li>
        <li>select_table_row_data(table_name, where_column_name, where_column_value)</li>
    </ul>
</div>
<?php
include $root . '/cms/layout/footer.php';

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

require_once $_SERVER['DOCUMENT_ROOT'] . '/cms/config.php';
?>
<div class="phdr">
    <b><i class="fa fa-tachometer" aria-hidden="true"></i> Quản lý tập tin</b> 
    (<a href="/cms/layout" style="color:orange;font-weight:700"><?php echo $current_type_db ?></a>)
</div>
<div class="menu" style="text-align:center">
    <a href="/cms/manager/add.php">Thêm mới</a> | 
    <a href="/cms/manager/delete.php">Xoá toàn bộ</a> | 
    <a href="/cms/manager/backup.php">Sao lưu</a> |
    <a href="/cms/setting.php">Cài đặt</a>
</div>